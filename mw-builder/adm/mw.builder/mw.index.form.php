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

$sub_menu = "110250";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$token = get_token();

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

if (!$mw[config][cf_index_width]) $mw[config][cf_index_width] = "800";
if ($mw[config][cf_index_cache] == '') $mw[config][cf_index_cache] = "10";

$g4['title'] = "INDEX 설정";
include_once ("$g4[admin_path]/admin.head.php");
?>

<form name='findex' method='post' onsubmit="return findexform_submit(this);">
<input type=hidden name=token value='<?=$token?>'>

<table width=100% cellpadding=0 cellspacing=0 border=0>
<colgroup width=20% class='col1 pad1 bold right'>
<colgroup width=30% class='col2 pad2'>
<colgroup width=20% class='col1 pad1 bold right'>
<colgroup width=30% class='col2 pad2'>
<tr class='ht'>
    <td colspan=4 align=left><?=subtitle($g4[title])?></td>
</tr>
<tr><td colspan=4 class=line1></td></tr>
<tr class='ht'>
    <td>INDEX 가로 크기</td>
    <td>
	<input type=text name=cf_index_width size=4 maxlength=4 value="<?=$mw[config][cf_index_width]?>" required numeric itemname="INDEX 가로 크기"> px (100 이하는 %)
    </td>
</tr>
<tr class='ht'>
    <td>최근 게시물 Cache 시간</td>
    <td>
	<input type=text size=4 name=cf_index_cache maxlength=4 value="<?=$mw[config][cf_index_cache]?>" numeric class=ed> 분
	<?=help("정해진 분마다 최근게시물을 파일로 저장하여 DB 부하를 줄여줍니다. 0으로 설정하면 작동하지 않습니다.")?>
    </td>
</tr>
<tr class='ht'>
    <td> INDEX HEAD 스킨</td>
    <td>
	<select id=cf_index_skin_head name=cf_index_skin_head><option value=''></option><?=$skin_options?></select>
    </td>
</tr>
<tr class='ht'>
    <td> INDEX MAIN 스킨</td>
    <td>
	<select id=cf_index_skin_main name=cf_index_skin_main><option value=''></option><?=$skin_options?></select>
    </td>
</tr>
<tr class='ht'>
    <td> INDEX TAIL 스킨</td>
    <td>
	<select id=cf_index_skin_tail name=cf_index_skin_tail><option value=''></option><?=$skin_options?></select>
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
document.findex.cf_index_skin_head.value = "<?=$mw[config][cf_index_skin_head]?>";
document.findex.cf_index_skin_main.value = "<?=$mw[config][cf_index_skin_main]?>";
document.findex.cf_index_skin_tail.value = "<?=$mw[config][cf_index_skin_tail]?>";
function findexform_submit(f)
{
    f.action = "./mw.index.update.php";
    return true;
}
</script>

<?
include_once ("$g4[admin_path]/admin.tail.php");
