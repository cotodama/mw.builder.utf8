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

// 19+ : 19세 이상
// 19- : 19세 미만 
// 19= : 19세만 
function mw_age($value)
{
    global $g4, $member;

    if (!$member[mb_birth])
        $member_age = 0;
    else
        $member_age = floor((date("Ymd", $g4[server_time]) - $member[mb_birth]) / 10000);

    preg_match("/^([0-9]+)([\+\-\=])$/", $value, $match);
    $age = $match[1];
    $age_type = $match[2];

    switch ($age_type) {
        case "+" :
            if ($member_age < $age) alert("나이 {$age}세 이상만 접근 가능합니다.");
            break;
        case "-" :
            if ($member_age >= $age) alert("나이 {$age}세 미만만 접근 가능합니다.");
            break;
        case "=" :
            if ($member_age != $age) alert("나이 {$age}세만 접근 가능합니다.");
            break;
    }
}

