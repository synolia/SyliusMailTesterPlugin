<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Synolia\SyliusMailTesterPlugin\DataRetriever\EmailKeysDataRetriever;
use Synolia\SyliusMailTesterPlugin\DependencyInjection\Pass\ResolvableFormTypeResolverCompilerPass;
use Synolia\SyliusMailTesterPlugin\Resolver\ResolvableFormTypeInterface;

final class SynoliaSyliusMailTesterExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $config);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('services.yaml');

        $container
            ->registerForAutoconfiguration(ResolvableFormTypeInterface::class)
            ->addTag(ResolvableFormTypeResolverCompilerPass::TAG_ID)
        ;

        $container->getDefinition(EmailKeysDataRetriever::class)->setArgument(0, '%sylius.mailer.emails%');
    }
}
