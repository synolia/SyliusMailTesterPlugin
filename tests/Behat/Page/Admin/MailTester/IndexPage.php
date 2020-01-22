<?php

declare(strict_types=1);

namespace Tests\Synolia\SyliusMailTesterPlugin\Behat\Page\Admin\MailTester;

use Sylius\Behat\Page\Admin\Crud\IndexPage as BaseIndexPage;

final class IndexPage extends BaseIndexPage implements IndexPageInterface
{
    /** @return string|bool|array */
    public function getField(string $field)
    {
        return $this->getDocument()->findField($field)->getValue();
    }

    public function getSelectorHtml(string $field): string
    {
        return $this->getDocument()->findField($field)->getOuterHtml();
    }

    public function writeInField(string $text, string $field): void
    {
        $field = $this->getDocument()->findField($field);
        $field->setValue($text);
    }
}
