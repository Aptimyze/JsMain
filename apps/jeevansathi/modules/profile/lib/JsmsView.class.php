<?php
/**
 * JsmsView.class.php
 */
 
/**
 * Class JsmsView Used For Decorating Response for profile for JSMS produc
 * This class extends DetailedViewApi class
 * @package    jeevansathi
 * @subpackage profile
 * @author     Kunal Verma
 * @version    15th Dec 2014
 */
class JsmsView extends DetailedViewApi
{
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
    $selfHavePhoto = $this->m_actionObject->loginProfile->getHAVEPHOTO();

    $havePhoto=$this->m_objProfile->getHAVEPHOTO();   
        if($havePhoto=='Y'){
            if($this->m_actionObject->THUMB_URL) {
                $thumbNailArray = PictureFunctions::mapUrlToMessageInfoArr($this->m_actionObject->THUMB_URL,'ThumbailUrl','',$this->m_actionObject->loginProfile->getGENDER());
                
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

      //thumbnail for self
      if($selfHavePhoto != "N")
      {
      	$pictureServiceObj=new PictureService($this->m_actionObject->loginProfile);
      	$ProfilePicUrlObj = $pictureServiceObj->getProfilePic();
      	$this->ProfilePicUrl='';
      	if (is_subclass_of($ProfilePicUrlObj, 'Picture'))
      	{
      		$this->profilePicPictureId = $ProfilePicUrlObj->getPICTUREID();               
      		$this->thumbnailPic = $ProfilePicUrlObj->getThumbailUrl();                             
      	}
      }      
      else
      {
      	$this->thumbnailPic = PictureService::getRequestOrNoPhotoUrl('noPhoto', "ProfilePic235Url", $this->m_actionObject->loginProfile->getGENDER());
      }
    $this->m_arrOut["selfThumbail"] = $this->thumbnailPic;
       
}
	/**
	 * getDecorated_LifeStyle
	 * 
 	 * @param void
	 * @return void 
	 * @access private
	 */
	protected function getDecorated_LifeStyle()
	{
		$objProfile = $this->m_objProfile;
		
		//LifeStyle
		$szLifeStyle = "";
		
		$arrTemp = array();
		$arrTemp1 = array();
		
		//Diet
		if(strlen($objProfile->getDIET())!=0 && $objProfile->getDIET() != ApiViewConstants::getNullValueMarker())
		{
			$arrTemp1[] = $objProfile->getDecoratedDiet();
		}
		
		//Drink
		if(strlen($objProfile->getDRINK())!=0 && $objProfile->getDRINK() != ApiViewConstants::getNullValueMarker())
		{
			$arrTemp[] = ApiViewConstants::$arrDrinkLabel[$objProfile->getDRINK()];
		}
		
		//Smoke
		if(strlen($objProfile->getSMOKE())!=0 && $objProfile->getSMOKE() != ApiViewConstants::getNullValueMarker())
		{
			$arrTemp[] = ApiViewConstants::$arrSmokeLabel[$objProfile->getSMOKE()];
		}
		if(is_array($arrTemp1) && count($arrTemp1))
		{
			$arrTemp[] = implode(", ",$arrTemp1);
		}
		//Open to pets
		if($objProfile->getOPEN_TO_PET() && $objProfile->getOPEN_TO_PET() != ApiViewConstants::getNullValueMarker())
		{
			$arrTemp[]  = ApiViewConstants::$arrPets_Preference[$objProfile->getOPEN_TO_PET()]['text'];
		}
		if(is_array($arrTemp) && count($arrTemp))
		{
			$szLifeStyle = implode(", ",$arrTemp);
		}
		
		if($szLifeStyle == "")
			$szLifeStyle = null;
			
		$this->m_arrOut['lifestyle'] = $szLifeStyle;
		
		//Residential status in Country
		$this->m_arrOut['res_status'] = null;
		if($objProfile->getDecoratedCountry() && $objProfile->getDecoratedCountry() != "India" && $objProfile->getDecoratedRstatus() )
		{
			$this->m_arrOut['res_status'] = $objProfile->getDecoratedRstatus() ." in " . $objProfile->getDecoratedCountry();
		}
		
		//House And Car
		$this->m_arrOut['assets'] = null;
		$cHouse = (strlen($objProfile->getOWN_HOUSE())>0)?$objProfile->getOWN_HOUSE():'N';
		$cCar 	= (strlen($objProfile->getHAVE_CAR())>0)?$objProfile->getHAVE_CAR():'N';
				
		if(in_array($cHouse,ApiViewConstants::$YES) && in_array($cCar,ApiViewConstants::$YES))
		{
			$this->m_arrOut['assets'] = ApiViewConstants::$arrHouseAndCar[$cHouse.$cHouse][$cCar];
		}
		else if(in_array($cHouse,ApiViewConstants::$YES) || in_array($cCar,ApiViewConstants::$YES))
		{
			$this->m_arrOut['assets'] = ApiViewConstants::$arrHouseAndCar[$cHouse][$cCar];
		}
		
		// Hobbies,Interest,DressStyle,Fav-Books,Movies,TvShow,Cuisine,Food		
		$arrHobbies = $objProfile->getHobbies();
		foreach(ApiViewConstants::$arrHobbies as $key => $val)
		{
			$this->m_arrOut[strtolower($key)] = null;
			if($arrHobbies->$val && $arrHobbies->$val != ApiViewConstants::getNullValueMarker()  )
				$this->m_arrOut[strtolower($key)] = $this->DecorateOpenTextField($arrHobbies->$val); 
		}		
		
		//Spoken Languages
		$this->m_arrOut['skills_speaks'] = null;
		$this->m_arrOut['skills_i_cook'] = null;
		if($arrHobbies && $arrHobbies->LANGUAGE != $arrHobbies->nullValueMarker )
		{
			$this->m_arrOut['skills_speaks'] =  "Speaks " . $arrHobbies->LANGUAGE;
		}
		//Food I can cook
		if($arrHobbies && $this->m_arrOut['i_cook'] && $this->m_arrOut['i_cook'] != $arrHobbies->nullValueMarker)
		{
			$this->m_arrOut['skills_i_cook'] = 'Can cook ' . $this->m_arrOut['i_cook'];
		}
		unset($this->m_arrOut['i_cook']);
	}
    
    protected function getDecorated_MoreReligionInfo()
	{
		$objProfile = $this->m_objProfile;
		$objProfile->setNullValueMarker("");
		
		$iReligion = $objProfile->getRELIGION();
		$arrReligionInfo = $this->m_arrReligionInfo;
		$arrReligionInfo = (array) $arrReligionInfo;
		
		$this->m_arrOut['muslim_m'] = null;
		$this->m_arrOut['christian_m'] = null;
		$this->m_arrOut['sikh_m'] = null;
		
		switch($iReligion)
		{
			case 2:// Muslim
			{		
				foreach(ApiViewConstants::$arrMulsim_key as $key => $val)
				{
					if(array_key_exists($val,$arrReligionInfo) && $arrReligionInfo[$val]){
						$label  = ApiViewConstants::$arrMulsim_keyLabel[$val];
						$value 	= $arrReligionInfo[$val];
						$arrMoreInfo[strtolower($val)] = array('label'=>$label,'value'=>$value);
                    }
				}
				
				if(isset($arrMoreInfo['hijab_marriage']['value']) && $objProfile->getGENDER() == 'F')
				{
					$arrMoreInfo['hijab_marriage']['label'] =ApiViewConstants::HIJAB_AFTER_MARRIAGE;
				
					unset($arrMoreInfo['hijab']);
					$arrMoreInfo['hijab'] = $arrMoreInfo['hijab_marriage'];
					unset($arrMoreInfo['hijab_marriage']);
				}
				else if(isset($arrMoreInfo['hijab_marriage']['value']) && $objProfile->getGENDER() == 'M')
                {
			unset($arrMoreInfo['hijab_marriage']);
                }
               
                if($arrMoreInfo['working_marriage'] && strlen($arrMoreInfo['working_marriage']))
                {
                   $arrMoreInfo['working_marriage']['value'] = ApiViewConstants::$arrWorkingMarriage[strtoupper(substr($arrMoreInfo['working_marriage']['value'],0,1))];
                }
                    
				if($objProfile->getGENDER() == 'F' && isset($arrMoreInfo['working_marriage']))
					unset($arrMoreInfo['working_marriage']);
				$this->m_arrOut['muslim_m'] = $arrMoreInfo;
			}
			break;
			case 3://Christian
			{
				$szChristianInfo = null;
				
				$arrChristianInfo = array();
				
				foreach(ApiViewConstants::$arrChristian_Key as $key => $val)
				{
					$value = ApiViewConstants::$arrChristian[$val][$arrReligionInfo[$val]];
					if($arrReligionInfo[$val] && $value)
					{
						$arrChristianInfo[] = $value;
					}
				}
				
				$szChristianInfo = implode(", ",$arrChristianInfo);
				
				if($szChristianInfo == "")
					$szChristianInfo = null;
				
				$this->m_arrOut['christian_m'] = $szChristianInfo;
			}
			break;
			case 4://Sikh
			{
				$szSikhInfo = "";
				$arrSikhInfo = array();
				if(in_array(strtoupper($arrReligionInfo['AMRITDHARI']),ApiViewConstants::$NO))
				foreach(ApiViewConstants::$arrSikh_Key as $key => $val)
				{
					$szStr = $arrReligionInfo[$val];
					
					if($szStr != '')
						$value = ApiViewConstants::$arrSikh[$val][$szStr];
					
					if($arrReligionInfo[$val] && $value)
					{
						if($val != 'CUT_HAIR' && $objProfile->getGENDER() == 'F')
						{
							continue; // For Male Only Check WEAR_TURBAN And CLEAN_SHAVEN Case
						}
						$arrSikhInfo[] = $value;
					}
				}
				
				$szSikhInfo = implode(", ",$arrSikhInfo);
				
				if($szSikhInfo == "")
					$szSikhInfo = null;

				$this->m_arrOut['sikh_m'] = $szSikhInfo;
			}
			break;
		}//End of Switch
		$objProfile->setNullValueMarker("");
		
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
    }
}


?>
