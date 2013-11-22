<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

global $mw_side_img_cnt;

if (!$mw_side_img_cnt)
    $mw_side_img_cnt = 0;

$mw_side_img_cnt++;

$rows = count($list);
$style_name = "mw-side-img-$bo_table-$rows-$subject_len-$mw_side_img_cnt";
?>
<style>
#<?php echo $style_name?> { clear:both; width:240px; margin:0px 0 0 0; border:0px solid #e1e1e1; }
#<?php echo $style_name?> .item { clear:both; display:none; }
#<?php echo $style_name?> .file-img { width:240px; height:180px; border:1px solid #e1e1e1; border-left:0; }
#<?php echo $style_name?> .subject { clear:both; height:20px; padding:5px 5px 0 0; text-align:center; }
#<?php echo $style_name?> .subject a:hover { color:#438A01; text-decoration:underline; }
#<?php echo $style_name?> .prev { float:left; margin:0 0 0 5px; cursor:pointer; }
#<?php echo $style_name?> .next	{ float:left; margin:0 5px 0 0; cursor:pointer; }
#<?php echo $style_name?> .link	{ float:left; margin:0 0 0 5px; text-align:center; }
</style>

<div id="<?php echo $style_name?>">
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
    <div id="<?php echo $style_name.$i?>" class="item">
        <div class="file"><!--
            --><a href="<?php echo $list[$i]['href']?>"><img src="<?php echo $img?>" class="file-img"></a></div>
        <table width="100%" height="25" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="20"><img src="<?php echo $latest_skin_path?>/img/btn-prev.gif"
                onclick="latest_side_img_prev<?php echo $mw_side_img_cnt?>()" class="prev" align="absmiddle"></td>
            <td align="center"><a href="<?php echo $list[$i]['href']?>"><?php echo $list[$i]['subject']?></a></td>
            <td width="20"><img src="<?php echo $latest_skin_path?>/img/btn-next.gif"
                onclick="latest_side_img_next<?php echo $mw_side_img_cnt?>()" class="next" align="absmiddle"></td>
        </tr>
        </table>
    </div>
    <?php } ?>
</div>

<script>
side_img_val<?php echo $mw_side_img_cnt?> = 0;
side_img_delay<?php echo $mw_side_img_cnt?> = 3000;
side_img_time<?php echo $mw_side_img_cnt?> = null;
function latest_side_img_prev<?php echo $mw_side_img_cnt?>() {
    latest_side_img_next<?php echo $mw_side_img_cnt?>(-1);
}
function latest_side_img_next<?php echo $mw_side_img_cnt?>(val) { 
    if (val < 0) 
	side_img_val<?php echo $mw_side_img_cnt?>--;
    else
	side_img_val<?php echo $mw_side_img_cnt?>++;
    if (side_img_val<?php echo $mw_side_img_cnt?> >= <?php echo $rows?>) side_img_val<?php echo $mw_side_img_cnt?> = 0;
    if (side_img_val<?php echo $mw_side_img_cnt?> < 0) side_img_val<?php echo $mw_side_img_cnt?> = <?php echo $rows-1?>;

    for (i=0; i<<?php echo $rows?>; i++) {
	document.getElementById("<?php echo $style_name?>" + i).style.display = "none";
    }
    img = document.getElementById("<?php echo $style_name?>" + side_img_val<?php echo $mw_side_img_cnt?>);
    img.style.display = "block";
}
function latest_side_img_auto<?php echo $mw_side_img_cnt?>() {
    side_img_time<?php echo $mw_side_img_cnt?> = setInterval("latest_side_img_next<?php echo $mw_side_img_cnt?>()", side_img_delay<?php echo $mw_side_img_cnt?>);
}
function latest_side_img_over<?php echo $mw_side_img_cnt?>() {
    clearInterval(side_img_time<?php echo $mw_side_img_cnt?>);
    side_img_time<?php echo $mw_side_img_cnt?> = null;
}
function latest_side_img_out<?php echo $mw_side_img_cnt?>() {
    latest_side_img_auto<?php echo $mw_side_img_cnt?>();
}
document.getElementById("<?php echo $style_name?>0").style.display = "block";
latest_side_img_auto<?php echo $mw_side_img_cnt?>();
document.getElementById("<?php echo $style_name?>").onmouseover = latest_side_img_over<?php echo $mw_side_img_cnt?>;
document.getElementById("<?php echo $style_name?>").onmouseout = latest_side_img_out<?php echo $mw_side_img_cnt?>;
</script>

