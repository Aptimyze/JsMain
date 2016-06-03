<?php
/**
 * @class ProfileSections
 * Demarcates different sections of view profile and edit profile page
 * Will be used for view profile page and my profile page.
 * */
class ProfileSections {
	private $profile;
	private $Hobbies;
	private $isEdit;
	function __construct($profile,$isEdit='') {
		$this->profile = $profile;
		$this->Hobbies = $this->profile->getHobbies();
		$this->isEdit=$isEdit;
	}
	/** @function
	 * @returns key value array of Life style section
	 * */
	public function getLifeAttr() {
		$challenged=$this->profile->getHANDICAPPED();
		$lifeAttrArr = array("Diet" => $this->profile->getDecoratedDiet(), "Smoke" => $this->profile->getDecoratedSmoke(), "Drink" => $this->profile->getDecoratedDrink(), "Complexion" => $this->profile->getDecoratedComplexion(), "Body Type" => $this->profile->getDecoratedBodytype(), "Challenged" => $this->profile->getDecoratedHandicapped(), "Nature of Handicap" => $this->profile->getDecoratedNatureHandicap(), "Blood Group" => $this->profile->getDecoratedBloodGroup(), "Weight" => $this->profile->getDecoratedWeight(), "Thalassemia" => $this->profile->getDecoratedThalassemia(), "Residential Status" => $this->profile->getDecoratedRstatus(), "Own House" => $this->profile->getDecoratedOwnHouse(), "Own Car" => $this->profile->getDecoratedHaveCar(), "Spoken language" => $this->Hobbies->LANGUAGE, "Open to pets" => $this->profile->getDecoratedOpenToPet(), "HIV +" => $this->profile->getDecoratedHiv(),);
		if ($challenged != 1 && $challenged != 2) unset($lifeAttrArr["Nature of Handicap"]);
		//New Text for new fields addition
		if($this->isEdit){
			if(sfConfig::get("app_myprofile_new_on")){
				$new_fields=array(
					"Thalassemia"=>1,
					"Own House"=>1,
					"Own Car" =>1,
					"Open to pets"=>1
				);
				$lifeAttrArr['NewFields']=$new_fields;
			}
		}
		return $lifeAttrArr;
	}
	/** @function
	 * @returns key value array of Education and occupation section
	 * */
	public function getEducationAndOcc() {
		$education = $this->profile->getEducationDetail();
		$eduOccArr = array("Name of School" => $education->SCHOOL, "Name of College" => $education->COLLEGE, "Graduation Degree" => $education->UG_DEGREE, "Other Graduation Degree" => $education->OTHER_UG_DEGREE, "PG College" => $education->PG_COLLEGE, "PG Degree" => $education->PG_DEGREE, "Other PG Degree" => $education->OTHER_PG_DEGREE, "Highest Degree" => $this->profile->getDecoratedEducation(), "Work Status" => $this->profile->getDecoratedWorkStatus(), "Occupation" => $this->profile->getDecoratedOccupation(), "Name of Organization" => $this->profile->getDecoratedCompany(), "Annual Income" => $this->profile->getDecoratedIncomeLevel(), "Interested in settling abroad?" => $this->profile->getDecoratedSettlingAbroad(),);
		if ($this->profile->getGENDER() == "F") $eduOccArr["Plan to work after marriage?"] = $this->profile->getDecoratedCareerAfterMarriage();
		if ($this->isEdit) {
			if (!$this->profile->getEDU_LEVEL_NEW()) $RedLabels["Highest Degree"] = 1;
			if (!$this->profile->getOCCUPATION()) $RedLabels["Occupation"] = 1;
			if (!$this->profile->getINCOME()) $RedLabels["Annual Income"] = 1;
			$eduOccArr["RedLabels"] = $RedLabels;
			if(sfConfig::get("app_myprofile_new_on")){
				$new_fields=array(
					"Name of School"=>1,
					"Name of College"=>1,
					"Graduation Degree" =>1,
					"Other Graduation Degree" =>1,
					"PG College" =>1,
					"PG Degree" =>1,
					"Other PG Degree" =>1,
					"Name of Organization" =>1,
					"Interested in settling abroad?" =>1,
				);
				$eduOccArr['NewFields']=$new_fields;
			}
		}
		return $eduOccArr;
	}
	/** @function
	 * @returns key value array of Family details section
	 * */
	public function getFamilyDetails() {
		$siblings = $this->profile->getSiblings();
		if($siblings->tbrother !== ''){
			$brother = $siblings->tbrother . " brother";
			if ($siblings->tbrother > 1) $brother.= "s";
			if ($siblings->mbrother!=='' && $siblings->tbrother !=0) $brother.= " <span class=\"gray\">of which married</span> " . $siblings->mbrother;
		}
		else
			$brother="-";
		if($siblings->tsister !==''){
			$sister = $siblings->tsister . " sister";
			if ($siblings->tsister > 1) $sister.= "s";
			if ($siblings->msister!=='' && $siblings->tsister!=0) $sister.= " <span class=\"gray\">of which married</span> " . $siblings->msister;
		}
		else
			$sister="-";
		$familyArr = array("Family Values" => $this->profile->getDecoratedFamilyValues(), "Family Type" => $this->profile->getDecoratedFamilyType(), "Family Status" => $this->profile->getDecoratedFamilyStatus(), "Family Income" => $this->profile->getDecoratedFamilyIncome(), "Father" => $this->profile->getDecoratedFamilyBackground(), "Mother" => $this->profile->getDecoratedMotherOccupation(), "Brother(s)" => $brother, "Sisters(s)" => $sister, "Living with Parents" => $this->profile->getDecoratedLiveWithParents(), "Name of person handling profile" => $this->profile->getDecoratedPersonHandlingProfile(),);

		if($this->isEdit){
			if(sfConfig::get("app_myprofile_new_on")){
				$new_fields=array(
					"Family Income"=>1,
					"Name of person handling profile"=>1,
				);
				$familyArr['NewFields']=$new_fields;
			}
		}
		
		return $familyArr;
	}
	/** @function
	 * @returns key value array of Astro section
	 * */
	public function getAstroKundali() {
		$AstroKundali = $this->profile->getAstroKundali();
		$astro = array("Country of Birth" => $this->profile->getDecoratedBirthCountry(), "City of Birth" => $this->profile->getDecoratedBirthCity(), "Date of Birth" => $AstroKundali->dateOfBirth, "Time of Birth" => $AstroKundali->birthTime,);
		if(MobileCommon::isMobile())
		{
			$astro["Manglik"] = $this->profile->getDecoratedManglik();
			$astro["Sun Sign"] = $AstroKundali->sunsign;
			$astro["Rashi"] = $AstroKundali->rashi;
		}
		else
		{
			$astro["Manglik/Chevvai Dosham"] = $this->profile->getDecoratedManglik();
			$astro["Sun Sign"] = $AstroKundali->sunsign;
			$astro["Rashi/ Moon Sign"] = $AstroKundali->rashi;
		}
		$astro["Nakshatra"] = $AstroKundali->nakshatra;
		$astro["Horoscope Match needed"] = $this->profile->getDecoratedHoroscopeMatch();
		if($this->isEdit){
			if(sfConfig::get("app_myprofile_new_on")){
				$new_fields=array(
					"Sun Sign"=>1,
				);
				$astro['NewFields']=$new_fields;
			}
		}
		return $astro;
	}
	/** @function
	 * @returns key value array of hobbies section
	 * */
	public function getHobbies() {
		$hobArray = array("Hobbies" => $this->Hobbies->HOBBY, "Interests" => $this->Hobbies->INTEREST, "Favourite Music" => $this->Hobbies->MUSIC, "Favourite Read" => $this->Hobbies->BOOK, "Favourite Books" => $this->Hobbies->FAV_BOOK, "Dress Style" => $this->Hobbies->DRESS, "Favourite TV Shows" => $this->Hobbies->FAV_TVSHOW, "Preferred Movies" => $this->Hobbies->MOVIE, "Favourite Movies" => $this->Hobbies->FAV_MOVIE, "Sports/ Fitness" => $this->Hobbies->SPORTS, "Favourite Cuisine" => $this->Hobbies->CUISINE, "Food I Cook" => $this->Hobbies->FAV_FOOD, "Favourite Vacation Destination" => $this->Hobbies->FAV_VAC_DEST,);
		if($this->isEdit){
			if(sfConfig::get("app_myprofile_new_on")){
				$new_fields=array(
					"Favourite Books"=>1,
					"Favourite TV Shows" =>1,
					"Favourite Movies" =>1,
					"Food I Cook" => 1,
					"Favourite Vacation Destination" =>1,
				);
				$hobArray['NewFields']=$new_fields;
			}
		}
		return $hobArray;
	}
	/** @function
	 * @returns key value array of Religion and Ethnicity section
	 * */
	public function getRelgionAndEthnicity($casteLabel, $sectLabel) {
		$relinfo = (array)$this->profile->getReligionInfo();
		$religion = $this->profile->getRELIGION();
		$arr["Religion"] = $this->profile->getDecoratedReligion();
		$arr["Mother Tongue"] = $this->profile->getDecoratedCommunity();
		$arr[$casteLabel] = $this->profile->getDecoratedCaste();
		$arr[$sectLabel] = $this->profile->getDecoratedSect();
		$nativePlaceObj = new JProfile_NativePlace($this->profile);
		$nativePlaceObj->getInfo();		
		$arr["Family based out of"] = $nativePlaceObj->getDecorated_ViewField();
		if ($religion == Religion::HINDU) {
			$new_fields['Sect']=1;
			$new_fields['Gothra (Maternal)']=1;
			$arr["Sub Caste"] = $this->profile->getDecoratedSubcaste();
			$arr["Gothra"] = $this->profile->getDecoratedGothra();
			$arr["Gothra (Maternal)"] = $this->profile->getDecoratedGothraMaternal();
		} elseif ($religion == Religion::CHRISTIAN) //Christian
		{
			unset($arr[Caste]);
			$arr["Diocese"] = $relinfo[DIOCESE];
			$arr["Baptised"] = $relinfo[BAPTISED];
			$arr["Do You Read Bible Everyday?"] = $relinfo[READ_BIBLE];
			$arr["Do You Offer Tithe Regularly?"] = $relinfo[OFFER_TITHE];
			$arr["Interested in spreading the Gospel?"] = $relinfo[SPREADING_GOSPEL];
		} elseif ($religion == 2) //Muslim
		{
			$new_fields['Caste']=1;
			$arr["Mathab"] = $relinfo["MATHTHAB"];
			$arr["Speak Urdu"] = $relinfo[SPEAK_URDU];
			$arr["Namaz"] = $relinfo[NAMAZ];
			$arr["Zakat"] = $relinfo[ZAKAT];
			$arr["Fasting"] = $relinfo[FASTING];
			$arr["Umrah/Hajj"] = $relinfo[UMRAH_HAJJ];
			$arr["Do You Read The Quran?"] = $relinfo[QURAN];
			if ($this->profile->getGender() == "M") {
				$arr["Sunnah Beard"] = $relinfo[SUNNAH_BEARD];
				$arr["Sunnah Cap"] = $relinfo[SUNNAH_CAP];
				$arr["Hijab"] = $relinfo[HIJAB];
				$arr["Can the Girl Work After Marriage?"] = $relinfo[WORKING_MARRIAGE];
			} else {
				$arr["Hijab after marriage"] = $relinfo[HIJAB_MARRIAGE];
			}
		} elseif ($religion == 5) //parsi
		{
			unset($arr[Caste]);
			unset($arr[Sect]);
			$arr["Are You a Zarathushtri?"] = $relinfo[ZARATHUSHTRI];
			$arr["Are Both Parents Zarathushtri?"] = $relinfo[PARENTS_ZARATHUSHTRI];
		} elseif ($religion == 4) //Sikh
		{
			$new_fields['Sect']=1;
			$arr["Gothra"] = $this->profile->getDecoratedGothra();
			$arr["Are You Amritdhari?"] = $relinfo[AMRITDHARI];
			if ($relinfo[AMRITDHARI] == "No") {
				$arr["Do You Cut Your Hair?"] = $relinfo[CUT_HAIR];
				if ($this->profile->getGender() == "M") {
					$arr["Do You Trim Your Beard?"] = $relinfo[TRIM_BEARD];
					$arr["Do You Wear Turban?"] = $relinfo[WEAR_TURBAN];
					$arr["Are You Clean Shaven?"] = $relinfo[CLEAN_SHAVEN];
				}
			}
		} elseif ($religion == 9) //Jain
		{
			if ($this->profile->getDecoratedCaste() == 'Jain: Shwetamber') $arr["Sampraday"] = $relinfo[0];
			$new_fields['Sect']=1;
			$arr["Gothra"] = $this->profile->getDecoratedGothra();
		} elseif ($religion == Religion::BUDDHIST){
			unset($arr[Caste]);
			$new_fields['Sect']=1;
			$arr["Gothra"] = $this->profile->getDecoratedGothra();
		}else {
			unset($arr[Caste]);
			unset($arr[Sect]);
		}
		if ($this->isEdit) {
			if (!$this->profile->getRELIGION()) $RedLabels['Religion'] = 1;
			if (!$this->profile->getCASTE()) $RedLabels['Caste'] = 1;
			if (!$this->profile->getMTONGUE()) $RedLabels['Mother Tongue'] = 1;
			$arr['RedLabels'] = $RedLabels;
			if(sfConfig::get("app_myprofile_new_on")){
				if(!is_array($new_fields))
					$new_fields=array();
				$arr['NewFields']=$new_fields;
			}
		}
		return $arr;
	}
	/** @function
	 * @returns key value array of DPP Education and Occupation section
	 * */
	public function getDppEducationAndOcc() {
		$jpartnerObj=$this->profile->getJpartner();
		$arr["Education Level"]=$jpartnerObj->getDecoratedPARTNER_ELEVEL_NEW();
		$arr["Occupation"]=$jpartnerObj->getDecoratedPARTNER_OCC();
		$arr["Income"]=$jpartnerObj->getDecoratedPARTNER_INCOME();
		
		return $arr;		
	}
	/** @function
	 * @returns key value array of DPP Religion and Ethnicity section
	 * */
	public function getDppReligionAndEth() {
		$jpartnerObj=$this->profile->getJpartner();
		$sectArr=array(
			"Muslim","Christian"
		);
		$arr["Religion"]=$jpartnerObj->getDecoratedPARTNER_RELIGION();
		if(in_array($arr["Religion"],$sectArr))
			$arr["Sect"]=$jpartnerObj->getDecoratedPARTNER_CASTE();
		else
			$arr["Caste"]=$jpartnerObj->getDecoratedPARTNER_CASTE();
		
		$arr["Mother tongue"]=$jpartnerObj->getDecoratedPARTNER_MTONGUE();
		$arr["Manglik"]=$jpartnerObj->getDecoratedPARTNER_MANGLIK();
		return $arr;		
	}
	
	/** @function
	 * @returns key value array of DPP Lifestyle and Attributes section
	 * */
	public function getDppLifeAttr() {
		$jpartnerObj=$this->profile->getJpartner();
		
		$arr["Diet"]=$jpartnerObj->getDecoratedPARTNER_DIET();
		
			$arr["Smoke"]=$jpartnerObj->getDecoratedPARTNER_SMOKE();
		
		$arr["Drink"]=$jpartnerObj->getDecoratedPARTNER_DRINK();
		$arr["Complexion"]=$jpartnerObj->getDecoratedPARTNER_COMP();
		
		$arr["Body Type"]=$jpartnerObj->getDecoratedPARTNER_BTYPE();
		$arr["Challenged"]=$jpartnerObj->getDecoratedHANDICAPPED();
		//Special check for partner handicap
		$ph_fstr=$arr["Challenged"];
		if(strstr($ph_fstr,'Physically Handicapped from birth')||strstr($ph_fstr,'Physically Handicapped due to accident'))
			$arr["Nature of Handicap"]=$jpartnerObj->getDecoratedNHANDICAPPED();
           
		return $arr;		
	}
}
?>
