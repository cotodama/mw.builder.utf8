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

header("Content-Type: text/html; charset=$g4[charset]");
$gmnow = gmdate("D, d M Y H:i:s") . " GMT";
header("Expires: 0"); // rfc2616 - Section 14.21
header("Last-Modified: " . $gmnow);
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: pre-check=0, post-check=0, max-age=0"); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

if ($w == 'u')
    check_demo();

auth_check($auth[$sub_menu], "w");

if (!$skin) exit;

// 스킨디렉토리
$skin_options = "";
$arr = get_skin_dir("mw.builder/$skin/img");
for ($k=0; $k<count($arr); $k++) 
{
    $select = "";
    $option = $arr[$k];
    if (strlen($option) > 10)
        $option = substr($arr[$k], 0, 18) . "…";
    if ($arr[$k] == $theme)
	$select = "selected";

    $skin_options .= "<option value='$arr[$k]' $select>$option</option>";
}
echo "테마 : <select id=gr_theme name=gr_theme><option></option>$skin_options</select>";

?>
