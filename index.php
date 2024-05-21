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
 * Admin tool "Delete all quiz attempts" - Manager view page.
 *
 * @package     tool_deletequizattempts
 * @copyright   2024 Danou Nauck, lern.link GmbH <danou.nauck@lernlink.de>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use tool_deletequizattempts\task\process_deletequizattempts_task;

// Require config.
// Let codechecker ignore the next line because otherwise it would complain about a missing login check
// after requiring config.php which is really not needed.
require(__DIR__.'/../../../config.php'); // phpcs:disable moodle.Files.RequireLogin.Missing

global $CFG, $PAGE, $OUTPUT, $USER;

// Require admin lib.
require_once($CFG->libdir . '/adminlib.php');

// Get the context.
$syscontext = context_system::instance();

// Security check.
require_capability('tool/deletequizattempts:manage', $syscontext);

// Get parameters.
$deleteall = optional_param('deleteall', false, PARAM_BOOL);

// Prepare the page.
$PAGE->set_url('/admin/tool/deletequizattempts/index.php');
$PAGE->set_context($syscontext);
$PAGE->set_title(get_string('pluginname', 'tool_deletequizattempts'));

// Let's check if we already received the command to delete.
if ($deleteall && confirm_sesskey()) {
    // Fire an ad hoc task to initiate the deletion process.
    $task = new process_deletequizattempts_task();
    $task->set_custom_data(['userid' => $USER->id]);
    $task->set_userid($USER->id);

    // Push the Task into the Queue.
    \core\task\manager::queue_adhoc_task($task, true);

    // Start page output.
    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('adminpage_heading', 'tool_deletequizattempts'));

    // Show notification.
    $notification = new \core\output\notification(get_string('adminpage_body_done', 'tool_deletequizattempts'),
        \core\output\notification::NOTIFY_INFO);
    $notification->set_show_closebutton(false);
    echo $OUTPUT->render($notification);

    // Finish page.
    echo $OUTPUT->footer();

    // Otherwise, if we are still in the first mask offering the button to the user.
} else {
    // Start page output.
    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('adminpage_heading', 'tool_deletequizattempts'));

    // Show page instruction.
    echo html_writer::tag('p', get_string('adminpage_body_before', 'tool_deletequizattempts'));

    // Build and render the button to open the confirm dialog.
    $btn = new \single_button(
        new moodle_url('/admin/tool/deletequizattempts/index.php', ['deleteall' => true]),
        get_string('deleteallattemps', 'tool_deletequizattempts'),
        'post',
        true);
    $btn->add_confirm_action(get_string('deleteallattempsconfirm', 'tool_deletequizattempts'));
    echo $OUTPUT->render($btn);;

    // Finish page.
    echo $OUTPUT->footer();
}
