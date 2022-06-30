@managing_mail_tester
Feature: Send every mails in one form
    In order to every mails
    As an Administrator
    I want to every mails in one form

    Background:
        Given I am logged in as an administrator

    @ui
    Scenario: Send every mails in one form
        When I go to the mail tester page
        Then I choose the subject every_subjects
        And I submit the subject
        Then I write "test@test.com" in the field "mail_tester[recipient]"
        And I write "test@test.com" in the field "mail_tester[contact_request][data][email]"
        And I write "test" in the field "mail_tester[contact_request][data][message]"
        And I write "test@test.com" in the field "mail_tester[my_dummy_form_type][data][email]"
        And I write "test" in the field "mail_tester[my_dummy_form_type][data][message]"
        And change value for "resettable-password@example.com" in select "mail_tester[password_reset][user]"
        And change value for "shop@example.com" in select "mail_tester[user_registration][user]"
        And change value for "resettable-password@example.com" in select "mail_tester[verification_token][user]"
        Then I submit the email
        And the email has been successfully send
