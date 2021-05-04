<?php

declare(strict_types=1);

namespace Tests\Synolia\SyliusMailTesterPlugin\PHPUnit\DependencyInjection\Pass;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Synolia\SyliusMailTesterPlugin\Resolver\FormTypeResolver;
use Synolia\SyliusMailTesterPlugin\Resolver\ResolvableFormTypeInterface;

final class ResolvableFormTypeResolverCompilerPassTest extends KernelTestCase
{
    public function testResolverIsFilledWithResolvableFormTypes(): void
    {
        self::bootKernel();

        $formTypeResolver = self::$container->get(FormTypeResolver::class);
        self::assertNotNull($formTypeResolver);
        $reflectionProvider = new \ReflectionClass($formTypeResolver);
        $formTypesProperty = $reflectionProvider->getProperty('formTypes');
        $formTypesProperty->setAccessible(true);
        /** @var array $formTypes */
        $formTypes = $formTypesProperty->getValue($formTypeResolver);

        self::assertGreaterThan(0, \count($formTypes));
        foreach ($formTypes as $formType) {
            self::assertInstanceOf(ResolvableFormTypeInterface::class, $formType);
        }
    }
}
