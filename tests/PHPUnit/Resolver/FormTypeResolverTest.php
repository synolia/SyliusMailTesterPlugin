<?php

declare(strict_types=1);

namespace Tests\Synolia\SyliusSchedulerCommandPlugin\PHPUni\Resolver;

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

        $this->formTypeResolver = self::$container->get(FormTypeResolver::class);
    }

    public function testResolverFoundSupportedFormType(): void
    {
        $this->assertInstanceOf(FormTypeInterface::class, $this->formTypeResolver->getFormType('my-dummy-form-type'));
    }

    public function testResolverDidNotFoundSupportedFormType(): void
    {
        $this->expectExceptionObject(new NoResolvableFormTypeFoundException(
            'No resolvable form found for email my-dummy-not-existing-form-type.'
        ));

        $this->formTypeResolver->getFormType('my-dummy-not-existing-form-type');
    }
}
