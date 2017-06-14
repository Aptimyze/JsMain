<?php
/**
 * @class ApiProfileSections
 * Demarcates different sections of view profile and edit profile page for App Pages
 * Will be used for Detailed profile page and my profile page in App.
 * */
class ApiProfileSectionsApp extends ApiProfileSections {
	protected $profile;
	protected $Hobbies;
	protected $isEdit;
	public $underScreening;
	public $screeningFieldArr;
	
	function __construct($profile,$isEdit='') {
		$this->profile = $profile;
		$request=sfContext::getInstance()->getRequest();
		$this->showAlternateEmail = $request->getParameter("showAlternateEmail");		
		$dbHobbies = new JHOBBYCacheLib();
		$this->Hobbies=$dbHobbies->getUserHobbiesApi($this->profile->getPROFILEID());
                $this->isEdit=$isEdit;
		$this->underScreening="under Screening";
		$this->setApiScreeningFields();
	}
	/** @function
	 * @returns key value array of Life style section of app
	 * */
	public function getApiLifeAttr() {

		$apiLifeAttrArr[]=$this->getApiFormatArray("DIET","Dietary Habits", $this->profile->getDecoratedDiet(),$this->profile->getDIET(),$this->getApiScreeningField("DIET"));

		$apiLifeAttrArr[]=$this->getApiFormatArray("SMOKE","Smoking Habits",$this->profile->getDecoratedSmoke(),$this->profile->getSMOKE(),$this->getApiScreeningField("SMOKE"));

		$apiLifeAttrArr[]=$this->getApiFormatArray("DRINK","Drinking Habits",$this->profile->getDecoratedDrink(),$this->profile->getDRINK(),$this->getApiScreeningField("DRINK"));

		$apiLifeAttrArr[]=$this->getApiFormatArray("OPEN_TO_PET","Open to Pets?",$this->profile->getDecoratedOpenToPet(),$this->profile->getOPEN_TO_PET(),$this->getApiScreeningField("OPEN_TO_PET"));

		$apiLifeAttrArr[]=$this->getApiFormatArray("OWN_HOUSE","Own a House?",$this->profile->getDecoratedOwnHouse(),$this->profile->getOWN_HOUSE(),$this->getApiScreeningField("OWN_HOUSE"));

		$apiLifeAttrArr[]=$this->getApiFormatArray("HAVE_CAR","Own a Car?",$this->profile->getDecoratedHaveCar(),$this->profile->getHAVE_CAR(),$this->getApiScreeningField("HAVE_CAR"));

		$apiLifeAttrArr[]=$this->getApiFormatArray("HOBBIES_LANGUAGE","Languages I Speak",$this->Hobbies[LANGUAGE][LABEL],$this->Hobbies[LANGUAGE][VALUE],$this->getApiScreeningField("HOBBIES_LANGUAGE"));

		$apiLifeAttrArr[]=$this->getApiFormatArray("FAV_FOOD","Food I Cook",$this->Hobbies[FAV_FOOD],$this->Hobbies[FAV_FOOD],$this->getApiScreeningField("FAV_FOOD"));

		$apiLifeAttrArr[]=$this->getApiFormatArray("HOBBIES_HOBBY","Hobbies",$this->Hobbies[HOBBY][LABEL],$this->Hobbies[HOBBY][VALUE],$this->getApiScreeningField("HOBBIES_HOBBY"));

		$apiLifeAttrArr[]=$this->getApiFormatArray("HOBBIES_INTEREST","Interests",$this->Hobbies[INTEREST][LABEL],$this->Hobbies[INTEREST][VALUE],$this->getApiScreeningField("HOBBIES_INTEREST"));

		$apiLifeAttrArr[]=$this->getApiFormatArray("HOBBIES_MUSIC","Favourite Music",$this->Hobbies[MUSIC][LABEL],$this->Hobbies[MUSIC][VALUE],$this->getApiScreeningField("HOBBIES_MUSIC"));

		$apiLifeAttrArr[]=$this->getApiFormatArray("HOBBIES_BOOK","Favourite Book",$this->Hobbies[BOOK][LABEL],$this->Hobbies[BOOK][VALUE],$this->getApiScreeningField("HOBBIES_BOOK"));

		$apiLifeAttrArr[]=$this->getApiFormatArray("HOBBIES_DRESS","Dress Style",$this->Hobbies[DRESS][LABEL],$this->Hobbies[DRESS][VALUE],$this->getApiScreeningField("HOBBIES_DRESS"));

		$apiLifeAttrArr[]=$this->getApiFormatArray("FAV_MOVIE","Movies",$this->Hobbies[FAV_MOVIE],$this->Hobbies[FAV_MOVIE],$this->getApiScreeningField("FAV_MOVIE"));

		$apiLifeAttrArr[]=$this->getApiFormatArray("HOBBIES_SPORTS","Sports",$this->Hobbies[SPORTS][LABEL],$this->Hobbies[SPORTS][VALUE],$this->getApiScreeningField("HOBBIES_SPORTS"));

		$apiLifeAttrArr[]=$this->getApiFormatArray("HOBBIES_CUISINE","Cuisine",$this->Hobbies[CUISINE][LABEL],$this->Hobbies[CUISINE][VALUE],$this->getApiScreeningField("HOBBIES_CUISINE"));

		$apiLifeAttrArr[]=$this->getApiFormatArray("FAV_BOOK","Favourite Read",$this->Hobbies[FAV_BOOK],$this->Hobbies[FAV_BOOK],$this->getApiScreeningField("FAV_BOOK"));

		$apiLifeAttrArr[]=$this->getApiFormatArray("FAV_TVSHOW","TV Shows",$this->Hobbies[FAV_TVSHOW],$this->Hobbies[FAV_TVSHOW],$this->getApiScreeningField("FAV_TVSHOW"));		

		$apiLifeAttrArr[]=$this->getApiFormatArray("FAV_VAC_DEST","Vacation Destination",$this->Hobbies[FAV_VAC_DEST],$this->Hobbies[FAV_VAC_DEST],$this->getApiScreeningField("FAV_VAC_DEST"));

    
		return $apiLifeAttrArr;
	}
	
	/** @function
	 * @returns key value array of Family details section of app
	 * */
	public function getApiFamilyDetails() {
		
		$familyArr[]=$this->getApiFormatArray("FAMILYINFO","About My Family" ,$this->profile->getDecoratedFamilyInfo(),$this->profile->getFAMILYINFO(),$this->getApiScreeningField("FAMILYINFO"));
		$familyArr[]=$this->getApiFormatArray("FAMILY_VALUES","Family Values" ,$this->profile->getDecoratedFamilyValues(),$this->profile->getFAMILY_VALUES(),$this->getApiScreeningField("FAMILY_VALUES"));
		$familyArr[]=$this->getApiFormatArray("FAMILY_TYPE","Family Type" ,$this->profile->getDecoratedFamilyType(),$this->profile->getFAMILY_TYPE(),$this->getApiScreeningField("FAMILY_TYPE"));
		$familyArr[]=$this->getApiFormatArray("FAMILY_STATUS","Family Status" ,$this->profile->getDecoratedFamilyStatus(),$this->profile->getFAMILY_STATUS(),$this->getApiScreeningField("FAMILY_STATUS"));
		$familyArr[]=$this->getApiFormatArray("PARENT_CITY_SAME","Living with parents?" ,$this->profile->getDecoratedLiveWithParents(),$this->profile->getPARENT_CITY_SAME(),$this->getApiScreeningField("PARENT_CITY_SAME"));
		$familyArr[]=$this->getApiFormatArray("FAMILY_BACK","Father is" ,$this->profile->getDecoratedFamilyBackground(),$this->profile->getFAMILY_BACK(),$this->getApiScreeningField("FAMILY_BACK"));
		$familyArr[]=$this->getApiFormatArray("MOTHER_OCC","Mother is" ,$this->profile->getDecoratedMotherOccupation(),$this->profile->getMOTHER_OCC(),$this->getApiScreeningField(""));
		$familyArr[]=$this->getApiFormatArray("FAMILY_INCOME","Family Income" ,$this->profile->getDecoratedFamilyIncome(),$this->profile->getFAMILY_INCOME(),$this->getApiScreeningField("FAMILY_INCOME"));

		$siblings = $this->profile->getSiblings();
		if($siblings->tbrother !== ''){
			$brother = $siblings->tbrother . " brother";
			if ($siblings->tbrother > 1) $brother.= "s";
			if ($siblings->mbrother!='' && $siblings->mbrother!=0 &&$siblings->tbrother !=0) $brother.= " of which married " . $siblings->mbrother;
			if($siblings->tbrother==="3+")
				$siblings->tbrother=4;
			if($siblings->mbrother==="3+")
				$siblings->mbrother=4;
			$sibling_value_bro=$siblings->tbrother.",".$siblings->mbrother;
		}
		else
		{
			$brother="";
			$sibling_value_bro="";
			
		}
		$familyArr[]=$this->getApiFormatArray("T_BROTHER","Brother(s)" ,$brother,$sibling_value_bro,$this->getApiScreeningField("T_BROTHER"));
		
		if($siblings->tsister !==''){
			$sister = $siblings->tsister . " sister";
			if ($siblings->tsister > 1) $sister.= "s";
			if ($siblings->msister!=='' && $siblings->msister!=0 && $siblings->tsister!=0) $sister.= " of which married " . $siblings->msister;
			if($siblings->tsister==="3+")
				$siblings->tsister=4;
			if($siblings->msister==="3+")
				$siblings->msister=4;
			$sibling_value_sis=$siblings->tsister.",".$siblings->msister;
		}
		else{
			$sister="";
			$sibling_value_sis=="";
			}
			$familyArr[]=$this->getApiFormatArray("T_SISTER","Sister(s)" ,$sister,$sibling_value_sis,$this->getApiScreeningField("T_SISTER"));

		return $familyArr;
	}
	
	/** @function
	 * @returns key value array of Astro section for app
	 * */
	public function getApiAstroKundali() {
		$AstroKundali = $this->profile->getAstroKundali();
		
		$astro[]=$this->getApiFormatArray("HOROSCOPE_MATCH","Horoscope match is must?" , $this->profile->getDecoratedHoroscopeMatch(),$this->profile->getHOROSCOPE_MATCH(),$this->getApiScreeningField("HOROSCOPE_MATCH"));
                $horoscope = new Horoscope();
		$horoExists = $horoscope->isHoroscopeExist($this->profile);	
		
		//segregated key for android and ios. added ios key in the end as per their request and kept the android key as it is
		if($horoExists=="Y")
		{
			$horoLabel = "Update Horoscope";			
		}
		elseif($horoExists=="N")
		{			
			$horoLabel = "Create Horoscope";			
		}

		if(MobileCommon::isApp() == "A" && $horoLabel)
		{
			$astro[0][HORO_BUTTON_LABEL] = $horoLabel;
		}		

    $this->addSunSign($astro,$AstroKundali);
    
		$astro[]=$this->getApiFormatArray("RASHI","Rashi/Moon Sign" , $AstroKundali->rashi,$this->profile->getRASHI(),$this->getApiScreeningField("RASHI"));
		//since we save label in nakshatra
		if($AstroKundali->nakshatra)
		{
			foreach(FieldMap::getFieldLabel('nakshatra','',1) as $key=>$val)
			{
				if($val==$this->profile->getNAKSHATRA())
					$nakshatra=$key;
			}
		}
		else
			$nakshatra="";
		$astro[]=$this->getApiFormatArray("NAKSHATRA","Nakshatra" , $AstroKundali->nakshatra,$nakshatra,$this->getApiScreeningField("NAKSHATRA"));
                $decManglikRemovedDontKnow = CommonFunction::setManglikWithoutDontKnow($this->profile->getDecoratedManglik());
                $manglikRemovedDontKnow = CommonFunction::setManglikWithoutDontKnow($this->profile->getMANGLIK());
		$astro[]=$this->getApiFormatArray("MANGLIK","Manglik" ,$decManglikRemovedDontKnow,$manglikRemovedDontKnow,$this->getApiScreeningField("MANGLIK"));
		
		$astro[]=$this->getApiFormatArray("ASTRO_DOB","Date of Birth" , $AstroKundali->dateOfBirth,"",$this->getApiScreeningField(""));
		
		$astro[]=$this->getApiFormatArray("ASTRO_BTIME","Time of Birth" , $AstroKundali->birthTime,"",$this->getApiScreeningField(""));
		
		$astro[]=$this->getApiFormatArray("ASTRO_COUNTRY_BIRTH","Country" , $this->profile->getDecoratedBirthCountry(),"",$this->getApiScreeningField(""));
		
		$astro[]=$this->getApiFormatArray("ASTRO_PLACE_BIRTH","City/Town" , $this->profile->getDecoratedBirthCity(),"",$this->getApiScreeningField(""));
		
		//create horoscope key for ios
		if(MobileCommon::isApp() == "I" && $horoLabel)
		{
			$astro[]=$this->getApiFormatArray("HORO_BUTTON_LABEL",$horoLabel , $horoLabel);
		}
		return $astro;
	}
	
	/** @function
	 * @returns key value array of My Education section for app
	 * */
	public function getApiEducation() {
		$educationValues=$this->profile->getEducationDetail("onlyValues");
    
    if($educationValues instanceof ProfileComponent){
      $educationValues = (array) $educationValues;
    }
    
		$education = $this->profile->getEducationDetail(1);
		
		
		$eduArr[]=$this->getApiFormatArray("EDUCATION","About My Education" ,$this->profile->getDecoratedEducationInfo(),$this->profile->getEDUCATION(),$this->getApiScreeningField("EDUCATION"));
		
		$eduArr[]=$this->getApiFormatArray("EDU_LEVEL_NEW","Highest Education",$this->profile->getDecoratedEducation(),$this->profile->getEDU_LEVEL_NEW(),$this->getApiScreeningField("EDU_LEVEL_NEW"));
		//highest degree should in a pg degree
		if(array_key_exists($this->profile->getEDU_LEVEL_NEW(),FieldMap::getFieldLabel("degree_pg","",1)))
		{
			$eduArr[]=$this->getApiFormatArray("DEGREE_PG","PG Degree" , FieldMap::getFieldLabel("degree_pg",$education['PG_DEGREE']),$educationValues[PG_DEGREE],$this->getApiScreeningField("DEGREE_PG"));
		
			$eduArr[]=$this->getApiFormatArray("PG_COLLEGE","PG College" , $education["PG_COLLEGE"],$educationValues[PG_COLLEGE],$this->getApiScreeningField("PG_COLLEGE"));
      
		}
		else
		{
			$eduArr[]=$this->getApiFormatArray("DEGREE_PG","PG Degree","","",$this->getApiScreeningField("DEGREE_PG"));
			$eduArr[]=$this->getApiFormatArray("PG_COLLEGE","PG College","","",$this->getApiScreeningField("PG_COLLEGE"));
		}
		//highest degree should not be high school or trade school
		if(!($this->profile->getEDU_LEVEL_NEW()=="23" ||$this->profile->getEDU_LEVEL_NEW()=="24"))
		{
			$eduArr[]=$this->getApiFormatArray("DEGREE_UG","UG Degree" , FieldMap::getFieldLabel("degree_ug",$education['UG_DEGREE']),$educationValues[UG_DEGREE],$this->getApiScreeningField("DEGREE_UG"));
		
			$eduArr[]=$this->getApiFormatArray("COLLEGE","UG College" , $education["COLLEGE"],$educationValues['COLLEGE'],$this->getApiScreeningField("COLLEGE"));
      
		}
		else
		{
			$eduArr[]=$this->getApiFormatArray("DEGREE_UG","UG Degree","","",$this->getApiScreeningField("DEGREE_UG"));		
			$eduArr[]=$this->getApiFormatArray("COLLEGE","UG College","","",$this->getApiScreeningField("COLLEGE"));
		}
		$eduArr[]=$this->getApiFormatArray("SCHOOL","School Name" , $education["SCHOOL"],$educationValues['SCHOOL'],$this->getApiScreeningField("SCHOOL"));

		return $eduArr;
	}
	
	/** @function
	 * @returns key value array of My Career section of app
	 * */
	public function getApiOccupation() {
		
		$occArr[]=$this->getApiFormatArray("JOB_INFO","About My Career" ,$this->profile->getDecoratedJobInfo(),$this->profile->getJOB_INFO(),$this->getApiScreeningField("JOB_INFO"));
		
		$occArr[]=$this->getApiFormatArray("COMPANY_NAME","Organization Name" , $this->profile->getDecoratedCompany(),$this->profile->getCOMPANY_NAME(),$this->getApiScreeningField("COMPANY_NAME"));
		
		$occArr[]=$this->getApiFormatArray("OCCUPATION","Occupation" , $this->profile->getDecoratedOccupation(),$this->profile->getOCCUPATION(),$this->getApiScreeningField("OCCUPATION"));
		
		$occArr[]=$this->getApiFormatArray("INCOME","Annual Income" , $this->profile->getDecoratedIncomeLevel(),$this->profile->getINCOME(),$this->getApiScreeningField("INCOME"));
		
		if ($this->profile->getGENDER() == "F")
		{
			$occArr[]=$this->getApiFormatArray("MARRIED_WORKING","Work after marriage?" , $this->profile->getDecoratedCareerAfterMarriage(),$this->profile->getMARRIED_WORKING(),$this->getApiScreeningField("MARRIED_WORKING"));
			//In iOS App show for female profiles only
      if (true === MobileCommon::isIOSApp()) {
        $occArr[]=$this->getApiFormatArray("GOING_ABROAD","Interested in settling abroad?" , $this->profile->getDecoratedSettlingAbroad(),$this->profile->getGOING_ABROAD(),$this->getApiScreeningField("GOING_ABROAD"));
      }
		}
    
    //Show for profiles for channels other then iOS App
    if (false === MobileCommon::isIOSApp()){
      $occArr[]=$this->getApiFormatArray("GOING_ABROAD","Interested in settling abroad?" , $this->profile->getDecoratedSettlingAbroad(),$this->profile->getGOING_ABROAD(),$this->getApiScreeningField("GOING_ABROAD"));
    }
		return $occArr;
	}
	
	/** @function
	 * @returns key value array of contact Information section of app
	 * */
	public function getApiContactInfo() {

		$contactArr[]=$this->getApiFormatArray("PROFILE_HANDLER_NAME","Profile Handler Name" , $this->profile->getDecoratedPersonHandlingProfile(),$this->profile->getPROFILE_HANDLER_NAME(),$this->getApiScreeningField("PROFILE_HANDLER_NAME"));

		$contactArr[]=$this->getApiFormatArray("EMAIL","Email Id" , $this->profile->getEMAIL(),$this->profile->getEMAIL(),$this->getApiScreeningField("EMAIL"),"Y",$this->getVerificationStatusForAltEmailAndMail($this->profile->getVERIFY_EMAIL()));
		
		if(MobileCommon::isDesktop() || MobileCommon::isApp() == "A" || (MobileCommon::isApp() == "I" && $this->showAlternateEmail == "1"))
		{
			$contactArr[]=$this->getApiFormatArray("ALT_EMAIL","Alternate Email Id" , $this->profile->getExtendedContacts()->ALT_EMAIL,$this->profile->getExtendedContacts()->ALT_EMAIL,"2","Y",$this->getVerificationStatusForAltEmailAndMail($this->profile->getExtendedContacts()->ALT_EMAIL_STATUS));
		}
		//mobile number
		if($this->profile->getPHONE_MOB())
		{
			$mobile_label = "+".$this->profile->getISD()."-".$this->profile->getPHONE_MOB();
			$mobile_value = $this->profile->getISD().",".$this->profile->getPHONE_MOB();
		}
		else
		{
			$mobile_label = "";
			$mobile_value ="";
		}
		$contactArr[]=$this->getApiFormatArray("PHONE_MOB","Mobile No." ,$mobile_label,$mobile_value,$this->getApiScreeningField("PHONE_MOB"));
		
		//Alternate mobile
		if($this->profile->getExtendedContacts()->ALT_MOBILE)
		{
			if(strpos($this->profile->getExtendedContacts()->ALT_MOBILE_ISD,"+")===0){
				$altISD=substr($this->profile->getExtendedContacts()->ALT_MOBILE_ISD,1);
			}
			else
				$altISD=$this->profile->getExtendedContacts()->ALT_MOBILE_ISD;

			$alternate_label= "+".$altISD."-".$this->profile->getExtendedContacts()->ALT_MOBILE;
			$alternate_value=$altISD.",".$this->profile->getExtendedContacts()->ALT_MOBILE;
		}
		else
		{
			$alternate_label= "";
			$alternate_value="";
		}
		$contactArr[]=$this->getApiFormatArray("ALT_MOBILE","Alternate No." ,$alternate_label,$alternate_value,$this->getApiScreeningField("ALT_MOBILE"));
		
		//Landline number
		if($this->profile->getPHONE_RES())
		{
			$landline_label= "+".$this->profile->getISD()."-".$this->profile->getSTD()."-".$this->profile->getPHONE_RES();
			$landline_value=$this->profile->getISD().",".$this->profile->getSTD().",".$this->profile->getPHONE_RES();
		}
		else
		{
			$landline_label= "";
			$landline_value="";
		}
		$contactArr[]=$this->getApiFormatArray("PHONE_RES","Landline No." ,$landline_label,$landline_value,$this->getApiScreeningField("PHONE_RES"));
			
		if($this->profile->getTIME_TO_CALL_START() && $this->profile->getTIME_TO_CALL_END())
		{
			$time_to_call_label=$this->profile->getTIME_TO_CALL_START()." to ".$this->profile->getTIME_TO_CALL_END();
			$time_to_call_value=$this->profile->getTIME_TO_CALL_START().",".$this->profile->getTIME_TO_CALL_END();
		}
		else
		{
			$time_to_call_label="";
			$time_to_call_value="";
		}
		
		$contactArr[]=$this->getApiFormatArray("TIME_TO_CALL_START","Suitable Time to Call" ,$time_to_call_label,$time_to_call_value,$this->getApiScreeningField("TIME_TO_CALL_START"));
                
                //Mobile Privacy Settings
                $contactArr[]=$this->getApiFormatArray("SHOWPHONE_MOB","" ,$this->profile->getSHOWPHONE_MOB(),$this->profile->getSHOWPHONE_MOB(),$this->getApiScreeningField("SHOWPHONE_MOB"));
                
                // if(MobileCommon::isApp()!="I"){

                //Landline Privacy Settings
                $contactArr[]=$this->getApiFormatArray("SHOWPHONE_RES","" ,$this->profile->getSHOWPHONE_RES(),$this->profile->getSHOWPHONE_RES(),$this->getApiScreeningField("SHOWPHONE_RES"));

                //Alt Number Privacy Settings
                $contactArr[]=$this->getApiFormatArray("SHOWALT_MOBILE","" ,$this->profile->getExtendedContacts("onlyValues")['SHOWALT_MOBILE'],$this->profile->getExtendedContacts("onlyValues")['SHOWALT_MOBILE'],$this->getApiScreeningField("SHOWALT_MOBILE"));
                // }
    
		
		return $contactArr;
	}
	
	public function getApiCriticalInfo() {
                //date of birth
		$criricalArr[]=$this->getApiFormatArray("DTOFBIRTH","Date of Birth",date("jS M Y", strtotime($this->profile->getDTOFBIRTH())),$this->profile->getDTOFBIRTH(),$this->getApiScreeningField("DTOFBIRTH"),"Y");
		
		//mstatus
		$criricalArr[]=$this->getApiFormatArray("MSTATUS","Marital Status" ,$this->profile->getDecoratedMaritalStatus(),$this->profile->getMSTATUS(),$this->getApiScreeningField("MSTATUS"),"Y");
                
		$criricalArr[]=$this->getApiFormatArray("MSTATUS_PROOF","   " ,"","",$this->getApiScreeningField("MSTATUS"),"Y");
                if($this->profile->getMSTATUS() != 'N'){
                    $criricalArr[]= $this->getApiFormatArray("HAVECHILD","Have Children?",$this->profile->getDecoratedHaveChild(),$this->profile->getHAVECHILD(),$this->getApiScreeningField("HAVECHILD"));
                }
                return $criricalArr;
        }
	/** @function
	 * @returns key value array of Basic Information section of app
	 * */
	public function getApiBasicInfo() {
		//your info
		$basicArr[]=$this->getApiFormatArray("YOURINFO","About Me"  ,$this->profile->getDecoratedYourInfo(),$this->profile->getYOURINFO(),$this->getApiScreeningField("YOURINFO"));
		//username
		$NameOfUser=new NameOfUser;
		$nameData=$NameOfUser->getNameData($this->profile->getPROFILEID());
//		if($this->profile->getGENDER()=="M")
//			$basicArr[]=$this->getApiFormatArray("NAME","Groom's Name"  ,$name,"",$this->getApiScreeningField("NAME"));
//		else
//			$basicArr[]=$this->getApiFormatArray("NAME","Bride's Name"  ,$name,"",$this->getApiScreeningField("NAME"));
		$name = $nameData[$this->profile->getPROFILEID()]['NAME'];
		$basicArr[]=$this->getApiFormatArray("NAME","Full Name"  ,$name,$name,$this->getApiScreeningField("NAME"));
		$basicArr[]=$this->getApiFormatArray("DISPLAYNAME","DISPLAYNAME",'',$nameData[$this->profile->getPROFILEID()]['DISPLAY'],'','Y');
		//gender
		$basicArr[]=$this->getApiFormatArray("GENDER","Gender",$this->profile->getDecoratedGender(),$this->profile->getGender(),$this->getApiScreeningField("GENDER"),"N");
		
		//date of birth
		$basicArr[]=$this->getApiFormatArray("DTOFBIRTH","Date of Birth",date("jS M Y", strtotime($this->profile->getDTOFBIRTH())),$this->profile->getDTOFBIRTH(),$this->getApiScreeningField("DTOFBIRTH"),"Y");
		
		//mstatus
		$basicArr[]=$this->getApiFormatArray("MSTATUS","Marital Status" ,$this->profile->getDecoratedMaritalStatus(),$this->profile->getMSTATUS(),$this->getApiScreeningField("MSTATUS"),"Y");
                
                //HaveChild
                if($this->profile->getMSTATUS() != 'N'){
                    $basicArr[]= $this->getApiFormatArray("HAVECHILD","Have Children?",$this->profile->getDecoratedHaveChild(),$this->profile->getHAVECHILD(),$this->getApiScreeningField("HAVECHILD"));
                }
                
                //Posted By
                $szRelation = $this->profile->getDecoratedRELATION();
                $basicArr[] =$this->getApiFormatArray("RELATION","Profile Managed by" ,$szRelation,$this->profile->getRELATION(),$this->getApiScreeningField("RELATION"));
		
		//country
		$basicArr[] =$this->getApiFormatArray("COUNTRY_RES","Country Living in" ,$this->profile->getDecoratedCountry(),$this->profile->getCOUNTRY_RES(),$this->getApiScreeningField("COUNTRY_RES"));
                
		$stateValue = substr($this->profile->getCITY_RES(),0,2);
                $stateLabel = FieldMap::getFieldLabel("state_india",$stateValue);
                
		$basicArr[] =$this->getApiFormatArray("STATE_RES","State Living in" ,$stateLabel,$stateValue,$this->getApiScreeningField("CITY_RES"));
                
		//city
                if($this->profile->getCITY_RES()!='')
		{
			if(substr($this->profile->getCITY_RES(),2)=="OT")
				$city = "0";
			else
				$city = $this->profile->getCITY_RES();
			$value= $city;
			$label = FieldMap::getFieldLabel("city",$city);
		}
		$basicArr[] =$this->getApiFormatArray("CITY_RES","City Living in" ,$label,$value,$this->getApiScreeningField("CITY_RES"));
		
		//religion
		$basicArr[]  =$this->getApiFormatArray("RELIGION","Religion" ,$this->profile->getDecoratedReligion(),$this->profile->getRELIGION(),$this->getApiScreeningField("RELIGION"),"N");
		
		//CASTE SECTION
		$religion = $this->profile->getReligion();
		if($religion==RELIGION::HINDU || $religion==Religion::JAIN || $religion==Religion::SIKH )
			$basicArr[]  =$this->getApiFormatArray("CASTE","Caste" ,$this->profile->getDecoratedCaste(),$this->profile->getCASTE(),$this->getApiScreeningField("CASTE"));
		elseif($religion== Religion::CHRISTIAN || $religion==Religion::MUSLIM)
			$basicArr[]  =$this->getApiFormatArray("CASTE","Sect" ,$this->profile->getDecoratedCaste(),$this->profile->getCASTE(),$this->getApiScreeningField("CASTE"));
                        $relinfo = (array)$this->profile->getReligionInfo();
                        $relinfo_values = (array)$this->profile->getReligionInfo(1);

                        $basicArr[]  =$this->getApiFormatArray("JAMAAT","Jamaat" ,$relinfo['JAMAAT'],$relinfo_values['JAMAAT'],$this->getApiScreeningField("JAMAAT"));
		
    
		//SUB-CASTE
		if($religion== Religion::HINDU)
			$basicArr[]  =$this->getApiFormatArray("SUBCASTE","Subcaste" ,$this->profile->getDecoratedSubcaste(),$this->profile->getSUBCASTE(),$this->getApiScreeningField("SUBCASTE"));
		
		//SECT
		if($religion==Religion::SIKH )
			$basicArr[]  =$this->getApiFormatArray("SECT","Sect" ,$this->profile->getDecoratedSect(),$this->profile->getSECT(),$this->getApiScreeningField("SECT"));
		elseif($religion==Religion::MUSLIM)
			$basicArr[]  =$this->getApiFormatArray("SECT","Caste" ,$this->profile->getDecoratedSect(),$this->profile->getSECT(),$this->getApiScreeningField("SECT"));
		
		//mtongue
		$basicArr[] =$this->getApiFormatArray("MTONGUE","Mother Tongue" ,$this->profile->getDecoratedCommunity(),$this->profile->getMTONGUE(),$this->getApiScreeningField("MTONGUE"));
		
    //Family based out of
		$this->getAncestralOrigin($basicArr);		
		//gothra
		if($religion==RELIGION::HINDU || $religion==Religion::JAIN || $religion==Religion::SIKH || $religion== Religion::BUDDHIST)
			$basicArr[] =$this->getApiFormatArray("GOTHRA","Gothra" ,$this->profile->getDecoratedGothra(),$this->profile->getGOTHRA(),$this->getApiScreeningField("GOTHRA"));
    
    
//parsi sikh christians
		$relinfo = (array)$this->profile->getReligionInfo();
		$relinfo_values = (array)$this->profile->getReligionInfo(1);
		if ($religion == Religion::CHRISTIAN) //Christian
		{
			$basicArr[] =$this->getApiFormatArray("DIOCESE","Diocese" ,$relinfo[DIOCESE],$relinfo[DIOCESE],$this->getApiScreeningField("DIOCESE"));
			//$basicArr["Caste"] ??????***************************************
			$basicArr[] =$this->getApiFormatArray("BAPTISED","Baptised?" ,$relinfo[BAPTISED],$relinfo_values[BAPTISED],$this->getApiScreeningField("BAPTISED"));

			$basicArr[] =$this->getApiFormatArray("READ_BIBLE","Reads Bible" ,$relinfo[READ_BIBLE],$relinfo_values[READ_BIBLE],$this->getApiScreeningField("READ_BIBLE"));

			$basicArr[] =$this->getApiFormatArray("OFFER_TITHE","Offers Tithe" ,$relinfo[OFFER_TITHE],$relinfo_values[OFFER_TITHE],$this->getApiScreeningField("OFFER_TITHE"));

			$basicArr[] =$this->getApiFormatArray("SPREADING_GOSPEL","Interested to spread the gospel?" ,$relinfo[SPREADING_GOSPEL],$relinfo_values[SPREADING_GOSPEL],$this->getApiScreeningField("SPREADING_GOSPEL"));
			
		} elseif ($religion == Religion::PARSI) //parsi
		{
			$basicArr[] =$this->getApiFormatArray("ZARATHUSHTRI","Are you a Zarusthri" ,$relinfo[ZARATHUSHTRI],$relinfo_values[ZARATHUSHTRI],$this->getApiScreeningField("ZARATHUSHTRI"));

			$basicArr[] =$this->getApiFormatArray("PARENTS_ZARATHUSHTRI","Are your parents Zarusthri?" ,$relinfo[PARENTS_ZARATHUSHTRI],$relinfo_values[PARENTS_ZARATHUSHTRI],$this->getApiScreeningField("PARENTS_ZARATHUSHTRI"));
			
		} elseif ($religion == Religion::SIKH) //Sikh
		{
			$this->getSikhProfileInfo($basicArr, $relinfo, $relinfo_values);			
		}
		elseif ($religion == Religion::MUSLIM) //MUSLIM
		{
			$basicArr[] =$this->getApiFormatArray("MATHTHAB","Ma'thab" ,$relinfo[MATHTHAB],$relinfo_values[MATHTHAB],$this->getApiScreeningField("MATHTHAB"));
			//$basicArr["Speak Urdu"] = $relinfo[SPEAK_URDU];

			$basicArr[] =$this->getApiFormatArray("NAMAZ","Namaz" ,$relinfo[NAMAZ],$relinfo_values[NAMAZ],$this->getApiScreeningField("NAMAZ"));
			
			$basicArr[] =$this->getApiFormatArray("ZAKAT","Zakat" ,$relinfo[ZAKAT],$relinfo_values[ZAKAT],$this->getApiScreeningField("ZAKAT"));

			$basicArr[] =$this->getApiFormatArray("FASTING","Fasting" ,$relinfo[FASTING],$relinfo_values[FASTING],$this->getApiScreeningField("FASTING"));
			
			$basicArr[] =$this->getApiFormatArray("UMRAH_HAJJ","Umrah/Hajj" ,$relinfo[UMRAH_HAJJ],$relinfo_values[UMRAH_HAJJ],$this->getApiScreeningField("UMRAH_HAJJ"));
			
			$basicArr[] =$this->getApiFormatArray("QURAN","Reading Quran" ,$relinfo[QURAN],$relinfo_values[QURAN],$this->getApiScreeningField("QURAN"));
			if ($this->profile->getGender() == "M") {

				$basicArr[] =$this->getApiFormatArray("SUNNAH_BEARD","Sunnah Beard" ,$relinfo[SUNNAH_BEARD],$relinfo_values[SUNNAH_BEARD],$this->getApiScreeningField("SUNNAH_BEARD"));

				$basicArr[] =$this->getApiFormatArray("SUNNAH_CAP","Sunnah Cap" ,$relinfo[SUNNAH_CAP],$relinfo_values[SUNNAH_CAP],$this->getApiScreeningField("SUNNAH_CAP"));
			}
			
			
			if ($this->profile->getGender() == "M") {
        $basicArr[] =$this->getApiFormatArray("HIJAB","Hijab" ,$relinfo[HIJAB],$relinfo_values[HIJAB],$this->getApiScreeningField("HIJAB"));
        
				$basicArr[] =$this->getApiFormatArray("WORKING_MARRIAGE","Can the girl work after marriage?" ,$relinfo[WORKING_MARRIAGE],$relinfo_values[WORKING_MARRIAGE],$this->getApiScreeningField("WORKING_MARRIAGE"));
			}
			else{
        
        if(MobileCommon::isApp()){//For Android and iOS its exist for both
          $basicArr[] =$this->getApiFormatArray("HIJAB","Hijab" ,$relinfo[HIJAB],$relinfo_values[HIJAB],$this->getApiScreeningField("HIJAB"));
        }
        
        $basicArr[] =$this->getApiFormatArray("HIJAB_MARRIAGE","Hijab after marriage?" ,$relinfo[HIJAB_MARRIAGE],$relinfo_values[HIJAB_MARRIAGE],$this->getApiScreeningField("HIJAB_MARRIAGE"));
      }
		}
		

		$basicArr[] =$this->getApiFormatArray("HEIGHT","Height" ,$this->profile->getDecoratedHeight(),$this->profile->getHEIGHT(),$this->getApiScreeningField("HEIGHT"));

		$basicArr[] =$this->getApiFormatArray("COMPLEXION","Complexion" ,$this->profile->getDecoratedComplexion(),$this->profile->getCOMPLEXION(),$this->getApiScreeningField("COMPLEXION"));

		$basicArr[] =$this->getApiFormatArray("BTYPE","Body Type" ,$this->profile->getDecoratedBodytype(),$this->profile->getBTYPE(),$this->getApiScreeningField("BTYPE"));

		$basicArr[] =$this->getApiFormatArray("WEIGHT","Weight(kgs)" ,$this->profile->getDecoratedWeight(),$this->profile->getWEIGHT(),$this->getApiScreeningField("WEIGHT"));

    $this->getProfileHandicappedInfo($basicArr);
		
		$basicArr[] =$this->getApiFormatArray("THALASSEMIA","Thalassemia" ,$this->profile->getDecoratedThalassemia(),$this->profile->getTHALASSEMIA(),$this->getApiScreeningField("THALASSEMIA"));

        //Replacing Positive/Negative to Yes/No Field Value
        $cHIV = $this->profile->getHIV();
        $szHivLabel = null;
        if($cHIV == 'Y')
        {
            $szHivLabel = "Yes";
        }else if($cHIV == 'N')
        {
            $szHivLabel = "No";
        }
        
		$basicArr[] =$this->getApiFormatArray("HIV","HIV+?" ,$szHivLabel,$this->profile->getHIV(),$this->getApiScreeningField("HIV"));
		   
        return $basicArr;
		
	}
	
	//DPP Section
	
	/** @function
	 * @returns key value array of DPP Basic section
	 **/
	public function getApiDppBasicInfo() {
		$jpartnerObj=$this->profile->getJpartner();
		
		//Spouse Info
		$arrOut[] = $this->getApiFormatArray("SPOUSE","About My Partner",trim($this->profile->getDecoratedSpouseInfo()),$this->profile->getSPOUSE(),$this->getApiScreeningField("SPOUSE"));
		//Height
		$szHeight = trim($jpartnerObj->getDecoratedLHEIGHT()) . " - " . trim($jpartnerObj->getDecoratedHHEIGHT());
		$szHeightVal = $jpartnerObj->getLHEIGHT().",".$jpartnerObj->getHHEIGHT();
		$arrOut[] = $this->getApiFormatArray("P_HEIGHT","Height",$szHeight,$szHeightVal,$this->getApiScreeningField("PARTNER_HEIGHT"));
		//Age
		$szAge=trim($jpartnerObj->getDecoratedLAGE())." - ".trim($jpartnerObj->getDecoratedHAGE())." years of age";
		$szAgeVal = $jpartnerObj->getLAGE().','.$jpartnerObj->getHAGE();
		$arrOut[] = $this->getApiFormatArray("P_AGE","Age",$szAge,$szAgeVal,$this->getApiScreeningField("PARTNER_AGE"));
		//Marital Status
		$szMStatus = $this->getDecorateDPP_Response($jpartnerObj->getPARTNER_MSTATUS());
		$arrOut[] = $this->getApiFormatArray("P_MSTATUS","Marital Status",trim($jpartnerObj->getDecoratedPARTNER_MSTATUS()),$szMStatus,$this->getApiScreeningField("PARTNER_MSTATUS"));
                //Have Children
		$szChildren = $this->getDecorateDPP_Response($jpartnerObj->getCHILDREN());
		$arrOut[] = $this->getApiFormatArray("P_HAVECHILD","Have Children",trim($jpartnerObj->getDecoratedCHILDREN()),$szChildren,$this->getApiScreeningField("CHILDREN"));
		//Country
		$szCountry = $this->getDecorateDPP_Response($jpartnerObj->getPARTNER_COUNTRYRES());
		$arrOut[] = $this->getApiFormatArray("P_COUNTRY","Country",trim($jpartnerObj->getDecoratedPARTNER_COUNTRYRES()),$szCountry,$this->getApiScreeningField("PARTNER_COUNTRYRES"));
		//State/City
		$szCity = $this->getDecorateDPP_Response($jpartnerObj->getPARTNER_CITYRES());
		$szState = $this->getDecorateDPP_Response($jpartnerObj->getSTATE());
		$arrOut[] = $this->handleStateCityData($szState,$szCity);

		$count_matches = SearchCommonFunctions::getMyDppMatches("",$this->profile,'',"",'',"","",1)["CNT"];

	    if ( !isset($count_matches))
	    {
	      $count_matches = 0;
	    }

	    $arrOut[]= $this->getApiFormatArray("P_MATCHCOUNT","","",$count_matches,"");
		return $arrOut;
	}

	/** @function
	 * @returns key value array of DPP Education and Occupation section
	 **/
	public function getApiDppEducationAndOcc() {
		$jpartnerObj=$this->profile->getJpartner();
		
		//Education
		$szEdu = $this->getDecorateDPP_Response($jpartnerObj->getPARTNER_ELEVEL_NEW());
		$arrOut[] = $this->getApiFormatArray("P_EDUCATION","Highest Degree",trim($jpartnerObj->getDecoratedPARTNER_ELEVEL_NEW()),$szEdu,$this->getApiScreeningField("PARTNER_ELEVEL_NEW"));
		//Occupation
		$szOcc = $this->getDecorateDPP_Response($jpartnerObj->getPARTNER_OCC());
		$arrOut[] = $this->getApiFormatArray("P_OCCUPATION","Occupation",trim($jpartnerObj->getDecoratedPARTNER_OCC()),$szOcc,$this->getApiScreeningField("PARTNER_OCC"));
		//Income
		$szIncome = $this->getDecorateDPP_Response($jpartnerObj->getLINCOME());
		$szIncome .= "," . $this->getDecorateDPP_Response($jpartnerObj->getHINCOME());
		$szIncome .= "," . $this->getDecorateDPP_Response($jpartnerObj->getLINCOME_DOL());
		$szIncome .= "," . $this->getDecorateDPP_Response($jpartnerObj->getHINCOME_DOL());
		$arrOut[] = $this->getApiFormatArray("P_INCOME","Income",trim($jpartnerObj->getDecoratedPARTNER_INCOME()),$szIncome,$this->getApiScreeningField("PARTNER_INCOME"));
		//Occupation Grouping
		$szOccGroup = $this->getDecorateDPP_Response($jpartnerObj->getOCCUPATION_GROUPING());		
		if(($szOccGroup == "" || $szOccGroup == "DM") && $szOcc != "DM")
		{
			$szOccGroup = CommonFunction::getOccupationGroups($szOcc);
			$decoratedOccGroup = CommonFunction::getOccupationGroupsLabelsFromValues($szOccGroup); 
		}
		else
		{
			$decoratedOccGroup = $jpartnerObj->getDecoratedOCCUPATION_GROUPING();
		}		
		$arrOut[] = $this->getApiFormatArray("P_OCCUPATION_GROUPING","Occupation",trim($decoratedOccGroup),$szOccGroup,$this->getApiScreeningField("OCCUPATION_GROUPING"));		
		return $arrOut;		
	}

	/** @function
	 * @returns key value array of Api DPP Religion and Ethnicity section
	 * */
	/** @function
	 * @returns key value array of Api DPP Religion and Ethnicity section
	 * */
	public function getApiDppReligionAndEth() {
		$jpartnerObj=$this->profile->getJpartner();
		
		$sectArr=array(
			"Muslim","Christian"
		);
		$szReligion=$jpartnerObj->getDecoratedPARTNER_RELIGION();
		if(in_array($szReligion,$sectArr))
		{
			$szCasteLabel 	= "Sect";
			$szCasteVal		= $jpartnerObj->getDecoratedPARTNER_CASTE();
		}
		else
		{
			$szCasteLabel 	= "Caste";
			$szCasteVal 	= $jpartnerObj->getDecoratedPARTNER_CASTE();
		}
		
		//Religion Info
		$szReligionVal = $this->getDecorateDPP_Response($jpartnerObj->getPARTNER_RELIGION());
		$arrOut[] = $this->getApiFormatArray("P_RELIGION","Religion",trim($szReligion),$szReligionVal,$this->getApiScreeningField("PARTNER_RELIGION"));
		//Caste
		$szCasteValues = $this->getDecorateDPP_Response($jpartnerObj->getPARTNER_CASTE());
		$arrOut[] = $this->getApiFormatArray("P_CASTE",$szCasteLabel,trim($szCasteVal),$szCasteValues,$this->getApiScreeningField("PARTNER_CASTE"));
		//Mother Tongue
		$szMTongue = $this->getDecorateDPP_Response($jpartnerObj->getPARTNER_MTONGUE());
		$arrOut[] = $this->getApiFormatArray("P_MTONGUE","Mother Tongue",trim($jpartnerObj->getDecoratedPARTNER_MTONGUE()),$szMTongue,$this->getApiScreeningField("PARTNER_MTONGUE"));
		//Manglik
                $manglikReplaced = CommonFunction::setManglikWithoutDontKnow($jpartnerObj->getPARTNER_MANGLIK());
                $decManglikRemovedDontKnow =  CommonFunction::setManglikWithoutDontKnow($jpartnerObj->getDecoratedPARTNER_MANGLIK());
		$szManglik = $this->getDecorateDPP_Response($manglikReplaced);
                
		$arrOut[] = $this->getApiFormatArray("P_MANGLIK","Manglik",$decManglikRemovedDontKnow,$szManglik,$this->getApiScreeningField("PARTNER_MANGLIK"));
		return $arrOut;		
	}
	
	/** @function
	 * @returns key value array of Api DPP Lifestyle and Attributes section
	 * */
	public function getApiDppLifeAttr() {
		$jpartnerObj=$this->profile->getJpartner();
			
		//Diet
		$szDiet= $this->getDecorateDPP_Response($jpartnerObj->getPARTNER_DIET());
		$arrOut[] = $this->getApiFormatArray("P_DIET","Diet",trim($jpartnerObj->getDecoratedPARTNER_DIET()),$szDiet,$this->getApiScreeningField("PARTNER_DIET"));
		
		//Smoke
		$szSmoke= $this->getDecorateDPP_Response($jpartnerObj->getPARTNER_SMOKE());
		$arrOut[] = $this->getApiFormatArray("P_SMOKE","Smoke",trim($jpartnerObj->getDecoratedPARTNER_SMOKE()),$szSmoke,$this->getApiScreeningField("PARTNER_SMOKE"));
		
		//Drink
		$szDrink= $this->getDecorateDPP_Response($jpartnerObj->getPARTNER_DRINK());
		$arrOut[] = $this->getApiFormatArray("P_DRINK","Drink",trim($jpartnerObj->getDecoratedPARTNER_DRINK()),$szDrink,$this->getApiScreeningField("PARTNER_DRINK"));
		
		//Complexion
		$szComlexion= $this->getDecorateDPP_Response($jpartnerObj->getPARTNER_COMP());
		$arrOut[] = $this->getApiFormatArray("P_COMPLEXION","Complexion",trim($jpartnerObj->getDecoratedPARTNER_COMP()),$szComlexion,$this->getApiScreeningField("PARTNER_COMP"));
		
		//Body Type
		$szBType = $this->getDecorateDPP_Response($jpartnerObj->getPARTNER_BTYPE());
		$arrOut[] = $this->getApiFormatArray("P_BTYPE","Body Type",trim($jpartnerObj->getDecoratedPARTNER_BTYPE()),$szBType,$this->getApiScreeningField("PARTNER_BTYPE"));
		
		//Challenged
		$szHandicapped=$jpartnerObj->getDecoratedHANDICAPPED();
		$szStr = $this->getDecorateDPP_Response($jpartnerObj->getHANDICAPPED());
		$arrOut[] = $this->getApiFormatArray("P_CHALLENGED","Challenged",trim($szHandicapped),$szStr,$this->getApiScreeningField("HANDICAPPED"));
		
		//Special check for partner handicap
		if(strstr($szHandicapped,'Physically Handicapped from birth') || strstr($szHandicapped,'Physically Handicapped due to accident'))
		{
			$szNHand = $this->getDecorateDPP_Response($jpartnerObj->getNHANDICAPPED());
			$arrOut[] = $this->getApiFormatArray("P_NCHALLENGED","Nature of Handicap",trim($jpartnerObj->getDecoratedNHANDICAPPED()),$szNHand,$this->getApiScreeningField("NHANDICAPPED"));
		}
		return $arrOut;
	}
	
	/** @function
	 * @returns key value array of Screening Fields section
	 * */
	public function setApiScreeningFields() {
		$flagFields=FieldMap::getFieldLabel("flagval","",1);

		unset($flagFields["sum"]);

		foreach($flagFields  as $key=>$val)
		{
			if($key && $key!="nakshatra")
			{
				if(!Flag::isFlagSet($key,$this->profile->getSCREENING()))
				{
					$this->screeningFieldArr[strtoupper($key)]='1';
				}
				else
					$this->screeningFieldArr[strtoupper($key)]='0';
			}
		}
    
	}
	/** @function
	 * @returns key value array of Screening Fields section
	 * */
	public function getApiScreeningField($field) {
		if($field)
		{
			if(array_key_exists($field,$this->screeningFieldArr))
			{
				return "".$this->screeningFieldArr[$field]."";
			}
		}
		return '2';

	}
	/** @function
	 * @param $szInput
	 * @returns key value array of a particular Field
	 * */
	protected function getDecorateDPP_Response($szInput)
	{
		if(stripos($szInput,"'") === false)
		{
			$szOut = $szInput;
		}
		else
		{
			$szInput = substr($szInput,stripos($szInput,"'")+1,strripos($szInput,"'")-1);
			$arrInput = explode("','",$szInput);
			$szOut = implode(',',$arrInput);
		}	
		//If No Value or Null Value is specified then Mark it as 'DM' which maps to Doesnot Matter Response
		if(strlen($szOut) == 0)
			return "DM";
	
		return $szOut;
	}
	
	/** @function
	 * @param $szInput
	 * @returns key value array of a particular Field
	 * */
	public function getApiIncompleteInfo()
	{
		$GENDER=$this->profile->getGENDER();
		$incompleteArr[]=$this->getApiIncompleteFormatArray("GENDER","Gender",$GENDER ,"N");
		
		//For Mstatus and religion Field
		$religion=$this->profile->getRELIGION();
		$caste=$this->profile->getCASTE();
		if(!$religion || !$caste)
		{
			$religionFlag=1;
		}
		//----Conditons and variables for incomplete layer ----
		
		//---CREATE PROFILE SECTION----
		$RELATION = $this->profile->getRELATION();
		if(!$RELATION)
		{
			$incompleteArr[]=$this->getApiIncompleteFormatArray("RELATION","Create Profile For","","Y");
		}

		//DOB Section
		$date = JsCommon::formatDate($this->profile->getDTOFBIRTH());
		if(!$date || $this->profile->getDTOFBIRTH()=="0000-00-00")
		{
			$incompleteArr[]=$this->getApiIncompleteFormatArray("DTOFBIRTH","Date of birth","","Y");
		}
		//--Height
		$HEIGHT=$this->profile->getHeight();
		if(!$HEIGHT)
		{
			$incompleteArr[]=$this->getApiIncompleteFormatArray("HEIGHT","Height","","Y");
		}
		//---Country And City SECTION----
		$COUNTRY_RES=$this->profile->getCOUNTRY_RES();
		$CITY_RES=$this->profile->getCITY_RES();
		
		if((!$COUNTRY_RES) ||($COUNTRY_RES==51 && (!$CITY_RES && $CITY_RES!='0')))
		{
			$incompleteArr[]=$this->getApiIncompleteFormatArray("COUNTRY_RES","Country living in",$COUNTRY_RES,"Y");
			$incompleteArr[]=$this->getApiIncompleteFormatArray("CITY_RES","City living in",$CITY_RES,"Y");
		}		
		//---Mstatus and Have Child Section Religion Caste			
		$MSTATUS = $this->profile->getMSTATUS();
		$HAVECHILD = $this->profile->getHAVECHILD();
		if((!$MSTATUS) || ($MSTATUS!="N" && (!$HAVECHILD)) || $religionFlag)
		{
			$incompleteArr[]=$this->getApiIncompleteFormatArray("MSTATUS","Maritial Status",$MSTATUS,"Y");
			$incompleteArr[]=$this->getApiIncompleteFormatArray("HAVECHILD","Have Child",$HAVECHILD,"Y");
			$incompleteArr[]=$this->getApiIncompleteFormatArray("RELIGION","Religion",$religion,"Y");
			$incompleteArr[]=$this->getApiIncompleteFormatArray("CASTE","Caste",$caste,"Y");
		}
		
		//MTONGUE
		$mTongue=$this->profile->getMTONGUE();
		if(!$mTongue)
		{
			$incompleteArr[]=$this->getApiIncompleteFormatArray("MTONGUE","Mother Tongue","","Y");
		}
		
		//PHONE AND LANDLINE NUMBER 
		$PHONE_RES = $this->profile->getPHONE_RES();
		$PHONE_MOB = $this->profile->getPHONE_MOB();
		if(!$PHONE_RES && !$PHONE_MOB)
		{
			//$incompleteArr[]=$this->getApiIncompleteFormatArray("PHONE_RES","Mobile No.","","Y");
			$incompleteArr[]=$this->getApiIncompleteFormatArray("PHONE_MOB","Mobile No.","","Y");
			
		}
		
		//Education 
		$edu_level_new = $this->profile->getEDU_LEVEL_NEW();
		if(!$edu_level_new)
		{
			$incompleteArr[]=$this->getApiIncompleteFormatArray("EDU_LEVEL_NEW","Highest Education","","Y");
		}
		//Occupation
		$occ_val = $this->profile->getOCCUPATION();
		if(!$occ_val)
		{
			$incompleteArr[]=$this->getApiIncompleteFormatArray("OCCUPATION","Occupation","","Y");
		}
		//Income
		$income_val = $this->profile->getINCOME();
		if(!$income_val)
		{
			$incompleteArr[]=$this->getApiIncompleteFormatArray("INCOME","Income","","Y");
		}
		//Your Info
		$yourinfo = $this->profile->getYOURINFO();
		$INFOLEN = strlen($yourinfo);
		if(($yourinfo=="") || $INFOLEN<100)
		{
			$incompleteArr[]=$this->getApiIncompleteFormatArray("YOURINFO","About Yourself",$yourinfo,"Y");
		}
		if(count($incompleteArr)==1)
			unset($incompleteArr[0]);
		return $incompleteArr;
		
	}
	/** @function
	 * @returns key value array of a particular Field
	 * @param $label String
	 * @param $labelVal String
	 * @param $screenBit int
	 * @param $edit char
	 * */
	public function getApiFormatArray($key,$label,$labelVal,$value,$screenBit="2",$edit="Y",$verifyStatus="") {
		$arr["key"]=$key;
		$arr["label"]=$label;
		$arr["label_val"]=$labelVal;
		$arr["value"]=$value;
		$arr["screenBit"]=$screenBit;
		$arr["edit"]=$edit;
		$arr["verifyStatus"]=strval($verifyStatus);
		return $arr;

	}


	/** @function
	 * @returns key value array of a particular incomplete Field
	 * @param $label String
	 * @param $labelVal String
	 * @param $screenBit int
	 * @param $edit char
	 * */
	public function getApiIncompleteFormatArray($key,$label,$value,$incomplete="Y") {
		$arr["key"]=$key;
		$arr["label"]=$label;
		//$arr["label_val"]=$labelVal;
		$arr["value"]=$value;
		$arr["incomplete"]=$incomplete;
		//$arr["edit"]=$edit;
		return $arr;

	}
  
  protected function getPartnerChildren($szVal){
    $valArray = explode(",",$szVal);
    $arrMap = FieldMap::getFieldLabel('children','',1);
    
    $arrOut = array();
    foreach($valArray as $key=>$val){
      if(array_key_exists("$val", $arrMap))
        $arrOut[] = $arrMap[$val];
    }
    
    if(count($arrOut)){
      return implode(", ",$arrOut);
    }
    
    return "Doesn't Matter";
  }
  
  protected function getProfileHandicappedInfo(&$basicArr){
    
    $basicArr[] =$this->getApiFormatArray("HANDICAPPED","Challenged" ,$this->profile->getDecoratedHandicapped(),$this->profile->getHANDICAPPED(),$this->getApiScreeningField("HANDICAPPED"));
		//Special check for partner handicap
		$ph_fstr=$this->profile->getDecoratedHandicapped();
		if(strstr($ph_fstr,'Physically Handicapped from birth')||strstr($ph_fstr,'Physically Handicapped due to accident'))
			$basicArr[] =$this->getApiFormatArray("NATURE_HANDICAP","Nature of Handicap" ,$this->profile->getDecoratedNatureHandicap(),$this->profile->getNATURE_HANDICAP(),$this->getApiScreeningField("NATURE_HANDICAP"));
	
  }
  
  protected function getSikhProfileInfo(&$basicArr,$relinfo,$relinfo_values){
    
    $basicArr[] =$this->getApiFormatArray("AMRITDHARI","Are you Amritdhari?" ,$relinfo[AMRITDHARI],$relinfo_values[AMRITDHARI],$this->getApiScreeningField("AMRITDHARI"));

    if ($relinfo_values[AMRITDHARI] == "N") {

      $basicArr[] =$this->getApiFormatArray("CUT_HAIR","Do you cut your hair?" ,$relinfo[CUT_HAIR],$relinfo_values[CUT_HAIR],$this->getApiScreeningField("CUT_HAIR"));

      if ($this->profile->getGender() == "M") {

        $basicArr[] =$this->getApiFormatArray("TRIM_BEARD","Do you trim your beard?" ,$relinfo[TRIM_BEARD],$relinfo_values[TRIM_BEARD],$this->getApiScreeningField("TRIM_BEARD"));

        $basicArr[] =$this->getApiFormatArray("WEAR_TURBAN","Do you wear turban?" ,$relinfo[WEAR_TURBAN],$relinfo_values[WEAR_TURBAN],$this->getApiScreeningField("WEAR_TURBAN"));

        $basicArr[] =$this->getApiFormatArray("CLEAN_SHAVEN","Are you clean shaven?" ,$relinfo[CLEAN_SHAVEN],$relinfo_values[CLEAN_SHAVEN],$this->getApiScreeningField("CLEAN_SHAVEN"));
      }
    }		
  }
  
    public function getCoverPhotoFromLib($profileid )
    {
        $coverPhotoServiceObj = new CoverPhotoService();
        $coverUrl = $coverPhotoServiceObj->getCoverPhotoURL($profileid);
        return $coverUrl;
    }
    
    protected  function getAncestralOrigin(&$basicArr){
      $nativePlaceObj = new JProfile_NativePlace($this->profile);
      $nativePlaceObj->getInfo();		
		//native or Family based out of
      $basicArr[] =$this->getApiFormatArray("ANCESTRAL_ORIGIN","Family based out of" ,$nativePlaceObj->getDecorated_ViewField(),$this->profile->getANCESTRAL_ORIGIN(),$this->getApiScreeningField("ANCESTRAL_ORIGIN"));
    $szNativeState = FieldMap::getFieldLabel("state_india", $nativePlaceObj->getNativeState());
    $szNativeCity = FieldMap::getFieldLabel("city", $nativePlaceObj->getNativeCity());
    $szNativeCountry = FieldMap::getFieldLabel("country", $nativePlaceObj->getNativeCountry());
    $basicArr[] =$this->getApiFormatArray("NATIVE_STATE","Family based out of" ,$szNativeState,$nativePlaceObj->getNativeState(),$this->getApiScreeningField("NATIVE_STATE"));
    $basicArr[] =$this->getApiFormatArray("NATIVE_CITY","Select City" ,$szNativeCity,$nativePlaceObj->getNativeCity(),$this->getApiScreeningField("NATIVE_CITY"));
    $basicArr[] =$this->getApiFormatArray("NATIVE_COUNTRY","Family based out of" ,$szNativeCountry,$nativePlaceObj->getNativeCountry(),$this->getApiScreeningField("NATIVE_COUNTRY"));
    }
    
    protected function addSunSign(&$astro,$AstroKundali){
     //Not exist for APP
    }

    /** @function
	 * @returns state and city array
	 * @param $stateVal String
	 * @param $cityVal String
	 * */
    public function handleStateCityData($stateVal,$cityVal)
    {	$jpartnerObj=$this->profile->getJpartner();
    	if($stateVal == "DM" && $cityVal == "DM")
    	{
    		$szStateCity = "DM";
    		$stateCityNames = "Doesn't Matter";
    	}
    	elseif($stateVal == "DM")
    	{
    		$szStateCity = $cityVal;
    		$stateCityNames = trim($jpartnerObj->getDecoratedPARTNER_CITYRES());
    	}
    	elseif($cityVal == "DM")
    	{
    		$szStateCity = $stateVal;
    		$stateCityNames = trim($jpartnerObj->getDecoratedSTATE());	
    	}
    	else
    	{
    		$szStateCity = $stateVal.",".$cityVal;
    		$stateNames = trim($jpartnerObj->getDecoratedSTATE(),',');
    		$cityNames = trim($jpartnerObj->getDecoratedPARTNER_CITYRES(),',');
    		$stateCityNames = $stateNames.",".$cityNames;
    	}
    	$stateCityArr = $this->getApiFormatArray("P_CITY","City/State",$stateCityNames,$szStateCity,$this->getApiScreeningField("PARTNER_CITYRES"));
    	return($stateCityArr);
    }

    
  public function getVerificationStatusForAltEmailAndMail($altEmailStatus)
  {    
    
    if($altEmailStatus == "Y")
      return 1;
    else
      return 0;
  }
    
}
?>
