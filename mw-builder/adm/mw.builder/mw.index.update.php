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

$sub_menu = "110250";
include_once("./_common.php");

check_demo();

auth_check($auth[$sub_menu], "w");

check_token();

$sql = " update $mw[config_table]
            set	cf_index_skin_head  = '$cf_index_skin_head',
		cf_index_skin_main  = '$cf_index_skin_main',
		cf_index_skin_tail  = '$cf_index_skin_tail',
		cf_index_cache	    = '$cf_index_cache',
		cf_index_width	    = '$cf_index_width' ";
sql_query($sql);

goto_url("./mw.index.form.php");
?>
