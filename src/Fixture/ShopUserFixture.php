<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Fixture;

final class ShopUserFixture extends \Sylius\Bundle\CoreBundle\Fixture\ShopUserFixture
{
    public function getName(): string
    {
        return 'synolia_mail_tester_shop_user';
    }
}
