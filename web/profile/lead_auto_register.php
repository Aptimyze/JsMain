<?php
$path=realpath(dirname(__FILE__)."/..");
include_once($path."/profile/auto_reg_functions.php");
include_once($path."/sugarcrm/include/utils/JsToLeadFieldMapping.php");
include_once($path."/sugarcrm/include/utils/Jsutils.php");
include_once($path."/sugarcrm/custom/crons/JsSuccessAutoRegEmail.php");
include_once($path."/sugarcrm/custom/crons/housekeepingConfig.php");
include_once($path."/sugarcrm/include/utils/systemProcessUsersConfig.php");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
global $partitionsArray;
global $process_user_mapping;
function register_lead($leadid){
	global $SUGARCRMDBCOLUMNS;
	global $REGPAGE1;
	global $LEADTOJPROFILE;
	global $LEADS;
	global $db;
	global $LEADTOJPROFILEOTHERPAGES,$path;
	global $partitionsArray;
	global $process_user_mapping;
	$select_of_lead=implode(",",$SUGARCRMDBCOLUMNS);
	$sql_lead="select ".$select_of_lead." from sugarcrm.leads as l,sugarcrm.leads_cstm as lc where l.id='".$leadid."' AND deleted='0' AND lc.id_c=l.id";
//	echo "$sql_lead<BR>";
	$result=mysql_query_decide($sql_lead,$db);
	if(mysql_num_rows($result))
	{
		$lead_row=mysql_fetch_assoc($result);
		$partitionLeadsCstm="sugarcrm.leads_cstm";
		$partitionLeads="sugarcrm.leads";
	}
	else
	{
		if(is_array($partitionsArray))
		{
			foreach($partitionsArray as $partition=>$partitionArray)
			{
				$partitionLeadsCstm="sugarcrm_housekeeping.".$partition."_leads_cstm";
		                $partitionLeads="sugarcrm_housekeeping.".$partition."_leads";
				$sql_lead="select ".$select_of_lead." from $partitionLeads as l,$partitionLeadsCstm as lc where l.id='".$leadid."' AND deleted='0' AND lc.id_c=l.id";
				$result=mysql_query_decide($sql_lead,$db);
				if(mysql_num_rows($result))
				{
					$lead_row=mysql_fetch_assoc($result);
					break;
				}
			}
		}
	}
//	print_r($lead_row);
	if($lead_row){
		//Lead data from sugarcrm database
	mysql_free_result($result);
//	print_r($lead_row);
//	echo "<BR>";
	$post_value=array();
	foreach($REGPAGE1 as $key=>$value){
		$post_value[$key]=$lead_row[$value];
	}
	//Caste,city_residence is defined differently in sugarcrm database so change it here
	$caste_arr=explode("_",$post_value['caste']);
	$post_value['caste']=$caste_arr[1];
	//picked country codes from custom/include/language/en.us_lang.php
	$sugar_country_arr=array(
		  128 => 'USA',
		  126 => 'UK',
		  7 => 'Australia',
		  125 => 'UAE',
		  22 => 'Canada',
		  0 => 'Others',
	  );
	if(!array_key_exists($post_value['city_residence'],$sugar_country_arr))
	{
		$post_value['country_residence']=51;
		$post_value['country_code']="+91";
		$sql_std_code="Select STD_CODE from newjs.CITY_NEW where VALUE='".$post_value['city_residence']."'";
		$sql_std_code_res=mysql_query_decide($sql_std_code);
		if($sql_std_code_res){
			$std_codeArr=mysql_fetch_row($sql_std_code_res);
			$post_value['state_code']="0".$std_codeArr['0'];
		}else die("Invalid city");
	}
		
	else
	{
		$post_value['country_residence']=$post_value['city_residence'];
		$post_value['city_residence']='0';
		$post_value['state_code']='';
		//If Others is selected then assuming country to be India
		if($post['country_residence']==0){
			$post_value['country_code']="+91";
			$post_value['country_residence']=51;
		}
		else {
			$con_sql="select ISD_CODE from newjs.COUNTRY_NEW where VALUE='".$post['country_residence']."'";
			$res_con=mysql_query_decide($con_sql);
			$row_con=mysql_fetch_assoc($res_con);
			$isd_code=$row_con['ISD_CODE'];
			$post_values['country_code']="+".$isd_code;
		}
	}
	switch($lead_row['posted_by_c']){
		case 2:
		   $post_value['relationship']=$lead_row['gender_c']=='M'?'2':'2D';
		   break;
		case 3:
		case 5:
		   $post_value['relationship']=$lead_row['gender_c']=='M'?'6':'6D';
		   break;
		default:
		   $post_value['relationship']=$lead_row['posted_by_c'];
		   break;
	}
	if($lead_row['enquirer_mobile_no_c'])
		$post_value['mobile']=$lead_row['enquirer_mobile_no_c'];
	if($lead_row['enquirer_landline_c'])
		$post_value['phone']=$lead_row['enquirer_landline_c'];
	//function defined in Jsutil.php file.
	$post_value['email']=get_lead_email($leadid);
	$email=$post_value['email'];

	//creat password
	$password=createJSPassword(6);
	$post_value['password']=$password;


	//make showphone and showmobile yes

	$post_value['showphone']='Y';
	$post_value['showmobile']='Y';

	//Date of birth split

	$bdate_arr=explode("-",$lead_row['date_birth_c']);
	$post_value['year']=$bdate_arr[0];
	$post_value['month']=$bdate_arr[1];
	$post_value['day']=$bdate_arr[2];
	$mobile=$post_value['mobile'];
	$phone=$post_value['phone'];
	$caste=$post_value['caste'];
	$religion=$post_value['religion'];
	//STD code setting
	if(!empty($lead_row['enquirer_landline_c'])){
		if($lead_row['std_enquirer_c'])
	     $post_value['state_code']=$lead_row['std_enquirer_c'];
	}
	else{
		if($lead_row['std_c'])
			$post_value['state_code']=$lead_row['std_c'];
	}
	
	$state_code=$post_value['state_code'];
	//has children section
	//by default keeping has children no
	
	$post_value['has_children']='N';
	$post_value['income']=$lead_row['income_c'];
	$post_value['degree']=$lead_row['education_c'];
	$post_value['occupation']=$lead_row['occupation_c'];

	$post_value['tieup_source']='sugarcrm';
//	foreach($LEADTOJPROFILE as $key=>$value);
//	echo "<BR>";
//	print_r($post_value);
	$errors=array();
	$is_errors=verify_page1($post_value,$errors);
	if($is_errors){
	//	print_r($errors);
		return false;
	}
	else{
		$username=register_user($post_value);
		$now=date("Y-m-d G:i:s");
		$fh=fopen("$path/profile/auto_registered.txt",'a');
		$str="$now $leadid $username $email $mobile $phone\n";
		fwrite($fh,$str);
		fclose($fh);
		if($phone)
        	$phone_no=$post_value['state_code']."-".$phone;
		else 
			$phone_no="";
		$jsMsg=new JsSuccessAutoRegEmail($leadid,$username,$password,$email,$mobile,$phone_no);
		$jsMsg->sendMessage();

		$processUserId=$process_user_mapping["auto_registration"];
		//update lead status
		$sql="update $partitionLeads set status='24',modified_user_id='$processUserId',date_modified=NOW() where id='$leadid'";
		$res=mysql_query_decide($sql);

		//update Jsprofileid of lead

		$sql="update $partitionLeadsCstm set jsprofileid_c='$username',username_c='$username',disposition_c='23' where id_c='$leadid'";
		$res=mysql_query_decide($sql);

		//Get profileid of the user

		$sql="select PROFILEID,AGE from newjs.JPROFILE where USERNAME='$username'";
		$res=mysql_query_decide($sql);
		$row=mysql_fetch_assoc($res);
		$profileid=$row['PROFILEID'];
		$age=$row['AGE'];
		$income=$post_value['income'];

		//Fields of other pages to be updated in JPROFILE database. Following are creation of update query
		$others_update_sql="update newjs.JPROFILE set ";
		$update_arr=array();
			foreach($LEADTOJPROFILEOTHERPAGES as $key=>$value){
				if($lead_row[$key]){
					$update_arr[]=$value." = '".$lead_row[$key]."' ";
				}
			}
		if($lead_row['manglik_c']){
			if($lead_row['manglik_c']=='Y')
				$manglik='M';
			else
				$manglik=$lead_row['manglik_c'];
			$update_arr[]="MANGLIK = '".$manglik."' ";
		}
		$update_arrTostring=implode(",",$update_arr);
		$others_update_sql.=$update_arrTostring;
		$others_update_sql.="where USERNAME='$username'";
//		echo $others_update_sql;
//		mysql_query_decide($others_update_sql) or logerror("Some problem into data of sugar where leadid=$leadid");
		$objUpdate = JProfileUpdateLib::getInstance();
		$arrUpdateParams = $objUpdate->convertUpdateStrToArray($update_arrTostring);
		$result = $objUpdate->editJPROFILE($arrUpdateParams,$username,'USERNAME');
		if(false == $result) {
			logerror("Some problem into data of sugar where leadid=$leadid");
		}
		$hobbies=array();
		if($lead_row['hobbies_c']){
		$hobbies_tmp=$lead_row['hobbies_c'];
		$hobbies_arr=explode(",",$hobbies_tmp);
		$hobbies=str_replace("^","",$hobbies_arr);
		}

		//create About yourself field if about the profile field in lead profile is empty. 
		//Method is defined in Jsutils file
		if($lead_row['about_the_profile_c']){
			$about_yourself=$lead_row['about_the_profile_c'];
		}
		else{
			if($lead_row['gender_c']=='M'){
				$all_fields=array(
					 'father_occupation'=>$lead_row['father_occupation_c'],
					 'brothers'=>$lead_row['no_of_brothers_c'],
					 'married_brothers'=>$lead_row['no_of_brothers_married_c'],
					 'drink'=>$lead_row['drink_c'],
					 'smoke'=>$lead_row['smoke_c'],
					 'school_name'=>$lead_row['school_name_c'],
					 'college_name'=>$lead_row['college_name_c'],
					 'current_employer'=>$lead_row['current_employer_c'],
					 'personality_attribute'=>$lead_row['lead_attribute_c']
				 );
				$about_yourself=create_aboutyourself($all_fields,'M');
			}
			else
			{

				$all_fields=array(
					'father_occupation'=>$lead_row['father_occupation_c'],
					'school_name'=>$lead_row['school_name_c'],
					'college_name'=>$lead_row['college_name_c'],
					'current_employer'=>$lead_row['current_employer_c'],
					'personality_attribute'=>$lead_row['lead_attribute_c'],
					'hobbies'=>$hobbies,
					'married_working'=>$lead_row['work_c'],
					'gotra'=>$lead_row['gothra_c'],
					'subcaste'=>$lead_row['subcaste_c']
				);
				$about_yourself=create_aboutyourself($all_fields,'F');
			}
		}
		if($about_yourself){
			update_about_yourself($about_yourself,$profileid,$db,$leadid,$partitionLeads,$partitionLeadsCstm,"auto_registration");
			if($post_value['gender']=='F')
				include_once($path."/profile/sugarcrm_registration/registration_page2.inc");

			/* Scenarios checked for IVR call: 1. junk number exist (no ivr call)
							  2. Duplicate Exist (no ivr call)
							  3. ivr call (if neither junk nor duplicate)
			*/
			include_once($path."/ivr/jsPhoneVerify.php");
			if($mobile){
				$ivr_phone 	=$mobile;
				$phoneType	='M';
				$ivr_std 	='';
			}
			else if($phone){
				$ivr_phone 	=$phone;
				$phoneType	='L';
				$ivr_std 	=trim($state_code);
				$ivr_phone	=$ivr_std."-".$phone;
			}
			if($mobile || $phone){
		    		$chk_junk =chkJunkNumberList($ivr_phone,$ivr_std,$phoneType);
				if($chk_junk)
					phoneUpdateProcess($profileid,'',$phoneType,'J');
			}
			/* IVR - code ends */
			/* SMS Code for sending sms to users */
			
include_once(JsConstants::$docRoot."/commonFiles/sms_inc.php");
			$parameters = array("KEY"=>"SI_WELCOME","PROFILEID"=>$profileid,"DATA"=>$profileid);
			sendSingleInstantSms($parameters);

			/* Ends Here of SMS code */
		}
		return true;

	}
	}
	else
	{
//		echo "<BR>No such lead in sugarcrm";
		return false;
	}

}
//register_lead('e83d4204-27c8-aaf2-fb27-4d772fa7e8e6');
?>
