@managing_mail_tester
Feature: No screen
  In order No screen
  As an Administrator
  I want No screen

  Background:
    Given I am logged in as an administrator

  @ui
  Scenario: No screen
    When a screenshot should not be made
    Then a screenshot should not be made
