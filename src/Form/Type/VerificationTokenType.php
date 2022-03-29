<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

final class VerificationTokenType extends AbstractType
{
    /** @var string */
    protected static $syliusEmailKey = 'verification_token';

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
                    $user = $event->getForm()->get('user')->getData();
                    if ($user instanceof $this->syliusShopUserClass) {
                        $user->setEmailVerificationToken('TEST_VERIFICATION_TOKEN');
                    }
                }
            )
        ;
    }
}
