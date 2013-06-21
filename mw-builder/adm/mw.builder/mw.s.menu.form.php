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

$sub_menu = "110500";
include_once("_common.php");
include_once("mw.menus.lib.php");

auth_check($auth[$sub_menu], "w");

$token = get_token();

//if ($is_admin != "super" && $w == "") alert("최고관리자만 접근 가능합니다.");

if ($w == "u" && $ms_id) {
    $ms = sql_fetch("select * from $mw[menu_small_table] where ms_id = '$ms_id'");
    $mm_id = $ms[mm_id];

    if ($ms[ms_use_not]) $ms_use_not= "checked";
    if ($ms[ms_check]) $ms_check= "checked";
    if ($ms[ms_only_admin]) $ms_only_admin = "checked";

    if ($ms[ms_lmenu]) $ms_lmenu = "checked";
    if ($ms[ms_rmenu]) $ms_rmenu = "checked";
}

$mm = sql_fetch("select * from $mw[menu_middle_table] where mm_id = '$mm_id'");

$html_title = "소메뉴";
if ($w == "") 
{
    $mm_id_attr = "required";
    $gr[gr_use_access] = 0;
    $html_title .= " 생성";
} 
else if ($w == "u") 
{
    $mm_id_attr = "readonly style='background-color:#dddddd'";
    //$gr = sql_fetch(" select * from $g4[group_table] where mm_id = '$mm_id' ");
    $html_title .= " 수정";
} 
else
    alert("제대로 된 값이 넘어오지 않았습니다.");

$board_options = "";
$sql = "select bo_table, bo_subject from $g4[board_table] where gr_id = '$mm[gr_id]'";
$qry = sql_query($sql);
while ($row = sql_fetch_array($qry)) {
    $board_options .= "<option value='$row[bo_table]'>$row[bo_subject] ($row[bo_table])</option>";
}

// 중메뉴 
$mm_options = "";
$sql = "select g.gr_id, g.gr_subject, mm_id, mm_name 
	  from $mw[menu_middle_table] as m, $g4[group_table] as g 
	 where m.gr_id = g.gr_id 
	 order by gr_order asc, mm_order asc, mm_id asc";
$qry = sql_query($sql);
while ($row = sql_fetch_array($qry)) {
    $mm_options .= "<option value='$row[mm_id]'>$row[gr_subject] - $row[mm_name]</option>";
}

$g4[title] = $html_title;
include_once("$g4[admin_path]/admin.head.php");
?>

<form name=fmenu method=post onsubmit="return fmenu_check(this);" autocomplete="off">
<input type=hidden name=w     value='<?=$w?>'>
<input type=hidden name=sfl   value='<?=$sfl?>'>
<input type=hidden name=stx   value='<?=$stx?>'>
<input type=hidden name=sst   value='<?=$sst?>'>
<input type=hidden name=sod   value='<?=$sod?>'>
<input type=hidden name=page  value='<?=$page?>'>
<input type=hidden name=token value='<?=$token?>'>
<input type=hidden name=ms_id value='<?=$ms_id?>'>
<table width=100% cellpadding=0 cellspacing=0>
<colgroup width=20% class='col1 pad1 bold right'>
<colgroup width=80% class='col2 pad2'>
<tr class='ht'>
    <td colspan=4 class=title align=left><img src='<?=$g4[admin_path]?>/img/icon_title.gif'> <?=$html_title?></td>
</tr>
<tr><td colspan=4 class='line1'></td></tr>
<? if ($w == 'u') { ?>
<tr class='ht'>
    <td>고유번호</td>
    <td> <?=$ms_id?> ($ms_id) </td>
</tr>
<? } ?>
<tr class='ht'>
    <td>중메뉴</td>
    <td>
	<select name=mm_id required itemname="중메뉴"><option value="">선택해주세요</option><?=$mm_options?></select>
        <input type="button" class="btn1" value="수정" onclick="window.open('mw.m.menu.form.php?w=u&mm_id='+fmenu.mm_id.value)">
	<script type="text/javascript">document.fmenu.mm_id.value = '<?=$mm_id?>'</script>
    </td>
</tr>
<tr class='ht'>
    <td>메뉴 제목</td>
    <td>
        <input type='text' class=ed name=ms_name size=40 required itemname='메뉴 제목' value='<?=get_text($ms[ms_name])?>'>
    </td>
</tr>
<tr class='ht'>
    <td>왼쪽메뉴 출력안함</td>
    <td>
	<input type=checkbox name=ms_lmenu value="1" <?=$ms_lmenu?>> 사용 
    </td>
</tr>
<tr class='ht'>
    <td>오른쪽메뉴 출력안함</td>
    <td>
	<input type=checkbox name=ms_rmenu value="1" <?=$ms_rmenu?>> 사용 
    </td>
</tr>
<tr class='ht'>
    <td>관리자만 출력</td>
    <td>
	<input type=checkbox name=ms_only_admin value="1" <?=$ms_only_admin?>> 사용
	<?=help("출력 여부만 결정합니다. 일반회원의 접근권한과는 관계가 없으니 유의하세요.")?>
    </td>
</tr>
<tr class='ht'>
    <td>출력안함</td>
    <td>
	<input type=checkbox name=ms_use_not value="1" <?=$ms_use_not?>> 출력안함 
    </td>
</tr>
<tr class='ht'>
    <td>점검중</td>
    <td>
	<input type=checkbox name=ms_check value="1" <?=$ms_check?>> 점검중 메시지 출력, 관리자만 접근가능
    </td>
</tr>
<tr class='ht'>
    <td>게시판 [4]</td>
    <td>
	<select name=bo_table onchange="get_cate(this.value)"><option value="">선택해주세요</option><?=$board_options?></select>
	<span id="select-cate"></span>
	<script type="text/javascript">document.fmenu.bo_table.value = '<?=$ms[bo_table]?>'</script>
    </td>
</tr>
<tr class='ht'>
    <td>페이지 [3]</td>
    <td>
	<select name=pg_id onchange="get_cate(this.value)">
        <option value="">선택해주세요</option>
        <?
        $sql = "select * from $mw[page_table] order by pg_id desc";
        $qry = sql_query($sql);
        while ($row = sql_fetch_array($qry)) {
        ?>
        <option value="<?=$row[pg_id]?>"><?=get_text($row[pg_subject])?></option>
        <? } ?>
        </select>
	<script type="text/javascript">document.fmenu.pg_id.value = '<?=$ms[pg_id]?>'</script>
    </td>
</tr>
<tr class='ht'>
    <td>사용자정의 URL [2]</td>
    <td>
	<?=$g4[url]?>
	<input type=text class=ed size=50 name=ms_url value="<?=$ms[ms_url]?>" maxlength=255>
	<?=help("사용자가 별도로 제작한 파일로 연결할 경우 도메인 이후 경로를 입력해주세요. 예) 전체 주소가 http://www.miwit.com/gnuboard4/bbs/new.php 일 경우 /bbs/new.php 만 작성하면 됩니다.")?>
    </td>
</tr>
<tr class='ht'>
    <td>외부링크 URL [1]</td>
    <td>
	<input type=text class=ed size=50 name=ms_out_url value="<?=$ms[ms_out_url]?>" maxlength=255>
	<?=help("사이트내 페이지가 아닌 다른곳으로 링크를 걸때 입력하세요.")?>
    </td>
</tr>
<tr class='ht'>
    <td>타겟</td>
    <td>
	<select name="ms_target">
	<option value=""> </option>
	<option value="_self"> 현재 프레임 (_self) </option>
	<option value="_top"> 전체 프레임 (_top) </option>
	<option value="_blank"> 새창 (_blank) </option>
	</select>
	<script type="text/javascript">document.fmenu.ms_target.value = '<?=$ms[ms_target]?>'</script>
    </td>
</tr>
<tr class='ht'>
    <td>CSS</td>
    <td>
	<input type=text size=30 class=ed name=ms_css value="<?=$ms[ms_css]?>"> 
	<?=help("강조하고 싶은 메뉴가 있다면 CSS 를 직접 입력해주세요. 예) color:#ff0000;")?>
    </td>
</tr>
<tr class='ht'>
    <td>메뉴접근 레벨</td>
    <td>
        <?=get_member_level_select("ms_level", 1, $member[mb_level], $ms[ms_level])?>
	<?=help("소메뉴에 접근할 수 있는 회원레벨")?>
    </td>
</tr>
<tr class='ht'>
    <td>메뉴접근 성별</td>
    <td>
        <select name="ms_gender">
            <option value=""> 모두 </option>
            <option value="M"> 남자만 </option>
            <option value="F"> 여자만 </option>
        </select>
	<script type="text/javascript">document.fmenu.ms_gender.value = '<?=$ms[ms_gender]?>'</script>
    </td>
</tr>
<tr class='ht'>
    <td>메뉴접근 나이</td>
    <td>
        <?
        preg_match("/^([0-9]+)([\+\-\=])$/", $ms[ms_age], $match);
        $age = $match[1];
        $age_type = $match[2];
        ?>
        <input type="text" class="ed" size="3" name="ms_age" value="<?=$age?>">세
        <select name="ms_age_type">
            <option value=""></option>
            <option value="+">이상 접근가능</option>
            <option value="-">미만 접근가능</option>
            <option value="=">만 접근가능</option>
        </select>
	<script type="text/javascript">document.fmenu.ms_age_type.value = '<?=$age_type?>'</script>
    </td>
</tr>
<tr><td colspan=4 class='line2'></td></tr>
</table>

<p align=center>
    <input type=submit class=btn1 accesskey='s' value='  확  인  '>&nbsp;
    <input type=button class=btn1 value='  목  록  ' onclick="document.location.href='<?=$mw[admin_path]?>/mw.s.menu.php?mm_id=<?=$mm_id?><?=$qstr?>';">
</form>

<script type="text/javascript" src="<?=$g4[path]?>/js/jquery.js"></script>
<script type="text/javascript">
document.fmenu.bo_table.value = "<?=$ms[bo_table]?>";

if (document.fmenu.w.value == '')
    document.fmenu.mm_id.focus();
else
    document.fmenu.ms_name.focus();

function fmenu_check(f)
{
    f.action = "<?=$mw[admin_path]?>/mw.s.menu.update.php";
    return true;
}

function get_cate(val, val2) {
    if (typeof val2 == 'undefined') val2 = '';
    var url = "<?=$mw[admin_path]?>/mw.s.menu.cate.php?bo_table=" + val + "&ca_name=" + val2;
    $("#select-cate").load(url);
}
$(document).ready(function() {
    get_cate("<?=$ms[bo_table]?>", "<?=urlencode($ms[ca_name])?>");
});

</script>

<?
include_once ("$g4[admin_path]/admin.tail.php");
