<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

final class UserRegistrationType extends AbstractType
{
    /** @var string */
    protected static $syliusEmailKey = 'user_registration';

    public function __construct(private string $syliusShopUserClass)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder->add('user', LimitedEntityType::class, [
            'class' => $this->syliusShopUserClass,
        ]);
    }
}
