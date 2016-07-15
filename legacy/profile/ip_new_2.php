<?php

/**************************************************************************************************************************
 * FILE NAME   		: ip_new_2.php
 * DESCRIPTION 		: New script was created for the Page1-PartB
 * MODIFY DATE        	: 28 SEP, 2005
 * MODIFIED BY        	: Nikhil Tandon
 * REASON             	: Ajax Based form 			
 * Copyright  2005, InfoEdge India Pvt. Ltd.
 **************************************************************************************************************************/

//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
$zipIt = 1;
if($zipIt)
ob_start("ob_gzhandler");
//end of it

require_once("connect.inc");
$db=connect_db();
//adding mailing to gmail account to check if file is being used
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
               $cc='eshajain88@gmail.com';
               $to='sanyam1204@gmail.com';
               $msg1='ip_new_2 is being hit. We can wrap this to JProfileUpdateLib';
               $subject="ip_new_2";
               $msg=$msg1.print_r($_SERVER,true);
               send_email($to,$msg,$subject,"",$cc);
 //ending mail part
$lang=$_COOKIE['JS_LANG'];
if($lang=="deleted")
	$lang="";

// assert that some things are not be shown in common templates as is the case with homepage
$smarty->assign("CAMEFROMHOMEPAGE","1");

/********************************************************************************************************/

if((substr($tieup_source,0,2))=='af' || $email_validation=='Y')
	$data=$id;
else
	$data=authenticated($checksum);

if((substr($tieup_source,0,2))=='af' || $email_validation=='Y' || $camefrom="page2")
{
	$profileid=$id;
}
else
	$profileid=$data["PROFILEID"];

if($source=="")
{
	if($newsource!="")
		$source=$newsource;
	elseif(isset($_COOKIE['JS_SOURCE']))
	{
		$source=$_COOKIE['JS_SOURCE'];
	}
}
// if source has come in that means that the person has clicked on a banner on jeevansathi
// we make source blank in index.php before including this file to implement this logic
else 
{
	//	savehit($source,$_SERVER['PHP_SELF']);

	if(isset($_COOKIE['JS_SOURCE']))
		$source=$_COOKIE['JS_SOURCE'];
}

//checking for gender cookie
if(isset($_COOKIE["JS_GENDER"]))
{
	$cookie_gender=$_COOKIE["JS_GENDER"];
}

//$smarty->assign("CREATIVE",tieup_creative($source,$cookie_gender));
//tieup_creative($source,'long_page');

if($cfrom=="page2")
{
	/****  check for banner sources*****/

	$sql="SELECT FORCE_EMAIL FROM MIS.SOURCE WHERE SOURCEID = '$tieup_source'";
	$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
	$row=mysql_fetch_array($result);
	$force_mail=$row["FORCE_EMAIL"];
	if($force_mail=='Y')
	{
		$email_validation='Y';
	}
 
	$is_error=0;
	//age calculation from DOB
	$array = array($Year, $Month, $Day);
	if($array)
	{
		$date_of_birth= implode("-", $array);
		$age=getAge($date_of_birth);
	}
	else
		$age=0;
		
	// added by Gaurav Arora on 25 July for new template of input profile.
	// end of code added for new template of input profile.

	if($tieup_source=="")
		$tieup_source="IP";
		
	$today=date("Y-m-d");

	$Education_Level_Old=get_old_value($Education_Level,"EDUCATION_LEVEL_NEW");

	if((substr($tieup_source,0,2))=='af' || $email_validation=='Y')
	{	
		$off_line='N';

		//query changed by Gaurav Arora on 25 July for new input profile pages.
		$Incomplete='N';
		
		$sql="UPDATE JPROFILE_AFFILIATE SET RELATION='".$Relationship."',GENDER='".$Gender."',DTOFBIRTH='".$date_of_birth."',MOD_DT=now(),LAST_LOGIN_DT='".$today."',SORT_DT=now(),INCOMPLETE='$Incomplete',AGE='".$age."',HAVEPHOTO='N',ACTIVATED='N',OFFLINE='".$off_line."',YOURINFO='".addslashes(stripslashes($Information))."',EDU_LEVEL='".$Education_Level_Old."',EDU_LEVEL_NEW='".$Education_Level."',PRIVACY='".$radioprivacy."',OCCUPATION='".$Occupation."' WHERE ID='".$profileid."'";
 
											 
		 mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
	}
	else
	{
		//query changed by Gaurav Arora on 25 July for new input profile pages.
		$sql = "UPDATE JPROFILE SET RELATION='".$Relationship."',GENDER='".$Gender."',DTOFBIRTH='".$date_of_birth."',MOD_DT=now(),LAST_LOGIN_DT='".$today."',SORT_DT=now(),INCOMPLETE='N',AGE='".$age."',HAVEPHOTO='N',ACTIVATED='N',YOURINFO='".addslashes(stripslashes($Information))."',EDU_LEVEL='".$Education_Level_Old."',EDU_LEVEL_NEW='".$Education_Level."',OCCUPATION='".$Occupation."',PRIVACY='".$radioprivacy."'  WHERE PROFILEID='".$profileid."'";
		mysql_query_decide($sql) or die("$sql".mysql_error_js());//logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");

	}
	if($Gender=='M')
	{
		if($age<25)
			$lage=18;
		else
			$lage=$age-7;
		$hage=$age;
	}
	else
	{
		$hage=$age+7;
		if($age<21)
			$lage=21;
		else
			$lage=$age;
	}
	if(($lage==21 && $hage==7) || ($lheight==0 && $hheight==10))
	{
		$err_msg="PROFILEID:$profileid<br>GENDER:$Gender<br>LAGE:$lage<br>HAGE:$hage<br>LHEIGHT:$lheight<br>HHEIGHT:$hheight<br>SOURCE=$tieup_source";
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
		send_email('gaurav.arora@jeevansathi.com',$err_msg,"Error in JPARTNER from $_SERVER[PHP_SELF] on line 144","register@jeevansathi.com");
	}

	if((substr($tieup_source,0,2))=='af' || $hit_source=='O' || $email_validation=='Y')
	{
		if($Gender=='M')
			$sql_jpartner="UPDATE JPARTNER_PAGE3 SET GENDER='F',LAGE='$lage',HAGE='$hage',DATE=now() WHERE PROFILEID='".$profileid."'";
		elseif($Gender=='F')
			$sql_jpartner="UPDATE JPARTNER_PAGE3 SET GENDER='M',LAGE='$lage',HAGE='$hage',DATE=now() WHERE PROFILEID='".$profileid."'";
			                                                                                                 
                mysql_query_decide($sql_jpartner) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_jpartner,"ShowErrTemplate");
                                                                                                 
                                                                                                 
	}
	else
	{
		if($Gender=='M')
			$sql_jpartner="UPDATE JPARTNER SET GENDER='F',LAGE='$lage',HAGE='$hage',DATE=now() WHERE PROFILEID='".$profileid."'";
		else
			$sql_jpartner="UPDATE JPARTNER SET GENDER='M',LAGE='$lage',HAGE='$hage',DATE=now() WHERE PROFILEID='".$profileid."'";
		mysql_query_decide($sql_jpartner) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_jpartner,"ShowErrTemplate");
	}
	$tm=time();
	
	$smarty->assign("CHECKSUM",$checksum);
	if($_SERVER["SERVER_NAME"]=="www.jeevansathi.com")
		$smarty->assign("SHOW_GOOGLE",'Y');
	//showPart2($checksum,$Gender,$hit_source,$Marital_Status,$tieup_source,$id);
}
else
{
	$smarty->assign("CHECKSUM",$checksum);
	$smarty->assign("username",$Username);
	//$smarty->assign("gender",$gender_profile);
	$smarty->assign("email",$Email);
	$smarty->assign("gender",$Gender);
	$smarty->assign("day",$Day);
	$smarty->assign("month",$Month);
	$smarty->assign("year",$Year);

	//$smarty->assign("mtongue",create_dd("","Mtongue"));
	$occupation=create_dd("","Occupation");
	$education_level=create_dd("","Education_Level_New");

	//added by gaurav arora on 25 July for new input profile pages
	$smarty->assign("occupation",$occupation);
	$smarty->assign("education_level",$education_level);

	//end of new code added for new input profile pages

	$smarty->assign("once","T");
	$smarty->assign("TIEUPSOURCE",$tieup_source);
	$smarty->assign("RADIOPRIVACY","A");
	$smarty->assign("HITSOURCE",$hit_source);	

	//$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));

	if($lang)
	{
		$smarty->assign("FOOT",$smarty->fetch($lang."_foot.htm"));
		$smarty->assign("HEAD",$smarty->fetch($lang."_headnew.htm"));
		$smarty->display($lang."_inputprofile_tieupB.htm");
	}
	else
	{
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
		$smarty->display("inputprofile_tieupB_revamp.htm");
	}
}
// flush the buffer
if($zipIt)
	ob_end_flush();
?>
