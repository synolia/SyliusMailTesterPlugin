<?php

declare(strict_types=1);

namespace Tests\Synolia\SyliusSchedulerCommandPlugin\PHPUnit\DataRetriever;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Synolia\SyliusMailTesterPlugin\DataRetriever\EmailKeysDataRetriever;

final class EmailKeysDataRetrieverTest extends KernelTestCase
{
    public function testRetrieveEmailSubjects(): void
    {
        self::bootKernel();
        $this->assertGreaterThan(0, \count(self::$container->get(EmailKeysDataRetriever::class)->getEmailKeys()));
    }
}
