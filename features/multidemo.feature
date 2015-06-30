Feature: Multi demo
  Shows how to run multiple browsers
  at once for single page app acceptance testing

  @javascript
  Scenario: basic scenario for three users
    Given "User1" starts session
    And "User2" starts session
    And "User3" starts session
    Then "User1" visits "/wiki/Main_Page"
    And "User2" visits "http://google.com"
    And "User3" visits "http://github.com"