<?php
if (!$dblink) include("_common.php");

$default_skin = $_POST[default_skin];
if (!$default_skin) $default_skin = "mw.basic.3";

$no_board = $_POST[no_board];
if (!$no_board) $no_board = "";

$default_charset = '';
if (preg_match("/^utf/i", $g4[charset]))
    $default_charset = "default charset=utf8;";

@mysql_query("alter table $g4[group_table] add gr_theme varchar(20) not null after gr_use_access");
@mysql_query("alter table $g4[group_table] add gr_home_not char(1) not null after gr_use_access");
@mysql_query("alter table $g4[group_table] add gr_only_admin char(1) not null after gr_use_access");
@mysql_query("alter table $g4[group_table] add gr_use_not char(1) not null after gr_use_access");
@mysql_query("alter table $g4[group_table] add gr_mmove char(1) not null after gr_use_access");
@mysql_query("alter table $g4[group_table] add gr_smove char(1) not null after gr_use_access");
@mysql_query("alter table $g4[group_table] add gr_width int not null default 980 after gr_use_access");
@mysql_query("alter table $g4[group_table] add gr_order int not null after gr_use_access");
@mysql_query("alter table $g4[group_table] add gr_cache int not null default 10 after gr_use_access");
@mysql_query("alter table $g4[group_table] add gr_skin_tail varchar(50) not null default '{$default_skin}' after gr_use_access");
@mysql_query("alter table $g4[group_table] add gr_skin_home varchar(50) not null default '{$default_skin}' after gr_use_access");
@mysql_query("alter table $g4[group_table] add gr_skin_main varchar(50) not null default '{$default_skin}' after gr_use_access");
@mysql_query("alter table $g4[group_table] add gr_skin_head varchar(50) not null default '{$default_skin}' after gr_use_access");
@mysql_query("alter table $g4[group_table] add gr_sub_domain varchar(50) not null after gr_use_access");
@mysql_query("alter table $g4[group_table] add gr_level tinyint not null after gr_theme");
@mysql_query("alter table $g4[group_table] add gr_check varchar(1) not null");
@mysql_query("alter table $g4[group_table] add gr_age varchar(5) not null");
@mysql_query("alter table $g4[group_table] add gr_gender varchar(1) not null");

// 1.0.2 - group_table
@mysql_query("alter table $g4[group_table] add `gr_sitemap_not` char(1) not null default '' after gr_use_not");
@mysql_query("alter table $g4[group_table] add `gr_more` char(1) not null default '' after gr_use_not");
@mysql_query("alter table $g4[group_table] add `gr_more_css` varchar(255) not null default '' after gr_more");
@mysql_query("alter table $g4[group_table] add `gr_url` varchar(255) not null default '' after gr_use_not");

@mysql_query("alter table $g4[group_table] change gr_theme gr_theme varchar(255) not null");

$sql = "create table $mw[popup_table] (
pp_id int not null auto_increment,
gr_id varchar(20) not null,
mb_id varchar(20) not null,
pp_start datetime not null,
pp_end datetime not null,
pp_use char(1) not null,
pp_center varchar(1) not null,
pp_left int not null,
pp_top int not null,
pp_width int not null,
pp_height int not null,
pp_type char(1) not null,
pp_time tinyint not null,
mb_level tinyint not null,
pp_subject varchar(255) not null,
pp_content text not null,
pp_datetime datetime not null,
primary key (pp_id),
index (mb_id),
index (pp_use, pp_start, pp_end)
) $default_charset";
@mysql_query($sql);

@mysql_query("alter table $mw[popup_table] add pp_center varchar(1) not null");

@mysql_query("alter table $mw[popup_table] drop pp_start_time");
@mysql_query("alter table $mw[popup_table] drop pp_end_time");
@mysql_query("alter table $mw[popup_table] change pp_start pp_start datetime not null");
@mysql_query("alter table $mw[popup_table] change pp_end pp_end datetime not null");

$sql = "create table $mw[page_table] (
pg_id int not null auto_increment,
pg_subject varchar(255) not null,
pg_content text not null,
pg_datetime datetime not null,
primary key (pg_id)
) $default_charset";
@mysql_query($sql);

$sql = "create table $mw[menu_middle_table] (
gr_id varchar(20) not null,
mm_id int not null auto_increment,
mm_name varchar(50) not null,
mm_left int not null,
mm_smenu char(1) not null,
mm_smove char(1) not null,
mm_lmenu char(1) not null,
mm_rmenu char(1) not null,
mm_skin varchar(50) not null default '{$default_skin}',
mm_order tinyint not null,
primary key (mm_id),
index (gr_id)
) $default_charset";
@mysql_query($sql);

// 1.0.2 - menu_middle_table
@mysql_query("alter table $mw[menu_middle_table] add `mm_only_admin` char(1)  not null default '' after mm_name");

// 1.0.3 - menu_middle_table
@mysql_query("alter table $mw[menu_middle_table] add `mm_out_url` varchar(255)  not null default ''");
@mysql_query("alter table $mw[menu_middle_table] add `mm_css` varchar(255)  not null default ''");

// 1.1.1
@mysql_query("alter table $mw[menu_middle_table] add `mm_level` tinyint  not null");

@mysql_query("alter table $mw[menu_middle_table] add `mm_check` varchar(1)  not null");
@mysql_query("alter table $mw[menu_middle_table] add `mm_gender` varchar(1)  not null");
@mysql_query("alter table $mw[menu_middle_table] add `mm_age` varchar(5)  not null");

$sql = "create table $mw[menu_small_table] (
mm_id varchar(20) not null,
ms_id int not null auto_increment,
ms_name varchar(50) not null,
bo_table varchar(20) not null,
ms_url varchar(255) not null,
ms_order tinyint not null,
primary key (ms_id),
index (mm_id)
) $default_charset";
@mysql_query($sql);

// 1.0.2 - menu_small_table
@mysql_query("alter table $mw[menu_small_table] add `ca_name` varchar(50) not null default '' after bo_table");
@mysql_query("alter table $mw[menu_small_table] add `ms_only_admin` char(1)  not null default '' after ms_name");

// 1.0.3 - menu_small_table
@mysql_query("alter table $mw[menu_small_table] add `ms_out_url` varchar(255)  not null default ''");
@mysql_query("alter table $mw[menu_small_table] add `ms_css` varchar(255)  not null default ''");
@mysql_query("alter table $mw[menu_small_table] add `ms_target` varchar(20)  not null default ''");

// 1.0.7 - menu_small_table
@mysql_query("alter table $mw[menu_small_table] add `ms_lmenu` varchar(1)  not null default ''");
@mysql_query("alter table $mw[menu_small_table] add `ms_rmenu` varchar(1)  not null default ''");

// 1.1.1
@mysql_query("alter table $mw[menu_small_table] add `ms_level` tinyint  not null");
@mysql_query("alter table $mw[menu_small_table] add `pg_id` int  not null");
 
@mysql_query("alter table $mw[menu_small_table] add `ms_check` varchar(1)  not null");
@mysql_query("alter table $mw[menu_small_table] add `ms_gender` varchar(1)  not null");
@mysql_query("alter table $mw[menu_small_table] add `ms_age` varchar(5)  not null");

/*$sql = "create table $mw[session_table] (
id varchar(32) not null,
ss_datetime datetime not null,
ss_data text not null,
mb_id varchar(20) not null,
ss_ip varchar(20) not null,
primary key  (id),
key ss_datetime (ss_datetime),
key mb_id (mb_id, ss_ip)
);";
@mysql_query($sql);*/

$sql = "create table $mw[board_visit_table] (
bv_date date not null,
gr_id varchar(20) not null,
bo_table varchar(20) not null,
bv_count int(11) not null,
primary key  (bv_date,gr_id,bo_table)
) $default_charset";
@mysql_query($sql);

$sql = "create table $mw[board_visit_log_table] (
log varchar(255) not null,
primary key  (log)
) $default_charset";
@mysql_query($sql);

$sql = "create table $mw[config_table] (
cf_www char(1) not null default ''
,cf_overlogin char(1) not null default ''
,cf_index_skin_head varchar(50) not null default '{$default_skin}'
,cf_index_skin_main varchar(50) not null default '{$default_skin}'
,cf_index_skin_tail varchar(50) not null default '{$default_skin}'
,cf_index_cache int not null default 10 
,cf_index_width int not null default 800
,cf_member_skin_head varchar(50) not null default '{$default_skin}'
,cf_member_skin_tail varchar(50) not null default '{$default_skin}'
,cf_default_group varchar(50) not null default ''
) $default_charset";
@mysql_query($sql);
$row = @mysql_fetch_array(mysql_query("select * from $mw[config_table]"));
if (!$row)
    @mysql_query("insert into $mw[config_table] values()");

@mysql_query("alter table $mw[config_table] add cf_member char(1) not null default '' after cf_www");
@mysql_query("alter table $mw[config_table] add cf_popular_cache int not null default '60' after cf_overlogin");
@mysql_query("alter table $mw[config_table] add cf_default_group varchar(50) not null default ''");

// 1.0.2 - config_table
@mysql_query("alter table $mw[config_table] change `cf_www` `cf_www` char(1) not null default ''");

@mysql_query("alter table $mw[config_table] add `cf_sub_domain_off` varchar(1) not null default ''");

@mysql_query("alter table $g4[board_table] change `bo_count_delete` `bo_count_delete` tinyint not null default '1'");
@mysql_query("alter table $g4[board_table] change `bo_count_modify` `bo_count_modify` tinyint not null default '1'");
@mysql_query("alter table $g4[board_table] change `bo_gallery_cols` `bo_gallery_cols` int not null default '4'");
@mysql_query("alter table $g4[board_table] change `bo_table_width` `bo_table_width` int not null default '100'");
@mysql_query("alter table $g4[board_table] change `bo_page_rows` `bo_page_rows` int not null default '20'");
@mysql_query("alter table $g4[board_table] change `bo_subject_len` `bo_subject_len` int not null default '60'");
@mysql_query("alter table $g4[board_table] change `bo_new` `bo_new` int not null default '24'");
@mysql_query("alter table $g4[board_table] change `bo_hot` `bo_hot` int not null default '100'");
@mysql_query("alter table $g4[board_table] change `bo_image_width` `bo_image_width` int not null default '600'");
@mysql_query("alter table $g4[board_table] change `bo_upload_count` `bo_upload_count` tinyint not null default '600'");
@mysql_query("alter table $g4[board_table] change `bo_upload_size` `bo_upload_size` int not null default '1048576'");
@mysql_query("alter table $g4[board_table] change `bo_reply_order` `bo_reply_order` tinyint not null default '1'");
@mysql_query("alter table $g4[board_table] change `bo_use_search` `bo_use_search` tinyint not null default '1'");
@mysql_query("alter table $g4[board_table] change `bo_skin` `bo_skin` varchar(255) not null default 'mw.basic'");
@mysql_query("alter table $g4[board_table] change `bo_disable_tags` `bo_disable_tags` varchar(255) not null default 'script|iframe'");
@mysql_query("alter table $g4[board_table] change `bo_use_secret` `bo_use_secret` tinyint not null default '1'");
@mysql_query("alter table $g4[board_table] change `bo_include_head` `bo_include_head` varchar(255) not null default '_head.php'");
@mysql_query("alter table $g4[board_table] change `bo_include_tail` `bo_include_tail` varchar(255) not null default '_tail.php'");

if (!$dblink && !$is_admin) {
    echo "완료.";
}
else if ($dblink && !$no_board) {
    $g4[path] = "..";

    $g4['url'] = 'http://' . $_SERVER['HTTP_HOST'];
    $dir = dirname($_SERVER["PHP_SELF"]);
    if (!file_exists("config.php"))
	$dir = dirname($dir);
    $g4['url'] .= $dir;
    $path = $dir;
    
    $path = "";

    // \ 를 / 롤 변경
    $g4['url'] = strtr($g4['url'], "\\", "/");

    // url 의 끝에 있는 / 를 삭제한다.
    $g4['url'] = preg_replace("/\/$/", "", $g4['url']);

    // 메뉴 자동 생성
    @mysql_query("insert into $g4[group_table] set gr_id = 'G01', gr_subject = 'G01', gr_theme = 'mw.blue', gr_order = 1");
    @mysql_query("insert into $g4[group_table] set gr_id = 'G02', gr_subject = 'G02', gr_theme = 'mw.brown', gr_order = 2");
    @mysql_query("insert into $g4[group_table] set gr_id = 'G03', gr_subject = 'G03', gr_theme = 'mw.green', gr_order = 3");
    @mysql_query("insert into $g4[group_table] set gr_id = 'G04', gr_subject = 'G04', gr_theme = 'mw.red', gr_order = 4");
    @mysql_query("insert into $g4[group_table] set gr_id = 'G05', gr_subject = 'G05', gr_theme = 'mw.violet', gr_order = 5");
    @mysql_query("insert into $g4[group_table] set gr_id = 'help', gr_subject = '이용안내', gr_theme = 'mw.black', gr_order = 6");

    $sql = "select * from $mw[menu_middle_table] where gr_id = 'G01'";
    $qry = mysql_query($sql);
    $row = mysql_fetch_array($qry);
    if (!$row) {
	$mi = $si = $bi = 1;
	for ($i=1; $i<6; $i++) {
	    $gr_id = "G".sprintf("%02d", $i);
	    for ($j=1; $j<6; $j++) {
		$mm_name = "M".sprintf("%02d", $mi++);
		$mm_left = ($j-1) * 70;
		@mysql_query("insert into $mw[menu_middle_table] set gr_id = '$gr_id', mm_name = '$mm_name', mm_left = '$mm_left'");
		$mm_id = mysql_insert_id();
		for ($n=1; $n<4; $n++) {
		    $ms_name = "S".sprintf("%02d", $si++);
		    $bo_table = $bo_subject = "B".sprintf("%02d", $bi++);

		    @mysql_query("insert into $mw[menu_small_table] set mm_id = '$mm_id', ms_name = '$ms_name', bo_table = '$bo_table', ms_order = '$n'");
		    @mysql_query("insert into $g4[board_table] set gr_id = '$gr_id', bo_table = '$bo_table', bo_subject = '$bo_subject'");

		    $file = file("../adm/sql_write.sql");
		    $sql = implode($file, "\n");

		    $create_table = $g4[write_prefix] . $bo_table;

		    $source = array("/__TABLE_NAME__/", "/;/");
		    $target = array($create_table, "");
		    $sql = preg_replace($source, $target, $sql);
		    @mysql_query($sql);

                    $board_path = "$g4[path]/data/file/$bo_table";

                    // 게시판 디렉토리 생성
                    @mkdir($board_path, 0707);
                    @chmod($board_path, 0707);

                    // 디렉토리에 있는 파일의 목록을 보이지 않게 한다.
                    $file = $board_path . "/index.php";
                    $f = @fopen($file, "w");
                    @fwrite($f, "");
                    @fclose($f);
                    @chmod($file, 0606);
		}
	    }
	}
	// 공지사항
	@mysql_query("insert into $mw[menu_middle_table] set gr_id = 'help', mm_name = '새소식', mm_smove = '1'");
	$mm_id = mysql_insert_id();

	@mysql_query("insert into $mw[menu_small_table] set mm_id = '$mm_id', ms_name = '새소식', bo_table = 'notice'");
	@mysql_query("insert into $g4[board_table] set gr_id = 'help', bo_table = 'notice', bo_subject = '새소식'");

	$file = file("../adm/sql_write.sql");
	$sql = implode($file, "\n");

	$create_table = $g4[write_prefix] . "notice";

	$source = array("/__TABLE_NAME__/", "/;/");
	$target = array($create_table, "");
	$sql = preg_replace($source, $target, $sql);
	@mysql_query($sql);

	@mysql_query("insert into $mw[menu_middle_table] set gr_id = 'help', mm_name = '회사소개', mm_smove = '1'");
	$mm_id = mysql_insert_id();
	@mysql_query("insert into $mw[menu_small_table] set mm_id = '$mm_id', ms_name = '회사소개', ms_url = '$path/page/help/company.php'");

	@mysql_query("insert into $mw[menu_middle_table] set gr_id = 'help', mm_name = '정책 및 운영', mm_smove = '1'");
	$mm_id = mysql_insert_id();
	@mysql_query("insert into $mw[menu_small_table] set mm_id = '$mm_id', ms_name = '이용약관', ms_url = '$path/page/help/stipulation.php'");
	@mysql_query("insert into $mw[menu_small_table] set mm_id = '$mm_id', ms_name = '개인정보 취급방침', ms_url = '$path/page/help/privacy.php'");
	@mysql_query("insert into $mw[menu_small_table] set mm_id = '$mm_id', ms_name = '책임의한계와 법적고지', ms_url = '$path/page/help/disclaimer.php'");
	@mysql_query("insert into $mw[menu_small_table] set mm_id = '$mm_id', ms_name = '이메일주소 수집거부', ms_url = '$path/page/help/rejection.php'");
	@mysql_query("insert into $mw[menu_small_table] set mm_id = '$mm_id', ms_name = '포인트정책', ms_url = '$path/page/help/point_policy.php'");

	@mysql_query("insert into $mw[menu_middle_table] set gr_id = 'help', mm_name = '최신글', mm_smove = '1'");
	$mm_id = mysql_insert_id();
	@mysql_query("insert into $mw[menu_small_table] set mm_id = '$mm_id', ms_name = '최신글', ms_url = '$path/bbs/new.php'");

	@mysql_query("insert into $mw[menu_middle_table] set gr_id = 'help', mm_name = '게시물 검색', mm_smove = '1'");
	$mm_id = mysql_insert_id();
	@mysql_query("insert into $mw[menu_small_table] set mm_id = '$mm_id', ms_name = '게시물 검색', ms_url = '$path/bbs/search.php'");

	@mysql_query("insert into $mw[menu_middle_table] set gr_id = 'help', mm_name = '포인트순위 100', mm_smove = '1'");
	$mm_id = mysql_insert_id();
echo $ms_id . "<br/";
	@mysql_query("insert into $mw[menu_small_table] set mm_id = '$mm_id', ms_name = '포인트순위', ms_url = '$path/page/help/point_ranking.php'");

	@mysql_query("insert into $mw[menu_middle_table] set gr_id = 'help', mm_name = '현재접속자', mm_smove = '1'");
	$mm_id = mysql_insert_id();
	@mysql_query("insert into $mw[menu_small_table] set mm_id = '$mm_id', ms_name = '현재접속자', ms_url = '$path/bbs/current_connect.php'");
     
       for ($i=1; $i<11; $i++) { 
	    $rank = sprintf("%02d", $i);
	    @mysql_query("insert into $g4[popular_table] set pp_word = '인기검색어-$rank', pp_date = '$g4[time_ymd]', pp_ip = ''");
	}

	@mysql_query("insert into $g4[poll_table] set po_subject = '지금 막 새로 설치한 배추빌더 어때요?', po_poll1 = '좋아요', po_poll2 = '멋져요', po_poll3 = '이뻐요', po_poll4 = '상큼해요', po_point = '1000', po_date = '$g4[time_ymd]', po_level = '1'");
	@mysql_query(" update $g4[config_table] set cf_max_po_id = '1' ");

    }

    include_once("$g4[path]/lib/common.lib.php");
    include_once("$g4[path]/adm/mw.builder/mw.menus.lib.php");

    $sql = "select gr_id from $g4[group_table] order by gr_order";
    $qry = sql_query($sql);
    while ($row = sql_fetch_array($qry)) {
	mw_set_group($row[gr_id]);
	mw_set_middle_menus($row[gr_id]);
	mw_set_middle_menus_admin($row[gr_id]);
    }
    mw_set_groups();
    mw_set_groups_admin();

    $sql = "select mm_id from $mw[menu_middle_table] where mm_id <> '' order by gr_id, mm_order";
    $qry = sql_query($sql);
    while ($row = sql_fetch_array($qry)) {
	mw_set_middle_menu($row[mm_id]);
	mw_set_small_menus($row[mm_id]);
	mw_set_small_menus_admin($row[mm_id]);
    }

    $sql = "select ms_id from $mw[menu_small_table] order by mm_id, ms_order";
    $qry = sql_query($sql);
    while ($row = sql_fetch_array($qry)) {
	mw_set_small_menu($row[ms_id]);
    }
}
