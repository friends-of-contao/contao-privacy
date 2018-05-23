<?php

/**
 * @copyright   Friends of Contao 2018
 * @author      Christian Barkowsky <https://brkwsky.de>
 * @package     ContaoPrivacy
 * @license     LGPL-3.0+
 * @see         https://github.com/friends-of-contao/contao-privacy
 */

/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_member']['fields']['privacyConsent'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_member']['privacyConsent'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('mandatory'=>true, 'feEditable'=>true, 'feViewable'=>true, 'feGroup'=>'contact'),
    'sql'                     => "char(1) NOT NULL default ''"
);
