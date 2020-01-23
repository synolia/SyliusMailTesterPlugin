<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type;

use Sylius\Component\Core\Model\Order;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

final class OrderConfirmationType extends AbstractType
{
    /** @var string */
    protected static $syliusEmailKey = 'order_confirmation';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder->add('order', EntityType::class, [
            'class' => Order::class,
            'choice_label' => 'number',
        ]);
    }
}
