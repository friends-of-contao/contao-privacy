<?php

/**
 * @copyright   Christian Barkowsky 2018 <https://brkwsky.de>
 * @author      Christian Barkowsky <https://brkwsky.de>
 * @package     PrivacyConsentBundle
 * @license     LGPL-3.0+
 * @see         https://github.com/christianbarkowsky/contao-privacyconsent-bundle
 */

namespace Contao\PrivacyConsentBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;

/**
 * Plugin for the Contao Manager.
 */
class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(BrkwskyPrivacyConsentBundle::class)->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
