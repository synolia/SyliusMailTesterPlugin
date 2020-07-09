<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

final class UserRegistrationType extends AbstractType
{
    /** @var string */
    protected static $syliusEmailKey = 'user_registration';

    /** @var string */
    private $syliusShopUserClass;

    public function __construct(string $syliusShopUserClass)
    {
        $this->syliusShopUserClass = $syliusShopUserClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder->add('user', EntityType::class, [
            'class' => $this->syliusShopUserClass,
        ]);
    }
}
