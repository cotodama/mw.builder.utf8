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

if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

@include_once("$g4[path]/lib/mw.cache.lib.php");
@include_once("$g4[path]/lib/mw.host.lib.php");

function mw_get_group_row($row) {
    global $g4, $mw;
    $mw_group = array();
    $mw_group = $row;
    //$mw_group[gr_target] = "_top";
    $mw_group[gr_url] = $row[gr_sub_domain] && !$mw[config][cf_sub_domain_off] ? "$g4[url]/" : "$g4[url]/?mw_main=$row[gr_id]";
    if ($row[gr_mmove]) { // 첫번째 중메뉴로 바로 이동
	$row2 = sql_fetch("select mm_id from $mw[menu_middle_table] where gr_id = '$row[gr_id]' order by mm_order limit 1");
	$mw_group[gr_url] .= $row[gr_sub_domain] && !$mw[config][cf_sub_domain_off] ? "?mw_menu=$row2[mm_id]" : "&mw_menu=$row2[mm_id]";
    }
    elseif ($row[gr_smove]) { // 첫번째 소메뉴로 바로 이동
	$row2 = sql_fetch("select * from $mw[menu_middle_table] where gr_id = '$row[gr_id]' order by mm_order limit 1");
	$row2 = sql_fetch("select * from $mw[menu_small_table] where mm_id = '$row2[mm_id]' order by ms_order limit 1");
	$mw_group[gr_url] = "$g4[url]/$g4[bbs]/board.php?bo_table=$row2[bo_table]";
	if ($row2[ca_name]) $mw_group[gr_url] .= "&sca=".urlencode($row2[ca_name]);
	if ($row2[pg_id]) $mw_group[gr_url] = "$g4[url]/page.php?pg_id=$row2[pg_id]";
	if ($row2[ms_url]) $mw_group[gr_url] = "$g4[url]$row2[ms_url]";
    }
    if ($row[gr_sub_domain] && !$mw[config][cf_sub_domain_off]) // 서브도메인 적용
	$mw_group[gr_url] = mw_sub_domain_url($row[gr_sub_domain], $mw_group[gr_url]);
    if ($row[gr_url]) { // 외부링크
	$mw_group[gr_url] = $row[gr_url];
	//$mw_group[gr_target] = "_blank";
    }

    return $mw_group;
}

function mw_get_middle_menu_row($row) {
    global $g4, $mw;
    $mw_mmenu = array();
    $mw_mmenu = $row;
    $mw_mmenu[mm_url] = "$g4[url]/?mw_main=$row[gr_id]&mw_menu=$row[mm_id]";
    if ($row[mm_smove]) { // 첫번째 소메뉴로 바로 이동
	$row2 = sql_fetch("select * from $mw[menu_small_table] where mm_id = '$row[mm_id]' order by ms_order limit 1");
	$mw_mmenu[mm_url] = "$g4[url]/$g4[bbs]/board.php?bo_table=$row2[bo_table]";
	if ($row2[ca_name]) $mw_mmenu[mm_url] .= "&sca=".urlencode($row2[ca_name]);
	if ($row2[pg_id]) $mw_mmenu[mm_url] = "$g4[url]/page.php?pg_id=$row2[pg_id]";
	if ($row2[ms_url]) $mw_mmenu[mm_url] = "$g4[url]$row2[ms_url]";
    }
    //if ($row[mm_url]) $mw_mmenu[mm_url] = $row[mm_url];
    $row2 = sql_fetch("select * from $g4[group_table] where gr_id = '$row[gr_id]'");
    if ($row2[gr_sub_domain] && !$mw[config][cf_sub_domain_off]) // 서브도메인 적용
	$mw_mmenu[mm_url] = mw_sub_domain_url($row2[gr_sub_domain], $mw_mmenu[mm_url]);
    if ($row[mm_out_url]) // 외부링크
	$mw_mmenu[mm_url] = $row[mm_out_url];

    return $mw_mmenu;
}

function mw_get_small_menu_row($row) {
    global $g4, $mw;
    $mw_smenu = array();
    $mw_smenu = $row;
    $mw_smenu[ms_url] = "$g4[url]/$g4[bbs]/board.php?bo_table=$row[bo_table]";
    if ($row[ca_name])
	$mw_smenu[ms_url] .= "&sca=".urlencode($row[ca_name]);
    if ($row[pg_id])
        $mw_smenu[ms_url] = "$g4[url]/page.php?pg_id=$row[pg_id]";
    if ($row[ms_url])
	$mw_smenu[ms_url] = "$g4[url]$row[ms_url]";
    $row2 = sql_fetch("select gr_id from $mw[menu_middle_table] where mm_id = '$row[mm_id]'");
    $row3 = sql_fetch("select * from $g4[group_table] where gr_id = '$row2[gr_id]'");
    if ($row3[gr_sub_domain] && !$mw[config][cf_sub_domain_off]) // 서브도메인 적용
	$mw_smenu[ms_url] = mw_sub_domain_url($row3[gr_sub_domain], $mw_smenu[ms_url]);
    if ($row[ms_out_url]) // 외부링크
	$mw_smenu[ms_url] = $row[ms_out_url];

    return $mw_smenu;
}

function mw_set_group($gr_id) { 
    global $g4, $mw, $is_admin;
    $cache_file = "$g4[path]/data/mw.cache/group-$gr_id";
    $mw_group = array();
    //$sql = "select * from $g4[group_table] where gr_use_not = '' and gr_id = '$gr_id'";
    $sql = "select * from $g4[group_table] where gr_id = '$gr_id'";
    $row = sql_fetch($sql);
    $mw_group = mw_get_group_row($row); 
    mw_cache_write($cache_file, $mw_group);
}

function mw_set_middle_menu($mm_id) {
    global $g4, $mw;
    $cache_file = "$g4[path]/data/mw.cache/mmenu-$mm_id";
    $sql = "select * from $mw[menu_middle_table] where mm_id = '$mm_id'";
    $row = sql_fetch($sql);
    $mw_mmenu = mw_get_middle_menu_row($row); 
    mw_cache_write($cache_file, $mw_mmenu);
}

function mw_set_small_menu($ms_id) {
    global $g4, $mw;
    $cache_file = "$g4[path]/data/mw.cache/smenu-$ms_id";
    $sql = "select * from $mw[menu_small_table] where ms_id = '$ms_id'";
    $row = sql_fetch($sql);
    $mw_smenu = mw_get_small_menu_row($row); 
    mw_cache_write($cache_file, $mw_smenu);
}

function mw_set_groups() { 
    global $g4, $mw, $is_admin;
    $cache_file = "$g4[path]/data/mw.cache/groups";
    $mw_groups = array();
    //$sql = "select * from $g4[group_table] where gr_use_not = '' and gr_more = '' and gr_only_admin = '' order by gr_order, gr_id";
    $sql = "select * from $g4[group_table] where gr_use_not = '' and gr_only_admin = '' order by gr_order, gr_id";
    $qry = sql_query($sql, false);
    for ($i=0; $row = sql_fetch_array($qry); $i++)
	$mw_groups[$i] = mw_get_group_row($row);
    mw_cache_write($cache_file, $mw_groups);
    return $mw_groups;
}

function mw_set_groups_admin() { 
    global $g4, $mw, $is_admin;
    $cache_file = "$g4[path]/data/mw.cache/groups-admin";
    $mw_groups = array();
    $sql = "select * from $g4[group_table] where gr_use_not = '' order by gr_order, gr_id";
    $qry = sql_query($sql, false);
    for ($i=0; $row = sql_fetch_array($qry); $i++)
	$mw_groups[$i] = mw_get_group_row($row);
    mw_cache_write($cache_file, $mw_groups);
    return $mw_groups;
}

function mw_set_middle_menus($mg_id) {
    global $g4, $mw;
    $cache_file = "$g4[path]/data/mw.cache/mmenus-$mg_id";
    $mw_mmenus = array();
    $sql = "select * from $mw[menu_middle_table] where gr_id = '$mg_id' and mm_only_admin = '' and mm_use_not = '' order by mm_order, mm_id";
    $qry = sql_query($sql, false);
    for ($i=0; $row = sql_fetch_array($qry); $i++) {
	$mw_mmenus[$i] = mw_get_middle_menu_row($row);
    }
    mw_cache_write($cache_file, $mw_mmenus);
    return $mw_mmenus;
}

function mw_set_middle_menus_admin($mg_id) {
    global $g4, $mw;
    $cache_file = "$g4[path]/data/mw.cache/mmenus-$mg_id-admin";
    $mw_mmenus = array();
    $sql = "select * from $mw[menu_middle_table] where gr_id = '$mg_id' and mm_use_not = '' order by mm_order, mm_id";
    $qry = sql_query($sql, false);
    for ($i=0; $row = sql_fetch_array($qry); $i++) {
	$mw_mmenus[$i] = mw_get_middle_menu_row($row);
    }
    mw_cache_write($cache_file, $mw_mmenus);
    return $mw_mmenus;
}

function mw_set_small_menus($mm_id) {
    global $g4, $mw;
    $cache_file = "$g4[path]/data/mw.cache/smenus-$mm_id";
    $mw_smenus = array();
    $sql = "select * from $mw[menu_small_table] where mm_id = '$mm_id' and ms_only_admin = '' and ms_use_not = '' order by ms_order, ms_id";
    $qry = sql_query($sql, false);
    for ($i=0; $row = sql_fetch_array($qry); $i++) {
	$mw_smenus[$i] = mw_get_small_menu_row($row);
    }
    mw_cache_write($cache_file, $mw_smenus);
    return $mw_smenus;
}

function mw_set_small_menus_admin($mm_id) {
    global $g4, $mw;
    $cache_file = "$g4[path]/data/mw.cache/smenus-$mm_id-admin";
    $mw_smenus = array();
    $sql = "select * from $mw[menu_small_table] where mm_id = '$mm_id' and ms_use_not = '' order by ms_order, ms_id";
    $qry = sql_query($sql, false);
    for ($i=0; $row = sql_fetch_array($qry); $i++) {
	$mw_smenus[$i] = mw_get_small_menu_row($row);
    }
    mw_cache_write($cache_file, $mw_smenus);
    return $mw_smenus;
}

// 그룹삭제시 속해있는 중메뉴, 소메뉴 삭제
$g = array();
$m = array();

$sql = "select gr_id from $g4[group_table]";
$qry = sql_query($sql);
while ($row = sql_fetch_array($qry)) $g[] = $row[gr_id];

$sql = "select mm_id from $mw[menu_middle_table] where gr_id not in ('".implode("','", $g)."')";
$qry = sql_query($sql);
while ($row = sql_fetch_array($qry)) $m[] = $row[mm_id];

$sql = "delete from $mw[menu_small_table] where mm_id in ('".implode("','", $m)."')";
$qry = sql_query($sql);

$sql = "delete from $mw[menu_middle_table] where mm_id in ('".implode("','", $m)."')";
$qry = sql_query($sql);

sql_query("alter table $g4[group_table] add gr_target varchar(20) not null after gr_url", false);
sql_query("alter table $mw[menu_middle_table] add mm_target  varchar(20) not null after mm_out_url", false);
sql_query("alter table $mw[menu_middle_table] add mm_use_not char(1) not null", false);
sql_query("alter table $mw[menu_small_table] add ms_use_not char(1) not null", false);
