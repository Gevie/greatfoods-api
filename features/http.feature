Feature: Http Debug

    Scenario: Call a route which does not exist
        When I add "Content-Type" header equal to "application/json"
        And I send a "GET" request to "/api/v1/route-not-found"
        Then the response status code should be 404
