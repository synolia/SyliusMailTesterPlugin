<?php

declare(strict_types=1);

namespace Synolia\SyliusMailTesterPlugin\Fixture\Factory;

use Faker\Factory;
use Sylius\Component\Core\Model\ShopUserInterface;

final class ShopUserExampleFactory extends \Sylius\Bundle\CoreBundle\Fixture\Factory\ShopUserExampleFactory
{
    public function create(array $options = []): ShopUserInterface
    {
        $user = parent::create($options);
        $user->setPasswordResetToken((Factory::create())->unique()->sha256);
        $user->setEmailVerificationToken((Factory::create())->unique()->sha256);

        return $user;
    }
}
