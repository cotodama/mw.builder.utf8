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

function getimagetype($type)
{
    switch ($type)
    {
        case "1": $re = "GIF"; break;
        case "2": $re = "JPG"; break;
        case "3": $re = "PNG"; break;
        case "4": $re = "SWF"; break;
        case "5": $re = "PSD"; break;
        case "6": $re = "BMP"; break;
        case "7": $re = "TIF"; break; // (intel byte order)
        case "8": $re = "TIF"; break; // (motorola byte order)
        case "9": $re = "JPC"; break;
        case "10": $re = "JP2"; break;
        case "11": $re = "JPX"; break;
        case "12": $re = "JB2"; break;
        case "13": $re = "SWC"; break;
        case "14": $re = "IFF"; break;
    }

    return strtolower($re);
}

// 랜덤 아이피주소 생성
function ip_rand()
{
    return rand(10,255).'.'.rand(10,255).'.'.rand(10,255).'.'.rand(10,255);
}

// 시간 간격출력 .. 080324, 최재영
function get_runtime($sec)
{
    if (!$sec)
        return "0초";

    $minute = 60;
    $hour = $minute*60;
    $day = $hour*24;
    $month = $day*30;
    $year = $day*365;

    $is_end = false;

    isset($str);

    if ($sec > $year) {
        $str = floor($sec/$year)."년 ";
        $sec -= (floor($sec/$year) * $year);
    } elseif ($sec > $month) {
        $str = floor($sec/$month)."개월 ";
        $sec -= (floor($sec/$month) * $month);
    } elseif ($sec > $day) {
        $str = floor($sec/$day)."일 ";
        $sec -= (floor($sec/$day) * $day);
    } elseif ($sec > $hour) {
        $str = floor($sec/$hour)."시간 ";
        $sec -= (floor($sec/$hour) * $hour);
    } elseif ($sec > $minute) {
        $str = floor($sec/$minute)."분 ";
        $sec -= (floor($sec/$minute) * $minute);
    } else {
        $str = $sec."초";
        $is_end = true;
    }

    if (!$is_end)
        $str .= get_runtime($sec);

    return $str;
}

// htmlspecialchars() 역 변환 .. 080324, 최재영
function unhtmlspecialchars($str)
{
    $trans = get_html_translation_table();
    $trans = array_flip($trans);
    $str = strtr($str, $trans);
    return $str;
}

function mw_get_skin_config($bo_table)
{
    global $g4;
    global $is_admin;

    $config_table = $g4['table_prefix']."mw_basic_config";

    $f = array();
    $f[] = "cf_singo_number";

    $sql_f = implode(", ", $f);

    $sql = "select {$sql_f} from {$config_table} where bo_table = '{$bo_table}' ";
    //if ($is_admin) echo "$sql<br>\n";
    $row = sql_fetch($sql, false);

    return $row;
}

// 게시물 정보($write_row)를 출력하기 위하여 $list로 가공된 정보를 복사 및 가공
function mw_get_list($write_row, $board, $skin_path, $subject_len=40)
{
    global $g4;
    global $config;
    global $qstr;
    global $page;
    global $mw;
    global $is_admin;

    $skin_path = str_replace("../", "", $skin_path);
    $skin_path = str_replace("./", "", $skin_path);
    $skin_path = $g4['url'].'/'.$skin_path;

    //$t = get_microtime();
    $bbs_path = "$g4[url]/$g4[bbs]";

    // 배열전체를 복사
    $list = $write_row;
    unset($write_row);

    global $basic_skin_config;
    $mw_basic = $basic_skin_config[$board['bo_table']];
    if (empty($mw_basic)) {
        $basic_skin_config[$board['bo_table']] = mw_get_skin_config($board['bo_table']); 
        $mw_basic = $basic_skin_config[$board['bo_table']];
    }

    $list['wr_singo_lock'] = false;
    if ($list['wr_singo'] && $list['wr_singo'] >= $mw_basic['cf_singo_number']) {
        $list['wr_subject'] = "신고가 접수된 게시물입니다.";
        $list['wr_content'] = "신고가 접수된 게시물입니다.";
        $list['wr_singo_lock'] = true;
    }

    $list['is_notice'] = preg_match("/[^0-9]{0,1}{$list['wr_id']}[\r]{0,1}/",$board['bo_notice']);

    if ($list['wr_view_block']) {
        $list['wr_subject'] = "보기가 차단된 게시물입니다.";
        $list['wr_content'] = "보기가 차단된 게시물입니다.";
    }

    if ($subject_len)
        $list['subject'] = conv_subject($list['wr_subject'], $subject_len, "…");
    else
        $list['subject'] = conv_subject($list['wr_subject'], $board['bo_subject_len'], "…");

    $list['subject'] = mw_builder_reg_str($list['subject']);

    // 목록에서 내용 미리보기 사용한 게시판만 내용을 변환함 (속도 향상) : kkal3(커피)님께서 알려주셨습니다.
    if ($board['bo_use_list_content'])
	{
		$html = 0;
		if (strstr($list['wr_option'], "html1"))
			$html = 1;
		else if (strstr($list['wr_option'], "html2"))
			$html = 2;

        $list['content'] = conv_content($list['wr_content'], $html);
	}

    $list['comment_cnt'] = "";
    if ($list['wr_comment_hide'])
        $list['wr_comment'] = 0;

    if ($list['wr_comment'])
        $list['comment_cnt'] = "($list[wr_comment])";

    // 당일인 경우 시간으로 표시함
    $list['datetime'] = substr($list['wr_datetime'],0,10);
    $list['datetime2'] = $list['wr_datetime'];
    if ($list['datetime'] == $g4['time_ymd'])
        $list['datetime2'] = substr($list['datetime2'],11,5);
    else
        $list['datetime2'] = substr($list['datetime2'],5,5);
    // 4.1
    $list['last'] = substr($list['wr_last'],0,10);
    $list['last2'] = $list['wr_last'];
    if ($list['last'] == $g4['time_ymd'])
        $list['last2'] = substr($list['last2'],11,5);
    else
        $list['last2'] = substr($list['last2'],5,5);

    $list['wr_homepage'] = get_text(addslashes($list['wr_homepage']));

    $tmp_name = get_text(cut_str($list['wr_name'], $config['cf_cut_name'])); // 설정된 자리수 만큼만 이름 출력
    if ($board['bo_use_sideview'])
        $list['name'] = get_sideview($list['mb_id'], $tmp_name, $list['wr_email'], $list['wr_homepage']);
    else
        $list['name'] = "<span class='".($list['mb_id']?'member':'guest')."'>$tmp_name</span>";

    $reply = $list['wr_reply'];

    $list['reply'] = "";
    if (strlen($reply) > 0) 
    {
        for ($k=0; $k<strlen($reply); $k++)
            $list['reply'] .= ' &nbsp;&nbsp; ';
    }

    $list['icon_reply'] = "";
    if ($list['reply'])
        $list['icon_reply'] = "<img src='$skin_path/img/icon_reply.gif' align='absmiddle'>";

    $list['icon_link'] = "";
    if ($list['wr_link1'] || $list['wr_link2'])
        $list['icon_link'] = "<img src='$skin_path/img/icon_link.gif' align='absmiddle'>";

    // 분류명 링크
    if (function_exists("mw_builder_seo_url"))
        $list['ca_name_href'] = mw_builder_seo_url($board[bo_table])."&sca=".urlencode($list['ca_name']);
    else
        $list['ca_name_href'] = "$bbs_path/board.php?bo_table=$board[bo_table]&sca=".urlencode($list['ca_name']);

    if (function_exists("mw_builder_seo_url"))
        $list['href'] = mw_builder_seo_url($board['bo_table'], $list['wr_id'], $qstr);
    else
        $list['href'] = "$bbs_path/board.php?bo_table=$board[bo_table]&wr_id=$list[wr_id]" . $qstr;

    //$list['href'] = "$bbs_path/board.php?bo_table=$board[bo_table]&wr_id=$list[wr_id]";
    if ($board['bo_use_comment'])
        $list['comment_href'] = "javascript:win_comment('$bbs_path/board.php?bo_table=$board[bo_table]&wr_id=$list[wr_id]&cwin=1');";
    else
        $list['comment_href'] = $list['href'];

    $list['icon_new'] = "";
    if ($list['wr_datetime'] >= date("Y-m-d H:i:s", $g4['server_time'] - ($board['bo_new'] * 3600)))
        $list['icon_new'] = "<img src='$skin_path/img/icon_new.gif' align='absmiddle'>";

    $list['icon_hot'] = "";
    if ($list['wr_hit'] >= $board['bo_hot'])
        $list['icon_hot'] = "<img src='$skin_path/img/icon_hot.gif' align='absmiddle'>";

    $list['icon_secret'] = "";
    if (strstr($list['wr_option'], "secret"))
        $list['icon_secret'] = "<img src='{$skin_path}/img/icon_secret.gif' align='absmiddle'>";

    // 링크
    for ($i=1; $i<=$g4['link_count']; $i++) 
    {
        $list['link'][$i] = set_http(get_text($list["wr_link{$i}"]));
        $list['link_href'][$i] = "$bbs_path/link.php?bo_table=$board[bo_table]&wr_id=$list[wr_id]&no=$i" . $qstr;
        $list['link_hit'][$i] = (int)$list["wr_link{$i}_hit"];
    }

    // 가변 파일
    $list['file'] = get_file($board['bo_table'], $list['wr_id']);

    if ($list['file']['count'])
        $list['icon_file'] = "<img src='{$skin_path}/img/icon_file.gif' align='absmiddle'>";

    return $list;
}

function goto_url2($url) {
    global $g4;

    header("location: {$url}");
    exit;

    /*include_once("$g4[path]/head.sub.php");
    echo "<script type='text/javascript'> window.onload = function () { location.replace('$url'); } </script>";
    include_once("$g4[path]/tail.sub.php");*/
    exit;
}

// 자동치환
function mw_builder_reg_str($str)
{
    global $g4;
    global $member;

    if ($member['mb_id']) {
        $str = str_replace("{닉네임}", $member['mb_nick'], $str);
        $str = str_replace("{별명}", $member['mb_nick'], $str);
    }
    else {
        $str = str_replace("{닉네임}", "회원", $str);
        $str = str_replace("{별명}", "회원", $str);
    }

    $str = preg_replace("/\[month\]/iU", date('n', $g4['server_time']), $str);
    $str = preg_replace("/\[last_day\]/iU", date('t', $g4['server_time']), $str);

    $str = preg_replace("/\[today\]/iU", date('Y년 m월 d일', $g4['server_time']), $str);
    $str = preg_replace("/\[day of the week\]/iU", get_yoil($g4['time_ymdhis']), $str);

    preg_match_all("/\[counting (.*)\]/iU", $str, $matches);
    for ($i=0, $m=count($matches[1]); $i<$m; $i++) {
        $str = preg_replace("/\[counting {$matches[1][$i]}\]/iU", mw_builder_counting_date($matches[1][$i]), $str);
    }

    return $str;
}

function mw_builder_counting_date($datetime, $endstr=" 남았습니다")
{
    global $g4;

    $timestamp = strtotime($datetime); // 글쓴날짜시간 Unix timestamp 형식 
    $current = $g4['server_time']; // 현재날짜시간 Unix timestamp 형식 

    if ($current >= $timestamp)
        return "종료 되었습니다.";

    if ($current <= $timestamp - 86400 * 365)
        $str = (int)(($timestamp - $current) / (86400 * 365)) . "년"; 
    else if ($current <= $timestamp - 86400 * 31)
        $str = (int)(($timestamp - $current) / (86400 * 31)) . "개월"; 
    else if ($current <= $timestamp - 86400 * 1)
        $str = (int)(($timestamp - $current) / 86400) . "일"; 
    else if ($current <= $timestamp - 3600 * 1)
        $str = (int)(($timestamp - $current) / 3600) . "시간"; 
    else if ($current <= $timestamp - 60 * 1)
        $str = (int)(($timestamp - $current) / 60) . "분"; 
    else
        $str = (int)($timestamp - $current) . "초"; 
    
    return $str.$endstr; 
}

// 자동치환
function mw_title_tag($str)
{
    return str_replace("\"", "'", $str);
}

function mw_get_last_thumb($bo_tables, $cnt=1, $is_rand=false)
{
    global $g4;

    $files = array();

    foreach ((array)$bo_tables as $bo_table)
    {
        $row = sql_fetch("select max(wr_id) as max_id from $g4[write_prefix]$bo_table where wr_is_comment = 0", false);
        $max = $row['max_id'];

        if (!$max) continue;

        $max_id = $max;

        $path = "$g4[path]/data/file/{$bo_table}/thumbnail"; 
        if (!is_dir($path))
            $path = "$g4[path]/data/file/{$bo_table}/thumb"; 

        $fnd = 0;

        for($i=0, $m=$max_id; $i<$m; $i++) {
            if ($is_rand)
                $max = rand(1, $max_id);
            else if ($i)
                --$max;

            $file_path = "{$path}/{$max}.jpg";
            if (!file_exists($file_path))
                $file_path = "{$path}/{$max}";

            if (file_exists($file_path)) {
                $filemtime = filemtime($file_path);
                $file = array();
                $file['bo_table'] = $bo_table;
                $file['wr_id'] = $max;
                $file['path'] = "$file_path?".filesize($file_path);
                $file['href'] = mw_builder_seo_url($bo_table, $max);
                $file['mtime'] = $filemtime;

                if (!$files[$filemtime]['wr_id']) {
                    $files[$filemtime] = $file;
                    ++$fnd;
                }
            }
            //if ($i>100) break;
            if ($fnd >= $cnt) break;
            if (!$max) break;
        }
    }

    if (!count($files)) return;

    krsort($files);

    $list = array();
    for ($i=1; $i<=$cnt; $i++) {
        $list[] = array_shift($files);
        if (!$files) break;
    }

    $files = null;
    unset($files);

    return $list;
}

/*
function mw_get_last_thumb($bo_tables, $cnt=1)
{
    global $g4;

    $files = array();

    foreach ((array)$bo_tables as $bo_table)
    {
        $row = sql_fetch("select max(wr_id) as max_id from $g4[write_prefix]$bo_table where wr_is_comment = 0", false);
        $max = $row['max_id'];

        if (!$max) continue;;

        $path = "$g4[path]/data/file/{$bo_table}/thumbnail"; 
        if (!is_dir($path))
            $path = "$g4[path]/data/file/{$bo_table}/thumb"; 

        $fnd = 0;

        do {
            $file_path = "{$path}/{$max}.jpg";
            if (!file_exists($file_path))
                $file_path = "{$path}/{$max}";

            if (file_exists($file_path)) {
                $file = array();
                $file['bo_table'] = $bo_table;
                $file['wr_id'] = $max;
                $file['path'] = $file_path;
                $file['href'] = mw_builder_seo_url($bo_table, $max);

                //$filemtime = filemtime($file_path);
                $files[$max] = $file;
                ++$fnd;
            }
            if ($fnd >= $cnt) break;
        }
        while (--$max);
    }

    if (!count($files)) return;

    krsort($files);

    $list = array();
    for ($i=1; $i<=$cnt; $i++) {
        $list[] = array_shift($files);
        if (!$files) break;
    }

    $files = null;
    unset($files);

    return $list;
}*/

function mw_get_thumb_path($bo_table, $wr_id, $file=null, $thumb_number=null)
{
    global $g4;

    $thumb = null;

    if (!$bo_table or !$wr_id) return $thumb;

    $img1 = "{$g4['path']}/data/file/{$bo_table}/thumbnail{$thumb_number}/{$wr_id}";
    $img2 = "{$g4['path']}/data/file/{$bo_table}/thumb/{$wr_id}";

    $jpg1 = "{$img1}.jpg";
    $jpg2 = "{$img2}.jpg";

    $thumb = $jpg1;

    if (!file_exists($thumb)) $thumb = $img1;
    if (!file_exists($thumb)) $thumb = $jpg2;
    if (!file_exists($thumb)) $thumb = $img2;
    if (!file_exists($thumb)) {
        if (!$file)
            $file = mw_builder_get_first_file($bo_table, $wr_id, true);
        $thumb = "{$g4['path']}/data/file/{$bo_table}}/{$file['bf_file']}";
        if (!preg_match("/\.(jpg|gif|png)$/i", $thumb)) {
            $thumb = null;
        }
    }
    if (is_dir($thumb)) $thumb = null;

    return $thumb;
}

function mw_builder_get_first_file($bo_table, $wr_id, $is_image=false)
{
    global $g4;

    $sql = "select * from $g4[board_file_table] ";
    $sql.= " where bo_table = '$bo_table' ";
    $sql.= "   and wr_id = '$wr_id' ";
    if ($is_image) 
        $sql.= " and bf_width > 0 ";
    $sql.= " order by bf_no ";
    $sql.= " limit 1";
    $row = sql_fetch($sql);

    return $row;
}

function mw_html_entities($str)
{
    $str = str_replace("\"", "&quot;", $str);
    //$str = str_replace("&", "&amp;", $str);
    $str = str_replace("<", "&lt;", $str);
    $str = str_replace(">", "&gt;", $str);
    $str = str_replace("'", "&prime;", $str);

    return $str;
}


