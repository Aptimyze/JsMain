<?php
/*********************************************************************************************
* FILE NAME   : inputprofile1.php
* DESCRIPTION : Get details for a new profile
* MODIFY DATE        : 19 May, 2005
* MODIFIED BY        : AMAN SHARMA
* REASON             : Changes made due to three page input structure                                                                                 
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
$db=connect_db();

$lang=$_COOKIE['JS_LANG'];
if($lang=="deleted")
	$lang="";

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



if((substr($tieup_source,0,2))=='af' || $email_validation=='Y')
	$data=$id;
else
	$data=authenticated($checksum);

$smarty->assign("CAMEFROMHOMEPAGE","1");
$smarty->assign("maritalstatus",$maritalstatus);

/***********************************************************************************************************************
			Added By	: Shakti Srivastava
			Reason		: For a "<img src>" tag being added to the templates which has to be passed
					: user's profileid
***********************************************************************************************************************/
		if((substr($tieup_source,0,2))=='af' || $email_validation=='Y')
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

if($camefrom=="page3")
{
  	//$data=authenticated($checksum);
	if((substr($tieup_source,0,2))=='af' || $email_validation=='Y')
	{
		$profileid=$id;
		$smarty->assign("ID_AFF",$id);
	}
	else
		$profileid=$data["PROFILEID"];

	$is_error=0;
	//$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));

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
		$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
		$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
		$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanel.htm"));
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
	}

	if(!$showParentsContact)
		$showParentsContact="Y"; 

	$btime=$Hour_Birth.":".$Min_Birth;
	if(!$showAddress)
		$showAddress="Y";
										 
	if(!$showMessenger)
		$showMessenger="Y";


	$today=date("Y-m-d H:i:s");
	
	if((substr($tieup_source,0,2))=='af' || $email_validation=='Y')
	{
		$table_name='newjs.JPROFILE_AFFILIATE';
		$id_name='ID';
	}
	else
	{
		$table_name='newjs.JPROFILE';
		$id_name='PROFILEID';
	}

//			$sql = "UPDATE $table_name SET OCCUPATION='$Occupation',CTC_TYPE='$Ctc_Type',CTC_LACS='$Ctc_Lacs',CTC_THOUSAND='$Ctc_Thousand', INCOME='$Income_Old',BTYPE='$Body_Type',COMPLEXION='$Complexion',HANDICAPPED='$Phyhcp',RES_STATUS='$Rstatus', COUNTRY_BIRTH='$Country_Birth', EDU_LEVEL='$Education_Level_Old',EDU_LEVEL_NEW='$Education_Level', EDUCATION='$Educ_Qualification', SMOKE='$Smoke', DRINK='$Drink', DIET='$Diet',SUBCASTE='$Subcaste',MANGLIK='$Manglik_Status',HAVECHILD='$Has_Children',YOURINFO='$Information',FAMILY_BACK='$Family_Back',FATHER_INFO='$Father_Info',SIBLING_INFO='$Sibling_Info',CITY_RES='$City_Res',  INCOMPLETE='N', MOD_DT=now(), LAST_LOGIN_DT='$today',PRIVACY='$radioprivacy' WHERE $id_name='$profileid'";
	//changed by Gaurav Arora on 26 July for new input profile pages.

/*********************************************************************************************************************
	CHANGED BY	:	SHAKTI SRIVASTAVA
	CHANGE DATE	:	5 OCTOBER, 2005
	REASON		:	CHANGES WERE MADE FOR NEW FIELD (DISPLAY HOROSCOPE)
*********************************************************************************************************************/
	$sql = "UPDATE $table_name SET BTYPE='$Body_Type',COMPLEXION='$Complexion',HANDICAPPED='$Phyhcp',RES_STATUS='$Rstatus', COUNTRY_BIRTH='$Country_Birth',EDUCATION='".addslashes(stripslashes($Educ_Qualification))."', SMOKE='$Smoke', DRINK='$Drink', DIET='$Diet',SUBCASTE='".addslashes(stripslashes($Subcaste))."',MANGLIK='$Manglik_Status',HAVECHILD='$Has_Children',FAMILY_BACK='$Family_Back',FATHER_INFO='".addslashes(stripslashes($Father_Info))."',SIBLING_INFO='".addslashes(stripslashes($Sibling_Info))."',JOB_INFO='".addslashes(stripslashes($Job))."',CITY_BIRTH='".addslashes(stripslashes($City_Birth))."',BTIME='$btime',NAKSHATRA='".addslashes(stripslashes($Nakshatram))."',GOTHRA='".addslashes(stripslashes($Gothra))."',FAMILY_VALUES='$Family_Values',PARENT_CITY_SAME='$Parent_City_Same',CONTACT='$Address',PINCODE='$pincode',SHOWADDRESS='$showAddress', SHOWMESSENGER='$showMessenger',PARENTS_CONTACT='".addslashes(stripslashes($Parents_Contact))."',SHOW_PARENTS_CONTACT='$showParentsContact',FATHER_INFO='".addslashes(stripslashes($Father_Info))."',SIBLING_INFO='".addslashes(stripslashes($Sibling_Info))."',FAMILYINFO='".addslashes(stripslashes($Family))."',MESSENGER_ID='$Messenger_ID', MESSENGER_CHANNEL='$Messenger', MOD_DT=now(), LAST_LOGIN_DT='$today', SORT_DT=now(), SHOW_HOROSCOPE='$display_horo' WHERE $id_name='$profileid'";
	//$sql = "UPDATE $table_name SET OCCUPATION='$Occupation',INCOME='$Income',BTYPE='$Body_Type',COMPLEXION='$Complexion',HANDICAPPED='$Phyhcp',RES_STATUS='$Rstatus', COUNTRY_BIRTH='$Country_Birth', EDU_LEVEL='$Education_Level_Old',EDU_LEVEL_NEW='$Education_Level', EDUCATION='".addslashes(stripslashes($Educ_Qualification))."', SMOKE='$Smoke', DRINK='$Drink', DIET='$Diet',SUBCASTE='".addslashes(stripslashes($Subcaste))."',MANGLIK='$Manglik_Status',HAVECHILD='$Has_Children',YOURINFO='".addslashes(stripslashes($Information))."',FAMILY_BACK='$Family_Back',FATHER_INFO='".addslashes(stripslashes($Father_Info))."',SIBLING_INFO='".addslashes(stripslashes($Sibling_Info))."',CITY_RES='$City_Res',  INCOMPLETE='N', MOD_DT=now(), LAST_LOGIN_DT='$today',PRIVACY='$radioprivacy' WHERE $id_name='$profileid'";

	mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
//$result= mysql_query_decide($sql) or mysql_error_js();

	if($table_name=='newjs.JPROFILE')
	{
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
	//showPart3($checksum,$gender,$hit_source,$maritalstatus,$tieup_source,$id);
}
else
{
	$country_birth=create_dd("","Country_Birth");
	$smarty->assign("family_back",create_dd("","Family_Back"));
	$smarty->assign("top_country",create_dd("","top_country"));
	$smarty->assign("country_birth",$country_birth);
	$smarty->assign("gender",$gender);
        $smarty->assign("hit_source",$hit_source);
	$smarty->assign("tieup_source",$tieup_source);	
	$smarty->assign("ID_AFF",$id);
	//$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
	$smarty->assign("CHECKSUM",$checksum);

	if($lang)
	{
		$smarty->assign("FOOT",$smarty->fetch($lang."_foot.htm"));
		$smarty->assign("HEAD",$smarty->fetch($lang."_headnew.htm"));
		$smarty->display($lang."_inputprofile1.htm");
	}
	else
	{
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
		$smarty->display("ip_new_3.htm");
	}
}

// flush the buffer
if($zipIt)
	ob_end_flush();

?>
