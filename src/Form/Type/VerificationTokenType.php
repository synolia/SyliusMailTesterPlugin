<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

final class VerificationTokenType extends AbstractType
{
    /** @var string */
    protected static $syliusEmailKey = 'verification_token';

    /** @var string */
    private $syliusChannelClass;

    /** @var string */
    private $syliusShopUserClass;

    public function __construct(string $syliusChannelClass, string $syliusShopUserClass)
    {
        $this->syliusChannelClass = $syliusChannelClass;
        $this->syliusShopUserClass = $syliusShopUserClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('channel', EntityType::class, [
                'class' => $this->syliusChannelClass,
            ])
            ->add('user', EntityType::class, [
                'class' => $this->syliusShopUserClass,
                'query_builder' => function (EntityRepository $entityRepository): QueryBuilder {
                    return $entityRepository->createQueryBuilder('shop_user')
                        ->where('shop_user.emailVerificationToken IS NOT NULL');
                },
            ])
        ;
    }
}
