<?php
/**
 * @class ApiProfileSections
 * Demarcates different sections of view profile and edit profile page for Mobile Pages
 * Will be used for Detailed profile page and my profile page in Mobile.
 * */
class ApiProfileSectionsMobile extends ApiProfileSections{
	private $profile;
	private $Hobbies;
	private $isEdit;
	public $underScreening;
	public $screeningFieldArr;
	private $text=0;
	private $checkbox=1;
	private $dropdown=2;
	private $nonEditable=3;
	private $textArea=4;
	function __construct($profile,$isEdit='') {
		$this->profile = $profile;
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
		$apiLifeAttrArr[Habits][outerSectionName]="Habits";
		$apiLifeAttrArr[Habits][outerSectionKey]="Habits";
		$apiLifeAttrArr[Habits][singleKey]=0;
		$apiLifeAttrArr[Habits][OnClick][]=$this->getApiFormatArray("DIET","Diet", $this->profile->getDecoratedDiet(),$this->profile->getDIET(),$this->getApiScreeningField("DIET"),$this->checkbox);

		$apiLifeAttrArr[Habits][OnClick][]=$this->getApiFormatArray("SMOKE","Do you smoke?",$this->profile->getDecoratedSmoke(),$this->profile->getSMOKE(),$this->getApiScreeningField("SMOKE"),$this->checkbox);

		$apiLifeAttrArr[Habits][OnClick][]=$this->getApiFormatArray("DRINK","Do you drink?",$this->profile->getDecoratedDrink(),$this->profile->getDRINK(),$this->getApiScreeningField("DRINK"),$this->checkbox);

		$apiLifeAttrArr[Habits][OnClick][]=$this->getApiFormatArray("OPEN_TO_PET","Open to Pets?",$this->profile->getDecoratedOpenToPet(),$this->profile->getOPEN_TO_PET(),$this->getApiScreeningField("OPEN_TO_PET"),$this->checkbox);
		
		$apiLifeAttrArr[Assets][outerSectionName]="Assets";
		$apiLifeAttrArr[Assets][outerSectionKey]="Assets";
		$apiLifeAttrArr[Assets][singleKey]=0;
		$apiLifeAttrArr[Assets][OnClick][]=$this->getApiFormatArray("OWN_HOUSE","Own a House?",$this->profile->getDecoratedOwnHouse(),$this->profile->getOWN_HOUSE(),$this->getApiScreeningField("OWN_HOUSE"),$this->checkbox);

		$apiLifeAttrArr[Assets][OnClick][]=$this->getApiFormatArray("HAVE_CAR","Own a Car?",$this->profile->getDecoratedHaveCar(),$this->profile->getHAVE_CAR(),$this->getApiScreeningField("HAVE_CAR"),$this->checkbox);
		
		$apiLifeAttrArr[Skills][outerSectionName]="Skills";
		$apiLifeAttrArr[Skills][outerSectionKey]="Skills";
		$apiLifeAttrArr[Skills][singleKey]=0;
		$apiLifeAttrArr[Skills][OnClick][]=$this->getApiFormatArray("HOBBIES_LANGUAGE","Languages I Speak",$this->Hobbies[LANGUAGE][LABEL],$this->Hobbies[LANGUAGE][VALUE],$this->getApiScreeningField("HOBBIES_LANGUAGE"),$this->dropdown,"",1,"updateLifestyle");

		$apiLifeAttrArr[Skills][OnClick][]=$this->getApiFormatArray("FAV_FOOD","Food I Cook",$this->Hobbies[FAV_FOOD],"",$this->getApiScreeningField("FAV_FOOD"),$this->text);

		$apiLifeAttrArr[hobbies][outerSectionName]="Hobbies";
		$apiLifeAttrArr[hobbies][outerSectionKey]="Hobbies";
		$apiLifeAttrArr[hobbies][outerSectionValue]=$this->Hobbies[HOBBY][LABEL];
		$apiLifeAttrArr[hobbies][singleKey]=1;
		$apiLifeAttrArr[hobbies][OnClick][]=$this->getApiFormatArray("HOBBIES_HOBBY","Hobbies",$this->Hobbies[HOBBY][LABEL],$this->Hobbies[HOBBY][VALUE],$this->getApiScreeningField("HOBBIES_HOBBY"),$this->dropdown,"",1,"updateLifestyle");

		$apiLifeAttrArr[Interests][outerSectionName]="Interests";
		$apiLifeAttrArr[Interests][outerSectionKey]="Interests";
		$apiLifeAttrArr[Interests][singleKey]=1;
		$apiLifeAttrArr[Interests][outerSectionValue]=$this->Hobbies[INTEREST][LABEL];
		$apiLifeAttrArr[Interests][OnClick][]=$this->getApiFormatArray("HOBBIES_INTEREST","Interests",$this->Hobbies[INTEREST][LABEL],$this->Hobbies[INTEREST][VALUE],$this->getApiScreeningField("HOBBIES_INTEREST"),$this->dropdown,"",1,"updateLifestyle");

		$apiLifeAttrArr[Favourite][outerSectionName]="Favourite";
		$apiLifeAttrArr[Favourite][outerSectionKey]="Favourite";
		$apiLifeAttrArr[Favourite][singleKey]=0;
		$apiLifeAttrArr[Favourite][OnClick][]=$this->getApiFormatArray("HOBBIES_MUSIC","Favourite Music",$this->Hobbies[MUSIC][LABEL],$this->Hobbies[MUSIC][VALUE],$this->getApiScreeningField("HOBBIES_MUSIC"),$this->dropdown,"",1,"updateLifestyle");

		$apiLifeAttrArr[Favourite][OnClick][]=$this->getApiFormatArray("HOBBIES_BOOK","Favourite Read",$this->Hobbies[BOOK][LABEL],$this->Hobbies[BOOK][VALUE],$this->getApiScreeningField("HOBBIES_BOOK"),$this->dropdown,'',1,"updateLifestyle");

		$apiLifeAttrArr[Favourite][OnClick][]=$this->getApiFormatArray("HOBBIES_DRESS","Dress Style",$this->Hobbies[DRESS][LABEL],$this->Hobbies[DRESS][VALUE],$this->getApiScreeningField("HOBBIES_DRESS"),$this->dropdown,"",1,"updateLifestyle");

		$apiLifeAttrArr[Favourite][OnClick][]=$this->getApiFormatArray("FAV_MOVIE","Movies",$this->Hobbies[FAV_MOVIE],"",$this->getApiScreeningField("FAV_MOVIE"),$this->text);

		$apiLifeAttrArr[Favourite][OnClick][]=$this->getApiFormatArray("HOBBIES_SPORTS","Sports",$this->Hobbies[SPORTS][LABEL],$this->Hobbies[SPORTS][VALUE],$this->getApiScreeningField("HOBBIES_SPORTS"),$this->dropdown,"",1,"updateLifestyle");

		$apiLifeAttrArr[Favourite][OnClick][]=$this->getApiFormatArray("HOBBIES_CUISINE","Cuisine",$this->Hobbies[CUISINE][LABEL],$this->Hobbies[CUISINE][VALUE],$this->getApiScreeningField("HOBBIES_CUISINE"),$this->dropdown,"",1,"updateLifestyle");

		$apiLifeAttrArr[Favourite][OnClick][]=$this->getApiFormatArray("FAV_BOOK","Books",$this->Hobbies[FAV_BOOK],"",$this->getApiScreeningField("FAV_BOOK"),$this->text);

		$apiLifeAttrArr[Favourite][OnClick][]=$this->getApiFormatArray("FAV_TVSHOW","TV Shows",$this->Hobbies[FAV_TVSHOW],"",$this->getApiScreeningField("FAV_TVSHOW"),$this->text);		

		$apiLifeAttrArr[Favourite][OnClick][]=$this->getApiFormatArray("FAV_VAC_DEST","Vacation Destination",$this->Hobbies[FAV_VAC_DEST],"",$this->getApiScreeningField("FAV_VAC_DEST"),$this->text);


		return $apiLifeAttrArr;
	}
	
	/** @function
	 * @returns key value array of Family details section of app
	 * */
	public function getApiFamilyDetails() {
		//your info
		$familyArr[FAMILYINFO][outerSectionName]="About My Family";
		$familyArr[FAMILYINFO][outerSectionKey]="AboutMyFamily";
		$familyArr[FAMILYINFO][singleKey]=0;
		$familyArr[FAMILYINFO][outerSectionValue]=$this->profile->getDecoratedFamilyInfo();
		$familyArr[FAMILYINFO][OnClick][]=$this->getApiFormatArray("FAMILYINFO","About My Family" ,$this->profile->getDecoratedFamilyInfo(),"",$this->getApiScreeningField("FAMILYINFO"),$this->textArea);
		
		$familyArr[Family][outerSectionName]="Family";
		$familyArr[Family][outerSectionKey]="Family";
		$familyArr[Family][singleKey]=0;
		$familyArr[Family][OnClick][]=$this->getApiFormatArray("FAMILY_VALUES","Family Values" ,$this->profile->getDecoratedFamilyValues(),$this->profile->getFAMILY_VALUES(),$this->getApiScreeningField("FAMILY_VALUES"),$this->dropdown);
		$familyArr[Family][OnClick][]=$this->getApiFormatArray("FAMILY_TYPE","Family Type" ,$this->profile->getDecoratedFamilyType(),$this->profile->getFAMILY_TYPE(),$this->getApiScreeningField("FAMILY_TYPE"),$this->dropdown);
		$familyArr[Family][OnClick][]=$this->getApiFormatArray("FAMILY_STATUS","Family Status" ,$this->profile->getDecoratedFamilyStatus(),$this->profile->getFAMILY_STATUS(),$this->getApiScreeningField("FAMILY_STATUS"),$this->dropdown);
		$familyArr[Family][OnClick][]=$this->getApiFormatArray("PARENT_CITY_SAME","Living with Parents?" ,$this->profile->getDecoratedLiveWithParents(),$this->profile->getPARENT_CITY_SAME(),$this->getApiScreeningField("PARENT_CITY_SAME"),$this->dropdown);
		
		$familyArr[Parents][outerSectionName]="Parent";
		$familyArr[Parents][outerSectionKey]="Parent";
		$familyArr[Parents][singleKey]=0;
		$familyArr[Parents][OnClick][]=$this->getApiFormatArray("FAMILY_BACK","Father's Occupation" ,$this->profile->getDecoratedFamilyBackground(),$this->profile->getFAMILY_BACK(),$this->getApiScreeningField("FAMILY_BACK"),$this->dropdown);
		$familyArr[Parents][OnClick][]=$this->getApiFormatArray("MOTHER_OCC","Mother's Occupation" ,$this->profile->getDecoratedMotherOccupation(),$this->profile->getMOTHER_OCC(),$this->getApiScreeningField(""),$this->dropdown);
		$familyArr[Parents][OnClick][]=$this->getApiFormatArray("FAMILY_INCOME","Family Income" ,$this->profile->getDecoratedFamilyIncome(),$this->profile->getFAMILY_INCOME(),$this->getApiScreeningField("FAMILY_INCOME"),$this->dropdown);

		$siblings = $this->profile->getSiblings();
		if($siblings->tbrother != ''){
			$brother = $siblings->tbrother . " brother";
			if ($siblings->tbrother > 1) $brother.= "(s) ";
			$brother.= " of which married " . ($siblings->mbrother!=0?$siblings->mbrother:"None");
			if($siblings->tbrother=="3+")
				$siblings->tbrother=4;
			if($siblings->mbrother=="3+")
				$siblings->mbrother=4;
			$sibling_value_bro=$siblings->tbrother.",".$siblings->mbrother;
			
			if($siblings->tbrother==0)
				$brother="None";
		}
		else
		{
			$brother="";
			$sibling_value_bro="";
			
		}
		
		$familyArr[Siblings][outerSectionName]="Siblings";
		$familyArr[Siblings][outerSectionKey]="Siblings";
		$familyArr[Siblings][singleKey]=0;
		$familyArr[Siblings][OnClick][]=$this->getApiFormatArray("T_BROTHER","Brother(s)" ,$brother,$sibling_value_bro,$this->getApiScreeningField("T_BROTHER"),$this->dropdown,"M_BROTHER",'',"updateSibling");
		
		if($siblings->tsister !=''){
			$sister = $siblings->tsister . " sister";
			if ($siblings->tsister > 1) $sister.= "(s) ";
			$sister.= " of which married " . ($siblings->msister!=0?$siblings->msister:"None");
			if($siblings->tsister=="3+")
				$siblings->tsister=4;
			if($siblings->msister=="3+")
				$siblings->msister=4;
			$sibling_value_sis=$siblings->tsister.",".$siblings->msister;
			
			if($siblings->tsister ==0)
				$sister="None";
		}
		else{
			$sister="";
			$sibling_value_sis=="";
			}
		
		$familyArr[Siblings][OnClick][]=$this->getApiFormatArray("T_SISTER","Sister(s)" ,$sister,$sibling_value_sis,$this->getApiScreeningField("T_SISTER"),$this->dropdown,"M_SISTER",'',"updateSibling");

		return $familyArr;
	}
	
	/** @function
	 * @returns key value array of Astro section for app
	 * */
	public function getApiAstroKundali() {
		$AstroKundali = $this->profile->getAstroKundali();
		$astro[HOROSCOPE_MATCH][outerSectionName]="Horoscope match is must?";
		$astro[HOROSCOPE_MATCH][outerSectionKey]="HoroscopeMustforMarriage";
		$astro[HOROSCOPE_MATCH][outerSectionValue]=$this->profile->getDecoratedHoroscopeMatch();		
		$astro[HOROSCOPE_MATCH][singleKey]=1;
		$astro[HOROSCOPE_MATCH][OnClick][]=$this->getApiFormatArray("HOROSCOPE_MATCH","Horoscope match is must?" , $this->profile->getDecoratedHoroscopeMatch(),$this->profile->getHOROSCOPE_MATCH(),$this->getApiScreeningField("HOROSCOPE_MATCH"),$this->dropdown);
		
		
		$astro[RASHI][outerSectionName]="Rashi";
		$astro[RASHI][outerSectionKey]="Rashi";
		$astro[RASHI][outerSectionValue]=$AstroKundali->rashi;
		$astro[RASHI][singleKey]=1;
		$astro[HOROSCOPE_MATCH][outerSectionValue]=$AstroKundali->rashi;
		$astro[RASHI][OnClick][]=$this->getApiFormatArray("RASHI","Rashi" , $AstroKundali->rashi,$this->profile->getRASHI(),$this->getApiScreeningField("RASHI"),$this->dropdown);
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
		$astro[NAKSHATRA][outerSectionName]="Nakshatra";
		$astro[NAKSHATRA][outerSectionKey]="Nakshatra";
		$astro[NAKSHATRA][outerSectionValue]=$AstroKundali->nakshatra;
		$astro[NAKSHATRA][singleKey]=1;
		$astro[NAKSHATRA][OnClick][]=$this->getApiFormatArray("NAKSHATRA","Nakshatra" , $AstroKundali->nakshatra,$nakshatra,$this->getApiScreeningField("NAKSHATRA"),$this->dropdown);
		
		$astro[MANGLIK][outerSectionName]="Manglik";
		$astro[MANGLIK][outerSectionKey]="Manglik";
		$astro[MANGLIK][outerSectionValue]=FieldMap::getFieldLabel("manglik_label",CommonFunction::setManglikWithoutDontKnow($this->profile->getMANGLIK()));
		$astro[MANGLIK][singleKey]=1;
		$astro[MANGLIK][OnClick][]=$this->getApiFormatArray("MANGLIK","Manglik" , $astro[MANGLIK][outerSectionValue],CommonFunction::setManglikWithoutDontKnow($this->profile->getMANGLIK()),$this->getApiScreeningField("MANGLIK"),$this->dropdown);
		/*
		$astro[ASTRO_DOB][outerSectionName]="Date of Birth";
		$astro[ASTRO_DOB][outerSectionKey]="DateofBirth";
		$astro[ASTRO_DOB][outerSectionValue]=$AstroKundali->dateOfBirth;
		$astro[ASTRO_DOB][singleKey]=1;
		$astro[ASTRO_DOB][OnClick][]=$this->getApiFormatArray("ASTRO_DOB","Date of Birth" , $AstroKundali->dateOfBirth,"",$this->getApiScreeningField(""),$this->dropdown);
		
		$astro[ASTRO_BTIME][outerSectionName]="Time of Birth";
		$astro[ASTRO_BTIME][outerSectionKey]="TimeofBirth";
		$astro[ASTRO_BTIME][outerSectionValue]=$AstroKundali->birthTime;
		$astro[ASTRO_BTIME][singleKey]=1;
		$astro[ASTRO_BTIME][OnClick][]=$this->getApiFormatArray("ASTRO_BTIME","Time of Birth" , $AstroKundali->birthTime,"",$this->getApiScreeningField(""),$this->dropdown);
		
		$astro[ASTRO_COUNTRY_BIRTH][outerSectionName]="Country";
		$astro[ASTRO_COUNTRY_BIRTH][outerSectionKey]="Country";
		$astro[ASTRO_COUNTRY_BIRTH][outerSectionValue]=$this->profile->getDecoratedBirthCountry();
		$astro[ASTRO_COUNTRY_BIRTH][singleKey]=1;
		$astro[ASTRO_COUNTRY_BIRTH][OnClick][]=$this->getApiFormatArray("ASTRO_COUNTRY_BIRTH","Country" , $this->profile->getDecoratedBirthCountry(),"",$this->getApiScreeningField(""),$this->dropdown);
		
		$astro[ASTRO_PLACE_BIRTH][outerSectionName]="City Town";
		$astro[ASTRO_PLACE_BIRTH][outerSectionKey]="CityTown";
		$astro[ASTRO_PLACE_BIRTH][outerSectionValue]=$this->profile->getDecoratedBirthCity();
		$astro[ASTRO_PLACE_BIRTH][singleKey]=1;
		$astro[ASTRO_PLACE_BIRTH][OnClick][]=$this->getApiFormatArray("ASTRO_PLACE_BIRTH","City/Town" , $this->profile->getDecoratedBirthCity(),"",$this->getApiScreeningField(""),$this->dropdown,"ASTRO_COUNTRY_BIRTH");
		*/
		return $astro;
	}
	
	/** @function
	 * @returns key value array of My Education section for app
	 * */
	public function getApiEducation() {
		$educationValues=$this->profile->getEducationDetail("onlyValues");
		if($educationValues instanceOf ProfileComponent)
			unset($educationValues);
		$education = $this->profile->getEducationDetail(1);
		// die("")
		//your info
		$eduArr[EDUCATION][outerSectionName]="About My Education";
		$eduArr[EDUCATION][outerSectionKey]="AboutMyEducation";
		$eduArr[EDUCATION][outerSectionValue]=$this->profile->getDecoratedEducationInfo();
		$eduArr[EDUCATION][singleKey]=0;
		$eduArr[EDUCATION][OnClick][]=$this->getApiFormatArray("EDUCATION","About My Education" ,$this->profile->getDecoratedEducationInfo(),"",$this->getApiScreeningField("EDUCATION"),$this->textArea);
		
		$eduArr[CollegeDetails][outerSectionName]="College Details";
		$eduArr[CollegeDetails][outerSectionKey]="CollegeDetails";
		$eduArr[CollegeDetails][singleKey]=0;
		$eduArr[CollegeDetails][OnClick][]=$this->getApiFormatArray("EDU_LEVEL_NEW","Highest Degree",$this->profile->getDecoratedEducation(),$this->profile->getEDU_LEVEL_NEW(),$this->getApiScreeningField("EDU_LEVEL_NEW"),$this->dropdown,"","","updateEducation");
		$isPG=FieldMap::getFieldLabel("degree_pg",$this->profile->getEDU_LEVEL_NEW())?1:0;
                $showPg = 0;
                if($this->profile->getEDU_LEVEL_NEW() == 21 || $this->profile->getEDU_LEVEL_NEW() == 42)
                   $showPg = 1;
		//highest degree should in a pg degree
		//if(array_key_exists($this->profile->getEDU_LEVEL_NEW(),FieldMap::getFieldLabel("degree_pg","",1)))
		//{
			//if(!$isPG)
			//$education["PG_DEGREE"]="N_B";
			
			$eduArr[CollegeDetails][OnClick][]=$this->getApiFormatArray("DEGREE_PG","PG Degree" , FieldMap::getFieldLabel("degree_pg",$education['PG_DEGREE']),$educationValues[PG_DEGREE],$this->getApiScreeningField("DEGREE_PG"),$this->dropdown,'','','',!$showPg);
			//if(!$isPG)
			//$education["PG_COLLEGE"]="N_B";
			$eduArr[CollegeDetails][OnClick][]=$this->getApiFormatArray("PG_COLLEGE","PG College" , $education["PG_COLLEGE"],"",$this->getApiScreeningField("PG_COLLEGE"),$this->text,'','','',!$isPG);
		//}
		//else
		//{
		//}
		//highest degree should not be high school or trade school
		//if(!($this->profile->getEDU_LEVEL_NEW()=="23" ||$this->profile->getEDU_LEVEL_NEW()=="24"))
		//{
		
		$ugDegree=FieldMap::getFieldLabel("degree_ug",$this->profile->getEDU_LEVEL_NEW());
			$isUG=$isPG || $ugDegree?1:0;
			
			
			
			if($this->profile->getEDU_LEVEL_NEW()==23 || $this->profile->getEDU_LEVEL_NEW()==24)
				$isUG=0;
			//if(!$isUG)
			//$education["UG_DEGREE"]='N_B';	
			$eduArr[CollegeDetails][OnClick][]=$this->getApiFormatArray("DEGREE_UG","Graduation Degree" , FieldMap::getFieldLabel("degree_ug",$education['UG_DEGREE']),$educationValues[UG_DEGREE],$this->getApiScreeningField("DEGREE_UG"),$this->dropdown,"","","",!$isUG);
			//if(!$isUG)
			//	$education["COLLEGE"]="N_B";
			$eduArr[CollegeDetails][OnClick][]=$this->getApiFormatArray("COLLEGE","Graduation College" , $education["COLLEGE"],"",$this->getApiScreeningField("COLLEGE"),$this->text,'','','',!$isUG);
		//}
		//else
		//{
		//}
		$eduArr[CollegeDetails][OnClick][]=$this->getApiFormatArray("SCHOOL","School Name" , $education["SCHOOL"],"",$this->getApiScreeningField("SCHOOL"),$this->text);

		// var_dump($education);

		return $eduArr;
	}
	
	/** @function
	 * @returns key value array of My Career section of app
	 * */
	public function getApiOccupation() {
		//your info
		$occArr[JOB_INFO][outerSectionName]="About My Career";
		$occArr[JOB_INFO][outerSectionKey]="AboutMyCareer";
		$occArr[JOB_INFO][singleKey]=0;
		$occArr[JOB_INFO][OnClick][]=$this->getApiFormatArray("JOB_INFO","About My Career" ,$this->profile->getDecoratedJobInfo(),"",$this->getApiScreeningField("JOB_INFO"),$this->textArea);
		
		
		//your info
		$occArr[CarrerDetails][outerSectionName]="Career Details";
		$occArr[CarrerDetails][outerSectionKey]="CarrerDetails";
		$occArr[CarrerDetails][singleKey]=0;
		$occArr[CarrerDetails][OnClick][]=$this->getApiFormatArray("COMPANY_NAME","Organization Name" , $this->profile->getDecoratedCompany(),$this->profile->getCOMPANY_NAME(),$this->getApiScreeningField("COMPANY_NAME"),$this->text);
		
		$occArr[CarrerDetails][OnClick][]=$this->getApiFormatArray("OCCUPATION","Work Area" , $this->profile->getDecoratedOccupation(),$this->profile->getOCCUPATION(),$this->getApiScreeningField("OCCUPATION"),$this->dropdown);
		
		$occArr[CarrerDetails][OnClick][]=$this->getApiFormatArray("INCOME","Annual Income" , $this->profile->getDecoratedIncomeLevel(),$this->profile->getINCOME(),$this->getApiScreeningField("INCOME"),$this->dropdown);
		
    $occArr[FuturePlans][outerSectionName]="Future Plans";
    $occArr[FuturePlans][outerSectionKey]="FuturePlans";
    $occArr[FuturePlans][singleKey]=0;
    
		if ($this->profile->getGENDER() == "F")
		{
			$occArr[FuturePlans][OnClick][]=$this->getApiFormatArray("MARRIED_WORKING","Planning to Work after Marriage?" , $this->profile->getDecoratedCareerAfterMarriage(),$this->profile->getMARRIED_WORKING(),$this->getApiScreeningField("MARRIED_WORKING"),$this->checkbox);
		}
    
    $occArr[FuturePlans][OnClick][]=$this->getApiFormatArray("GOING_ABROAD","Interested in Settling Abroad?" , $this->profile->getDecoratedSettlingAbroad(),$this->profile->getGOING_ABROAD(),$this->getApiScreeningField("GOING_ABROAD"),$this->checkbox);
		return $occArr;
	}
	
	/** @function
	 * @returns key value array of contact Information section of app
	 * */
	public function getApiContactInfo() {
		$contactArr[]=$this->getApiFormatArray("PROFILE_HANDLER_NAME","Name of the Profile Creator" , $this->profile->getDecoratedPersonHandlingProfile(),"",$this->getApiScreeningField("PROFILE_HANDLER_NAME"),$this->text,'','','',true);

		$contactArr[]=$this->getApiFormatArray("EMAIL","Email Id" , $this->profile->getEMAIL(),"",$this->getApiScreeningField("EMAIL"),$this->text,"",0,"","","",array(),$this->getVerificationStatusForAltEmailAndMail($this->profile->getVERIFY_EMAIL()));
		
		//Alternate Email
		$contactArr[]=$this->getApiFormatArray("ALT_EMAIL","Alternate Email Id" , $this->profile->getExtendedContacts()->ALT_EMAIL,"","0",$this->text,"",0,"","","",array(),$this->getVerificationStatusForAltEmailAndMail($this->profile->getExtendedContacts()->ALT_EMAIL_STATUS));
		
		//mobile number
		if($this->profile->getPHONE_MOB())
		{
			$mobile_label = "+".$this->profile->getISD()."-".$this->profile->getPHONE_MOB();
			$mobile_value = $this->profile->getISD().",".$this->profile->getPHONE_MOB();
		}
		else
		{
			$mobile_label = "+".$this->profile->getISD()."-Number";
			$mobile_value =$this->profile->getISD().",";
		}
		$contactArr[]=$this->getApiFormatArray("PHONE_MOB","Mobile No." ,$mobile_label,$mobile_value,$this->getApiScreeningField("PHONE_MOB"),$this->text);
		
		//Alternate mobile
		if($this->profile->getExtendedContacts()->ALT_MOBILE_ISD)
		{
			if(strpos($this->profile->getExtendedContacts()->ALT_MOBILE_ISD,"+")===0){
				$altISD=substr($this->profile->getExtendedContacts()->ALT_MOBILE_ISD,1);
			}
			else
				$altISD=$this->profile->getExtendedContacts()->ALT_MOBILE_ISD;
		}
		else
			$altISD=$this->profile->getISD();
		if($this->profile->getExtendedContacts()->ALT_MOBILE)
		{
			

			$alternate_label= "+".$altISD."-".$this->profile->getExtendedContacts()->ALT_MOBILE;
			$alternate_value=$altISD.",".$this->profile->getExtendedContacts()->ALT_MOBILE;
		}
		else
		{
			$alternate_label= "+$altISD-Number";
			$alternate_value="";
		}
		$altArr=$this->getApiFormatArray("ALT_MOBILE","Alternate Mobile No." ,$alternate_label,$alternate_value,$this->getApiScreeningField("ALT_MOBILE"),$this->text);
		$contactArr[]=$altArr;
		//Landline number
		if($this->profile->getPHONE_RES())
		{
			$landline_label= "+".$this->profile->getISD()."-".$this->profile->getSTD()."-".$this->profile->getPHONE_RES();
			$landline_value=$this->profile->getISD().",".$this->profile->getSTD().",".$this->profile->getPHONE_RES();
		}
		else
		{
			$landline_label= "+".$this->profile->getISD();
			if($this->profile->getSTD()){
				$landline_label.="-".$this->profile->getSTD()."-Number";
				$landline_value=$this->profile->getISD().",".$this->profile->getSTD().",";
			}
			else{
				$landline_label.="-Area Code-Number";
				$landline_value=$this->profile->getISD().",".",";
			}
				
			//$landline_value="";
		}
		$contactArr[]=$this->getApiFormatArray("PHONE_RES","Landline No." ,$landline_label,$landline_value,$this->getApiScreeningField("PHONE_RES"),$this->text);
			
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
		$contactArr[]=$this->getApiFormatArray("TIME_TO_CALL_START","Suitable Time to Call" ,$time_to_call_label,$time_to_call_value,$this->getApiScreeningField("TIME_TO_CALL_START"),$this->dropdown,"","","updateTimeToCall","");
		
		
		$contactArrFinal[PROFILE_HANDLER_NAME][outerSectionName]="Name of the Profile Creator";
		$contactArrFinal[PROFILE_HANDLER_NAME][outerSectionKey]="NameoftheProfileCreator";
		$contactArrFinal[PROFILE_HANDLER_NAME][outerSectionValue]=$this->profile->getDecoratedPersonHandlingProfile();
		$contactArrFinal[PROFILE_HANDLER_NAME][singleKey]=1;
		$contactArrFinal[PROFILE_HANDLER_NAME][OnClick]=$contactArr;
		
		$contactArrFinal[EMAIL][outerSectionName]="Email Id";
		$contactArrFinal[EMAIL][outerSectionKey]="EmailId";
		$contactArrFinal[EMAIL][outerSectionValue]=$this->profile->getEMAIL();
		$contactArrFinal[EMAIL][singleKey]=1;
		$contactArrFinal[EMAIL][OnClick]=$contactArr;

		$contactArrFinal[ALT_EMAIL][outerSectionName]="Alternate Email Id";
		$contactArrFinal[ALT_EMAIL][outerSectionKey]="AlternateEmailId";
		$contactArrFinal[ALT_EMAIL][outerSectionValue]=$this->profile->getExtendedContacts()->ALT_EMAIL; 
		$contactArrFinal[ALT_EMAIL][singleKey]=1;
		$contactArrFinal[ALT_EMAIL][OnClick]=$contactArr;
		
		$contactArrFinal[PHONE_MOB][outerSectionName]="Mobile No.";
		$contactArrFinal[PHONE_MOB][outerSectionKey]="MobileNo";
		$contactArrFinal[PHONE_MOB][outerSectionValue]=$mobile_label;
		$contactArrFinal[PHONE_MOB][singleKey]=1;
		$contactArrFinal[PHONE_MOB][OnClick]=$contactArr;
		
		$contactArrFinal[ALT_MOBILE][outerSectionName]="Alternate Mobile No.";
		$contactArrFinal[ALT_MOBILE][outerSectionKey]="AlternateMobileNo";
		$contactArrFinal[ALT_MOBILE][outerSectionValue]=$alternate_label;
		$contactArrFinal[ALT_MOBILE][singleKey]=1;
		$contactArrFinal[ALT_MOBILE][OnClick]=$contactArr;
		
		$contactArrFinal[PHONE_RES][outerSectionName]="Landline No.";
		$contactArrFinal[PHONE_RES][outerSectionKey]="LandlineNo";
		$contactArrFinal[PHONE_RES][outerSectionValue]=$landline_label;
		$contactArrFinal[PHONE_RES][singleKey]=1;
		$contactArrFinal[PHONE_RES][OnClick]=$contactArr;
		
		$contactArrFinal[TIME_TO_CALL_START][outerSectionName]="Suitable Time to Call";
		$contactArrFinal[TIME_TO_CALL_START][outerSectionKey]="SuitableTimetoCall";
		$contactArrFinal[TIME_TO_CALL_START][outerSectionValue]=$time_to_call_label;
		$contactArrFinal[TIME_TO_CALL_START][singleKey]=1;
		$contactArrFinal[TIME_TO_CALL_START][OnClick]=$contactArr;
		return $contactArrFinal;
	}
		
	
	/** @function
	 * @returns key value array of Basic Information section of app
	 * */
	public function getApiBasicInfo() {
		
		//your info
		$basicArr[YOURINFO][outerSectionName]="About Me";
		$basicArr[YOURINFO][outerSectionKey]="AboutMe";
		$basicArr[YOURINFO][singleKey]=0;
		$basicArr[YOURINFO][OnClick][]=$this->getApiFormatArray("YOURINFO","About Me"  ,$this->profile->getDecoratedYourInfo(),"",$this->getApiScreeningField("YOURINFO"),$this->textArea);
		//username
		$nameOfUserObj = new NameOfUser;
                $nameData = $nameOfUserObj->getNameData($this->profile->getPROFILEID());
                $dispStr = "Show to All";
                if($nameData[$this->profile->getPROFILEID()]["DISPLAY"] == "N"){
                   $dispStr = "Don't Show";
                }
                $settingData = array("display_string"=>$dispStr,"displayValue"=>$nameData[$this->profile->getPROFILEID()]["DISPLAY"],'callbackoverlay'=>"CalloverlayName");
		$basicArr[basic][outerSectionName]="Basic Details";
		$basicArr[basic][outerSectionKey]="BasicDetails";
		$basicArr[basic][singleKey]=0;
//		if($this->profile->getGENDER()=="M")
//			$basicArr[basic][OnClick][]=$this->getApiFormatArray("NAME","Groom's Name"  ,$name,"",$this->getApiScreeningField("NAME"),$this->text);
//		else
//			$basicArr[basic][OnClick][]=$this->getApiFormatArray("NAME","Bride's Name"  ,$name,"",$this->getApiScreeningField("NAME"),$this->text);
		$basicArr[basic][OnClick][]=$this->getApiFormatArray("NAME","Full Name",$nameData[$this->profile->getPROFILEID()]["NAME"],"",$this->getApiScreeningField("NAME"),$this->text,"","","","",1,$settingData);
		//country
		$value=$this->profile->getCOUNTRY_RES();
		$label=$this->profile->getDecoratedCountry();
		
		$basicArr[basic][OnClick][] =$this->getApiFormatArray("COUNTRY_RES","Country Living in" ,$label,$value,$this->getApiScreeningField("COUNTRY_RES"),$this->dropdown,"","","UpdateCountrySection");
		$stateValue = substr($this->profile->getCITY_RES(),0,2);
        $stateLabel = FieldMap::getFieldLabel("state_india",$stateValue);
		if($this->profile->getCOUNTRY_RES()=="51")
			$hidden = false;
		else
			$hidden=true;
		$basicArr[basic][OnClick][] =$this->getApiFormatArray("STATE_RES","State Living in" ,$stateLabel,$stateValue,$this->getApiScreeningField("CITY_RES"),$this->dropdown,"","","UpdateStateSection",$hidden);
		$value='';
		$label='';
		if($this->profile->getCITY_RES()!='')
		{
			if(substr($this->profile->getCITY_RES(),2)=="OT")
				$city = "0";
			else
				$city = $this->profile->getCITY_RES();
			$value= $city;
			$label = FieldMap::getFieldLabel("city",$city);
		}
		if(($this->profile->getCOUNTRY_RES()=="51" && ($stateValue!='0' && $stateValue!='')) || $this->profile->getCOUNTRY_RES()=="128")
			$hiddenCity = false;
		else
			$hiddenCity = true;
		$basicArr[basic][OnClick][] =$this->getApiFormatArray("CITY_RES","City Living in" ,$label,$value,$this->getApiScreeningField("CITY_RES"),$this->dropdown,'','',"UpdateCitySection",$hiddenCity);
		
		//city
		//$basicArr[basic][OnClick][] =$this->getApiFormatArray("CITY_RES","City Living in" ,$this->profile->getDecoratedCity(),$this->profile->getCITY_RES(),$this->getApiScreeningField("CITY_RES"),$this->dropdown,"CITY_RES");
		
		//gender
		$basicArr[basic][OnClick][]=$this->getApiFormatArray("GENDER","Gender",$this->profile->getDecoratedGender(),$this->profile->getGender(),$this->getApiScreeningField("GENDER"),$this->nonEditable);
		
		//date of birth
		$basicArr[basic][OnClick][]=$this->getApiFormatArray("DTOFBIRTH","Date of Birth",date("jS M Y", strtotime($this->profile->getDTOFBIRTH())),"",$this->getApiScreeningField("DTOFBIRTH"),$this->nonEditable);
		
		//mstatus
		$basicArr[basic][OnClick][]=$this->getApiFormatArray("MSTATUS","Marital Status" ,$this->profile->getDecoratedMaritalStatus(),$this->profile->getMSTATUS(),$this->getApiScreeningField("MSTATUS"),$this->nonEditable);
		
                $basicArr[basic][OnClick][] =$this->getApiFormatArray("RELATION","Profile Managed by",$this->profile->getDecoratedRelation(),$this->profile->getRELATION(),$this->getApiScreeningField("RELATION"),$this->dropdown);
		$relinfo = (array)$this->profile->getReligionInfo();
		$relinfo_values = (array)$this->profile->getReligionInfo(1);
		
		
		$basicArr["Ethnicity"][outerSectionName]="Ethnicity";
		$basicArr["Ethnicity"][outerSectionKey]="Ethnicity";
		$basicArr["Ethnicity"][singleKey]=0;
		//religion
		$basicArr["Ethnicity"][OnClick][] =$this->getApiFormatArray("RELIGION","Religion" ,$this->profile->getDecoratedReligion(),$this->profile->getRELIGION(),$this->getApiScreeningField("RELIGION"),$this->nonEditable);
		
		//CASTE SECTION
		$religion = $this->profile->getReligion();
		if($religion==RELIGION::HINDU || $religion==Religion::JAIN || $religion==Religion::SIKH )
			$basicArr["Ethnicity"][OnClick][]  =$this->getApiFormatArray("CASTE","Caste" ,$this->profile->getDecoratedCaste(),$this->profile->getCASTE(),$this->getApiScreeningField("CASTE"),$this->dropdown);
		elseif($religion== Religion::CHRISTIAN || $religion==Religion::MUSLIM)
			$basicArr["Ethnicity"][OnClick][]  =$this->getApiFormatArray("CASTE","Sect" ,$this->profile->getDecoratedCaste(),$this->profile->getCASTE(),$this->getApiScreeningField("CASTE"),$this->dropdown);
                if($religion==RELIGION::CHRISTIAN)
		$basicArr["Ethnicity"][OnClick][] =$this->getApiFormatArray("DIOCESE","Diocese" ,$relinfo[DIOCESE],"",$this->getApiScreeningField("DIOCESE"),$this->text);
		//SUB-CASTE
		if($religion== Religion::HINDU)
			$basicArr["Ethnicity"][OnClick][] =$this->getApiFormatArray("SUBCASTE","Subcaste" ,$this->profile->getDecoratedSubcaste(),$this->profile->getSUBCASTE(),$this->getApiScreeningField("SUBCASTE"),$this->text);
		
		//SECT
		if($religion==Religion::SIKH )
			$basicArr["Ethnicity"][OnClick][]  =$this->getApiFormatArray("SECT","Sect" ,$this->profile->getDecoratedSect(),$this->profile->getSECT(),$this->getApiScreeningField("SECT"),$this->dropdown);
		elseif($religion==Religion::MUSLIM)
			$basicArr["Ethnicity"][OnClick][]  =$this->getApiFormatArray("SECT","Caste" ,$this->profile->getDecoratedSect(),$this->profile->getSECT(),$this->getApiScreeningField("SECT"),$this->dropdown);
		
		$community_small_label = FieldMap::getFieldLabel("community_small",$this->profile->getMTONGUE());
		//mtongue
		$basicArr["Ethnicity"][OnClick][] =$this->getApiFormatArray("MTONGUE","Mother Tongue" ,$community_small_label,$this->profile->getMTONGUE(),$this->getApiScreeningField("MTONGUE"),$this->dropdown);
		
    //Native Place Fields
		$nativePlaceObj = new JProfile_NativePlace($this->profile);
		$nativePlaceObj->getInfo();		
    $szNativeState = FieldMap::getFieldLabel("state_india", $nativePlaceObj->getNativeState());
    $szNativeCity = FieldMap::getFieldLabel("city", $nativePlaceObj->getNativeCity());
    $szNativeCountry = FieldMap::getFieldLabel("country", $nativePlaceObj->getNativeCountry());
		
    $bHideAncestralOrigin = 0;
    
    //If Country is other than India
    if("51" != $nativePlaceObj->getNativeCountry()) {
      $bHideAncestralOrigin = 1;
      $bHideNativeState = 1;

      $basicArr["Ethnicity"][OnClick][] =$this->getApiFormatArray("NATIVE_COUNTRY","Family based out of" ,$szNativeCountry,$nativePlaceObj->getNativeCountry(),$this->getApiScreeningField("NATIVE_COUNTRY"),$this->dropdown,"NATIVE_CITY",0,"updateNative");
      
    } else {
      $bHideNativeState = 0;
      if($szNativeCity != "Others")
      {
        $bHideAncestralOrigin = 1;
      }
      $arrNativeValue = array();
      $arrNativeLabel = array();
      if ($szNativeState) {
        $arrNativeValue[] =  $szNativeState;
        $arrNativeLabel[] =  $nativePlaceObj->getNativeState();
      }
      if ($szNativeCity) {
        $arrNativeValue[] =  $szNativeCity;
        $arrNativeLabel[] =  $nativePlaceObj->getNativeCity();
      }
      
      $szNativeValue = implode('-', $arrNativeValue);
      $szNativeLabel = implode(',', $arrNativeLabel);
      
    }
    //native or Family based out of
    $basicArr["Ethnicity"][OnClick][] =$this->getApiFormatArray("NATIVE_STATE","Family based out of" ,$szNativeValue,$szNativeLabel,$this->getApiScreeningField("NATIVE_STATE"),$this->dropdown,"NATIVE_CITY",0,"updateNative",$bHideNativeState);
    $basicArr["Ethnicity"][OnClick][] =$this->getApiFormatArray("ANCESTRAL_ORIGIN","Please specify" ,$this->profile->getDecoratedAncestralOrigin(),$this->profile->getANCESTRAL_ORIGIN(),$this->getApiScreeningField("ANCESTRAL_ORIGIN"),$this->text,"",0,"",$bHideAncestralOrigin);
    
		//gothra
		if($religion==RELIGION::HINDU || $religion==Religion::JAIN || $religion==Religion::SIKH || $religion== Religion::BUDDHIST)
			$basicArr["Ethnicity"][OnClick][]=$this->getApiFormatArray("GOTHRA","Gothra" ,$this->profile->getDecoratedGothra(),$this->profile->getGOTHRA(),$this->getApiScreeningField("GOTHRA"),$this->text);
		if($religion==RELIGION::CHRISTIAN || $religion==Religion::PARSI || $religion==Religion::SIKH || $religion== Religion::MUSLIM)
		{
			$basicArr["BeliefSystem"][outerSectionName]="Belief System";
			$basicArr["BeliefSystem"][outerSectionKey]="BeliefSystem";
			$basicArr["BeliefSystem"][singleKey]=0;
		}
		
		//parsi sikh christians
		$relinfo = (array)$this->profile->getReligionInfo();
		$relinfo_values = (array)$this->profile->getReligionInfo(1);
		if ($religion == Religion::CHRISTIAN) //Christian
		{
			//$basicArr["Caste"] ??????***************************************
			$basicArr["BeliefSystem"][OnClick][] =$this->getApiFormatArray("BAPTISED","Baptised" ,$relinfo[BAPTISED],$relinfo_values[BAPTISED],$this->getApiScreeningField("BAPTISED"),$this->checkbox);

			$basicArr["BeliefSystem"][OnClick][] =$this->getApiFormatArray("READ_BIBLE","Read Bible" ,$relinfo[READ_BIBLE],$relinfo_values[READ_BIBLE],$this->getApiScreeningField("READ_BIBLE"),$this->checkbox);

			$basicArr["BeliefSystem"][OnClick][] =$this->getApiFormatArray("OFFER_TITHE","Offers Tithe" ,$relinfo[OFFER_TITHE],$relinfo_values[OFFER_TITHE],$this->getApiScreeningField("OFFER_TITHE"),$this->checkbox);

			$basicArr["BeliefSystem"][OnClick][] =$this->getApiFormatArray("SPREADING_GOSPEL","Interested in Spreading the Gospel" ,$relinfo[SPREADING_GOSPEL],$relinfo_values[SPREADING_GOSPEL],$this->getApiScreeningField("SPREADING_GOSPEL"),$this->checkbox);
			
		} elseif ($religion == Religion::PARSI) //parsi
		{
			
			$basicArr["BeliefSystem"][OnClick][] =$this->getApiFormatArray("ZARATHUSHTRI","Are you a Zarathushtri" ,$relinfo[ZARATHUSHTRI],$relinfo_values[ZARATHUSHTRI],$this->getApiScreeningField("ZARATHUSHTRI"),$this->checkbox);

			$basicArr["BeliefSystem"][OnClick][] =$this->getApiFormatArray("PARENTS_ZARATHUSHTRI","Are your Parents Zarathushtri" ,$relinfo[PARENTS_ZARATHUSHTRI],$relinfo_values[PARENTS_ZARATHUSHTRI],$this->getApiScreeningField("PARENTS_ZARATHUSHTRI"),$this->checkbox);
			
		} elseif ($religion == Religion::SIKH) //Sikh
		{
			$basicArr["BeliefSystem"][OnClick][] =$this->getApiFormatArray("AMRITDHARI","Are you Amritdhari?" ,$relinfo[AMRITDHARI],$relinfo_values[AMRITDHARI],$this->getApiScreeningField("AMRITDHARI"),$this->checkbox);

                        $basicArr["BeliefSystem"][outerSectionName]="Belief System";
                        $basicArr["BeliefSystem"][outerSectionKey]="BeliefSystem";
                        $basicArr["BeliefSystem"][OnClick][] =$this->getApiFormatArray("CUT_HAIR","Do you cut your hair?" ,$relinfo[CUT_HAIR],$relinfo_values[CUT_HAIR],$this->getApiScreeningField("CUT_HAIR"),$this->checkbox);

                        if ($this->profile->getGender() == "M") {

                                $basicArr["BeliefSystem"][OnClick][] =$this->getApiFormatArray("TRIM_BEARD","Do you trim your beard?" ,$relinfo[TRIM_BEARD],$relinfo_values[TRIM_BEARD],$this->getApiScreeningField("TRIM_BEARD"),$this->checkbox);

                                $basicArr["BeliefSystem"][OnClick][] =$this->getApiFormatArray("WEAR_TURBAN","Do you wear turban?" ,$relinfo[WEAR_TURBAN],$relinfo_values[WEAR_TURBAN],$this->getApiScreeningField("WEAR_TURBAN"),$this->checkbox);

                                $basicArr["BeliefSystem"][OnClick][] =$this->getApiFormatArray("CLEAN_SHAVEN","Are you clean shaven?" ,$relinfo[CLEAN_SHAVEN],$relinfo_values[CLEAN_SHAVEN],$this->getApiScreeningField("CLEAN_SHAVEN"),$this->checkbox);
                        }
			
		}
		elseif ($religion == Religion::MUSLIM) //MUSLIM
		{       
                        if($this->profile->getCASTE()!="243")
			$basicArr["BeliefSystem"][OnClick][] =$this->getApiFormatArray("MATHTHAB","Ma'thab" ,$relinfo[MATHTHAB],$relinfo_values[MATHTHAB],$this->getApiScreeningField("MATHTHAB"),$this->checkbox);
			//$basicArr["Speak Urdu"] = $relinfo[SPEAK_URDU];
                        
			$basicArr["BeliefSystem"][OnClick][] =$this->getApiFormatArray("NAMAZ","Namaz" ,$relinfo[NAMAZ],$relinfo_values[NAMAZ],$this->getApiScreeningField("NAMAZ"),$this->checkbox);
			
			$basicArr["BeliefSystem"][OnClick][] =$this->getApiFormatArray("ZAKAT","Zakat" ,$relinfo[ZAKAT],$relinfo_values[ZAKAT],$this->getApiScreeningField("ZAKAT"),$this->checkbox);

			$basicArr["BeliefSystem"][OnClick][] =$this->getApiFormatArray("FASTING","Fasting" ,$relinfo[FASTING],$relinfo_values[FASTING],$this->getApiScreeningField("FASTING"),$this->checkbox);
			
			$basicArr["BeliefSystem"][OnClick][] =$this->getApiFormatArray("UMRAH_HAJJ","Umrah/Hajj" ,$relinfo[UMRAH_HAJJ],$relinfo_values[UMRAH_HAJJ],$this->getApiScreeningField("UMRAH_HAJJ"),$this->checkbox);
			
			$basicArr["BeliefSystem"][OnClick][] =$this->getApiFormatArray("QURAN","Reading Quran" ,$relinfo[QURAN],$relinfo_values[QURAN],$this->getApiScreeningField("QURAN"),$this->checkbox);
			if ($this->profile->getGender() == "M") {

				$basicArr["BeliefSystem"][OnClick][] =$this->getApiFormatArray("SUNNAH_BEARD","Sunnah Beard" ,$relinfo[SUNNAH_BEARD],$relinfo_values[SUNNAH_BEARD],$this->getApiScreeningField("SUNNAH_BEARD"),$this->checkbox);

				$basicArr["BeliefSystem"][OnClick][] =$this->getApiFormatArray("SUNNAH_CAP","Sunnah Cap" ,$relinfo[SUNNAH_CAP],$relinfo_values[SUNNAH_CAP],$this->getApiScreeningField("SUNNAH_CAP"),$this->checkbox);
			}
			if ($this->profile->getGender() == "M")
				$basicArr["BeliefSystem"][OnClick][] =$this->getApiFormatArray("HIJAB","Hijab" ,$relinfo[HIJAB],$relinfo_values[HIJAB],$this->getApiScreeningField("HIJAB"),$this->checkbox);
			else
				$basicArr["BeliefSystem"][OnClick][] =$this->getApiFormatArray("HIJAB_MARRIAGE","Hijab after marriage" ,$relinfo[HIJAB_MARRIAGE],$relinfo_values[HIJAB_MARRIAGE],$this->getApiScreeningField("HIJAB_MARRIAGE"),$this->checkbox);
			
			if ($this->profile->getGender() == "M") {

				$basicArr["BeliefSystem"][OnClick][] =$this->getApiFormatArray("WORKING_MARRIAGE","Can the girl work after marriage?" ,$relinfo[WORKING_MARRIAGE],$relinfo_values[WORKING_MARRIAGE],$this->getApiScreeningField("WORKING_MARRIAGE"),$this->checkbox);
			}
			
		}
		
		
		$basicArr["Appearance"][outerSectionName]="Appearance";
		$basicArr["Appearance"][outerSectionKey]="Appearance";
		$basicArr["Appearance"][singleKey]=0;
		
		$basicArr["Appearance"][OnClick][] =$this->getApiFormatArray("HEIGHT","Height" ,$this->profile->getDecoratedHeight(),$this->profile->getHEIGHT(),$this->getApiScreeningField("HEIGHT"),$this->dropdown);
		
		$basicArr["Appearance"][OnClick][] =$this->getApiFormatArray("COMPLEXION","Complexion" ,$this->profile->getDecoratedComplexion(),$this->profile->getCOMPLEXION(),$this->getApiScreeningField("COMPLEXION"),$this->dropdown);

		$basicArr["Appearance"][OnClick][] =$this->getApiFormatArray("BTYPE","Body Type" ,$this->profile->getDecoratedBodytype(),$this->profile->getBTYPE(),$this->getApiScreeningField("BTYPE"),$this->dropdown);

		$basicArr["Appearance"][OnClick][] =$this->getApiFormatArray("WEIGHT","Weight (kgs)" ,$this->profile->getDecoratedWeight(),$this->profile->getWEIGHT(),$this->getApiScreeningField("WEIGHT"),$this->text);

		$basicArr["SpecialCases"][outerSectionName]="Special Cases";
		$basicArr["SpecialCases"][outerSectionKey]="SpecialCases";
		$basicArr["SpecialCases"][singleKey]=0;
		$decoratedHandicapped=FieldMap::getFieldLabel("handicapped_mobile",$this->profile->getHANDICAPPED());
		$basicArr["SpecialCases"][OnClick][] =$this->getApiFormatArray("HANDICAPPED","Challenged" ,$decoratedHandicapped,$this->profile->getHANDICAPPED(),$this->getApiScreeningField("HANDICAPPED"),$this->dropdown,'','','updateChallenge');
		//Special check for partner handicap
		//$ph_fstr=$this->profile->getDecoratedHandicapped();
		//if(strstr($ph_fstr,'Physically Handicapped from birth')||strstr($ph_fstr,'Physically Handicapped due to accident'))
		
		if(in_array($this->profile->getHANDICAPPED(),array("1","2")))
			$showNh=1;
		$basicArr["SpecialCases"][OnClick][] =$this->getApiFormatArray("NATURE_HANDICAP","Nature of Handicap" ,$this->profile->getDecoratedNatureHandicap(),$this->profile->getNATURE_HANDICAP(),$this->getApiScreeningField("NATURE_HANDICAP"),$this->dropdown,'','','updateHandicap',!$showNh);
			

		$basicArr["SpecialCases"][OnClick][] =$this->getApiFormatArray('THALASSEMIA',"Thalassemia" ,$this->profile->getDecoratedThalassemia(),$this->profile->getTHALASSEMIA(),$this->getApiScreeningField("THALASSEMIA"),$this->dropdown);

		$basicArr["SpecialCases"][OnClick][] =$this->getApiFormatArray("HIV","HIV+",$this->profile->getDecoratedHiv(),$this->profile->getHIV(),$this->getApiScreeningField("HIV"),$this->dropdown);


		
		return $basicArr;
		
	}
	
	//DPP Section
	
	/** @function
	 * @returns key value array of DPP Basic section
	 **/
	public function getApiDppBasicInfo() {
		$jpartnerObj=$this->profile->getJpartner();
		//Spouse Info
                $DppBasicArr["SPOUSE"][outerSectionName]="About My Partner";
                $DppBasicArr["SPOUSE"][outerSectionKey]="AboutMyPartner";
                $DppBasicArr["SPOUSE"][singleKey]=0;
                $DppBasicArr["SPOUSE"][OnClick][]= $this->getApiFormatArray("SPOUSE","About My Partner",trim($this->profile->getDecoratedSpouseInfo()),"",$this->getApiScreeningField("SPOUSE"),$this->textArea);
		//BasicDetails
                $DppBasicArr["BasicDetails"][outerSectionName]="Basic Details";
                $DppBasicArr["BasicDetails"][outerSectionKey]="Basic_Details";
                $DppBasicArr["BasicDetails"][singleKey]=0;
                //Height
		$szHeight = trim($jpartnerObj->getDecoratedLHEIGHT()) . " - " . trim($jpartnerObj->getDecoratedHHEIGHT());
		$szHeightVal = $jpartnerObj->getLHEIGHT().",".$jpartnerObj->getHHEIGHT();
		$DppBasicArr["BasicDetails"][OnClick][]= $this->getApiFormatArray("P_HEIGHT","Height",$szHeight,$szHeightVal,$this->getApiScreeningField("PARTNER_HEIGHT"),$this->dropdown,'','','dppHeight');
		//Age
		$szAge=trim($jpartnerObj->getDecoratedLAGE())." - ".trim($jpartnerObj->getDecoratedHAGE())." years of age";
		$szAgeVal = $jpartnerObj->getLAGE().','.$jpartnerObj->getHAGE();
		$DppBasicArr["BasicDetails"][OnClick][]= $this->getApiFormatArray("P_AGE","Age",$szAge,$szAgeVal,$this->getApiScreeningField("PARTNER_AGE"),$this->dropdown,'','','dppAge');
		//Marital Status
		$szMStatus = $this->getDecorateDPP_Response($jpartnerObj->getPARTNER_MSTATUS());
		$DppBasicArr["BasicDetails"][OnClick][]= $this->getApiFormatArray("P_MSTATUS","Marital Status",trim($jpartnerObj->getDecoratedPARTNER_MSTATUS()),$szMStatus,$this->getApiScreeningField("PARTNER_MSTATUS"),$this->dropdown,'',1,'dppMstatus');
                //Have Children
                $showHaveChild=1;
                if($jpartnerObj->getPARTNER_MSTATUS()=="'N'" || $jpartnerObj->getPARTNER_MSTATUS()=="")
                    $showHaveChild=0;
		$szChildren = $this->getDecorateDPP_Response($jpartnerObj->getCHILDREN());
		$DppBasicArr["BasicDetails"][OnClick][] = $this->getApiFormatArray("P_HAVECHILD","Have Children",trim($jpartnerObj->getDecoratedCHILDREN()),$szChildren,$this->getApiScreeningField("CHILDREN"),$this->dropdown,'',1,'',!$showHaveChild);
		//Country
		$szCountry = $this->getDecorateDPP_Response($jpartnerObj->getPARTNER_COUNTRYRES());
		

		$DppBasicArr["BasicDetails"][OnClick][] = $this->getApiFormatArray("P_COUNTRY","Country",trim($jpartnerObj->getDecoratedPARTNER_COUNTRYRES()),$szCountry,$this->getApiScreeningField("PARTNER_COUNTRYRES"),$this->dropdown,'',1,'dppCountry');

		$count_matches = SearchCommonFunctions::getMyDppMatches("",$this->profile,'',"",'',"","",1)["CNT"];

	    if ( !isset($count_matches))
	    {
	      $count_matches = 0;
	    }
		//City
		if(strpos($szCountry,"51")!==false || strpos($szCountry,"128")!==false)
			$showCity=1;
		$szCity = $this->getDecorateDPP_Response($jpartnerObj->getPARTNER_CITYRES());
		$szState = $this->getDecorateDPP_Response($jpartnerObj->getSTATE());
		$DppBasicArr["BasicDetails"][OnClick][]= $this->handleStateCityData($szState,$szCity,$showCity);
                $DppBasicArr["BasicDetails"][OnClick][] = $this->getApiFormatArray("P_MATCHCOUNT","","",(string)$count_matches,"","",'',1,"","Y");
		return $DppBasicArr;
	}

	/** @function
	 * @returns key value array of DPP Education and Occupation section
	 **/
	public function getApiDppEducationAndOcc() {
		$jpartnerObj=$this->profile->getJpartner();
		$DppBasicArr["EduAndOcc"][outerSectionName]="Education and Occupation";
                $DppBasicArr["EduAndOcc"][outerSectionKey]="EducationandOccupation";
                $DppBasicArr["EduAndOcc"][singleKey]=0;
                
		//Education
		$szEdu = $this->getDecorateDPP_Response($jpartnerObj->getPARTNER_ELEVEL_NEW());
		$p_edulevel=trim($jpartnerObj->getDecoratedPARTNER_ELEVEL_NEW());
		if($szEdu=="DM")
			$p_edulevel="Doesn't matter";
			
		$DppBasicArr["EduAndOcc"][OnClick][] = $this->getApiFormatArray("P_EDUCATION","Highest Degree",$p_edulevel,$szEdu,$this->getApiScreeningField("PARTNER_ELEVEL_NEW"),$this->dropdown,'',1);
		//Occupation
		$szOcc = $this->getDecorateDPP_Response($jpartnerObj->getPARTNER_OCC());
		$p_occlevel=trim($jpartnerObj->getDecoratedPARTNER_OCC());
		if($szEdu=="DM")
			$p_occlevel="Doesn't matter";
		
		$DppBasicArr["EduAndOcc"][OnClick][] = $this->getApiFormatArray("P_OCCUPATION","Occupation",$p_occlevel,$szOcc,$this->getApiScreeningField("PARTNER_OCC"),$this->dropdown,'',1);
		//Income
		$szIncomeRs = $this->getDecorateDPP_Response($jpartnerObj->getLINCOME());
		$szIncomeRs .= "," . $this->getDecorateDPP_Response($jpartnerObj->getHINCOME());
		$szIncomeDol =$this->getDecorateDPP_Response($jpartnerObj->getLINCOME_DOL());
		$szIncomeDol .= "," . $this->getDecorateDPP_Response($jpartnerObj->getHINCOME_DOL());
		$incLab=explode(", ",$jpartnerObj->getDecoratedPARTNER_INCOME());
		if(!$incLab[1])
			$incLab[1]="";
		if(!$incLab[0] || $incLab[0]=="-")
			$incLab[0]="";	
		
		$DppBasicArr["EduAndOcc"][OnClick][] = $this->getApiFormatArray("P_INCOME_RS","Income Rs",trim($incLab[0]),$szIncomeRs,$this->getApiScreeningField("PARTNER_INCOME"),$this->dropdown);
		
		$DppBasicArr["EduAndOcc"][OnClick][] = $this->getApiFormatArray("P_INCOME_DOL","Income $",trim($incLab[1]),$szIncomeDol,$this->getApiScreeningField("PARTNER_INCOME"),$this->dropdown);
		return $DppBasicArr;		
	}

	/** @function
	 * @returns key value array of Api DPP Religion and Ethnicity section
	 * */
	/** @function
	 * @returns key value array of Api DPP Religion and Ethnicity section
	 * */
	public function getApiDppReligionAndEth() {
		$jpartnerObj=$this->profile->getJpartner();
                $DppBasicArr["Religion"][outerSectionName]="Religion and Ethnicity";
                $DppBasicArr["Religion"][outerSectionKey]="ReligionandEthnicity";
                $DppBasicArr["Religion"][singleKey]=0;
                        $showSect=0;
                        $showCaste=0;
                        $relSect=0;$relCaste=0;
			$religion=explode(",",trim($jpartnerObj->getPARTNER_RELIGION()));
                        foreach ($religion as $value) {
                            if($value=="'2'" || $value=="'3'")
                                    $relSect++;
                            if($value=="'1'" || $value=="'4'" || $value=="'9'")
                                    $relCaste++;
                        }
			$szReligion= $jpartnerObj->getDecoratedPARTNER_RELIGION();
			if($relSect>0 && $relCaste==0)
				$showSect=1;
                        else if($relCaste>0)
                                $showCaste=1;
				
			$szCasteVal= $jpartnerObj->getDecoratedPARTNER_CASTE();
                        
		//Religion Info
		$szReligionVal = $this->getDecorateDPP_Response($jpartnerObj->getPARTNER_RELIGION());
		$DppBasicArr["Religion"][OnClick][]= $this->getApiFormatArray("P_RELIGION","Religion",trim($szReligion),$szReligionVal,$this->getApiScreeningField("PARTNER_RELIGION"),$this->dropdown,'',1,"dppReligion");
		//Caste
		$szCasteValues = $this->getDecorateDPP_Response($jpartnerObj->getPARTNER_CASTE());
		$DppBasicArr["Religion"][OnClick][]= $this->getApiFormatArray("P_CASTE","Caste",trim($szCasteVal),$szCasteValues,$this->getApiScreeningField("PARTNER_CASTE"),$this->dropdown,'',1,'dppCaste',!$showCaste);
		
		$DppBasicArr["Religion"][OnClick][]= $this->getApiFormatArray("P_SECT","Sect",trim($szCasteVal),$szCasteValues,$this->getApiScreeningField("PARTNER_CASTE"),$this->dropdown,'',1,'dppCaste',!$showSect);
		//Mother Tongue
		$szMTongue = $this->getDecorateDPP_Response($jpartnerObj->getPARTNER_MTONGUE());
			// check for doesn't matter else get small label for longer labels 
		   if($szMTongue=="DM")
		   {
			 	$community_small="Doesn't Matter";
		   }
		   else
           {
			        $MTongueArr=explode(",",$szMTongue);
                foreach ($MTongueArr as $val)
                    $community_small.=" ".FieldMap::getFieldLabel("community_small",$val).",";
                $community_small=rtrim($community_small, ",");
           }
		$DppBasicArr["Religion"][OnClick][] = $this->getApiFormatArray("P_MTONGUE","Mother Tongue",$community_small,$szMTongue,$this->getApiScreeningField("PARTNER_MTONGUE"),$this->dropdown,'',1);
		//Manglik
                $manglikReplaced = CommonFunction::setManglikWithoutDontKnow($jpartnerObj->getPARTNER_MANGLIK());
                $decManglikRemovedDontKnow = CommonFunction::setManglikWithoutDontKnow($jpartnerObj->getDecoratedPARTNER_MANGLIK());
		$szManglik = $this->getDecorateDPP_Response($manglikReplaced);
                
		$DppBasicArr["Religion"][OnClick][] = $this->getApiFormatArray("P_MANGLIK","Manglik",$decManglikRemovedDontKnow,$szManglik,$this->getApiScreeningField("PARTNER_MANGLIK"),$this->dropdown,'',1);
		return $DppBasicArr;		
	}
	
	/** @function
	 * @returns key value array of Api DPP Lifestyle and Attributes section
	 * */
	public function getApiDppLifeAttr() {
		$jpartnerObj=$this->profile->getJpartner();
		$DppBasicArr["Lifestyle"][outerSectionName]="Lifestyle and Appearance";
                $DppBasicArr["Lifestyle"][outerSectionKey]="LifestyleandAppearance";
                $DppBasicArr["Lifestyle"][singleKey]=0;	
		//Diet
		$szDiet= $this->getDecorateDPP_Response($jpartnerObj->getPARTNER_DIET());
                $szDietVal=trim($jpartnerObj->getDecoratedPARTNER_DIET());
                if($szDiet=="DM")
                    $szDietVal="Doesn't Matter";
		$DppBasicArr["Lifestyle"][OnClick][] = $this->getApiFormatArray("P_DIET","Diet",$szDietVal,$szDiet,$this->getApiScreeningField("PARTNER_DIET"),$this->dropdown,"",1);
		
		//Smoke
		$szSmoke= $this->getDecorateDPP_Response($jpartnerObj->getPARTNER_SMOKE());
                $szSmokeVal=trim($jpartnerObj->getDecoratedPARTNER_SMOKE());
                if($szSmoke=="DM")
                    $szSmokeVal="Doesn't Matter";
		$DppBasicArr["Lifestyle"][OnClick][] = $this->getApiFormatArray("P_SMOKE","Smoke",$szSmokeVal,$szSmoke,$this->getApiScreeningField("PARTNER_SMOKE"),$this->dropdown,"",1);
		
		//Drink
		$szDrink= $this->getDecorateDPP_Response($jpartnerObj->getPARTNER_DRINK());
                $szDrinkVal=trim($jpartnerObj->getDecoratedPARTNER_DRINK());
                if($szDrink=="DM")
                    $szDrinkVal="Doesn't Matter";
		$DppBasicArr["Lifestyle"][OnClick][] = $this->getApiFormatArray("P_DRINK","Drink",$szDrinkVal,$szDrink,$this->getApiScreeningField("PARTNER_DRINK"),$this->dropdown,"",1);
		
		//Complexion
		$szComlexion= $this->getDecorateDPP_Response($jpartnerObj->getPARTNER_COMP());
                $szComplexionVal=trim($jpartnerObj->getDecoratedPARTNER_COMP());
                if($szComlexion=="DM")
                    $szComplexionVal="Doesn't Matter";
		$DppBasicArr["Lifestyle"][OnClick][]= $this->getApiFormatArray("P_COMPLEXION","Complexion",$szComplexionVal,$szComlexion,$this->getApiScreeningField("PARTNER_COMP"),$this->dropdown,"",1);
		
		//Body Type
		$szBType = $this->getDecorateDPP_Response($jpartnerObj->getPARTNER_BTYPE());
                $szBtypeVal=trim($jpartnerObj->getDecoratedPARTNER_BTYPE());
                if($szBType=="DM")
                    $szBtypeVal="Doesn't Matter";
		$DppBasicArr["Lifestyle"][OnClick][] = $this->getApiFormatArray("P_BTYPE","Body Type",$szBtypeVal,$szBType,$this->getApiScreeningField("PARTNER_BTYPE"),$this->dropdown,"",1);
		
		//Challenged
                $szStr = $this->getDecorateDPP_Response($jpartnerObj->getHANDICAPPED());
		$szHandicapped=$jpartnerObj->getDecoratedHANDICAPPED();
                if($szStr=="DM")
                    $szHandicapped="Doesn't Matter";
		$DppBasicArr["Lifestyle"][OnClick][] = $this->getApiFormatArray("P_CHALLENGED","Challenged",trim($szHandicapped),$szStr,$this->getApiScreeningField("HANDICAPPED"),$this->dropdown,"",1,"updateDppChallenge");
		
		//Special check for partner handicap
		if(strstr($szHandicapped,'Physically Handicapped from birth') || strstr($szHandicapped,'Physically Handicapped due to accident'))
                        $showNHand=1;
                $szNHand = $this->getDecorateDPP_Response($jpartnerObj->getNHANDICAPPED());
                $szNHandVal=trim($jpartnerObj->getDecoratedNHANDICAPPED());
                if($szNHand=="DM")
                    $szNHandVal="Doesn't Matter";
                $DppBasicArr["Lifestyle"][OnClick][] = $this->getApiFormatArray("P_NCHALLENGED","Nature of Handicap",$szNHandVal,$szNHand,$this->getApiScreeningField("NHANDICAPPED"),$this->dropdown,"",1,"",!$showNHand);
		
		return $DppBasicArr;
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
	private function getDecorateDPP_Response($szInput)
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
		
		if((!$COUNTRY_RES) ||($COUNTRY_RES==51 && (!$CITY_RES)))
		{
			$incompleteArr[]=$this->getApiIncompleteFormatArray("COUNTRY_RES","Country living in",$COUNTRY_RES,"Y");
                        $stateValue = substr($this->profile->getCITY_RES(),0,2);
                        $incompleteArr[] =$this->getApiIncompleteFormatArray("STATE_RES","State Living in" ,$stateValue,"Y");
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
			$incompleteArr[]=$this->getApiIncompleteFormatArray("EDU_LEVEL_NEW","Highest Degree","","Y");
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
	public function getApiFormatArray($key,$label,$labelVal,$value,$screenBit,$action=0,$dependant="",$multi=0,$callBack="",$hidden="",$showSettings="",$settingData=array(),$verifyStatus="") {
	//	$arr["sectionName"]=$sectionName;
	//	$arr["sectionValue"]=$sectionValue;
		$arr["key"]=$key;
		$arr["label"]=$label;
		$arr["label_val"]=$labelVal;
		$arr["value"]=$value;
		$arr["screenBit"]=$screenBit;
		$arr["action"]=$action;
		$arr["dependant"]=$dependant;
		$arr["multi"]=$multi;
		$arr["callBack"]=$callBack;
		$arr["hidden"]=$hidden;
		$arr["showSettings"]=$showSettings;
		$arr["settingData"]=$settingData;
		$arr["verifyStatus"]=$verifyStatus;
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

	/** @function
	 * @returns state and city array
	 * @param $stateVal String
	 * @param $cityVal String
	 * */
	public function handleStateCityData($stateVal,$cityVal,$showCity='')
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
    		$stateNames = trim($jpartnerObj->getDecoratedSTATE());
    		$cityNames = trim($jpartnerObj->getDecoratedPARTNER_CITYRES());
    		$stateCityNames = $stateNames.",".$cityNames;
    	}
    	$stateCityArr = $this->getApiFormatArray("P_CITY","State/City",$stateCityNames,$szStateCity,$this->getApiScreeningField("PARTNER_CITYRES"),$this->dropdown,'',1,'',!$showCity);
    	return($stateCityArr);
    }

    public function getVerificationStatusForAltEmailAndMail($EmailStatus)
    {    
    	if($EmailStatus == "Y")
    		return 1;
    	else
    		return 0;
    }
}
?>
