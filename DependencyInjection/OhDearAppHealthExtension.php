<?php
declare(strict_types=1);

namespace Devolicious\OhDearAppHealthBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;

final class OhDearAppHealthExtension extends Extension
{
    /**
     * @param array<mixed> $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter(
            'oh_dear_app_health.secret',
            $config['secret']
        );

        $yamlLoader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $yamlLoader->load('services.yaml');
    }
}
