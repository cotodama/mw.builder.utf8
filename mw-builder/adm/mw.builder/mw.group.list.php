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

$sub_menu = "110300";
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

$row = sql_fetch("select max(gr_order) as m, count(gr_id) as c from $g4[group_table]");
$max = $row[m];
$cnt = $row[c];

// 신규 그룹 order 정하기
$sql = "select * from $g4[group_table] where gr_order = 0";
$qry = sql_query($sql);
while ($row = sql_fetch_array($qry))
    sql_query("update $g4[group_table] set gr_order = '". ++$max ."' where gr_id = '$row[gr_id]'");

// 그룹삭제시 order 재정렬이 안되기 때문에 list 에서 update 해준다.
if ($max != $cnt) {
    $ord = 1;
    $qry = sql_query("select gr_id from $g4[group_table] order by gr_order");
    while ($row = sql_fetch_array($qry)) {
       sql_query("update $g4[group_table] set gr_order = '".($ord++)."' where gr_id = '$row[gr_id]'"); 
    }
}

$token = get_token();

$sql_common = " from $g4[group_table] ";

$sql_search = " where (1) ";
if ($is_admin != "super")
    $sql_search .= " and (gr_admin = '$member[mb_id]') ";

if ($stx) {
    $sql_search .= " and ($sfl like '%$stx%') ";
}

if ($sst)
    $sql_order = " order by $sst $sod ";
else
    $sql_order = " order by gr_order asc, gr_id asc ";

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

    $skin_options .= "<option value='$arr[$k]'>$option</option>";
}

$listall = "<a href='mw.group.php'>처음</a>";

$colspan = 12;
?>

<form name=fsearch method=get>
<table width=100% cellpadding=3 cellspacing=1>
<tr>
    <td width=50% align=left><?=$listall?> (그룹수 : <?=number_format($total_count)?>개)</td>
    <td width=50% align=right>
        <select name=sfl>
            <option value="gr_subject">제목</option>
            <option value="gr_id">ID</option>
            <!--<option value="gr_admin">그룹관리자</option>-->
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
<table width=100% cellpadding=0 cellspacing=1 border=0>
<colgroup width=30>
<colgroup width=''>
<colgroup width=100>
<colgroup width=60>
<colgroup width=90>
<!--<colgroup width=80>-->
<colgroup width=90>
<colgroup width=90>
<!--<colgroup width=30>-->
<!--<colgroup width=30>-->
<colgroup width=40>
<colgroup width=80>
<tr><td colspan='<?=$colspan?>' class='line1'></td></tr>
<tr class='bgcol1 bold col1 ht center'>
    <td><input type=checkbox name=chkall value="1" onclick="check_all(this.form)"></td>
    <td>그룹ID</td>
    <td>제목</td>
    <td>도메인 <?=help("<span style='font-weight:normal'>서브도메인을 설정합니다. 네임서버 및 웹서버에 서브도메인이 설정되어 있어야 정상 작동 합니다.</span>")?></td>
    <td>HEAD 스킨</td>
    <!--<td>MAIN 스킨</td>-->
    <td>HOME 스킨</td>
    <td>TAIL 스킨</td>
    <!--<td>출력<br/>안함</td>-->
    <!--<td>관리<br/>자만</td>-->
    <td>중메뉴</td>
    <td><? echo "<a href='$g4[admin_path]/boardgroup_form.php'><img src='$g4[admin_path]/img/icon_insert.gif' border=0 title='생성'></a>"; ?></td>
</tr>
<tr><td colspan='<?=$colspan?>' class='line2'></td></tr>
<?
for ($i=0; $row=sql_fetch_array($result); $i++) 
{
    // 중간메뉴수
    $row1 = sql_fetch("select count(mm_id) as cnt from $mw[menu_middle_table] where gr_id = '$row[gr_id]'", false);

    $s_upd = "<a href='$mw[admin_path]/mw.group.form.php?$qstr&w=u&gr_id=$row[gr_id]'><img src='$g4[admin_path]/img/icon_modify.gif' border=0 title='수정'></a>";
    $s_mup = "<a href='javascript:mw_move(\"up\", \"$row[gr_id]\")'><img src='$mw[admin_path]/img/icon_up.gif' border=0 title='위로'></a>";
    $s_mdw = "<a href='javascript:mw_move(\"down\", \"$row[gr_id]\")'><img src='$mw[admin_path]/img/icon_down.gif' border=0 title='아래로'></a>";
    $s_del = "";
    if ($is_admin == "super") {
        //$s_del = "<a href=\"javascript:post_delete('boardgroup_delete.php', '$row[gr_id]');\"><img src='$g4[admin_path]/img/icon_delete.gif' border=0 title='삭제'></a>";
    }

    $list = $i%2;
    echo "<input type=hidden name=gr_id[$i] value='$row[gr_id]'>";
    echo "<tr class='list$list' onmouseover=\"this.className='mouseover';\" onmouseout=\"this.className='list$list';\" height=27 align=center>";
    echo "<td><input type=checkbox name=chk[] value='$i'></td>";
    echo "<td><a href='$g4[bbs_path]/group.php?gr_id=$row[gr_id]'><b>$row[gr_id]</b></a></td>";
    echo "<td><input type=text name=gr_subject[$i] size=12 value='$row[gr_subject]' class=ed></td>";
    echo "<td><input type=text name=gr_sub_domain[$i] size=8 value='$row[gr_sub_domain]' class=ed></td>";
    echo "<td><select id=gr_skin_head_$i name=gr_skin_head[$i] style='width:85px;'><option value=''></option>$skin_options</select></td>";
    //echo "<td><select id=gr_skin_main_$i name=gr_skin_main[$i] style='width:75px;'><option value=''></option>$skin_options</select></td>";
    echo "<td><select id=gr_skin_home_$i name=gr_skin_home[$i] style='width:85px;'><option value=''></option>$skin_options</select></td>";
    echo "<td><select id=gr_skin_tail_$i name=gr_skin_tail[$i] style='width:85px;'><option value=''></option>$skin_options</select></td>";
    //echo "<td>".($row[gr_use_not]?'&radic;':'&nbsp;')."</td>";
    //echo "<td>".($row[gr_only_admin]?'&radic;':'&nbsp;')."</td>";
    echo "<td><a href='$mw[admin_path]/mw.m.menu.php?gr_id=$row[gr_id]'>$row1[cnt]</a></td>";
    echo "<td>$s_upd $s_mup $s_mdw </td>";
    echo "</tr>\n";
    echo "<script type='text/javascript'>";
    echo "document.getElementById('gr_skin_head_$i').value='$row[gr_skin_head]';";
    //echo "document.getElementById('gr_skin_main_$i').value='$row[gr_skin_main]';";
    echo "document.getElementById('gr_skin_home_$i').value='$row[gr_skin_home]';";
    echo "document.getElementById('gr_skin_tail_$i').value='$row[gr_skin_tail]';";
    echo "</script>";
} 

if ($i == 0)
    echo "<tr><td colspan='$colspan' align=center height=100 bgcolor=#ffffff>자료가 없습니다.</td></tr>"; 

echo "<tr><td colspan='$colspan' class='line2'></td></tr>";
echo "</table>";

//$pagelist = get_paging($config[cf_write_pages], $page, $total_page, "$_SERVER[PHP_SELF]?$qstr&page=");
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

