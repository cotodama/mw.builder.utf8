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

$sub_menu = "110400";
include_once("_common.php");
include_once("mw.menus.lib.php");

auth_check($auth[$sub_menu], "w");

$token = get_token();

//if ($is_admin != "super" && $w == "") alert("최고관리자만 접근 가능합니다.");

$html_title = "중메뉴";
if ($w == "") 
{
    $html_title .= " 생성";
    $mm[mm_width] = 900;
    $mm[mm_left] = 0;
    $mm[mm_reft] = 1;
    $mm[mm_seft] = 0;
} 
else if ($w == "u") 
{
    $html_title .= " 수정";
    $mm = sql_fetch("select * from $mw[menu_middle_table] where mm_id = '$mm_id'");
    $gr_id = $mm[gr_id];
    if ($mm[mm_smove]) $mm_smove = "checked";
    if ($mm[mm_lmenu]) $mm_lmenu = "checked";
    if ($mm[mm_rmenu]) $mm_rmenu = "checked";
    if ($mm[mm_smenu]) $mm_smenu = "checked";
    if ($mm[mm_only_admin]) $mm_only_admin = "checked";
    if ($mm[mm_use_not]) $mm_use_not= "checked";
    if ($mm[mm_check]) $mm_check= "checked";
} 
else
    alert("제대로 된 값이 넘어오지 않았습니다.");

// 스킨디렉토리
$skin_options = "";
$arr = get_skin_dir("mw.builder");
for ($k=0; $k<count($arr); $k++) 
{
    $option = $arr[$k];
    if (strlen($option) > 10)
        $option = substr($arr[$k], 0, 18) . "…";

    $skin_options .= "<option value='{$arr[$k]}'>$option</option>";
}

// 그룹
$group_options = "";
$sql = "select * from $g4[group_table] order by gr_order asc, gr_id asc";
$qry = sql_query($sql);
while ($row = sql_fetch_array($qry)) {
    $group_options .= "<option value='$row[gr_id]'>$row[gr_subject]</option>";
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
<input type=hidden name=mm_id value='<?=$mm_id?>'>
<table width=100% cellpadding=0 cellspacing=0>
<colgroup width=30% class='col1 pad1 bold right'>
<colgroup width=70% class='col2 pad2'>
<tr class='ht'>
    <td colspan=4 class=title align=left><img src='<?=$g4[admin_path]?>/img/icon_title.gif'> <?=$html_title?></td>
</tr>
<tr><td colspan=4 class='line1'></td></tr>
<? if ($w == 'u') { ?>
<tr class='ht'>
    <td>고유번호</td>
    <td> <?=$mm_id?> ($mm_id)</td>
</tr>
<? } ?>
<tr class='ht'>
    <td>그룹</td>
    <td>
	<select name=gr_id required itemname="그룹"><option value="">선택해주세요</option><?=$group_options?></select>
        <input type="button" class="btn1" value="수정" onclick="window.open('mw.group.form.php?w=u&gr_id='+fmenu.gr_id.value)">
	<script type="text/javascript">document.fmenu.gr_id.value = '<?=$gr_id?>'</script>
    </td>
</tr>
<tr class='ht'>
    <td>메뉴 제목</td>
    <td>
        <input type='text' class=ed name=mm_name size=40 required itemname='메뉴 제목' value='<?=get_text($mm[mm_name])?>'>
	<a href="<?=$g4[path]?>?mw_main=<?=$mm[gr_id]?>&mw_menu=<?=$mm[mm_id]?>"><img src="<?=$g4[admin_path]?>/img/icon_view.gif" align=absmiddle></a>
    </td>
</tr>
<tr class='ht'>
    <td>MAIN 스킨</td>
    <td>
	<select id=mm_skin name=mm_skin><option value=''></option><?=$skin_options?></select>
    </td>
</tr>
<tr class='ht'>
    <td>관리자만 출력</td>
    <td>
	<input type=checkbox name=mm_only_admin value="1" <?=$mm_only_admin?>> 사용
	<?=help("출력 여부만 결정합니다. 일반회원의 접근권한과는 관계가 없으니 유의하세요.")?>
    </td>
</tr>
<tr class='ht'>
    <td>출력안함</td>
    <td>
	<input type=checkbox name=mm_use_not value="1" <?=$mm_use_not?>> 출력안함 
    </td>
</tr>
<tr class='ht'>
    <td>점검중</td>
    <td>
	<input type=checkbox name=mm_check value="1" <?=$mm_check?>> 점검중 메시지 출력, 관리자만 접근가능
    </td>
</tr>
<tr class='ht'>
    <td>첫번째 소메뉴로 바로이동</td>
    <td>
	<input type=checkbox name=mm_smove value="1" <?=$mm_smove?>> 사용
    </td>
</tr>
<tr class='ht'>
    <td>왼쪽메뉴 출력안함</td>
    <td>
	<input type=checkbox name=mm_lmenu value="1" <?=$mm_lmenu?>> 사용 
    </td>
</tr>
<tr class='ht'>
    <td>오른쪽메뉴 출력안함</td>
    <td>
	<input type=checkbox name=mm_rmenu value="1" <?=$mm_rmenu?>> 사용 
    </td>
</tr>
<tr class='ht'>
    <td>상단 소메뉴 출력안함</td>
    <td>
	<input type=checkbox name=mm_smenu value="1" <?=$mm_smenu?>> 사용 
    </td>
</tr>
<tr class='ht'>
    <td>소메뉴 좌측여백</td>
    <td>
	<input type=text class=ed size=3 name=mm_left value="<?=$mm[mm_left]?>" numeric maxlength=3> px
    </td>
</tr>
<tr class='ht'>
    <td>외부 URL</td>
    <td>
	<input type=text class=ed size=50 name=mm_out_url value="<?=$mm[mm_out_url]?>" maxlength=255>
	<?=help("사이트내 페이지가 아닌 다른곳으로 링크를 걸때 입력하세요.")?>
    </td>
</tr>
<tr class='ht'>
    <td>타겟</td>
    <td>
	<select name="mm_target">
	<option value=""> </option>
	<option value="_self"> 현재 프레임 (_self) </option>
	<option value="_top"> 전체 프레임 (_top) </option>
	<option value="_blank"> 새창 (_blank) </option>
	</select>
	<script type="text/javascript">document.fmenu.mm_target.value = '<?=$mm[mm_target]?>'</script>
    </td>
</tr>
<tr class='ht'>
    <td>CSS</td>
    <td>
	<input type=text size=30 class=ed name=mm_css value="<?=$mm[mm_css]?>"> 
	<?=help("강조하고 싶은 메뉴가 있다면 CSS 를 직접 입력해주세요. 예) color:#ff0000;")?>
    </td>
</tr>
<tr class='ht'>
    <td>메뉴접근 레벨</td>
    <td>
        <?=get_member_level_select("mm_level", 1, $member[mb_level], $mm[mm_level])?>
	<?=help("중메뉴에 접근할 수 있는 회원레벨")?>
    </td>
</tr>
<tr class='ht'>
    <td>메뉴접근 성별</td>
    <td>
        <select name="mm_gender">
            <option value=""> 모두 </option>
            <option value="M"> 남자만 </option>
            <option value="F"> 여자만 </option>
        </select>
	<script type="text/javascript">document.fmenu.mm_gender.value = '<?=$mm[mm_gender]?>'</script>
    </td>
</tr>
<tr class='ht'>
    <td>메뉴접근 나이</td>
    <td>
        <?
        preg_match("/^([0-9]+)([\+\-\=])$/", $mm[mm_age], $match);
        $age = $match[1];
        $age_type = $match[2];
        ?>
        <input type="text" class="ed" size="3" name="mm_age" value="<?=$age?>">세
        <select name="mm_age_type">
            <option value=""></option>
            <option value="+">이상 접근가능</option>
            <option value="-">미만 접근가능</option>
            <option value="=">만 접근가능</option>
        </select>
	<script type="text/javascript">document.fmenu.mm_age_type.value = '<?=$age_type?>'</script>
    </td>
</tr>
<tr><td colspan=4 class='line2'></td></tr>
</table>

<p align=center>
    <input type=submit class=btn1 accesskey='s' value='  확  인  '>&nbsp;
    <input type=button class=btn1 accesskey='s' value='  소메뉴관리  ' onclick="document.location.href='<?=$mw[admin_path]?>/mw.s.menu.php?mm_id=<?=$mm_id?>';">&nbsp;
    <input type=button class=btn1 value='  목  록  ' onclick="document.location.href='<?=$mw[admin_path]?>/mw.m.menu.php?gr_id=<?=$gr_id?><?=$qstr?>';">
</form>

<script language='JavaScript'>
document.fmenu.mm_skin.value = "<?=$mm[mm_skin]?>";

if (document.fmenu.w.value == '')
    document.fmenu.gr_id.focus();
else
    document.fmenu.mm_name.focus();

function fmenu_check(f)
{
    f.action = "<?=$mw[admin_path]?>/mw.m.menu.update.php";
    return true;
}
</script>

<?
include_once ("$g4[admin_path]/admin.tail.php");
