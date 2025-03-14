<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\DependencyInjection;

use Sylius\InvoicingPlugin\SyliusInvoicingPlugin;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Synolia\SyliusMailTesterPlugin\Form\Type\Plugin\InvoicingPlugin\InvoiceType;

final class SynoliaSyliusMailTesterExtension extends Extension
{
    /**
     * @inheritdoc
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(\dirname(__DIR__, 2) . '/config'));

        $loader->load('services.yaml');

        if (!class_exists(SyliusInvoicingPlugin::class)) {
            $container->removeDefinition(InvoiceType::class);
        }
    }
}
