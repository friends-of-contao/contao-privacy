<?php

/**
 * @copyright   Friends of Contao 2018
 * @author      Christian Barkowsky <https://brkwsky.de>
 * @author      Mathias Arzberger <https://pdir.de>
 * @author      Fritz Michael Gschwantner <https://github.com/fritzmg>
 * @package     ContaoPrivacy
 * @license     LGPL-3.0+
 * @see         https://github.com/friends-of-contao/contao-privacy
 */

$assetsDir = 'bundles/foccontaoprivacy';

/**
 * Backend modules
 */

array_insert($GLOBALS['BE_MOD']['foc'], 0, array
(
    'focPrivacyOverview' => array
    (
        'callback' => 'Foc\ContaoPrivacyBundle\Modules\PrivacyOverview',
        'icon' => $assetsDir . '/img/icon.png',
        // 'javascript'        =>  $assetsDir . '/js/backend.min.js',
        'stylesheet' => $assetsDir . '/css/privacyOverview.css'
    ),
));

/**
 * Front end modules
 */
$GLOBALS['FE_MOD']['miscellaneous']['analyticsOptOut'] = 'Foc\ContaoPrivacyBundle\Modules\AnalyticsOptOut';

/**
 * Content elements
 */
$GLOBALS['TL_CTE']['media']['youtube'] = 'Foc\ContaoPrivacyBundle\Elements\YouTube';
$GLOBALS['TL_CTE']['media']['vimeo'] = 'Foc\ContaoPrivacyBundle\Elements\Vimeo';