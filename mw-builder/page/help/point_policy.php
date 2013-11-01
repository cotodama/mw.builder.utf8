<?php
include_once("_common.php");
include_once("$g4[path]/lib/mw.builder.lib.php");

$g4[title] = "포인트 정책";
include_once("_head.php");

$list = array();
$idx = 0;
$rowspan = array();
$sql = "select * from $g4[group_table] where gr_use_not = '' and gr_only_admin = '' order by gr_order, gr_id";
$qry = sql_query($sql);
while ($row = sql_fetch_array($qry)) {
    $rowspan[$row[gr_id]] = 0;
    $sql2 = "select * from $mw[menu_middle_table] where gr_id = '$row[gr_id]' and mm_only_admin = '' order by mm_order, mm_id";
    $qry2 = sql_query($sql2);
    while ($row2 = sql_fetch_array($qry2)) {
	$sql3 = "select * from $mw[menu_small_table] where mm_id = '$row2[mm_id]' and bo_table <> '' and ms_only_admin = '' order by ms_order, ms_id";
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
	    //if ($row4[bo_read_level] == 1) $list[$idx][bo_read_point] = 0;
	    //if ($row4[bo_download_level] == 1) $list[$idx][bo_download_point] = 0;
	    //if ($row4[bo_write_level] == 1) $list[$idx][bo_write_point] = 0;
	    //if ($row4[bo_comment_level] == 1) $list[$idx][bo_comment_point] = 0;
	    $idx++;
	    $rowspan[$row[gr_id]] += 1;
	}
    }
}
$total_count = sizeof($list);
?>
<style type="text/css">
.info-box { padding:10px; background-color:#efefef; margin-bottom:10px; border:1px solid #ddd; text-align:left; }
.info { height:20px; margin:0 0 0 10px; font-size:12px; }
.point-policy { background-color:#ddd; }
.point-policy td { background-color:#fff; }
.point-policy .head { height:30px; text-align:center; font-weight:bold; background-color:#fafafa; }
.point-policy .body { height:25px; text-align:center; }
.point-policy .body.right { text-align:right; padding-right:10px; }
.point-policy .body.left { text-align:left; padding-left:10px; }
.point-policy .body a:hover { text-decoration:underline; }
</style>

<div class="info-box">
<div class='info'>·사이트에서는 회원님들의 각종 혜택을 위해 포인트 제도를 운영하고 있습니다.</div> 
<div class='info'>·각 게시판 활동 포인트는 아래 표를 참고해주세요.</div> 
<div class='info'>·포인트 정책은 수시로 변경될 수 있으며 이를 별도로 통보하지 않습니다.</div> 
<div class='info'>·포인트 획득을 위해 도배, 의미없는 글을 작성하는 등의 행위는 통보없이 "포인트 몰수"  될 수 있습니다. </div> 
<div class='info'>·30일간 로그인하지 않은 회원의 포인트는 초기화 됩니다. </div> 
</div>
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
