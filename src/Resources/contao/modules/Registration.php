<?php

/**
 * Registration for ContaoPrivacy
 *
 * @copyright   Friends of Contao 2018
 * @author      Christian Barkowsky <https://brkwsky.de>
 * @package     ContaoPrivacy
 * @license     LGPL-3.0+
 * @see         https://github.com/friends-of-contao/contao-privacy
 */

namespace Foc\ContaoPrivacyBundle\Modules;

use Contao\Date;
use Contao\FormCheckBox;
use Contao\Input;
use Contao\System;
use Contao\Database;
use Contao\Encryption;
use Contao\StringUtil;
use Contao\Environment;
use Contao\BackendTemplate;
use Contao\FrontendTemplate;
use Contao\ModuleRegistration;

/**
 * Class Registration
 * @package Foc\ContaoPrivacyBundle\Modules
 */
class Registration extends ModuleRegistration
{
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'member_default';

    /**
     * Display a wildcard in the back end
     *
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE') {
            /** @var BackendTemplate|object $objTemplate */
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['focRegistration'][0]) . ' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        $this->editable = deserialize($this->editable);

        // Return if there are no editable fields
        if (empty($this->editable) || !\is_array($this->editable)) {
            return '';
        }

        return parent::generate();
    }

    /**
     * Generate the module
     */
    protected function compile()
    {
        /** @var PageModel $objPage */
        global $objPage;

        $GLOBALS['TL_LANGUAGE'] = $objPage->language;

        System::loadLanguageFile('tl_member');
        $this->loadDataContainer('tl_member');

        // Call onload_callback (e.g. to check permissions)
        if (\is_array($GLOBALS['TL_DCA']['tl_member']['config']['onload_callback'])) {
            foreach ($GLOBALS['TL_DCA']['tl_member']['config']['onload_callback'] as $callback) {
                if (\is_array($callback)) {
                    $this->import($callback[0]);
                    $this->{$callback[0]}->{$callback[1]}();
                } elseif (\is_callable($callback)) {
                    $callback();
                }
            }
        }

        // Activate account
        if (strncmp(Input::get('token'), 'RG', 2) === 0) {
            $this->activateAcount();

            return;
        }

        if ($this->memberTpl != '') {
            /** @var FrontendTemplate|object $objTemplate */
            $objTemplate = new FrontendTemplate($this->memberTpl);

            $this->Template = $objTemplate;
            $this->Template->setData($this->arrData);
        }

        $this->Template->fields = '';

        $objCaptcha = null;
        $doNotSubmit = false;
        $strFormId = 'tl_registration_' . $this->id;

        // Predefine the group order (other groups will be appended automatically)
        $arrGroups = array
        (
            'personal' => array(),
            'address'  => array(),
            'contact'  => array(),
            'login'    => array(),
            'profile'  => array()
        );

        // Captcha
        if (!$this->disableCaptcha) {
            $arrCaptcha = array
            (
                'id' => 'registration',
                'label' => $GLOBALS['TL_LANG']['MSC']['securityQuestion'],
                'type' => 'captcha',
                'mandatory' => true,
                'required' => true
            );

            /** @var FormCaptcha $strClass */
            $strClass = $GLOBALS['TL_FFL']['captcha'];

            // Fallback to default if the class is not defined
            if (!class_exists($strClass)) {
                $strClass = 'FormCaptcha';
            }

            /** @var FormCaptcha $objCaptcha */
            $objCaptcha = new $strClass($arrCaptcha);

            if (Input::post('FORM_SUBMIT') == $strFormId) {
                $objCaptcha->validate();

                if ($objCaptcha->hasErrors()) {
                    $doNotSubmit = true;
                }
            }
        }

        $objMember = null;

        // Check for a follow-up registration (see #7992)
        if (Input::post('email', true) != '' && ($objMember = \MemberModel::findUnactivatedByEmail(Input::post('email', true))) !== null) {
            $this->resendActivationMail($objMember);

            return;
        }

        $arrUser = array();
        $arrFields = array();
        $hasUpload = false;
        $i = 0;

        // Build form
        foreach ($this->editable as $field) {
            $arrData = $GLOBALS['TL_DCA']['tl_member']['fields'][$field];

            // Map checkboxWizards to regular checkbox widgets
            if ($arrData['inputType'] == 'checkboxWizard') {
                $arrData['inputType'] = 'checkbox';
            }

            // Map fileTrees to upload widgets (see #8091)
            if ($arrData['inputType'] == 'fileTree') {
                $arrData['inputType'] = 'upload';
            }

            /** @var Widget $strClass */
            $strClass = $GLOBALS['TL_FFL'][$arrData['inputType']];

            // Continue if the class is not defined
            if (!class_exists($strClass)) {
                continue;
            }

            $arrData['eval']['required'] = $arrData['eval']['mandatory'];

            // Unset the unique field check upon follow-up registrations
            if ($objMember !== null && $arrData['eval']['unique'] && Input::post($field) == $objMember->$field) {
                $arrData['eval']['unique'] = false;
            }

            $objWidget = new $strClass($strClass::getAttributesFromDca($arrData, $field, $arrData['default'], '', '', $this));

            $objWidget->storeValues = true;
            $objWidget->rowClass = 'row_' . $i . (($i == 0) ? ' row_first' : '') . ((($i % 2) == 0) ? ' even' : ' odd');

            // DSGVO checkbox
            if ($objWidget instanceof FormCheckBox && 'privacyConsent' == $objWidget->name) {
                $objWidget->template = 'form_checkbox_foc';
                $objWidget->label = $this->focPrivacy;

                $arrOption[] = [
                    'type'      => 'option',
                    'label'     => $this->focPrivacyLabel,
                    'value'     => ''
                ];

                $objWidget->options  = $arrOption;
            }

            // Increase the row count if its a password field
            if ($objWidget instanceof FormPassword) {
                $objWidget->rowClassConfirm = 'row_' . ++$i . ((($i % 2) == 0) ? ' even' : ' odd');
            }

            // Validate input
            if (Input::post('FORM_SUBMIT') == $strFormId) {
                $objWidget->validate();
                $varValue = $objWidget->value;

                // Check whether the password matches the username
                if ($objWidget instanceof FormPassword && password_verify(Input::post('username'), $varValue)) {
                    $objWidget->addError($GLOBALS['TL_LANG']['ERR']['passwordName']);
                }

                $rgxp = $arrData['eval']['rgxp'];

                // Convert date formats into timestamps (check the eval setting first -> #3063)
                if ($varValue != '' && \in_array($rgxp, array('date', 'time', 'datim'))) {
                    try {
                        $objDate = new Date($varValue, Date::getFormatFromRgxp($rgxp));
                        $varValue = $objDate->tstamp;
                    } catch (\OutOfBoundsException $e) {
                        $objWidget->addError(sprintf($GLOBALS['TL_LANG']['ERR']['invalidDate'], $varValue));
                    }
                }

                // Make sure that unique fields are unique (check the eval setting first -> #3063)
                if ($arrData['eval']['unique'] && $varValue != '' && !Database::getInstance()->isUniqueValue('tl_member', $field, $varValue)) {
                    $objWidget->addError(sprintf($GLOBALS['TL_LANG']['ERR']['unique'], $arrData['label'][0] ?: $field));
                }

                // Save callback
                if ($objWidget->submitInput() && !$objWidget->hasErrors() && \is_array($arrData['save_callback'])) {
                    foreach ($arrData['save_callback'] as $callback) {
                        try {
                            if (\is_array($callback)) {
                                $this->import($callback[0]);
                                $varValue = $this->{$callback[0]}->{$callback[1]}($varValue, null);
                            } elseif (\is_callable($callback)) {
                                $varValue = $callback($varValue, null);
                            }
                        } catch (\Exception $e) {
                            $objWidget->class = 'error';
                            $objWidget->addError($e->getMessage());
                        }
                    }
                }

                // Store the current value
                if ($objWidget->hasErrors()) {
                    $doNotSubmit = true;
                } elseif ($objWidget->submitInput()) {
                    // Set the correct empty value (see #6284, #6373)
                    if ($varValue === '') {
                        $varValue = $objWidget->getEmptyValue();
                    }

                    // Encrypt the value (see #7815)
                    if ($arrData['eval']['encrypt']) {
                        $varValue = Encryption::encrypt($varValue);
                    }

                    // Set the new value
                    $arrUser[$field] = $varValue;
                }
            }

            if ($objWidget instanceof \uploadable) {
                $hasUpload = true;
            }

            $temp = $objWidget->parse();

            $this->Template->fields .= $temp;
            $arrFields[$arrData['eval']['feGroup']][$field] .= $temp;

            ++$i;
        }

        // Captcha
        if (!$this->disableCaptcha) {
            $objCaptcha->rowClass = 'row_'.$i . (($i == 0) ? ' row_first' : '') . ((($i % 2) == 0) ? ' even' : ' odd');
            $strCaptcha = $objCaptcha->parse();

            $this->Template->fields .= $strCaptcha;
            $arrFields['captcha']['captcha'] .= $strCaptcha;
        }

        $this->Template->rowLast = 'row_' . ++$i . ((($i % 2) == 0) ? ' even' : ' odd');
        $this->Template->enctype = $hasUpload ? 'multipart/form-data' : 'application/x-www-form-urlencoded';
        $this->Template->hasError = $doNotSubmit;

        // Create new user if there are no errors
        if (Input::post('FORM_SUBMIT') == $strFormId && !$doNotSubmit) {
            $this->createNewUser($arrUser);
        }

        $this->Template->loginDetails = $GLOBALS['TL_LANG']['tl_member']['loginDetails'];
        $this->Template->addressDetails = $GLOBALS['TL_LANG']['tl_member']['addressDetails'];
        $this->Template->contactDetails = $GLOBALS['TL_LANG']['tl_member']['contactDetails'];
        $this->Template->personalData = $GLOBALS['TL_LANG']['tl_member']['personalData'];
        $this->Template->captchaDetails = $GLOBALS['TL_LANG']['MSC']['securityQuestion'];

        // Add the groups
        foreach ($arrFields as $k => $v) {
            // Deprecated since Contao 4.0, to be removed in Contao 5.0
            $this->Template->$k = $v;

            $key = $k . (($k == 'personal') ? 'Data' : 'Details');
            $arrGroups[$GLOBALS['TL_LANG']['tl_member'][$key]] = $v;
        }

        $this->Template->categories = $arrGroups;
        $this->Template->formId = $strFormId;
        $this->Template->slabel = StringUtil::specialchars($GLOBALS['TL_LANG']['MSC']['register']);
        $this->Template->action = Environment::get('indexFreeRequest');

        // Deprecated since Contao 4.0, to be removed in Contao 5.0
        $this->Template->captcha = $arrFields['captcha']['captcha'];
    }
}
