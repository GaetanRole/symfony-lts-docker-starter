# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

Feature:
  In order to navigate on the Symfony application
  As a user
  I want to access on the home page

  Scenario: A user can access to the home page
    When a user sends a request to "/en/"
    Then the response status code should be "200"
