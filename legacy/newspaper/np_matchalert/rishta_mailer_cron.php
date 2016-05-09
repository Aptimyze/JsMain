<?php
                                                                                                                             
/*********************************************************************************************
* FILE NAME     : rishta_mailer.php 
* DESCRIPTION   : Selects email of Rishta.com user and sends mail
* CREATION DATE : 24 june, 2005
* CREATED BY    : Aman Sharma
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
	include "connect.inc";

	$sql_up1="update MMM.RISHTA_EMAILS as t1,newjs.JPROFILE as t2 set t1.DONT_DELIVER='Y' where t1.EMAIL=t2.EMAIL";
	$result= mysql_query($sql_up1) or logerror1("In checking with JPROFILE email","");
	
	$sql_up2="update MMM.RISHTA_EMAILS as t1,newjs.OLDEMAIL as t2 set t1.DONT_DELIVER='Y' where t1.EMAIL=t2.OLD_EMAIL";
        $result= mysql_query($sql_up2) or logerror1("In checking with OLDEMAIL email","");

	// changes made to query to update unsubscribe from table UNSUBSCRIBE
	//$sql_up3 = "UPDATE MMM.RISHTA_EMAILS a LEFT JOIN jsadmin.UNSUBSCRIBE u ON a.EMAIL = u.EMAIL SET DONT_DELIVER='Y' WHERE a.EMAIL IS NOT NULL AND u.SOURCE='R'";

	$sql_up3="update MMM.RISHTA_EMAILS set DONT_DELIVER='Y' where UNSUBSCRIBE='Y'";
	$result= mysql_query($sql_up3) or logerror1("In checking with UNSUBSCRIBE","");
	
	$sql_up4="update MMM.RISHTA_EMAILS set SENT='',SENT_TIME=''";
        $result= mysql_query($sql_up4) or logerror1("In checking with SENT","");


	$sql_res="select EMAIL from MMM.RISHTA_EMAILS where DONT_DELIVER=''";
        $result= mysql_query($sql_res) or logerror1("In rishta at selecting email","");
//        $msg=$smarty->fetch("rishta_mailer.htm");
	$subject="Who will you marry ? Find out now";
	$from="info@jeevansathi.com";
	while($row=mysql_fetch_array($result))
        {
                $email=addslashes($row["EMAIL"]);
		$smarty->assign("email",$email);
		$msg=$smarty->fetch("rishta_mailer.htm");
		send_email($email,$msg,$subject,$from);
		$sql="update MMM.RISHTA_EMAILS set SENT='Y',SENT_TIME=now() where EMAIL='$email'";
		mysql_query($sql) or logerror1("In rishta at setting SENT var","");
	}
?>
