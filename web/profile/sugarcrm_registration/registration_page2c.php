<?php
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
$zipIt = 0;
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
include_once($root_path1."/profile/auto_reg_functions.php");
include_once($root_path1."/sugarcrm/include/utils/Jsutils.php");
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
  $lead_sql="Select subcaste_c,school_name_c,college_name_c,current_employer_c,gothra_c, work_c,father_occupation_c,hobbies_c,lead_attribute_c from sugarcrm.leads_cstm where id_c='$record_id'";
  $lead_res=mysql_query_decide($lead_sql);
  $lead_row=mysql_fetch_array($lead_res);
  if($lead_row)
  {
  foreach($lead_row as $lead_key => $lead_value)
  {
	  if($lead_value){
	  switch($lead_key)
	  {
	  case 'subcaste_c':
		  $subcaste=$lead_row['subcaste_c'];
		  $smarty->assign("subcaste",$subcaste);
		  break;
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
		  $smarty->assign("CURRENT_EMPLOYER",$current_employer);
		  break;
	  case 'gothra_c':
		  $gotra=$lead_row['gothra_c'];
		  $smarty->assign("gotra",$gotra);
		  break;
	  case 'work_after_marriage_c':
		  $married_working=$lead_row['work_after_marriage_c'];
		  $smarty->assign("MARRIED_WORKING",$married_working);
		  break;
	  case 'father_occupation_c':
		  $father_occupation=$lead_row['father_occupation_c'];
		  break;
	  case 'hobbies_c':
		  $hobbies_tmp=$lead_row['hobbies_c'];
		  $hobbies_arr=explode(",",$hobbies_tmp);
		  $hobbies=str_replace("^","",$hobbies_arr);
		  $smarty->assign("HOBBIES",$hobby);
		  break;
	  case 'lead_attribute_c':
		  $personality_attribute=$lead_row['lead_attribute_c'];
		  $smarty->assign("PERSONALITY_ATTRIBUTE",$personality_attribute);
		  break;
	  }
	  }
  }
}
}
if($profileid)
{
	$sql="select USERNAME,SCREENING,RELIGION,EDU_LEVEL_NEW,OCCUPATION,INCOME,AGE,PHONE_MOB,PHONE_RES,STD,MOB_STATUS,LANDL_STATUS from newjs.JPROFILE where PROFILEID='$profileid'";
	$res=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
	$row=mysql_fetch_array($res);
	$current_screening_flag = $row['SCREENING'];
	$username= $row['USERNAME'];
	$religion=$row['RELIGION'];
	if(!$occupation)
		$occupation=$row['OCCUPATION'];
	if(!$degree)
			$degree=$row['EDU_LEVEL_NEW'];
	if(!$income)
		$income=$row['INCOME'];
	$age=$row['AGE'];
	$mobile=$row['PHONE_MOB'];
	$phone=$row['PHONE_RES'];
	$state_code=$row['STD'];
	$landl_status=$row['LANDL_STATUS'];
	$mob_status=$row['MOB_STATUS'];
	$smarty->assign("OCCUPATION",$occupation);
	$smarty->assign("DEGREE",$degree);
	$smarty->assign("INCOME",$income);
}


/****Tracking purpose********/
$smarty->assign("USERNAME",$username);
$smarty->assign("TIEUP_SOURCE",$tieup_source);

if($submit_pg2c){
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
		if($diet)
		{
			$jprofile_update[]="DIET='$diet'"; 
		}
		if($subcaste)
		{
			$jprofile_update[]=" SUBCASTE='".addslashes(stripslashes(mysql_real_escape_string($subcaste)))."'";
			$current_screening_flag = removeFlag("SUBCASTE",$current_screening_flag);
		}
		if($gotra)
		{
			$jprofile_update[] = "GOTHRA='".addslashes(stripslashes(mysql_real_escape_string($gotra)))."'";
			$current_screening_flag = removeFlag("GOTHRA",$current_screening_flag);
		}
		if($married_working){
			$jprofile_update[]=" MARRIED_WORKING='$married_working'";
		}
		if($secondary_source)
			$jprofile_update[]="SEC_SOURCE='".mysql_real_escape_string($secondary_source)."'";
		if($sugar_incomplete=='Y'){
			$jprofile_update[]="OCCUPATION='$occupation'";
			$jprofile_update[]="EDU_LEVEL_NEW='$degree'";
			$jprofile_update[]="INCOME='$income'";
		}
		$allfields=array(
			'father_occupation'=>$father_occupation,
			'diet'=>$diet,
			'subcaste'=>$subcaste,
			'gotra'=>$gotra,
			'married_working'=>$married_working,
			'hobbies'=>$hobbies,
            'school_name'=>$school_name,
		    'college_name'=>$college_name,
			'current_employer'=>$current_employer,
			'personality_attribute'=>$personality_attribute
		 );

		$about_yourself=create_aboutyourself($allfields,'F');
		if($from_sugar_exec)
                                $process="register_lead_button";
                        else
                                $process="auto_registration";
                update_about_yourself($about_yourself,$profileid,$db,$record_id,'sugarcrm.leads','sugarcrm.leads_cstm',$process);
		include_once('registration_page2.inc');

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
			if($ivr_phone && ($mob_status!='Y' && $landl_status!='Y')){
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
			if(strtolower($tieup_source)=='ofl_prof')
	                {
			      $current_screening_flag="1099511627775";
		        }
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
		
		unset($about_yourself_text);
		unset($jprofile_update);
		header("Location: $SITE_URL/register/page4?record_id=$record_id");
exit(0);
	}
	else
	{
		$about_yourself=htmlspecialchars(stripslashes($about_yourself),ENT_QUOTES);
		$smarty->assign("about_yourself",$about_yourself);
		$smarty->assign("CHECKSUM",$checksum);
		$smarty->assign("record_id",$record_id);
		$smarty->assign("from_sugar_exec",$from_sugar_exec);
		  $smarty->assign("SCHOOL_NAME",$school_name);
		  $smarty->assign("COLLEGE_NAME",$college_name);
		  $smarty->assign("CURRENT_EMPLOYER",$lead_row['current_employer_c']);
		  $smarty->assign("PERSONALITY_ATTRIBUTE",$personality_attribute);
		  $smarty->assign("gotra",$gotra);
		  $smarty->assign("subcaste",$subcaste);
		  $smarty->assign("DIET",$diet);
		  $smarty->assign("MARRIED_WORKING",$married_working);
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
		    foreach($HOBBIES_DROP as $hobbies_key=>$hobbies_value){
			       if(count($hobbies) &&in_array($hobbies_key,$hobbies))
				       $option_string.="<option value=\"$hobbies_key\" selected=\"yes\">$hobbies_value</option>";	   
				   else 
					   $option_string.="<option value=\"$hobbies_key\" >$hobbies_value</option>";
			}	   
			$smarty->assign('HOBBIES_STR',$option_string);
			$option_string="";

			 if($degree!='')
				degreeDropDown();
			 if($occupation!='')
				occupationDropDown();
		    if($income!='')
				incomeDropDown();
			/* Religion Assignment */
			if($religion == '1')
				$smarty->assign("HINDU","1");
			else if($religion == '2' && $gender=='M')
				$smarty->assign("MUSLIM_BOY","1");
			else if($religion == '2' && $gender=='F')
				$smarty->assign("MUSLIM_GIRL","1");
			else if($religion == '3')
				$smarty->assign("CHRISTIAN","1");
			else if($religion == '4')
				$smarty->assign("SIKH","1");
			else if($religion == '5')
				$smarty->assign("PARSI","1");
			else if($religion == '6')
				$smarty->assign("JEWISH","1");
			else if($religion == '7')
				$smarty->assign("BUDDHIST","1");
			else if($religion == '9')
				$smarty->assign("JAIN","1");
			/* Ends Here */
			if($from_sugar_exec){
                               $smarty->assign('CID',$_COOKIE[CRM_LOGIN]);
                               $smarty->assign('crq',$crq);
                               $smarty->assign("from_sugar_exec",$from_sugar_exec);
                                        }
$smarty->display("sugarcrm_registration/sugarcrm_registration_pg2c.htm");
 if($zipIt && !$dont_zip_now)
             ob_end_flush();

?>

