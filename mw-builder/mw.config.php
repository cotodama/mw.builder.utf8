<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$mw = array();
$mw['table_prefix']		= "mw_"; // 테이블명 접두사
$mw['config_table']		= $mw['table_prefix'] . "config";	    // 설정 테이블 
$mw['session_table']		= $mw['table_prefix'] . "session";	    // 세션 테이블 
$mw['board_visit_table']	= $mw['table_prefix'] . "board_visit";      // 게시판별 접속자 
$mw['board_visit_log_table']    = $mw['table_prefix'] . "board_visit_log";  // 게시판별 접속자 
$mw['menu_middle_table']	= $mw['table_prefix'] . "menu_middle";	    // 중간메뉴 
$mw['menu_small_table']		= $mw['table_prefix'] . "menu_small";	    // 소메뉴 
$mw['popup_table']		= $mw['table_prefix'] . "popup";	    // 팝업창 관리 
$mw['page_table']		= $mw['table_prefix'] . "page";	            // 페이지 관리


$mw['admin'] = "mw.builder";
$mw['admin_path'] = $g4['admin_path'] . "/" . $mw['admin']; 

?>
