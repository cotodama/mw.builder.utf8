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

include_once("$g4[path]/lib/mw.common.lib.php");
include_once("$g4[path]/lib/outlogin.lib.php");
include_once("$g4[path]/lib/poll.lib.php");
include_once("$g4[path]/lib/visit.lib.php");
include_once("$g4[path]/lib/connect.lib.php");
include_once("$g4[path]/lib/popular.lib.php");
include_once("$g4[path]/lib/mw.host.lib.php");
include_once("$g4[path]/lib/mw.latest.lib.php");
include_once("$g4[path]/lib/mw.latest.main.lib.php");
include_once("$g4[path]/lib/mw.latest.rand.lib.php");
include_once("$g4[path]/lib/mw.latest.group.lib.php");
include_once("$g4[path]/lib/mw.latest.multi.lib.php");
include_once("$g4[path]/lib/mw.latest.tab.lib.php");
include_once("$g4[path]/lib/mw.popular.lib.php");
include_once("$g4[path]/lib/mw.new.lib.php");
include_once("$g4[path]/lib/mw.popup.lib.php");
include_once("$g4[path]/lib/mw.string.lib.php");
include_once("$g4[path]/lib/mw.menus.lib.php");
include_once("$g4[path]/lib/mw.age.lib.php");

if (defined("_MW_INDEX_")) {
    if ($_GET['mw_main']) $mg_id = $_GET['mw_main'];
    if ($_GET['mw_menu']) $mm_id = $_GET['mw_menu'];
}

// 서브도메인 얻음
$sub_domain = mw_get_sub_domain();

// 인덱스 URL
if ($mw[config][cf_www])
    $mw[index_url] = mw_sub_domain_url("www", $g4[url]);
else
    $mw[index_url] = $g4[url];

if ($mw[config][cf_default_group] && !is_member_page() && !$gr_id && !$mg_id) {
    $gr_id = $mw[config][cf_default_group];
    $mg_id = $gr_id;
    $group = mw_get_group($mg_id); 
}

// 그룹 목록 및 갯수
$mw_groups = mw_get_groups();
$mw_groups_count = sizeof($mw_groups);

$mw_groups_sitemap = $mw_groups_more = $mw_groups_head = array();
for ($i=0; $i<$mw_groups_count; $i++) {
    if (!$mw_groups[$i][gr_sitemap_not]) // 사이트맵 출력 안함
        $mw_groups_sitemap[] = $mw_groups[$i];
    if ($mw_groups[$i][gr_more]) // 더보기로 출력
        $mw_groups_more[] = $mw_groups[$i];
    else // 상단 그룹 출력값
	$mw_groups_head[] = $mw_groups[$i];
}

$mw_groups_more_count = sizeof($mw_groups_more);
$mw_groups_head_count = sizeof($mw_groups_head);
$mw_groups_sitemap_count = sizeof($mw_groups_sitemap);

if ($mw_groups_more_count) {
    $mw_groups_head[$mw_groups_head_count] = array();
    $mw_groups_head[$mw_groups_head_count][gr_subject] = "<span id='group-more-button'>더보기 ↓</span>";
    $mw_groups_head[$mw_groups_head_count][gr_url] = "#;\" onclick=\"javascript:group_more();\"";
    $mw_groups_head_count++;
}

// 서브도메인이 있는 경우 서브도메인으로 그룹ID 얻기
if ($sub_domain && $sub_domain != "www") {
    for ($i=0; $i<$mw_groups_count; $i++) {
	if ($mw_groups[$i][gr_sub_domain] == $sub_domain) {
	    $mg_id = $mw_groups[$i][gr_id];
	    break;
	}
    }
}

// 사용자 정의 URL
if (!defined("_MW_INDEX_") && !$bo_table && !$pg_id) {
    $ms_id = "";
    $sql = "select * from $mw[menu_small_table] where ms_url <> '' order by ms_order";
    $qry = sql_query($sql);
    while ($row = sql_fetch_array($qry)) {
	if (strstr($_SERVER[PHP_SELF], $row[ms_url])) {
	    $mw_smenu = $row;
	    $ms_id = $row[ms_id];
	    break;
	}
	if (strstr($_SERVER[REQUEST_URI], $row[ms_url])) {
	    $mw_smenu = $row;
	    $ms_id = $row[ms_id];
	    break;
	}
    }
    if ($ms_id) {
	$mm_id = $mw_smenu[mm_id];
	$mw_mmenu = mw_get_middle_menu($mm_id);
	$mg_id = $mw_mmenu[gr_id];
	$group = mw_get_group($mg_id); 
    }
}

// 배추빌더 page 기능
if ($pg_id) {
    $ms_id = "";
    $row = sql_fetch("select * from $mw[menu_small_table] where pg_id = '$pg_id' order by ms_id");
    if ($row) {
        $mw_smenu = $row;
        $ms_id = $row[ms_id];
    }
    if ($ms_id) {
	$mm_id = $mw_smenu[mm_id];
	$mw_mmenu = mw_get_middle_menu($mm_id);
	$mg_id = $mw_mmenu[gr_id];
	$group = mw_get_group($mg_id); 
    }
}

if ($mg_id && !$group) {
    if (!$gr_id) $gr_id = $mg_id;
    $group = mw_get_group($mg_id);
}

if ($bo_table && !$mm_id) {
    $sql = "select * from $mw[menu_small_table] where bo_table = '$bo_table' and ca_name = '$sca' order by ms_order limit 1";
    $row = sql_fetch($sql, false);
    if (!$row) {
	$sql = "select * from $mw[menu_small_table] where bo_table = '$bo_table' order by ms_order limit 1";
	$row = sql_fetch($sql, false);
    }
    $mm_id = $row[mm_id];
    $ms_id = $row[ms_id];
    if ($mm_id) {
	$mw_smenus = mw_get_small_menus($mm_id);
	$mw_mmenu = mw_get_middle_menu($mm_id);
	$mw_smenu = mw_get_small_menu($ms_id);
    }
    if (!$mg_id) $mg_id = $gr_id;
}

if ($mg_id) {
    // 첫번째 중메뉴로 바로 이동
    if (defined("_MW_INDEX_") && $group[gr_mmove] && !$mm_id) {
	goto_url2($group[gr_url]);
    }
    // 첫번째 소메뉴로 바로 이동
    elseif (defined("_MW_INDEX_") && $group[gr_smove] && !$mm_id) {
	goto_url2($group[gr_url]);
    }
    for ($i=0; $i<$mw_groups_count; $i++) {
	if ($mg_id == $mw_groups[$i][gr_id]) {
	    $group[gr_url] = $mw_groups[$i][gr_url];
	    break;
	}
    }
    $mw_mmenus = mw_get_middle_menus($mg_id);
}

if ($mm_id) {
    if (!$mw_mmenu)
	$mw_mmenu = mw_get_middle_menu($mm_id);
    // 첫번째 소메뉴로 바로 이동
    if (defined("_MW_INDEX_") && $mw_mmenu[mm_smove]) {
	goto_url2($mw_mmenu[mm_url]);
    }
    if (!$mw_smenus)
        $mw_smenus = mw_get_small_menus($mm_id);
}

if (is_member_page() && $mw[config][cf_member] && !$mw[config][cf_sub_domain_off]) { // 회원페이지 member 서브도메인 접속
    mw_sub_domain_only("member");
} else if ($group[gr_sub_domain] && !$mw[config][cf_sub_domain_off]) { // 그룹별 서브도메인 접속
    mw_sub_domain_only($group[gr_sub_domain]);
} else if ($mw[config][cf_www]) { // www 로만 접속
    mw_sub_domain_only("www");
}

if ($group[gr_level] > $member[mb_level]) { alert("접근권한이 없습니다."); }
if ($mw_mmenu[mm_level] > $member[mb_level]) { alert("접근권한이 없습니다."); }
if ($mw_smenu[ms_level] > $member[mb_level]) { alert("접근권한이 없습니다."); }

if ($group[gr_check] && $is_admin != 'super') { alert("점검중 입니다."); }
if ($mw_mmenu[mm_check] && $is_admin != 'super') { alert("점검중 입니다."); }
if ($mw_smenu[ms_check] && $is_admin != 'super') { alert("점검중 입니다."); }

if (!$is_admin && $group[gr_gender] && $group[gr_gender] == 'M' && $member[mb_sex] != 'M') { alert("남자만 접근 가능합니다."); }
if (!$is_admin && $group[gr_gender] && $group[gr_gender] == 'F' && $member[mb_sex] != 'F') { alert("여자만 접근 가능합니다."); }
if (!$is_admin && $mw_mmenu[mm_gender] && $mw_mmenu[mm_gender] == 'M' && $member[mb_sex] != 'M') { alert("남자만 접근 가능합니다."); }
if (!$is_admin && $mw_mmenu[mm_gender] && $mw_mmenu[mm_gender] == 'F' && $member[mb_sex] != 'F') { alert("여자만 접근 가능합니다."); }
if (!$is_admin && $mw_smenu[ms_gender] && $mw_smenu[ms_gender] == 'M' && $member[mb_sex] != 'M') { alert("남자만 접근 가능합니다."); }
if (!$is_admin && $mw_smenu[ms_gender] && $mw_smenu[ms_gender] == 'F' && $member[mb_sex] != 'F') { alert("여자만 접근 가능합니다."); }

if (!$is_admin) {
    mw_age($group[gr_age]);
    mw_age($mw_mmenu[mm_age]);
    mw_age($mw_smenu[ms_age]);
}

$mw_index_skin_head_path = "$g4[path]/skin/mw.builder/{$mw[config][cf_index_skin_head]}";
$mw_index_skin_main_path = "$g4[path]/skin/mw.builder/{$mw[config][cf_index_skin_main]}";
$mw_index_skin_tail_path = "$g4[path]/skin/mw.builder/{$mw[config][cf_index_skin_tail]}";

if ($mg_id) {
    $mw_group_skin_head_path = "$g4[path]/skin/mw.builder/{$group[gr_skin_head]}";
    $mw_group_skin_home_path = "$g4[path]/skin/mw.builder/{$group[gr_skin_home]}";
    $mw_group_skin_tail_path = "$g4[path]/skin/mw.builder/{$group[gr_skin_tail]}";
    $mw_mmenu_skin_path = "$g4[path]/skin/mw.builder/{$mw_mmenu[mm_skin]}";
} else {
    $mw_member_skin_head_path = "$g4[path]/skin/mw.builder/{$mw[config][cf_member_skin_head]}";
    $mw_member_skin_tail_path = "$g4[path]/skin/mw.builder/{$mw[config][cf_member_skin_tail]}";
}

if (!$group[gr_theme])
    $group[gr_theme] = "mw.blue";

if (!$g4['title']) {
    if ($ms_id) {
        if ($mw_smenu['ms_name'])
            $g4['title'] = $mw_smenu['ms_name'];
    }

    if ($mm_id && $mw_smenu['ms_name'] != $mw_mmenu['mm_name']) {
        if ($g4['title']) $g4['title'] .= " - ";
        if ($mw_mmenu['mm_name'])
            $g4['title'] .= $mw_mmenu['mm_name'];
    }

    if ($mg_id) {
        if ($g4['title']) $g4['title'] .= " - ";
        if ($group['gr_subject'])
            $g4['title'] .= $group['gr_subject'];
    }
}

