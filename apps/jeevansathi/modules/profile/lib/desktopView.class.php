<?php
 
/**
 * Class desktopView Used For Decorating Response for profile in JSPC
 * This class extends DetailedViewApi class
 * @package    jeevansathi
 * @subpackage profile
 * @author     Ankit Shukla
 */
class desktopView extends DetailedViewApi
{
  /**
   * This Variable holds status of Response of edit Page
   * @access private
   * @var Object
   */
  private $bResponseForEditView = false;
/**
 * Constructor function
 * @param $actionObject
 * @return void
 * @access public
 */
  public function __construct($actionObject)
  {
    parent::__construct($actionObject);
  }
/**
* function to decorate primary info values in hobbies
* @param void
* @return void 
* @access protected
*/
  protected function getDecorated_PrimaryInfo(){
    $viewerProfile = $this->m_actionObject->loginProfile->getPROFILEID();
    $viewedProfile = $this->m_objProfile->getPROFILEID();
    parent::getDecorated_PrimaryInfo();
    //$this->m_arrOut['gtalkOnline'] = $this->m_actionObject->GTALK_ONLINE;
    $this->m_arrOut['isOnline'] = $this->m_actionObject->ISONLINE;
    $this->m_arrOut['profile_posted'] = $this->m_objProfile->getDecoratedRelation();
    $this->m_arrOut['posted_name'] = $this->m_objProfile->getDecoratedPersonHandlingProfile();
    $this->m_arrOut['religion'] = $this->m_objProfile->getDecoratedReligion();
    $this->m_arrOut['income'] = $this->m_objProfile->getDecoratedIncomeLevel();
    $this->m_arrOut['documents_provided'] = $this->m_objProfile->getDecoratedID_PROOF_TYP();
    $subscription = $this->getMembershipType();
    $this->m_arrOut['subscription_icon'] = $this->getFormattedSubscription($subscription);
    $mod_date = substr($this->m_objProfile->getMOD_DT(), 0, 10);
    $mydateArr = explode("-", $mod_date);
    $this->m_arrOut['last_mod'] = my_format_date($mydateArr[2], $mydateArr[1], $mydateArr[0], 1);
    if($this->m_objProfile->getGender() == $this->m_actionObject->loginProfile->getGender())
        $this->m_arrOut['sameGender']=1;            
    $havePhoto=$this->m_objProfile->getHAVEPHOTO();
        if($havePhoto=='Y'){
            if($this->m_actionObject->THUMB_URL) {
                $thumbNailArray = PictureFunctions::mapUrlToMessageInfoArr($this->m_actionObject->THUMB_URL,'ThumbailUrl','',$this->m_objProfile->getGender());
                if($thumbNailArray[label] != '')
                    $thumbNail = PictureFunctions::getNoPhotoJSMS($this->m_objProfile->getGender(),'ProfilePic120Url');
                else
                    $thumbNail = $thumbNailArray['url'];
            }
                
        }
        else {
            $thumbNail = PictureFunctions::getNoPhotoJSMS($this->m_objProfile->getGender(),'ProfilePic120Url');
        }
      $this->m_arrOut['thumbnailPic'] = $thumbNail;
    if($viewerProfile){
        $introCall = new getIntroCallHistory;
        $membershipObj = new MembershipHandler;
        $ProfilesObj = new ASSISTED_PRODUCT_AP_CALL_HISTORY();
        $introCallArr = $introCall->offCallHistory($viewerProfile,$viewedProfile,$membershipObj,$ProfilesObj);
        $this->m_arrOut['introCallData'] = $introCallArr;
    }
    
    //Cover Photo
    $coverPhotoSeriveObj= new CoverPhotoService();
    $this->m_arrOut['coverPhoto'] = $coverPhotoSeriveObj->getCoverPhotoURL($viewedProfile);
    if(!$this->bResponseForEditView && ($this->m_arrOut['coverPhoto'] == PictureStaticVariablesEnum::$defaultCoverPhotoUrl) ){
        $this->m_arrOut['coverPhoto'] = PictureStaticVariablesEnum::$defaultViewProfileCoverPhotoUrl;
    }
    
    if($this->bResponseForEditView){
      $objProfile = $this->m_objProfile;
      //Caste Sect Work
      $iReligion = $objProfile->getRELIGION();
      $szSectLabel = null;
      $szSectValue = null;
      switch($iReligion){
        case 1://Hindu
          $szCasteValue = $objProfile->getDecoratedCaste();
          $szSectValue  = $objProfile->getDecoratedSect();
          $szCasteLabel = 'Caste';
          $szSectLabel  = 'Sect';
        break;
        case 2://Muslim
          $szCasteValue = $objProfile->getDecoratedCaste();
          $szSectValue = $objProfile->getDecoratedSect();
          $szCasteLabel = 'Sect';
          $szSectLabel  = 'Caste';
	  $relinfo = (array)$objProfile->getReligionInfo();
	  $jamaat = $relinfo['JAMAAT'];
        break;
        case 3://Christain
          $szCasteValue = $objProfile->getDecoratedCaste();
          $szCasteLabel = 'Sect';
        break;
        case 4://Sikh
          $szCasteValue = $objProfile->getDecoratedCaste();
          $szSectValue  = $objProfile->getDecoratedSect();
          $szCasteLabel = 'Caste';
          $szSectLabel  = 'Sect';
        break;
        case 9://Jain
          $szCasteValue = $objProfile->getDecoratedCaste();
          $szCasteLabel = 'Caste';
        break;  
        default://Parsi,Jewish,Bahai,Other,Buddhist
          $szCasteValue = null;
          $szCasteLabel = null;
      }
      $this->m_arrOut['caste_sect_label'] = null;
      $this->m_arrOut['caste_sect']       = null;
      if($szCasteValue && $szCasteLabel){
        $this->m_arrOut['caste_sect_label'] = $szCasteLabel . ($szSectLabel === null ? "" : ", ".$szSectLabel);
        $this->m_arrOut['edit_caste'] = $szCasteValue . ($szSectValue === null ? "" : ", " );
        $this->m_arrOut['edit_sect']  = ($szSectValue === null ? "" : $szSectValue);
      }
	if($objProfile->getReligion()=="2")
		$this->m_arrOut['jamaat']=($jamaat)===null?"-":$jamaat;
	$this->m_arrOut['caste_val']=$objProfile->getCaste();
      
      //Name Work
      //Caching
      $nameOfUserOb=new NameOfUser();
      $this->m_arrOut['name'] = ApiViewConstants::getNullValueMarker();        
      $nameOfUserArr = $nameOfUserOb->getNameData($objProfile->getPROFILEID());
      $szName = $nameOfUserArr[$objProfile->getPROFILEID()]["NAME"];
      if(strlen($szName)) {
        $this->m_arrOut['name'] = $szName;
      }
      unset($nameOfUserOb);
      $aadharVerificationObj = new aadharVerification();
        $aadharArr = $aadharVerificationObj->getAadharDetails($objProfile->getPROFILEID());
        if($aadharArr[$objProfile->getPROFILEID()]["VERIFY_STATUS"] == "Y")
                $this->m_arrOut['aadhar']=$aadharArr[$objProfile->getPROFILEID()]["AADHAR_NO"];
        else
                $this->m_arrOut['aadhar']="";
        
        unset($aadharVerificationObj);
                
      /*$name_pdo = new incentive_NAME_OF_USER();
      $this->m_arrOut['name'] = ApiViewConstants::getNullValueMarker();
      
      $szName = $name_pdo->getName($objProfile->getPROFILEID());
      if(strlen($szName)) {
        $this->m_arrOut['name'] = $szName;
      }
      unset($name_pdo);*/
      
      include_once (sfConfig::get("sf_web_dir") . "/profile/ntimes_function.php");
      //In case of viewing own page, to see the count of profile visitors.
      $this->m_arrOut['profileViews'] = ntimes_count($viewedProfile, "SELECT");
      
      $profileDOB = $objProfile->getDTOFBIRTH();
      $profileDOB = DateTime::createFromFormat('Y-m-d',$profileDOB);
      $this->m_arrOut['formatted_dob'] = $profileDOB->format('d M Y');
    }

  }
/**
* function to decorate Matching values with viewer's profile
* @param void
* @return void 
* @access protected
*/
  protected function getDecorated_LookingFor(){
      parent::getDecorated_LookingFor();
      $objProfile = $this->m_objProfile;  
      $jPartnerObj = $objProfile->getJpartner();
       $this->m_arrOut['dpp_diet'] = $jPartnerObj->getDecoratedPARTNER_DIET();
       $this->m_arrOut['dpp_smoke'] = $jPartnerObj->getDecoratedPARTNER_SMOKE();
       $this->m_arrOut['dpp_drink'] = $jPartnerObj->getDecoratedPARTNER_DRINK();
       $this->m_arrOut['dpp_complexion']=$jPartnerObj->getDecoratedPARTNER_COMP();
       $this->m_arrOut['dpp_btype'] = $jPartnerObj->getDecoratedPARTNER_BTYPE();
       $this->m_arrOut['dpp_handi'] = $jPartnerObj->getDecoratedHANDICAPPED();
       $this->m_arrOut['dpp_religion'] = strip_tags($this->m_arrOut['dpp_religion']);
       $this->m_arrOut['dpp_caste'] = $this->getCasteLabelForGrouping($jPartnerObj->getPARTNER_CASTE());
       $this->m_arrOut['dpp_city'] = strip_tags($this->m_arrOut['dpp_city']);
       $this->m_arrOut['dpp_country'] = strip_tags($this->m_arrOut['dpp_country']);
       $this->m_arrOut['dpp_mtongue'] = strip_tags($this->m_arrOut['dpp_mtongue']);
       $this->m_arrOut['dpp_occupation'] = strip_tags($this->m_arrOut['dpp_occupation']);
       $this->m_arrOut['dpp_have_children'] = $jPartnerObj->getDecoratedCHILDREN();
       $state = $jPartnerObj->getDecoratedSTATE();
       if($state && $this->m_arrOut['dpp_city'])
           $this->m_arrOut['dpp_city'] = $state.','.$this->m_arrOut['dpp_city'];
       elseif($state)
           $this->m_arrOut['dpp_city'] = $state;
       if($jPartnerObj->getDecoratedNHANDICAPPED())
   $this->m_arrOut['dpp_natureHandi']= $jPartnerObj->getDecoratedNHANDICAPPED(); 
  }
  /**
* function to decorate Lifestyle values in Viewer's profile
* @param void
* @return void 
* @access protected
*/
  protected function getDecorated_LifeStyle(){
      
     $objProfile = $this->m_objProfile;
    
      //LifeStyle
   
      $arrTemp = array();
      $arrTemp1 = array();
      $arrReligiousBeliefs = array();


    /******* Habbits******/
    //Diet
    if(strlen($objProfile->getDIET())!=0 && $objProfile->getDIET() != ApiViewConstants::getNullValueMarker())
    {
      $arrTemp1[] = $objProfile->getDecoratedDiet();
    }
    else if($this->bResponseForEditView){
      $arrTemp1[] = 'Dietary Habits?';
    }
    //Drink
    if(strlen($objProfile->getDRINK())!=0 && $objProfile->getDRINK() != ApiViewConstants::getNullValueMarker())
    {
      $arrTemp1[] = ApiViewConstants::$arrDrinkLabelDesktop[$objProfile->getDRINK()];
    }
    else if($this->bResponseForEditView){
      $arrTemp1[] = 'Drinking Habits?';
    }
    //Smoke
    if(strlen($objProfile->getSMOKE())!=0 && $objProfile->getSMOKE() != ApiViewConstants::getNullValueMarker())
    {
      $arrTemp1[] = ApiViewConstants::$arrSmokeLabelDesktop[$objProfile->getSMOKE()];
    }
    else if($this->bResponseForEditView){
      $arrTemp1[] = 'Smoking Habits?';
    }
    if(is_array($arrTemp1) && count($arrTemp1))
    {
      $arrTemp['habbits'] = implode(", ",$arrTemp1);
    }
    unset($arrTemp1);


    /******* Appearance******/
    //Body Type
    if(strlen($objProfile->getBTYPE())!=0 && $objProfile->getBTYPE() != ApiViewConstants::getNullValueMarker())
    {
      $arrTemp1[] = $objProfile->getDecoratedBodytype();
    }
    else if($this->bResponseForEditView){
      $arrTemp1[] = 'Body type?';
    }
    //Complexion
    if(strlen($objProfile->getCOMPLEXION())!=0 && $objProfile->getCOMPLEXION() != ApiViewConstants::getNullValueMarker())
    {
      $arrTemp1[] = $objProfile->getDecoratedComplexion();
    }
    else if($this->bResponseForEditView){
      $arrTemp1[] = 'Complexion?';
    }
    //Weight
    if(strlen($objProfile->getWEIGHT())!=0 && $objProfile->getWEIGHT()!=0 && $objProfile->getWEIGHT() != ApiViewConstants::getNullValueMarker())
    {
      $arrTemp1[] = $objProfile->getDecoratedWeight();
    }
    else if($this->bResponseForEditView){
      $arrTemp1[] = 'Weight?';
    }
    
    if(is_array($arrTemp1) && count($arrTemp1))
    {
      $arrTemp['appearance'] = implode(", ",$arrTemp1);
    }
    unset($arrTemp1);

    //House And Car
    if(strlen($objProfile->getOWN_HOUSE())!=0 && $objProfile->getOWN_HOUSE() != ApiViewConstants::getNullValueMarker())
    {
      $arrTemp1[] = ApiViewConstants::$arrOwnsHouse[$objProfile->getOWN_HOUSE()];
    }
    else if($this->bResponseForEditView){
      $arrTemp1[] = 'Own a house?';
    }
    
    if(strlen($objProfile->getHAVE_CAR())!=0 && $objProfile->getHAVE_CAR() != ApiViewConstants::getNullValueMarker())
    {
      $arrTemp1[] = ApiViewConstants::$arrOwnsCar[$objProfile->getHAVE_CAR()];
    }
    else if($this->bResponseForEditView){
      $arrTemp1[] = 'Own a car?';
    }
    
     if(is_array($arrTemp1) && count($arrTemp1))
    {
      $arrTemp['assets'] = implode(", ",$arrTemp1);
    }
    unset($arrTemp1);

    //open to pets
    $arrTemp['open_to_pets'] = "";
    if(strlen($objProfile->getOPEN_TO_PET())!=0 && $objProfile->getOPEN_TO_PET() != ApiViewConstants::getNullValueMarker())
    {
      $arrTemp['open_to_pets'] = ApiViewConstants::$arrOpenToPets[$objProfile->getOPEN_TO_PET()];
    }
    else if($this->bResponseForEditView){
      $arrTemp['open_to_pets'] = 'Open to pets?';
    }

    //Residential Status
    $arrTemp['res_status'] = "";
    if(strlen($objProfile->getRES_STATUS())!=0 && $objProfile->getRES_STATUS() != ApiViewConstants::getNullValueMarker())
    {
      $arrTemp['res_status'] = $objProfile->getDecoratedRstatus();
    }
    else if($this->bResponseForEditView){
      $arrTemp['res_status'] = $objProfile->getDecoratedRstatus();
    }
    
    //Languages Known
    $arrTemp['language'] = "";
    $arrHobbies = $objProfile->getHobbies();
    
    if($arrHobbies && $arrHobbies->LANGUAGE != $arrHobbies->nullValueMarker )
    {
      $arrTemp['language'] =  $arrHobbies->LANGUAGE;
    }
    else if($this->bResponseForEditView){
      $arrTemp['language'] = ApiViewConstants::getNullValueMarker();
    }

    //Blood Group
    $arrTemp['blood_group'] = "";
    if(strlen($objProfile->getBLOOD_GROUP())!=0 && $objProfile->getBLOOD_GROUP() != ApiViewConstants::getNullValueMarker())
    {
      $arrTemp['blood_group'] = $objProfile->getDecoratedBloodGroup();
    }
    else if($this->bResponseForEditView){
      $arrTemp['blood_group'] = $objProfile->getDecoratedBloodGroup();
    }
    


    /******* Special Cases*******/
    //Challenged
     if(strlen($objProfile->getHANDICAPPED())!=0 && $objProfile->getHANDICAPPED() != ApiViewConstants::getNullValueMarker())
     {
        if($objProfile->getDecoratedHandicapped()!= "None")
          $arrTemp1[] = $objProfile->getDecoratedHandicapped();
        else
          $arrTemp1[] = "Challenged - None";
     }
     else if($this->bResponseForEditView){
      $arrTemp1[] = 'Challenged?';
     }
     
      //Nature of Handicap
     $arrAllowedChallenged = array("1","2");
     if(strlen($objProfile->getNATURE_HANDICAP())!=0 && $objProfile->getNATURE_HANDICAP() != ApiViewConstants::getNullValueMarker())
    {
       $arrTemp1[] = $objProfile->getDecoratedNatureHandicap();
    }
    else if($this->bResponseForEditView && in_array($objProfile->getHANDICAPPED(),$arrAllowedChallenged) ){
      $arrTemp1[] = 'Nature of handicap?';
    }

    //Thalassemia
    if(strlen($objProfile->getTHALASSEMIA())!=0 && $objProfile->getTHALASSEMIA() != ApiViewConstants::getNullValueMarker())
    {
      $arrTemp1[] = "Thalassemia - ".$objProfile->getDecoratedThalassemia();
    }
    else if($this->bResponseForEditView){
      $arrTemp1[] = 'Thalassemia?';
    }

    //HIV
    if(strlen($objProfile->getHIV())!=0 && $objProfile->getHIV() != ApiViewConstants::getNullValueMarker())
    {
      if($objProfile->getDecoratedHiv() == "Positive")
        $arrTemp1[] = "HIV+ - Yes";
      else
        $arrTemp1[] = "HIV+ - No";
    }
    else if($this->bResponseForEditView){
      $arrTemp1[] = 'HIV+ve?';
    }
    
    if(is_array($arrTemp1) && count($arrTemp1))
    {
      $arrTemp['special_cases'] = implode(", ",$arrTemp1);
    }
    unset($arrTemp1);

    /***** Religious Beliefs*****/ 
  
    $arrTemp1 = (array)$this->m_arrReligionInfo;
    $religion = $objProfile->getRELIGION();
    switch($religion)
    {
      case 2: //Muslim
      {
        if($objProfile->getGENDER() == "M")
          $arrReligiousBeliefs = ApiViewConstants::$arrMuslimMaleLabels;
        else
          $arrReligiousBeliefs = ApiViewConstants::$arrMuslimFemaleLabels;
        //"243" is Others sect        
        if($this->bResponseForEditView && $objProfile->getCASTE() == "243"){
          unset($arrReligiousBeliefs[0]);
        }
      }
      break;
      case 3: //Christian
      {
        $arrReligiousBeliefs = ApiViewConstants::$arrChristianLabels;
      }
      break;
      case 4: //Sikh
      {
        if($objProfile->getGENDER() == "M"){
          $arrReligiousBeliefs = ApiViewConstants::$arrSikhMaleLabels;
          if($this->bResponseForEditView && substr($arrTemp1["AMRITDHARI"],0,1) == "Y" ){
            unset($arrReligiousBeliefs[1]);
            unset($arrReligiousBeliefs[2]);
            unset($arrReligiousBeliefs[3]);
            unset($arrReligiousBeliefs[4]);
          }
        }
        else
        {
          $arrReligiousBeliefs = ApiViewConstants::$arrSikhFemaleLabels;
          if($this->bResponseForEditView && substr($arrTemp1["AMRITDHARI"],0,1) == "Y" ){
            unset($arrReligiousBeliefs[1]);
          }
        }
        
      }
      break;
      case 5: //Parsi
      {
        $arrReligiousBeliefs = ApiViewConstants::$arrParsiLabels;
      }
      break;
    }
    foreach($arrReligiousBeliefs as $key => $value){
    if($arrTemp1[$value["old_label"]]!="" && $this->bResponseForEditView==false){
        $string.= $value["new_label"]." - ".$arrTemp1[$value["old_label"]].", ";
      }
      else if( $this->bResponseForEditView ) {
        $string.= $value["new_label"].( ($arrTemp1[$value["old_label"]] == null || $arrTemp1[$value["old_label"]] == ApiViewConstants::getNullValueMarker()) ? "?, " :  (" - ".$arrTemp1[$value["old_label"]].", ") );
      }
    }
    $arrTemp['religious_beliefs'] = rtrim($string,", ");
    $arrTemp['religion_value'] = $religion;
    unset($arrTemp1);
    
    $arrTemp['hobbies'] = (array)$arrHobbies;

    if($this->bResponseForEditView){
      unset($arrTemp['hobbies']);
      $arrTemp['hobbies'] = array();
      $arrTemp1['hobbies'] = (array)$arrHobbies;
      foreach($arrTemp1['hobbies'] as $key=>$val){
        $key = preg_replace('/[^a-z0-9A-Z -_]+/', '', $key);
        $arrTemp['hobbies'][$key] = $val;
      }
      unset($arrTemp1);
      unset($arrTemp['hobbies']['ProfileComponentnullValueMarker']);
      foreach(ApiViewConstants::$arrAllowedHobbies as $key=>$val){
        
        if(count($arrTemp['hobbies']) == 0 || false === array_key_exists($val,$arrTemp['hobbies'])){
          $arrTemp['hobbies'][$val] = $this->m_objProfile->getNullValueMarker();
        }
        
        if(strlen(trim($val)) == 0){
          $arrTemp['hobbies'][$val] = $this->m_objProfile->getNullValueMarker();
        }
      }
    }

    $this->m_arrOut = $arrTemp;
   
  }
  /**
* function to decorate Family values of viewer's profile
* @param void
* @return void 
* @access protected
*/
  protected function getDecorated_AboutFamily(){
    
      parent::getDecorated_AboutFamily();
      $objProfile = $this->m_objProfile;
      $arrTemp = array();
      $siblings = $objProfile->getSiblings();
    if($siblings->tbrother !== ''){
      $brother = $siblings->tbrother . " brother";
      if ($siblings->tbrother > 1) $brother.= "s";
      if ($siblings->mbrother!=='' && $siblings->tbrother !=0) $brother.= " of which ".$siblings->mbrother." married " ;
      $arrTemp['sibling_brother'] = $brother;
    }
    else if($this->bResponseForEditView)
    {
      $arrTemp['sibling_brother'] = ApiViewConstants::getNullValueMarker();
    }
    
    if($siblings->tsister !==''){
      $sister = $siblings->tsister . " sister";
      if ($siblings->tsister > 1) $sister.= "s";
      if ($siblings->msister!=='' && $siblings->tsister!=0) $sister.= " of which ".$siblings->msister." married ";
      
      $arrTemp['sibling_sister'] = $sister;
      $this->m_arrOut['sibling']= $arrTemp;
    }
    else if($this->bResponseForEditView)
    {
      $arrTemp['sibling_sister'] = ApiViewConstants::getNullValueMarker();
      $this->m_arrOut['sibling']= $arrTemp;
    }
      //Profile Handler Name
      $arrTemp['profile_handler'] = "";
     if(strlen($objProfile->getPROFILE_HANDLER_NAME())!=0 && $objProfile->getPROFILE_HANDLER_NAME() != ApiViewConstants::getNullValueMarker())
     {
       $arrTemp['profile_handler'] = $objProfile->getDecoratedPersonHandlingProfile();
     }
     $this->m_arrOut['profile_posted_by'] = $arrTemp['profile_handler'];

     //Family Values
     $arrTemp['family_values'] = "";
     if(strlen($objProfile->getFAMILY_VALUES())!=0 && $objProfile->getFAMILY_VALUES() != ApiViewConstants::getNullValueMarker())
     {
       $arrTemp['family_values'] = $objProfile->getDecoratedFamilyValues();
     }
     $this->m_arrOut['family_values'] = $arrTemp['family_values'];
    

     //Family Type
     $arrTemp['family_type'] = "";
     if(strlen($objProfile->getFAMILY_TYPE())!=0 && $objProfile->getFAMILY_TYPE() != ApiViewConstants::getNullValueMarker())
     {
       $arrTemp['family_type'] = $objProfile->getDecoratedFamilyType();
     }
     $this->m_arrOut['family_type'] = $arrTemp['family_type'];
    

    //Family Status
     $arrTemp['family_status'] = "";
     if(strlen($objProfile->getFAMILY_STATUS())!=0 && $objProfile->getFAMILY_STATUS() != ApiViewConstants::getNullValueMarker())
     {
       $arrTemp['family_status'] = $objProfile->getDecoratedFamilyStatus();
     }
     $this->m_arrOut['family_status'] = $arrTemp['family_status'];
    
    // Gothra Maternal
     $arrTemp['gothra_maternal'] = "";
     if(strlen($objProfile->getGOTHRA_MATERNAL())!=0 && $objProfile->getGOTHRA_MATERNAL() != ApiViewConstants::getNullValueMarker())
     {
       $arrTemp['gothra_maternal'] = $objProfile->getDecoratedGothraMaternal();
     }
     $this->m_arrOut['gothra_maternal'] = $arrTemp['gothra_maternal'];
    
     $szPH_Name = $objProfile->getDecoratedPersonHandlingProfile();
     if(strlen($szPH_Name)!=0){
       $this->m_arrOut['profile_handler_name'] = $szPH_Name;
     }
     
     if($this->bResponseForEditView && ($this->m_arrOut['living'] == null || $this->m_arrOut['living'] == ApiViewConstants::getNullValueMarker())){
       $this->m_arrOut['living'] = "Living with parents?";
     }
     
     
     if($this->bResponseForEditView){
       $checkForNull = array('sub_caste','gothra','gothra_maternal','family_values','family_income','family_type','family_status','father_occ','mother_occ','native_place','myfamily');
       
       foreach($checkForNull as $k=>$v){
         if($this->m_arrOut[$v] == null || strlen($this->m_arrOut[$v]) == 0)
            $this->m_arrOut[$v] = ApiViewConstants::getNullValueMarker();
       }
     }
  }
  private function getFormattedSubscription($subscription) {
      switch ($subscription){
          case "erishta" : return "eRishta";
          break;
          case "evalue" : return "eValue";
          break;
          case "jsexclusive" : return "JS Exclusive";
          break;
          case "eadvantage" : return "eAdvantage";
          break;
      }
  }

  /**
   * getDecorated_MyEducation
   * 
   * @param void
   * @return void , Stores Key Value Pairs of Education Section in m_arrOut
   * @access protected
   */
  protected function getDecorated_MyEducation()
  {
    parent::getDecorated_MyEducation();
    $objProfile = $this->m_objProfile;
      $arrTemp1 = array();
      $arrTemp = array();
      $verificationValue = array();
      if(strlen($objProfile->getHAVE_JEDUCATION())!=0 && $objProfile->getHAVE_JEDUCATION() != ApiViewConstants::getNullValueMarker())
      {
        $arrTemp["other_pg_degree"] = $objProfile->getEducationDetail()->OTHER_PG_DEGREE;
        $arrTemp["other_ug_degree"] = $objProfile->getEducationDetail()->OTHER_UG_DEGREE;
        
        if($this->bResponseForEditView){
          //other_pg_degree
          $arrTemp["other_pg_degree"] = ($arrTemp["other_pg_degree"] ==null || $arrTemp["other_pg_degree"] == '-') ?  ApiViewConstants::getNullValueMarker() : $arrTemp["other_pg_degree"];
          $arrTemp["other_ug_degree"] = ($arrTemp["other_ug_degree"] ==null || $arrTemp["other_ug_degree"] == '-') ?  ApiViewConstants::getNullValueMarker() : $arrTemp["other_ug_degree"];
          $this->m_arrOut['edit_other_pg_degree'] = $arrTemp["other_pg_degree"];
          $this->m_arrOut['edit_other_ug_degree'] = $arrTemp["other_ug_degree"];
        }
      }
    unset($arrTemp1);
    $this->m_arrOut["other_degree"] = $arrTemp;

    /*** work status***/
     if(strlen($objProfile->getWORK_STATUS())!=0 && $objProfile->getWORK_STATUS() != ApiViewConstants::getNullValueMarker())
      {
        $arrTemp1["work_status"] = $objProfile->getDecoratedWorkStatus();
      }
      $this->m_arrOut["decorated_work_status"]=$arrTemp1;
      unset($arrTemp1);
      
      /*** verification seal ***/
      $verificationSealObj = new VerificationSealLib($objProfile);
      $this->verificationSeal = $verificationSealObj->getVerificationSeal();
      $arrTemp2 = array();
      $arrTemp = array();
      $arrTemp2 = $this->verificationSeal;

      $arrTemp1[] = $arrTemp2["VERIFICATION_SEAL"]["Self_Address"];
      $arrTemp1[] = $arrTemp2["VERIFICATION_SEAL"]["Parents_Address"];

      if(is_array($arrTemp1) && count($arrTemp1))
      {
        $arrTemp = implode(", ",array_unique($arrTemp1));
      }
      unset($arrTemp1);

      $this->m_arrOut["documents_provided"] = $this->verificationSeal;
      $this->m_arrOut["documents_provided"]["address"]=ltrim(rtrim($arrTemp,", "),",");

      if(is_array($this->verificationSeal))
      {
        foreach ($this->verificationSeal["VERIFICATION_SEAL"] as $key => $value) {           
          switch ($key)
          {
            case "Qualification":  $displaySeal[]= "Education";
            break;
            case "Self_Address":
            case "Parents_Address": $displaySeal[]="Address";
            break;
            case "Date_of_Birth":$displaySeal[]="Age";
            break;
            case "Income" : $displaySeal[]="Income";
            break;
            case "Divorce" : $displaySeal[]="Marital Status";
            break;

          }
        }  
        
        $this->verificationSeal = implode(array_unique($displaySeal),", ");

      }
      $this->m_arrOut["verification_value"] = $this->verificationSeal;
      $this->m_arrOut["verification_value_arr"] = array_unique($displaySeal);

    if($this->bResponseForEditView){
      $objEducation = $objProfile->getEducationDetail();
      
      if($this->m_arrOut['post_grad'] && $objEducation->PG_DEGREE == $objEducation->nullValueMarker){
        $this->m_arrOut['post_grad']['deg'] = ApiViewConstants::getNullValueMarker();
      }
      
      if($this->m_arrOut['under_grad'] && $objEducation->UG_DEGREE == $objEducation->nullValueMarker){
        $this->m_arrOut['under_grad']['deg'] = ApiViewConstants::getNullValueMarker();
      }
    }

  }    

  
  /*
   * Function to setResponse Type for Edit View
   * @param bStatus
   * @return void
   */
  public function setResponseForEditView($bStatus){
    $this->bResponseForEditView = false;
    if(isset($bStatus) && 
       ($bStatus == 1 || $bStatus == 'Y')
      ){
      $this->bResponseForEditView = true;
    }

  }
  
  protected function getDecorated_MyCareer(){
    parent::getDecorated_MyCareer();
    $objProfile = $this->m_objProfile;  
    
    if($this->bResponseForEditView){
      
      if($this->m_arrOut['post_grad'] && $this->m_arrOut['post_grad']['deg'] == null){
        $this->m_arrOut['post_grad']['deg'] = $objProfile->getNullValueMarker();
      }
      
      if($this->m_arrOut['under_grad'] && $this->m_arrOut['under_grad']['deg'] == null){
        $this->m_arrOut['under_grad']['deg'] = $objProfile->getNullValueMarker();
      }
      
      if($this->m_arrOut['post_grad'] && $this->m_arrOut['post_grad']['name'] == null){
        $this->m_arrOut['post_grad']['name'] = $objProfile->getNullValueMarker();
      }
      
      if($this->m_arrOut['under_grad'] && $this->m_arrOut['under_grad']['name'] == null){
        $this->m_arrOut['under_grad']['name'] = $objProfile->getNullValueMarker();
      }
      
      if($this->m_arrOut['work_status']['company'] == null){
        $this->m_arrOut['work_status']['company'] = $objProfile->getNullValueMarker();
      }
      //Company Name
      $this->m_arrOut['edit_company_name'] = $this->m_arrOut['work_status']['company'] ;
      //Edit Post Grad
      $this->m_arrOut['post_grad_deg'] = $this->m_arrOut['post_grad']['deg'];
      $this->m_arrOut['post_grad_collg'] = $this->m_arrOut['post_grad']['name'];
      
      //Edit Post Grad
      $this->m_arrOut['under_grad_deg'] = $this->m_arrOut['under_grad']['deg'];
      $this->m_arrOut['under_grad_collg'] = $this->m_arrOut['under_grad']['name'];
      
      if($this->m_arrOut['under_grad'] == null){
        $this->m_arrOut['under_grad_deg'] = "Not Applicable";
        $this->m_arrOut['under_grad_collg'] = "Not Applicable";
        $this->m_arrOut['edit_other_ug_degree'] = "Not Applicable";
      }
      
      if($this->m_arrOut['post_grad'] == null){
        $this->m_arrOut['post_grad_deg'] = "Not Applicable";
        $this->m_arrOut['post_grad_collg'] = "Not Applicable";
        $this->m_arrOut['edit_other_pg_degree'] = "Not Applicable";
      }
      
      if($this->m_arrOut['decorated_work_status'] == null){
        $this->m_arrOut['decorated_work_status']["work_status"] = ApiViewConstants::getNullValueMarker();
      }
        
      //edit_work_status
      $this->m_arrOut['edit_work_status'] = $objProfile->getDecoratedWorkStatus();
      
       $arrLabel = array('abroad'=>"Interested in settling abroad?",'plan_to_work'=>"Work after marriage?"); 
       $checkForNull = array('abroad','plan_to_work','school',"edit_other_pg_degree","edit_other_ug_degree","mycareer","myedu","myfamily","nakshatra","horo_match","rashi","toShowHoroscope","have_child");//Replace 
       
       foreach($checkForNull as $k=>$v){
         if($this->m_arrOut[$v] == null || strlen($this->m_arrOut[$v]) == 0 || $this->m_arrOut[$v] == "-"){
            $this->m_arrOut[$v] = ApiViewConstants::getNullValueMarker();
         }
         if($this->m_arrOut[$v] == ApiViewConstants::getNullValueMarker()  && array_key_exists($v, $arrLabel)){
           $this->m_arrOut[$v] = $arrLabel[$v];
         }
       }
       
       
     }
  }
  
  protected function getDecorated_AstroInfo(){
    parent::getDecorated_AstroInfo();
        $objProfile = $this->m_objProfile;
        $arrAstroKundali = $this->m_arrAstro;
        if($arrAstroKundali->nakshatra && $arrAstroKundali->nakshatra != ApiViewConstants::getNullValueMarker())
    {
      $nakshatra = $arrAstroKundali->nakshatra;
    }
        if($objProfile->getHOROSCOPE_MATCH())
      $horo_match = ApiViewConstants::$arrHoroScope_Required[$objProfile->getHOROSCOPE_MATCH()] ;
        
        $astro_privacy = FieldMap::getFieldLabel("astro_privacy_label", $objProfile->getSHOW_HOROSCOPE());
        $cManglik = CommonFunction::setManglikWithoutDontKnow($objProfile->getMANGLIK());
    $szManglik = ApiViewConstants::getManglikLabel($cManglik);
        $rashi = $arrAstroKundali->rashi;
        $sunsign = $arrAstroKundali->sunsign;
        $profileId = $objProfile->getPROFILEID();
        if($this->bResponseForEditView){
            if (!check_astro_details($profileId, "Y")){
                /*include_once(sfConfig::get("sf_web_dir")."/profile/horoscope_upload.inc");
                    if (get_horoscope($profileId)){
                    $HOROSCOPE = "Y";
                }
                else{
                    $HOROSCOPE = "N";
                }*/
                $HOROSCOPE = "N";
                $this->m_arrOut['NO_ASTRO']=1;
            }
            else{
                $HOROSCOPE = "Y";
            }
            
            /*$horoStoreObj = new NEWJS_HOROSCOPE_FOR_SCREEN;
            $horoRow =  $horoStoreObj->getHoroscopeIfNotDeleted($objProfile->getPROFILEID());
            unset($horoStoreObj);

            if($horoRow === false || !$horoRow ){
               $horo_for_screen = null; 
            }
            else{
                $horo_for_screen = 'Y';
            }*/
            if($HOROSCOPE == 'Y')/*$horo_for_screen == 'Y' || */
                $this->m_arrOut['horo_available'] = 'Y';
            else
                $this->m_arrOut['horo_available'] = 'N';
        }
        
        $arrCity_Country = array();
    
    if($objProfile->getCITY_BIRTH())
    {
      $arrCity_Country[] = $objProfile->getDecoratedBirthCity();
    }
    
    if($objProfile->getCOUNTRY_BIRTH()) 
    {
      $arrCity_Country[] = $objProfile->getDecoratedBirthCountry();
    }
    
    $szStr = implode(", ",$arrCity_Country);
    $this->m_arrOut['city_country'] = (strlen($szStr) === 0 )? null : $szStr ;
        
        
        
        
        $astroArr = (array)$this->m_arrAstro;
        $this->m_arrOut['astro_date'] = $astroArr['dateOfBirth'];
        $this->m_arrOut['astro_time'] = $astroArr['birthTimeHour']." hrs:".$astroArr['birthTimeMin']." mins";
        $this->m_arrOut['astro_time_check'] = $astroArr['birthTimeHour'];
        
        $this->m_arrOut['nakshatra'] = $nakshatra;
        $this->m_arrOut['horo_match'] = $horo_match;
        $this->m_arrOut['astro_privacy'] = $astro_privacy;
        $this->m_arrOut['astro_manglik'] = $szManglik;
        $this->m_arrOut['astro_sunsign'] = $sunsign;
        $this->m_arrOut['rashi'] = $rashi;
        $arrLabel = array('horo_match'=>"Horoscope match is must?"); 
        $checkForNull = array("nakshatra","horo_match","astro_privacy","astro_manglik","astro_sunsign","rashi","sunsign");//Replace 
        foreach($checkForNull as $k=>$v){
         if($this->m_arrOut[$v] == null || strlen($this->m_arrOut[$v]) == 0 || $this->m_arrOut[$v] == "-"){
            $this->m_arrOut[$v] = ApiViewConstants::getNullValueMarker();
         }
         if($this->m_arrOut[$v] == ApiViewConstants::getNullValueMarker()  && array_key_exists($v, $arrLabel)){
             if($this->bResponseForEditView){
                $this->m_arrOut[$v] = $arrLabel[$v];
             }
         }
       }
  }
  
  /*
   * getViewProfileResponse
   */
  public function getViewProfileResponse()
  {
        $originalProflie = null;

        if($this->m_actionObject->loginProfile->getPROFILEID() == $this->m_objProfile->getPROFILEID())
        {
            $originalProflie = $this->m_objProfile;
            $this->m_objProfile = $this->m_actionObject->loginProfile;
        }
        
    //About Me Section
    $this->m_arrOut = array();
    $this->getDecorated_PrimaryInfo();
    $this->getDecorated_MyEducation();
    $this->getDecorated_MyCareer();
    $arrAboutSec = $this->m_arrOut;

      
    //Profile Pic Section 
    $this->m_arrOut = array();
    $this->getDecorated_Photo();
    $arrPicSection = $this->m_arrOut;
      
    //More Info
    $this->m_arrOut = array();
    $this->getDecorated_MoreInfo();
    $arrMoreInfo = $this->m_arrOut;
    
    // Compiling Output Section
    $this->m_arrSectionOut = array(
              'about'   =>  $arrAboutSec,
              'pic'   =>  $arrPicSection,
              'page_info' =>  $arrMoreInfo,
              );
              
    return $this->m_arrSectionOut;
  }
  
  private function getDecorate_ContactDetails(){
    if($this->bResponseForEditView === false){
      return ;
    }
    
    $objProfile = $this->m_objProfile;  
    
    //Email
    $this->m_arrOut['my_email'] = $objProfile->getEMAIL();
    $this->m_arrOut['email_status'] = "Verify";
      if($objProfile->getVERIFY_EMAIL() == "Y"){
        $this->m_arrOut['email_status'] = "Verified";
      }
    //alternate Email
    $this->m_arrOut['my_alt_email'] = $objProfile->getExtendedContacts("onlyValues")["ALT_EMAIL"];
    $this->m_arrOut['alt_email_status'] = "Verify";
    if (  $objProfile->getExtendedContacts("onlyValues")["ALT_EMAIL_STATUS"] == 'Y')
    {
      $this->m_arrOut['alt_email_status'] = "Verified";
    }
    if ( $this->m_arrOut['my_alt_email'] == NULL )
    {
      $this->m_arrOut['alt_email_status'] = "";
      $this->m_arrOut['my_alt_email'] = ApiViewConstants::JSPC_NULL_VALUE_MARKER;
    }
    //Mobile number
    $mobile_label = ApiViewConstants::getNullValueMarker();
    $this->m_arrOut['mobile_desc'] = '';
    $this->m_arrOut['phone_mob_status'] = "";
    if($objProfile->getPHONE_MOB() && $objProfile->getPHONE_MOB() != ApiViewConstants::getNullValueMarker()){
      $mobile_label = "+".$objProfile->getISD()."-".$objProfile->getPHONE_MOB();
      
      $tempArray = array();
      if(strlen($objProfile->getMOBILE_NUMBER_OWNER())){
        $tempArray[] = $objProfile->getDecoratedMobileNumberOwner();
      }
      
      if(strlen($objProfile->getMOBILE_OWNER_NAME())){
        $tempArray[] = $objProfile->getMOBILE_OWNER_NAME();
      }
      
      $mobileDesc = implode(", ",$tempArray);
      if(strlen($mobileDesc)){
        $this->m_arrOut['mobile_desc'] = "used by " . $mobileDesc;
      }
      //Phone Mob Verification Status
      $this->m_arrOut['phone_mob_status'] = "Verify";
      if($objProfile->getMOB_STATUS() == "Y"){
        $this->m_arrOut['phone_mob_status'] = "Verified";
      }
    }
    $this->m_arrOut['mobile'] = $mobile_label;
    
    //Alt Mobile
    $alternate_label = ApiViewConstants::getNullValueMarker();
    $this->m_arrOut['alt_mobile_desc'] = '';
    $this->m_arrOut['alt_mob_status'] = "";
    if($objProfile->getExtendedContacts()->ALT_MOBILE && $objProfile->getExtendedContacts()->ALT_MOBILE != ApiViewConstants::getNullValueMarker()){
      
      if(strpos($objProfile->getExtendedContacts()->ALT_MOBILE_ISD,"+")===0){
        $altISD=substr($objProfile->getExtendedContacts()->ALT_MOBILE_ISD,1);
      }
      else{
        $altISD=$objProfile->getExtendedContacts()->ALT_MOBILE_ISD;
      }
      
      $alternate_label= "+".$altISD."-".$objProfile->getExtendedContacts()->ALT_MOBILE;
      
      
      $tempArray = array();
      if(strlen($objProfile->getExtendedContacts()->ALT_MOBILE_NUMBER_OWNER) && $objProfile->getExtendedContacts()->ALT_MOBILE_NUMBER_OWNER != ApiViewConstants::getNullValueMarker()){
        $tempArray[] = $objProfile->getExtendedContacts()->ALT_MOBILE_NUMBER_OWNER;
      }
      
      if(strlen($objProfile->getExtendedContacts()->ALT_MOBILE_OWNER_NAME) && $objProfile->getExtendedContacts()->ALT_MOBILE_OWNER_NAME != ApiViewConstants::getNullValueMarker()){
        $tempArray[] = $objProfile->getExtendedContacts()->ALT_MOBILE_OWNER_NAME;
      }
     
      $alt_mobile_desc = implode(", ",$tempArray);
      if(strlen($alt_mobile_desc)){
        $this->m_arrOut['alt_mobile_desc'] = "used by " . $alt_mobile_desc;
      }
      
       //Alt Mob Verification Status
      $this->m_arrOut['alt_mob_status'] = "Verify";
      if($objProfile->getExtendedContacts()->ALT_MOB_STATUS == "Y"){
        $this->m_arrOut['alt_mob_status'] = "Verified";
      }
    }
    $this->m_arrOut['alt_mobile'] = $alternate_label;
    
    //Landline Number
    $landline_label = ApiViewConstants::getNullValueMarker();
    $this->m_arrOut['landline_desc'] = '';
    $this->m_arrOut['phone_res_status'] = "";
    if($objProfile->getPHONE_RES() && $objProfile->getPHONE_RES()!= ApiViewConstants::getNullValueMarker() )
    {
      $landline_label= "+".$objProfile->getISD()."-".$objProfile->getSTD()."-".$objProfile->getPHONE_RES();
      
      $tempArray = array();
      if(strlen($objProfile->getPHONE_NUMBER_OWNER())){
         $tempArray[] = $objProfile->getDecoratedLandlineNumberOwner();
        
        if(strlen($objProfile->getPHONE_OWNER_NAME())){
          $tempArray[] = $objProfile->getPHONE_OWNER_NAME();
        }
      }
            
      $landline_desc = implode(", ",$tempArray);
      if(strlen($landline_desc)){
        $this->m_arrOut['landline_desc'] = "used by " . $landline_desc;
      }
      
      //Phone Res Verification Status
      $this->m_arrOut['phone_res_status'] = "Verify";
      if($objProfile->getLANDL_STATUS()== "Y"){
        $this->m_arrOut['phone_res_status'] = "Verified";
      }
    }
    $this->m_arrOut['landline'] = $landline_label;
   
    //Time to call
    $time_to_call_label = ApiViewConstants::getNullValueMarker();
    if( $objProfile->getTIME_TO_CALL_START() && $objProfile->getTIME_TO_CALL_END() &&
        $objProfile->getTIME_TO_CALL_START() != ApiViewConstants::getNullValueMarker() && 
        $objProfile->getTIME_TO_CALL_END() != ApiViewConstants::getNullValueMarker()
      )
    {
      $time_to_call_label=$objProfile->getTIME_TO_CALL_START()." - ".$objProfile->getTIME_TO_CALL_END();
    }
    $this->m_arrOut['time_to_call'] = $time_to_call_label;
    
    
   
    
    
    
   
    
    //Address
    $this->m_arrOut['address'] = ApiViewConstants::getNullValueMarker();
    if($objProfile->getCONTACT() && $objProfile->getCONTACT()!=ApiViewConstants::getNullValueMarker()){
      $this->m_arrOut['address'] = $objProfile->getCONTACT();
      
      if($objProfile->getPINCODE() && $objProfile->getPINCODE() != ApiViewConstants::NULL_VALUE_MARKER){
        $this->m_arrOut['address'] .='-'.$objProfile->getPINCODE();
      }
    }
    
    //Parents Address
    $this->m_arrOut['parent_address'] = ApiViewConstants::getNullValueMarker();
    if($objProfile->getPARENTS_CONTACT() && $objProfile->getPARENTS_CONTACT()!=ApiViewConstants::getNullValueMarker()){
      $this->m_arrOut['parent_address'] = $objProfile->getPARENTS_CONTACT();
      
      if($objProfile->getPARENT_PINCODE() && $objProfile->getPARENT_PINCODE() != ApiViewConstants::NULL_VALUE_MARKER){
        $this->m_arrOut['parent_address'] .='-'.$objProfile->getPARENT_PINCODE();
      }
    }
    
    //Verification 
     $this->m_arrOut['my_verification_id'] = ApiViewConstants::getNullValueMarker();
    if($objProfile->getID_PROOF_TYP() && $objProfile->getID_PROOF_TYP()!=ApiViewConstants::getNullValueMarker() && $objProfile->getID_PROOF_NO() && $objProfile->getID_PROOF_NO()!=ApiViewConstants::getNullValueMarker()){
      $this->m_arrOut['my_verification_id'] = $objProfile->getDecoratedID_PROOF_TYP() .' - ' . $objProfile->getID_PROOF_NO();
    }
    if($this->bResponseForEditView){
        $verifyDocsObj = new ProfileDocumentVerificationByUserService();
        $this->Docs = $verifyDocsObj->getDocumentsList($objProfile->getPROFILEID()); 
        if($this->Docs){
            $this->m_arrOut['addr_proof_type'] = $verifyDocsObj->getDecoratedProof('addr_proof_type', $this->Docs['ADDR']['PROOF_TYPE']);
            $this->m_arrOut['id_proof_type'] = $verifyDocsObj->getDecoratedProof('id_proof_type', $this->Docs['ID']['PROOF_TYPE']);
        }
    }
  }
  
  /*
   * getResponse
   */
  public function getResponse(){
    $out = parent::getResponse();
    
    $this->m_arrOut = array();
    if($this->bResponseForEditView){
      $this->getDecorate_ContactDetails();
      $out["contact"] = $this->m_arrOut;
    }
    return $out;
  }
  private function getCasteLabelForGrouping($casteArr){
        $casteArr = trim(str_replace("'1'", '', $casteArr),',');
        $casteArr = trim(str_replace("'153'", '', $casteArr),',');
        $casteArr = trim(str_replace("'148'", '', $casteArr),',');
        $casteArr = trim(str_replace("'496'", '', $casteArr),',');
        $casteGroupArr=FieldMap::getFieldLabel("caste_group_array",'',1);
        foreach(explode(",",$casteArr) as $v)
        {
            $label[] = (FieldMap::getFieldLabel("caste_without_religion",trim($v,"'"))).(array_key_exists(trim($v,"'"),$casteGroupArr)?"- All":"");
        }
        $casteArr=implode(", ",$label);
return $casteArr;

  }
   protected  function getMembershipType()
    {
        //Service Type
        $serviceType = $this->m_objProfile->getSUBSCRIPTION();
        $value = CommonFunction::getMainMembership($serviceType);
        if($value == false)
            $value = null;
        
        return $value;
    }
}
