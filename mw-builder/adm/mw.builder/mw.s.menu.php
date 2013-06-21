<?php
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

$sub_menu = "110500";
include_once("_common.php");
include_once("mw.menus.lib.php");

auth_check($auth[$sub_menu], "r");

$listall = "<a href='$_SERVER[PHP_SELF]'>처음</a>";

$g4[title] = "소메뉴관리";
include_once("$g4[admin_path]/admin.head.php");

$colspan = 9;
?>

<script type="text/javascript" src="<?=$g4[path]?>/js/jquery.js"></script>
<script type="text/javascript">
function menu_reload(mm_id) {
    $("#menu-list").load("<?=$mw[admin_path]?>/mw.s.menu.list.php?<?=$qstr?>&mm_id=" + mm_id);
}
function mw_move(act, mm_id, ms_id) {
    $.get("mw.s.menu.move.php?w=" + act + "&mm_id=" + mm_id + "&ms_id=" + ms_id, setTimeout("menu_reload('" + mm_id + "')", 100));
}
$(document).ready(function(){
   menu_reload('<?=$mm_id?>');
});

</script>

<script type="text/javascript">
var list_update_php = "<?=$mw[admin_path]?>/mw.s.menu.list.update.php";
var list_delete_php = "<?=$mw[admin_path]?>/mw.s.menu.list.delete.php";
</script>

<div id="menu-list">

</div>

<script type="text/javascript">
// POST 방식으로 삭제
function post_delete(action_url, mm_id, ms_id)
{
    var f = document.fpost;

    if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
	f.mm_id.value = mm_id;
	f.ms_id.value = ms_id;
	f.action      = action_url;
	f.submit();
    }
}
</script>

<?
include_once("$g4[admin_path]/admin.tail.php");
