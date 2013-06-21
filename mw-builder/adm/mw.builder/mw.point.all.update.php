<?
/**
 * MW Builder for Gnuboard4
 *
 * Copyright (c) 2008 Choi Jae-Young <www.miwit.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

$sub_menu = "110850";
include_once("./_common.php");

auth_check($auth[$sub_menu], "w");

check_token();

$po_point   = $_POST['po_point'];
$po_content = $_POST['po_content'];

//$mb = get_member($mb_id);

$sql = "select mb_id from $g4[member_table] where mb_level > 1 and mb_leave_date = '' and mb_intercept_date = '' order by mb_id";
$qry = sql_query($sql);
while ($row = sql_fetch_array($qry)) {
    insert_point($row[mb_id], $po_point, $po_content, '@passive', $row[mb_id], $member[mb_id]."-".uniqid(""));
}

goto_url("$g4[admin_path]/point_list.php");
?>
