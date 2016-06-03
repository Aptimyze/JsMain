<?
/*********************************************************************************************
* FILE NAME     : advertise_here.php
* DESCRIPTION   : Allows the advertiser to submit his details
* CREATION DATE : 3 September, 2005
* CREATED BY    : SHAKTI SRIVASTAVA
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
                                                                                                 
include('connect.inc');
include('common_func_inc.php');
include('../profile/comfunc.inc');
include('display_result.inc');
                                                                                                 
$db=connect_db();
                                                                                                 
populate_head();
populate_left();

$smarty->assign("WED_HEAD",$smarty->fetch("wedding_head.htm"));
$smarty->assign("WED_LEFT",$smarty->fetch("wedding_left.htm"));
$smarty->assign('WED_RIGHT',$smarty->fetch('wedding_right.htm'));
$smarty->assign("FOOT",$smarty->fetch("foot.htm"));

if($submit)
{
	$NAME=trim($NAME);
	$CONTACT_PERSON=trim($CONTACT_PERSON);
	$MAIL=trim($MAIL);
	$PHONE=trim($PHONE);
	$ADDRESS=trim($ADDRESS);
	$MICROSITE_URL=trim($MICROSITE_URL);
	$DESCPN=trim($DESCPN);

	$iserror=0;
	$msg="";
	maStripVARS("addslashes");
	
	if($NAME=="")
	{
		$iserror++;
		$smarty->assign("NAME_ERR","1");
	}
	if($CONTACT_PERSON=="")
	{
		$iserror++;
		$smarty->assign("CPERSON_ERR","1");
	}
	if($MAIL=="")
	{
                $iserror++;
                $smarty->assign("MAIL_ERR","1");
	}
	else if(checkemail($MAIL))
	{
		$msg.="E-mail is not correct. ";
		$iserror++;
		$smarty->assign("MAIL_ERR","1");
	}

	if($PHONE=="")
	{
		$iserror++;
		$smarty->assign("PHONE_ERR","1");
	}
	else if(checkphone($PHONE))
	{
		$msg.="Phone number is not correct";
		$iserror++;
                $smarty->assign("PHONE_ERR","1");
	}

	if($ADDRESS=="")
	{
		$iserror++;
		$smarty->assign("ADD_ERR","1");
	}
	if($SUBS=='BA')
	{
		$PAID='N';
	}
	else
	{
		$PAID='Y';
	}

	if($iserror>0)
	{
		maStripVARS("stripslashes");
		$smarty->assign("MSG",$msg);
		$smarty->assign("NAME",$NAME);
		$smarty->assign("CATEGORY",$CATEGORY);
		$smarty->assign("CONTACT_PERSON",$CONTACT_PERSON);
		$smarty->assign("MAIL",$MAIL);
		$smarty->assign("PHONE",$PHONE);
		$smarty->assign("ADDRESS",$ADDRESS);
		$smarty->assign("MICROSITE_URL",$MICROSITE_URL);
		$smarty->assign("CATEGORY",$CATEGORY);
		$smarty->assign("CITY",$CITY);
		$smarty->assign("DESCPN",$DESCPN);
		$smarty->assign("SUBS",$SUBS);
		$smarty->display("advertise_here.htm");
	}
	else
	{

		if($MICROSITE_URL!="" && !strstr($MICROSITE_URL,"http://"))
		{
			$MICROSITE_URL="http://".$MICROSITE_URL;
		}

		$sql="INSERT INTO wedding_classifieds.LISTINGS VALUES ('','$NAME','$CONTACT_PERSON','$ADDRESS','$PHONE','$MAIL','$CITY','$DESCPN','$PAID','$SUBS','','$MICROSITE_URL','N','$CATEGORY','','')";
		$res=mysql_query_decide($sql) or logError("Error while inserting into LISTINGS ".mysql_error_js(),$sql);

		$msg="Thank you for advertising on the JeevanSathi Wedding Directory.<br>We need to screen your company profile and after approval, the advertisement will go live on our website.<br><br>With Warm Regards<br>The JeevanSathi.com Team<br><a href=\"http://www.jeevansathi.com\">www.jeevansathi.com</a>";
		$smarty->assign("MSG",$msg);
		$smarty->display("message.htm");

		$from="advertise@jeevansathi.com";
		$subject="Thank You Mr. ".$NAME;
		$to=$MAIL;
		$smarty->assign("ADV",$NAME);
		$msg=$smarty->fetch("thankyou_mailer.htm");
		send_email($to,$msg,$subject,$from);
	}
}
else
{
	$smarty->display("advertise_here.htm");
}
?>
