<?php

declare(strict_types=1);

namespace Tests\Synolia\SyliusMailTesterPlugin\PHPUnit\DataRetriever;

use PHPUnit\Framework\TestCase;
use Synolia\SyliusMailTesterPlugin\DataRetriever\EmailKeysDataRetriever;

final class EmailKeysDataRetrieverTest extends TestCase
{
    /**
     * @dataProvider configurationDataProvider
     */
    public function testCreateInstance(array $configuration, array $expectedResult): void
    {
        self::assertSame(
            $expectedResult,
            (new EmailKeysDataRetriever($configuration))->getEmailKeys()
        );
    }

    public function configurationDataProvider(): \Generator
    {
        yield 'Empty array' => [[], []];
        yield 'Simple array key/value' => [
            [
                'test' => '123',
                'test2' => '123',
            ],
            ['test', 'test2'],
        ];
        yield 'Advanced array key/value' => [
            [
                'test' => [
                    'test3' => 'my value',
                    'test4' => [
                        'test5' => 'another value',
                    ],
                ],
                'test2' => '123',
            ],
            ['test', 'test2'],
        ];
    }
}
