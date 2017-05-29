<?php
/**
 * @class JProfile_NativePlace
 * @brief contains get methods for native place 
 * @author Kunal Verma
 * @created 2014-05-21
 */

/**
 * JProfile_NativePlace class implementing all logic related to native place fields(Country,city,state)
 * 
 */
class JProfile_NativePlace
{
	/**
	 * 
	 * This variable holds the object of LoggedInProfile/ProfileObject.
	 * @access private
	 * @var Profile ( Instance of LoggedInProfile/ProfileObject ) 
	 */
	private $m_objProfile;
	
	/**
	 * 
	 * This variable holds the profileid
	 * @access private
	 * @var Integer 
	 */
	private $m_iProfileID;
	
	/**
	 * 
	 * This variable holds the raw value of open text field
	 * @access private
	 * @var String 
	 */
	private $m_szOpenTextValue;
	
	/**
	 * 
	 * This variable holds the decorated value of open text field (with screening msg)
	 * @access private
	 * @var String 
	 */
	private $m_szDecorate_OpenTextValue;
	
	/**
	 * 
	 * This variable holds the raw value of NATIVE_CITY column in newjs.NATIVE_PLACE
	 * @access private
	 * @var String 
	 */
	private $m_szNativeCity;
	
	/**
	 * 
	 * This variable holds the decorated value of NATIVE_CITY column in newjs.NATIVE_PLACE
	 * @access private
	 * @var String 
	 */
	private $m_szDecorate_NativeCity;
	
	/**
	 * 
	 * This variable holds the raw value of NATIVE_COUNTRY column in newjs.NATIVE_PLACE
	 * @access private
	 * @var String 
	 */
	private $m_szNativeCountry;
	
	/**
	 * 
	 * This variable holds the decorated value of NATIVE_COUNTRY column in newjs.NATIVE_PLACE
	 * @access private
	 * @var String 
	 */
	private $m_szDecorate_NativeCountry;
	
	/**
	 * 
	 * This variable holds the raw value of NATIVE_STATE column in newjs.NATIVE_PLACE
	 * @access private
	 * @var String 
	 */
	private $m_szNativeState;
	
	/**
	 * 
	 * This variable holds the decorated value of NATIVE_STATE column in newjs.NATIVE_PLACE
	 * @access private
	 * @var String 
	 */
	private $m_szDecorate_NativeState;
	
	/**
	 * 
	 * This variable holds the decorated value of Native Place as per the logic mentioned in trac #2745
	 * @access private
	 * @var String 
	 */
	private $m_szDecoratedViewField;
	
	/**
	 * 
	 * This variable holds the status of record exist in database or not
	 * @access private
	 * @var Boolean 
	 */
	private $m_bRecordExist=null;
	
	/**
	 *Constructor 
	 * @param argVar can be ProfileId or Object of Profile or LoggedInProfile class also
	 * @return void
	 * @access public
	 */
	public function __construct($argVar = '')
	{
		$this->extractInfo($argVar);
	}
	/**
	 * getInfo
	 * getInfo : Main function to initalized JProfile_NativePlace object with the Information
	 * 			After execution of this function all information can be accessed by public getMethod
	 * @access public
	 * @param $iProfileID
	 * @return array of information(Only Native_Country,Native_City,Native_state) 
	 */	
	public function getInfo($iProfileID = '')
	{
		if($iProfileID != '')
			$this->extractInfo($iProfileID);	
		
		if($this->m_iProfileID && is_numeric($this->m_iProfileID))
		{
			$storeObj = ProfileNativePlace::getInstance();
			$arrResult = $storeObj->getRecord($this->m_iProfileID);

			if($arrResult === null)
				$this->m_bRecordExist = false;
			else
				$this->m_bRecordExist = true;
					
			$this->assignInfo($arrResult);
			return $arrResult;
		}
		
	}
	/**
	 * extractInfo
	 * Extract info from  given arguement which could be of any of these type
	 * 				1) LoggedInProfile Object
	 * 				2) Profile Object
	 * 				3) Profile Id
	 * @access private
	 * @param argVar
	 * @return void
	 */	
	private function extractInfo($argVar)
	{
		if(is_object($argVar) && ($argVar instanceof Profile || $argVar instanceof LoggedInProfile))
		{
			$this->m_iProfileID 				= $argVar->getPROFILEID();
			$this->m_szOpenTextValue 			= $argVar->getANCESTRAL_ORIGIN();
			$this->m_szDecorate_OpenTextValue 	= $argVar->getDecoratedAncestralOrigin();
			$this->m_objProfile = $argVar;
		}
		else if($argVar && is_numeric($argVar))
		{
			$this->m_iProfileID = $argVar;
			$objProfile = new Profile();
			$objProfile->getDetail($this->m_iProfileID,"PROFILEID","*");
			$this->m_szOpenTextValue 			= $objProfile->getANCESTRAL_ORIGIN();
			$this->m_szDecorate_OpenTextValue 	= $objProfile->getDecoratedAncestralOrigin();
			$this->m_objProfile = $objProfile;
		}
		else if($argVar && is_string($argVar) && is_numeric(intval($argVar)))
		{
			$this->m_iProfileID = intval($argVar);
			$objProfile = new Profile();
			$objProfile->getDetail($this->m_iProfileID,"PROFILEID","*");
			$this->m_szOpenTextValue 			= $objProfile->getANCESTRAL_ORIGIN();
			$this->m_szDecorate_OpenTextValue 	= $objProfile->getDecoratedAncestralOrigin();
			$this->m_objProfile = $objProfile;
		}
		if($this->m_szDecorate_OpenTextValue == $this->m_objProfile->getNullValueMarker()){
      $this->m_szDecorate_OpenTextValue = "";
    }
	}
	/**
	 * assignInfo
	 * Assign respective value in raw and decorated member varaible of class, which can be accessed by get methods
	 * @access private
	 * @param arrInfo
	 * @return void
	 */	
	private function assignInfo($arrInfo)
	{
		if(is_array($arrInfo))
		{
			$this->m_szNativeCity		= $arrInfo[NATIVE_CITY];
			$this->m_szNativeState 		= ($arrInfo[NATIVE_STATE]==null||$arrInfo[NATIVE_STATE]=='0')?'':$arrInfo[NATIVE_STATE];
			$this->m_szNativeCountry 	= ($arrInfo[NATIVE_COUNTRY]==null||$arrInfo['NATIVE_COUNTRY']=='0')?'':$arrInfo[NATIVE_COUNTRY];
			
			$this->m_szDecorate_NativeCity 		= ($this->m_szNativeCity != '0') ?$this->getLabel($this->m_szNativeCity,'city_india') : ("Others");
			$this->m_szDecorate_NativeCountry 	= $this->getLabel($this->m_szNativeCountry,'country');
			$this->m_szDecorate_NativeState 	= $this->getLabel($this->m_szNativeState,'state_india');
		}	
		
		$this->BakeDecoratedNativePlace();
	}	
	/**
	 * BakeDecoratedNativePlace
	 * Create Native Place Field Value as per the fields(native city,state,country and open text field) and m_szDecoratedViewField contain final value, which will be used to display
	 * @access private
	 * @param void
	 * @return void
	 */
	private function BakeDecoratedNativePlace()
	{
                // if(MobileCommon::isApp()=="I")
                // {
                //         $this->m_szDecoratedViewField = $this->m_szDecorate_OpenTextValue;
                //         return;
                // }
		$szSuffixText = null;
		$szPrefixText = null;
		
		if(strlen($this->m_szDecorate_NativeState)!=0)
		{
			$szSuffixText = $this->m_szDecorate_NativeState;
		}
		else if(strlen($this->m_szDecorate_NativeCountry)!=0)
		{
			$szSuffixText = $this->m_szDecorate_NativeCountry;
		}
		
		if(strlen($this->m_szDecorate_NativeState)!=0 && strlen($this->m_szDecorate_NativeCity)!=0 &&  strlen($this->m_szOpenTextValue)==0 && ((strlen($this->m_szDecorate_OpenTextValue)!=0 && $this->m_szDecorate_OpenTextValue=='-') || strlen($this->m_szDecorate_OpenTextValue)==0))
		{
			$szPrefixText = $this->m_szDecorate_NativeCity;
		}
		else if(strlen($this->m_szDecorate_OpenTextValue)!=0 && $this->m_szDecorate_OpenTextValue!='-')
		{
			$szPrefixText = $this->m_szDecorate_OpenTextValue;
		}

		//Case:<OpenText Value>, <Country>
		//Showing <Country> Only 
		if($szPrefixText && $szSuffixText && $szSuffixText == $this->m_szDecorate_NativeCountry && stristr($szPrefixText,$this->m_szOpenTextValue)!=false)
		{
			$szPrefixText=null;
		}	
		
		if($szPrefixText)
		{
			if(stripos($szPrefixText,"green")!=false)
			{
				$szTemp = str_ireplace($this->m_szOpenTextValue, " ", $szPrefixText);
				$szPrefixText = $this->m_szOpenTextValue;
			}
			$arr[] = $szPrefixText;
		}
		
		if($szSuffixText)
			$arr[] = $szSuffixText;
		
		$this->m_szDecoratedViewField = null;
		if(is_array($arr) && count($arr))
			$this->m_szDecoratedViewField = implode(", ",$arr);
		//If Screening msg exist then append it	
		if($szTemp)
			$this->m_szDecoratedViewField .= $szTemp;
	}
	/**
	 * getLabel
	 * @access private
	 * @param $szValue : Value of Label
	 * @param $szFieldMapKey : Field Map Key 
	 * @return Label Value stored in FieldMapLib Class
	 */
	private function getLabel($szValue,$szFieldMapKey)
	{
		if(!strlen($szValue) || !strlen($szFieldMapKey))
			return null;
		
		return FieldMap::getFieldLabel($szFieldMapKey,$szValue);
	}
	/**
	 * getNativeCity
	 * @access public
	 * @param void
	 * @return Raw Value stored in NATIVE_CITY column of newjs.NATIVE_PLACE table
	 */
	public function getNativeCity()
	{
		return $this->m_szNativeCity;
	}
	/**
	 * getNativeState
	 * @access public
	 * @param void
	 * @return Raw Value stored in NATIVE_STATE column of newjs.NATIVE_PLACE table
	 */
	public function getNativeState()
	{
		return $this->m_szNativeState;
	}
	/**
	 * getNativeCountry
	 * @access public
	 * @param void
	 * @return Raw Value stored in NATIVE_COUNTRY column of newjs.NATIVE_PLACE table
	 */
	public function getNativeCountry()
	{
		return $this->m_szNativeCountry;
	}
	/**
	 * getOpenTextValue
	 * @access public
	 * @param void
	 * @return Raw Value stored in open text value 
	 */
	public function getOpenTextValue()
	{
		return $this->m_szOpenTextValue;
	}
	/**
	 * getDecorated_NativeCountry
	 * @access public
	 * @param void
	 * @return Decorated Country 
	 */
	public function getDecorated_NativeCountry()
	{
		return $this->m_szDecorate_NativeCountry;
	}
	/**
	 * getDecorated_NativeCity
	 * @access public
	 * @param void
	 * @return Decorated City 
	 */
	public function getDecorated_NativeCity()
	{
		return $this->m_szDecorate_NativeCity;
	}
	/**
	 * getDecorated_NativeState
	 * @access public
	 * @param void
	 * @return Decorated State 
	 */
	public function getDecorated_NativeState()
	{
		return $this->m_szDecorate_NativeState;
	}
	/**
	 * getDecorated_OpenTextValue
	 * @access public
	 * @param void
	 * @return Decorated String, After Screening Process
	 */
	public function getDecorated_OpenTextValue()
	{
		return $this->m_szDecorate_OpenTextValue;
	}
	/**
	 * getDecorated_ViewField
	 * @access public
	 * @param void
	 * @return Decorated String, which will be display on Detailed Profile Page
	 */
	public function getDecorated_ViewField()
	{
		if(strlen($this->m_szDecoratedViewField)==0)
    {   
      return $this->m_objProfile->getNullValueMarker();//"-";//Null Value Symbol
    }
		return $this->m_szDecoratedViewField;
	}
	/**
	 * IsRecordExist()
	 * @param void
	 * @return True; if profile data exist in newjs.Native_Place Table else FALSE
	 * @access public
	 */
	public function IsRecordExist()
	{
		return $this->m_bRecordExist;
	}
	/**
	 * getCompletionStatus() : Used for calculating completion status of fields
	 * @param void
	 * @return  true : if Fields are filled and can be marked as complete in profile completion score logic
	 * 			else returns false
	 * @access public
	 */
	public function getCompletionStatus()
	{
		if($this->m_bRecordExist === null)
			$this->getInfo();
		// If native_city and native_state are filled and native_city not marked as 'others'
		if(strlen($this->m_szNativeCity)!=0 && $this->m_szNativeCity !='0' && $this->m_szNativeState)	
		{
			return true;
		}
		
		//If State is filled and city is filled as 'others' option then open text filed must be filled
		if(strlen($this->m_szNativeCity)!=0 && $this->m_szNativeCity ==='0' && $this->m_szNativeState && strlen($this->m_szOpenTextValue)!=0)
		{
			return true;
		}
		//If native country is specified then india then marking as completely filled status
		if($this->m_szNativeCountry && $this->m_szNativeCountry != 51)
		{
			return true;
		}
		//Return false in any other scenario
		
		return false;
	}	
	/**
	 * HandleLegacy() : Used for Handling edit flow from Api
	 * @param iProfileID : ProfileId
	 * @param iProfileID : szAncestralOrigin - Provided by uses
	* @return  True : If szAncestralOrigin is either proper city or country(as per database) else False. This value is used for marking screening bit
	 * @access public
	 */
	public function HandleLegacy($iProfileID,$szAncestralOrigin)
	{
		$szState        	= '';
		$szCity         	= '0';
		$szCountry      	= '';
		$arrCountryMap 		= FieldMap::getFieldLabel("country",'',1);
		$arrCityMap 		= FieldMap::getFieldLabel("city_india",'',1);
		$bMarkScreen		= false;		
		$szAncestralOrigin 	= strtolower(trim($szAncestralOrigin));
		if(in_array(ucwords($szAncestralOrigin),$arrCountryMap))
		{
			$szCountry		= array_search(ucwords($szAncestralOrigin),$arrCountryMap);
			$szCity         = '';
			$szState        = '';
			$bMarkScreen	= true;
		}
		else if(in_array(ucwords($szAncestralOrigin),$arrCityMap))
		{
			$szCity         = array_search(ucwords($szAncestralOrigin),$arrCityMap);
			$szState        = substr($szCity,0,2);
			 if($szCity === $szState)//Case : State is present in open text field
				$szCity 	= '';
			$szCountry      = 51;
			$bMarkScreen	= true;
		}
		
		$nativePlaceArr = array('PROFILEID'=>$iProfileID,'NATIVE_COUNTRY'=>$szCountry,'NATIVE_STATE'=>$szState,'NATIVE_CITY'=>$szCity );
		$nativePlaceObj = ProfileNativePlace::getInstance();
		
		if($nativePlaceObj->InsertRecord($nativePlaceArr) === 0)
		{
			unset($nativePlaceArr[PROFILEID]);
			$nativePlaceObj->UpdateRecord($iProfileID,$nativePlaceArr);
		}
		return $bMarkScreen;
	}
	/**
	 * LogUpdate() : Log the update done from edit page.
	 * @param arrNativePlaceArr : Array of paramters  
     * @return  void
	 * @access public
	 */
	public function LogUpdate($arrNativePlaceArr)
	{
		$edit_storeObj = new NEWJS_EDIT_LOG_NATIVE_PLACE;
		$edit_storeObj->InsertRecord($arrNativePlaceArr);
		unset($edit_storeObj);
	}
}
?>
