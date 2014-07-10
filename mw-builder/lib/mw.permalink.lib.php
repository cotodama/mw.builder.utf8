<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

function mw_seo_url($bo_table, $wr_id=0, $qstr='', $mobile=1)
{
    global $g4;
    global $mw;
    global $mw_mobile;

    return mw_builder_seo_url($bo_table, $wr_id, $qstr, $mobile);
}

function mw_builder_seo_url($bo_table, $wr_id=0, $qstr='', $mobile=1)
{
    global $g4;
    global $mw;
    global $mw_basic;
    global $mw_mobile;

    //$url = $g4['bbs_path']."/board.php?bo_table=".$bo_table;
    $url = $g4['url'];
    if ($bo_table)
        $url .= '/'.$g4['bbs'].'/board.php?bo_table='.$bo_table;

    if ($wr_id)
        $url .= "&wr_id=".$wr_id;

    if ($qstr)
        $url .= $qstr;

    $seo_path = '/b/';
    if ($mobile && mw_is_mobile_builder())
        $seo_path = '/m/b/';

    if ($mobile == 2)
        $seo_path = '/m/b/';

    if ($mw['config']['cf_seo_url'])
    {
        $url = $g4['url'];

        if ($bo_table)
            $url .= $seo_path.$bo_table;

        if ($wr_id)
            $url .= '-'.$wr_id;

        if ($qstr)
            $url .= '?'.$qstr;
    }

    return $url;
}

function mw_is_mobile_builder()
{
    $is_mobile = false;

    if (strstr($_SERVER['PHP_SELF'], "/plugin/mobile/"))
        $is_mobile = true;
    else if (strstr($_SERVER['PHP_SELF'], "/m/b/"))
        $is_mobile = true;

    return $is_mobile;
}


/*function mw_builder_seo_url($bo_table, $wr_id=0, $qstr='', $mobile=1)
{
    global $g4;
    global $mw;

    $url = $g4['bbs_path'].'/board.php?bo_table='.$bo_table;

    if ($wr_id)
        $url .= '&wr_id='.$wr_id;

    if ($qstr)
        $url .= $qstr;

    if ($mw['config']['cf_seo_url'])
    {
        $url = $g4['url'].'/b/'.$bo_table;

        if ($wr_id)
            $url .= '-'.$wr_id;

        if ($qstr)
            $url .= '?'.$qstr;
    }

    return $url;
}
*/

function mw_builder_seo_page($pg_id)
{
    global $g4;
    global $mw;

    $url = $g4['url'].'/page?pg_id='.$pg_id;

    if ($mw['config']['cf_seo_url']) {
        $url = $g4['url'].'/page_'.$pg_id;
    }

    return $url;
}


function mw_builder_seo_main($gr_id)
{
    global $g4;
    global $mw;

    $url = $g4['url'].'/?mw_main='.$gr_id;

    if ($mw['config']['cf_seo_url']) {
        $url = $g4['url'].'/main_'.$gr_id;
    }

    return $url;
}

function mw_builder_seo_sign()
{
    global $mw;

    if ($mw['config']['cf_seo_url'])
        return '?';
    else
        return '&';
}

function mw_bbs_path($path)
{
    global $g4;

    if (mw_is_mobile_builder()) {
        $path = preg_replace("/\.\//iUs", $g4['path'].'/plugin/mobile/', $path);
    }
    else {
        $path = preg_replace("/\.\//iUs", $g4['bbs_path'].'/', $path);
    }

    return $path;
}

function mw_seo_bbs_path($path)
{
    global $g4;
    global $bo_table;

    if (mw_is_mobile_builder()) {
        $path = str_replace('../../plugin/mobile/board.php?bo_table='.$bo_table, mw_seo_url($bo_table).'?', $path);
    }
    else {
        $path = str_replace('../board.php?bo_table='.$bo_table, mw_seo_url($bo_table).'?', $path);
    }

    return $path;
}


