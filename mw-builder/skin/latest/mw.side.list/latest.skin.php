<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$board_skin_path = "{$g4['path']}/skin/board/{$board['bo_skin']}";
$skin_lib_path = "$board_skin_path/mw.lib/mw.function.lib.php";
if (file_exists($skin_lib_path) && !defined("_MW_BOARD_")) include_once($skin_lib_path);

$style_name = "mw-latest-side-$bo_table-$rows-$subject_len";
?>
<style type="text/css">
.<?php echo $style_name?> { text-align:left; border:0px solid #e1e1e1; }
.<?php echo $style_name?> .subject { margin:0; }
.<?php echo $style_name?> .subject div { margin:5px 0 0 10px;}
.<?php echo $style_name?> .subject a { font-size:12px; color:#555; color:#145daa; font-weight:bold; letter-spacing:-1px; text-decoration:none; }
.<?php echo $style_name?> ul { margin:0 5px 5px 5px; padding:7px 0 5px 0; list-style:none; border:1px solid #e1e1e1; background-color:#fff; }
.<?php echo $style_name?> ul li { margin:0 0 0 7px; padding:0 0 0 7px; background:url(<?php echo $latest_skin_path?>/img/dot.gif) no-repeat 0 7px; height:20px; }
.<?php echo $style_name?> ul li a:hover { color:#438A01; text-decoration:underline; }
.<?php echo $style_name?> .comment { font-size:11px; color:#FF6600; font-family:dotum; }
</style>

<div class="<?php echo $style_name?>">
    <div class="subject">
        <div><a href="<?php echo $g4['bbs_path']?>/board.php?bo_table=<?php echo $bo_table?>"><!--
            --><?php echo $board['bo_subject']?></a></div>
    </div>
    <ul>
    <?php
    for ($i=0; $i<$rows; $i++) {
        $tmp_table = $bo_table;
        if (!$bo_table) $tmp_table = $list[$i]['bo_table'];
        if (function_exists('bc_code')) { $list[$i]['subject'] = bc_code($list[$i]['wr_subject']); }
        $list[$i]['subject'] = mw_builder_reg_str($list[$i]['subject']);
        $list[$i]['comment_cnt'] = $list[$i]['wr_comment'] ? "+{$list[$i]['wr_comment']}" : ''; ?>
        <li><a href="<?php echo $g4['bbs_path']?>/board.php?bo_table=<?php
            echo $bo_table?>&wr_id=<?php echo $list[$i]['wr_id']?>"><?php
            echo cut_str($list[$i]['subject'], $subject_len)?>&nbsp;<!--
            --><span class="comment"><?php echo $list[$i]['comment_cnt']?></span></a></li>
    <?php } ?>
    </ul>
</div>

