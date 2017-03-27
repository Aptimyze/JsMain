<?php
//This class handles all the logics related to Field Sales Executive Performance MIS

class FieldSalesExecutivePerformanceMis
{
	private $resultArr;
	private $dateRange;
	private $start_dt;
	private $end_dt;
	private $execArray;
	private $execDetailsArr;

	public function __construct($execArray,$start_dt,$end_dt,$dateArr="")
	{
		$this->start_dt = $start_dt." 00:00:00";
		$this->end_dt = $end_dt." 23:59:59";
		$this->execArray = $execArray;

		if($dateArr && is_array($dateArr))
		{
			foreach($dateArr as $k=>$v)
			{
				$this->resultArr[$k]["DATE"] = $v;
			}
		}
	}

	public function setDateRange($start_dt, $end_dt)
	{
		$this->dateRange[0] = $start_dt." 00:00:00";
		$this->dateRange[1] = $end_dt." 23:59:59";
	}
	public function getDateRange(){return $this->dateRange;}
	public function getResultArr(){return $this->resultArr;}

	public function getExecDetailsArr()
	{
		if($this->execDetailsArr && is_array($this->execDetailsArr))		//Sorting on the basis of allocation date
		{
			foreach($this->execDetailsArr as $k=>$v)
			{
				$tempArr[$k] = strtotime($v["ALLOCATION_DATE"]);
			}
			asort($tempArr);
			foreach($tempArr as $k=>$v)
			{
				$sortedArr[] = $this->execDetailsArr[$k];
			}
			unset($this->execDetailsArr);
			foreach($sortedArr as $k=>$v)
			{
				$sortedArr[$k]["NO"] = $k+1;
			}
			$this->execDetailsArr = $sortedArr;
			unset($sortedArr);
		}
		return $this->execDetailsArr;
	}

	public function setExecDetailsArr($x='')
	{
		if(!$x)
			unset($this->execDetailsArr);
		else
			$this->execDetailsArr = $x;
	}

	//This function generates the fresh visits count
	public function generateFreshVisitsData($filterDocumentVerification = false)
	{
		if($this->execArray && is_array($this->execArray))
		{
			$incHisObj = new incentive_HISTORY('newjs_slave');
			$output = $incHisObj->getFreshVisitDoneDataForExecs($this->execArray,$this->start_dt,$this->end_dt);
			unset($incHisObj);

			$start_dt = date('Y-m-d 00:00:00', strtotime('-29 day', strtotime($this->start_dt)));
			if($output && is_array($output))
			{
				$proVerDocObj = new PROFILE_VERIFICATION_DOCUMENTS('newjs_slave');
				foreach($output as $k=>$v) {
					$profilesArr[] = $v["PROFILEID"];
					$countArr = $proVerDocObj->countVerifiedDocumentsForProfilesArr($profilesArr);
				}
				foreach($output as $k=>$v)
				{
					if($filterDocumentVerification == true){
						$count = $countArr[$v["PROFILEID"]];
					} else {
						$count = 1;
					}
					if($count >= 1){
						$result = $this->getAgentAllotedData($v["ENTRYBY"],$v["PROFILEID"]);
						if($result && is_array($result))
						{
							if($flag==0 && $result["ALLOT_TIME"]<=$v["ENTRY_DT"] && $result["DE_ALLOCATION_DT"]." 23:59:59">=$v["ENTRY_DT"])
							{
								$maObj = new MANUAL_ALLOT('newjs_slave');
								$fs_alloc_stat = $maObj->getAllocationStatus($v["ENTRYBY"],$v["PROFILEID"],$start_dt,$this->end_dt);
								if($fs_alloc_stat != 'FS') 	continue;
								unset($maObj);

								$date = date("Y-m-d",strtotime($v["ENTRY_DT"]));
								if($this->resultArr[$date][$v["ENTRYBY"]]["FRESH_VISITS"])
									$this->resultArr[$date][$v["ENTRYBY"]]["FRESH_VISITS"] = $this->resultArr[$date][$v["ENTRYBY"]]["FRESH_VISITS"]+1;
								else
									$this->resultArr[$date][$v["ENTRYBY"]]["FRESH_VISITS"] = 1;
							}
						}
					}
				}
			}
		}
	}

	//This function generated the profiles paid count
	public function generateProfilesWhichPaidData()
	{
		$start_dt = date('Y-m-d 00:00:00', strtotime('-29 day', strtotime($this->start_dt)));
		if($this->execArray && is_array($this->execArray))
		{
			$incMIEObj = new incentive_MONTHLY_INCENTIVE_ELIGIBILITY('newjs_slave');
			$output = $incMIEObj->getProfilesWhichPaidDataForExecs($this->execArray,$this->start_dt,$this->end_dt);
			unset($incMIEObj);
		}
		if($output && is_array($output))
		{
			foreach($output as $k=>$v){
				$maObj = new MANUAL_ALLOT('newjs_slave');
				$fs_alloc_stat = $maObj->getAllocationStatus($v["ALLOTED_TO"],$v["PROFILEID"],$start_dt,$this->end_dt);
				$result = $this->getAgentAllotedData($v["ALLOTED_TO"],$v["PROFILEID"]);
				if($fs_alloc_stat != 'FS' || $result["ALLOT_TIME"]>$v["ENTRY_DT"] || $result["DE_ALLOCATION_DT"]<$v["D"])
				 	continue;
				unset($maObj);
				$this->resultArr[$v["D"]][$v["ALLOTED_TO"]]["PROFILES_COUNT_WHO_PAID"] += 1;
			}
		}
	}

	//This function generates the total sales amount
	public function generateSalesData()
	{
		$start_dt = date('Y-m-d 00:00:00', strtotime('-29 day', strtotime($this->start_dt)));
		{
			$incMIEObj = new incentive_MONTHLY_INCENTIVE_ELIGIBILITY('newjs_slave');
			$output = $incMIEObj->getSalesDataForExecs1($this->execArray,$this->start_dt,$this->end_dt);
			unset($incMIEObj);
		}

		if($output && is_array($output))
		{
			foreach($output as $k=>$v){
				$maObj = new MANUAL_ALLOT('newjs_slave');
				$fs_alloc_stat = $maObj->getAllocationStatus($v["ALLOTED_TO"],$v["PROFILEID"],$start_dt,$this->end_dt);
				$result = $this->getAgentAllotedData($v["ALLOTED_TO"],$v["PROFILEID"]);
				if($fs_alloc_stat != 'FS' || $result["ALLOT_TIME"]>$v["ENTRY_DT"] || $result["DE_ALLOCATION_DT"]<$v["D"])
				 	continue;
				unset($maObj);
				$this->resultArr[$v["D"]][$v["ALLOTED_TO"]]["SALES"] += $v["AMOUNT"];
			}
		}
	}

	//This function performs the exec wise summation, day wise summation and grand total
	public function generateExecutiveWiseAndDayWiseSummation()
	{
		if($this->resultArr && is_array($this->resultArr))
		{
			foreach($this->resultArr as $k=>$v)
			{
				foreach($v as $kk=>$vv)
				{
					if($kk!="DATE")
					{
						if($execWiseSummation[$kk] && is_array($execWiseSummation[$kk]))
						{
							if($vv["FRESH_VISITS"])
								$execWiseSummation[$kk]["FRESH_VISITS"] = $execWiseSummation[$kk]["FRESH_VISITS"] + $vv["FRESH_VISITS"];
							if($vv["PROFILES_COUNT_WHO_PAID"])
								$execWiseSummation[$kk]["PROFILES_COUNT_WHO_PAID"] = $execWiseSummation[$kk]["PROFILES_COUNT_WHO_PAID"] + $vv["PROFILES_COUNT_WHO_PAID"];
							if($vv["SALES"])
								$execWiseSummation[$kk]["SALES"] = $execWiseSummation[$kk]["SALES"] + $vv["SALES"];
						}
						else
						{
							if($vv["FRESH_VISITS"])
								$execWiseSummation[$kk]["FRESH_VISITS"] = $vv["FRESH_VISITS"];
							else
								$execWiseSummation[$kk]["FRESH_VISITS"] = 0;
							if($vv["PROFILES_COUNT_WHO_PAID"])
								$execWiseSummation[$kk]["PROFILES_COUNT_WHO_PAID"] = $vv["PROFILES_COUNT_WHO_PAID"];
							else
								$execWiseSummation[$kk]["PROFILES_COUNT_WHO_PAID"] = 0;
							if($vv["SALES"])
								$execWiseSummation[$kk]["SALES"] = $vv["SALES"];
							else
								$execWiseSummation[$kk]["SALES"] = 0;
						}

						if($dayWiseSummation[$k] && is_array($dayWiseSummation[$k]))
						{
							if($vv["FRESH_VISITS"])
								$dayWiseSummation[$k]["FRESH_VISITS"] = $dayWiseSummation[$k]["FRESH_VISITS"] + $vv["FRESH_VISITS"];
							if($vv["PROFILES_COUNT_WHO_PAID"])
								$dayWiseSummation[$k]["PROFILES_COUNT_WHO_PAID"] = $dayWiseSummation[$k]["PROFILES_COUNT_WHO_PAID"] + $vv["PROFILES_COUNT_WHO_PAID"];
							if($vv["SALES"])
								$dayWiseSummation[$k]["SALES"] = $dayWiseSummation[$k]["SALES"] + $vv["SALES"];
						}
						else
						{
							if($vv["FRESH_VISITS"])
								$dayWiseSummation[$k]["FRESH_VISITS"] = $vv["FRESH_VISITS"];
							else
								$dayWiseSummation[$k]["FRESH_VISITS"] = 0;
							if($vv["PROFILES_COUNT_WHO_PAID"])
								$dayWiseSummation[$k]["PROFILES_COUNT_WHO_PAID"] = $vv["PROFILES_COUNT_WHO_PAID"];
							else
								$dayWiseSummation[$k]["PROFILES_COUNT_WHO_PAID"] = 0;
							if($vv["SALES"])
								$dayWiseSummation[$k]["SALES"] = $vv["SALES"];
							else
								$dayWiseSummation[$k]["SALES"] = 0;
						}

						if($totalSummation && is_array($totalSummation))
						{
							if($vv["FRESH_VISITS"])
								$totalSummation["FRESH_VISITS"] = $totalSummation["FRESH_VISITS"] + $vv["FRESH_VISITS"];
							if($vv["PROFILES_COUNT_WHO_PAID"])
								$totalSummation["PROFILES_COUNT_WHO_PAID"] = $totalSummation["PROFILES_COUNT_WHO_PAID"] + $vv["PROFILES_COUNT_WHO_PAID"];
							if($vv["SALES"])
								$totalSummation["SALES"] = $totalSummation["SALES"] + $vv["SALES"];
						}
						else
						{
							if($vv["FRESH_VISITS"])
								$totalSummation["FRESH_VISITS"] = $vv["FRESH_VISITS"];
							else
								$totalSummation["FRESH_VISITS"] = 0;
							if($vv["PROFILES_COUNT_WHO_PAID"])
								$totalSummation["PROFILES_COUNT_WHO_PAID"] = $vv["PROFILES_COUNT_WHO_PAID"];
							else
								$totalSummation["PROFILES_COUNT_WHO_PAID"] = 0;
							if($vv["SALES"])
								$totalSummation["SALES"] = $vv["SALES"];
							else
								$totalSummation["SALES"] = 0;
						}

					}
				}
			}
		}
		$output["EXEC_WISE"] = $execWiseSummation;
		$output["DAY_WISE"] = $dayWiseSummation;
		$output["GRAND_TOTAL"] = $totalSummation;
		return $output;
	}

	//This function is called when a hyperlink for fresh visits is clicked for an executive on Result Screen 1
	public function getFreshVisitsDataDetails()
	{
		if($this->execArray && is_array($this->execArray))
		{
			$incHisObj = new incentive_HISTORY('newjs_slave');
			$infoArr = $incHisObj->getFreshVisitDoneDataForExecs($this->execArray,$this->dateRange[0],$this->end_dt);
			foreach($infoArr as $v) {
				$freshDataArr[$v['ENTRYBY']][$v['PROFILEID']] = $v['ENTRY_DT'];
			}

			$output = $incHisObj->getFreshVisitDoneDataForExecs($this->execArray,$this->start_dt,$this->end_dt);
			unset($incHisObj);

			$start_dt = date('Y-m-d 00:00:00', strtotime('-29 day', strtotime($this->dateRange[0])));
			if($output && is_array($output))
			{
				$i=0;
				foreach($output as $k=>$v)
				{
					if($v['ENTRY_DT'] > $freshDataArr[$v['ENTRYBY']][$v['PROFILEID']])
						continue;
					$result = $this->getAgentAllotedData($v["ENTRYBY"],$v["PROFILEID"]);
					if($result && is_array($result))
					{
						if($flag==0 && $result["ALLOT_TIME"]<=$v["ENTRY_DT"] && $result["DE_ALLOCATION_DT"]." 23:59:59">=$v["ENTRY_DT"])
						{
							$maObj = new MANUAL_ALLOT('newjs_slave');
							$fs_alloc_stat = $maObj->getAllocationStatus($v["ENTRYBY"],$v["PROFILEID"],$start_dt,$this->dateRange[1]);
							if($fs_alloc_stat != 'FS') 	continue;
							unset($maObj);

							$execDetailsArr[$i]["USERNAME"] = $v["USERNAME"];
							$execDetailsArr[$i]["PROFILEID"] = $v["PROFILEID"];
							$execDetailsArr[$i]["ALLOCATION_DATE"] = date("j F Y",strtotime($result["ALLOT_TIME"]));
							$execDetailsArr[$i]["FRESH_VISIT_DATE"] = date("j F Y",strtotime($v["ENTRY_DT"]));
							$execDetailsArr[$i]["ALLOT_TIME"] = $result["ALLOT_TIME"];
							$execDetailsArr[$i]["DE_ALLOT_TIME"] = $result["DE_ALLOCATION_DT"]." 23:59:59";
							$i++;
						}
					}
					unset($result);
				}
			}
			unset($output);

			if($execDetailsArr && is_array($execDetailsArr))
			{
				$jj=0;
				foreach($execDetailsArr as $k=>$v)
				{
					$imieObj = new incentive_MONTHLY_INCENTIVE_ELIGIBILITY('newjs_slave');
					$output = $imieObj->getSalesDataForExecs($this->execArray,$v["ALLOT_TIME"],$v["DE_ALLOT_TIME"],$v["PROFILEID"]);
					unset($imieObj);

					$profileid = $v["PROFILEID"];
					$flag = 1;
					if($output && is_array($output))
					{
						foreach($output as $kk=>$vv)
						{
							$this->execDetailsArr[$jj]["USERNAME"] = $v["USERNAME"];
							$this->execDetailsArr[$jj]["PROFILEID"] = $v["PROFILEID"];
							$this->execDetailsArr[$jj]["ALLOCATION_DATE"] = $v["ALLOCATION_DATE"];
							$this->execDetailsArr[$jj]["FRESH_VISIT_DATE"] = $v["FRESH_VISIT_DATE"];
							$this->execDetailsArr[$jj]["PAYMENT_DATE"] = date("j F Y",strtotime($vv["D"]));
							$this->execDetailsArr[$jj]["AMOUNT"] = $vv["AMOUNT"];
							$this->execDetailsArr[$jj]["ALLOT_TT"] = $v["ALLOT_TIME"];
							$this->execDetailsArr[$jj]["PAYMENT_DD"] = $vv["D"];
							$jj++;
							$flag = 0;
						}
					}

					if($flag)
					{
						$this->execDetailsArr[$jj]["USERNAME"] = $v["USERNAME"];
						$this->execDetailsArr[$jj]["PROFILEID"] = $v["PROFILEID"];
						$this->execDetailsArr[$jj]["ALLOCATION_DATE"] = $v["ALLOCATION_DATE"];
						$this->execDetailsArr[$jj]["FRESH_VISIT_DATE"] = $v["FRESH_VISIT_DATE"];
						$this->execDetailsArr[$jj]["PAYMENT_DATE"] = "";
						$this->execDetailsArr[$jj]["AMOUNT"] = "";
						$this->execDetailsArr[$jj]["ALLOT_TT"] = $v["ALLOT_TIME"];
						$this->execDetailsArr[$jj]["PAYMENT_DD"] = $vv["D"];
						$jj++;
					}
				}
			}
		}
	}

	//This function is called when a hyperlink for sales amount or profiles count which paid is clicked for an executive on Result Screen 1
	public function getProfilesPaymentDataDetails()
	{
		$imieObj = new incentive_MONTHLY_INCENTIVE_ELIGIBILITY('newjs_slave');
		$output = $imieObj->getSalesDataForExecs($this->execArray,$this->start_dt,$this->end_dt,'',1);
		$start_dt = date('Y-m-d 00:00:00', strtotime('-29 day', strtotime($this->dateRange[0])));
		foreach ($output as $k => $v) {
			$maObj = new MANUAL_ALLOT('newjs_slave');
			$fs_alloc_stat = $maObj->getAllocationStatus($v["ALLOTED_TO"],$v["PROFILEID"],$start_dt,$this->dateRange[1]);
			if($fs_alloc_stat != 'FS') 	unset($output[$k]);
			unset($maObj);
		}
		unset($imieObj);
		if(is_array($output) && is_array($output))
		{
			$idStr = "";
			foreach($output as $k=>$v)
				$idStr = $idStr.$v["PROFILEID"].",";
			$idStr = rtrim($idStr,",");

			if($idStr)
			{
				$profileArrayObj = new ProfileArray;
				$profileArray = $profileArrayObj->getResultsBasedOnJprofileFields(array("PROFILEID"=>$idStr),'','',"PROFILEID,USERNAME",'JPROFILE','newjs_slave');
				unset($profileArrayObj);

				foreach($profileArray as $k=>$v)
					$usernameArr[$v->getPROFILEID()] = $v->getUSERNAME();
				unset($profileArray);
			}
			foreach($output as $k=>$v)
			{
				$result = $this->getAgentAllotedData($v["ALLOTED_TO"],$v["PROFILEID"]);
				if($result && is_array($result))
				{
					$output[$k]["ALLOT_TIME"] = $result["ALLOT_TIME"];
					$output[$k]["DE_ALLOT_TIME"] = $result["DE_ALLOCATION_DT"]." 23:59:59";
				}
				$output[$k]["USERNAME"] = $usernameArr[$v["PROFILEID"]];
				unset($result);
			}
			unset($usernameArr);

			$incHisObj = new incentive_HISTORY('newjs_slave');
			$jj=0;
			foreach($output as $k=>$v)
			{
				if($v["ENTRY_DT"]<$v["ALLOT_TIME"] || $v["ENTRY_DT"]>$v["DE_ALLOT_TIME"])	continue;

				if($v["ALLOT_TIME"] && $v["DE_ALLOT_TIME"])
					$result = $incHisObj->getVisitDoneDataForExecs($this->execArray,$v["ALLOT_TIME"],$v["DE_ALLOT_TIME"],$v["PROFILEID"]);

				$uniqAgentProfilePair = array();
				if($result && is_array($result))
				{
					foreach($result as $kk=>$vv)
					{
						$flag=0;
						foreach($uniqAgentProfilePair as $entryby=>$profileid){
							if($entryby==$vv['ENTRYBY'] && $profileid==$vv["PROFILEID"] || $vv['ENTRYBY']!=$v['ALLOTED_TO'])
								$flag=1;
						}
						if($flag==1)	continue;
						$uniqAgentProfilePair[$vv["ENTRYBY"]] = $vv["PROFILEID"];
						$this->execDetailsArr[$jj]["USERNAME"] = $v["USERNAME"];
						$this->execDetailsArr[$jj]["PROFILEID"] = $v["PROFILEID"];
						$this->execDetailsArr[$jj]["ALLOCATION_DATE"] = date("j F Y",strtotime($v["ALLOT_TIME"]));
						$this->execDetailsArr[$jj]["FRESH_VISIT_DATE"] = date("j F Y",strtotime($vv["ENTRY_DT"]));
						$this->execDetailsArr[$jj]["PAYMENT_DATE"] = date("j F Y",strtotime($v["D"]));
						$this->execDetailsArr[$jj]["AMOUNT"] = $v["AMOUNT"];
						$this->execDetailsArr[$jj]["ALLOT_TT"] = $v["ALLOT_TIME"];
						$this->execDetailsArr[$jj]["PAYMENT_DD"] = $v["D"];
						$jj++;
					}
				}
				else
				{
					$this->execDetailsArr[$jj]["USERNAME"] = $v["USERNAME"];
					$this->execDetailsArr[$jj]["PROFILEID"] = $v["PROFILEID"];
					$this->execDetailsArr[$jj]["ALLOCATION_DATE"] = date("j F Y",strtotime($v["ALLOT_TIME"]));
					$this->execDetailsArr[$jj]["FRESH_VISIT_DATE"] = "";
					$this->execDetailsArr[$jj]["PAYMENT_DATE"] = date("j F Y",strtotime($v["D"]));
					$this->execDetailsArr[$jj]["AMOUNT"] = $v["AMOUNT"];
					$this->execDetailsArr[$jj]["ALLOT_TT"] = $v["ALLOT_TIME"];
					$this->execDetailsArr[$jj]["PAYMENT_DD"] = $v["D"];
					$jj++;
				}
				unset($result);
			}
			unset($incHisObj);
		}
	}

	//This function is called to convert the final output in the way it needs to be shown in xls
	public function generateDataForXLS($hierarchyData,$execWiseAndDayWiseSummation,$emp_id_arr)
	{
		$headerString = "Manager/Supervisor/Executive\tEmployee ID\t";
		foreach($this->resultArr as $k=>$v)
		{
			$headerString = $headerString.$v["DATE"]."\t";
		}
		$headerString = $headerString."Total\r\n";

		$dataString = "";
		if($hierarchyData && is_array($hierarchyData))
		{
			foreach($hierarchyData as $k=>$v)
			{
				$dataString = $dataString.$v["USERNAME"]."\t";
				$dataString = $dataString.$emp_id_arr[$v["USERNAME"]]."\t";
				foreach($this->resultArr as $kk=>$vv)
				{
					if($vv[$v["USERNAME"]]["FRESH_VISITS"])
						$dataString = $dataString.$vv[$v["USERNAME"]]["FRESH_VISITS"]."\t";
					else
						$dataString = $dataString."0\t";
				}
				if($execWiseAndDayWiseSummation["EXEC_WISE"][$v["USERNAME"]]["FRESH_VISITS"])
					$dataString = $dataString.$execWiseAndDayWiseSummation["EXEC_WISE"][$v["USERNAME"]]["FRESH_VISITS"]."\r\n";
				else
					$dataString = $dataString."0\r\n";
			}
		}
/*		$dataString = $dataString."Total\t";
		foreach($this->resultArr as $k=>$v)
		{
			if($execWiseAndDayWiseSummation["DAY_WISE"][$k]["FRESH_VISITS"])
				$dataString = $dataString.$execWiseAndDayWiseSummation["DAY_WISE"][$k]["FRESH_VISITS"]."\t";
			else
				$dataString = $dataString."0\t";
		}
		$dataString = $dataString.$execWiseAndDayWiseSummation["GRAND_TOTAL"]["FRESH_VISITS"]."\r\n";*/
		$output = $headerString.$dataString;
		return $output;
	}

	// Function to get background color per agent
	public function getBackgroundColor($reporters){
		foreach($reporters as $agent){
			$jsObj = new jsadmin_PSWRDS('newjs_slave');
			$priv = $jsObj->getPrivilegeForAgent($agent);
			$color = $this->getRowColour($priv);
			$res[$agent] =  $color;
		}
		return $res;
	}

	// Function to get actual Field Sales agents during the selection period
	public function getActualFieldSalesAgents(){
		$incManualAllot = new MANUAL_ALLOT('newjs_slave');
		$agents = $incManualAllot->getAllotedAgentsDetailsInDateRange($this->start_dt, $this->end_dt);
		$fsAgents = array();

		foreach($agents as $name=>$profArr){
			$tempProfiles = array();
			foreach($profArr as $id=>$params){
				if(!in_array($params['PROFILEID'], $tempProfiles)){
					if($params['CALL_SOURCE'] == 'FS'){
						$fsAgents[$name][$params['PROFILEID']] = $params['ALLOT_TIME'];
					}
				}
				$tempProfiles[] = $params['PROFILEID'];
			}
		}
		return $fsAgents;
	}

	// Function to get agent wise alloted profiles with their allot time and deallocation date in array format
	public function getAgentAllotedProfileArray($agentArray){
		$crmDailyAllotObj = new CRM_DAILY_ALLOT('newjs_slave');
		$agentAllotedProfileArray = array();
		foreach($agentArray as $key=>$value){
			$agentAllotedProfileArray[$value] = $crmDailyAllotObj->getAgentAllotedProfileArray($value, $this->start_dt, $this->end_dt);
		}
		return $agentAllotedProfileArray;
	}

	public function getAgentAllotedProfileArrayFromTrac($agentArray){
		$crmDailyAllotTracObj = new CRM_DAILY_ALLOT_TRACK('newjs_slave');
		$agentAllotedProfileArray = array();
		foreach($agentArray as $key=>$value){
			$agentAllotedProfileArray[$value] = $crmDailyAllotTracObj->getAgentAllotedProfileArray($value, $this->start_dt, $this->end_dt);
		}
		return $agentAllotedProfileArray;
	}

	public function unionCrmData($crm, $trac){
		$output = array_merge_recursive($crm, $trac);
		return $output;
	}

	// Function to filter out our data based on real Field Sales Agents
	public function filterActualData($profArr, $agentDetails){
		foreach($profArr as $exec=>$arr){
			foreach($arr as $key=>$val){
				if(!in_array($val['PROFILEID'], array_keys($agentDetails[$exec]))){
					unset($profArr[$exec][$key]);
				}
				if(strtotime($profArr[$exec][$key]['ALLOT_TIME']) != strtotime($agentDetails[$exec][$val['PROFILEID']])){
					unset($profArr[$exec][$key]);
				}
			}
			// Resetting Key Values
			$profArr[$exec] = array_values($profArr[$exec]);
		}
		return $profArr;
	}

	// Function to get the count of alloted profiles per agent
	public function getAgentAllotedProfileCount($profileArray){
		$tempCount = array();
		foreach($profileArray as $key=>$value){
			if(is_array($profileArray[$key])){
				$tempCount[$key] = count($profileArray[$key]);
			} else {
				$tempCount[$key] = 0;
			}
		}
		return $tempCount;
	}

	// Function to get list of Fresh Visits done per agent after allotment before deallocation period expires
	public function getAgentAllotedProfileFreshVisitArray($agentAllotedArray, $start_date, $end_date){
		$crmHistoryObj = new incentive_HISTORY('newjs_slave');
		$agentAllotedProfileFreshVisitArray = array();
		$profid = array();
		$outputArray = array();
		$end_date = $end_date." 23:59:59";
		foreach($agentAllotedArray as $key=>$value){
			$agentAllotedProfileFreshVisitArray[$key] = $crmHistoryObj->getAgentAllotedProfileFreshVisitArray($key, $value);
		}
		foreach($agentAllotedProfileFreshVisitArray as $exec=>&$arr){
			foreach($arr as $k=>&$v){
				if(in_array($v['PROFILEID'], $profid)){
					unset($agentAllotedProfileFreshVisitArray[$exec][$k]);
				}
				$profid[] = $v['PROFILEID'];
			}
		}
		foreach($agentAllotedArray as $agent=>$proArr){
			foreach($proArr as $key=>$value){
				foreach($agentAllotedProfileFreshVisitArray as $exec=>&$arr){
					foreach($arr as $k=>&$v){
						if($agent == $exec && $value['PROFILEID'] == $v['PROFILEID']){
							$temp = $agentAllotedProfileFreshVisitArray[$agent][$k];
							if(strtotime($v['ENTRY_DT'])<=(strtotime($value['ALLOT_TIME'])+2592000)){ // allot time + 30 days
                            	$outputArray[$agent][$key] = $temp;
                            }
							unset($temp);
						}
					}
				}
			}
		}
		return $outputArray;
	}

	// Function to get count of Fresh Visits done per agent
	public function getFreshVisitCount($freshVisitArray){
		$tempCount = array();
		foreach($freshVisitArray as $key=>$value){
			if(is_array($freshVisitArray[$key])){
				$tempCount[$key] = count($freshVisitArray[$key]);
			} else {
				$tempCount[$key] = 0;
			}
		}
		return $tempCount;
	}

	// Function to get number of Alloted Profiles that have Paid within the alloted period duration
	public function getAgentAllotedProfilePaidArray($allotedArray){
		$crmMonthlyIncEligObj = new incentive_MONTHLY_INCENTIVE_ELIGIBILITY('newjs_slave');
        	$purchasesObj = new BILLING_PURCHASES('newjs_slave');
		$agentAllotedProfilePaidArray = array();
		foreach($allotedArray as $key=>$value){
            foreach($value as $k => $v){
                $data = $crmMonthlyIncEligObj->getAgentAllotedProfilePaidArrayForProfileid($key, $v);
                $purchaseData = $purchasesObj->fetchAllDataForBillidArr(array_keys($data));
                $amount = 0;
                foreach($data as $dataKey => $dataVal){
                    $taxRate = $purchaseData[$dataKey]['TAX_RATE'];
                    $amount += ($dataVal['AMOUNT']*((100-$taxRate)/100));
                }
                unset($purchaseData);
                $agentAllotedProfilePaidTempArray[] = array('PROFILEID'=>$v['PROFILEID'], 'COUNT'=>count($data), 'AMOUNT'=>$amount, 'ENTRY_DT'=>$data[0]['ENTRY_DT']);
            }
			$agentAllotedProfilePaidArray[$key] = $agentAllotedProfilePaidTempArray;
            unset($agentAllotedProfilePaidTempArray);
		}
		foreach($agentAllotedProfilePaidArray as $exec=>&$arr){
			foreach($arr as $k=>&$v){
				if(in_array($v['PROFILEID'], $profid) || $v['COUNT'] == 0){
					unset($agentAllotedProfilePaidArray[$exec][$k]);
				}
				$profid[] = $v['PROFILEID'];
			}
		}
		foreach($allotedArray as $agent=>$proArr){
			foreach($proArr as $key=>$value){
				//print_r($agent." ".$key." ".$value['PROFILEID']);
				foreach($agentAllotedProfilePaidArray as $exec=>&$arr){
					foreach($arr as $k=>&$v){
						if($agent == $exec && $value['PROFILEID'] == $v['PROFILEID']){
							$temp = $agentAllotedProfilePaidArray[$agent][$k];
							unset($agentAllotedProfilePaidArray[$agent][$k]);
							$agentAllotedProfilePaidArray[$agent][$key] = $temp;
							unset($temp);
						}
					}
				}
			}
		}
		return $agentAllotedProfilePaidArray;
	}

	// Function to get the profiles which Paid for the Main Membership, excluding the VAS, etc
	public function getAgentAllotedMainMemPaidArray($allotedArray){
		$billPurchasesObj = new BILLING_PURCHASES();
		$agentAllotedMainMemPaidArray = array();
		foreach($allotedArray as $key=>$value){
			$agentAllotedMainMemPaidArray[$key] = $billPurchasesObj->getAgentAllotedMainMemPaidArray($value);
		}
		return $agentAllotedMainMemPaidArray;
	}  

	// Function to get paid profile count
	public function getPaidProfileCount($paidArray){
		$tempCount = array();
		foreach($paidArray as $key=>$value){
			if(is_array($value)){
				foreach($value as $k=>$v){
					$tempCount[$key]++;
				}
			} else {
				$tempCount[$key] = 0;
			}
		}
		return $tempCount;
	}

	// Function to calculate to the nearest integer the %age of Fresh Visited Profiles
	public function getFreshVisitPercentage($freshCount, $allotCount){
		$tempPercentage = array();
		foreach($allotCount as $key=>$value){
			if(array_key_exists($key, $freshCount) && $allotCount[$key]!=0){
				$tempPercentage[$key] = round((($freshCount[$key]/$allotCount[$key])*100),0);
			} else {
				$tempPercentage[$key] = 0;
			}
		}
		return $tempPercentage;
	}

	// Function to calculate to the nearest integer the %age of Visited/Paid
	public function getVisitPaidPercentage($freshCount, $paidCount){
		$tempPercentage = array();
		foreach($freshCount as $key=>$value){
			if(array_key_exists($key, $paidCount) && $freshCount[$key]!=0){
				$tempPercentage[$key] = round((($paidCount[$key]/$freshCount[$key])*100),0);
			} else {
				$tempPercentage[$key] = 0;
			}
		}
		return $tempPercentage;
	}

	// Function to calculate to the nearest decimal(single) the %age of Paid/Allocated
	public function getAllotedPaidPercentage($paidCount, $allotCount){
		$tempPercentage = array();
		foreach($allotCount as $key=>$value){
			if(array_key_exists($key, $paidCount) && $allotCount[$key]!=0){
				$tempPercentage[$key] = round((($paidCount[$key]/$allotCount[$key])*100),1);
			} else {
				$tempPercentage[$key] = 0;
			}
		}
		return $tempPercentage;
	}

	// Function to get Total Sales done by agent within allocation period and Field Sales
	public function getTotalSales($paidArray){
		$tempSum = array();
		foreach($paidArray as $key=>$value){
			if(is_array($value)){
				foreach($value as $kk=>$vv){
					$tempSum[$key] += $vv['AMOUNT'];
				}
			}
		}
		return $tempSum;
	}

	// Function to get Ticket Size
	public function getTicketSize($profileCount, $totalSales){
		$tempSize = array();
		foreach($profileCount as $key=>$value){
			if(array_key_exists($key, $totalSales) && $profileCount[$key]!=0){
				$tempSize[$key] = round(($totalSales[$key]/$profileCount[$key]),0);
			} else {
				$tempSize[$key] = 0;
			}
		}
		return $tempSize;
	}

	// Function to get totals as per PRD for count arrays !
	public function getResivedCount($countArray, $hierarchyArray){
		$revisedCountArray = $countArray;
		foreach($hierarchyArray as $key=>$value){
			if(is_array($value)){
				foreach($value as $kk=>$vv){
					if($vv != $key ){
						$revisedCountArray[$key] += $countArray[$vv];
					}
				}
			}
		}
		return $revisedCountArray;
	}

	//Function to get Hierarchy Array
	public function getHierarchyArray($allReporters){
		$tempArr = array();
		foreach($allReporters as $key=>$value){
			$hierarchyObj = new hierarchy($value);
			$reporters = $hierarchyObj->getAllReporters();
			unset($hierarchyObj);
			$tempArr[$value] = $reporters;
		}
		return $tempArr;
	}

	// Function to get profile usernames corresponding to their profile ID's
	public function getProfileUsernames($profileArray){
		$profileUsername = array();
		foreach($profileArray as $key=>$value){
			if(is_array($value)){
				$jprofileObj = new JPROFILE();
				foreach($value as $kk=>$vv){
					if(!array_key_exists($vv['PROFILEID'], $profileUsername)){
						$profileUsername[$vv['PROFILEID']] = $jprofileObj->getUsername($vv['PROFILEID']);
					}
				}

			}
		}
		return $profileUsername;
	}

	//Function to generate XLS Data for Efficiency
	public function generateDataForXLSEfficiency($agents, $alloted, $fresh, $freshPerc, $paid, $vpaidPerc, $vallotPerc, $sales, $ticketSize){
		$headerString = "Executive\tNumber of Allocations\tNumber of Fresh Visits\tFresh Visit %\tNumber of Profiles that Paid\tVisit Paid Conversion %\tAllocation Paid Conversion %\tSales\tTicket Size\r\n";

		$dataString = "";
		if($agents && is_array($agents))
		{
			foreach($agents as $k=>$v)
			{
				if(!empty($alloted[$v]) && $alloted[$v] !=0){
					$dataString = $dataString.$v."\t";
					$dataString = $dataString.$alloted[$v]."\t";
					$dataString = $dataString.$fresh[$v]."\t";
					$dataString = $dataString.$freshPerc[$v]."\t";
					if($paid[$v]){
						$dataString = $dataString.$paid[$v]."\t";
					}else{
						$dataString = $dataString."0 \t";
					}
					$dataString = $dataString.$vpaidPerc[$v]."\t";
					$dataString = $dataString.$vallotPerc[$v]."\t";
					if($sales[$v]){
						$dataString = $dataString.$sales[$v]."\t";
					}else{
						$dataString = $dataString."0 \t";
					}
					if($ticketSize[$v]){
						$dataString = $dataString.$ticketSize[$v]."\r\n";
					}else{
						$dataString = $dataString."0 \r\n";
					}
				}
			}
		}

		$output = $headerString.$dataString;
		return $output;
	}
	//Function to generate team wise data
	public function generateTeamWiseData($ddarr, $allReporters){
		foreach($ddarr as $dd=>$val){
			foreach($allReporters as $agent){
				$h_obj = new hierarchy($agent);
				$h = $h_obj->getAllReporters();
				unset($h[0]);

				if($h && is_array($h)){
					foreach($h as $rep){
						if($this->resultArr[$dd][$rep]['FRESH_VISITS'])
							$this->resultArr[$dd][$agent]['FRESH_VISITS'] += $this->resultArr[$dd][$rep]['FRESH_VISITS'];
						if($this->resultArr[$dd][$rep]['PROFILES_COUNT_WHO_PAID'])
							$this->resultArr[$dd][$agent]['PROFILES_COUNT_WHO_PAID'] += $this->resultArr[$dd][$rep]['PROFILES_COUNT_WHO_PAID'];
						if($this->resultArr[$dd][$rep]['SALES'])
							$this->resultArr[$dd][$agent]['SALES'] += $this->resultArr[$dd][$rep]['SALES'];
					}					
				}
				unset($h_obj);			
			}
		}
	}
	public function getRowColour($priv)
	{
		$color = "White";
		$priv = explode("+", $priv);
		if(in_array("ExcFld",$priv))
			$color = "#E0E0E0";
		if(in_array("SupFld",$priv))
			$color = "#DCDCDC";
		if(in_array("MgrFld",$priv))
			$color = "#FFEFD5";
		if(in_array("SLHDO",$priv))
			$color = "#FFDAB9";
		return $color;
	}

	public function sortAgentsAccordingToHierarchy($referenceArr, $targetArr){
		$newArr = array();
		foreach($referenceArr as $key=>$agent){
			if(in_array($agent['USERNAME'], $targetArr)){
				$newArr[] = $agent['USERNAME'];
			}
		}
		return $newArr;
	}

	public function getEligibleExecutives($reporters, $start_date, $end_date)
	{
		// 30 days prior of start date
		$start_date = date('Y-m-d 00:00:00', strtotime('-29 day', strtotime($start_date)));
		$end_date = $end_date." 23:59:59";
		$maObj = new MANUAL_ALLOT('newjs_slave');
		$allocation = $maObj->getDistinctFieldSalesAgents($start_date, $end_date);
		
		// visibility check
		$fsObj = new FieldSalesFollowUpStatusMis();
		$reportersOfAgent = $fsObj->fetchReportersOfAgent($reporters);
		$vis = $fsObj->visibiltyCheck($reportersOfAgent, $allocation);

		$res = array();
		foreach($vis as $k=>$val){
			if($val==1)
				$res[] = $k;
		}
		return $res;
	}
     
    public function sort_execDetailsArr(&$execDetailsArr)
    {
            foreach($execDetailsArr as $k=>$v){
                    $allocation_time[$k] = $v['ALLOT_TT'];
                    $username[$k] = $v['USERNAME'];
                    $payment_date[$k] = $v['PAYMENT_DD'];
            }
            array_multisort($allocation_time,SORT_ASC,$username,SORT_ASC,$payment_date,SORT_ASC,$execDetailsArr);
    }
    public function getAgentAllotedData($agentName, $profileId)
    {
	$incCDAObj = new CRM_DAILY_ALLOT('newjs_slave');
	$res = $incCDAObj->getAllocationDates($agentName,$profileId);
	if(!$res)
	{
		$incCDATObj = new CRM_DAILY_ALLOT_TRACK('newjs_slave');
		$res = $incCDATObj->getAllocationDates($agentName,$profileId);
	}
	return $res;	
    }
    public function getExecEmployeeID($allReporters)
    {
    	$pswrdsObj = new jsadmin_PSWRDS('newjs_slave');
    	$emp_id_arr = $pswrdsObj->fetchAllUsernamesAndEmpID($allReporters);
    	return $emp_id_arr;
    }

}
?>
