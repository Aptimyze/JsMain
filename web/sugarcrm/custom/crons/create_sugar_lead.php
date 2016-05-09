<?php
$path=realpath(dirname(__FILE__)."/../..");
include($path.'/include/utils/Jscreate_lead.php');
/**
 * It will create a sugarcrm lead. data should be given in following format
 * Leads email address: $email
 * Enquirer email address: $email2
 * Lead Mobile number: $mobile1
 * Enquirer Mobile number: $mobile2
 * Caste: $caste_c
 * Religion: $religion_c
 * Gender: $gender_c
 * Mother tongue: $mother_tongue_c
 * Lead landline number: $phone1
 * Enquirer landline number:$phone2
 * Name of lead: last_name
 * Dateof birth (in YYYY/MM/DD format): $date_birth_c
 * Age: $age_c
 * Marital status: $marital_status_c
 * Education: $education_c
 * Occupation: $occupation_c
 * City: $city_c
 * STD: $std_c
 * School name: $school_name_c
 * College Name: $college_name_c
 * Father Occupation: $father_occupation_c
 * No of brothers: $no_of_brothers_c
 * No of Married brothers: $no_of_brothers_married_c
 * Smoke : $smoke_c
 * Drink: $drink_c
 * Planning to work after marriage: $work_c
 * Which attribute describes you best: $lead_attribute_c
 * Gotra: $gothra_c
 * Subcaste: $subcaste_c
 * Income : $income_c
 * Current Employer: $current_employer_c
 * Maglik: $manglik_c
 * Height: $height_c
 * Primary Postal Code: $primary_address_postalcode
 * Primary Address: $primary_address_street
 * Source: $source_c
 * */
$data=array_merge($_GET,$_POST);
	if(count($data)>=1){
		$errors=validate_lead_data($data);
		$com_fields=array(
			'email',
			'email2',
			'mobile1',
			'mobile2',
			'phone1',
			'phone2'
		);
		$com_arr1=array();
		$com_arr2=array();
		foreach($com_fields as $field){
			if(in_array($field,$errors))
				$com_arr1[]=$field;
			if(array_key_exists($field,$data))
				$com_arr2[]=$field;
		}
		$cnt1=count($com_arr1);
		if(count(array_diff($com_arr2,$com_arr1))==0 &&$cnt1>0)
			echo "Err: Error in ".implode(" ",$com_arr1)." ".implode(" ",array_diff($errors,$com_arr1));
		else
		{
			$res=jscreate_lead($data,$errors);
			if($res){
				if(count($errors)){
					$err=implode(" ",$errors);
					echo "Info:Incorrect fields - $err";
				}
				else echo "Success";
			}
		}
	}
	else 
		echo "Err: No data";
?>
