<?php
include("connect.inc");
$db=connect_db();

if ($_GET['op'] == 'username')
{
  $query = "SELECT USERNAME FROM newjs.JPROFILE where USERNAME='".mysql_escape_string($_GET['value'])."'";
  $result = mysql_query_decide($query);
  $num_rows = mysql_num_rows($result);
	if ($num_rows == 0)
	{
		$msg = 'available';
		$class = "green";
  } else {
    $msg = 'Not Available';
    $class = "blue";
  }
}
else if($_GET['op'] == 'emailid')
{
	//$query = "SELECT EMAIL FROM newjs.JPROFILE where EMAIL='".mysql_escape_string($_GET['value'])."'";
  //$result = mysql_query_decide($query);
  //$num_rows = mysql_num_rows($result);
	$Email=$_GET['value'];
	$check_email=checkemail($Email);
	$check_old_email=checkoldemail($Email);
	if($check_email || $check_old_email)
	{
		$msg = 'This Emailid is already present in our database,enter another emailid';
                $class = "red";
	}
	else
	{
		$msg = '';
		$class = "green";
	}
}
//die();
 echo "<?xml version='1.0' encoding='UTF-8'?><SPAN class=$class>$msg</SPAN>";
?>
