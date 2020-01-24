@managing_mail_tester
Feature: Send an email
    In order to send an email
    As an Administrator
    I want to send an email

    Background:
        Given I am logged in as an administrator

    @ui
    Scenario: Send contact_request mail
        When I go to the mail tester page
        Then I choose the subject contact_request
        And I submit the subject
        Then I write "test@test.com" in the field "mail_tester[recipient]"
        And I write "test" in the field "mail_tester[form_subject_chosen][data][message]"
        And I write "test@test.com" in the field "mail_tester[form_subject_chosen][data][email]"
        Then I submit the email
        And the email has been successfully send

    @ui
    Scenario: Send order_confirmation mail
        When I go to the mail tester page
        Then I choose the subject order_confirmation
        And I submit the subject
        Then I write "test@test.com" in the field "mail_tester[recipient]"
        Then I submit the email
        And the email has been successfully send