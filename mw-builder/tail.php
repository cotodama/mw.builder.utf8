<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가 

include_once("$g4[path]/lib/mw.builder.lib.php");

if ($mg_id) {
    @include_once("$mw_group_skin_tail_path/group.tail.skin.php");
} else if (is_member_page()) {
    @include_once("$mw_member_skin_tail_path/member.tail.skin.php");
}

include_once("$g4[path]/tail.sub.php");
?>
