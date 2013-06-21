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

$sub_menu = "110800";
include_once("./_common.php");

auth_check($auth[$sub_menu], "r");

$token = get_token();

$sql_common = " from $mw[popup_table] ";

$sql_search = " where (1) ";

if ($stx) {
    $sql_search .= " and ($sfl like '%$stx%') ";
}

if ($sst)
    $sql_order = " order by $sst $sod ";
else
    $sql_order = " order by pp_id desc ";

$sql = " select count(*) as cnt
         $sql_common 
         $sql_search 
         $sql_order ";
$row = sql_fetch($sql);
$total_count = $row[cnt];

$rows = $config[cf_page_rows];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if (!$page) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * 
          $sql_common 
          $sql_search
          $sql_order
          limit $from_record, $rows ";
$result = sql_query($sql);

$listall = "<a href='mw.popup.php'>처음</a>";

$colspan = 9;

$g4[title] = "팝업창관리";
include_once("$g4[admin_path]/admin.head.php");

$colspan = 9;
?>

<script type="text/javascript">
var list_delete_php = "<?=$mw[admin_path]?>/mw.popup.list.delete.php";
</script>

<form name=fsearch method=get>
<table width=100% cellpadding=3 cellspacing=1>
<tr>
    <td width=50% align=left><?=$listall?> (팝업수 : <?=number_format($total_count)?>개)</td>
    <td width=50% align=right>
        <select name=sfl>
            <option value="pp_subject">제목</option>
        </select>
        <input type=text name=stx class=ed required itemname='검색어' value='<?=$stx?>'>
        <input type=image src='<?=$g4[admin_path]?>/img/btn_search.gif' align=absmiddle></td>
</tr>
</table>
</form>

<form name=fpopuplist method=post>
<input type=hidden name=sst   value='<?=$sst?>'>
<input type=hidden name=sod   value='<?=$sod?>'>
<input type=hidden name=sfl   value='<?=$sfl?>'>
<input type=hidden name=stx   value='<?=$stx?>'>
<input type=hidden name=page  value='<?=$page?>'>
<input type=hidden name=token value='<?=$token?>'>
<table width=100% cellpadding=0 cellspacing=1 border=0>
<colgroup width=30>
<!--<colgroup width=40>-->
<colgroup width=120>
<colgroup width=''>
<colgroup width=80>
<colgroup width=80>
<colgroup width=40>
<colgroup width=60>
<tr><td colspan='<?=$colspan?>' class='line1'></td></tr>
<tr class='bgcol1 bold col1 ht center'>
    <td><input type=checkbox name=chkall value="1" onclick="check_all(this.form)"></td>
    <!--<td>번호</td>-->
    <td>작성자</td>
    <td>제목</td>
    <td><?=subject_sort_link('pp_start', '', 'desc')?>시작일</a></td>
    <td><?=subject_sort_link('pp_end', '', 'desc')?>종료일</a></td>
    <td><?=subject_sort_link('pp_use', '', 'desc')?>사용</a></td>
    <td><? echo "<a href='$mw[admin_path]/mw.popup.form.php'><img src='$g4[admin_path]/img/icon_insert.gif' border=0 title='생성'></a>"; ?></td>
</tr>
<tr><td colspan='<?=$colspan?>' class='line2'></td></tr>
<?
for ($i=0; $row=sql_fetch_array($result); $i++) 
{
    $mb = get_member($row[mb_id]);
    $mb_name = get_sideview($mb[mb_id], $mb[mb_nick], $mb[mb_email], $mb[mb_homepage]);

    $pp_subject = cut_str($row[pp_subject], 70);
    $pp_use = $row[pp_use] ? '&radic;' : '&nbsp;';

    $s_upd = "<a href='$mw[admin_path]/mw.popup.form.php?$qstr&w=u&pp_id=$row[pp_id]'><img src='$g4[admin_path]/img/icon_modify.gif' border=0 title='수정'></a>";
    $s_del = "<a href=\"javascript:post_delete('mw.popup.delete.php', '$row[pp_id]');\"><img src='$g4[admin_path]/img/icon_delete.gif' border=0 title='삭제'></a>";

    $num = $total_count - ($page - 1) * $rows - $i;

    $list = $i%2;
    echo "<input type=hidden name=pp_id[$i] value='$row[pp_id]'>";
    echo "<tr class='list$list' onmouseover=\"this.className='mouseover';\" onmouseout=\"this.className='list$list';\" height=27 align=center>";
    echo "<td><input type=checkbox name=chk[] value='$i'></td>";
    //echo "<td>$num</td>";
    echo "<td>$mb_name</td>";
    echo "<td align=left>&nbsp;$pp_subject</td>";
    echo "<td>$row[pp_start]</td>";
    echo "<td>$row[pp_end]</td>";
    echo "<td>$pp_use</td>";
    echo "<td>$s_upd $s_del </td>";
    echo "</tr>\n";
} 

if ($i == 0)
    echo "<tr><td colspan='$colspan' align=center height=100 bgcolor=#ffffff>자료가 없습니다.</td></tr>"; 

echo "<tr><td colspan='$colspan' class='line2'></td></tr>";
echo "</table>";

$pagelist = get_paging($config[cf_write_pages], $page, $total_page, "$_SERVER[PHP_SELF]?$qstr&page=");
echo "<table width=100% cellpadding=3 cellspacing=1 border=0>";
echo "<tr><td width=25%>";
//echo "<input type=button class='btn1' value='선택수정' onclick=\"btn_check(this.form, 'update')\">";
echo " <input type=button class='btn1' value='선택삭제' onclick=\"btn_check(this.form, 'delete')\">";
echo "</td>";
echo "<td width=50% align=center>$pagelist</td>\n";
echo "<td width=25% align=right>&nbsp;</td></tr></table>\n";

if ($stx)
    echo "<script>document.fsearch.sfl.value = '$sfl';</script>";
?>
</form>

<script type="text/javascript">
// POST 방식으로 삭제
function post_delete(action_url, val)
{
    var f = document.fpost;

    if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
	f.pp_id.value = val;
	f.action      = action_url;
	f.submit();
    }
}
</script>

<form name='fpost' method='post'>
<input type='hidden' name='sst'   value='<?=$sst?>'>
<input type='hidden' name='sod'   value='<?=$sod?>'>
<input type='hidden' name='sfl'   value='<?=$sfl?>'>
<input type='hidden' name='stx'   value='<?=$stx?>'>
<input type='hidden' name='page'  value='<?=$page?>'>
<input type='hidden' name='token' value='<?=$token?>'>
<input type='hidden' name='pp_id'>
</form>

<?
include_once("$g4[admin_path]/admin.tail.php");
?>
