Feature:
    In order to write a protocol
    I wantr to have a page with input for sender and receipent with the text that was sent

    Background:
        Given I am authenticated as "admin@vdolog.de" using "admin1234"

    Scenario: Protocol of an existing open game is editable
        Given There is a game named "Unlocked Game for Protocol"
        And I am on the protocol page of game named "Unlocked Game for Protocol"
        Then I should see form "protocol"
        And I should see fields in form "protocol"
            | id                |
            | protocol_sender   |
            | protocol_content  |
            | protocol_recipent |

    Scenario: Protocol of an blocked game is not editable
        Given There is a game named "Blocked Game for Protocol"
        And the game named "Blocked Game for Protocol" is blocked
        And I am on the protocol page of game named "Blocked Game for Protocol"
        Then I should not see form "protocol"

    Scenario: A protocol could be written
        Given There is a game named "Unlocked Game for Protocol"
        And I am on the protocol page of game named "Unlocked Game for Protocol"
        When I fill in "protocol_sender" with "200"
        And I fill in "protocol_recipent" with "300"
        And I fill in "protocol_content" with "Sicherungsmaterial Kabel vorhanden?"
        And I press "Speichern"
        Then I should see protocol parent entry from "200" to "300" with content "Sicherungsmaterial Kabel vorhanden?"
