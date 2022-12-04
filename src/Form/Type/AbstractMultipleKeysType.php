<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type;

use Synolia\SyliusMailTesterPlugin\Resolver\ResolvableFormTypeInterface;

abstract class AbstractMultipleKeysType extends \Symfony\Component\Form\AbstractType implements ResolvableFormTypeInterface
{
    /** @var string[] */
    protected static $syliusEmailKeys = [];

    public function support(string $emailKey): bool
    {
        return in_array($emailKey, static::$syliusEmailKeys, true);
    }

    public function getCode(): string
    {
        return static::$syliusEmailKeys[0];
    }

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return static::$syliusEmailKeys;
    }

    public function getFormType(string $emailKey): ResolvableFormTypeInterface
    {
        return $this;
    }
}
