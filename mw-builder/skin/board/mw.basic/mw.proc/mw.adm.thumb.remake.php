<?php
/**
 * Bechu-Basic Skin for Gnuboard4
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

include_once("_common.php");
include_once("$board_skin_path/mw.lib/mw.skin.basic.lib.php");
header("Content-Type: text/html; charset=$g4[charset]");

if ($is_admin != 'super')
    die("로그인 해주세요.");

if (!$bo_table)
    die("bo_table 값이 없습니다.");

if (!$token or get_session("ss_config_token") != $token) 
    die("토큰 에러로 실행 불가합니다.");

$sql = " select * from $g4[board_file_table] where bo_table = '$bo_table' and bf_width = 0 ";
$sql.= " and ( ";
$sql.= " right(lower(bf_source), 3) = 'jpg' ";
$sql.= " or right(lower(bf_source), 3) = 'gif' ";
$sql.= " or right(lower(bf_source), 3) = 'png') ";
$qry = sql_query($sql);
while ($row = sql_fetch_array($qry)) {
    $file = "$g4[path]/data/file/$bo_table/$row[bf_file]";
    $size = @getImageSize($file);
    sql_query(" update $g4[board_file_table] set bf_width = '$size[0]', bf_height = '$size[1]', bf_type = '$size[2]' where bo_table = '$bo_table' and wr_id = '$row[wr_id]' and bf_no = '$row[bf_no]' ");
}

$sql = "select wr_id, wr_content, wr_datetime, wr_link1, wr_link2 from $write_table where wr_is_comment = '0' order by wr_num";
$qry = sql_query($sql);
while ($write = sql_fetch_array($qry)) {
    $wr_id = $write[wr_id];
    $wr_content = $write[wr_content];

    $thumb_file = mw_thumb_jpg("$thumb_path/{$wr_id}");
    $thumb2_file = mw_thumb_jpg("$thumb2_path/{$wr_id}");
    $thumb3_file = mw_thumb_jpg("$thumb3_path/{$wr_id}");
    $thumb4_file = mw_thumb_jpg("$thumb4_path/{$wr_id}");
    $thumb5_file = mw_thumb_jpg("$thumb5_path/{$wr_id}");
 
    $file = mw_get_first_file($bo_table, $wr_id, true);
    if (!empty($file)) {
        $source_file = "$file_path/{$file[bf_file]}";
        mw_make_thumbnail($mw_basic[cf_thumb_width], $mw_basic[cf_thumb_height], $source_file,
            "{$thumb_file}", $mw_basic[cf_thumb_keep]);
        mw_make_thumbnail($mw_basic[cf_thumb2_width], $mw_basic[cf_thumb2_height], $source_file,
            "{$thumb2_file}", $mw_basic[cf_thumb2_keep]);
        mw_make_thumbnail($mw_basic[cf_thumb3_width], $mw_basic[cf_thumb3_height], $source_file,
            "{$thumb3_file}", $mw_basic[cf_thumb3_keep]);
        mw_make_thumbnail($mw_basic[cf_thumb4_width], $mw_basic[cf_thumb4_height], $source_file,
            "{$thumb4_file}", $mw_basic[cf_thumb4_keep]);
        mw_make_thumbnail($mw_basic[cf_thumb5_width], $mw_basic[cf_thumb5_height], $source_file,
            "{$thumb5_file}", $mw_basic[cf_thumb5_keep]);
    }
    else {
        $is_thumb = false;
        preg_match_all("/<img.*src=\"(.*)\"/iU", $wr_content, $matchs);
        for ($i=0, $m=count($matchs[1]); $i<$m; ++$i) {
            $mat = $matchs[1][$i];
            if (strstr($mat, "mw.basic.comment.image")) $mat = '';
            if (strstr($mat, "mw.emoticon")) $mat = '';
            if (preg_match("/cheditor[0-9]\/icon/i", $mat)) $mat = '';
            if ($mat) {
                $mat = str_replace($g4[url], "..", $mat);
                mw_make_thumbnail($mw_basic[cf_thumb_width], $mw_basic[cf_thumb_height], $mat,
                    "{$thumb_file}", $mw_basic[cf_thumb_keep]);
                mw_make_thumbnail($mw_basic[cf_thumb2_width], $mw_basic[cf_thumb2_height], $mat,
                    "{$thumb2_file}", $mw_basic[cf_thumb2_keep]);
                mw_make_thumbnail($mw_basic[cf_thumb3_width], $mw_basic[cf_thumb3_height], $mat,
                    "{$thumb3_file}", $mw_basic[cf_thumb3_keep]);
                mw_make_thumbnail($mw_basic[cf_thumb4_width], $mw_basic[cf_thumb4_height], $mat,
                    "{$thumb4_file}", $mw_basic[cf_thumb4_keep]);
                mw_make_thumbnail($mw_basic[cf_thumb5_width], $mw_basic[cf_thumb5_height], $mat,
                    "{$thumb5_file}", $mw_basic[cf_thumb5_keep]);
                $is_thumb = true;
                break;
            } // if
        } // for
        if (!$is_thumb) {
            if (file_exists($thumb_file)) unlink($thumb_file);
            if (file_exists($thumb2_file)) unlink($thumb2_file);
            if (file_exists($thumb3_file)) unlink($thumb3_file);
            if (file_exists($thumb4_file)) unlink($thumb4_file);
            if (file_exists($thumb5_file)) unlink($thumb5_file);
        } // if
    } // if

    if (!file_exists($thumb_file)) {
        if (preg_match("/youtu/i", $write['wr_link1'])) mw_get_youtube_thumb($wr_id, $write['wr_link1'], $write['wr_datetime']);
        else if (preg_match("/youtu/i", $write['wr_link2'])) mw_get_youtube_thumb($wr_id, $write['wr_link2'], $write['wr_datetime']);
        else if (preg_match("/vimeo/i", $write['wr_link1'])) mw_get_vimeo_thumb($wr_id, $write['wr_link1'], $write['wr_datetime']);
        else if (preg_match("/vimeo/i", $write['wr_link2'])) mw_get_vimeo_thumb($wr_id, $write['wr_link2'], $write['wr_datetime']);
    }
}

echo "썸네일을 모두 재생성하였습니다.";
