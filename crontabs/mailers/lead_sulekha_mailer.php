<?php

/************************************************************************************************************************
  * FILENAME           : lead_sulekha_mailer.php
  * DESCRIPTION        : Mail will be send to the User who are in the REG_LEAD Table for conversion of lead whose source is sulekha.
  * Date               : 6th May 2010
  ***********************************************************************************************************************/

include("../profile/connect.inc");
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
$db_slave = connect_slave();
$db_master = connect_db();

//$smarty->relative_dir="mailer/";

$from='info@jeevansathi.com';
$sub='Complete your registration to find the right life partner';

$sql="SELECT LEADID,GENDER,EMAIL,DTOFBIRTH,RELIGION,MTONGUE,ENTRY_DT FROM MIS.REG_LEAD WHERE LEAD_CONVERSION='N' AND TYPE='A'";
$res= mysql_query($sql,$db_slave) or die(mysql_error1($db_slave));

while($row=mysql_fetch_array($res))
{
	$leadid		=$row['LEADID'];
	$email		=$row['EMAIL'];
	$gender 	=$row['GENDER'];
	$religion	=$row['RELIGION'];
	$dt_birth 	=$row['DTOFBIRTH'];
	$mtongue 	=$row['MTONGUE'];
	$entry_dt 	=$row['ENTRY_DT'];
	
	check_leadStatus($leadid,$entry_dt,$db_master);
	$mailer =checkMailerDate($entry_dt);
	if($email && $mailer)
	{
		//$email ="manoj.rana@naukri.com";
		$msg ="";
		$religion_label =$RELIGIONS["$religion"];
		$mtongue_label	=$MTONGUE_DROP_SMALL["$mtongue"];
		$age_label	=getAgeYears($dt_birth);
		if($gender=='M')
			$msg =male_mailer_content($leadid,$religion_label,$mtongue_label,$age_label,$SITE_URL);	
		elseif($gender=='F')
			$msg =female_mailer_content($leadid,$religion_label,$mtongue_label,$age_label,$SITE_URL);

		send_email($email,$msg,$sub,$from);

		$sql="UPDATE MIS.REG_LEAD SET SENT_MAIL='Y' WHERE EMAIL='$email'";
		mysql_query($sql,$db_master) or die(mysql_error1($db_master));
	}
}
mail("manoj.rana@naukri.com","Sulekha registration lead mailer ran successfully", date("Y-m-d"));

/* Functions added for sending mailers */

/*  lead status flags - A:Active, I:Inactive, D:Duplicate, INV:Invalid */
function check_leadStatus($leadid,$date="",$db_master)
{
	$date_arr =explode(" ",$date);
	$entry_dt =trim($date_arr['0']);
	$today_date =trim(date("Y-m-d"));
	$service_date =date("Y-m-d",JSstrToTime("$entry_dt +30 days"));

        if($today_date >$service_date){
		$sql="UPDATE MIS.REG_LEAD SET TYPE='I' WHERE LEADID='$leadid'";
		mysql_query($sql,$db_master) or die(mysql_error1($db_master));
	}	
}

function checkMailerDate($date="")
{
	$date_arr =explode(" ",$date);
	$entry_dt =trim($date_arr['0']);
	$today_date =trim(date("Y-m-d"));

	$service_date_1 =date("Y-m-d",JSstrToTime("$entry_dt +1 days"));
	$service_date_7 =date("Y-m-d",JSstrToTime("$entry_dt +7 days"));
	$service_date_15=date("Y-m-d",JSstrToTime("$entry_dt +15 days"));
	$service_date_23=date("Y-m-d",JSstrToTime("$entry_dt +23 days"));	

	if( ($today_date==$service_date_1) || ($today_date==$service_date_7) || ($today_date==$service_date_15) || ($today_date==$service_date_23) )
		return true;
	return false;
}

function getAgeYears($age)
{
	$age_arr =explode("-",$age);		
	$age_year =$age_arr[0];

	$today_date =date("Y-m-d");
	$today_date_arr =explode("-",$today_date);
	$today_date_year =$today_date_arr['0'];

	$years =$today_date_year - $age_year;	
	return $years;
}

function male_mailer_content($leadid,$religion,$mtongue,$age,$SITE_URL)
{
	$str  ="";
	$url  ="$SITE_URL/profile/registration_page1.php?leadid=$leadid";
	$str .="Hello,<br><br>";	
	$str .="On the basis of profile details provided by you, we have searched the Jeevansathi.com database for $religion female profiles under the age of $age from $mtongue. <br><br>";
	$str .="We are happy to inform you that we have found several female profiles that match your criteria. <br> 
		To get the details of these profiles, please <a href='$url' target='_blank' style='text-decoration:underline; color:#006fb5;'>register with Jeevansathi.com.</a> <br> 
		Once you register, you will get daily recommendations in your inbox. <br> 
		If you need any assistance, please do get in touch with us.<br><br>";
	$str .="Best regards, <br>";
	$str .="Jeevansathi.com Team<br>";
	return $str;
}

function female_mailer_content($leadid,$religion,$mtongue,$age,$SITE_URL)
{
        $str  ="";
	$url  ="$SITE_URL/profile/registration_page1.php?leadid=$leadid";
        $str .="Hello,<br><br>";
        $str .="On the basis of profile details provided by you, we have searched the Jeevansathi.com database for $religion male profiles above the age of $age from $mtongue. <br><br>";
        $str .="We are happy to inform you that we have found several male profiles that match your criteria. <br> 
                To get the details of these profiles, please <a href='$url' target='_blank' style='text-decoration:underline; color:#006fb5;'>register with Jeevansathi.com.</a> <br> 
                Once you register, you will get daily recommendations in your inbox. <br> 
                If you need any assistance, please do get in touch with us.<br><br>";
        $str .="Best regards, <br>";
        $str .="Jeevansathi.com Team<br>";
	return $str;	
}

function mysql_error1($db)
{
        mail("manoj.rana.@naukri.com","Error in Sulekha registration lead_mailer.php",mysql_error($db));
}


?>
