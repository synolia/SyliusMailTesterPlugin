<?php

declare(strict_types=1);

namespace Tests\Application\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Synolia\SyliusMailTesterPlugin\Resolver\ResolvableFormTypeInterface;

final class DummyFormType extends AbstractType implements ResolvableFormTypeInterface
{
    private const SYLIUS_EMAIL_KEY = 'my-dummy-form-type';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('test', TextType::class);
    }

    public function support(string $emailKey): bool
    {
        return $emailKey === self::SYLIUS_EMAIL_KEY;
    }

    public function getFormType(string $emailKey): FormTypeInterface
    {
        return $this;
    }

    public function getCode(): string
    {
        return self::SYLIUS_EMAIL_KEY;
    }
}
