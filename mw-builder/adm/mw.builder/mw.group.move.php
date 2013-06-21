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

$sub_menu = "110300";
include_once("_common.php");
include_once("mw.menus.lib.php");

check_demo();

auth_check($auth[$sub_menu], "w");

$gr_id = $_GET[gr_id];
$row = sql_fetch("select max(gr_order) as max from $g4[group_table]");
$max = $gr_order = $row[max];

$sql = "select * from $g4[group_table] where gr_order = 0 order by gr_id asc";
$qry = sql_query($sql);

while ($row = sql_fetch_array($qry)) {
    $gr_order++;
    sql_query("update $g4[group_table] set gr_order = '$gr_order' where gr_id = '$row[gr_id]'");
} 
if ($gr_order > $max) $max = $gr_order;

if ($w == "up") {
    $row = sql_fetch("select * from $g4[group_table] where gr_id = '$gr_id'");
    if ($row[gr_order] == 1) alert("맨처음입니다.");
    $rup = sql_fetch("select * from $g4[group_table] where gr_order < '$row[gr_order]' order by gr_order desc limit 1");
    $qry = sql_query("update $g4[group_table] set gr_order = gr_order + 1 where gr_id = '$rup[gr_id]'"); 
    $qry = sql_query("update $g4[group_table] set gr_order = gr_order - 1 where gr_id = '$gr_id'"); 
}
elseif ($w == "down") {
    $row = sql_fetch("select * from $g4[group_table] where gr_id = '$gr_id'");
    if ($row[gr_order] == $max ) alert("맨마지막입니다.");
    $rdw = sql_fetch("select * from $g4[group_table] where gr_order > '$row[gr_order]' order by gr_order asc limit 1");
    $qry = sql_query("update $g4[group_table] set gr_order = gr_order + 1 where gr_id = '$gr_id'"); 
    $qry = sql_query("update $g4[group_table] set gr_order = gr_order - 1 where gr_id = '$rdw[gr_id]'"); 
}

?>
