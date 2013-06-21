<?php
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

$sub_menu = "110800";
include_once("./_common.php");

if ($w == 'u')
    check_demo();

auth_check($auth[$sub_menu], "w");

check_token();

$_POST[pp_start] .= " ".$_POST[pp_start_time];
$_POST[pp_end] .= " ".$_POST[pp_end_time];

$sql_common = " pp_start = '$_POST[pp_start]',
		pp_end = '$_POST[pp_end]',
		gr_id = '$_POST[gr_id]',
		pp_use = '$_POST[pp_use]',
		pp_center = '$_POST[pp_center]',
		pp_left = '$_POST[pp_left]',
		pp_top = '$_POST[pp_top]',
		pp_width = '$_POST[pp_width]',
		pp_height = '$_POST[pp_height]',
		pp_type = '$_POST[pp_type]',
		pp_time = '$_POST[pp_time]',
		mb_level = '$_POST[mb_level]',
		pp_subject = '$_POST[pp_subject]',
		pp_content = '$_POST[pp_content]'";

if ($w == "") {
    $sql = "insert into $mw[popup_table] 
		    set mb_id = '$member[mb_id]',
			pp_datetime = '$g4[time_ymdhis]',
			$sql_common ";
    sql_query($sql);
    $pp_id = mysql_insert_id();

}
elseif ($w == "u") {
 
    $sql = " update $mw[popup_table]
		set $sql_common
	      where pp_id = '$_POST[pp_id]' ";
    sql_query($sql);
}

goto_url("$mw[admin_path]/mw.popup.form.php?w=u&pp_id=$pp_id&$qstr");

