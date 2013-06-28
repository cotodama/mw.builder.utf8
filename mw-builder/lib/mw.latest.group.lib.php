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
function mw_latest_group($skin_dir="", $gr_id, $rows=10, $subject_len=40, $is_img=0, $minute=0)
{
    global $g4, $mw;

    if ($skin_dir)
        $latest_skin_path = "$g4[path]/skin/latest/$skin_dir";
    else
        $latest_skin_path = "$g4[path]/skin/latest/basic";

    $cache_file_list = "$g4[path]/data/mw.cache/latest-{$gr_id}-list-{$rows}-{$is_img}-{$subject_len}";
    $cache_file_file = "$g4[path]/data/mw.cache/latest-{$gr_id}-file-{$rows}-{$is_img}-{$subject_len}";
    $cache_file_board = "$g4[path]/data/mw.cache/latest-{$gr_id}-board-{$rows}-{$is_img}-{$subject_len}";

    $board = mw_cache_read($cache_file_board, $minute);
    $list = mw_cache_read($cache_file_list, $minute);
    $file = mw_cache_read($cache_file_file, $minute);

    $tables = array();
    $sql = "select mm_id from $mw[menu_middle_table] where gr_id = '$gr_id' order by mm_order";
    $qry = sql_query($sql);
    while ($row = sql_fetch_array($qry)) {
	$sql2 = "select * from $mw[menu_small_table] where mm_id = '$row[mm_id]' and bo_table <> '' order by ms_order";
	$qry2 = sql_query($sql2);
	while ($row2 = sql_fetch_array($qry2)) {
	    $tables[] = $row2[bo_table];
	}
    }
    $sql_tables = implode("','", $tables);

    if ($is_img && !$file) {
	$file = array();
	/*$sql = "select f.bo_table, f.wr_id, f.bf_file 
		  from $g4[board_file_table] as f, $g4[board_table] as b 
		 where f.bo_table = b.bo_table and b.bo_use_search = '1' and f.bo_table in ('$sql_tables') and bf_type > 0 and bf_type < 4 and bf_no = 0 
		 order by bf_datetime desc limit $is_img";
	$qry = sql_query($sql);
	for ($i=0; $row=sql_fetch_array($qry); $i++) {
	    $file[$i] = array();
	    $file[$i][wr_id] = $row[wr_id];
	    $file[$i][path] = "$g4[path]/data/file/$row[bo_table]/thumbnail/$row[wr_id]";
	    //$file[$i][href] = "$g4[bbs_path]/board.php?bo_table=$row[bo_table]&wr_id=$row[wr_id]";
	    $file[$i][href] = "$g4[url]/$g4[bbs]/board.php?bo_table=$row[bo_table]&wr_id=$row[wr_id]";
	    if (!@file_exists($file[$i][path])) $file[$i][path] = "$g4[path]/data/file/$row[bo_table]/thumb/$row[wr_id]";
	    if (!@file_exists($file[$i][path])) $file[$i][path] = "$g4[path]/data/file/$row[bo_table]/$row[bf_file]";
	    if (!@file_exists($file[$i][path])) $file[$i][path] = "$latest_skin_path/img/noimage.gif";
	    if (!@file_exists($file[$i][path])) $file[$i] = null;
	    if (@is_dir($file[$i][path])) $file[$i] = null;
	    if ($file[$i]) {
		$row2 = sql_fetch("select wr_subject, wr_comment from $g4[write_prefix]$row[bo_table] where wr_id = '$row[wr_id]'");
		$file[$i][subject] = conv_subject($row2[wr_subject], $subject_len, "…");
		$file[$i][wr_comment] = $row2[wr_comment];
	    }
	}*/
        $file = mw_get_last_thumb($tables, $is_img);
        for ($i=0, $m=count($file); $i<$m; ++$i) {
            $row = sql_fetch("select wr_subject, wr_comment, wr_link1 from {$g4['write_prefix']}{$file[$i]['bo_table']} where wr_id = '{$file[$i]['wr_id']}'");
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

	$noids = array();
	for ($i=0; $i<count($file); $i++) {
	    $noids[] = $file[$i][wr_id];
	}
	$noids = implode("','", $noids);

	$sql = "select n.bo_table, n.wr_id 
		  from $g4[board_new_table] as n, $g4[board_table] as b 
		 where n.bo_table = b.bo_table and wr_id = wr_parent and b.bo_use_search = '1' and n.bo_table in ('$sql_tables') and n.wr_id not in ('$noids')
		 order by bn_datetime desc limit $rows";
	$qry = sql_query($sql);
	for ($i=0; $row=sql_fetch_array($qry); $i++) {
	    if ($old_board != $row[bo_table]) {
		$board = sql_fetch("select * from $g4[board_table] where bo_table = '$row[bo_table]'");
		$old_board = $row[bo_table];
	    }
	    if ($board) {
		$board['bo_use_sideview'] = 1;
		$tmp_write_table = $g4['write_prefix'] . $row[bo_table]; // 게시판 테이블 전체이름

		$sql2 = " select * from $tmp_write_table where wr_id = '$row[wr_id]' ";
		$row2 = sql_fetch($sql2);
		$list[$i] = mw_get_list($row2, $board, $latest_skin_path, $subject_len);
                $list[$i][href] = "$g4[bbs_path]/board.php?bo_table=$row[bo_table]&wr_id=$row[wr_id]";
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
		$list[$i][subject] = cut_str("게시물이 없습니다.", $subject_len);
		$list[$i][href] = "#";
		$list[$i][bo_table] = "#";
	    }
	}
	else if ($minute > 0) {
	    mw_cache_write($cache_file_list, $list);
	    mw_cache_write($cache_file_board, $board);
	}
    }

    ob_start();
    include "$latest_skin_path/latest.skin.php";
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
} 
