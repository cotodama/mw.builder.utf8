<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

global $mw_tab_cnt;

if (!$mw_tab_cnt)
    $mw_tab_cnt = 0;

$mw_tab_cnt++;

$count = count($tab);
$style_name = "mw-tab-$file_tables-$rows-$subject_len";
?>

<style type="text/css">
.<?php echo $style_name?>-subject { clear:both; background:url(<?php echo $latest_skin_path?>/img/main-bar-bg.gif); height:25px; margin:0 5px 0 5px; }
.<?php echo $style_name?>-subject div.tab { float:left; background:url(<?php echo $latest_skin_path?>/img/main-bar-off.gif); height:25px; }
.<?php echo $style_name?>-subject div.tab-on { float:left; background:url(<?php echo $latest_skin_path?>/img/main-bar-on.gif); height:25px; }
.<?php echo $style_name?>-subject div.link { margin:5px 7px 0 7px; } 
.<?php echo $style_name?>-subject div.div { float:left; height:25px; }
.<?php echo $style_name?>-subject a { font-weight:bold; color:#145DAA; }
.<?php echo $style_name?> { clear:both; text-align:left; background-color:#fff; margin:0 5px 0 5px; }
.<?php echo $style_name?> { border-left:1px solid #d8d8d8; border-right:1px solid #d8d8d8; border-bottom:1px solid #d8d8d8; }
.<?php echo $style_name?> ul { margin:0 0 5px 7px; padding:10px 0 0 0; list-style:none; }
.<?php echo $style_name?> ul li { margin:0; padding:0 0 0 7px; background:url(<?php echo $latest_skin_path?>/img/dot.gif) no-repeat 0 5px; height:20px; line-height:20px; overflow:hidden; }
.<?php echo $style_name?> ul li a:hover { color:#438A01; text-decoration:underline; }
.<?php echo $style_name?> .comment { font-size:11px; color:#FF6600; font-family:dotum; }
</style>

<div class="<?php echo $style_name?>-subject">
    <?php for ($i=0; $i<$count; $i++) { ?>
    <div class="div"><img src="<?php echo $latest_skin_path?>/img/main-bar-div.gif"></div>
    <div class="tab" id="tab-<?php echo $mw_tab_cnt?>-<?php echo ($i)?>"
        onmouseover="tab_<?php echo $mw_tab_cnt?>_over(<?php echo $i?>)"
        onmouseout="tab_<?php echo $mw_tab_cnt?>_over_cancel()">
    <div class="link"><a href="<?php echo $g4['bbs_path']?>/board.php?bo_table=<?php
        echo $bo_tables[$i]?>"><?php echo $tab[$bo_tables[$i]]['board']['bo_subject']?></a></div>
    </div>
    <?php } ?>
    <div class="div"><img src="<?php echo $latest_skin_path?>/img/main-bar-div.gif"></div>
</div>

<div class="<?php echo $style_name?>">
<?php // 게시판 시작
$index = 0;
if ($count > 0) {
    foreach ($tab as $bo_table => $list) {
	if ($bo_table == 'main') continue;
	$file = $tab[$bo_table]['file'];
	$board = $tab[$bo_table]['board'];
?>
<div id="latest-tab-<?php echo $mw_tab_cnt?>-<?php echo $index?>" style="display:none;">
<ul> 
<?php
for ($i=0; $i<$rows; $i++) {
    $list[$i]['subject'] = mw_builder_reg_str($list[$i]['subject']);
    $list[$i]['comment_cnt'] = $list[$i]['wr_comment'] ? "+{$list[$i]['wr_comment']}" : '';
    $list[$i]['href'] = "{$g4['bbs_path']}/board.php?bo_table=$bo_table&wr_id=".$list[$i]['wr_id'];
    ?>
    <li><a href="<?php echo $list[$i]['href']?>"><?php echo $list[$i]['subject']?><!--
        -->&nbsp;<span class="comment"><?php echo $list[$i]['comment_cnt']?></span></a></li> 
<?php } ?> 
</ul>
</div>
<?php $index++; } } ?>
</div>

<script>
function tab_<?php echo $mw_tab_cnt?>_over(i) {
    //set_cookie("ck_tab_<?php echo $mw_tab_cnt?>", i, 0, g4_cookie_domain);
    main_tab_<?php echo $mw_tab_cnt?>_val = setTimeout("tab_<?php echo $mw_tab_cnt?>_over_action(" + i + ")", 100);
}

function tab_<?php echo $mw_tab_cnt?>_over_cancel() {
    clearTimeout(main_tab_<?php echo $mw_tab_cnt?>_val);
}

function tab_<?php echo $mw_tab_cnt?>_over_action(i) {
    <?php for ($i=0; $i<$count; $i++) { ?>
    document.getElementById("tab-<?php echo $mw_tab_cnt?>-<?php echo $i?>").className = "tab"; 
    document.getElementById("latest-tab-<?php echo $mw_tab_cnt?>-<?php echo $i?>").style.display = "none"; 
    <?php } ?>
    document.getElementById("tab-<?php echo $mw_tab_cnt?>-" + i).className = "tab-on"; 
    document.getElementById("latest-tab-<?php echo $mw_tab_cnt?>-" + i).style.display = "block"; 
}

document.getElementById("tab-<?php echo $mw_tab_cnt?>-0").className = "tab-on"; 
document.getElementById("latest-tab-<?php echo $mw_tab_cnt?>-0").style.display = "block"; 
</script>

