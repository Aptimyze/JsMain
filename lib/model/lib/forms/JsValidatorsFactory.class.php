<?php

/**
 * Description of JsValidationsFactory
 * this class is for creating validation class objects depending on the fields supplied
 * @author ankit
 */
class JsValidatorsFactory {
    
  /*
   * Function to create a validator object based on the field passed as an argument
   * @field- a single name of field for which vaidator object is required
   * @from_values - if form has been submitted then this field is passed
   * @page - page for getFieldMapKey
   * @loggedInObj - in case of edit a logged object is required
   * return- return a validator object
   */
  public static function getValidatorObj($field,$form_values="",$page,$loggedInObj="") {
                $const_cl=$field->getConstraintClass();
		$field_map_name=ObjectiveFieldMap::getFieldMapKey($field->getName(),$page);
		//get all dropdown values from Fieldmaplib
                if($loggedInObj) {
                  $form_values['religion_caste'] = $loggedInObj->getRELIGION();
                  $form_values['gender_dob'] = $loggedInObj->getGENDER();
                }
                else {
                  $form_values['religion_caste'] = $form_values['religion'];
                  $form_values['gender_dob'] = $form_values['gender'];
                }
		$defaultMsg = ErrorHelp::getDefaultMessage($field->getNAME());
		$errInvalid = ErrorHelp::INVALID_VALUE_ERR;
                $hobby_arr=array('hobbies_language','hobbies_hobby','hobbies_interest','hobbies_music','hobbies_book','hobbies_sports','hobbies_cuisine','hobbies_dress');
		$not_to_check_arr=array('termsandconditions','source','record_id','phone_mob','phone_res','promo','email','password','dtofbirth');
		if($field_map_name &&  !in_array($field_map_name,$not_to_check_arr))
		{
			$choices=@array_keys(FieldMap::getFieldLabel($field_map_name,'',1));
                        if($field->getName() == "CITY_RES"){
                            $choices[]="0";
                        }
                        if($field->getName()=="CITY_RES")
			{
				$stateArr = array_keys(FieldMap::getFieldLabel("state_india",'',1));
				foreach($stateArr as $x=>$y)
				{
					$choices[]=$y."OT";
				}
				$stateArr = array_keys(FieldMap::getFieldLabel("city_usa",'',1));
				foreach($stateArr as $x=>$y)
				{
					$choices[]=$y;
				}
			}
			$choiceValidator = new sfValidatorChoice(array('choices'=>$choices,'required'=>false),array('invalid'=>$errInvalid));
		}
                if(in_array($field_map_name,$hobby_arr))
		{
			return new jsValidatorWhiteList(array('required'=>false,'FieldMapLabel'=>@$field_map_name,'Value'=>@$form_values[$field->getName()],'FieldName'=>@$field->getName(),'isHobby'=>1));
		}
		switch($const_cl){
		case 'partner_height':
			{
				return new jsValidatorPartner(array('small'=>@$form_values['p_lheight'],'large'=>@$form_values['p_hheight'],"type"=>"P_HHEIGHT"));
				break;
			}
		case 'partner_age':
			{
				return new jsValidatorPartner(array('small'=>@$form_values['p_lage'],'large'=>@$form_values['p_hage'],"type"=>"P_HAGE"));
				break;
			}
		case 'partner_rupee':
			{
				return new jsValidatorPartner(array('required'=>false,'small'=>@$form_values['p_lrs'],'large'=>@$form_values['p_hrs'],"type"=>"P_HRUPEE"));
				break;
			}
		case 'partner_dollar':
		{
			return new jsValidatorPartner(array('required'=>false,'small'=>@$form_values['p_lds'],'large'=>@$form_values['p_hds'],'type'=>'P_HDOLLAR'));
			break;
		}
		case 'string':
			return new sfValidatorString(array('required'=>false));
			break;
                case 'stringName':
			return new sfValidatorString(array('required'=>false,'nameField'=>1));
			break;
		case 'email':
			$err_em_mes="This email is already registered in our system.";
                        if(!MobileCommon::isApp() && !MobileCommon::isMobile())
                                $err_em_mes.=" To retrieve the username and password,"."<a style=\"cursor:pointer\" onclick=\"send_username_password('".@$form_values['email']."')\" name=\"retrieve_profile_link\" id=\"retrieve_profile_link\"  style=\"color:#C8080E;\">click here.</a>";
			return new jsValidatorMail(array(),array('required' => $defaultMsg,'err_email_duplicate'=>"$err_em_mes"));
			break;
		case 'password':
			return new jsValidatorPassword(array('min_length'=>8,'email'=>@$form_values['email']),array('required' => $defaultMsg));
		case 'name_of_user':
			return new jsValidatorNameOfUser(array('required'=>true,'nameOfUser'=>@$form_values['name_of_user']));
			break;
		case 'pin':
		{
			return new jsValidatorPincode(array('required'=>false,'city'=>$form_values['city_res']));		
			break;
		}
		case 'mobile':
			if(isset($form_values['phone_res']['landline']))
				return new jsValidatorMobile(array('min_length'=>10,'max_length'=>10,'required'=>false,'landline'=>$form_values['phone_res']['landline']));
			else
				return new jsValidatorMobile(array('min_length'=>10,'max_length'=>10));				
			break;
		case 'landline':
			return new jsValidatorLandline(array('min_length'=>10,'max_length'=>10,'required'=>false));
			break;
                case 'alt_mobile':
				return new jsValidatorMobile(array('min_length'=>10,'max_length'=>10,'required'=>false,'altMobile'=>1));
			break;
		case 'yourinfo' :
			return new jsValidatorYourinfo(array('required'=>true,'min_length'=>100,'yourinfo'=>$form_values['yourinfo']));
			break;
		case 'messenger_id':
			return new jsValidatorMessenger(array('required'=>false));
			break;
		case 'messenger_channel':
			return new sfValidatorAnd(array($choiceValidator,new jsValidatorMessengerChannel(array('messenger_id'=>$form_values['messenger_id']))),array('required'=>false));
			break;
		case 'mandatory':
			return new sfValidatorString(array(),array('required' => $defaultMsg));
			break;
		case 'mstatus':
			return new sfValidatorAnd(
                    array(new jsValidatorMStatus(),$choiceValidator),array(),array('required'=>$defaultMsg));
			break;
                case 'manglik':
			return new sfValidatorAnd(array($choiceValidator,new jsValidatorManglik(array('religion'=>$form_values['religion']))),array('required'=>false));
			break;
		case 'havechild':
			if($form_values['mstatus']!='N')
			return new sfValidatorAnd(array($choiceValidator,new jsValidatorHasChildren(array('mstatus'=>$form_values['mstatus']))),array('required'=>true),array('required' => $defaultMsg));
			else
			return new jsValidatorHasChildren(array('mstatus'=>$form_values['mstatus']));
			break;	
		case 'dob':
			if(@$form_values['gender_dob']=='M')
				$min=21;
			else
				$min=18;
			return new sfValidatorDate(array('required'=>true,'max'=>date('Y-m-d',strtotime( date('Y-m-d') . " -$min year" ))),array('required' => $defaultMsg,'max'=>"You must be atleast $min years old to register to this site."));
			break;
		case 'caste':
		{
			return new jsValidatorCaste(array('religion'=>$form_values['religion_caste']),array('required' => $defaultMsg));
			break;
		}
                case 'religion':
			return new jsValidatorReligion(array('caste'=>$form_values['caste'],'required'=>false),array('required' => $defaultMsg));
			break;
		case 'dropdown_req':
			return new sfValidatorChoice(array('choices'=>$choices),array('invalid'=>$errInvalid,'required' => $defaultMsg));
			break;
		case 'dropdown_not_req':
			return $choiceValidator;
			break;
		case 'native_place':
			{
				$szName = $field->getName();
				$szMapLabel = ObjectiveFieldMap::getFieldMapKey($szName);
				if($form_values)
				{
					$InputValues = $form_values[strtolower($field->getName())];
				}
				return new jsValidatorNativePlace(array('required'=>false,'FieldMapLabel'=>@$szMapLabel,'Value'=>@$InputValues,'FieldName'=>@$szName));
				break;
			}
                case 'sibling':
			return new sfValidatorSibling(array('required'=>false,'formValues'=>$form_values));
			break;		
		case 'time_to_call':
			return new jsValidatorTimeToCall(array('required'=>false));
			break;
		case 'city':
			$city=$form_values['city_res'];
			$country=$form_values['country_res'];
			return new jsValidatorCountryCity(array('required'=>false,'city'=>$city,'country'=>$country,'fieldName'=>'city'));
			break;
		case 'country':
			$city=$form_values['city_res'];
			$country=$form_values['country_res'];
			return new jsValidatorCountryCity(array('required'=>true,'city'=>$city,'country'=>$country,'fieldName'=>'country'));
			break;
		case 'sect':
			return new jsValidatorSect(array('religion'=>$form_values['religion_caste'],'required'=>false),array('required' => $defaultMsg));
			break;	
		case 'integer':
			return new sfValidatorInteger(array('required'=>false));
			break;	
		case 'HANDICAPPED':
			return new jsValidatorHandicapped(array('handicapped'=>$form_values['handicapped'],'natureOfhandicapped'=>$form_values['nature_handicap'],'fieldName'=>"HANDICAPPED",'required'=>false),array('required' => $defaultMsg));
			break;
		case 'NATURE_HANDICAP':
			return new jsValidatorHandicapped(array('handicapped'=>$form_values['handicapped'],'natureOfhandicapped'=>$form_values['nature_handicap'],'fieldName'=>"NATURE_HANDICAP",'required'=>false),array('required' => $defaultMsg));
			break;
		case 'partner_age':
			{
				return new jsValidatorAge(array('minAge'=>@$form_values['p_lage'],'maxAge'=>@$form_values['p_hage'],"Gender"=>@$form_values['p_gender']));
				break;
			}
		case 'partner_mstatus':		
		case 'partner_country':
		case 'partner_city':
		case 'partner_caste':	
		case 'partner_religion':
		case 'partner_mtongue':
		case 'partner_manglik':
		case 'partner_diet':
		case 'partner_smoke':
		case 'partner_drink':
		case 'partner_complexion':
		case 'partner_btype':
		case 'partner_challenged':
		case 'partner_nchallenged':
		case 'partner_education':
		case 'partner_occupation':
			{
				$szName = $field->getName();
				$szMapLabel = ObjectiveEditFieldMap::getFieldMapKey($szName);
				$InputValues = $form_values[$field->getName()];

				return new jsValidatorWhiteList(array('required'=>false,'FieldMapLabel'=>@$szMapLabel,'Value'=>@$InputValues,'FieldName'=>@$szName,'isHobby'=>0));
				break;
			}
                        
                
		}
	}
  }

