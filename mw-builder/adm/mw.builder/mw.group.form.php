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

$sub_menu = "110300";
include_once("_common.php");
include_once("mw.menus.lib.php");

auth_check($auth[$sub_menu], "w");

$token = get_token();

//if ($is_admin != "super" && $w == "") alert("최고관리자만 접근 가능합니다.");

$html_title = "그룹관리";
if ($w == "") 
{
    $gr_id_attr = "required";
    $gr[gr_use_access] = 0;
    $html_title .= " 생성";
} 
else if ($w == "u") 
{
    $gr_id_attr = "readonly style='background-color:#dddddd'";
    $gr = sql_fetch(" select * from $g4[group_table] where gr_id = '$gr_id' ");
    $html_title .= " 수정";
    if ($gr[gr_mmove]) $gr_mmove = "checked";
    if ($gr[gr_smove]) $gr_smove = "checked";
    if ($gr[gr_use_not]) $gr_use_not= "checked";
    if ($gr[gr_check]) $gr_check= "checked";
    if ($gr[gr_only_admin]) $gr_only_admin = "checked";
    if ($gr[gr_home_not]) $gr_home_not = "checked";
    if ($gr[gr_sitemap_not]) $gr_sitemap_not = "checked";
    if ($gr[gr_more]) $gr_more = "checked";
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

$g4[title] = $html_title;
include_once("$g4[admin_path]/admin.head.php");
?>

<form name=fboardgroup method=post onsubmit="return fboardgroup_check(this);" autocomplete="off">
<input type=hidden name=w     value='<?=$w?>'>
<input type=hidden name=sfl   value='<?=$sfl?>'>
<input type=hidden name=stx   value='<?=$stx?>'>
<input type=hidden name=sst   value='<?=$sst?>'>
<input type=hidden name=sod   value='<?=$sod?>'>
<input type=hidden name=page  value='<?=$page?>'>
<input type=hidden name=token value='<?=$token?>'>
<table width=100% cellpadding=0 cellspacing=0>
<colgroup width=30% class='col1 pad1 bold right'>
<colgroup width=70% class='col2 pad2'>
<tr class='ht'>
    <td colspan=4 class=title align=left><img src='<?=$g4[admin_path]?>/img/icon_title.gif'> <?=$html_title?></td>
</tr>
<tr><td colspan=4 class='line1'></td></tr>
<tr class='ht'>
    <td>그룹 ID</td>
    <td><input type='text' class=ed name=gr_id size=11 maxlength=10 <?=$gr_id_attr?> alphanumericunderline itemname='그룹 아이디' value='<?=$group[gr_id]?>'> 영문자, 숫자, _ 만 가능 (공백없이)</td>
</tr>
<tr class='ht'>
    <td>그룹 제목</td>
    <td>
        <input type='text' class=ed name=gr_subject size=40 maxlength=40 required itemname='그룹 제목' value='<?=get_text($group[gr_subject])?>'>
	<a href="<?=$g4[path]?>?mw_main=<?=$group[gr_id]?>"><img src="<?=$g4[admin_path]?>/img/icon_view.gif" align=absmiddle></a>
    </td>
</tr>
<tr class='ht'>
    <td>서브도메인</td>
    <td>
	<input type=text class=ed size=15 name=gr_sub_domain value="<?=$group[gr_sub_domain]?>" maxlength=20>
	<?=help("도메인이 g4.miwit.com 이라면 g4 만 입력하세요.
네임서버 및 웹서버에 서브도메인이 설정되어 있어야 정상 작동 합니다.
서브도메인을 * (예:*.domain.com) 로 설정하면 편리합니다. 서버설정은 호스팅 회사에 문의하세요.")?>
    </td>
</tr>
<tr class='ht'>
    <td>그룹 HEAD 스킨</td>
    <td>
	<select id=gr_skin_head name=gr_skin_head onchange="get_theme(this.value)"><option value=''></option><?=$skin_options?></select>
	<span id="select-theme"></span>
    </td>
</tr>
<!--
<tr class='ht'>
    <td>그룹 MAIN 스킨</td>
    <td>
	<select id=gr_skin_main name=gr_skin_main><option value=''></option><?=$skin_options?></select>
    </td>
</tr>
-->
<tr class='ht'>
    <td>그룹 TAIL 스킨</td>
    <td>
	<select id=gr_skin_tail name=gr_skin_tail><option value=''></option><?=$skin_options?></select>
    </td>
</tr>
<tr class='ht'>
    <td>그룹 HOME 스킨</td>
    <td>
	<select id=gr_skin_home name=gr_skin_home><option value=''></option><?=$skin_options?></select>
    </td>
</tr>
<tr class='ht'>
    <td>최근 게시물 Cache 시간</td>
    <td>
	<input type=text size=4 name=gr_cache maxlength=4 value="<?=$group[gr_cache]?>" numeric class=ed> 분
	<?=help("정해진 분마다 최근게시물을 파일로 저장하여 DB 부하를 줄여줍니다. 0으로 설정하면 작동하지 않습니다.")?>
    </td>
</tr>
<tr class='ht'>
    <td>출력안함</td>
    <td>
	<input type=checkbox name=gr_use_not value="1" <?=$gr_use_not?>> 출력안함 
    </td>
</tr>
<tr class='ht'>
    <td>점검중</td>
    <td>
	<input type=checkbox name=gr_check value="1" <?=$gr_check?>> 점검중 메시지 출력, 관리자만 접근가능
    </td>
</tr>
<tr class='ht'>
    <td>사이트맵 출력 안함</td>
    <td>
	<input type=checkbox name=gr_sitemap_not value="1" <?=$gr_sitemap_not?>> 출력안함 
    </td>
</tr>
<tr class='ht'>
    <td>더보기로 출력</td>
    <td>
	<input type=checkbox name=gr_more value="1" <?=$gr_more?>>
	<?=help("그룹메뉴가 많아 자리확보가 어려운 경우 '더보기'를 클릭했을 때 메뉴가 출력되게 합니다.")?>
	&nbsp;&nbsp;CSS <input type=text size=30 class=ed name=gr_more_css value="<?=$group[gr_more_css]?>"> 
	<?=help("'더보기' 메뉴중 강조하고 싶은 메뉴가 있다면 CSS 를 직접 입력해주세요. 예) font-weight:bold;")?>
    </td>
</tr>
<tr class='ht'>
    <td>관리자만 출력</td>
    <td>
	<input type=checkbox name=gr_only_admin value="1" <?=$gr_only_admin?>> 사용
	<?=help("출력 여부만 결정합니다. 일반회원의 접근권한과는 관계가 없으니 유의하세요.")?>
    </td>
</tr>
<tr class='ht'>
    <td>첫번째 중메뉴로 바로이동</td>
    <td>
	<input type=checkbox name=gr_mmove value="1" <?=$gr_mmove?>> 사용
    </td>
</tr>
<tr class='ht'>
    <td>첫번째 소메뉴로 바로이동</td>
    <td>
	<input type=checkbox name=gr_smove value="1" <?=$gr_smove?>> 사용
    </td>
</tr>
<tr class='ht'>
    <td>그룹 홈 사용안함</td>
    <td>
	<input type=checkbox name=gr_home_not value="1" <?=$gr_home_not?>> 사용안함
    </td>
</tr>
<tr class='ht'>
    <td>가로크기</td>
    <td>
	<input type=text class=ed size=4 name=gr_width value="<?=$group[gr_width]?>" numeric required maxlength=4> px (100 이하는 %)
    </td>
</tr>
<tr class='ht'>
    <td>외부링크 URL</td>
    <td>
	<input type=text class=ed size=30 name=gr_url value="<?=$group[gr_url]?>">
	<?=help("외부로 링크걸 때 입력하세요.")?>
    </td>
</tr>
<tr class='ht'>
    <td>타겟</td>
    <td>
	<select name="gr_target">
	<option value=""> </option>
	<option value="_self"> _self </option>
	<option value="_top"> _top </option>
	<option value="_blank"> _blank </option>
	</select>
	<script type="text/javascript">document.fboardgroup.gr_target.value = '<?=$group[gr_target]?>'</script>
    </td>
</tr>
<tr class='ht'>
    <td>메뉴접근 레벨</td>
    <td>
        <?=get_member_level_select("gr_level", 1, $member[mb_level], $group[gr_level])?>
	<?=help("그룹에 접근할 수 있는 회원레벨")?>
    </td>
</tr>
<tr class='ht'>
    <td>메뉴접근 성별</td>
    <td>
        <select name="gr_gender">
            <option value=""> 모두 </option>
            <option value="M"> 남자만 </option>
            <option value="F"> 여자만 </option>
        </select>
    </td>
</tr>
<tr class='ht'>
    <td>메뉴접근 나이</td>
    <td>
        <?
        preg_match("/^([0-9]+)([\+\-\=])$/", $group[gr_age], $match);
        $age = $match[1];
        $age_type = $match[2];
        ?>
        <input type="text" class="ed" size="3" name="gr_age" value="<?=$age?>">세
        <select name="gr_age_type">
            <option value=""></option>
            <option value="+">이상 접근가능</option>
            <option value="-">미만 접근가능</option>
            <option value="=">만 접근가능</option>
        </select>
    </td>
</tr>
<tr><td colspan=4 class='line2'></td></tr>
</table>

<p align=center>
    <input type=submit class=btn1 accesskey='s' value='  확  인  '>&nbsp;
    <input type=button class=btn1 accesskey='s' value='  중메뉴관리  ' onclick="document.location.href='<?=$mw[admin_path]?>/mw.m.menu.php?gr_id=<?=$gr_id?>';">&nbsp;
    <input type=button class=btn1 value='  목  록  ' onclick="document.location.href='<?=$mw[admin_path]?>/mw.group.php?<?=$qstr?>';">
</form>

<script type="text/javascript" src="<?=$g4[path]?>/js/jquery.js"></script>
<script type="text/javascript">
document.fboardgroup.gr_skin_head.value = "<?=$group[gr_skin_head]?>";
//document.fboardgroup.gr_skin_main.value = "<?=$group[gr_skin_main]?>";
document.fboardgroup.gr_skin_tail.value = "<?=$group[gr_skin_tail]?>";
document.fboardgroup.gr_skin_home.value = "<?=$group[gr_skin_home]?>";
document.fboardgroup.gr_gender.value = "<?=$group[gr_gender]?>";
document.fboardgroup.gr_age_type.value = "<?=$age_type?>";

if (document.fboardgroup.w.value == '')
    document.fboardgroup.gr_id.focus();
else
    document.fboardgroup.gr_subject.focus();

function fboardgroup_check(f)
{
    f.action = "<?=$mw[admin_path]?>/mw.group.update.php";
    return true;
}
function get_theme(val, val2) {
    if (typeof val2 == 'undefined') val2 = '';
    var url = "<?=$mw[admin_path]?>/mw.group.theme.php?skin=" + val + "&theme=" + val2;
    $("#select-theme").load(url);
}
$(document).ready(function() {
    get_theme("<?=$group[gr_skin_head]?>", "<?=$group[gr_theme]?>");
});
</script>

<?
include_once ("$g4[admin_path]/admin.tail.php");
