<?php
//write the getter and setter method of filter
class FilterBean
{
	private $profileId;
        private $lage;
        private $hage;
        private $religion;
	private $mtongue;
	private $country;
	private $caste;
	private $mstatus;
	private $income;
	private $manglik;
	private $db;
	private $shardArr=array();
	private $mysqlObj;
	private $reverseCriteria;
        private $isDvd;
	private $isKundli;

        public function __construct($db,$shardArr,$mysqlObj,$dvd='',$Kundli='')
        {
                //get receiver profile
                $this->db=$db;
                $this->shardArr=$shardArr;
                $this->mysqlObj=$mysqlObj;
                $this->isDvd=$dvd;
		$this->isKundli=$Kundli;
                //$this->matchalertsTrends=$matchalertsTrends;
        }

        public function getProfileId()
        {
                return $this->profileId;
        }
	public function setProfileId($profileid)
	{
		$this->profileId=$profileid;
	}

        public function getLage()
        {
                return $this->lage;
        }
        public function setLage($lage)
        {
                $this->lage=$lage;
        }
        public function getHage()
        {
                return $this->hage;
        }
        public function setHage($hage)
        {
                $this->hage=$hage;
        }
	//PHASE2
        public function getLageRelax()
        {
                return $this->lageRelax;
        }
        public function setLageRelax($lageRelax)
        {
                $this->lageRelax=$lageRelax;
        }
        public function getHageRelax()
        {
                return $this->hageRelax;
        }
        public function setHageRelax($hageRelax)
        {
                $this->hageRelax=$hageRelax;
        }
	//PHASE2
        
        public function getLheight()
        {
                return $this->lheight;
        }
        public function setLheight($lheight)
        {
                $this->lheight=$lheight;
        }
        public function getHheight()
        {
                return $this->hheight;
        }

        public function setHheight($hheight)
        {
                $this->hheight=$hheight;
        }

	//PHASE2
        public function getLheightRelax()
        {
                return $this->lheightRelax;
        }
        public function setLheightRelax($lheightRelax)
        {
                $this->lheightRelax=$lheightRelax;
        }
        public function getHheightRelax()
        {
                return $this->hheightRelax;
        }
        public function setHheightRelax($hheightRelax)
        {
                $this->hheightRelax=$hheightRelax;
        }
	public function setCanUseRelaxation($val)
	{
		$this->canUseRelaxation=$val;
	}
        public function getCanUseRelaxation()
        {
                return $this->canUseRelaxation;
        }
	//PHASE2
        
        
	public function getMtongue()
        {
                return $this->mtongue;
        }

        public function setMtongue($mtongue)
        {
                $this->mtongue=$mtongue;
        }
        
         public function getCountry()
        {
                return $this->country;
        }

        public function setCountry($country)
        {
                $this->country=$country;
        }

        public function getCaste()
        {
                return $this->caste;
        }

        public function setCaste($caste)
        {
                $this->caste=$caste;
        }
        
        public function getMstatus()
        {
                return $this->mstatus;
        }

        public function setMstatus($mstatus)
        {
                $this->mstatus=$mstatus;
        }
        
        public function getIncome()
        {
                return $this->income;
        }

        public function setIncome($income)
        {
                $this->income=$income;
        }

        public function getReligion()
        {
                return $this->religion;
        }

        public function setReligion($religion)
        {
                $this->religion=$religion;
        }

        public function getManglik()
        {
                return $this->manglik;
        }

        public function setManglik($manglik)
        {
                $this->manglik=$manglik;
        }

	public function getCityRes()
	{
		return $this->cityRes;
	}

	public function setCityRes($cityRes)
	{
		$this->cityRes=$cityRes;
	}

	public function getOcc()
	{
		return $this->occ;
	}

	public function setOcc($occ)
	{
		$this->occ=$occ;
	}

	public function getEdu()
	{
		return $this->edu;
	}

	public function setEdu($edu)
	{
		$this->edu=$edu;
	}

        public function getGender()
        {
                return $this->gender;
        }

        public function setGender($gender)
        {
                $this->gender=$gender;
        }

        public function getTable()
        {
                return $this->table;
        }

        public function setTable($table)
        {
                $this->table=$table;
        }

	public function getSkippedRecords()
	{
		return $this->skippRecords;
	}


	//PHASE2-OLD ALGO SWITCH
        public function getBtype()
        {
                return $this->btype;
        }
        public function setBtype($btype)
        {
                $this->btype=$btype;
        }
        public function getDiet()
        {
                return $this->diet;
        }
        public function setDiet($diet)
        {
                $this->diet=$diet;
        }
        public function getDrink()
        {
                return $this->drink;
        }
        public function setDrink($drink)
        {
                $this->drink=$drink;
        }
        public function getElevel()
        {
                return $this->elevel;
        }
        public function setElevel($elevel)
        {
                $this->elevel=$elevel;
        }
        public function getRelation()
        {
                return $this->relation;
        }
        public function setRelation($relation)
        {
                $this->relation=$relation;
        }
        public function getSmoke()
        {
                return $this->smoke;
        }
        public function setSmoke($smoke)
        {
                $this->smoke=$smoke;
        }
        public function getComp()
        {
                return $this->comp;
        }
        public function setComp($comp)
        {
                $this->comp=$comp;
        }
        public function getNhandi()
        {
                return $this->nhandi;
        }
        public function setNhandi($nhandi)
        {
                $this->nhandi=$nhandi;
        }
	//PHASE2-OLD ALGO SWITCH
	




        //handling not cases
        public function getPartnerMstatusIgnore()
        {
                return $this->mstatusIgnore;
        }
        public function setPartnerMstatusIgnore($mstatusIgnore)
        {
                $this->mstatusIgnore=$mstatusIgnore;
        }
        public function getPartnerManglikIgnore()
        {
                return $this->manglikIgnore;
        }
        public function setPartnerManglikIgnore($manglikIgnore)
        {
                $this->manglikIgnore=$manglikIgnore;
        }
        public function getPartnerCountryResIgnore()
        {
                return $this->countryResIgnore;
        }
        public function setPartnerCountryResIgnore($countryResIgnore)
        {
                $this->countryResIgnore=$countryResIgnore;
        }
        public function getCasteMtongue()
        {
                return $this->casteMtongue;
        }
        public function setCasteMtongue($casteMtongue)
        {
                $this->casteMtongue=$casteMtongue;
        }
        //handling not cases


	public function setSkippedRecords()
	{
		if($this->isKundli)
			$sql_ignore="select IGNORED_PROFILEID AS ALL_IGNORED_PROFILEID FROM newjs.IGNORE_PROFILE WHERE PROFILEID='$this->profileId' AND UPDATED='Y' UNION select PROFILEID AS ALL_IGNORED_PROFILEID FROM newjs.IGNORE_PROFILE WHERE IGNORED_PROFILEID='$this->profileId' AND UPDATED='Y'";
		else
			$sql_ignore="select IGNORED_PROFILEID AS ALL_IGNORED_PROFILEID FROM IGNORE_PROFILE WHERE PROFILEID='$this->profileId' AND UPDATED='Y' UNION select PROFILEID AS ALL_IGNORED_PROFILEID FROM IGNORE_PROFILE WHERE IGNORED_PROFILEID='$this->profileId' AND UPDATED='Y'";
	       $result_ignore=mysql_query($sql_ignore,$this->db) or  logerror1("In matchalert_mailer.php",$sql_ignore,'','',$this->db);
        	while($row_ignore=mysql_fetch_array($result_ignore))
	        {
        	         if($row_ignore["ALL_IGNORED_PROFILEID"])
                	         $previous_rec_arr[]=$row_ignore["ALL_IGNORED_PROFILEID"];
	        }
                if($this->isDvd=='D')
                {
                        $sql="SELECT MATCHES FROM matchalerts.DvDLogs WHERE RECEIVER='$this->profileId'";
                        $res=mysql_query($sql,$this->db) or  logerror1("In matchalert_mailer.php",$sql,'','',$this->db);
                        while($row=mysql_fetch_array($res))
                        {
                                $tempArr[]=$row["MATCHES"];
                        }
                        if($tempArr)
                        {
                                $tempStr=implode(",",$tempArr);
                                $tempArr=explode(",",$tempStr);
                                if($previous_rec_arr)
                                        $previous_rec_arr=array_merge($tempArr,$previous_rec_arr);
                                else
                                        $previous_rec_arr=$tempArr;
                        }
                }
                else
                {
			if($this->isKundli)
			{
                        	$sql="SELECT MATCHID AS USER FROM kundli_alert.KUNDLI_CONTACT_CENTER WHERE PROFILEID='$this->profileId'";
                        	$res=mysql_query($sql,$this->db) or  logerror1("In matchalert_mailer.php",$sql,'','',$this->db);
                        	while($row=mysql_fetch_array($res))
                        	{
                                	$previous_rec_arr[]=$row["USER"];
                        	}
			}
			else
			{
                        	$sql="SELECT USER FROM matchalerts.LOG WHERE RECEIVER='$this->profileId'";
                        	$res=mysql_query($sql,$this->db) or  logerror1("In matchalert_mailer.php",$sql,'','',$this->db);
                        	while($row=mysql_fetch_array($res))
                        	{
                                	$previous_rec_arr[]=$row["USER"];
                        	}
			}
                }
		
		if($this->isKundli)
	        	$sql="SELECT BOOKMARKEE FROM newjs.BOOKMARKS WHERE BOOKMARKER='$this->profileId'";
		else
	        	$sql="SELECT BOOKMARKEE FROM BOOKMARKS WHERE BOOKMARKER='$this->profileId'";
		$res=mysql_query($sql,$this->db) or  logerror1("In matchalert_mailer.php",$sql,'','',$this->db);
		while($row=mysql_fetch_array($res))
		{
			$previous_rec_arr[]=$row["BOOKMARKEE"];
		}

		//untested
		global $db_211;
		$sql="SELECT VIEWED AS ALREADY_CONTACTED FROM newjs.VIEW_LOG_TRIGGER WHERE VIEWER='$this->profileId' AND TO_DAYS( NOW( ) ) - TO_DAYS( DATE ) <8 ";
		$res=mysql_query($sql,$db_211) or  logerror1("In matchalert_mailer.php",$sql,'','',$db_211);
		while($row=mysql_fetch_array($res))
		{
			$previous_rec_arr[]=$row["ALREADY_CONTACTED"];
		}
		//untested

        	$myDbName=getProfileDatabaseConnectionName($this->profileId,'slave',$this->mysqlObj,$this->db);
	        @mysql_ping($this->shardArr[$myDbName]);
        	$myDb=$this->shardArr[$myDbName];

	        $sql="SELECT RECEIVER AS ALREADY_CONTACTED FROM newjs.CONTACTS WHERE SENDER='$this->profileId'";
        	$res=mysql_query($sql,$myDb) or  logerror1("In matchalert_mailer.php",$sql,'','',$myDb);
	        while($row=mysql_fetch_array($res))
        	{
                	$previous_rec_arr[]=$row["ALREADY_CONTACTED"];
	        }

       		$sql="SELECT SENDER AS ALREADY_CONTACTED FROM newjs.CONTACTS WHERE RECEIVER='$this->profileId'";
	        $res=mysql_query($sql,$myDb) or  logerror1("In matchalert_mailer.php",$sql,'','',$myDb);
        	while($row=mysql_fetch_array($res))
	        {
        	        $previous_rec_arr[]=$row["ALREADY_CONTACTED"];
	        }
		if(is_array($previous_rec_arr))
			$previous_rec_arr_str=implode("','",$previous_rec_arr);
		if($previous_rec_arr_str)
			$previous_rec_arr_str="'".$previous_rec_arr_str."'";
		$this->skippRecords=$previous_rec_arr_str;
	}

        public function getReverseCriteria()
        {
                return $this->reverseCriteria;
        }

        public function setReverseCriteria()
        {
                $sql="SELECT CASTE FROM matchalerts.JPROFILE WHERE PROFILEID='$this->profileId'";
        	$res=mysql_query($sql,$this->db) or  logerror1("In matchalert_mailer.php",$sql,'','',$this->db);
	        $row=mysql_fetch_array($res);
		$reverseCriteria=$row["CASTE"];		
                $this->reverseCriteria=$reverseCriteria;
        }
        public function setForwardCriteria($receiverObj)
	{
		$jpartnerTrendsObj=$receiverObj->getPartnerProfile();
		$this->setProfileId($jpartnerTrendsObj->getPROFILEID());
                $this->setGender($jpartnerTrendsObj->getGENDER());
                $this->setReligion($jpartnerTrendsObj->getPARTNER_RELIGION());

		//PHASE2
                //$this->setLheight($jpartnerTrendsObj->getLHEIGHT());
                //$this->setHheight($jpartnerTrendsObj->getHHEIGHT());
		$lheight=$jpartnerTrendsObj->getLHEIGHT();
		$hheight=$jpartnerTrendsObj->getHHEIGHT();
		$maxDiff=configVariables::$maxHeightDiff;
		$this->setLheight($lheight);
		$this->setHheight($hheight);

		if($jpartnerTrendsObj->getGENDER()=='M')
		{
                        $relaxValue=$lheight-$receiverObj->getRecHeight();
                        if($relaxValue>1)
                                $relaxValue=2;
                        elseif($relaxValue==1)
                                $relaxValue=1;
                        else
                                $relaxValue=0;
		}
		else
		{
                        $relaxValue=$receiverObj->getRecHeight()-$lheight;
                        if(($maxDiff-$relaxValue)>1)
                                $relaxValue=2;
                        elseif(($maxDiff-$relaxValue)>0)
                                $relaxValue=1;
                        else
                                $relaxValue=0;
		}
		if($relaxValue || $jpartnerTrendsObj->getPARTNER_COUNTRYRES()!='')
			$this->setCanUseRelaxation(1);
		
		$this->setLheightRelax($relaxValue);


		if($jpartnerTrendsObj->getGENDER()=='F')
		{
                        $relaxValue=$receiverObj->getRecHeight()-$hheight;
                        if($relaxValue>1)
                                $relaxValue=2;
                        elseif($relaxValue==1)
                                $relaxValue=1;
                        else
                                $relaxValue=0;

		}
		else
		{
                        $relaxValue=$hheight-$receiverObj->getRecHeight();
                        if(($maxDiff-$relaxValue)>1)
                                $relaxValue=2;
                        elseif(($maxDiff-$relaxValue)>0)
                                $relaxValue=1;
                        else
                                $relaxValue=0;

		}
		if($relaxValue || $jpartnerTrendsObj->getPARTNER_COUNTRYRES()!='')
			$this->setCanUseRelaxation(1);
		$this->setHheightRelax($relaxValue);
		//PHASE2
		
                $this->setMtongue($jpartnerTrendsObj->getPARTNER_MTONGUE());
                $this->setCountry($jpartnerTrendsObj->getPARTNER_COUNTRYRES());
                $this->setMstatus($jpartnerTrendsObj->getPARTNER_MSTATUS());
                $this->setPartnerMstatusIgnore($jpartnerTrendsObj->getPARTNER_MSTATUS_IGNORE());
		if($jpartnerTrendsObj->getGENDER()=='M')
	                $this->setIncome($jpartnerTrendsObj->getPARTNER_INCOME());


		//PHASE2
                //$this->setLage($jpartnerTrendsObj->getLAGE());
                //$this->setHage($jpartnerTrendsObj->getHAGE());
		$lage=$jpartnerTrendsObj->getLAGE();
                $hage=$jpartnerTrendsObj->getHAGE();
		$maxDiff=configVariables::$maxAgeDiff;
		$this->setLage($lage);
                $this->setHage($hage);


		if($jpartnerTrendsObj->getGENDER()=='M')
		{
                        $relaxValue=$lage-$receiverObj->getRecAge();
			if($relaxValue>1)
				$relaxValue=2;
			elseif($relaxValue==1)
				$relaxValue=1;	
			else
				$relaxValue=0;
		}
		else
		{
			$relaxValue=$receiverObj->getRecAge()-$lage;
			if(($maxDiff-$relaxValue)>1)
				$relaxValue=2;
			elseif(($maxDiff-$relaxValue)>0)
				$relaxValue=1;
			else
				$relaxValue=0;
		}
		if($relaxValue || $jpartnerTrendsObj->getPARTNER_COUNTRYRES()!='')
			$this->setCanUseRelaxation(1);
		$this->setLageRelax($relaxValue);


                if($jpartnerTrendsObj->getGENDER()=='F')
                {
                        $relaxValue=$receiverObj->getRecAge()-$hage;
                        if($relaxValue>1)
                        	$relaxValue=2;
			elseif($relaxValue==1)
				$relaxValue=1;
                        else
                                $relaxValue=0;
                }
                else
		{
			$relaxValue=$hage-$receiverObj->getRecAge();
			if(($maxDiff-$relaxValue)>1)
				$relaxValue=2;
			elseif(($maxDiff-$relaxValue)>0)
				$relaxValue=1;
			else
				$relaxValue=0;
		}
		if($relaxValue || $jpartnerTrendsObj->getPARTNER_COUNTRYRES()!='')
			$this->setCanUseRelaxation(1);
		$this->setHageRelax($relaxValue);
		//PHASE2

                $this->setCaste($jpartnerTrendsObj->getPARTNER_CASTE());
                $this->setManglik($jpartnerTrendsObj->getPARTNER_MANGLIK());
                $this->setCityRes($jpartnerTrendsObj->getPARTNER_CITYRES());
                $this->setOcc($jpartnerTrendsObj->getPARTNER_OCC());
                $this->setEdu($jpartnerTrendsObj->getPARTNER_ELEVEL_NEW());
                $this->setPartnerManglikIgnore($jpartnerTrendsObj->getPARTNER_MANGLIK_IGNORE());
                $this->setPartnerCountryResIgnore($jpartnerTrendsObj->getPARTNER_COUNTRY_RES_IGNORE());
		$this->setCasteMtongue($jpartnerTrendsObj->getCASTE_MTONGUE());

		//PHASE2-OLD ALGO SWITCH
		$this->setBtype($jpartnerTrendsObj->getPARTNER_BTYPE());
		$this->setDiet($jpartnerTrendsObj->getPARTNER_DIET());
		$this->setDrink($jpartnerTrendsObj->getPARTNER_DRINK());
		$this->setRelation($jpartnerTrendsObj->getPARTNER_RELATION());
		$this->setSmoke($jpartnerTrendsObj->getPARTNER_SMOKE());
		$this->setComp($jpartnerTrendsObj->getPARTNER_COMP());
		$this->setNhandi($jpartnerTrendsObj->getNHANDICAPPED());
		//$this->set($jpartnerTrendsObj->getPARTNER_());
		//PHASE2-OLD ALGO SWITCH

                $this->setSkippedRecords();
		if (!$this->isKundli)
                	$this->setReverseCriteria();
/*
                if($jpartnerTrendsObj->getGENDER() == 'M')
                {
			if($this->matchalertsTrends=='Y')
	                        $this->setTable("TRENDS_SEARCH_MALE");
			else
	                        $this->setTable("SEARCH_MALE");
                }
                else
                {
			if($this->matchalertsTrends=='Y')
                        	$this->setTable("TRENDS_SEARCH_FEMALE");
			else
                        	$this->setTable("SEARCH_FEMALE");
                }
*/
	}

	public function getFilterCriteriaArray($relaxForwardFilter,$receiverObj)
	{
		if($this->getLage() && $this->getHage())
                {
                        $whereArr[]="AGE BETWEEN ".$this->getLage()." AND ".$this->getHage();
                }
                if($this->getLheight() && $this->getHheight())
                {
                        $whereArr[]="HEIGHT BETWEEN ".$this->getLheight()." AND ".$this->getHheight();
                }
                if($this->getMstatus())
                {
                        $whereArr[]="MSTATUS IN (".$this->getMstatus().")";
                }
                if($this->getPartnerMstatusIgnore())
                {
                        $whereArr[]="MSTATUS <>".$this->getPartnerMstatusIgnore()."";
                }
                if($this->getMtongue())
                {
                        $whereArr[]="MTONGUE IN (".$this->getMtongue().")";
                }
                if($this->getReligion())
                {
                        $whereArr[]="RELIGION IN (".$this->getReligion().")";
                }
                //PHASE2
                if($receiverObj->getSwitchToDpp()==1)
                {
                        if($receiverObj->getRecHandicapped()!='')
                                $whereArr[]="HANDICAPPED IN (".$receiverObj->getRecHandicapped().")";
                }
                else
                {
                        if($receiverObj->getRecHandicapped()=='N')
                                $whereArr[]="HANDICAPPED IN ('','N')";
			elseif($receiverObj->getRecHandicapped()=='A')
                                $whereArr[]="HANDICAPPED IN ('','N','1','2')";
			elseif($receiverObj->getRecHandicapped()=='B')
                                $whereArr[]="HANDICAPPED IN ('','N','3','4')";
			
                }
                if($receiverObj->getRecHiv()=='N')
                        $whereArr[]="HIV<>'Y'";
                //PHASE2

		//PHASE2-OLD ALGO SWITCH
		if($this->getBtype())
			$whereArr[]="BTYPE IN (".$this->getBtype().")";
		if($this->getDiet())
			$whereArr[]="DIET IN (".$this->getDiet().")";
		if($this->getDrink())
			$whereArr[]="DRINK IN (".$this->getDrink().")";
		if($this->getElevel())
			$whereArr[]="EDU_LEVEL IN (".$this->getElevel().")";
		if($this->getRelation())
			$whereArr[]="RELATION IN (".$this->getRelation().")";
		if($this->getSmoke())
			$whereArr[]="SMOKE IN (".$this->getSmoke().")";
		if($this->getComp())
			$whereArr[]="COMPLEXION  IN (".$this->getComp().")";
		/*
		if($this->getNhandi())
			$whereArr[]=" IN (".$this->getNhandi().")";
		*/
		/*
		if($this->get())
			$whereArr[]=" IN (".$this->get().")";
		*/
		//PHASE2-OLD ALGO SWITCH
		
	
                if($this->getCaste())
                {
                        $castetemp=$this->getCaste();
			if($castetemp)
			{
			$castetemp=trim($castetemp,"'");
                        $caste=explode("','",$castetemp);
                        if(is_array($caste))
                        {
                                $casteStr=implode("','",$caste);
                                $seCaste=get_all_caste($casteStr,'',1,$this->db);
                                if(is_array($seCaste))
                                        $caste_str="'".implode("','",$seCaste)."'";
                        }
//			if(!$caste_str)
//				echo "TRACKERROR-->>".$receiverObj->getPartnerProfile()->getPROFILEID()."<<--";
			
			if($caste_str)
                        	$whereArr[]="CASTE IN (".$caste_str.")";
			}
                        //$whereArr[]="CASTE IN (".$this->getCaste().")";
                }
                if($this->getIncome())
                {
                        if($receiverObj->getHasTrend() != true)
                                $whereArr[]="INCOME IN (".$this->getIncome().")";
                        else
                                $whereArr[]="INCOME_SORTBY IN (".$this->getIncome().")";
                }
                //if($this->getManglik() && !$relaxForwardFilter)
                if($this->getManglik())//PHASE2
                {
                        $whereArr[]="MANGLIK IN (".$this->getManglik().")";
                }
                if($this->getPartnerManglikIgnore())
                {
                        $whereArr[]="MANGLIK <>".$this->getPartnerManglikIgnore()."";
                }

                if($this->getCityRes())
                {
                        $whereArr[]="CITY_RES IN (".$this->getCityRes().")";
                }
                if($this->getOcc())
                {
                        $whereArr[]="OCCUPATION IN (".$this->getOcc().")";
                }
                if($this->getEdu())
                {
                        $whereArr[]="EDU_LEVEL_NEW IN (".$this->getEdu().")";
                }

                if($this->getCountry() && !$relaxForwardFilter)
                {
                        $whereArr[]="COUNTRY_RES IN (".$this->getCountry().")";
                }
                if($this->getPartnerCountryResIgnore())
                {
                        $whereArr[]="COUNTRY_RES <>".$this->getPartnerCountryResIgnore()."";
                }
		if($this->getCasteMtongue())
		{
			$temp=explode("#",$this->getCasteMtongue());
			foreach($temp as $k=>$v)
			{		
				$temp1=explode("-",$v);
				if($temp1)
				{
					if($temp1[1])
						$final[]="(CASTE=$temp1[0] and MTONGUE=$temp1[1])";
					elseif($temp1[0])
						$final[]="(CASTE=$temp1[0])";
				}
			}
			$finalStr=implode(" OR ",$final);
			$whereArr[]="($finalStr)";
		}
		return $whereArr;
	}
}

	
?>
