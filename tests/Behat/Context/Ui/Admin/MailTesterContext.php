<?php

declare(strict_types=1);

namespace Tests\Synolia\SyliusMailTesterPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPageInterface;
use Sylius\Behat\NotificationType;
use Sylius\Behat\Service\NotificationCheckerInterface;
use Sylius\Behat\Service\Resolver\CurrentPageResolverInterface;
use Synolia\SyliusMailTesterPlugin\DataRetriever\EmailKeysDataRetriever;
use Tests\Synolia\SyliusMailTesterPlugin\Behat\Page\Admin\MailTester\IndexPageInterface;
use Webmozart\Assert\Assert;

final class MailTesterContext implements Context
{
    /** @var IndexPageInterface */
    private $indexPage;

    /** @var CurrentPageResolverInterface */
    private $currentPageResolver;

    /** @var EmailKeysDataRetriever */
    private $emailKeysDataRetriever;

    /** @var NotificationCheckerInterface */
    private $notificationChecker;

    public function __construct(
        IndexPageInterface $indexPage,
        CurrentPageResolverInterface $currentPageResolver,
        EmailKeysDataRetriever $emailKeysDataRetriever,
        NotificationCheckerInterface $notificationChecker
    ) {
        $this->indexPage = $indexPage;
        $this->currentPageResolver = $currentPageResolver;
        $this->emailKeysDataRetriever = $emailKeysDataRetriever;
        $this->notificationChecker = $notificationChecker;
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
        Assert::isEmpty($currentPage->getFieldValue($field));
    }

    /**
     * @Then I choose the subject :subject
     */
    public function iChooseTheSubject(string $subject): void
    {
        /** @var IndexPageInterface $currentPage */
        $currentPage = $this->resolveCurrentPage();
        $mailTesterSubjectValue = $currentPage->writeInField($subject, 'mail_tester[subjects]')->getValue();
        Assert::contains($subject, $mailTesterSubjectValue);
    }

    /**
     * @Then the subject chosen in the form should be :subject
     */
    public function theSubjectChooseInTheFormShouldBe(string $subject): void
    {
        /** @var IndexPageInterface $currentPage */
        $currentPage = $this->resolveCurrentPage();
        $mailTesterSubjectValue = $currentPage->getFieldValue('mail_tester[subjects]');
        Assert::eq($subject, $mailTesterSubjectValue);
    }

    /**
     * @Then I submit the subject
     */
    public function iSubmitTheSubject(): void
    {
        /** @var IndexPageInterface $currentPage */
        $currentPage = $this->resolveCurrentPage();
        $currentPage->pressButton('mail_tester[change_form_subject]');
    }

    /**
     * @Then I submit the email
     */
    public function iSubmitTheEmail(): void
    {
        /** @var IndexPageInterface $currentPage */
        $currentPage = $this->resolveCurrentPage();
        $currentPage->pressButton('mail_tester[submit]');
    }

    /**
     * @Then the mail tester field :field should not be empty
     */
    public function theMailTesterFieldShouldNotBeEmpty(string $field): void
    {
        /** @var IndexPageInterface $currentPage */
        $currentPage = $this->resolveCurrentPage();
        Assert::notEmpty($currentPage->getFieldValue($field));
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
        Assert::contains($currentPage->getFieldValue($field), $text);
    }

    /**
     * @Then the subjects should have every subjects
     */
    public function theSubjectsShouldHaveEverySubjects(): void
    {
        /** @var IndexPageInterface $currentPage */
        $currentPage = $this->resolveCurrentPage();
        foreach ($this->emailKeysDataRetriever->getEmailKeys() as $emailKey) {
            Assert::contains($currentPage->getSelectorHtml('mail_tester[subjects]'), $emailKey);
        }
    }

    /**
     * @Then the email has been successfully send
     */
    public function theEmailHasBeenSuccessfullySend(): void
    {
        $this->notificationChecker->checkNotification(
            'sylius.ui.admin.mail_tester.success',
            NotificationType::success()
        );
    }
}
