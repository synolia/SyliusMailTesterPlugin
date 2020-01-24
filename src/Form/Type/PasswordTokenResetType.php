<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type;

use Doctrine\ORM\EntityRepository;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Core\Model\ShopUser;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

final class PasswordTokenResetType extends AbstractType
{
    private const SYLIUS_EMAIL_KEYS = [
        'password_reset',
        'reset_password_token',
        'reset_password_pin',
    ];

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('channel', EntityType::class, [
                'class' => Channel::class,
            ])
            ->add('user', EntityType::class, [
                'class' => ShopUser::class,
                'query_builder' => function (EntityRepository $entityRepository) {
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
