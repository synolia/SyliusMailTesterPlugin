<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type;

use Sylius\Component\Core\Model\Order;
use Sylius\Component\Core\Model\Shipment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

final class ShipmentConfirmation extends AbstractType
{
    /** @var string */
    protected static $syliusEmailKey = 'shipment_confirmation';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('order', EntityType::class, [
                'class' => Order::class,
                'choice_label' => 'number',
            ])
            ->add('shipment', EntityType::class, [
                'class' => Shipment::class,
            ])
        ;
    }
}
