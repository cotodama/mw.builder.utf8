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

if (!defined('_GNUBOARD_')) exit;

@include_once("$g4[path]/lib/mw.cache.lib.php");

// 최신글 추출 (캐쉬적용)
function mw_new($skin_dir="", $rows=10, $subject_len=40, $minute=0)
{
    global $g4;

    if ($skin_dir)
        $latest_skin_path = "$g4[path]/skin/latest/$skin_dir";
    else
        $latest_skin_path = "$g4[path]/skin/latest/basic";

    $cache_file_list = "$g4[path]/data/mw.cache/new_list_{$rows}_{$subject_len}";

    $list = mw_cache_read($cache_file_list, $minute);

    if (!$list) {
	$list = array();
	$old_table = "";
	$sql = "select n.bo_table, n.wr_id, b.bo_subject 
		  from $g4[board_new_table] as n, $g4[board_table] as b 
		 where n.bo_table = b.bo_table and wr_id = wr_parent and b.bo_use_search = '1' 
		 order by bn_datetime desc limit $rows";
	$qry = sql_query($sql);
	for ($i=0; $row=mysql_fetch_array($qry); $i++) {
	    $bo_table = $row[bo_table];
	    $wr_id = $row[wr_id];
	    if ($old_table != $bo_table) {
		$board = sql_fetch("select * from $g4[board_table] where bo_table = '$bo_table'");
		$old_table = $bo_table;
	    }
	    $ro2 = sql_fetch("select * from $g4[write_prefix]$row[bo_table] where wr_id = '$wr_id'");
	    $list[$i] = get_list($ro2, $board, $latest_skin_path, $subject_len);
	    $list[$i][bo_subject] = $row[bo_subject];
	}

	if ($i == 0) {
	    for ($i=0; $i<$rows; $i++) {
		$list[$i][subject] = cut_str("최신글이 없어요.", $subject_len);
	    }
	}
	mw_cache_write($cache_file_list, $list);
    }

    ob_start();
    include "$latest_skin_path/latest.skin.php";
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
} 
?>
