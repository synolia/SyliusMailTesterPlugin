<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type;

use Sylius\Component\Core\Model\Order;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Synolia\SyliusMailTesterPlugin\Resolver\ResolvableFormTypeInterface;

final class OrderConfirmationFormType extends AbstractType implements ResolvableFormTypeInterface
{
    private const SYLIUS_EMAIL_KEY = 'order_confirmation';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder->add('order', EntityType::class, [
            'class' => Order::class,
            'choice_label' => 'number',
        ]);
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
