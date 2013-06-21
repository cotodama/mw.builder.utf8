<? 
$sub_menu = "110150"; 
include_once("_common.php"); 

auth_check($auth[$sub_menu], "r");

$g4[title] = "버전확인"; 

include_once("$g4[admin_path]/admin.head.php"); 

echo "현재버전 : <b>";
$args = "head -1 ".$g4[path]."/HISTORY-MW-BUILDER"; 
system($args); 
echo "</b>";
?> 

<table width=100% border="0" align="left" cellpadding="0" cellspacing="0"> 
<tr> 
    <td> 
        <textarea name="textarea" style='width:100%; line-height:150%; padding:10px;' rows="25" class=tx readonly><?=implode("", file("$g4[path]/HISTORY-MW-BUILDER"));?></textarea> 
    </td> 
</tr> 
</table> 

<? 
include_once("$g4[admin_path]/admin.tail.php"); 
?> 
