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

if (!defined('_GNUBOARD_')) exit;

@include_once("$g4[path]/lib/mw.file.lib.php");

function mw_cache_read($cache_file, $cache_time) // 분단위
{
    global $g4;

    if (!is_file($cache_file)) return false;
    if (!$cache_time) return false;

    $diff_time = $g4[server_time] - filemtime($cache_file);

    $cache_time *= 60; 

    if ($diff_time > $cache_time) return false;

    ob_start();
    readfile($cache_file);
    $content = ob_get_contents();
    ob_end_clean();

    $content = base64_decode($content);
    $content = unserialize($content);

    return $content;
}

function mw_cache_write($cache_file, $content)
{
    global $g4;

    $content = serialize($content);
    $content = base64_encode($content);

    mw_make_dirs($cache_file, true);

    $f = fopen($cache_file, "w");
    fwrite($f, $content);
    fclose($f);
}

?>
