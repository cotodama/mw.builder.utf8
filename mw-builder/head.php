<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

include_once("$g4[path]/lib/mw.builder.lib.php");
include_once("$g4[path]/head.sub.php");

if ($mg_id) {
    @include_once("$mw_group_skin_head_path/group.head.skin.php");
} else if (is_member_page()) {
    @include_once("$mw_member_skin_head_path/member.head.skin.php");
}
?>
