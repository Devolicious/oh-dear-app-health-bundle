<?php
declare(strict_types=1);

namespace Devolicious\OhDearAppHealthBundle\DependencyInjection\Compiler;

use Devolicious\OhDearAppHealthBundle\HealthChecker;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CheckerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(HealthChecker::class)) {
            return;
        }

        $definition = $container->findDefinition(HealthChecker::class);

        $taggedServices = $container->findTaggedServiceIds('oh_dear_app_health.checker');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addHealthChecker', [new Reference($id)]);
        }
    }
}
