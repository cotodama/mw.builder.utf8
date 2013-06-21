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

if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

function mw_get_menus_file($cache_file) {
    ob_start();
    readfile($cache_file);
    $content = ob_get_contents();
    ob_end_clean();

    $content = base64_decode($content);
    $content = unserialize($content);

    return $content;
}

function mw_get_groups() { // 그룹 정보 얻음
    global $g4, $is_admin;

    $cache_file = "$g4[path]/data/mw.cache/groups";
    if ($is_admin)
	$cache_file .= "-admin";

    return mw_get_menus_file($cache_file);
}

function mw_get_middle_menus($mg_id) { // 중메뉴 정보 얻음
    global $g4, $is_admin;

    $cache_file = "$g4[path]/data/mw.cache/mmenus-$mg_id";
    if ($is_admin)
	$cache_file .= "-admin";

    return mw_get_menus_file($cache_file);
}

function mw_get_small_menus($mm_id) { // 소메뉴 정보 얻음
    global $g4, $is_admin;

    $cache_file = "$g4[path]/data/mw.cache/smenus-$mm_id";
    if ($is_admin)
	$cache_file .= "-admin";

    return mw_get_menus_file($cache_file);
}

function mw_get_group($gr_id) { // 그룹 정보 얻음
    global $g4, $is_admin;

    $cache_file = "$g4[path]/data/mw.cache/group-$gr_id";
    return mw_get_menus_file($cache_file);
}

function mw_get_middle_menu($mm_id) { // 중메뉴 정보 얻음
    global $g4;

    $cache_file = "$g4[path]/data/mw.cache/mmenu-$mm_id";
    return mw_get_menus_file($cache_file);
}

function mw_get_small_menu($ms_id) { // 소메뉴 정보 얻음
    global $g4;

    $cache_file = "$g4[path]/data/mw.cache/smenu-$ms_id";
    return mw_get_menus_file($cache_file);
}


function is_member_page() { // 회원관련 페이지인지 검사
    global $g4, $config;
    $mpage = array(
	"/$g4[bbs]/login.php"
	//,"/$g4[bbs]/login_check.php"
	//,"/$g4[bbs]/logout.php"
	,"/$g4[bbs]/log"
	,"/$g4[bbs]/register"
	//,"/$g4[bbs]/register.php"
	//,"/$g4[bbs]/register_form.php"
	//,"/$g4[bbs]/register_form_update.php"
	//,"/$g4[bbs]/register_result.php"
	,"/$g4[bbs]/member_confirm.php"
	,"/$g4[bbs]/kcaptcha"
	,"/skin/member"
    );
    $is_member_page = false;
    for ($i=0, $max=count($mpage); $i<$max; $i++) {
        if (strstr($_SERVER[PHP_SELF], $mpage[$i])) {
	    $is_member_page = true;
	    break;
	}
    }
    return $is_member_page;
}

?>
