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

use Contao\Input;
use Contao\Environment;
use Contao\BackendTemplate;
use Contao\FrontendTemplate;
use Contao\NewsletterChannelModel;

/**
 * Class ModuleSubscribe
 * @package Foc\ContaoPrivacyBundle\Modules
 */
class ModuleSubscribe extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'nl_default_foc';

	/**
	 * Display a wildcard in the back end
	 *
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE') {
			/** @var \BackendTemplate|object $objTemplate */
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['focSubscribe'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		$this->nl_channels = deserialize($this->nl_channels);

		// Return if there are no channels
		if (!is_array($this->nl_channels) || empty($this->nl_channels)) {
			return '';
		}

		return parent::generate();
	}

	/**
	 * Generate the module
	 */
	protected function compile()
	{
		// Overwrite default template
		if ($this->nl_template) {
			/** @var \FrontendTemplate|object $objTemplate */
			$objTemplate = new FrontendTemplate($this->nl_template);

			$this->Template = $objTemplate;
			$this->Template->setData($this->arrData);
		}

		// Activate e-mail address
		if (Input::get('token')) {
			$this->activateRecipient();

			return;
		}

		// Subscribe
		if (Input::post('FORM_SUBMIT') == 'tl_subscribe') {
			$this->addRecipient();
		}

		$blnHasError = false;

		// Error message
		if (strlen($_SESSION['SUBSCRIBE_ERROR'])) {
			$blnHasError  = true;
			$this->Template->mclass = 'error';
			$this->Template->message = $_SESSION['SUBSCRIBE_ERROR'];
			$_SESSION['SUBSCRIBE_ERROR'] = '';
		}

		// Confirmation message
		if (strlen($_SESSION['SUBSCRIBE_CONFIRM'])) {
			$this->Template->mclass = 'confirm';
			$this->Template->message = $_SESSION['SUBSCRIBE_CONFIRM'];
			$_SESSION['SUBSCRIBE_CONFIRM'] = '';
		}

		$arrChannels = array();
		$objChannel = NewsletterChannelModel::findByIds($this->nl_channels);

		// Get the titles
		if ($objChannel !== null) {
			while ($objChannel->next()) {
				$arrChannels[$objChannel->id] = $objChannel->title;
			}
		}

		// Default template variables
		$this->Template->email = '';
		$this->Template->channels = $arrChannels;
		$this->Template->showChannels = !$this->nl_hideChannels;
		$this->Template->submit = specialchars($GLOBALS['TL_LANG']['MSC']['subscribe']);
		$this->Template->channelsLabel = $GLOBALS['TL_LANG']['MSC']['nl_channels'];
		$this->Template->emailLabel = $GLOBALS['TL_LANG']['MSC']['emailAddress'];
		$this->Template->action = Environment::get('indexFreeRequest');
		$this->Template->formId = 'tl_subscribe';
		$this->Template->id = $this->id;
		$this->Template->hasError = $blnHasError;

        // DSGVO checkbox
        $this->Template->focPrivacy = $this->focPrivacy;
        $this->Template->focPrivacyLabel = $this->focPrivacyLabel;
        $this->Template->mandatoryField = $GLOBALS['TL_LANG']['MSC']['mandatory'];
	}
}
