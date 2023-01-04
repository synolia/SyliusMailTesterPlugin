<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type;

use Sylius\Bundle\CoreBundle\Mailer\Emails;
use Symfony\Component\Form\FormBuilderInterface;

final class OrderConfirmationType extends AbstractMultipleKeysType
{
    /** @var array<string> */
    protected static $syliusEmailKeys = [
        Emails::ORDER_CONFIRMATION,
        Emails::ORDER_CONFIRMATION_RESENT,
    ];

    public function __construct(private string $syliusOrderClass)
    {
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
