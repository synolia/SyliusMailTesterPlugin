<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Fixture;

use Doctrine\Persistence\ObjectManager;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Synolia\SyliusMailTesterPlugin\Fixture\Factory\ShopUserExampleFactory;

#[AutoconfigureTag('sylius_fixtures.fixture')]
final class ShopUserFixture extends \Sylius\Bundle\CoreBundle\Fixture\ShopUserFixture
{
    public function __construct(
        ObjectManager $objectManager,
        #[Autowire(ShopUserExampleFactory::class)]
        ExampleFactoryInterface $exampleFactory,
    ) {
        parent::__construct($objectManager, $exampleFactory);
    }

    public function getName(): string
    {
        return 'synolia_mail_tester_shop_user';
    }
}
