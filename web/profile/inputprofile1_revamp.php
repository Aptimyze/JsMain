<?php

/*********************************************************************************************
* FILE NAME   : inputprofile1.php
* DESCRIPTION : Get details for a new profile
* MODIFY DATE        : 19 May, 2005
* MODIFIED BY        : AMAN SHARMA
* REASON             : Changes made due to three page input structure                                                                                 
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
$http_msg=print_r($_SERVER,true);
//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//end of it

require_once("connect.inc");
include_once("manglik.php");
include_once(JsConstants::$docRoot."/commonFiles/flag.php");

$db=connect_db();

$lang=$_COOKIE['JS_LANG'];
if($lang=="deleted")
	$lang="";

/****  check for banner sources*****/
                                                                                                 
$sql="SELECT FORCE_EMAIL,GROUPNAME FROM MIS.SOURCE WHERE SOURCEID = '$tieup_source'";
$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
$row=mysql_fetch_array($result);
$force_mail=$row["FORCE_EMAIL"];
if($force_mail=='Y')
{
        $email_validation='Y';
}
$smarty->assign("groupname",$row['GROUPNAME']);
                                                                                              
/********/



if((substr($tieup_source,0,2))=='af' || $hit_source=='O' || $email_validation=='Y')
	$data=$id;
else
	$data=authenticated($checksum,'y');
if($data["BUREAU"]==1 && ($_COOKIE['JSMBLOGIN'] || $mbureau=="bureau"))
{
        $fromprofilepage=1;
        mysql_select_db_js('marriage_bureau');
        include_once('../marriage_bureau/connectmb.inc');
        $mbdata=authenticatedmb($mbchecksum);
        if(!$mbdata)timeoutmb();
        $smarty->assign("source",$mbdata["SOURCE"]);
        $smarty->assign("mbchecksum",$mbdata["CHECKSUM"]);
        mysql_select_db_js('newjs');
        //$data=login_every_user($profileid);
        $mbureau="bureau1";
}
$smarty->assign("CAMEFROMHOMEPAGE","1");
$smarty->assign("maritalstatus",$maritalstatus);


//Set the text on the basis of profile posted by whom//
edu_occ();

//Setting text ends here



/***********************************************************************************************************************
			Added By	: Shakti Srivastava
			Reason		: For a "<img src>" tag being added to the templates which has to be passed
					: user's profileid
***********************************************************************************************************************/
		if((substr($tieup_source,0,2))=='af' || $hit_source=='O' || $email_validation=='Y')
                {
			$sql="SELECT USERNAME FROM newjs.JPROFILE_AFFILIATE WHERE ID='$id'";
			$res=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			$row=mysql_fetch_array($res);

                        $smarty->assign("uniqueid",$row['USERNAME']);
                }
                else
		{
                        $smarty->assign("uniqueid",$data["USERNAME"]);
		}
/***********************************************************************************************************************/
//Code Added by Nikhil//
//// Code to show manglik text on the basis of Mother tougue , this function declared in manglik.php//
$return=manglik($data['PROFILEID']);
$manglik_data=explode("+",$return);
$smarty->assign("Manglik",$manglik_data[1]);
////code ends here//
if($Submit)
{
  	
//$data=authenticated($checksum);
	if(isset($data))
	{
		if((substr($tieup_source,0,2))=='af' || $hit_source=='O' || $email_validation=='Y')
		{
			$profileid=$id;
			$smarty->assign("ID_AFF",$id);
		}
		else
			$profileid=$data["PROFILEID"];

		if($hit_source!='O')
	   	{ 		
			//blank entries validation
			$is_error=0;
			if($Body_Type=="")
			{
				$is_error++;
				$smarty->assign("check_bodytype","Y");
			}
			if($Complexion=="")
			{
				$is_error++;
				$smarty->assign("check_complexion","Y");
			}
			if($Smoke=="")
                        {
                                $is_error++;
                                $smarty->assign("check_smoke","Y");
                        }
                        if($Diet=="")
                        {
                                $is_error++;
                                $smarty->assign("check_diet","Y");
                        }
                        if($Drink=="")
                        {
                                $is_error++;
                                $smarty->assign("check_drink","Y");
                        }
			if (!$display_horo)
                        {
				$is_error++;
				$smarty->assign("check_horo","Y");
			}
			if($Manglik_Status=="")
			{
				$is_error++;
				$smarty->assign("check_manglik","Y");
			}
			if($mbureau!="bureau1")
                        {
				if(trim($Address)=="")
				{
					$is_error++;
					$smarty->assign("check_address","Y");
				}
			}		
		}	
		else
		{
			$is_error=0;
		}
		//added by sriram to add skip functionality.
		if($skip_your_info)
		{
			$is_error=0;
		}
		if($lang)
		{
			$smarty->assign("HEAD",$smarty->fetch($lang."_headnew.htm"));
			$smarty->assign("TOPLEFT",$smarty->fetch($lang."_topleft.htm"));
			$smarty->assign("LEFTPANEL",$smarty->fetch($lang."_leftpanel.htm"));
			$smarty->assign("FOOT",$smarty->fetch($lang."_foot.htm"));
			$smarty->assign("SUBFOOTER",$smarty->fetch($lang."_subfooter.htm"));
		}
		else
		{
			if($mbureau=="bureau1")
                        {
                                $smarty->assign("mb_username_profile",$data["USERNAME"]);
                                $smarty->assign("checksum",$data["CHECKSUM"]);
                                $smarty->assign("HEAD",$smarty->fetch("top_band.htm"));
                                $smarty->assign("LEFTPANEL",$smarty->fetch("mb_side_links.htm"));                        }
                        else
                        {
                                $smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
                                $smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
                                $smarty->assign("LEFTPANEL",$smarty->fetch("leftpanel.htm"));
                        }
                        $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
                        $smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
		}

	   	if($is_error > 0)
	    	{
			// remove slashes
			maStripVARS("stripslashes");

	    		$smarty->assign("NO_OF_ERROR",$is_error);
	    		$smarty->assign("hit_source",$hit_source);
	    		$smarty->assign("tieup_source",$tieup_source);
            		
		        $smarty->assign("gender",$gender);
			$sql="SELECT USERNAME,MTONGUE FROM JPROFILE WHERE PROFILEID='".$data['PROFILEID']."'";
                        $res = mysql_query_decide($sql) or logError("Error in getting code value",$sql);
                        $row = mysql_fetch_array($res);
                        $nak_array=loadnakshatra($row['MTONGUE'],$Nakshatram);
                        $smarty->assign("nak_array",$nak_array);
			$smarty->assign("country_birth",create_dd($Country_Birth,"Country_Birth"));
			$smarty->assign("top_country",create_dd($Country_Birth,"top_country"));
			$family_back=create_dd($Family_Back,"Family_Back");
	                
			$smarty->assign("educ_qualification",$Educ_Qualification);
			$smarty->assign("smoke",$Smoke);
			$smarty->assign("drink",$Drink);
			$smarty->assign("diet",$Diet);
			
			$smarty->assign("body",$Body_Type);
			$smarty->assign("manglik",$Manglik_Status);
			$smarty->assign("complexion",$Complexion);      
		        $smarty->assign("phyhcp",$Phyhcp);
			$smarty->assign("job",$Job);
			$smarty->assign("city_birth",$City_Birth);
			$smarty->assign("hour_birth",$Hour_Birth);
                        $smarty->assign("min_birth",$Min_Birth);
                        $smarty->assign("nakshatram",$Nakshatram);
			$smarty->assign("address",$Address);
			$smarty->assign("SHOWADDRESS",$showAddress);
			$smarty->assign("messenger",$Messenger);
                        $smarty->assign("messenger_id",$Messenger_ID);
			$smarty->assign("SHOWMESSENGER",$showMessenger);
			$smarty->assign("USERNAME",$row['USERNAME']);
			$smarty->assign("SMALLFOOTER",$smarty->fetch("smallfooter.htm"));

/*********************************************************************************************************************
			CHANGED BY	:	SHAKTI SRIVASTAVA
			CHANGE DATE	:	5 OCTOBER, 2005
			REASON		:	CHANGES WERE MADE FOR NEW FIELD (DISPLAY HOROSCOPE)
*********************************************************************************************************************/
			$smarty->assign("display_horo",$display_horo);
/*********************************************************************************************************************/
			$smarty->assign("js_UniqueID",$js_UniqueID);
                        $smarty->assign("BIRTH_YR",$BIRTH_YR);
                        $smarty->assign("BIRTH_MON",$BIRTH_MON);
                        $smarty->assign("BIRTH_DAY",$BIRTH_DAY);	
			$smarty->assign("CHECKSUM",$checksum);
			$smarty->assign("SMALL_HEAD",$smarty->fetch("small_headnew.htm"));

			if($lang)
				$smarty->display($lang."_inputprofile1.htm");
			else
				$smarty->display("inputprofile1_revamp.htm");
				//$smarty->display("inputprofile1.htm");
	    	}
	    	else 
	    	{
			if(!$skip_your_info)
			{
				//$Gothra=redo_gothra($Gothra);
				$btime=$Hour_Birth.":".$Min_Birth;
				if(!$showAddress)
					$showAddress="Y";
													 
				if(!$showMessenger)
					$showMessenger="Y";

		
				$today= CommonUtility::makeTime(date("Y-m-d"));
				
				if((substr($tieup_source,0,2))=='af' || $hit_source=='O' || $email_validation=='Y')
				{
					$table_name='newjs.JPROFILE_AFFILIATE';
					$id_name='ID';
				}
				else
				{
					$table_name='newjs.JPROFILE';
					$id_name='PROFILEID';
				}

				$sql_mtongue = "SELECT SCREENING,MTONGUE FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
				$res_mtongue = mysql_query_decide($sql_mtongue) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_mtongue,"ShowErrTemplate");
				$row_mtongue = mysql_fetch_array($res_mtongue);
				$mtongue = $row_mtongue['MTONGUE'];
				$current_screening_flag = $row_mtongue['SCREENING'];

				if ($display_horo == 'Y')
				{
					/*$sql = "INSERT INTO newjs.ASTRO_PULLING_REQUEST (PROFILEID,ENTRY_DT,PENDING,TYPE) VALUES('$profileid',NOW(),'Y','I')";
					mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");*/

					$sql = "INSERT INTO MIS.ASTRO_COMMUNITY_WISE (PROFILEID,MTONGUE,ENTRY_DT) VALUES('$profileid','$mtongue',NOW())";
					mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
					//end of - added by sriram for finding user's mtongue and storing it for tracking.
				}


	/*********************************************************************************************************************
				CHANGED BY	:	SHAKTI SRIVASTAVA
				CHANGE DATE	:	5 OCTOBER, 2005
				REASON		:	CHANGES WERE MADE FOR NEW FIELD (DISPLAY HOROSCOPE)
	*********************************************************************************************************************/
				//$sql = "UPDATE $table_name SET BTYPE='$Body_Type',COMPLEXION='$Complexion',HANDICAPPED='$Phyhcp',RES_STATUS='$Rstatus', COUNTRY_BIRTH='$Country_Birth',EDUCATION='".addslashes(stripslashes($Educ_Qualification))."', SMOKE='$Smoke', DRINK='$Drink', DIET='$Diet',SUBCASTE='".addslashes(stripslashes($Subcaste))."',MANGLIK='$Manglik_Status',HAVECHILD='$Has_Children',FAMILY_BACK='$Family_Back',FATHER_INFO='".addslashes(stripslashes($Father_Info))."',SIBLING_INFO='".addslashes(stripslashes($Sibling_Info))."',JOB_INFO='".addslashes(stripslashes($Job))."',CITY_BIRTH='".addslashes(stripslashes($City_Birth))."',BTIME='$btime',NAKSHATRA='".addslashes(stripslashes($Nakshatram))."',GOTHRA='".addslashes(stripslashes($Gothra))."',FAMILY_VALUES='$Family_Values',PARENT_CITY_SAME='$Parent_City_Same',CONTACT='$Address',PINCODE='$pincode',SHOWADDRESS='$showAddress', SHOWMESSENGER='$showMessenger',PARENTS_CONTACT='".addslashes(stripslashes($Parents_Contact))."',SHOW_PARENTS_CONTACT='$showParentsContact',FATHER_INFO='".addslashes(stripslashes($Father_Info))."',SIBLING_INFO='".addslashes(stripslashes($Sibling_Info))."',FAMILYINFO='".addslashes(stripslashes($Family))."',MESSENGER_ID='$Messenger_ID', MESSENGER_CHANNEL='$Messenger', MOD_DT=now(), LAST_LOGIN_DT='$today', SORT_DT=now(), SHOW_HOROSCOPE='$display_horo' WHERE $id_name='$profileid'";
			

				if($Educ_Qualification)
					$current_screening_flag = removeFlag("EDUCATION",$current_screening_flag);
				if($Job)
					$current_screening_flag = removeFlag("JOB_INFO",$current_screening_flag);
				if($City_Birth)
					$current_screening_flag = removeFlag("CITYBIRTH",$current_screening_flag);
				if($Address)
					$current_screening_flag = removeFlag("CONTACT",$current_screening_flag);
				if($Messenger)
					$current_screening_flag = removeFlag("MESSENGER",$current_screening_flag);

				if(($_COOKIE['OPERATOR']!="deleted" && $_COOKIE['OPERATOR']!="")  || $tieup_source=='ofl_prof')
                                {
                                        $current_screening_flag=131071;
					send_email("nikhil.dhiman@jeevansathi.com","test message","3--$profileid---$_COOKIE[OPERATOR]-----$source-----$tieup_source","offline@jeevansathi.com");
                                }

				//changed by Gaurav on 16 May 2006 for new input profile page.
				$sql = "UPDATE $table_name SET BTYPE='$Body_Type',COMPLEXION='$Complexion',HANDICAPPED='$Phyhcp', COUNTRY_BIRTH='$Country_Birth',EDUCATION='".addslashes(stripslashes($Educ_Qualification))."', SMOKE='$Smoke', DRINK='$Drink', DIET='$Diet',MANGLIK='$Manglik_Status',JOB_INFO='".addslashes(stripslashes($Job))."',CITY_BIRTH='".addslashes(stripslashes($City_Birth))."',BTIME='$btime',NAKSHATRA='".addslashes(stripslashes($Nakshatram))."',CONTACT='$Address',SHOWADDRESS='$showAddress', SHOWMESSENGER='$showMessenger',MESSENGER_ID='$Messenger_ID', MESSENGER_CHANNEL='$Messenger', MOD_DT=now(), LAST_LOGIN_DT='$today', SORT_DT=now(), SHOW_HOROSCOPE='$display_horo', SCREENING='$current_screening_flag' WHERE $id_name='$profileid'";

				mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
	//$result= mysql_query_decide($sql) or mysql_error_js();
		     
				$smarty->assign("CHECKSUM",$checksum);
				if($table_name=='newjs.JPROFILE')
				{
					//added by Neha Verma for archiving contact info

					$arr_ip=explode(",",getenv("HTTP_X_FORWARDED_FOR"));
					if($arr_ip[1]!="")
					        $ip=$arr_ip[1];
					else
        					$ip=FetchClientIP(); //Gets ipaddress of user

		                        //Address
					if($Address)
					{
        		                	$sql_id= "INSERT INTO newjs.CONTACT_ARCHIVE (PROFILEID,FIELD) VALUES($profileid,'CONTACT')";
        	        	        	$res_id= mysql_query_decide($sql_id) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_id,"ShowErrTemplate");

        	        	        	$changeid=mysql_insert_id_js();
        	        	        	$sql_info= "INSERT INTO newjs.CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,NEW_VAL) VALUES($changeid,now(),'$ip','$Address')";
        	        	        	$res_info= mysql_query_decide($sql_info) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
					}
        	        	        //Messenger
					if($Messenger_ID && $Messenger)
					{
        	        	        	$sql_id_ph= "INSERT INTO newjs.CONTACT_ARCHIVE (PROFILEID,FIELD) VALUES($profileid,'MESSENGER')";
        	        	        	$res_id_ph= mysql_query_decide($sql_id_ph) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
						$changeid=mysql_insert_id_js();
						if($Messenger_ID)
							$msgr=$Messenger_ID."@".$Messenger;
        	        	        	$sql_info_ph= "INSERT INTO newjs.CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,NEW_VAL) VALUES($changeid,now(),'$ip','$msgr')";
        	               	 		$res_info_ph= mysql_query_decide($sql_info_ph) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
					}
					//end	

					$sql="select EMAIL,USERNAME,PASSWORD from JPROFILE where PROFILEID='$profileid'";
					$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
					$myrow1=mysql_fetch_array($result);
				
					$smarty->assign("EMAIL",$myrow1["EMAIL"]);
					$smarty->assign("USERNAME",$myrow1["USERNAME"]);
					$smarty->assign("PASSWORD",$myrow1["PASSWORD"]);
					$smarty->assign("CHECKSUM",$checksum);
					
					$smarty->assign("HEAD",$smarty->fetch("head_mailer.htm"));
					$smarty->assign("SUBFOOTER",$smarty->fetch("subfooter_mailer.htm"));
				}
			}
			showPart3($checksum,$gender,$hit_source,$maritalstatus,$tieup_source,$id);
		}	
	}
	else 
	{
		TimedOut();
	}
}
else
{
	$country_birth=create_dd("","Country_Birth");
	$smarty->assign("top_country",create_dd("","top_country"));
	$sql="select USERNAME,MTONGUE , DTOFBIRTH, COUNTRY_RES FROM newjs.JPROFILE WHERE PROFILEID='".$data['PROFILEID']."'";
        $res=mysql_query_decide($sql) or die(mysql_error_js());
        $row=mysql_fetch_array($res);
        $nak_array=loadnakshatra($row['MTONGUE'],'');

	list($BIRTH_YR,$BIRTH_MON,$BIRTH_DAY) = explode("-",$row['DTOFBIRTH']);
        $smarty->assign("js_UniqueID",$data['PROFILEID']);
        $smarty->assign("BIRTH_YR",$BIRTH_YR);
        $smarty->assign("BIRTH_MON",$BIRTH_MON);
        $smarty->assign("BIRTH_DAY",$BIRTH_DAY);
                                                                                                                             
        $smarty->assign("country_birth",$country_birth);
        $smarty->assign("nak_array",$nak_array);
	$smarty->assign("country_birth",$country_birth);
	$smarty->assign("gender",$gender);
        $smarty->assign("hit_source",$hit_source);
	$smarty->assign("tieup_source",$tieup_source);
	$smarty->assign("ID_AFF",$id);
	$smarty->assign("USERNAME",$row['USERNAME']);
	//$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
	$smarty->assign("CHECKSUM",$checksum);

	/*Added by sriram for tracking Google lead conversion*/
	$sql_gl = "SELECT GROUPNAME FROM MIS.SOURCE WHERE SourceID = '$tieup_source'";
	$res_gl = mysql_query_decide($sql_gl) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_gl,"ShowErrTemplate");
	$row_gl = mysql_fetch_array($res_gl);
	if($row_gl['GROUPNAME']=="google")
		$smarty->assign("reg_comp_frm_ggl","1");
	elseif($row_gl['GROUPNAME']=="Google_NRI")
		$smarty->assign("reg_comp_frm_ggl_nri","1");
	/*End of - Added by sriram for tracking Google lead conversion*/
	
	//added by puneet on June 7 2006 ,if a user comes from google to check out the drop out ratios when user moves to different pages, page0 page1 page2 page3
	//if($tieup_source=='a09' || $tieup_source=='A09')
	if($_COOKIE["JS_SHORT_FORM"])
	{
		$sql="select count(*) as cnt from FROM_GOOGLE_HITS  where DATE=CURDATE()";
		$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$row=mysql_fetch_array($res);
		$cnt=$row['cnt'];
		if($cnt>0)
			$sql="UPDATE FROM_GOOGLE_HITS set PAGE3=PAGE3+1 WHERE DATE=CURDATE()";
		else
			$sql="insert into FROM_GOOGLE_HITS(DATE,PAGE3) values (now(),'1')";
		mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	}
	//code ends added by puneet on June 7 2006 ,if a user comes from google to check out the drop out ratios when user moves to different pages, page0 page1 page2 page3	
	
	if($lang)
	{
		$smarty->assign("FOOT",$smarty->fetch($lang."_foot.htm"));
		$smarty->assign("HEAD",$smarty->fetch($lang."_headnew.htm"));
		$smarty->display($lang."_inputprofile1.htm");
	}
	else
	{
		if($mbureau=="bureau1")
                        $smarty->assign("HEAD",$smarty->fetch("top_band.htm"));
                else
			$smarty->assign("SMALL_HEAD",$smarty->fetch("small_headnew.htm"));

		$smarty->assign("SMALLFOOTER",$smarty->fetch("smallfooter.htm"));
		$smarty->display("inputprofile1_revamp.htm");
		//$smarty->display("inputprofile1.htm");
	}
}
function showPart3($checksum,$gender,$hit_source,$maritalstatus,$tieup_source,$id="")
{
	global $smarty;
        include("inputprofile2_revamp.php");
        //include("inputprofile2.php");
}

// flush the buffer
if($zipIt)
	ob_end_flush();

?>
