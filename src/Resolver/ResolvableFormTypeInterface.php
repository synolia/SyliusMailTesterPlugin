<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Resolver;

use Symfony\Component\Form\FormTypeInterface;

interface ResolvableFormTypeInterface
{
    public function support(string $emailKey): bool;

    public function getFormType(string $emailKey): FormTypeInterface;

    public function getCode(): string;
}
