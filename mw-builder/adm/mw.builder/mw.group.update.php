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

$sub_menu = "110300";
include_once("_common.php");
include_once("mw.menus.lib.php");

if ($w == 'u')
    check_demo();

auth_check($auth[$sub_menu], "w");

//if ($is_admin != "super" && $w == "") alert("최고관리자만 접근 가능합니다.");

if (!preg_match("/^([A-Za-z0-9_]{1,10})$/", $gr_id))
    alert("그룹 ID는 공백없이 영문자, 숫자, _ 만 사용 가능합니다. (10자 이내)");

if (!$gr_subject) alert("그룹 제목을 입력하세요.");

check_token();

if ($_POST[gr_age] && $_POST[gr_age_type])  {
    $_POST[gr_age] .= $_POST[gr_age_type];
} else {
    $_POST[gr_age] = '';
}

$sql_common = " gr_subject	= '$_POST[gr_subject]',
                gr_sub_domain   = '$_POST[gr_sub_domain]',  
                gr_skin_head    = '$_POST[gr_skin_head]',  
                gr_skin_tail    = '$_POST[gr_skin_tail]',
                gr_skin_home    = '$_POST[gr_skin_home]',
                gr_theme        = '$_POST[gr_theme]',
                gr_cache	= '$_POST[gr_cache]',
                gr_width	= '$_POST[gr_width]',
                gr_mmove	= '$_POST[gr_mmove]',
                gr_smove	= '$_POST[gr_smove]',
                gr_use_not	= '$_POST[gr_use_not]',
                gr_sitemap_not	= '$_POST[gr_sitemap_not]',
                gr_more		= '$_POST[gr_more]',
                gr_more_css	= '$_POST[gr_more_css]',
                gr_only_admin	= '$_POST[gr_only_admin]',
                gr_url		= '$_POST[gr_url]',
                gr_target	= '$_POST[gr_target]',
                gr_level	= '$_POST[gr_level]',
                gr_check	= '$_POST[gr_check]',
                gr_gender	= '$_POST[gr_gender]',
                gr_age	        = '$_POST[gr_age]',
                gr_home_not	= '$_POST[gr_home_not]'";

$sql = " update $g4[group_table]
	    set $sql_common
	  where gr_id = '$_POST[gr_id]' ";
sql_query($sql);

include_once("mw.menus.lib.php");
mw_set_groups();
mw_set_groups_admin();
mw_set_group($gr_id);

goto_url("$mw[admin_path]/mw.group.form.php?w=u&gr_id=$gr_id&$qstr");
