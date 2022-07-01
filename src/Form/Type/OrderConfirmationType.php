<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

final class OrderConfirmationType extends AbstractMultipleKeysType
{
    /** @var array<string> */
    protected static $syliusEmailKeys = [
        'order_confirmation',
        'order_confirmation_resent',
    ];

    /** @var string */
    private $syliusOrderClass;

    public function __construct(string $syliusOrderClass)
    {
        $this->syliusOrderClass = $syliusOrderClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder->add('order', LimitedEntityType::class, [
            'class' => $this->syliusOrderClass,
            'choice_label' => 'number',
        ]);
    }
}
