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

$sub_menu = "110550";
include_once("./_common.php");
include_once("mw.menus.lib.php");

check_demo();

auth_check($auth[$sub_menu], "w");

$sql = "select gr_id from $g4[group_table] order by gr_order";
$qry = sql_query($sql);
while ($row = sql_fetch_array($qry)) {
    mw_set_group($row[gr_id]);
    mw_set_middle_menus($row[gr_id]);
    mw_set_middle_menus_admin($row[gr_id]);
}
mw_set_groups();
mw_set_groups_admin();
//mw_set_groups_more();
//mw_set_groups_more_admin();

$sql = "select mm_id from $mw[menu_middle_table] where mm_id <> '' order by gr_id, mm_order";
$qry = sql_query($sql);
while ($row = sql_fetch_array($qry)) {
    mw_set_middle_menu($row[mm_id]);
    mw_set_small_menus($row[mm_id]);
    mw_set_small_menus_admin($row[mm_id]);
}

$sql = "select ms_id from $mw[menu_small_table] order by mm_id, ms_order";
$qry = sql_query($sql);
while ($row = sql_fetch_array($qry)) {
    mw_set_small_menu($row[ms_id]);
}

$g4[title] = "전체메뉴캐쉬생성";
include_once("$g4[admin_path]/admin.head.php");

if ($is_cache_delete)
    echo "<script>document.getElementById('ct').innerHTML += '<br><br>메뉴캐쉬 생성완료.';</script>\n";
else
    echo "메뉴캐쉬 생성완료";

include_once("$g4[admin_path]/admin.tail.php");
?>
