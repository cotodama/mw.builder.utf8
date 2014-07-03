<?php
/**
 * Miwit Latest Board Group for Gnuboard4
 *
 * Copyright (c) 2008 Choi Jae-Young <www.miwit.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */


if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

function mw_board_group($title, $info, $timer, $list)
{
    global $g4, $mw, $mbgi;

    $timer *= 1000;

    $board_list = array();
    $skin_list = array();

    if (!$mbgi)
        $mbgi = 1;
    else
        $mbgi++;

    $count = 0;
    foreach ($list as $bo_table => $arr) {
        $row = sql_fetch("select bo_subject from $g4[board_table] where bo_table = '{$bo_table}'");
        $board[$count][bo_subject] = $row[bo_subject];
        $board[$count][bo_table] = $bo_table;
        $board[$count][skin] = $arr[skin];
        $board[$count][rows] = $arr[rows];
        $board[$count][length] = $arr[length];
        $board[$count][image_count] = $arr[image_count];
        $count++;
    }

    ob_start();
    ?>

    <style type="text/css">
    #mw_board_group_<?=$mbgi?> { background-color:#e1e1e1; width:100%; height:155px; border-top:2px solid #72ACE7; }
    #mw_board_group_<?=$mbgi?> td { background-color:#fff; }
    #mw_board_group_<?=$mbgi?> .quick_move { color:#72ACE7; font-size:10px; color:#E1E1E1; display:none; }
    #mw_board_group_<?=$mbgi?> .mw_board_group_<?=$mbgi?>_title { background-color:#fcfcfc; height:25px; padding:2px 0 0 15px; font-weight:bold; overflow:hidden; }
    #mw_board_group_<?=$mbgi?> .mw_board_group_<?=$mbgi?>_title a { font-size:11px; }
    #mw_board_group_<?=$mbgi?> .mw_board_group_<?=$mbgi?>_info { padding:2px 0 0 5px; height:25px; overflow:hidden; background-color:#f8f8f8; font-size:11px; }
    #mw_board_group_<?=$mbgi?> .mw_board_group_<?=$mbgi?>_info a { font-size:11px; }
    #mw_board_group_<?=$mbgi?> .mw_board_group_<?=$mbgi?>_list { width:130px; }
    #mw_board_group_<?=$mbgi?> .mw_board_group_<?=$mbgi?>_list a { font-family:dotum; }
    #mw_board_group_<?=$mbgi?> .mw_board_group_<?=$mbgi?>_list_layout { padding:5px 0 0 15px; }
    #mw_board_group_<?=$mbgi?> .mw_board_group_<?=$mbgi?>_list_layout div { height:20px; }
    #mw_board_group_<?=$mbgi?> .mw_board_group_<?=$mbgi?>_contents {  }
    <? $rand_index = rand(0, $count-1); ?>
    <? for ($i=0; $i<$count; $i++) { ?>
    <? if ($rand_index == $i) { $rand_table = $board[$i][bo_table]; continue; } ?>
    #mw_board_group_<?=$mbgi?> .mw_board_group_<?=$mbgi?>_content.<?=$board[$i][bo_table]?> { display:none; }
    <? } ?>
    #mw_board_group_<?=$mbgi?> .mw_board_group_<?=$mbgi?>_item { float:left; width:100px; overflow:hidden; }
    #mw_board_group_<?=$mbgi?> .mw_board_group_<?=$mbgi?>_item_selected { color:#6079A8; text-decoration:underline; font-weight:bold; font-size:11px; float:left; width:100px; overflow:hidden; }
    #mw_board_group_<?=$mbgi?> .mw_board_group_<?=$mbgi?>_item { color:#6B6D70; font-size:11px; font-family:dotum; }
    #mw_board_group_<?=$mbgi?> .mw_board_group_<?=$mbgi?>_item:hover { color:#2F3743; text-decoration:underline; }

    .mw-board-group-list-img { text-align:center; padding:15px 0 0 0;  }
    .mw-board-group-list-img .subject { background:url(<?=$latest_skin_path?>/img/box-bg.gif); height:24px; margin:0 0 7px 0; }
    .mw-board-group-list-img .subject .bo_table { margin:5px 0 0 5px; float:left; }
    .mw-board-group-list-img .subject .bo_table a { font-size:12px; color:#555; font-weight:bold; letter-spacing:-1px; text-decoration:none; }
    .mw-board-group-list-img .subject .list { margin:5px 5px 0 0; float:right; }
    .mw-board-group-list-img .subject .list a { font-weight:normal; font-size:11px; letter-spacing:-1px; color:#555; }
    .mw-board-group-list-img .file { width:100px; height:50px; padding-bottom:5px; }
    .mw-board-group-list-img .file-img { width:80px; height:50px; border:1px solid #e2e2e2; }
    .mw-board-group-list-img .post-subject { width:80px; height:30px; line-height:15px; overflow:hidden; font-family:dotum; margin:5px 5px 0 0; letter-spacing:-1px; font-size:10px; }
    .mw-board-group-list-img .post-subject a:hover { color:#438A01; text-decoration:underline; }
    .mw-board-group-list-img .comment { font-size:11px; color:#FF6600; font-family:dotum; letter-spacing:-1px; }

    .mw-board-group-list { text-align:left; padding:10px 0 0 10px; }
    .mw-board-group-list a { color:#2F3743; }
    .mw-board-group-list .subject { background:url(<?=$latest_skin_path?>/img/box-bg.gif); height:24px; margin:0 0 7px 0; }
    .mw-board-group-list .subject .bo_table { margin:5px 0 0 5px; float:left; }
    .mw-board-group-list .subject .bo_table a { font-size:12px; color:#555; font-weight:bold; letter-spacing:-1px; text-decoration:none; }
    .mw-board-group-list .subject .list { margin:5px 5px 0 0; float:right; }
    .mw-board-group-list .subject .list a { font-weight:normal; font-size:11px; letter-spacing:-1px; color:#555; }
    .mw-board-group-list ul { margin:0 0 5px 7px; padding:0; list-style:none; }
    .mw-board-group-list ul li { margin:0; padding:0 0 0 7px; background:url(<?=$latest_skin_path?>/img/dot.gif) no-repeat 0 5px; height:20px; }
    .mw-board-group-list ul li a:hover { color:#438A01; text-decoration:underline; }
    .mw-board-group-list .file-img { width:100px; height:65px; border:1px solid #e2e2e2; }
    .mw-board-group-list .file-subject { line-height:15px; font-size:11px; letter-spacing:-1px; width:100px; height:28px; margin:3px 0 0 0; overflow:hidden; cursor:pointer; }
    .mw-board-group-list .file a:hover { color:#438A01; text-decoration:underline; }
    .mw-board-group-list .comment { font-size:11px; color:#FF6600; font-family:dotum; letter-spacing:-1px; }
    </style>


    <table border="0" cellpadding="0" cellspacing="1" id="mw_board_group_<?=$mbgi?>">
    <tr>
        <td class="mw_board_group_<?=$mbgi?>_title"><?=$title?></td>
        <td class="mw_board_group_<?=$mbgi?>_info"><?=$info?></td>
    </tr>
    <tr>
        <td class="mw_board_group_<?=$mbgi?>_list" valign="top">
            <div class="mw_board_group_<?=$mbgi?>_list_layout">
                <? for ($i=0; $i<$count; $i++) { ?>
                <div><a href="javascript:mw_board_group_<?=$mbgi?>_click('<?=$board[$i][bo_table]?>')" id="mw_board_group_<?=$mbgi?>_item_<?=$board[$i][bo_table]?>"
                    class="mw_board_group_<?=$mbgi?>_item" ondblclick="location.href='<?=mw_builder_seo_url($board[$i][bo_table])?>'"><?=$board[$i][bo_subject]?></a>
                    <a href="<?=mw_builder_seo_url($board[$i][bo_table])?>" class="quick_move">▶</a>
                </div>
                <? } ?>
            </div>
        </td>
        <td class="mw_board_group_<?=$mbgi?>_contents" valign="top">
            <? for ($i=0; $i<$count; $i++) { ?>
            <div class="mw_board_group_<?=$mbgi?>_content <?=$board[$i][bo_table]?>">
                <?=mw_latest($board[$i][skin], $board[$i][bo_table], $board[$i][rows], $board[$i][length], $board[$i][image_count], $mw[config][cf_index_cache])?>
            </div>
            <? } ?>
        </td>
    </tr>
    </table>

    <script type="text/javascript">
    mw_board_group_<?=$mbgi?>_list = new Array();
    <? for ($i=0; $i<$count; $i++) { ?>
    mw_board_group_<?=$mbgi?>_list[<?=$i?>] = "<?=$board[$i][bo_table]?>";
    <? } ?> 
    mw_board_group_<?=$mbgi?>_index = "<?=$rand_index?>";
    mw_board_group_<?=$mbgi?>_time = "";

    function mw_board_group_<?=$mbgi?>_click(bo_table) {
        $(".mw_board_group_<?=$mbgi?>_content").each(function () {
            $(this).css("display","none");
        });
        $(".mw_board_group_<?=$mbgi?>_content."+bo_table).css("display","block");

        $(".mw_board_group_<?=$mbgi?>_item_selected").each(function () {
            $(this).removeClass().addClass("mw_board_group_<?=$mbgi?>_item");
        });
        $("#mw_board_group_<?=$mbgi?>_item_"+bo_table).removeClass().addClass("mw_board_group_<?=$mbgi?>_item_selected");

        for (i=0; i<<?=$count?>; i++) {
            if (mw_board_group_<?=$mbgi?>_list[i] == bo_table) {
                mw_board_group_<?=$mbgi?>_index = i;
            }
        }
    }

    function mw_board_group_<?=$mbgi?>_random() {
        if (++mw_board_group_<?=$mbgi?>_index >= <?=$count?>)
            mw_board_group_<?=$mbgi?>_index = 0;
        mw_board_group_<?=$mbgi?>_click(mw_board_group_<?=$mbgi?>_list[mw_board_group_<?=$mbgi?>_index]);
        mw_board_group_<?=$mbgi?>_time = setTimeout(mw_board_group_<?=$mbgi?>_random, <?=$timer?>);
    }
    
    $(document).ready(function() {
        mw_board_group_<?=$mbgi?>_click("<?=$rand_table?>");
        mw_board_group_<?=$mbgi?>_time = setTimeout(mw_board_group_<?=$mbgi?>_random, <?=$timer?>);

        $("#mw_board_group_<?=$mbgi?>").hover(function () { 
            clearTimeout(mw_board_group_<?=$mbgi?>_time)
        }, function () {
            mw_board_group_<?=$mbgi?>_time = setTimeout(mw_board_group_<?=$mbgi?>_random, <?=$timer?>);
        });
    });

    </script>

    <?
    $content = ob_get_contents();
    ob_clean();

    return $content;
}

