<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\DataRetriever;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

final readonly class EmailKeysDataRetriever implements EmailKeysDataRetrieverInterface
{
    /** @var array<int, string> */
    private array $emailKeys;

    public function __construct(
        #[Autowire(param: 'sylius.mailer.emails')]
        array $configuration,
    ) {
        $this->emailKeys = \array_keys($configuration);
    }

    public function getEmailKeys(): array
    {
        return $this->emailKeys ?? [];
    }
}
