<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

if ($mw_index_skin_head_path)
    $skin_path = $mw_index_skin_head_path;
else
    $skin_path = $mw_group_skin_head_path;

?>
<style>
#group-more-layer { border:2px solid #777; background-color:#fff; display:none; position:absolute; width:320px; z-index:100; }
#group-more-layer ul { margin:0; padding:0; list-style:none; }
#group-more-layer li { margin:0; padding:0; float:left; width:100px; height:25px; overflow:hidden; text-align:left;}
#group-more-layer .margin { padding:10px; }
#group-more-layer .close { clear:both; text-align:right; }
</style>

<script>
function group_more() {
    var b = document.getElementById("group-more-button");
    var l = document.getElementById("group-more-layer");
    if (l.style.display != "inline") {
	l.style.display = "inline";
	l.style.left = get_left_pos(b) - 200 + 'px';
	l.style.top = get_top_pos(b) + 20 + 'px';
    } else {
	l.style.display = "none";
    }
}
</script>

<div id="group-more-layer">
<div class="margin">
    <ul>
        <?php for ($i=0; $i<$mw_groups_more_count; $i++) { ?>
        <li><a href="<?=$mw_groups_more[$i][gr_url]?>"
            target="<?=$mw_groups_more[$i][gr_target]?>"
            style="<?=$mw_groups_more[$i][gr_more_css]?>"><?=$mw_groups_more[$i][gr_subject]?></a> </li>
        <?php } ?>
    </ul>
    <div class="close"><img src="<?=$skin_path?>/img/x.gif"
        onclick="group_more()" style="cursor:pointer"></div>
</div>
</div>

