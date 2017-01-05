<?php
// including for logging purpose
include_once(JsConstants::$docRoot."/classes/LoggingWrapper.class.php");
class VariableDiscount 
{
	private $fixedScore		=79;
	private $fixedDiscount		=20;
	private $baseDiscountPercentage	=85;
	private $cutOffDurationInMonth 	=11;
	private $atsCoolOffPeriod 	=37;
	
        public function __construct($masterDb, $slaveDb=''){

		$this->myDb =$masterDb;
		mysql_query('set session wait_timeout=50000',$this->myDb);

		if($slaveDb)	
			$this->slaveDb =$slaveDb;
		else
			$this->slaveDb =$masterDb;
		mysql_query('set session wait_timeout=50000',$this->slaveDb);

		$this->todayDate 	=date("Y-m-d"); 
		$this->atsCooloffDate 	=date("Y-m-d",strtotime("$this->todayDate - $this->atsCoolOffPeriod days"));

		// truncate table 
                $sqlTrc1 ="TRUNCATE TABLE billing.VARIABLE_DISCOUNT_POOL_TECH_V2";
                mysql_query_decide($sqlTrc1,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqlTrc1.mysql_error($this->myDb)));

        }

	// Steps involved(1)
	// add profiles in VD Pool
	public function addProfileInVddPool()
	{
		$tableArr =array('M','F');
		foreach($tableArr as $key=>$val){
			if($val=='M')	
      	        		$sql ="SELECT PROFILEID from newjs.SEARCH_MALE WHERE AGE>23";
			else
				$sql ="SELECT PROFILEID from newjs.SEARCH_FEMALE";		
	       	        $res = mysql_query_decide($sql,$this->slaveDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($this->slaveDb)));
       		        while($row = mysql_fetch_array($res)){
       	       		        $pid 		=$row['PROFILEID'];
       	       	        	$sql1 		="insert ignore into billing.VARIABLE_DISCOUNT_POOL_TECH_V2(`PROFILEID`) VALUES('$pid')";
       	       	        	mysql_query_decide($sql1,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql1.mysql_error($this->myDb)));
        		}
		}
	}

	// Steps involved(2)
	public function removeVdPoolProfiles($profileArr='')
	{
		$profileStr ='';
		if(is_array($profileArr))
			$profileStr =@implode(",",$profileArr);
		$sql ="delete from billing.VARIABLE_DISCOUNT_POOL_TECH_V2";
		if($profileStr)
			$sql .=" where PROFILEID IN($profileStr)";
		mysql_query_decide($sql,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($this->myDb)));
	}

	// Steps involved(2)
	public function fetchVdPoolProfiles()
	{
		$sql ="select PROFILEID from billing.VARIABLE_DISCOUNT_POOL_TECH_V2";
		$res =mysql_query_decide($sql,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($this->myDb)));
		while($row = mysql_fetch_array($res)){
			$pidArr[] = $row['PROFILEID'];
		}
		return $pidArr;
	}

	// Steps involved(2)
        public function filterPaymentCondition($profileid)
        {
		// Filter ever paid profile
                $sqlP ="select distinct(PROFILEID) from billing.PURCHASES WHERE PROFILEID='$profileid' AND STATUS='DONE' AND MEMBERSHIP='Y'";
                $resP =mysql_query_decide($sqlP,$this->slaveDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqlP.mysql_error($this->slaveDb)));
                if($rowP = mysql_fetch_array($resP)){
                        $pid =$rowP['PROFILEID'];
                        $profiles =$this->fetchClusterProfiles($pid);
			if(count($profiles)==0)
				$profiles =array($pid);
			$this->removeVdPoolProfiles($profiles);
			return;
                }

                // Filter High Score Vd profiles
                $sqlAts ="SELECT PROFILEID FROM MIS.ATS_DISCOUNT WHERE PROFILEID='$profileid' AND ENTRY_DT>'$this->atsCooloffDate'";
                $resAts =mysql_query_decide($sqlAts,$this->slaveDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqlAts.mysql_error($this->slaveDb)));
                if($rowAts = mysql_fetch_array($resAts)){
                        $pid =$rowAts['PROFILEID'];
                        $this->removeVdPoolProfiles(array($pid));
                        return;
                }
        }

        // Steps involved(2)
        public function filterOtherCondition()
        {
                // Filter already given VD profiles 
                $sqlN ="delete billing.VARIABLE_DISCOUNT_POOL_TECH_V2.* from billing.VARIABLE_DISCOUNT_POOL_TECH_V2, billing.VARIABLE_DISCOUNT_POOL_TECH where billing.VARIABLE_DISCOUNT_POOL_TECH_V2.PROFILEID=billing.VARIABLE_DISCOUNT_POOL_TECH.PROFILEID";
                mysql_query_decide($sqlN,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqlN.mysql_error($this->myDb)));
		

                // Filter negative treatment profile
                $sqlN ="delete billing.VARIABLE_DISCOUNT_POOL_TECH_V2.* from billing.VARIABLE_DISCOUNT_POOL_TECH_V2, incentive.NEGATIVE_TREATMENT_LIST where billing.VARIABLE_DISCOUNT_POOL_TECH_V2.PROFILEID=incentive.NEGATIVE_TREATMENT_LIST.PROFILEID AND incentive.NEGATIVE_TREATMENT_LIST.FLAG_OUTBOUND_CALL='N'";
                mysql_query_decide($sqlN,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqlN.mysql_error($this->myDb))); 

                // Filter Fto duplicate profile 
                $sqlF ="delete billing.VARIABLE_DISCOUNT_POOL_TECH_V2.* from billing.VARIABLE_DISCOUNT_POOL_TECH_V2, FTO.FTO_CURRENT_STATE where billing.VARIABLE_DISCOUNT_POOL_TECH_V2.PROFILEID=FTO.FTO_CURRENT_STATE.PROFILEID AND FTO.FTO_CURRENT_STATE.STATE_ID='14'";
                mysql_query_decide($sqlF,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqlF.mysql_error($this->myDb)));
        }


	// Steps involved(2)
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

	// Steps involved(2)
	public function filterVdPoolProfiles()
	{	
		mail("manoj.rana@naukri.com","setp1 Start", date("Y-m-d H:i:s"));
		$this->filterOtherCondition();

		mail("manoj.rana@naukri.com","setp2 Start", date("Y-m-d H:i:s"));
               	$poolProfilesArr =$this->fetchVdPoolProfiles();
		if(count($poolProfilesArr)>0){
			foreach($poolProfilesArr as $keyPid=>$profileid)
				$this->filterPaymentCondition($profileid);
		}
		unset($poolProfilesArr);
                mail("manoj.rana@naukri.com","setp3 Start", date("Y-m-d H:i:s"));
	}

	// Steps involved(3)
        public function setVdDiscount($discountValue='',$profileid='',$setDiscountType='')
        {
		$sql ="update billing.VARIABLE_DISCOUNT_POOL_TECH_V2 SET DISCOUNT='$this->fixedDiscount'";
		mysql_query_decide($sql,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($this->myDb)));
        }

	// Steps involved(4)
	// insert variable discount in VARIABLE_DISCOUNT table 
        public function addVariableDiscount()
        {
		$lastVdGivenDetails 	=$this->fetchVdDates();
                $startDate     		=$lastVdGivenDetails['SDATE'];
                $endDate   		=$lastVdGivenDetails['EDATE'];
		$activationDt 		=$lastVdGivenDetails['ENTRY_DT'];

                $sqlTrc ="TRUNCATE TABLE billing.VD_GIVEN_LASTTIME_V2";
                mysql_query_decide($sqlTrc,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqlTrc.mysql_error($this->myDb)));

                $sql1 ="select PROFILEID,DISCOUNT from billing.VARIABLE_DISCOUNT_POOL_TECH_V2";
                $res1 =mysql_query_decide($sql1,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql1.mysql_error($this->myDb)));
                while($row1 =mysql_fetch_array($res1)){
			$discount 	=$row1['DISCOUNT'];
			$profileid	=$row1['PROFILEID'];
			$sqlIns ="insert ignore into billing.VARIABLE_DISCOUNT (`PROFILEID`,`DISCOUNT`,`SDATE`,`EDATE`,`ENTRY_DT`) VALUES('$profileid','$discount','$startDate','$endDate','$activationDt')";
			mysql_query_decide($sqlIns,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqlIns.mysql_error($this->myDb)));

			$sqlLog ="insert ignore into billing.VD_GIVEN_LASTTIME_V2(PROFILEID) VALUES('$profileid')";
			mysql_query_decide($sqlLog,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sqlLog.mysql_error($this->myDb)));
		}
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
        public function logVdProcess($processStep)
        {
                $sql ="update billing.VARIABLE_DISCOUNT_DURATION SET STEPS_COMPLETED='$processStep' ORDER BY ENTRY_DT DESC LIMIT 1";
                mysql_query_decide($sql,$this->myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($this->myDb)));
        }
}
?>
