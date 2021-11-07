<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type\Plugin\RefundPlugin;

use Sylius\RefundPlugin\Entity\CreditMemo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Synolia\SyliusMailTesterPlugin\Form\Type\AbstractType;

final class UnitsRefundedType extends AbstractType
{
    /** @var string */
    protected static $syliusEmailKey = 'units_refunded';

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (!class_exists(CreditMemo::class)) {
            return;
        }

        $builder->add('creditMemo', EntityType::class, [
            'class' => CreditMemo::class,
            'choice_label' => 'number',
        ]);
    }
}
