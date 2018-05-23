<?php

/**
 * @copyright   Friends of Contao 2018
 * @author      Mathias Arzberger <https://pdir.de>
 * @package     ContaoPrivacy
 * @license     LGPL-3.0+
 * @see         https://github.com/friends-of-contao/contao-privacy
 */

namespace Foc\ContaoPrivacyBundle\Cron;

use Contao\FrontendCron;

class Automator
{

    /**
     * Deletes logfiles older than cleanup setting
     */
    public function cleanupLogfiles()
    {
        // check if cleanup is enabled
        if (!\Config::get('foc_logfile_cleanup')) {
            return;
        }

        // get the current timestamp
        $date = time();

        // subtract the days configured in the settings
        $date = (\Config::get('foc_logfile_cleanup_interval') * 86400);

        $arrFiles = preg_grep('/\.log$/', scan(TL_ROOT . '/system/logs'));

        foreach ($arrFiles as $strFile)
        {
            $objFile = new \File('system/logs/' . $strFile);

            // Delete older files
            if ($objFile->exists() && $objFile->mtime < $date)
            {
                $objFile->delete();
            }
        }

        // Add a log entry
        $this->log('Cleanup logfiles', __METHOD__, TL_CRON);
    }
}
