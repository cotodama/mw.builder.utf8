<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// 배추빌더 기본설정
$mw[config] = sql_fetch("select * from $mw[config_table] limit 1", false);

include_once($g4['path'].'/lib/mw.permalink.lib.php');
include_once($g4['path'].'/lib/mw.common.lib.php');

if ($bo_table && !isset($w))
    include_once($g4['path'].'/lib/mw.builder.lib.php');


