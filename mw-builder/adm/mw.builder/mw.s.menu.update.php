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

$sub_menu = "110500";
include_once("_common.php");
include_once("mw.menus.lib.php");

if ($w == 'u')
    check_demo();

auth_check($auth[$sub_menu], "w");

//if ($is_admin != "super" && $w == "") alert("최고관리자만 접근 가능합니다.");

if (!$ms_name) alert("메뉴 제목을 입력하세요.");

check_token();

$mm_id = $_POST[mm_id];
$ms_id = $_POST[ms_id];

/*$sql = " select count(*) as cnt from $mw[menu_small_table] where ms_name = '$_POST[ms_name]' and ms_id <> '$ms_id' ";
$row = sql_fetch($sql);
if ($row[cnt]) 
    alert("이미 존재하는 메뉴 제목 입니다.");*/

if ($_POST[ms_age] && $_POST[ms_age_type])  {
    $_POST[ms_age] .= $_POST[ms_age_type];
} else {
    $_POST[ms_age] = '';
}

$sql_common = " mm_id  		= '$_POST[mm_id]',
		ms_name		= '$_POST[ms_name]',
		ms_only_admin	= '$_POST[ms_only_admin]',
		bo_table	= '$_POST[bo_table]',
		ca_name		= '$_POST[ca_name]',
                ms_url	        = '$_POST[ms_url]',
                ms_out_url      = '$_POST[ms_out_url]',
                ms_target       = '$_POST[ms_target]',
                ms_use_not      = '$_POST[ms_use_not]',
                ms_check        = '$_POST[ms_check]',
                ms_gender       = '$_POST[ms_gender]',
                ms_age          = '$_POST[ms_age]',
                ms_lmenu        = '$_POST[ms_lmenu]',
                ms_rmenu        = '$_POST[ms_rmenu]',
                ms_level        = '$_POST[ms_level]',
                pg_id           = '$_POST[pg_id]',
                ms_css	        = '$_POST[ms_css]'";
if ($w == "") 
{
    $row = sql_fetch("select max(ms_order) as max from $mw[menu_small_table]");
    if (!$row)
	$max = 1;
    else
	$max = $row[max] + 1;

    $sql = " insert into $mw[menu_small_table] set $sql_common, ms_order = '$max' ";
    sql_query($sql);

    $ms_id = mysql_insert_id();
} 
else if ($w == "u") 
{
    $sql = " update $mw[menu_small_table]
		set $sql_common
	      where ms_id = '$ms_id' ";
    sql_query($sql);
}
mw_set_small_menu($ms_id);
mw_set_small_menus($mm_id);

goto_url("$mw[admin_path]/mw.s.menu.form.php?w=u&mm_id=$mm_id&ms_id=$ms_id$qstr");
