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
    'eval'                    => array('tl_class'=>'w50'),
    'sql'                     => "blob NULL"
);

/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'deleteCookies';
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['deleteCookies'] = 'keepCookies';
$GLOBALS['TL_DCA']['tl_module']['palettes']['analyticsOptOut'] = '{title_legend},name,headline,type;{config_legend},deleteCookies;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
