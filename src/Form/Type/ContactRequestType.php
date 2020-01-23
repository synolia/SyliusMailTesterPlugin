<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class ContactRequestType extends AbstractType
{
    /** @var string */
    protected static $syliusEmailKey = 'contact_request';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add(
                $builder->create('data', FormType::class, ['by_reference' => true])
                    ->add('email', EmailType::class)
                    ->add('message', TextType::class)
            )
        ;
    }
}
