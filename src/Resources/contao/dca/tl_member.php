<?php

/**
 * @copyright   Christian Barkowsky 2018 <https://brkwsky.de>
 * @author      Christian Barkowsky <https://brkwsky.de>
 * @package     PrivacyConsentBundle
 * @license     LGPL-3.0+
 * @see         https://github.com/christianbarkowsky/contao-privacyconsent-bundle
 */

/**
 * Config
 */
$GLOBALS['TL_DCA']['tl_member']['config']['onload_callback'][] = array('brkwsky_privacy.listener.member_operation', 'onLoad');

/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_member']['fields']['privacyConsent'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_member']['privacyConsent'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('mandatory'=>true, 'feEditable'=>false, 'feViewable'=>true, 'feGroup'=>'contact'),
    'sql'                     => "char(1) NOT NULL default ''"
);
