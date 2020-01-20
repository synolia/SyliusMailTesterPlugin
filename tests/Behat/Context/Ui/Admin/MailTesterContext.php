<?php

declare(strict_types=1);

namespace Tests\Synolia\SyliusMailTesterPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

final class MailTesterContext implements Context
{
    /**
     * @When a screenshot should not be made
     */
    public function aScreenshotShouldNotBeMade(): void
    {
        Assert::true(true, true);
    }
}
