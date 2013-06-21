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

$sub_menu = "110800";
include_once("./_common.php");

auth_check($auth[$sub_menu], "w");

$token = get_token();

$html_title = "팝업창";
if ($w == "") 
{
    $html_title .= " 생성";
    $pp[pp_start] = "$g4[time_ymd] 00:00:00";
    $pp[pp_end] = date("Y-m-d 23:59:59", $g4[server_time]+(86400*7));
    $pp_use = "checked";
    $pp_type_1 = "checked";
    $pp[pp_left] = $pp[pp_top] = 10;
    $pp[pp_width] = 450;
    $pp[pp_height] = 500;
    $pp_time_1 = "checked";
    $pp[pp_center] = "";
} 
else if ($w == "u") 
{
    $html_title .= " 수정";
    $pp = sql_fetch("select * from $mw[popup_table] where pp_id = '$pp_id'");
    $pp_use = $pp_type_1 = $pp_type_2 = "";
    $pp_time_1 = $pp_time_3 = $pp_time_7 = $pp_time_30 = "";
    if ($pp[pp_use]) $pp_use = "checked";
    if ($pp[pp_type] == "1") $pp_type_1 = "checked";
    if ($pp[pp_type] == "2") $pp_type_2 = "checked";
    if ($pp[pp_time] == "1") $pp_time_1 = "checked";
    if ($pp[pp_time] == "3") $pp_time_3 = "checked";
    if ($pp[pp_time] == "7") $pp_time_7 = "checked";
    if ($pp[pp_time] == "30") $pp_time_30 = "checked";
} 
else
    alert("제대로 된 값이 넘어오지 않았습니다.");

sql_query("alter table $mw[popup_table] drop pp_start_time", false);
sql_query("alter table $mw[popup_table] drop pp_end_time", false);
sql_query("alter table $mw[popup_table] change pp_start pp_start datetime not null", false);
sql_query("alter table $mw[popup_table] change pp_end pp_end datetime not null", false);


$g4[title] = $html_title;
include_once("$g4[admin_path]/admin.head.php");

include_once("$g4[path]/lib/cheditor4.lib.php");
echo "<script src='$g4[cheditor4_path]/cheditor.js'></script>";
echo cheditor1('pp_content', '100%', '250');

//==============================================================================
// jquery date picker
//------------------------------------------------------------------------------
// 참고) ie 에서는 년, 월 select box 를 두번씩 클릭해야 하는 오류가 있습니다.
//------------------------------------------------------------------------------
// jquery-ui.css 의 테마를 변경해서 사용할 수 있습니다.
// base, black-tie, blitzer, cupertino, dark-hive, dot-luv, eggplant, excite-bike, flick, hot-sneaks, humanity, le-frog, mint-choc, overcast, pepper-grinder, redmond, smoothness, south-street, start, sunny, swanky-purse, trontastic, ui-darkness, ui-lightness, vader
// 아래 css 는 date picker 의 화면을 맞추는 코드입니다.
?>

<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<style type="text/css">
<!--
.ui-datepicker { font:12px dotum; }
.ui-datepicker select.ui-datepicker-month, 
.ui-datepicker select.ui-datepicker-year { width: 70px;}
.ui-datepicker-trigger { margin:0 0 -5px 2px; }
-->
</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.min.js"></script>
<script type="text/javascript">
/* Korean initialisation for the jQuery calendar extension. */
/* Written by DaeKwon Kang (ncrash.dk@gmail.com). */
jQuery(function($){
	$.datepicker.regional['ko'] = {
		closeText: '닫기',
		prevText: '이전달',
		nextText: '다음달',
		currentText: '오늘',
		monthNames: ['1월(JAN)','2월(FEB)','3월(MAR)','4월(APR)','5월(MAY)','6월(JUN)',
		'7월(JUL)','8월(AUG)','9월(SEP)','10월(OCT)','11월(NOV)','12월(DEC)'],
		monthNamesShort: ['1월','2월','3월','4월','5월','6월',
		'7월','8월','9월','10월','11월','12월'],
		dayNames: ['일','월','화','수','목','금','토'],
		dayNamesShort: ['일','월','화','수','목','금','토'],
		dayNamesMin: ['일','월','화','수','목','금','토'],
		weekHeader: 'Wk',
		dateFormat: 'yy-mm-dd',
		firstDay: 0,
		isRTL: false,
		showMonthAfterYear: true,
		yearSuffix: ''};
	$.datepicker.setDefaults($.datepicker.regional['ko']);

    $('#pp_start').datepicker({
        showOn: 'button',
        buttonImage: '<?=$g4[path]?>/img/calendar.gif',
        buttonImageOnly: true,
        buttonText: "달력",
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        yearRange: 'c-99:c+99'
    }); 
    $('#pp_end').datepicker({
        showOn: 'button',
        buttonImage: '<?=$g4[path]?>/img/calendar.gif',
        buttonImageOnly: true,
        buttonText: "달력",
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        yearRange: 'c-99:c+99'
    }); 
});
</script>
<?
//==============================================================================
?>


<form name=fpopup method=post onsubmit="return fpopup_check(this);" autocomplete="off">
<input type=hidden name=w     value='<?=$w?>'>
<input type=hidden name=sfl   value='<?=$sfl?>'>
<input type=hidden name=stx   value='<?=$stx?>'>
<input type=hidden name=sst   value='<?=$sst?>'>
<input type=hidden name=sod   value='<?=$sod?>'>
<input type=hidden name=page  value='<?=$page?>'>
<input type=hidden name=token value='<?=$token?>'>
<input type=hidden name=pp_id value='<?=$pp_id?>'>
<table width=100% cellpadding=0 cellspacing=0>
<colgroup width=15% class='col1 pad1 bold right'>
<colgroup width=35% class='col2 pad2'>
<colgroup width=15% class='col1 pad1 bold right'>
<colgroup width=35% class='col2 pad2'>
<tr class='ht'>
    <td colspan=4 class=title align=left><img src='<?=$g4[admin_path]?>/img/icon_title.gif'> <?=$html_title?></td>
</tr>
<tr><td colspan=4 class='line1'></td></tr>
<tr class='ht'>
    <td>사용여부</td>
    <td>
	<input type="checkbox" name="pp_use" value="1" <?=$pp_use?>> 사용 
    </td>
    <td>시작일</td>
    <td>
        <input type='text' class=ed name=pp_start id=pp_start maxlength=10 size=10 required itemname='시작일' value='<?=substr($pp[pp_start], 0, 10)?>'>
        <select name="pp_start_time">
        <option value=""></option>
        <? for ($i=0; $i<=23; $i++) { $t = sprintf("%02d", $i); ?>
        <option value="<?=$t?>:00:00"> <?=$t?>시 00분 </option>
        <option value="<?=$t?>:30:00"> <?=$t?>시 30분 </option>
        <? } ?>
        </select>
        <script type="text/javascript"> fpopup.pp_start_time.value = "<?=substr($pp[pp_start], 11, 10)?>"; </script>
    </td>
</tr>
<tr>
    <td>창위치</td>
    <td>
        <input type="radio" name="pp_center" value="" <? if (!$pp[pp_center]) echo "checked"; ?>>지정
        <input type="radio" name="pp_center" value="1" <? if ($pp[pp_center]) echo "checked"; ?>>화면가운데
    </td>
    <td>종료일</td>
    <td>
        <input type='text' class=ed name=pp_end id=pp_end maxlength=10 size=10 required itemname='종료일' value='<?=substr($pp[pp_end], 0, 10)?>'>
        <select name="pp_end_time">
        <option value=""></option>
        <option value="00:00:00"> 00시 00분 </option>
        <? for ($i=0; $i<=23; $i++) { $t = sprintf("%02d", $i); ?>
        <option value="<?=$t?>:30:00"> <?=$t?>시 30분 </option>
        <option value="<?=$t?>:59:59"> <?=$t?>시 59분 </option>
        <? } ?>
        </select>
        <script type="text/javascript"> fpopup.pp_end_time.value = "<?=substr($pp[pp_end], 11, 10)?>"; </script>
    </td>
</tr>
<tr class='ht'>
    <td>창위치 위</td>
    <td>
        <input type='text' class=ed name=pp_top size=3 required itemname='창위치 위' numeric value='<?=$pp[pp_top]?>'> px
    </td>
    <td>창크기 가로</td>
    <td>
        <input type='text' class=ed name=pp_width size=3 required itemname='창크기 가로' numeric value='<?=$pp[pp_width]?>'> px
    </td>
</tr>
<tr class='ht'>
    <td>창위치 왼쪽</td>
    <td>
        <input type='text' class=ed name=pp_left size=3 required itemname='창위치 왼쪽' numeric value='<?=$pp[pp_left]?>'> px
    </td>
    <td>창크기 세로</td>
    <td>
        <input type='text' class=ed name=pp_height size=3 required itemname='창크기 세로' numeric value='<?=$pp[pp_height]?>'> px
    </td>
</tr>
<tr class='ht'>
    <td>팝업 종류</td>
    <td>
	<input type="radio" name="pp_type" value="1" <?=$pp_type_1?>> 레이어
	<input type="radio" name="pp_type" value="2" <?=$pp_type_2?>> 새창 
    </td>
    <td>시간</td>
    <td>
	<input type="radio" name="pp_time" value="1" <?=$pp_time_1?>> 하루 
	<input type="radio" name="pp_time" value="3" <?=$pp_time_3?>> 3일 
	<input type="radio" name="pp_time" value="7" <?=$pp_time_7?>> 7일 
	<input type="radio" name="pp_time" value="30" <?=$pp_time_30?>> 한달 
    </td>
</tr>
<tr class='ht'>
    <td>적용 그룹</td>
    <td>
	<select name="gr_id" id="gr_id" itemname="적용그룹">
	<option value=""> 모두 </option>
	<option value="index"> 인덱스 </option>
	<?
	$sql = "select * from $g4[group_table] order by gr_id";
	$qry = sql_query($sql);
	while ($row = sql_fetch_array($qry)) {
	?>
	<option value="<?=$row[gr_id]?>"> <?=$row[gr_subject]?> </option>
	<? } ?>
	<select>
	<script type="text/javascript"> document.fpopup.gr_id.value = "<?=$pp[gr_id]?>";</script>
    </td>
    <td>적용레벨</td>
    <td>
	<?=get_member_level_select("mb_level", 1, $member[mb_level], $pp[mb_level])?>
    </td>

</tr>
<tr class='ht'>
    <td>제목</td>
    <td colspan=3><input type='text' class=ed name=pp_subject style="width:97%" maxlength=200 required itemname='제목' value='<?=$pp[pp_subject]?>'></td>
</tr>
<tr class='ht'>
    <td>내용</td>
    <td colspan=3>
        <?=cheditor2('pp_content', $pp[pp_content]);?>
	<!--<textarea name="pp_content" rows=10 style="width:100%" required itemname="내용" geditor><?//=$pp[pp_content]?></textarea>-->
    </td>
</tr>
<tr><td colspan=4 class='line2'></td></tr>
</table>

<p align=center>
    <input type=submit class=btn1 accesskey='s' value='  확  인  '>&nbsp;
    <input type=button class=btn1 value='  목  록  ' onclick="document.location.href='<?=$mw[admin_path]?>/mw.popup.php?<?=$qstr?>';">
</form>

<script type="text/javascript">
document.fpopup.pp_subject.focus();

function fpopup_check(f)
{
    <?=cheditor3('pp_content')?>

    f.action = "<?=$mw[admin_path]?>/mw.popup.update.php";
    return true;
}
</script>


<?
include_once ("$g4[admin_path]/admin.tail.php");
