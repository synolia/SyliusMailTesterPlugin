<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\CoreBundle\Mailer\Emails;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

final class AdminPasswordResetType extends AbstractType
{
    /** @var string */
    protected static $syliusEmailKey = Emails::ADMIN_PASSWORD_RESET;

    public function __construct(
        #[Autowire(param: 'sylius.model.admin_user.class')]
        private readonly string $syliusAdminUserClass,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('adminUser', EntityType::class, [
                'class' => $this->syliusAdminUserClass,
                'query_builder' => fn (EntityRepository $entityRepository): QueryBuilder => $entityRepository->createQueryBuilder('admin_user'),
            ])
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event): void {
                    /** @var UserInterface $adminUser */
                    $adminUser = $event->getForm()->get('adminUser')->getData();
                    if ($adminUser instanceof $this->syliusAdminUserClass) {
                        $adminUser->setPasswordResetToken('TEST_RESET_TOKEN');
                    }
                },
            )
        ;
    }
}
