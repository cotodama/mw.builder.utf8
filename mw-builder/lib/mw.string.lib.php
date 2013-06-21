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

if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// 이메일등의 자동수집을 막기위해 자바스크립트로 한글자씩 잘라 출력함.
// 한글등의 2 byte 이상의 문자는 작동 안함.
function mw_nobot_slice($str) {
    $ret = "<script type=\"text/javascript\">";
    $ret .= "document.write(";
    for ($i=0; $i<strlen($str); $i++) {
	$ret .= "\"".substr($str, $i, 1)."\" + ";	
    }
    $ret .= "\"\")</script>";
    return $ret;
}
?>
