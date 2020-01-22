<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Synolia\SyliusMailTesterPlugin\Resolver\ResolvableFormTypeInterface;

final class ContactRequestType extends AbstractType implements ResolvableFormTypeInterface
{
    private const SYLIUS_EMAIL_KEY = 'contact_request';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add(
                $builder->create('data', FormType::class, ['by_reference' => true])
                    ->add('email', EmailType::class)
                    ->add('message', TextType::class)
            )
        ;
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
