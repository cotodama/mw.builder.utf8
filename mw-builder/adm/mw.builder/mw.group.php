<?
/**
 * MW Builder for Gnuboard4
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

$sub_menu = "110300";
include_once("_common.php");
include_once("mw.menus.lib.php");

auth_check($auth[$sub_menu], "r");

$g4[title] = "그룹관리";
include_once("$g4[admin_path]/admin.head.php");

$colspan = 9;
?>

<script type="text/javascript" src="<?=$g4[path]?>/js/jquery.js"></script>
<script type="text/javascript">
function group_reload() {
    $("#group-list").load("<?=$mw[admin_path]?>/mw.group.list.php?<?=$qstr?>");
}
function mw_move(act, gr_id) {
    var url = "mw.group.move.php?w=" + act + "&gr_id=" + gr_id;
    $.post(url, setTimeout("group_reload()", 100));
}
$(document).ready(function(){
   group_reload();
});

</script>

<div class="mw-admin-info-box">
이 페이지는 "게시판그룹관리" 메뉴의 확장 메뉴입니다.
그룹 생성 및 기본적인 관리는 "<a href="<?=$g4[admin_path]?>/boardgroup_list.php">게시판그룹관리</a>" 에서 하실 수 있습니다.
</div>

<script type="text/javascript">
var list_update_php = "<?=$mw[admin_path]?>/mw.group.list.update.php";
</script>

<div id="group-list">

</div>

<script type="text/javascript">
// POST 방식으로 삭제
function post_delete(action_url, val)
{
    var f = document.fpost;

    if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
	f.gr_id.value = val;
	f.action      = action_url;
	f.submit();
    }
}
</script>

<form name='fpost' method='post'>
<input type='hidden' name='sst'   value='<?=$sst?>'>
<input type='hidden' name='sod'   value='<?=$sod?>'>
<input type='hidden' name='sfl'   value='<?=$sfl?>'>
<input type='hidden' name='stx'   value='<?=$stx?>'>
<input type='hidden' name='page'  value='<?=$page?>'>
<input type='hidden' name='token' value='<?=$token?>'>
<input type='hidden' name='gr_id'>
</form>

<?
include_once("$g4[admin_path]/admin.tail.php");
?>
