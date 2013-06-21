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

$sub_menu = "110400";
include_once("./_common.php");
include_once("mw.menus.lib.php");

check_demo();

auth_check($auth[$sub_menu], "w");

check_token();

for ($i=0; $i<count($chk); $i++) {
    // 실제 번호를 넘김
    $k = $chk[$i];

    $sql = " update $mw[menu_middle_table]
		set mm_name         = '{$_POST[mm_name][$k]}',  
		    mm_left	    = '{$_POST[mm_left][$k]}',
		    mm_skin	    = '{$_POST[mm_skin][$k]}'
	      where mm_id	    = '{$_POST[mm_id][$k]}' ";
    sql_query($sql);
    mw_set_middle_menu($_POST[mm_id][$k]);
}
mw_set_middle_menus($gr_id);

goto_url("$mw[admin_path]/mw.m.menu.php?gr_id={$gr_id}$qstr");
?>
