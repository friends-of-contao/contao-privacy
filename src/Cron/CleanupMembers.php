<?php

/**
 * @copyright   Friends of Contao 2018
 * @author      David Maack <david.maack@arcor.de>
 * @package     ContaoPrivacy
 * @license     LGPL-3.0+
 * @see         https://github.com/friends-of-contao/contao-privacy
 */

namespace Foc\ContaoPrivacyBundle\Cron;
use Contao\FrontendCron;

class CleanupMembers
{

    /**
     *  Select all memeber with an unused registration token, older than X days
     */
    public function run() {

        // check if cleanup is enabled
        if (!\Config::get('foc_member_cleanup')) {
            return;
        }

        // get the current timestamp
        $date = time();

        // subtract the hours configured in the settings
        $date -= (\Config::get('foc_member_cleanup_interval') * 86400);

        /** @var \Contao\Database $db */
        $db = \Database::getInstance()
            ->prepare('DELETE FROM tl_member WHERE disable = 1 AND lastLogin = 0 and activation != "" AND dateAdded > ?')
            ->execute($date);
    }
}