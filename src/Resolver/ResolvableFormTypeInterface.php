<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Resolver;

interface ResolvableFormTypeInterface
{
    public function support(string $emailKey): bool;

    public function getFormType(string $emailKey): self;

    public function getCode(): string;
}
