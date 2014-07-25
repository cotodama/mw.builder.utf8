<?php
$g4_path = "../.."; // common.php 의 상대 경로
include_once ("$g4_path/common.php");
include_once("$g4[admin_path]/admin.lib.php");
include_once("$g4[path]/lib/mw.common.lib.php");

header("Content-Type: text/html; charset=$g4[charset]");

@extract($_GET);
@extract($_POST);
@extract($_SERVER);

