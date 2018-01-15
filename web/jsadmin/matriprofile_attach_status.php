<?php
include("connect.inc");
//$db=connect_master();
$sql = "select PROFILEID from billing.MATRI_PROFILE where PROFILEID = '$id' and STATUS='Y'";
$result = mysql_query_decide($sql);
$exec=getname($checksum);
$sql_e="SELECT EMAIL FROM jsadmin.PSWRDS WHERE USERNAME='$exec'";
$res_e=mysql_query_decide($sql_e) or die("error".mysql_error_js());
$row_e=mysql_fetch_array($res_e);
$smarty->assign("exec_email",$row_e['EMAIL']);
$smarty->assign("id",$id);
$smarty->assign("username",$username);
$smarty->assign("status",$status);
$smarty->assign("mid",$mid);
$smarty->assign("checksum",$checksum);
$smarty->display("matriprofile_attach_status.htm");
?>

