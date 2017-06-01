<?php
//for edit profile functions
include_once(sfConfig::get("sf_web_dir") . "/profile/functions_edit_profile.php");
//for phone_update_process
include_once(sfConfig::get("sf_web_dir")."/ivr/jsivrFunctions.php");
class FieldForm extends sfForm
{
	private $page_obj;
	public $formValues;
	public $fieldNameArr;
	public $lengthArr;
  private $arrLogFields = array();
	function __construct($defaults = array(),$loggedInObj,$incomplete="N",$options = array(), $CSRFSecret = null)
	{
		$this->fieldNameArr=$defaults;
		$this->loggedInObj=$loggedInObj;
		$this->incomplete=$incomplete;
		$this->lengthArr = EditProfileEnum::$lengthArr;
		parent::__construct($defaults, $options, $CSRFSecret);
	}
	public function configure()
	{
	  $this->disableLocalCSRFProtection();
	  if(is_array($this->fieldNameArr))
	  {
		  
		foreach($this->fieldNameArr as $fieldName=>$value)
		{
			$field=ProfileEditFields::getPageField($fieldName);
			if($field instanceof Field)
			{
				if($this->incomplete=="N")
					$validators[$fieldName]= ValidatorsFactory::getEditValidator($field,$this->fieldNameArr,$this->loggedInObj);
				else
					$validators[$fieldName]= ValidatorsFactory::getIncompleteValidator($field,$this->fieldNameArr,$this->loggedInObj);
			}
			else
			{
				$errorArr[error]=json_decode(json_encode(array("Invalid Field is submitted")), FALSE);
				$apiResponseHandlerObj=ApiResponseHandler::getInstance();
				$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
				$apiResponseHandlerObj->setResponseBody($errorArr);
				$apiResponseHandlerObj->generateResponse();
				ValidationHandler::getValidationHandler("","Invalid Field ".$fieldName." is submitted in Edit Profile of Api");
				die;
			}
			$this->fieldObjArr[$field->getNAME()]=$field;
		}

	  }
		$this->setValidators($validators);
	}
	public function getPageObject(){
	  return $this->page_obj;
	}
	
	/* update or insert data into table
		* @returns LoggedInProfile object if it is created otherwise return id of entry that was updated
	* */
	public function updateData(){
	  $bExecuteNative_PlaceUpdate = false;
	  $this->formValues=$this->getValues();
          $sendSMSToPhone = $this->loggedInObj->getPHONE_MOB();
          $prevManglikStatus = $this->loggedInObj->getMANGLIK();
          $fieldsEdited = array();
          $sendSMS = ProfileEnums::$sendInstantMessagesForFields;
	  foreach($this->formValues as $field_name=>$value){
		if(in_array($field_name,ProfileEnums::$saveBlankIfZeroForFields) && $value=="0")
		{
			$value = "";
		}
		if(array_key_exists($field_name,$sendSMS))
		{
                        if($field_name != "MANGLIK" || ($field_name == "MANGLIK" && ($prevManglikStatus !="S0" && $prevManglikStatus !="S")))
                                $fieldsEdited[] = $field_name;
		}
		 // if($value!==null){
		  $field_name=strtoupper($field_name);
		  $field_obj=$this->fieldObjArr[$field_name];
		  $table_name_arr=explode(":",$field_obj->getTableName());
		  $table_name=$table_name_arr[0];
		  $column_name_arr=explode(",",$table_name_arr[1]);
		  if(count($column_name_arr)==1)
		  {
			  //Normal case - only one column to be updated for one form field
			  $column_name=$column_name_arr[0];
			  switch($table_name){
				case "JPROFILE":
					if(array_key_exists($column_name,$this->lengthArr)  && $value)
					{
						$value=htmlentities($value);
						$jprofileFieldArr[$column_name]= substr($value,0,$this->lengthArr[$column_name]);
					}
					else{
					 	$jprofileFieldArr[$column_name]=$value;
					}
                                        if(in_array($field_name,EditProfileEnum::$editableCriticalArr)){
                                                $criticalInfoFieldArr[$column_name] = $value;
                                        }
					if($column_name == "ANCESTRAL_ORIGIN")
					{
						$bExecuteNative_PlaceUpdate = true;
					}
					 break;
                                case "CRITICAL_INFO_CHANGED_DOCS":
                                        $criticalInfoFieldArr[$column_name] = $value;
                                        break;
				case  "JPROFILE_EDUCATION":
					 $jprofileEducationArr[$column_name]=$value;
					 break;
				case "JPROFILE_CONTACT":
					  $jprofileContactArr[$column_name]=$value;
					  break;
				case "JHOBBY":
					if($column_name=="HOBBY")
						$hobbyArr[$column_name][$field_name]=$value;
					else
					  $hobbyArr[$column_name]=$value;
					  break;
				case "JP_CHRISTIAN":
						 $jpChristArr[$column_name]=$value;
					  break;
                                case "JP_MUSLIM":
                                         $jpMuslimArr[$column_name]=$value;
                                  break;
                                case "JP_SIKH":
                                         $jpSikhArr[$column_name]=$value;
                                  break;
                                case "JP_PARSI":
                                         $jpParsiArr[$column_name]=$value;
                                  break;
                                case "JP_JAIN":
                                         $jpJainArr[$column_name]=$value;
                                  break;
                                case "NAME_OF_USER":
                                         $incentiveUsernameArr[$column_name]=trim($value);
                                  break;
                                case "NATIVE_PLACE":
						$nativePlaceArr[$column_name] = $value;
						if($column_name=="NATIVE_COUNTRY"&&$value!='51')
						{
							$nativePlaceArr['NATIVE_CITY']='';
							$nativePlaceArr['NATIVE_STATE']='';
						}
					  break;
                                case "VERIFICATION_DOCUMENT":
						$verificationArr[$field_name] = $value;
					  break;
			  }
			  //Need to ask ?
			  //Handle religion related fields here as religion table names start with JP_
			  if(strpos($table_name,"JP_")!==false && $value){
				  //for maththab there are certain changes in values do following
				  if($column_name=='MATHTHAB'){

					  $maththab_exceptions=array('150'=>'8','254'=>'1','255'=>'2','256'=>'3','257'=>'4','258'=>'5','259'=>'6','260'=>'7');
					  $religionArr[$column_name]=$maththab_exceptions[$value]?$maththab_exceptions[$value]:$value;
				  }
				  $religionArr[$column_name]=$value;
				  $religion_table=$table_name;
			  }
		  }
		  else
		  {
			  //Abnormal case: Handle where a Field contains multiple columns as mobile contains isd and mobile number, landline isd,std and landline number
			  //In db values for mulptiple inpupts will be done as <column_name1>-<value_name1>,<column_name2>-<value_name2>
				  foreach($column_name_arr as $column){
					  $column_arr=explode("-",$column);
					  $column_name=$column_arr[0];
					  $value_name=$column_arr[1];
					  switch($table_name){
						 case "JPROFILE":
							 if(!$jprofileFieldArr[$column_name])
								$jprofileFieldArr[$column_name]=$value[$value_name];
							 break;
						  case "JPROFILE_CONTACT":
								$jprofileContactArr[$column_name]=$value[$value_name];
							break;
					  }
				  }
		  }
	  }
        if(count($criticalInfoFieldArr)){
                $infoChngObj = new newjs_CRITICAL_INFO_CHANGED();
                $editedFields = array_keys($criticalInfoFieldArr);
                $infoChngObj->insert($this->loggedInObj->getPROFILEID(),implode(",",$editedFields));
                unset($infoChngObj);
                if(isset($criticalInfoFieldArr["DOCUMENT_PATH"])){
                        $docObj = new CriticalInfoChangeDocUploadService();
                        $docObj->performDbInsert($this->loggedInObj->getPROFILEID(),$criticalInfoFieldArr["DOCUMENT_PATH"]);
                }
                if(isset($criticalInfoFieldArr["MSTATUS"]) && $criticalInfoFieldArr["MSTATUS"] == "D"){
                        unset($jprofileFieldArr["MSTATUS"]);
                }
        }
		//Native Place Update
    if(count($nativePlaceArr)){
			$nativePlaceArr[PROFILEID]=$this->loggedInObj->getPROFILEID();
						
      $nativePlaceObj = ProfileNativePlace::getInstance();
      $this->checkForChange($nativePlaceArr,'NativePlace');
      if ($nativePlaceObj->InsertRecord($nativePlaceArr) === 0) {
        unset($nativePlaceArr[PROFILEID]);
        $nativePlaceObj->UpdateRecord($this->loggedInObj->getPROFILEID(), $nativePlaceArr);
      }
                        

      //Log this update
      $nativePlaceArr['PROFILEID'] = $this->loggedInObj->getPROFILEID();
      unset($nativePlaceObj);
      $nativePlaceObj = new JProfile_NativePlace($this->loggedInObj);
      $nativePlaceObj->LogUpdate($nativePlaceArr);
	  }
          if(count($verificationArr)){
                $serviceObj = new ProfileDocumentVerificationByUserService();
                $serviceObj->performDbInsert($this->loggedInObj->getPROFILEID(), $verificationArr);
          }
//		if($bExecuteNative_PlaceUpdate)
//		{
//			$objNativePlace = new JProfile_NativePlace;
//			$bSet_NativePlaceBit = $objNativePlace->HandleLegacy($this->loggedInObj->getPROFILEID(),$jprofileFieldArr["ANCESTRAL_ORIGIN"]);
//		}

	  $flag_arr=array_keys(FieldMap::getFieldLabel('flagval','',1));

		  if(!$this->loggedInObj->getPROFILEID()){
			  echo "error Profileid not found in field form class";
			}
		  else{
			  $profileid=$this->loggedInObj->getPROFILEID();
		  	  //Update screening flag for corresponding array
			  if($screen_flag=$this->loggedInObj->getSCREENING()){
				
				if(count($jprofileFieldArr))
				{
					foreach($jprofileFieldArr as $field=>$value){
						if($value){
							if(in_array(strtolower($field),$flag_arr) && $field!="NAKSHATRA"){
								$screen_flag = Flag::removeFlag($field, $screen_flag);
							}
						}
						if($field=="DTOFBIRTH")
						{
                                                        $jprofileFieldArr["AGE"] = CommonFunction::getAge($jprofileFieldArr['DTOFBIRTH']);
                                                }
						if($field=="YOURINFO")
						{
							if($jprofileFieldArr[YOURINFO]!=$this->loggedInObj->getYOURINFO())
							{
								if(Flag::isFlagSet("YOURINFO",$this->loggedInObj->getSCREENING()))
								{
									// insert OR update about me in YOUR_INFO_OLD table
									$dbYourInfoOldObj= new YOUR_INFO_OLD();
									$dbYourInfoOldObj->updateAboutMeOld($this->loggedInObj->getPROFILEID(),$this->loggedInObj->getYOURINFO());
								}
							}
						}
					}
				}
				if(count($jprofileEducationArr))
				{
					foreach($jprofileEducationArr as $field=>$value){
						if($value){
							if(in_array(strtolower($field),$flag_arr)){
								$screen_flag = Flag::removeFlag($field, $screen_flag);
							}
						}
            else if(is_null($value)){
              $jprofileEducationArr[$field] = "";
            }
					}
				}
				if(count($jpChristArr))
				{
					foreach($jpChristArr as $field=>$value){
            if(in_array(strtolower($field),$flag_arr)){
              if($value){
                $screen_flag = Flag::removeFlag($field, $screen_flag);
              }
              else {
                $screen_flag = Flag::setFlag($field, $screen_flag);
              }
            }
					}
				}
				if(count($jpMuslimArr))
				{
					foreach($jpMuslimArr as $field=>$value){
						if($value){
							if(in_array(strtolower($field),$flag_arr)){
								$screen_flag = Flag::removeFlag($field, $screen_flag);
							}
						}
					}
				}
				if(count($jpSikhArr))
				{
					foreach($jpSikhArr as $field=>$value){
						if($value){
							if(in_array(strtolower($field),$flag_arr)){
								$screen_flag = Flag::removeFlag($field, $screen_flag);
							}
						}
					}
				}
				if(count($hobbyArr))
				{
					foreach($hobbyArr as $field=>$value){
						if($value){
							if(in_array(strtolower($field),$flag_arr)){
								$screen_flag = Flag::removeFlag($field, $screen_flag);
							}
						}
					}
				}
				if(count($jprofileContactArr))
				{
					foreach($jprofileContactArr as $field=>$value){
            if(in_array(strtolower($field),$flag_arr)){
              if($value){
                $screen_flag = Flag::removeFlag($field, $screen_flag);
              }
              else {
                $screen_flag = Flag::setFlag($field, $screen_flag);
              }
            }
					}
				}
			  }
				$screen_flag = JsCommon::setAutoScreenFlag($screen_flag,array_keys($this->formValues));
				$screen_flag = $this->autoScreenAutoSuggest($screen_flag,$this->formValues);
//				$screen_flag = $this->autoScreenMinorDiff($screen_flag,$this->formValues);
                                if($bSet_NativePlaceBit)
                                {
                                        $jprofileFieldArr["ANCESTRAL_ORIGIN"]="";//Set ANCESTRAL_ORIGIN to NULL
                                        $screen_flag = Flag::setFlag("ANCESTRAL_ORIGIN", $screen_flag);
                                }
                                if(count($incentiveUsernameArr) && array_key_exists("NAME",$incentiveUsernameArr))
                                {
                                     if($incentiveUsernameArr['NAME'])
                                     {
                                      $nameOfUserObj = new NameOfUser();
                                      $nameOfUserArr['NAME']=$nameOfUserObj->filterName($incentiveUsernameArr['NAME']);
                                      $isNameAutoScreened  = $nameOfUserObj->isNameAutoScreened($incentiveUsernameArr['NAME'],$this->loggedInObj->getGENDER());
                                        if($isNameAutoScreened)
                                        {
                                                $jprofileFieldArr['SCREENING'] = Flag::setFlag($FLAGID="name",$screen_flag);
                                        }
                                      }
                                      if(!$incentiveUsernameArr['NAME'] || !$isNameAutoScreened)
                                      {
                                                $screen_flag = Flag::removeFlag($FLAGID="name", $screen_flag);
                                      }
                                }
			  if(array_key_exists("ANCESTRAL_ORIGIN",$jprofileFieldArr))
			  {
				if($jprofileFieldArr["ANCESTRAL_ORIGIN"]=="")
					$screen_flag = Flag::setFlag("ANCESTRAL_ORIGIN", $screen_flag);
				else
					$screen_flag = Flag::removeFlag("ANCESTRAL_ORIGIN", $screen_flag);
			  }
			  if($screen_flag!=$this->loggedInObj->getSCREENING())
				$jprofileFieldArr['SCREENING']=$screen_flag;

			//Logging array for edit profiles
                        				$editLogArr=array();
                          
                          $hasJEducation = 1;
                          if(isset($jprofileFieldArr["EDU_LEVEL_NEW"])){
                                        $degreeGroup = FieldMap::getFieldLabel('degree_grouping_reg','',true);
                                        $jprofArrObj                = ProfileEducation::getInstance("newjs_masterRep");
                                        $profileDetailsArray = $jprofArrObj->getProfileEducation(array($this->loggedInObj->getPROFILEID()),'mailer');
                                        foreach($degreeGroup as $dg=>$dgArr){
                                                $degreeGroup[$dg] = $dgArr = explode(",",$dgArr);
                                                foreach($dgArr as $d=>$dgValue){
                                                     $degreeGroup[$dg][$d] = trim($dgValue);
                                                }
                                        }
                                        if(in_array($jprofileFieldArr["EDU_LEVEL_NEW"],$degreeGroup["ug"])){
                                               $jprofileEducationArr[UG_DEGREE] =  '';
                                               $jprofileEducationArr[PG_DEGREE] =  '';
                                               if(!$profileDetailsArray[0]['SCHOOL'])
                                                 $hasJEducation = 0;
                                        }elseif(in_array($jprofileFieldArr["EDU_LEVEL_NEW"],$degreeGroup["g"])){
                                                if(!$profileDetailsArray[0]['UG_DEGREE'] || $profileDetailsArray[0]['UG_DEGREE'] == '')
                                                        $jprofileEducationArr[UG_DEGREE] =  $jprofileFieldArr["EDU_LEVEL_NEW"];
                                                
                                                $jprofileEducationArr[PG_DEGREE] =  '';
                                        }elseif(in_array($jprofileFieldArr["EDU_LEVEL_NEW"],$degreeGroup["pg"])){
                                                if(!$profileDetailsArray[0]['PG_DEGREE'] || $profileDetailsArray[0]['PG_DEGREE'] == '')
                                                        $jprofileEducationArr[PG_DEGREE] =  $jprofileFieldArr["EDU_LEVEL_NEW"];
                                        }
                          }
			if(count($jprofileEducationArr)){
				if (isset($jprofileEducationArr[UG_DEGREE]) && 
          (is_null($jprofileEducationArr[UG_DEGREE]) || !strlen($jprofileEducationArr[UG_DEGREE]))
          ) {
          $jprofileEducationArr[UG_DEGREE] = NULL;
          $jprofileEducationArr[COLLEGE ] = NULL;
          $jprofileEducationArr[OTHER_UG_DEGREE] = NULL;
        }
				if (isset($jprofileEducationArr[PG_DEGREE]) &&
          (is_null($jprofileEducationArr[PG_DEGREE]) || !strlen($jprofileEducationArr[PG_DEGREE]))
          ) {
          $jprofileEducationArr[PG_DEGREE] = NULL;
          $jprofileEducationArr[PG_COLLEGE] = NULL;
          $jprofileEducationArr[OTHER_PG_DEGREE] = NULL; 
        }
        
				$this->checkForChange($jprofileEducationArr,"Education");
				$this->loggedInObj->editEducation($jprofileEducationArr);
				$jprofileFieldArr['HAVE_JEDUCATION']="Y";
                                
                                if($hasJEducation == 0)
                                        $jprofileFieldArr['HAVE_JEDUCATION']="N";
                                
				$editLogArr=array_merge($editLogArr,$jprofileEducationArr);
			}
			
			if(count($jprofileContactArr)){
				if(isset($jprofileFieldArr[PHONE_MOB])){
						if ($jprofileContactArr[ALT_MOBILE] == $jprofileFieldArr[PHONE_MOB] && $jprofileFieldArr[PHONE_MOB] !="" )
						{
							unset($jprofileContactArr[ALT_MOBILE]);
							unset($jprofileContactArr[ALT_MOBILE_ISD]);
						}
				}
				if(($jprofileContactArr[ALT_MOBILE] ==$this->loggedInObj->getExtendedContacts()->ALT_MOBILE) && ($jprofileContactArr[ALT_MOBILE_ISD] ==$this->loggedInObj->getExtendedContacts()->ALT_MOBILE_ISD))
				{
					unset($jprofileContactArr[ALT_MOBILE]);
					unset($jprofileContactArr[ALT_MOBILE_ISD]);
				}
				if(isset($jprofileContactArr[ALT_MOBILE]))
				{
					$jprofileContactArr['ALT_MOB_STATUS']='N';
					//phoneUpdateProcess($profileid, '', 'A', 'E'); ?????????
					$this->checkForChange($jprofileContactArr,"Contact");
					$this->loggedInObj->editCONTACT($jprofileContactArr);
					////////////////////////////
					$memObject=JsMemcache::getInstance();
					$memObject->delete('showConsentMsg_'.$this->loggedInObj->getPROFILEID());			
					$memObject->delete($this->loggedInObj->getPROFILEID().'_PHONE_VERIFIED');			
					////////////////////////////
					$jprofileFieldArr['HAVE_JCONTACT']="Y";
				}
        else if(count($jprofileContactArr)){
        	if(array_key_exists("ALT_EMAIL", $jprofileContactArr))
        	{
        		$jprofileContactArr["ALT_EMAIL_STATUS"] = "N";
        	}
          $this->checkForChange($jprofileContactArr,"Contact");
					$this->loggedInObj->editCONTACT($jprofileContactArr);
          $jprofileFieldArr['HAVE_JCONTACT']="Y";
        }
				$editLogArr=array_merge($editLogArr,$jprofileContactArr);
			}

			if(count($hobbyArr)){
				if(count($hobbyArr[HOBBY]))
				{
          $userSpecifiedHobbies = array();
          $userFinalSelection = array();
          
          $hobbies = $this->loggedInObj->getHobbies("onlyValues");
          $allHobbies = $hobbies['HOBBY'];
          
          if(strlen($allHobbies)){
            $userSpecifiedHobbies = explode(',',$allHobbies);
            $userFinalSelection = array_flip($userSpecifiedHobbies);
          }

          foreach($hobbyArr[HOBBY] as $key=>$val){
            $hobbiesMap = HobbyLib::getHobbyLabel(strtolower($key), "", 1);
            
            $userPrevious_SelectionArray = array_intersect(array_keys($hobbiesMap), $userSpecifiedHobbies);
            //Unset Previous Selection
            if(count($userPrevious_SelectionArray)){
              foreach($userPrevious_SelectionArray as $k=>$v){
                unset($userFinalSelection[$v]);
              }
            }
             //Store new selection
            if($val){
              $newSelection = explode(',',$val);
              $userFinalSelection = array_flip(array_merge(array_flip($userFinalSelection),$newSelection));
            }
          }

          $updatedHobbies = array_flip($userFinalSelection);
          sort($updatedHobbies,SORT_NUMERIC);
          
          if (count($updatedHobbies) > 0){ 
            //hobbies value to be update in JHOBBY table
            $hobbyArr[HOBBY] = implode(",", $updatedHobbies);
            //
            foreach ($updatedHobbies as $hob_value) $hob_str.= HobbyLib::getHobbyLabel('hobbies', $hob_value) . ",";
          }
          else
            $hobbyArr[HOBBY]='';

          //Remove comma from last
          if ($hob_str) $hob_str = substr($hob_str, 0, -1);
          //if(any jprofile field updated)
          if(count($jprofileFieldArr))
            $keywordArr[HOBBY] = $hob_str;
          else//if no jprofile field updated then add to the jprofile array
            $jprofileFieldArr[KEYWORDS] = $this->getUpdatedKeyword(array("HOBBY" => $hob_str));
				}
				if($this->checkForChange($hobbyArr,"Hobbies"))
					$this->loggedInObj->editHobby($hobbyArr);
				unset($hobbyArr[HOBBY]);
				$editLogArr=array_merge($editLogArr,$hobbyArr);
			}
			
			
			//NAME OF USER (INCENTIVE TABLE)

			if(count($incentiveUsernameArr) && $this->checkForChange($incentiveUsernameArr,'NameUser'))
			{
				$nameOfUserObj = new NameOfUser();
				$nameData = $nameOfUserObj->getNameData($profileid);
				if(!empty($nameData))
					$nameOfUserObj->updateName($profileid,$incentiveUsernameArr);
				else
					$nameOfUserObj->insertName($profileid,$incentiveUsernameArr['NAME'],$incentiveUsernameArr['DISPLAY']);
			}
			//incomplete users 
			$now = date("Y-m-d H:i:s");
			if($this->incomplete=="Y")
			{
                                if(isset($jprofileFieldArr[YOURINFO]))
                                        RegChannelTrack::insertPageChannel($profileid,PageTypeTrack::_PAGE2);
                                
				$jprofileFieldArr[INCOMPLETE]="N";
				//if($this->loggedInObj->getPREACTIVATED()=="U")
					$jprofileFieldArr[ACTIVATED]="N";//$this->loggedInObj->getPREACTIVATED();
				$jprofileFieldArr[ENTRY_DT] = $now;
				if($jprofileFieldArr[DTOFBIRTH])
				{
					$dob = new DateTime($jprofileFieldArr[DTOFBIRTH]);
					$currentDate = new DateTime();					
					$diff= $currentDate->diff($dob);
					$jprofileFieldArr[AGE] =$diff->y;
				}
			}
			
			
			if(count($jprofileFieldArr)){
				$jprofileFieldArr['MOD_DT']=$now;
				
				if($jprofileFieldArr[ISD]!=$this->loggedInObj->getISD())
				{
					$isdUpdated=1;
				}
				else
					$isdUpdated=0;
				//PHONE_MOB STATUS AND IVR CALL HANDLING
				if (isset($jprofileFieldArr[PHONE_MOB])){
					if(($jprofileFieldArr[PHONE_MOB] ==$this->loggedInObj->getPHONE_MOB()) && !$isdUpdated)
					{
							unset($jprofileFieldArr[PHONE_MOB]);
					}
					else
					{
						$phone_updated = 1;
						$mob_updated = 1;
						$jprofileFieldArr['MOB_STATUS']='N';
						////////////////////////////
					$memObject=JsMemcache::getInstance();
					$memObject->delete($this->loggedInObj->getPROFILEID().'_PHONE_VERIFIED');			
					$memObject->delete('showConsentMsg_'.$this->loggedInObj->getPROFILEID());			
					////////////////////////////
					
					}
				}
				//PHONE_RES STATUS AND PHONE WITH STD AND IVR CALL PARAMETERS
				if (isset($jprofileFieldArr[PHONE_RES]) && isset($jprofileFieldArr[STD])){
					if(!$jprofileFieldArr[PHONE_RES])
					{
						$jprofileFieldArr['PHONE_WITH_STD']="";
					}
					elseif(($jprofileFieldArr[PHONE_RES] ==$this->loggedInObj->getPHONE_RES()) && !$isdUpdated)
					{
						unset($jprofileFieldArr[PHONE_RES]);
						if($jprofileFieldArr[STD] ==$this->loggedInObj->getSTD())
							unset($jprofileFieldArr[STD]);
						else
							$jprofileFieldArr['PHONE_WITH_STD'] = $jprofileFieldArr[STD] . $jprofileFieldArr[PHONE_RES];
					}
					else
					{
						$phone_updated = 1;
						$phone_changed = 1;
						$jprofileFieldArr['LANDL_STATUS']='N';
						$jprofileFieldArr['PHONE_WITH_STD'] = $jprofileFieldArr[STD] . $jprofileFieldArr[PHONE_RES];
					}
				}
				
				//For nakshatra we save label in JPROFILE
				if(isset($jprofileFieldArr['NAKSHATRA']))
					$jprofileFieldArr['NAKSHATRA']=FieldMap::getFieldLabel('nakshatra',$jprofileFieldArr['NAKSHATRA']);
				//KEYWORDS 
				if (isset($jprofileFieldArr[OCCUPATION]))
					$keywordArr[OCCUPATION] = FieldMap::getFieldLabel('occupation', $jprofileFieldArr[OCCUPATION]);
				if (isset($jprofileFieldArr[CITY]))
					$keywordArr[CITY] = FieldMap::getFieldLabel('city', $jprofileFieldArr[CITY]);
				if (isset($hobbyArr[HOBBY]))
					$keywordArr[HOBBY] =  $hobbyArr[HOBBY];
				if (isset($jprofileFieldArr[CASTE]))
					$keywordArr[CASTE] = FieldMap::getFieldLabel('caste', $jprofileFieldArr[CASTE]);
				if (isset($jprofileFieldArr[HEIGHT]))
					$keywordArr[HEIGHT] = FieldMap::getFieldLabel('height', $jprofileFieldArr[HEIGHT]);
				if(count($keywordArr))
					$jprofileFieldArr[KEYWORDS] = $this->getUpdatedKeyword($keywordArr);
				$editLogArr=array_merge($editLogArr,$jprofileFieldArr);
				
			        //List of country for which we store city
			        $arrAllowedCountry = array(51,128);
			        // If country is changed then check do we store city or not
			        if($jprofileFieldArr[COUNTRY_RES] && !in_array(intval($jprofileFieldArr[COUNTRY_RES]),$arrAllowedCountry))
			        {
			            $jprofileFieldArr[CITY_RES] = '';
			        }
				//PinnCode change when City is changed
				if( ($jprofileFieldArr[CITY_RES] && $jprofileFieldArr[CITY_RES] != $this->loggedInObj->getCITY_RES() ) || 
			            ($jprofileFieldArr[COUNTRY_RES] &&  $jprofileFieldArr[COUNTRY_RES] != $this->loggedInObj->getCOUNTRY_RES() )
			          ){
					$jprofileFieldArr[PINCODE]="";
			        }
			 	
				//Email Change:
				if(isset($jprofileFieldArr["EMAIL"]))
				{
					//bot EMAIL ENTRY
					bot_email_entry($profileid, $jprofileFieldArr[EMAIL]);
					//Insert into autoexpiry table, to expire all autologin url coming before date
					$autoExObj=new ProfileAUTO_EXPIRY();
					$autoExObj->replace($profileid,'E',date("Y-m-d H:i:s"));
					//end
					insert_in_old_email($profileid, $this->loggedInObj->getEMAIL());
					
					$jprofileFieldArr['VERIFY_EMAIL']='N';
					$this->emailUID=(new NEWJS_EMAIL_CHANGE_LOG())->insertEmailChange($profileid,$jprofileFieldArr['EMAIL']);

				}
				$this->checkForChange($jprofileFieldArr);
				//update in jprofile
				$this->loggedInObj->edit($jprofileFieldArr);
				if($this->emailUID)
				(new emailVerification())->sendVerificationMail($profileid,$this->emailUID);
					
				
			}
			
			//jpartner update
			if($this->incomplete=="Y")
			{
				$this->setJpartnerAfterIncompleteLayer($jprofileFieldArr);
			}
			//Archive contact details function defined in functions_edit_profile.php
			if($jprofileFieldArr[PHONE_MOB] ||$jprofileFieldArr[PHONE_RES])
			{
				if($jprofileFieldArr[PHONE_MOB])
					phoneUpdateProcess($profileid, '', 'M', 'E');
				$smartyVar = archive_contacts($jprofileFieldArr, $profileid);
				ivr_call($profileid, $phone_changed, $phone_updated, $mob_updated,$jprofileFieldArr[PHONE_MOB], $jprofileFieldArr[PHONE_RES], $jprofileFieldArr[STD],"");
			}
		
			if(count($jpChristArr)|| count($jpMuslimArr)||count($jpSikhArr) ||count($jpParsiArr) || count($jpJainArr))
			{
				if(count($jpChristArr)){
					$dbChristian= new NEWJS_JP_CHRISTIAN();
					if($this->checkForChange($jpChristArr,"BeliefSystem"))
						$dbChristian->update($profileid,$jpChristArr);
				}
				if(count($jpMuslimArr)){
					$dbMuslim= new NEWJS_JP_MUSLIM();
					if($this->checkForChange($jpMuslimArr,"BeliefSystem"))
						$dbMuslim->update($profileid,$jpMuslimArr);
				}
				if(count($jpSikhArr)){
					$dbSikh= new NEWJS_JP_SIKH();
					if($this->checkForChange($jpSikhArr,"BeliefSystem"))
						$dbSikh->update($profileid,$jpSikhArr);
				}
				if(count($jpParsiArr))
				{
					$dbSikh= new NEWJS_JP_PARSI();
					if($this->checkForChange($jpParsiArr,"BeliefSystem"))
						$dbSikh->update($profileid,$jpParsiArr);
				}
				if(count($jpJainArr))
				{
					$dbSikh= new NEWJS_JP_JAIN();
					$dbSikh->update($profileid,$jpJainArr);
				}
			}
			
			
			
			
			if($jprofileFieldArr[SUBCASTE]){
				//mapping auto sug data to the user input.
				mapAutoSugSubcasteData($this->loggedInObj->getPROFILEID(), "SUBCASTE", $jprofileFieldArr['SUBCASTE']);
			}
			
                        if(!empty($fieldsEdited) && JsMemcache::getInstance()->get($this->loggedInObj->getPROFILEID()."_5MINS") === false){
                                $this->sendEditImportantFieldsSMS($this->loggedInObj->getPROFILEID(),$fieldsEdited,$sendSMSToPhone);
                        }
			//EDIT LOG
			$this->editLog($editLogArr);
		}

	  return 1;
	}
        /**
         * This function will add profile to critical information change message queue
         * @param type $profileId  Profile Id
         * @param type $fieldsEdited Edited fields
         * @param type $sendSMSToPhone (phone number before change in case of update in phone number) 
         */
	public function sendEditImportantFieldsSMS($profileId,$fieldsEdited,$sendSMSToPhone){
                $producerObj = new Producer();
                if($producerObj->getRabbitMQServerConnected())
                {
                        $senderSmsData=array('process'=>'SMS','data'=>array('type'=>'CRITICAL_INFORMATION_CHANGE','body'=>array('receiverid'=>$profileId, "PHONE"=>$sendSMSToPhone,"editedFields"=>$fieldsEdited) ), 'redeliveryCount'=>0 );
                        $producerObj->sendMessage($senderSmsData);
                }
        }
	public function editLog($logArr) {
		if(count($logArr))
		{
			$logArr['PROFILEID'] = $this->loggedInObj->getPROFILEID();
			$logArr['MOD_DT'] = date("Y-m-d H:i:s");
			log_edit($logArr);
			if(array_key_exists("INCOMPLETE",$logArr))
				insert_in_duplication_check_fields($logArr['PROFILEID'],'new','134217727');
			else
				$this->update_duplication_fields($logArr);
		}
    
    if (count($this->arrLogFields) && JsConstants::$useMongoDb) {
      $this->arrLogFields['PROFILEID'] = $this->loggedInObj->getPROFILEID();
      $logObject = new PROFILE_EDIT_LOG;
      $logObject->insertOne($this->arrLogFields);
    }
	}
	
	public function getUpdatedKeyword($keysToChangeArray) {
		$keywords[AGE] = $this->loggedInObj->getAGE();
		$keywords[GENDER] = $this->loggedInObj->getDecoratedGender();
		$keywords[HEIGHT] = $this->loggedInObj->getDecoratedHeight();
		$keywords[CASTE] = $this->loggedInObj->getDecoratedCaste();
		$keywords[OCCUPATION] = $this->loggedInObj->getDecoratedOccupation();
		$keywords[CITY] = $this->loggedInObj->getDecoratedCity();
		if ($keysToChangeArray[HOBBY]) $hobby = "|" . $keysToChangeArray[HOBBY];
		else $hobby = strstr($this->loggedInObj->getKEYWORDS(), "|");
		foreach ($keysToChangeArray as $key => $value) {
			if (in_array($key,EditProfileEnum::$keywordFieldArray)) $keywords[$key] = $value;
			else throw Exception("Keyword field $key is not in Key field list. Please use only 'AGE','GENDER','HEIGHT','CASTE','OCCUPATION','CITY','HOBBY' or add an entry for new keyword field here");
		}
		unset($keywords[HOBBY]);
		$keyword = addslashes(stripslashes(implode(",", $keywords) . $hobby));
		return $keyword;
	}
	
	function update_duplication_fields($logArr){
			$this->changed_fields=array_unique($logArr);
		$duplication_fields=EditProfileEnum::$duplication_fields;
			$profileid=$this->loggedInObj->getPROFILEID();
		$dup_fields=array_intersect($duplication_fields,$this->changed_fields);
		
		if(count($dup_fields)){
			$res=get_from_duplication_check_fields($profileid);
			if($res[TYPE]=='NEW')
				return;
			if($res)
				$val=$res[FIELDS_TO_BE_CHECKED];
			else
				$val=0;
			foreach($dup_fields as $field){
				$val=Flag::setFlag($field,$val,'duplicationFieldsVal');
			}
			insert_in_duplication_check_fields($profileid,'edit',$val);
		}
	}

	function editableFieldsValidation($fieldArr,$incomplete="N")
	{
		if($incomplete=="N")
		{
			$editableArr=EditProfileEnum::$editableArr;
			$editableCriticalArr=EditProfileEnum::$editableCriticalArr;
			foreach($fieldArr as $key=>$val)
			{
				if(!in_array($key,$editableArr) && !in_array($key,$editableCriticalArr))
					return $key;
			}
		}
		else
		{
			$editableArr=EditProfileEnum::$editableIncompleteArr;
			foreach($fieldArr as $key=>$val)
			{
				if(!in_array($key,$editableArr))
					return $key;
			}
		}
	return false;
	}
	
	
	function incompleteFieldsValidation($fieldArr,$incompleteArr)
	{
		if(is_array($fieldArr)&& is_array($incompleteArr))
		{
			foreach($incompleteArr as $key=>$val)
			{
                                if($val["key"] == "STATE_RES"){
                                    unset($incompleteArr[$key]);
                                    continue;
                                }
				if($val[incomplete]=="Y")
				{
					$incompKeyArr[]=$val[key];
				}
			}
			foreach($fieldArr as $k=>$v)
			{
				$incompFieldKey[]=$k;
			}
			$arrDiff1=array_diff($incompKeyArr, $incompFieldKey);
			$arrDiff2=array_diff($incompFieldKey, $incompKeyArr);
			if(!count($arrDiff1) && !count($arrDiff2))
				return  "";
			else
				return $arrDiff1+$arrDiff2;
		}
		else
			return array();
	}
	
	
	/* It sets jpartner default values
	 * */
	private function setJpartnerAfterIncompleteLayer($fieldArr){
	//DPP Auto Suggestor implemenation :
		$dppObj=new DppAutoSuggest($this->loggedInObj);
		$jpartnerObj=$dppObj->getJpartnerObj();
		
		foreach (DppAutoSuggestEnum::$FIELD_ID_ARRAY as $key=>$val)
		{
			if(array_key_exists($val,$fieldArr))
			{
				$dppArr[]=$val;
			}
		}
		$gender=$this->loggedInObj->getGENDER();		

		if($gender=='M')
			 $jpartnerObj->setGENDER('F');
		else
			 $jpartnerObj->setGENDER('M');
		$jpartnerObj->setDPP('R');
		
		$dppObj->insertJpartnerDPP($dppArr);
	}
	/**
	 * This function compares values of profile with values in paramArray
	 * and return true if there is change else false
	 */
	public function checkForChange($paramArray,$table="") {
		unset($paramArray[MOD_DT]);
		unset($paramArray[LAST_LOGIN_DT]);
		$flag = false;
    $oriValueArr = array();
    
		if($table=="Education")
		{
			$eduArr=$this->loggedInObj->getEducationDetail("values");
			foreach ($paramArray as $key => $value) {
					$orig_value = $eduArr[$key];
				if (trim($value) !== $orig_value && (trim($value) !== "" || $orig_value !== "0")){
						$flag = true;
					$changed_fields[]=$key;
          $oriValueArr[$key] = $orig_value;
				}
			}
		}
		elseif($table=="Contact")
		{
			$contactArr=$this->loggedInObj->getExtendedContacts("values");
			foreach ($paramArray as $key => $value) {
					$orig_value = $contactArr[$key];
				if (trim($value) !== $orig_value && (trim($value) !== "" || $orig_value !== "0")){
						$flag = true;
					$changed_fields[]=$key;
          $oriValueArr[$key] = $orig_value;
				}
			}
		}
		elseif($table=="Hobbies")
		{
			$hobbyArr=$this->loggedInObj->getHobbies("values");
			foreach ($paramArray as $key => $value) {
					$orig_value = $hobbyArr[$key];
				if (trim($value) !== $orig_value && (trim($value) !== "" || $orig_value !== "0")){
						$flag = true;
					$changed_fields[]=$key;
          $oriValueArr[$key] = $orig_value;
				}
			}
		}
		elseif($table=="BeliefSystem")
		{
			$relinfo = (array)$this->loggedInObj->getReligionInfo();
			$relinfo_values = (array)$this->loggedInObj->getReligionInfo(1);
			foreach ($paramArray as $key => $value) {
			$orig_value = $relinfo_values[$key];
			if($key=="DIOCESE")
				$orig_value = $relinfo[$key];
			if (trim($value) !== $orig_value && (trim($value) !== "" || $orig_value !== "0")){
						$flag = true;
            $oriValueArr[$key] = $orig_value;
            $changed_fields[] = $key; 
				}
			}
		}
    elseif($table=="NativePlace") 
    {
      $nativePlaceObj = new JProfile_NativePlace($this->loggedInObj);
      $nativeArray = $nativePlaceObj->getInfo();
      foreach ($paramArray as $key => $value) {
        $orig_value = $nativeArray[$key];
				if (trim($value) !== $orig_value && (trim($value) !== "" || $orig_value !== "0")){
						$flag = true;
					$changed_fields[]=$key;
          $oriValueArr[$key] = $orig_value;
				}
			}
      unset($nativePlaceObj);
    }
    elseif($table == 'NameUser')
    {
        $nameObj= new NameOfUser;
        $nameData = $nameObj->getNameData($this->loggedInObj->getPROFILEID());
        $arrResult = array();
        if(!empty($nameData))
                $arrResult = $nameData[$this->loggedInObj->getPROFILEID()];
        
       //if($orgiValue['NAME'])
       foreach ($paramArray as $key => $value) {
        $orig_value = $arrResult[$key];
				if (trim($value) !== $orig_value && (trim($value) !== "" || $orig_value !== "0")){
          $flag = true;
					$changed_fields[]=$key;
          $oriValueArr[$key] = $orig_value;
				}
			}
      unset($nameObj);
    }
		else
		{
			foreach ($paramArray as $key => $value) {
				$getMethod = "get" . $key;
				$orig_value = $this->loggedInObj->$getMethod();
				if (trim($value) !== $orig_value && (trim($value) !== "" || $orig_value !== "0")){
						$flag = true;
					$changed_fields[]=$key;
          $oriValueArr[$key] = $orig_value;
				}
			}
		}
		$searchSetObj=new VerificationSealLib($this->loggedInObj);
		$searchSetObj->resetVerificationSeal($changed_fields);
    
    //Add into log array
    foreach($changed_fields as $key=>$value) {
      $this->arrLogFields[$value] = $oriValueArr[$value];
    }
    
		return $flag;
	}


        public function autoScreenAutoSuggest($screenVal,$editArr)
        {
                $autoScreenAutoSuggestArr = array("SCHOOL"=>"school","COLLEGE"=>"collg","COMPANY_NAME"=>"org","PG_COLLEGE"=>"PGcollg","GOTHRA"=>"gothra","DIOCESE"=>"dioceses","SUBCASTE"=>"subcaste");
		$fields = array_keys($autoScreenAutoSuggestArr);
                foreach($editArr as $k=>$v)
                {
			unset($data);
                        if(in_array($k,$fields))
                        {
                                $obj = ImportAutoSugFactory::getAutoSugAgent($autoScreenAutoSuggestArr[$k]);
				if($k!="SUBCASTE")
					$data = $obj->match($v);
				else
				{
					if(in_array("CASTE",array_keys($editArr)))
						$caste = $editArr['CASTE'];
					else
						$caste  = $this->loggedInObj->getCASTE();
					$data = $obj->match($v,$caste);
				}
				if(is_array($data))
				{
					$screenVal = Flag::setFlag(strtolower($k),$screenVal);
				}
                        }
                }
		return $screenVal;
        }
	public function autoScreenMinorDiff($screenVal,$editArr)
	{
		$autoScreenMinorDiffArr = array("YOURINFO","FAMILYINFO","JOB_INFO","EDUCATION");
		foreach($editArr as $k=>$v)
		{
			if(in_array($k,$autoScreenMinorDiffArr))
			{
				if(Flag::isFlagSet("YOURINFO",$this->loggedInObj->getSCREENING()))
				{
					$originalVal = call_user_func(array($this->loggedInObj,"get".$k));
					$ignorable = $this->checkIgnorableDiff($v,$originalVal);
					if($ignorable===true)
					{
						$screenVal = Flag::setFlag(strtolower($k),$screenVal);
					}
				}
			}
		}
		return $screenVal;
	}
	public function checkIgnorableDiff($s1,$s2)
	{
	        $s1 = preg_replace('/[ .]+/', '', trim($s1));
	        $s2 = preg_replace('/[ .]+/', '', trim($s2));
		if (strcasecmp($s1, $s2) == 0)
		{
			return true;
		}
		return false;
	}
}
?>
