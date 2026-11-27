<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Resolver;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Form\FormTypeInterface;

#[AutoconfigureTag]
interface ResolvableFormTypeInterface extends FormTypeInterface
{
    public function support(string $emailKey): bool;

    public function getFormType(string $emailKey): self;

    public function getCode(): string;
}
