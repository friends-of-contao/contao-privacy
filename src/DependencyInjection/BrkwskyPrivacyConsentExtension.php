<?php

/**
 * @copyright   Christian Barkowsky 2018 <https://brkwsky.de>
 * @author      Christian Barkowsky <https://brkwsky.de>
 * @package     PrivacyConsentBundle
 * @license     LGPL-3.0+
 * @see         https://github.com/christianbarkowsky/contao-privacyconsent-bundle
 */

namespace Brkwsky\PrivacyConsentBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Adds the bundle services to the container.
 */
class BrkwskyPrivacyConsentExtension extends Extension
{

    /**
     * {@inheritdoc}
     */
    public function load(array $mergedConfig, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $loader->load('listener.yml');
    }
}
