<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

@include_once("$mw_group_skin_head_path/menu.more.skin.php");

$group_width = $group[gr_width];

if (!$group['gr_theme']) {
    $group['gr_theme'] = "basic";
}
else if (!is_dir("{$mw_group_skin_head_path}/img/{$group['gr_theme']}")) {
    $group['gr_theme'] = "basic";
}
?>

<link rel="stylesheet" href="<?=$mw_group_skin_head_path?>/style.css" type="text/css"/>

<style type="text/css">
body { text-align:center; }
.mw-group-page { width:<?=$group_width?><?=$group_width>100?'px':'%'?>; clear:both; margin:10px auto 0 auto; text-align:left; }
/*.mw-top-menus { background-color:#fafafa; border-bottom:1px solid #e9e9e9; }*/
.mw-top-menus { color:#ddd; }
.mw-top-menus a { text-decoration:none; color:#888; font-size:11px; }
.mw-top-menus .menus-left { float:left; }

.menus-right { float:right; color:#ddd; }
.menus-right .item { float:right; margin:5px 3px 0 3px; }
.menus-right .btn { float:right; margin:5px 20px 0 3px; }

.mw-left-menus { border:1px solid #d8d8d8; background-color:#f8f9f8; text-align:left; }
.mw-left-menus .title { margin:10px 0 3px 10px; }
.mw-left-menus .title { font-size:15px; font-weight:bold; color:#3f4ea1; letter-spacing:-2px; font-family:dotum; }
.mw-left-menus ul { margin:0 2px 2px 2px; padding:5px 0 5px 0; border:1px solid #f0f0f0; background-color:#fff; list-style:none; }
.mw-left-menus ul li { padding:7px 0 5px 15px; } 
.mw-left-menus ul li { background:url(<?=$mw_group_skin_head_path?>/img/dot.gif) no-repeat 7px 12px; border-bottom:1px dotted #e7e7e7; } 
.mw-left-menus a:hover { text-decoration:underline; } 
.mw-left-menus .select { color:#cc0000; color:#3f4ea1; font-weight:bold; } 

.latest-block { margin-top:10px; }

.mw-index-menu-bar { clear:both; height:36px; margin:10px 0 0 0; }
.mw-index-menu-bar a:hover,
.mw-index-menu-bar a:link,
.mw-index-menu-bar a:active,
.mw-index-menu-bar a:visited
{ color:#fff; text-decoration:none; font-size:12px; }
.mw-index-menu-left { width:16px; height:36px; float:left; }
.mw-index-menu-right { height:36px; float:right; }
.mw-index-menu-item { float:left; padding:12px 7px 0 7px; font-weight:bold; }
.mw-index-menu-div { width:10px; height:36px; float:left; }

.mw-index-menu-select1 { float:left; }
.mw-index-menu-select2 { float:left; }
.mw-index-menu-select3 { float:left; padding:12px 7px 0 7px; font-weight:bold; }

.mw-index-menu-select3 a:hover,
.mw-index-menu-select3 a:link,
.mw-index-menu-select3 a:active,
.mw-index-menu-select3 a:visited { color:#fffc00; font-weight:bold; }
/*.mw-index-menu-select3 a:visited { color:#000; font-weight:bold; }*/

.mw-index-menu-bar { background:url(<?=$mw_group_skin_head_path?>/img/<?=$group[gr_theme]?>/mm.png); }
.mw-index-menu-div { background:url(<?=$mw_group_skin_head_path?>/img/<?=$group[gr_theme]?>/md.png) center no-repeat; }

/*
.mw-index-menu-select1 { height:32px; float:left; padding:0; margin:4px 5px 0 5px; }
.mw-index-menu-select2 { height:32px; float:left; padding:0; margin:0; }
.mw-index-menu-select3 { height:32px; float:left; padding:8px 10px 0 10px; margin:0; }

.mw-index-menu-select1 { background:url(<?=$mw_group_skin_head_path?>/img/<?=$group[gr_theme]?>/msm.png); }
.mw-index-menu-select2 { background:url(<?=$mw_group_skin_head_path?>/img/<?=$group[gr_theme]?>/msl.png) top left no-repeat; }
.mw-index-menu-select3 { background:url(<?=$mw_group_skin_head_path?>/img/<?=$group[gr_theme]?>/msr.png) top right no-repeat; }
*/

.mw-index-menu-bar .mw-drop-menu { display:none; position:absolute; background-color:#fff; z-index:9999; border:1px solid #ddd; padding:10px; width:150px; }
.mw-index-menu-bar .mw-drop-menu div { height:22px; color:#444; padding:0 0 0 12px; }
.mw-index-menu-bar .mw-drop-menu div { background:url(<?=$mw_group_skin_head_path?>/img/dot.gif) no-repeat 3px 7px; }
.mw-index-menu-bar .mw-drop-menu a:link,
.mw-index-menu-bar .mw-drop-menu a:visited,
.mw-index-menu-bar .mw-drop-menu a:active { color:#444; text-decoration:none; }
.mw-index-menu-bar .mw-drop-menu a:hover { text-decoration:underline; }
</style>

<table border=0 cellpadding=0 cellspacing=0 class="mw-group-page"><tr><td>

<table width=100% height=40 border=0 cellpadding=0 cellspacing=0>
<tr>
<td valign=top width=40%>
    <!-- 로고 -->
    <div style="float:left;"><a href="<?=$mw[index_url]?>"><img src="<?=$mw_group_skin_head_path?>/img/mw_logo.gif"></a></div>
    <!-- 그룹명 -->
    <div style="float:left; padding:20px 0 0 10px;">
	- <a href="<?=$group[gr_url]?>" style="font-size:15px; font-weight:bold;"><?=$group[gr_subject]?></a>
    </div>
</td>
<td valign=top>
    <div class="menus-right">
	<?php for ($i=$mw_groups_head_count-1; $i>-1; $i--) { ?>
	<?php if ($mw_groups_head[$i][gr_level_view] > $member[mb_level]) continue; ?>
	<div class="item"><a href="<?=$mw_groups_head[$i][gr_url]?>"
            target="<?=$mw_groups_head[$i][gr_target]?>"
            title="<?=mw_html_entities($mw_groups_head[$i][gr_title])?>"
            style="<?=$mw_groups_head[$i][gr_more_css]?>"><?=$mw_groups_head[$i][gr_subject]?></a></div>
	<div class="item">|</div>
	<?php } ?>

	<?php
        if ($is_member) { // 로그인한 회원
            if ($is_admin) { //  관리자
                echo "<div class=\"item\"><a href=\"{$g4['admin_path']}\">관리자</a></div>";
            }
            else {
                echo "<div class=\"item\"><a href=\"{$g4['bbs_path']}/member_confirm.php?url=register_form.php\">";
                echo "정보수정</a></div>";
            }
            echo "<div class=\"btn\"><a href=\"{$g4['bbs_path']}/logout.php?url={$logout_url}\">";
            echo "<img src=\"{$mw_group_skin_head_path}/img/btn_logout.gif\" align=\"absmiddle\"></a></div>";
	}
        else { // 비회원
            echo "<div class=\"item\"><a href=\"{$g4['bbs_path']}/register.php\">회원가입</a></div>";
	    echo "<div class=\"btn\"><a href=\"{$g4['bbs_path']}/login.php?url={$urlencode}\">";
            echo "<img src=\"{$mw_group_skin_head_path}/img/btn_login.gif\" align=\"absmiddle\"></a></div>";
	}
        ?>
    </div>
</td>
</tr>
</table>

<!-- 중 메뉴 -->
<div class="mw-index-menu-bar">
    <div class="mw-index-menu-left"><!--
        --><img src="<?=$mw_group_skin_head_path?>/img/<?=$group[gr_theme]?>/ml.png"></div>
    <?php
    $select_div_begin = "<div class='mw-index-menu-select1'>";
    $select_div_begin.= "<div class='mw-index-menu-select2'>";
    $select_div_begin.= "<div class='mw-index-menu-select3' mm_id='[mm_id]'>";
    $select_div_end = "</div></div></div>";

    for ($i=0, $m=count($mw_mmenus); $i<$m; $i++) { 
        if ($mw_mmenus[$i][mm_level_view] > $member[mb_level]) continue;
        if ($i > 0) echo "<span class='mw-index-menu-div'></span>";

        if ($mw_mmenus[$i][mm_id] == $mm_id) {
            $div_begin = str_replace("[mm_id]", $mw_mmenus[$i][mm_id], $select_div_begin);
            $div_end = $select_div_end;
            $mw_mmenus[$i][mm_css] = '';
        }
        else {
            $div_begin = "<div class='mw-index-menu-item' mm_id='{$mw_mmenus[$i][mm_id]}'>";
            $div_end = "</div>";
        }

        ?>
        <?=$div_begin?><a href="<?=$mw_mmenus[$i][mm_url]?>"
            target="<?=$mw_mmenus[$i][mm_target]?>"
            title="<?=mw_html_entities($mw_mmenus[$i][mm_title])?>"
            style="<?=$mw_mmenus[$i][mm_css]?>"><?=$mw_mmenus[$i][mm_name]?></a><?=$div_end?>

        <?php ob_start(); // 소메뉴 드롭다운 ?>
        <div id="mw-drop-menu-<?=$mw_mmenus[$i][mm_id]?>" class="mw-drop-menu">
        <?php
        $mw_smenus = mw_get_small_menus($mw_mmenus[$i][mm_id]);
	for ($j=0; $j<sizeof($mw_smenus); $j++) {
            if ($mw_smenus[$i][ms_level_view] > $member[mb_level]) continue;
            ?><div><a href="<?=$mw_smenus[$j][ms_url]?>"
                target="<?=$mw_smenus[$j][ms_target]?>"
                title="<?=mw_html_entities($mw_smenus[$j][ms_title])?>"><?=$mw_smenus[$j][ms_name]?></a></div><?
        }
        ?>
        </div> <!-- mw-drop-menu -->
        <?php
        $drop_menu = ob_get_contents();
        ob_end_clean();

        if (sizeof($mw_smenus) > 1) {
            echo $drop_menu;
        }
    }
    ?>
    <div class="mw-index-menu-right"><!--
        --><img src="<?=$mw_group_skin_head_path?>/img/<?=$group[gr_theme]?>/mr.png"></div>
</div>

<script>
$(document).ready(function () {
    $(".mw-index-menu-item").mouseenter(function () {
        $(".mw-drop-menu").hide();
        mm_id = $(this).attr("mm_id");
        t = $(this).offset().top;
        l = $(this).offset().left;
        $("#mw-drop-menu-"+mm_id).css("top", t+30);
        $("#mw-drop-menu-"+mm_id).css("left", l);
        $("#mw-drop-menu-"+mm_id).show();
    });
    $(".mw-index-menu-bar").mouseleave(function () {
        $(".mw-drop-menu").hide();
    });
    $(".mw-index-menu-select3").mouseenter(function () {
        $(".mw-drop-menu").hide();
        mm_id = $(this).attr("mm_id");
        t = $(this).offset().top;
        l = $(this).offset().left;
        $("#mw-drop-menu-"+mm_id).css("top", t+30);
        $("#mw-drop-menu-"+mm_id).css("left", l);
        $("#mw-drop-menu-"+mm_id).show();
    });
});
</script>

<?php if ($mm_id) { // 좌측 소메뉴 출력 ?>
<table width=100% border=0 cellpadding=0 cellspacing=0 style="margin-top:10px;">
<tr>
    <?php if (!$mw_mmenu[mm_lmenu] && !$mw_smenu[ms_lmenu]) { ?>
    <td width=180 valign=top>
	<div style="margin-bottom:10px;"><?=outlogin("mw.outlogin3")?></div>
	<div class="mw-left-menus">
	<div class="title"><?=$mw_mmenu[mm_name]?></div>
	<ul>
	<?php
        $mw_smenus = mw_get_small_menus($mm_id);
        for ($i=0; $i<sizeof($mw_smenus); $i++) {
	    if ($mw_smenus[$i][ms_id] == $mw_smenu[ms_id]) {
                $mw_smenus[$i][ms_name] = "<span class=select>{$mw_smenus[$i][ms_name]}</span>";
            }
            if ($mw_smenus[$i][ms_level_view] > $member[mb_level]) continue;
            ?>
            <li><a href="<?=$mw_smenus[$i][ms_url]?>"
                target="<?=$mw_smenus[$i][ms_target]?>"
                title="<?=mw_html_entities($mw_smenus[$i][ms_title])?>"><span
                style="<?=$mw_smenus[$i][ms_css]?>"><?=$mw_smenus[$i][ms_name]?></span></a></li><?php } ?>
	</ul>
	</div>
        <div class="latest-block"><a href="http://bbs.miwit.com/bbs/board.php?bo_table=bbs_freelancer"><img
            src="<?=$mw_group_skin_head_path?>/img/freelancer.gif"></a></div>
        <div class="latest-block"><a href="http://bbs.miwit.com/bbs/board.php?bo_table=bbs_lsy"><img
            src="<?=$mw_group_skin_head_path?>/img/lsy.gif"></a></div>
	&nbsp;
    </td>
    <td width=10></td>
    <?php } ?>
    <td valign=top>
<?php } // if ($mm_id) ?>

