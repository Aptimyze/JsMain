<?php
class EditIncompleteLayer extends EditProfileComponent {
	function submit() {
		$UPDATE = 1;
		$this->request = $this->action->getRequest();
		$now = date("Y-m-d H:i:s");
		$Education_Level = $this->request->getParameter('Education_Level');
		if ($Education_Level) $edu_level = get_old_value($Education_Level, "EDUCATION_LEVEL_NEW");
		$paramArr = array("EDU_LEVEL_NEW" => $Education_Level, "EDU_LEVEL" => $edu_level, "OCCUPATION" => $this->request->getParameter('Occupation'), "INCOME" => $this->request->getParameter('Income'), "MOD_DT" => $now, );
		$paramArr[YOURINFO] = stripslashes(trim($this->request->getParameter('Information')));
		
		// require in validation also
		$this->gender=$this->loginProfile->getGENDER();
		$this->religion=$this->loginProfile->getRELIGION();
		$this->caste=$this->loginProfile->getCASTE();
		$this->relation=$this->loginProfile->getRELATION();
		$this->dtoBirth=$this->loginProfile->getDTOFBIRTH();
		$this->dateFlag = DateTime::createFromFormat("Y-m-d", $this->dtoBirth);
		$this->height=$this->loginProfile->getHEIGHT();
		$this->COUNTRY_RES=$this->loginProfile->getCOUNTRY_RES();
		$this->CITY_RES=$this->loginProfile->getCITY_RES();
		$this->mTongue=$this->loginProfile->getMTONGUE();
		$this->mStatus = $this->loginProfile->getMSTATUS();
		$this->haveChild = $this->loginProfile->getHAVECHILD();
		$this->PHONE_RES=$this->loginProfile->getPHONE_RES();
		$this->PHONE_MOB= $this->loginProfile->getPHONE_MOB();
		//*******new fields of page 1 added: **********
		
		$paramArr[GENDER]=$this->gender;
		//Gender-Relation fields
		if(!$this->relation)
		{	
			//$paramArr[GENDER]=$this->request->getParameter('genderValue');
			$paramArr[RELATION]=$this->request->getParameter('Realtionship');
			/*if($paramArr[GENDER]=="" || $paramArr[RELATION]=="")
			{
				$this->error = "mand_gender";
				return;			
			}
			else
			{
				if(!$this->checkForErrorPage1("GENDER",$paramArr))
					return;
			}
			if($paramArr[RELATION] =="2" ||$paramArr[RELATION] =="2D" || $paramArr[RELATION] =="6" || $paramArr[RELATION] =="6D")
			{
				if($paramArr[RELATION] =="2" ||$paramArr[RELATION] =="6")
					$paramArr[GENDER]="M";
				else
					$paramArr[GENDER]="F";
			}
			$profileFieldArr[]="GENDER";*/
		}
		
		//date of Birth fields
		if(!$this->dateFlag || $this->dtoBirth=="0000-00-00")
		{
			$dateArr[year]=$this->request->getParameter('year');
			$dateArr[month]=$this->request->getParameter('month');
			$dateArr[day]=$this->request->getParameter('day');
			if($dateArr[year]=="" || $dateArr[month]=="" || $dateArr[day]=="")
			{
				$this->error = "mand_dto";
				return;	
			}
			$dobDate = new DateTime($dateArr[year].'/'.$dateArr[month].'/'.$dateArr[day]);
			$paramArr[DTOFBIRTH]=date_format($dobDate, 'Y-m-d');
			$age=$this->checkForErrorPage1("DTOFBIRTH",$paramArr);
			if(!$age)
				return;	
			else
				$paramArr[AGE]=$age;
			$profileFieldArr[]="AGE";
		}
	
		//Height field
		if(!$this->height)
		{
			$paramArr[Height]=$this->request->getParameter('Height');
			if($paramArr[Height]=="")
			{
				$this->error = "mand_height";
				return;	
			}
			$profileFieldArr[]="HEIGHT";
			//if ($paramArr[HEIGHT] != $this->loginProfile->getHEIGHT())
				//$paramArr[KEYWORDS] = $this->getUpdatedKeyword(array("HEIGHT" => FieldMap::getFieldLabel('height', $paramArr[HEIGHT])));
		}
	
		//Country-City fields
		if((!$this->COUNTRY_RES) || ($this->COUNTRY_RES==51 && (!$this->CITY_RES)))
		{
			$country_residence_val = explode("|X|", $this->request->getParameter('country_residence'));
			$country_residence_val = explode("|}|", $country_residence_val[0]);
			$country_residence = $country_residence_val[1];
			$city_residence = $this->request->getParameter('city_residence');
			if ($country_residence != 51) $city_residence = "";
			if ($city_residence != '0') {
				$city_residence_val = explode("|{|", $city_residence);
				$city_residence = $city_residence_val[1];
			}
			$paramArr[CITY_RES] = $city_residence;
			$paramArr[COUNTRY_RES] = $country_residence;
			if($paramArr[COUNTRY_RES]=="")
			{
				$this->error = "mand_country";
				return;	
			}
			if(!$this->checkForErrorPage1("COUNTRYCITY",$paramArr))
				return;
			$profileFieldArr[]="COUNTRY_RES";
			//$paramArr[KEYWORDS] = $this->getUpdatedKeyword(array('CITY' => FieldMap::getFieldLabel("city", $paramArr[CITY_RES])));
			
		}
		//Phone Mobile and Residence Fields
		
		if(!$this->PHONE_RES && !$this->PHONE_MOB)
		{
			$paramArr['PHONE_MOB'] = trim($this->request->getParameter('Mobile'));
			$paramArr['PHONE_RES'] = trim($this->request->getParameter('Phone'));
			$paramArr['STD'] = trim($this->request->getParameter('State_Code'));
			$paramArr['ISD'] = trim($this->request->getParameter('ISD'));
			if($paramArr['ISD']=='0')
				$paramArr['ISD']='+91';
			else
			$paramArr['ISD']=ltrim($paramArr['ISD'],'0');
			if(strpos($paramArr['ISD'],'+')===false)
				$paramArr['ISD']="+".$paramArr['ISD'];
			$arr=explode('+',$paramArr['ISD']);
			$paramArr['ISD']=$arr[1];
			$paramArr['SHOWPHONE_RES'] = $this->request->getParameter('Showphone');
			$paramArr['SHOWPHONE_MOB'] = $this->request->getParameter('Showmobile');
			
			if($paramArr['PHONE_MOB']=="" && $paramArr['PHONE_RES'] =="")
			{
				$this->error = "mand_fields";
				return;
			}
			else if(!$this->checkForErrorPage1("PHONE_NUMBER",$paramArr))
				return;
			else
			{
				$paramArr['MOB_STATUS']='N';
				$paramArr['LANDL_STATUS']='N';
			}
			
			
			
		}
		// Mtongue field
		
		if(!$this->mTongue)
		{
			$paramArr[MTONGUE]=$this->request->getParameter('mTongue');
			if($paramArr[MTONGUE]=="")
			{
				$this->error = "mand_mTongue";
				return;	
			}
			$profileFieldArr[]="MTONGUE";
			if(!$this->checkForErrorPage1("MTONGUE",$paramArr))
				return;	
		}
	
		//Religion and Caste fields
		if(!$this->religion || !$this->caste)
		{
			$Religion_temp = explode('|X|', $this->request->getParameter('Religion'));
			$Religion = $Religion_temp[0];
			$religionInfo = $this->loginProfile->getReligionInfo(1);
			switch ($Religion) {
				case Religion::HINDU:
					$Caste = $this->request->getParameter('Caste_hindu');
				break;
				case Religion::MUSLIM:
					$Caste = $this->request->getParameter('Caste_muslim');
				break;
				case Religion::JAIN:
					$Caste = $this->request->getParameter('Caste_jain');
				break;
				case Religion::CHRISTIAN:
					$Caste = $this->request->getParameter('Caste_christian');
				break;
				case Religion::SIKH:
					$Caste = $this->request->getParameter('Caste_sikh');
				break;
				default:
					if ($Religion == Religion::JEWISH)
						$Caste = 148;
					elseif ($Religion == Religion::BUDDHIST)
						$Caste = 1;
					elseif($Religion == Religion::BAHAI)
						$Caste=496;
					elseif($Religion == Religion::OTHER)
						$Caste=162;
					elseif($Religion == Religion::PARSI)
						$Caste=153;
			}
			
			$paramArr[RELIGION]=$Religion;
			$paramArr[CASTE]=$Caste;
			if($paramArr[RELIGION]=="" ||$paramArr[CASTE]=="" )
			{
				$this->error = "mand_religion_caste";
				return;	
			}
			if(!$this->checkForErrorPage1("RELIGION",$paramArr))
				return;
			$profileFieldArr[]="RELIGION";
			$profileFieldArr[]="CASTE";
		}
		//Mstatus and have children fields
		if((!$this->mStatus) || ($this->mStatus!="N" && (!$this->haveChild)))
		{
			$paramArr[MSTATUS]=$this->request->getParameter('mStatus_residence');
			$paramArr[HAVECHILD]=$this->request->getParameter('haveChildern_residence');
			if($paramArr[MSTATUS]=="")
			{
				$this->error = "mand_mstatus";
				return;	
			}
			if(!$this->checkForErrorPage1("MSTATUS",$paramArr))
			{
				return;	
			}
			$profileFieldArr[]="MSTATUS";
		}
		
		$errorCheck=$this->checkForError($paramArr);
		$toChange = $this->checkForChange($paramArr);

	
		//Make changes only if at least a field was changed and info is correct
		if ($toChange && $errorCheck) {
			//update keywords if occupation changed
				if($paramArr[YOURINFO]!=$this->loginProfile->getYOURINFO())
				{
                                    RegChannelTrack::insertPageChannel($this->loginProfile->getPROFILEID(),PageTypeTrack::_PAGE2);
					if(Flag::isFlagSet("YOURINFO",$this->loginProfile->getSCREENING()))
					{
						// insert OR update about me in YOUR_INFO_OLD table
						$dbYourInfoOldObj= new YOUR_INFO_OLD();
						$dbYourInfoOldObj->updateAboutMeOld($this->loginProfile->getPROFILEID(),$this->loginProfile->getYOURINFO());
					}
				}
				if($paramArr['PHONE_MOB'] ||$paramArr['PHONE_RES'])
				{
					if($paramArr['PHONE_MOB'])
						$screeningFlagArr[phone_mob]=$paramArr['PHONE_MOB'];
					if($paramArr['PHONE_RES'])
						$screeningFlagArr[phone_res]=$paramArr['PHONE_RES'];
				}
				$screeningFlagArr[YOURINFO]=$paramArr[YOURINFO];
				$curflag = $this->getScreeningFlag($screeningFlagArr);
				
				$mark = $this->request->getParameter('mark');
				//To check for incompleteness of profile.
				$incompleteFields = IncompleteLib::incompleteFieldsOfProfile($this->loginProfile);
				$fieldsTocheckForIncomplete = array("YOURINFO","EDU_LEVEL_NEW", "OCCUPATION", "INCOME");
			/*	
			 * Need to check can we remove it
			 * if (!$mark) {
					if (count(array_intersect($fieldsTocheckForIncomplete, $incompleteFields))) {
						$mark = 2;
					}
				}
				if($mark){
					if (!count(array_diff($incompleteFields,$fieldsTocheckForIncomplete)) && $paramArr[EDU_LEVEL_NEW] && $paramArr[OCCUPATION] && $paramArr[INCOME] && strlen($paramArr[YOURINFO])>=100) 
					{
						$paramArr[INCOMPLETE] = 'N';
						$paramArr[ENTRY_DT] = $now;
					}
					$this->after_login = 0;
				}else $this->after_login = $mark;*/
				$paramArr[INCOMPLETE] = 'N';
				//if($this->loginProfile->getPREACTIVATED())
					//$paramArr[ACTIVATED]="U";//$this->loginProfile->getPREACTIVATED();
					$paramArr[ACTIVATED]="N";//$this->loginProfile->getPREACTIVATED();
				$paramArr[ENTRY_DT] = $now;
				//$paramArr[SCREENING] = $curflag;
				$paramArr[SCREENING] = 0;
				if(!FTOStateHandler::profileExistsInFTOStateLog($this->loginProfile->getPROFILEID())){
					//Fto state change after completion of page2
					$fto_action = FTOStateUpdateReason::REGISTER;
					$this->loginProfile->getPROFILE_STATE()->updateFTOState($this->loginProfile, $fto_action);
				}
				if($this->request->getParameter('IncompleteMail'))
					$paramArr[SEC_SOURCE]='I';
				//Channel tracking for Incomplete SMS to track incomplete to complete )
				if($this->request->getParameter('channel')=='INCOM_SMS')
				{	
					$MIS_INCOMPLETE_SMS_OBJ=new MIS_INCOMPLETE_SMS();
					$MIS_INCOMPLETE_SMS_OBJ->insertCompletion($this->loginProfile->getPROFILEID());
				}
			if ($paramArr[OCCUPATION] != $this->loginProfile->getOCCUPATION()){
				$profileFieldArr[]="OCCUPATION";
				$keywordArr[OCCUPATION]=FieldMap::getFieldLabel('occupation', $paramArr[OCCUPATION]);
			}
			if($paramArr[AGE])
				$keywordArr[AGE]=$paramArr[AGE];
			/*if($paramArr[GENDER])
				$keywordArr[GENDER]= FieldMap::getFieldLabel('gender', $paramArr[GENDER]);*/
			if($paramArr[HEIGHT])
				$keywordArr[HEIGHT]= FieldMap::getFieldLabel('height', $paramArr[HEIGHT]);
			if($paramArr[CASTE])
				$keywordArr[CASTE]= FieldMap::getFieldLabel('caste', $paramArr[CASTE]);
			if($paramArr[CITY_RES])
				$keywordArr[CITY]= FieldMap::getFieldLabel('city', $paramArr[CITY_RES]);
			if(is_array($keywordArr))
				$paramArr[KEYWORDS] = $this->getUpdatedKeyword($keywordArr);
			$this->updateAndLog($paramArr,array(),1);
			
			//ivr call //contact archive update
			if($paramArr['PHONE_MOB'] ||$paramArr['PHONE_RES'])
			{
				$this->contactArchiveUpdate();
			}
			//dpp auto suggestor
			if(count($profileFieldArr))
				$this->setJpartnerAfterIncompleteLayer($profileFieldArr);
			
				
		if (!headers_sent()) update_cookie($paramArr[INCOME], "INCOME");
		}
	}
	public function display() {
		$request = $this->action->getRequest();
		$this->action->GENDER = $this->loginProfile->getGENDER();
		$this->action->DTOFB = JsCommon::formatDate($this->loginProfile->getDTOFBIRTH());
		$this->action->RELATION = $this->loginProfile->getRELATION();
		$this->action->edu_level_new = $this->loginProfile->getEDU_LEVEL_NEW();
		$this->action->occ_val = $this->loginProfile->getOCCUPATION();
		$this->action->income_val = $this->loginProfile->getINCOME();
		$this->action->occupation = DropDownCreator::createDD("occupation", $this->action->occ_val);
		$this->action->INCOME = DropDownCreator::createDD("income_level", $this->action->income_val);
		$this->action->education_level = DropDownCreator::createDD("education",$this->action->edu_level_new);
		$yourinfo = $this->loginProfile->getYOURINFO();
		$this->action->YOURINFO = $yourinfo;
		$this->action->INFOLEN = strlen($yourinfo);
		if ($for_about_us = $request->getParameter('for_about_us')) {
			if ($relation == 2 && $this->loginProfile->getGENDER == 'M') $relation = 7;
			$this->action->for_about_value = $relation;
			$this->action->for_about_us = $for_about_us;
		}
		
		
		//----Conditons and variables for incomplete layer ----
		
		//---GENDER and CREATE PROFILE SECTION----

		if(!$this->action->RELATION)
		{
			$this->action->relationFlag=1;
			//$this->action->GENDER=="";
			$this->action->RELATION=="";
			//$this->action->relationDD= DropDownCreator::createDD("relationship");
		}
		$this->action->genderFlag=0;
		//DOB Section
		$this->action->dOB=$this->loginProfile->getDTOFBIRTH();
		$date = DateTime::createFromFormat("Y-m-d", $this->action->dOB);
		if(!$date || $this->action->dOB=="0000-00-00")
		{
			$this->action->dOBFlag=1;
		}
		//--Height
		$this->action->HEIGHT=$this->loginProfile->getHeight();
		if(!$this->action->HEIGHT)
		{
			$this->action->heightFlag=1;
			$this->action->heightDD= DropDownCreator::createDD("Height");
		}
		//---Country And City SECTION----
		$this->action->COUNTRY_RES=$this->loginProfile->getCOUNTRY_RES();
		$this->action->CITY_RES=$this->loginProfile->getCITY_RES();
		
		if((!$this->action->COUNTRY_RES) ||($this->action->COUNTRY_RES==51 &&(!$this->action->CITY_RES)))
		{
			$this->action->countryCityFlag=1;
			$this->action->COUNTRY_RES="";
			$this->action->CITY_RES="";
			$this->action->COUNTRY_RES = DropDownCreator::createDD("cityAndCountry", $this->action->COUNTRY_RES);
		}		
		//---Mstatus and Have Child Section---		
		$this->action->MSTATUS = $this->loginProfile->getMSTATUS();
		$this->action->HAVECHILD = $this->loginProfile->getHAVECHILD();
		if((!$this->action->MSTATUS) || ($this->action->MSTATUS!="N" && (!$this->action->HAVECHILD)))
		{
			$this->action->mStatusFlag=1;
			$this->action->MSTATUS = "";
			$this->action->HAVECHILD="";
			$this->action->mstatusDD = DropDownCreator::createDD("mstatus");
			$this->action->havechildDD = DropDownCreator::createDD("children_ascii_array");
		}
		
		//PHONE AND LANDLINE NUMBER 
		$this->action->PHONE_RES = $this->loginProfile->getPHONE_RES();
		$this->action->PHONE_MOB = $this->loginProfile->getPHONE_MOB();
		if(!$this->action->PHONE_RES && !$this->action->PHONE_MOB)
		{
			$this->action->phoneFLag=1;
			$this->action->country_code_mob = $this->action->country_code="+91";
			
		}
		//MTONGUE
		$this->action->mTongue=$this->loginProfile->getMTONGUE();
		if(!$this->action->mTongue)
		{
			$this->action->mTongueFlag=1;
			$this->action->MTONGUE = DropDownCreator::createDD("mtongue");
		}
		//RELIGON AND CASTE 
		$this->action->religion=$this->loginProfile->getRELIGION();
		$this->action->caste=$this->loginProfile->getCASTE();
		if(!$this->action->religion || !$this->action->caste)
		{
			$this->action->religionFlag=1;
			$this->action->RELIGION = populate_religion();
			//$this->action->CASTE = DropDownCreator::createDD("caste");
			$this->action->CASTE_HINDU = DropDownCreator::createDD("caste_hindu");
			$this->action->SECT_HINDU = DropDownCreator::createDD("sect_hindu");
			$this->action->CASTE_JAIN = DropDownCreator::createDD("caste_jain");
			$this->action->SECT_JAIN = DropDownCreator::createDD("sect_jain");
			$this->action->CASTE_CHRISTIAN = DropDownCreator::createDD("caste_christian");
			$this->action->CASTE_PARSI = DropDownCreator::createDD("caste_parsi");
			$this->action->CASTE_MUSLIM = DropDownCreator::createDD("caste_muslim");
			$this->action->SECT_MUSLIM = DropDownCreator::createDD("sect_muslim");
			$this->action->CASTE_SIKH = DropDownCreator::createDD("caste_sikh");
			$this->action->SECT_SIKH = DropDownCreator::createDD("sect_sikh");
			$this->action->SECT_BUDDHIST = DropDownCreator::createDD("sect_buddhist");
		}
		
		
	}
	public function getTemplateName() {
		return "profile_edit_incomplete_layer";
	}
	public function getOnSubmitJs() {
		        return "return validate();";
				    }

	public function getLayerHeading() {
		return "Complete your profile";
	}
	
	// checks mandataory fields should not be blank and about_me should not be less than 100 chars
	private function checkForError($paramArr){
		if (strlen($paramArr[YOURINFO]) <= 100) {
					$this->error = "min_chars";
					return false;
		}
		elseif ($paramArr[EDU_LEVEL_NEW] =="" || $paramArr[OCCUPATION] =="" || $paramArr[INCOME] =="") {
					$this->error = "mand_fields";
					return false;
		}
		else{
			return true;
		}
	}
	private function checkForErrorPage1($field,$paramArr){
		Switch($field)
		{
			case GENDER:
				$relationArr=array('1','2','4','5','6','2D','6D');
				if(($paramArr[GENDER]!="M" && $paramArr[GENDER]!="F")|| !$this->fieldValueExist("relationship",$paramArr[RELATION]))
				{
					$this->error = "wrong_gender";
					return false;
				}
				else
					return true;
				break;
			case DTOFBIRTH:
				$dob = new DateTime($paramArr[DTOFBIRTH]);
				$currentDate = new DateTime();
				
				$diff = $currentDate->diff($dob);
				if($paramArr[GENDER]=="M" &&  $diff->y <21)
				{
					$this->error = "wrong_age";
					return 0;
				}
				elseif($paramArr[GENDER]=="F" &&  $diff->y <18)
				{
					$this->error = "wrong_age";
					return 0;
				}
				else
					return $diff->y;
				break;
			case COUNTRYCITY:
				if($paramArr[COUNTRY_RES] ==51 && $paramArr[CITY_RES]=="")
				{
					$this->error = "mand_city";
					return false;
				}
				elseif($paramArr[COUNTRY_RES]==51 && !$this->fieldValueExist("city",$paramArr[CITY_RES]))
				{
					$this->error = "wrong_city";
					return false;	
				}
				else
					return true;
			case MSTATUS:
				//for married check for female
				if($this->gender)
					$gender=$this->gender;
				else
					$gender=$paramArr[GENDER];
				//for muslim religion check
				if($this->religion)
					$religion=$this->religion;
				else
					$religion=$paramArr[RELIGION];
					
				if($paramArr[MSTATUS]!=MStatus::NEVER_MARRIED && $paramArr[HAVECHILD] =="")
				{
					$this->error = "mand_mStatus";
					return;	
				}
				elseif($paramArr[MSTATUS]==MStatus::MARRIED && $gender=='F')
				{
					$this->error = "wrong_mStatus";
					return;	
				}
				elseif($paramArr[MSTATUS]==MStatus::MARRIED && $gender=='M' && $religion!=Religion::MUSLIM)
				{
					$this->error = "wrong_mStatusReligion";
					return;		
				}
				elseif(!$this->fieldValueExist("mstatus",$paramArr[MSTATUS]))
				{
					$this->error = "wrong_mstatusValue";
					return false;	
				}
				else
					return true;
			case NUMBER_CHECK:
				$phoneFlag=$this->mobileNumberValidation($paramArr);
				$landlineFlag=$this->landlineNumberValidation($paramArr);
				
				if($phoneFlag !=0 || $landlineFlag!=0)
					return true;
				else
				{
					if($phoneFlag==0)
						$this->error = "wrong_mobile";
					else
						$this->error = "wrong_landline";
					return false;
				}
			case MTONGUE:
				$dbObj = new NEWJS_MTONGUE();
				$clean = $dbObj->getMtongue($paramArr[MTONGUE]);
				if(!$clean["VALUE"])
				{
					$this->error = "wrong_mtongue";
					return false;	
				}
				else
					return true;
			case RELIGION:
				$dbObj = new NEWJS_CASTE();
				$clean = $dbObj->getCastesOfParent($paramArr[RELIGION]);
				if(!in_array($paramArr[CASTE],$clean))
				{
					$this->error = "wrong_caste";
					return false;
				}
				elseif(!$this->fieldValueExist("religion",$paramArr[RELIGION]))
				{
					$this->error = "wrong_religion";
					return false;	
				}
				else
					return true;
			default:			
			return true;
			
		}
	}
	private function fieldValueExist($key,$value)
	{
		if(FieldMap::getFieldLabel($key, $value))
			return true;
		else
			return false;
	}
	private function mobileNumberValidation($paramArr)
	{
	$phone = $paramArr['PHONE_RES'];

	if(!(strlen($phone)>0 && empty($paramArr['PHONE_MOB'])))
	{
		// elements must be either empty or a number
		foreach (array('ISD', 'PHONE_MOB') as $key)
		{
		  if (isset($paramArr[$key]) && !preg_match('/^[+]?[0-9]+$/', $paramArr[$key]) && !empty($paramArr[$key]))
		  {
			return 0;
		  }
		}
		if (!preg_match('/^[0-9]+$/', $paramArr['PHONE_MOB']))
		{
			return 0;
		}
		if($paramArr['ISD']=='')
		{
			return 0;
		}
			
		$clean = (string) $paramArr['PHONE_MOB'];
		$length = strlen($clean);
		
		if (in_array($paramArr['ISD'],array('0','91','+91')) && $length > 10)
		{
			return 0;
		}
		elseif (!in_array($paramArr['ISD'],array('0','91','+91')) && $length >14)
		{
			return 0;
		}

		if (in_array($paramArr['ISD'],array('0','91','+91')) && $length < 10)
		{
			return 0;
		}
		elseif (!in_array($paramArr['ISD'],array('0','91','+91')) && $length < 6)
		{
			return 0;
		}
		return 1;
	}
	}
	
	private function landlineNumberValidation($paramArr)
	{
		if(count(array_filter($paramArr)) && isset($paramArr['landline']) && !empty($paramArr['landline']))
		{
			if (!preg_match('/^[+]?[0-9]+$/', $paramArr['ISD']))
			{
				return 0;//throw new sfValidatorError($this, 'err_phone_invalid', array('value' => $paramArr));
			}
			foreach (array('STD', 'PHONE_RES') as $key)
			{
				if (!preg_match('/^[0-9]+$/', $paramArr[$key]))
				{
					return 0;//throw new sfValidatorError($this, 'err_phone_invalid', array('value' => $paramArr));
				}
			}
			if(in_array($paramArr['ISD'],array('0','91','+91')))
				$paramArr['STD'] = ltrim($paramArr['STD'],'0');
			$clean =  $paramArr['STD'].$paramArr['PHONE_RES'];  	
			$length = strlen($clean);
			
			if (in_array($paramArr['ISD'],array('0','91','+91'))  && $length > 10)
			{
				return 0;//throw new sfValidatorError($this, 'err_phone_invalid', array('value' => $clean, 'err_phone_invalid' => $this->getOption('max_length')));
			}
			elseif (!in_array($paramArr['ISD'],array('0','91','+91')) && $length >14)
			{
				return 0;//throw new sfValidatorError($this, 'err_phone_invalid', array('value' => $clean));
			}
			if (in_array($paramArr['ISD'],array('0','91','+91')) && $length < 10)
			{
				return 0;// throw new sfValidatorError($this, 'err_phone_invalid', array('value' => $clean, 'err_phone_invalid' => $this->getOption('min_length')));
			}
			elseif (!in_array($paramArr['ISD'],array('0','91','+91')) && $length <6)
			{
				return 0;//   throw new sfValidatorError($this, 'err_phone_invalid', array('value' => $clean));
			}
			return 1;
		}
	}
	
	/** Initiate IVR call for phone verification
	 * */
    private function initiatePhoneVerification() {
			return true;
			$phone_mob=$this->loginProfile->getPHONE_MOB();
			$phone_res=$this->loginProfile->getPHONE_RES();
			$profileid=$this->loginProfile->getPROFILEID();
			$isdArr=explode('+',$this->loginProfile->getISD());
			if($isdArr[0]=="+")
				$isd=$isdArr[1];
			else
				$isd=$isdArr[0];
			$std=$this->loginProfile->getSTD();
			//added by nitesh in registration revamp
			/* As Requirment we have shifted IVR-  Phone No. Verification Code after profile completation in second page
			* Scenarios checked for IVR call: 1. junk number exist (no ivr call)
			2. Duplicate Exist (no ivr call)
			3. ivr call (if neither junk nor duplicate)
			*/
			include_once (sfConfig::get("sf_web_dir")."/ivr/jsPhoneVerify.php");
			include_once (sfConfig::get("sf_web_dir")."/ivr/jsivrFunctions.php");
			if($phone_mob){
				$ivr_phone = $phone_mob;
				$phoneType = 'M';
				$ivr_std = '';
				$ivr_isd=$isd;
			}
			else if($phone_res){
				$ivr_phone 	=$phone_res;
				$phoneType	='L';
				$ivr_isd=$isd;
				$ivr_std =trim($std);
				$ivr_phone	=$phone_res;				
			}
			$chk_junk = chkJunkNumberList($ivr_phone, $phoneType);
			if ($chk_junk) phoneUpdateProcess($profileid, '', $phoneType, 'J');
			 
			/* IVR - code ends */
	}

	/** contactArchiveUpdate: 
     * Function  to track the contact Archives informataion
     * @param $email,$id,$ip,$phone,$country_code,$state_code,$mobile,$country_code_mob
     * @return
     *
     */
   private function contactArchiveUpdate() {
        //EMAIL
		$ip= CommonFunction::getIP();
        $dbContactArchive = new NEWJS_CONTACT_ARCHIVE();
        $dbContactArchiveInfo = new CONTACT_ARCHIVE_INFO();
		
		$id=$this->loginProfile->getPROFILEID();
		
        //PHONE_RES
		$phone_res=$this->loginProfile->getPHONE_RES();
		$phone_mob=$this->loginProfile->getPHONE_MOB();
        if ($phone_res != '') {
            //required these varaibles:
            $phone = $this->loginProfile->getISD() . "-" . $this->loginProfile->getSTD() . "-" . $phone_res;
            $changeid = $dbContactArchive->insert($id, "PHONE_RES");
            $dbContactArchiveInfo->insert($changeid, $ip, $phone);
        }
        //MOBILE_RES
		//required these varaibles:
		$arch_mobile = $this->loginProfile->getISD() . "-" . $this->loginProfile->getPHONE_MOB();
		$changeid = $dbContactArchive->insert($id, "PHONE_MOB");
		$dbContactArchiveInfo->insert($changeid, $ip, $arch_mobile);
    }

/* It sets jpartner default values
	 * */
	private function setJpartnerAfterIncompleteLayer($fieldArray){
	//DPP Auto Suggestor implemenation :
		$dppObj=new DppAutoSuggest($this->loginProfile);
		$jpartnerObj=$dppObj->getJpartnerObj();
		//$profileFieldArr=$fieldArray
		//$profileFieldArr=array("MSTATUS","MTONGUE","CASTE","COUNTRY_RES","AGE","RELIGION","HEIGHT");
		

		$gender=$this->loginProfile->getGENDER();		

		if($gender=='M')
			 $jpartnerObj->setGENDER('F');
		else
			 $jpartnerObj->setGENDER('M');
		$jpartnerObj->setDPP('R');
		
		$dppObj->insertJpartnerDPP($fieldArray);
	}
}
