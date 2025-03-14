<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type;

use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\LocaleBundle\Form\Type\LocaleChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Synolia\SyliusMailTesterPlugin\Resolver\ResolvableFormTypeInterface;
use Synolia\SyliusMailTesterPlugin\Resolver\ResolvableMultipleFormTypeInterface;

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
            'label' => 'sylius.ui.admin.mail_tester.change_form_subject',
        ]);

        if (isset($options['data']['form_every_subjects'])) {
            /** @var ResolvableFormTypeInterface $subject */
            foreach ($options['data']['form_every_subjects'] as $subject) {
                if (!class_exists('Sylius\Plus\SyliusPlusPlugin') && str_starts_with($subject->getCode(), 'sylius_plus')) {
                    continue;
                }

                if ($subject instanceof ResolvableMultipleFormTypeInterface) {
                    foreach ($subject->getCodes() as $code) {
                        $builder->add(
                            $code,
                            $subject::class,
                            ['label_attr' => ['class' => 'fs-2']],
                        );
                    }
                } else {
                    $builder->add(
                        $subject->getCode(),
                        $subject::class,
                        ['label_attr' => ['class' => 'fs-2']],
                    );
                }
            }
            $builder->add(
                'submit',
                SubmitType::class,
                [
                'label' => 'sylius.ui.admin.mail_tester.submit'],
            );
        }

        if (isset($options['data']['form_subject'])) {
            /** @var ResolvableFormTypeInterface $subject */
            $subject = $options['data']['form_subject'];

            $builder
                ->add('form_subject_chosen', $subject::class, ['label' => false])
                ->add(
                    'submit',
                    SubmitType::class,
                    [
                    'label' => 'sylius.ui.admin.mail_tester.submit'],
                )
            ;
        }

        $builder->getForm();
    }
}
