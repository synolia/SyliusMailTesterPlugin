<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

final class MailTesterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('recipient', EmailType::class, ['label' => 'sylius.ui.admin.mail_tester.recipient'])
            ->add('subjects', ChoiceSubjectsType::class)
            ->add('submit', SubmitType::class, ['attr' => ['class' => 'ui labeled icon primary button'], 'label' => 'sylius.ui.admin.mail_tester.submit'])
            ->getForm()
        ;
    }
}
