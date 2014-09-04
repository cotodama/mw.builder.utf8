<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

@include_once("$mw_index_skin_head_path/menu.more.skin.php");

$group_width = $group[gr_width];
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

.mw-menus-middle-select1 { background:url(<?=$mw_group_skin_head_path?>/img/<?=$group[gr_theme]?>/mw-bar-select-bg.gif); }
.mw-menus-middle-select2 { background:url(<?=$mw_group_skin_head_path?>/img/<?=$group[gr_theme]?>/mw-bar-select-left.gif) top left no-repeat; }
.mw-menus-middle-select3 { background:url(<?=$mw_group_skin_head_path?>/img/<?=$group[gr_theme]?>/mw-bar-select-right.gif) top right no-repeat; }

.mw-menus-middle-start   { height:32px; clear:both; padding:6px 0 0 0; font-size:10pt; color:#fff; font-weight:bold; font-family:gulim; }
.mw-menus-middle-item    { height:32px; float:left; padding:8px 10px 0 10px; margin:0 5px 0 5px; text-align:center; }
/*.mw-menus-middle-item    { background:url(<?=$mw_group_skin_head_path?>/img/<?=$group[gr_theme]?>/mw-bar-div.gif) 7px right no-repeat; }*/
.mw-menus-middle-item a  { color:#fff; font-size:10pt; }

.mw-menus-middle-select1 { height:32px; float:left; padding:0; margin:0 5px 0 5px; }
.mw-menus-middle-select2 { height:32px; float:left; padding:0; }
.mw-menus-middle-select3 { height:32px; float:left; padding:8px 10px 0 10px; text-align:center; }
.mw-menus-middle-select3 a { color:#000; font-size:10pt; }

.mw-menus-small { margin:7px 0 0 0; text-align:left; clear:both; height:20px; }
.mw-menus-small { margin-left:<?=$mw_mmenu[mm_left]?>px; }
.mw-menus-small-item { float:left; margin:0 10px 0 10px; }
.mw-menus-small-div { float:left; color:#ddd; }

.mw-left-menus { border:1px solid #d8d8d8; background-color:#f8f9f8; text-align:left; }
.mw-left-menus .title { margin:10px 0 3px 10px; }
.mw-left-menus .title { font-size:15px; font-weight:bold; color:#3f4ea1; letter-spacing:-2px; font-family:dotum; }
.mw-left-menus ul { margin:0 2px 2px 2px; padding:5px 0 5px 0; border:1px solid #f0f0f0; background-color:#fff; list-style:none; }
.mw-left-menus ul li { padding:7px 0 5px 15px; } 
.mw-left-menus ul li { background:url(<?=$mw_group_skin_head_path?>/img/dot.gif) no-repeat 7px 12px; border-bottom:1px dotted #e7e7e7; } 
.mw-left-menus a:hover { text-decoration:underline; } 
.mw-left-menus .select { color:#cc0000; color:#3f4ea1; font-weight:bold; } 

.latest-block { margin-top:10px; }

</style>

<table border=0 cellpadding=0 cellspacing=0 class="mw-group-page"><tr><td>

<script type="text/javascript">
function fsearchbox_submit(f)
{
    if (f.stx.value == '')
    {
        alert("검색어를 입력하세요.");
        f.stx.select();
        f.stx.focus();
        return;
    }

    /*
    // 검색에 많은 부하가 걸리는 경우 이 주석을 제거하세요.
    var cnt = 0;
    for (var i=0; i<f.stx.value.length; i++)
    {
        if (f.stx.value.charAt(i) == ' ')
            cnt++;
    }

    if (cnt > 1)
    {
        alert("빠른 검색을 위하여 검색어에 공백은 한개만 입력할 수 있습니다.");
        f.stx.select();
        f.stx.focus();
        return;
    }
    */

    f.action = "<?=$g4['bbs_path']?>/search.php";
    f.submit();
}
</script>

<table width=100% height=40 border=0 cellpadding=0 cellspacing=0>
<tr>
<td valign=top width=40%>
    <div style="float:left;"><a href="<?=$mw[index_url]?>"><img src="<?=$mw_group_skin_head_path?>/img/mw_logo.gif"></a></div>
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
    </div>    <!--
    <form name=fsearch method=get action="javascript:fsearch_submit(document.fsearch);">
    <input type=hidden name=srows value=<?=$srows?>>
    <input type=hidden name=sop value=and>
    <select name=sfl class=select>
    <option value="wr_subject||wr_content">제목+내용</option>
    <option value="wr_subject">제목</option>
    <option value="wr_content">내용</option>
    <option value="mb_id">회원아이디</option>
    <option value="wr_name">이름</option>
    </select>
    <input type=text name=stx maxlength=20 required itemname="검색어" value="<?=$text_stx?>">
    <input type=submit value=" 검 색 ">
    </form>
    <? if (!$sfl) $sfl = "wr_subject||wr_content"; ?>
    <script type="text/javascript">
    //document.fsearch.sfl.value = "<?=$sfl?>";
    </script>
    -->
</td>
</tr>
</table>

<div id="head-fixed">

<table width=100% height=70 border=0 cellpadding=0 cellspacing=0 align=center>
<tr>
    <td width=10 style="background:url(<?=$mw_group_skin_head_path?>/img/<?=$group[gr_theme]?>/mw-bar-left.gif) no-repeat;"></td>
    <td style="background:url(<?=$mw_group_skin_head_path?>/img/<?=$group[gr_theme]?>/mw-bar-bg.gif) repeat-x;" valign=top>
        <div class="mw-menus-middle-start">
	    <? if (!$group[gr_home_not]) { // 그룹홈 메뉴 ?>
	    <? if ($mm_id) { // 그룹홈 선택 ?>
	    <div class="mw-menus-middle-item"><a href="<?=$group[gr_url]?>"><?=$group[gr_subject]?>홈</a></div>
	    <? } else { ?>
	    <div class="mw-menus-middle-select1"><div class="mw-menus-middle-select2"><div class="mw-menus-middle-select3">
	    <a href="<?=$group[gr_url]?>"><?=$group[gr_subject]?>홈</a>
	    </div></div></div>
	    <? } ?>
	    <? } ?>

	    <? for ($i=0; $i<sizeof($mw_mmenus); $i++) { // 중메뉴 출력 ?>
            <? if ($mw_mmenus[$i][mm_level_view] > $member[mb_level]) continue; ?>
	    <? if ($mw_mmenus[$i][mm_id] == $mm_id) { // 중메뉴 선택 ?>
	    <div class="mw-menus-middle-select1"><div class="mw-menus-middle-select2"><div class="mw-menus-middle-select3">
	    <a href="<?=$mw_mmenus[$i][mm_url]?>" title="<?=mw_html_entities($mw_mmenus[$i][mm_title])?>" target="<?=$mw_mmenus[$i][mm_target]?>"><?=$mw_mmenus[$i][mm_name]?></a>
	    </div></div></div>
	    <? } else { ?>
	    <div class="mw-menus-middle-item"><a href="<?=$mw_mmenus[$i][mm_url]?>" title="<?=mw_html_entities($mw_mmenus[$i][mm_title])?>" target="<?=$mw_mmenus[$i][mm_target]?>"><span style="<?=$mw_mmenus[$i][mm_css]?>"><?=$mw_mmenus[$i][mm_name]?></span></a></div>
	    <? } ?>
	    <? } ?>
	</div>
	<div class="mw-menus-small">
	    <? if (!$mw_mmenu[mm_smenu]) { // 소메뉴 출력 여부 ?>
	    <? if (!$mw_mmenu[mm_smove] || sizeof($mw_smenus) > 1) { // 중메뉴에서 소메뉴로 바로이동하지 않거나 소메뉴가 1개 초과일 경우에만 출력 ?>
	    <? for ($i=0; $i<sizeof($mw_smenus); $i++) { // 소메뉴 출력 ?>
            <? if ($mw_smenus[$i][ms_level_view] > $member[mb_level]) continue; ?>
	    <? if ($i > 0) echo "<div class='mw-menus-small-div'>|</div>"; ?>
	    <? if ($mw_smenus[$i][ms_id] == $ms_id) { // 소메뉴 선택 ?>
	    <div class="mw-menus-small-item"><a href="<?=$mw_smenus[$i][ms_url]?>" title="<?=mw_html_entities($mw_smenus[$i][ms_title])?>" target="<?=$mw_smenus[$i][ms_target]?>"><span style="<?=$mw_smenus[$i][ms_css]?>"><b><?=$mw_smenus[$i][ms_name]?></b></span></a></div>
	    <? } else { ?>
	    <div class="mw-menus-small-item"><a href="<?=$mw_smenus[$i][ms_url]?>" title="<?=mw_html_entities($mw_smenus[$i][ms_title])?>" target="<?=$mw_smenus[$i][ms_target]?>"><span style="<?=$mw_smenus[$i][ms_css]?>"><?=$mw_smenus[$i][ms_name]?></span></a></div>
	    <? } ?> <? } ?> <? } ?> <? } ?>
	</div>
    </td>
    <td width=10 style="background:url(<?=$mw_group_skin_head_path?>/img/<?=$group[gr_theme]?>/mw-bar-right.gif) no-repeat;"></td>
</tr>
</table>

</div><!-- head-fixed -->

<div id="head-fixed-back" class="head-fixed-back" style="display:none;"></div>

<script src="<?php echo $g4['path']?>/js/mw.head.fixed.js"></script>
<script>
$(window).ready(function () {
    $(window).bind('scroll', function () {
        head_fixed.run(<?php echo $group_width?>, 80);
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

