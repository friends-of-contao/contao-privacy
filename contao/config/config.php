<?php

/**
 * @copyright   Friends of Contao 2018
 * @author      David Maack <david.maack@arcor.de>
 * @package     ContaoPrivacy
 * @license     LGPL-3.0+
 * @see         https://github.com/friends-of-contao/contao-privacy
 */

/**
 * Activate the crons
 */
$GLOBALS['TL_CRON']['minutely'][]      = array('Foc\ContaoPrivacyBundle\Cron\CleanupMembers', 'run');
