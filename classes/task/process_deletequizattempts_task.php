<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace tool_deletequizattempts\task;

use coding_exception;
use core\task\adhoc_task;

/**
 * Admin tool "Delete all quiz attempts" - Adhoc task that performs deletion of student quiz attempts.
 *
 * @package     tool_deletequizattempts
 * @copyright   2024 Danou Nauck, lern.link GmbH <danou.nauck@lernlink.de>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class process_deletequizattempts_task extends adhoc_task {
    /**
     * Return the name of the component.
     *
     * @return string The name of the component.
     */
    public function get_component() {
        return 'tool_deletequizattempts';
    }

    /**
     * Run the adhoc task and delete the quiz attempts.
     */
    public function execute() {
        global $CFG, $USER, $DB;
        require_once($CFG->dirroot.'/mod/quiz/locallib.php');

        // Get the ad-hoc task data.
        $customdata = $this->get_custom_data();

        // Get the user ID who triggered the task.
        if (isset($customdata->userid)) {
            // Try to find the according user id.
            $userid = $customdata->userid;
        } else {
            // Otherwise use the one from the system if none is supplied.
            $userid = $USER->id;
        }

        // Get the quiz module ID.
        $quizmoduleid = $DB->get_field('modules', 'id', ['name' => 'quiz']);

        // Get all quizzes that exist.
        $cmresults = $DB->get_records('course_modules', ['module' => $quizmoduleid], '', 'id');

        // Iterate over the list of all found quizzes.
        foreach ($cmresults as $thisquizcm) {
            // Delete the quiz attempts.
            self::delete_quizattempts($thisquizcm->id, $userid);
        }
    }

    /**
     * Delete the quiz attempts.
     *
     * @param int $qid The quiz ID.
     * @param int $userid The user ID.
     * @return int The amount of deleted quiz attempts.
     */
    public static function delete_quizattempts($qid, $userid) {
        global $DB, $USER;

        // We let the user know on which quiz we are currently working.
        mtrace('Processing quiz attempts of quiz CMID '.$qid);

        // Make sure qid is valid.
        if ($qid <= 0) {
            mtrace('... Invalid quiz ID. Must be > 0. The quiz will be skipped.');
            return false;
        }

        // Security check that this quiz is really existing in a course.
        if (!$cm = get_coursemodule_from_id('quiz', $qid)) {
            mtrace('... Invalid course module ID. Must be > 0. The quiz will be skipped.');
            return false;
        }

        // Make sure that also the connected quiz from the CMID is also existing.
        if (!$quiz = $DB->get_record('quiz', ['id' => $cm->instance])) {
            mtrace('... Invalid course module. The quiz will be skipped.');
            return false;
        }

        // Get all available quiz attempts for this particular quiz.
        $attempts = $DB->get_records('quiz_attempts', ['quiz' => $quiz->id]);

        // Count the attempts.
        $numberofattempts = count($attempts);

        // If at least one attempt exists.
        if ($numberofattempts > 0) {
            // Iterate over the attempts.
            foreach ($attempts as $attempt) {
                // Delete the attempt from the quiz.
                quiz_delete_attempt($attempt, $quiz);

                // Trace this attempt.
                mtrace ('... Deleting attempt '. $attempt->id);
            }

            // Trace this quiz.
            mtrace('... Deleted '.$numberofattempts.' attempts as a whole.');

            // Fire an event.
            $eventargs['userid'] = isset($userid) ? $userid : $USER->id;
            $eventargs['context'] = \context_module::instance($qid);
            $eventargs['objectid'] = $qid;
            $otherarray['amount'] = $numberofattempts;
            $eventargs['other'] = $otherarray;
            $event = \tool_deletequizattempts\event\event_quizattemptsdeleted::create($eventargs);
            $event->trigger();

            // Otherwise.
        } else {
            // Trace.
            mtrace('... The quiz does not contain any attempts.');
        }

        // Return the number of attempts (which have been deleted).
        return $numberofattempts;
    }
}
