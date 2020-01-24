@managing_mail_tester
Feature: Check mail tester fields
    In order to have every mail tester field
    As an Administrator
    I want to have every mail tester field

    Background:
        Given I am logged in as an administrator

    @ui
    Scenario: Check the recipient field
        When I go to the mail tester page
        Then the mail tester field "mail_tester[recipient]" should be empty
        Then I write test in the field "mail_tester[recipient]"
        Then the mail tester field "mail_tester[recipient]" should have test as value
        Then the subjects should have every subjects