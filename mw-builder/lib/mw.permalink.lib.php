<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

if (@is_file($g4['path'].'/lib/mw.host.lib.php'))
    include_once($g4['path'].'/lib/mw.host.lib.php');

function mw_seo_except($bo_table)
{
    global $mw;

    if (!$bo_table) return false;

    $list = explode(',', $mw['config']['cf_seo_except']);
    $list = array_filter($list, 'trim');

    if (in_array($bo_table, $list))
        return true;

    return false;
}

function mw_seo_url($bo_table, $wr_id=0, $qstr='', $mobile=1)
{
    global $g4;
    global $mw;
    global $mw_basic;
    global $mw_mobile;
    global $is_admin;

    return mw_builder_seo_url($bo_table, $wr_id, $qstr, $mobile);
}

function mw_builder_seo_url($bo_table, $wr_id=0, $qstr='', $mobile=1)
{
    global $g4;
    global $mw;
    global $mw_basic;
    global $mw_mobile;
    global $is_admin;

    $url = $g4['url'];

    if (!$mobile && $mw_mobile['m_subdomain'])
        $url = preg_replace("/^http:\/\/m\./", "http://", $url);

    if (($mobile && mw_is_mobile_builder()) or ($mobile == 2))  {
        if ($mw_mobile['m_subdomain'] && !preg_match("/^http:\/\/m\./", $url)) {
            $url = mw_sub_domain_url("m", $url);
        }
        $seo_path = '/plugin/mobile';
    }
    else
        $seo_path = '/'.$g4['bbs'];

    if ($bo_table)
        $url .= $seo_path.'/board.php?bo_table='.$bo_table;

    if ($wr_id)
        $url .= "&wr_id=".$wr_id;

    if ($qstr == '?') $qstr = '';


    if ($qstr)
        $url .= $qstr;

    if ($mw['config']['cf_seo_url'])
    {
        if (mw_seo_except($bo_table))
            return $url;

        $url = $g4['url'];

        if (!$mobile && $mw_mobile['m_subdomain'])
            $url = preg_replace("/^http:\/\/m\./", "http://", $url);

        $seo_path = '/b/';

        if (($mobile && mw_is_mobile_builder()) or ($mobile == 2))  {
            if ($mw_mobile['m_subdomain'] && !preg_match("/^http:\/\/m\./", $url)) {
                $url = mw_sub_domain_url("m", $url);
            }
            $url.= '/m/';
            $seo_path = 'b/';
        }

        if ($bo_table)
            $url .= $seo_path.$bo_table;

        if ($wr_id)
            $url .= '-'.$wr_id;

        if ($qstr)
            $url .= '?'.$qstr;
    }

    $url = preg_replace("/&page=0(&)/", "$1", $url);
    $url = preg_replace("/&page=0$/", '', $url);
    $url = preg_replace("/&page=1(&)/", "$1", $url);
    $url = preg_replace("/&page=1$/", '', $url);
    //$url = preg_replace("/&page=(&)/", "$1", $url);
    //$url = preg_replace("/&page=$/", '', $url);
    $url = str_replace("?&", '?', $url);
    $url = preg_replace("/\?$/", "", $url);

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


function mw_builder_seo_page($pg_id)
{
    global $g4;
    global $mw;

    $url = $g4['url'].'/page.php?pg_id='.$pg_id;

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

    $wr_id = null;

    if (preg_match("/&wr_id=([0-9]+)&/iUs", $path, $mat)) {
        $wr_id = $mat[1];
        $path = str_replace('&wr_id='.$wr_id, '', $path);
    }

    $path = preg_replace("/&page=[01]?[&$]?/i", '', $path);

    if (mw_is_mobile_builder()) {
        $path = str_replace('../../plugin/mobile/board.php?bo_table='.$bo_table, mw_seo_url($bo_table, $wr_id).'?', $path);
    }
    else {
        $path = str_replace('../bbs/board.php?bo_table='.$bo_table, mw_seo_url($bo_table, $wr_id).'?', $path);
    }


    $path = preg_replace("/\?$/", "", $path);

    return $path;
}

function mw_seo_query()
{
    global $_SERVER;

    $ret = null;
    $rev = array('bo_table', 'wr_id', 'page', 'stx', 'sfl', 'sca', 'sst', 'sod', 'sop', 'spt');

    $qry = explode("&", $_SERVER["QUERY_STRING"]);
    foreach ((array)$qry as $item) {
        $var = explode("=", $item);

        if (!in_array($var[0], $rev)) {
            $ret .= $var[0] . '=' . $var[1];
        }
    }
    $ret = '&'.$ret;

    return $ret;
}

