<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$admin = get_member($config[cf_admin], "mb_email");
$email = mw_nobot_slice($admin[mb_email]);
?>

<style type="text/css">
.mw-banner { height:30px; margin:10px 0 0 0; text-align:center; }
.mw-banner span { margin:0 5px 0 5px; }
#mw-site-info { clear:both; text-align:center; margin:0 0 20px 0; padding:10px; color:#555; font-size:8pt; letter-spacing:-1px; -webkit-text-size-adjust:none; }
#mw-site-info .menu { color:#ddd; line-height:25px; }
#mw-site-info .menu a { color:#555; font-size:8pt; letter-spacing:-1px; }
#mw-site-info .d { color:#ddd; margin:0 2px 0 2px; }
#mw-site-info a.site { color:#3173B6; font-size:8pt; letter-spacing:-1px; }
#mw-site-info a:hover { text-decoration:underline; }
</style>

<div class="mw-banner">
<span><a href="http://www.miwit.com" target=_blank><img src="<?=$mw_index_skin_tail_path?>/img/b1.gif" alt="miwit.com"></a></span>
<span><a href="http://www.sir.co.kr" target=_blank><img src="<?=$mw_index_skin_tail_path?>/img/b2.gif" alt="sir.co.kr"></a></span>
<span><a href="http://www.dnsever.com" target="_blank"><img src="<?=$mw_index_skin_tail_path?>/img/dnsever-banner.gif" alt="DNS Powered by DNSEver.com"></a></span>
<span><a href="#" target=_blank><img src="<?=$mw_index_skin_tail_path?>/img/banner-tail.gif"></a></span>
<span><a href="#" target=_blank><img src="<?=$mw_index_skin_tail_path?>/img/banner-tail.gif"></a></span>
</div>

<div id="mw-site-info">
<div class="menu">
<a href="#">회사소개</a>
<span class="d">|</span> <a href="#">이용약관</a>
<span class="d">|</span> <a href="#">개인정보취급방침</a>
<span class="d">|</span> <a href="#">책임의한계와 법적고지</a>
<span class="d">|</span> <a href="#">이메일무단수집거부</a>
<span class="d">|</span> <a href="#">이용안내</a>
</div>
Copyright ⓒ <a href="<?=mw_sub_domain_url("www", $g4[url])?>" class="site">www<?=$g4[cookie_domain]?></a>.
All rights reserved.
<? //echo "contact: $email"; ?>
</div>

<?php
// 팝업창 
if (_MW_INDEX_ && !is_member_page()) echo mw_popup();
?>

<?php
if ((strstr(strtolower($_SERVER['HTTP_USER_AGENT']), "mobile") and file_exists($g4['path']."/extend/mw.mobile.extend.php"))) {
    echo "<div style='margin:0; padding:30px; text-align:center; border-top:1px solid #ccc;''>";
    $mobile_link = mw_seo_url($bo_table, $wr_id, '', 2);
    /*$mobile_link = "$g4[path]/plugin/mobile";
    if ($bo_table) {
        $mobile_link .= "/board.php?bo_table=".$bo_table;
        if ($wr_id)
            $mobile_link .= "&wr_id=".$wr_id;
    }*/
    echo "<a href='{$mobile_link}' style='font:bold 60px gulim;'>모바일 웹으로 보기</a>";
    echo "</div>";
}
