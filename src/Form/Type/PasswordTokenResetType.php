<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

final class PasswordTokenResetType extends AbstractMultipleKeysType
{
    protected static $syliusEmailKeys = [
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
                    return $entityRepository->createQueryBuilder('shop_user');
                },
            ])
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) {
                    /** @var UserInterface $user */
                    $user = $event->getForm()->get('user')->getData();
                    if ($user instanceof $this->syliusShopUserClass) {
                        $user->setPasswordResetToken('TEST_RESET_TOKEN');
                    }
                }
            );
    }
}
