Feature:
    In order to manage a game
    I want to be able to handle game creation

    Scenario: Startpage contents an overview of all created games
        Given I am on "/"
        Then I should see "Übersicht der Spiele"

    Scenario: Startpage allows to go to game creation form
        Given I am on "/"
        When I follow "Neues Spiel"
        Then I should be on "/game/new"

    Scenario: A game has some actions to go to
        Given There is a game named "GameWithActions"
        And I am on "/"
        Then I should see delete link for game "GameWithActions"
        And I should see radio link for game "GameWithActions"
        And I should see export link for game "GameWithActions"
        And I should see edit link for game "GameWithActions"
        And I should see block link for game "GameWithActions"
        And I should not see unblock link for game "GameWithActions"

    Scenario: Game form allows to create a new game
        Given I am on "/game/new"
        When I fill in "create_game_name" with "FooGame"
        And I press "Speichern"
        Then I should be on "/"
        And I should see "FooGame" in the "table" element
        And I should see "Das Spiel mit dem Namen \"FooGame\" wurde erfolgreich gespeichert."

    Scenario: A game could not be created with empty name
        And I am on "/game/new"
        And I press "Speichern"
        Then I should be on "/game/new"
        And I should see "Dieser Wert sollte nicht leer sein."

    Scenario: A game could only be created more than once
        Given There is a game named "UniqueGameName"
        And I am on "/game/new"
        When I fill in "create_game_name" with "UniqueGameName"
        And I press "Speichern"
        Then I should be on "/"
        And I should see "UniqueGameName" in the "table" element
        And I should see "Das Spiel mit dem Namen \"UniqueGameName\" wurde erfolgreich gespeichert."

    Scenario: A game could be deleted
        Given There is a game named "DeletionFooGame"
        And I am on "/"
        And I see delete link for game "DeletionFooGame"
        When I follow delete link for game "DeletionFooGame"
        Then I should be on delete confirmation page for game "DeletionFooGame"
        And I should see "Löschen" in the "button" element
        When I press "Löschen"
        Then I should be on "/"
        And I should not see "DeletionFooGame" in the "table" element
        And I should see "Das Spiel mit dem Namen \"DeletionFooGame\" wurde erfolgreich gelöscht."

    Scenario: A game could be locked
        Given There is a game named "UnlockedGame"
        And I am on "/"
        When I follow block link for game "UnlockedGame"
        Then I should be on "/"
        And I should see "Das Spiel mit dem Namen \"UnlockedGame\" wurde erfolgreich gesperrt."
        And I should see radio link for game "UnlockedGame"
        And I should see export link for game "UnlockedGame"
        And I should not see edit link for game "UnlockedGame"
        And I should not see block link for game "UnlockedGame"
        And I should see unblock link for game "UnlockedGame"
