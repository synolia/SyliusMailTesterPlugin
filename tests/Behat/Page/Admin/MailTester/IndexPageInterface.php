<?php

declare(strict_types=1);

namespace Tests\Synolia\SyliusMailTesterPlugin\Behat\Page\Admin\MailTester;

use Behat\Mink\Element\NodeElement;
use Sylius\Behat\Page\Admin\Crud\IndexPageInterface as BaseIndexPageInterface;

interface IndexPageInterface extends BaseIndexPageInterface
{
    public function getField(string $field): ?NodeElement;

    public function getFieldValue(string $field);

    public function getSelectorHtml(string $field): string;

    public function writeInField(string $text, string $field): ?NodeElement;

    public function pressButton(string $field): void;
}
