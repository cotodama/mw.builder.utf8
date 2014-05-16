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

if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

mw_bomb();
mw_basic_move_cate($bo_table, $wr_id);

$mb = get_member($view[mb_id], 'mb_level');

// is_notice 그누보드 버그 보완
$view[is_notice] = preg_match("/(^|[\r\n]){$wr_id}($|[\r\n])/",$board[bo_notice]); 

$move_flag = false;
// 자동이동
if (!$view[is_notice] and !$write[wr_auto_move] and $mw_basic[cf_auto_move]['use'] and $mw_basic[cf_auto_move]['bo_table']
    and (!$mw_basic[cf_auto_move]['day'] or $mw_basic[cf_auto_move]['day'] > ($g4[server_time]-strtotime($write[wr_datetime]))/(60*60*24)))
{
/*
    if (($mw_basic[cf_auto_move]['hit'] and $mw_basic[cf_auto_move]['hit'] <= $write[wr_hit])
     or ($mw_basic[cf_auto_move]['good'] and $mw_basic[cf_auto_move]['good'] <= $write[wr_good] && !$mw_basic[cf_auto_move]['sub'])
     or ($mw_basic[cf_auto_move]['nogood'] and $mw_basic[cf_auto_move]['nogood'] <= $write[wr_nogood] && !$mw_basic[cf_auto_move]['sub'])
     or ($mw_basic[cf_auto_move]['sub'] and $mw_basic[cf_auto_move]['good'] <= ($write[wr_good]-$write[wr_nogood]))
     or ($mw_basic[cf_auto_move]['singo'] and $mw_basic[cf_auto_move]['singo'] <= $write[wr_singo])
     or ($mw_basic[cf_auto_move]['comment'] and $mw_basic[cf_auto_move]['comment'] <= $write[wr_comment]))
*/
    if ($mw_basic[cf_auto_move]['sub']) {
        if (    ($mw_basic[cf_auto_move]['hit'] <= $write[wr_hit])
            and ($mw_basic[cf_auto_move]['good'] <= ($write[wr_good]-$write[wr_nogood]))
            and ($mw_basic[cf_auto_move]['singo'] <= $write[wr_singo])
            and ($mw_basic[cf_auto_move]['comment'] <= $write[wr_comment]))
        {
            $move_flag = true;
        }
    }
    else {
        if (    ($mw_basic[cf_auto_move]['hit'] <= $write[wr_hit])
            and ($mw_basic[cf_auto_move]['good'] <= $write[wr_good])
            and ($mw_basic[cf_auto_move]['nogood'] <= $write[wr_nogood])
            and ($mw_basic[cf_auto_move]['singo'] <= $write[wr_singo])
            and ($mw_basic[cf_auto_move]['comment'] <= $write[wr_comment]))
        {
            $move_flag = true;
        }
    }
}
if ($move_flag) {
    sql_query("update $write_table set wr_auto_move = '1' where wr_id = '$wr_id' ", false);
    mw_move($board, $wr_id, $mw_basic[cf_auto_move]['bo_table'], $mw_basic[cf_auto_move]['use']);
}

// 실명인증 & 성인인증
if (($mw_basic[cf_kcb_read] || $write[wr_kcb_use]) && !is_okname()) {
    check_okname();
    return;
}

$mw_membership = array();
$mw_membership_icon = array();

// 링크로그
for ($i=1; $i<=$g4['link_count']; $i++)
{
    //if ($mw_basic[cf_link_log])  {
        $view['link'][$i] = set_http(get_text($view["wr_link{$i}"]));
        $view['link_href'][$i] = "$pc_skin_path/link.php?bo_table=$board[bo_table]&wr_id=$view[wr_id]&no=$i" . $qstr;
        $view['link_hit'][$i] = (int)$view["wr_link{$i}_hit"];
    //}
    $view['link_target'][$i] = $view["wr_link{$i}_target"];
    if (!$view['link_target'][$i])
        $view['link_target'][$i] = '_blank';
}

// 링크게시판
if ($mw_basic[cf_link_board] && !$is_admin && $view[mb_id] != $member[mb_id] && $view[link][1]) {
    //goto_url("board.php?bo_table=$bo_table$qstr");
    goto_url($view['link_href'][1]);
}
// 게시물별 링크
elseif ($write[wr_link_write] && !$is_admin && $view[mb_id] != $member[mb_id] && $view[link][1]) {
    if ($mw_basic[cf_read_level] && $list[$i][wr_read_level]) {
        if ($list[$i][wr_read_level] <= $member[mb_level])
            goto_url($view['link_href'][1]);
        else
            alert("글을 읽을 권한이 없습니다. ", "board.php?bo_table=$bo_table$qstr");
    }
    else if ($member[mb_level] >= $board[bo_read_level])
        goto_url($view['link_href'][1]);
    else
        alert("글을 읽을 권한이 없습니다. ", "board.php?bo_table=$bo_table$qstr");
}

if ($mw_basic[cf_read_level] && $write[wr_read_level] && $write[wr_read_level] > $member[mb_level]) {
    alert("글을 읽을 권한이 없습니다.");
}

// 글읽을 조건 
if ($mw_basic[cf_read_point] && !$is_admin) {
    if ($member[mb_point] < $mw_basic[cf_read_point]) {
        alert("이 게시판은 $mw_basic[cf_read_point] 포인트 이상 소지자만 글읽기가 가능합니다.");
    }
}
if ($mw_basic[cf_read_register] && !$is_admin) {
    $gap = ($g4[server_time] - strtotime($member[mb_datetime])) / (60*60*24);
    if ($gap < $mw_basic[cf_read_register]) {
        alert("이 게시판은 가입후 $mw_basic[cf_read_register] 일이 지나야 글읽기가 가능합니다.");
    }
}

if ($board[bo_read_point] < 0 && $view[mb_id] != $member[mb_id] && !$point && $is_member && !$is_admin && $mw_basic[cf_read_point_message]) {
    $tmp = sql_fetch(" select * from $g4[point_table] where mb_id = '$member[mb_id]' and po_rel_table = '$bo_table' and po_rel_id = '{$view[wr_id]}' and po_rel_action = '읽기' and po_datetime = '$g4[time_ymdhis]'");
    if ($tmp) {
        delete_point($member[mb_id], $bo_table, $view[wr_id], '읽기');
        set_session("ss_view_{$bo_table}_{$wr_id}", '');
        unset($_SESSION["ss_view_{$bo_table}_{$wr_id}"]);

        echo "<script type='text/javascript'>if (confirm('글을 읽으시면 $board[bo_read_point] 포인트 차감됩니다.\\n\\n현재포인트 : $member[mb_point]p\\n\\n')) location.href = '{$_SERVER["REQUEST_URI"]}&point=1'; else history.back();</script>";
        exit;
    }
} 

if (!$is_admin && $write[wr_view_block])
    alert("이 게시물 보기는 차단되었습니다. 관리자만 접근 가능합니다.");

// 호칭
if (strlen(trim($mw_basic[cf_name_title]))) {
    $view[name] = str_replace("<span class='member'>{$view[wr_name]}</span>", "<span class='member'>{$view[wr_name]} {$mw_basic[cf_name_title]}</span>", $view[name]);
}

// 멤버쉽 아이콘
if (function_exists("mw_cash_membership_icon") && $view[mb_id] != $config[cf_admin])
{
    if (!in_array($view[mb_id], $mw_membership)) {
        $mw_membership[] = $view[mb_id];
        $mw_membership_icon[$view[mb_id]] = mw_cash_membership_icon($view[mb_id]);
        $view[name] = $mw_membership_icon[$view[mb_id]].$view[name];
    } else {
        $view[name] = $mw_membership_icon[$view[mb_id]].$view[name];
    }
}

if ($view[wr_anonymous] || $mw_basic[cf_attribute] == 'anonymous') {
    $view[name] = mw_anonymous_nick($write[mb_id], $write[wr_ip]);
    $view[wr_name] = $view[name];
    $mw_basic[cf_latest] = false;
}

if (($mw_basic[cf_must_notice] || $mw_basic[cf_must_notice_read] || $mw_basic[cf_must_notice_comment]) && $view[is_notice]) // 공지 읽기 필수
{
    if ($member[mb_id]) {
        sql_query("insert into $mw[must_notice_table] set bo_table = '$bo_table', wr_id = '$wr_id', mb_id = '$member[mb_id]', mu_datetime = '$g4[time_ymdhis]'", false);
    }
}
else
{
    if ($mw_basic[cf_must_notice_read]) {
        $tmp_notice = str_replace("\n", ",", trim($board[bo_notice]));
        $cnt_notice = sizeof(explode(",", $tmp_notice));

        if ($tmp_notice) {
            $sql = "select count(*) as cnt from $mw[must_notice_table] where bo_table = '$bo_table' and mb_id = '$member[mb_id]' and wr_id in ($tmp_notice)";
            $row = sql_fetch($sql);
            if ($row[cnt] != $cnt_notice)
                alert("$board[bo_subject] 공지를 모두 읽으셔야 글읽기가 가능합니다.", "$g4[bbs_path]/board.php?bo_table=$bo_table");
        }
    }
}

// 파일 출력
if ($mw_basic[cf_social_commerce]) {
    $file_start = 2;
}
else if ($mw_basic[cf_talent_market]) {
    $file_start = 1;
}
else {
    $file_start = 0;
}

$jwplayer = false;
$jwplayer_count = 0;

ob_start();
$cf_img_1_noview = $mw_basic[cf_img_1_noview];
for ($i=$file_start; $i<=$view[file][count]; $i++) {
    if ($cf_img_1_noview && $view[file][$i][view]) {
        $cf_img_1_noview = false;
        continue;
    }

    if (strstr($mw_basic['cf_multimedia'], '/movie/') && preg_match("/\.($config[cf_movie_extension])$/i", $view[file][$i][file])) {
        $tmp = '';
        echo mw_jwplayer("{$g4[path]}/data/file/{$board[bo_table]}/{$view[file][$i][file]}");
        echo "<br/><br/>";
        if (trim($view[file][$i][content]))
            echo $view[file][$i][content] . "<br/><br/>";

        $view[file][$i][movie] = true;
    } 
    else if ($view[file][$i][view])
    {
        // 원본 강제 리사이징
        if ($mw_basic[cf_original_width] && $mw_basic[cf_original_height]) {
            if ($view[file][$i][image_width] > $mw_basic[cf_original_width] || $view[file][$i][image_height] > $mw_basic[cf_original_height]) {
                $file = "$file_path/{$view[file][$i][file]}";
                mw_make_thumbnail($mw_basic[cf_original_width], $mw_basic[cf_original_height], $file, $file, true);
                if ($mw_basic[cf_watermark_use]) mw_watermark_file($file);
                $size = getImageSize($file);
                $view[file][$i][image_width] = $size[0];
                $view[file][$i][image_height] = $size[1];
                sql_query("update $g4[board_file_table] set bf_width = '$size[0]', bf_height = '$size[1]',
                    bf_filesize = '".filesize($file)."'
                    where bo_table = '$bo_table' and wr_id = '$wr_id' and bf_file = '{$view[file][$i][file]}'");
            }
        }
        // 이미지 크기 조절
        if ($board[bo_image_width] < $view[file][$i][image_width]) {
            $img_width = $board[bo_image_width];
        } else {
            $img_width = $view[file][$i][image_width];
        }
        $view[file][$i][view] = str_replace("<img", "<img width=\"{$img_width}\"", $view[file][$i][view]);

        // 이미지 저장 방지
        if ($mw_basic[cf_image_save_close])
            $view[file][$i][view] = str_replace("<img", "<img oncontextmenu=\"return false\" style=\"-webkit-touch-callout:none\" ", $view[file][$i][view]);

        // 워터마크 이미지 출력
        if ($mw_basic[cf_watermark_use]) {
            preg_match("/src='([^']+)'/iUs", $view[file][$i][view], $match);
            $watermark_file = mw_watermark_file($match[1]);
            $view[file][$i][view] = str_replace($match[1], $watermark_file, $view[file][$i][view]);
        }

	if ($mw_basic[cf_exif]) {
	    $view[file][$i][view] = str_replace("image_window(this)", "show_exif($i, this, event)", $view[file][$i][view]);
	    $view[file][$i][view] = str_replace("title=''", "title='클릭하면 메타데이터를 보실 수 있습니다.'", $view[file][$i][view]);
        } else if($mw_basic[cf_no_img_ext]) { // 이미지 확대 사용 안함
	    $view[file][$i][view] = str_replace("onclick='image_window(this);'", "", $view[file][$i][view]);
	    $view[file][$i][view] = str_replace("style='cursor:pointer;'", "", $view[file][$i][view]);
	} else {
	    $view[file][$i][view] = str_replace("onclick='image_window(this);'", 
		"onclick='mw_image_window(this, {$view[file][$i][image_width]}, {$view[file][$i][image_height]});'", $view[file][$i][view]);
	    // 제나빌더용 (그누보드 원본수정으로 인해 따옴표' 가 없음;)
	    $view[file][$i][view] = str_replace("onclick=image_window(this);", 
		"onclick='mw_image_window(this, {$view[file][$i][image_width]}, {$view[file][$i][image_height]});'", $view[file][$i][view]); 
	}
        echo $view[file][$i][view] . "<br/><br/>";
        if (trim($view[file][$i][content]))
            echo $view[file][$i][content] . "<br/><br/>";
    }
    else if ($mw_basic[cf_iframe_level] and $mw_basic[cf_iframe_level] <= $mb[mb_level]) {
        if (strstr($mw_basic['cf_multimedia'], '/image/') && preg_match("/\.($config[cf_image_extension])$/i", $view['file'][$i]['file'])) {
            echo mw_file_view($view['file'][$i]['path'].'/'.$view['file'][$i]['file'], $view)."<br><br>";
        }
        else if (strstr($mw_basic['cf_multimedia'], '/flash/') && preg_match("/\.($config[cf_flash_extension])$/i", $view['file'][$i]['file'])) {
            echo mw_file_view($view['file'][$i]['path'].'/'.$view['file'][$i]['file'], $view)."<br><br>";
        }
    }
}
$file_viewer = ob_get_contents();
ob_end_clean();

// 링크 첨부
$link_file_viewer = '';
for ($i=1; $i<=$g4['link_count']; $i++) {
    if (strstr($mw_basic['cf_multimedia'], '/youtube/') && preg_match("/youtu/i", $view['link'][$i])) {
        $link_file_viewer .= mw_youtube($view['link'][$i])."<br><br>";
        //$view['link'][$i] = '';
    }
    elseif (strstr($mw_basic['cf_multimedia'], '/youtube/') && preg_match("/vimeo/i", $view['link'][$i])) {
        $link_file_viewer .= mw_vimeo($view['link'][$i])."<br><br>";
        //$view['link'][$i] = '';
    }
    elseif (strstr($mw_basic['cf_multimedia'], '/link_movie/') && preg_match("/\.($config[cf_movie_extension])$/i", $view['link'][$i])) {
        $link_file_viewer .= mw_jwplayer($view['link'][$i])."<br><br>";
        $view['link'][$i] = '';
    }
    else if (strstr($mw_basic['cf_multimedia'], '/link_image/') && preg_match("/\.($config[cf_image_extension])$/i", $view['link'][$i])) {
        $link_file_viewer .= mw_file_view($view['link'][$i], $view)."<br><br>";
        $view['link'][$i] = '';
    }
    else if (strstr($mw_basic['cf_multimedia'], '/link_flash/') && preg_match("/\.($config[cf_flash_extension])$/i", $view['link'][$i])) {
        $link_file_viewer .= mw_file_view($view['link'][$i], $view)."<br><br>";
        $view['link'][$i] = '';
    }
    else if ($mw_basic['cf_youtube_only']) {
        $view['link'][$i] = '';
    }
}
$view[content] = $link_file_viewer . $view[content]; 

// 웹에디터 첨부 이미지 워터마크 처리
if ($mw_basic[cf_watermark_use])
    $view[content] = mw_create_editor_image_watermark($view[content]);

if (!$mw_basic[cf_zzal] && !strstr($view[content], "{이미지:") && !$write['wr_lightbox'])// 파일 출력  
    $view[content] = $file_viewer . $view[content]; 

if ($write[wr_singo] && $write[wr_singo] >= $mw_basic[cf_singo_number] && $mw_basic[cf_singo_write_block]) {
    $content = " <div class='singo_info'> 신고가 접수된 게시물입니다. (신고수 : $write[wr_singo]회)<br/>";
    $content.= " <span onclick=\"btn_singo_view({$view[wr_id]})\" class='btn_singo_block'>여기</span>를 클릭하시면 내용을 볼 수 있습니다.";
    if ($is_admin == "super")
        $content.= " <span class='btn_singo_block' onclick=\"btn_singo_clear({$view[wr_id]})\">[신고 초기화]</span> ";
    $content.= " </div>";
    $content.= " <div id='singo_block_{$view[wr_id]}' class='singo_block'> {$view[content]} </div>";

    $view[wr_subject] = "신고가 접수된 게시물입니다.";
    $view[subject] = $view[wr_subject];
    $view[content] = $content;
}

@include($mw_basic[cf_include_view_top]);

// 컨텐츠샵 멤버쉽
if (function_exists("mw_cash_is_membership") && $member[mb_id] != $write[mb_id]) {
    $is_membership = @mw_cash_is_membership($member[mb_id], $bo_table, "mp_view");
    if ($is_membership == "no")
        ;
    else if ($is_membership != "ok")
        mw_cash_alert_membership($is_membership);
        //alert("$is_membership 회원만 이용 가능합니다.");
}

// 관리자라면 CheckBox 보임
$is_checkbox = false;
if ($member[mb_id] && ($is_admin == "super" || $group[gr_admin] == $member[mb_id] || $board[bo_admin] == $member[mb_id])) 
    $is_checkbox = true;

$prev_wr_subject = str_replace("\"", "'", $prev_wr_subject);
$next_wr_subject = str_replace("\"", "'", $next_wr_subject);

if ($is_admin && strstr($write[wr_option], "secret")) {
    // 잠금 해제 버튼
    $nosecret_href = "javascript:btn_nosecret();";
} else if ($is_admin) {
    // 잠금 버튼
    $secret_href = "javascript:btn_secret();";
}

// 파일로그
if ($mw_basic[cf_download_log] && $is_admin) {
    $download_log_href = "javascript:btn_download_log()";
}

// 링크로그
if ($mw_basic[cf_link_log] && $is_admin) {
    $link_log_href = "javascript:btn_link_log()";
}


// 로그버튼
if ($mw_basic[cf_post_history] && $member[mb_level] >= $mw_basic[cf_post_history_level]) {
    $history_href = "javascript:btn_history($wr_id)";
}

$is_singo_admin = mw_singo_admin($member[mb_id]);

// 신고 버튼
if ($mw_basic[cf_singo]) {
    $singo_href = "javascript:btn_singo($wr_id, $wr_id)";
}

// 인쇄 버튼
if ($mw_basic[cf_print]) {
    $print_href = "javascript:btn_print()";
}

// 쓰기버튼 항상 출력
if ($mw_basic[cf_write_button])
    $write_href = "./write.php?bo_table=$bo_table";

// 글쓰기 버튼에 분류저장
if ($sca && $write_href)
    $write_href .= "&sca=".urlencode($sca);

// 글쓰기 버튼 공지
if ($write_href && $mw_basic[cf_write_notice]) {
    $write_href = "javascript:btn_write_notice('$write_href');";
}

// 스킨설정버튼
$config_href = "javascript:mw_config()";

// RSS 버튼
$rss_href = "";
if ($board[bo_use_rss_view])
    $rss_href = "./rss.php?bo_table=$bo_table";

$view[rich_content] = preg_replace("/{이미지\:([0-9]+)[:]?([^}]*)}/ie", "mw_view_image(\$view, '\\1', '\\2')", $view[content]);

if ($mw_basic[cf_no_img_ext]) { // 이미지 확대 사용 안함
    $view[rich_content] = preg_replace("/name='target_resize_image\[\]' onclick='image_window\(this\)'/iUs", "", $view[rich_content]);

    if ($mw_basic[cf_image_save_close])
        $view[rich_content] = str_replace("<img", "<img oncontextmenu=\"return false\" style=\"-webkit-touch-callout:none\" ", $view[rich_content]);


}
else {
    // 웹에디터 이미지 클릭시 원본 사이즈 조정
    $data = $view[rich_content];
    $path = $size = null;
    preg_match_all("/<img\s+name='target_resize_image\[\]' onclick='image_window\(this\)'.*src=\"(.*)\"/iUs", $data, $matchs);
    for ($i=0; $i<count($matchs[1]); $i++) {
        $match = $matchs[1][$i];
        $no_www = str_replace("www.", "", $g4[url]);
        $path = "";
        if (strstr($match, $g4[url])) {
            $path = str_replace($g4[url], $g4[path], $match);
        } elseif (strstr($match, $no_www)) {
            $path = str_replace($no_www, $g4[path], $match);
        } elseif (substr($match, 0, 1) == "/") {
            $path = $_SERVER[DOCUMENT_ROOT].$match;
        //} else { $path = $match;
        }
        if ($path)
            $size = @getimagesize($path);
        else
            $size = @getimagesize($match);
        if ($size[0] && $size[1]) {
            $match = str_replace("/", "\/", $match);
            $match = str_replace(".", "\.", $match);
            $match = str_replace("+", "\+", $match);
            $pattern = "/(onclick=[\'\"]{0,1}image_window\(this\)[\'\"]{0,1}) (.*)(src=\"$match\")/iU";
            $replacement = "onclick='mw_image_window(this, $size[0], $size[1])' $2$3";

            // 이미지 저장 방지
            if ($mw_basic[cf_image_save_close])
                $replacement .= "oncontextmenu=\"return false\" style=\"-webkit-touch-callout:none\"";

            if ($size[0] > $board[bo_image_width])
                $replacement .= " width=\"$board[bo_image_width]\"";
            $data = @preg_replace($pattern, $replacement, $data);
        }
    }
    $view[rich_content] = $data;
}

// 추천링크 방지
$view[rich_content] = preg_replace("/bbs\/good\.php\?/i", "#", $view[rich_content]);


// 조회수, 추천수, 비추천수 컴마
if ($mw_basic[cf_comma]) {
    $view[wr_hit] = number_format($view[wr_hit]);
    $view[wr_good] = number_format($view[wr_good]);
    $view[wr_nogood] = number_format($view[wr_nogood]);
}

// 컨텐츠샵
$mw_price = "";
if ($mw_basic[cf_contents_shop]) {
    if (!$view[wr_contents_price])
	$mw_price = "무료";
    else
	$mw_price = $mw_cash[cf_cash_name] . " " . number_format($view[wr_contents_price]).$mw_cash[cf_cash_unit];
}

if ($mw_basic[cf_attribute] == "1:1" && !$is_admin) {
    $prev_href = '';
    $next_href = '';
    $prev_wr_subject = '';
    $next_wr_subject = '';
}

// 전체목록보이기 사용 에서도 이전글, 다음글 버튼 출력
if ($mw_basic[cf_attribute] != "1:1" && (!$prev_href || !$next_href))
{
   if ($sql_search) {
        if (trim(substr($sql_search, 0, 4)) != "and") {
            $sql_search = " and " . $sql_search;
        }
    }

    // 윗글을 얻음
    $sql = " select wr_id, wr_subject from $write_table where wr_is_comment = 0 and wr_num = '$write[wr_num]' and wr_reply < '$write[wr_reply]' $sql_search order by wr_num desc, wr_reply desc limit 1 ";
    $prev = sql_fetch($sql);
    // 위의 쿼리문으로 값을 얻지 못했다면
    if (!$prev[wr_id])     {
        $sql = " select wr_id, wr_subject from $write_table where wr_is_comment = 0 and wr_num < '$write[wr_num]' $sql_search order by wr_num desc, wr_reply desc limit 1 ";
        $prev = sql_fetch($sql);
    }

    // 아래글을 얻음
    $sql = " select wr_id, wr_subject from $write_table where wr_is_comment = 0 and wr_num = '$write[wr_num]' and wr_reply > '$write[wr_reply]' $sql_search order by wr_num, wr_reply limit 1 ";
    $next = sql_fetch($sql);
    // 위의 쿼리문으로 값을 얻지 못했다면
    if (!$next[wr_id]) {
        $sql = " select wr_id, wr_subject from $write_table where wr_is_comment = 0 and wr_num > '$write[wr_num]' $sql_search order by wr_num, wr_reply limit 1 ";
        $next = sql_fetch($sql);
    }

    // 이전글 링크
    $prev_href = "";
    if ($prev[wr_id]) {
        $prev_wr_subject = get_text(cut_str($prev[wr_subject], 255));
        $prev_href = "./board.php?bo_table=$bo_table&wr_id=$prev[wr_id]&page=$page" . $qstr;
    }

    // 다음글 링크
    $next_href = "";
    if ($next[wr_id]) {
        $next_wr_subject = get_text(cut_str($next[wr_subject], 255));
        $next_href = "./board.php?bo_table=$bo_table&wr_id=$next[wr_id]&page=$page" . $qstr;
    }
}

$view[rich_content] = preg_replace_callback("/\[code\](.*)\[\/code\]/iUs", "_preg_callback", $view[rich_content]);

// 리워드
if ($mw_basic[cf_reward]) {
    $reward = sql_fetch("select * from $mw[reward_table] where bo_table = '$bo_table' and wr_id = '$wr_id'");
    if ($reward[re_edate] != "0000-00-00" && $reward[re_edate] < $g4[time_ymd]) { // 날짜 지나면 종료
        sql_query("update $mw[reward_table] set re_status = '' where bo_table = '$bo_table' and wr_id = '$wr_id'");
        $reward[re_status] = '';
    }
    else
        //$reward[url] = mw_get_reward_url($reward);
        $reward[url] = "$g4[path]/plugin/reward/go.php?bo_table=$bo_table&wr_id=$wr_id";

    if ($is_member)
        $reward[script] = "window.open('$reward[url]');";
    else
        $reward[script] = "alert('로그인 후 이용해주세요.');";
}

// 분류 사용 여부
$is_category = false;
if ($board[bo_use_category]) 
{
    $is_category = true;
    $category_location = "./board.php?bo_table=$bo_table&sca=";
    $category_option = get_category_option($bo_table); // SELECT OPTION 태그로 넘겨받음

    if ($mw_basic[cf_default_category] && !$sca) $sca = $mw_basic[cf_default_category];
}

// 분류 선택 또는 검색어가 있다면
if (!$total_count && ($sca || $stx))
{
    $sql_search = get_sql_search($sca, $sfl, $stx, $sop);

    // 가장 작은 번호를 얻어서 변수에 저장 (하단의 페이징에서 사용)
    $sql = " select MIN(wr_num) as min_wr_num from $write_table ";
    $row = sql_fetch($sql);
    $min_spt = $row[min_wr_num];

    if (!$spt) $spt = $min_spt;

    $sql_search .= " and (wr_num between '".$spt."' and '".($spt + $config[cf_search_part])."') ";

    // 원글만 얻는다. (코멘트의 내용도 검색하기 위함)
    $sql = " select distinct wr_parent from $write_table where $sql_search ";
    $result = sql_query($sql);
    $total_count = mysql_num_rows($result);
} 
else 
{
    $sql_search = "";

    $total_count = $board[bo_count_write];
}

// 자동치환
$view[rich_content] = mw_reg_str($view[rich_content]);
$view[wr_subject] = mw_reg_str($view[wr_subject]);
$view[wr_subject] = bc_code($view[wr_subject], 0, 0);

$prev_wr_subject = bc_code($prev_wr_subject, 0, 0);
$prev_wr_subject = mw_reg_str($prev_wr_subject);

$next_wr_subject = bc_code($next_wr_subject, 0, 0);
$next_wr_subject = mw_reg_str($next_wr_subject);

// IP보이기 사용 여부
$ip = "";
$is_ip_view = $board[bo_use_ip_view];
if ($is_admin) {
    $is_ip_view = true;
    $ip = $write[wr_ip];
} else if ($mw_basic[cf_attribute] == 'anonymous') {
    $ip = "";
} else if ($view[wr_anonymous]) {
    $ip = "";
} else if ($view[mb_id] == $config[cf_admin]) {
    $ip = "";
} else // 관리자가 아니라면 IP 주소를 감춘후 보여줍니다.
    $ip = preg_replace("/([0-9]+).([0-9]+).([0-9]+).([0-9]+)/", "\\1.♡.\\3.\\4", $write[wr_ip]);

// 짧은 글주소 사용 - 자체도메인
$shorten = '';
if ($mw_basic[cf_shorten])
    $shorten = "$g4[url]/$bo_table/$wr_id";

$new_time = date("Y-m-d H:i:s", $g4[server_time] - ($board[bo_new] * 3600));
$row = sql_fetch(" select count(*) as cnt from $write_table where wr_is_comment = 0 and wr_datetime >= '$new_time' ");
$new_count = $row[cnt];

// 이미지 링크
$view[rich_content] = preg_replace("/\[\<a\s*href\=\"(http|https|ftp)\:\/\/([^[:space:]]+)\.(gif|png|jpg|jpeg|bmp)\"\s*[^\>]*\>.*\<\/a\>\]/iUs", "<img src='$1://$2.$3' id='target_resize_image[]' onclick='image_window(this);'>", $view[rich_content]);
$view[rich_content] = preg_replace("/\[(http|https|ftp)\:\/\/([^[:space:]]+)\.(gif|png|jpg|jpeg|bmp)\]/iUs", "<img src='$1://$2.$3' id='target_resize_image[]' onclick='image_window(this);'>", $view[rich_content]);

// 최고, 그룹관리자라면 글 복사, 이동 가능
$copy_href = $move_href = "";
if ($write[wr_reply] == "" && ($is_admin == "super" || $is_admin == "group")) {
    $copy_href = "javascript:win_open('$pc_skin_path/move.php?sw=copy&bo_table=$bo_table&wr_id=$wr_id&page=$page".$qstr."', 'boardcopy', 'left=50, top=50, width=500, height=550, scrollbars=1');";
    $move_href = "javascript:win_open('$pc_skin_path/move.php?sw=move&bo_table=$bo_table&wr_id=$wr_id&page=$page".$qstr."', 'boardmove', 'left=50, top=50, width=500, height=550, scrollbars=1');";
}

// 배추코드
$view[rich_content] = bc_code($view[rich_content], 1, 0);
if (strstr($write[wr_option], "html")) {
    $view[rich_content] = mw_tag_debug($view[rich_content]);
}
$view[rich_content] = mw_set_sync_tag($view[rich_content]);

if ($mw_basic[cf_iframe_level] && $mw_basic[cf_iframe_level] <= $mb[mb_level]) {
    $view[rich_content] = mw_special_tag($view[rich_content]);
}

if ($mw_basic[cf_umz]) { // 짧은 글주소 사용 
    //if ($write[wr_umz] == "") {
    if ($mw_basic[cf_umz2]) {
        if (substr(trim($write[wr_umz]), 0, strlen($mw_basic[cf_umz2])+7) != "http://$mw_basic[cf_umz2]") {
            $url = "$g4[url]/$g4[bbs]/board.php?bo_table=$bo_table&wr_id=$wr_id";
            $umz = umz_get_url($url);
            sql_query("update $write_table set wr_umz = '$umz' where wr_id = '$wr_id'");
            $view[wr_umz] = $umz;
        }
    } else {
        if (substr(trim($write[wr_umz]), 0, 10) != "http://umz") {
            $url = "$g4[url]/$g4[bbs]/board.php?bo_table=$bo_table&wr_id=$wr_id";
            $umz = umz_get_url($url);
            sql_query("update $write_table set wr_umz = '$umz' where wr_id = '$wr_id'");
            $view[wr_umz] = $umz;
        }
    }
}

$view_sns = null;

if ($mw_basic[cf_sns])
{
    $view_url = "$g4[url]/$g4[bbs]/board.php?bo_table=$bo_table&wr_id=$wr_id";

    if ($mw_basic[cf_umz] && $view[wr_umz]) $sns_url = $view[wr_umz];
    else if ($mw_basic[cf_shorten]) $sns_url = $shorten;
    else if ($trackback_url) $sns_url = $trackback_url;
    else $sns_url = $view_url;

    $sns_url = trim($sns_url);

    $me2day_url = '';//"http://me2day.net/posts/new?new_post[body]=".urlencode(set_utf8($view[wr_subject])." - \"$sns_url\":$sns_url");
    //$twitter_url = "http://twitter.com/home?status=".urlencode(set_utf8($view[wr_subject])." - $sns_url");
    //$twitter_url = "http://twitter.com/?status=".str_replace("+", " ", urlencode(set_utf8($view[wr_subject])." - $sns_url"));
    $twitter_url = "https://twitter.com/intent/tweet?source=webclient&text=".str_replace("+", " ", urlencode(set_utf8($view[wr_subject])." - $sns_url"));
    $facebook_url = "http://www.facebook.com/share.php?u=".urlencode($view_url);
    $yozm_url = ''; //"http://yozm.daum.net/api/popup/prePost?sourceid=41&link={$sns_url}&prefix=".urlencode(set_utf8($view[wr_subject]));
    $cy_url = "javascript:window.open('http://csp.cyworld.com/bi/bi_recommend_pop.php?url={$sns_url}', ";
    $cy_url.= "'recom_icon_pop', 'width=400,height=364,scrollbars=no,resizable=no');";
    $naver_url = "http://bookmark.naver.com/post?ns=1&title=".urlencode(set_utf8($view[wr_subject]))."&url={$sns_url}";
    $google_url = "http://www.google.com//bookmarks/mark?op=add&title=".urlencode(set_utf8($view[wr_subject]))."&bkmk={$sns_url}";
    $kakao_url = "kakaolink://sendurl?msg=".urlencode(set_utf8($view[wr_subject]))."&appver=1&appid={$_SERVER[HTTP_HOST]}&url=".urlencode($sns_url);
    $kakaostory_url = "storylink://posting?post=".urlencode(set_utf8($view[wr_subject])).urlencode("\n".$sns_url)."&apiver=1.0&appname=".urlencode($config[cf_title])."&appver=1&appid={$_SERVER[HTTP_HOST]}";

    $facebook_like_href = urlencode($view_url);

    ob_start();
    ?>
    <!--<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>-->
    <? if (strstr($mw_basic[cf_sns], '/me2day/')) { ?>
    <div><a href="<?=$me2day_url?>" target="_blank" title="이 글을 미투데이로 보내기"><img 
        src="<?=$pc_skin_path?>/img/send_me2day.png" border="0"></a></div>
    <? } ?>
    <? if (strstr($mw_basic[cf_sns], '/twitter/')) { ?>
    <div><a href="<?=$twitter_url?>" target="_blank" title="이 글을 트위터로 보내기"><img
        src="<?=$pc_skin_path?>/img/send_twitter.png" border="0"></a></div>
    <? } ?>
    <? if (strstr($mw_basic[cf_sns], '/facebook/')) { ?>
    <div><a href="<?=$facebook_url?>" target="_blank" title="이 글을 페이스북으로 보내기"><img
        src="<?=$pc_skin_path?>/img/send_facebook.png" border="0"></a></div>
    <? } ?>
    <? if (strstr($mw_basic[cf_sns], '/yozm/') && $yozm_url) { ?>
    <div><a href="<?=$yozm_url?>" target="_blank" title="이 글을 요즘으로 보내기"><img
        src="<?=$pc_skin_path?>/img/send_yozm.png" border="0"></a></div>
    <? } ?>
    <? if (strstr($mw_basic[cf_sns], '/cyworld/')) { ?>
    <div><img src="<?=$pc_skin_path?>/img/send_cy.png" border="0" onclick="<?=$cy_url?>" style="cursor:pointer" title="싸이월드 공감"></div>
    <? } ?>
    <? if (strstr($mw_basic[cf_sns], '/naver/')) { ?>
    <div><a href="<?=$naver_url?>" target="_blank" title="이 글을 네이버 북마크로 보내기"><img
        src="<?=$pc_skin_path?>/img/send_naver.png" border="0"></a></div>
    <? } ?>
    <? if (strstr($mw_basic[cf_sns], '/google/')) { ?>
    <div><a href="<?=$google_url?>" target="_blank" title="이 글을 구글 북마크로 보내기"><img
        src="<?=$pc_skin_path?>/img/send_google.png" border="0"></a></div>
    <? } ?>
    <? if (strstr(strtolower($_SERVER[HTTP_USER_AGENT]), "mobile") or $is_admin) { ?>
        <?
        if (!strstr(strtolower($_SERVER[HTTP_USER_AGENT]), "mobile"))
            $kakao_url = "#;\" onclick=\"javascript:alert('모바일 기기에서만 작동합니다.');";

        $kakao_name = mw_kakao_str($config[cf_title], 50);
        $kakao_subject = mw_kakao_str($view[wr_subject], 50);
        $kakao_content = mw_kakao_str($view[wr_content], 50);

        $kakao_thumb = "{$g4[url]}/data/file/{$bo_table}/thumbnail/{$wr_id}";
        if ($mw_basic['cf_thumb_jpg'])
            $kakao_thumb .= ".jpg";
        ?>
        <? if (strstr($mw_basic[cf_sns], '/kakaostory/')) { ?>
        <script src="<?=$pc_skin_path?>/mw.js/kakao.link.js"></script>
        <script>
        function kakaostorylink()
        {
            <? if (!strstr(strtolower($_SERVER[HTTP_USER_AGENT]), "mobile")) { ?>
            alert("모바일 기기에서만 작동합니다."); return;
            <? } ?>
            kakao.link("story").send({
                post : "<?=$kakao_subject?>\n<?=$sns_url?>",
                appid : "<?=$_SERVER[HTTP_HOST]?>",
                appver : "1.0",
                appname : "<?=$kakao_name?>",
                urlinfo : JSON.stringify({title:"<?=$kakao_subject?>", desc:"<?=$kakao_content?>", imageurl:["<?=$kakao_thumb?>"], type:"article"})
            });
        }
        </script>

        <!--<div><a href="<?=$kakaostory_url?>"><img src="<?=$pc_skin_path?>/img/send_kakaostory.png" valign="middle"></a></div>-->
        <div><a href="#;" onclick="kakaostorylink()"><img src="<?=$pc_skin_path?>/img/send_kakaostory.png" valign="middle" style="cursor:pointer;"></a></div>
        <? } ?>
        <? if (strstr($mw_basic[cf_sns], '/kakao/')) { ?>
        <div><a href="<?=$kakao_url?>"><img src="<?=$pc_skin_path?>/img/send_kakaotalk.png" valign="middle"></a></div>
        <? } ?>
    <? } ?>

    <? if (strstr($mw_basic[cf_sns], '/facebook_good/')) { ?>
    <div><iframe src="http://www.facebook.com/plugins/like.php?href=<?=$facebook_like_href?>&amp;layout=button_count&amp;show_faces=true&amp;width=450&amp;action=like&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:21px;" allowTransparency="true"></iframe></div>
    <? } ?>

    <? if (strstr($mw_basic[cf_sns], '/google_plus/')) { ?>
    <!-- +1 버튼이 렌더링되기를 원하는 곳에 이 태그를 넣습니다. -->
    <div><g:plusone size="medium" annotation="inline" width="150"></g:plusone></div>

    <!-- 적절한 곳에 이 렌더링 호출을 넣습니다. -->
    <script type="text/javascript">
      window.___gcfg = {lang: 'ko'};

      (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/plusone.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
      })();
    </script>
    <? } ?>

    <?
    $view_sns = ob_get_contents();
    ob_end_clean();
}

$google_map_code = null;
$google_map_is_view = false;
if ($mw_basic[cf_google_map] && trim($write[wr_google_map])) {
    ob_start();
    ?>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true&language=ko"></script>
    <script type="text/javascript" src="<?=$pc_skin_path?>/mw.js/mw.google.js"></script>
    <script type="text/javascript">
    $(document).ready(function () {
        mw_google_map("google_map", "<?=addslashes($write[wr_google_map])?>");
    });
    </script>
    <div id="google_map" style="width:100%; height:300px; border:1px solid #ccc; margin:10px 0 10px 0;"></div>
    <?
    $google_map_code = ob_get_contents();
    ob_end_clean();

    if (strstr($view[rich_content], "{구글지도}")) {
        $view[rich_content] = preg_replace("/\{구글지도\}/", $google_map_code, $view[rich_content]);
        $google_map_is_view = true;
    }
}

if ($mw_basic[cf_contents_shop] == '2' and $write[wr_contents_price]) // 배추 컨텐츠샵 내용보기 결제
{
    $is_per = true;
    $is_per_msg = '예외오류';

    if (!$is_member) $is_per = false;

    $con = mw_is_buy_contents($member[mb_id], $bo_table, $wr_id);
    if (!$con and $is_per) $is_per = false;

    if (!$is_per) {

        $view[wr_contents_preview] = conv_content($view[wr_contents_preview], $html);
        ob_start();
        ?>
        <div class="contents_shop_view">
            <?=conv_content($view[wr_contents_preview], $html)?>
            <div style="margin:20px 0 0 0;"><input type="button" class="btn1" value="내용보기" onclick="buy_contents('<?=$bo_table?>','<?=$wr_id?>', 0)"/></div>
        </div>
        <script type="text/javascript">
        function contents_shop_view() {
        }
        </script>
        <?
        $contents_shop_view = ob_get_contents();
        ob_end_clean();

        $view[wr_content] = $contents_shop_view;
        $view[content] = $view[wr_content];
        $view[rich_content] = $view[wr_content];
        $write[wr_content] = $view[wr_content];
        $write[content] = $view[wr_content];
        $view[file] = null;
    }
}

$view[rich_content] = mw_youtube_content($view[rich_content]);

if (function_exists("mw_moa_read"))
    mw_moa_read($member['mb_id'], $bo_table, $wr_id);

$ob_exam = '';
$ob_exam_flag = false;
if ($mw_basic['cf_exam']) {
    if (file_exists("{$exam_path}/view.skin.php")) {
        ob_start();
        include("{$exam_path}/view.skin.php");
        $ob_exam = ob_get_contents();
        ob_end_clean();

        if (preg_match("/\[시험문제\]/i", $view[rich_content])) {
            $ob_exam_flag = true;
            $view[rich_content] = preg_replace("/\[시험문제\]/i", $ob_exam, $view[rich_content]);
        }
    }
}

$ob_marketdb = '';
$ob_marketdb_flag = false;
if ($mw_basic['cf_marketdb'] and $write['wr_marketdb']) { 
    if (file_exists("{$marketdb_path}/view.skin.php")) {
        ob_start();
        include("{$marketdb_path}/view.skin.php");
        $ob_marketdb = ob_get_contents();
        ob_end_clean();

        if (preg_match("/\[마케팅DB\]/i", $view[rich_content])) {
            $ob_marketdb_flag = true;
            $view[rich_content] = preg_replace("/\[마케팅DB\]/i", $ob_marketdb, $view[rich_content]);
        }
    }
}

if (!$mw_basic['cf_time_view'])
    $mw_basic['cf_time_view'] = "Y-m-d (w) H:i";

$view['datetime2'] = mw_get_date($write['wr_datetime'], $mw_basic['cf_time_view']);

