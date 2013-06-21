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

$sub_menu = "110400";
include_once("_common.php");
include_once("mw.menus.lib.php");

if ($w == 'u')
    check_demo();

auth_check($auth[$sub_menu], "w");

//if ($is_admin != "super" && $w == "") alert("최고관리자만 접근 가능합니다.");

if (!$mm_name) alert("메뉴 제목을 입력하세요.");

check_token();

$gr_id = $_POST[gr_id];
$mm_id = $_POST[mm_id];

if ($_POST[mm_age] && $_POST[mm_age_type])  {
    $_POST[mm_age] .= $_POST[mm_age_type];
} else {
    $_POST[mm_age] = '';
}

$sql_common = " gr_id  		= '$_POST[gr_id]',
		mm_name		= '$_POST[mm_name]',
		mm_only_admin	= '$_POST[mm_only_admin]',
                mm_left	        = '$_POST[mm_left]',  
                mm_smove	= '$_POST[mm_smove]',  
                mm_lmenu	= '$_POST[mm_lmenu]',  
                mm_rmenu	= '$_POST[mm_rmenu]',  
                mm_smenu	= '$_POST[mm_smenu]',  
                mm_out_url	= '$_POST[mm_out_url]',  
                mm_target	= '$_POST[mm_target]',  
                mm_check	= '$_POST[mm_check]',  
                mm_gender	= '$_POST[mm_gender]',  
                mm_age	        = '$_POST[mm_age]',  
                mm_use_not	= '$_POST[mm_use_not]',  
                mm_css		= '$_POST[mm_css]',  
                mm_level	= '$_POST[mm_level]',  
                mm_skin		= '$_POST[mm_skin]'";
if ($w == "") 
{
    $row = sql_fetch("select max(mm_order) as max from $mw[menu_middle_table] where gr_id = '$gr_id'");
    if (!$row[max]) 
	$max = 1; 
    else 
	$max = $row[max] + 1;

    $sql = " insert into $mw[menu_middle_table] set $sql_common, mm_order = '$max' ";
    sql_query($sql);

    $mm_id = mysql_insert_id();
} 
else if ($w == "u") 
{
    $sql = " update $mw[menu_middle_table]
		set $sql_common
	      where mm_id = '$mm_id' ";
    sql_query($sql);
}
mw_set_middle_menu($mm_id);
mw_set_middle_menus($gr_id);


goto_url("$mw[admin_path]/mw.m.menu.form.php?w=u&gr_id=$gr_id&mm_id=$mm_id&$qstr");
