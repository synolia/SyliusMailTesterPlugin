<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type;

use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\LocaleBundle\Form\Type\LocaleChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

final class MailTesterType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('recipient', EmailType::class, ['label' => 'sylius.ui.admin.mail_tester.recipient'])
            ->add('subjects', ChoiceSubjectsType::class)
            ->add('localeCode', LocaleChoiceType::class, [
                'placeholder' => null,
                'label' => 'synolia_mail_tester.form.locale',
            ])
            ->add('channel', ChannelChoiceType::class, [
                'label' => 'synolia_mail_tester.form.channel',
            ])
        ;

        if (isset($options['data']['subject'])) {
            $builder->add('subjects', ChoiceSubjectsType::class, ['data' => $options['data']['subject']]);
        }

        $builder->add('change_form_subject', SubmitType::class, [
            'attr' => ['class' => 'ui icon secondary button'],
            'label' => 'sylius.ui.admin.mail_tester.change_form_subject',
        ]);

        if (isset($options['data']['form_every_subjects'])) {
            foreach ($options['data']['form_every_subjects'] as $subject) {
                $builder->add(
                    $subject->getCode(),
                    get_class($subject),
                    ['label_attr' => ['class' => 'ui massive label']]
                );
            }
            $builder->add(
                'submit',
                SubmitType::class,
                [
                'attr' => ['class' => 'ui icon primary button'],
                'label' => 'sylius.ui.admin.mail_tester.submit', ]
            );
        }

        if (isset($options['data']['form_subject'])) {
            $builder
                ->add('form_subject_chosen', get_class($options['data']['form_subject']), ['label' => false])
                ->add(
                    'submit',
                    SubmitType::class,
                    [
                    'attr' => ['class' => 'ui icon primary button'],
                    'label' => 'sylius.ui.admin.mail_tester.submit', ]
                );
        }

        $builder->getForm();
    }
}
