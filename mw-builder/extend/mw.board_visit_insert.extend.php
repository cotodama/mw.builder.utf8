<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

if ($gr_id && $bo_table)
{
    $board_visit = "$g4[time_ymd].$gr_id.$bo_table.$_SERVER[REMOTE_ADDR]";

    $qry = sql_query("insert into $mw[board_visit_log_table] set log = '$board_visit'", false);
    if ($qry) {
        $sql = " update $mw[board_visit_table] set bv_count = bv_count + 1 where bv_date = '$g4[time_ymd]' and gr_id = '$gr_id' and bo_table = '$bo_table' ";
        $qry = sql_query($sql, false);

        // 수정된 row가 하나도 없다면(업데이트가 안되는 오류) 새로운 날짜별 행을 생성하도록 insert를 실행
        if (mysql_affected_rows() == 0) {
            $sql = " insert $mw[board_visit_table] set bv_date = '$g4[time_ymd]', gr_id = '$gr_id', bo_table = '$bo_table', bv_count = 1 ";
            $qry = sql_query($sql, false);
        }
    }
    unset($board_visit);
}

?>
