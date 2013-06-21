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
function mw_latest_main($skin_dir="", $bo_tables, $rows=10, $subject_len=40, $minute=0)
{
    global $g4;

    if ($skin_dir)
        $latest_skin_path = "$g4[path]/skin/latest/$skin_dir";
    else
        $latest_skin_path = "$g4[path]/skin/latest/basic";

    $main = array();
    $list = array();

    // 종합
    $cache_main = "$g4[path]/data/mw.cache/latest-main";

    $multiple = 4;

    $main = mw_cache_read($cache_main, $minute);
    $main_rows = $rows * $multiple;

    $tmp = explode(",", $bo_tables);
    $bo_tables = array();
    for ($i=0, $idx=0; $i<count($tmp); $i++) {
	$bo_table = trim($tmp[$i]);
	if ($bo_table == "") continue;
	$bo_tables[$idx++] = $bo_table;
    }

    if (!$main) {

	$file = array();
	$sql = "select * from $g4[board_file_table] as f, $g4[board_table] as b
		 where f.bo_table = b.bo_table and bf_type > 0 and bf_type < 4 and bf_no = 0 and b.bo_use_search = '1'
		 order by bf_datetime desc limit $multiple";
	$qry = sql_query($sql);
	for ($i=0; $row = sql_fetch_array($qry); $i++) {
	    $file[$i][bo_table] = $row[bo_table];
	    $file[$i][bo_subject] = $row[bo_subject];
	    $file[$i][wr_id] = $row[wr_id];
	    $file[$i][path] = "$g4[path]/data/file/$row[bo_table]/thumbnail/$row[wr_id]";
	    $file[$i][href] = "$g4[bbs_path]/board.php?bo_table=$row[bo_table]&wr_id=$row[wr_id]";
	    if (!@file_exists($file[$i][path])) $file[$i][path] = "$g4[path]/data/file/$row[bo_table]/thumb/$row[bf_file]";
	    if (!@file_exists($file[$i][path])) $file[$i][path] = "$g4[path]/data/file/$row[bo_table]/$row[bf_file]";
	    if (!@file_exists($file[$i][path])) $file[$i][path] = "$latest_skin_path/img/noimage.gif";
	    if (!@file_exists($file[$i][path])) $file[$i] = null;
	    if (@is_dir($file[$i][path])) $file[$i] = null;
	    if ($file[$i]) {
		$row = sql_fetch("select wr_subject, wr_comment from $g4[write_prefix]$row[bo_table] where wr_id = '$row[wr_id]'");
		$file[$i][subject] = conv_subject($row[wr_subject], $subject_len, "…");
		$file[$i][wr_comment] = $row[wr_comment];
	    }

	}
        if (count($file) < $multiple) {
            for ($i=count($file); $i<$multiple; $i++) {
                $file[$i][path] = "$latest_skin_path/img/noimage.gif";
                $file[$i][subject] = "...";
                $file[$i][href] = "#";
            }   
        }

	$sql = "select n.bo_table, n.wr_id, b.bo_subject 
		  from $g4[board_new_table] as n, $g4[board_table] as b 
		 where n.bo_table = b.bo_table and wr_id = wr_parent and b.bo_use_search = '1' ";
        for ($i=0; $i<count($file); $i++) {
            $sql.= " and !(n.bo_table = '{$file[$i][bo_table]}' and n.wr_id = '{$file[$i][wr_id]}') ";
        }
	$sql .= " order by bn_datetime desc limit $main_rows";
	$qry =  sql_query($sql);
	for ($i=0; $row = sql_fetch_array($qry); $i++) {
	    $tmp_write_table = $g4['write_prefix'] . $row[bo_table]; // 게시판 테이블 전체이름
	    $sql = "select wr_subject,ca_name,wr_comment from $tmp_write_table where wr_id = '$row[wr_id]'";
	    $row2 = sql_fetch($sql);
	    $list[$i]['wr_subject'] = $row2['wr_subject'];
	    $list[$i]['subject'] = conv_subject($row2['wr_subject'], $subject_len, "…");
	    $list[$i]['wr_id'] = $row[wr_id];
	    $list[$i]['ca_name'] = get_text($row2[ca_name]);
	    $list[$i]['bo_table'] = $row[bo_table];
	    $list[$i]['href'] = "$g4[bbs_path]/board.php?bo_table=$row[bo_table]&wr_id=$row[wr_id]";
	    $list[$i]['bo_subject'] = $row[bo_subject];
	    $list[$i]['wr_comment'] = $row2[wr_comment];
	}
	if (!$i) {
	    for ($i=0; $i<$main_rows; $i++) {
		$list[$i][subject] = cut_str("게시물이 없어요.", $subject_len);
		$list[$i][href] = "#";
	    }
	}

	$main['main'] = $list;
	$main['main']['file'] = $file;

	// 게시판
	for ($j=0; $j<count($bo_tables); $j++)
        {
	    $bo_table = trim($bo_tables[$j]);

	    $sql = "select * from $g4[board_file_table] where bo_table = '$bo_table' and bf_type > 0 and bf_type < 4  and bf_no = 0 ";
	    $sql.= "order by bf_datetime desc limit 1";
	    $row = sql_fetch($sql);
	    if ($row) {
		$file = array();
		$file[wr_id] = $row[wr_id];
		$file[path] = "$g4[path]/data/file/$bo_table/thumbnail/$row[wr_id]";
		$file[href] = "$g4[bbs_path]/board.php?bo_table=$bo_table&wr_id=$row[wr_id]";
		if (!@file_exists($file[path])) $file[path] = "$g4[path]/data/file/$bo_table/thumb/$row[wr_id]";
		if (!@file_exists($file[path])) $file[path] = "$g4[path]/data/file/$bo_table/$row[bf_file]";
		if (!@file_exists($file[path])) $file[path] = "$latest_skin_path/img/noimage.gif";
		if (!@file_exists($file[path])) $file = null;
		if ($file) {
		    $row = sql_fetch("select wr_subject from $g4[write_prefix]$bo_table where wr_id = '$row[wr_id]'");
                    $file[subject] = conv_subject($row[wr_subject], $subject_len, "…");
		}
	    } else {
		$file[href] = "#";
		$file[path] = "$latest_skin_path/img/noimage.gif";
		$file[subject] = "...";
	    }

	    $list = array();

	    $sql = " select * from $g4[board_table] where bo_table = '$bo_table'";
	    $board = sql_fetch($sql);

	    if ($board) {
		$tmp_write_table = $g4['write_prefix'] . $bo_table; // 게시판 테이블 전체이름

		$sql = " select * from $tmp_write_table where wr_is_comment = 0 and wr_id <> '{$file[wr_id]}' order by wr_num limit 0, $rows ";
		$qry = sql_query($sql);

		for ($i=0; $row = sql_fetch_array($qry); $i++) {
		    $list[$i] = get_list($row, $board, $latest_skin_path, $subject_len);
		    $list[$i][content] = $list[$i][wr_content] = "";
		}
		if (!$i) {
		    for ($i=0; $i<$rows; $i++) {
			$list[$i][subject] = cut_str("게시물이 없어요.", $subject_len);
			$list[$i][href] = "#";
		    }
		}
	    } else {
		$list = array();
		$board = array();
		$board[bo_subject] = "none";
		for ($i=0; $i<$rows; $i++) {
		    $list[$i][subject] = "'$bo_table' 게시판이 존재하지 않아요.";
		    $list[$i][href] = "#";
		}
	    }

	    $main[$bo_table] = $list;
	    $main[$bo_table]['board'] = $board;
	    $main[$bo_table]['file'] = $file;
	}
	mw_cache_write($cache_main, $main);
    }
    $list = $file = $bo_table = $board = null;

    ob_start();
    include "$latest_skin_path/latest.skin.php";
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
} 
?>
