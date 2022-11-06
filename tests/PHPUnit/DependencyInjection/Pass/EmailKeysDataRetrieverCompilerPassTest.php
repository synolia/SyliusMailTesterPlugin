<?php

declare(strict_types=1);

namespace Tests\Synolia\SyliusMailTesterPlugin\PHPUnit\DependencyInjection\Pass;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Synolia\SyliusMailTesterPlugin\DataRetriever\EmailKeysDataRetriever;

final class EmailKeysDataRetrieverCompilerPassTest extends KernelTestCase
{
    public function testRetrieverIsFilledWithEmailKeys(): void
    {
        self::bootKernel();

        /** @var EmailKeysDataRetriever $emailKeysDataRetriever */
        $emailKeysDataRetriever = static::getContainer()->get(EmailKeysDataRetriever::class);
        /** @var array $emailKeys */
        $emailKeys = $emailKeysDataRetriever->getEmailKeys();

        self::assertGreaterThan(0, \count($emailKeys));
    }
}
