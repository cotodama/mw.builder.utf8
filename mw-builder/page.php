<?
include_once("_common.php");
include_once("$g4[path]/lib/mw.builder.lib.php");

if (!$pg_id)
    alert("pg_id 가 없습니다.");

$pg = sql_fetch("select * from $mw[page_table] where pg_id = '$pg_id'");
if (!$pg)
    alert("데이터가 없습니다.");

$g4[title] = $pg[pg_subject];
include_once("$g4[path]/head.php");

//echo conv_content($pg[pg_content], 1);
echo $pg[pg_content];

include_once("$g4[path]/tail.php");
?>
