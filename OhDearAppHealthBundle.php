<?php
declare(strict_types=1);

namespace Devolicious\OhDearAppHealthBundle;

use Devolicious\OhDearAppHealthBundle\DependencyInjection\Compiler\CheckerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class OhDearAppHealthBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new CheckerPass());
    }
}
