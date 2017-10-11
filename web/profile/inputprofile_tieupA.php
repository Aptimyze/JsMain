<?php

/************************************************************************************************************************
* 	FILE NAME	:	inputprofile_tieup.php
* 	DESCRIPTION 	: 	Get details for a new profile
* 	MODIFY DATE	: 	19 May, 2005
* 	MODIFIED BY	: 	AMAN SHARMA
* 	REASON		: 	Changes made due to three page input structure 			
* 	Copyright  2005, InfoEdge India Pvt. Ltd.
************************************************************************************************************************/

//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//end of it

include_once("connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once("hits.php");
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");

$path=$_SERVER['DOCUMENT_ROOT'];
include_once($path."/classes/Jpartner.class.php");

$jpartnerObj=new Jpartner;
$mysqlObj=new Mysql;

$db=connect_db();

$lang=$_COOKIE['JS_LANG'];

// assert that some things are not be shown in common templates as is the case with homepage
$smarty->assign("CAMEFROMHOMEPAGE","1");

$ip=FetchClientIP();//Gets ipaddress of user
if(strstr($ip, ","))    
{                       
	$ip_new = explode(",",$ip);
	$ip = $ip_new[1];
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
	savehit($source,$_SERVER['PHP_SELF']);
	
	if(isset($_COOKIE['JS_SOURCE']))
		$source=$_COOKIE['JS_SOURCE'];
}

//checking for gender cookie

if(isset($_COOKIE["JS_GENDER"]))
{
	$cookie_gender=$_COOKIE["JS_GENDER"];
}

//$smarty->assign("CREATIVE",tieup_creative($source,$cookie_gender,$lang));
tieup_creative($source,'long_page');
if($Showphone=='N')
        $Showphone='';
if($Showmobile=='N')
        $Showmobile='';
/**************************************************************************************************************************
for($i=0;$i<=7;$i++)
{
	if($i>3)
	{
		$gender="M";
		$x=$i-4;
	}
	else 
	{
		$gender="F";
		$x=$i;
	}
	
	$profileid=fetchphoto($gender,$x+1);
	
	if($gender=="M")
        	$sql = "SELECT SQL_CACHE AGE,HEIGHT,CASTE,CITY_RES,COUNTRY_RES,OCCUPATION FROM SEARCH_MALE WHERE PROFILEID=$profileid";
        else 
        	$sql = "SELECT SQL_CACHE AGE,HEIGHT,CASTE,CITY_RES,COUNTRY_RES,OCCUPATION FROM SEARCH_FEMALE WHERE PROFILEID=$profileid";
        	
        $result=mysql_query_decide($sql);
        $myrow=mysql_fetch_array($result);
        
        mysql_free_result($result);
        
	$age_array[]=$myrow["AGE"];
                                                                                         
        $temp=$myrow['HEIGHT'];
	$height=explode("&",$HEIGHT_DROP["$temp"]);
	$height_array[]=$height[0];

        $temp=$myrow['CASTE'];
	$caste_array[]=$CASTE_DROP["$temp"];
	
        $temp=$myrow['OCCUPATION'];
        $occupation_array[]=$OCCUPATION_DROP["$temp"];
        
        $temp=$myrow['CITY_RES'];
        if($myrow['COUNTRY_RES']==51)
        	$city_array[]=$CITY_INDIA_DROP["$temp"];
        elseif($myrow['COUNTRY_RES']==128)
        	$city_array[]=$CITY_USA_DROP["$temp"];
        else 
        	$city_array[]="";

        $temp=$myrow['COUNTRY_RES'];
        $country_array[]=$COUNTRY_DROP["$temp"];
        
        $checksum_array[]=md5($profileid+5)."i".($profileid+5);
        $profilechecksum_array[]=md5($profileid)."i".$profileid;
        
}

$smarty->assign("CHECKSUM_ARRAY",$checksum_array);
$smarty->assign("PROFILECHECKSUM_ARRAY",$profilechecksum_array);
$smarty->assign("COUNTRY_ARRAY",$country_array);
$smarty->assign("CITY_ARRAY",$city_array);
$smarty->assign("OCCUPATION_ARRAY",$occupation_array);
$smarty->assign("CASTE_ARRAY",$caste_array);
$smarty->assign("HEIGHT_ARRAY",$height_array);
$smarty->assign("AGE_ARRAY",$age_array);
*/

/****
*       MODIFIED BY          :  Gaurav Arora
*       DATE OF MODIFICATION :  25 July 2005
*       MODIFICATION         :  Gender set as new templates ask for opposite gender
****/
       
/*if($Gender=="M")
	$Gender="F";
elseif($Gender=="F")
	$Gender="M";
*/
/**********************************************************************************************************************/

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

		$Email=trim($Email);
	
		$Religion_temp = explode('|X|',$Religion);
		$Religion = $Religion_temp[0];
		
/************************************************************************************************************************
			Changed By	: Shakti Srivastava
			Reason		: Relationship field will not be there in the new inputprofile page
************************************************************************************************************************/
/*		if($Relationship=="")
		{
			$is_error++;
			$smarty->assign("check_relationship","Y");
		}
*/	
/************************************************************************************************************************/

		if($Gender=="")
		{
			$is_error++;
			$smarty->assign("check_gender","Y");
		}
	
		if($Religion=="")
		{
			$is_error++;
			$smarty->assign("check_religion","Y");
		}
	
		if($Mtongue=="")
		{
			$is_error++;
			$smarty->assign("check_mtongue","Y");
		}

	
		if($Country_Residence=="")
		{
			$is_error++;
			$smarty->assign("check_countryres","Y");
		}
	
		if($Height=="")
		{
			$is_error++;
			$smarty->assign("check_height","Y");
		}
		//echo "caste=".$Caste;	
		if($Caste)
		{
		//	echo "here error";	
			$sql="SELECT PARENT,SMALL_LABEL from CASTE WHERE VALUE='$Caste'";
			$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			$myrow=mysql_fetch_row($result);
			$Caste_label=$myrow[1];
		}
		else
		{
			$is_error++;
			$myrow[0]=-1;
			$smarty->assign("check_caste","Y");
		}
    	
		if($Religion!="" && $myrow[0]!=$Religion)
		{
			$is_error++;
			$Caste="";
			$smarty->assign("check_caste","Y");
		}
/*************************************************************************************************************************
*               ADDED BY        :       Puneet Makkar
*               REASON          :       TO CHECK VALIDITY OF STD AND ISD CODES AND TO PREFILL CODES IF JAVASCRIPT IS OFF
*************************************************************************************************************************/
		if($Country_Code=='')
                        $Country_Code=get_code('COUNTRY',$Country_Residence);
                if($Country_Code_Mob=='')
                        $Country_Code_Mob=get_code('COUNTRY',$Country_Residence);
                if($Country_Residence==51 && $State_Code=='' )
                {
			$State_Code=get_code('CITY_INDIA',$City_India);
                }

		$check_country_code=0;
		$check_country_code_mob=0;
		$check_state_code=0;
	
		if($Phone!='')
			$check_country_code=checkrphone($Country_Code);
		if($Mobile!='')
			$check_country_code_mob=checkrphone($Country_Code_Mob);
		if($Country_Residence=='51')
			$check_state_code=checkrphone($State_Code);
		if($check_state_code==1)
                {
                        $is_error++;
                        $smarty->assign("check_phone_v",$check_state_code);
                        $smarty->assign("phone_msg","State Code  has invalid characters");
                }

		if($check_country_code==1)
		{
                    	$is_error++;
			$smarty->assign("check_phone_v",$check_country_code);
			$smarty->assign("phone_msg","Country Code  has invalid characters");
		}

		if($check_country_code_mob==1)
		{
                    	$is_error++;
			$smarty->assign("check_mobile_v",$check_country_code_mob);
			$smarty->assign("mobile_msg","Country Code  has invalid characters");
		}

/*************************************************************************************************************************
*		ADDED BY	:	AMAN SHARMA
*		REASON		:	TO CHECK VALIDITY OF PHONE NUMBERS
*************************************************************************************************************************/
                                                                                
		$check_phone_v=checkrphone($Phone);
		/*if ($check_phone_v==1)
		{
			$is_error++;
			$smarty->assign("check_phone_v",$check_phone_v);
			$smarty->assign("phone_msg","Phone no. has invalid characters");
		}*/

/***********************************************************************************************************************
*       MODIFIED BY          :  Gaurav Arora
*       DATE OF MODIFICATION :  25 July 2005
*       MODIFICATION         :  fields added for new input profile pages
*************************************************************************************************************************/
		$check_mobile_v=checkrphone($Mobile);

                if ($check_mobile_v==1 && $check_phone_v==1)
                {
                        $is_error++;
		}	
                
		if ($check_phone_v==1 )
		{	
			$smarty->assign("check_phone_v",$check_phone_v);
			$smarty->assign("phone_msg","Phone no. has invalid characters");
                }        
		
		if ($check_mobile_v==1 )
		{	
			$smarty->assign("check_mobile_v",$check_mobile_v);
                        $smarty->assign("mobile_msg","Mobile no. has invalid characters");
                }




/************************************************************************************************************************
			Changed By	: Shakti Srivastava
			Reason		: Information field will not be there in the new inputprofile page
************************************************************************************************************************/
/*27		if(trim($Information)=="" || strlen(trim($Information))<100)
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
*/
/************************************************************************************************************************/

		if($Country_Residence=='51')
                {
                        if($City_India=='')
                        {
                                $is_error++;
                                $smarty->assign("check_city_residence","Y");
				$smarty->assign("CITY_INDIA","Y");
                        }
                }
		if($Country_Residence=='128')
                {
                        if($City_USA=='')
                        {
                                $is_error++;
                                $smarty->assign("check_city_residence","Y");
				$smarty->assign("CITY_USA","Y");
                        }
                }

/************************************************************************************************************************
			Changed By	: Shakti Srivastava
			Reason		: Education field will not be there in the new inputprofile page
************************************************************************************************************************/
/*		if($Education_Level=="")
		{
			$is_error++;
			$smarty->assign("check_education_level","Y");
		}
*/											 
/************************************************************************************************************************/

		if($Income=="")
		{
			$is_error++;
                        $smarty->assign("check_income","Y");
                }


		if($termscheckbox=="")
		{
			$is_error++;
                        $smarty->assign("TERMSCHECKBOX","");
		}
		else
		{
			$smarty->assign("TERMSCHECKBOX","T");
		}

		$check_email=checkemail($Email);
		$check_old_email=checkoldemail($Email);	
		$check_email_af=checkemail_af($Email);

		if($check_email || $check_old_email || $check_email_af)
		{
			$is_error++;
			if($check_old_email==2)
				$check_email=$check_old_email;
			elseif($check_email_af==2)
				$check_email=$check_email_af;
			$smarty->assign("check_email",$check_email);
		}
	
/************************************************************************************************************************
			Changed By:Shakti Srivastava
			Reason	  :Username and Re-Type password field will not be present in the new inputprofile
************************************************************************************************************************/
/*
		$check_user=check_username($Username);
		if($check_user)
		{
			$is_error++;
			$smarty->assign("check_user",$check_user);
		}
		
		$check_user1=isvalid_username($Username);
		if($check_user1)
		{
			$is_error++;
			$smarty->assign("check_user1",$check_user1);
		}
		
		$check_password1=check_password($Password1,$Username);
		if($check_password1)
		{
			$is_error++;
			$smarty->assign("check_password1",$check_password1);
		}
		
		$confirm_password=confirm_password($Password1,$Password2);
		if($confirm_password)
		{
			$is_error++;
			$smarty->assign("confirm_password",$confirm_password);
		}
*/
/********************************************************************************************************************/

		$Password1=trim($Password1);

		if($Password1=="" || strlen($Password1)>40 || strlen($Password1)<5)
		{
			$is_error++;
			$smarty->assign("check_password1","1");
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

		if($Marital_Status=="")
		{
			$is_error++;
			$smarty->assign("check_marital","Y");
		}
	
	/*	if(trim($Phone)=="" )
		{
			$is_error++;
			$smarty->assign("check_phone","Y");
		}*/
	  }	
     	  else
	  {
          	$is_error = 0;
		$check_email=checkemail($Email);
                $check_old_email=checkoldemail($Email);
                if($check_email || $check_old_email)
                {
                        $is_error++;
                        if($check_old_email==2)
                                $check_email=$check_old_email;
                        $smarty->assign("check_email",$check_email);
                }
		if($Year && $Month && $Day)
                {
                        $array = array($Year, $Month, $Day);
                        $date_of_birth= implode("-", $array);
                }
		
	  }	
     
          if($is_error > 0)
          {     
		maStripVARS("stripslashes");

		$smarty->assign("NO_OF_ERROR",$is_error);

		if($lang)
		{
			$religion=populate_religion_hin($Religion);
			$caste=populate_caste_hin($Caste);
			$income=create_dd_hin("$Income","Income");
			$city_india=create_dd_hin("$City_India","City_India");
			$country_residence=create_dd_hin($Country_Residence,"Country_Residence");
			$smarty->assign("top_country",create_dd_hin($Country_Residence,"top_country"));
			$mtongue=create_dd_hin($Mtongue,"Mtongue");

		}
		else
		{
			$religion=populate_religion($Religion);
			$caste=populate_caste($Caste);
			$income=create_dd("$Income","Income");
			$city_india=create_dd("$City_India","City_India");
			$country_residence=create_dd($Country_Residence,"Country_Residence");
			$smarty->assign("top_country",create_dd($Country_Residence,"top_country"));
			$mtongue=create_dd($Mtongue,"Mtongue");
		}

		$smarty->assign("religion",$religion);
		$smarty->assign("caste",$caste);

/*************************************************************************************************************************
*       MODIFIED BY          :  Gaurav Arora
*       DATE OF MODIFICATION :  25 July 2005
*       MODIFICATION         :  fields added for new input profile pages
*************************************************************************************************************************/
		$smarty->assign("income",$income);
//		$occupation=create_dd("$Occupation","Occupation");
//		$smarty->assign("occupation",$occupation);
//		$education_level=create_dd("$Education_Level","Education_Level_New");
//		$smarty->assign("education_level",$education_level);

		if($City_India)
			$smarty->assign("CITY_INDIA","Y");
		$smarty->assign("city_india",$city_india);

		if($City_USA)
                        $smarty->assign("CITY_USA","Y");
		$city_usa=create_dd("$City_USA","City_USA");
		$smarty->assign("city_usa",$city_usa);
		//$smarty->assign("cor",$cor);

		$smarty->assign("mobile",$Mobile);

//		$smarty->assign("information",$Information);
//		$smarty->assign("CHARACTERS",strlen($Information));
//		$smarty->assign("RADIOPRIVACY",$radioprivacy);

		$smarty->assign("CHECKBOXALERT1",$checkboxalert1);
		$smarty->assign("CHECKBOXALERT2",$checkboxalert2);
		//$smarty->assign("CHECKBOXALERT3",$checkboxalert3);

		if($Showmobile)
                {
                        $smarty->assign("showmobile","N");
                        $showphone="N";
                }
                else
                {
                        $smarty->assign("showmobile","Y");
                        $showphone="Y";
                }

		$smarty->assign("GENDERCHANGE","Y");
	
		$smarty->assign("mtongue",$mtongue);
		$smarty->assign("country_residence",$country_residence);

		//echo $height=create_dd($Height,"Height");
		$smarty->assign("height",$height);

		$smarty->assign("gender",$Gender);

		$smarty->assign("marital",$Marital_Status);

//		$smarty->assign("relationship",$Relationship);
//		$smarty->assign("username",stripslashes($Username));

		$smarty->assign("password1",$Password1);

//		$smarty->assign("password2",$Password2);

		if(!$GET_SMS)
                        $GET_SMS="N";
		$smarty->assign("GET_SMS",$GET_SMS);
		$smarty->assign("email",$Email);
		$smarty->assign("phone",$Phone);
		$smarty->assign("day",$Day);
		$smarty->assign("month",$Month);
		$smarty->assign("year",$Year);
		
		if($Showphone)
		{
			$smarty->assign("showphone","N");
			$showphone="N";
		}
		else
		{
			$smarty->assign("showphone","Y");
			$showphone="Y";
		}
		$smarty->assign("maritalstatus",$Marital_Status);
	//added by gaurav arora on 2 June 2006 for new input profile pages
	//echo "a=".$Caste;
	$smarty->assign("tempcaste",$Caste);

		$smarty->assign("HITSOURCE",$hit_source);	

		$smarty->assign("TIEUPSOURCE",$tieup_source);
		
		$smarty->assign("country_code",$Country_Code);
		$smarty->assign("country_code_mob",$Country_Code_Mob);
		$smarty->assign("state_code",$State_Code);
		
		$ccc=create_code("COUNTRY");
		$csc=create_code("CITY_INDIA");
		//$cuc=create_code("CITY_USA");
		$smarty->assign("country_isd_code",$ccc);
		$smarty->assign("india_std_code",$csc);
		//$smarty->assign("usa_city_code",$cuc);
		$smarty->assign("Annulled_Reason",htmlspecialchars($Annulled_Reason,ENT_QUOTES));
		if($lang)
		{
			$smarty->assign("FOOT",$smarty->fetch($lang."_foot.htm"));
			$smarty->assign("HEAD",$smarty->fetch($lang."_headnew.htm"));
			$smarty->display($lang."_inputprofile_tieupA.htm");
		}
		else
		{
			$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
			$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
			//added by sriram for testing
			$smarty->assign("testing",$testing);
			if($testing)
				$smarty->display("inputprofile_tieupA_revamp.htm");
			else
				$smarty->display("inputprofile_tieupA.htm");
		}
	}
	else
	{	
		if($Country_Code!='')
			$ISD=$Country_Code;
		elseif($Country_Code_Mob!='')
			$ISD=$Country_Code_Mob;
		//age calculation from DOB
		//echo $Year.$Month.$Day;
		$array = array($Year, $Month, $Day);
		if($date_of_birth)
		{
			$date_of_birth= implode("-", $array);
			$age=getAge($date_of_birth);
		}
		else
			$age=0;
		//echo $age;	
		$Religion_temp = explode('|X|',$Religion);
		$Religion = $Religion_temp[0];
		
		if($Showphone)
			$showphone="N";
		else
			$showphone="Y";

		// added by Gaurav Arora on 25 July for new template of input profile.
		if($Showmobile)
                        $showmobile="N";
                else
                        $showmobile="Y";	
		// end of code added for new template of input profile.

		if(!$GET_SMS)
                        $GET_SMS="N";	
		
		if($tieup_source=="")
			$tieup_source="IP";
			
		$today=date("Y-m-d H:i:s");


		if($Country_Residence==51)
			$City_Res=$City_India;
		elseif($Country_Residence==128)	
			$City_Res=$City_USA;
		else
			$City_Res="";

/*		if($hit_source!='O')            
			$Education_Level_Old=get_old_value($Education_Level,"EDUCATION_LEVEL_NEW");
		else
		{
			if($Education_Level)
				$Education_Level_Old=get_old_value($Education_Level,"EDUCATION_LEVEL_NEW");

		}
*/

		if(!$checkboxalert1)
			$checkboxalert1='U';
		if(!$checkboxalert2)
                        $checkboxalert2='U';
		if(!$checkboxalert3)
                        $checkboxalert3='U';

		while(1)
		{
			$Username=username_gen();
														    
			$sql="SELECT COUNT(*) as cnt FROM JPROFILE WHERE USERNAME='$Username'";
			$res_username=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			$row_username=mysql_fetch_array($res_username);
													    
			$sql="SELECT COUNT(*) as cnt FROM JPROFILE_AFFILIATE WHERE USERNAME='$Username'";
			$res_username2=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			$row_username2=mysql_fetch_array($res_username2);
													    
			if($row_username['cnt']==0 && $row_username2['cnt']==0)
				break;
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
			//$sql = "INSERT INTO JPROFILE_AFFILIATE(USERNAME,PASSWORD,EMAIL,RELATION,GENDER,MSTATUS,RELIGION,CASTE,MTONGUE,DTOFBIRTH,PHONE_RES,COUNTRY_RES,HEIGHT,ENTRY_DT,MOD_DT,LAST_LOGIN_DT,INCOMPLETE,SHOWPHONE_RES,AGE,IPADD,SOURCE,HAVEPHOTO,ACTIVATED,PROMO_MAILS,SERVICE_MESSAGES,PERSONAL_MATCHES,OFFLINE) VALUES ('".addslashes(stripslashes($Username))."','$Password1','$Email','$Relationship','$Gender','$Marital_Status','$Religion','$Caste','$Mtongue','$date_of_birth','$Phone','$Country_Residence','$Height',now(),now(),'$today','Y','$showphone','$age','$ip','$tieup_source','N','N','S','S','A','$off_line')"; 

/*			if($off_line=='Y')
			{
//				if(!($Gender||$Relationship||$Marital_Status||$Religion||$Caste||$Mtongue||$Country_Residence||$Height||$date_of_birth||$Information||$Education_Level_Old||$Occupation))
				if(!($Gender||$Marital_Status||$Religion||$Caste||$Country_Residence||$Height||$date_of_birth))
				{
					if($Gender=='M')
					{
						if(!$Income)
							$Incomplete='Y';
					}

					if(!($Phone && $Mobile))
					{
						$Incomplete='Y';
					}
					if($Country_Residence==51 || $Country_Residence==128)
					{
						if(!$City_Res)
							$Incomplete='Y';
					}
				}
			}
			else
				$Incomplete='N';
*/
			if($hit_source=='O')
			{       
//				$sql = "INSERT INTO JPROFILE_AFFILIATE(EMAIL,RELATION,GENDER,MSTATUS,RELIGION,CASTE,MTONGUE,DTOFBIRTH,PHONE_RES,COUNTRY_RES,HEIGHT,ENTRY_DT,MOD_DT,LAST_LOGIN_DT,SORT_DT,INCOMPLETE,SHOWPHONE_RES,AGE,IPADD,SOURCE,HAVEPHOTO,ACTIVATED,OFFLINE,YOURINFO,EDU_LEVEL,EDU_LEVEL_NEW,OCCUPATION,INCOME,CITY_RES,PRIVACY,PHONE_MOB,SHOWPHONE_MOB,PROMO_MAILS,SERVICE_MESSAGES,PERSONAL_MATCHES) VALUES ('$Email','$Relationship','$Gender','$Marital_Status','$Religion','$Caste','$Mtongue','$date_of_birth','$Phone','$Country_Residence','$Height',now(),now(),'$today',now(),'$Incomplete','$showphone','$age','$ip','$tieup_source','N','N','$off_line','".addslashes(stripslashes($Information))."','$Education_Level_Old','$Education_Level','$Occupation','$Income','$City_Res','$radioprivacy','$Mobile','$showmobile','$checkboxalert3','$checkboxalert2','$checkboxalert1')"; 
			 $sql = "INSERT INTO JPROFILE_AFFILIATE(EMAIL,USERNAME,GENDER,MSTATUS,RELIGION,CASTE,DTOFBIRTH,PHONE_RES,COUNTRY_RES,HEIGHT,ENTRY_DT,MOD_DT,LAST_LOGIN_DT,SORT_DT,INCOMPLETE,SHOWPHONE_RES,AGE,IPADD,SOURCE,HAVEPHOTO,ACTIVATED,OFFLINE,CITY_RES,PHONE_MOB,SHOWPHONE_MOB,INCOME,PERSONAL_MATCHES,SERVICE_MESSAGES,PROMO_MAILS,GET_SMS,STD,ISD,MTONGUE) VALUES ('$Email','$Username','$Gender','$Marital_Status','$Religion','$Caste','$date_of_birth','$Phone','$Country_Residence','$Height',now(),now(),'$today',now(),'Y','$showphone','$age','$ip','$tieup_source','N','N','$off_line','$City_Res','$Mobile','$showmobile','$Income','$checkboxalert1','$checkboxalert2','$checkboxalert3','$GET_SMS','$State_Code','$ISD'.'$Mtongue')"; 
			}
			else
			{
			       //				$sql = "INSERT INTO JPROFILE_AFFILIATE(USERNAME,PASSWORD,EMAIL,RELATION,GENDER,MSTATUS,RELIGION,CASTE,MTONGUE,DTOFBIRTH,PHONE_RES,COUNTRY_RES,HEIGHT,ENTRY_DT,MOD_DT,LAST_LOGIN_DT,SORT_DT,INCOMPLETE,SHOWPHONE_RES,AGE,IPADD,SOURCE,HAVEPHOTO,ACTIVATED,OFFLINE,YOURINFO,EDU_LEVEL,EDU_LEVEL_NEW,OCCUPATION,INCOME,CITY_RES,PRIVACY,PHONE_MOB,SHOWPHONE_MOB,PROMO_MAILS,SERVICE_MESSAGES,PERSONAL_MATCHES) VALUES ('".addslashes(stripslashes($Username))."','$Password1','$Email','$Relationship','$Gender','$Marital_Status','$Religion','$Caste','$Mtongue','$date_of_birth','$Phone','$Country_Residence','$Height',now(),now(),'$today',now(),'$Incomplete','$showphone','$age','$ip','$tieup_source','N','N','$off_line','".addslashes(stripslashes($Information))."','$Education_Level_Old','$Education_Level','$Occupation','$Income','$City_Res','$radioprivacy','$Mobile','$showmobile','$checkboxalert3','$checkboxalert2','$checkboxalert1')"; 
				$sql = "INSERT INTO JPROFILE_AFFILIATE(PASSWORD,EMAIL,USERNAME,GENDER,MSTATUS,RELIGION,CASTE,DTOFBIRTH,PHONE_RES,COUNTRY_RES,HEIGHT,ENTRY_DT,MOD_DT,LAST_LOGIN_DT,SORT_DT,INCOMPLETE,SHOWPHONE_RES,AGE,IPADD,SOURCE,HAVEPHOTO,ACTIVATED,OFFLINE,CITY_RES,PHONE_MOB,SHOWPHONE_MOB,INCOME,PERSONAL_MATCHES,SERVICE_MESSAGES,PROMO_MAILS,GET_SMS,STD,ISD,MTONGUE) VALUES ('$Password1','$Email','$Username','$Gender','$Marital_Status','$Religion','$Caste','$date_of_birth','$Phone','$Country_Residence','$Height',now(),now(),'$today',now(),'Y','$showphone','$age','$ip','$tieup_source','N','N','$off_line','$City_Res','$Mobile','$showmobile','$Income','$checkboxalert1','$checkboxalert2','$checkboxalert3','$GET_SMS','$State_Code','$ISD','$Mtongue')"; 
			}
                                                                                                 
			 mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		}
		else
		{
				$sql="select LABEL from HEIGHT where VALUE='$Height'";
	                        $res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        	                $myrow_height=mysql_fetch_array($res);
                	        $height_label=$myrow_height["LABEL"];
                        	$height_label=substr($height_label,0,10);

				if($City_India!='')
	                              $sql="select LABEL FROM CITY_NEW where VALUE='$City_Res'";
        	                elseif($City_USA!='')
                	              $sql="select LABEL FROM CITY_NEW where VALUE='$City_Res'";
                        	$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

	                        $myrow_city=mysql_fetch_array($res);
        	                $city_label=$myrow_city["LABEL"];

				if($Gender=='M') 
        	                        $gender="Male";
	                        elseif($Gender=='F')
                	                $gender="Female";

				$keyword=addslashes(stripslashes($gender.",".$age.",".$Caste_label.",".$height_label.",".$city_label));


			//query changed by Gaurav Arora on 25 July for new input profile pages.
//			$sql = "INSERT INTO JPROFILE(USERNAME,PASSWORD,EMAIL,RELATION,GENDER,MSTATUS,RELIGION,CASTE,MTONGUE,DTOFBIRTH,PHONE_RES,COUNTRY_RES,HEIGHT,ENTRY_DT,MOD_DT,LAST_LOGIN_DT,SORT_DT,INCOMPLETE,SHOWPHONE_RES,AGE,IPADD,SOURCE,HAVEPHOTO,ACTIVATED,YOURINFO,EDU_LEVEL,EDU_LEVEL_NEW,OCCUPATION,INCOME,CITY_RES,PRIVACY,PHONE_MOB,SHOWPHONE_MOB,PROMO_MAILS,SERVICE_MESSAGES,PERSONAL_MATCHES) VALUES ('".addslashes(stripslashes($Username))."','$Password1','$Email','$Relationship','$Gender','$Marital_Status','$Religion','$Caste','$Mtongue','$date_of_birth','$Phone','$Country_Residence','$Height',now(),now(),'$today',now(),'N','$showphone','$age','$ip','$tieup_source','N','N','".addslashes(stripslashes($Information))."','$Education_Level_Old','$Education_Level','$Occupation','$Income','$City_Res','$radioprivacy','$Mobile','$showmobile','$checkboxalert3','$checkboxalert2','$checkboxalert1')";
			//$sql = "INSERT INTO JPROFILE(USERNAME,PASSWORD,EMAIL,RELATION,GENDER,MSTATUS,RELIGION,CASTE,MTONGUE,DTOFBIRTH,PHONE_RES,COUNTRY_RES,HEIGHT,ENTRY_DT,MOD_DT,LAST_LOGIN_DT,INCOMPLETE,SHOWPHONE_RES,AGE,IPADD,SOURCE,HAVEPHOTO,ACTIVATED,PROMO_MAILS,SERVICE_MESSAGES,PERSONAL_MATCHES) VALUES ('".addslashes(stripslashes($Username))."','$Password1','$Email','$Relationship','$Gender','$Marital_Status','$Religion','$Caste','$Mtongue','$date_of_birth','$Phone','$Country_Residence','$Height',now(),now(),'$today','Y','$showphone','$age','$ip','$tieup_source','N','N','S','S','A')";

			$sql = "INSERT INTO JPROFILE(USERNAME,PASSWORD,EMAIL,GENDER,MSTATUS,RELIGION,CASTE,DTOFBIRTH,PHONE_RES,COUNTRY_RES,HEIGHT,ENTRY_DT,MOD_DT,LAST_LOGIN_DT,SORT_DT,INCOMPLETE,SHOWPHONE_RES,AGE,IPADD,SOURCE,HAVEPHOTO,ACTIVATED,CITY_RES,PHONE_MOB,SHOWPHONE_MOB,INCOME,KEYWORDS,PERSONAL_MATCHES,SERVICE_MESSAGES,PROMO_MAILS,GET_SMS,STD,ISD,MTONGUE) VALUES ('$Username','$Password1','$Email','$Gender','$Marital_Status','$Religion','$Caste','$date_of_birth','$Phone','$Country_Residence','$Height',now(),now(),'$today',now(),'Y','$showphone','$age','$ip','$tieup_source','N','N','$City_Res','$Mobile','$showmobile','$Income','$keyword','$checkboxalert1','$checkboxalert2','$checkboxalert3','$GET_SMS','$State_Code','$ISD','$Mtongue')";
			mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");

/***********************************************************************************************************************/

		}
	    
		$id=mysql_insert_id_js();
		if($Marital_Status=='A')
                {
                        $areason=htmlspecialchars($Annulled_Reason,ENT_QUOTES);
                        $sql_a="insert into newjs.ANNULLED(PROFILEID,REASON,ENTRY_DT,SCREENED) values('$id','$areason',now(),'$ANNULLED_SCREENED')";
                        mysql_query_decide($sql_a) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_a,"ShowErrTemplate");

                }
		if($lang)
		{
			$sql="INSERT INTO MIS.LANG_REGISTER VALUES ('','$id','$lang')";
			mysql_query_decide($sql);
		}

/************************************************************************************************************************
		ADDED BY	:	SHAKTI SRIVASTAVA
		DATE		:	29 SEPTEMBER, 2005
		REASON		:	TO GENERATE USERID AUTOMATICALLY AND SUBSEQUENTLY UPDATE TABLES AFTER 
				:	GENERATING THE USERID
************************************************************************************************************************/

		$sql_incomp="INSERT IGNORE INTO newjs.INCOMPLETE_PROFILES VALUES('$id',now())";
		mysql_query_decide($sql_incomp) or logError("Due to some temporary problem your request could not be processed. Please try after some time.".mysql_error_js(),$sql_incomp,"ShowErrTemplate");

/*************************************************************************************************************************
		ADDED BY 	:	AMAN SHARMA 
		REASON		:	TO SYNCHRONISE CHANGES IN NAMES TABLE
**************************************************************************************************************************/
		if($hit_source!='O')            
		{    
			$sql_name_insert="INSERT INTO NAMES VALUES ('$Username')";
                 	mysql_query_decide($sql_name_insert) or logError("NAMES TABLE ENTRY NOT DONE.",$sql_name_insert,"ShowErrTemplate");
	        }  
/**********************************END OF CHANGES************************************************************************/

		
		$tm=time();

/***********************************************************************************************************************
		ADDED BY	::	GAURAV ARORA 
		ADDED ON 	:: 	27 JULY 2005	
		REASON	 	:: 	FIELDS TO BE INSERTED IN JPARTNER TABLE TO SAVE HHEIGHT, LHEIGHT AS HEIGHT+10,
				::	HEIGHT-10 TO SAVE HAGE, LAGE as AGE+7, AGE-7.
************************************************************************************************************************/
		
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
                        //added by sriram - if maximum height is selected,then hheight should be the maximum height.
                        if($Height > $hheight)
                                $hheight = 32;
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
                        //added by sriram - if maximum age is selected,then hage should be the maximum age.
                        if($hage > 70)
                                $hage = 70;
		}
		//echo $lage.$hage.$lheight.$hheight;
		if((substr($tieup_source,0,2))=='af' || $hit_source=='O' || $email_validation=='Y')
		{
			if($Gender=='M')
        	                $sql_jpartner="INSERT into JPARTNER_PAGE3(PROFILEID,GENDER,LAGE,HAGE,LHEIGHT,HHEIGHT,DATE) values ('$id','F','$lage','$hage','$lheight','$hheight',now())";
                	elseif($Gender=='F')
                        	$sql_jpartner="INSERT into JPARTNER_PAGE3(PROFILEID,GENDER,LAGE,HAGE,LHEIGHT,HHEIGHT,DATE) values ('$id','M','$lage','$hage','$lheight','$hheight',now())";
			else
                        	$sql_jpartner="INSERT into JPARTNER_PAGE3(PROFILEID,GENDER,LAGE,HAGE,LHEIGHT,HHEIGHT,DATE) values ('$id','','$lage','$hage','$lheight','$hheight',now())";
                                                                                                 
	                mysql_query_decide($sql_jpartner) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_jpartner,"ShowErrTemplate");

			$checksum_aff=md5($id) . "i" . $id;

			$smarty->assign("ID_AFF",$id);
		}
		else
		{
			/**************************** Sharding done by Sadaf : start ***********************************/
			$myDbName=getProfileDatabaseConnectionName($id,'',$mysqlObj);
			$myDb=$mysqlObj->connect("$myDbName");
			if($Gender=='M')
				$jpartnerObj->setGENDER('F');
			else
				$jpartnerObj->setGENDER('M');
			$jpartnerObj->setPROFILEID($id);
			$jpartnerObj->setLAGE($lage);
			$jpartnerObj->setHAGE($hage);
			$jpartnerObj->setLHEIGHT($lheight);
			$jpartnerObj->setHHEIGHT($hheight);
			$jpartnerObj->setDPP('R');

			$jpartnerObj->updatePartnerDetails($myDb,$mysqlObj);
			/***************************** Sharding done by Sadaf : end ************************************/

			$cookies['PROFILEID']=$id;
                        $cookies['USERNAME']=$Username;
                        $cookies['GENDER']=$Gender;
                        $cookies['SUBSCRIPTION']='';
                        $cookies['ACTIVATED']='N';

                        $protect_obj->setcookies($cookies);
                        //$checksum=(md5($id)."i".$id);
                        $checksum=$protect_obj->js_encrypt(md5($id)."i".$id);   //just modified
		}

		setcookie("JS_SOURCE","",0,"/");
	       
		if((substr($tieup_source,0,2))=='af' || $hit_source=='O' || $email_validation=='Y')
		{
			$sql="select EMAIL,USERNAME,PASSWORD from JPROFILE_AFFILIATE where ID='$id'";
                        $result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
                        $myrow1=mysql_fetch_array($result);

			if($myrow1['USERNAME'])
				$msg="Dear $myrow1[USERNAME]<br><br>Thank you for registering on JeevanSathi.com and becoming an esteemed member of this family.<br><br>Now you are just one step away from your dream life partner. Just click on the following link to activate your matrimonial profile on our website.<br><br>This will make your profile visible to the members according to the privacy preferences you have chosen. Hence we request you to activate your matrimonial profile.<br><br><a href=\"$SITE_URL/profile/validate_function.php?checksum=$checksum_aff\" target=\"_blank\">Activate</a><br><br>(IMPORTANT: Your profile will not be displayed on our website if you ignore the activation process.)<br><br>You can use the following Username and Password to login<br>Username: $myrow1[USERNAME]<br>Password: $myrow1[PASSWORD]<br><br>Hope to see you on JeevanSathi.com soon!<br><br>With warm Regards<br>The JeevanSathi.com Team<br><a href=\"http://www.jeevansathi.com\" target=\"_blank\">www.jeevansathi.com</a>";
			else
				$msg="Dear Member<br><br>Thank you for registering on JeevanSathi.com and becoming an esteemed member of this family.<br><br>Now you are just one step away from your dream life partner. Just click on the following link to activate your matrimonial profile on our website.<br><br>This will make your profile visible to the members according to the privacy preferences you have chosen. Hence we request you to activate your matrimonial profile.<br><br><a href=\"$SITE_URL/profile/validate_function.php?checksum=$checksum_aff\" target=\"_blank\">Activate</a><br><br>(IMPORTANT: Your profile will not be displayed on our website if you ignore the activation process.)<br><br>Hope to see you on JeevanSathi.com soon!<br><br>With warm Regards<br>The JeevanSathi.com Team<br><a href=\"http://www.jeevansathi.com\" target=\"_blank\">www.jeevansathi.com</a>";

	                send_email($myrow1["EMAIL"],$msg,"JeevanSathi.com-Account Activation","register@jeevansathi.com","","","","","","Y");

//	                send_email1("shakti.srivastava@Techteam2.localhost.localdomain",$msg,"JeevanSathi.com-Account Activation","register@jeevansathi.com");
		}
		else
		{
			$sql="select EMAIL,USERNAME,PASSWORD from JPROFILE where PROFILEID=$id";
                        $result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
                        $myrow1=mysql_fetch_array($result);

			$smarty->assign("EMAIL",$myrow1["EMAIL"]);
                        $smarty->assign("USERNAME",$myrow1["USERNAME"]);
                        $smarty->assign("PASSWORD",$myrow1["PASSWORD"]);

			$msg = $smarty->fetch("automated_response.htm");

	                send_email($myrow1["EMAIL"],$msg,"Thank you for registering with jeevansathi.com ","register@jeevansathi.com","","","","","","Y");

//	                send_email1("shakti.srivastava@Techteam2.localhost.localdomain",$msg,"Thank you for registering with jeevansathi.com ","register@jeevansathi.com");
		}


		$smarty->assign("TIEUPSOURCE",$tieup_source);

		if($Showphone)
                {
                        $smarty->assign("showphone","N");
                        $showphone="N";
                }
                else
                {
                        $smarty->assign("showphone","Y");
                        $showphone="Y";
                }


		showPartB($checksum,$Gender,$hit_source,$Marital_Status,$tieup_source,$Religion,$Caste,$Height,$Day,$Month,$Year,$Phone,$Mobile,$Country_Residence,$City_India,$City_USA,$Email,$showphone,$showmobile,$Income,$Username,$id);
        }
}
else
{
	/*if($tieupemail)
	{
		$sql="insert into TIEUPEMAIL(EMAIL) values ('" . addslashes($tieupemail) . "')";
		mysql_query_decide($sql);
		
		$smarty->assign("email",$tieupemail);
	}*/

	if($submit_google)
	{
		$sql = "INSERT INTO PROFILE_BRIEF(NAME,AGE,GENDER,MSTATUS,CASTE,EMAIL) Values ('".addslashes(stripslashes($username))."','$age','$gender_profile','$Marital_Status','$Caste','$email')";
		mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		
		$smarty->assign("username",$username);
		$smarty->assign("gender",$gender_profile);
		$smarty->assign("marital",$Marital_Status);
		if($lang)
			$smarty->assign("caste",create_dd_hin("$Caste","Caste"));
		else
			$smarty->assign("caste",create_dd("$Caste","Caste"));
		$smarty->assign("email",$email);
	}	

	if($lang)
	{
		$smarty->assign("religion",populate_religion_hin(1));
		$smarty->assign("mtongue",create_dd_hin("","Mtongue"));
		$smarty->assign("country_residence",create_dd_hin("","Country_Residence"));
		$smarty->assign("top_country",create_dd_hin("51","top_country"));
		$city_india=create_dd_hin("","City_India");	
	//	$smarty->assign("family_back",create_dd_hin("","Family_Back"));
	}
	else
	{
		$smarty->assign("religion",populate_religion(1));
		$smarty->assign("mtongue",create_dd("","Mtongue"));
		$smarty->assign("country_residence",create_dd("","Country_Residence"));
		$smarty->assign("top_country",create_dd("51","top_country"));
		$city_india=create_dd("","City_India");	
	//	$smarty->assign("family_back",create_dd("","Family_Back"));
	}
	$smarty->assign("height",create_dd(8,"Height"));

	//added by gaurav arora on 25 July for new input profile pages
	$smarty->assign("maritalstatus",$Marital_Status);
	//added by gaurav arora on 2 June 2006 for new input profile pages
	//echo "a=".$Caste;
	$smarty->assign("tempcaste",$Caste);

	$income=create_dd("","Income");
        $smarty->assign("income",$income);

/*      $occupation=create_dd("","Occupation");
        $smarty->assign("occupation",$occupation);
	$education_level=create_dd("","Education_Level_New");
        $smarty->assign("education_level",$education_level);
*/
	$smarty->assign("city_india",$city_india);

        $city_usa=create_dd("","City_USA");
        $smarty->assign("city_usa",$city_usa);
	//echo $city_usa[2];

//        $smarty->assign("cor",$cor);

	$smarty->assign("CHECKBOXALERT1","A");
	$smarty->assign("CHECKBOXALERT2","S");
	$smarty->assign("CHECKBOXALERT3","S");

/*	end of new code added for new input profile pages*/


/*	code added by Nikhil Tandon to add a hidden field countrycodes seprated by $*/
	$ccc=create_code("COUNTRY");
	$csc=create_code("CITY_INDIA");
	//$cuc=create_code("CITY_USA");
	$smarty->assign("country_isd_code",$ccc);
	$smarty->assign("india_std_code",$csc);
	//$smarty->assign("usa_city_code",$cuc);
/*	code ends for add a hidden field countrycodes seprated by $*/

	
	$smarty->assign("showphone","Y");
	$smarty->assign("showmobile","Y");
	$smarty->assign("once","T");
	$smarty->assign("TIEUPSOURCE",$source);

//	$smarty->assign("RADIOPRIVACY","A");

	$smarty->assign("HITSOURCE",$hit_source);	
	//$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));

	if($lang)
	{
		$smarty->assign("FOOT",$smarty->fetch($lang."_foot.htm"));
		$smarty->assign("HEAD",$smarty->fetch($lang."_headnew.htm"));
		$smarty->display($lang."_inputprofile_tieupA.htm");
	}
	else
	{
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
		//added by sriram for testing
		$smarty->assign("testing",$testing);
		if($testing)
			$smarty->display("inputprofile_tieupA_revamp.htm");
		else
			$smarty->display("inputprofile_tieupA.htm");
	}
}
/*************************************************************************************************************************
*	FUNCTION NAME	:	showPartB()
*	DESCRIPTION	:	includes inputprofile_tieupB.php and displays Page1-PartB
*	ADDED BY	:	SHAKTI SRIVASTAVA
*************************************************************************************************************************/

//function showPartB($checksum,$Gender,$hit_source,$Marital_Status,$tieup_source,$Religion,$Caste,$Height,$Day,$Month,$Year,$Phone,$Mobile,$Country_Residence,$City_India,$City_USA,$Email,$showphone,$showmobile,$Income,$Username,$id="")

//modified by sriram for testing
function showPartB($checksum,$Gender,$hit_source,$Marital_Status,$tieup_source,$Religion,$Caste,$Height,$Day,$Month,$Year,$Phone,$Mobile,$Country_Residence,$City_India,$City_USA,$Email,$showphone,$showmobile,$Income,$Username,$id="",$testing)
{
	global $smarty;
	include("inputprofile_tieupB.php");
}


/***********************************************************************************************************************
Changed By	: Gaurav Arora
Reason		: The function was required in order to send mail to the user after registration.
***********************************************************************************************************************/

function send_email1($email,$msg,$subject,$from)
{
	if(!stristr($email,"@jsxyz.com"))
	{
		$boundry = "b".md5(uniqid(time()));
		$MP = "/usr/sbin/sendmail -t  ";
		$spec_envelope = 1;
		if($spec_envelope)
		{
			$MP .= " -N never -R hdrs -f $from";
		}
		$fd = popen($MP,"w");
		fputs($fd, "X-Mailer: PHP3\n");
		fputs($fd, "MIME-Version:1.0 \n");
		fputs($fd, "To: $email\n");
		fputs($fd, "From: $from \n");
		fputs($fd, "Subject: $subject \n");
		fputs($fd, "Content-Type: text/html; boundary=$boundry\n");
		fputs($fd, "Content-Transfer-Encoding: 7bit \r\n");
		fputs($fd, "$msg\r\n");
		fputs($fd, "\r\n . \r\n");
		$p=pclose($fd);
		return $p;
	}
}

function get_code($tablename,$value)
{
	$sql = "select CODE from newjs.$tablename where VALUE='$value'";
	$res = mysql_query_decide($sql) or logError("Error in getting code value",$sql);
	$myrow = mysql_fetch_array($res);	
	$code=$myrow['CODE'];
	return $code;
}

// flush the buffer
if($zipIt)
	ob_end_flush();
?>
