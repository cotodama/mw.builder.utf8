<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

if (!function_exists("print_date_image")) {
function print_date_image() {
    global $g4, $latest_skin_path;

    $today = date("Y.m.d", $g4['server_time']);
    for ($i=0, $m=strlen($today); $i<$m; $i++) {
        $c = substr($today, $i, 1);
        if ($c == ".") $c = "p";
        echo "<span class='mw_print_date d$c'></span>";
    }

    $week = date("w", $g4['server_time']);
    echo "<span class='mw_print_week w$week'></span>";
}}

$board_skin_path = "{$g4['path']}/skin/board/{$board['bo_skin']}";
$skin_lib_path = "$board_skin_path/mw.lib/mw.function.lib.php";
if (file_exists($skin_lib_path) && !defined("_MW_BOARD_")) include_once($skin_lib_path);

$style_name = "mw-latest-side-$bo_table-$rows-$subject_len";
?>
<style>
.<?php echo $style_name?> { border:1px solid #e1e1e1; background-color:#f8f8f8; height:155px; width:100%; margin:0; padding:0; }
.<?php echo $style_name?> td { margin:0; padding:0; }
.<?php echo $style_name?> ul { margin:0 0 7px 7px; padding:0; list-style:none; font:normal 12px 'gulim'; letter-spacing:0; }
.<?php echo $style_name?> ul li { margin:0; padding:0 0 0 7px; background:url(<?php echo $latest_skin_path?>/img/dot.gif) no-repeat 0 7px; line-height:20px; }
.<?php echo $style_name?> ul li a:hover { color:#438A01; text-decoration:underline; }
.<?php echo $style_name?> ul li span.comment { color:#FF6600; font:normal 10px 'dotum'; letter-spacing:-1px; }

.mw_date_image { height:20px; margin:12px 0 10px 10px; padding:0; }

.mw_print_date { width:10px; height:13px; float:left; }
.mw_print_date.d0 { background:url(<?php echo $latest_skin_path?>/img/num.png) -1px 0 no-repeat; }
.mw_print_date.d1 { background:url(<?php echo $latest_skin_path?>/img/num.png) -10px 0 no-repeat; }
.mw_print_date.d2 { background:url(<?php echo $latest_skin_path?>/img/num.png) -20px 0 no-repeat; }
.mw_print_date.d3 { background:url(<?php echo $latest_skin_path?>/img/num.png) -30px 0 no-repeat; }
.mw_print_date.d4 { background:url(<?php echo $latest_skin_path?>/img/num.png) -40px 0 no-repeat; }
.mw_print_date.d5 { background:url(<?php echo $latest_skin_path?>/img/num.png) -50px 0 no-repeat; }
.mw_print_date.d6 { background:url(<?php echo $latest_skin_path?>/img/num.png) -60px 0 no-repeat; }
.mw_print_date.d7 { background:url(<?php echo $latest_skin_path?>/img/num.png) -70px 0 no-repeat; }
.mw_print_date.d8 { background:url(<?php echo $latest_skin_path?>/img/num.png) -80px 0 no-repeat; }
.mw_print_date.d9 { background:url(<?php echo $latest_skin_path?>/img/num.png) -90px 0 no-repeat; }
.mw_print_date.dp { background:url(<?php echo $latest_skin_path?>/img/num.png) -103px 0 no-repeat; width:5px; }

.mw_print_week { width:30px; height:15px; float:left; }
.mw_print_week.w1 { background:url(<?php echo $latest_skin_path?>/img/week.png) 0 0 no-repeat; }
.mw_print_week.w2 { background:url(<?php echo $latest_skin_path?>/img/week.png) -30px 0 no-repeat; }
.mw_print_week.w3 { background:url(<?php echo $latest_skin_path?>/img/week.png) -60px 0 no-repeat; }
.mw_print_week.w4 { background:url(<?php echo $latest_skin_path?>/img/week.png) -90px 0 no-repeat; }
.mw_print_week.w5 { background:url(<?php echo $latest_skin_path?>/img/week.png) -120px 0 no-repeat; }
.mw_print_week.w6 { background:url(<?php echo $latest_skin_path?>/img/week.png) -150px 0 no-repeat; }
.mw_print_week.w0 { background:url(<?php echo $latest_skin_path?>/img/week.png) -180px 0 no-repeat; }
</style>

<table class="<?php echo $style_name?>" border="0" cellpadding="0" cellspacing="0">
<tr>
    <td valign="top">
        <a href="<?php echo $g4['bbs_path']?>/board.php?bo_table=<?php
            echo $bo_table?>"><div class="mw_date_image"><?php echo print_date_image()?></div></a>
        <table border=0 cellpadding=0 cellspacing=0>
        <tr>
            <td valign=top>
                <ul>
                    <?php
                    for ($i=0; $i<$rows; $i++) { 
                        if (function_exists('bc_code')) { $list[$i]['subject'] = bc_code($list[$i]['wr_subject']); }
                        $list[$i]['subject'] = mw_builder_reg_str($list[$i]['subject']);
                        $list[$i]['href'] = "{$g4['bbs_path']}/board.php?bo_table=$bo_table&wr_id={$list[$i]['wr_id']}"; ?>
                    <li><a href="<?php echo $list[$i]['href']?>"><?php echo cut_str($list[$i]['subject'], $subject_len)?></a>
                        <span class='comment'><?php echo $list[$i]['wr_comment']?'+'.$list[$i]['wr_comment']:''?></span></li>
                    <?php } ?>
                </ul>
            </td>
        </tr>
        </table>
    </td>
</tr>
</table>

