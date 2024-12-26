<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\UserBundle\Mailer\Emails;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

final class VerificationTokenType extends AbstractType
{
    /** @var string */
    protected static $syliusEmailKey = Emails::EMAIL_VERIFICATION_TOKEN;

    public function __construct(
        #[Autowire(param: 'sylius.model.shop_user.class')]
        private readonly string $syliusShopUserClass,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('user', EntityType::class, [
                'class' => $this->syliusShopUserClass,
                'query_builder' => fn (EntityRepository $entityRepository): QueryBuilder => $entityRepository->createQueryBuilder('shop_user'),
            ])
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event): void {
                    /** @var UserInterface $user */
                    $user = $event->getForm()->get('user')->getData();
                    if ($user instanceof $this->syliusShopUserClass) {
                        $user->setEmailVerificationToken('TEST_VERIFICATION_TOKEN');
                    }
                },
            )
        ;
    }
}
