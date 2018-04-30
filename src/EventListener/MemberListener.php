<?php

/**
 * @copyright   Christian Barkowsky 2018 <https://brkwsky.de>
 * @author      Christian Barkowsky <https://brkwsky.de>
 * @package     PrivacyConsentBundle
 * @license     LGPL-3.0+
 * @see         https://github.com/christianbarkowsky/contao-privacyconsent-bundle
 */

namespace Brkwsky\PrivacyConsentBundle\EventListener;

/**
 * Class MemberListener
 * @package Brkwsky\PrivacyConsentBundle\EventListener
 */
class MemberListener
{

    /**
     * MemberListener constructor.
     */
    public function __construct()
    {
    }

    /**
     * On load callback
     */
    public function onLoad()
    {
        if (TL_MODE == 'FE') {
            $GLOBALS['TL_LANG']['tl_member']['privacyConsent'] = $GLOBALS['TL_LANG']['privacyConsent'];
        }
    }
}
