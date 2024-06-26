<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Admin tool "Delete all quiz attempts" - Settings.
 *
 * @package     tool_deletequizattempts
 * @copyright   2024 Danou Nauck, lern.link GmbH <danou.nauck@lernlink.de>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $ADMIN->add('tools', new admin_externalpage(
            'tool_deletequizattempts',
            get_string('pluginname', 'tool_deletequizattempts'),
            new moodle_url('/admin/tool/deletequizattempts/index.php'),
            'tool/deletequizattempts:manage')
    );
}
