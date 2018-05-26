<?php

/**
 * @copyright   Friends of Contao 2018
 * @author      Mathias Arzberger <https://pdir.de>
 * @package     ContaoPrivacy
 * @license     LGPL-3.0+
 * @see         https://github.com/friends-of-contao/contao-privacy
 */

/**
 * palettes
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{foc_privacy_legend},foc_logfile_cleanup,foc_logfile_cleanup_interval';

/**
 * Add field
 */
$GLOBALS['TL_DCA']['tl_settings']['fields']['foc_logfile_cleanup'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['foc_logfile_cleanup'],
    'inputType'               => 'checkbox',
    'eval'                    => array(
        'submitOnChange'=>true,
        'tl_class'     => 'w50'
    ),
    'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['foc_logfile_cleanup_interval'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['foc_logfile_cleanup_interval'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array(
        'tl_class'     => 'w50',
        'mandatory'    => false
    ),
    'sql'                      => "int(10) NOT NULL default '9'"
);