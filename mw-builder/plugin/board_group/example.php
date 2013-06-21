<?
$g4_path = ".";
include_once("$g4_path/common.php");


// 사용할 곳에 필수 include
include_once("$g4[path]/plugin/board_group/lib.php");
include_once("$g4[path]/lib/mw.latest.lib.php");

/*
    사용법 : echo mw_board_group("왼쪽타이틀", "오른쪽타이틀", 전환시간(초), array(

    "B01" : 게시판 ID
    "skin" => "mw.board.group.imag" : 이미지 최근 게시물 스킨
    "skin" => "mw.board.group.list : 일반 최근 게시물 스킨
    "rows" : 게시물 출력갯수
    "length" : 제목 길이
    "image_count" : 이미지 아닌 일반 최근게시물 스킨에서 왼쪽 이미지 출력 여부
*/
?>

<div>
<?
echo mw_board_group("바꿔주삼", "어서오시렵니까?^^", 5, array(
    "B01"   => array("skin" => "mw.board.group.image",      "rows" => 4, "length" => 30, "image_count" => 1),
    "B02"   => array("skin" => "mw.board.group.image",      "rows" => 4, "length" => 30, "image_count" => 1),
    "B03"   => array("skin" => "mw.board.group.list",       "rows" => 5, "length" => 35, "image_count" => 1),
    "B04"   => array("skin" => "mw.board.group.list",       "rows" => 5, "length" => 35, "image_count" => 1),
    "B05"   => array("skin" => "mw.board.group.image",      "rows" => 4, "length" => 30, "image_count" => 1),
    "B06"   => array("skin" => "mw.board.group.list",       "rows" => 5, "length" => 35, "image_count" => 1),
));
?>
</div>


