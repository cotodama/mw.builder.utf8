<?
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

$sub_menu = "110830";
include_once("./_common.php");

auth_check($auth[$sub_menu], "w");

$token = get_token();

$html_title = "페이지";
if ($w == "") 
{
    $html_title .= " 생성";
} 
else if ($w == "u") 
{
    $html_title .= " 수정";
    $pp = sql_fetch("select * from $mw[page_table] where pg_id = '$pg_id'");
} 
else
    alert("제대로 된 값이 넘어오지 않았습니다.");

$g4[title] = $html_title;
include_once("$g4[admin_path]/admin.head.php");

include_once("$g4[path]/lib/cheditor4.lib.php");
echo "<script src='$g4[cheditor4_path]/cheditor.js'></script>";
echo cheditor1('pg_content', '100%', '250');
?>

<form name=fpage method=post onsubmit="return fpage_check(this);" autocomplete="off">
<input type=hidden name=w     value='<?=$w?>'>
<input type=hidden name=sfl   value='<?=$sfl?>'>
<input type=hidden name=stx   value='<?=$stx?>'>
<input type=hidden name=sst   value='<?=$sst?>'>
<input type=hidden name=sod   value='<?=$sod?>'>
<input type=hidden name=page  value='<?=$page?>'>
<input type=hidden name=token value='<?=$token?>'>
<input type=hidden name=pg_id value='<?=$pg_id?>'>
<table width=100% cellpadding=0 cellspacing=0>
<colgroup width=15% class='col1 pad1 bold right'>
<colgroup width=85% class='col2 pad2'>
<tr class='ht'>
    <td colspan=4 class=title align=left><img src='<?=$g4[admin_path]?>/img/icon_title.gif'> <?=$html_title?></td>
</tr>
<tr><td colspan=4 class='line1'></td></tr>
<tr class='ht'>
    <td>제목</td>
    <td><input type='text' class=ed name=pg_subject style="width:97%" maxlength=200 required itemname='제목' value='<?=$pp[pg_subject]?>'></td>
</tr>
<tr class='ht'>
    <td>내용</td>
    <td style="padding:0 0 10px 0;">
        <?=cheditor2('pg_content', $pp[pg_content]);?>
    </td>
</tr>
<tr><td colspan=4 class='line2'></td></tr>
</table>

<p align=center>
    <input type=submit class=btn1 accesskey='s' value='  확  인  '>&nbsp;
    <input type=button class=btn1 value='  목  록  ' onclick="document.location.href='<?=$mw[admin_path]?>/mw.page.php?<?=$qstr?>';">
</form>

<script language='JavaScript'>
document.fpage.pg_subject.focus();

function fpage_check(f)
{
    <?=cheditor3('pg_content')?>

    f.action = "<?=$mw[admin_path]?>/mw.page.update.php";
    return true;
}
</script>
<script type="text/javascript" src="<?=$g4[path]?>/geditor/geditor.js"></script>


<?
include_once ("$g4[admin_path]/admin.tail.php");
?>
