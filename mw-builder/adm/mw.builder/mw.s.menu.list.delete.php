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

check_demo();

auth_check($auth[$sub_menu], "d");

check_token();

for ($i=0; $i<count($chk); $i++) {
    // 실제 번호를 넘김
    $k = $chk[$i];

    sql_query(" delete from $mw[menu_small_table] where ms_id = '{$_POST[ms_id][$k]}' ");
    mw_set_small_menu($_POST[ms_id][$k]);
}
mw_set_small_menus($mm_id);

goto_url("$mw[admin_path]/mw.s.menu.php?mm_id={$_POST[mm_id]}$qstr");

