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
@include_once("$g4[path]/lib/mw.common.lib.php");

// 최신글 추출 (캐쉬적용)
function mw_latest_multi($skin_dir="", $tables, $rows=10, $subject_len=40, $is_img=0, $minute=0)
{
    global $g4, $mw;

    if ($skin_dir)
        $latest_skin_path = "$g4[path]/skin/latest/$skin_dir";
    else
        $latest_skin_path = "$g4[path]/skin/latest/basic";

    $file_tables = implode("-", $tables);

    $cache_file_list = "$g4[path]/data/mw.cache/latest-multi-{$file_tables}-list-{$rows}-{$is_img}-{$subject_len}";
    $cache_file_file = "$g4[path]/data/mw.cache/latest-multi-{$file_tables}-file-{$rows}-{$is_img}-{$subject_len}";
    $cache_file_board = "$g4[path]/data/mw.cache/latest-multi-{$file_tables}-board-{$rows}-{$is_img}-{$subject_len}";

    $board_list = mw_cache_read($cache_file_board, $minute);
    $list = mw_cache_read($cache_file_list, $minute);
    $file = mw_cache_read($cache_file_file, $minute);

    $sql_tables = implode("','", $tables);

    $sql = "select * from {$g4['board_table']} where bo_table in ('{$sql_tables}') ";
    $qry = sql_query($sql);
    while ($row = sql_fetch_array($qry)) {
        $board_list[$row['bo_table']] = $row;
    }

    if ($is_img && !$file) {
        $file = mw_get_last_thumb($tables, $is_img);
        for ($i=0, $m=count($file); $i<$m; ++$i) {
            $row = sql_fetch("select * from {$g4[write_prefix]}{$file[$i]['bo_table']} where wr_id = '{$file[$i]['wr_id']}'");
            $row = mw_get_list($row, $board_list[$file[$i]['bo_table']], $latest_skin_path, $subject_len);

            if ($row['wr_view_block'])
                $file[$i]['path'] = "$latest_skin_path/img/noimage.gif";

            $file[$i]['wr_subject'] = $row['wr_subject'];
            $file[$i]['subject'] = conv_subject($row['wr_subject'], $subject_len, "…");
            $file[$i]['wr_comment'] = $row['wr_comment'];
            $file[$i]['wr_link1'] = $row['wr_link1'];
        }

        if (!count($file)) {
            for ($i=0; $i<$is_img; $i++) {
                $file[$i][path] = "$latest_skin_path/img/noimage.gif";
                $file[$i][subject] = "...";
                $file[$i][href] = "#";
            }   
        }
	mw_cache_write($cache_file_file, $file);
    }

    if (!$list) {
	$list = array();
	$old_board = "";

	$sql = "select n.bo_table, n.wr_id 
		  from $g4[board_new_table] as n, $g4[board_table] as b 
		 where n.bo_table = b.bo_table and wr_id = wr_parent and b.bo_use_search = '1' and n.bo_table in ('$sql_tables')";
        for ($i=0; $i<count($file); $i++) {
            $sql.= " and !(n.bo_table = '{$file[$i][bo_table]}' and n.wr_id = '{$file[$i][wr_id]}') ";
        }
	$sql .= " order by bn_datetime desc limit $rows";
	$qry = sql_query($sql);
	for ($i=0; $row=sql_fetch_array($qry); $i++) {
	    /*if ($old_board != $row[bo_table]) {
		$board = sql_fetch("select * from $g4[board_table] where bo_table = '$row[bo_table]'");
		$old_board = $row[bo_table];
	    }*/
            $board = $board_list[$row['bo_table']];
	    if ($board) {
		$board['bo_use_sideview'] = 1;
		$tmp_write_table = $g4['write_prefix'] . $row[bo_table]; // 게시판 테이블 전체이름

		$sql2 = " select * from $tmp_write_table where wr_id = '$row[wr_id]' ";
		$row2 = sql_fetch($sql2);
		$list[$i] = mw_get_list($row2, $board, $latest_skin_path, $subject_len);
                $list[$i][href] = mw_builder_seo_url($row[bo_table], $row[wr_id]);
		$list[$i][bo_subject] = $board[bo_subject];
		$list[$i][bo_table] = $board[bo_table];
		$list[$i][content] = $list[$i][wr_content] = "";
	    }
	}
	if (!$i) {
	    for ($i=0; $i<$rows; $i++) {
		$board = array();
		$board[bo_subject] = "none";
		$list[$i][bo_subject] = "none";
		$list[$i][subject] = cut_str("게시물이 없어요.", $subject_len);
		$list[$i][href] = "#";
	    }
	}
	else if ($minute > 0) {
	    mw_cache_write($cache_file_list, $list);
	    mw_cache_write($cache_file_board, $board_list);
	}
    }


    ob_start();
    include "$latest_skin_path/latest.skin.php";
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
} 
