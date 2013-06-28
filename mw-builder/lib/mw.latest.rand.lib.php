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

// 랜덤은 캐쉬적용 안함.
//@include_once("$g4[path]/lib/mw.cache.lib.php");

function mw_latest_rand($skin_dir="", $bo_table, $rows=10, $subject_len=40, $is_img=0, $minute=0)
{
    global $g4;

    if ($skin_dir)
        $latest_skin_path = "$g4[path]/skin/latest/$skin_dir";
    else
        $latest_skin_path = "$g4[path]/skin/latest/basic";

    $cache_file_list = "$g4[path]/data/mw.cache/latest-rand-{$bo_table}-list-{$rows}-{$is_img}-{$subject_len}";
    $cache_file_file = "$g4[path]/data/mw.cache/latest-rand-{$bo_table}-file-{$rows}-{$is_img}-{$subject_len}";
    $cache_file_board = "$g4[path]/data/mw.cache/latest-rand-{$bo_table}-board-{$rows}-{$is_img}-{$subject_len}";

    //$list = mw_cache_read($cache_file_list, $minute);
    //$board = mw_cache_read($cache_file_board, $minute);
    //$file = mw_cache_read($cache_file_file, $minute);

    if ($is_img && !$file) {
        $file = array();
	$sql = "select *
		  from $g4[board_file_table]
		 where bo_table = '$bo_table' and bf_type > 0 and bf_type < 4 and bf_no = 0
		 order by rand() limit $is_img";
	$qry = sql_query($sql);
	for ($i=0; $row=sql_fetch_array($qry); $i++) {
	    $file[$i] = array();
	    $file[$i][wr_id] = $row[wr_id];
	    $file[$i][path] = "$g4[path]/data/file/$bo_table/thumbnail/$row[wr_id]";
	    //$file[$i][href] = "$g4[bbs_path]/board.php?bo_table=$row[bo_table]&wr_id=$row[wr_id]";
	    $file[$i][href] = "$g4[url]/$g4[bbs]/board.php?bo_table=$row[bo_table]&wr_id=$row[wr_id]";
	    if (!@file_exists($file[$i][path])) $file[$i][path] = "$g4[path]/data/file/$row[bo_table]/thumb/$row[wr_id]";
	    if (!@file_exists($file[$i][path])) $file[$i][path] = "$g4[path]/data/file/$row[bo_table]/$row[bf_file]";
	    if (!@file_exists($file[$i][path])) $file[$i][path] = "$latest_skin_path/img/noimage.gif";
	    if (!@file_exists($file[$i][path])) $file[$i] = null;
	    if (@is_dir($file[$i][path])) $file[$i] = null;
	    if ($file[$i]) {
		$row2 = sql_fetch("select wr_subject,wr_comment, wr_link1 from $g4[write_prefix]$row[bo_table] where wr_id = '$row[wr_id]'");
                $file[$i][subject] = conv_subject($row2[wr_subject], $subject_len, "…");
                $file[$i][wr_comment] = $row2[wr_comment];
                $file[$i][wr_link1] = $row2[wr_link1];
	    }
	}

        if (!count($file)) {
            for ($i=0; $i<$is_img; $i++) {
                $file[$i][path] = "$latest_skin_path/img/noimage.gif";
                $file[$i][subject] = "...";
                $file[$i][href] = "#";
            }   
        }
	//mw_cache_write($cache_file_file, $file);
    }

    if (!$list) {
	$sql = " select * from $g4[board_table] where bo_table = '$bo_table'";
	$board = sql_fetch($sql);

	$noids = array();
	for ($i=0; $i<count($file); $i++) {
	    $noids[] = $file[$i][wr_id];
	}
	$noids = implode("','", $noids);

	if ($board) {
	    $tmp_write_table = $g4['write_prefix'] . $bo_table; // 게시판 테이블 전체이름

	    $sql = " select * from $tmp_write_table where wr_is_comment = 0 and wr_id not in ('$noids') order by rand() limit 0, $rows ";
	    $qry = sql_query($sql);

	    for ($i=0; $row = sql_fetch_array($qry); $i++) {
		$list[$i] = mw_get_list($row, $board, $latest_skin_path, $subject_len);
                $list[$i][href] = "$g4[bbs_path]/board.php?bo_table=$bo_table&wr_id=$row[wr_id]";
		$list[$i][content] = $list[$i][wr_content] = "";
	    }

	    if (!$i) {
		for ($i=0; $i<$rows; $i++) {
		    $list[$i][subject] = cut_str("게시물이 없어요.", $subject_len);
		    $list[$i][href] = "#";
		}
	    }

	    if ($minute > 0) {
		mw_cache_write($cache_file_list, $list);
		mw_cache_write($cache_file_board, $board);
	    }

	} else {
	    $list = array();
	    $board = array();
	    $board[bo_subject] = "none";
	    for ($i=0; $i<$rows; $i++) {
		$list[$i][subject] = cut_str("'$bo_table' 게시판이 존재하지 않아요.", $subject_len);
		$list[$i][href] = "#";
	    }
	}
    }

    ob_start();
    include "$latest_skin_path/latest.skin.php";
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
} 
