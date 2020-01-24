<?php

declare(strict_types=1);

namespace Tests\Application\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Synolia\SyliusMailTesterPlugin\Form\Type\AbstractType;

final class DummyFormType extends AbstractType
{
    protected static $syliusEmailKey = 'my-dummy-form-type';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('test', TextType::class);
    }
}
