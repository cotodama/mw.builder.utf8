<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

//$style_name = "mw-latest-list-img-$bo_table-$rows-$subject_len";
$style_name = "mw-board-group-list-img";
?>

<!--
<style type="text/css">
.<?=$style_name?> { text-align:center; padding:15px 0 0 0;  }
.<?=$style_name?> .subject { background:url(<?=$latest_skin_path?>/img/box-bg.gif); height:24px; margin:0 0 7px 0; }
.<?=$style_name?> .subject .bo_table { margin:5px 0 0 5px; float:left; }
.<?=$style_name?> .subject .bo_table a { font-size:12px; color:#555; font-weight:bold; letter-spacing:-1px; text-decoration:none; }
.<?=$style_name?> .subject .list { margin:5px 5px 0 0; float:right; }
.<?=$style_name?> .subject .list a { font-weight:normal; font-size:11px; letter-spacing:-1px; color:#555; }
.<?=$style_name?> .file { width:100px; height:50px; padding-bottom:5px; }
.<?=$style_name?> .file-img { width:80px; height:50px; border:1px solid #e2e2e2; }
.<?=$style_name?> .post-subject { width:80px; height:30px; line-height:15px; overflow:hidden; font-family:dotum; margin:5px 5px 0 0; letter-spacing:-1px; font-size:10px; }
.<?=$style_name?> .post-subject a:hover { color:#438A01; text-decoration:underline; }
</style>
-->

<div class="<?=$style_name?>">
<!--
<div style="border:1px solid #fff">
<div class="subject">
<div class="bo_table"><a href="<?=$g4[bbs_path]?>/board.php?bo_table=<?=$bo_table?>"><?=$board[bo_subject]?></a></div>
<div class="list"><a href="<?=$g4[bbs_path]?>/board.php?bo_table=<?=$bo_table?>"><img src="<?=$latest_skin_path?>/img/l.gif" aling="absmiddle"> 목록</a></div>
</div>
-->

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
<!--</div>-->
</div>

