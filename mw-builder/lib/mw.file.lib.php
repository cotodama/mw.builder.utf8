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

// -----------------------------------------------------
//  파일 확장자 
// -----------------------------------------------------
function mw_get_file_type($filename)
{
    $rpos = strrpos($filename, ".");
    $type = substr($filename, $rpos, strlen($filename)-$rpos);
    //echo "\n$filename\n$rpos\n$type\n";exit;
    return strtolower($type);
}

// -----------------------------------------------------
//  파일명 
// -----------------------------------------------------

function mw_get_url_filename($url)
{
    $rpos = strrpos($url, "/");
    $filename = substr($url, $rpos, strlen($url)-$rpos);
    //echo "\n$filename\n$rpos\n$type\n";exit;
    return $filename;
}

// -----------------------------------------------------
//  make_dirs("data/file/free");
//  data, file, free 디렉토리를 순차-하위로 생성한다.
// -----------------------------------------------------
function mw_make_dirs($path, $last_is_file=false)
{
    $dir_arr = explode("/", $path);

    unset($path);

    foreach ($dir_arr as $dir)
    {
        $dir = trim($dir);
        if (!$dir) continue;

        if ($last_is_file) {
            if ($dir == $dir_arr[count($dir_arr)-1]) {
                return false;
            }
        }

        if ($path)  {
            $path .= "/".$dir;
        } else {
            $path = $dir;
        }

        if (is_dir($path)) continue;

        @mkdir($path, 0707);
        @chmod($path, 0707);

        // 디렉토리에 있는 파일의 목록을 보이지 않게 한다.
        $file = $path . "/index.php";
        $f = @fopen($file, "w");
        @fwrite($f, "");
        @fclose($f);
        @chmod($file, 0606);
    }
}

// -----------------------------------------------------
//  디렉토리에 존재하는 모든 파일목록(경로)를 얻는다.
// -----------------------------------------------------
function mw_get_dir_filelist($path)
{
    if (!is_dir($path)) return false;

    $files = array();

    if ($handle = opendir($path)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                $files[] = "{$path}/{$file}";
            }
        }
        closedir($handle);
    }
    return $files;
}

?>
