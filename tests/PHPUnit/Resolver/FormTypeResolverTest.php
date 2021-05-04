<?php

declare(strict_types=1);

namespace Tests\Synolia\SyliusMailTesterPlugin\PHPUnit\Resolver;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormTypeInterface;
use Synolia\SyliusMailTesterPlugin\Resolver\FormTypeResolver;
use Synolia\SyliusMailTesterPlugin\Resolver\NoResolvableFormTypeFoundException;

final class FormTypeResolverTest extends KernelTestCase
{
    /** @var \Synolia\SyliusMailTesterPlugin\Resolver\FormTypeResolver */
    private $formTypeResolver;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        /** @var FormTypeResolver|null $formTypeResolver */
        $formTypeResolver = self::$container->get(FormTypeResolver::class);
        self::assertInstanceOf(FormTypeResolver::class, $formTypeResolver);

        $this->formTypeResolver = $formTypeResolver;
    }

    public function testResolverFoundSupportedFormType(): void
    {
        self::assertInstanceOf(FormTypeInterface::class, $this->formTypeResolver->getFormType('my_dummy_form_type'));
    }

    public function testResolverDidNotFoundSupportedFormType(): void
    {
        $this->expectExceptionObject(new NoResolvableFormTypeFoundException(
            'No resolvable form found for email my-dummy-not-existing-form-type.'
        ));

        $this->formTypeResolver->getFormType('my-dummy-not-existing-form-type');
    }
}
