<?php
define("_MW_INDEX_", TRUE); // index 파일

include_once("_common.php");

//if ($mw_main && !$group) alert("잘못된 그룹ID 입니다.");

include_once("$g4[path]/lib/mw.builder.lib.php");

if ($mg_id) { 
    $g4[title] = $group[gr_subject];
    include_once("$g4[path]/head.php");
    if ($mm_id) 
        include_once("$mw_mmenu_skin_path/mmenu.main.skin.php");
    else
        include_once("$mw_group_skin_home_path/group.home.skin.php");
    include_once("$g4[path]/tail.php");
} else {
    include_once("$g4[path]/head.sub.php");
    include_once("$mw_index_skin_head_path/index.head.skin.php");
    include_once("$mw_index_skin_main_path/index.main.skin.php");
    include_once("$mw_index_skin_tail_path/index.tail.skin.php");
    include_once("$g4[path]/tail.sub.php");
}

