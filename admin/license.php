<?php
/* vim:set softtabstop=4 shiftwidth=4 expandtab: */
/**
 *
 * LICENSE: GNU Affero General Public License, version 3 (AGPLv3)
 * Copyright 2001 - 2019 Ampache.org
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

require_once '../lib/init.php';

if (!Access::check('interface', '100')) {
    UI::access_denied();

    return false;
}

UI::show_header();

// Switch on the actions
switch ($_REQUEST['action']) {
    case 'edit':
        if ((filter_has_var(INPUT_POST, 'license_id'))) {
            $license = new License(filter_input(INPUT_POST, 'license_id', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES));
            if ($license->id) {
                $license->update($_POST);
            }
            $text = T_('License Updated');
        } else {
            License::create($_POST);
            $text = T_('License Created');
        }
        show_confirmation($text, '', AmpConfig::get('web_path') . '/admin/license.php');
    break;
    case 'show_edit':
        $license = new License($_REQUEST['license_id']);
        // intentional fall through
    case 'show_create':
        require_once AmpConfig::get('prefix') . UI::find_template('show_edit_license.inc.php');
        break;
    case 'delete':
        License::delete($_REQUEST['license_id']);
        show_confirmation(T_('License Deleted'), '', AmpConfig::get('web_path') . '/admin/license.php');
    break;
    default:
        $browse = new Browse();
        $browse->set_type('license');
        $browse->set_simple_browse(true);
        $license_ids = $browse->get_objects();
        $browse->show_objects($license_ids);
        $browse->store();
    break;
}

UI::show_footer();
