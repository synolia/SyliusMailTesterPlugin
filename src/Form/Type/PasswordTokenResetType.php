<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

final class PasswordTokenResetType extends AbstractType
{
    private const SYLIUS_EMAIL_KEYS = [
        'password_reset',
        'reset_password_token',
        'reset_password_pin',
    ];

    /** @var string */
    private $syliusShopUserClass;

    public function __construct(string $syliusShopUserClass)
    {
        $this->syliusShopUserClass = $syliusShopUserClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('user', EntityType::class, [
                'class' => $this->syliusShopUserClass,
                'query_builder' => function (EntityRepository $entityRepository): QueryBuilder {
                    return $entityRepository->createQueryBuilder('shop_user')
                        ->where('shop_user.passwordResetToken IS NOT NULL');
                },
            ])
        ;
    }

    public function support(string $emailKey): bool
    {
        return in_array($emailKey, self::SYLIUS_EMAIL_KEYS, true);
    }

    public function getCode(): string
    {
        return self::SYLIUS_EMAIL_KEYS[0];
    }
}
