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

@include_once("$g4[path]/lib/mw.cache.lib.php");

// 인기검색어 출력
// $skin_dir : 스킨 디렉토리
// $pop_cnt : 검색어 몇개
// $date_cnt : 몇일 동안
function mw_popular($skin_dir='basic', $pop_cnt=7, $date_cnt=3, $minute=0)
{
    global $config, $g4;

    if (!$skin_dir) $skin_dir = 'basic';

    $cache_file_list = "$g4[path]/data/mw.cache/popular-{$g4[time_ymd]}-{$pop_cnt}-{$date_cnt}";
    $list = mw_cache_read($cache_file_list, $minute);

    if (!$list) {
	$date_gap = date("Y-m-d", $g4[server_time] - ($date_cnt * 86400));
	$date_gap_old = date("Y-m-d", strtotime($date_gap) - ($date_cnt * 86400));
	$sql = " select pp_word, count(*) as cnt from $g4[popular_table]
		  where pp_date between '$date_gap' and '$g4[time_ymd]'
                    and pp_word not in (select mb_id from $g4[member_table])
                    and trim(pp_word) <> ''
		  group by pp_word
		  order by cnt desc, pp_word
		  limit 0, $pop_cnt ";
	$result = sql_query($sql, false);
        if (!$result) {
            $sql = " select pp_word, count(*) as cnt from $g4[popular_table]
                      where pp_date between '$date_gap' and '$g4[time_ymd]'
                        and trim(pp_word) <> ''
                      group by pp_word
                      order by cnt desc, pp_word
                      limit 0, $pop_cnt ";
            $result = sql_query($sql);
        }

	$old = array();
	$sql2 = " select pp_word, count(*) as cnt from $g4[popular_table]
		  where pp_date between '$date_gap_old' and '$date_gap'
		  group by pp_word
		  order by cnt desc, pp_word
		  limit 0, 100 ";
	$qry2 = sql_query($sql2);
	$count = mysql_num_rows($qry2);
	for ($j=0; $row2=sql_fetch_array($qry2); $j++) {
	    $old[$j] = $row2;
	    $old[$old[$j][pp_word]] = $j;
	}

	for ($i=0; $row=sql_fetch_array($result); $i++) 
	{
	    /*for ($j=0; $j<$count; $j++) {
		if ($old[$j][pp_word] == $row[pp_word]) {
		    break;
		}
	    }*/
	    $j = $old[$row[pp_word]];
	    if (!$j || !is_numeric($j)) $j = 0;

	    $list[$i] = $row;
	    $list[$i][pp_word] = get_text($list[$i][pp_word]); // 스크립트등의 실행금지
	    $list[$i][pp_word] = urldecode($list[$i][pp_word]);
	    $list[$i][pp_rank] = $i + 1;
	    if ($count == $j) {
		$list[$i][old_pp_rank] = 0;
		$list[$i][rank_gap] = 0;
	    } else {
		$list[$i][old_pp_rank] = $j + 1;
		$list[$i][rank_gap] = $list[$i][old_pp_rank] - $list[$i][pp_rank];
	    }
	    if ($list[$i][rank_gap] > 0)
		$list[$i][icon] = "up";
	    else if ($list[$i][rank_gap] < 0)
		$list[$i][icon] = "down";
	    else if ($list[$i][old_pp_rank] == 0)
		$list[$i][icon] = "new";
	    else if ($list[$i][rank_gap] == 0)
		$list[$i][icon] = "nogap";
	}
	mw_cache_write($cache_file_list, $list);
    }
    
    ob_start();
    $popular_skin_path = "$g4[path]/skin/popular/$skin_dir";
    include_once ("$popular_skin_path/popular.skin.php");
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}
?>
