<?
// 이 파일은 새로운 파일 생성시 반드시 포함되어야 함
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

$begin_time = get_microtime();

if (!$g4['title'])
    $g4['title'] = $config['cf_title'];

// 쪽지를 받았나?
if ($member['mb_memo_call']) {
    $mb = get_member($member[mb_memo_call], "mb_nick");
    sql_query(" update {$g4[member_table]} set mb_memo_call = '' where mb_id = '$member[mb_id]' ");

    alert($mb[mb_nick]."님으로부터 쪽지가 전달되었습니다.", $_SERVER[REQUEST_URI]);
}


// 현재 접속자
//$lo_location = get_text($g4[title]);
//$lo_location = $g4[title];
// 게시판 제목에 ' 포함되면 오류 발생
$lo_location = addslashes($g4['title']);
if (!$lo_location)
    $lo_location = $_SERVER['REQUEST_URI'];
//$lo_url = $g4[url] . $_SERVER['REQUEST_URI'];
$lo_url = $_SERVER['REQUEST_URI'];
if (strstr($lo_url, "/$g4[admin]/") || $is_admin == "super") $lo_url = "";

// 자바스크립트에서 go(-1) 함수를 쓰면 폼값이 사라질때 해당 폼의 상단에 사용하면
// 캐쉬의 내용을 가져옴. 완전한지는 검증되지 않음
header("Content-Type: text/html; charset=$g4[charset]");
$gmnow = gmdate("D, d M Y H:i:s") . " GMT";
header("Expires: 0"); // rfc2616 - Section 14.21
header("Last-Modified: " . $gmnow);
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: pre-check=0, post-check=0, max-age=0"); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
/*
// 만료된 페이지로 사용하시는 경우
header("Cache-Control: no-cache"); // HTTP/1.1
header("Expires: 0"); // rfc2616 - Section 14.21
header("Pragma: no-cache"); // HTTP/1.0
*/
?>
<?php if (strstr($_SERVER['PHP_SELF'], $g4['admin'])) { ?>
<!-- <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"> -->
<html>
<?php } else { ?>
<!doctype html>
<html lang="ko">
<?php } ?>
<head>
<meta charset="<?=$g4['charset']?>">
<meta http-equiv="content-type" content="text/html; charset=<?=$g4['charset']?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php
if ($bo_table && $wr_id)
{
    $ogp_thumb = $g4['path']."/data/file/".$bo_table."/thumbnail/".$wr_id.".jpg";

    if (!is_file($ogp_thumb))
        $ogp_thumb = $g4['path']."/data/file/".$bo_table."/thumbnail/".$wr_id;

    if (!is_file($ogp_thumb))
        $ogp_thumb = $g4['path']."/data/file/".$bo_table."/thumb/".$wr_id;

    if (!is_file($ogp_thumb))
        $ogp_thumb = '';

    $ogp_thumb = str_replace($g4['path'], $g4['url'], $ogp_thumb);

    $ogp_title = trim(cut_str(strip_tags($write['wr_subject']), 255));
    $ogp_site_name = trim(cut_str(strip_tags($config['cf_title']), 255));

    $ogp_url = $g4['url']."/".$g4['bbs']."/board.php?bo_table=".$bo_table."&wr_id=".$wr_id;
    if (function_exists("mw_seo_url"))
        $ogp_url = mw_seo_url($bo_table, $wr_id);

    $ogp_description = cut_str(str_replace("\n", " ", strip_tags($write['wr_content'])), 255);
    $ogp_description = trim(preg_replace("/{이미지:[0-9]+}/iUs", "", $ogp_description));

    $ogp = "<meta property=\"og:image\" content=\"{$ogp_thumb}\"/>\n";
    $ogp.= "<meta property=\"og:title\" content=\"{$ogp_title}\"/>\n";
    $ogp.= "<meta property=\"og:site_name\" content=\"{$ogp_site_name}\"/>\n";
    $ogp.= "<meta property=\"og:url\" content=\"{$ogp_url}\"/>\n";
    $ogp.= "<meta property=\"og:description\" content=\"{$ogp_description}\"/>\n";
    echo $ogp;
}
?>
<title><?=$g4['title']?></title>
<link rel="stylesheet" href="<?=$g4['path']?>/style.css" type="text/css">
</head>
<script type="text/javascript">
// 자바스크립트에서 사용하는 전역변수 선언
var g4_path      = "<?=$g4['path']?>";
var g4_bbs       = "<?=$g4['bbs']?>";
var g4_bbs_img   = "<?=$g4['bbs_img']?>";
var g4_url       = "<?=$g4['url']?>";
var g4_is_member = "<?=$is_member?>";
var g4_is_admin  = "<?=$is_admin?>";
var g4_bo_table  = "<?=isset($bo_table)?$bo_table:'';?>";
var g4_sca       = "<?=isset($sca)?$sca:'';?>";
var g4_charset   = "<?=$g4['charset']?>";
var g4_cookie_domain = "<?=$g4['cookie_domain']?>";
var g4_is_gecko  = navigator.userAgent.toLowerCase().indexOf("gecko") != -1;
var g4_is_ie     = navigator.userAgent.toLowerCase().indexOf("msie") != -1;
<? if ($is_admin) { echo "var g4_admin = '{$g4['admin']}';"; } ?>
</script>
<script type="text/javascript" src="<?=$g4['path']?>/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="<?=$g4['path']?>/js/common.js"></script>
<body topmargin="0" leftmargin="0" <?=isset($g4['body_script']) ? $g4['body_script'] : "";?>>
<a name="g4_head"></a>
