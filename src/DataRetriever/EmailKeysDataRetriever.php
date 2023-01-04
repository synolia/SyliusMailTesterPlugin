<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\DataRetriever;

final class EmailKeysDataRetriever implements EmailKeysDataRetrieverInterface
{
    /** @var array<int, string> */
    private array $emailKeys;

    public function __construct(array $configuration)
    {
        $this->emailKeys = \array_keys($configuration);
    }

    public function getEmailKeys(): array
    {
        return $this->emailKeys ?? [];
    }
}
