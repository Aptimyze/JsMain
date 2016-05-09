<?php
class Receiver
{
	private $profileObj;
	private $switchToDpp;
	private $hasTrend;
	private $sameGenderError;
	private $isPartnerProfileExist;

        public function getSwitchToDpp(){return $this->switchToDpp;}
        public function getHasTrend(){return $this->hasTrend;}
	public function getSameGenderError(){return $this->sameGenderError;}
	public function getProfileObj(){return $this->profileObj;}
	public function getIsPartnerProfileExist(){return $this->isPartnerProfileExist;}


	//Added for Receiver info
			
        public function __construct($profileId,$db)
        {
		$sql="select INITIATED  , ACCEPTED FROM twowaymatch.TRENDS WHERE PROFILEID='$profileId'";
		$result=mysql_query($sql,$db) or logerror1("In matchalert_mailer.php",$sql);
		$row=mysql_fetch_array($result);
		$cnt=$row["INITIATED"] + $row["ACCEPTED"];

		$sql="SELECT  COUNT(*) AS CNT from  newjs.MATCH_LOGIC WHERE LOGIC_STATUS='O' AND PROFILEID='$profileId'";
		$result=mysql_query($sql,$db) or logerror1("In SendMatchAlert.php",$sql);
		$row=mysql_fetch_array($result);
		if($row["CNT"]>0)
		{
			unset($cnt);
			$this->switchToDpp=1;
		}
		else
			$this->switchToDpp=0;

		$profileObj = Profile::getInstance('newjs_slave',$profileId);
		$profileObj->getDetail("","","USERNAME,AGE,GENDER,RELIGION,HEIGHT,CASTE,INCOME,MTONGUE,ENTRY_DT,HAVEPHOTO,SHOW_HOROSCOPE,COUNTRY_RES,BTYPE,COMPLEXION,EDU_LEVEL_NEW,OCCUPATION,MSTATUS,CITY_RES,DRINK,SMOKE,DIET,MANGLIK,RELATION,HANDICAPPED,HIV,SUBSCRIPTION,BTIME,MOB_STATUS,LANDL_STATUS,ACTIVATED,INCOMPLETE,LAST_LOGIN_DT,EMAIL");

		if($cnt > MailerConfigVariables::$trendThreshold)
		{
			$this->hasTrend=1;
			$jpartnerObj = new TrendsPartnerProfile();
			$jpartnerObj->setPartnerDetails($profileId);
			if($jpartnerObj && $jpartnerObj->getPROFILEID())
				$this->isPartnerProfileExist="Y";
			else
				$this->isPartnerProfileExist="N";
		}
		else
		{
			$this->hasTrend=0;
			$jpartnerObj = new PartnerProfile($profileObj);
			$jpartnerObj->getDppCriteria("","MAILER");
			if($jpartnerObj && $jpartnerObj->getIsDppExist())
				 $this->isPartnerProfileExist="Y";
                	else
                        	$this->isPartnerProfileExist="N";
		}

	
		if($profileObj->getGENDER()==$jpartnerObj->getGENDER())
                	$this->sameGenderError='Y';
		else
                	$this->sameGenderError='N';

		$this->profileObj = $profileObj;
	}
}
?>
