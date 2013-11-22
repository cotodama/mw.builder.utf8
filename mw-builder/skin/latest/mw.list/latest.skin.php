<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$board_skin_path = "{$g4['path']}/skin/board/{$board['bo_skin']}";
$skin_lib_path = "$board_skin_path/mw.lib/mw.function.lib.php";
if (file_exists($skin_lib_path) && !defined("_MW_BOARD_")) include_once($skin_lib_path);

$style_name = "mw-latest-list-$bo_table-$rows-$subject_len";
?>
<style>
.<?php echo $style_name?> { border:1px solid #e1e1e1; text-align:left; }
.<?php echo $style_name?> .subject { background:url(<?php echo $latest_skin_path?>/img/box-bg.gif); height:24px; margin:0 0 7px 0; }
.<?php echo $style_name?> .subject .bo_table { margin:5px 0 0 5px; float:left; }
.<?php echo $style_name?> .subject .bo_table a { font-size:12px; color:#555; font-weight:bold; letter-spacing:-1px; text-decoration:none; }
.<?php echo $style_name?> .subject .list { margin:5px 5px 0 0; float:right; }
.<?php echo $style_name?> .subject .list a { font-weight:normal; font-size:11px; letter-spacing:-1px; color:#555; }
.<?php echo $style_name?> ul { margin:5px 0 7px 10px; padding:0; list-style:none; }
.<?php echo $style_name?> ul li { margin:0; padding:0 0 0 7px; background:url(<?php echo $latest_skin_path?>/img/dot.gif) no-repeat 0 5px; height:20px; }
.<?php echo $style_name?> ul li a:hover { color:#438A01; text-decoration:underline; }
.<?php echo $style_name?> .file-img { width:100px; height:65px; border:1px solid #e2e2e2; }
.<?php echo $style_name?> .file-subject { line-height:15px; font-size:11px; letter-spacing:-1px; width:100px; height:28px; margin:3px 0 0 0; overflow:hidden; }
.<?php echo $style_name?> .file a:hover { color:#438A01; text-decoration:underline; }
.<?php echo $style_name?> .line { font-size:1px; line-height:1px; height:1px; border-bottom:1px dotted #e1e1e1; margin-bottom:10px; }
</style>

<div class="<?php echo $style_name?>">
<div style="border:1px solid #fff">
<div class="subject">
    <div class="bo_table"><a href="<?php echo $g4['bbs_path']?>/board.php?bo_table=<?php
        echo $bo_table?>"><?php echo $board['bo_subject']?></a></div>
    <div class="list"><a href="<?php echo $g4['bbs_path']?>/board.php?bo_table=<?php
        echo $bo_table?>"><img src="<?php echo $latest_skin_path?>/img/l.gif" aling="absmiddle"> 목록</a></div>
</div>

<?php
for ($a=0; $a<$rows/5; $a++) {
    if ($a > 0) echo "<div class='line'></div>";
    if (function_exists('bc_code')) { $list[$i]['subject'] = bc_code($list[$i]['wr_subject']); }
    ?>
    <table border=0 cellpadding=0 cellspacing=0>
    <tr>
    <?php if ($is_img && $file[$a]) { ?>
    <td width=120 align=center class=file>
        <a href="<?php echo $file[$a]['href']?>"><!--
            --><div><img src="<?php echo $file[$a]['path']?>" class="file-img"></div><!--
            --><div class="file-subject"><?php echo $file[$a]['subject']?></div></a>
    </td>
    <?php }//if  ?>
    <td valign=top>
        <ul>
        <?php
        $s = $a*5;
        $e = ($a+1)*5;
        $r = rand($s, $e-1);
        for ($i=$s; $i<$e; $i++) {
            if ($r == $i) $list[$i]['subject'] = "<strong>".$list[$i]['subject']."</strong>";
            if ($list[$i]['icon_secret']) $list[$i]['subject'] .= "&nbsp;&nbsp;" . $list[$i]['icon_secret'];
            //if ($list[$i]['icon_file']) $list[$i]['subject'] .= "&nbsp;" . $list[$i]['icon_file'];
            //if ($list[$i]['icon_new']) $list[$i]['subject'] .= "&nbsp;" . $list[$i]['icon_new'];
            //if ($list[$i]['icon_hot']) $list[$i]['subject'] .= "&nbsp;" . $list[$i]['icon_hot'];
            $list[$i]['subject'] = mw_builder_reg_str($list[$i]['subject']);
            $list[$i]['href'] = "{$g4['bbs_path']}/board.php?bo_table=$bo_table&wr_id=".$list[$i]['wr_id'];
            ?>
            <li><a href="<?php echo $list[$i]['href']?>"><?php echo $list[$i]['subject']?></a>&nbsp;</li>
        <?php }//for ?>
        </ul>
    </td>
    </tr>
    </table>
<?php }//for ?>
</div>
</div>

