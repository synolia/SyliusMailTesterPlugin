<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type;

use Sylius\Plus\Returns\Domain\Model\ReturnRequest;
use Sylius\Plus\Returns\Domain\Model\ReturnRequestInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Webmozart\Assert\Assert;

final class SyliusPlusReturnRequestType extends AbstractMultipleKeysType
{
    /** @var array<string> */
    protected static $syliusEmailKeys = [
        'sylius_plus_return_request_confirmation',
        'sylius_plus_return_request_accepted',
        'sylius_plus_return_request_rejected',
        'sylius_plus_return_request_resolution_changed',
        'sylius_plus_return_request_repaired_items_sent',
    ];

    public function __construct(private string $syliusOrderClass)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (!class_exists(ReturnRequest::class)) {
            return;
        }

        parent::buildForm($builder, $options);

        $builder
            ->add('order', LimitedEntityType::class, [
                'class' => $this->syliusOrderClass,
                'choice_label' => 'number',
            ])
            ->add('returnRequest', LimitedEntityType::class, [
                'class' => ReturnRequest::class,
                'choice_label' => static function (ReturnRequestInterface $returnRequestInterface): string {
                    $order = $returnRequestInterface->order();
                    Assert::notNull($order);

                    return sprintf(
                        'state: %s / resolution: %s / order: %s',
                        $returnRequestInterface->state(),
                        $returnRequestInterface->resolution(),
                        $order->getNumber(),
                    );
                },
            ])
        ;
    }
}
