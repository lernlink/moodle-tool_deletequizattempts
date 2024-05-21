@tool @tool_deletequizattempts
Feature: Delete students Quiz attempts manually in the backend
  A test to check if we can manually delete Test attempts
  as a manager
  to clean the database

  Scenario: Delete all quiz attempts
    Given the following "users" exist:
      | username | firstname | lastname | email                |
      | teacher1 | Terry1    | Teacher1 | teacher1@example.com |
      | student1 | Sam1      | Student1 | student1@example.com |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1        | 0        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | student1 | C1     | student        |
    And the following "activity" exists:
      | activity | quiz                  |
      | course   | C1                    |
      | idnumber | 00001                 |
      | name     | Test quiz name        |
      | intro    | Test quiz description |
      | section  | 1                     |
      | grade    | 10                    |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on
    And I add a "True/False" question to the "Test quiz name" quiz with:
      | Question name                      | First question                          |
      | Question text                      | Answer the first question               |
      | General feedback                   | Thank you, this is the general feedback |
      | Correct answer                     | False                                   |
      | Feedback for the response 'True'.  | So you think it is true                 |
      | Feedback for the response 'False'. | So you think it is false                |
    And I log out

    And I am on the "Test quiz name" "quiz activity" page logged in as student1
    And I press "Attempt quiz"
    Then I should see "Question 1"
    And I should see "Answer the first question"
    And I set the field "True" to "1"
    And I press "Finish attempt ..."
    And I should see "Answer saved"
    And I press "Submit all and finish"
    # TODO To be continued
