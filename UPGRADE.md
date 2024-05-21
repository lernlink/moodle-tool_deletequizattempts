Upgrading this plugin
=====================

This is an internal documentation for plugin developers with some notes what has to be considered when updating this plugin to a new Moodle major version.

General
-------

* Generally, this is a quite simple plugin with just one purpose.
* It does not rely on any fluctuating library functions and should remain quite stable between Moodle major versions. 
* Thus, the upgrading effort is low.


Upstream changes
----------------

* This plugin does not inherit or copy anything from upstream sources. 


Automated tests
---------------

* The plugin has currently no coverage with Behat tests.


Manual tests
------------

* Login as teacher
* Create a quiz in a course

* Login as student
* Go to the course
* Submit a quiz attempt

* Login as teacher
* Go to the course
* Verify that the quiz attempt is there

* Login as admin
* Go to the tool's management page
* Press the 'Delete quiz attempts' button
* Wait for the cronjob to run the tool's ad-hoc task
* Verify that a \tool_deletequizattempts\event\event_quizattemptsdeleted event has been recorded

* Login as teacher
* Go to the course
* Verify that the quiz attempt is not there anymore
