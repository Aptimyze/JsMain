<?php
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
$zipIt = 1;
if($zipIt && !$dont_zip_now && $dont_zip_more!=1)
{
        $dont_zip_more=1;
        ob_start("ob_gzhandler");
}

$root_path1=$_SERVER['DOCUMENT_ROOT'];
include_once($root_path1."/profile/connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
include_once($root_path1."/profile/screening_functions.php");
include_once(JsConstants::$docRoot."/commonFiles/flag.php");
include_once($root_path1."/profile/registration_functions.inc");
include_once($root_path1."/classes/Jpartner.class.php");
include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");
include_once($root_path1."/sugarcrm/include/utils/Jsutils.php");
include_once($root_path1."/profile/auto_reg_functions.php");
include_once($root_path1."/profile/mobile_detect.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
$db=connect_db();
$data_auth=$protect_obj->checkSession("","Y");
if(!$data_auth){
	$data_auth=authenticated($checksum,'y');
	if(!$data_auth){
		header("Location: ".$SITE_URL."/profile/sugarcrm_registration/registration_page1.php?record_id=$record_id&secondary_source=$secondary_source");
		exit;
	} 
}
$profileid=$data_auth[PROFILEID];
$smarty->assign("CHECKSUM",$checksum);
$smarty->assign("record_id",$record_id);
$smarty->assign("from_sugar_exec",$from_sugar_exec);
$smarty->assign("sec_source",$secondary_source);
$smarty->assign("PROFILEID",$profileid);
$smarty->assign("SUGAR_INCOMPLETE",$sugar_incomplete);
$religion='1';
$now = date("Y-m-d G:i:s");
if($record_id)
{
  $lead_sql="Select school_name_c,college_name_c,current_employer_c, no_of_brothers_c,no_of_brothers_married_c,work_c,father_occupation_c,lead_attribute_c from sugarcrm.leads_cstm where id_c='$record_id'";
  $lead_res=mysql_query_decide($lead_sql);
  $lead_row=mysql_fetch_array($lead_res);
  if($lead_row)
  {
  foreach($lead_row as $lead_key => $lead_value)
  {
	  if($lead_value){
	  switch($lead_key)
	  {
	  case 'school_name_c':
		  $school_name=$lead_row['school_name_c'];
		  $smarty->assign("SCHOOL_NAME",$school_name);
		  break;
	  case 'college_name_c':
		  $college_name=$lead_row['college_name_c'];
		  $smarty->assign("COLLEGE_NAME",$college_name);
		  break;
	  case 'current_employer_c':
		  $current_employer=$lead_row['current_employer_c'];
		  $smarty->assign("CURRENT_EMPLOYER",$lead_row['current_employer_c']);
		  break;
	  case 'father_occupation_c':
		  $father_occupation=$lead_row['father_occupation_c'];
		  break;
	  case 'lead_attribute_c':
		  $personality_attribute=$lead_row['lead_attribute_c'];
		  $smarty->assign("PERSONALITY_ATTRIBUTE",$personality_attribute);
		  break;
	  case 'no_of_brothers_c':
		  $brothers=$lead_row['no_of_brothers_c'];
		  $smarty->assign("brothers",$brothers);
		  break;
	  case 'no_of_brothers_married_c':
		  $married_brothers=$lead_row['no_of_brothers_married_c'];
		  $smarty->assign("married_brothers",$married_brothers);
		  break;
	  }
	  }
  }
  }
}
if($profileid)
{
	$sql="select USERNAME,SCREENING,SMOKE,DRINK,EDU_LEVEL_NEW,OCCUPATION,INCOME,PHONE_RES,PHONE_MOB,STD,MOB_STATUS,LANDL_STATUS from newjs.JPROFILE where PROFILEID='$profileid'";
	$res=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
	$row=mysql_fetch_array($res);
	$current_screening_flag = $row['SCREENING'];
	$username= $row['USERNAME'];
	if(!$smoke)
		$smoke=$row['SMOKE'];
	if(!$drink)
		$drink=$row['DRINK'];
	if(!$occupation)
		$occupation=$row['OCCUPATION'];
	if(!$degree)
			$degree=$row['EDU_LEVEL_NEW'];
	if(!$income)
			$income=$row['INCOME'];
	$state_code=$row['STD'];
	$mobile=$row['PHONE_MOB'];
	$phone=$row['PHONE_RES'];
	$mob_status=$row['MOB_STATUS'];
	$landl_status=$row['LANLD_STATUS'];
$smarty->assign("OCCUPATION",$occupation);
$smarty->assign("DEGREE",$degree);
$smarty->assign("INCOME",$income);
}
if($smoke)$smarty->assign("SMOKE",$smoke);
if($drink)$smarty->assign("DRINK",$drink);

/****Tracking purpose********/
$smarty->assign("USERNAME",$username);
$smarty->assign("TIEUP_SOURCE",$tieup_source);

if($submit_pg2b){
	if($sugar_incomplete=='Y'){
		if($sugar_income)$income=$sugar_income;
		if($sugar_deg)$degree=$sugar_deg;
		if($sugar_occ)$occupation=$sugar_occ;
	if(!$degree)
	{
		$is_error++;
		$smarty->assign("degree_err",'1');
		$errors[]="degree_err1";
	}
	if(!$occupation)
	{
		$is_error++;
		$smarty->assign("occupation_err",'1');
		$errors[]="occupation_err1";
	}
	if(!$income)
	{
		$is_error++;
		$smarty->assign("income_err",'1');
		$errors[]="income_err1";
	}
	if($is_error)
	{
		foreach($errors as $error_str){
			$error_name=substr($error_str,0,-1);
			$error_value=substr($error_str,-1);
			$smarty->assign($error_name,$error_value);
		}
		
		$smarty->assign("OCCUPATION",$occupation);
		$smarty->assign("DEGREE",$degree);
		$smarty->assign("INCOME",$income);
		$smarty->assign("SUGAR_INCOMPLETE",$sugar_incomplete);
	}	
	}
	if($is_error==0)
	{
		$sql_upd_jp = "UPDATE newjs.JPROFILE SET ";
			
		if($father_occupation)
		{
			$jprofile_update[]=" FAMILY_BACK='$father_occupation'";
		}
		if($brothers!='')
		{
			$jprofile_update[]=" T_BROTHER='$brothers'";
		if($married_brothers!='')
		{
			$jprofile_update[]=" M_BROTHER='$married_brothers'";
		}
		}
		if($live_with_parents)
		{
			//Not Applicable option is not there in JPROFILE so not updating it if user has selected not applicable
		    if($live_with_parents != 'A')	$jprofile_update[]="PARENT_CITY_SAME='$live_with_parents'";
		}		
		if($drink)
		{
			$jprofile_update[]=" DRINK='$drink'";
		}
		if($smoke)
		{
			$jprofile_update[]="SMOKE='$smoke'"; 
		}
		if($diet)
		{
			$jprofile_update[]="DIET='$diet'"; 
		}
		if($sugar_incomplete=='Y'){
			$jprofile_update[]="OCCUPATION='$occupation'";
			$jprofile_update[]="EDU_LEVEL_NEW='$degree'";
			$jprofile_update[]="INCOME='$income'";
		}
		if($secondary_source)
			$jprofile_update[]="SEC_SOURCE='".mysql_real_escape_string($secondary_source)."'";
		$all_fields=array(
			'father_occupation'=>$father_occupation,
			'brothers'=>$brothers,
			'married_brothers'=>$married_brothers,
			'drink'=>$drink,
			'smoke'=>$smoke,
			'diet'=>$diet,
			'school_name'=>$school_name,
			'college_name'=>$college_name,
			'current_employer'=>$current_employer,
			'live_with_parents'=>$live_with_parents,
			'personality_attribute'=>$personality_attribute
		);

	    $about_yourself=create_aboutyourself($all_fields,'M');	
		if($about_yourself)
		{
                        if($from_sugar_exec)
                                $process="register_lead_button";
                        else
                                $process="auto_registration";
                        update_about_yourself($about_yourself,$profileid,$db,$record_id,'sugarcrm.leads','sugarcrm.leads_cstm',$process);
                }

			/* Scenarios checked for IVR call: 1. junk number exist (no ivr call)
							  2. Duplicate Exist (no ivr call)
							  3. ivr call (if neither junk nor duplicate)
			*/
			include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsPhoneVerify.php");
			if($mobile){
				$ivr_phone 	=$mobile;
				$phoneType	='M';
				$ivr_std 	='';
			}
			else if($phone){
				$ivr_phone 	=$phone;
				$phoneType	='L';
				$ivr_std 	=trim($state_code);
				if($ivr_std)
					$ivr_phone	=$ivr_std."-".$phone;
			}
			if(($ivr_phone)&&($mob_status!='Y' && $landl_status!='Y')){
		    		$chk_junk =chkJunkNumberList($ivr_phone,$phoneType);
				if($chk_junk)
					phoneUpdateProcess($profileid,'',$phoneType,'J');
			}
			/* IVR - code ends */
			/* SMS Code for sending sms to users */
			
		//	include_once "$root_path1/profile/InstantSMS.php";
		//	 $sms = new InstantSMS("REGISTER_CONFIRM", $profileid);
		//	 $sms->send();
    
			/* Ends Here of SMS code */
		if(count($jprofile_update) > 0)
		{
			$objUpdate = JProfileUpdateLib::getInstance();

			$jprofile_update[]="SCREENING=$current_screening_flag";
			$jprofile_update_str = @implode(", ",$jprofile_update);

			$arrUpdateParams = $objUpdate->convertUpdateStrToArray($jprofile_update_str);
			$result = $objUpdate->editJPROFILE($arrUpdateParams,$profileid,'PROFILEID');
			if($result === false){
				$sql_upd_jp .= $jprofile_update_str." WHERE PROFILEID='$profileid'";
				logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_upd_jp,"ShowErrTemplate");
			}
		}
		//New Login
    $authWeb = new WebAuthentication();
    $authWeb->loginFromReg();
		unset($about_yourself);
        unset($jporofile_update);
	header("Location: $SITE_URL/register/page4?record_id=$record_id");
		die;
	}
	else
	{
		$about_yourself=htmlspecialchars(stripslashes($about_yourself),ENT_QUOTES);
		$smarty->assign("about_yourself",$about_yourself);
		$smarty->assign("DRINK",$drink);
		$smarty->assign("SMOKE",$smoke);
		$smarty->assign("CHECKSUM",$checksum);
		$smarty->assign("record_id",$record_id);
		$smarty->assign("from_sugar_exec",$from_sugar_exec);
		  $smarty->assign("SCHOOL_NAME",$school_name);
		  $smarty->assign("COLLEGE_NAME",$college_name);
		  $smarty->assign("CURRENT_EMPLOYER",$lead_row['current_employer_c']);
		  $smarty->assign("PERSONALITY_ATTRIBUTE",$personality_attribute);
		  $smarty->assign("brothers",$brothers);
		  $smarty->assign("married_brothers",$married_brothers);
		  $smarty->assign("DIET",$diet);
		  $smarty->assign("live_with_parents",$live_with_parents);
	}


}

			$option_string="";
			$sql = "SELECT SQL_CACHE LABEL, VALUE FROM FAMILY_BACK ORDER BY SORTBY";
			$res = mysql_query_decide($sql) or logError("error",$sql);
			while($row = mysql_fetch_array($res))
			{
				if($father_occupation == $row['VALUE'])
				      	$option_string.= "<option value=\"$row[VALUE]\" selected=\"yes\">$row[LABEL]</option>";
				else
				       $option_string.= "<option value=\"$row[VALUE]\">$row[LABEL]</option>";
			}
			$smarty->assign('FATHER_OCCUPATION',$option_string);
			$option_string="";
			if($degree!='')
				degreeDropDown();
			if($occupation!='')
				occupationDropDown();
			if($income!='')
				incomeDropDown();
			if($from_sugar_exec){
                               $smarty->assign('CID',$_COOKIE[CRM_LOGIN]);
                               $smarty->assign('crq',$crq);
			       $smarty->assign("from_sugar_exec",$from_sugar_exec);
					}
$smarty->display("sugarcrm_registration/sugarcrm_registration_pg2b.htm");
if($zipIt && !$dont_zip_now)
ob_end_flush();
?>

