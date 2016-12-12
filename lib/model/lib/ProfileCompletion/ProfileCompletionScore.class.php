<?php
/**
 * 
 * The Profile Completion Score For Desktop And Mobile Version of Site
 * Returns Percentage and Details of Incomplete Section and Link of those Section
 * Also Contain an API response function
 * 
 * 
 * Example how to call
 * <code>
 * // If LoggedInProfile Objects is Given <br>
 * $cScoreObject = ProfileCompletionFactory::getInstance(null,$objProfile,null); <br>
 * // If ProfileID is Given <br>
 * $cScoreObject = ProfileCompletionFactory::getInstance(null,null,$ProfileID); <br> 
 * $iPCS = $cScoreObject->getProfileCompletionScore(); <br>
 * $arrMsgDetails = $cScoreObject->GetIncompleteDetails(); <br>
 * $arrLinkDetails = $cScoreObject->GetLink(); <br>
 * $arrAPI  = $cScoreObject->GetAPIResponse(); <br>
 * </code>
 * 
 * @package jeevansathi
 * @subpackage ProfileCompletion
 * @author Kunal Verma
 * @created 17th Dec 2013
 */
/**
 * Class For Calculating Profile Completion Score for Desktop And Mobile Site
 * @package ProfileCompletion
 * @author  Kunal Verma
 */
class ProfileCompletionScore extends AbstractProfileCompletionScore
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
	const INIT_ERROR = "Either LoggedInProfile object OR Profile-ID Expected While Creating ProfileCompletionScore Object";
	
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
			throw new jsException("",ProfileCompletionScore::INIT_ERROR);
		}
		
		$this->m_bIsScoreCalculated = false;
		$this->m_bIsIncompleteDetailsFilled = false;
		
		$arrSectionKey = array('ME','CAREER','RELIGION','ASTRO','FAMILY','LIFE','HOBBY','PHOTO', 'BASIC');
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
    * About Me Section
    * @param void
    * @return Percentage of Section Complete
    * @access private
    */
	private function Calc_AboutMe_Section()
	{
		//About ME Section - (20%)
		$iPercentage  =0;
		
		$szInfo = $this->m_objProfile->getYOURINFO();
		$iLen = strlen($szInfo);
		
		$this->m_arrSection_Max['ME'] = 25; //Earlier 20, now increased to 25. Family info moved from family section to About Me.
		
		if($iLen>=100 && $iLen <200)
		{
			$iPercentage+=15;
			
			$this->m_arrIncompletePercentage['ME'] = 5;
		}
		else if($iLen >= 200)
		{
			$iPercentage +=20;
			$this->m_arrIncompletePercentage['ME'] = 0;
		}
		else
		{
			$this->m_arrIncompletePercentage['ME'] = 20;
		}
        
        if($this->m_objProfile->getFAMILYINFO())
		{
			$iPercentage+=7;
		}
		else
		{
			$this->m_arrIncompletePercentage['ME']+= 7;
			$this->m_bShow_AboutFamilyLayer = true;
		}
        
        if($this->m_arrIncompletePercentage['ME'] == 0)
		{
			$this->m_arrSectionStatus['ME'] = "C";
		}
		else if($this->m_arrIncompletePercentage['ME'] == $this->m_arrSection_Max['ME'])
		{
			$this->m_arrSectionStatus['ME'] = "N";
		}
		else
		{
			$this->m_arrSectionStatus['ME'] = "P";
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
		//Career Details - (20%) 
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
		
		//List Of Occuption
		$arrOcc_Group = array('13','36','37','44','52');
		
		//Check Name of School
		/*if($arrEducation['SCHOOL'])
		{
			$iPercentage+=3;
			$this->m_arrSection_Max['CAREER'] +=3;
		}
		else
		{
			$this->m_arrIncompletePercentage['CAREER'] = 3;
			$this->m_arrSection_Max['CAREER'] +=3;
		}*/
		//Highest Degree Check
		$arrNotAllowedEdu_Level = array(23,24);//23(High School) & 24(Trade School)		
		//Check Name of College
		if($arrEducation['COLLEGE'] && !in_array($iHighestDegree,$arrNotAllowedEdu_Level))
		{
			$iPercentage+=2;
			$this->m_arrSection_Max['CAREER'] +=2;
		}
		else if(!in_array($iHighestDegree,$arrNotAllowedEdu_Level))
		{
			$this->m_arrIncompletePercentage['CAREER'] += 2;
			$this->m_arrSection_Max['CAREER'] +=2;
		}
		
		//Check Name of PG College		
		if($arrEducation['PG_COLLEGE'] && in_array($iHighestDegree,$arrPG_Group))
		{
			$iPercentage+=2;
			$this->m_arrSection_Max['CAREER'] +=2;
		}
		else if(in_array($iHighestDegree,$arrPG_Group))
		{
			$this->m_arrIncompletePercentage['CAREER'] += 2;
			$this->m_arrSection_Max['CAREER'] +=2;
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
    * Religion & Ethnicitys Section
    * @param void
    * @return Percentage of Section Complete
    * @access private
    */
	private function Calc_Religion_Section()
	{
		//Religion & Ethnicity - (10%) And Astro in case of HINDU, JAIN, SIKH, AND BUDDHIST
		$iPercentage = 0;
		$this->m_arrSection_Max['RELIGION'] = 0;
		//Earlier set to 5, now set to 0. 'Native Place' moved to Family Details
        
		switch($this->m_objProfile->getRELIGION())
		{
			//
			case 1://Hindu
			case 4://Sikh
			case 7://Buddhist
			case 9://Jain
			{	
				$this->m_arrSection_Max['RELIGION'] += 0;
                //Shifted Gothra to Family Details. Henche updating it to 0.
				
				//Check For Astro Details
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
			}
			break;
		case 2://Muslim
			{
				$this->m_arrSection_Max['RELIGION'] += 0;
				//Shifted NAMAZ, ZAKAT, FASTING  to Lifestyle. updated max from 5 to 2;
                //Shifted sect to Basic. Updated from 2 to 0.
				$arrReligionInfo = $this->m_objProfile->getReligionInfo();
				
				$this->m_arrSectionStatus['ASTRO'] = "C";
				
				break;
			}
			case 3://Christain
				{
					$this->m_arrSection_Max['RELIGION'] += 0;
					//DIOCESE, BAPTISED, READ_BIBLE, OFFER_TITHE, SPREADING_GOSPEL moved from Religion to Lifestyle. Hence score changed from 5 to 0.
					$arrReligionInfo = $this->m_objProfile->getReligionInfo();
					$this->m_arrSectionStatus['ASTRO'] = "C";
					
					break;
				}
			}//End of switch
			
			if($this->m_arrIncompletePercentage['RELIGION'] === 0 )
			{
				$this->m_arrSectionStatus['RELIGION'] = 'C';
			}
			else if($this->m_arrIncompletePercentage['RELIGION'] === $this->m_arrSection_Max['RELIGION'] )
			{
				$this->m_arrSectionStatus['RELIGION'] = 'N';
			}
			else
			{
				$this->m_arrSectionStatus['RELIGION'] = 'P';
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
		// About Family -20%
        //Now increased to 25% by addition of Native Place
        //Decreased to 20% by removal of Family Info from here to About Me.
		$iPercentage = 0;
		$this->m_arrSection_Max['FAMILY'] = 21; 
		$this->m_bShow_AboutFamilyLayer = false;
        
        //Get Family Info moved from Family to About Me Section
		
        $this->m_arrIncompletePercentage['FAMILY'] = 0;
        //Earlier in Religion
        $objNativePlace = new JProfile_NativePlace($this->m_objProfile);
		if($objNativePlace->getCompletionStatus())
		{
			$iPercentage+=5;
		}
		else
		{
			$this->m_arrIncompletePercentage['FAMILY'] += 5;
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
        
        $religion = $this->m_objProfile->getRELIGION();
        if($religion == 1 || $religion == 4 || $religion == 7 || $religion == 9 )
        {
            $this->m_arrSection_Max['FAMILY'] += 5;
            if($this->m_objProfile->getGOTHRA())
            {
                $iPercentage+=5;
            }
            else
            {
                $this->m_arrIncompletePercentage['FAMILY'] +=5;
            }
        }
        
        
		return $iPercentage;
	}
	
	/**
    * Life Style Section
    * @param void
    * @return Percentage of Section Complete
    * @access private
    */
	private function Calc_LifeStyle_Section()
	{
				
		//Lifestyle
		$iPercentage =0;
		$this->m_arrSection_Max['LIFE'] = 3;
		
		if($this->m_objProfile->getDIET() && $this->m_objProfile->getSMOKE() && $this->m_objProfile->getDRINK())
		{
			$iPercentage+=1;
		}
		else
		{
			$this->m_arrIncompletePercentage['LIFE'] = 1 ;
		}
		
		if($this->m_objProfile->getCOMPLEXION() && ($this->m_objProfile->getBTYPE() || $this->m_objProfile->getWEIGHT()))
		{
			$iPercentage+=1;
		}
		else
		{
			$this->m_arrIncompletePercentage['LIFE'] += 1 ;
		}
		
		if($this->m_objProfile->getHANDICAPPED()!='N')
		{
			$val = $this->m_objProfile->getHANDICAPPED();
			if($val<=2 && $this->m_objProfile->getNATURE_HANDICAP())
			{
				$iPercentage+=1;
			}
			else if($val==3 || $val==4)
			{
				$iPercentage+=1;
			}
			else
			{
				$this->m_arrIncompletePercentage['LIFE'] += 1 ;
			}
		}
		else
		{
			$iPercentage+=1;
		}
        
        //For Muslim:
        //NAMAZ, ZAKAT, FASTING moved from Religion to Lifestyle
        
        if($this->m_objProfile->getRELIGION() == 2)
        {
            $this->m_arrSection_Max['LIFE'] += 3;
            
            $arrReligionInfo = $this->m_objProfile->getReligionInfo();
            
            if($arrReligionInfo->NAMAZ)
            {
                $iPercentage+=1;
            }
            else
            {
                $this->m_arrIncompletePercentage['LIFE'] +=1;
            }
            
            if($arrReligionInfo->ZAKAT)
            {
                $iPercentage+=1;
            }
            else
            {
                $this->m_arrIncompletePercentage['LIFE'] +=1;
            }
				
            if($arrReligionInfo->FASTING)
            {
                $iPercentage+=1;
            }	
            else
            {
                $this->m_arrIncompletePercentage['LIFE'] +=1;
            }
            
        }
        
        if($this->m_objProfile->getRELIGION() == 3)
        {
            $this->m_arrSection_Max['LIFE'] += 5;
            
            $arrReligionInfo = $this->m_objProfile->getReligionInfo();
            if($arrReligionInfo->DIOCESE)
            {
                $iPercentage+=1;
            }
            else
            {
                $this->m_arrIncompletePercentage['LIFE'] +=1;
            }


            if($arrReligionInfo->BAPTISED)
            {
                $iPercentage+=1;
            }
            else
            {
                $this->m_arrIncompletePercentage['LIFE'] +=1;
            }

            if($arrReligionInfo->READ_BIBLE)
            {
                $iPercentage+=1;
            }
            else
            {
                $this->m_arrIncompletePercentage['LIFE'] +=1;
            }

            if($arrReligionInfo->OFFER_TITHE)
            {
                $iPercentage+=1;
            }
            else
            {
                $this->m_arrIncompletePercentage['LIFE'] +=1;
            }

            if($arrReligionInfo->SPREADING_GOSPEL)
            {
                $iPercentage+=1;
            }
            else
            {
                $this->m_arrIncompletePercentage['LIFE'] +=1;
            }
        }
        
		
		if($this->m_arrIncompletePercentage['LIFE'] === 0)
		{
			$this->m_arrSectionStatus['LIFE'] = "C";
		}
		else if($this->m_arrIncompletePercentage['LIFE'] === $this->m_arrSection_Max['LIFE'])
		{
			$this->m_arrSectionStatus['LIFE'] = "N";
		}
		else
		{
			$this->m_arrSectionStatus['LIFE'] = "P";
		}
        
        
        
		return $iPercentage;
	}
	
	/**
    * Hobbies Section
    * @param void
    * @return Percentage of Section Complete
    * @access private
    */
	private function Calc_Hobbies_Section()
	{
		// Hobbies And Interest
        //Now changed to Your Likes
		$iPercentage =0;
		$this->m_arrSection_Max['HOBBY'] =2;
		
		$objHobbies = new JHOBBYCacheLib;
		$arrHobbies = $objHobbies->getUserHobbies($this->m_objProfile->getPROFILEID());
		
		if($arrHobbies['HOBBY'] || $arrHobbies['INTEREST'])
		{
			$iPercentage+=1;
		}
		else
		{
			$this->m_arrIncompletePercentage['HOBBY'] = 1 ;
		}
		
		if($arrHobbies['FAV_MOVIE'] || $arrHobbies['FAV_TVSHOW'] || $arrHobbies['FAV_BOOK'])
		{
			$iPercentage+=1;
		}
		else
		{
			$this->m_arrIncompletePercentage['HOBBY'] += 1 ;
		}
		
		if($this->m_arrIncompletePercentage['HOBBY'] === 0)
		{
			$this->m_arrSectionStatus['HOBBY'] = "C";
		}
		else if($this->m_arrIncompletePercentage['HOBBY'] === $this->m_arrSection_Max['HOBBY'])
		{
			$this->m_arrSectionStatus['HOBBY'] = "N";
		}
		else
		{
			$this->m_arrSectionStatus['HOBBY'] = "P";
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
    
    private function Calc_BasicDetails_Section()
    {
        //Basic Details section contains only Sect for Muslim
        $iPercentage  =0;
        $religion = $this->m_objProfile->getRELIGION();
        if($religion == 2){
            $this->m_arrSection_Max['BASIC'] = 2;
            
            if($this->m_objProfile->getSECT() && $this->m_objProfile->getSECT()!=0)
            {
                $iPercentage+=2;
                $this->m_arrSectionStatus['BASIC'] = "C";
            }
            else
            {
                $this->m_arrIncompletePercentage['BASIC'] =2;
            }
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
		
		//About ME
		$iPercentage+= $this->Calc_AboutMe_Section();
		
		//Career
		$iPercentage+= $this->Calc_Career_Section();
		
		//Religion
		$iPercentage+= $this->Calc_Religion_Section();
		
		//Family
		$iPercentage+= $this->Calc_Family_Section();
		
		//LifeStyle
		$iPercentage+= $this->Calc_LifeStyle_Section();
		
		//Hobbies 
		$iPercentage+= $this->Calc_Hobbies_Section();
		
    //Basic 
    $iPercentage+= $this->Calc_BasicDetails_Section();
    
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
		$iCount2 = $objHoroscope2->getHoroscopeIfNotDeleted($iProfileID);*/
		
		$objHoroscope3 = ProfileAstro::getInstance();
		$iCount3 = $objHoroscope3->getIfAstroDetailsPresent($iProfileID);
		
		if($iCount3) /*$iCount1 || $iCount2 ||*/
			return true;
			
		return false;
	}
	
	
	/**
    * To Bake Messages as per the Section Status And Incomplete Percentage
    * 
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
		
		if(count($arrOutTemp)!=1 && count(array_unique($arrOutTemp)) < count($arrOutTemp)){
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
							
		$arrPreFix = array('PHOTO'=>"Upload", "DEFAULT"=>"Add",'ME'=>"Write About");
		
		$arrString = array('PHOTO'=>" Photos", 'CAREER'=>" Career Details",
						   'FAMILY'=>" Family Details",'RELIGION'=>" Ethnicity Details",
						   'ASTRO'=>" Horoscope Details",'LIFE'=>" Lifestyle Details",
						   'HOBBY'=>" Your Likes", 'ME'=>" You & Family", 'BASIC'=>" Basic Details" );
						   
		$szMore = " more";
		//$szBrings = " will bring ";
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
			
			$szMsg .= $arrString[$key] . $szSymbols ."$arrPercentage[$key]%";
			$arrOut[$key] = $szMsg;
		}
        
		return $arrOut;
	}
	
	/**
    * User defined comparing functions used in Sorting Section in case of TIE(See PRD)
    * 
    * @param $key1 : String
    * @param $key2 : String
    * @return 0 (when key1 == key2) , -1 (when key1 < key2) , 1 (when key1 > key2)
    * @access private
    */
	private function CompareKeys($key1,$key2)
	{
		
		$arrOrder = array('PHOTO'=>'0','CAREER'=>'1','FAMILY'=>'2','RELIGION'=>'3','ASTRO'=>'4','ME'=>'5','LIFE'=>'6','HOBBY'=>'7');
	
		if($arrOrder[$key1]==$arrOrder[$key2])
			return 0;
		return ($arrOrder[$key1] < $arrOrder[$key2])?-1:1;
	}
	
	
	/**
    * Sorting Sections As per Order Specified in PRD
    * 
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
				
				usort($arrEle,array("ProfileCompletionScore", "CompareKeys"));		
			
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
    * 
    * @param $arrInput
    * @return Array of Links 
    * @access private
    */
	private function CreateLinkArray($arrInput,$szFromPage='')
	{
		$arrEditLink = array(
							'PHOTO'=>"/social/addPhotos",
							'CAREER'=>"career",
							'FAMILY'=>"family",
							'RELIGION'=>"RelEthnic",
							'ASTRO'=>"horoscope",
							'LIFE'=>"lifestyle",
							'HOBBY'=>"likes",
							'ME'=>"about",
                            'BASIC'=>"basic"
						);
		$arrMyJSLink = array(
								'PHOTO'=>"",
								'CAREER'=>"EditWhatNew=EduOcc",
								'FAMILY'=>"EditWhatNew=FamilyDetails",
								'RELIGION'=>"EditWhatNew=RelEthnic",
								'ASTRO'=>"EditWhatNew=AstroData",
								'LIFE'=>"EditWhatNew=LifeStyle",
								'HOBBY'=>"EditWhatNew=Interests",
								'ME'=>"EditWhatNew=PMF",
                                'BASIC'=>"EditWhatNew=Basic"
						);
		if(MobileCommon::isDesktop())				
        {
            if($szFromPage == "MyJS")
            {
                $arrEditLink = array(
							'PHOTO'=>"/social/addPhotos",
							'CAREER'=>"/profile/viewprofile.php?ownview=1&from=MyJS&EditWhatNew=career",
							'FAMILY'=>"/profile/viewprofile.php?ownview=1&from=MyJS&EditWhatNew=family",
							'RELIGION'=>"/profile/viewprofile.php?ownview=1&from=MyJS&EditWhatNew=RelEthnic",
							'ASTRO'=>"/profile/viewprofile.php?ownview=1&from=MyJS&EditWhatNew=horoscope",
							'LIFE'=>"/profile/viewprofile.php?ownview=1&from=MyJS&EditWhatNew=lifestyle",
							'HOBBY'=>"/profile/viewprofile.php?ownview=1&from=MyJS&EditWhatNew=likes",
							'ME'=>"/profile/viewprofile.php?ownview=1&from=MyJS&EditWhatNew=about",
                            'BASIC'=>"/profile/viewprofile.php?ownview=1&from=MyJS&EditWhatNew=basic"
						);
            }
            $arrInputLink = $arrEditLink;
        }
        else
        {
            if($this->m_bShow_AboutFamilyLayer && $this->m_arrIncompletePercentage['FAMILY'] ==5)
            {	
                $arrEditLink['FAMILY'] = "/profile/editProfile?flag=PMF&width=700&for_fam=1&ajax_error=1";
                $arrMyJSLink['FAMILY'] = "EditWhatNew=PMF";
            }

            $arrInputLink = $arrEditLink;
            if($szFromPage!='' && $szFromPage == "MyJS")
            {
                $arrInputLink = $arrMyJSLink;
            }
        }
		$arrOutLink = array();				
		
		foreach($arrInput as $key=>$val)
		{
			$arrOutLink[$key] = $arrInputLink[$key];
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
	public function	GetIncompleteDetails($topFewMsg = '')
	{
		
		if($this->m_bIsScoreCalculated != true)
			return false;
		
		if($this->m_bIsIncompleteDetailsFilled)
			return $this->m_arrMessage;
			
		if($this->m_iPercentage < 100 && $this->m_bIsIncompleteDetailsFilled == false)
		{
			$this->m_arrMessage = $this->BakeMsg();
			$this->m_bIsIncompleteDetailsFilled = true;
            if($topFewMsg){
                $this->m_arrMessage = $this->getTopFewMsg($this->m_arrMessage, $topFewMsg);
            }
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
	public function GetLink($szFromPage='')
	{
		if($this->m_iPercentage < 100 && $this->GetIncompleteDetails())
		{
			$this->m_arrLink = $this->CreateLinkArray($this->m_arrMessage,$szFromPage);
			return $this->m_arrLink;
		}
		
		return null;
	}

	 /**
	 * Public function to Get API Response 
	 * @param void
	 * @return : In Case of Incomplete profile : Array of all sections with incomplete percentage details
	 *         : Else null
	 * @access public
	 */
	public function GetAPIResponse()
	{
		if($this->m_bIsIncompleteDetailsFilled == false)
		{
			if($this->m_bIsScoreCalculated == false)
				$this->Calculate();
			
			if($this->GetLink() == null)
			{
				// Log Error
				// 
				return null;
			}
		}
		
		$iLen1 = count($this->m_arrIncompletePercentage);
		$iLen2 = count($this->m_arrLink);
		
		if($iLen1===0 || $iLen2===0)
			return null;
			
		foreach($this->m_arrLink as $szKey => $szVal)
		{
			$iIncompletePerctage = $this->m_arrIncompletePercentage[$szKey] ;

			$arrApiResponse[$szKey] = array('PERCENTAGE'=>$iIncompletePerctage,'LINK'=>$szVal);
		}
		return $arrApiResponse;
	}
    
    public function getTopFewMsg($msgArray, $noOfMsg)
    {
        $i = 0;
        foreach ($msgArray as $key => $value){
            if($i < $noOfMsg){
               $arrMsgDetails[$key] = $value;
               $i++;
            }
        }
        return $arrMsgDetails;
    }
}
?>
