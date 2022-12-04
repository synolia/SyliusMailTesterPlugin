<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type;

use Sylius\Bundle\CoreBundle\Mailer\Emails;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class ContactRequestType extends AbstractType
{
    /** @var string */
    protected static $syliusEmailKey = Emails::CONTACT_REQUEST;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add(
                $builder->create('data', FormType::class, ['by_reference' => true, 'label' => false])
                    ->add('email', EmailType::class)
                    ->add('message', TextType::class)
            )
        ;
    }
}
