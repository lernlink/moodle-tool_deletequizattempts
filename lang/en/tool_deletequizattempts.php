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

/**
 * Admin tool "Delete all quiz attempts" - Language pack.
 *
 * @package     tool_deletequizattempts
 * @copyright   2024 Danou Nauck, lern.link GmbH <danou.nauck@lernlink.de>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Delete all quiz attempts';

// Manager view page.
$string['adminpage_heading'] = 'Delete all students\'s quiz attempts';
$string['adminpage_body_before'] = 'Here, you can delete all existing quiz attempts. After you deleted them, you will be able to edit, alter or modify the existing quizzes again as they are not longer blocked by previous completed attempts.';
$string['adminpage_body_done'] = 'An ad-hoc task has been scheduled which will delete all existing quiz attempts in the background. The ad-hoc task will be processed with the next cron run.';
$string['deleteallattemps'] = 'Delete all students quiz attempts now';
$string['deleteallattempsconfirm'] = 'Are you sure you want to delete all student attempts in all quizzes? The attempts will be deleted permanently and you will not be able to get the data back.';

// Events.
$string['event_deleteallattempts'] = 'All students\' quiz attempts deleted';
$string['event_description'] = 'The user with id {$a->userid} deleted {$a->amount} quiz attempts of the quiz with course module id {$a->objectid}.';

// Privacy API.
$string['privacy:metadata'] = 'The plugin to delete students quiz attempts does not store any personal data.';
