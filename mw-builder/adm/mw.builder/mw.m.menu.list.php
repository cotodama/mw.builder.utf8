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

header("Content-Type: text/html; charset=$g4[charset]");
$gmnow = gmdate("D, d M Y H:i:s") . " GMT";
header("Expires: 0"); // rfc2616 - Section 14.21
header("Last-Modified: " . $gmnow);
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: pre-check=0, post-check=0, max-age=0"); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

auth_check($auth[$sub_menu], "r");

$token = get_token();

$sql_common = " from $mw[menu_middle_table] ";
$sql_search = " where gr_id = '$gr_id' ";
if ($stx)
    $sql_search .= " and ($sfl like '%$stx%') ";

if ($sst)
    $sql_order = " order by $sst $sod ";
else
    $sql_order = " order by mm_order asc, mm_id asc ";

$sql = " select count(*) as cnt
         $sql_common 
         $sql_search 
         $sql_order ";
$row = sql_fetch($sql);
$total_count = $row[cnt];

// 순서를 update 해준다.
$row = sql_fetch("select max(mm_order) as mm_max $sql_common where gr_id = '$gr_id'");
$max = $row[mm_max];
if ($total_count != $max) {
    $ord = 1;
    $qry = sql_query("select mm_id $sql_common where gr_id = '$gr_id' order by mm_order");
    while ($row = sql_fetch_array($qry)) {
       sql_query("update $mw[menu_middle_table] set mm_order = '".($ord++)."' where mm_id = '$row[mm_id]'"); 
    }
}

$rows = $config[cf_page_rows];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if (!$page) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * 
          $sql_common 
          $sql_search
          $sql_order"; 
//          limit $from_record, $rows ";
$result = sql_query($sql);

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

$listall = "<a href='mw.m.menu.php'>처음</a>";

$colspan = 9;
?>

<form name=fsearch method=get style="margin:0;">
<table width=100% cellpadding=3 cellspacing=1>
<tr>
    <td width=50% align=left>
	그룹 : <select name=gr_id onchange="menu_reload(this.value)"><option value="">선택해주세요</option><?=$group_options?></select>
	<?//=$listall?>
	(메뉴수 : <?=number_format($total_count)?>개)
	<script type="text/javascript">document.fsearch.gr_id.value = '<?=$gr_id?>'</script>
    </td>
    <td width=50% align=right>
        <select name=sfl>
            <option value="mm_name">제목</option>
            <option value="mm_id">ID</option>
        </select>
        <input type=text name=stx class=ed required itemname='검색어' value='<?=$stx?>'>
        <input type=image src='<?=$g4[admin_path]?>/img/btn_search.gif' align=absmiddle></td>
</tr>
</table>
</form>

<form name=fboardgrouplist method=post>
<input type=hidden name=sst   value='<?=$sst?>'>
<input type=hidden name=sod   value='<?=$sod?>'>
<input type=hidden name=sfl   value='<?=$sfl?>'>
<input type=hidden name=stx   value='<?=$stx?>'>
<input type=hidden name=page  value='<?=$page?>'>
<input type=hidden name=token value='<?=$token?>'>
<input type=hidden name=gr_id value='<?=$gr_id?>'>
<table width=100% cellpadding=0 cellspacing=1 border=0>
<colgroup width=30>
<colgroup width=100>
<colgroup width=30>
<colgroup width=''>
<colgroup width=90>
<colgroup width=80>
<colgroup width=50>
<colgroup width=110>
<tr><td colspan='<?=$colspan?>' class='line1'></td></tr>
<tr class='bgcol1 bold col1 ht center'>
    <td><input type=checkbox name=chkall value="1" onclick="check_all(this.form)"></td>
    <td>그룹 제목</td>
    <td>번호</td>
    <td>중메뉴 제목</td>
    <td>MAIN 스킨</td>
    <td>좌측여백</td>
    <td>소메뉴</td>
    <td><? echo "<a href='$mw[admin_path]/mw.m.menu.form.php?gr_id=$gr_id'><img src='$g4[admin_path]/img/icon_insert.gif' border=0 title='생성'></a>"; ?></td>
</tr>
<tr><td colspan='<?=$colspan?>' class='line2'></td></tr>
<?
for ($i=0; $row=sql_fetch_array($result); $i++) 
{
    // 소메뉴수
    $row1 = sql_fetch("select count(ms_id) as cnt from $mw[menu_small_table] where mm_id = '$row[mm_id]'", false);

    $s_upd = "<a href='$mw[admin_path]/mw.m.menu.form.php?$qstr&w=u&mm_id=$row[mm_id]'><img src='$g4[admin_path]/img/icon_modify.gif' border=0 title='수정'></a>";
    $s_mup = "<a href='javascript:mw_move(\"up\", \"$row[gr_id]\", \"$row[mm_id]\")'><img src='$mw[admin_path]/img/icon_up.gif' border=0 title='위로'></a>";
    $s_mdw = "<a href='javascript:mw_move(\"down\", \"$row[gr_id]\", \"$row[mm_id]\")'><img src='$mw[admin_path]/img/icon_down.gif' border=0 title='아래로'></a>";
    $s_del = "";
    if ($is_admin == "super") {
        $s_del = "<a href=\"javascript:post_delete('mw.m.menu.delete.php', '$row[gr_id]', '$row[mm_id]');\"><img src='$g4[admin_path]/img/icon_delete.gif' border=0 title='삭제'></a>";
    }

    $list = $i%2;
    echo "<input type=hidden name=mm_id[$i] value='$row[mm_id]'>";
    echo "<tr class='list$list' onmouseover=\"this.className='mouseover';\" onmouseout=\"this.className='list$list';\" height=27 align=center>";
    echo "<td><input type=checkbox name=chk[] value='$i'></td>";
    echo "<td><a href='$mw[admin_path]/mw.group.form.php?w=u&gr_id=$gr_id'><b>$group[gr_subject]</b></a></td>";
    echo "<td>$row[mm_id]</td>";
    echo "<td><input type=text name=mm_name[$i] size=25 value='$row[mm_name]' class=ed></td>";
    echo "<td><select id=mm_skin_$i name=mm_skin[$i]><option value=''></option>$skin_options</select></td>";
    echo "<td><input type=text name=mm_left[$i] size=3 value='$row[mm_left]' numeric maxlength=3 class=ed> px</td>";
    echo "<td><a href='$mw[admin_path]/mw.s.menu.php?mm_id=$row[mm_id]'>$row1[cnt]</a></td>";
    echo "<td>$s_upd $s_del $s_mup $s_mdw </td>";
    echo "</tr>\n";
    echo "<script type='text/javascript'>";
    echo "document.getElementById('mm_skin_$i').value='$row[mm_skin]';";
    echo "</script>";
} 

if (!$gr_id)
    echo "<tr><td colspan='$colspan' align=center height=100 bgcolor=#ffffff>왼쪽 상단 셀렉트 박스에서 그룹을 선택해주세요.</td></tr>"; 
elseif ($i == 0)
    echo "<tr><td colspan='$colspan' align=center height=100 bgcolor=#ffffff>자료가 없습니다.</td></tr>"; 

echo "<tr><td colspan='$colspan' class='line2'></td></tr>";
echo "</table>";

$pagelist = get_paging($config[cf_write_pages], $page, $total_page, "$_SERVER[PHP_SELF]?$qstr&page=");
echo "<table width=100% cellpadding=3 cellspacing=1>";
echo "<tr><td width=70%>";
echo "<input type=button class='btn1' value='선택수정' onclick=\"btn_check(this.form, 'update')\">";
//echo " <input type=button value='선택삭제' onclick=\"btn_check(this.form, 'delete')\">";
echo "</td>";
echo "<td width=30% align=right>$pagelist</td></tr></table>\n";

if ($stx)
    echo "<script>document.fsearch.sfl.value = '$sfl';</script>";
?>
</form>

<script type="text/javascript">
document.fsearch.gr_id.focus();
</script>

<form name='fpost' method='post'>
<input type='hidden' name='sst'   value='<?=$sst?>'>
<input type='hidden' name='sod'   value='<?=$sod?>'>
<input type='hidden' name='sfl'   value='<?=$sfl?>'>
<input type='hidden' name='stx'   value='<?=$stx?>'>
<input type='hidden' name='page'  value='<?=$page?>'>
<input type='hidden' name='token' value='<?=$token?>'>
<input type='hidden' name='gr_id'>
<input type='hidden' name='mm_id'>
</form>

