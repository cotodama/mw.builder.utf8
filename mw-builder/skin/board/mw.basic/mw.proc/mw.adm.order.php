<?php
/**
 * Bechu-Basic Skin for Gnuboard4
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

include_once("_common.php");
header("Content-Type: text/html; charset=$g4[charset]");

if ($is_admin != 'super')
    die("로그인 해주세요.");

if (!$bo_table)
    die("bo_table 값이 없습니다.");

if (!$token or get_session("ss_config_token") != $token) 
    die("토큰 에러로 실행 불가합니다.");

$data = array();

$sql_order = "";
switch ($item) {
    case "date" :
        $sql_order = 'wr_datetime';
        if ($order == 'desc')
            $sql_order .= ' desc ';
        else
            $sql_order .= ' asc ';
        break;
    case "subject" :
        $sql_order = 'wr_subject';
        if ($order == 'desc')
            $sql_order .= ' asc ';
        else
            $sql_order .= ' desc ';
        break;
}

$sql = "select wr_id, wr_num from {$write_table} where wr_is_comment = 0 and wr_reply = '' order by $sql_order";
$qry = sql_query($sql);
while ($row = sql_fetch_array($qry)) {
    $data[] = $row;
}

sql_query("update {$write_table} set wr_num = wr_num * -1");
$wr_num = 0;
foreach ($data as $row) {
    $wr_num--;
    $row[wr_num] *= -1;

    $sql = "update {$write_table} set wr_num = '{$wr_num}' where wr_num = '{$row[wr_num]}'";
    sql_query($sql);
}

echo "정렬을 완료하였습니다.";
