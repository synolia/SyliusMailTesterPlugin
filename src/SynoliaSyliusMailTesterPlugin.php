<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Synolia\SyliusMailTesterPlugin\DependencyInjection\Pass\ResolvableFormTypeResolverCompilerPass;

final class SynoliaSyliusMailTesterPlugin extends Bundle
{
    use SyliusPluginTrait;

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new ResolvableFormTypeResolverCompilerPass());
    }
}
