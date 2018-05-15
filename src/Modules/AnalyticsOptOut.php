<?php

/**
 * @copyright   Friends of Contao 2018
 * @author      Fritz Michael Gschwantner <https://github.com/fritzmg>
 * @package     ContaoPrivacy
 * @license     LGPL-3.0-or-later
 * @see         https://github.com/christianbarkowsky/contao-privacyconsent-bundle
 */

namespace Foc\ContaoPrivacyBundle\Modules;

use Contao\BackendTemplate;
use Contao\Environment;
use Contao\Input;
use Contao\Module;
use Contao\StringUtil;
use Contao\System;
use Patchwork\Utf8;

/**
 * {@inheritdoc}
 */
class AnalyticsOptOut extends Module
{

    /**
     * Cookie name
     * @var string
     */
    const COOKIE_NAME = 'analyticsOptOut';

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_analyticsOptOut';


    /**
     * Display a login form
     *
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            /** @var BackendTemplate|object $objTemplate */
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ' . Utf8::strtoupper($GLOBALS['TL_LANG']['FMD']['analyticsOptOut'][0]) . ' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        // Opt-out/in
        if (Input::post('FORM_SUBMIT') == 'tl_analyticsOptOut_' . $this->id)
        {
            if (Input::cookie(self::COOKIE_NAME))
            {
                System::setCookie(self::COOKIE_NAME, '', 0);
                Input::setCookie(self::COOKIE_NAME, '');
            }
            else
            {
                // delete cookies
                if ($this->deleteCookies)
                {
                    $container = System::getContainer();
                    $session = $container->get('session');
                    $request = $container->get('request_stack')->getCurrentRequest();

                    // keep user defined cookies
                    $keep = StringUtil::deserialize($this->keepCookies, true);

                    // keep csrf token cookie and opt-out cookie
                    $csrf = $container->getParameter('contao.csrf_token_name');
                    $keep = \array_merge($keep, [self::COOKIE_NAME, 'csrf_http-'.$csrf, 'csrf_https-'.$csrf]);

                    // keep session cookie
                    if ($session->isStarted())
                    {
                        $keep[] = $session->getName();
                    }

                    $keep = \array_values(\array_unique(\array_filter($keep)));

                    foreach ($request->cookies as $name => $value)
                    {
                        if (!\in_array($name, $keep))
                        {
                            System::setCookie($name, '', 0);
                        }
                    }
                }

                System::setCookie(self::COOKIE_NAME, 1, time() + 365 * 24 * 60 * 60);
                Input::setCookie(self::COOKIE_NAME, 1);
            }
        }

        return parent::generate();
    }


    /**
     * Generate the module
     */
    protected function compile()
    {
        $this->Template->formId = 'tl_analyticsOptOut_' . $this->id;
        $this->Template->action = ampersand(Environment::get('indexFreeRequest'));

        if (Input::cookie(self::COOKIE_NAME))
        {
            $this->Template->slabel = StringUtil::specialchars($GLOBALS['TL_LANG']['MSC']['analyticsOptIn']);
            $this->Template->optin = true;
        }
        else
        {
            $this->Template->slabel = StringUtil::specialchars($GLOBALS['TL_LANG']['MSC']['analyticsOptOut']);
            $this->Template->optin = false;
        }
    }
}
