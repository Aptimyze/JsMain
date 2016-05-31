<?php
/***************************
@author: lakshay
@developed Date : 08-11-2013
****************************/

class misGenerationhandler
{
	public function fetchProfiles($processObj)
	{
		$processName=$processObj->getProcessName();
		if($processName=="FTA_EFFICIENCY_MIS")
		{
			$dialerDetailsObj=new incentive_FTA_DATA();
			$time=date("Y-m-d H:i:s",time()-2*60*60);
			$profiles=$dialerDetailsObj->getProfilesCalledAfter($time);
			/*
			$time1="2014-02-17 00:00:00";
			$time2="2014-02-17 23:59:59";
                        $profiles=$dialerDetailsObj->getProfilesCalledBetween($time1,$time2);
			*/
		}
		elseif($processName=='CRM_HANDLED_REVENUE')
		{
			$method 		=$processObj->getMethod();
			$paymentDetailsObj      =new BILLING_PAYMENT_DETAIL('newjs_slave');
			if($method=='NEW_PROFILES'){
				$monthlyIncentiveObj 	=new incentive_MONTHLY_INCENTIVE_ELIGIBILITY('newjs_slave');	
				$receiptId 		=$monthlyIncentiveObj->getMaxReceiptId();
				$profiles 		=$paymentDetailsObj->getPaidProfiles($receiptId);
			}
			elseif($method=='MANUAL_ALLOT'){
				$lastHandledDtObj	=new incentive_LAST_HANDLED_DATE('newjs_slave');				
				$manualAllotObj		=new MANUAL_ALLOT('newjs_slave');
				$id                     =$processObj->getIdAllot();
				$lastManualEntryDt	=$lastHandledDtObj->getHandledDate($id);
				$profilesArr 		=$manualAllotObj->getManualAllotedProfiles($lastManualEntryDt);
				if(count($profilesArr)>0){
					$entryDate =date("Y-m-d 00:00:00",JSstrToTime("$todayDate -30 days"));
					foreach($profilesArr as $key=>$dataArr){
						$manualEntryDt =$dataArr['ENTRY_DT'];	
						$detailsArr =$paymentDetailsObj->getPaymentDetails($dataArr['PROFILEID'],$entryDate);
						foreach($detailsArr as $key1=>$details) 
							$profiles[] =$details;
					}
					if($manualEntryDt)
						$processObj->setStartDate($manualEntryDt);
				}
			}
		}
		return $profiles;
	}
	public function filterProfiles($profiles,$processObj)
	{
		if(count($profiles)==0)
			return;
		$processName=$processObj->getProcessName(); 
		if($processName=='CRM_HANDLED_REVENUE')
		{
			$method		  =$processObj->getMethod();
			$manualEntryDt 	  =$processObj->getStartDate();	
			$crmDailyAllotObj =new CRM_DAILY_ALLOT('newjs_slave');
			foreach($profiles as $key=>$dataArr)
			{
				$profileid	=$dataArr['PROFILEID'];
			
				// filter for Allocation Validity for Payment
                			$allocationVaildity =$crmDailyAllotObj->getValidAllocationForPayment($profileid,$dataArr['ENTRY_DT']);	
                			if(!$allocationVaildity)
                				continue;

				// filter for Profile validity for Payment
                			$validProfile =$this->checkProfileValidityForPayment($profileid,$allocationVaildity['ALLOTED_TO'],$allocationVaildity['ALLOT_TIME'],$allocationVaildity['DE_ALLOCATION_DT'],$dataArr['BILLID'],$method, $manualEntryDt,$allocationVaildity['REAL_DE_ALLOCATION_DT']);

                			if($validProfile){
                				$dataArr['ALLOTED_TO']          =$allocationVaildity['ALLOTED_TO'];
                				$dataArr['ALLOT_TIME']          =$allocationVaildity['ALLOT_TIME'];
                				$dataArr['DE_ALLOCATION_DT']    =$allocationVaildity['DE_ALLOCATION_DT'];
                				$filteredProfiles[] 		=$dataArr;	
                			}
                		}
                		return $filteredProfiles;	
                	}
                }
                public function saveProfiles($profiles,$processObj)
                {
                	$processName=$processObj->getProcessName();
                	if($processName=="FTA_EFFICIENCY_MIS")
                	{
                		$fieldType=misFields::$ftaRegular;
                		$ftaMisObj=new mis_FTA_EFFICIENCY();
                		$profilesCount=count($profiles);
                		for($x=0;$x<$profilesCount;$x++)
                		{
                			$profileid=$profiles[$x]['PROFILEID'];
                			$agent=$profiles[$x]['EXECUTIVE'];
                			for($y=0;$y<count($fieldType);$y++)
                			{	
                				if($fieldType[$y]=="CALLED_DATE")
                					$calledTime=$profiles[$x]['CALLED_DATE'];
                				else
                					$calledTime="";
                				$ftaMisObj->insertProfile($profileid,$fieldType[$y],$agent,$calledTime);	
                			}
                		}
                	}
                	elseif($processName=="CRM_HANDLED_REVENUE")
                	{
                		$method			=$processObj->getMethod();
                		$manualEntryDt 		=$processObj->getStartDate();
                		$lastHandledDtObj       =new incentive_LAST_HANDLED_DATE();
                		$monthlyIncentiveObj    =new incentive_MONTHLY_INCENTIVE_ELIGIBILITY();
                		$pswrdsObj		=new jsadmin_PSWRDS('newjs_slave'); 
                		$profilesCount 		=count($profiles);
                		for($i=0; $i<$profilesCount; $i++)
                		{
                			$dataArr 	=$profiles[$i];
                			$center		=$pswrdsObj->getCenter($dataArr['ALLOTED_TO']);

                                // Store data in table
                			$monthlyIncentiveObj->insertProfile($dataArr['RECEIPTID'],$dataArr['BILLID'],$dataArr['PROFILEID'],$dataArr['ALLOTED_TO'],$center,$dataArr['AMOUNT'],$dataArr['ENTRY_DT'],$dataArr['MODE'],$dataArr['ALLOT_TIME'],$dataArr['APPLE_COMMISSION']);
                		}
                		if($method=='MANUAL_ALLOT' && $manualEntryDt){
                			$id =$processObj->getIdAllot();
                			$lastHandledDtObj->setHandledDate($id, $manualEntryDt);
                		}			
                	}
                }
                public function updateProfiles()
                {
                	$time=date("Y-m-d H:i:s",time()-60*60);
                	$ftaEfficiencyObj=new MIS_FTA_EFFICIENCY();
                	$messageLogObj1=new NEWJS_MESSAGE_LOG("shard1_master");	
                	$messageLogObj2=new NEWJS_MESSAGE_LOG("shard2_master");
                	$messageLogObj3=new NEWJS_MESSAGE_LOG("shard3_master");
                	$shards=array(0=>$messageLogObj1,1=>$messageLogObj2,2=>$messageLogObj3);
                	$lastIdObj = new incentive_FTA_MESSAGE_LOG_LAST_ID();
                	$lastId_arr = $lastIdObj->getAllIds();	
		//profiles which have sent eoi in last hour
                	foreach($shards as $key=>$shard)
                	{
                		unset($profiles);
                		unset($profilesCount);
                		$maxId=$shard->getMaxId();
                		$lastIdObj->setId($key,$maxId);
                		$profiles=$shard->getProfilesSentEoiAfter($lastId_arr[$key],$maxId);
                		$profilesCount=count($profiles);
                		for($a=0;$a<$profilesCount;$a++)
                		{
                			$whichShard = $profiles[$a]['PROFILEID']%3;
                			$profileShard = $shards[$whichShard];
                			if($shard==$profileShard)
                			{
                				$hasSent=$profileShard->hasSentEoiBefore($lastId_arr[$key],$profiles[$a]['PROFILEID']);
                				if(!$hasSent)
                					$filteredProfiles[$profiles[$a]['PROFILEID']]["EOI_DATE"]=$profiles[$a]["DATE"];
                			}
                		}
                	}


		//profiles which have screened photo first time in last hour
                	unset($profilesPhotoScreened);
                	$photoFirstObj=new PHOTO_FIRST();
                	$profilesPhotoScreened=$photoFirstObj->getProfilesScreenedAfter($time);
                	$profilesPhotoScreenedCount=count($profilesPhotoScreened);
                	for($b=0;$b<$profilesPhotoScreenedCount;$b++)
                	{
                		$profileid=$profilesPhotoScreened[$b]['PROFILEID'];
                		$valueDate=$profilesPhotoScreened[$b]['ENTRY_DT'];
                		$filteredProfiles[$profilesPhotoScreened[$b]["PROFILEID"]]["PHOTO_DATE"]=$profilesPhotoScreened[$b]["ENTRY_DT"];
                	}

		//profiles which paid in last hour
                	unset($profiles);
                	$purchasesObj=new billing_PURCHASES();
                	$profiles=$purchasesObj->getProfilesPaidAfter($time);
                	$profilesCount=count($profiles);
                	for($c=0;$c<$profilesCount;$c++)
                	{
                		$isPaid=$purchasesObj->isPaidBefore($profiles[$c]['PROFILEID'],$time);
                		if(!$isPaid)
                			$filteredProfiles[$profiles[$c]["PROFILEID"]]["PAID_DATE"]=$profiles[$c]["ENTRY_DT"];
                	}
                	if(is_array($filteredProfiles))
                	{
                		foreach($filteredProfiles as $pid=>$fields)
                		{
                			foreach($fields as $field=>$value)
                			{	
                				if($field=="EOI_DATE"||$field=="PHOTO_DATE")
                					$entry_dt=date('Y-m-d H:i:s',time()-3*24*60*60);
                				else if($field=="PAID_DATE")
                					$entry_dt=date('Y-m-d H:i:s',time()-30*24*60*60);	
                				$ftaEfficiencyObj->updateProfilesParameters($pid,$field,$value,$entry_dt);
                			}
                		}	
                	}
                }
                public function fetchMisData($agents,$processObj,$range)
                {
                	$processName=$processObj->getProcessName();		
                	if($processName=="FTA_REGULAR")
                	{
                		if(is_array($agents))
                		{
                			$ftaMisObj=new mis_FTA_EFFICIENCY();
                			$fieldType=misFields::$ftaRegular;
                			foreach($agents as $agent)
                			{
                				foreach($fieldType as $type)
                				{
                					$count=$ftaMisObj->getCount($agent,$type,$range);
                					$allCounts[$agent][$type]=$count;
                				}
                			}
                		}
                		return $allCounts;
                	}
                }
                public function checkProfileValidityForPayment($profileid, $allotedTo, $allotTime, $deAllocationDt, $billId, $method, $manualEntryDt='',$realDeAllocationDt) 
                {
                	$deAllocationTrackObj 	=new incentive_DEALLOCATION_TRACK('newjs_slave');
                	$purchaseObj		=new BILLING_PURCHASES('newjs_slave');
                	$crmDailyAllotObj	=new CRM_DAILY_ALLOT('newjs_slave');
                	$manualAllotObj		=new MANUAL_ALLOT('newjs_slave');
                	$historyObj		=new incentive_HISTORY('newjs_slave');

			// 1. filter to check Actual De-allocation done
                	if($method=='MANUAL_ALLOT'){
                		$manualAllot =1;
                		$allotTime   =$manualEntryDt;	
                		
                		$lastDeAllocationId 	=$deAllocationTrackObj->getLastDeAllocationId($profileid,$allotedTo);
                		if($lastDeAllocationId)
                			$validDeAllocation =$deAllocationTrackObj->checkValidDeAllocation($lastDeAllocationId, $allotTime, $deAllocationDt,$manualAllot);
			}
			else{
				$realDeAllocationDtArr 	=@explode(" ",$realDeAllocationDt);	
				$realDeAllocationDtOnly =$realDeAllocationDtArr[0];
                                if(($realDeAllocationDtOnly<=$deAllocationDt) && ($realDeAllocationDt>$allotTime))
                                        $validDeAllocation =1;
			}

		if($validDeAllocation && !$manualAllot)		// Not Manual Allot case
		return;	
		if($lastDeAllocationId && !$validDeAllocation && $manualAllot)		// Manual Allot case
		return;

		$purchaseDetails 	=$purchaseObj->getPurchaseDetails($billId);	
		$purchaseDtTime   	=JSstrToTime($purchaseDetails['ENTRY_DT']);
		$purchaseDate     	=date("Y-m-d",$purchaseDtTime);
		$checkingDate   	=date("Y-m-d",$purchaseDtTime-86400);
		$checkingTime  		=JSstrToTime($checkingDate);

		// 2. filter for JS Premium/JS Premium Outsourced 
		$jsPremium =$this->jsPremiumFilter($purchaseDetails['SERVICEID'], $allotedTo);
		if(!$jsPremium)
			return;	

		// 3. filter to check last Allocation Details 
		$lastAllocationDetails  =$crmDailyAllotObj->getLastAllocationDetails($profileid);
		$allotTimeNew 		=JSstrToTime($lastAllocationDetails['ALLOT_TIME']);
		$allotDateNew  		=date("Y-m-d",$allotTimeNew);
		$allotTimeNew		=JSstrToTime($allotDateNew);
		if($allotedTo && ($allotedTo !=$lastAllocationDetails['ALLOTED_TO']))
			return;

		// filter to check Current Allocation is Not Manual Allocation
		$isManualAllocation =$manualAllotObj->checkCurrentManualAllocation($profileid,$allotedTo);	

		/* filter to check case:1. Billing Date and Allocation date are not same
					2. Difference between (billing date - 1) and allocation date is more than 15 days
		*/
					$diffDays =round((($checkingTime-$allotTimeNew)/86400)+1);
		//echo "checkingDate=".$checkingDate."|"."allotDate=".$allotDateNew."|"."diffDays=".$diffDays;
		if($allotDateNew==$purchaseDate || $diffDays<15){
			if($isManualAllocation)
				return true;
			$dispositionCheck =$historyObj->checkDispositionStatus($profileid, $allotedTo, $lastAllocationDetails['ALLOT_TIME'], $purchaseDetails['ENTRY_DT']);
			if($dispositionCheck)
				return true;
			return false;
		}
		if($isManualAllocation)
			return true;	
		$dispositionExist =$historyObj->checkLast15DaysDisposition($profileid, $allotedTo, $purchaseDate);	
		if($dispositionExist)
			return true;
		return;
	}

	// JS premium / JS premium Outsourced filter
	public function jsPremiumFilter($serviceId, $allotedTo)
	{
		$jsadPswrdsObj          =new jsadmin_PSWRDS('newjs_slave');
		$privilegeStr           =$jsadPswrdsObj->getPrivilegeForAgent($allotedTo);
		$privilages             =explode("+",$privilegeStr);
		if(in_array("ExcPrm",$privilages) || in_array("ExPrmO",$privilages))
		{
			if(in_array("ExPrmO",$privilages)){
				if(!strstr($serviceId,'X'))
					return;
			}
			else{
				if(!strstr($serviceId,'ES')&&!strstr($serviceId,'X')&&!strstr($serviceId,'NCP'))
					return;
			}
		}
		return true;
	}
	public function handleMonthlyIncentivePool($processObj)
	{
		$deAllocationTrackObj	=new incentive_DEALLOCATION_TRACK('newjs_slave');
		$lastHandledDtObj       =new incentive_LAST_HANDLED_DATE('newjs_slave');
		$lastHandledDtSetObj    =new incentive_LAST_HANDLED_DATE();
		$monthlyIncentiveObj    =new incentive_MONTHLY_INCENTIVE_ELIGIBILITY();		

		// filter Manual Released profiles
		//$startDate 		=date("Y-m")."-01 00:00:00";	// within 1 month records checked
					$todayDate		=date("Y-m-d");
		$startDate   		=date("Y-m-d 00:00:00",JSstrToTime("$todayDate -30 days")); // last 31 days records checked
		$id 			=$processObj->getIdAllot();
		$lastManualReleasedDt   =$lastHandledDtObj->getHandledDate($id);
		$releasedProfiles 	=$deAllocationTrackObj->getManualReleasedProfiles($lastManualReleasedDt);
		if(count($releasedProfiles)>0){
			foreach($releasedProfiles as $key=>$dataArr){
				$deAllocationDt =$dataArr['DEALLOCATION_DT'];
				$monthlyIncentiveObj->deleteRecord($dataArr['PROFILEID'], $dataArr['ALLOTED_TO'], $startDate);
			}
		}
		if($deAllocationDt)
			$lastHandledDtSetObj->setHandledDate($id, $deAllocationDt);			

		// filter Payment Refund/Cancel Status profiles
		$paymentDetailsObj      =new BILLING_PAYMENT_DETAIL('newjs_slave');
		$billDetails 		=$paymentDetailsObj->getLast30DaysCancelledBill();
		if(count($billDetails)>0){
			foreach($billDetails as $key=>$dataArr)
				$monthlyIncentiveObj->deleteCancelledPayment($dataArr['RECEIPTID'],$dataArr['BILLID']);	
		}
	}
	public function isPrivilege_P_MG($username)
	{
		$jsadminPswrdsObj = new jsadmin_PSWRDS();
		$priv = $jsadminPswrdsObj->getPrivilegeForAgent($username);
		$priv = explode("+", $priv);
		if(in_array("P",$priv) || in_array("MG",$priv))
			return true;
		return false;
	}
        public function isValid_locationwise($user)
        {
                $priv = explode("+", $user['PRIVILAGE']);
                if(($user['ACTIVE']=='Y') && (in_array("ExcSl",$priv) || in_array("SLMNTR",$priv) || in_array("SLSUP",$priv)))
                        return true;
                return false;
        }
	public function isPrivilege_P_MG_TRNG($username)
	{
		$jsadminPswrdsObj = new jsadmin_PSWRDS();
		$priv = $jsadminPswrdsObj->getPrivilegeForAgent($username);
		$priv = explode("+", $priv);
		if(in_array("P",$priv) || in_array("MG",$priv) || in_array("TRNG",$priv))
			return true;
		return false;
	}
	public function isValid($user)
	{
		$priv = explode("+", $user['PRIVILAGE']);
		if(($user['ACTIVE']=='Y') && (in_array("ExcSl",$priv) || in_array("SLMNTR",$priv) || in_array("SLSUP",$priv)))
			return true;
		return false;
	}
        public function isValid_teamwise($user)
        {
                $priv = explode("+", $user['PRIVILAGE']);
                if(($user['ACTIVE']=='Y') && (in_array('ExcSl',$priv) || in_array('SLMNTR',$priv) || in_array('SLSUP',$priv) || in_array('SLMGR',$priv) || in_array('SLSMGR',$priv) || in_array('SLHD',$priv) || in_array('SLHDO',$priv)))
                        return true;
                return false;
        }
	public function net_off_tax_calculation($amount,$date='')
	{
		if($date)
		{
			$dt=explode(' ',$date);
			list($y,$m,$d)=explode('-',$dt[0]);
			$ts_dt=mktime(0, 0, 0,$m,$d,$y);
		}
		else
			$ts_dt=time();
		$ts=mktime(0,0,0,'4','1','2009');
		$ts_new = mktime(0,0,0,'4','1','2012');
		$ts_new = mktime(0,0,0,'4','1','2012');
		$ts_new2 = mktime(0,0,0,'6','1','2015');
                $ts_new3 = mktime(0,0,0,'11','15','2015');
                if($ts_new3<=$ts_dt)
                {
                        $net_off_tax_rate =billingVariables::NET_OFF_TAX_RATE;
                }
                elseif($ts_new2<=$ts_dt)
                {
                        //$net_off_tax_rate =billingVariables::NET_OFF_TAX_RATE;
                        $net_off_tax_rate =0.12281;
                }
		elseif($ts>$ts_dt || $ts_new<=$ts_dt)
		{
			$net_off_tax_rate = 0.11;
			//$percent='11';
		}
		else
		{
			$net_off_tax_rate = 0.09;
			//$percent='9';
		}
		$net_off_tax_amount = round($amount - (($net_off_tax_rate) * $amount),1);
		return round($net_off_tax_amount);
	}
	public function is_leap_yr($year)
	{
		return(($year%4==0) && ($year%100!==0) || ($year%400==0));
	}
	public function calculateTargetAchievement($sales_without_tax, $final_target, $selected_month, $selected_year, $fortnight)
	{
		if($final_target==0 || $final_target=='') return array("NA","black");
		$targetAchievement = $sales_without_tax/$final_target*100;
		$currDate = date('d');
		$currMonth = date('M');
		$currYear = date('Y');
		$N = crmParams::$monthDays[$selected_month];
        if($fortnight == 1)
            $N = 15;
        else{
            if(crmParams::$monthDays[$selected_month] == 28)
                $N = 13;
            else if(crmParams::$monthDays[$selected_month] == 30)
                $N = 15;
            else
                $N = 16;
	    $dayArr =array("16"=>"1","17"=>"2","18"=>"3","19"=>"4","20"=>"5","21"=>"6","22"=>"7","23"=>"8","24"=>"9","25"=>"10","26"=>"11","27"=>"12","28"=>"13","29"=>"14","30"=>"15","31"=>"16");
	    $currDate =$dayArr[$currDate];

        }
		if($this->is_leap_yr($selected_year) && $selected_month=="Feb" && $fortnight == 2)
			$N = 29;
		$monthNum = crmParams::$monthOrder[$selected_month];
		$selectedSeq = $selected_year*100 + $monthNum; 
		$monthNum = crmParams::$monthOrder[$currMonth];
		$currSeq = $currYear*100 + $monthNum;
		$X=0;
		if($selectedSeq < $currSeq) $X=100;
		elseif($selectedSeq==$currSeq && $N!=0){
			$X=$currDate/$N*100;
		}

		if($targetAchievement<(95*$X/100)) $color="red";
		else if($targetAchievement>=(95*$X/100) && $targetAchievement<$X) $color="orange";
		else if($targetAchievement>=$X) $color="green";
		$targetAchievement = round($targetAchievement)."%";
		return array($targetAchievement, $color);
	}
	public function getRowColour($priv)
	{
		$color = "White";
		$priv = explode("+", $priv);
		if(in_array("ExcSl",$priv))
			$color = "#F7F7F7";
		if(in_array("SLMNTR",$priv))
			$color = "#F0F0F0";
		if(in_array("SLSUP",$priv))
			$color = "#E8E8E8";
		if(in_array("SLMGR",$priv))
			$color = "#E0E0E0";
		if(in_array("SLSMGR",$priv))
			$color = "#DCDCDC";
		if(in_array("SLHD",$priv))
			$color = "#FFEFD5";
		if(in_array("SLHDO",$priv))
			$color = "#FFDAB9";
		return $color;
	}
        public function sort_locationwise(&$location)
        {
                $keys = array_keys($location);
                foreach($location as $key=>$value){
                        $sales[$key] = $value['TOTAL_SALES'];
                        $target[$key] = $value['TOTAL_TARGET'];
                }
                array_multisort($sales,SORT_DESC,$target,SORT_DESC,$keys,SORT_ASC,$location);
                foreach($location as $key=>$value)
                        natcasesort($location[$key]['USERNAME']);
        }
       // Function to retrieve background color for a set of agents(i.e $reporters)
	public function getBackgroundColor($reporters){
		foreach($reporters as $agent){
			$jsObj = new jsadmin_PSWRDS();
			$priv = $jsObj->getPrivilegeForAgent($agent);
			$color = $this->getRowColour($priv);
			$res[$agent] =  $color;
		}
		return $res;
	}

	public function get_SLHDO(){
		$jsObj = new jsadmin_PSWRDS();
		$res = $jsObj->get_name_priv();

		$slhdo = array();
		foreach($res as $k=>$v){
			$priv = explode('+', $v);
			if(in_array('SLHDO',$priv))
				$slhdo[] = $k;
		}
		if(count($slhdo)==1)	return $slhdo[0];
	}
	// function to get MIS Mainpage Details 
        public function fetchMainPageDetails($public='')
	{
		$misMainPageObj         =new MIS_MAINPAGE();
		$mainPageDetails        =$misMainPageObj->getMainPageDetails($public);
		return $mainPageDetails;
        }
        // function to get MIS Mainpage links details
        public function fetchMainPageLinkDetails($privilegeArr, $mainPageDetailsArr, $cid, $agentName){

                $i              =0;
                $siteUrl        =sfConfig::get("app_site_url");
                $array1         =array('$cid','$misname','$user');
                $array2         =array("$cid","$agentName","$agentName");
		$privilegeArr	=array_unique($privilegeArr);
		$arraySet	=array();

                foreach($privilegeArr as $key=>$val){
                        foreach($mainPageDetailsArr as $key1=>$val1){
                                $privilegeStr =$val1['PRIVILEGE'];
				$linkName     =trim($val1['NAME']);		
                                $privilegeArr =@explode("+", $privilegeStr);
                                if(in_array("$val", $privilegeArr) && (!in_array("$linkName", $arraySet))){
					$arraySet[]		    =$linkName;	
                                        $linkArr[$i]['NAME']        =$linkName;
                                        $mainUrl                    =$val1['MAIN_URL'];
                                        $jumpUrl                    =$val1['JUMP_URL'];
                                        $linkArr[$i]['MAIN_URL']    =$siteUrl.str_replace($array1,$array2,$mainUrl);
                                        if($jumpUrl)
                                                $linkArr[$i]['JUMP_URL']    =$siteUrl.str_replace($array1,$array2,$jumpUrl);
                                        $i++;
                                }
                                unset($privilegeArr);
                        }
                }
                return $linkArr;
        }
        
    public function salesProcessWiseTracking()
    {
        //Basic condition to record the sale: Agent should have privilage EXcSl
        $jsAdminPswrdsObj=new jsadmin_PSWRDS("newjs_slave");
        $agentsPriv = $jsAdminPswrdsObj->getPrivilegesForSalesTarget();
        unset($jsAdminPswrdsObj);
        //Get sale within last month from incentive.MONTHLY_INCENTIVE_ELIGIBILITY
        //****Comment from here after 2nd March,2016****
        /*
        $stDate = new DateTime("first day of last month");
        $endDate = new DateTime("last day of last month");
        $stDate = $stDate->format('Y-m-d')." 00:00:00";
        $endDate = $endDate->format('Y-m-d')." 23:59:59";
         */
        //****Comment till here*****
        
        ////JSC-1193 resheduling cron
        //One time Handling
        /*
        $curDate = date('Y-m-d');
        $stDate = "2016-03-01 00:00:00";
        $endDate = date('Y-m-d', strtotime('-1 day',  strtotime($curDate)))." 23:59:59";
        */
        
        //After live
        
        $curDate = date('Y-m-d');
        $stDate = date('Y-m-d', strtotime('-1 day',  strtotime($curDate)))." 00:00:00";
        $endDate = date('Y-m-d', strtotime('-1 day',  strtotime($curDate)))." 23:59:59";
        
        $incetiveMonthlyIncentiveElgObj = new incentive_MONTHLY_INCENTIVE_ELIGIBILITY("newjs_slave");
        $sales = $incetiveMonthlyIncentiveElgObj->getSalesWithinDates($stDate, $endDate);
        foreach($sales as $billId => $val)
        {
            $incentiveSaleBillIdsArr[] = $billId;
            $saleDetails["AGENT"] = $val['ALLOTED_TO'];
            $amountWithTax = ($val['AMOUNT'] - $val['APPLE_COMMISSION'])*((100-$val['SPLIT_SHARE'])/100);
            $netOfTaxFactor = 100/(100+$val['TAX_RATE']);
            $saleDetails["AMOUNT"] = $amountWithTax*($netOfTaxFactor);
            $saleDetails["DATE"] = date('Y-m-d', strtotime($val["ENTRY_DT"]));
            //Add the sale amount to its respective process
            $processWiseSale = $this->addAmountToProcess($saleDetails,$agentsPriv[$saleDetails["AGENT"]],$processWiseSale);
            //If the case of SPLIT_AGENT, add to the process of split agent
            if($val['SPLIT_AGENT']){
                $saleDetails["AGENT"] = $val['SPLIT_AGENT'];
                $amountWithTax = ($val['AMOUNT'] - $val['APPLE_COMMISSION'])*($val['SPLIT_SHARE']/100);
                $saleDetails["AMOUNT"] = $amountWithTax*($netOfTaxFactor);
                $processWiseSale = $this->addAmountToProcess($saleDetails,$agentsPriv[$saleDetails['AGENT']],$processWiseSale);
            }
        }
        //Get all the sales done within last month
        $paymentDetailsObj = new BILLING_PAYMENT_DETAIL("newjs_slave");
        $paymentDetails = $paymentDetailsObj->getProfilesWithinDateRangeWithTaxRate($stDate, $endDate);
        //Formating data on the basis of BILLID
        foreach($paymentDetails as $key => $val)
        {
            $paymentDetailsBillIdArr[] = $val['BILLID']; //Add to array to get diff from the entries in incentive.MONTHLY_INCENTIVE_ELIGIBILITY
            if($val['TYPE'] == "RS"){
                $paymentArr[$val['BILLID']]['AMOUNT'] = $val['AMOUNT'];
                $paymentArr[$val['BILLID']]['APPLE_COMMISSION'] = $val['APPLE_COMMISSION'];
            }
            else{
                $paymentArr[$val['BILLID']]['AMOUNT'] = $val['AMOUNT']*$val['DOL_CONV_RATE'];
                $paymentArr[$val['BILLID']]['APPLE_COMMISSION'] = $val['APPLE_COMMISSION']*$val['DOL_CONV_RATE'];
            }
            $paymentArr[$val['BILLID']]['ENTRY_DT'] = date('Y-m-d', strtotime($val["ENTRY_DT"]));
            $paymentArr[$val['BILLID']]['TAX_RATE'] = $val['TAX_RATE'];
        }
        if($paymentDetailsBillIdArr){
            if($incentiveSaleBillIdsArr){
                $paymentDetailSaleBillIds = array_diff($paymentDetailsBillIdArr, $incentiveSaleBillIdsArr);
            }
            else{
                $paymentDetailSaleBillIds = $paymentDetailsBillIdArr;
            }
        }
        //Adding the sales from billing.PAYMENT_DETAIL which are not exisiting in incentive.MONTHLY_INCENTIVE_ELIGIBILITY to UNASSISTED_SALES
        foreach($paymentDetailSaleBillIds as $key => $val){
            $netOfTaxFactor = 100/(100+$paymentArr[$val]['TAX_RATE']);
            $processWiseSale[$paymentArr[$val]['ENTRY_DT']]['UNASSISTED_SALES']+= ($paymentArr[$val]['AMOUNT'] - $paymentArr[$val]['APPLE_COMMISSION'])*($netOfTaxFactor);
        }
        //Adding the processWiseSale array  to incentive_SALES_PROCESS_WISE_TRACKING
        $salesProcessObj = new incentive_SALES_PROCESS_WISE_TRACKING();
        foreach($processWiseSale as $key => $val){
            $paramsArr["DATE"] = $key;
            foreach(crmParams::$processNames as $processKey => $processVal){
                $paramsArr[$processKey] = round($val[$processKey],2);
            }
            $salesProcessObj->insert($paramsArr);
        }
        unset($incetiveMonthlyIncentiveElgObj);
        unset($sales);
        unset($incentiveSaleBillIdsArr);
        unset($saleDetails);
        unset($processWiseSale);
        unset($paymentDetailsObj);
        unset($paymentDetails);
        unset($paymentDetailsBillIdArr);
        unset($paymentArr);
        unset($paymentDetailSaleBillIds);
        unset($salesProcessObj);
        unset($paramsArr);
    }

    public function addAmountToProcess($saleDetails,$priv,$processWiseSale)
    {
        $date = $saleDetails["DATE"];
        $amount = $saleDetails["AMOUNT"];
        //This if checks if the agent has the basic privilage 'ExcSl'
        if($priv){
            if(strpos($priv, 'ExcDIb') !== false){
                $processWiseSale[$date]['INBOUND_TELE']+= $amount;
            }
            else if(strpos($priv, 'ExcBSD') !== false || strpos($priv, 'ExcBID') !== false){
                $processWiseSale[$date]['CENTER_SALES']+= $amount;
            }
            else if(strpos($priv, 'ExcFP') !== false){
                $processWiseSale[$date]['FP_TELE']+= $amount;
            }
            else if(strpos($priv, 'ExcRnw') !== false){
                $processWiseSale[$date]['CENTRAL_RENEW_TELE']+= $amount;
            }
            else if(strpos($priv, 'ExcFld') !== false){
                $processWiseSale[$date]['FIELD_SALES']+= $amount;
            }
            else if(strpos($priv, 'ExcFSD') !== false || strpos($priv, 'ExcFID') !== false){
                $processWiseSale[$date]['FRANCHISEE_SALES']+= $amount;
            }
            else if(strpos($priv, 'ExcDOb') !== false || strpos($priv, 'ExcPrm') !== false || strpos($priv, 'PreNri') !== false){
                $processWiseSale[$date]['OUTBOUND_TELE']+= $amount;
            }
            else{
                $processWiseSale[$date]['UNASSISTED_SALES']+= $amount;
            }
        }
        else{
            $processWiseSale[$date]['UNASSISTED_SALES']+= $amount;
        }
        return $processWiseSale;
    }
    
    public function bakeDataForSalesProcessMIS($data)
    {
        foreach($data as $key => $row){
            foreach(crmParams::$processNames as $processKey => $processVal){
                $result[$processKey][$row['DT_RANGE']] += $row[$processKey];
                $result[$processKey]['TOTAL']          += $row[$processKey];
            }
        }
        return $result;
    }
}
?>
