<?php
define('sugarEntry',true);
$path=realpath(dirname(__FILE__)."/../../..");
chdir("$path/sugarcrm");
require_once("include/entryPoint.php");
require_once("include/utils/Jsde_duplicate.php");
require_once("include/utils/Jsutils.php");
require_once("include/utils/JsToLeadFieldMapping.php");
require_once("custom/include/language/en_us.lang.php");
/*  
 *  This function will create a lead if lead data is provided in associative array format
 *  at least two fields are compulosry. One is last_name and others are one of email,email1,mobile1,mobile2,phone1,phone2
 *  If lead is successfully created true will be returned otherwise false 
 */
function jscreate_lead($data,$errors=array()){
	global $SUGARCRMDBCOLUMNS;
	$db_slave = connect_slave();
	$duplicate=new Duplicate($db_slave);
	mysql_select_db("sugarcrm",$db_slave);
	global $current_user;
		$current_user=new User();
	$current_user->getSystemUser();

	if(!count($data)){
		echo "Err: No data";
		add_in_err_lead($data,'No_data');
		return false;
	}
	if($data['last_name']==''){
		echo "Err: No lead name";
		add_in_err_lead($data,'No_last');
		return false;
	}
	$data['mobile1']=trim($data['mobile1']);
        $data['mobile2']=trim($data['mobile2']);
	if($data['email']!='' or $data['email2']!='' or $data['mobile1']!='' or $data['mobile2']!='' or $data['phone1']!='' or $data['phone2']!=''){
		$db = & DBManagerFactory::getInstance();
		try{
			$db->query("set session wait_timeout=30000,interactive_timeout=30000,net_read_timeout=30000");
		}
		catch(Exception $ex)
		{
		
		}
		$myQuery = "SELECT GET_LOCK('sugarcrm.create_lead',15)";
		if($db->getOne($myQuery)==="1"){
		$isDup=false;
		$jsLead=new Lead();
		if($data['checkJprofile']){
			if(!$data['phone1'])
                                $data['std_c']="";
                        if(!$data['phone2'])
                                $data['std_enquirer_c']="";
			$isDup1=$duplicate->isDuplicate($data['email'],$data['mobile1'],$data['std_c'],$data['phone1']);
			$isDup2=$duplicate->isDuplicate($data['email2'],$data['mobile2'],$data['std_enquirer_c'],$data['phone2']);
			if($isDup1 || $isDup2)
				$isDup=true;
		}
		else{
		$dup1=$duplicate->getDuplicateLeadId($data['email'],$data['mobile1'],$data['std_c'],$data['phone1']);
		$dup2=$duplicate->getDuplicateLeadId($data['email2'],$data['mobile2'],$data['std_enquirer_c'],$data['phone2']);
		if(!$dup1)
			$dup1=array();
		if(!$dup2)
			$dup2=array();
		$dup1=array_merge($dup1,$dup2);
		unset($dup2);
		if(count($dup1)){
			$isDup=true;
			//Trac 574 requirement..if lead is duplicate then update viewed_profileid_c only
			if($data['from_dp_banner']){
				$jsLead->id=$dup1[0];
				$jsLead->viewed_profileid_c=$data['viewed_profileid_c'];
				$jsLead->save(true);
				return true;
			}
		}
		elseif($data['from_dp_banner']){
			$isDup=$duplicate->isDuplicate($data['email'],$data['mobile1'],$data['std_c'],$data['phone1']);
		}
		//trac 574 changes end 
		}
		if($isDup){
			echo "Err: Duplicate";
		add_in_err_lead($data,'Dup');
			return(false);
		}
		if($data['email']&&!in_array('email',$errors))
			$data['email1']=$data['email'];
		if($data['email2']&&!in_array('email2',$errors))
			$data['enquirer_email_id_c']=$data['email2'];
		if($data['mobile1']&&!in_array('mobile1',$errors))
			$data['phone_mobile']=$data['mobile1'];
		if($data['mobile2']&&!in_array('mobile2',$errors))
			$data['enquirer_mobile_no_c']=$data['mobile2'];
		if($data['phone1']&&!in_array('phone1',$errors))
			$data['phone_home']=$data['phone1'];
		if($data['phone2']&&!in_array('phone2',$errors))
			$data['enquirer_landline_c']=$data['phone2'];
		unset($data['email']);
		unset($data['email2']);
		unset($data['phone1']);
		unset($data['mobile1']);
		unset($data['mobile2']);
		unset($data['phone2']);
		unset($data['from_dp_banner']);
		$SUGARCRMDBCOLUMNS[]='email1';
		$SUGARCRMDBCOLUMNS[]='source_c';
		$SUGARCRMDBCOLUMNS[]='last_name';
		$SUGARCRMDBCOLUMNS[]='primary_address_postalcode';
		$SUGARCRMDBCOLUMNS[]='primary_address_street';
		foreach($data as $key=>$value){
			if(in_array($key,$SUGARCRMDBCOLUMNS)){
//				echo "$key=>$value";
				if(!in_array($key,$errors))
					$jsLead->$key=$value;
			}
		}
		$jsLead->modified_user_id='1';
		$jsLead->created_by='1';
		$jsLead->assigned_user_id='1';
		$jsLead->status='13';
		$jsLead->disposition_c='24';
		$jsLead->save(true);
		}
		$db->query("SELECT RELEASE_LOCK('sugarcrm.create_lead')");
		return true;
	}
	else{
		echo "Err: No Contact Info";
		return false;
	}
}
function validate_lead_data($data){
	$options=$GLOBALS['app_list_strings'];
	$errors=array();
	if(array_key_exists('posted_by_c',$data))
    	if(!array_key_exists($data['posted_by_c'],$options['relation_list']))
			$errors[]='posted_by_c';
	if(array_key_exists('gender_c',$data))
  		if(!array_key_exists($data['gender_c'],$options['gender_list']))
      		$errors[]='gender_c';
	if(array_key_exists('marital_status_c',$data))
	  if(!array_key_exists($data['marital_status_c'],$options['Mstatus']))
		  $errors[]='marital_status_c';
	if(array_key_exists('religion_c',$data))
	  if(!array_key_exists($data['religion_c'],$options['Religion']))
		  $errors[]='religion_c';
	if(array_key_exists('caste_c',$data))
	  if(!array_key_exists($data['caste_c'],$options['Caste']))
		  $errors[]='caste_c';
	if(array_key_exists('mother_tongue_c',$data))
	  if(!array_key_exists($data['mother_tongue_c'],$options['Mtongue']))
		  $errors[]='mother_tongue_c';
	if(array_key_exists('education_c',$data))
	  if(!array_key_exists($data['education_c'],$options['Education']))
		  $errors[]='education_c';
	if(array_key_exists('occupation_c',$data))
	  if(!array_key_exists($data['occupation_c'],$options['Occupation']))
		  $errors[]='occupation_c';
	if(array_key_exists('income_c',$data))
	  if(!array_key_exists($data['income_c'],$options['Income']))
		  $errors[]='income_c';
	if(array_key_exists('source_c',$data))
	  if(!array_key_exists($data['source_c'],$options['source_dom']))
		  $errors[]='source_c';
	if(array_key_exists('manglik_c',$data))
	  if(!array_key_exists($data['manglik_c'],$options['Manglik_list']))
		  $errors[]='manglik_c';
	if(array_key_exists('city_c',$data))
	  if(!array_key_exists($data['city_c'],$options['City_list']))
		  $errors[]='city_c';
	if(array_key_exists('std_c',$data)){
	  if(!preg_match('/^\d+$/',$data['std_c']))
		  $errors[]='std_c';
	}
	if(array_key_exists('isd_c',$data)){
	  if(!preg_match('/^\d+$/',$data['isd_c']))
		  $errors[]='isd_c';
	}
	if(array_key_exists('mobile1',$data)){
	  if(!preg_match('/^\d+$/',trim($data['mobile1'])))
		  $errors[]='mobile1';
	}
	if(array_key_exists('mobile2',$data)){
	  if(!preg_match('/^\d+$/',trim($data['mobile2'])))
		  $errors[]='mobile2';
	}
	if(array_key_exists('isd_enquirer_c',$data)){
	  if(!preg_match('/^\d+$/',$data['isd_enquirer_c']))
		  $errors[]='isd_enquirer_c';
	}
	if(array_key_exists('std_enquirer_c',$data)){
	  if(!preg_match('/^\d+$/',$data['std_enquirer_c']))
		  $errors[]='std_enquirer_c';
	}
	if(array_key_exists('phone1',$data)){
	  if(!preg_match('/^\d+$/',$data['phone1']))
		  $errors[]='phone1';
	}
	if(array_key_exists('phone2',$data)){
	  if(!preg_match('/^\d+$/',$data['phone2']))
		  $errors[]='phone2';
	}
	if(array_key_exists('drink_c',$data))
	  if(!array_key_exists($data['drink_c'],$options['lead_drink_list']))
		  $errors[]='drink_c';
	if(array_key_exists('age_c',$data))
	  if(!preg_match("/\d+/",$data['age_c']))
		  $errors[]='age_c';
	if(array_key_exists('smoke_c',$data))
	  if(!array_key_exists($data['smoke_c'],$options['lead_smoke_list']))
		  $errors[]='smoke_c';
	if(array_key_exists('work_c',$data))
	  if(!array_key_exists($data['work_c'],$options['work_after_marriage_list']))
		  $errors[]='work_c';
	if(array_key_exists('father_occupation_c',$data))
	  if(!array_key_exists($data['father_occupation_c'],$options['father_occupation_list']))
		  $errors[]='father_occupation_c';
	if(array_key_exists('hobbies_c',$data))
	  if(!array_key_exists($data['hobbies_c'],$options['hobbies_list']))
		  $errors[]='hobbies_c';
	if(array_key_exists('no_of_brothers_c',$data)){
	  if(!array_key_exists($data['no_of_brothers_c'],$options['no_of_brothers_list']))
		  $errors[]='no_of_brothers_c';
	}
	if(array_key_exists('no_of_brothers_married_c',$data)){
	  if(!array_key_exists($data['no_of_brothers_married_c'],$options['no_of_brothers_married_list']))
		  $errors[]='no_of_brothers_married_c';
	}
	if(array_key_exists('no_of_sisters_c',$data)){
	  if(!array_key_exists($data['no_of_sisters_c'],$options['no_of_sisters_list']))
		  $errors[]='no_of_sisters_c';
	}
	if(array_key_exists('no_of_sisters_married_c',$data)){
	  if(!array_key_exists($data['no_of_sisters_married_c'],$options['no_of_sisters_married_list']))
		  $errors[]='no_of_sisters_married_c';
	}
	if(array_key_exists('email2',$data)){
		if(!preg_match('/.*@.*/',$data['email2']))
		  $errors[]='email2';
	}
	if(array_key_exists('email',$data)){
		if(!preg_match('/.*@.*/',$data['email']))
		  $errors[]='email';
	}
	if(array_key_exists('date_birth_c',$data)){
		if(!preg_match('/19\d\d-[01]\d-[0123]\d/',$data['date_birth_c']))
			$errors[]='date_birth_c';
	}
	if(array_key_exists('lead_attribute_c',$data))
	  if(!array_key_exists($data['lead_attribute_c'],$options['lead_attribute_list']))
		  $errors[]='lead_attribute_c';
	if(array_key_exists('have_children_c',$data)){
		if($data['have_children_c']!='0' and $data['have_children_c']!='1')
			$errors[]='have_chidren_c';
	}
	if(array_key_exists('primary_address_postalcode',$data)){
		if(!preg_match('/^\d+$/',$data['primary_address_postalcode']))
			$errors[]='primary_address_postalcode';
	}
	if(array_key_exists('height_c',$data)){

        $db = & PearDatabase::getInstance();
		$myQuery = "select LABEL,VALUE from newjs.HEIGHT";
		$myResult=$db->query($myQuery);
		$myRow= array();
		while ($myRow = $db->fetchByAssoc($myResult)) {
			$myArray [$myRow['VALUE']] = $myRow['LABEL'] ;
		}
		$options['Height']= $myArray;
		if(!array_key_exists($data['height_c'],$options['Height']))
			$errors[]='height_c';
	}
	return $errors;

}
function add_in_err_lead($data,$err){
    if($data[source_c]==12 || strtolower($data[js_source_c])=='profilepgk'){
       $sql="INSERT INTO sugarcrm.err_leads values ('$data[email]','$data[age_c]','$data[mobile1]','$data[mother_tongue_c]','$data[last_name]',              '$data[gender_c]','$data[source_c]','$data[js_source_c]','$err')";
        $db = & PearDatabase::getInstance();
       $myResult=$db->query($sql);
    }
}
/*
Jscreate_lead(array(
	'last_name'=>'someone1',
	'mobile1'=>'8923780238'));
print_r(validate_lead_data(array(
	'date_birth_c'=>'1933-14-34',
	'no_of_sisters_c'=>'4',
     'isd_c'=>'42244')));
 */
 
?>
