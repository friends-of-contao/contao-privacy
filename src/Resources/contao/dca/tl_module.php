<?php

/**
 * @copyright   Christian Barkowsky 2018 <https://brkwsky.de>
 * @author      Fritz Michael Gschwantner <https://github.com/fritzmg>
 * @package     PrivacyConsentBundle
 * @license     LGPL-3.0-or-later
 * @see         https://github.com/christianbarkowsky/contao-privacyconsent-bundle
 */

/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['deleteCookies'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['deleteCookies'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('submitOnChange'=>true, 'tl_class'=>'clr w50'),
    'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['keepCookies'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['keepCookies'],
    'exclude'                 => true,
    'inputType'               => 'listWizard',
    'eval'                    => array('tl_class'=>'clr w50'),
    'sql'                     => "blob NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['focPrivacy'] = [
    'label'       => &$GLOBALS['TL_LANG']['tl_module']['focPrivacy'],
    'exclude'     => true,
    'inputType'   => 'textarea',
    'eval'        => ['mandatory'=>true, 'rte'=>'tinyMCE', 'helpwizard'=>true],
    'explanation' => 'insertTags',
    'sql'         => "text NULL",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['focPrivacyLabel'] = [
    'label'       => &$GLOBALS['TL_LANG']['tl_module']['focPrivacyLabel'],
    'exclude'     => true,
    'inputType'   => 'text',
    'eval'        => ['mandatory'=>true, 'decodeEntities'=>true, 'maxlength'=>255],
    'sql'         => "varchar(255) NOT NULL default ''",
];

/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'deleteCookies';
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['deleteCookies'] = 'keepCookies';
$GLOBALS['TL_DCA']['tl_module']['palettes']['analyticsOptOut'] = '{title_legend},name,headline,type;{config_legend},deleteCookies;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['focRegistration'] = '{title_legend},name,headline,type;{config_legend},editable,newsletters,disableCaptcha,focPrivacy,focPrivacyLabel;{account_legend},reg_groups,reg_allowLogin,reg_assignDir;{redirect_legend},jumpTo;{email_legend:hide},reg_activate;{template_legend:hide},memberTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';

if (\class_exists('\NotificationCenter\tl_form')) {
    $GLOBALS['TL_DCA']['tl_module']['palettes']['focRegistration'] = str_replace('reg_activate;', 'reg_activate,nc_notification,nc_activation_notification;', $GLOBALS['TL_DCA']['tl_module']['palettes']['focRegistration']);
}
