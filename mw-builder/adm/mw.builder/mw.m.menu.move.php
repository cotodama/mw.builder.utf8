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

auth_check($auth[$sub_menu], "w");

$mm_id = $_GET[mm_id];

$mm = sql_fetch("select * from $mw[menu_middle_table] where mm_id = '$mm_id'");
$gr_id = $mm[gr_id];
 
$row = sql_fetch("select max(mm_order) as max from $mw[menu_middle_table] where gr_id = '$gr_id'");
$max = $mm_order = $row[max];

$sql = "select * from $mw[menu_middle_table] where gr_id = '$gr_id' and mm_order = 0 order by gr_id asc";
$qry = sql_query($sql);

while ($row = sql_fetch_array($qry)) {
    $mm_order++;
    sql_query("update $mw[menu_middle_table] set mm_order = '$mm_order' where mm_id = '$row[mm_id]'");
} 
if ($mm_order > $max) $max = $mm_order;

if ($w == "up") {
    if ($mm[mm_order] == 1) alert("맨처음입니다.");
    $rup = sql_fetch("select mm_id from $mw[menu_middle_table] where gr_id = '$gr_id' and mm_order < '$mm[mm_order]' order by mm_order desc limit 1");
    $qry = sql_query("update $mw[menu_middle_table] set mm_order = mm_order + 1 where mm_id = '$rup[mm_id]'"); 
    $qry = sql_query("update $mw[menu_middle_table] set mm_order = mm_order - 1 where mm_id = '$mm_id'"); 
}
elseif ($w == "down") {
    if ($mm[mm_order] == $max ) alert("맨마지막입니다.");
    $rdw = sql_fetch("select mm_id from $mw[menu_middle_table] where gr_id = '$gr_id' and mm_order > '$mm[mm_order]' order by mm_order asc limit 1");
    $qry = sql_query("update $mw[menu_middle_table] set mm_order = mm_order + 1 where mm_id = '$mm_id'"); 
    $qry = sql_query("update $mw[menu_middle_table] set mm_order = mm_order - 1 where mm_id = '$rdw[mm_id]'"); 
}

?>
