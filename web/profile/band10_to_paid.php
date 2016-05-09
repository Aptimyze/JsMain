<?php
/**********************************************************************************************
  FILENAME    : band10_to_paid.php
  DESCRIPTION : Ask the user to Pay if he is in band 10.
  INCLUDE     : connect.inc
  CREATED BY  : Lavesh Raawt
  CREATED ON  : 25 August,2006
**********************************************************************************************/
                                                                                                                             
include_once("connect.inc");
                                                                                                                             
$db=connect_db();

$data=authenticated($checksum);

$pid=$data['PROFILEID'];

$sql="INSERT IGNORE INTO newjs.BAND10_TO_PAID VALUES('$pid',now())";
mysql_query_decide($sql,$db) or die(mysql_error_js());

$sql="SELECT count(*) as cnt FROM CONTACTS WHERE RECEIVER='$pid' AND TYPE='A'";
$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
$row=mysql_fetch_array($res);

$sql="SELECT count(*) as cnt1 FROM CONTACTS WHERE SENDER='$pid' AND TYPE='A'";
$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
$row1=mysql_fetch_array($res);

$count=$row1['cnt1']+$row['cnt'];

$smarty->assign("data",$data["PROFILEID"]);
$smarty->assign("username",$data["USERNAME"]);
$smarty->assign("count",$count);

$smarty->assign("CHECKSUM",$checksum);
$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
$smarty->display("band10_to_paid.htm");


?>
