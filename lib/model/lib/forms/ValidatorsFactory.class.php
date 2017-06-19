<?php
class ValidatorsFactory{
public static $validateZeroForFields = array("FAMILY_INCOME","NATIVE_COUNTRY","STATE_INDIA");
	
	public static function getValidator($field,$form_values="",$page=""){		
		$const_cl=$field->getConstraintClass();
		$field_map_name=ObjectiveFieldMap::getFieldMapKey($field->getName(),$page);
		//get all dropdown values from Fieldmaplib
		$defaultMsg = ErrorHelp::getDefaultMessage($field->getNAME());
		$errInvalid = ErrorHelp::INVALID_VALUE_ERR;
		$not_to_check_arr=array('termsandconditions','source','record_id','phone_mob','phone_res','promo','email','password','dtofbirth');
		if($field_map_name &&  !in_array($field_map_name,$not_to_check_arr))
		{
			$choices=@array_keys(FieldMap::getFieldLabel($field_map_name,'',1));
                        if(in_array($field->getName(),self::$validateZeroForFields))
                        {
                                $choices[]='0';
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
                case 'jamaat':
                        return new jsValidatorJamaat(array('caste'=>$form_values['caste'],'required'=>false),array('required' => $defaultMsg));
                        break;
                case 'sectMuslim':
                        return new jsValidatorSectMuslim(array('religion'=>$form_values['religion'],'required'=>false),array('required' => $defaultMsg));
                        break;
		case 'email':
			$err_em_mes="This email is already registered in our system.";
                        if(!MobileCommon::isApp() && !MobileCommon::isMobile())
                                $err_em_mes.=" To retrieve the username and password,"."<a href=\"#\" onclick=\"send_username_password('".@$form_values['email']."')\" name=\"retrieve_profile_link\" id=\"retrieve_profile_link\"  style=\"color:#C8080E;\">click here.</a>";
			return new jsValidatorMail(array(),array('required' => $defaultMsg,'err_email_duplicate'=>"$err_em_mes"));
			break;
		case 'password':
			return new jsValidatorPassword(array('min_length'=>8,'email'=>@$form_values['email']),array('required' => $defaultMsg));
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
		case 'havechild':
			if($form_values['mstatus']!='N')
			return new sfValidatorAnd(array($choiceValidator,new jsValidatorHasChildren(array('mstatus'=>$form_values['mstatus']))),array('required'=>true),array('required' => $defaultMsg));
			else
			return new jsValidatorHasChildren(array('mstatus'=>$form_values['mstatus']));
			break;	
		case 'dob':
			if(@$form_values['gender']=='M')
				$min=21;
			else
				$min=18;
			$max = 70;
			return new sfValidatorDate(array('required'=>true,'max'=>date('Y-m-d',strtotime( date('Y-m-d') . " -$min year" )),'min'=>date('Y-m-d',strtotime( date('Y-m-d') . " -$max year" ))),array('required' => $defaultMsg,'max'=>"You must be atleast $min years old to register to this site.",'min'=>"Maximum age criteria not met."));
			break;
		case 'caste':
		{
			return new jsValidatorCaste(array('religion'=>$form_values['religion']),array('required' => $defaultMsg));
			break;
		}
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
		}
	}
	
	public static function getEditValidator($field,$form_values="",$loggedInObj){
		$const_cl=$field->getConstraintClass();
		$field_map_name=ObjectiveEditFieldMap::getFieldMapKey($field->getName(),$page);
                        
		//get all dropdown values from Fieldmaplib
		$defaultMsg = ErrorHelp::getDefaultMessage($field->getNAME());
		$errInvalid = ErrorHelp::INVALID_VALUE_ERR;
		$hobby_arr=array('hobbies_language','hobbies_hobby','hobbies_interest','hobbies_music','hobbies_book','hobbies_sports','hobbies_cuisine','hobbies_dress','hobbies_movie');
		$not_to_check_arr=array('termsandconditions','source','record_id','phone_mob','phone_res','promo','email','password','dtofbirth','isd','gender','jamaat');
		if($field_map_name && !in_array($field_map_name,$hobby_arr)&& !in_array($field_map_name,$not_to_check_arr))
		{
			$choices=@array_keys(FieldMap::getFieldLabel($field_map_name,'',1));
			if(in_array($field->getName(),self::$validateZeroForFields))
			{
				$choices[]='0';
			}
			$choiceValidator = new sfValidatorChoice(array('choices'=>$choices,'required'=>false),array('invalid'=>$errInvalid));
		}
  
		if(in_array($field_map_name,$hobby_arr))
		{
			return new jsValidatorWhiteList(array('required'=>false,'FieldMapLabel'=>@$field_map_name,'Value'=>@$form_values[$field->getName()],'FieldName'=>@$field->getName(),'isHobby'=>1));
		}
		switch($const_cl){
		case 'string':
			return new sfValidatorString(array('required'=>false));
			break;
                case 'dob':
			return new jsValidatorDateOfBirth(array("dtofbirth"=>$form_values['DTOFBIRTH']),array('required' => true));
//			if(@$loggedInObj->getGENDER()=='M')
//				$min=21;
//			else
//				$min=18;
//			return new sfValidatorDate(array('required'=>true,'max'=>date('Y-m-d',strtotime( date('Y-m-d') . " -$min year" ))),array('required' => $defaultMsg,'max'=>"You must be atleast $min years old to register to this site."));
			break;
		case 'stringName':
			return new sfValidatorString(array('required'=>false,'nameField'=>1));
			break;
		case 'email':
			return new jsValidatorMail(array('altEmail'=>$form_values["ALT_EMAIL"]),array('required' => $defaultMsg,'err_email_duplicate'=>"This email is already registered in our system"));
			break;
		case 'alt_email':
			return new jsValidatorAlternateMail(array('email'=>$form_values["EMAIL"],'required'=>false));
			break;
		case 'jamaat':
			if($form_values['CASTE'])
			{
				$caste = $form_values['CASTE'];
			}
			else
			{
				$caste = $loggedInObj->getCASTE();
			}
			return new jsValidatorJamaat(array('caste'=>$caste,'required'=>false),array('required' => $defaultMsg));
			break;
		case 'pin':
		{
      //if desktop and profile is marked complete then only string
      if(MobileCommon::isDesktop() && $loggedInObj->getINCOMPLETE() == "N"){
        return new sfValidatorString(array('required'=>false));
      }
      if($form_values['city_res']){
        $cityVal = $form_values['city_res'];
      }else{
        $cityVal = $loggedInObj->getCITY_RES();
      }
			return new jsValidatorPincode(array('required'=>false,'city'=>$cityVal));		
			break;
		}
		case 'mobile':
			if(isset($form_values['PHONE_RES']['landline']))
				return new jsValidatorMobile(array('min_length'=>10,'max_length'=>10,'required'=>false,'landline'=>$form_values['PHONE_RES']['landline']));
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
			return new sfValidatorString(array('required'=>true,'min_length'=>100),array('required'=>$defaultMsg,'min_length'=>$defaultMsg));
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
                        return new jsValidatorMStatus(array('required' => true));
			break;
		case 'havechild':
			return new sfValidatorAnd(array($choiceValidator,new jsValidatorHasChildren(array('mstatus'=>$form_values['mstatus']))),array('required'=>false),array('required' => $defaultMsg));
			break;	
		case 'caste':
			return new jsValidatorCaste(array('religion'=>$loggedInObj->getRELIGION(),'jamaat'=>@$form_values['JAMAAT']),array('required' => $defaultMsg));
			break;
		case 'religion':
			return new jsValidatorReligion(array('caste'=>$form_values['CASTE'],'required'=>false),array('required' => $defaultMsg));
			break;
		case 'dropdown_req':
			return new sfValidatorChoice(array('choices'=>$choices),array('invalid'=>$errInvalid,'required' => $defaultMsg));
			break;
		case 'dropdown_not_req':
			return $choiceValidator;
			break;
		case 'sibling':
			return new sfValidatorSibling(array('required'=>false,'formValues'=>$form_values));
			break;		
		case 'time_to_call':
			return new jsValidatorTimeToCall(array('required'=>false));
			break;
                case 'city':
			$city=$form_values['CITY_RES'];
			$country=$form_values['COUNTRY_RES'];
			return new jsValidatorCountryCity(array('required'=>false,'city'=>$city,'country'=>$country,'fieldName'=>'city'));
			break;
		case 'country':
			$city=$form_values['CITY_RES'];
			$country=$form_values['COUNTRY_RES'];
			return new jsValidatorCountryCity(array('required'=>true,'city'=>$city,'country'=>$country,'fieldName'=>'country'));
			break;
                case 'sect':
			return new jsValidatorSect(array('religion'=>$loggedInObj->getRELIGION(),'required'=>false),array('required' => $defaultMsg));
			break;	
		case 'integer':
			return new sfValidatorInteger(array('required'=>false));
			break;	
		case 'HANDICAPPED':
			return new jsValidatorHandicapped(array('handicapped'=>$form_values['HANDICAPPED'],'natureOfhandicapped'=>$form_values['NATURE_HANDICAP'],'fieldName'=>"HANDICAPPED",'required'=>false),array('required' => $defaultMsg));
			break;
		case 'NATURE_HANDICAP':
			return new jsValidatorHandicapped(array('handicapped'=>$form_values['HANDICAPPED'],'natureOfhandicapped'=>$form_values['NATURE_HANDICAP'],'fieldName'=>"NATURE_HANDICAP",'required'=>false),array('required' => $defaultMsg));
			break;
		case 'partner_height':
			{
        $szName = $field->getName();
        $szMapLabel = ObjectiveEditFieldMap::getFieldMapKey($szName);
        $InputValues = $form_values[$field->getName()];

        return new sfValidatorAnd(array( new jsValidatorWhiteList(array('required'=>false,'FieldMapLabel'=>@$szMapLabel,'Value'=>@$InputValues,'FieldName'=>@$szName,'isHobby'=>0)), new jsValidatorPartner(array('small'=>@$form_values['P_LHEIGHT'],'large'=>@$form_values['P_HHEIGHT'],"type"=>"P_HHEIGHT"))),array('required'=>true),array('required' => $defaultMsg));

				break;
			}
		case 'partner_age':
			{
				return new jsValidatorAge(array('minAge'=>@$form_values['P_LAGE'],'maxAge'=>@$form_values['P_HAGE'],"Gender"=>@$form_values['P_GENDER']));
				break;
			}
		case 'partner_rupee':
			{
				return new jsValidatorPartner(array('required'=>false,'small'=>@$form_values['P_LRS'],'large'=>@$form_values['P_HRS'],"type"=>"P_HRUPEE"));
				break;
			}
		case 'partner_dollar':
			{
				return new jsValidatorPartner(array('required'=>false,'small'=>@$form_values['P_LDS'],'large'=>@$form_values['P_HDS'],'type'=>'P_HDOLLAR'));
				break;
			}
    case 'partner_havechild':{
      $szName = $field->getName();
      $szMapLabel = ObjectiveEditFieldMap::getFieldMapKey($szName);
      $InputValues = $form_values[$field->getName()];
      
      if(isset($form_values['P_MSTATUS']))
        $mstatusVal = $form_values['P_MSTATUS'];
      else
        $mstatusVal = $loggedInObj->getJpartner()->getPARTNER_MSTATUS();
      
      return new sfValidatorAnd(array( new jsValidatorWhiteList(array('required'=>false,'FieldMapLabel'=>@$szMapLabel,'Value'=>@$InputValues,'FieldName'=>@$szName,'isHobby'=>0)),new jsValidatorHasChildren(array('mstatus'=>$mstatusVal))),array('required'=>false),array('required' => $defaultMsg));
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
		case 'partner_occupation_grouping':
		case 'partner_state':
		case 'partner_city_india':
			{
				$szName = $field->getName();
				$szMapLabel = ObjectiveEditFieldMap::getFieldMapKey($szName);
				$InputValues = $form_values[$field->getName()];
				
				return new jsValidatorWhiteList(array('required'=>false,'FieldMapLabel'=>@$szMapLabel,'Value'=>@$InputValues,'FieldName'=>@$szName,'isHobby'=>0));
				break;
			}
    case 'native_place':
			{
				$szName = $field->getName();
				$szMapLabel = ObjectiveEditFieldMap::getFieldMapKey($szName);
				if($form_values)
				{
					$InputValues = $form_values[$field->getName()];
				}
				return new jsValidatorNativePlace(array('required'=>false,'FieldMapLabel'=>@$szMapLabel,'Value'=>@$InputValues,'FieldName'=>@$szName));
				break;
			}
    case 'proof_type':
			{
				$szName = $field->getName();
				$szMapLabel = ObjectiveEditFieldMap::getFieldMapKey($szName);
				if($form_values)
				{
					$InputValues = $form_values[$field->getName()];
				}
				return new jsValidatorProofVal(array('required'=>false,'FieldMapLabel'=>@$szMapLabel,'Value'=>@$InputValues,'FieldName'=>@$szName));
				break;
			}
    case 'proof_val':
			{
                $szName = $field->getName();
				return new jsValidatorProof(array('required'=>false,'file'=>@$form_values[$szName],'name'=>@$form_values[$szName]['name'],'size'=>@$form_values[$szName]['size']));
				break;
			}
                        case 'mstatus_proof':
			{
                $szName = $field->getName();
				return new jsValidatorDivorcedProof(array('required'=>true,'file'=>@$form_values[$szName],'name'=>@$form_values[$szName]['name'],'size'=>@$form_values[$szName]['size'],'mstatus'=>@$form_values['MSTATUS']));
				break;
			}
		}
	}


	public static function getIncompleteValidator($field,$form_values="",$loggedInObj){
		$const_cl=$field->getConstraintClass();
		$field_map_name=ObjectiveEditFieldMap::getFieldMapKey($field->getName(),$page);
		//get all dropdown values from Fieldmaplib
		$defaultMsg = ErrorHelp::getDefaultMessage($field->getNAME());
		$errInvalid = ErrorHelp::INVALID_VALUE_ERR;
		
		//$hobby_arr=array('hobbies_language','hobbies_hobby','hobbies_interest','hobbies_music','hobbies_book','hobbies_sports','hobbies_cuisine','hobbies_dress');
		$not_to_check_arr=array('termsandconditions','source','record_id','phone_mob','phone_res','promo','email','password','dtofbirth','isd','gender');
		if($field_map_name && !in_array($field_map_name,$not_to_check_arr))
		{
			$choices=@array_keys(FieldMap::getFieldLabel($field_map_name,'',1));
			$choiceValidator = new sfValidatorChoice(array('choices'=>$choices,'required'=>false),array('invalid'=>$errInvalid));
		}
		
		switch($const_cl){
		case 'string':
			return new sfValidatorString(array('required'=>false));
			break;
		case 'dob':
			if(@$loggedInObj->getGENDER()=='M')
				$min=21;
			else
				$min=18;
                        $max = 70;
                        return new sfValidatorDate(array('required'=>true,'max'=>date('Y-m-d',strtotime( date('Y-m-d') . " -$min year" )),'min'=>date('Y-m-d',strtotime( date('Y-m-d') . " -$max year" ))),array('required' => $defaultMsg,'max'=>"You must be atleast $min years old to register to this site.",'min'=>"Maximum age criteria not met."));

			break;
		case 'mobile':
			if(isset($form_values['PHONE_RES']['landline']))
				return new jsValidatorMobile(array('min_length'=>10,'max_length'=>10,'required'=>false,'landline'=>$form_values['PHONE_RES']['landline']));
			else
				return new jsValidatorMobile(array('min_length'=>10,'max_length'=>10));				
			break;
		case 'landline':
			return new jsValidatorLandline(array('min_length'=>10,'max_length'=>10,'required'=>false));
			break;
		case 'alt_mobile':
				return new jsValidatorMobile(array('min_length'=>10,'max_length'=>10));				
			break;
		case 'yourinfo' :
			return new sfValidatorString(array('required'=>true,'min_length'=>100),array('required'=>$defaultMsg,'min_length'=>$defaultMsg));
			break;
		case 'mandatory':
			return new sfValidatorString(array(),array('required' => $defaultMsg));
			break;
		case 'mstatus':
			return new sfValidatorAnd(
                    array(new jsValidatorMStatus(),$choiceValidator),array(),array('required'=>$defaultMsg));
			break;
		case 'havechild':
			return new sfValidatorAnd(array($choiceValidator,new jsValidatorHasChildren(array('mstatus'=>$form_values['mstatus']))),array('required'=>false),array('required' => $defaultMsg));
			break;	
		case 'caste':
			return new jsValidatorCaste(array('religion'=>$form_values['RELIGION']),array('required' => $defaultMsg));
			break;
		case 'religion':
			return new jsValidatorReligion(array('caste'=>$form_values['CASTE'],'required'=>false),array('required' => $defaultMsg));
			break;
		case 'dropdown_req':
			return new sfValidatorChoice(array('choices'=>$choices),array('invalid'=>$errInvalid,'required' => $defaultMsg));
			break;
		case 'dropdown_not_req':
			return $choiceValidator;
			break;
		case 'integer':
			return new sfValidatorInteger(array('required'=>false));
			break;
		case 'city':
			$city=$form_values['CITY_RES'];
			$country=$form_values['COUNTRY_RES'];
			return new jsValidatorCountryCity(array('required'=>false,'city'=>$city,'country'=>$country,'fieldName'=>'city'));
			break;
		case 'country':
			$city=$form_values['CITY_RES'];
			$country=$form_values['COUNTRY_RES'];
			return new jsValidatorCountryCity(array('required'=>true,'city'=>$city,'country'=>$country,'fieldName'=>'country'));
			break;

		}
	}

}

