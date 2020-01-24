<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Synolia\SyliusMailTesterPlugin\DataRetriever\EmailKeysDataRetriever;

final class ChoiceSubjectsType extends AbstractType
{
    /** @var EmailKeysDataRetriever */
    private $emailKeysDataRetriever;

    public function __construct(EmailKeysDataRetriever $emailKeysDataRetriever)
    {
        $this->emailKeysDataRetriever = $emailKeysDataRetriever;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $emailKeys = [];
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
