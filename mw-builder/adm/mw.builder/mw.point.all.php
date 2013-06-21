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

$sub_menu = "110850";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$token = get_token();

$g4[title] = "포인트 일괄지급";
include_once ("$g4[admin_path]/admin.head.php");

$colspan = 4;
?>

<?=subtitle($g4[title])?>

<form name=fpointlist2 method=post onsubmit="return fpointlist2_submit(this);" autocomplete="off">
<input type=hidden name=sfl   value='<?=$sfl?>'>
<input type=hidden name=stx   value='<?=$stx?>'>
<input type=hidden name=sst   value='<?=$sst?>'>
<input type=hidden name=sod   value='<?=$sod?>'>
<input type=hidden name=page  value='<?=$page?>'>
<input type=hidden name=token value='<?=$token?>'>
<table width=100% cellpadding=0 cellspacing=1 class=tablebg>
<colgroup width=100>
<colgroup width=''>
<colgroup width=100>
<tr><td colspan='<?=$colspan?>' class='line1'></td></tr>
<tr class='bgcol1 bold col1 ht center'>
    <td>포인트</td>
    <td>포인트 내용</td>
    <td>입력</td>
</tr>
<tr><td colspan='<?=$colspan?>' class='line2'></td></tr>
<tr class='ht center'>
    <td><input type=text class=ed name=po_point required itemname='포인트' size=10></td>
    <td><input type=text class=ed name=po_content required itemname='내용' style='width:99%;'></td>
    <td><input type=submit class=btn1 value='  확  인  '></td>
</tr>
<tr><td colspan='<?=$colspan?>' class='line2'></td></tr>
</form>
</table>

<script type="text/javascript">
function fpointlist2_submit(f)
{
    if (!confirm("모든 회원에게 포인트를 일괄 지급하시겠습니까?")) return false;

    f.action = "mw.point.all.update.php";
    return true;
}
</script>

<?
include_once ("$g4[admin_path]/admin.tail.php");
?>
