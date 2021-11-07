<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Synolia\SyliusMailTesterPlugin\DataRetriever\EmailKeysDataRetrieverInterface;

final class ChoiceSubjectsType extends AbstractType
{
    public const EVERY_SUBJECTS = 'every_subjects';

    /** @var EmailKeysDataRetrieverInterface */
    private $emailKeysDataRetriever;

    public function __construct(EmailKeysDataRetrieverInterface $emailKeysDataRetriever)
    {
        $this->emailKeysDataRetriever = $emailKeysDataRetriever;
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $emailKeys = ['sylius.ui.admin.mail_tester.every_subjects' => self::EVERY_SUBJECTS];
        foreach ($this->emailKeysDataRetriever->getEmailKeys() as $emailKey) {
            $emailKeys[$emailKey] = $emailKey;
        }

        $resolver->setDefaults(['choices' => $emailKeys]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
