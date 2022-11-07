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
        $formTypeResolver = static::getContainer()->get(FormTypeResolver::class);
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

    /**
     * @dataProvider provideEmailKeyAndExpectedForm
     *
     * @param class-string<object> $className
     */
    public function testItRetrieveCorrectFormForEmailKey(string $emailKey, string $className): void
    {
        self::assertInstanceOf($className, $this->formTypeResolver->getFormType($emailKey));
    }

    public function provideEmailKeyAndExpectedForm(): \Generator
    {
        yield 'Refund plugin' => ['units_refunded', \Synolia\SyliusMailTesterPlugin\Form\Type\Plugin\RefundPlugin\UnitsRefundedType::class];
        yield 'Contact request' => ['contact_request', \Synolia\SyliusMailTesterPlugin\Form\Type\ContactRequestType::class];
        yield 'Order confirmation' => ['order_confirmation', \Synolia\SyliusMailTesterPlugin\Form\Type\OrderConfirmationType::class];
        yield 'Order confirmation resend' => ['order_confirmation_resent', \Synolia\SyliusMailTesterPlugin\Form\Type\OrderConfirmationType::class];
        yield 'Password reset' => ['password_reset', \Synolia\SyliusMailTesterPlugin\Form\Type\PasswordTokenResetType::class];
        yield 'Password reset token' => ['reset_password_token', \Synolia\SyliusMailTesterPlugin\Form\Type\PasswordTokenResetType::class];
        yield 'Password reset pin' => ['reset_password_pin', \Synolia\SyliusMailTesterPlugin\Form\Type\PasswordTokenResetType::class];
        yield 'Shipment confirmation' => ['shipment_confirmation', \Synolia\SyliusMailTesterPlugin\Form\Type\ShipmentConfirmation::class];
        yield 'User Registration' => ['user_registration', \Synolia\SyliusMailTesterPlugin\Form\Type\UserRegistrationType::class];
        yield 'Token verification' => ['verification_token', \Synolia\SyliusMailTesterPlugin\Form\Type\VerificationTokenType::class];
        yield '[Plus] Return request confirmation' => ['sylius_plus_return_request_confirmation', \Synolia\SyliusMailTesterPlugin\Form\Type\SyliusPlusReturnRequestType::class];
        yield '[Plus] Return request accepted' => ['sylius_plus_return_request_accepted', \Synolia\SyliusMailTesterPlugin\Form\Type\SyliusPlusReturnRequestType::class];
        yield '[Plus] Return request rejected' => ['sylius_plus_return_request_rejected', \Synolia\SyliusMailTesterPlugin\Form\Type\SyliusPlusReturnRequestType::class];
        yield '[Plus] Return request resolution changed' => ['sylius_plus_return_request_resolution_changed', \Synolia\SyliusMailTesterPlugin\Form\Type\SyliusPlusReturnRequestType::class];
        yield '[Plus] Return request repaired items sent' => ['sylius_plus_return_request_repaired_items_sent', \Synolia\SyliusMailTesterPlugin\Form\Type\SyliusPlusReturnRequestType::class];
        yield '[Plus] Loyalti purchase coupon' => ['sylius_plus_loyalty_purchase_coupon', \Synolia\SyliusMailTesterPlugin\Form\Type\SyliusPlusLoyaltyPurchaseCouponType::class];
    }
}
