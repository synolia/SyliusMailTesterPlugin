<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Form\Type;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\FormBuilderInterface;

final class SyliusPlusLoyaltyPurchaseCouponType extends AbstractType
{
    /** @var string */
    protected static $syliusEmailKey = 'sylius_plus_loyalty_purchase_coupon';

    public function __construct(
        #[Autowire(param: 'sylius.model.promotion_coupon.class')]
        private readonly string $syliusPromotionCouponClass,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('promotionCoupon', LimitedEntityType::class, [
                'class' => $this->syliusPromotionCouponClass,
                'choice_label' => 'code',
            ])
        ;
    }
}
