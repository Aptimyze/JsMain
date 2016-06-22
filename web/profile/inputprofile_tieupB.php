<?php

/**************************************************************************************************************************
 * FILE NAME   		: inputprofile_tieupB.php
 * DESCRIPTION 		: New script was created for the Page1-PartB
 * MODIFY DATE        	: 28 SEP, 2005
 * MODIFIED BY        	: SHAKTI SRIVASTAVA
 * REASON             	: Changes made due to three page input structure 			
 * Copyright  2005, InfoEdge India Pvt. Ltd.
 **************************************************************************************************************************/
$http_msg=print_r($_SERVER,true);
//to zip the file before sending it
$zipIt = 0;
//adding mailing to gmail account to check if file is being used
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
               $cc='eshajain88@gmail.com';
               $to='sanyam1204@gmail.com';
               $msg1='inputprofile_tieupB is being hit. We can wrap this to JProfileUpdateLib';
               $subject="inputprofile_tieupB";
               $msg=$msg1.print_r($_SERVER,true);
               send_email($to,$msg,$subject,"",$cc);
 //ending mail part
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
$zipIt = 1;
if($zipIt)
ob_start("ob_gzhandler");
//end of it

if($Gender == "")
{
	header('Location: '.$SITE_URL.'/P/inputprofile_tieup.php?source=hpblack');
        die();
}
require_once("connect.inc");
include_once("manglik.php");

$path=$_SERVER['DOCUMENT_ROOT'];
include_once($path."/classes/Jpartner.class.php");

$jpartnerObj=new Jpartner;
$mysqlObj=new Mysql;

$db=connect_db();
$lang=$_COOKIE['JS_LANG'];
if($lang=="deleted")
	$lang="";

// assert that some things are not be shown in common templates as is the case with homepage
$smarty->assign("CAMEFROMHOMEPAGE","1");

//section added for webchutney by Gaurav on 11 May 2006
if($groupname=='wchutney')
{
	$smarty->assign("script_name",$script_name);
	$smarty->assign("http_referer",$http_referer);
	$smarty->assign("remote_host",$remote_host);
	$smarty->assign("rfr",$rfr);
	$smarty->assign("groupname",$groupname);
}                                                                                                                            
//end of section added for webchutney by Gaurav on 11 May 2006

/********************************************************************************************************/
if((substr($tieup_source,0,2))=='af' || $hit_source=='O' || $email_validation=='Y')
	$data=$id;
else
	$data=authenticated($checksum,'y');

if((substr($tieup_source,0,2))=='af' || $hit_source=='O' || $email_validation=='Y')
{
	$profileid=$id;
}
else
{
	$profileid=$data["PROFILEID"];
	if(!$profileid)
	{
		$data = login($username,$Password1);
		$checksum = $data['CHECKSUM'];
		$profileid = $data['PROFILEID'];
	}
	$myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
	$myDb=$mysqlObj->connect("$myDbName");
	$jpartnerObj->setPROFILEID($profileid);
}
if($data["BUREAU"]==1 && $_COOKIE["JSMBLOGIN"])
{
        $fromprofilepage=1;
        include_once('../marriage_bureau/connectmb.inc');
        mysql_select_db_js('marriage_bureau');
        $data=authenticatedmb($mbchecksum);
        //$smarty->assign('mbchecksum',$hecksum);
        if(!$data)
                timeoutmb();
        $smarty->assign('mbchecksum',$data["CHECKSUM"]);
        $smarty->assign('source',$data["SOURCE"]);
        mysql_select_db_js('newjs');
        $smarty->assign('frommarriagebureau',$fromprofilepage);
}

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
$smarty->assign("source",$tieup_source);
//$smarty->assign("CREATIVE",tieup_creative($source,$cookie_gender));
//tieup_creative($source,'long_page');

if($Submit)
{
	if($hit_source!='O')
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

		/********/

		$is_error=0;
			
		//Added By lavesh
		if($check_subcaste1=='Y')
                {
			if(!$Subcaste)
			{
				$smarty->assign("check_subcaste","Y");
				$is_error++;
			}
                }
		$smarty->assign("check_subcaste1",$check_subcaste1);
		//Ends Here
		
		//Wrong or blank entry validation
		if($Relationship=="")
		{
			$is_error++;
			$smarty->assign("check_relationship","Y");
		}

		if($Gender=="")
		{
			$is_error++;
			$smarty->assign("check_gender","Y");
		}

		/*if($Mtongue=="")
		{
			$is_error++;
			$smarty->assign("check_mtongue","Y");
		}*/

		/****
		 *       MODIFIED BY          :  Gaurav Arora
		 *       DATE OF MODIFICATION :  25 July 2005
		 *       MODIFICATION         :  fields added for new input profile pages
		 ****/

		if(trim($Information)=="" || strlen(trim($Information))<100)
		{
			$is_error++;
			$smarty->assign("check_information","Y");
			$check_information="Y";
		}

		if($Occupation=="")
		{
			$is_error++;
			$smarty->assign("check_occupation","Y");
		}


		if($Education_Level=="")
		{
			$is_error++;
			$smarty->assign("check_education_level","Y");
		}


		$check_date=validate_date($Day,$Month,$Year);
		if($check_date==1)
		{
			$is_error++;
			$smarty->assign("check_date",$check_date);
		}
		elseif($Gender!="")
		{
			$array = array($Year, $Month, $Day);
			$date_of_birth= implode("-", $array);
			$age=getAge($date_of_birth);

			if($Gender=="M" && $age < 21)
			{
				$is_error++;
				$smarty->assign("DATEOFBIRTH_LESS",1);
			}
			elseif($Gender=="F" && $age < 18)
			{
				$is_error++;
				$smarty->assign("DATEOFBIRTH_LESS",1);
			}
		}
	}	
	if($is_error > 0)
	{     
		// remove slashes
		maStripVARS("stripslashes");

		$smarty->assign("NO_OF_ERROR",$is_error);
/****
*       MODIFIED BY          :  Gaurav Arora
*       DATE OF MODIFICATION :  25 July 2005
*       MODIFICATION         :  fields added for new input profile pages
****/
		$occupation=create_dd("$Occupation","Occupation");
		//$education_level=create_dd("$Education_Level","Education_Level_New");
		//changed by sriram on Jan 9 2006 for grouping on registration page
		$education_level=create_dd("$Education_Level","Education_Level_New","","","Y");
		//$mtongue=create_dd($Mtongue,"Mtongue");

		$smarty->assign("occupation",$occupation);
		$smarty->assign("education_level",$education_level);
		$smarty->assign("information",$Information);
		$smarty->assign("CHARACTERS",strlen($Information));
		$smarty->assign("RADIOPRIVACY",$radioprivacy);
		//$smarty->assign("mtongue",$mtongue);
		$smarty->assign("relationship",$Relationship);
		$smarty->assign("gender",$Gender);
		$smarty->assign("username",$username);
		$smarty->assign("Password1",$Password1);

		//Added By Lavesh as this script is not working properly
		$smarty->assign("CHECKSUM",$checksum);
		
		$smarty->assign("GENDERCHANGE","Y");

		$smarty->assign("day",$Day);
		$smarty->assign("month",$Month);
		$smarty->assign("year",$Year);

		$smarty->assign("HITSOURCE",$hit_source);	
		$smarty->assign("TIEUPSOURCE",$tieup_source);

		//added by sriram.
		$smarty->assign("name_of_user",$name_of_user);

		//Added By lavesh
		$smarty->assign("gothra",$Gothra);
		$smarty->assign("subcaste",$Subcaste);
		//Ends Here
		populate_day_month_year();
		if($lang)
		{
			$smarty->assign("FOOT",$smarty->fetch($lang."_foot.htm"));
			$smarty->assign("HEAD",$smarty->fetch($lang."_headnew.htm"));
			$smarty->display($lang."_inputprofile_tieupB.htm");
		}
		else
		{
			$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
			$smarty->assign("SMALL_HEAD",$smarty->fetch("small_headnew.htm"));
			//added by sriram for new registration pages
			//if($new_r_page)
			//{
				$smarty->assign("new_r_page",$new_r_page);
				$smarty->display("inputprofile_tieupB_revamp.htm");
			/*}
			else
				$smarty->display("inputprofile_tieupB.htm");*/
			//$smarty->display("inputprofile_tieupB_may2006.htm");
		}
	}
	else
	{
		//function called by lavesh to check whether gothra is valid or not.if not valid Gothra is set to blank.
                $Gothra=redo_gothra($Gothra);
		//age calculation from DOB
		$array = array($Year, $Month, $Day);
		if($date_of_birth)
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

		if($hit_source!='O')            
			$Education_Level_Old=get_old_value($Education_Level,"EDUCATION_LEVEL_NEW");
		else
		{
			if($Education_Level)
				$Education_Level_Old=get_old_value($Education_Level,"EDUCATION_LEVEL_NEW");

		}

		if((substr($tieup_source,0,2))=='af' || $hit_source=='O' || $email_validation=='Y')
		{	
			if($hit_source=='O')
			{
				$off_line='Y';
			}
			else
			{
				$off_line='N';
			}

			//query changed by Gaurav Arora on 25 July for new input profile pages.
			if($off_line=='Y')
			{
				if(!($Gender||$Relationship||$date_of_birth||$Information||$Education_Level_Old||$Occupation))
				{
					$Incomplete='Y';
				}
			}
			else
				$Incomplete='N';

			if($hit_source=='O')
			{
				$sql="UPDATE JPROFILE_AFFILIATE SET RELATION='$Relationship',GENDER='$Gender',DTOFBIRTH='$date_of_birth',MOD_DT=now(),LAST_LOGIN_DT='$today',SORT_DT=now(),INCOMPLETE='$Incomplete',AGE='$age',HAVEPHOTO='N',ACTIVATED='N',OFFLINE='$off_line',YOURINFO='".addslashes(stripslashes($Information))."',EDU_LEVEL='$Education_Level_Old',EDU_LEVEL_NEW='$Education_Level',OCCUPATION='$Occupation',PRIVACY='$radioprivacy',GOTHRA='".trim($Gothra)."',SUBCASTE='".trim($Subcaste)."' WHERE ID='$profileid'";
			}
			else
			{
				$sql="UPDATE JPROFILE_AFFILIATE SET RELATION='".$Relationship."',GENDER='".$Gender."',DTOFBIRTH='".$date_of_birth."',MOD_DT=now(),LAST_LOGIN_DT='".$today."',SORT_DT=now(),INCOMPLETE='$Incomplete',AGE='".$age."',HAVEPHOTO='N',ACTIVATED='N',OFFLINE='".$off_line."',YOURINFO='".addslashes(stripslashes($Information))."',EDU_LEVEL='".$Education_Level_Old."',EDU_LEVEL_NEW='".$Education_Level."',PRIVACY='".$radioprivacy."',GOTHRA='".trim($Gothra)."',SUBCASTE='".trim($Subcaste)."' WHERE ID='".$profileid."'";
	 
			}
                                                                                                 
			 mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		}
		else
		{
			//Activation should be Y always when offline operator is registring the profiles
			if(($_COOKIE['OPERATOR']!="deleted" && $_COOKIE['OPERATOR']!="")  || $tieup_source=='ofl_prof')
                        {
                                $activated='N';
				$screening=131071;
				send_email("nikhil.dhiman@jeevansathi.com","test message","2--$profileid---$_COOKIE[OPERATOR]-----$source-----$tieup_source","offline@jeevansathi.com");	
                        }
                        else
                        {
                                $activated='N';
				$screening=0;
                        }

			//query changed by Gaurav Arora on 25 July for new input profile pages.
			$sql = "UPDATE JPROFILE SET RELATION='".$Relationship."',GENDER='".$Gender."',DTOFBIRTH='".$date_of_birth."',MOD_DT=now(),LAST_LOGIN_DT='".$today."',SORT_DT=now(),INCOMPLETE='N',AGE='".$age."',HAVEPHOTO='N',ACTIVATED='$activated',YOURINFO='".addslashes(stripslashes($Information))."',EDU_LEVEL='".$Education_Level_Old."',EDU_LEVEL_NEW='".$Education_Level."',OCCUPATION='".$Occupation."',PRIVACY='".$radioprivacy."',GOTHRA='".trim($Gothra)."',SUBCASTE='".trim($Subcaste)."', SCREENING='$screening'  WHERE PROFILEID='".$profileid."'";

			mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");

		}

		//added by sriram to store the name of user
		if($name_of_user)
		{
			$sql_name = "REPLACE INTO incentive.NAME_OF_USER(PROFILEID,NAME) VALUES('$profileid','".addslashes(stripslashes($name_of_user))."')";
			mysql_query_decide($sql_name) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_name,"ShowErrTemplate");
		}

		//added by sriram to prevent lheight and hheight mismatch on moving from first page to second page and changing the gender.
		$sql_jprofile = "SELECT HEIGHT FROM newjs.JPROFILE WHERE PROFILEID = '$profileid'";
		$res_jprofile = mysql_query_decide($sql_jprofile) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_jprofile,"ShowErrTemplate");
		$row_jprofile = mysql_fetch_array($res_jprofile);
		$Height = $row_jprofile['HEIGHT'];

		if($Gender=='M')
                {
                        $hheight=$Height;
                        if($Height>10)
                                $lheight=$Height-10;
                        else
                                $lheight=1;
                }
                else
                {
                        $lheight=$Height;
                        if($Height<=20)
                                $hheight=$Height+10;
                        else
                                $hheight=30;
                        if($Height > $hheight)
                                $hheight = 32;
                }
		//added by sriram to prevent lheight and hheight mismatch on moving from first page to second page and changing the gender.
			
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
			//added by sriram - if maximum age is selected,then hage should be the maximum age.
			if($hage > 70)
				$hage = 70;
                }
		//added by Gaurav on 27 June to check from where lage=21,hage=7,lheight=0 or hheight=10 is going in JPARTNER
                if((($lage==21 && $hage==7) || ($lheight==0 && $hheight==10) || $lage==0) && substr($tieup_source,0,2)!='af')
                {
                        $err_msg="PROFILEID:$profileid<br>GENDER:$Gender<br>LAGE:$lage<br>HAGE:$hage<br>LHEIGHT:$lheight<br>HHEIGHT:$hheight<br>SOURCE=$tieup_source";
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");

                        //send_email('gaurav.arora@jeevansathi.com',$err_msg,"Error in JPARTNER from $_SERVER[PHP_SELF]","register@jeevansathi.com");
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
                       if($Gender=="M")
				$jpartnerObj->setGENDER("F");
			else
				$jpartnerObj->setGENDER("M");
			$jpartnerObj->setLAGE($lage);
			$jpartnerObj->setHAGE($hage);
			$jpartnerObj->setLHEIGHT($lheight);
			$jpartnerObj->setHHEIGHT($hheight);
			$jpartnerObj->setDPP("R");
			$jpartnerObj->updatePartnerDetails($myDb,$mysqlObj);
                }
 
		$tm=time();
		
		$smarty->assign("CHECKSUM",$checksum);
		if($_SERVER["SERVER_NAME"]=="www.jeevansathi.com")
                        $smarty->assign("SHOW_GOOGLE",'Y');
		//showPart2($checksum,$Gender,$hit_source,$Marital_Status,$tieup_source,$id,$frommarriagebureau);
		//modified by sriram for new registration pages
		showPart2($checksum,$Gender,$hit_source,$Marital_Status,$tieup_source,$id,$frommarriagebureau,$new_r_page);
        }
}
else
{
	//Added By lavesh for Caste Subcaste combination error.

	$combination_error=validate_combination($Caste,$Subcaste);
													     
	if($combination_error)
	{
		$smarty->assign("check_subcaste","Y");
		$smarty->assign("check_subcaste1","Y");
	}
	//Ends Here

	if($frommarriagebureau==1)
        {
                $smarty->assign('frommarriagebureau',$frommarriagebureau);
                $smarty->assign('relationship',"5");
        }
	$smarty->assign("CHECKSUM",$checksum);
	$smarty->assign("username",$Username);
	$smarty->assign("Password1",$Password1);
	//$smarty->assign("gender",$gender_profile);
	$smarty->assign("email",$Email);
	$smarty->assign("gender",$Gender);
	$smarty->assign("day",$Day);
	$smarty->assign("month",$Month);
	$smarty->assign("year",$Year);

	//To set the Bride/Groom in htm on the basis of Gender  selected
	if($Gender=="F")
		$TYPE_GEN="her";
	else
		$TYPE_GEN="him";

	$smarty->assign("TYPE_GEN",$TYPE_GEN);
       //Setting bride/groom ends here

	//$smarty->assign("mtongue",create_dd("","Mtongue"));
	$occupation=create_dd("","Occupation");
	//$education_level=create_dd("","Education_Level_New");
	//changed by sriram on Jan 9 2006 for grouping on registration page
	$education_level=create_dd("$Education_Level","Education_Level_New","","","Y");

	//added by gaurav arora on 25 July for new input profile pages
        $smarty->assign("occupation",$occupation);
        $smarty->assign("education_level",$education_level);

	//end of new code added for new input profile pages
	
	$smarty->assign("once","T");
	$smarty->assign("TIEUPSOURCE",$tieup_source);
	$smarty->assign("RADIOPRIVACY","A");
	$smarty->assign("HITSOURCE",$hit_source);	

	//$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
	
	//added by puneet on June 7 2006 ,if a user comes from google to check out the drop out ratios when user moves to different pages, page0 page1 page2 page3
	//if($tieup_source=='a09'  || $tieup_source=='A09')
	if($_COOKIE["JS_SHORT_FORM"])
	{
		$sql="select count(*) as cnt from FROM_GOOGLE_HITS  where DATE=CURDATE()";
		$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$row=mysql_fetch_array($res);
		$cnt=$row['cnt'];
		if($cnt>0)
			$sql="UPDATE FROM_GOOGLE_HITS set PAGE2=PAGE2+1 WHERE DATE=CURDATE()";
		else
			$sql="insert into FROM_GOOGLE_HITS(DATE,PAGE2) values (now(),'1')";
		mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	}
	//code ends added by puneet on June 7 2006 ,if a user comes from google to check out the drop out ratios when user moves to different pages, page0 page1 page2 page3
	populate_day_month_year();
	if($lang)
	{
		$smarty->assign("FOOT",$smarty->fetch($lang."_foot.htm"));
		$smarty->assign("HEAD",$smarty->fetch($lang."_headnew.htm"));
		$smarty->display($lang."_inputprofile_tieupB.htm");
	}
	else
	{
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		if($frommarriagebureau==1)
			$smarty->assign("HEAD",$smarty->fetch("top_band.htm"));
		else
			$smarty->assign("SMALL_HEAD",$smarty->fetch("small_headnew.htm"));
		//added by sriram for new registration pages
		/*if($new_r_page)
		{*/
			$smarty->assign("new_r_page",$new_r_page);
		$smarty->display("inputprofile_tieupB_revamp.htm");
		/*}
		else
			$smarty->display("inputprofile_tieupA.htm");*/
		//$smarty->display("inputprofile_tieupB_may2006.htm");
	}
}

//function showPart2($checksum,$gender,$hit_source,$maritalstatus,$tieup_source,$id,$frommarriagebureau)
//modified by sriram for new registration pages
function showPart2($checksum,$gender,$hit_source,$maritalstatus,$tieup_source,$id,$frommarriagebureau,$new_r_page)
{
	global $smarty;
	if($new_r_page)
		include("inputprofile1_revamp.php");
	else
		include("inputprofile1.php");
}

// flush the buffer
if($zipIt)
	ob_end_flush();

?>
