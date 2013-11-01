<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

//$style_name = "mw-latest-list-img-$bo_table-$rows-$subject_len";
$style_name = "mw-board-group-list-img";
?>
<div class="<?=$style_name?>">
<table border=0 cellpadding=0 cellspacing=0 style="margin:0 auto 0 auto;">
<tr>
<? for ($i=0; $i<$rows; $i++) { ?>
<?
$img = "$g4[path]/data/file/$bo_table/thumb/{$list[$i][wr_id]}";
if (!@file_exists($img)) $img = "$g4[path]/data/file/$bo_table/thumbnail/{$list[$i][wr_id]}";
if (!@file_exists($img)) {
    $img = "{$list[$i][file][0][path]}/{$list[$i][file][0][file]}";
    $ext = strtolower(substr($img, strlen($img)-3, 3));
    if (!($ext == "gif" || $ext == "jpg" || $ext == "png")) $img = "";
}
if (!@file_exists($img)) $img = "$latest_skin_path/img/noimage.gif";
if (!$list[$i][wr_id]) $img = "$latest_skin_path/img/noimage.gif";
if (@is_dir($img)) $img = "$latest_skin_path/img/noimage.gif";

$list[$i][subject] = mw_builder_reg_str($list[$i][subject]);
$list[$i][href] = "$g4[bbs_path]/board.php?bo_table=$bo_table&wr_id=".$list[$i][wr_id];
?>
<td align="center" valign=top class=file>
    <div class="post-img"><a href="<?=$list[$i][href]?>"><img src="<?=$img?>" class="file-img"></a></div>
    <div class="post-subject"><a href="<?=$list[$i][href]?>"><?=$list[$i][subject]?></a>
    <span class='comment'><?=$list[$i][wr_comment]?'+'.$list[$i][wr_comment]:''?></div>
</td>
<? if (($i+1)%4==0) echo "</tr><tr>"; ?>
<? } ?>
</tr>
</table>
</div>

