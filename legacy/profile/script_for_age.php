<?php

include("connect.inc");
connect_db();
//adding mailing to gmail account to check if file is being used
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
               $cc='eshajain88@gmail.com';
               $to='sanyam1204@gmail.com';
               $msg1='script_for_age is being hit. We can wrap this to JProfileUpdateLib';
               $subject="script_for_age";
               $msg=$msg1.print_r($_SERVER,true);
               send_email($to,$msg,$subject,"",$cc);
 //ending mail part
$sql="SELECT DTOFBIRTH,PROFILEID FROM newjs.JPROFILE ORDER BY PROFILEID ASC LIMIT 200000,30000";
$res=mysql_query_decide($sql) or die(mysql_error_js());
if($row=mysql_fetch_array($res))
{
	do
	{
		$dob=$row['DTOFBIRTH'];
		$profileid=$row['PROFILEID'];
		$getage=getAge($dob);

		$sql="UPDATE newjs.JPROFILE SET AGE='$getage' WHERE PROFILEID='$profileid'";
		mysql_query_decide($sql) or die(mysql_error_js());
	}while($row=mysql_fetch_array($res));
}
?>
