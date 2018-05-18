<?php

/**
 * @copyright   Friends of Contao 2018
 * @author      David Maack <david.maack@arcor.de>
 * @package     ContaoPrivacy
 * @license     LGPL-3.0+
 * @see         https://github.com/friends-of-contao/contao-privacy
 */

/**
 * palettes
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default']                .= ';{foc_privacy_legend},foc_member_cleanup,foc_member_cleanup_interval';


/**
 * Add field
 */
$GLOBALS['TL_DCA']['tl_settings']['fields']['foc_member_cleanup_interval'] = array(
    'label'            => &$GLOBALS['TL_LANG']['tl_settings']['foc_member_cleanup_interval'],
    'exclude'          => true,
    'inputType'        => 'text',
    'eval'             => array(
        'tl_class'     => 'w50',
        'mandatory'    => false
    )
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['foc_member_cleanup'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['foc_member_cleanup'],
    'inputType'               => 'checkbox',
    'eval'                    => array('submitOnChange'=>true),
);