<?
include_once("_common.php");
include_once("$g4[path]/lib/mw.builder.lib.php");

$idx = 0;
$rowspan = array();
$sql = "select * from $g4[group_table] where gr_use_not = '' and gr_only_admin = '' order by gr_order, gr_id";
$qry = sql_query($sql);
while ($row = sql_fetch_array($qry)) {
    $rowspan[$row[gr_id]] = 0;
    $sql2 = "select * from $mw[menu_middle_table] where gr_id = '$row[gr_id]' order by mm_order, mm_id";
    $qry2 = sql_query($sql2);
    while ($row2 = sql_fetch_array($qry2)) {
	$sql3 = "select * from $mw[menu_small_table] where mm_id = '$row2[mm_id]' and bo_table <> '' order by ms_order, ms_id";
	$qry3 = sql_query($sql3);
	while ($row3 = sql_fetch_array($qry3)) {
	    $sql4 = "select * from $g4[board_table] where bo_table = '$row3[bo_table]' ";
	    $row4 = sql_fetch($sql4);
	    if ($row4[bo_read_level] >= 10 && $row4[bo_write_level] >= 10 &&$row4[bo_comment_level] >= 10 && $row4[bo_download_level] >= 10) continue;
	    $list[$idx] = $row4; 
	    $list[$idx][href] = "$g4[bbs_path]/board.php?bo_table=$row4[bo_table]";
	    $list[$idx][gr_id] = $row[gr_id];
	    $list[$idx][gr_subject] = $row[gr_subject];
	    $list[$idx][ms_name] = $row3[ms_name];
	    if ($row4[bo_read_level] == 1) $list[$idx][bo_read_point] = 0;
	    if ($row4[bo_download_level] == 1) $list[$idx][bo_download_point] = 0;
	    if ($row4[bo_write_level] == 1) $list[$idx][bo_write_point] = 0;
	    if ($row4[bo_comment_level] == 1) $list[$idx][bo_comment_point] = 0;
	    $idx++;
	    $rowspan[$row[gr_id]] += 1;
	}
    }
}
$total_count = sizeof($list);

$g4[title] = "포인트 정책";
include_once("_head.php");
?>
<style type="text/css">
.info { height:25px; margin:0 0 0 10px; font-size:13px; }
.point-policy { background-color:#ddd; }
.point-policy td { background-color:#fff; }
.point-policy .head { height:30px; text-align:center; font-weight:bold; background-color:#fafafa; }
.point-policy .body { height:25px; text-align:center; }
.point-policy .body.right { text-align:right; padding-right:10px; }
.point-policy .body.left { text-align:left; padding-left:10px; }
.point-policy .body a:hover { text-decoration:underline; }
</style>

<?
if ($config[cf_register_point]) echo "<div class='info'>· 회원가입 포인트 : <strong>".number_format($config[cf_register_point])."</strong> 점</div>";
if ($config[cf_login_point]) echo "<div class='info'>· 로그인 포인트 : <strong>".number_format($config[cf_login_point])."</strong> 점</div>";
?>

<table border=0 cellpadding=0 cellspacing=1 width=100% class="point-policy">
<colgroup width="100"/>
<colgroup width=""/>
<colgroup width="80"/>
<colgroup width="80"/>
<colgroup width="80"/>
<colgroup width="80"/>
<tr>
    <td class="head"> 서비스 </td>
    <td class="head"> 메뉴 </td>
    <td class="head"> 글읽기 </td>
    <td class="head"> 글쓰기 </td>
    <td class="head"> 코멘트 쓰기 </td>
    <td class="head"> 다운로드 </td>
</tr>
<? for ($i=0; $i<$total_count; ++$i) { ?> 
<tr>
    <? if ($list[$i-1][gr_subject] != $list[$i][gr_subject]) { ?>
	<td class="body" rowspan="<?=$rowspan[$list[$i][gr_id]]?>"> <?=$list[$i][gr_subject]?> </td>
    <? } ?>
    <td class="body left"> <a href="<?=$list[$i][href]?>"><?=$list[$i][ms_name]?></a> </td>
    <td class="body right"> <?=$list[$i][bo_read_point]?> 점 </td>
    <td class="body right"> <?=$list[$i][bo_write_point]?> 점 </td>
    <td class="body right"> <?=$list[$i][bo_comment_point]?> 점 </td>
    <td class="body right"> <?=number_format($list[$i][bo_download_point])?> 점 </td>
</tr>
<? } ?>
</table>

<?
include_once("_tail.php");
?>
