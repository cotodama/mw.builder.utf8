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

if (!defined('_GNUBOARD_')) exit;

@include_once("$g4[path]/lib/mw.cache.lib.php");

// 최신글 추출 (캐쉬적용)
function mw_latest_tab($skin_dir="", $bo_tables, $rows=10, $subject_len=40, $minute=0)
{
    global $g4, $mw;

    if ($skin_dir)
        $latest_skin_path = "$g4[path]/skin/latest/$skin_dir";
    else
        $latest_skin_path = "$g4[path]/skin/latest/basic";

    $tmp_tables = explode(",", $bo_tables);
    $bo_tables = array();
    for ($i=0, $j=0; $i<count($tmp_tables); $i++) {
	$tmp_tables[$i] = trim($tmp_tables[$i]);
	if (!$tmp_tables[$i]) continue;
	$bo_tables[$j++] = $tmp_tables[$i];
    }
    $sql_tables = implode("','", $bo_tables);
    $file_tables = implode("-", $bo_tables);

    $cache_file_tab = "$g4[path]/data/mw.cache/latest-tab-{$file_tables}-list-{$rows}-{$subject_len}";
    $tab = mw_cache_read($cache_file_tab, $minute);

    if (!$tab) {
	$tab = array();
	for ($i=0; $i<count($bo_tables); $i++) {
	    $list = array();
	    $bo_table = $bo_tables[$i];
	    $tmp_write_table = $g4['write_prefix'] . $bo_table;
	    $board = sql_fetch("select * from $g4[board_table] where bo_table = '$bo_table'");
	    $sql = "select * from $tmp_write_table where wr_is_comment = 0 order by wr_num asc limit $rows";
	    $qry = sql_query($sql);
	    for ($j=0; $row=sql_fetch_array($qry); $j++) {
		$list[$j] = mw_get_list($row, $board, $latest_skin_path, $subject_len);
		$list[$j][content] = $list[$i][wr_content] = "";
                $list[$j][href] = "$g4[bbs_path]/board.php?bo_table=$bo_table&wr_id={$list[$j][wr_id]}";
	    }
	    if (!$j) {
		for ($j=0; $j<$rows; $j++) {
		    if (!$board) {
			$board = array();
			$board[bo_subject] = "none";
		    }
		    $list[$j][bo_subject] = $board[bo_subject];
		    $list[$j][subject] = cut_str("게시물이 없어요.", $subject_len);
		    $list[$j][href] = "#";
		}
	    }
	    $tab[$bo_table] = $list;
	    $tab[$bo_table]['board'] = $board;
	}
	mw_cache_write($cache_file_tab, $tab);
    }

    ob_start();
    include "$latest_skin_path/latest.skin.php";
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
} 
