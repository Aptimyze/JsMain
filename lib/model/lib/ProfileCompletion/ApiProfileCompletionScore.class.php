<?php
/**
 * The Profile Completion Score For API 
 * Returns Percentage and Details of Incomplete Section and Link of those Section
 * Also Contain an API response function
 * 
 * Example how to call
 * <code>
 * // If LoggedInProfile Objects is Given <br>
 * $cScoreObject = ProfileCompletionFactory::getInstance("API",$objProfile,null); 
 * // If ProfileID is Given <br>
 * $cScoreObject = ProfileCompletionFactory::getInstance("API",null,$ProfileID);<br>
 * $iPCS = $cScoreObject->getProfileCompletionScore();<br>
 * $arrMsgDetails = $cScoreObject->GetIncompleteDetails();<br>
 * $arrLinkDetails = $cScoreObject->GetLink();<br>
 * $arrAPI  = $cScoreObject->GetAPIResponse();<br>
 * </code>
 * 
 * @package jeevansathi
 * @subpackage ProfileCompletion
 * @author Kunal Verma
 * @created 30th Dec 2013
 */

/**
 * Class For Calculating Profile Completion Score for API
 * @package ProfileCompletion
 * @author  Kunal Verma
 */
class ApiProfileCompletionScore extends AbstractProfileCompletionScore
{
	/**
	 * 
	 * This variable holds the amount of profile complete(in percentage) .
	 * @access private
	 * @var Integer
	 */
	private $m_iPercentage;
	
	/**
	 * 
	 * This variable holds the list of section and its corresponding status
	 * Filled By N(None Filled) , P(Paritialy Filled) and C(Complete)
	 * @access private
	 * @var Array
	 */	
	private $m_arrSectionStatus;
	
	/**
	 * 
	 * This variable holds the list of section and its corresponding incomplete percentage
	 * @access private
	 * @var Array
	 */
	private $m_arrIncompletePercentage;
	
	/**
	 * 
	 * This variable holds the list of section and its Corresponding MAX Percentage Assigned
	 * @access private
	 * @var Array
	 */
	private $m_arrSection_Max;
	
	/**
	 * 
	 * This variable holds the list of section and its Corresponding Message which will be shown on View
	 * @access private
	 * @var Array
	 */
	private $m_arrMessage;
	
	/**
	 * 
	 * This variable holds the list of section and its Corresponding Link to complete the profile 
	 * @access private
	 * @var Array
	 */	
	private $m_arrLink;
	
	/**
	 * 
	 * This variable holds the Status of Score Calculation is Done Or Not
	 * @access private
	 * @var Boolean
	 */
	private $m_bIsScoreCalculated;

	/**
	 * 
	 * This variable holds the Status of List of message is generated or not
	 * @access private
	 * @var Boolean
	 */
	private $m_bIsIncompleteDetailsFilled;
	

	/*
	 * Custom Error Messages For Throwing Exceptions
	 */
	const INIT_ERROR = "Either LoggedInProfile object OR Profile-ID Expected While Creating ApiProfileCompletionScore Object";
	
	/**
	 * 
	 * Constructor for initializing object of ProfileCompletionScore class
	 * @param $Var
	 * $Var Can be ProfileObject Or ProfileID(Integer Value)
	 * @throws jsException
	 */
	public function __construct($Var)
	{
		
        $this->m_objProfile = null;
		parent::initProfileObject($Var);
        if(null === $this->m_objProfile)
		{
			throw new jsException("",ApiProfileCompletionScore::INIT_ERROR);
		}
		
		$this->m_bIsScoreCalculated = false;
		$this->m_bIsIncompleteDetailsFilled = false;
				
		$arrSectionKey = array('PHOTO','EDUCATION','CAREER','FAMILY','BASIC','ASTRO','LIFESTYLE');
	
		$this->m_arrSectionStatus = array();//Filled By N(None Filled) , P(Paritialy Filled) and C(Complete)
		$this->m_arrIncompletePercentage = array();// Left Out Percentage
		$this->m_arrSection_Max = array();
		
		// Initializing arrays
		foreach($arrSectionKey as $key)
		{
			$this->m_arrSectionStatus[$key] = 'N';
			$this->m_arrIncompletePercentage[$key] = 0;
			$this->m_arrSection_Max[$key] = 0;
		}
		
		
	}
/////// Calculating Score As Per Sections And Updating Status and Incomplete Percentage in members variables////////
	/**
    * Basic Details Section
    * @param void
    * @return Percentage of Section Complete
    * @access private
    */
	private function Calc_Basic_Section()
	{
		//Basic Section - (32%)
		$iPercentage  =0;
		
		$szInfo = $this->m_objProfile->getYOURINFO();
		$iLen = strlen($szInfo);
		
		$this->m_arrSection_Max['BASIC'] = 27;
		
		if($iLen>=100 && $iLen <200)
		{
			$iPercentage+=15;
			$this->m_arrIncompletePercentage['BASIC'] = 5;
		}
		else if($iLen >=200)
		{
			$iPercentage +=20;
		}
		else
		{
			$this->m_arrIncompletePercentage['BASIC'] = 20;
		}
		
		// Check Complexion and Challenged
		if($this->m_objProfile->getCOMPLEXION() && ($this->m_objProfile->getBTYPE() || $this->m_objProfile->getWEIGHT()))
		{
			$iPercentage+=1;
		}
		else
		{
			$this->m_arrIncompletePercentage['BASIC'] += 1 ;
		}
		
		
		$szHandicapped = $this->m_objProfile->getHANDICAPPED();
		if($szHandicapped != 'N')
		{
			if($szHandicapped<=2 && $this->m_objProfile->getNATURE_HANDICAP())
			{
				$iPercentage+=1;
			}
			else if($szHandicapped==3 || $szHandicapped==4)
			{
				$iPercentage+=1;
			}
			else
			{
				$this->m_arrIncompletePercentage['BASIC'] += 1 ;
			}
		}
		else
		{
			$iPercentage+=1;
		}
		
		// Check Religion
		if(/*Christian*/ 3 == $this->m_objProfile->getRELIGION()) 
		{ 
			$this->m_arrSection_Max['BASIC'] += 5;
			
			$arrReligionInfo = $this->m_objProfile->getReligionInfo();
							
			if($arrReligionInfo->DIOCESE)
			{
				$iPercentage+=1;
			}
			else
			{
				$this->m_arrIncompletePercentage['BASIC'] +=1;
			}
			
			
			if($arrReligionInfo->BAPTISED)
			{
				$iPercentage+=1;
			}
			else
			{
				$this->m_arrIncompletePercentage['BASIC'] +=1;
			}
			
			if($arrReligionInfo->READ_BIBLE)
			{
				$iPercentage+=1;
			}
			else
			{
				$this->m_arrIncompletePercentage['BASIC'] +=1;
			}
			
			if($arrReligionInfo->OFFER_TITHE)
			{
				$iPercentage+=1;
			}
			else
			{
				$this->m_arrIncompletePercentage['BASIC'] +=1;
			}
			
			if($arrReligionInfo->SPREADING_GOSPEL)
			{
				$iPercentage+=1;
			}
			else
			{
				$this->m_arrIncompletePercentage['BASIC'] +=1;
			}
		}
		else if(/*Muslim*/ 2 == $this->m_objProfile->getRELIGION())
		{
			$arrReligionInfo = $this->m_objProfile->getReligionInfo();

			$this->m_arrSection_Max['BASIC'] += 5;		
			
			if($arrReligionInfo->NAMAZ)
			{
				$iPercentage+=1;
			}
			else
			{
				$this->m_arrIncompletePercentage['BASIC'] +=1;
			}
			
			
			if($arrReligionInfo->ZAKAT)
			{
				$iPercentage+=1;
			}
			else
			{
				$this->m_arrIncompletePercentage['BASIC'] +=1;
			}
			
			if($arrReligionInfo->FASTING)
			{
				$iPercentage+=1;
			}	
			else
			{
				$this->m_arrIncompletePercentage['BASIC'] +=1;
			}
			
			if($this->m_objProfile->getSECT() && $this->m_objProfile->getSECT()!=0)
			{
				$iPercentage+=2;
			}
			else
			{
				$this->m_arrIncompletePercentage['BASIC'] +=2;
			}
		}
		
		//Native Place
		$objNativePlace = new JProfile_NativePlace($this->m_objProfile);
		if($objNativePlace->getCompletionStatus())
		{
			$iPercentage+=5;
		}
		else
		{
			$this->m_arrIncompletePercentage['BASIC'] +=5;
		}
			
		//Gothra Check for Hindu, Sikh , Jain and Buddisht Only
		$iReligion = $this->m_objProfile->getRELIGION();
		$arrAllowed = array(1,4,7,9);
		
		if(in_array($iReligion,$arrAllowed))
		{
			$this->m_arrSection_Max['BASIC'] +=5;
			if($this->m_objProfile->getGOTHRA())
			{
				$iPercentage+=5;
			}
			else
			{
				$this->m_arrIncompletePercentage['BASIC'] +=5;
			}
		}	
		
		if($this->m_arrIncompletePercentage['BASIC'] === 0)
		{
			$this->m_arrSectionStatus['BASIC'] = 'C';
		}
		else if($this->m_arrIncompletePercentage['BASIC'] === $this->m_arrSection_Max['BASIC'])
		{
			$this->m_arrSectionStatus['BASIC'] = 'N';
		}
		else
		{
			$this->m_arrSectionStatus['BASIC'] = 'P';
		}
		
		
		return $iPercentage;
	}
	
	/**
    * Astro Section
    * @param void
    * @return Percentage of Section Complete
    * @access private
    */
	private function Calc_Astro_Section()
	{
		//Check For Astro Details
		// For Hindu , Sikh, Jain And Buddhist Only
		$arrReligion = array(1,4,7,9);
		$iReligion = $this->m_objProfile->getRELIGION();
		
		if(!in_array($iReligion,$arrReligion))
		{
			$this->m_arrSectionStatus['ASTRO'] = "C";
			$this->m_arrSection_Max['ASTRO'] = 0;
			return 0;
		}
		
		$this->m_arrSection_Max['ASTRO'] = 10;
		
		if($this->m_objProfile->getMANGLIK())
		{
			$iPercentage+=3;
		}
		else
		{
			$this->m_arrIncompletePercentage['ASTRO'] += 3;
		}
		
		if($this->m_objProfile->getRASHI())
		{
			$iPercentage +=1;
		}
		else
		{
			$this->m_arrIncompletePercentage['ASTRO'] += 1;
		}
		if($this->m_objProfile->getNAKSHATRA())
		{
			$iPercentage +=1;
		}
		else
		{
			$this->m_arrIncompletePercentage['ASTRO'] += 1;
		}
		
		if($this->IsHoroscopeFilled($this->m_objProfile->getPROFILEID()))
		{
			$iPercentage += 5;
		}
		else
		{
			$this->m_arrIncompletePercentage['ASTRO'] += 5;
		}
		
		if($this->m_arrIncompletePercentage['ASTRO'] === 0 || $this->m_arrIncompletePercentage['ASTRO'] == 0)
		{
			$this->m_arrSectionStatus['ASTRO'] = "C";
		}
		else if($this->m_arrIncompletePercentage['ASTRO'] < $this->m_arrSection_Max['ASTRO'])
		{
			$this->m_arrSectionStatus['ASTRO'] = "P";
		}
		else
		{
			$this->m_arrSectionStatus['ASTRO'] = "N";
		}
		
		return $iPercentage;
	}	
	
	/**
    * Education Section
    * @param void
    * @return Percentage of Section Complete
    * @access private
    */
	private function Calc_Education_Section()
	{
		// Get Education Details from JPROFILE_EDUCATION
		
		$iPercentage = 0;
		$jsEdu = ProfileEducation::getInstance();
		$arrEducation = $jsEdu->getProfileEducation($this->m_objProfile->getPROFILEID());
                
		$iHighestDegree = $this->m_objProfile->getEDU_LEVEL_NEW();
	
		//List of Master Degree
		$arrPG_Degree = FieldMap::getFieldLabel("degree_grouping",'',1);
		$arrPG_Group = $arrPG_Degree['PG'];
		$arrPG_Group = explode(" , ",$arrPG_Group);
		
		foreach($arrPG_Group as $key=>$val)
		{
			$arrPG_Group[$key] = trim($val);
		}
				
		//Check Name of School
		/*if($arrEducation['SCHOOL'])
		{
			$iPercentage+=3;
			$this->m_arrSection_Max['EDUCATION'] +=3;
		}
		else
		{
			$this->m_arrIncompletePercentage['EDUCATION'] = 3;
			$this->m_arrSection_Max['EDUCATION'] +=3;
		}*/
		//Highest Degree Check
		$arrNotAllowedEdu_Level = array(23,24);//23(High School) & 24(Trade School)		
		//Check Name of College
		if($arrEducation['COLLEGE'] && !in_array($iHighestDegree,$arrNotAllowedEdu_Level))
		{
			$iPercentage+=2;
			$this->m_arrSection_Max['EDUCATION'] +=2;
		}
		else if(!in_array($iHighestDegree,$arrNotAllowedEdu_Level))
		{
			$this->m_arrIncompletePercentage['EDUCATION'] += 2;
			$this->m_arrSection_Max['EDUCATION'] +=2;
		}
		
		//Check Name of PG College		
		if($arrEducation['PG_COLLEGE'] && in_array($iHighestDegree,$arrPG_Group))
		{
			$iPercentage+=2;
			$this->m_arrSection_Max['EDUCATION'] +=2;
		}
		else if(in_array($iHighestDegree,$arrPG_Group))
		{
			$this->m_arrIncompletePercentage['EDUCATION'] += 2;
			$this->m_arrSection_Max['EDUCATION'] +=2;
		}
                
                //Check UG Degree		
		if($arrEducation['UG_DEGREE'] && !in_array($iHighestDegree,$arrNotAllowedEdu_Level))
		{
			$iPercentage+=4;
			$this->m_arrSection_Max['CAREER'] +=4;
		}
		else if(!in_array($iHighestDegree,$arrNotAllowedEdu_Level))
		{
			$this->m_arrIncompletePercentage['CAREER'] += 4;
			$this->m_arrSection_Max['CAREER'] +=4;
		}
                //Check PG Degree		
		if($arrEducation['PG_DEGREE'] && in_array($iHighestDegree,$arrPG_Group))
		{
			$iPercentage+=4;
			$this->m_arrSection_Max['CAREER'] +=4;
		}
		else if(in_array($iHighestDegree,$arrPG_Group))
		{
			$this->m_arrIncompletePercentage['CAREER'] += 4;
			$this->m_arrSection_Max['CAREER'] +=4;
		}
		
		if($this->m_arrIncompletePercentage['EDUCATION'] === $this->m_arrSection_Max['EDUCATION'])
		{
			$this->m_arrSectionStatus['EDUCATION'] = 'N';
		}
		else if($this->m_arrIncompletePercentage['EDUCATION'] === 0)
		{
			$this->m_arrSectionStatus['EDUCATION'] = 'C';
		}
		else
		{
			$this->m_arrSectionStatus['EDUCATION'] = 'P';
		}
		return $iPercentage;
	}
	/**
    * Career Section
    * @param void
    * @return Percentage of Section Complete
    * @access private
    */
	private function Calc_Career_Section()
	{
		//Career Details - (5%) 
	
		$iPercentage = 0;
			
		//List Of Occuption
		$arrOcc_Group = array('13','36','37','44','52');
	
		if($this->m_objProfile->getOCCUPATION() && !in_array($this->m_objProfile->getOCCUPATION(),$arrOcc_Group))
		{
			if($this->m_objProfile->getCOMPANY_NAME())
				$iPercentage+=2;
			else
				$this->m_arrIncompletePercentage['CAREER'] += 2;
			$this->m_arrSection_Max['CAREER'] +=2;
		}
		
		
		if($this->m_arrIncompletePercentage['CAREER'] == 0)
		{
			$this->m_arrSectionStatus['CAREER'] = "C";
		}
		else if($this->m_arrIncompletePercentage['CAREER'] == $this->m_arrSection_Max['CAREER'])
		{
			$this->m_arrSectionStatus['CAREER'] = "N";
		}
		else
		{
			$this->m_arrSectionStatus['CAREER'] = "P";
		}
		
		return $iPercentage;
	}
	/**
    * Family Section
    * @param void
    * @return Percentage of Section Complete
    * @access private
    */
	private function Calc_Family_Section()
	{
		// About Family - 20%
		$iPercentage = 0;
		
		$this->m_arrSection_Max['FAMILY'] = 21;
		
		if($this->m_objProfile->getFAMILYINFO())
		{
			$iPercentage+=7;
		}
		else
		{
			$this->m_arrIncompletePercentage['FAMILY'] = 7;
		}
		
		if($this->m_objProfile->getFAMILY_BACK())
		{
			$iPercentage+=4;
		}
		else
		{
			$this->m_arrIncompletePercentage['FAMILY'] +=4;
		}
		
		if($this->m_objProfile->getMOTHER_OCC())
		{
			$iPercentage+=4;
		}
		else
		{
			$this->m_arrIncompletePercentage['FAMILY'] +=4;
		}
		
		if($this->m_objProfile->getFAMILY_INCOME() ||  $this->m_objProfile->getFAMILY_STATUS())
		{
			$iPercentage+=1;
		}
		else
		{
			$this->m_arrIncompletePercentage['FAMILY'] +=1;
		}
		
		if($this->m_objProfile->getFAMILY_VALUES())
		{
			$iPercentage+=1;
		}
		else
		{
			$this->m_arrIncompletePercentage['FAMILY'] +=1;
		}
		
		if($this->m_objProfile->getFAMILY_TYPE())
		{
			$iPercentage+=1;
		}
		else
		{
			$this->m_arrIncompletePercentage['FAMILY'] +=1;
		}
			
		$numBrother = $this->m_objProfile->getT_BROTHER();
		$numSister = $this->m_objProfile->getT_SISTER();
		$numMarriedBrother = $this->m_objProfile->getM_BROTHER();
		$numMarriedSister = $this->m_objProfile->getM_SISTER();
		
		if(strlen($numBrother)!=0 && strlen($numSister)!=0  && is_numeric($numBrother) && is_numeric($numSister) && $numBrother>=0 && $numSister>=0 && $numMarriedBrother<=$numBrother && $numMarriedSister<=$numSister)
		{
			$iPercentage+=3;
		}
		else
		{
			$this->m_arrIncompletePercentage['FAMILY'] +=3;
		}		
		
		
		if($this->m_arrIncompletePercentage['FAMILY']===0)
		{
			$this->m_arrSectionStatus['FAMILY'] = "C";
		}
		else if($this->m_arrIncompletePercentage['FAMILY'] === $this->m_arrSection_Max['FAMILY'])
		{
			$this->m_arrSectionStatus['FAMILY'] = "N";
		}
		else
		{
			$this->m_arrSectionStatus['FAMILY'] = "P";
		}
		
		return $iPercentage;
	}

	/**
    * Life Style And Hobby Section
    * @param void
    * @return Percentage of Section Complete
    * @access private
    */
	private function Calc_LifeStyle_AND_Hobby_Section()
	{
				
		//Lifestyle
		$iPercentage =0;
		$this->m_arrSection_Max['LIFESTYLE'] = 3;
		
		if($this->m_objProfile->getDIET() && $this->m_objProfile->getSMOKE() && $this->m_objProfile->getDRINK())
		{
			$iPercentage+=1;
		}
		else
		{
			$this->m_arrIncompletePercentage['LIFESTYLE'] = 1 ;
		}
		
				
		$objHobbies = new JHOBBYCacheLib;
		$arrHobbies = $objHobbies->getUserHobbies($this->m_objProfile->getPROFILEID());
		
		if($arrHobbies['HOBBY'] || $arrHobbies['INTEREST'])
		{
			$iPercentage+=1;
		}
		else
		{
			$this->m_arrIncompletePercentage['LIFESTYLE'] = 1 ;
		}
		
		if($arrHobbies['FAV_MOVIE'] || $arrHobbies['FAV_TVSHOW'] || $arrHobbies['FAV_BOOK'])
		{
			$iPercentage+=1;
		}
		else
		{
			$this->m_arrIncompletePercentage['LIFESTYLE'] += 1 ;
		}
		
		
		if($this->m_arrIncompletePercentage['LIFESTYLE'] === 0)
		{
			$this->m_arrSectionStatus['LIFESTYLE'] = "C";
		}
		else if($this->m_arrIncompletePercentage['LIFESTYLE'] === $this->m_arrSection_Max['LIFESTYLE'])
		{
			$this->m_arrSectionStatus['LIFESTYLE'] = "N";
		}
		else
		{
			$this->m_arrSectionStatus['LIFESTYLE'] = "P";
		}
		return $iPercentage;
	}
	
	/**
    * Photo Section
    * @param void
    * @return Percentage of Section Complete
    * @access private
    */
	private function Calc_Photo_Section()
	{
		// Photo
		$iMaxPercentage = 0;
		foreach($this->m_arrSection_Max as $val)
		{
			$iMaxPercentage+= $val;
		}
		
		$iPhotoScore = 100 - $iMaxPercentage;
		
		$this->m_arrSection_Max['PHOTO'] = $iPhotoScore;

		if($this->m_objProfile->getHAVEPHOTO() && ($this->m_objProfile->getHAVEPHOTO() =='Y' || $this->m_objProfile->getHAVEPHOTO() == 'U'))
		{
			$iPicCount = parent::getUploadPicCount();
			if($iPicCount >= 2)
			{
				$iPercentage+=$iPhotoScore;
				$this->m_arrSectionStatus['PHOTO'] = "C";
			}
			else
			{
				$iPercentage += floor($iPhotoScore/2);
				$this->m_arrIncompletePercentage['PHOTO'] = $iPhotoScore - floor($iPhotoScore/2);
				$this->m_arrSectionStatus['PHOTO'] = "P";
			}
		}
		else
		{
			$this->m_arrIncompletePercentage['PHOTO'] = $iPhotoScore ;
			$this->m_arrSectionStatus['PHOTO'] = "N";
		}
		return $iPercentage;
	}
	
	/**
    * Calculate Profile Completion Score And Update Member Variables
    * @param void
    * @return void
    * @access private
    */
	private function Calculate()
	{
		$iPercentage = 0;
		
		//Basic
		$iPercentage+= $this->Calc_Basic_Section();
		
		//Education
		$iPercentage+= $this->Calc_Education_Section();
		
		//Career
		$iPercentage+= $this->Calc_Career_Section();
				
		//Family
		$iPercentage+= $this->Calc_Family_Section();
		
		//LifeStyle And Hobby
		$iPercentage+= $this->Calc_LifeStyle_AND_Hobby_Section();
		
		//Astro
		$iPercentage+= $this->Calc_Astro_Section();
		
		//Photo
		$iPercentage+= $this->Calc_Photo_Section();
		
		$this->m_iPercentage			= $iPercentage;
		$this->m_bIsScoreCalculated		= true;
        
	}
	
	/**
    * To Check Horoscope Status in various Store
    * 
    * @param $iProfileID : Integer
    * @return TRUE : If horoscope exist in any store otehrwise FALSE
    * @access private
    */		
	private function IsHoroscopeFilled($iProfileID)
	{
		if($iProfileID === null || $iProfileID == null )
			return false;
			
		/*$objHoroscope1 = new newjs_HOROSCOPE;
		$iCount1 = $objHoroscope1->getIfHoroscopePresent($iProfileID);
		
		$objHoroscope2 = new NEWJS_HOROSCOPE_FOR_SCREEN;
		$iCount2 = $objHoroscope2->getHoroscope($iProfileID);*/
		
		$objHoroscope3 = ProfileAstro::getInstance();
		$iCount3 = $objHoroscope3->getIfAstroDetailsPresent($iProfileID);
		
		if($iCount3) /*$iCount1 || $iCount2 ||*/
			return true;
			
		return false;
	}
	
	/**
    * To Bake Messages as per the Section Status And Incomplete Percentage
    * @param void
    * @return Array of Details 
    * @access private
    */
	private function BakeMsg()
	{
		$arrOutTemp = array();
		$arrOut = array();
		
		$arrStatus  = $this->m_arrSectionStatus;
		$arrPercentage = $this->m_arrIncompletePercentage;
		
		$iRelationID = $this->m_objProfile->getRELATION();
		$relationKey = $iRelationID;
		
		if($iRelationID == 2 || $iRelationID == 3)
		{
			$gender = $this->m_objProfile->getGENDER();
			$relationKey = $iRelationID.$gender;
		}
		
		arsort($arrPercentage);
		foreach($arrPercentage as $key=>$val)
		{
			if($val!=0)
			{
				$arrOutTemp[$key]=$val;
			}
			else
				break;
		}
		
		if(count($arrOutTemp)!=1 && count(array_unique($arrOutTemp)) <= count($arrOutTemp)){
			$arrSorted = $this->SortSection($arrOutTemp);
		}
		else{
			$arrSorted = $arrOutTemp;
		}
				
		foreach($arrSorted as $key=>$val)
		{
			$arrOut[$key] = "";
		}
		
		$arrRelation = array('1'=>" yourself",'2M'=>" your son",'2F'=>" your daughter",'4'=>" your relative",
							'5'=>" your client",'3M'=>" your brother",'3F'=>" your sister");
							
		$arrPreFix = array('PHOTO'=>"Upload", "DEFAULT"=>"Add",'BASIC'=>"Write more About");
		
		$arrString = array('PHOTO'=>" Photos", 'CAREER'=>" Career Details",
						   'FAMILY'=>" Family Details",'ASTRO'=>" Astro Details",
						   'LIFESTYLE'=>" Lifestyle Details",'EDUCATION'=>" Education Details", 
						   'BASIC'=>" You" );
						   
		$szMore = " more";
		//$szBrings = " will brings ";
		$szSymbols = ": +";
		foreach($arrOut as $key => $val)
		{
			$szMsg = "";
			
			if(array_key_exists($key,$arrPreFix))
			{
				$szMsg .= $arrPreFix[$key];
			}
			else
			{
				$szMsg .= $arrPreFix['DEFAULT'];
			}
			
			if(($arrStatus[$key] == 'P' || $arrStatus[$key] == "P") && $key=='PHOTO')
			{
				$szMsg .= $szMore;
			}
			
			$szMsg .= $arrString[$key] . $szSymbols ."$arrPercentage[$key]%.";
			$arrOut[$key] = $szMsg;
		}
				
		return $arrOut;
	}
	
	/**
    * User defined comparing functions used in Sorting Section in case of TIE(See PRD)
    * @param $key1 : String
    * @param $key2 : String
    * @return 0 (when key1 == key2) , -1 (when key1 < key2) , 1 (when key1 > key2)
    * @access private
    */	
	private function CompareKeys($key1,$key2)
	{
		
		$arrOrder = array('PHOTO'=>'0','EDUCATION'=>'1','CAREER'=>'2','FAMILY'=>'3','BASIC'=>'4','ASTRO'=>'5','LIFESTYLE'=>'6');
	
		if($arrOrder[$key1]==$arrOrder[$key2])
			return 0;
		return ($arrOrder[$key1] < $arrOrder[$key2])?-1:1;
	}
	
	/**
    * Sorting Sections As per Order Specified in PRD
    * @param $arrInput
    * @return $arrInput in Sorted Order Using CompareKeys for Sorting
    * @access private
    */
	private function SortSection($arrInput)
	{
		$arrOutput = array();
		$iStIndex = -1;
		$iEndIndex = -1;
		$val = 0;
		$iCount =0;
	
		for($iCount=0;$iCount<count($arrInput);$iCount++)
		{
			
			$szNext = next($arrInput);
			
			if($szNext != false && current($arrInput) == $szNext && !$val )
			{
				$val = current($arrInput);
				$iStIndex = $iCount;
			}
			else
			{
				if($szNext === false)
					prev($arrInput);
					
				$arrOutput[key($arrInput)] = current($arrInput);
			}
			
			if($val !=0)
			{
				$iCtr =0;
				$arrTemp = array_reverse($arrInput);
				foreach($arrTemp as $Value)
				{
					
					if($val == $Value)
						break;
					
					++$iCtr;
				}
				$iCtr = count($arrInput) - $iCtr;
				$iEndIndex = $iCtr;
				
				$val = 0;
				$len = $iEndIndex - $iStIndex;
				
				$arrEle = array_slice($arrInput,$iStIndex,$len,true);
				$arrEle = array_keys($arrEle);
				
				usort($arrEle,array("ApiProfileCompletionScore", "CompareKeys"));		
			
				foreach($arrEle as $szKey)
				{
					$arrOutput[$szKey] = $arrInput[$szKey];
				} 
				
				$iCount =$iEndIndex-1; 
			}
			
		}//End of For Loop
		return $arrOutput;
	}
	
	/**
    * To Create Link Arrays as per the incomplete detail array
    * @param $arrInput
    * @return Array of Links 
    * @access private
    */
	private function CreateLinkArray($arrInput)
	{
		$arrLink = array(
							'PHOTO'=>"/social/addPhotos",
							'EDUCATION'=>"profile/editProfile?flag=PEO&width=700&ajax_error=1",
							'CAREER'=>"profile/editProfile?flag=PEO&width=700&ajax_error=1",
							'FAMILY'=>"/profile/editProfile?flag=PFD&width=700&ajax_error=1",
							'ASTRO'=>"/profile/editProfile?flag=CUH&width=700&ajax_error=1",
							'LIFESTYLE'=>"profile/editProfile?flag=PLA&width=700&ajax_error=1",
							'BASIC'=>"/profile/editProfile?flag=PMF&width=700&for_fam=1&ajax_error=1" 
						);
		$arrOutLink = array();				
		foreach($arrInput as $key=>$val)
		{
			$arrOutLink[$key] = $arrLink[$key];
		}
		return $arrOutLink;
	}
	

///////////////////////////////PUBLIC FUNCTIONS////////////////////////////////////////////////////////////////////
	/**
    * To getProfileCompletionScore
    * @param void
    * @return Percentage(integer)
    * @access public
    */
	public function getProfileCompletionScore()
	{
        if($this->m_bIsDeletedProfile)
        {
            return ;
        }
         
		if($this->m_bIsScoreCalculated == false)
		{
			$this->Calculate();
		}
		
		return $this->m_iPercentage;
	}
	
	 /**
	 * Public function to Get Incomplete Section Details
	 * @param void
	 * @return : In Case of Incomplete profile : Array of all messages which we display to User on View
	 *         : Else null
	 * @access public
	 */
	public function	GetIncompleteDetails()
	{
		
		if($this->m_bIsScoreCalculated != true)
			return false;
		
		if($this->m_bIsIncompleteDetailsFilled)
			return $this->m_arrMessage;
			
		if($this->m_iPercentage < 100 && $this->m_bIsIncompleteDetailsFilled == false)
		{
			$this->m_arrMessage = $this->BakeMsg();
			$this->m_bIsIncompleteDetailsFilled = true;
			return $this->m_arrMessage;
		}
		
		return null;
	}
	 /**
	 * Public function to Get Link of Incomplete Sections 
	 * @param void
	 * @return : In Case of Incomplete profile : Array of all links which we display to User on View
	 *         : Else null
	 * @access public
	 */
	public function GetLink()
	{
		if($this->m_iPercentage < 100 && $this->GetIncompleteDetails())
		{
			$this->m_arrLink = $this->CreateLinkArray($this->m_arrMessage);
			return $this->m_arrLink;
		}
		
		return null;
	}
	
	 /**
	 * Public function to Get API Response 
	 * @param $szPage : String
	 * @return : In Case of Incomplete profile : Array of all sections with incomplete percentage details
	 *         : Else null
	 * @access public
	 */
	public function GetAPIResponse($szPage=null)
	{
		if($this->m_bIsIncompleteDetailsFilled == false)
		{
			if($this->m_bIsScoreCalculated == false)
			{
				$this->Calculate();
			}	
			$this->GetLink();
		}
			
		$arrKeyID = array('PHOTO'=>'1','EDUCATION'=>'2','CAREER'=>'3','FAMILY'=>'4','LIFESTYLE'=>'5','BASIC'=>'6','ASTRO'=>'7');
		
		$arrTitle = array('PHOTO'=>'Photos','EDUCATION'=>'My Education','CAREER'=>'My Career','FAMILY'=>'About my family','LIFESTYLE'=>'Lifestyle','BASIC'=>'Basic Details','ASTRO'=>'Astro/Kundli Details');
			
		$arrTitleJS = array('PHOTO'=>'Photos','EDUCATION'=>'Education','CAREER'=>'Career','FAMILY'=>'Family','LIFESTYLE'=>'Lifestyle','BASIC'=>'Basic','ASTRO'=>'Kundli');
		
		$cssMap=array('Photos'=>'camera','Basic'=>'basicdetail','Kundli'=>'myjs_kundli','Education'=>'myjs_edu','Career'=>'myjs_career','Family'=>'myjs_family','Lifestyle'=>'lifestyle_2');
		$urlMap=array('Photos'=>'Album','Basic'=>'Details','Kundli'=>'Kundli','Education'=>'Education','Career'=>'Career','Family'=>'Family','Lifestyle'=>'Lifestyle');
				
if($szPage && $szPage == "MYJS")
			$arrTitle = $arrTitleJS;
		if(is_array($this->m_arrMessage))
		{
			foreach($this->m_arrMessage as $szKey => $szVal)
			{
				$iIncompletePerctage = $this->m_arrIncompletePercentage[$szKey];
				$szkeyID			 = $arrKeyID[$szKey];
				$szTitle			 = $arrTitle[$szKey];
				$arrApiResponse[] = array('id'=>$szkeyID,'title'=>$szTitle,'per'=>strval($iIncompletePerctage),'cssClass'=> $cssMap[$arrTitle[$szKey]],'url'=>'/profile/viewprofile.php?ownview=1#'.$urlMap[$szTitle]);
			}
		}
		
		$iReligion = $this->m_objProfile->getRELIGION();
		
		$arrAllowedReligion  = array(1,4,7,9);
		foreach($this->m_arrSectionStatus as $szKey => $szVal)
		{
			//If Religion is not From Hindu,Jain,Sikh,Buddhist then skip Astro Section
			if(!in_array($iReligion,$arrAllowedReligion) && $szKey == 'ASTRO')
				continue;
			if(!is_array($this->m_arrMessage) || !array_key_exists($szKey,$this->m_arrMessage))
			{
				$iIncompletePerctage = $this->m_arrIncompletePercentage[$szKey];
				$szkeyID			 = $arrKeyID[$szKey];
				$szTitle			 = $arrTitle[$szKey];
				$arrApiResponse[] = array('id'=>$szkeyID,'title'=>$szTitle,'per'=>strval($iIncompletePerctage),'cssClass'=> $cssMap[$arrTitle[$szKey]],'url'=>'/profile/viewprofile.php?ownview=1#'.$urlMap[$szTitle]);
			}
		}
		return $arrApiResponse;
	}
}
?>
