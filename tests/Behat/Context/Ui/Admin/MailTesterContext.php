<?php

declare(strict_types=1);

namespace Tests\Synolia\SyliusMailTesterPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPageInterface;
use Sylius\Behat\Service\Resolver\CurrentPageResolverInterface;
use Tests\Synolia\SyliusMailTesterPlugin\Behat\Page\Admin\MailTester\IndexPageInterface;
use Webmozart\Assert\Assert;

final class MailTesterContext implements Context
{
    /** @var IndexPageInterface */
    private $indexPage;

    /** @var CurrentPageResolverInterface */
    private $currentPageResolver;

    public function __construct(IndexPageInterface $indexPage, CurrentPageResolverInterface $currentPageResolver)
    {
        $this->indexPage = $indexPage;
        $this->currentPageResolver = $currentPageResolver;
    }

    /**
     * @return IndexPageInterface|SymfonyPageInterface
     */
    private function resolveCurrentPage(): SymfonyPageInterface
    {
        return $this->currentPageResolver->getCurrentPageWithForm([
            $this->indexPage,
        ]);
    }

    /**
     * @When I go to the mail tester page
     */
    public function iGoToTheMailTesterPage(): void
    {
        $this->indexPage->open();
    }

    /**
     * @Then the mail tester field :field should be empty
     */
    public function theMailTesterFieldShouldBeEmpty(string $field): void
    {
        /** @var IndexPageInterface $currentPage */
        $currentPage = $this->resolveCurrentPage();
        Assert::isEmpty($currentPage->getField($field));
    }

    /**
     * @Then the mail tester field :field should not be empty
     */
    public function theMailTesterFieldShouldNotBeEmpty(string $field): void
    {
        /** @var IndexPageInterface $currentPage */
        $currentPage = $this->resolveCurrentPage();
        Assert::notEmpty($currentPage->getField($field));
    }

    /**
     * @Then I write :text in the field :field
     */
    public function iWriteInTheField(string $text, string $field): void
    {
        /** @var IndexPageInterface $currentPage */
        $currentPage = $this->resolveCurrentPage();
        $currentPage->writeInField($text, $field);
    }

    /**
     * @Then the mail tester field :field should have :text as value
     */
    public function theMailTesterFiledShouldHaveAsValue(string $field, string $text): void
    {
        /** @var IndexPageInterface $currentPage */
        $currentPage = $this->resolveCurrentPage();
        Assert::contains($currentPage->getField($field), $text);
    }

    /**
     * @When a screenshot should not be made
     */
    public function aScreenshotShouldNotBeMade(): void
    {
        Assert::true(true, true);
    }
}
