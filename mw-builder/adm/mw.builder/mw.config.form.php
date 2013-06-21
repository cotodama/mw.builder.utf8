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

$sub_menu = "110100";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$token = get_token();

$g4['title'] = "배추빌더 설정";
include_once ("$g4[admin_path]/admin.head.php");

// 스킨디렉토리
$skin_options = "";
$arr = get_skin_dir("mw.builder");
for ($k=0; $k<count($arr); $k++) 
{
    $option = $arr[$k];
    if (strlen($option) > 10)
        $option = substr($arr[$k], 0, 18) . "…";

    $skin_options .= "<option value='$arr[$k]'>$option</option>";
}

$sql = "select * from $mw[config_table] limit 1";
$mw[config] = sql_fetch($sql, false);

if ($mw[config][cf_overlogin]) $cf_overlogin = "checked";
if ($mw[config][cf_www]) $cf_www = "checked";
if ($mw[config][cf_member]) $cf_member = "checked";
if ($mw[config][cf_sub_domain_off]) $cf_sub_domain_off = "checked";

// 그룹
$group_options = "";
$sql = "select * from $g4[group_table] order by gr_order asc, gr_id asc";
$qry = sql_query($sql);
while ($row = sql_fetch_array($qry)) {
    $group_options .= "<option value='$row[gr_id]'>$row[gr_subject]</option>";
}
?>

<form name='fconfig' method='post' onsubmit="return fconfigform_submit(this);">
<input type=hidden name=token value='<?=$token?>'>

<table width=100% cellpadding=0 cellspacing=0 border=0>
<colgroup width=20% class='col1 pad1 bold right'>
<colgroup width=30% class='col2 pad2'>
<colgroup width=20% class='col1 pad1 bold right'>
<colgroup width=30% class='col2 pad2'>
<tr class='ht'>
    <td colspan=4 align=left><?=subtitle("기본 설정")?></td>
</tr>
<tr><td colspan=4 class=line1></td></tr>
<tr class='ht'>
    <td>www 로만 접속가능</td>
    <td>
	<input type=checkbox name=cf_www value=1 <?=$cf_www?>> 사용
	<?=help("http://domain.com 으로 접속하면 http://www.domain.com 으로 자동 변경됩니다.")?>
    </td>
</tr>
<tr class='ht'>
    <td>회원정보 서브도메인 member 로만 접속가능</td>
    <td>
	<input type=checkbox name=cf_member value=1 <?=$cf_member?>> 사용
    </td>
</tr>
<!--
<tr class='ht'>
    <td>중복로그인 방지</td>
    <td>
	<input type=checkbox name=cf_overlogin value=1 <?=$cf_overlogin?>> 사용
    </td>
</tr>
-->
<tr class='ht'>
    <td>서브도메인 사용안함</td>
    <td>
	<input type=checkbox name=cf_sub_domain_off value=1 <?=$cf_sub_domain_off?>> 사용안함 
        <?=help("그룹관리에서 서브도메인이 입력되어 있어도 이곳에서 사용안함으로 하면 서브도메인을 사용하지 않습니다.")?>
    </td>
</tr>
<tr class='ht'>
    <td>인기검색어 Cache 시간</td>
    <td>
	<input type=text size=4 name=cf_popular_cache maxlength=4 value="<?=$mw[config][cf_popular_cache]?>" numeric class=ed> 분
	<?=help("정해진 분마다 인기검색어를 파일로 저장하여 DB 부하를 줄여줍니다. 0으로 설정하면 작동하지 않습니다.")?>
    </td>
</tr>
<tr class='ht'>
    <td>기본그룹</td>
    <td>
	<select name="cf_default_group" itemname="그룹">
        <option value="">주의! 2단메뉴 사용시에만 선택해주세요.</option>
        <option value="">선택안함</option>
        <?=$group_options?></select>
	<?=help("2단메뉴 사용시 선택해주세요. (기본 3단메뉴) ")?>
    </td>
</tr>
<tr class='ht'>
    <td> 회원 HEAD 스킨</td>
    <td>
	<select id=cf_member_skin_head name=cf_member_skin_head><option value=''></option><?=$skin_options?></select>
    </td>
</tr>
<tr class='ht'>
    <td> 회원 TAIL 스킨</td>
    <td>
	<select id=cf_member_skin_tail name=cf_member_skin_tail><option value=''></option><?=$skin_options?></select>
    </td>
</tr>
<tr><td colspan=4 class=line2></td></tr>
<tr><td colspan=4 class=ht></td></tr>
</table>

<p align=center>
    <input type=submit class=btn1 accesskey='s' value='  확  인  '>
</p>
</form>

<script type="text/javascript">
document.fconfig.cf_member_skin_head.value = "<?=$mw[config][cf_member_skin_head]?>";
document.fconfig.cf_member_skin_tail.value = "<?=$mw[config][cf_member_skin_tail]?>";
document.fconfig.cf_default_group.value = "<?=$mw[config][cf_default_group]?>";
function fconfigform_submit(f)
{
    f.action = "./mw.config.update.php";
    return true;
}
</script>

<?
include_once ("$g4[admin_path]/admin.tail.php");
