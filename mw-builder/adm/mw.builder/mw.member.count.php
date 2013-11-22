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

$sub_menu = "110910";
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

if (empty($fr_date)) $fr_date = date("Y-m-d", $g4[server_time]-86400*7);
if (empty($to_date)) $to_date = $g4[time_ymd];

$qstr = "fr_date=$fr_date&to_date=$to_date";

$g4[title] = '회원가입 통계';

$colspan = 4;

include_once("$g4[admin_path]/admin.head.php");
?>

<?=subtitle($g4[title])?>

<script type="text/javascript">
function fcount_submit(ymd, gr_id, bo_table) 
{
    var f = document.fcount;
    f.ymd.value = ymd;
    f.gr_id.value = gr_id;
    f.bo_table.value = bo_table;
    f.action = "<?=$PHP_SELF?>";
    f.submit();
}
</script>

<form name=fcount method=get style="margin:0;">
<input type=hidden name="ymd">
<input type=hidden name="gr_id" value="<?=$gr_id?>">
<input type=hidden name="bo_table" value="<?=$bo_table?>">
<table width=100% cellpadding=3 cellspacing=1>
<tr>
    <td>
        기간 : 
        <input type='text' name='fr_date' size=11 maxlength=10 value='<?=$fr_date?>' class=ed>
        -
        <input type='text' name='to_date' size=11 maxlength=10 value='<?=$to_date?>' class=ed>
        &nbsp;
        <input type=button class=btn1 value=' 일 ' onclick="fcount_submit('d', document.fcount.gr_id.value, document.fcount.bo_table.value);">
        <input type=button class=btn1 value=' 월 ' onclick="fcount_submit('m', document.fcount.gr_id.value, document.fcount.bo_table.value);">
        <input type=button class=btn1 value=' 년 ' onclick="fcount_submit('y', document.fcount.gr_id.value, document.fcount.bo_table.value);">
        &nbsp;&nbsp;
    </td>
</tr>
</table>
</form>


<table width=100% cellpadding=0 cellspacing=1 border=0>
<colgroup width=100>
<colgroup width=100>
<colgroup width=100>
<colgroup width=''>
<tr><td colspan='<?=$colspan?>' class='line1'></td></tr>
<tr class='bgcol1 bold col1 ht center'>
    <td>년-월-일</td>
    <td>가입수</td>
    <td>비율(%)</td>
    <td>그래프</td>
</tr>
<tr><td colspan='<?=$colspan?>' class='line2'></td></tr>

<?
$list = array();
$max = $sum = 0;

$sql_select = " count(*) as cnt ";
$sql_common = " from $g4[member_table] ";
$sql_search = " where mb_datetime between '$fr_date 00:00:00' and '$to_date 23:59:59' and mb_level > 1 ";
$sql_group  = " group by day ";
$sql_order = " order by mb_datetime desc ";

switch ($ymd) {
    case "y":
        $sql_select .= ", left(mb_datetime, 4) as day ";
        break;
    case "m":
        $sql_select .= ", left(mb_datetime, 7) as day  ";
        break;
    default:
	$ymd = "d";
        $sql_select .= ", left(mb_datetime, 10) as day  ";
        break;
}

$sql = "select
	$sql_select
        $sql_common 
        $sql_search 
        $sql_group 
        $sql_order";
$qry = sql_query($sql);

for ($i=0; $row=sql_fetch_array($qry); $i++) {
    $list[$i] = $row;
    if ($row[cnt] > $max) $max = $row[cnt];
    $sum += $row[cnt];
}
$total_count = count($list);

$rows = $config[cf_page_rows];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if (!$page) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

for ($i=$from_record; $i<$from_record+$rows; $i++) { 
    if ($i>=$total_count) break;

    $per1 = round($list[$i][cnt] / $sum * 100);
    $per2 = round($list[$i][cnt] / $max * 100);
    $day = $list[$i][day];

    if ($ymd == "d")
        $day .= " (".get_yoil($day).")";
    if ($ymd == "m")
        $day = "<a href='$_SERVER[PHP_SELF]?ymd=d&fr_date=$day-01&to_date=".date("Y-m-t", strtotime("$day-01 00:00:00"))."'>$day</a>";
    if ($ymd == "y")
        $day = "<a href='$_SERVER[PHP_SELF]?ymd=m&fr_date=$day-01-01&to_date=$day-12-31'>$day</a>";
 
    $l = ($i%2);
    echo "
    <tr class='list$l col1 ht center'>
	<td> $day </td>
	<td> ".number_format($list[$i][cnt])." </td>
	<td> $per1% </td>
	<td align=left> 
	    <img src='$g4[admin_path]/img/graph.gif' width='$per2%' height='18'>
	</td>
    <tr>";
}

if ($i == 0)
    echo "<tr><td colspan='$colspan' height=100 align=center>자료가 없습니다.</td></tr>"; 
else {
    echo "<tr><td colspan='$colspan' class='line2'></td></tr>
<tr class='bgcol1 bold col1 ht center'>
    <td>합계</td>
    <td>".number_format($sum)."</td>
    <td>100%</td>
    <td>&nbsp;</td>
</tr>";
}

echo "<tr><td colspan='$colspan' class='line1'></td></tr>";
echo "</table>";

$page = get_paging($config[cf_write_pages], $page, $total_page, "$_SERVER[PHP_SELF]?$qstr&domain=$domain&ymd=$ymd&page=");
if ($page) {
    echo "<table width=100% cellpadding=3 cellspacing=1><tr><td align=right>$page</td></tr></table>";
}

include_once("$g4[admin_path]/admin.tail.php");
