<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\DataRetriever;

final class EmailKeysDataRetriever
{
    /** @var array */
    private $configuration;

    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getEmailKeys(): array
    {
        return $this->configuration ? array_keys($this->configuration) : [];
    }
}
