<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$style_name = "mw-board-group-list";
?>

<div class="<?php echo $style_name?>">
<table border="0" cellpadding="0" cellspacing="0">
<tr>
<?php if ($is_img && $file[0]) { ?>
<td width="120" align="center" class="file">
    <a href="<?php echo $file[0]['href']?>"><!--
    --><div><img src="<?php echo $file[0]['path']?>" class="file-img"></div><!--
    --><div class="file-subject"><?php echo mw_builder_reg_str($file[0]['subject'])?><!--
    --><span class="comment"><?php echo $file[0]['wr_comment']?'+'.$file[0]['wr_comment']:''?></span></div></a>
</td>
<?php } ?>
<td valign="top">
    <ul>
    <?php
    $r = rand(0, $rows-1);
    for ($i=0; $i<$rows; $i++) {
        $tmp_table = $bo_table;
        if (!$bo_table)
            $tmp_table = $list[$i]['bo_table'];
        if ($r == $i) $list[$i]['subject'] = "<strong>".$list[$i]['subject']."</strong>";
        if ($list[$i]['icon_secret']) $list[$i]['subject'] .= "&nbsp;&nbsp;" . $list[$i]['icon_secret'];
        if ($list[$i]['icon_file']) $list[$i]['subject'] .= "&nbsp;" . $list[$i]['icon_file'];
        if ($list[$i]['icon_new']) $list[$i]['subject'] .= "&nbsp;" . $list[$i]['icon_new'];
        //if ($list[$i]['icon_hot']) $list[$i]['subject'] .= "&nbsp;" . $list[$i]['icon_hot'];
        $list[$i]['subject'] = mw_builder_reg_str($list[$i]['subject']);
        $list[$i]['href'] = $g4['bbs_path']."/board.php?bo_table={$tmp_table}&wr_id=".$list[$i]['wr_id'];
        ?>
        <li>
            <a href="<?php echo $list[$i]['href']?>"><?php echo $list[$i]['subject']?></a>
            <span class="comment"><?php echo $list[$i]['wr_comment']?'+'.$list[$i]['wr_comment']:''?></span>
        </li>
    <?php } ?>
    </ul>
</td>
</tr>
</table>
</div>

