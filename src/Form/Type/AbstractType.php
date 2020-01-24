<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type;

use Synolia\SyliusMailTesterPlugin\Resolver\ResolvableFormTypeInterface;

abstract class AbstractType extends \Symfony\Component\Form\AbstractType implements ResolvableFormTypeInterface
{
    /** @var string */
    protected static $syliusEmailKey;

    public function support(string $emailKey): bool
    {
        return $emailKey === static::$syliusEmailKey;
    }

    public function getCode(): string
    {
        return static::$syliusEmailKey;
    }

    public function getFormType(string $emailKey): ResolvableFormTypeInterface
    {
        return $this;
    }
}
