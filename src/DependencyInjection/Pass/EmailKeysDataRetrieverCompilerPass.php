<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\DependencyInjection\Pass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Synolia\SyliusMailTesterPlugin\DataRetriever\EmailKeysDataRetriever;

final class EmailKeysDataRetrieverCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $container->getDefinition(EmailKeysDataRetriever::class)->setArgument(0, '%sylius.mailer.emails%');
    }
}
