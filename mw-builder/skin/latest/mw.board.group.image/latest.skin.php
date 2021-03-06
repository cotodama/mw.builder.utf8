<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$style_name = "mw-board-group-list-img";
?>
<div class="<?php echo $style_name?>">
<table border="0" cellpadding="0" cellspacing="0" style="margin:0 auto 0 auto;">
<tr>
    <?php for ($i=0; $i<$rows; $i++) { 
        $tmp_table = $bo_table;
        if (!$bo_table)
            $tmp_table = $list[$i]['bo_table'];

        $img = mw_get_thumb_path($tmp_table, $list[$i]['wr_id'], $list[$i]['file'][0]);
        if (!$img)
            $img = "{$latest_skin_path}/img/noimage.gif";

        $list[$i]['subject'] = mw_builder_reg_str($list[$i]['subject']);
        $list[$i]['title'] = mw_title_tag(mw_builder_reg_str($list[$i]['wr_subject']));
        //$list[$i]['href'] = "{$g4['bbs_path']}/board.php?bo_table={$tmp_table}&wr_id={$list[$i]['wr_id']}";
        $list[$i]['href'] = mw_builder_seo_url($tmp_table, $list[$i]['wr_id'], $qstr);
    ?>
    <td align="center" valign="top" class="file">
        <div class="post-img"><a href="<?php echo $list[$i]['href']?>" title="<?php
            echo $list[$i]['title']?>"><img src="<?php echo $img?>" class="file-img"></a></div>
        <div class="post-subject"><a href="<?php echo $list[$i]['href']?>" title="<?php
            echo $list[$i]['title']?>"><?php echo $list[$i]['subject']?></a>
        <span class='comment'><?php echo $list[$i]['wr_comment']?'+'.$list[$i]['wr_comment']:''?></div>
    </td>
    <?php if (($i+1)%4==0) echo "</tr><tr>"; } ?>
</tr>
</table>
</div>
