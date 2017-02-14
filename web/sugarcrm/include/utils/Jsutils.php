<?php
$path1=dirname(__FILE__);
//die;
chdir("$path1/../..");
include_once("custom/include/language/en_us.lang.php");
include_once(JsConstants::$docRoot."/profile/connect_db.php");
/**get the detail of a lead given the lead_id 
 * @param string lead_id 
 * @returns Array of all the fields-value of the lead
 * */
function get_lead($lead_id,$db){
	$result_field1=$db->requireSingleRow("Select * from leads,leads_cstm where id='$lead_id' AND id_c='$lead_id'");
	//$result_field2=$db->requireSingleRow("Select * from leads_cstm where id_c='$lead_id'");
	$email_id=$db->getOne("Select email_address_id from email_addr_bean_rel where bean_id='$lead_id' AND deleted='0'");
	$email_address=$db->getOne("select email_address from email_addresses where id=$email_id");
//	$result_fields=array_merge($result_field1,$result_field2);
	$result_field1['email_address']=$email_address;
	return $result_field1;
}
/** assign profileID to a lead. This is used to update profileid_c field of the lead.
 * @param string id of the lead
 * @param string profile id to be assigned
 * @returns true if updated otherwise false
 * */
function addProfileId($lead_id,$profileid,$db){
	$existngPID=$db->getOne("select jsprofileid_c from sugarcrm.leads_cstm where id_c='$lead_id'");
	if(empty($existngPID)){
	$sql="UPDATE sugarcrm.leads_cstm set jsprofileid_c='$profileid' where id_c='$lead_id'";
	$result=$db->query($sql);
	}
	if($result)
		return true;
	else
		return false;
}
/** get the email address of the lead given leadid
 * @param string lead id
 * @returns email address of the lead
 *
 * */

function get_lead_email($leadid){
	$db = connect_slave();
	$lead_enq_sql="select enquirer_email_id_c from sugarcrm.leads_cstm where id_c='$leadid'";
	$lead_res=mysql_query_decide($lead_enq_sql);
	$row=mysql_fetch_assoc($lead_res);
	if($row['enquirer_email_id_c']!=''){
//	print_r($lead_res);
		return $row['enquirer_email_id_c'];
	}
	$sql_email1="select email_address_id from sugarcrm.email_addr_bean_rel where bean_id='$leadid' and deleted <> '1'";
	$res_email1=mysql_query_decide($sql_email1);
	if($row_email1=mysql_fetch_assoc($res_email1)){
	$email_addr_id=$row_email1['email_address_id'];
	$sql_email2="select email_address from sugarcrm.email_addresses where id='$email_addr_id'";
	$res_email2=mysql_query_decide($sql_email2);
	$row_email2=mysql_fetch_assoc($res_email2);
	return $row_email2['email_address'];
	}
	return '';
}

function create_aboutyourself($about_yourselfArr,$gender){
	$result_arr=array();
	if($gender=='M'){
		if($about_yourselfArr['father_occupation'])
		{
			$result_arr[] = "Father's Occupation: ".$GLOBALS['app_list_strings']['father_occupation_list'][$about_yourselfArr['father_occupation']];
		}
		if($about_yourselfArr['brothers'])
		{
			$brother_text= "No of Borthers: ".$about_yourselfArr['brothers'];
		if($about_yourselfArr['married_brothers'])
		{
			$brother_text.=" of which married: ".$about_yourselfArr['married_brothers'];
			$result_arr[]=$brother_text; 
		}
		}
		if($about_yourselfArr['live_with_parents'])
		{
			switch($about_yourselfArr['live_with_parents']){
			case 'Y': $lwp_temp="Yes";
			break;
			case 'N': $lwp_temp="No";
			break;
			case 'A': $lwp_temp="Not Applicable";
			break;
			}
			$result_arr[] = "Living with Parents: ".$lwp_temp;
			//Not Applicable option is not there in JPROFILE so not updating it if user has selected not applicable
		}		
		if($about_yourselfArr['school_name'])
		{
			$result_arr[]=" School Name: ".$about_yourselfArr['school_name'];
		}
		if($about_yourselfArr['college_name'])
		{
			$result_arr[]=" College Name: ".$about_yourselfArr['college_name'];
		}
		if($about_yourselfArr['current_employer'])
		{
			$result_arr[]=" Current Employer: ".$about_yourselfArr['current_employer'];
		}
		if($about_yourselfArr['drink'])
		{
			switch($about_yourselfArr['drink']){
			case 'Y': $drink_temp="Yes";
			break;
			case 'N': $drink_temp="No";
			break;
			case 'O': $drink_temp="Occasionally";
			break;
			}
			$result_arr[]=" Drink: $drink_temp";
		}
		if($about_yourselfArr['smoke'])
		{
			switch($about_yourselfArr['smoke']){
			case 'Y': $smoke_temp="Yes";
			break;
			case 'N': $smoke_temp="No";
			break;
			case 'O': $smoke_temp="Occasionally";
			break;
			}
			$result_arr[]="Smoke: $smoke_temp";
		}
		if($about_yourselfArr['diet'])
		{
			switch($about_yourselfArr['diet']){
			case 'V': $diet_temp="Vegetarian";
			break;
			case 'N': $diet_temp="Non Vegetarian";
			break;
			case 'J': $diet_temp="Jain";
			break;
			}
			$result_arr[]="Diet: $diet_temp ";
		}
		if($about_yourselfArr['personality_attribute'])
		{
			$result_arr[]="Which attribute describes him the best: ".$GLOBALS['app_list_strings']['lead_attribute_list'][$about_yourselfArr['personality_attribute']];
		}
	}
	else if($gender=='F'){
		if($about_yourselfArr['father_occupation'])
		{
			$result_arr[] = "Father's Occupation: ".$GLOBALS['app_list_strings']['father_occupation_list'][$about_yourselfArr['father_occupation']];
			$jprofile_update[]=" FATHER_OCC='".$about_yourselfArr['father_occupation']."'";
		}
		if($about_yourselfArr['school_name'])
		{
			$result_arr[]=" School Name: ".$about_yourselfArr['school_name'];
		}
		if($about_yourselfArr['college_name'])
		{
			$result_arr[]=" College Name: ".$about_yourselfArr['college_name'];
		}
		if($about_youselfArr['current_employer'])
		{
			$result_arr[]=" Current Employer: ".$about_yourselfArr['current_employer'];
		}
		if($about_yourselfArr['diet'])
		{
			switch($about_yourselfArr['diet']){
			case 'V': $diet_temp="Vegetarian";
			break;
			case 'N': $diet_temp="Non Vegetarian";
			break;
			case 'J': $diet_temp="Jain";
			break;
			}
			$result_arr[]="Diet: $diet_temp";
		}
		if($about_yourselfArr['personality_attribute'])
		{
			$result_arr[]="Which attribute describes her the best: ".$GLOBALS['app_list_strings']['lead_attribute_list'][$about_yourselfArr['personality_attribute']];
		}
		if($about_yourselfArr['subcaste'])
		{
			$result_arr[] = "SUBCASTE: ".$about_yourselfArr['subcaste'];
		}
		if($about_yourselfArr['gotra'])
		{
			$result_arr[] = "GOTHRA: ".$about_yourselfArr['gotra'];
		}
		if($about_yourselfArr['hobbies']){
			foreach($about_yourselfArr['hobbies'] as $hobby)
				$hob_arr[]=$GLOBALS['app_list_strings']['hobbies_list'][$hobby];
			$hob_str=@implode(", ",$hob_arr);
			
			$result_arr[]= "Hobbies: ".$hob_str;
		}
		if($about_yourselfArr['married_working']){
			switch($about_yourselfArr['married_working']){
			case 'Y': $mar_str='Yes';
				break;
			case 'N': $mar_str='No';
				break;
			}
			$result_arr[]="Planning to work after marriage: $mar_str";
		}
		}
		if(count($result_arr) > 0)
		{
			$result_arr_str = @implode("<BR>",$result_arr);
			$about_yourself_final=$result_arr_str;
			$about_yourself=mysql_real_escape_string(stripslashes($about_yourself_final));
			return $about_yourself;
		}
		else return false;
}
function fetchLeadSource($source_c,$campaign_id,$db){
			$db = connect_slave();
			$sugar_source_to_js_source=array(
				1=>"S_PAd_Call",
				2=>"S_Walk_In",
				5=>"S_PAd_Mail",
				6=>"S_Samaj_Bk",
				7=>"S_SamajWeb",
				9=>"S_FreeSite",
				10=>"S_PaidSite",
				12=>"S_Mob_Site",
				13=>"S_AdNaukri",
				14=>"S_Alliance",
				15=>"S_Inb_Toll",
				16=>"S_PaidAuto",
				17=>"S_Profilep",
				);
		if($source_c==4){//If source is newspaper, then get the campaign
			if($campaign_id)
			{
				$news_paper_to_js_source=array(
					1=>"S_TOI",
					2=>"S_HT",
					3=>"S_AmarUjla",
					4=>"S_Jagran",
					5=>"S_Hindu",
					6=>"S_RPatrika",
					7=>"S_P_Kesari",
					8=>"S_Tribune",
					9=>"Sakaal",
					10=>"S_NaiDunia",
					11=>"Satta",
					12=>"Smchr",
					13=>"Smchr",
					14=>"S_Lokmat",
					15=>"Sandesh",
					16=>"S_Hitwada",
					17=>"S_MManorma",
				);
				$sql="select newspaper_c from sugarcrm.campaigns_cstm where id_c='".$campaign_id."'";
				if($db instanceof DBManager){
					$result_fields=$db->requireSingleRow($sql);
				}
				else {
					$res=mysql_query_decide($sql) or die("Problem somewhere");
					$result_fields=mysql_fetch_assoc($res);
				}	
				if($result_fields[newspaper_c])
					return $news_paper_to_js_source[$result_fields[newspaper_c]];
				else
					return "S_NwsPpr_O";
			}
		}
		else
			return $sugar_source_to_js_source[$source_c];
}
?>
