<?php
/**
 * @brief This class is main base class for all the tuples
 * @author Reshu Rajput
 * @created 2013-09-27
 */
class Tuple {
    static public $fields;
    public $PROFILEID;
    public $USERNAME;
    public $GENDER;
    public $AGE;
    public $HEIGHT;
    public $RELIGION;
    public $MTONGUE;
    public $OCCUPATION;
    public $HAVEPHOTO;
    public $PHOTO_DISPLAY;
    public $CASTE;
    public $MSTATUS;
    public $SUBCASTE;
    public $EDUCATION;
    public $INCOME;
    public $SHOW_HOROSCOPE;
    public $PHONE_FLAG;
    public $SUBSCRIPTION;
    public $ENTRY_DT;
    public $TIME;
    public $CALL_STATUS;
    public $CALL_COMMENTS;
    public $LAST_CALL_DATE;
    public $COUNT;
    public $MESSAGEID;
    public $CALLOUT_MESSAGES;
    public $ICONS;
    public $BUTTONS;
    public $IS_ALBUM;
    public $CITY;
    public $ACTIVATED;
    public $displayString;
    public $IS_ALBUM_TEXT;
    public $PROFILECHECKSUM;
    public $ASTRO_DETAILS;
    public $SEEN;
    public $MESSAGE;
    public $SENT_MESSAGE;
    public $LAST_MESSAGE;
    public $PHOTO_COUNT;
    public $IS_PHOTO_REQUESTED;
    public $CONTACTS;
    public $LAST_LOGIN_DT;
    public $IS_BOOKMARKED;
    public $YOURINFO;
    public $LOGICLEVEL;
    public $profileObject;
    public $PIC_ID;
    public $SearchPicUrl;
    public $ProfilePicUrl;
    public $ThumbailUrl;
    public $Thumbail96Url;
    public $ProfilePic120Url;
    public $ProfilePic235Url;
    public $ProfilePic450Url;
    public $MobileAppPicUrl;
    public $MainPicUrl;
    public $OriginalPicUrl;
    public $IS_IGNORED;
    public $FILTERED;
    public $edu_level_new;
    public $MOBPHOTOSIZE;
    public $INTEREST_VIEWED_DATE;
		public $VERIFICATIONSEAL;
		public $COMPANY_NAME;
		public $COLLEGE;
		public $PG_COLLEGE;
		public $ANCESTRAL_ORIGIN;
		public $NATIVE_CITY;
		public $NATIVE_STATE;
        public $EMAIL;
        public $GUNA;
        public $MSG_DEL;
        public $TYPE;
        public $SENDER;
        public $RECEIVER;
        public $COMPLETE_VERIFICATION_STATUS;
    //Getters and setter of all the base class as well as child class fields
        
        public function getprofileObject() {
       return  $this->profileObject;
    }
    public function getPROFILEID() {
        return $this->PROFILEID;
    }
    public function getUSERNAME() {
        return $this->USERNAME;
    }
    public function getGENDER() {
        return $this->GENDER;
    }
    public function getCOMPANY_NAME() {
        return $this->COMPANY_NAME;
    }
    public function getCOLLEGE(){
        return $this->COLLEGE;
    }
    public function getPG_COLLEGE(){
        return $this->PG_COLLEGE;
    }
    public function getAGE() {
        return $this->AGE;
    }
    public function getHEIGHT() {
        return $this->HEIGHT;
    }
    public function getRELIGION() {
        return $this->RELIGION;
    }
    public function getMTONGUE() {
        return $this->MTONGUE;
    }
    public function getOCCUPATION() {
        return $this->OCCUPATION;
    }
    public function getHAVEPHOTO() {
        return $this->HAVEPHOTO;
    }
    public function getPHOTO_DISPLAY() {
        return $this->PHOTO_DISPLAY;
    }
    public function getCASTE() {
        return $this->CASTE;
    }
    public function getMSTATUS() {
        return $this->MSTATUS;
    }
    public function getSUBCASTE() {
        return $this->SUBCASTE;
    }
    public function getEDUCATION() {
        return $this->EDUCATION;
    }
    public function getINCOME() {
        return $this->INCOME;
    }
    public function getCITY() {
        return $this->CITY;
    }
    public function getCITY_ID() {
        return $this->CITY_ID;
    }
    public function getACTIVATED() {
		return $this->ACTIVATED;
	}
     public function getEMAIL() {
        return $this->EMAIL;
    }
    public function getVERIFICATION_SEAL()
    {   
        $verificationSealObj=new VerificationSealLib($this->profileObject,'1');
        $verificationSeal = $verificationSealObj->getFsoStatus();
        unset($verificationSealObj);    
        return $verificationSeal;
    }
    public function getVERIFICATION_STATUS()
    {        
        if($this->getVERIFICATION_SEAL())
            return 1;
        else
            return 0;
        
    }
    public function getGUNA() {
        return $this->GUNA;
    }

    public function getCOMPLETE_VERIFICATION_STATUS()
    {
        if(MobileCommon::isApp())
        {
            $aadharObj = new aadharVerification();
            $aadharArr = $aadharObj->getAadharDetails($this->PROFILEID);
            unset($aadharObj);
            $verificationSeal = $this->getVERIFICATION_SEAL();
            if($verificationSeal && $aadharArr[$this->PROFILEID]["VERIFY_STATUS"] == "Y")
            {
               return 3; //both are verified(aadhar and verified by visit)
            }
            elseif($aadharArr[$this->PROFILEID]["VERIFY_STATUS"] == "Y")
            {
                return 2; //aadhar verified
            }
            else
                return $verificationSeal;
        }
        else
        {
            if($this->getVERIFICATION_SEAL())
                return 1;
            else
                return 0;
        }
    }
        public function setprofileObject($x) {
        $this->profileObject=$x;
    }
    public function setANCESTRAL_ORIGIN($x) {
        $this->ANCESTRAL_ORIGIN=$x;
    }
    public function getANCESTRAL_ORIGIN() {
        return $this->ANCESTRAL_ORIGIN;
    }
    
    public function setNATIVE_CITY($x) {
        $this->NATIVE_CITY=$x;
    }
    public function getNATIVE_CITY() {
        return $this->NATIVE_CITY;
    }
    
    public function setNATIVE_STATE($x) {
        $this->NATIVE_STATE=$x;
    }
    public function getNATIVE_STATE() {
        return $this->NATIVE_STATE;
    }
    public function setNATIVE_STATE_ID($x) {
        $this->NATIVE_STATE_ID=$x;
    }
    public function getNATIVE_STATE_ID() {
        return $this->NATIVE_STATE_ID;
    }
    
    public function setPROFILEID($x) {
        $this->PROFILEID = $x;
    }
    public function setCOMPANY_NAME($x) {
        $this->COMPANY_NAME = $x;
    }
    public function setCOLLEGE($x) {
        $this->COLLEGE = $x;
    }
    public function setPG_COLLEGE($x) {
        $this->PG_COLLEGE = $x;
    }
    public function setUSERNAME($x) {
        $this->USERNAME = $x;
    }
    public function setGENDER($x) {
        $this->GENDER = $x;
    }
    public function setAGE($x) {
        $this->AGE = $x;
    }
    public function setHEIGHT($x) {
        $this->HEIGHT = html_entity_decode($x);
    }
    public function setRELIGION($x) {
        $this->RELIGION = $x;
    }
    public function setMTONGUE($x) {
        $this->MTONGUE = $x;
    }
    public function setOCCUPATION($x) {
        $this->OCCUPATION = $x;
    }
    public function setHAVEPHOTO($x) {
        $this->HAVEPHOTO = $x;
    }
    public function setPHOTO_DISPLAY($x) {
        $this->PHOTO_DISPLAY = $x;
    }
    public function setCASTE($x) {
        $this->CASTE = $x;
    }
    public function setMSTATUS($x) {
        $this->MSTATUS = $x;
    }
    public function setSUBCASTE($x) {
        $this->SUBCASTE = $x;
    }
    public function setEDUCATION($x) {
        $this->EDUCATION = $x;
    }
    public function setINCOME($x) {
        $this->INCOME = $x;
    }
    public function setCITY($x) {
        $this->CITY = $x;
    }
    public function setCITY_ID($x) {
        $this->CITY_ID = $x;
    }
    public function getSHOW_HOROSCOPE() {
        return $this->SHOW_HOROSCOPE;
    }
    public function getPHONE_FLAG() {
        return $this->PHONE_FLAG;
    }
   
    public function setSHOW_HOROSCOPE($x) {
        $this->SHOW_HOROSCOPE = $x;
    }
    public function setPHONE_FLAG($x) {
        $this->PHONE_FLAG = $x;
    }
   
    public function getSUBSCRIPTION() {
        return $this->SUBSCRIPTION;
    }
   
    public function setSUBSCRIPTION($x) {
        $this->SUBSCRIPTION = $x;
    }
   
    public function getTIME() {
        return $this->TIME;
    }
    public function getCALL_STATUS() {
        return $this->CALL_STATUS;
    }
    public function getCALL_COMMENTS() {
        return $this->CALL_COMMENTS;
    }
    public function getLAST_CALL_DATE() {
        return $this->LAST_CALL_DATE;
    }
    public function getCOUNT() {
        return $this->COUNT;
    }
    public function getMESSAGEID() {
        return $this->MESSAGEID;
    }
    public function setTIME($x) {
        $this->TIME = $x;
    }
    public function setCALL_STATUS($x) {
        $this->CALL_STATUS = $x;
    }
    public function setCALL_COMMENTS($x) {
        $this->CALL_COMMENTS = $x;
    }
    public function setLAST_CALL_DATE($x) {
        $this->LAST_CALL_DATE = $x;
    }
    public function setCOUNT($x) {
        $this->COUNT = $x;
    }
    public function setMESSAGEID($x) {
        $this->MESSAGEID = $x;
    }
public function setPIC_ID($x) {
        $this->PIC_ID = $x;
    }
public function getPIC_ID($x="") {
       return $this->PIC_ID;
    }

    public function getICONS() {
        return $this->ICONS;
    }
    public function getBUTTONS() {
        return $this->BUTTONS;
    }
    public function getCALLOUT_MESSAGES($key='')
    {
    	if($key)
    		return $this->CALLOUT_MESSAGES[$key];
    	return $this->CALLOUT_MESSAGES;
    }

    public function setCALLOUT_MESSAGES($x) {
        $this->CALLOUT_MESSAGES = $x;
    }
    public function setICONS($x) {
        $this->ICONS = $x;
    }
    public function setBUTTONS($x) {
        $this->BUTTONS = $x;
    }
    public function getENTRY_DT() {
        return $this->ENTRY_DT;
    }
    public function setENTRY_DT($x) {
        $this->ENTRY_DT = $x;
    }
    public function getIS_ALBUM() {
        return $this->IS_ALBUM;
    }
    public function setIS_ALBUM($x) {
        $this->IS_ALBUM = $x;
    }
    public function setNAME_OF_USER($x) {
	$this->NAME_OF_USER = $x;
    }
    public function getNAME_OF_USER() {
	return $this->NAME_OF_USER;
    }
    public function getIS_ALBUM_TEXT() {
        return $this->IS_ALBUM_TEXT;
    }
    public function setIS_ALBUM_TEXT($x) {
        $this->IS_ALBUM_TEXT = $x;
    }

    public function getPROFILECHECKSUM() {
        return $this->PROFILECHECKSUM;
    }
    public function setPROFILECHECKSUM($x) {
        $this->PROFILECHECKSUM = $x;
    }

    public function setACTIVATED($x) {
		$this->ACTIVATED = $x;
	}
	public function setDisplayString($string) {
		$this->displayString = $string;
	}
	public function getDisplayString() {
		return $this->displayString;
	}
	
	 public function setASTRO_DETAILS($string) {
                $this->ASTRO_DETAILS = $string;
        }
        public function getASTRO_DETAILS() {
                return $this->ASTRO_DETAILS;
        }
	public function setSEEN($string) {
		
                $this->SEEN = $string=="Y"?$string:"N";
        }
        public function getSEEN() {
                return $this->SEEN;
        }
	public function getMESSAGE(){
			return $this->MESSAGE;
		}
	public function setMESSAGE($string)
	{
		$this->MESSAGE = (CommonUtility::strip_selected_tags($string,'script'));
	}
	public function getSENT_MESSAGE(){
			return $this->SENT_MESSAGE;
		}
	public function setSENT_MESSAGE($string)
	{
		$this->SENT_MESSAGE = (CommonUtility::strip_selected_tags($string,'script'));
	}
	public function getLAST_MESSAGE(){  
                return $this->LAST_MESSAGE;
        }
	public function setLAST_MESSAGE($string)
	{
		$this->LAST_MESSAGE = (CommonUtility::strip_selected_tags($string,'script'));
	}
	public function getPHOTO_COUNT(){
		return $this->PHOTO_COUNT;
	}
	public function setPHOTO_COUNT($string){
		$this->PHOTO_COUNT = $string;
	}
	public function getLOCATION(){
		return $this->CITY;
	}
	public function getedu_level_new(){
		return $this->edu_level_new;
	}
	public function getIS_PHOTO_REQUESTED(){
		return $this->IS_PHOTO_REQUESTED;
	}
	public function setIS_PHOTO_REQUESTED($string){
		$this->IS_PHOTO_REQUESTED = $string;
	}
	public function setCONTACTS($array){
		$this->CONTACTS = $array;
	}
	public function getCONTACTS(){
		return $this->CONTACTS;
	}
	public function getLAST_LOGIN_DT()
	{
		return $this->LAST_LOGIN_DT;
	}
	public function setLAST_LOGIN_DT($string)
	{
		$this->LAST_LOGIN_DT = $string;
	}
    public function setEMAIL($string)
    {
        $this->EMAIL = $string;
    }
    public function setGUNA($x)
    {
        $this->GUNA = $x;
    }
	public function getsubscription_icon()
	{
            $subscription=$this->getSUBSCRIPTION();              // JSExclusive.... excluded for app as it is not implemented in app yet. 
                  if(CommonFunction::isJsExclusiveMember($subscription))     
                    if (MobileCommon::isApp()=="A")
                    {
						$newIconCondition=strstr(sfContext::getInstance()->getRequest()->getParameter("newActions"),"JSEXCLUSIVE");
						if($newIconCondition)
						{
							return mainMem::JSEXCLUSIVE;
						}
						else
							return null; 
			}
                     else  return mainMem::JSEXCLUSIVE_LABEL;
        if(strpos($this->getSUBSCRIPTION(),'N')!== false){
            if(MobileCommon::isApp()){
                return IdToAppImagesMapping::EADVANTAGE_SRP;
            }
            else{
                return IdToAppImagesMapping::EADVANTAGE_SRP;
            }
        }                    
		if(strstr($this->getSUBSCRIPTION(),'F'))
		{
			if(strstr($this->getSUBSCRIPTION(),'D'))
				return IdToAppImagesMapping::EVALUE_SRP;
			else
				return IdToAppImagesMapping::ERISHTA_SRP;
		}
		else
			return null;
	}
    public function getsubscription_text()
    {
        $subscription=$this->getSUBSCRIPTION();
        if(CommonFunction::isEvalueMember($subscription))
            $subscription = mainMem::EVALUE_LABEL;
        elseif(CommonFunction::isErishtaMember($subscription))
            $subscription = mainMem::ERISHTA_LABEL;
        elseif(CommonFunction::isJsExclusiveMember($subscription))
            $subscription = mainMem::JSEXCLUSIVE_LABEL;
        elseif(CommonFunction::isEadvantageMember($subscription)){
            $subscription = mainMem::EADVANTAGE_LABEL; 
        }
        else
            $subscription = null;
        return $subscription;   
    }
	public function getuserloginstatus()
	{
		return $this->getLastLogin($this->getLAST_LOGIN_DT());
	}
	
	public function getLastLogin($lastLoginDate)
	{
		$lastLogin = explode("T",$lastLoginDate);
		$lastLoginDate = $lastLogin[0];
		$lastOnlineStr = "Last Online ".CommonUtility::convertDateToISTDay($lastLoginDate);
		return $lastOnlineStr;
	}
	public function getDecoratedTime()
	{
		
		$lastLoginDate                   = $this->getTIME();
		$lastLogin = explode("T",$lastLoginDate);
		$lastLoginDate = $lastLogin[0];
		if(is_numeric($lastLoginDate))
		{	$lastLoginDate =  date("Y-m-j",strtotime("2005-01-01 +$lastLoginDate days"));
		}
		$lastOnlineStr = CommonUtility::convertDateToISTDay($lastLoginDate);
		return $lastOnlineStr;
	}
	
	
	public function getThumbailUrl() {
		return $this->ThumbailUrl;
	}
	public function getSearchPicUrl() {
	return $this->SearchPicUrl;
	}
	
	public function getMobileAppPicUrl()
	{
		return $this->MobileAppPicUrl;
	}
	public function getOriginalPicUrl()
        {
                return $this->OriginalPicUrl;
        }
	
	public function getProfilePic120Url()
	{
		return $this->ProfilePic120Url;
	}
         public function getProfilePic235Url()
	{
		return $this->ProfilePic235Url;
	}
         public function getProfilePic450Url()
	{
		return $this->ProfilePic450Url;
	}
	public function getProfilePicUrl()
        {
                return $this->ProfilePicUrl;
        }
	public function getThumbail96Url()
        {
                return $this->Thumbail96Url;
        }
        public function getMainPicUrl()
        {
                return $this->MainPicUrl;
        }
        public function setSearchPicUrl($x) {
		$this->SearchPicUrl = $x;
	}
	public function setMainPicUrl($x) {
		$this->MainPicUrl = $x;
	}
	public function setThumbailUrl($x) {
		$this->ThumbailUrl = $x;
	}
	
	public function setThumbail96Url($x)
        {
                $this->Thumbail96Url = $x;
        }
        
	public function setMobileAppPicUrl($string)
	{
		$this->MobileAppPicUrl = $string;
	}
	public function setProfilePic120Url($url)
	{
		$this->ProfilePic120Url=$url;
	}
         public function setProfilePic235Url($url)
	{
		$this->ProfilePic235Url=$url;
	}
         public function setProfilePic450Url($url)
	{
		$this->ProfilePic450Url=$url;
	}
        public function setOriginalPicUrl($url)
	{
		$this->OriginalPicUrl=$url;
	}
	public function setProfilePicUrl($url)
	{
		$this->ProfilePicUrl=$url;
	}
	
	public function gettuple_title_field()
	{
		return $this->USERNAME;
		}
	public function setIS_BOOKMARKED($string)
	{
		$this->IS_BOOKMARKED = $string;
	}
	public function getIS_BOOKMARKED()
	{
		return $this->IS_BOOKMARKED;
	}
	public function setYOURINFO($string)
        {
                $this->YOURINFO = $string;
        }
        public function getYOURINFO()
        {
                return $this->YOURINFO;
        }
	public function setLOGICLEVEL($string)
        {
                $this->LOGICLEVEL = $string;
        }
        public function getLOGICLEVEL()
        {
                return $this->LOGICLEVEL;
        }
        public function getIS_IGNORED()
        {
            return $this->IS_IGNORED;
        }		
        public function setIS_IGNORED($string)
        {
            $this->IS_IGNORED = $string;
        }
        public function getFILTERED()
        {
            return $this->FILTERED;
        }
        public function setFILTERED($string)
        {
            $this->FILTERED = $string;
        }
       
        public function setedu_level_new($string)
        {
            $this->edu_level_new = $string;
        }
				public function getMOBPHOTOSIZE()
        {
            return $this->MOBPHOTOSIZE;
        }
        public function setMOBPHOTOSIZE($string)
        {
            $this->MOBPHOTOSIZE = $string;
        }
        
        
        
        public function getINTEREST_VIEWED_DATE()
        {
            return $this->INTEREST_VIEWED_DATE;
        }
        public function setINTEREST_VIEWED_DATE($string)
        {
            $this->INTEREST_VIEWED_DATE = $string;
        }
         public function setMSG_DEL($string)
        {
            $this->MSG_DEL = $string;
        }
        public function getMSG_DEL()
        {
            return $this->MSG_DEL;
        }
         public function setTYPE($string)
        {
            $this->TYPE = $string;
        }
        public function getTYPE()
        {
            return $this->TYPE;
        }
         public function setSENDER($string)
        {
            $this->SENDER = $string;
        }
        public function getSENDER()
        {
            return $this->SENDER;
        }
         public function setRECEIVER($string)
        {
            $this->RECEIVER = $string;
        }
        public function getRECEIVER()
        {
            return $this->RECEIVER;
        }
}
?>
