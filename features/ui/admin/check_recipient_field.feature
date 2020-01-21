@managing_mail_tester
Feature: Check recipient field
    In order to have the recipient field
    As an Administrator
    I want to have the recipient field

    Background:
        Given I am logged in as an administrator

    @ui
    Scenario: Check the recipient field
        When I go to the mail tester page
        Then the mail tester field "mail_tester[recipient]" should be empty
        Then I write test in the field "mail_tester[recipient]"
        Then the mail tester field "mail_tester[recipient]" should have test as value