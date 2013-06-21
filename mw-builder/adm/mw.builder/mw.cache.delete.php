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

$sub_menu = "110600";
include_once("./_common.php");

if ($is_admin != "super")
    alert("최고관리자만 접근 가능합니다.", $g4[path]);

$is_cache_delete = true;

$g4[title] = "캐쉬 삭제";
include_once("$g4[admin_path]/admin.head.php");
echo "'완료' 메세지가 나오기 전에 프로그램의 실행을 중지하지 마십시오.<br><br>";
echo "<span id='ct'></span>";
include_once("$g4[admin_path]/admin.tail.php");
flush();

// 그룹삭제시 속해있는 중메뉴, 소메뉴 삭제
$g = array();
$m = array();

$sql = "select gr_id from $g4[group_table]";
$qry = sql_query($sql);
while ($row = sql_fetch_array($qry)) $g[] = $row[gr_id];

$sql = "select mm_id from $mw[menu_middle_table] where gr_id not in ('".implode("','", $g)."')";
$qry = sql_query($sql);
while ($row = sql_fetch_array($qry)) $m[] = $row[mm_id];

$sql = "delete from $mw[menu_small_table] where mm_id in ('".implode("','", $m)."')";
$qry = sql_query($sql);

$sql = "delete from $mw[menu_middle_table] where mm_id in ('".implode("','", $m)."')";
$qry = sql_query($sql);



$cache_path = "$g4[path]/data/mw.cache";  // 캐쉬가 저장된 디렉토리 
if (!$dir=@opendir($cache_path)) { 
  echo "캐쉬 디렉토리를 열지못했습니다."; 
} 

$cnt=0;
while ($file = readdir($dir)) { 
    if ($file == "." || $file == "..") continue;
    if ($file == "index.php") continue;
    if (is_dir("$cache_path/$file")) continue;

    $cnt++;
    $return = unlink("$cache_path/$file"); 
    echo "<script>document.getElementById('ct').innerHTML += '$cache_path/$file<br/>';</script>\n";
    flush();

    if ($cnt%10==0)
	echo "<script>document.getElementById('ct').innerHTML = '';</script>\n";
}

include_once("mw.menu.cache.php");

echo "<script>document.getElementById('ct').innerHTML += '<br><br>캐쉬데이터 {$cnt}건 삭제 완료.<br><br>프로그램의 실행을 끝마치셔도 좋습니다.';</script>\n";
?>
