<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Resolver;

interface ResolvableMultipleFormTypeInterface extends ResolvableFormTypeInterface
{
    public function getCodes(): array;
}
