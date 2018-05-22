<?php

/**
 * @copyright   Friends of Contao 2018
 * @author      Fritz Michael Gschwantner <https://github.com/fritzmg>
 * @package     PrivacyConsentBundle
 * @license     LGPL-3.0-or-later
 * @see         https://github.com/friends-of-contao/contao-privacy
 */

/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_content']['fields']['videoSplash'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['videoSplash'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('submitOnChange'=>true, 'tl_class'=>'clr w50', 'tl_style'=>'height:auto;'),
    'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['youtubeNoCookie'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['youtubeNoCookie'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>'w50', 'tl_style'=>'height:auto;'),
    'sql'                     => "char(1) NOT NULL default ''"
);

/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['youtube'] = str_replace
(
    'playerSize',
    'videoSplash,youtubeNoCookie,playerSize',
    $GLOBALS['TL_DCA']['tl_content']['palettes']['youtube']
);

if($GLOBALS['TL_DCA']['tl_content']['palettes']['vimeo']) {
    $GLOBALS['TL_DCA']['tl_content']['palettes']['vimeo'] = str_replace
    (
        'playerSize',
        'videoSplash,youtubeNoCookie,playerSize',
        $GLOBALS['TL_DCA']['tl_content']['palettes']['vimeo']
    );
}

$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'videoSplash';
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['videoSplash'] = 'singleSRC,size';
