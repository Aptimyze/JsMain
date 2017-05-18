<?php

/**
 * ApiProfileSectionsDesktop 
 * 
 * @package Jeevansathi
 * @subpackage Profile 
 * @author Kunal Verma
 * @created 06th Nov 2015
 */


class ApiProfileSectionsDesktop extends ApiProfileSectionsApp{
	protected $Docs;
  /*
   * Declaring and Defining Member Function
   */
  /*
   * Constructor
   */
  public function __construct($profile,$isEdit='') {
    parent::__construct($profile,$isEdit);
        $this->verifyDocsObj = new ProfileDocumentVerificationByUserService();
        $this->Docs = $this->verifyDocsObj->getDocumentsList($profile->getPROFILEID());       
  }
  
  public function getApiCriticalInfo(){
    $criricalArr = parent::getApiCriticalInfo();
    return $criricalArr;
  }
  public function getApiBasicInfo(){
    $basicArr = parent::getApiBasicInfo();
    
    //Native Place
		$nativePlaceObj = new JProfile_NativePlace($this->profile);
		$nativePlaceObj->getInfo();
    $szNativeState = FieldMap::getFieldLabel("state_india", $nativePlaceObj->getNativeState());
    $szNativeCity = FieldMap::getFieldLabel("city", $nativePlaceObj->getNativeCity());
    $szNativeCountry = FieldMap::getFieldLabel("country", $nativePlaceObj->getNativeCountry());
    $basicArr[] =$this->getApiFormatArray("NATIVE_STATE","Family based out of" ,$szNativeState,$nativePlaceObj->getNativeState(),$this->getApiScreeningField("NATIVE_STATE"));
    $basicArr[] =$this->getApiFormatArray("NATIVE_CITY","Select City" ,$szNativeCity,$nativePlaceObj->getNativeCity(),$this->getApiScreeningField("NATIVE_CITY"));
    $basicArr[] =$this->getApiFormatArray("NATIVE_COUNTRY","Family based out of" ,$szNativeCountry,$nativePlaceObj->getNativeCountry(),$this->getApiScreeningField("NATIVE_COUNTRY"));
    
    //Sect Section
    $religion = $this->profile->getReligion();
    if($religion==RELIGION::HINDU || $religion==Religion::SIKH )
			$basicArr[]  =$this->getApiFormatArray("SECT","Sect" ,$this->profile->getDecoratedSect(),$this->profile->getSECT(),$this->getApiScreeningField("SECT"));
		elseif($religion== Religion::CHRISTIAN || $religion==Religion::MUSLIM)
			$basicArr[]  =$this->getApiFormatArray("SECT","Caste" ,$this->profile->getDecoratedSect(),$this->profile->getSECT(),$this->getApiScreeningField("SECT"));
           
                
    //state
        $stateVal = substr($this->profile->getCITY_RES(),0,2);
        $basicArr[] =$this->getApiFormatArray("STATE_RES","State Living in" ,"",$stateVal,$this->getApiScreeningField("COUNTRY_RES"));
    
    $value='';
    if($this->profile->getCITY_RES()!='0'){
    if($this->profile->getCITY_RES())
    {
            if(substr($this->profile->getCITY_RES(),2)=="OT")
                    $city = "0";
            else
                    $city = $this->profile->getCITY_RES();
            $value= $city;
            $label = FieldMap::getFieldLabel("city",$city);
    }
        $basicArr[] =$this->getApiFormatArray("CITY_RES","City Living in" ,$label,$value,$this->getApiScreeningField("CITY_RES"));
    }
    //gothra_maternal 
    if($religion==RELIGION::HINDU )
      $basicArr[] =$this->getApiFormatArray("GOTHRA_MATERNAL","Gothra (maternal)" ,$this->profile->getDecoratedGothraMaternal(),$this->profile->getGOTHRA_MATERNAL(),$this->getApiScreeningField("GOTHRA_MATERNAL"));
    
    //Cover Photo
    $basicArr[]= $this->getApiFormatArray("COVER","Cover Photo",$this->getCoverPhotoFromLib($this->profile->getPROFILEID()),$this->getCoverPhotoFromLib($this->profile->getPROFILEID()));

    return $basicArr;
  }
  
  public function getApiLifeAttr(){
    $apiLifeAttrArr = parent::getApiLifeAttr();
    
    //Residential Status
    $apiLifeAttrArr[]=$this->getApiFormatArray("RES_STATUS","Residential Status", $this->profile->getDecoratedRstatus(),$this->profile->getRES_STATUS(),$this->getApiScreeningField("RES_STATUS"));
    
    
    //Blood Group
    $apiLifeAttrArr[]=$this->getApiFormatArray("BLOOD_GROUP","Blood Group", $this->profile->getDecoratedBloodGroup(),$this->profile->getBLOOD_GROUP(),$this->getApiScreeningField("BLOOD_GROUP"));
    
    //Hobbie Movie
    $apiLifeAttrArr[]=$this->getApiFormatArray("HOBBIES_MOVIE","Favourite Movies",$this->Hobbies[MOVIE][LABEL],$this->Hobbies[MOVIE][VALUE],$this->getApiScreeningField("HOBBIES_MOVIE"));
    
    return $apiLifeAttrArr;
  }
  
  protected function getProfileHandicappedInfo(&$basicArr){
    
    $basicArr[] =$this->getApiFormatArray("HANDICAPPED","Challenged" ,$this->profile->getDecoratedHandicapped(),$this->profile->getHANDICAPPED(),$this->getApiScreeningField("HANDICAPPED"));
		
		$basicArr[] =$this->getApiFormatArray("NATURE_HANDICAP","Nature of Handicap" ,$this->profile->getDecoratedNatureHandicap(),$this->profile->getNATURE_HANDICAP(),$this->getApiScreeningField("NATURE_HANDICAP"));
	
  }
  
  protected function getSikhProfileInfo(&$basicArr,$relinfo,$relinfo_values){
    
    $basicArr[] = $this->getApiFormatArray("AMRITDHARI", "Are you Amritdhari?", $relinfo[AMRITDHARI], $relinfo_values[AMRITDHARI], $this->getApiScreeningField("AMRITDHARI"));

    $basicArr[] = $this->getApiFormatArray("CUT_HAIR", "Do you cut your hair?", $relinfo[CUT_HAIR], $relinfo_values[CUT_HAIR], $this->getApiScreeningField("CUT_HAIR"));

    if ($this->profile->getGender() == "M") {

      $basicArr[] = $this->getApiFormatArray("TRIM_BEARD", "Do you trim your beard?", $relinfo[TRIM_BEARD], $relinfo_values[TRIM_BEARD], $this->getApiScreeningField("TRIM_BEARD"));

      $basicArr[] = $this->getApiFormatArray("WEAR_TURBAN", "Do you wear turban?", $relinfo[WEAR_TURBAN], $relinfo_values[WEAR_TURBAN], $this->getApiScreeningField("WEAR_TURBAN"));

      $basicArr[] = $this->getApiFormatArray("CLEAN_SHAVEN", "Are you clean shaven?", $relinfo[CLEAN_SHAVEN], $relinfo_values[CLEAN_SHAVEN], $this->getApiScreeningField("CLEAN_SHAVEN"));
      
    }
  }
  
  public function getApiContactInfo() {
    $contactArr = parent::getApiContactInfo();
    $extendedContactArray = $this->profile->getExtendedContacts("onlyValues");
    $extendedContactObj   = $this->profile->getExtendedContacts();
    //Mobile Number Owner Name
    $contactArr[]=$this->getApiFormatArray("MOBILE_OWNER_NAME","Name of owner" ,$this->profile->getMOBILE_OWNER_NAME(),$this->profile->getMOBILE_OWNER_NAME(),$this->getApiScreeningField("MOBILE_OWNER_NAME"));

    
    //Landline Number Owner Name
    $contactArr[]=$this->getApiFormatArray("PHONE_OWNER_NAME","Name of owner" ,$this->profile->getPHONE_OWNER_NAME(),$this->profile->getPHONE_OWNER_NAME(),$this->getApiScreeningField("PHONE_OWNER_NAME"));
    
    //Alt Number Owner Name
    $contactArr[]=$this->getApiFormatArray("ALT_MOBILE_OWNER_NAME","Name of owner" ,$extendedContactArray['ALT_MOBILE_OWNER_NAME'],$extendedContactArray['ALT_MOBILE_OWNER_NAME'],$this->getApiScreeningField("ALT_MOBILE_OWNER_NAME"));
    
    //Mobile Number Owner
    $contactArr[]=$this->getApiFormatArray("MOBILE_NUMBER_OWNER","Relationship" ,$this->profile->getDecoratedMobileNumberOwner(),$this->profile->getMOBILE_NUMBER_OWNER(),$this->getApiScreeningField("MOBILE_NUMBER_OWNER"));
    
    //Landline Number Owner Name
    $contactArr[]=$this->getApiFormatArray("PHONE_NUMBER_OWNER","Relationship" ,$this->profile->getDecoratedLandlineNumberOwner(),$this->profile->getPHONE_NUMBER_OWNER(),$this->getApiScreeningField("PHONE_NUMBER_OWNER"));
    
    //Alt Number Owner Name
    $contactArr[]=$this->getApiFormatArray("ALT_MOBILE_NUMBER_OWNER","Relationship",$extendedContactObj->ALT_MOBILE_NUMBER_OWNER,$extendedContactArray['ALT_MOBILE_NUMBER_OWNER'],$this->getApiScreeningField("ALT_MOBILE_NUMBER_OWNER"));
    
    //Contact Address
    $contactArr[]=$this->getApiFormatArray("CONTACT","Contact Address" ,$this->profile->getCONTACT(),$this->profile->getCONTACT(),$this->getApiScreeningField("CONTACT"));
    
    //Parents Contact Address
    $contactArr[]=$this->getApiFormatArray("PARENTS_CONTACT","Parent's Address" ,$this->profile->getPARENTS_CONTACT(),$this->profile->getPARENTS_CONTACT(),$this->getApiScreeningField("PARENTS_CONTACT"));
    
    //Show Address
    $contactArr[]=$this->getApiFormatArray("SHOWADDRESS","Contact Address" ,$this->profile->getSHOWADDRESS(),$this->profile->getSHOWADDRESS(),$this->getApiScreeningField("SHOWADDRESS"));
    
    //Show Parents Contact Address
    $contactArr[]=$this->getApiFormatArray("SHOW_PARENTS_CONTACT","" ,$this->profile->getSHOW_PARENTS_CONTACT(),$this->profile->getSHOW_PARENTS_CONTACT(),$this->getApiScreeningField("SHOW_PARENTS_CONTACT"));
    
    if($this->profile->getCOUNTRY_RES() == "51"){
      //Pincode
      $contactArr[]=$this->getApiFormatArray("PINCODE","Pin Code" ,$this->profile->getPINCODE(),$this->profile->getPINCODE(),$this->getApiScreeningField("PINCODE"));

      //Parent Pincode
      $contactArr[]=$this->getApiFormatArray("PARENT_PINCODE","Pin Code" ,$this->profile->getPARENT_PINCODE(),$this->profile->getPARENT_PINCODE(),$this->getApiScreeningField("PARENT_PINCODE"));
    }else{
      //Pincode
      $contactArr[]=$this->getApiFormatArray("PINCODE","Pin Code" ,"","",$this->getApiScreeningField("PINCODE"));

      //Parent Pincode
      $contactArr[]=$this->getApiFormatArray("PARENT_PINCODE","Pin Code" ,"","",$this->getApiScreeningField("PARENT_PINCODE"));
    }
    
    //$contactArr[]=$this->getApiFormatArray("ID_PROOF_TYP","ID type" ,$this->profile->getDecoratedID_PROOF_TYP(),$this->profile->getID_PROOF_TYP(),$this->getApiScreeningField("ID_PROOF_TYP"));
    
    //$contactArr[]=$this->getApiFormatArray("ID_PROOF_NO","ID Number" ,$this->profile->getID_PROOF_NO(),$this->profile->getID_PROOF_NO(),$this->getApiScreeningField("ID_PROOF_NO"));
    
    $contactArr[]=$this->getApiFormatArray("ID_PROOF_TYPE","ID Proof",$this->verifyDocsObj->getDecoratedProof("id_proof_type",$this->Docs['ID']['PROOF_TYPE']),$this->Docs['ID']['PROOF_TYPE'],$this->getApiScreeningField("ID_PROOF_TYP"));
    
    $contactArr[]=$this->getApiFormatArray("ADDR_PROOF_TYPE","Address Proof",$this->verifyDocsObj->getDecoratedProof("addr_proof_type",$this->Docs['ADDR']['PROOF_TYPE']),$this->Docs['ADDR']['PROOF_TYPE'],$this->getApiScreeningField("ADDR_PROOF_TYPE"));
    $contactArr[]=$this->getApiFormatArray("ID_PROOF_VAL","" ,$this->Docs['ID']['PROOF_VAL'],$this->Docs['ID']['PROOF_VAL'],$this->getApiScreeningField("ADDR_PROOF_TYPE"));
    $contactArr[]=$this->getApiFormatArray("ADDR_PROOF_VAL","" ,$this->Docs['ADDR']['PROOF_VAL'],$this->Docs['ADDR']['PROOF_VAL'],$this->getApiScreeningField("ADDR_PROOF_TYPE"));
    
    return $contactArr;
  }
  protected  function getAncestralOrigin(&$basicArr){	
		//native or Family based out of
      $basicArr[] =$this->getApiFormatArray("ANCESTRAL_ORIGIN"," Please specify" ,$this->profile->getDecoratedAncestralOrigin(),$this->profile->getANCESTRAL_ORIGIN(),$this->getApiScreeningField("ANCESTRAL_ORIGIN"));
    }
  
  protected function addSunSign(&$astro,$AstroKundali){
     $astro[]=$this->getApiFormatArray("SUNSIGN","Sun Sign" , $AstroKundali->sunsign,$this->profile->getSUNSIGN(),$this->getApiScreeningField("SUNSIGN"));
  }
  
  public function getApiAstroKundali() {
    $astro = parent::getApiAstroKundali();
    
    $astro[]=$this->getApiFormatArray("ASTRO_PRIVACY","Horoscope Privacy" , $this->profile->getDecoratedSHOW_HOROSCOPE(),$this->profile->getSHOW_HOROSCOPE());
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
      
      $eduArr[]=$this->getApiFormatArray("OTHER_PG_DEGREE","Other PG Degree",$education["OTHER_PG_DEGREE"],$educationValues['OTHER_PG_DEGREE'],$this->getApiScreeningField("OTHER_PG_DEGREE"));
		}
		else
		{
			$eduArr[]=$this->getApiFormatArray("DEGREE_PG","PG Degree","","",$this->getApiScreeningField("DEGREE_PG"));
			$eduArr[]=$this->getApiFormatArray("PG_COLLEGE","PG College","","",$this->getApiScreeningField("PG_COLLEGE"));
      $eduArr[]=$this->getApiFormatArray("OTHER_PG_DEGREE","Other PG Degree","","",$this->getApiScreeningField("OTHER_PG_DEGREE"));
		}
		//highest degree should not be high school or trade school
		if(!($this->profile->getEDU_LEVEL_NEW()=="23" ||$this->profile->getEDU_LEVEL_NEW()=="24"))
		{
			$eduArr[]=$this->getApiFormatArray("DEGREE_UG","UG Degree" , FieldMap::getFieldLabel("degree_ug",$education['UG_DEGREE']),$educationValues[UG_DEGREE],$this->getApiScreeningField("DEGREE_UG"));
		
			$eduArr[]=$this->getApiFormatArray("COLLEGE","UG College" , $education["COLLEGE"],$educationValues['COLLEGE'],$this->getApiScreeningField("COLLEGE"));
      
      $eduArr[]=$this->getApiFormatArray("OTHER_UG_DEGREE","Other UG Degree",$education["OTHER_UG_DEGREE"],$educationValues['OTHER_UG_DEGREE'],$this->getApiScreeningField("OTHER_UG_DEGREE"));
		}
		else
		{
			$eduArr[]=$this->getApiFormatArray("DEGREE_UG","UG Degree","","",$this->getApiScreeningField("DEGREE_UG"));		
			$eduArr[]=$this->getApiFormatArray("COLLEGE","UG College","","",$this->getApiScreeningField("COLLEGE"));
      $eduArr[]=$this->getApiFormatArray("OTHER_UG_DEGREE","Other UG Degree","","",$this->getApiScreeningField("OTHER_PG_DEGREE"));
		}
		$eduArr[]=$this->getApiFormatArray("SCHOOL","School Name" , $education["SCHOOL"],$educationValues['SCHOOL'],$this->getApiScreeningField("SCHOOL"));

		return $eduArr;
	}
  
  public function getApiOccupation() {
    $occArr = parent::getApiOccupation();
    $occArr[]=$this->getApiFormatArray("WORK_STATUS","Work Status" , $this->profile->getDecoratedWorkStatus(),$this->profile->getWORK_STATUS(),$this->getApiScreeningField("WORK_STATUS"));
    return $occArr;
  }
  
  public function getApiDppBasicInfo() {
    $arrOut = parent::getApiDppBasicInfo();
    $jpartnerObj=$this->profile->getJpartner();
    //Have Child
    $szHaveChild = $this->getDecorateDPP_Response($jpartnerObj->getCHILDREN());
    $szDecordateHaveChild = $this->getPartnerChildren($szHaveChild);
    $arrOut[] = $this->getApiFormatArray("P_HAVECHILD","Have Children",trim($szDecordateHaveChild),$szHaveChild,$this->getApiScreeningField("PARTNER_HAVECHILD"));
  

    
    return $arrOut;
  }
}
