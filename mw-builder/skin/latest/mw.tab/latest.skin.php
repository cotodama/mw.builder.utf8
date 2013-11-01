<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

global $mw_tab_cnt;

if (!$mw_tab_cnt)
    $mw_tab_cnt = 0;

$mw_tab_cnt++;

$count = count($tab);
$style_name = "mw-tab-$file_tables-$rows-$subject_len";
?>

<style type="text/css">
.<?=$style_name?>-subject { clear:both; background:url(<?=$latest_skin_path?>/img/main-bar-bg.gif); height:25px; margin:0 5px 0 5px; }
.<?=$style_name?>-subject div.tab { float:left; background:url(<?=$latest_skin_path?>/img/main-bar-off.gif); height:25px; }
.<?=$style_name?>-subject div.tab-on { float:left; background:url(<?=$latest_skin_path?>/img/main-bar-on.gif); height:25px; }
.<?=$style_name?>-subject div.link { margin:5px 7px 0 7px; } 
.<?=$style_name?>-subject div.div { float:left; height:25px; }
.<?=$style_name?>-subject a { font-weight:bold; color:#145DAA; }
.<?=$style_name?> { clear:both; text-align:left; background-color:#fff; margin:0 5px 0 5px; }
.<?=$style_name?> { border-left:1px solid #d8d8d8; border-right:1px solid #d8d8d8; border-bottom:1px solid #d8d8d8; }
.<?=$style_name?> ul { margin:0 0 5px 7px; padding:10px 0 0 0; list-style:none; }
.<?=$style_name?> ul li { margin:0; padding:0 0 0 7px; background:url(<?=$latest_skin_path?>/img/dot.gif) no-repeat 0 5px; height:20px; line-height:20px; overflow:hidden; }
.<?=$style_name?> ul li a:hover { color:#438A01; text-decoration:underline; }
.<?=$style_name?> .comment { font-size:11px; color:#FF6600; font-family:dotum; }
</style>

<div class="<?=$style_name?>-subject">
<? for ($i=0; $i<$count; $i++) { ?>
<div class="div"><img src="<?=$latest_skin_path?>/img/main-bar-div.gif"></div>
<div class="tab" id="tab-<?=$mw_tab_cnt?>-<?=($i)?>" onmouseover="tab_<?=$mw_tab_cnt?>_over(<?=$i?>)" onmouseout="tab_<?=$mw_tab_cnt?>_over_cancel()">
<div class="link"><a href="<?=$g4[bbs_path]?>/board.php?bo_table=<?=$bo_tables[$i]?>"><?=$tab[$bo_tables[$i]]['board']['bo_subject']?></a></div>
</div>
<? } ?>
<div class="div"><img src="<?=$latest_skin_path?>/img/main-bar-div.gif"></div>
</div>

<div class="<?=$style_name?>">

<? // 게시판 시작
$index = 0;
if ($count > 0) {
    foreach ($tab as $bo_table => $list) {
	if ($bo_table == 'main') continue;
	$file = $tab[$bo_table]['file'];
	$board = $tab[$bo_table]['board'];
?>
<div id="latest-tab-<?=$mw_tab_cnt?>-<?=$index?>" style="display:none;">
<ul> 
<? for ($i=0; $i<$rows; $i++) { ?>
<? $list[$i][subject] = mw_builder_reg_str($list[$i][subject]); ?>
<? $list[$i][comment_cnt] = $list[$i][wr_comment] ? "+{$list[$i][wr_comment]}" : ''; ?>
<? $list[$i][href] = "$g4[bbs_path]/board.php?bo_table=$bo_table&wr_id=".$list[$i][wr_id]; ?>
<li><a href="<?=$list[$i][href]?>"><?=$list[$i][subject]?> <span class="comment"><?=$list[$i][comment_cnt]?></span></a></li> 
<? } ?> 
</ul>
</div>

<?
$index++; } }
?>

</div>

<script type="text/javascript">
function tab_<?=$mw_tab_cnt?>_over(i) {
    //set_cookie("ck_tab_<?=$mw_tab_cnt?>", i, 0, g4_cookie_domain);
    main_tab_<?=$mw_tab_cnt?>_val = setTimeout("tab_<?=$mw_tab_cnt?>_over_action(" + i + ")", 100);
}

function tab_<?=$mw_tab_cnt?>_over_cancel() {
    clearTimeout(main_tab_<?=$mw_tab_cnt?>_val);
}

function tab_<?=$mw_tab_cnt?>_over_action(i) {
<? for ($i=0; $i<$count; $i++) { ?>
document.getElementById("tab-<?=$mw_tab_cnt?>-<?=$i?>").className = "tab"; 
document.getElementById("latest-tab-<?=$mw_tab_cnt?>-<?=$i?>").style.display = "none"; 
<? } ?>
document.getElementById("tab-<?=$mw_tab_cnt?>-" + i).className = "tab-on"; 
document.getElementById("latest-tab-<?=$mw_tab_cnt?>-" + i).style.display = "block"; 
}

/*
d = get_cookie("ck_tab_<?=$mw_tab_cnt?>");
if (!d) d = 0;

document.getElementById("tab-<?=$mw_tab_cnt?>-" + d).className = "tab-on"; 
document.getElementById("latest-tab-<?=$mw_tab_cnt?>-" + d).style.display = "block"; 
*/
document.getElementById("tab-<?=$mw_tab_cnt?>-0").className = "tab-on"; 
document.getElementById("latest-tab-<?=$mw_tab_cnt?>-0").style.display = "block"; 
</script>

