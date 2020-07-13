<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

final class ShipmentConfirmation extends AbstractType
{
    /** @var string */
    protected static $syliusEmailKey = 'shipment_confirmation';

    /** @var string */
    private $syliusOrderClass;

    /** @var string */
    private $syliusShipmentClass;

    public function __construct(string $syliusOrderClass, string $syliusShipmentClass)
    {
        $this->syliusOrderClass = $syliusOrderClass;
        $this->syliusShipmentClass = $syliusShipmentClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('order', EntityType::class, [
                'class' => $this->syliusOrderClass,
                'choice_label' => 'number',
            ])
            ->add('shipment', EntityType::class, [
                'class' => $this->syliusShipmentClass,
            ])
        ;
    }
}
