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

if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

function mw_popup() {
    global $g4, $mw, $member, $_COOKIE, $gr_id;

    $ret = "";
    //$ret.= "<script type=\"text/javascript\" src=\"$g4[path]/js/jquery.js\"></script>\n";
    $ret.= "<script type=\"text/javascript\" src=\"$g4[path]/js/jquery.easydrag.js\"></script>\n";
    $ret.= "<script type=\"text/javascript\">\n";
    $ret.= "function mw_popup_layer_close(ly, day) {\n";
    $ret.= "	if (document.getElementById(ly+\"-close-check\").checked) {\n";
    $ret.= "	    set_cookie(ly, 'no', day*24, g4_cookie_domain);\n";
    $ret.= "	}\n";
    $ret.= "	$(\"#\"+ly).css('display','none');\n";
    $ret.= "}\n";
    $ret.= "function mw_popup_win_close(ly, day) {\n";
    $ret.= "	eval('var mw_popup_win = ' + ly);\n";
    $ret.= "	if (mw_popup_win.document.getElementById(ly+\"-close-check\").checked) {\n";
    $ret.= "	    set_cookie(ly, 'no', day*24, g4_cookie_domain);\n";
    $ret.= "	}\n";
    $ret.= "	mw_popup_win.close();\n";
    $ret.= "}\n";
    $ret.= "function set_cookie(name, value, expirehours, domain) {\n";
    $ret.= "    var today = new Date();\n";
    $ret.= "    today.setTime(today.getTime() + (60*60*1000*expirehours));\n";
    $ret.= "    var cook = name + \"=\" + escape( value ) + \"; path=/; expires=\" + today.toGMTString() + \";\";\n";
    $ret.= "    if (domain) cook += \"domain=\" + domain + \";\";\n";
    $ret.= "    document.cookie = cook;\n";
    $ret.= "}\n";
    $ret.= "</script>\n";
    $ret.= "<style type=\"text/css\">\n";
    $ret.= ".mw-popup-close-button { background-color:#000; color:#ddd; border:0; cursor:pointer; } \n";
    $ret.= "</style>\n";

    $sql = " select * from $mw[popup_table] where pp_use = '1' ";
    $sql.= " and ('$g4[time_ymdhis]' between pp_start and pp_end) order by pp_id desc";
    $qry = sql_query($sql, false);
    for ($i=0; $row=sql_fetch_array($qry); $i++) {
	$popup_name = "mw_popup_$row[pp_id]";
	switch ($row[pp_time]) {
	    case "1": $close_name = "하루"; break;
	    case "3": $close_name = "3일"; break;
	    case "7": $close_name = "일주일"; break;
	    case "30": $close_name = "한달"; break;
	}
	if ($row[mb_level] > $member[mb_level]) continue; // 회원권한 체크	
	if ($_COOKIE[$popup_name] == "no") continue; // 쿠키
        if ($row[gr_id] == "index" && defined("_MW_INDEX_") && !$gr_id) ; // 인덱스 팝업
        else if ($row[gr_id] == "") ; // 모든 팝업
        else if ($row[gr_id] == $gr_id) ; // 그룹 팝업
        else continue;
	if ($row[pp_type] == "1") { // 레이어 팝업
	    $close_layer = "\n<div id=\"$popup_name-close\"><input type=\"checkbox\" name=\"$popup_name-close-check\" id=\"$popup_name-close-check\">&nbsp;";
	    $close_layer.= "{$close_name} 동안 이 창을 열지 않습니다.";
	    $close_layer.= "<input type=\"button\" value=\"[닫기]\" class=\"mw-popup-close-button\" ";
	    $close_layer.= "onclick=\"mw_popup_layer_close('$popup_name', '$row[pp_time]')\"></div>";
	    $ret.= "<style type=\"text/css\">\n";
	    $ret.= "#$popup_name-close { position:absolute; width:$row[pp_width]px; height:25px; left:-1px; top:0; margin:".($row[pp_height])."px 0 0 0; padding:0; z-index:99999; }\n";
	    $ret.= "#$popup_name-close { color:#ddd; text-align:center; background-color:#000; border:1px solid #000; }\n";
	    $ret.= "#$popup_name { position:absolute; width:$row[pp_width]px; height:$row[pp_height]px; left:$row[pp_left]px; top:$row[pp_top]px; }\n";
	    $ret.= "#$popup_name { border:1px solid #ddd; background-color:#fff; text-align:left; padding:0; z-index:99999; }\n";
	    $ret.= "</style>\n";
	    $ret.= "<div id=\"$popup_name\">$close_layer$row[pp_content]</div>\n";
	    $ret.= "<script type=\"text/javascript\">\n";
	    $ret.= "$(\"#$popup_name\").easydrag();\n";
            if ($row[pp_center]) {
                $ret.= "mw_popup_center_top = $(window).scrollTop() + ($(window).height() - $row[pp_height]) / 2\n";
                $ret.= "mw_popup_center_left = $(window).scrollLeft() + ($(window).width() - $row[pp_width]) / 2\n";
                $ret.= "$(\"#$popup_name\").css('top', mw_popup_center_top);\n";
                $ret.= "$(\"#$popup_name\").css('left', mw_popup_center_left);\n";
            }
	    $ret.= "</script>\n";
	} else { // 새창 팝업
	    $content = $row[pp_content];
	    $content = addslashes($content);
	    $content = str_replace("\n", "", $content);
	    $content = str_replace("\r", "", $content);
	    $ret.= "<script type=\"text/javascript\">\n";
	    $ret.= "$popup_name = window.open('', '$popup_name', 'width=$row[pp_width], height=".($row[pp_height]+25).", left=$row[pp_left], top=$row[pp_top]');\n";
	    $ret.= "$popup_name.document.open();\n";
            if ($row[pp_center]) {
                $ret.= "mw_popup_center_top = (screen.height - $row[pp_height]-50) / 2\n";
                $ret.= "mw_popup_center_left = (screen.width - $row[pp_width]) / 2\n";
                $ret.= "$popup_name.moveTo(mw_popup_center_left, mw_popup_center_top);\n";
            }
	    $ret.= "$popup_name.document.write (\"<html><head> \\n<meta http-equiv='imagetoolbar' CONTENT='no'> ";
	    $ret.= "					 <meta http-equiv='content-type' content='text/html; charset=\\\"+g4_charset+\\\"'>\\n\");\n"; 
	    $ret.= "$popup_name.document.write (\"<title>$row[pp_subject]</title> \\n\");\n"; 
	    $ret.= "$popup_name.document.write (\"</head> \\n\\n\");\n";
	    $ret.= "$popup_name.document.write (\"<body leftmargin=0 topmargin=0 bgcolor=#ffffff> \\n\");\n";
	    $ret.= "$popup_name.document.write (\"$content\");\n";
	    $ret.= "$popup_name.document.write (\"<div id='$popup_name-close'>\");\n";
	    $ret.= "$popup_name.document.write (\"<input type='checkbox' name='$popup_name-close-check' id='$popup_name-close-check'>&nbsp;\");\n";
	    $ret.= "$popup_name.document.write (\"{$close_name} 동안 이 창을 열지 않습니다.\");\n";
	    $ret.= "$popup_name.document.write (\"<input type='button' value='[닫기]' class='mw-popup-close-button'\");\n";
	    $ret.= "$popup_name.document.write (\"onclick=\\\"opener.mw_popup_win_close('$popup_name', '$row[pp_time]')\\\"></div>\");\n";
	    $ret.= "$popup_name.document.write (\"<style type='text/css'>\");\n";
	    $ret.= "$popup_name.document.write (\"#$popup_name-close { position:absolute; width:$row[pp_width]px; height:25px; } \");\n";
	    $ret.= "$popup_name.document.write (\"#$popup_name-close { left:-1px; top:0; margin:$row[pp_height]px 0 0 0; padding:0; } \");\n";
	    $ret.= "$popup_name.document.write (\"#$popup_name-close { color:#ddd; text-align:center; background-color:#000; border:1px solid #000; } \");\n";
	    $ret.= "$popup_name.document.write (\"#$popup_name-close { font-size:12px; } \");\n";
	    $ret.= "$popup_name.document.write (\".mw-popup-close-button { background-color:#000; color:#ddd; border:0; cursor:pointer; } \");\n";
	    $ret.= "$popup_name.document.write (\"</style>\");\n";
	    $ret.= "$popup_name.document.write (\"</body></html>\"\n);";
	    $ret.= "$popup_name.document.close();\n";
	    $ret.= "$popup_name.focus();\n";
	    $ret.= "</script>\n";
	}
    }
    return $ret;
}
