<?php

declare(strict_types=1);

namespace Tests\Synolia\SyliusMailTesterPlugin\PHPUnit\Resolver;

use Sylius\Bundle\CoreBundle\Mailer\Emails;
use Sylius\Bundle\UserBundle\Mailer\Emails as UserBundleEmails;
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
        yield 'Contact request' => [Emails::CONTACT_REQUEST, \Synolia\SyliusMailTesterPlugin\Form\Type\ContactRequestType::class];
        yield 'Order confirmation' => [Emails::ORDER_CONFIRMATION, \Synolia\SyliusMailTesterPlugin\Form\Type\OrderConfirmationType::class];
        yield 'Order confirmation resend' => [Emails::ORDER_CONFIRMATION_RESENT, \Synolia\SyliusMailTesterPlugin\Form\Type\OrderConfirmationType::class];
        yield 'Password reset' => [Emails::PASSWORD_RESET, \Synolia\SyliusMailTesterPlugin\Form\Type\PasswordTokenResetType::class];
        yield 'Password reset token' => [UserBundleEmails::RESET_PASSWORD_TOKEN, \Synolia\SyliusMailTesterPlugin\Form\Type\PasswordTokenResetType::class];
        yield 'Password reset pin' => [UserBundleEmails::RESET_PASSWORD_PIN, \Synolia\SyliusMailTesterPlugin\Form\Type\PasswordTokenResetType::class];
        yield 'Shipment confirmation' => [Emails::SHIPMENT_CONFIRMATION, \Synolia\SyliusMailTesterPlugin\Form\Type\ShipmentConfirmation::class];
        yield 'User Registration' => [Emails::USER_REGISTRATION, \Synolia\SyliusMailTesterPlugin\Form\Type\UserRegistrationType::class];
        yield 'Token verification' => [UserBundleEmails::EMAIL_VERIFICATION_TOKEN, \Synolia\SyliusMailTesterPlugin\Form\Type\VerificationTokenType::class];
        yield '[Plus] Return request confirmation' => ['sylius_plus_return_request_confirmation', \Synolia\SyliusMailTesterPlugin\Form\Type\SyliusPlusReturnRequestType::class];
        yield '[Plus] Return request accepted' => ['sylius_plus_return_request_accepted', \Synolia\SyliusMailTesterPlugin\Form\Type\SyliusPlusReturnRequestType::class];
        yield '[Plus] Return request rejected' => ['sylius_plus_return_request_rejected', \Synolia\SyliusMailTesterPlugin\Form\Type\SyliusPlusReturnRequestType::class];
        yield '[Plus] Return request resolution changed' => ['sylius_plus_return_request_resolution_changed', \Synolia\SyliusMailTesterPlugin\Form\Type\SyliusPlusReturnRequestType::class];
        yield '[Plus] Return request repaired items sent' => ['sylius_plus_return_request_repaired_items_sent', \Synolia\SyliusMailTesterPlugin\Form\Type\SyliusPlusReturnRequestType::class];
        yield '[Plus] Loyalti purchase coupon' => ['sylius_plus_loyalty_purchase_coupon', \Synolia\SyliusMailTesterPlugin\Form\Type\SyliusPlusLoyaltyPurchaseCouponType::class];
    }
}
