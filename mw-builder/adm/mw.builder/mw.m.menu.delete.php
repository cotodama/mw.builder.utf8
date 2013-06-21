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
include_once("_common.php");
include_once("mw.menus.lib.php");

check_demo();

auth_check($auth[$sub_menu], "d");

check_token();

$mm_id = mysql_real_escape_string(trim($_POST['mm_id']));
$row = sql_fetch(" select count(*) as cnt from $mw[menu_small_table] where mm_id = '$mm_id' ");
if ($row[cnt]) {
    $msg = "이 중메뉴에 속한 소메뉴가 존재하여 중메뉴를 삭제할 수 없습니다.\\n\\n이 중메뉴에 속한 소메뉴를 먼저 삭제하여 주십시오.";
    $url = "$mw[admin_path]/mw.s.menu.php?mm_id=$mm_id";
    alert($msg, $url);
}

$sql = "select * from $mw[menu_middle_table] where mm_id = '$mm_id'";
$row = sql_fetch($sql);

// 중메뉴 order 변경
sql_query("update $mw[menu_middle_table] set mm_order = mm_order - 1 where gr_id = '$row[gr_id]' and mm_order > '$row[mm_order]'");

// 중메뉴 삭제
sql_query(" delete from $mw[menu_middle_table] where mm_id = '$mm_id' ");

goto_url("$mw[admin_path]/mw.m.menu.php?gr_id=$gr_id$qstr");

?>
