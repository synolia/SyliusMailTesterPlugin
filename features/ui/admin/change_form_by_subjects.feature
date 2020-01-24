@managing_mail_tester
Feature: Change form by subjects
    In order to get the proper form for the subject
    As an Administrator
    I want to get the proper form for the subject

    Background:
        Given I am logged in as an administrator

    @ui
    Scenario: Change subjects to get their form
        When I go to the mail tester page
        Then I choose the subject contact_request
        And I submit the subject
        And the subject chosen in the form should be contact_request
        Then the mail tester field "mail_tester[form_subject_chosen][data][email]" should be empty