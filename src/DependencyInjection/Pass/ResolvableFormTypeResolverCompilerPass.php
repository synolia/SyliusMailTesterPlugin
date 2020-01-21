<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\DependencyInjection\Pass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Synolia\SyliusMailTesterPlugin\Resolver\FormTypeResolver;

final class ResolvableFormTypeResolverCompilerPass implements CompilerPassInterface
{
    public const TAG_ID = 'app.resolvable_form_type.resolver';

    public function process(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(FormTypeResolver::class);
        $taggedServices = $container->findTaggedServiceIds(self::TAG_ID);
        $taggedId = \array_keys($taggedServices);
        foreach ($taggedId as $id) {
            $definition->addMethodCall('addFormType', [new Reference($id)]);
        }
    }
}
