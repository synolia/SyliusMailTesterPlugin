<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\DataRetriever;

interface EmailKeysDataRetrieverInterface
{
    public function getEmailKeys(): array;
}
