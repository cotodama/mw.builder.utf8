<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$style_name = "mw-latest-list-img-$bo_table-$rows-$subject_len";
?>
<style>
.<?php echo $style_name?> { border:1px solid #e1e1e1; }
.<?php echo $style_name?> .subject { background:url(<?php echo $latest_skin_path?>/img/box-bg.gif); height:24px; margin:0 0 7px 0; }
.<?php echo $style_name?> .subject .bo_table { margin:5px 0 0 5px; float:left; }
.<?php echo $style_name?> .subject .bo_table a { font-size:12px; color:#555; font-weight:bold; letter-spacing:-1px; text-decoration:none; }
.<?php echo $style_name?> .subject .list { margin:5px 5px 0 0; float:right; }
.<?php echo $style_name?> .subject .list a { font-weight:normal; font-size:11px; letter-spacing:-1px; color:#555; }
.<?php echo $style_name?> .file { width:140px; height:80px; padding-bottom:5px; }
.<?php echo $style_name?> .file-img { width:120px; height:80px; border:2px solid #e2d2e2; }
.<?php echo $style_name?> .post-img a:hover img { border:2px solid #ff4e00; }
.<?php echo $style_name?> .post-subject { width:115px; height:35px; overflow:hidden; padding:5px 5px 0 0; letter-spacing:-1px; font-size:11px; }
.<?php echo $style_name?> .post-subject a:hover { color:#438A01; text-decoration:underline; }
</style>

<div class="<?php echo $style_name?>">
<div style="border:1px solid #fff">
<div class="subject">
    <div class="bo_table"><a href="<?php echo $g4['bbs_path']?>/board.php?bo_table=<?php
        echo $bo_table?>"><?php echo $board['bo_subject']?></a></div>
    <div class="list"><a href="<?php echo $g4['bbs_path']?>/board.php?bo_table=<?php
        echo $bo_table?>"><img src="<?php echo $latest_skin_path?>/img/l.gif" aling="absmiddle"> 목록</a></div>
</div>
<table border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
<?php
for ($i=0; $i<$rows; $i++) { 
    $tmp_table = $bo_table;
    if (!$bo_table)
        $tmp_table = $list[$i]['bo_table'];

    $img = mw_get_thumb_path($tmp_table, $list[$i]['wr_id'], $list[$i]['file'][0]);
    if (!$img)
        $img = "{$latest_skin_path}/img/noimage.gif";

    $list[$i]['subject'] = mw_builder_reg_str($list[$i]['subject']);

    $list[$i]['href'] = "{$g4['bbs_path']}/board.php?bo_table={$tmp_table}&wr_id={$list[$i]['wr_id']}";
    ?>
    <td align=center valign=top class=file>
        <div class="post-img"><a href="<?php echo $list[$i]['href']?>"><img src="<?php echo $img?>" class="file-img"></a></div>
        <div class="post-subject"><a href="<?php echo $list[$i]['href']?>"><?php echo $list[$i]['subject']?></a></div>
    </td>
<?php }//for ?>
</tr>
</table>
</div>
</div>

