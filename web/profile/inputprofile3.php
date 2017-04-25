<?php
/*********************************************************************************************
* FILE NAME   : inputprofile2.php
* DESCRIPTION : Get details for a new profile
* CREATION DATE        : 19 May, 2005
* CREATED BY        : AMAN SHARMA
* REASON             :Created due to three page input structure                                                                                 
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//end of it

require_once("connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/flag.php");
connect_db();
include("registration_common.php");
$lang=$_COOKIE['JS_LANG'];
if($lang=="deleted")
	$lang="";

if($maritalstatus=='N')
	$smarty->assign("maritalstatus","N");

//bannert value assignment.
$smarty->assign("bms_right",177);
//bannert value assignment.

/****  check for banner sources*****/
                                                                                                 
$sql="SELECT FORCE_EMAIL FROM MIS.SOURCE WHERE SOURCEID = '$tieup_source'";
$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
$row=mysql_fetch_array($result);
$force_mail=$row["FORCE_EMAIL"];
if($force_mail=='Y')
{
        $email_validation='Y';
}
if(!((substr($tieup_source,0,2))=='af' || $hit_source=='O' || $email_validation=='Y'))
{
	$data=authenticated($checksum,'y');
}
if($data["BUREAU"]==1)
{
	$fromprofilepage=1;
        include_once('../marriage_bureau/connectmb.inc');
        mysql_select_db_js('marriage_bureau');
        $mbdata=authenticatedmb($mbchecksum);
        if(!$mbdata) timeoutmb();
        $smarty->assign('mbchecksum',$mbdata["CHECKSUM"]);
        $smarty->assign('source',$mbdata["SOURCE"]);
        mysql_select_db_js('newjs');
        $smarty->assign('frommarriagebureau',$fromprofilepage);
}                                                                                                 
/********/
 //Set the text on the basis of profile posted by whom//

 edu_occ($data);
                                                                                                                             
 //Setting text ends here

if($Submit)
{
	if((substr($tieup_source,0,2))=='af' || $hit_source=='O' || $email_validation=='Y')
	{
		$data=$id;
	}
        if($data)
        {	
		//if($source=='A' || $source=='O')
		if((substr($tieup_source,0,2))=='af' || $hit_source=='O' || $email_validation=='Y')
        	{
			$profileid=$id;
                	$smarty->assign("ID_AFF",$id);
                	$table_name='newjs.JPROFILE_AFFILIATE';
		        $id_name='ID';
        	}
        	else
        	{
			$profileid=$data["PROFILEID"];
                	$table_name='newjs.JPROFILE';
			$id_name='PROFILEID';
        	}
		$sql_caste="select CASTE,MTONGUE,SCREENING from $table_name where $id_name='$profileid'";
		$res_caste=mysql_query_decide($sql_caste) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_caste,"ShowErrTemplate");
		$row_caste=mysql_fetch_array($res_caste);
		$tempcaste=$row_caste['CASTE'];
		$tempmtongue=$row_caste['MTONGUE'];
		$current_screening_flag = $row_caste['SCREENING'];
		if($hit_source!='O')
		{
			if($Rstatus=="")
			{
				$is_error++;
				$smarty->assign("check_rstatus","Y");
			}
			if($father_occ=="")
			{
				$is_error++;
				$smarty->assign("check_father","red");
			}
			if($mother_occ=="")
			{
				$is_error++;
				$smarty->assign("check_mother","red");
			}
			if($mbrother>$tbrother)
			{
				$is_error++;
				$smarty->assign("check_mbrother","red");
			}
			if($msister>$tsister)
			{
				$is_error++;
				$smarty->assign("check_msister","red");
			}
			if($ftype=="")
			{
				$is_error++;
				$smarty->assign("check_ftype","red");
			}
                        if($fstatus=="")
                        {
                                $is_error++;
                                $smarty->assign("check_fstatus","red");
                        }
			if($Family_Values=="")	
			{
				$is_errror++;
				$smarty->assign("check_familyvalues","Y");
			}
			
		}
		else
		{
			$is_error=0;
		}
		//added by sriram for skip functionality
		if($skip_to_upload_photo)
		{
			$is_error=0;
		}

		$smarty->assign("HEAD",$smarty->fetch("head.htm"));
		$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
		$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
		$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanel.htm"));
		$smarty->assign("SMALLFOOTER",$smarty->fetch("smallfooter.htm"));
		$smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));

		if($is_error > 0)
                {
			// remove slashes
			maStripVARS("stripslashes");

                        $smarty->assign("NO_OF_ERROR",$is_error);
			$smarty->assign("father_occ",create_dd($father_occ,"Family_Back"));
			$smarty->assign("mother_occ",create_dd($mother_occ,"MOTHER_OCC"));
			$smarty->assign("tbrother$tbrother","selected");
			$smarty->assign("mbrother$mbrother","selected");
			$smarty->assign("tsister$tsister","selected");
			$smarty->assign("msister$msister","selected");
		        $smarty->assign("ftype$ftype","checked=\"checked\"");
			$smarty->assign("fstatus$fstatus","checked=\"checked\"");  	
			$smarty->assign("rstatus",$Rstatus);
                        $smarty->assign("familyvalues",$Family_Values);
			$smarty->assign("parent_city_same",$Parent_City_Same);
			$smarty->assign("SHOWPARENTSCONTACT",$showParentsContact);
                        $smarty->assign("parents_contact",$Parents_Contact);
			$smarty->assign("Family",$Family);

			$smarty->assign("gender",$gender);
			$smarty->assign("hit_source",$hit_source);
			$smarty->assign("tieup_source",$tieup_source);
			$smarty->assign("CHECKSUM",$checksum);

			if($lang)
			{
				$smarty->assign("FOOT",$smarty->fetch($lang."_foot.htm"));
        	        	$smarty->assign("HEAD",$smarty->fetch($lang."_headnew.htm"));
				$smarty->display($lang."_inputprofile2.htm");
			}
			else
			{
				$smarty->assign("SMALLFOOTER",$smarty->fetch("smallfooter.htm"));
        	        	$smarty->assign("SMALL_HEAD",$smarty->fetch("small_headnew.htm"));
				$smarty->display("inputprofile_about_family.htm");
			}
		}
		else
		{
			if(!$skip_to_upload_photo)//added by sriram
			{
				//added by puneet on June 7 2006 ,if a user comes from google to check out the drop out ratios when user moves to different pages, page0 page1 page2 page3
				//if($tieup_source=='a09'  || $tieup_source=='A09' )
				if($_COOKIE["JS_SHORT_FORM"])
				{
					$sql="select count(*) as cnt from FROM_GOOGLE_HITS  where DATE=CURDATE()";
					$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
					$row=mysql_fetch_array($res);
					$cnt=$row['cnt'];
					if($cnt>0)
						$sql="UPDATE FROM_GOOGLE_HITS set SITE=SITE+1 WHERE DATE=CURDATE()";
					else
						$sql="insert into FROM_GOOGLE_HITS(DATE,SITE) values (now(),'1')";
					mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
					setcookie("JS_SHORT_FORM","",0,"/");
				}
				//code ends added by puneet on June 7 2006 ,if a user comes from google to check out the drop out ratios when user moves to different pages, page0 page1 page2 page3

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

				//added by sriram on 25th jan 2007 , to prevent HAVECHILD field from being empty when Marital Status is 'Never Married'.
				if($maritalstatus == "N" && $Has_Children == "")
					$Has_Children='N';
				if($Family=='Click here to write about your parents, brothers & sisters and your extended family.')
					$Family = "";

				if($Family)
					$current_screening_flag = removeFlag("FAMILYINFO",$current_screening_flag);
				if($Parents_Contact)
					$current_screening_flag = removeFlag("PARENTS_CONTACT",$current_screening_flag);

				if(($_COOKIE['OPERATOR']!="deleted" && $_COOKIE['OPERATOR']!="")  || $tieup_source=='ofl_prof')
                                {
                                        $current_screening_flag=131071;
					send_email("nikhil.dhiman@jeevansathi.com","test message","5--$profileid---$_COOKIE[OPERATOR]-----$source-----$tieup_source","offline@jeevansathi.com");
                                }
				//This string is created by Nikhil , since new fields were introduced//
				$update_str="FAMILY_BACK='$father_occ',MOTHER_OCC='$mother_occ',T_BROTHER='$tbrother',M_BROTHER='$mbrother',T_SISTER='$tsister',M_SISTER='$msister',FAMILY_TYPE='$ftype',FAMILY_STATUS='$fstatus'";

				$sql = "UPDATE $table_name SET HAVECHILD='$Has_Children',$update_str,FAMILYINFO='".addslashes(stripslashes($Family))."',FAMILY_VALUES='$Family_Values',PARENT_CITY_SAME='$Parent_City_Same',PARENTS_CONTACT='".addslashes(stripslashes($Parents_Contact))."',SHOW_PARENTS_CONTACT='$showParentsContact',MOD_DT=now(), LAST_LOGIN_DT='$today', SORT_DT=now(), SCREENING='$current_screening_flag' WHERE $id_name='$profileid'";
mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
				//code added by neha for archiving parents address					
				if($table_name=='newjs.JPROFILE')
        	                {
					$arr_ip=explode(",",getenv("HTTP_X_FORWARDED_FOR"));
                                        if($arr_ip[1]!="")
                                                $ip=$arr_ip[1];
                                        else
                                                $ip=FetchClientIP(); //Gets ipaddress of user

                                        //Parents Address
					if($Parents_Contact!='')
					{
	                                        $sql_id= "INSERT INTO newjs.CONTACT_ARCHIVE (PROFILEID,FIELD) VALUES($profileid,'PARENTS_CONTACT')";
        	                                $res_id= mysql_query_decide($sql_id) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_id,"ShowErrTemplate");

                	                        $changeid=mysql_insert_id_js();
						$Address=addslashes(stripslashes($Parents_Contact));
                                	        $sql_info= "INSERT INTO newjs.CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,NEW_VAL) VALUES($changeid,now(),'$ip','$Address')";
	                                        $res_info= mysql_query_decide($sql_info) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
					}
				}
			}	
			
			
			//Added By lavesh on 26 may for adding contact phone number according to city.
                        $sql="SELECT USERNAME,CITY_RES FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
                        $result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
                        $row=mysql_fetch_array($result);
                        $smarty->assign("username",$row['USERNAME']);
                        $CITY_RES=$row['CITY_RES'];
                        $sql="SELECT SQL_CACHE PHONE FROM newjs.BRANCHES WHERE VALUE='$CITY_RES'";
                        $result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
                        $row1=mysql_fetch_array($result);
                        $phone=$row1['PHONE'];
                        if(!$phone)
                        {
                                //$phone="0120-233600,233800";
                                $sql="SELECT SQL_CACHE PHONE FROM newjs.BRANCHES WHERE VALUE='UP25'";
                                $result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
                                $row1=mysql_fetch_array($result);
                                $phone=$row1['PHONE'];
                        }
                        $smarty->assign("contact_number",$phone);

			$sql="SELECT SQL_CACHE STORY,SID FROM newjs.INDIVIDUAL_STORIES WHERE SID='62' OR SID='63'";
                        $result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
                        while($row=mysql_fetch_array($result))
                        {
                                if($row['SID']=='62')
                                {
                                        $story2=$row["STORY"];
                                        $story2=substr($story2,0,125);
                                        $smarty->assign("story2",$story2);
                                                                                                                             
                                }
                                else
                                {
                                        $story1=$row["STORY"];
                                        $story1=substr($story1,0,125);
                                        $smarty->assign("story1",$story1);
                                }
                        }

			//if coming thru affiliate or offline
			if((substr($tieup_source,0,2))=='af' || $hit_source=='O' || $email_validation=='Y')
			{
				$sql="SELECT EMAIL FROM JPROFILE_AFFILIATE WHERE ID='$profileid'";
                                $result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
                                $row=mysql_fetch_array($result);
				$email=$row["EMAIL"];
				$smarty->assign("EMAIL",$email);
				$smarty->display("thanks.htm");
			}
			else
			{
				if($lang)
				{
					$smarty->assign("HEAD",$smarty->fetch($lang."_headnew.htm"));
					$smarty->assign("LEFTPANEL",$smarty->fetch($lang."_leftpanelnew.htm"));
					$smarty->assign("FOOT",$smarty->fetch($lang."_foot.htm"));
					$smarty->assign("SUBFOOTER",$smarty->fetch($lang."_subfooternew.htm"));
					$smarty->display($lang."_regcomplete.htm");
				}
				else
				{
					if($data["BUREAU"]==1)
					{
                                                $smarty->assign("mb_username_profile",$data["USERNAME"]);
                                                $smarty->assign("frommarriage_bureau",1);
                                                $smarty->assign("checksum",$data["CHECKSUM"]);
                                                $smarty->assign("HEAD",$smarty->fetch("top_band.htm"));
                                                $smarty->assign("LEFTPANEL",$smarty->fetch("mb_side_links.htm"));
                                        }
                                        else
                                        {
                                                $smarty->assign("username",$data["USERNAME"]);
						//smarty assigned to show upgrade link on top grey band.
						$smarty->assign("mem_case",2);
                                                $smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
                                                $smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
                                        }
					$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
					$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
					//$smarty->display("registration_complete_revamp.htm");
					$smarty->display("registration_complete.htm");
				}
			}
		}
	}
	else
       	{
               	TimedOut();
       	}
}
else
{
	//$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
	if((substr($tieup_source,0,2))=='af' || $hit_source=='O' || $email_validation=='Y')
	{
		$profileid=$id;
		$smarty->assign("ID_AFF",$id);
		$table_name='newjs.JPROFILE_AFFILIATE';
		$id_name='ID';
	}
	else
	{
		$profileid=$data["PROFILEID"];
		$table_name='newjs.JPROFILE';
		$id_name='PROFILEID';
	}
	$sql_caste="select CASTE,MTONGUE,MSTATUS from $table_name where $id_name='$profileid'";
	$res_caste=mysql_query_decide($sql_caste) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_caste,"ShowErrTemplate");
	$row_caste=mysql_fetch_array($res_caste);
	$tempcaste=$row_caste['CASTE'];
	$tempmtongue=$row_caste['MTONGUE'];
	$smarty->assign("maritalstatus",$row_caste['MSTATUS']);

	display_related_castes($tempcaste,$searchid,$checksum,'caste','');
	display_related_castes($tempcaste,$searchid,$checksum,'mtongue',$tempmtongue);
	display_city();
	$smarty->assign("CHECKSUM",$checksum);
	$smarty->assign("gender",$gender);
	$smarty->assign("ID_AFF",$id);
	$smarty->assign("hit_source",$hit_source);
	$smarty->assign("tieup_source",$tieup_source);
	
	//added by puneet on June 7 2006 ,if a user comes from google to check out the drop out ratios when user moves to different pages, page0 page1 page2 page3         //if($tieup_source=='a09' || $tieup_source=='A09')
	if($_COOKIE["JS_SHORT_FORM"])
	{
		$sql="select count(*) as cnt from FROM_GOOGLE_HITS  where DATE=CURDATE()";
		$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$row=mysql_fetch_array($res);
		$cnt=$row['cnt'];
		if($cnt>0)
			$sql="UPDATE FROM_GOOGLE_HITS set PAGE5=PAGE5+1 WHERE DATE=CURDATE()";
		else
			$sql="insert into FROM_GOOGLE_HITS(DATE,PAGE5) values (now(),'1')";
		mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	}
	//code ends added by puneet on June 7 2006 ,if a user comes from google to check out the drop out ratios when user moves to different pages, page0 page1 page2 page3

	if($lang)
	{
		$smarty->assign("FOOT",$smarty->fetch($lang."_foot.htm"));
		$smarty->assign("HEAD",$smarty->fetch($lang."_headnew.htm"));
		$smarty->display($lang."_inputprofile2.htm");
	}
	else
	{
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		if($data["BUREAU"]==1)
			$smarty->assign("HEAD",$smarty->fetch("top_band.htm"));
		else
			$smarty->assign("SMALL_HEAD",$smarty->fetch("small_headnew.htm"));
		
                $smarty->assign("father_occ",create_dd("","Family_Back"));
                $smarty->assign("mother_occ",create_dd("","MOTHER_OCC"));
		$smarty->assign("SMALLFOOTER",$smarty->fetch("smallfooter.htm"));
		$smarty->display('inputprofile_about_family.htm');
	}
}


// flush the buffer
if($zipIt)
	ob_end_flush();
?>
