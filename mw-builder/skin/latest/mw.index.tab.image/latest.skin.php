<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$style_name = "mw-latest-list-img-$bo_table-$rows-$subject_len";
?>
<style type="text/css">
.<?php echo $style_name?> { border:0px solid #000; padding:0; margin:0; }
.<?php echo $style_name?> .item { display:inline; float:left; width:50px; height:90px; border:0px solid #000; margin:5px 0 0 7px; }
.<?php echo $style_name?> .file-img { width:49px; height:49px; border:1px solid #e2e2e2; }
.<?php echo $style_name?> .post-subject { width:50px; height:40px; overflow:hidden; }
.<?php echo $style_name?> .post-subject a { font-family:dotum; letter-spacing:-1px; font-size:10px; }
.<?php echo $style_name?> .post-subject a:hover { color:#438A01; text-decoration:underline; }
</style>

<div class="<?php echo $style_name?>">
<div style="clear:both"></div>
    <?php for ($i=0; $i<$rows; $i++) { 
    $tmp_table = $bo_table;
    if (!$bo_table)
        $tmp_table = $list[$i]['bo_table'];

    $img = mw_get_thumb_path($tmp_table, $list[$i]['wr_id'], $list[$i]['file'][0]);
    if (!$img)
        $img = "{$latest_skin_path}/img/noimage.gif";

    $list[$i]['subject'] = mw_builder_reg_str($list[$i]['subject']);
    ?>
    <div class="item">
        <div class="post-img"><a href="<?php echo $list[$i]['href']?>"><img src="<?php echo $img?>" class="file-img"></a></div>
        <div class="post-subject"><a href="<?php echo $list[$i]['href']?>"><?php echo $list[$i]['subject']?></a></div>
    </div>
    <?php } ?>
    <div style="clear:both"></div>
</div>

