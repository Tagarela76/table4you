Feature: We go to main page and see 10 restaurants per page

    Background:
        Given There is no "Restaurant" in database

    Scenario: User should see 10 restaurants per page on home page
        Given I am on "/"
        When I add restaurant "test#1"
        Then I should find restaurant "test#1"