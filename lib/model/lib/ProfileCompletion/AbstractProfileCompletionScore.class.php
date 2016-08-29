<?php
/**
 * Description of AbstractProfileCompletionScore
 * Abstract Class for ProfileCompletionScore
 * Declaration of all abstract method and definition of common method
 * @package ProfileCompletion
 * @author Kunal Verma
 * @created 31st march 2015
 */

abstract class AbstractProfileCompletionScore {
    /**
	 * 
	 * This variable holds the object of LoggedInProfile.
	 * @access protected
	 * @var Profile ( Instance of LoggedInProfile ) 
	 */
	protected  $m_objProfile;
    
    /*
     * This varaible holds the status of deletion , 
     * if profile is marked deleted then do not calculate its score
     * @access protected
     * @var Boolean
     */
    protected $m_bIsDeletedProfile=false;
    
    /*
     * Const List of Profile Fields
     */
    const LIST_FIELDS = 'PROFILEID,RASHI,NAKSHATRA,FAMILY_INCOME,FAMILY_VALUES,WEIGHT,GENDER,HAVEPHOTO,PHOTO_DISPLAY,RELIGION,EDUCATION,JOB_INFO,WORK_STATUS,HANDICAPPED,NATURE_HANDICAP,BTYPE,COMPLEXION,DIET,SMOKE,DRINK,SHOW_HOROSCOPE,HOROSCOPE_MATCH,MANGLIK,CITY_RES,FAMILYINFO,FAMILY_STATUS,FAMILY_TYPE,FAMILY_BACK,MOTHER_OCC,T_BROTHER,T_SISTER,M_BROTHER,M_SISTER,RELATION,PARENT_CITY_SAME,YOURINFO,PHONE_RES,PHONE_MOB,SUBCASTE,CASTE,EDU_LEVEL,EDU_LEVEL_NEW,EDU_LEVEL,OCCUPATION,INCOME,PRIVACY,COMPANY_NAME,MOB_STATUS,LANDL_STATUS,PHONE_FLAG,ANCESTRAL_ORIGIN,SECT,GOTHRA,ACTIVATED';
   
    /*
     * Const Varaible For THRESHOLD SCORE
     * If score is less then threshold score then fire Email to improve score
     * @var Integer
     * @access Const
     */
    CONST THRESHOLD_SCORE = 60;
    
    /*
     * Const Varaible For THRESHOLD SCORE
     * If score is less then threshold score then fire Email to improve score
     * @var Integer
     * @access Const
     */
    CONST IMPROVE_SCORE_MAILER_ID = 1794;
    
    /*
     * Force Extending class to define this method
     */
    /**
    * To getProfileCompletionScore
    * @param void
    * @return Percentage(integer)
    * @access public
    */	
    abstract public function getProfileCompletionScore();
       
    /*
     * initProfile Object
     * 
     * Initalize the profile object with all the information
     * @access protected
     * @return void
     */
    protected function initProfileObject($Var)
    {
        if($Var instanceof Profile)
		{
			$this->m_objProfile = $Var;
		}	
		else if(is_numeric($Var))
		{
			$this->m_objProfile = null;
			$iProfileID = $Var;
		}	
		else
		{
			throw new jsException('','Profile Object Init Error');
		}
        
        //Fill Up Details 
		if(null === $this->m_objProfile && $iProfileID)
		{
            $this->m_objProfile = LoggedInProfile::getInstance("",$iProfileID);
            $this->m_objProfile->getDetail($iProfileID,"PROFILEID",self::LIST_FIELDS,"RAW");			
		}
		else
        {
            $this->m_objProfile->getDetail("","",self::LIST_FIELDS);
        }
        $this->m_objProfile->setNullValueMarker("");
        //If Activated is set to D then, profile is delete marked
		if(!$this->m_objProfile->getACTIVATED() || $this->m_objProfile->getACTIVATED()=='D')
        {
            $this->m_bIsDeletedProfile = true;
        }
    }
    
    /*
     * Update the Profile Completion Score
     * @access protected
     * @return void
     */
    protected function updateScore($iScore)
    {
        //If Deleted marked then do not store
        if($this->m_bIsDeletedProfile)
            return ;
        
        //If non numeric score or null then dont update
        if(!is_numeric(intval($iScore)) || !$iScore)
		{
			$subject = "ProfileCompletionScore : Score is non numeric score or null";
            $szMailBody = "Profileid of user is : ".$this->m_objProfile->getPROFILEID();
            $szMailBody .= "\n\n'".print_r($this->m_objProfile,true)."'";
            
			SendMail::send_email("kunal.test02@gmail.com",$szMailBody,$subject);
			return ;
		}   
		try{
			//Create Object of Store Table
			$objScoreTable = new PROFILE_PROFILE_COMPLETION_SCORE;
			$objScoreTable->replaceRecord($this->m_objProfile->getPROFILEID(), intval($iScore));
		}
		catch(Exception $e)
		{     LoggingManager::getInstance()->logThis(LoggingEnums::LOG_ERROR,$e);
			$subject = "ProfileCompletionScore : Exception While updaing score";
			SendMail::send_email("kunal.test02@gmail.com","'".print_r($e,true)."'",$subject);
		}
    }
    
    /*
     * get Upload Pic Count
     * @access Protected
     * @return Profile Pic Count
     */
    protected function getUploadPicCount() {
        $picService = new PictureService($this->m_objProfile);
        $iPicCount = $picService->getUserUploadedPictureCount();
        unset($picService);
        return $iPicCount;
    }
    
    /*
     * updateProfileCompletionScore
     * Its calculate and update score on store
     * @access Public
     * @return Profile Completion Score
     */
    public function updateProfileCompletionScore()
    {
        $iScore = $this->getProfileCompletionScore();
        $this->updateScore($iScore);
        
        return $iScore;
    }
    
    /*
     * Improve Score Mailer
     * Function to send Email If score is less than  60%
     */
    public function improveScoreMailer($iScore="")
    {
      if(!$iScore){
        $iScore = $this->getProfileCompletionScore();
      }
      
      if($iScore  && $iScore >= self::THRESHOLD_SCORE){
        return $iScore;
      }
       
      $objImproveScoreMailer =new EmailSender(MailerGroup::IMPROVE_SCORE,self::IMPROVE_SCORE_MAILER_ID);
      $objImproveScoreMailer->setProfileId($this->m_objProfile->getPROFILEID()); 
      $objImproveScoreMailer->send();
      
      unset($objImproveScoreMailer);
      return $iScore;
    }
}
?>
