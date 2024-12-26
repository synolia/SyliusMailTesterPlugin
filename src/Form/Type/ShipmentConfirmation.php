<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type;

use Sylius\Bundle\CoreBundle\Mailer\Emails;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\FormBuilderInterface;

final class ShipmentConfirmation extends AbstractType
{
    /** @var string */
    protected static $syliusEmailKey = Emails::SHIPMENT_CONFIRMATION;

    public function __construct(
        #[Autowire(param: 'sylius.model.order.class')]
        private readonly string $syliusOrderClass,
        #[Autowire(param: 'sylius.model.shipment.class')]
        private readonly string $syliusShipmentClass,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('order', LimitedEntityType::class, [
                'class' => $this->syliusOrderClass,
                'choice_label' => 'number',
            ])
            ->add('shipment', LimitedEntityType::class, [
                'class' => $this->syliusShipmentClass,
                'choice_label' => static function (ShipmentInterface $shipment): string {
                    $order = $shipment->getOrder();
                    if (!$order instanceof OrderInterface) {
                        throw new \LogicException('order not found');
                    }

                    return sprintf(
                        '%s / order: %s, tracking: %s',
                        $shipment->getId(),
                        $order->getNumber(),
                        $shipment->getTracking() ?? 'NOT SET',
                    );
                },
            ])
        ;
    }
}
