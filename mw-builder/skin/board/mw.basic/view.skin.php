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

$mw_is_view = true;

include_once("$board_skin_path/mw.lib/mw.skin.basic.lib.php");
include("view_head.skin.php");
?>
<script type="text/javascript">
document.title = "<?=strip_tags(addslashes($view[wr_subject]))?>";
</script>
<!--
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.min.js"></script>
-->
<link type="text/css" href="<?=$board_skin_path?>/mw.js/ui-lightness/jquery-ui-1.8.19.custom.css" rel="stylesheet" />
<script type="text/javascript" src="<?=$board_skin_path?>/mw.js/jquery-ui-1.8.19.custom.min.js"></script>
<script type="text/javascript" src="<?=$board_skin_path?>/mw.js/tooltip.js"></script>

<script type="text/javascript" src="<?=$board_skin_path?>/mw.js/syntaxhighlighter/scripts/shCore.js"></script>
<script type="text/javascript" src="<?=$board_skin_path?>/mw.js/syntaxhighlighter/scripts/shBrushPhp.js"></script>
<link type="text/css" rel="stylesheet" href="<?=$board_skin_path?>/mw.js/syntaxhighlighter/styles/shCore.css"/>
<link type="text/css" rel="stylesheet" href="<?=$board_skin_path?>/mw.js/syntaxhighlighter/styles/shThemeDefault.css"/>
<script type="text/javascript">
SyntaxHighlighter.config.clipboardSwf = '<?=$board_skin_path?>/mw.js/syntaxhighlighter/scripts/clipboard.swf';
SyntaxHighlighter.all();
</script>
<link rel="stylesheet" href="<?=$board_skin_path?>/style.common.css?<?=filemtime("$board_skin_path/style.common.css")?>" type="text/css">

<script type="text/javascript" src="<?=$board_skin_path?>/mw.js/ZeroClipboard.js"></script>
<script type="text/javascript">
function initClipboard() {
    var clip = new ZeroClipboard(document.getElementById("post_url_copy"), {
        moviePath: "<?=$board_skin_path?>/mw.js/ZeroClipboard.swf"
    });

    clip.on( "load", function(client) {
        // alert( "movie is loaded" );
        clip.setText($("#post_url").text());

        client.on( "complete", function(client, args) {
        // `this` is the element that was clicked
            clip.setText($("#post_url").text());
            alert("클립보드에 복사되었습니다. \'Ctrl+V\'를 눌러 붙여넣기 해주세요.");
        });
    });
}
$(document).ready(function () {
    if ($("#post_url").text()) {
        initClipboard();
    }
});
</script>

<? if ($mw_basic[cf_source_copy]) { // 출처 자동 복사 ?>
<? $copy_url = $shorten ? $shorten : set_http("{$g4[url]}/{$g4[bbs]}/board.php?bo_table={$bo_table}&wr_id={$wr_id}"); ?>
<script type="text/javascript" src="<?=$board_skin_path?>/mw.js/autosourcing.open.compact.js"></script>
<style type="text/css">
DIV.autosourcing-stub { display:none }
DIV.autosourcing-stub-extra { position:absolute; opacity:0 }
</style>
<script type="text/javascript">
AutoSourcing.setTemplate("<p style='margin:11px 0 7px 0;padding:0'> <a href='{link}' target='_blank'> [출처] {title} - {link}</a> </p>");
AutoSourcing.setString(<?=$wr_id?> ,"<?=$config[cf_title];//$view[wr_subject]?>", "<?=$view[wr_name]?>", "<?=$copy_url?>");
AutoSourcing.init( 'view_%id%' , true);
</script>
<? } ?>

<? if ($mw_basic[cf_content_align] && $write[wr_align]) { ?>
<style>
#view_content { text-align:<?=$write[wr_align]?>; }
</style>
<? } ?>

<!-- 게시글 보기 시작 -->
<table width="<?=$bo_table_width?>" align="center" cellpadding="0" cellspacing="0"><tr><td id=mw_basic>

<?  if ($mw_basic['cf_include_head'] && file_exists($mw_basic['cf_include_head']) && strstr($mw_basic[cf_include_head_page], '/v/'))
    include_once($mw_basic[cf_include_head]); ?>

<? include_once("$board_skin_path/mw.proc/mw.list.hot.skin.php"); ?>

<!-- 분류 셀렉트 박스, 게시물 몇건, 관리자화면 링크 -->
<table width="100%">
<tr height="25">
    <td width="30%">
        <form name="fcategory_view" method="get" style="margin:0;">
        <? if ($is_category && !$mw_basic[cf_category_tab]) { ?>
            <select name=sca onchange="location='<?=$category_location?>'+this.value;">
            <? if (!$mw_basic[cf_default_category]) { ?> <option value=''>전체</option> <? } ?>
            <?=$category_option?>
            </select>
        <? } ?>
        </form>
    </td>
    <td align="right">
        <? if ($mw_basic[cf_social_commerce]) { ?>
        <span class=mw_basic_total style="cursor:pointer;" onclick="win_open('<?=$social_commerce_path?>/order_list.php?bo_table=<?=$bo_table?>', 'order_list', 'width=800,height=600,scrollbars=1');">[주문내역]</span>
        <? } ?>
        <? include("$board_skin_path/mw.proc/mw.smart-alarm-config.php") ?>
        <span class=mw_basic_total>총 게시물 <?=number_format($total_count)?>건, 최근 <?=number_format($new_count)?> 건</span>
        <? if ($is_admin && $mw_basic[cf_collect] == 'rss' && file_exists("$g4[path]/plugin/rss-collect/_lib.php")) {?>
        <img src="<?=$g4[path]?>/plugin/rss-collect/img/btn_collect.png" align="absmiddle" style="cursor:pointer;" onclick="win_open('<?=$g4[path]?>/plugin/rss-collect/config.php?bo_table=<?=$bo_table?>', 'rss_collect', 'width=800,height=600,scrollbars=1')">
        <? } ?>
        <? if ($is_admin && $mw_basic[cf_collect] == 'youtube' && file_exists("$g4[path]/plugin/youtube-collect/_lib.php")) {?>
        <img src="<?=$g4[path]?>/plugin/youtube-collect/img/btn_collect.png" align="absmiddle" style="cursor:pointer;" onclick="win_open('<?=$g4[path]?>/plugin/youtube-collect/config.php?bo_table=<?=$bo_table?>', 'youtube_collect', 'width=800,height=600,scrollbars=1')">
        <? } ?>
        <a style="cursor:pointer" class="tooltip"
            title="읽기:<?=$board[bo_read_point]?>,
쓰기:<?=$board[bo_write_point]?><?
if ($mw_basic[cf_contents_shop_write]) { echo " ($mw_cash[cf_cash_name]$mw_basic[cf_contents_shop_write_cash]$mw_cash[cf_cash_unit])"; } ?>,
댓글:<?=$board[bo_comment_point]?>,
다운:<?=$board[bo_download_point]?>"><!--
        --><img src='<?=$board_skin_path?>/img/btn_info.gif' border=0 align=absmiddle></a>
        <? if ($mw_basic[cf_social_commerce] && $rss_href && file_exists("$social_commerce_path/img/xml.png")) { ?>
            <a href='<?=$social_commerce_path?>/xml.php?bo_table=<?=$bo_table?>'><img src='<?=$social_commerce_path?>/img/xml.png' border=0 align=absmiddle></a>
        <? } else if ($rss_href) { ?><a href='<?=$rss_href?>'><img src='<?=$board_skin_path?>/img/btn_rss.gif' border=0 align=absmiddle></a><?}?>
        <? if ($is_admin == "super") { ?><a href="<?=$config_href?>"><img src="<?=$board_skin_path?>/img/btn_config.gif" title="스킨설정" border="0" align="absmiddle"></a><?}?>
        <? if ($admin_href) { ?><a href="<?=$admin_href?>"><img src="<?=$board_skin_path?>/img/btn_admin.gif" title="관리자" width="63" height="22" border="0" align="absmiddle"></a><?}?>
    </td>
</tr>
<tr><td height=5></td></tr>
</table>

<script type="text/javascript">
<?  if (!$mw_basic[cf_category_tab]) { ?>
if ('<?=$sca?>') document.fcategory_view.sca.value = '<?=urlencode($sca)?>';
<? } ?>
</script>

<? include_once("$board_skin_path/mw.proc/mw.notice.top.php") ?>

<? include_once("$board_skin_path/mw.proc/mw.search.top.php") ?>

<? include_once("$board_skin_path/mw.proc/mw.cash.membership.skin.php") ?>

<!-- 링크 버튼 -->
<?
ob_start();
?>
<table width=100%>
<tr height=35>
    <td>
        <? if ($search_href) { echo "<a href=\"$search_href\"><img src='$board_skin_path/img/btn_search_list.gif' border='0' align='absmiddle'></a> "; } ?>
        <? echo "<a href=\"$list_href\"><img src='$board_skin_path/img/btn_list.gif' border='0' align='absmiddle'></a> "; ?>

        <? if ($write_href) { echo "<a href=\"$write_href\"><img src='$board_skin_path/img/btn_write.gif' border='0' align='absmiddle'></a> "; } ?>
        <? if ($reply_href) { echo "<a href=\"$reply_href\"><img src='$board_skin_path/img/btn_reply.gif' border='0' align='absmiddle'></a> "; } ?>

        <? if ($update_href) { echo "<a href=\"$update_href\"><img src='$board_skin_path/img/btn_update.gif' border='0' align='absmiddle'></a> "; } ?>
        <? if ($delete_href) { echo "<a href=\"$delete_href\"><img src='$board_skin_path/img/btn_delete.gif' border='0' align='absmiddle'></a> "; } ?>

        <? //if ($good_href) { echo "<a href=\"$good_href\" target='hiddenframe'><img src='$board_skin_path/img/btn_good.gif' border='0' align='absmiddle'></a> "; } ?>
        <? //if ($nogood_href) { echo "<a href=\"$nogood_href\" target='hiddenframe'><img src='$board_skin_path/img/btn_nogood.gif' border='0' align='absmiddle'></a> "; } ?>

        <? //if ($scrap_href) { echo "<a href=\"javascript:;\" onclick=\"win_scrap('$scrap_href');\"><img src='$board_skin_path/img/btn_scrap.gif' border='0' align='absmiddle'></a> "; } ?>

    </td>
    <td align=right>
        <? if ($prev_href) { echo "<input type=image src=\"$board_skin_path/img/btn_prev.gif\" onclick=\"location.href='$prev_href'\" title=\"$prev_wr_subject\" accesskey='b'>&nbsp;"; } ?>
        <? if ($next_href) { echo "<input type=image src=\"$board_skin_path/img/btn_next.gif\" onclick=\"location.href='$next_href'\" title=\"$next_wr_subject\" accesskey='n'>&nbsp;"; } ?>
    </td>
</tr>
</table>
<?
$link_buttons = ob_get_contents();
ob_end_flush();
?>

<?
if ($is_category && $mw_basic[cf_category_tab]) {
    $category_list = explode("|", $board[bo_category_list]);
?>
<div class="category_tab">
<ul>
    <? if (!$mw_basic[cf_default_category]) { ?>
    <li <? if (!$sca) echo "class='selected'";?>><div><a href="<?=$g4[bbs_path]?>/board.php?bo_table=<?=$bo_table?>">전체</a></div></li>
    <? } ?>
    <? for ($i=0, $m=sizeof($category_list); $i<$m; $i++) { ?>
    <li <? if (urldecode($sca) == $category_list[$i]) echo "class='selected'";?>><div><a 
        href="<?=$g4[bbs_path]?>/board.php?bo_table=<?=$bo_table?>&sca=<?=urlencode($category_list[$i])?>"><?=$category_list[$i]?></a></div></li>
    <? } ?>
</ul>
</div>
<? } ?>

<!-- 제목, 글쓴이, 날짜, 조회, 추천, 비추천 -->
<table width="100%" cellspacing="0" cellpadding="0">
<tr><td height=2 class=mw_basic_line_color></td></tr>
<tr>
    <td class=mw_basic_view_subject>
        <? if ($view[wr_is_mobile]) echo "<img src='$board_skin_path/img/icon_mobile.png' class='mobile_icon'>"; ?>
        <? if ($is_category) { echo ($category_name ? "[$view[ca_name]] " : ""); } ?>
        <h1><?=cut_hangul_last(get_text($view[wr_subject]))?> <?=$view[icon_secret]?></h1>
        <? if ($mw_basic[cf_reward]) echo "&nbsp;<img src='$board_skin_path/img/btn_reward_$reward[re_status].gif' align='absmiddle'>"; ?>
        <? if ($mw_basic[cf_attribute] == 'qna' && !$view[is_notice]) { ?>
        <img src="<?=$board_skin_path?>/img/icon_qna_<?=$view[wr_qna_status]?>.png" align="absmiddle"></span> <?}?>

    </td>
</tr>
<tr><td height=1 bgcolor=#E7E7E7></td></tr>
<tr>
    <td height=30 class=mw_basic_view_title>
	<? if ($mw_basic[cf_contents_shop]) { // 배추 컨텐츠샵 ?>
	<strong>가격</strong> : 
	<span class="mw_basic_contents_price"><?=$mw_price?></span>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<? } ?>
        <? //if ($mw_basic[cf_attribute] != "anonymous") { ?>
        글쓴이 : 
	<span class=mw_basic_view_name> <?=$view[name]?>
        <? if ($mw_basic[cf_icon_level] && !$view[wr_anonymous] && $mw_basic[cf_attribute] != "anonymous" && $write[mb_id] && $write[mb_id] != $config[cf_admin]) { ?>
        <span class="icon_level<?=mw_get_level($write[mb_id])+1?>" style="border:1px solid #ddd;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        <? } ?>
	<? if ($is_ip_view && $ip) { ?>
	&nbsp;(<?=$ip?>)
        <? if ($is_admin) { ?>
            <img src="<?=$board_skin_path?>/img/btn_ip.gif" align=absmiddle title='IP조회' style="cursor:pointer" onclick="btn_ip('<?=$view[wr_ip]?>')">
            <img src="<?=$board_skin_path?>/img/btn_ip_search.gif" align=absmiddle title='IP검색' style="cursor:pointer" onclick="btn_ip_search('<?=$view[wr_ip]?>')">
        <? } ?>
	<? //} // mw_basic[cf_attribute] != 'anonymous'?>
	</span>
        <? } ?>
        날짜 : <span class=mw_basic_view_datetime><?=substr($view[wr_datetime],0,10)." (".get_yoil($view[wr_datetime]).") ".substr($view[wr_datetime],11,5)?></span>
        조회 : <span class=mw_basic_view_hit><?=$view[wr_hit]?></span>
        <? /*if ($is_good) { ?>추천 : <span class=mw_basic_view_good><?=$view[wr_good]?></span><?}*/?>
        <? /*if ($is_nogood) { ?>비추천 : <span class=mw_basic_view_nogood><?=$view[wr_nogood]?></span><?}*/?>
        <? if ($singo_href) { ?><a href="<?=$singo_href?>"><img src="<?=$board_skin_path?>/img/btn_singo2.gif" align=absmiddle title='신고'></a><?}?>
        <? if ($print_href) { ?><a href="<?=$print_href?>"><img src="<?=$board_skin_path?>/img/btn_print.gif" align=absmiddle title='인쇄'></a><?}?>
    </td>
</tr>
<?  if ($mw_basic[cf_umz]) { // 짧은 글주소 사용 ?>
<tr><td height=1 bgcolor=#E7E7E7></td></tr>
<tr>
    <td height=30 class=mw_basic_view_title>
        글주소 : <span id="post_url"><?=$view[wr_umz]?></span>
        <img src="<?=$board_skin_path?>/img/copy.png" id="post_url_copy" align="absmiddle">
        <? if ($is_admin) { ?>
        <span id='btn_get_umz'><a><img src="<?=$board_skin_path?>/img/reumz.png" align="absmiddle"/></a></span>
        <script type="text/javascript">
        $(document).ready(function () {
            $("#btn_get_umz a").css("cursor", "pointer");
            $("#btn_get_umz").bind("click", function () {
                tmp = $("#btn_get_umz").html();
                $("#btn_get_umz").html("<img src='<?=$board_skin_path?>/img/icon_loading.gif' height='16' align='absmiddle'/>");
                $.get("<?=$board_skin_path?>/mw.proc/mw.umz.php", { 'bo_table':'<?=$bo_table?>', 'wr_id':'<?=$wr_id?>' }, function (url) {
                    if (url)
                        $("#post_url").text(url);
                    $("#btn_get_umz").html(tmp);
                });
            });
        });
        </script>
        <?} ?>
    </td>
</tr>
<? } ?>

<? if ($mw_basic[cf_shorten]) { ?>
<tr><td height=1 bgcolor=#E7E7E7></td></tr>
<tr>
    <td height=30 class=mw_basic_view_title>
        글주소 : <span id="post_url"><?=$shorten?></span>
        <img src="<?=$board_skin_path?>/img/copy.png" id="post_url_copy" align="absmiddle">
    </td>
</tr>
<? } ?>

<? if ($mw_basic[cf_include_file_head]) { echo "<tr><td>"; @include_once($mw_basic[cf_include_file_head]); echo "</td></tr>"; } ?>

<? if ($mw_basic[cf_file_head]) { echo "<tr><td>$mw_basic[cf_file_head]</td></tr>"; } ?>
<?
// 가변 파일
$cnt = 0;
for ($i=0; $i<count($view[file]); $i++) {
    if ($view[file][$i][source] && !$view[file][$i][view] && !$view[file][$i][movie]) {
        $cnt++;
?>
<tr><td height=1 bgcolor=#E7E7E7></td></tr>
<tr>
    <td class=mw_basic_view_file>
        <a href="javascript:file_download('<?=$view[file][$i][href]?>', '<?=addslashes($view[file][$i][source])?>', '<?=$i?>');" title="<?=$view[file][$i][content]?>">
        <img src="<?=$board_skin_path?>/img/icon_file_down.gif" align=absmiddle>
        <?=$view[file][$i][source]?></a>
        <span class=mw_basic_view_file_info> (<?=$view[file][$i][size]?>), Down : <?=$view[file][$i][download]?>, <?=$view[file][$i][datetime]?></span>
        <? if ($good_href) { ?>
        <img src="<?=$board_skin_path?>/img/btn_down_good.png" align="absmiddle" style="cursor:pointer;" onclick="mw_good_act_nocancel('good')"/>
        <? } ?>
    </td>
</tr>
<?
    }
}

// 링크
$cnt = 0;
for ($i=1; $i<=$g4[link_count]; $i++) {
    if ($view[link][$i]) {
        $cnt++;
        $link = cut_str($view[link][$i], 70);
?>
<tr><td height=1 bgcolor=#E7E7E7></td></tr>
<tr>
    <td class=mw_basic_view_link>
        <img src='<?=$board_skin_path?>/img/icon_link.gif' align=absmiddle>
        <a href='<?=$view[link_href][$i]?>' target='<?=$view[link_target][$i]?>'><?=$link?></a>
        <span class=mw_basic_view_link_info>(<?=$view[link_hit][$i]?>)</span>
        <span><img src="<?=$board_skin_path?>/img/qr.png" class="qr_code" value="<?=$view[link][$i]?>" align="absmiddle"></span>
    </td>
</tr>
<?
    }
}
?>

<script type="text/javascript">
$(document).ready(function () {
    $("#mw_basic").append("<div id='qr_code_layer'>QR CODE</div>");
    $(".qr_code").css("cursor", "pointer");
    $(".qr_code").toggle(function () {
        var url = $(this).attr("value");
        var x = $(this).offset().top;
        var y = $(this).offset().left;

        //$(".qr_code").append("<div");
        $("#qr_code_layer").hide("fast");

        $("#qr_code_layer").css("position", "absolute");
        $("#qr_code_layer").css("top", x + 20);
        $("#qr_code_layer").css("left", y);
        $("#qr_code_layer").html("<div class='qr_code_google'><img src='http://chart.apis.google.com/chart?cht=qr&chld=H|2&chs=100&chl="+url+"'></div>");
        $("#qr_code_layer").html($("#qr_code_layer").html() + "<div class='qr_code_info'>모바일로 QR코드를 스캔하면 웹사이트 또는 모바일사이트에 바로 접속할 수 있습니다.</div>");
        $("#qr_code_layer").show("fast");
    }, function () {
        $("#qr_code_layer").hide("fast");
    });
});
</script>
<style type="text/css">
#qr_code_layer { display:none; position:absolute; background-color:#fff; border:2px solid #ccc; padding:10px; width:280px; }
#qr_code_layer .qr_code_google { border:5px solid #469CE0; float:left; }
#qr_code_layer .qr_code_google img { width:100px; height:100px; }
#qr_code_layer .qr_code_info { float:left; margin:0 0 0 10px; width:115px; font:normal 12px 'gulim'; line-height:18px; color:#555; }
</style>

<? if ($mw_basic[cf_file_tail]) { echo "<tr><td>$mw_basic[cf_file_tail]</td></tr>"; } ?>

<? if ($mw_basic[cf_include_file_tail]) { echo "<tr><td>"; @include_once($mw_basic[cf_include_file_tail]); echo "</td></tr>"; } ?>

<? if ($is_admin || $history_href || $is_singo_admin) { ?>
<tr><td height=1 bgcolor=#E7E7E7></td></tr>
<tr>
    <td height=40 class="func_buttons">
        <?
        ob_start();
        if ($is_singo_admin && $view[mb_id] != $member[mb_id]) { 
            echo "<span><a href=\"javascript:btn_intercept('$view[mb_id]')\">";
            echo "<img src='$board_skin_path/img/btn_intercept.gif' border='0' align='absmiddle'></a></span>"; 
        }
        if ($history_href) {
            echo "<span><a href=\"$history_href\"><img src='$board_skin_path/img/btn_history.gif' border='0' align='absmiddle'></a></span>";
        }
	if ($is_admin) {
            if ($download_log_href) {
                echo "<span><a href=\"$download_log_href\"><img src='$board_skin_path/img/btn_download_log.gif' border='0' align='absmiddle'></a></span>";
            }
            if ($link_log_href) {
                echo "<span><a href=\"$link_log_href\"><img src='$board_skin_path/img/btn_link_log.gif' border='0' align='absmiddle'></a></span>";
            }
            if ($copy_href) {
                echo "<span><a href=\"$copy_href\"><img src='$board_skin_path/img/btn_copy.gif' border='0' align='absmiddle'></a></span>";
            }
            if ($move_href) {
                echo "<span><a href=\"$move_href\"><img src='$board_skin_path/img/btn_move.gif' border='0' align='absmiddle'></a></span>";
            }
            if ($is_category) {
                echo "<span><a href=\"javascript:mw_move_cate_one();\"><img src=\"$board_skin_path/img/btn_select_cate.gif\" border=\"0\" align='absmiddle'></a></span>";
            }
            if ($nosecret_href) {
                echo "<span><a href=\"$nosecret_href\"><img src='$board_skin_path/img/btn_nosecret.gif' border='0' align='absmiddle'></a></span>";
            }
            if ($secret_href) {
                echo "<span><a href=\"$secret_href\"><img src='$board_skin_path/img/btn_secret.gif' border='0' align='absmiddle'></a></span>";
            }

            echo "<span><a href=\"javascript:btn_now()\"><img src='$board_skin_path/img/btn_now.gif' border='0' align='absmiddle'></a></span>";

            if ($view[is_notice]) $btn_notice = '_off'; else $btn_notice = ''; 
            echo "<span><a href=\"javascript:btn_notice()\"><img src='$board_skin_path/img/btn_notice{$btn_notice}.gif'";
            echo " border='0' align='absmiddle'></a></span>"; 

            if ($view[wr_comment_hide]) $btn_comment_hide = '_no'; else $btn_comment_hide = ''; 
            echo "<span><img src='$board_skin_path/img/btn_comment_hide{$btn_comment_hide}.gif' ";
            echo "onclick='btn_comment_hide()' style='cursor:pointer' align='absmiddle'></span>";

            if ($is_admin == "super") {
                echo "<span><img src=\"$board_skin_path/img/btn_member_email.gif\" style=\"cursor:pointer;\" ";
                echo "onclick=\"void(mw_member_email())\" align=\"absmiddle\"></span>"; 
            }

            $row = sql_fetch("select * from $mw[popup_notice_table] where bo_table = '$bo_table' and wr_id = '$wr_id'", false);
            if ($row) { $btn_popup = '_off'; $is_popup = true; } else { $btn_popup = ''; $is_popup = false; }
            echo "<span><a href=\"javascript:btn_popup()\"><img src='$board_skin_path/img/btn_popup{$btn_popup}.png' border='0' align='absmiddle'></a></span>"; 

            echo "<span><a href=\"javascript:void(btn_copy_new())\"><img src='$board_skin_path/img/btn_copy_new.png' border='0' align='absmiddle'></a></span>"; 

            if ($write[wr_view_block]) { $btn_block = '_off'; } else { $btn_block = ''; }
            echo "<span><a href=\"javascript:btn_view_block()\"><img src='$board_skin_path/img/btn_view_block{$btn_block}.png' ";
            echo "border='0' align='absmiddle'></a></span>"; 
        }
        $mw_admin_button = ob_get_contents();
        ob_end_flush();
        ?>
        <div class="block"></div>
    </td>
</tr>
<? } ?>

<? if ($mw_basic[cf_social_commerce]) { ?>
<tr>
    <td>
        <? include("$social_commerce_path/view.skin.php") ?>
    </td>
</tr>
<? } ?>

<? if ($mw_basic[cf_talent_market]) { ?>
<tr>
    <td>
        <? include("$talent_market_path/view.skin.php") ?>
    </td>
</tr>
<? } ?>

<?
$bomb = sql_fetch(" select * from $mw[bomb_table] where bo_table = '$bo_table' and wr_id = '$wr_id' ");
if ($bomb) {
?>
<tr>
    <td>
        <div class="mw_basic_view_bomb">
        <img src="<?=$board_skin_path?>/img/icon_bomb.gif" align="absmiddle">&nbsp;
        이 게시물이 자동 폭파되기까지 <span id="bomb_end_timer"></span> 남았습니다.
        </div>
        <script type="text/javascript">
        var bomb_end_time = <?=(strtotime($bomb[bm_datetime])-$g4[server_time])?>;
        function bomb_run_timer()
        {
            var timer = document.getElementById("bomb_end_timer");

            dd = Math.floor(bomb_end_time/(60*60*24));
            hh = Math.floor((bomb_end_time%(60*60*24))/(60*60));
            mm = Math.floor(((bomb_end_time%(60*60*24))%(60*60))/60);
            ii = Math.floor((((bomb_end_time%(60*60*24))%(60*60))%60));

            var str = "";

            if (dd > 0) str += dd + "일 ";
            if (hh > 0) str += hh + "시간 ";
            if (mm > 0) str += mm + "분 ";
            str += ii + "초 ";

            //timer.style.color = "#FF6C00";
            timer.style.color = "#FF0000";
            timer.style.fontWeight = "bold";
            timer.innerHTML = str;

            bomb_end_time--;

            if (bomb_end_time <= 0)  {
                clearInterval(bomb_tid);
                location.href = "<?=$g4[bbs_path]?>/board.php?bo_table=<?=$bo_table?>";
            }
        }
        bomb_run_timer();
        bomb_tid = setInterval('bomb_run_timer()', 1000); 
        </script>
    </td>
</tr>
<? } ?>

<tr>
    <td class=mw_basic_view_content>
        <div id=view_<?=$wr_id?>>

        <? @include_once($mw_basic[cf_include_view_head])?>

        <?=bc_code($mw_basic[cf_content_head])?>

        <div id=view_content>

        <? if ($mw_basic[cf_reward] && $reward[url]) { // 리워드 ?>
        <style type="text/css">
        .reward_button { background:url(<?=$board_skin_path?>/img/btn_reward_click.jpg) no-repeat; width:140px; height:60px; cursor:pointer; margin:0 0 10px 0; }
        .reward_click { margin:10px 0 10px 0; font-weight:bold; }
        .reward_info { margin:0 0 30px 0; }
        </style>
        <div class="reward_button" onclick="<?=$reward[script]?>"></div>
        <div class="reward_click">↑ 위 배너를 클릭하시면 됩니다 </div>
        <div class="reward_info">
        <div class="point">적립 : <?=number_format($reward[re_point])?> P</div>
        <div class="edate">마감 : <?=$reward[re_edate]?></div>
        </div>
        <? } ?>

        <?  if ($mw_basic['cf_lightbox'] && $mw_basic['cf_lightbox'] <= $mb['mb_level'] && $view['wr_lightbox']) { ?>
        <script> board_skin_path = "<?=$board_skin_path?>"; </script>
        <script src="<?=$board_skin_path?>/mw.js/lightbox/js/jquery-1.7.2.min.js"></script>
        <script src="<?=$board_skin_path?>/mw.js/lightbox/js/lightbox.js"></script>
        <link href="<?=$board_skin_path?>/mw.js/lightbox/css/lightbox.css" rel="stylesheet" />

        <div class="lightbox_container">
        <?
        mw_make_lightbox();
        for ($i=$file_start; $i<=$view['file']['count']; $i++) {
            $file = $view['file'][$i];
            if (!$file['view']) continue;
            if ($cf_img_1_noview) {
                $cf_img_1_noview = false;
                continue;
            }
            $lightbox_file = "{$file['path']}/{$file['file']}";
            $lightbox_thumb = "{$lightbox_path}/{$wr_id}-{$i}";

            echo "\n<a href=\"{$lightbox_file}\" rel=\"lightbox[roadtrip]\"><img src=\"{$lightbox_thumb}\"></a>";
        }
        ?>
        </div>
        <? } ?>

        <?echo $view[rich_content]; // {이미지:0} 과 같은 코드를 사용할 경우?>

        <?=bc_code($mw_basic[cf_content_add])?>
        <? @include_once($mw_basic[cf_include_view])?>

        </div>

        <? if ($mw_basic[cf_zzal] && $file_viewer) { ?>
        <div class=mw_basic_view_zzal>
            <input type=button id=zzbtn value="<?=$view[wr_zzal]?> 보기" onclick="zzalview()" class=mw_basic_view_zzal_button>

            <script language=javascript>
            function zzalview()
            {
                var zzb = document.getElementById("zzb");
                var btn = document.getElementById("zzbtn");
                if (zzb.style.display == "none")
                {
                    zzb.style.display = "block";
                    btn.value = "<?=$view[wr_zzal]?> 가리기";
                    //resizeBoardImage(650);
                }
                else
                {
                    zzb.style.display = "none";
                    btn.value = "<?=$view[wr_zzal]?> 보기";
                }
            }
            </script>

            <div id=zzb style="display:none; margin-top:20px;"><?=$file_viewer?></div>
        </div>
        <? } ?>

        <?php
        if ($mw_basic['cf_exam']) {
            if (file_exists("{$exam_path}/view.skin.php")) {
                include("{$exam_path}/view.skin.php");
            }
        }

        if ($mw_basic['cf_marketdb'] and $write['wr_marketdb']) { 
            if (file_exists("{$marketdb_path}/view.skin.php")) {
                include("{$marketdb_path}/view.skin.php");
            }
        } ?>

        <!-- 테러 태그 방지용 --></xml></xmp><a href=""></a><a href=''></a>

        <? if ($mw_basic[cf_ccl] && $view[wr_ccl][by]) { ?>
        <div class=mw_basic_ccl>
        <a rel="license" href="<?=$view[wr_ccl][link]?>" title="<?=$view[wr_ccl][msg]?>" target=_blank>
        <img src="<?=$board_skin_path?>/mw.ccl/ccls_by.gif">
        <? if ($view[wr_ccl][nc] == "nc") { ?><img src="<?=$board_skin_path?>/mw.ccl/ccls_nc.gif"><? } ?>
        <? if ($view[wr_ccl][nd] == "nd") { ?><img src="<?=$board_skin_path?>/mw.ccl/ccls_nd.gif"><? } ?>
        <? if ($view[wr_ccl][nd] == "sa") { ?><img src="<?=$board_skin_path?>/mw.ccl/ccls_sa.gif"><? } ?>
        </a>
        </div>
        <? } ?>

        <? if ($board[bo_use_good] || $board[bo_use_nogood]) { // 추천, 비추천?>
            <div id="mw_good"></div>

            <script type="text/javascript">
            function mw_good_load() {
                $.get("<?=$board_skin_path?>/mw.proc/mw.good.php?bo_table=<?=$bo_table?>&wr_id=<?=$wr_id?>", function (data) {
                    $("#mw_good").html(data);
                });
            }
            function mw_good_act(good) {
                $.get("<?=$board_skin_path?>/mw.proc/mw.good.act.php?bo_table=<?=$bo_table?>&wr_id=<?=$wr_id?>&good="+good, function (data) {
                    alert(data);
                    mw_good_load();
                });
            }
            function mw_good_act_nocancel(good) {
                $.get("<?=$board_skin_path?>/mw.proc/mw.good.act.php?bo_table=<?=$bo_table?>&wr_id=<?=$wr_id?>&good="+good+"&no_cancel=1", function (data) {
                    alert(data);
                    mw_good_load();
                });
            }

            mw_good_load();
            </script>
        <? } ?>

        <?=bc_code($mw_basic[cf_content_tail])?>

        <? @include_once($mw_basic[cf_include_view_tail])?>

        </div>
    </td>
</tr>

<? if ($mw_basic[cf_talent_market]) { ?>
<tr>
    <td>
        <?php echo $talent_market_content; ?>
    </td>
</tr>
<? } ?>

<? if ($mw_basic[cf_google_map] && trim($write[wr_google_map]) && !$google_map_is_view && $google_map_code) { ?>
<tr>
    <td>
        <?=$google_map_code?>
    </td>
</tr>
<? } ?>

<?
if ($is_signature && $signature && !$view[wr_anonymous] && $mw_basic[cf_attribute] != "anonymous") // 서명출력
{ 
    $tmpsize = array(0, 0);
    $is_comment_image = false;
    $comment_image = mw_get_noimage();
    if ($mw_basic[cf_attribute] != "anonymous" && !$view[wr_anonymous] && $view[mb_id] && file_exists("$comment_image_path/{$view[mb_id]}")) {
        $comment_image = "$comment_image_path/{$view[mb_id]}";
        $is_comment_image = true;
        $tmpsize = @getImageSize($comment_image);
    }

    $signature = preg_replace("/<a[\s]+href=[\'\"](http:[^\'\"]+)[\'\"][^>]+>(.*)<\/a>/i", "[$1 $2]", $signature);
    $signature = nl2br(strip_tags($signature));
    $signature = preg_replace("/\[([^\s]+) ([^\]]+)\]/i", "<a href='$1'>$2</a>", $signature);
    //$signature = htmlspecialchars($signature);
?>
<tr>
    <td class="mw_basic_view_signature">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td width="70">
                <div class="line">

                <img src="<?=$comment_image?>" class="comment_image" onclick="mw_image_window(this, <?=$tmpsize[0]?>, <?=$tmpsize[1]?>);">

                <? if (($is_member && $view[mb_id] == $member[mb_id] && !$view[wr_anonymous]) || $is_admin) { ?>
                <div style="margin:0 0 0 10px;"><a href="javascript:mw_member_photo('<?=$view[mb_id]?>')"
                    style="font:normal 11px 'gulim'; color:#888; text-decoration:none;"><? echo $is_comment_image ? "사진변경" : "사진등록"; ?></a></div>
                <? } ?>
                <script type="text/javascript">
                function mw_member_photo(mb_id) {
                    win_open('<?=$board_skin_path?>/mw.proc/mw.comment.image.php?bo_table=<?=$bo_table?>&mb_id='+mb_id,'comment_image','width=500,height=350');
                }
                </script>
                <?
                if ($mw_basic[cf_icon_level] && !$view[wr_anonymous] && $mw_basic[cf_attribute] != "anonymous" && $write[mb_id] && $write[mb_id] != $config[cf_admin]) { 
                    $level = mw_get_level($view[mb_id]);
                    echo "<div class=\"icon_level".($level+1)."\">&nbsp;</div>";
                    $exp = $icon_level_mb_point[$view[mb_id]] - $level*$mw_basic[cf_icon_level_point];
                    $per = round($exp/$mw_basic[cf_icon_level_point]*100);
                    if ($per > 100) $per = 100;
                    echo "<div style=\"background:url($board_skin_path/img/level_exp_bg.gif); width:61px; height:3px; font-size:1px; line-height:1px; margin:5px 0 0 3px;\">";
                    echo "<div style=\"background:url($board_skin_path/img/level_exp_dot.gif); width:$per%; height:3px;\">&nbsp;</div>";
                    echo "</div>";
                }
                ?>
                </div>
            </td>
            <td class="content">
                <div id="signature"><table border="0" cellpadding="0" cellspacing="0"><tr><td>
                <?=$signature?>
                </td></tr></table></div>
            </td>
        </tr>
        </table>
    </td>
</tr>
<? } ?>
<?  if ($mw_basic[cf_quiz]) { // 퀴즈 ?>
<tr>
    <td class=mw_basic_view_quiz>
        <div id="mw_quiz"></div>

        <script type="text/javascript">
        function mw_quiz_load() {
            $.get("<?=$quiz_path?>/view.php?bo_table=<?=$bo_table?>&wr_id=<?=$wr_id?>", function (data) {
                $("#mw_quiz").html(data);
            });
        }
        mw_quiz_load();
        </script>

    </td>
</tr>
<? } ?>

<?  if ($mw_basic[cf_vote]) { // 설문 ?>
<tr>
    <td class=mw_basic_view_vote>
        <div id="mw_vote"></div>

        <script type="text/javascript">
        function mw_vote_load() {
            $.get("<?=$board_skin_path?>/mw.proc/mw.vote.php?bo_table=<?=$bo_table?>&wr_id=<?=$wr_id?>", function (data) {
                $("#mw_vote").html(data);
            });
        }
        function mw_vote_result() {
            $.get("<?=$board_skin_path?>/mw.proc/mw.vote.php?bo_table=<?=$bo_table?>&wr_id=<?=$wr_id?>&result_view=1", function (data) {
                $("#mw_vote").html(data);
            });
        }
        function mw_vote_join() {
            var is_check = false;
            var vt_num = $("input[name='vt_num']");
            var choose = '';
            for (i=0; i<vt_num.length; i++)  {
                if (vt_num[i].checked) {
                    is_check = true;
                    choose += i + ',';
                }
            }
            if (!is_check) {
                alert("설문항목을 선택해주세요.");
                return;
            }
            $.get("<?=$board_skin_path?>/mw.proc/mw.vote.join.php?bo_table=<?=$bo_table?>&wr_id=<?=$wr_id?>&vt_num="+choose, function (data) {
                alert(data);
                mw_vote_load();
            });
        }
        mw_vote_load();
        </script>

    </td>
</tr>
<? } ?>

<?
if ($mw_basic[cf_attribute] == 'qna' && !$view[is_notice]) {
    $qna_save_point = round($write[wr_qna_point]*round($mw_basic[cf_qna_save]/100,2));
    $qna_total_point = $qna_save_point + $mw_basic[cf_qna_point_add];
    $uname = $board[bo_use_name] ? $member[mb_name] : $member[mb_nick];
?>
<tr>
    <td>
        <div class="mw_basic_qna_info">
            <? if ($is_member) { ?> <div><span class="mb_id"><?=$uname?></span>님의 지식을 나누어 주세요!</div> <? } ?>
            <div class="info2">
                <? if ($write[wr_qna_point]) { ?> 질문자가 자신의 포인트 <span class="num"><b><?=$write[wr_qna_point]?></b></span> 점을 걸었습니다.<br/> <? } ?>
                답변하시면 포인트 <span class="num"><b><?=$board[bo_comment_point]?></b>점</span>을<? if ($qna_total_point) { ?>, 답변이 채택되면
                포인트 <span class="num"><b><?=$qna_total_point?></b>점 <? } ?>
                <? if ($mw_basic[cf_qna_point_add]) { ?>
                    (채택 <b><?=$qna_save_point?></b> + 추가 <b><?=$mw_basic[cf_qna_point_add]?></b>) <? } ?></span>을 드립니다.
            </div>
        </div>
    </td>
</tr>
<? } ?>

<?  if ($mw_basic[cf_sns] or (($board[bo_use_good] or $board[bo_use_nogood]) and $mw_basic[cf_view_good] and $member[mb_level] >= $mw_basic[cf_view_good]) or $scrap_href) { ?>
<tr>
    <td>
        <? if (($board[bo_use_good] or $board[bo_use_nogood]) and $mw_basic[cf_view_good] and $member[mb_level] >= $mw_basic[cf_view_good]) { ?>
        <div class="view_good"><input type="button" value="추천(비) 회원목록" class="btn1"
            onclick="win_open('<?=$board_skin_path?>/mw.proc/mw.good.list.php?bo_table=<?=$bo_table?>&wr_id=<?=$wr_id?>', 'good_list',
            'width=600,height=500,scrollbars=1');"/></div>
        <? } ?>

        <?
        if ($scrap_href) {
            $sql = " select count(*) as cnt from $g4[scrap_table] where bo_table = '$bo_table' and wr_id = '$wr_id' ";
            $row = sql_fetch($sql);
            $scrap_count = $row[cnt];
            ?>
            <div class="scrap_button"><input type="button" class="btn1" id="scrap_button" value="스크랩 +<?=$scrap_count?>" onclick="scrap_ajax()"></div>
            <script type="text/javascript">
            function scrap_ajax() {
                $.get("<?=$board_skin_path?>/mw.proc/mw.scrap.php", {
                    'bo_table' : '<?=$bo_table?>',
                    'wr_id' : '<?=$wr_id?>',
                    'token' : '<?=$token?>' // 토큰 새로만들어야 하는데 이것까지 토큰 쓰기에는 세션이 너무;
                }, function (str) {
                    tmp = str.split('|');
                    if (tmp[0] == 'false') {
                        alert(tmp[1]);
                        return;
                    }
                    $("#scrap_button").val("스크랩 +" + tmp[0]);
                    $("#scrap_button").effect("highlight", {}, 3000);
                });
            }
            </script>
        <? } ?>

        <? if ($mw_basic[cf_sns]) { ?>
        <div class="sns"> <?=$view_sns?> </div>
        <? } ?>
    </td>
</tr>
<? } ?>

<? if ($mw_basic[cf_related] && $view[wr_related]) { ?>
<? $rels = mw_related($view[wr_related]); ?>
<? if (count($rels)) {?>
<? if ($mw_basic[cf_related_table]) $bo_table2 = $mw_basic[cf_related_table]; else $bo_table2 = $bo_table; ?>
<tr>
    <td class=mw_basic_view_related>
        <h3>
            관련글
            <a href="board.php?bo_table=<?=$bo_table2?>&sfl=wr_subject||wr_content,1&sop=or&stx=<?=urlencode(str_replace(",", " ", $view[wr_related]))?>">[더보기]</a>
        </h3>
    </td>
</tr>
<tr>
    <td class="mw_basic_view_content mw_basic_view_related">
        <ul>
        <? for ($i=0; $i<count($rels); $i++) { ?>
        <li> <a href="<?=$rels[$i][href]?>">[<?=substr($rels[$i][wr_datetime], 0, 10)?>] <?=$rels[$i][subject]?> <?=$rels[$i][comment]?></a> </li>
        <? } ?>
        </ul>
    </td>
</tr>
<? } ?>
<? } ?>

<? if ($mw_basic[cf_latest]) { ?>
<? $latest = mw_view_latest(); ?>
<? if (count($latest)) {?>
<?
$bo_subject = $board[bo_subject];
if ($mw_basic[cf_latest_table]) {
    $tmp = sql_fetch("select bo_subject from $g4[board_table] where bo_table = '$mw_basic[cf_latest_table]'");
    $bo_subject = $tmp[bo_subject];
}
?>
<tr>
    <td class=mw_basic_view_latest>
        <h3>
            <?=$view[name]?> 님의 <?=$bo_subject?> 최신글
            <a href="board.php?bo_table=<?=$bo_table?>&sfl=mb_id,1&stx=<?=$write[mb_id]?>">[더보기]</a>
        </h3>
    </td>
</tr>
<tr>
    <td class="mw_basic_view_content mw_basic_view_latest">
        <ul>
        <? for ($i=0; $i<count($latest); $i++) { ?>
        <li> <a href="<?=$latest[$i][href]?>">[<?=substr($latest[$i][wr_datetime], 0, 10)?>] <?=$latest[$i][subject]?> <?=$latest[$i][comment]?></a> </li>
        <? } ?>
        </ul>
    </td>
</tr>
<? } ?>
<? } ?>



</table>
<? if ($is_admin) { ?>
<div style="padding:10px 0 0 0;" class="func_buttons">
    <?=$mw_admin_button?>
    <div class="block"></div>
</div>
<? } ?>
<br>

<?
if (!$view[wr_comment_hide] && ($mw_basic[cf_comment_level] <= $member[mb_level])) {
    include_once("./view_comment.php"); // 코멘트 입출력 
}
?>

<?=$link_buttons?>

<?  if ($mw_basic['cf_include_tail'] && file_exists($mw_basic['cf_include_tail']) && strstr($mw_basic[cf_include_tail_page], '/v/'))
    include_once($mw_basic[cf_include_tail]); ?>

</td></tr></table><br>

<? if ($mw_basic[cf_exif]) { ?>
<script type="text/javascript">
function show_exif(no, obj, event) {
    var url = "<?=$board_skin_path?>/mw.proc/mw.exif.show.php";

    if (g4_is_ie) {
	x = window.event.clientX; 
	y = window.event.clientY + document.body.scrollTop;
    } else {
	x = event.clientX;
	y = event.clientY + document.body.scrollTop;
    }

    $.post (url, { bo_table:'<?=$bo_table?>', wr_id:'<?=$wr_id?>', bf_no:no }, function (req) {
            var exif = document.getElementById("exif-info");
            exif.style.left = x;
            exif.style.top = y;
            exif.style.display = "block";
            exif.innerHTML = req;
            exif.onclick = function () { this.style.display = "none"; }
	}
    );
}
</script>
<style type="text/css">
#exif-info { display:none; position:absolute; width:350px; height:200px; }
#exif-info { cursor:pointer; color:#bfbfbf;  }
#exif-info { background:url(<?=$board_skin_path?>/img/exif.png) no-repeat; }
*html #exif-info { background:; filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?=$board_skin_path?>/img/exif.png',sizingMethod='crop'); }
#exif-info table { margin:55px 0 0 20px; }
#exif-info td { color:#ddd; height:18px;  }
</style>

<div id="exif-info" title='클릭하면 창이 닫힙니다.'></div>
<? } ?>

<? if ($download_log_href) { ?>
<script type="text/javascript">
function btn_download_log() {
    win_open("<?=$board_skin_path?>/mw.proc/mw.download.log.php?bo_table=<?=$bo_table?>&wr_id=<?=$wr_id?>", "mw_download_log", "width=500, height=300, scrollbars=yes");
}
</script>
<? } ?>

<? if ($link_log_href) { ?>
<script type="text/javascript">
function btn_link_log() {
    win_open("<?=$board_skin_path?>/mw.proc/mw.link.log.php?bo_table=<?=$bo_table?>&wr_id=<?=$wr_id?>", "mw_link_log", "width=500, height=300, scrollbars=yes");
}
</script>
<? } ?>

<? if ($history_href) { ?>
<script type="text/javascript">
function btn_history(wr_id) {
    win_open("<?=$board_skin_path?>/mw.proc/mw.history.list.php?bo_table=<?=$bo_table?>&wr_id=" + wr_id, "mw_history", "width=500, height=300, scrollbars=yes");
}
</script>
<? } ?>

<? if ($singo_href) { ?>
<script type="text/javascript">
function btn_singo(wr_id, parent_id) {
    //if (confirm("이 게시물을 정말 신고하시겠습니까?")) {
    //hiddenframe.location.href = "<?=$board_skin_path?>/mw.proc/mw.btn.singo.php?bo_table=<?=$bo_table?>&wr_id=" + wr_id + "&parent_id=" + parent_id;
    win_open("<?=$board_skin_path?>/mw.proc/mw.btn.singo.php?bo_table=<?=$bo_table?>&wr_id=" + wr_id + "&parent_id=" + parent_id, "win_singo", "width=500,height=300,scrollbars=yes");
    //}
}
function btn_singo_view(wr_id) {
    var id = "singo_block_" + wr_id;

    if (document.getElementById(id).style.display == 'block')
        document.getElementById(id).style.display = 'none';
    else
        document.getElementById(id).style.display = 'block';
}

function btn_singo_clear(wr_id) {
    if (confirm("정말 초기화 하시겠습니까?")) {
        $.get("<?=$board_skin_path?>/mw.proc/mw.btn.singo.clear.php?bo_table=<?=$bo_table?>&token=<?=$token?>&wr_id="+wr_id, function(msg) {
            alert(msg);
        });
    }
}
</script>
<? } ?>

<? if ($print_href) { ?>
<script type="text/javascript">
function btn_print() {
    win_open("<?=$board_skin_path?>/mw.proc/mw.print.php?bo_table=<?=$bo_table?>&wr_id=<?=$wr_id?>", "print", "width=800,height=600,scrollbars=yes");
}
</script>
<? } ?>



<? if ($secret_href || $nosecret_href) { ?>
<script type="text/javascript">
function btn_secret() {
    if (confirm("이 게시물을 비밀글로 설정하시겠습니까?")) {
        hiddenframe.location.href = "<?=$board_skin_path?>/mw.proc/mw.btn.secret.php?bo_table=<?=$bo_table?>&wr_id=<?=$wr_id?>&token=<?=$token?>";
    }
}
function btn_nosecret() {
    if (confirm("이 게시물의 비밀글 설정을 해제하시겠습니까?")) {
        hiddenframe.location.href = "<?=$board_skin_path?>/mw.proc/mw.btn.secret.php?bo_table=<?=$bo_table?>&wr_id=<?=$wr_id?>&token=<?=$token?>&flag=no";
    }
}

</script>
<? } ?>


<? if ($is_singo_admin && $view[mb_id] != $member[mb_id]) { ?>
<script type="text/javascript">
function btn_intercept(mb_id) {
    win_open("<?=$board_skin_path?>/mw.proc/mw.intercept.php?bo_table=<?=$bo_table?>&mb_id=" + mb_id, "intercept", "width=500,height=300,scrollbars=yes");
}
</script>
<? } ?>
<? if ($is_admin) { ?>
<script type="text/javascript">
function btn_now() {
    var renum = 0;
    if (confirm("이 게시물의 작성시간을 현재로 변경하시겠습니까?")) {
        if (confirm("날짜순으로 정렬 하시겠습니까?")) renum = 1;

        $.get("<?=$board_skin_path?>/mw.proc/mw.time.now.php", { 
            "bo_table":"<?=$bo_table?>", 
            "wr_id":"<?=$wr_id?>", 
            "token":"<?=$token?>", 
            "renum":renum 
            } , function (ret) {
                if (ret)
                    alert(ret);
                else
                    location.reload();
            });
    }
}

function btn_view_block() {
    <? if ($write[wr_view_block]) { ?>
    if (!confirm("이 게시물 보기차단을 해제 하시겠습니까?")) return;
    <? } else { ?>
    if (!confirm("이 게시물 보기를 차단하시겠습니까?")) return;
    <? } ?>
    $.post("<?=$board_skin_path?>/mw.proc/mw.view.block.php", {
        "bo_table":"<?=$bo_table?>",
        "wr_id":"<?=$wr_id?>",
        "token":"<?=$token?>"
    }, function (str) {
        if (str)
            alert(str);
    });
}
function btn_ip(ip) {
    win_open("<?=$board_skin_path?>/mw.proc/mw.whois.php?ip=" + ip, "whois", "width=700,height=600,scrollbars=yes");
}
function btn_ip_search(ip) {
    win_open("<?=$g4[admin_path]?>/member_list.php?sfl=mb_ip&stx=" + ip);
}
function btn_notice() {
    var is_off = 0;
    <? if ($view[is_notice]) { ?>
    if (!confirm("이 공지를 내리시겠습니까?")) return;
    is_off = 1; 
    <? } else { ?>
    if (!confirm("이 글을 공지로 등록하시겠습니까?")) return;
    <? } ?>
    $.get("<?=$board_skin_path?>/mw.proc/mw.notice.php?bo_table=<?=$bo_table?>&wr_id=<?=$wr_id?>&token=<?=$token?>&is_off="+is_off, function(data) {
        alert(data);
    });
}
function btn_popup() {
    var is_off = 0;
    <? if ($is_popup) { ?>
    if (!confirm("이 팝업공지를 내리시겠습니까?")) return;
    is_off = 1; 
    <? } else { ?>
    if (!confirm("이 글을 팝업공지로 등록하시겠습니까?")) return;
    <? } ?>
    $.get("<?=$board_skin_path?>/mw.proc/mw.popup.php?bo_table=<?=$bo_table?>&wr_id=<?=$wr_id?>&token=<?=$token?>", function(data) {
        alert(data);
    });
}
function btn_comment_hide() {
    var is_off = 0;
    <? if (!$view[wr_comment_hide]) { ?>
    if (!confirm("이 글의 댓글을 감추시겠습니까?")) return;
    is_off = 1; 
    <? } else { ?>
    if (!confirm("이 글의 댓글을 보이시겠습니까?")) return;
    <? } ?>
    $.get("<?=$board_skin_path?>/mw.proc/mw.comment.hide.php?bo_table=<?=$bo_table?>&wr_id=<?=$wr_id?>&token=<?=$token?>&is_off="+is_off, function(data) {
        alert(data);
        location.reload();
    });
}
</script>
<? } ?>

<?
if ($mw_basic[cf_contents_shop] == "1")  // 배추컨텐츠샵-다운로드 결제
{
    $is_per = true;
    $is_buy = false;
    $is_per_msg = '예외오류';

    if (!$is_member) {
	//alert("로그인 해주세요.");
        $is_per = false;
	$is_per_msg = "로그인 해주세요.";
    }

    //if (!mw_is_buy_contents($member[mb_id], $bo_table, $wr_id) && $is_admin != "super")
    $con = mw_is_buy_contents($member[mb_id], $bo_table, $wr_id);
    if (!$con and $is_per)
    {
	//alert("결제 후 다운로드 하실 수 있습니다.");
        $is_per = false;
	$is_per_msg = "결제 후 다운로드 하실 수 있습니다.";
    }
    else if (!$write[wr_contents_price]) ;
    else
    {
        if ($mw_basic[cf_contents_shop_download_count] and $is_per) {
            $sql1 = "select count(*) as cnt from $mw_cash[cash_list_table] where rel_table = '$bo_table' and rel_id = '$wr_id' and cl_cash < 0";
            $row1 = sql_fetch($sql1);
            $sql2 = "select count(*) as cnt from $mw[download_log_table] where bo_table = '$bo_table' and wr_id = '$wr_id' and dl_datetime > '$con[cl_datetime]'";
            $row2 = sql_fetch($sql2);
            if ($row2[cnt] >= ($mw_basic[cf_contents_shop_download_count])) {
                //alert("다운로드 횟수 ($mw_basic[cf_contents_shop_download_count]회) 를 넘었습니다.\\n\\n재결제 후 다운로드 할 수 있습니다.");
                $is_per = false;
                $is_per_msg = "다운로드 횟수 ($mw_basic[cf_contents_shop_download_count]회) 를 넘었습니다.\\n\\n재결제 후 다운로드 할 수 있습니다.";
            }
        }

        if ($mw_basic[cf_contents_shop_download_day] and $is_per) {
            $gap = floor(($g4[server_time] - strtotime($con[cl_datetime])) / (60*60*24));
            if ($gap >= $mw_basic[cf_contents_shop_download_day]) {
                //alert("다운로드 기간 ($mw_basic[cf_contents_shop_download_day]일) 이 지났습니다.\\n\\n재결제 후 다운로드 할 수 있습니다.");
                $is_per = false;
                $is_per_msg = "다운로드 기간 ($mw_basic[cf_contents_shop_download_day]일) 이 지났습니다.\\n\\n재결제 후 다운로드 할 수 있습니다.";
            }
        }
    }
}

?>

<? if ($mw_basic[cf_contents_shop]) { // 배추컨텐츠샵 ?>
<script type="text/javascript" src="<?=$mw_cash[path]?>/cybercash.js"></script>
<script type="text/javascript">
var mw_cash_path = "<?=$mw_cash[path]?>";
</script>
<!--<span><img src="<?=$board_skin_path?>/img/icon_cash2.gif" style="cursor:pointer;" onclick="buy_contents('<?=$bo_table?>', '<?=$wr_id?>')" align="absmiddle"></span>-->
<? } ?>


<script type="text/javascript">
function file_download(link, file, no) {
    <?
    if ($member[mb_level] < $board[bo_download_level]) {
        $alert_msg = "다운로드 권한이 없습니다.";
        if ($member[mb_id]) { 
            echo "alert('$alert_msg'); return;\n";
        } else {
            echo "alert('$alert_msg\\n\\n회원이시라면 로그인 후 이용해 보십시오.');\n";
            echo "location.href = './login.php?wr_id=$wr_id$qstr&url=".urlencode("$g4[bbs_path]/board.php?bo_table=$bo_table&wr_id=$wr_id")."';\n";
            echo "return;";
        }
    }
    ?>

    <? if ($board[bo_download_point] < 0) { ?>if (confirm("'"+decodeURIComponent(file)+"' 파일을 다운로드 하시면 포인트가 차감(<?=number_format($board[bo_download_point])?>점)됩니다.\n\n포인트는 게시물당 한번만 차감되며 다음에 다시 다운로드 하셔도 중복하여 차감하지 않습니다.\n\n그래도 다운로드 하시겠습니까?"))<?}?>

    <? if ($mw_basic[cf_contents_shop] == "1" and !$is_per) { // 배추컨텐츠샵 다운로드 결제 ?>
    alert("<?=$is_per_msg?>");
    buy_contents('<?=$bo_table?>', '<?=$wr_id?>', no);
    return;
    <? } ?>

    if (<?=$mw_basic[cf_download_popup]?>)
        win_open("<?=$board_skin_path?>/mw.proc/download.popup.skin.php?bo_table=<?=$bo_table?>&wr_id=<?=$wr_id?>&no="+no, "download_popup", "width=<?=$mw_basic[cf_download_popup_w]?>,height=<?=$mw_basic[cf_download_popup_h]?>,scrollbars=yes");
    else
        document.location.href=link;
}
</script>

<script type="text/javascript" src="<?="$g4[path]/js/board.js"?>"></script>
<script type="text/javascript" src="<?="$board_skin_path/mw.js/mw_image_window.js"?>"></script>

<script type="text/javascript">
// 서명 링크를 새창으로
if (document.getElementById('signature')) {
    var target = '_blank';
    var link = document.getElementById('signature').getElementsByTagName("a");
    for(i=0;i<link.length;i++) {
        link[i].target = target;
    }
}
</script>

<? if ($mw_basic[cf_write_notice]) { ?>
<script type="text/javascript">
// 글쓰기버튼 공지
function btn_write_notice(url) {
    var msg = "<?=$mw_basic[cf_write_notice]?>";
    if (confirm(msg))
	location.href = url;
}
</script>
<? } ?>

<? if ($mw_basic[cf_link_blank]) { // 본문 링크를 새창으로 ?>
<script type="text/javascript">
if (document.getElementById('view_content')) {
    var target = '_blank';
    var link = document.getElementById('view_content').getElementsByTagName("a");
    for(i=0;i<link.length;i++) {
        link[i].target = target;
    }
}
</script>
<? } ?>

<? if ($mw_basic[cf_source_copy]) { // 출처 자동 복사 ?>
<script type="text/javascript">
function mw_copy()
{
    if (window.event)
    {
        window.event.returnValue = true;
        window.setTimeout('mw_add_source()', 10);
    }
}
function mw_add_source()
{
    if (window.clipboardData) {
        txt = window.clipboardData.getData('Text');
        txt = txt + "\r\n[출처 : <?=$g4[url]?>]\r\n";
        window.clipboardData.setData('Text', txt);
    }
}
//document.getElementById("view_content").oncopy = mw_copy;

</script>
<? } ?>

<? if ($is_admin == "super") { ?>
<script type="text/javascript">
function mw_config() {
    win_open("<?=$board_skin_path?>/mw.adm/mw.config.php?bo_table=<?=$bo_table?>", "config", "width=980, height=700, scrollbars=yes");
}
function mw_member_email() {
    if (!confirm("이 글을 회원메일로 등록하시겠습니까?")) return false;
    $.get("<?=$board_skin_path?>/mw.proc/mw.member.email.php?bo_table=<?=$bo_table?>&wr_id=<?=$wr_id?>&token=<?=$token?>", function (data) {
        if (confirm(data)) location.href = "<?=$g4[admin_path]?>/mail_list.php";
    });
}
</script>
<? } ?>

<? if ($is_admin) { ?>
<script type="text/javascript">
function btn_copy_new() {
    if (!confirm("이 글을 새글로 등록하시겠습니까?")) return false;
    $.get("<?=$board_skin_path?>/mw.proc/mw.copy.new.php?token=<?=$token?>&bo_table=<?=$bo_table?>&wr_id=<?=$wr_id?>", function (data) {
        tmp = data.split("|");
        if (tmp[0] == 'true') {
            location.href = "<?=$g4[bbs_path]?>/board.php?bo_table=<?=$bo_table?>&wr_id="+tmp[1];
        } else {
            alert(tmp[1]);
        }
    });
}
</script>
<? } ?>

<? if ($is_category) { ?>
<script type="text/javascript">
// 선택한 게시물 분류 변경
function mw_move_cate_one() {
    var sub_win = window.open("<?=$board_skin_path?>/mw.proc/mw.move.cate.php?bo_table=<?=$bo_table?>&chk_wr_id[0]=<?=$wr_id?>",
        "move", "left=50, top=50, width=500, height=550, scrollbars=1");
}
</script>
<? } ?>

<script> $(document).ready (function() { resizeBoardImage(<?=$board[bo_image_width]?>); }); </script>

<style type="text/css">
/* 본문 img */
#mw_basic .mw_basic_view_content img {
    max-width:<?=$board[bo_image_width]?>px;
    height:auto; 
}

#mw_basic .mw_basic_comment_content img {
    max-width:<?=$board[bo_image_width]-200?>px;
    height:auto; 
}


<?=$mw_basic[cf_css]?>
</style>

<?
// 팝업공지
$sql = "select * from $mw[popup_notice_table] where bo_table = '$bo_table' order by wr_id desc";
$qry = sql_query($sql, false);
while ($row = sql_fetch_array($qry)) {
    $row2 = sql_fetch("select * from $write_table where wr_id = '$row[wr_id]'");
    if (!$row2) {
        sql_query("delete from $mw[popup_notice_table] where bo_table = '$bo_table' and wr_id = '$row[wr_id]'");
        continue;
    }
    $view = get_view($row2, $board, $board_skin_path, 255);
    mw_board_popup($view, $html);
}

