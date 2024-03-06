<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Synolia\SyliusMailTesterPlugin\DependencyInjection\Pass\ResolvableFormTypeResolverCompilerPass;
use Synolia\SyliusMailTesterPlugin\Form\Type\Plugin\InvoicingPlugin\InvoiceType;
use Synolia\SyliusMailTesterPlugin\Resolver\ResolvableFormTypeInterface;

final class SynoliaSyliusMailTesterExtension extends Extension
{
    /**
     * @inheritdoc
     */
    public function load(array $config, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('services.yaml');

        $container
            ->registerForAutoconfiguration(ResolvableFormTypeInterface::class)
            ->addTag(ResolvableFormTypeResolverCompilerPass::TAG_ID)
        ;

        if (!class_exists('Sylius\InvoicingPlugin\SyliusInvoicingPlugin')) {
            $container->removeDefinition(InvoiceType::class);
        }
    }
}
