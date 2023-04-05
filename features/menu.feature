Feature: API Menu

    Background:
        Given the following menus exist:
        | id | name     | description                     | order |
        | 1  | Starters | This is a temporary description | 1     |
        | 2  | Mains    |                                 | 2     |
    
    Scenario: Get all menus
        When I send a GET request to "menus"
        Then the response status code should be 200
            And the response contains 2 items
            And response.0.name string equals "Starters"
            And response.0.description string equals "This is a temporary description"
            And response.0.order integer equals 1
            And response.1.name string equals "Mains"
            And response.1.description is null
            And response.1.order integer equals 2
        # And the JSON response should have the following menus:
        #     | id | name     | description                     | order |
        #     | 1  | Starters | This is a temporary description | 1     |
        #     | 2  | Mains    |                                 | 2     |
