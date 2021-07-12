<?php

declare(strict_types=1);

namespace App\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Synolia\SyliusMailTesterPlugin\Form\Type\AbstractType;

final class DummyFormType extends AbstractType
{
    protected static $syliusEmailKey = 'my_dummy_form_type';

    public function buildForm(FormBuilderInterface $builder, array $options)
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
