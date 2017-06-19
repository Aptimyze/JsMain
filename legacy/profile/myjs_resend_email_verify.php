<?php

include("connect.inc");
include_once("vishwas_seal_functions.php");
$_SERVER['ajax_error']=1;
$db=connect_db();
$data=authenticated($checksum);
$profileid=$data["PROFILEID"];
/*
$sql1="SELECT USERNAME,PASSWORD,EMAIL FROM JPROFILE WHERE  activatedKey=1 and PROFILEID=$profileid";
$res1=mysql_query_decide($sql1); 
$row1=@mysql_fetch_array($res1);
$username=$row1["USERNAME"];
$password=$row1["PASSWORD"];
$email=$row1["EMAIL"];
*/
$email=send_verify_email($profileid,1);
if($email)
	echo $email;
else
	echo 'N';
exit;
?>
