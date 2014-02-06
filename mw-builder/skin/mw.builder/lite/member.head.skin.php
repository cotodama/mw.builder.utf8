<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
?>
<style type="text/css">
.mw_member_logo { float:left; }
.mw_member_process { float:right; margin:20px 0 0 0; }
.mw_member_process div { float:left; }
</style>

<table border="0" cellpadding="0" cellspacing="0" width="624" align="center" style="margin:20px auto 10px auto;">
<tr>
    <td>
        <div class="mw_member_logo"><a href="<?=$g4[path]?>/"><img src="<?=$mw_member_skin_head_path?>/img/mw_logo.gif"></a></div>
        <div class="mw_member_process">
        <? if (!$is_member) { ?>

            <? if (strstr($_SERVER[PHP_SELF], "$g4[bbs]/login.php")) {  // 로그인 창 ?>
                <div><img src="<?=$mw_member_skin_head_path?>/img/member_login.gif"></div>
            <? } ?>

            <? if (strstr($_SERVER[PHP_SELF], "$g4[bbs]/register")) { ?>
                <? if (!strstr($_SERVER[PHP_SELF], "$g4[bbs]/register.php")) { // 회원가입 - 약관동의 ?>
                    <div><img src="<?=$mw_member_skin_head_path?>/img/reg_01.gif"></div>
                <? } else { ?>
                    <div><img src="<?=$mw_member_skin_head_path?>/img/reg_01_on.gif"></div>
                <? } ?>

                <? if (!strstr($_SERVER[PHP_SELF], "$g4[bbs]/register_form.php")) { // 회원가입 - 정보입력 ?>
                    <div><img src="<?=$mw_member_skin_head_path?>/img/reg_02.gif"></div>
                <? } else { ?>
                    <div><img src="<?=$mw_member_skin_head_path?>/img/reg_02_on.gif"></div>
                <? } ?>

                <? if (!strstr($_SERVER[PHP_SELF], "$g4[bbs]/register_result.php")) { // 회원가입 - 가입완료 ?>
                    <div><img src="<?=$mw_member_skin_head_path?>/img/reg_03.gif"></div>
                <? } else { ?>
                    <div><img src="<?=$mw_member_skin_head_path?>/img/reg_03_on.gif"></div>
                <? } ?>
            <? } ?>

        <? } else {?>
            <? if (strstr($_SERVER[PHP_SELF], "$g4[bbs]/member_confirm.php")) { // 패스워드 확인 ?>
                <div><img src="<?=$mw_member_skin_head_path?>/img/member_confirm.gif"></div>
            <? } ?>
            <? if (strstr($_SERVER[PHP_SELF], "$g4[bbs]/register_form.php")) { // 정보수정 ?>
                <div><img src="<?=$mw_member_skin_head_path?>/img/member_modify.gif"></div>
            <? } ?>
        <? } ?>
        </div>
    </td>
</tr>
</table>

<div style="height:5px; background-color:#469ce0; text-align:center; line-height:0; font-size:0;"></div>

<table border="0" cellpadding="0" cellspacing="0" width="624" align="center" style="margin:10px auto 50px auto;">
<tr>
    <td>

