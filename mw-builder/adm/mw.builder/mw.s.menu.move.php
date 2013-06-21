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

$sub_menu = "110500";
include_once("_common.php");
include_once("mw.menus.lib.php");

check_demo();

auth_check($auth[$sub_menu], "w");

$ms_id = $_GET[ms_id];

$ms = sql_fetch("select * from $mw[menu_small_table] where ms_id = '$ms_id'");
$mm_id = $ms[mm_id];
 
$row = sql_fetch("select max(ms_order) as max from $mw[menu_small_table] where mm_id = '$mm_id'");
$max = $ms_order = $row[max];

$sql = "select * from $mw[menu_small_table] where mm_id = '$mm_id' and ms_order = 0 order by ms_id asc";
$qry = sql_query($sql);

while ($row = sql_fetch_array($qry)) {
    $ms_order++;
    sql_query("update $mw[menu_small_table] set ms_order = '$ms_order' where ms_id = '$row[ms_id]'");
} 
if ($ms_order > $max) $max = $ms_order;

if ($w == "up") {
    if ($ms[ms_order] == 1) alert("맨처음입니다.");
    $rup = sql_fetch("select ms_id from $mw[menu_small_table] where mm_id = '$mm_id' and ms_order < '$ms[ms_order]' order by ms_order desc limit 1");
    $qry = sql_query("update $mw[menu_small_table] set ms_order = ms_order + 1 where ms_id = '$rup[ms_id]'"); 
    $qry = sql_query("update $mw[menu_small_table] set ms_order = ms_order - 1 where ms_id = '$ms_id'"); 
}
elseif ($w == "down") {
    if ($ms[ms_order] == $max ) alert("맨마지막입니다.");
    $rdw = sql_fetch("select ms_id from $mw[menu_small_table] where mm_id = '$mm_id' and ms_order > '$ms[ms_order]' order by ms_order asc limit 1");
    $qry = sql_query("update $mw[menu_small_table] set ms_order = ms_order + 1 where ms_id = '$ms_id'"); 
    $qry = sql_query("update $mw[menu_small_table] set ms_order = ms_order - 1 where ms_id = '$rdw[ms_id]'"); 
}

?>
