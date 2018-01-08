<?php
include_once(JsConstants::$alertDocRoot."/classes/Mysql.class.php");
include_once(JsConstants::$alertDocRoot."/classes/Jpartner.class.php");
class Receiver
{
        private $profileId;
        private $gender;
        private $partnerProfile;
	private $filters;

	//Added for Receiver info
        public function getRecCaste()
        {
                return $this->recCaste;
        }
        public function getRecMtongue()
        {
                return $this->recMtongue;
        }
        public function getRecAge()
        {
                return $this->recAge;
        }
        public function getRecIncome()
        {
                return $this->recIncome;
        }
        public function getRecHeight()
        {
                return $this->recHeight;
        }
        public function getRecEdu()
        {
                return $this->recEdu;
        }
        public function getRecOcc()
        {
                return $this->recOcc;
        }
        public function getRecCity()
        {
                return $this->recCity;
        }
        public function getRecCountry()
        {
                return $this->recCountry;
        }
        public function getRecManglik()
        {
                return $this->recManglik;
        }
        public function getRecMstatus()
        {
                return $this->recMstatus;
        }
        public function getRecReligion()
        {
                return $this->recReligion;
        }
        public function getRecGender()
        {
                return $this->recGender;
        }
        public function getRecBtype()
        {
                return $this->recBtype;
        }
	public function getRecLastLoginDt()
	{
		return $this->recLastLoginDt;
	}
	public function getRecEmail()
	{
		return $this->recEmail;
	}

	//PHASE2
        public function getRecHiv()
        {
                return $this->recHiv;
        }
        public function getRecHandicapped()
        {
                return $this->recHandicapped;
        }
        public function getSwitchToDpp()
        {
                return $this->switchToDpp;
        }
	//PHASE2


	//Added for Receiver info
			
        public function __construct($profileId,$db,$DvD='',$Kundli='',$myDbArr='',$mysqlObj='')
        {
		if (!$Kundli)
		{
			if(!$DvD)
			{
				$sql="select INITIATED  , ACCEPTED FROM matchalerts.TRENDS WHERE PROFILEID='$profileId'";
				$result=mysql_query($sql,$db) or logerror1("In matchalert_mailer.php",$sql);
				$row=mysql_fetch_array($result);
				$cnt=$row["INITIATED"] + $row["ACCEPTED"];

				//tested ??
				//if($cnt > configVariables::$trendThreshold)
				{
					$sql="SELECT  COUNT(*) AS CNT from  newjs.MATCH_LOGIC WHERE LOGIC_STATUS='O' AND PROFILEID='$profileId'";
					$result=mysql_query($sql,$db) or logerror1("In SendMatchAlert.php",$sql);
					$row=mysql_fetch_array($result);
					if($row["CNT"]>0)
					{
						unset($cnt);
                				$this->switchToDpp=1;
					}
				}
			}
		}
		//tested ??

		if($cnt > configVariables::$trendThreshold)
		{
			//Trends Logic
		        $jpartnerObj=new TrendsPartnerProfile('matchalerts.TRENDS');
			if (!$mysqlObj)
				$mysqlObj=new Mysql;
		        $jpartnerObj->setPartnerDetails($profileId);
		        $this->setPartnerProfile($jpartnerObj);   
			//Trends Logic
		}
		else
		{
			if (!$mysqlObj)
				$mysqlObj=new Mysql;
			if ($Kundli)
			{
				$myDbName=getProfileDatabaseConnectionName($profileId,'slave',$mysqlObj,$db);
				$myDb=$myDbArr[$myDbName];
		        	$jpartnerObj=new Jpartner('newjs.JPARTNER');
		        	$jpartnerObj->setPartnerDetails($profileId,$myDb,$mysqlObj);
			}
			else
			{
		        	$jpartnerObj=new Jpartner('matchalerts.JPARTNER');
		        	$jpartnerObj->setPartnerDetails($profileId,$db,$mysqlObj);
			}
		        $this->setPartnerProfile($jpartnerObj);   
		}
		//Added for Receiver info

		$sql="SELECT SQL_CACHE HIV,HANDICAPPED,RELIGION,GENDER,CASTE,MTONGUE,AGE,INCOME,HEIGHT,EDU_LEVEL_NEW,OCCUPATION,CITY_RES,COUNTRY_RES,MANGLIK,MSTATUS,BTYPE,LAST_LOGIN_DT,EMAIL"; 
		if ($Kundli)
			$sql.=" FROM newjs.JPROFILE WHERE PROFILEID=$profileId";
		else
			$sql.=" FROM matchalerts.JPROFILE WHERE PROFILEID=$profileId";
		$result=mysql_query($sql,$db) or logerror1("In matchalert_mailer.php",$sql);
		$myrow=mysql_fetch_array($result);


		if($myrow)
		{
			$this->recCaste=$myrow["CASTE"];
			$this->recMtongue=$myrow["MTONGUE"];
			$this->recAge=$myrow["AGE"];
			$this->recIncome=$myrow["INCOME"];
			$this->recHeight=$myrow["HEIGHT"];
			$this->recEdu=$myrow["EDU_LEVEL_NEW"];
			$this->recOcc=$myrow["OCCUPATION"];
			$this->recCity=$myrow["CITY_RES"];
			$this->recCountry=$myrow["COUNTRY_RES"];
			$this->recManglik=$myrow["MANGLIK"];
			$this->recMstatus=$myrow["MSTATUS"];
			$this->recReligion=$myrow["RELIGION"];
			$this->recGender=$myrow["GENDER"];
			$this->recBtype=$myrow["BTYPE"];
			$this->recLastLoginDt=$myrow["LAST_LOGIN_DT"];
			$this->recEmail=$myrow["EMAIL"];

                        //PHASE2
                        if($this->switchToDpp!=1)
                        {
                                if($myrow["HANDICAPPED"]=='N' || $myrow["HANDICAPPED"]=='')
                                        $this->recHandicapped='N';
				elseif(in_array($myrow["HANDICAPPED"],array(1,2)))
                                        $this->recHandicapped="A";
				elseif(in_array($myrow["HANDICAPPED"],array(3,4)))
                                        $this->recHandicapped="B";
                                if($myrow["HIV"]=='N' || $myrow["HIV"]=='')
                                        $this->recHiv='N';
                                else
                                        $this->recHiv='Y';
                        }
                        else
                        {
                                if($jpartnerObj->getHANDICAPPED()!='')
                                        $this->recHandicapped=$jpartnerObj->getHANDICAPPED();
                                if($myrow["HIV"]=='N' || $myrow["HIV"]=='')
                                        $this->recHiv='N';
                                else
                                        $this->recHiv='Y';
                        }
                        //PHASE2

		}

		if($cnt <= configVariables::$trendThreshold)
		{
			if($jpartnerObj->getPARTNER_CASTE()=='')
			{
				//PHASE2
	                	//$jpartnerObj->setCasteMapping($this->recCaste,$this->recMtongue,$db,$mysqlObj);
				if(!in_array($this->recReligion,array(1,2)))
				{
					$jpartnerObj->setCasteMapping($this->recCaste,$this->recMtongue,$db,$mysqlObj,$myrow["RELIGION"]);
				}
				else
					$jpartnerObj->setCasteMapping($this->recCaste,$this->recMtongue,$db,$mysqlObj);
				//PHASE2
				
			}
		}
		if($myrow["GENDER"]==$jpartnerObj->getGENDER())
                	$this->sameGenderError='Y';
		else
                	$this->sameGenderError='N';

		//Added for Receiver info	
	}
        public function getPartnerProfile()
        {
                return $this->partnerProfile;
        }
        public function setPartnerProfile($partnerProfile)
        {
                $this->partnerProfile=$partnerProfile;
        }
        public function getHasTrend()
        {
                return $this->partnerProfile instanceof TrendsPartnerProfile;
        }

        public function getFilters()
        {
                return $this->filters;
        }
	
        public function setFilters($filters)
        {
                $this->filters=$filters;
        }

	public function getSameGenderError()
        {
                return $this->sameGenderError;
        }
}
?>
