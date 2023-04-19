Feature: API Menu
    Background:
        # Do nothing, IDE currently requires background to be present
        # Future task: Authenticate with the API, not yet implemented gating
        # Future task: Allow response checking (then) via TableNode

    Scenario: Get all menus
        Given the following menus exist:
            | id | name     | description                     | order |
            | 1  | Starters | This is a temporary description | 1     |
            | 2  | Mains    |                                 | 2     |

        When I send a GET request to 'menus'
        Then the response status code should be 200
            And the response contains 2 items
            And response.0.name string equals 'Starters'
            And response.0.description string equals 'This is a temporary description'
            And response.0.order integer equals 1
            And response.1.name string equals 'Mains'
            And response.1.description is null
            And response.1.order integer equals 2

    Scenario: Get individual menus
        When I send a GET request to 'menus/1'
        Then the response status code should be 200
            And response.id integer equals 1
            And response.description string equals 'This is a temporary description'
            And response.order integer equals 1
        
        When I send a GET request to 'menus/2'
        Then the response status code should be 200
            And response.id integer equals 2
            And response.description is null
            And response.order integer equals 2

    Scenario: Get individual menu (Not found)
        When I send a GET request to 'menus/3'
        Then the response status code should be 404
            And response.error string equals 'Menu item "3" not found'

    Scenario: Create new menu (Validation failed)
        When I send a POST request to 'menus' with:
            """
            {
                "name": "",
                "description": "Fail name blank and order unique validation"
            }
            """
        Then the response status code should be 400
            And response.errors.name string equals 'Name cannot be blank'
            
        When I send a POST request to 'menus' with:
            """
            {
                "name": "This is more than 128 characters, this is more than 128 characters, this is more than 128 characters, this is more than 128 characters.",
                "description": "This is more than 256 characters, this is more than 256 characters, this is more than 256 characters, this is more than 256 characters, this is more than 256 characters, this is more than 256 characters, this is more than 256 characters, this is more than 256 characters."
            }
            """
        Then the response status code should be 400
            And response.errors.name string equals 'Name cannot be longer than 128 characters'
            And response.errors.description string equals 'Description cannot be longer than 255 characters'

    # Scenario: Create a new menu
    Scenario: Create new menu
        When I send a POST request to 'menus' with:
            """
                {
                    "name": "Platters",
                    "description": "A mix of our best starter items",
                    "order": 3
                }
            """
        Then the response status code should be 200
            And response.id integer equals 3
            And response.name string equals 'Platters'
            And response.description string equals 'A mix of our best starter items'
            And response.order integer equals 3

        When I send a GET request to 'menus/3'
        Then the response status code should be 200
            And response.name string equals 'Platters'
            And response.description string equals 'A mix of our best starter items'
            And response.order integer equals 3

    # Scenario: Delete a menu (Not found)

    # Scenario: Delete a menu

    # Scenario: Update a menu (Not found)

    # Scenario: Update a menu (Validation failed)

    # Scenario: Update a menu
