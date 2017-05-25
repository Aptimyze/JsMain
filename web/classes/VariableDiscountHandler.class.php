<?php
// including for logging purpose
include_once(JsConstants::$docRoot."/classes/LoggingWrapper.class.php");
class VariableDiscountHandler 
{
	private $cutOffDurationInMonth 	=11;
	private $emailId ='manoj.rana@naukri.com'; 	
	
        public function __construct($masterDb, $slaveDb=''){

		$this->myDb =$masterDb;
		mysql_query('set session wait_timeout=50000',$this->myDb);

		if($slaveDb)	
			$this->slaveDb =$slaveDb;
		else
			$this->slaveDb =$masterDb;
		mysql_query('set session wait_timeout=50000',$this->slaveDb);

		$this->todayDate 		=date("Y-m-d"); 
		$this->cutOffDate		=date("Y-m-d",strtotime("$this->todayDate - $this->cutOffDurationInMonth month"));
                $this->twoMonthOldDate  	=date("Y-m-d",strtotime("$this->todayDate - 2 month"));
		$this->lastWeekDate		=date("Y-m-d",strtotime("$this->todayDate - 6 days"));     
		$this->discountFieldMapping 	=array("1"=>"1_DISCOUNT","2"=>"2_DISCOUNT","3"=>"3_DISCOUNT","6"=>"6_DISCOUNT","12"=>"12_DISCOUNT","L"=>"L_DISCOUNT");

		// New table structure is created as required
		$this->createNewTableStructure();
        }

	// Steps involved(1,3)
	// add profiles in VD Pool
	public function addProfileInVddPool()
	{
      	        $sql ="SELECT PROFILEID,ANALYTIC_SCORE FROM incentive.MAIN_ADMIN_POOL WHERE CUTOFF_DT>='$this->cutOffDate' AND ENTRY_DT<'$this->lastWeekDate'";
       	        $res = mysql_query_decide($sql,$this->slaveDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($this->slaveDb)));
       	        while($row = mysql_fetch_array($res)){
       	       	        $pid 		=$row['PROFILEID'];
			$analyticScore 	=$row['ANALYTIC_SCORE'];
       	       	        $sql1 		="insert ignore into billing.VARIABLE_DISCOUNT_POOL_TECH(`PROFILEID`,`SCORE`) VALUES('$pid','$analyticScore')";
       	       	        mysql_query_decide($sql1,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql1.mysql_error($this->myDb)));

                        $sql1           ="insert ignore into billing.VARIABLE_DISCOUNT_POOL_TECH_LOG1(`PROFILEID`,`SCORE`) VALUES('$pid','$analyticScore')";
                        mysql_query_decide($sql1,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql1.mysql_error($this->myDb)));

        	}
	}

        // Steps involved(2,4)
	// filter conditions
        public function filterVdPoolProfiles()
        {
                mail($this->emailId,"Step-2 VD (Filter: OtherCondition) Start", date("Y-m-d H:i:s"));
                $this->filterOtherCondition();

                mail($this->emailId,"Step-2 VD (Filter: DuplicateClusterProfiles) Start", date("Y-m-d H:i:s"));
                $poolProfilesArr =$this->fetchVdPoolProfiles();
                if(count($poolProfilesArr)>0){
                        foreach($poolProfilesArr as $keyPid=>$profileid)
                        	 $this->filterDuplicateClusterProfiles($profileid);
                }
                unset($poolProfilesArr);

                mail($this->emailId,"Step-2 VD (Filter:JprofileCondition) Start", date("Y-m-d H:i:s"));
                $poolProfilesArr =$this->fetchVdPoolProfiles();
                if(count($poolProfilesArr)>0){
                        foreach($poolProfilesArr as $keyPid=>$profileid)
                                $this->filterJprofileCondition($profileid);
                }
                unset($poolProfilesArr);
        }

        // Steps involved(5)
	// get VD Discount
        public function calculateVdDiscount()
        {
                $profilesArr    =$this->fetchVdPoolProfiles();
                $mTongueArr     =$this->fetchMtongue();
                if(count($profilesArr)>0){
                        foreach($profilesArr as $keyProfileid=>$profileid){
                                $discountArr =$this->fetchVdDiscount($profileid,$mTongueArr);
                                if($discountArr)
                                        $this->setVdDiscount($discountArr,$profileid);
                        }
                }
        }

        // Steps involved(6)
        // add variable discount in VARIABLE_DISCOUNT table 
        public function addVariableDiscount()
        {
                $lastVdGivenDetails     =$this->fetchVdDates();
                $startDate              =$lastVdGivenDetails['SDATE'];
                $endDate                =$lastVdGivenDetails['EDATE'];
                $activationDt           =$lastVdGivenDetails['ENTRY_DT'];

                /*$todayDate              = date("Y-m-d");
		$timeVal		= date('H');
		$timeArr		= array("18","19","20","21","22","23","24");
                if((strtotime($startDate) != strtotime($todayDate)) && (!in_array($timeVal, $timeArr)))
			return;
		*/

                $sql1 ="select * from billing.VARIABLE_DISCOUNT_POOL_TECH";
                $res1 =mysql_query_decide($sql1,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql1.mysql_error($this->myDb)));
                while($row1 =mysql_fetch_array($res1)){

                        $profileid      =$row1['PROFILEID'];
			$discount       =$this->getMaxDiscount($profileid);

			// Add profile in VD table
                        $sqlIns ="insert ignore into billing.VARIABLE_DISCOUNT (`PROFILEID`,`DISCOUNT`,`SDATE`,`EDATE`,`ENTRY_DT`) VALUES('$profileid','$discount','$startDate','$endDate','$activationDt')";
                        mysql_query_decide($sqlIns,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqlIns.mysql_error($this->myDb)));

			// Add profiles in VD Offer Duration table
			$sqlVdDuration ="insert ignore into billing.VARIABLE_DISCOUNT_OFFER_DURATION select * from billing.VARIABLE_DISCOUNT_DURATION_POOL_TECH WHERE PROFILEID='$profileid'";			
			mysql_query_decide($sqlVdDuration,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqlVdDuration.mysql_error($this->myDb)));
                }
        }

	// removed profiles from VD Pool 
	public function removeVdPoolProfiles($profileArr,$flag='')
	{
		if(is_array($profileArr))
			$profileStr =@implode(",",$profileArr);
		$sql ="delete from billing.VARIABLE_DISCOUNT_POOL_TECH where PROFILEID IN($profileStr)";
		mysql_query_decide($sql,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($this->myDb)));
		foreach($profileArr as $key=>$pid){
			$sqlLog ="insert into billing.VD_FILTER_LOG(`PROFILEID`,`TYPE`,`ENTRY_DT`) VALUES('$pid','$flag',now())";
			mysql_query_decide($sqlLog,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqlVdDuration.mysql_error($this->myDb)));
		}
	}

	// fetch profiles from VD Pool
	public function fetchVdPoolProfiles()
	{
		$sql ="select PROFILEID from billing.VARIABLE_DISCOUNT_POOL_TECH";
		$res =mysql_query_decide($sql,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($this->myDb)));
		while($row = mysql_fetch_array($res)){
			$pidArr[] = $row['PROFILEID'];
		}
		return $pidArr;
	}

        public function filterJprofileCondition($profileid)
        {
                // Filter Jprofile condition
                $sqlJ ="select GENDER,MTONGUE,AGE,SUBSCRIPTION from newjs.JPROFILE where PROFILEID='$profileid' AND ACTIVATED='Y' AND activatedKey=1";
                $resJ =mysql_query_decide($sqlJ,$this->slaveDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqlJ.mysql_error($this->slaveDb)));
                if($rowJ = mysql_fetch_array($resJ)){
                        $gender         =$rowJ['GENDER'];
                        $mtongue        =$rowJ['MTONGUE'];
                        $age            =$rowJ['AGE'];
			$subscription	=$rowJ['SUBSCRIPTION'];
                        if($gender=='M' && $age<=23){
                                $this->removeVdPoolProfiles(array($profileid),'G');
                                return;
                        }
			$subExpiryPeriodExist =$this->checkSubscriptionExpiryPeriod($profileid);
			if(!$subExpiryPeriodExist){
                        	if((strstr($subscription,"F")!="")||(strstr($subscription,"D")!="")){
					$this->removeVdPoolProfiles(array($profileid),'P');
					return;
				}
			}
			else if($subExpiryPeriodExist){
				$this->removeVdPoolProfiles(array($profileid),'R');
				return;
			}
                	$sqlVd ="update billing.VARIABLE_DISCOUNT_POOL_TECH SET GENDER='$gender',MTONGUE='$mtongue' where PROFILEID='$profileid'";
                	mysql_query_decide($sqlVd,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqlVd.mysql_error($this->myDb)));
		}else
			$this->removeVdPoolProfiles(array($profileid),'N');
	}	

	// Profiles expiring in the period of- 30 days before expiry to 10 days after expiry.
        public function checkSubscriptionExpiryPeriod($profileid)
        {
                unset($profileFlagSet);
                $sqlP ="SELECT MAX(EXPIRY_DT) AS EXP_DT FROM billing.SERVICE_STATUS WHERE (SERVEFOR LIKE '%F%' OR SERVEFOR='X') AND ACTIVE IN('Y','E') AND PROFILEID=$profileid";
                $resP =mysql_query_decide($sqlP,$this->slaveDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqlP.mysql_error($this->slaveDb)));
                $rowP = mysql_fetch_array($resP);
                $mainMemExpiryDate =$rowP['EXP_DT'];
                if($mainMemExpiryDate){
                        $expiryDateSetw30 =date('Y-m-d',time()+29*86400);
                        $expiryDateSetw10 =date('Y-m-d',time()-10*86400);
                        if($mainMemExpiryDate>=date('Y-m-d',time()) && $mainMemExpiryDate<=$expiryDateSetw30)
                                $profileFlagSet =true;
                        elseif($mainMemExpiryDate<date('Y-m-d',time()) && $mainMemExpiryDate>=$expiryDateSetw10)
                                $profileFlagSet =true;

			return $profileFlagSet;
                }
	}

        public function filterOtherCondition()
        {
                // Filter negative treatment profile
                $sqlN ="delete billing.VARIABLE_DISCOUNT_POOL_TECH.* from billing.VARIABLE_DISCOUNT_POOL_TECH, incentive.NEGATIVE_TREATMENT_LIST where billing.VARIABLE_DISCOUNT_POOL_TECH.PROFILEID=incentive.NEGATIVE_TREATMENT_LIST.PROFILEID AND incentive.NEGATIVE_TREATMENT_LIST.FLAG_OUTBOUND_CALL='N'";
                mysql_query_decide($sqlN,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqlN.mysql_error($this->myDb)));
        }

	// fetch Duplicate Cluster Profiles
	public function fetchClusterProfiles($profileid)
	{
		$sql ="select DUPLICATE_ID from duplicates.DUPLICATE_PROFILES where PROFILEID='$profileid'";
		$res =mysql_query_decide($sql,$this->slaveDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($this->slaveDb)));
		if($row = mysql_fetch_array($res)){
			$duplicateId =$row['DUPLICATE_ID'];
			$sql1 ="select PROFILEID from duplicates.DUPLICATE_PROFILES where DUPLICATE_ID='$duplicateId'";
			$res1 =mysql_query_decide($sql1,$this->slaveDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql1.mysql_error($this->slaveDb)));
			while($row1 = mysql_fetch_array($res1)){
				$pidArr[] =$row1['PROFILEID'];	
			}
		}
		return $pidArr;
	}

        public function filterDuplicateClusterProfiles($profileid)
        {	
                $profilesArr =$this->fetchClusterProfiles($profileid);
		if(count($profilesArr)==0)
			$profilesArr =array($profileid);
		
		if(count($profilesArr)>1){
			$profileStr =implode(",",$profilesArr);
			$maxscore=0;
        	        $sql ="select SCORE,PROFILEID from billing.VARIABLE_DISCOUNT_POOL_TECH WHERE PROFILEID IN($profileStr) ORDER BY PROFILEID ASC";
        	        $res =mysql_query_decide($sql,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($this->myDb)));
        	        while($row = mysql_fetch_array($res))
			{
				if($row['SCORE']>$maxscore)
				{
					$maxscore = $row['SCORE'];
					$maxprofileid = $row['PROFILEID'];
				}
				$pidArr[$row['PROFILEID']] =$row['PROFILEID'];
			}
			if(count($pidArr)>1){
        	        	unset($pidArr[$maxprofileid]);                  		
        	       		$this->removeVdPoolProfiles($pidArr,'D');
			}	
		}
		unset($pidArr);
		unset($pidArr1);
		unset($profilesArr);		
        }

        public function setVdDiscount($discountArr,$profileid)
        {
		if(is_array($discountArr)){
			foreach($discountArr as $service=>$valArr){
				foreach($valArr as $fieldName=>$discount){
					$fieldNameArr[] =$fieldName;
					$valuesArr[] 	=$discount;			
				}
				// Start : Code to pick last discount capping by executive
				$discNegLogObj = new incentive_DISCOUNT_NEGOTIATION_LOG('newjs_masterRep');
				$lastNegDet = $discNegLogObj->getLastNegotiatedDiscountDetails($profileid);
				if (!empty($lastNegDet) && strtotime($lastNegDet['ENTRY_DT']) < time() && strtotime($lastNegDet['EXPIRY_DT']) > time()) {
					$valuesArrTemp = $valuesArr;	
					foreach ($valuesArrTemp as $key=>$val) {
						$valuesArr[$key] = min($val, $lastNegDet['DISCOUNT']);
					}
					unset($valuesArrTemp);
				}
				unset($discNegLogObj, $lastNegDet);
				// End : Code to pick last discount capping by executive
				$valuesArr[] 	=$profileid;
				$valuesArr[] 	=$service;
				$valuesStr 	="'".implode("','",$valuesArr)."'";
				$fieldsStr 	=implode(",",$fieldNameArr).",PROFILEID,SERVICE"; 
			
				$sql ="insert ignore into billing.VARIABLE_DISCOUNT_DURATION_POOL_TECH($fieldsStr) VALUES($valuesStr)";		
				mysql_query_decide($sql,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($this->myDb)));
				unset($fieldNameArr);
				unset($valuesArr);	
				unset($valuesStr);
				unset($fieldsStr);
			}
		}
        }
	public function getMaxDiscount($profileid)
	{
		$sql ="select * from billing.VARIABLE_DISCOUNT_DURATION_POOL_TECH where PROFILEID='$profileid'";	
		$res =mysql_query_decide($sql,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($this->myDb)));
		while($row = mysql_fetch_assoc($res)){
			$discountDetails[] =$row;
		}		
		if(count($discountDetails)>0){
	                foreach($discountDetails as $key=>$val){
	                        $discountArr =$val;
	                        unset($discountArr['PROFILEID']);
	                        unset($discountArr['SERVICE']);
	                        foreach($discountArr as $key1=>$val1)
	                                $discountNewArr[] =$val1;
	                }
			$maxDiscount =max($discountNewArr);
		}
		return $maxDiscount;
	}
	public function fetchVdDiscount($profileid,$mTongueArr)
	{
		$discountArr =array();
		$sql ="select SCORE,GENDER,MTONGUE from billing.VARIABLE_DISCOUNT_POOL_TECH where PROFILEID='$profileid'";
                $res =mysql_query_decide($sql,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($this->myDb)));
                if($row = mysql_fetch_array($res)){
                        $score 		=$row['SCORE'];
			$gender		=$row['GENDER'];
			$mtongue 	=$row['MTONGUE'];
			if(!in_array($mtongue,$mTongueArr))
				$mtongue =0;

			$discountFieldNameStr =implode(",",$this->discountFieldMapping);
			$discountFieldNameStr.=",SERVICE";	 
			$sqlLook ="select $discountFieldNameStr from billing.DISCOUNT_LOOKUP where SCORE_LOWER_LIMIT<=$score AND SCORE_UPPER_LIMIT>='$score' AND GENDER='$gender' AND MTONGUE='$mtongue'";
			$resLook =mysql_query_decide($sqlLook,$this->slaveDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqlLook.mysql_error($this->slaveDb)));
			while($rowLook = mysql_fetch_array($resLook)){
				$service =$rowLook['SERVICE'];
				foreach($this->discountFieldMapping as $duration=>$fieldName)
					$discountArr[$service][$fieldName] =$rowLook["$fieldName"];
			}
			return $discountArr;	
		}
		return;
	}

	// function to get last Vd given dates
        public function fetchVdDates()
        {
		$vdDetailsArr =array();
                $sql ="select * from billing.VARIABLE_DISCOUNT_DURATION ORDER BY ENTRY_DT DESC LIMIT 1";
                $res =mysql_query_decide($sql,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($this->myDb)));
                $row =mysql_fetch_array($res);
                $vdDetailsArr['SDATE']     =$row['SDATE'];
                $vdDetailsArr['EDATE']     =$row['EDATE'];
                $vdDetailsArr['ENTRY_DT']  =$row['ENTRY_DT'];
		return $vdDetailsArr;
	}

        // function to get last Vd given dates
        public function logVdProcess($processStep,$status='')
        {
                $sql ="update billing.VARIABLE_DISCOUNT_DURATION SET STEPS_COMPLETED='$processStep'";
		if($status)
			$sql .=",STATUS='$status'";
		$sql .=" ORDER BY ENTRY_DT DESC LIMIT 1";
                mysql_query_decide($sql,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($this->myDb)));
        }

        // function to get mtongue values
        public function fetchMtongue()
        {
		$mTongueArr =array();
                $sql ="select distinct(MTONGUE) MTONGUE from billing.DISCOUNT_LOOKUP";
                $res =mysql_query_decide($sql,$this->slaveDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($this->slaveDb)));
                while($row =mysql_fetch_array($res))
	                $mTongueArr[] =$row['MTONGUE'];
                return $mTongueArr;
        }
	// function to check if currently VD Offer is active
        public function isVdActive()
        {
		$totCnt =0;
                $sql ="select count(vd.PROFILEID) as cnt from billing.VARIABLE_DISCOUNT vd inner join billing.VARIABLE_DISCOUNT_OFFER_DURATION vd_od on vd.PROFILEID=vd_od.PROFILEID AND vd.EDATE>=CURDATE()";
                $res =mysql_query_decide($sql,$this->slaveDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($this->slaveDb)));
                if($row =mysql_fetch_array($res))
                        $totCnt =$row['cnt'];
		return $totCnt;
        }
	// function to create table structure (tables:billing.VARIABLE_DISCOUNT_POOL_TECH, billing.VARIABLE_DISCOUNT_OFFER_DURATION)
	public function createNewTableStructure()
	{
                // Truncate table billing.VARIABLE_DISCOUNT_POOL_TECH
                $sql ="TRUNCATE TABLE billing.VARIABLE_DISCOUNT_POOL_TECH";
                mysql_query_decide($sql,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($this->myDb)));

                // Truncate table billing.VARIABLE_DISCOUNT_POOL_TECH
                $sql ="TRUNCATE TABLE billing.VARIABLE_DISCOUNT_DURATION_POOL_TECH";
                mysql_query_decide($sql,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($this->myDb)));

                $sql ="TRUNCATE TABLE billing.VARIABLE_DISCOUNT_POOL_TECH_LOG1";
                mysql_query_decide($sql,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($this->myDb)));

                $sql ="TRUNCATE TABLE billing.VD_FILTER_LOG";
                mysql_query_decide($sql,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($this->myDb)));

	}

	public function checkDiscountEligibleStatus(){

		$curHr =date("H");
                $sql ="select * from billing.VARIABLE_DISCOUNT_DURATION ORDER BY ENTRY_DT DESC LIMIT 1";
                $res =mysql_query_decide($sql,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($this->myDb)));
                if($row =mysql_fetch_array($res)){
                	$status     	=$row['STATUS'];
			if($status!='Y')
				return;
                	$scheduleDate  	=$row['SCHEDULE_DATE'];
			list($schDate, $schTime) 	=explode(" ",$scheduleDate );
			list($schHr, $schMin,$schSec)	=explode(":", $schTime);  

			if((strtotime($schDate)==strtotime($this->todayDate)) && ($schHr==$curHr)){
				return 1;
			}
		}
		return;
	}

}
?>
