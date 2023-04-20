@api @api_v1 @menu
Feature: API Menu
    This series of scenarios is intended to test the ability to interact with
    the menu entities via REST endpoints (/api/v1/menus).

    This feature ensures that endpoints called with the correct HTTP methods and
    correct payload where applicable pass as intended. It also ensures that
    things such as not found, gating and validation is working as intended.

    Background:
        # Do nothing, IDE currently requires background to be present
        # Future task: Authenticate with the API, not yet implemented gating
        # Future task: Allow response checking (then) via TableNode and PyStringNode

    @get @successful
    Scenario: Get all menus
        # Ensure that get works for all menus

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

    @get @successful
    Scenario: Get individual menus
        # Ensure that get works for more than one menu entity

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

    @get @not_found
    Scenario: Get individual menu (Not found)
        # Ensure that get throws a 404 when menu cannot be found

        When I send a GET request to 'menus/3'
        Then the response status code should be 404
            And response.error string equals 'The "Menu" record with id "3" could not be found.'

    @post @validation
    Scenario: Create new menu (Validation failed)
        # Ensure validation is preventing POST creation
        #  1. Name cannot be blank
        #  2. Name cannot exceed 128 characters
        #  3. Description cannot exceed 256 characters

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

    @post @successful
    Scenario: Create new menu
        # Ensure that a POST request to menus can create a new entity.
        # Ensures that newly created entity can be retrieved with GET /menus/:id

        When I send a POST request to 'menus' with:
            """
                {
                    "name": "Platters",
                    "description": "A mix of our best starter items",
                    "order": 3
                }
            """
        Then the response status code should be 201
            And response.id integer equals 3
            And response.name string equals 'Platters'
            And response.description string equals 'A mix of our best starter items'
            And response.order integer equals 3

        When I send a GET request to 'menus/3'
        Then the response status code should be 200
            And response.name string equals 'Platters'
            And response.description string equals 'A mix of our best starter items'
            And response.order integer equals 3

    @put @not_found
    Scenario: Update a menu item with PUT (Not found)
        # Ensure that update throws a 404 when menu cannot be found

        When I send a PUT request to 'menus/4' with:
            """
                {
                    "name": "Chef Specials",
                    "description": "A selection of our chef's recommended specials",
                    "order": 4
                }
            """

        Then the response status code should be 404
            And response.error string equals 'The "Menu" record with id "4" could not be found.'

    @put @validation
    Scenario: Update a menu item with PUT (Validation failed)
        # Ensure validation is preventing PUT update
        #  1. Name cannot be blank
        #  2. Name cannot exceed 128 characters
        #  3. Description cannot exceed 256 characters

        When I send a PUT request to 'menus/3' with:
            """
            {
                "name": "",
                "description": "Fail name blank and order unique validation",
                "order": 3
            }
            """
        Then debug response
            And the response status code should be 400
            And response.errors.name string equals 'Name cannot be blank'
            
        When I send a PUT request to 'menus/3' with:
            """
            {
                "name": "This is more than 128 characters, this is more than 128 characters, this is more than 128 characters, this is more than 128 characters.",
                "description": "This is more than 256 characters, this is more than 256 characters, this is more than 256 characters, this is more than 256 characters, this is more than 256 characters, this is more than 256 characters, this is more than 256 characters, this is more than 256 characters.",
                "order": 3
            }
            """
        Then the response status code should be 400
            And response.errors.name string equals 'Name cannot be longer than 128 characters'
            And response.errors.description string equals 'Description cannot be longer than 255 characters'


    @put @successful
    Scenario: Update a menu item with PUT (Successful)
        # Ensure that a PUT request to menus can update an entity.
        # Ensures that updated entity can be retrieved with GET /menus/:id

        When I send a PUT request to 'menus/3' with:
            """
                {
                    "name": "Chef Specials",
                    "description": "A selection of our chef's recommended specials",
                    "order": null
                }
            """

        Then the response status code should be 200
            And debug response
            And response.name string equals 'Chef Specials'
            And response.description string equals "A selection of our chef's recommended specials"
            And response does not have property 'order'

        When I send a GET request to 'menus/3'
        Then the response status code should be 200
            And debug response
            And response.name string equals 'Chef Specials'
            And response.description string equals "A selection of our chef's recommended specials"
            And response does not have property 'order'

    @patch @not_found
    Scenario: Update a menu item with PATCH (Not found)
        # Ensure that update throws a 404 when menu cannot be found

        When I send a PUT request to 'menus/4' with:
            """
                {
                    "name": "Head Chef Specials"
                }
            """

        Then the response status code should be 404
            And response.error string equals 'The "Menu" record with id "4" could not be found.'

    @patch @validation
    Scenario: Update a menu item with PATCH (Validation failed)
        # Ensure validation is preventing PATCH update
        #  1. Name cannot be blank
        #  2. Name cannot exceed 128 characters
        #  3. Description cannot exceed 256 characters

        When I send a PATCH request to 'menus/3' with:
            """
            {
                "name": ""
            }
            """
        Then debug response
            And the response status code should be 400
            And response.errors.name string equals 'Name cannot be blank'
            
        When I send a PATCH request to 'menus/3' with:
            """
            {
                "name": "This is more than 128 characters, this is more than 128 characters, this is more than 128 characters, this is more than 128 characters."
            }
            """
        Then the response status code should be 400
            And response.errors.name string equals 'Name cannot be longer than 128 characters'

        When I send a PATCH request to 'menus/3' with:
            """
            {
                "description": "This is more than 256 characters, this is more than 256 characters, this is more than 256 characters, this is more than 256 characters, this is more than 256 characters, this is more than 256 characters, this is more than 256 characters, this is more than 256 characters."
            }
            """
        Then the response status code should be 400
            And response.errors.description string equals 'Description cannot be longer than 255 characters'

    @patch @successful
    Scenario: Update a menu item with PATCH (Successful)
        # Ensure that a PATCH request to menus can update an entity.
        # Ensures that updated entity can be retrieved with GET /menus/:id

        When I send a PATCH request to 'menus/3' with:
            """
                {
                    "name": "Head Chef Specials"
                }
            """

        Then the response status code should be 200
            And debug response
            And response.name string equals 'Head Chef Specials'
            And response.description string equals "A selection of our chef's recommended specials"
            And response does not have property 'order'

        When I send a GET request to 'menus/3'
        Then the response status code should be 200
            And debug response
            And response.name string equals 'Head Chef Specials'
            And response.description string equals "A selection of our chef's recommended specials"
            And response does not have property 'order'

    @delete @not_found
    Scenario: Delete a menu (Not found)
        # Ensure that delete throws a 404 when menu cannot be found

        When I send a DELETE request to 'menus/4'

        Then the response status code should be 404
            And response.error string equals 'The "Menu" record with id "4" could not be found.'

    @delete @successful
    Scenario: Delete a menu (Successful)
        # Ensure that a DELETE request to menus can soft delete an entity.
        # Ensures that deleted entity can not be retrieved with GET /menus/:id

        When I send a DELETE request to 'menus/3'

        Then the response status code should be 200
            And response.message string equals 'Menu item "3" deleted'

        When I send a GET request to 'menus/3'

        Then the response status code should be 404
            And response.error string equals 'The "Menu" record with id "3" could not be found.'
