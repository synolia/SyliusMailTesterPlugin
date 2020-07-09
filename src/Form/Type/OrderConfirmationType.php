<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

final class OrderConfirmationType extends AbstractType
{
    /** @var string */
    protected static $syliusEmailKey = 'order_confirmation';

    /** @var string */
    private $syliusOrderClass;

    public function __construct(string $syliusOrderClass)
    {
        $this->syliusOrderClass = $syliusOrderClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder->add('order', EntityType::class, [
            'class' => $this->syliusOrderClass,
            'choice_label' => 'number',
        ]);
    }
}
