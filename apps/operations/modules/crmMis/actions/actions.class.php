<?php
/**
* crmMis actions.
*
* @package    jeevansathi
* @subpackage crmMis
* @author     lakshay
* @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
*/
class crmMisActions extends sfActions
{
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
        public function executeMisMainpage(sfWebRequest $request)
        {
                $paramArr 	=$request->getParameterHolder()->getAll();
                $this->cid	=$paramArr['cid'];
		$this->public   =$paramArr['public'];

		if($_SERVER['HTTP_HOST']=='crm.jeevansathi.com')
			$this->public='Y';

                $agentAllocDetailsObj 	=new AgentAllocationDetails();
                $agentName 		=$agentAllocDetailsObj->fetchAgentName($this->cid);
		$privilegeStr     	=$agentAllocDetailsObj->getprivilage($this->cid);
		$privilegeArr		=@explode("+", $privilegeStr);

		$misHandlerObj          =new misGenerationhandler();
		$mainPageDetails	=$misHandlerObj->fetchMainPageDetails($this->public);			
		$this->linkDetailsArr	=$misHandlerObj->fetchMainPageLinkDetails($privilegeArr, $mainPageDetails, $this->cid, $agentName);	
		$this->setTemplate('misMainpage');	
        }
	public function executeExecutiveEfficiencyFtoResult2(sfWebRequest $request){
		$this->incentiveEligible=$request->getParameter("incentive");
		$allotedTo=$request->getParameter("allotedTo");
		$st_date=$request->getParameter("startDate");
		$end_date=$request->getParameter("endDate");
		$execMisObj=new FTO_EXEC_EFFICIENCY_MIS();
		$this->cid=$request->getParameter("cid");
		$dataArr=$execMisObj->fetchProfiles($allotedTo,$this->incentiveEligible,$st_date,$end_date);
		$jprofileObj=new JPROFILE();
		$ftoActivityInfoObj=new FTO_ACTIVITY_INFO();
		for($i=0;$i<count($dataArr);$i++)
		{
			$user=$jprofileObj->get($dataArr[$i]['PROFILEID'],"PROFILEID","USERNAME");
			$dataArr[$i]['USERNAME']=$user["USERNAME"];
			$dataArr[$i]['ALLOT_TIME']=date("d-M-y H:i:s",JSstrToTime($dataArr[$i]['ALLOT_TIME']));
			$dataArr[$i]['PHOTO_DT']=date("d-M-y H:i:s",JSstrToTime($dataArr[$i]['PHOTO_DT']));
			$dataArr[$i]['PHONE_VERIFY_DT']=date("d-M-y H:i:s",JSstrToTime($dataArr[$i]['PHONE_VERIFY_DT']));
			$dataArr[$i]['FTO_OFFER_DT']=date("d-M-y H:i:s",JSstrToTime($dataArr[$i]['FTO_OFFER_DT']));
			$dataArr[$i]['FIRST_EOI_DT']=date("d-M-y H:i:s",JSstrToTime($dataArr[$i]['FIRST_EOI_DT']));
			$dataArr[$i]['FTO_ACTIVATION_DT']=date("d-M-y H:i:s",JSstrToTime($dataArr[$i]['FTO_ACTIVATION_DT']));
			$dataArr[$i]['FTO_INCENTIVE_DT']=date("d-M-y H:i:s",JSstrToTime($dataArr[$i]['FTO_INCENTIVE_DT']));
			$dataArr[$i]['DEALLOCATION_DT']=date("d-M-y H:i:s",JSstrToTime($dataArr[$i]['DEALLOCATION_DT']));
			if(!JSstrToTime($dataArr[$i]['PHOTO_DT'])||!JSstrToTime($dataArr[$i]['FTO_OFFER_DT'])||!JSstrToTime($dataArr[$i]['PHONE_VERIFY_DT'])||!JSstrToTime($dataArr[$i]['FIRST_EOI_DT']))
				$firstActivities=$ftoActivityInfoObj->getFirstActivities($dataArr[$i]['PROFILEID']);
			if(!JSstrToTime($dataArr[$i]['FTO_ACTIVATION_DT']))
			{
				$dataArr[$i]['FTO_ACTIVATION_DT']="";
			}
			if(!JSstrToTime($dataArr[$i]['FTO_INCENTIVE_DT']))
			{
				$dataArr[$i]['FTO_INCENTIVE_DT']="";
			}
			if(!JSstrToTime($dataArr[$i]['PHOTO_DT']))     //equvalent to 0000-00-00 00:00:00
			{
				if($user['HAVEPHOTO']=='Y')
				{
					if(JSstrToTime($firstActivities['PHOTO_DT'])<JSstrToTime($dataArr[$i]['ALLOT_TIME'])&&JSstrToTime($firstActivities['PHOTO_DT']))
						$dataArr[$i]['PHOTO_DT']="Done Before Allocation";
					else
						$dataArr[$i]['PHOTO_DT']="";
				}
				else
					$dataArr[$i]['PHOTO_DT']="";
			}
			if(!JSstrToTime($dataArr[$i]['PHONE_VERIFY_DT']))
			{
				$jprofileContactObj=new ProfileContact();
				$valueArr['PROFILEID']=$dataArr[$i]['PROFILEID'];
				$result=$jprofileContactObj->getArray($valueArr,"","","ALT_MOB_STATUS");
				if($user['MOB_STATUS']=='Y'||$user['LANDL_STATUS']=='Y'||$result[0]['ALT_MOB_STATUS']=='Y')
				{
					if(JSstrToTime($firstActivities['PHONE_VERIFY_DT'])<JSstrToTime($dataArr[$i]['ALLOT_TIME'])&&JSstrToTime($firstActivities['PHONE_VERIFY_DT']))
						$dataArr[$i]['PHONE_VERIFY_DT']="Done Before Allocation";
					else
						$dataArr[$i]['PHONE_VERIFY_DT']="";
				}
				else
					$dataArr[$i]['PHONE_VERIFY_DT']="";
			}
			if(!JSstrToTime($dataArr[$i]['FIRST_EOI_DT']))
			{
				if(JSstrToTime($firstActivities['FIRST_EOI_DT'])<JSstrToTime($dataArr[$i]['ALLOT_TIME'])&&JSstrToTime($firstActivities['FIRST_EOI_DT']))
					$dataArr[$i]['FIRST_EOI_DT']="Done Before Allocation";
				else
					$dataArr[$i]['FIRST_EOI_DT']="";
			}
			if(!JSstrToTime($dataArr[$i]['FTO_OFFER_DT']))
			{
				if(JSstrToTime($firstActivities['FTO_OFFER_DT'])<JSstrToTime($dataArr[$i]['ALLOT_TIME'])&&JSstrToTime($firstActivities['FTO_OFFER_DT']))
					$dataArr[$i]['FTO_OFFER_DT']="Done Before Allocation";
				else
					$dataArr[$i]['FTO_OFFER_DT']="";
			}
		}
		if($this->incentiveEligible=='Y')
			$labelForFto ="Date of FTO";
		else
			$labelForFto ="Date of FTO activation";
		$this->dataArr=$dataArr;
		$this->labelArr =array(
			array("NAME"=>"Sl. No.","VALUE"=>"0"),
			array("NAME"=>"Username","VALUE"=>"1"),
			array("NAME"=>"Date of Allocation","VALUE"=>"2"),
			array("NAME"=>"Date of photo upload","VALUE"=>"3"),
			array("NAME"=>"Date of phone verification","VALUE"=>"4"),
			array("NAME"=>"Date of Free Trial Offer","VALUE"=>"5"),
			array("NAME"=>"Date of First Sent Eoi","VALUE"=>"6"),
			array("NAME"=>"$labelForFto","VALUE"=>"7"),
			array("NAME"=>"Date of De-allocation","VALUE"=>"8"),
			);
		if($request->getParameter("type")=="M")
		{
			$month=date("m",JSstrToTime($st_date));
			$month=date("F",JSstrToTime($st_date));
			$year=date("Y",JSstrToTime($st_date));
			$headLabel="For the Month $month-$year";
		}
		else
		{
			$stDt=date("d-M-y",JSstrToTime($st_date));
			$endDt=date("d-M-y",JSstrToTime($end_date));
			$headLabel="For the Period $stDt to $endDt";
		}
		$this->head_label=$headLabel;
		$this->allotedTo=$allotedTo;
		$this->RESULT="1";
	}
	public function executeFTAFTOProcessEfficiency(sfWebRequest $request){
		$cid=$request->getParameter("cid");
		$agentName=$request->getParameter("name");
		$date = date("Y-m-d");
		$ftoDays=15;
		if($request->getParameter("submit")||$request->getParameter("outside")=='Y')
		{
			$select_type=$request->getParameter("select_type");
			$format_type=$request->getParameter("format_type");
			if($select_type=="M")
			{
				$year_month=$request->getParameter("year_month");
				$month=$request->getParameter("month");
				$startDt       =$year_month."-".$month."-01";
				$endDt         =$year_month."-".$month."-31";
				$month=date("F",JSstrToTime($startDt));
				$headLabel="For the Month : $month-$year_month";
			}
			elseif($select_type=="R")
			{
				$month_r1=$request->getParameter("month_r1");
				$year_r1=$request->getParameter("year_r1");
				$day_r1=$request->getParameter("day_r1");
				$month_r2=$request->getParameter("month_r2");
				$day_r2=$request->getParameter("day_r2");
				$year_r2=$request->getParameter("year_r2");
				$startDt       =$year_r1."-".$month_r1."-".$day_r1;
				$endDt         =$year_r2."-".$month_r2."-".$day_r2;
				$start_Dt=date("d-M-y",JSstrToTime($startDt));
				$end_Dt=date("d-M-y",JSstrToTime($endDt));
				$headLabel="For the Period : $start_Dt To $end_Dt";
			}
			elseif($request->getParameter("outside")=='Y')
			{
				list($year_r1,$month_r1,$day_r1) =explode("-",date("Y-m-1"));
				list($year_r2,$month_r2,$day_r2)=explode("-",date("Y-m-d"));
				$startDt       =$year_r1."-".$month_r1."-".$day_r1;
				$endDt         =$year_r2."-".$month_r2."-".$day_r2;
				$month=date("F");
				$headLabel="For the Month : $month-$year_r1";
			}
			$pswrdsObj=new jsadmin_PSWRDS();
			$ftoMisObj=new FTO_EXEC_EFFICIENCY_MIS();
			$agents=$pswrdsObj->fetchAgentsWithPriviliges("%FTAFTO%");
			list($conversionCount,$conversionOnDay)=$ftoMisObj->getProcessEfficiency($startDt,$endDt,$agents);
			if($format_type=="XLS")
			{
				$headerLabel="\t\t\t\n\n";
				$headerLabel.="\t\t\tFTA FTO Process Efficiency Mis\n";
				$headerLabel.="\t\t\t".$headLabel."\n\n\n";
				$headerLabel.="Date"."\t";
				$headerLabel.="Number Of Allocations"."\t";
				for($x=1; $x<=$ftoDays; $x++)
					$headerLabel .=$x."\t";
				$headerLabel.="Total";
				$headerLabel.="\n";
				foreach($conversionCount as $date=>$cnt)
				{
					$dataset.=date("d-M-y",JSstrToTime($date))."\t";
					$dataset.=$conversionCount[$date]["ALLOCATIONS"]."\t";
					$totalAllocations+=$conversionCount[$date]["ALLOCATIONS"];
					$totalActivations+=$conversionCount[$date]["ACTIVATIONS"];
					for($c=0;$c<$ftoDays;$c++)
					{
						if($conversionCount[$date][$c])
							$dataset.=$conversionCount[$date][$c]."(".round($conversionCount[$date][$c]/$conversionCount[$date]["ALLOCATIONS"]*100)."%)"."\t";
						else
							$dataset.="0"."\t";
					}
					$dataset.=$conversionCount[$date]["ACTIVATIONS"]."(".round($conversionCount[$date]["ACTIVATIONS"]/$conversionCount[$date]["ALLOCATIONS"]*100)."%)";
					$dataset.="\n";
				}
				$result="Total\t";
				$result.=$totalAllocations."\t";
				for($c=0;$c<$ftoDays;$c++)
					$result.=$conversionOnDay[$c]."(".round($conversionOnDay[$c]/$totalAllocations*100)."%)"."\t";
				$result.=$totalActivations."(".round($totalActivations/$totalAllocations*100)."%)";
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename=ftoEfficiencyReport.xls");
				header("Pragma: no-cache");
				header("Expires: 0");
				$finalData=$headerLabel.$dataset.$result;
				echo $finalData;
				die();
			}
			$this->RESULT="1";
			$this->ftoDays=$ftoDays;
			$this->conversionCount=$conversionCount;
			$this->conversionOnDay=$conversionOnDay;
			$this->headLabel=$headLabel;
			$this->allocationsOnDay=$allocationsOnDay;
		}
		else
		{
			list($curyear,$curmonth,$curday) = explode("-",$date);
			$date45DaysBefore=date("Y-m-d",time()-45*86400);
			list($year45DaysBefore,$month45DaysBefore,$day45DaysBefore)=explode("-",$date45DaysBefore);
			$month_arr = array(
				array("NAME" => "January", "VALUE" => "01"),
				array("NAME" => "February", "VALUE" => "02"),
				array("NAME" => "March", "VALUE" => "03"),
				array("NAME" => "April", "VALUE" => "04"),
				array("NAME" => "May", "VALUE" => "05"),
				array("NAME" => "June", "VALUE" => "06"),
				array("NAME" => "July", "VALUE" => "07"),
				array("NAME" => "August", "VALUE" => "08"),
				array("NAME" => "September", "VALUE" => "09"),
				array("NAME" => "October", "VALUE" => "10"),
				array("NAME" => "November", "VALUE" => "11"),
				array("NAME" => "December", "VALUE" => "12"),
				);
			$quarter_arr =array(
				array("NAME"=>"Apr-June","VALUE"=>"1"),
				array("NAME"=>"Jul-Sept","VALUE"=>"2"),
				array("NAME"=>"Oct-Dec","VALUE"=>"3"),
				array("NAME"=>"Jan-Mar","VALUE"=>"4")
				);

			for($i=1;$i<=31;$i++)
				$ddarr[] = $i;

			for($i=0;$i<12;$i++)
				$mmarr[] = $month_arr[$i];

			for($i=2010;$i<=$curyear+2;$i++)
				$yyarr[] = $i;
			for($i=0;$i<4;$i++)
				$qqarr[] = $quarter_arr[$i];
			$this->ddarr=$ddarr;
			$this->mmarr=$mmarr;
			$this->yyarr=$yyarr;
			$this->stDt=$startDt;
			$this->endDt=$endDt;
			$this->curyear=$curyear;
			$this->curmonth=$curmonth;
			$this->curday=$curday;
			$this->year45DaysBefore=$year45DaysBefore;
			$this->month45DaysBefore=$month45DaysBefore;
			$this->day45DaysBefore=$day45DaysBefore;
		}
		$this->cid=$cid;
	}
	public function executeFtaRegular(sfWebRequest $request){
		if($request->getParameter('submit')||$request->getParameter('outside')=='Y')
		{
			$select_type=$request->getParameter('select_type');
			$agentAllocationObj=new AgentAllocationDetails();
			$misHandlerObj=new misGenerationhandler();
			$processObj=new PROCESS();
			$processObj->setProcessName('FTA_REGULAR');
			$month=$request->getParameter('month');
			$year_month=$request->getParameter('year_month');
			$month_r1=$request->getParameter('month_r1');
			$month_r2=$request->getParameter('month_r2');
			$year_r1=$request->getParameter('year_r1');
			$year_r2=$request->getParameter('year_r2');
			$day_r1=$request->getParameter('day_r1');
			$day_r2=$request->getParameter('day_r2');
			$cid=$request->getParameter('cid');
			if($request->getParameter('outside')=='Y')
			{
				list($year_r1,$month_r1,$day_r1) =explode("-",date("Y-m-1"));
				list($year_r2,$month_r2,$day_r2)=explode("-",date("Y-m-d"));
				$month=date("F");
				$headLabel="For the Month : $month-$year_r1";
				$start_dt =$year_r1."-".$month_r1."-01"." 00:00:00";
				$end_dt =$year_r1."-".$month_r1."-31"." 23:59:59";
			}
			if($select_type=="M")
			{
				$start_dt       =$year_month."-".$month."-01"." 00:00:00";
				$end_dt         =$year_month."-".$month."-31"." 23:59:59";
				$headLabelTxt   ='For the month ';
				$monthText	=date("F",JSstrToTime($start_dt));
				$headLabel	=$headLabelTxt.$monthText."-".$year_month;
			}
			elseif($select_type=="R")
			{
				$start_dt       =$year_r1."-".$month_r1."-".$day_r1." 00:00:00";
				$end_dt         =$year_r2."-".$month_r2."-".$day_r2." 23:59:59";
				$headLabelTxt   ='For the period : ';
				$startDt=date("d-M-y",JSstrToTime($start_dt));
				$endDt=date("d-M-y",JSstrToTime($end_dt));
				$headLabel	=$headLabelTxt.$startDt." To ".$endDt;
			}

			$label_arr =array(
				array("NAME"=>"Executive","VALUE"=>"0"),
				array("NAME"=>"Number of profiles which were called","VALUE"=>"1"),
				array("NAME"=>"Number of profiles on which photo was uploaded","VALUE"=>"2"),
				array("NAME"=>"Number of profiles on which sent EoI","VALUE"=>"3"),
				array("NAME"=>"Number of profiles which paid","VALUE"=>"4"),
				);
			$agentName=$request->getParameter('user');
			if($agentName=="")
				die("No Agent Selected");
			$agents=$agentAllocationObj->fetchAgentsByHierarchy($agentName,'','',1);
			$range['START_DATE']=$start_dt;
			$range['END_DATE']=$end_dt;
			$data_arr=$misHandlerObj->fetchMisData($agents,$processObj,$range);
			if($request->getParameter('format_type')=="XLS")
			{

				$headerLabel="\t\t\t\n\n";
				$headerLabel.="\t\t\tFTA Executive Efficiency Mis\n";
				$headerLabel.="\t\t\t".$headLabel."\n\n\n";
				$headerLabel.="Executive"."\t";
				$headerLabel.="Number Of Profiles which were called"."\t";
				$headerLabel.="Number Of Profiles on which photo was uploaded"."\t";
				$headerLabel.="Number Of Profiles which sent EOI"."\t";
				$headerLabel.="Number Of Profiles which paid"."\t";
				$headerLabel.="\n";
				foreach($data_arr as $agent=>$data)
				{
					$dataset.=$agent."\t";
					$dataset.=$data['CALLED_DATE']."\t";
					$dataset.=$data['PHOTO_DATE']."\t";
					$dataset.=$data['EOI_DATE']."\t";
					$dataset.=$data['PAID_DATE']."\t";
					$dataset.="\n";
				}
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename=ftoEfficiencyReport.xls");
				header("Pragma: no-cache");
				header("Expires: 0");
				$finalData=$headerLabel.$dataset.$result;
				echo $finalData;
				die();
			}
			$this->data_arr=$data_arr;
			$this->start_dt=$start_dt;
			$this->end_dt=$end_dt;
			$this->label_arr=$label_arr;
			$this->execFtoCntTot=$execFtoCntTot;
			$this->supCountArr=$supCountArr;
			$this->head_label=$headLabel;
			$this->type=$select_type;
			$this->user=$agentName;
			$this->RESULT=1;
			$this->cid=$cid;
		}
		else
		{
			$date1 = date("Y-m-d",time()-30*24*60*60);
			$date  = date("Y-m-d");
			$name=$request->getParameter('user');
			$cid=$request->getParameter('cid');
			list($curyear,$curmonth,$curday) = explode("-",$date);
			list($curyear1,$curmonth1,$curday1) = explode("-",$date1);
			$month_arr = array(
				array("NAME" => "January", "VALUE" => "01"),
				array("NAME" => "February", "VALUE" => "02"),
				array("NAME" => "March", "VALUE" => "03"),
				array("NAME" => "April", "VALUE" => "04"),
				array("NAME" => "May", "VALUE" => "05"),
				array("NAME" => "June", "VALUE" => "06"),
				array("NAME" => "July", "VALUE" => "07"),
				array("NAME" => "August", "VALUE" => "08"),
				array("NAME" => "September", "VALUE" => "09"),
				array("NAME" => "October", "VALUE" => "10"),
				array("NAME" => "November", "VALUE" => "11"),
				array("NAME" => "December", "VALUE" => "12"),
				);
			for($i=1;$i<=31;$i++)
				$ddarr[] = $i;
			for($i=0;$i<12;$i++)
				$mmarr[] = $month_arr[$i];
			for($i=2010;$i<=$curyear+2;$i++)
				$yyarr[] = $i;
			$this->curyear=$curyear;
			$this->curmonth=$curmonth;
			$this->curday=$curday;
			$this->curyear1=$curyear1;
			$this->curmonth1=$curmonth1;
			$this->curday1=$curday1;
			$this->mmarr=$mmarr;
			$this->yyarr=$yyarr;
			$this->ddarr=$ddarr;
			$this->name=$name;
			$this->user=$name;
			$this->cid=$cid;
		}
		$this->setTemplate('fta_efficiency_report');
	}


	public function executeCrmHandledRevenueCsvGenerate(sfWebRequest $request)
	{	
		if($request->getParameter("fromMisCron")==1)
		{
			$agentAllocDetailsObj   =new AgentAllocationDetails();
			$this->TAX_RATE = billingVariables::TAX_RATE;
			$this->SUBMIT_STATUS = 1;
			$this->monthName = $request->getParameter("monthValue");
			$this->yearName = $request->getParameter("yearValue");
            $this->fortnight = $request->getParameter("fortnightValue");
			$this->report_type = $request->getParameter("report_type");
			$this->report_content = $request->getParameter("report_content");
			$this->report_format = $request->getParameter("report_format");
			$this->monthNum = crmParams::$monthOrder[$this->monthName];
			if($this->monthNum<10) 	$this->monthNum = "0".$this->monthNum;
			$jsadminPswrdsObj = new jsadmin_PSWRDS('newjs_slave');
			$incentiveSalesTargetObj = new incentive_SALES_TARGET('newjs_slave');
			$incentiveMonthlyObj = new incentive_MONTHLY_INCENTIVE_ELIGIBILITY('newjs_slave');
			$misGenerationhandlerObj = new misGenerationhandler();
			$this->agentName = $misGenerationhandlerObj->get_SLHDO();

			$allCenters = $jsadminPswrdsObj->fetchAllDistinctCenters();
			$targetMonth = $this->monthName."-".$this->yearName;
            if($this->fortnight == 1){
                $stFortDate = 1;
                $endFortDate = 15;
            }
            else{
                $stFortDate = 16;
                if(crmParams::$monthDays[$this->monthName] == 28)
                    $endFortDate = 28;
                else if(crmParams::$monthDays[$this->monthName] == 30)
                    $endFortDate = 30;
                else
                    $endFortDate = 31;
            }
            if($misGenerationhandlerObj->is_leap_yr($this->yearName) && $this->monthName=="Feb" && $this->fortnight == 2)
                $endFortDate = 29;
			$st_date=$this->yearName."-".crmParams::$monthOrder[$this->monthName]."-".$stFortDate." 00:00:00";
			$end_date=$this->yearName."-".crmParams::$monthOrder[$this->monthName]."-".$endFortDate." 23:59:59";
            $this->ddarr = range($stFortDate,$endFortDate);
			$ddarr_cnt = count($this->ddarr);
			$this->empDetailArr = $jsadminPswrdsObj->fetchAllUsernamesAndEmpID();
			$hierarchyObj = new hierarchy($this->agentName);
			$allReporters = $hierarchyObj->getAllReporters();
			$res = $jsadminPswrdsObj->fetchAgentInfo($allReporters);
			
				$target = $incentiveSalesTargetObj->fetchSalesTarget($allReporters, $targetMonth, $this->fortnight);
				$individualTarget = $target[0];
				$finalTarget = $target[1];
				$daywise = $incentiveMonthlyObj->fetchDaywiseData($st_date, $end_date, $allReporters);
				$this->detail = array();

				$given_dt = $this->yearName.$this->monthNum;
				$curr_dt = date('Ym');
				foreach($res as $key=>$value)
				{
					if($this->report_type == "LOCATION"){
						if($given_dt<$curr_dt && !$daywise[$key]['CENTER']) continue;
						else if(!$daywise[$key]['CENTER'] && !$misGenerationhandlerObj->isValid_locationwise($value)) continue;
						if($daywise[$key]['CENTER'] && !in_array($daywise[$key]['CENTER'], $allCenters)) continue;
						$this->detail[$key]['CENTER'] =  $value['CENTER'];
					}
					else if($this->report_type == "TEAM"){
						if(!$daywise[$key]['CENTER'] && !$misGenerationhandlerObj->isValid_teamwise($value)) continue;
						$this->detail[$key]['COLOR'] = $misGenerationhandlerObj->getRowColour($res[$key]['PRIVILAGE']);
					}
					$this->detail[$key]['USERNAME'] = $key;

					if(!$individualTarget[$key])	   $individualTarget[$key]=0;
					$this->detail[$key]['INDIVIDUAL_TARGET'] =  $individualTarget[$key];

					if(!$finalTarget[$key])	   $finalTarget[$key]=0;
					$this->detail[$key]['FINAL_TARGET'] =  $finalTarget[$key];

					$this->detail[$key]['AMOUNT'] =  $daywise[$key]['AMOUNT'];
					$this->detail[$key]['TOTAL_AMOUNT']=0;
					for($i=$stFortDate; $i<=$endFortDate; $i++)
					{
						if(!$this->detail[$key]['AMOUNT'][$i]){
							$this->detail[$key]['AMOUNT'][$i]='';
							continue;
						}
						$this->detail[$key]['TOTAL_AMOUNT'] += $this->detail[$key]['AMOUNT'][$i];
					}
					ksort($this->detail[$key]['AMOUNT']);
					$this->detail[$key]['SALES_WITHOUT_TAX'] = $misGenerationhandlerObj->net_off_tax_calculation($this->detail[$key]['TOTAL_AMOUNT'], $end_date);
					$this->detail[$key]['TARGET_ACHIEVEMENT'] = $misGenerationhandlerObj->calculateTargetAchievement($this->detail[$key]['SALES_WITHOUT_TAX'], $this->detail[$key]['INDIVIDUAL_TARGET'], $this->monthName, $this->yearName, $this->fortnight);
				}
                
				if($this->report_type == "LOCATION")
				{
					$this->location = array();
					foreach($this->detail as $key=>$value){
						if(!$this->detail[$key]['CENTER']) continue;
						$this->location[$this->detail[$key]['CENTER']]['TOTAL_AMOUNT'] += $this->detail[$key]['TOTAL_AMOUNT'];
						$this->location[$this->detail[$key]['CENTER']]['TOTAL_SALES'] += $this->detail[$key]['SALES_WITHOUT_TAX'];
						$this->location[$this->detail[$key]['CENTER']]['TOTAL_TARGET'] += $this->detail[$key]['INDIVIDUAL_TARGET'];
						$this->location[$this->detail[$key]['CENTER']]['USERNAME'][] = $key;
						for($i=$stFortDate;$i<=$endFortDate;$i++)
							$this->location[$this->detail[$key]['CENTER']]['DAYWISE_AMOUNT'][$i] += $this->detail[$key]['AMOUNT'][$i];
					}
					foreach($this->location as $key=>$value)
						$this->location[$key]['ACHIEVEMENT'] = $misGenerationhandlerObj->calculateTargetAchievement($this->location[$key]['TOTAL_SALES'],$this->location[$key]['TOTAL_TARGET'], $this->monthName, $this->yearName,  $this->fortnight);

					$this->overall = array();
					foreach($this->location as $key=>$value)
					{
						$this->overall['AMOUNT'] += $value['TOTAL_AMOUNT'];
						$this->overall['SALES'] += $value['TOTAL_SALES'];
						$this->overall['TARGET'] += $value['TOTAL_TARGET'];
						for($i=$stFortDate;$i<=$endFortDate;$i++)
							$this->overall['DAYWISE_AMOUNT'][$i] += $value['DAYWISE_AMOUNT'][$i];
					}
					$this->overall['ACHIEVEMENT'] = $misGenerationhandlerObj->calculateTargetAchievement($this->overall['SALES'], $this->overall['TARGET'], $this->monthName, $this->yearName,  $this->fortnight);
					$misGenerationhandlerObj->sort_locationwise($this->location);

					// Blank cells Handling in Interface
					if($given_dt < $curr_dt)
						$this->blank_cells_past = 1;
					else if($given_dt == $curr_dt){
						$this->blank_cells_curr = 1;
						$this->dt_curr = date('d');
					}
				}
				else if($this->report_type == "TEAM")
				{
					$users = array();
					foreach($this->detail as $key=>$val)
						$users[] = $this->detail[$key]['USERNAME'];
					$names = $agentAllocDetailsObj->getValidUsersForSalesTarget();
					if(count($names['BOSS'])!=1){
						$this->overall_sales_head_check = 1;
						$this->setTemplate('crmHandledRevenueTeamWise');
						return;
					}
					if($misGenerationhandlerObj->isPrivilege_P_MG($this->agentName))
						$hierarchyObj = new hierarchy($names['BOSS'][0]);
					$this->hierarchy = $hierarchyObj->getHierarchyData($users);
					foreach($this->hierarchy as $v)
						$hlist[] = $v['USERNAME'];

					$this->team = array();
					foreach($this->hierarchy as $key=>$value){
						if($this->detail[$value['USERNAME']]['TOTAL_AMOUNT'])
							$this->team[$value['USERNAME']]['IS_HYPERLINK'] = 1;
					}
					foreach($this->hierarchy as $key=>$value){
						$h_obj = new hierarchy($value['USERNAME']);
						$h = $h_obj->getHierarchyData($hlist);

						foreach($h as $k=>$val){
							$this->team[$value['USERNAME']]['TOTAL_AMOUNT'] += $this->detail[$val['USERNAME']]['TOTAL_AMOUNT'];
							$this->team[$value['USERNAME']]['SALES_WITHOUT_TAX'] += $this->detail[$val['USERNAME']]['SALES_WITHOUT_TAX'];
							for($i=$stFortDate;$i<=$endFortDate;$i++)
								$this->team[$value['USERNAME']]['AMOUNT'][$i] += $this->detail[$val['USERNAME']]['AMOUNT'][$i];
						}
						$this->team[$value['USERNAME']]['FINAL_TARGET'] = $this->detail[$value['USERNAME']]['FINAL_TARGET'];
						$this->team[$value['USERNAME']]['TARGET_ACHIEVEMENT'] = $misGenerationhandlerObj->calculateTargetAchievement($this->team[$value['USERNAME']]['SALES_WITHOUT_TAX'], $this->team[$value['USERNAME']]['FINAL_TARGET'], $this->monthName, $this->yearName,  $this->fortnight);
					}
				}

			if($this->report_format == "XLS")
			{
				if($this->report_type == "LOCATION")
					$headerLabel.="Center/Executive\tEmployee_ID\tTarget\tSales(without_tax)\tTarget_Achievement\t";
				else if($this->report_type == "TEAM" && $this->report_content == "REVENUE")
					$headerLabel.="Manager/Supervisor/Executive\tEmployee_ID\tTarget\tSales(without_tax)\tTarget_Achievement\t";
				else if($this->report_type == "TEAM" && $this->report_content == "TICKET")
				{
					$headerLabel.="Manager/Supervisor/Executive\tEmployee_ID\t";
					foreach ($this->ddarr as $dd) {
						$headerLabel .= "$dd\t";
					}
					$headerLabel .= "Total\tTicket_Size\n";
				}

				if($this->report_content != "TICKET")
				{
					for($i=$stFortDate; $i<=$endFortDate; $i++)
						$headerLabel.=$i."\t";
					$headerLabel.="Total_Sales\n\n";
				}

				if($this->report_type == "LOCATION"){
					foreach($this->location as $loc=>$info)
					{
						$dataset.=$loc."\n";
						foreach($info['USERNAME'] as $val)
						{
							$dataset.=$val."\t";
							$dataset.=$this->empDetailArr[$val]."\t";
							$dataset.=$this->detail[$val]['INDIVIDUAL_TARGET']."\t";
							$dataset.=$this->detail[$val]['SALES_WITHOUT_TAX']."\t";
							$dataset.=$this->detail[$val]['TARGET_ACHIEVEMENT'][0]."\t";
							for($i=$stFortDate;$i<=$endFortDate;$i++)
								$dataset.=$this->detail[$val]['AMOUNT'][$i]."\t";
							$dataset.=$this->detail[$val]['TOTAL_AMOUNT']."\t";
							$dataset.="\n";
						}
						$dataset.=$loc."_TOTAL\t\t".$info['TOTAL_TARGET']."\t".$info['TOTAL_SALES']."\t".$info['ACHIEVEMENT'][0]."\t";
						for($i=$stFortDate;$i<=$endFortDate;$i++)
							$dataset.=$info['DAYWISE_AMOUNT'][$i]."\t";
						$dataset.=$info['TOTAL_AMOUNT']."\n\n";
					}
					$dataset.="GRAND_TOTAL\t\t".$this->overall['TARGET']."\t".$this->overall['SALES']."\t".$this->overall['ACHIEVEMENT'][0]."\t";
					for($i=$stFortDate;$i<=$endFortDate;$i++)
						$dataset.=$this->overall['DAYWISE_AMOUNT'][$i]."\t";
					$dataset.=$this->overall['AMOUNT'];
				}
				else if($this->report_type == "TEAM" && $this->report_content == "REVENUE"){
					foreach($this->hierarchy as $key=>$val)
					{
						$levelSeparator = "";
						if($val['LEVEL'])
							for($i=$val['LEVEL'];$i>=0;$i--) 
							{
								$levelSeparator.="__";
							}
						$dataset.=$levelSeparator.$val['USERNAME']."\t";
						$dataset.=$this->empDetailArr[$val['USERNAME']]."\t";
						$dataset.=$this->team[$val['USERNAME']]['FINAL_TARGET']."\t";
						$dataset.=$this->team[$val['USERNAME']]['SALES_WITHOUT_TAX']."\t";
						$dataset.=$this->team[$val['USERNAME']]['TARGET_ACHIEVEMENT'][0]."\t";
						for($i=$stFortDate;$i<=$endFortDate;$i++)
							$dataset.=$this->team[$val['USERNAME']]['AMOUNT'][$i]."\t";
						$dataset.=$this->team[$val['USERNAME']]['TOTAL_AMOUNT']."\t";
						$dataset.="\n";
					}
				}
				else if($this->report_type == "TEAM" && $this->report_content == "TICKET"){
					foreach ($this->hierarchyData as $val) {
						$dataset .= $val['USERNAME']."\t";
						$dataset .= $this->empDetailArr[$val['USERNAME']]."\t";
						foreach ($this->ddarr as $dd) {
							$dataset .= $this->teamwiseData[$val['USERNAME']][$dd]."\t";
						}
						$dataset .= $this->teamwiseData[$val['USERNAME']]['TOTAL']."\t";
						$dataset .= $this->teamwiseData[$val['USERNAME']]['TICKET_SIZE']."\n";
					}
				}
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename=Sales_Credit_Details_".$this->yearName."_".$this->monthName.".xls");
				header("Pragma: no-cache");
				header("Expires: 0");
				$finalData=$headerLabel.$dataset;
				echo $finalData;
				die();
			}
		}
		else
		{
			echo "Invalid request";
			die;
		}
	}

	public function executeCrmHandledRevenueMis(sfWebRequest $request){
		$this->cid      =$request->getParameter("cid");
		$agentAllocDetailsObj   =new AgentAllocationDetails();
		$this->agentName =$agentAllocDetailsObj->fetchAgentName($this->cid);

		$this->SUBMIT_STATUS = 0;
		$this->monthName = date('M');
		$this->yearName = date('Y');

		$this->monthArr = array_keys(crmParams::$monthOrder);
		$this->yearArr = range(date('Y')+1,2004);
        $day = date('d');
        if($day >15){
            $this->fortnight = '2';
        }
        else{
            $this->fortnight = '1';
        }
		if($request->getParameter("submit") || $request->getParameter("outside"))
		{
			$this->TAX_RATE = billingVariables::TAX_RATE;
			$this->SUBMIT_STATUS = 1;
			if($request->getParameter("submit")){
				$this->monthName = $request->getParameter("monthValue");
				$this->yearName = $request->getParameter("yearValue");
                $this->fortnight = $request->getParameter("fortnightValue");
				$this->report_type = $request->getParameter("report_type");
				$this->report_content = $request->getParameter("report_content");
				$this->report_format = $request->getParameter("report_format");
			}
			if($request->getParameter("outside")){
				$this->report_type = "TEAM";
				$this->report_content = "REVENUE";
				$this->report_format = "HTML";
			}
			$this->monthNum = crmParams::$monthOrder[$this->monthName];
			if($this->monthNum<10) 	$this->monthNum = "0".$this->monthNum;
			$jsadminPswrdsObj = new jsadmin_PSWRDS('newjs_masterRep');
			$incentiveSalesTargetObj = new incentive_SALES_TARGET('newjs_masterRep');
			$incentiveMonthlyObj = new incentive_MONTHLY_INCENTIVE_ELIGIBILITY('newjs_masterRep');
			$misGenerationhandlerObj = new misGenerationhandler();

			$allCenters = $jsadminPswrdsObj->fetchAllDistinctCenters();
			$targetMonth = $this->monthName."-".$this->yearName;
            if($this->fortnight == 1){
                $stFortDate = 1;
                $endFortDate = 15;
            }
            else{
                $stFortDate = 16;
                if(crmParams::$monthDays[$this->monthName] == 28)
                    $endFortDate = 28;
                else if(crmParams::$monthDays[$this->monthName] == 30)
                    $endFortDate = 30;
                else
                    $endFortDate = 31;
            }
            if($misGenerationhandlerObj->is_leap_yr($this->yearName) && $this->monthName=="Feb" && $this->fortnight == 2)
                $endFortDate = 29;
			$st_date=$this->yearName."-".crmParams::$monthOrder[$this->monthName]."-".$stFortDate." 00:00:00";
			$end_date=$this->yearName."-".crmParams::$monthOrder[$this->monthName]."-".$endFortDate." 23:59:59";
            $this->ddarr = range($stFortDate,$endFortDate);
			$ddarr_cnt = count($this->ddarr);
			$this->empDetailArr = $jsadminPswrdsObj->fetchAllUsernamesAndEmpID();
			if($misGenerationhandlerObj->isPrivilege_P_MG($this->agentName,'Y'))
			{
				$hierarchyObj = new hierarchy($misGenerationhandlerObj->get_SLHDO());
			}
			else
			{
				$hierarchyObj = new hierarchy($this->agentName);
			}
			$allReporters = $hierarchyObj->getAllReporters();
			$res = $jsadminPswrdsObj->fetchAgentInfo($allReporters);
			if($this->report_content == "TICKET")
			{
				// $this->ddarr is an array of all dates in the selected month
				//$this->ddarr = range(1, date("t", strtotime($st_date)));
                $this->ddarr = range($stFortDate,$endFortDate);
				$agentwiseData = $incentiveMonthlyObj->fetchDaywiseTicketsAndAmount($st_date, $end_date);
				//$usernameArr = array_keys($agentwiseData);
				//$validUsers = $agentAllocDetailsObj->getValidUsersForSalesTarget($usernameArr);

				// detailed hierarchy information
				$this->hierarchyData = $hierarchyObj->getHierarchyData($allReporters);

				$allReporters = array();
				foreach ($this->hierarchyData as $val) 
				{
					$allReporters[] = $val['USERNAME'];
				}

				// calculate ticket-size for each agent on individual basis (not teamwise)
				foreach ($agentwiseData as $alloted_to => $ddarr) 
				{
					if(!in_array($alloted_to, $allReporters))
						unset($agentwiseData[$alloted_to]);
					$agentwiseData[$alloted_to]['NET_OFF_TAX_AMT'] = $misGenerationhandlerObj->net_off_tax_calculation($agentwiseData[$alloted_to]['AMT'], $end_date);
					$agentwiseData[$alloted_to]['TICKET_SIZE'] = round($agentwiseData[$alloted_to]['NET_OFF_TAX_AMT']/$agentwiseData[$alloted_to]['TOTAL']);
				}

				// Teamwise data generation
				$this->teamwiseData = array();
				foreach($this->hierarchyData as $val)
				{
					$agent = $val['USERNAME'];
					$h_obj = new hierarchy($agent);
					$h = $h_obj->getAllReporters(); // $h is an array having all reporters under $agent

					if($h && is_array($h))
					{
						foreach($h as $rep)
						{
							foreach($this->ddarr as $d) 
							{
								if($agentwiseData[$rep][$d])
									$this->teamwiseData[$agent][$d] += $agentwiseData[$rep][$d];
							}

							// total net_off_tax_amt of all reporters of $agent
							if($agentwiseData[$rep]['NET_OFF_TAX_AMT'])
								$this->teamwiseData[$agent]['NET_OFF_TAX_AMT'] += $agentwiseData[$rep]['NET_OFF_TAX_AMT'];
							// total tickets of all reporters of $agent
							if($agentwiseData[$rep]['TOTAL'])
								$this->teamwiseData[$agent]['TOTAL'] += $agentwiseData[$rep]['TOTAL'];
						}					
					}
					// ticket size of head = total net_off_tax_amt of all reporters / total tickets of all reporters
					if($this->teamwiseData[$agent]['TOTAL'] > 0)
						$this->teamwiseData[$agent]['TICKET_SIZE'] = round($this->teamwiseData[$agent]['NET_OFF_TAX_AMT']/$this->teamwiseData[$agent]['TOTAL']);

					// background color for $agent based on its privilege
					$this->teamwiseData[$agent]['BACKGROUND_COLOR'] = $misGenerationhandlerObj->getRowColour($res[$agent]['PRIVILAGE']);
					unset($h);
					unset($h_obj);			
				}
				foreach($this->teamwiseData as $key=>$val){
					if($val['TOTAL'] <= 0){
						unset($this->teamwiseData[$key]);
					} else {
						$teamArr[] = $key;
					}
				}
				foreach($this->hierarchyData as $key=>$val){
					if(!in_array($val['USERNAME'], $teamArr)){
						unset($this->hierarchyData[$key]);
					}
				}
			}
			else
			{
				$target = $incentiveSalesTargetObj->fetchSalesTarget($allReporters, $targetMonth, $this->fortnight);
				$individualTarget = $target[0];
				$finalTarget = $target[1];
				$daywise = $incentiveMonthlyObj->fetchDaywiseData($st_date, $end_date, $allReporters);
				$this->detail = array();

				$given_dt = $this->yearName.$this->monthNum;
				$curr_dt = date('Ym');
				foreach($res as $key=>$value)
				{
					if($this->report_type == "LOCATION"){
						if($given_dt<$curr_dt && !$daywise[$key]['CENTER']) continue;
						else if(!$daywise[$key]['CENTER'] && !$misGenerationhandlerObj->isValid_locationwise($value)) continue;
						if($daywise[$key]['CENTER'] && !in_array($daywise[$key]['CENTER'], $allCenters)) continue;
						$this->detail[$key]['CENTER'] =  $value['CENTER'];
					}
					else if($this->report_type == "TEAM"){
						if(!$daywise[$key]['CENTER'] && !$misGenerationhandlerObj->isValid_teamwise($value)) continue;
						$this->detail[$key]['COLOR'] = $misGenerationhandlerObj->getRowColour($res[$key]['PRIVILAGE']);
					}
					$this->detail[$key]['USERNAME'] = $key;

					if(!$individualTarget[$key])	   $individualTarget[$key]=0;
					$this->detail[$key]['INDIVIDUAL_TARGET'] =  $individualTarget[$key];

					if(!$finalTarget[$key])	   $finalTarget[$key]=0;
					$this->detail[$key]['FINAL_TARGET'] =  $finalTarget[$key];

					$this->detail[$key]['AMOUNT'] =  $daywise[$key]['AMOUNT'];
					$this->detail[$key]['TOTAL_AMOUNT']=0;
					for($i=$stFortDate; $i<=$endFortDate; $i++)
					{
						if(!$this->detail[$key]['AMOUNT'][$i]){
							$this->detail[$key]['AMOUNT'][$i]='';
							continue;
						}
						$this->detail[$key]['TOTAL_AMOUNT'] += $this->detail[$key]['AMOUNT'][$i];
					}
					ksort($this->detail[$key]['AMOUNT']);
					$this->detail[$key]['SALES_WITHOUT_TAX'] = $misGenerationhandlerObj->net_off_tax_calculation($this->detail[$key]['TOTAL_AMOUNT'], $end_date);
					$this->detail[$key]['TARGET_ACHIEVEMENT'] = $misGenerationhandlerObj->calculateTargetAchievement($this->detail[$key]['SALES_WITHOUT_TAX'], $this->detail[$key]['INDIVIDUAL_TARGET'], $this->monthName, $this->yearName, $this->fortnight);
				}
                
				if($this->report_type == "LOCATION")
				{
					$this->location = array();
					foreach($this->detail as $key=>$value){
						if(!$this->detail[$key]['CENTER']) continue;
						$this->location[$this->detail[$key]['CENTER']]['TOTAL_AMOUNT'] += $this->detail[$key]['TOTAL_AMOUNT'];
						$this->location[$this->detail[$key]['CENTER']]['TOTAL_SALES'] += $this->detail[$key]['SALES_WITHOUT_TAX'];
						$this->location[$this->detail[$key]['CENTER']]['TOTAL_TARGET'] += $this->detail[$key]['INDIVIDUAL_TARGET'];
						$this->location[$this->detail[$key]['CENTER']]['USERNAME'][] = $key;
						for($i=$stFortDate;$i<=$endFortDate;$i++)
							$this->location[$this->detail[$key]['CENTER']]['DAYWISE_AMOUNT'][$i] += $this->detail[$key]['AMOUNT'][$i];
					}
					foreach($this->location as $key=>$value)
						$this->location[$key]['ACHIEVEMENT'] = $misGenerationhandlerObj->calculateTargetAchievement($this->location[$key]['TOTAL_SALES'],$this->location[$key]['TOTAL_TARGET'], $this->monthName, $this->yearName,  $this->fortnight);

					$this->overall = array();
					foreach($this->location as $key=>$value)
					{
						$this->overall['AMOUNT'] += $value['TOTAL_AMOUNT'];
						$this->overall['SALES'] += $value['TOTAL_SALES'];
						$this->overall['TARGET'] += $value['TOTAL_TARGET'];
						for($i=$stFortDate;$i<=$endFortDate;$i++)
							$this->overall['DAYWISE_AMOUNT'][$i] += $value['DAYWISE_AMOUNT'][$i];
					}
					$this->overall['ACHIEVEMENT'] = $misGenerationhandlerObj->calculateTargetAchievement($this->overall['SALES'], $this->overall['TARGET'], $this->monthName, $this->yearName,  $this->fortnight);
					$misGenerationhandlerObj->sort_locationwise($this->location);

					// Blank cells Handling in Interface
					if($given_dt < $curr_dt)
						$this->blank_cells_past = 1;
					else if($given_dt == $curr_dt){
						$this->blank_cells_curr = 1;
						$this->dt_curr = date('d');
					}
				}
				else if($this->report_type == "TEAM")
				{
					$users = array();
					foreach($this->detail as $key=>$val)
						$users[] = $this->detail[$key]['USERNAME'];
					$names = $agentAllocDetailsObj->getValidUsersForSalesTarget();
					if(count($names['BOSS'])!=1){
						$this->overall_sales_head_check = 1;
						$this->setTemplate('crmHandledRevenueTeamWise');
						return;
					}
					if($misGenerationhandlerObj->isPrivilege_P_MG($this->agentName,'Y'))
						$hierarchyObj = new hierarchy($names['BOSS'][0]);
					$this->hierarchy = $hierarchyObj->getHierarchyData($users);
					foreach($this->hierarchy as $v)
						$hlist[] = $v['USERNAME'];

					$this->team = array();
					foreach($this->hierarchy as $key=>$value){
						if($this->detail[$value['USERNAME']]['TOTAL_AMOUNT'])
							$this->team[$value['USERNAME']]['IS_HYPERLINK'] = 1;
					}
					foreach($this->hierarchy as $key=>$value){
						$h_obj = new hierarchy($value['USERNAME']);
						$h = $h_obj->getHierarchyData($hlist);

						foreach($h as $k=>$val){
							$this->team[$value['USERNAME']]['TOTAL_AMOUNT'] += $this->detail[$val['USERNAME']]['TOTAL_AMOUNT'];
							$this->team[$value['USERNAME']]['SALES_WITHOUT_TAX'] += $this->detail[$val['USERNAME']]['SALES_WITHOUT_TAX'];
							for($i=$stFortDate;$i<=$endFortDate;$i++)
								$this->team[$value['USERNAME']]['AMOUNT'][$i] += $this->detail[$val['USERNAME']]['AMOUNT'][$i];
						}
						$this->team[$value['USERNAME']]['FINAL_TARGET'] = $this->detail[$value['USERNAME']]['FINAL_TARGET'];
						$this->team[$value['USERNAME']]['TARGET_ACHIEVEMENT'] = $misGenerationhandlerObj->calculateTargetAchievement($this->team[$value['USERNAME']]['SALES_WITHOUT_TAX'], $this->team[$value['USERNAME']]['FINAL_TARGET'], $this->monthName, $this->yearName,  $this->fortnight);
					}
				}

			}

			if($this->report_format == "XLS")
			{
				if($this->report_type == "LOCATION")
					$headerLabel.="Center/Executive\tEmployee_ID\tTarget\tSales(without_tax)\tTarget_Achievement\t";
				else if($this->report_type == "TEAM" && $this->report_content == "REVENUE")
					$headerLabel.="Manager/Supervisor/Executive\tEmployee_ID\tTarget\tSales(without_tax)\tTarget_Achievement\t";
				else if($this->report_type == "TEAM" && $this->report_content == "TICKET")
				{
					$headerLabel.="Manager/Supervisor/Executive\tEmployee_ID\t";
					foreach ($this->ddarr as $dd) {
						$headerLabel .= "$dd\t";
					}
					$headerLabel .= "Total\tTicket_Size\n";
				}

				if($this->report_content != "TICKET")
				{
					for($i=$stFortDate; $i<=$endFortDate; $i++)
						$headerLabel.=$i."\t";
					$headerLabel.="Total_Sales\n\n";
				}

				if($this->report_type == "LOCATION"){
					foreach($this->location as $loc=>$info)
					{
						$dataset.=$loc."\n";
						foreach($info['USERNAME'] as $val)
						{
							$dataset.=$val."\t";
							$dataset.=$this->empDetailArr[$val]."\t";
							$dataset.=$this->detail[$val]['INDIVIDUAL_TARGET']."\t";
							$dataset.=$this->detail[$val]['SALES_WITHOUT_TAX']."\t";
							$dataset.=$this->detail[$val]['TARGET_ACHIEVEMENT'][0]."\t";
							for($i=$stFortDate;$i<=$endFortDate;$i++)
								$dataset.=$this->detail[$val]['AMOUNT'][$i]."\t";
							$dataset.=$this->detail[$val]['TOTAL_AMOUNT']."\t";
							$dataset.="\n";
						}
						$dataset.=$loc."_TOTAL\t\t".$info['TOTAL_TARGET']."\t".$info['TOTAL_SALES']."\t".$info['ACHIEVEMENT'][0]."\t";
						for($i=$stFortDate;$i<=$endFortDate;$i++)
							$dataset.=$info['DAYWISE_AMOUNT'][$i]."\t";
						$dataset.=$info['TOTAL_AMOUNT']."\n\n";
					}
					$dataset.="GRAND_TOTAL\t\t".$this->overall['TARGET']."\t".$this->overall['SALES']."\t".$this->overall['ACHIEVEMENT'][0]."\t";
					for($i=$stFortDate;$i<=$endFortDate;$i++)
						$dataset.=$this->overall['DAYWISE_AMOUNT'][$i]."\t";
					$dataset.=$this->overall['AMOUNT'];
				}
				else if($this->report_type == "TEAM" && $this->report_content == "REVENUE"){
					foreach($this->hierarchy as $key=>$val)
					{
						$dataset.=$val['USERNAME']."\t";
						$dataset.=$this->empDetailArr[$val['USERNAME']]."\t";
						$dataset.=$this->team[$val['USERNAME']]['FINAL_TARGET']."\t";
						$dataset.=$this->team[$val['USERNAME']]['SALES_WITHOUT_TAX']."\t";
						$dataset.=$this->team[$val['USERNAME']]['TARGET_ACHIEVEMENT'][0]."\t";
						for($i=$stFortDate;$i<=$endFortDate;$i++)
							$dataset.=$this->team[$val['USERNAME']]['AMOUNT'][$i]."\t";
						$dataset.=$this->team[$val['USERNAME']]['TOTAL_AMOUNT']."\t";
						$dataset.="\n";
					}
				}
				else if($this->report_type == "TEAM" && $this->report_content == "TICKET"){
					foreach ($this->hierarchyData as $val) {
						$dataset .= $val['USERNAME']."\t";
						$dataset .= $this->empDetailArr[$val['USERNAME']]."\t";
						foreach ($this->ddarr as $dd) {
							$dataset .= $this->teamwiseData[$val['USERNAME']][$dd]."\t";
						}
						$dataset .= $this->teamwiseData[$val['USERNAME']]['TOTAL']."\t";
						$dataset .= $this->teamwiseData[$val['USERNAME']]['TICKET_SIZE']."\n";
					}
				}
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename=Sales_Credit_Details_".$this->yearName."_".$this->monthName.".xls");
				header("Pragma: no-cache");
				header("Expires: 0");
				$finalData=$headerLabel.$dataset;
				echo $finalData;
				die();
			}
			else
			{
				if(($this->report_type == "LOCATION") && ($this->report_content == "REVENUE"))
				{
					$this->setTemplate('crmHandledRevenueLocationWise');
					return;
				}
				if(($this->report_type == "TEAM") && ($this->report_content == "REVENUE"))
				{
					$this->setTemplate('crmHandledRevenueTeamWise');
					return;
				}
				if(($this->report_type == "TEAM") && ($this->report_content == "TICKET"))
				{
					$this->setTemplate('crmHandledRevenueTicketWise');
					return;
				}

			}
		}
		$this->setTemplate('crmHandledRevenueMis');
	}

	/*
	This function is called when we click on link titled as "Field Sales Follow-up Status MIS"
	*/
	public function executeFieldSalesFollowUpStatusMis(sfWebRequest $request)
	{
		$this->cid = $request->getParameter('cid');
		$agentAllocObj = new AgentAllocationDetails();
		$misObj = new misGenerationhandler();
		$fsObj = new FieldSalesFollowUpStatusMis();
		$this->agentName = $agentAllocObj->fetchAgentName($this->cid);

		if(!$misObj->get_SLHDO()){
			$this->overall_sales_head_check = 1;
			$this->setTemplate('fieldSalesFollowUpStatusMis');
			return;
		}
		if($misObj->isPrivilege_P_MG($this->agentName))
			$hierarchyObj = new hierarchy($misObj->get_SLHDO());
		else
			$hierarchyObj = new hierarchy($this->agentName);

		// Hierarchical Data
		$reporters = $hierarchyObj->getAllReporters();
		$this->hierarchy = $hierarchyObj->getHierarchyData($reporters);

		// Background color
		$this->background_color = $fsObj->getBackgroundColor($reporters);

		// Individual Agent Data
		$allocationBucket = $fsObj->fetchAllocationBucketCount($reporters);
		$this->allocationBucket = $allocationBucket[0];
		$this->allocationBucket_free = $allocationBucket[1];
		$this->todayFollowUps = $fsObj->fetchTodayFollowUpsCount($reporters);
		$this->yesterdayFollowUps = $fsObj->fetchYesterdayFollowUpsCount($reporters);
		$this->dayBeforeYesterdayFollowUps = $fsObj->fetchDayBeforeYesterdayFollowUpsCount($reporters);
		$this->earlierThanDayBeforeYesterdayFollowUps = $fsObj->fetchEarlierThanDayBeforeYesterdayFollowUpsCount($reporters);
		$this->totalPendingFollowUps= $fsObj->fetchTotalPendingFollowUpsCount($reporters, $this->todayFollowUps, $this->yesterdayFollowUps, $this->dayBeforeYesterdayFollowUps, $this->earlierThanDayBeforeYesterdayFollowUps);
		$this->futureFollowUps = $fsObj->fetchFutureFollowUpsCount($reporters);

		// Team-wise Data
		$reportersOfAgent = $fsObj->fetchReportersOfAgent($reporters);
		$this->teamwise = $fsObj->fetchTeamWiseData($reportersOfAgent, $this->todayFollowUps, $this->yesterdayFollowUps, $this->dayBeforeYesterdayFollowUps, $this->earlierThanDayBeforeYesterdayFollowUps, $this->totalPendingFollowUps, $this->futureFollowUps);
		$this->todayFollowUps_t = $this->teamwise[0];
		$this->yesterdayFollowUps_t = $this->teamwise[1];
		$this->dayBeforeYesterdayFollowUps_t = $this->teamwise[2];
		$this->earlierThanDayBeforeYesterdayFollowUps_t = $this->teamwise[3];
		$this->totalPendingFollowUps_t = $this->teamwise[4];
		$this->futureFollowUps_t = $this->teamwise[5];

		// Visibilty check of agents and their hierarchy based on allocation bucket
		$this->is_visible = $fsObj->visibiltyCheck($reportersOfAgent, $this->allocationBucket);

		$this->setTemplate('fieldSalesFollowUpStatusMis');
	}
	/*
	This function is called when we click on a hyperlink on result screen 1 of Field Sales Follow-up Status MIS
	*/
	public function executeFieldSalesFollowUpStatusMisResultScreen2(sfWebRequest $request)
	{
		$this->cid = $request->getParameter('cid');
		$this->exec = $request->getParameter('exec');
		$this->col_id = $request->getParameter('column');

		$agentAllocObj = new AgentAllocationDetails();
		$misObj = new misGenerationhandler();
		$fsObj = new FieldSalesFollowUpStatusMis();

		$this->agentName = $agentAllocObj->fetchAgentName($this->cid);

		if($misObj->isPrivilege_P_MG($this->agentName))
			$hierarchyObj = new hierarchy($misObj->get_SLHDO());
		else
			$hierarchyObj = new hierarchy($this->agentName);

		$reporters = $hierarchyObj->getAllReporters();
		if(in_array($this->exec, $reporters)){
			$this->child = 1;
		} else {
			$this->child = 0;
		}

		//$fsObj = new FieldSalesFollowUpStatusMis();

		if($this->col_id  == 0 || $this->col_id  == 1){
			if($this->col_id == 0){
				$this->column = "All";
			} elseif($this->col_id == 1) {
				$this->column = "Allocation Bucket";
			}
			$this->allotedProfiles = $fsObj->getAllotedProfiles($this->exec);
			$this->allotedProfiles = $fsObj->getFreeProfileDataForExecutive($this->allotedProfiles, $this->exec);
			$this->allotedProfiles = $fsObj->sortProfileArrayByFollowUpDate($this->allotedProfiles);
			$this->count = count($this->allotedProfiles);

		} elseif ($this->col_id  == 2){ // Todays Followup
			$this->column = "Today's Follow-ups";
			$this->start_date = date("Y-m-d 00:00:00");
			$this->end_date = date("Y-m-d 23:59:59");
			$this->allotedProfiles = $fsObj->generateProfileData($this->exec, $this->start_date, $this->end_date);
			$this->count = count($this->allotedProfiles);

		} elseif ($this->col_id  == 3) { // Yesterdays Followups
			$this->column = "Yesterday's Follow-ups";
			$this->start_date = date("Y-m-d H:i:s", (strtotime(date("Y-m-d 00:00:00")) - 24*60*60));
			$this->end_date = date("Y-m-d H:i:s", (strtotime($this->start_date) + (24*60*60 -1)));
			$this->allotedProfiles = $fsObj->generateProfileData($this->exec, $this->start_date, $this->end_date);
			$this->count = count($this->allotedProfiles);

		} elseif ($this->col_id  == 4) { // Day before Yesterdays Followups
			$this->column = "Day Before Yesterday's Follow-ups";
			$this->start_date = date("Y-m-d H:i:s", (strtotime(date("Y-m-d 00:00:00")) - 2*24*60*60));
			$this->end_date = date("Y-m-d H:i:s", (strtotime($this->start_date) + (24*60*60 -1)));
			$this->allotedProfiles = $fsObj->generateProfileData($this->exec, $this->start_date, $this->end_date);
			$this->count = count($this->allotedProfiles);

		} elseif ($this->col_id  == 5) { // Earlier than Day Before Yesterdays Followups
			$this->column = "Earlier than Day Before Yesterday's Follow-ups";
			$this->start_date = date("Y-m-d H:i:s", (strtotime(date("Y-m-d 00:00:00")) - 2*24*60*60 - 1));
			$this->allotedProfiles = $fsObj->generateProfileData($this->exec, $this->start_date);
			$this->count = count($this->allotedProfiles);

		} elseif ($this->col_id  == 6) { // Total pending FollowUps
			$this->column = "Total Pending Follow-ups";
			$this->start_date = date("Y-m-d 23:59:59");
			$this->allotedProfiles = $fsObj->generateProfileData($this->exec, $this->start_date);
			$this->count = count($this->allotedProfiles);

		} elseif ($this->col_id  == 7) { // Future Followups
			$this->column = "Future Follow-ups";
			$this->allotedProfiles = $fsObj->getFutureFollowUps($this->exec);
			$this->allotedProfiles = $fsObj->sortProfileArrayByFollowUpDate($this->allotedProfiles);
			$this->count = count($this->allotedProfiles);

		}

		unset($fsObj);

		$this->setTemplate('fieldSalesFollowUpStatusMisResultScreen2');
	}
	/*
	This function is called when we click on link titled as "Renewal Follow-up Status MIS"
	*/
	public function executeRenewalFollowUpStatusMis(sfWebRequest $request)
	{
		$this->cid = $request->getParameter('cid');
		$agentAllocObj = new AgentAllocationDetails();
		$misObj = new misGenerationhandler();
		$fsObj = new FieldSalesFollowUpStatusMis();
		$rnObj = new RenewalFollowUpStatusMis();
		$this->agentName = $agentAllocObj->fetchAgentName($this->cid);

		if(!$misObj->get_SLHDO()){
			$this->overall_sales_head_check = 1;
			$this->setTemplate('renewalFollowUpStatusMis');
			return;
		}
		if($misObj->isPrivilege_P_MG_TRNG($this->agentName))
			$hierarchyObj = new hierarchy($misObj->get_SLHDO());
		else
			$hierarchyObj = new hierarchy($this->agentName);

		$reporters = $hierarchyObj->getAllReporters();
		$reportersOfAgent = $fsObj->fetchReportersOfAgent($reporters);

		// Allocation Bucket
		$allocationBucket = $fsObj->fetchAllocationBucketCount($reporters);
		$this->allocationBucket = $allocationBucket[0];

		// Visibilty check of agents and their hierarchy based on allocation bucket
		$this->is_visible = $fsObj->visibiltyCheck($reportersOfAgent, $this->allocationBucket);
		$reporters = array();
		foreach($this->is_visible as $agent=>$vis){
			if($vis==1)
				$reporters[] = $agent;
		}
		if($reporters && is_array($reporters)){
			$this->hierarchy = $hierarchyObj->getHierarchyData($reporters);
			$this->background_color = $fsObj->getBackgroundColor($reporters);
			$this->profilesWithoutFollowups = $rnObj->fetchProfilesWithoutFollowupDateCount($reporters);

			$this->expireRangeArr = array(array(0,0), array(1,3), array(4,7), array(8,14), array(15,21), array(22,29));
			$res = $rnObj->fetchRenewalProfilesCount($reporters, $this->expireRangeArr);
			$this->renewalProfiles = $res[0];
			$this->renewalProfilesNotFollowedup = $res[1];
			$this->renewalProfilesNotFollowedupRangeWise = $res[2];

			// Team-wise Data
			$reportersOfAgent = $fsObj->fetchReportersOfAgent($reporters);
			$teamwise = $rnObj->fetchTeamWiseData($reportersOfAgent, $this->profilesWithoutFollowups, $this->renewalProfiles, $this->renewalProfilesNotFollowedup, $this->renewalProfilesNotFollowedupRangeWise);
			$this->profilesWithoutFollowups_t = $teamwise[0];
			$this->renewalProfiles_t = $teamwise[1];
			$this->renewalProfilesNotFollowedup_t = $teamwise[2];
			$this->renewalProfilesNotFollowedupRangeWise_t = $teamwise[3];			
		}
		$this->setTemplate('renewalFollowUpStatusMis');
	}
	/*
	This function is called when we click on a hyperlink on result screen 1 of Renewal Follow-up Status MIS
	*/
	public function executeRenewalFollowUpStatusMisResultScreen2(sfWebRequest $request)
	{
		$this->cid = $request->getParameter('cid');
		$this->exec = $request->getParameter('exec');
		$this->col_id = $request->getParameter('column');
		$fsObj = new FieldSalesFollowUpStatusMis();
		$rnObj = new RenewalFollowUpStatusMis();

		$header = array("Renewal Profiles Not Followed-up yet","Profiles without Follow-up Date","Renewal Profiles","Renewal Profiles Not Followed-up yet","Expiring Today","Expiring in next 1-3 days","Expiring in next 4-7 days","Expiring in next 8-14 days","Expiring in next 15-21 days","Expiring in next 22-29 days");
		$this->column = $header[$this->col_id];
		$this->expireRangeArr = array(array(0,0), array(1,3), array(4,7), array(8,14), array(15,21), array(22,29));

		if($this->col_id==1)
			$this->profileData = $rnObj->fetchProfileDataWithoutFollowupDate($this->exec);
		else
			$this->profileData = $rnObj->fetchProfileData($this->exec, $this->expireRangeArr, $this->col_id);
		
		$this->count = count($this->profileData);
		
		$this->setTemplate('renewalFollowUpStatusMisResultScreen2');
	}

        /*
        This function is called when we click on Field Sales Executive Performance MIS or its corresponding Jump link. It also called when we submit the dates and Result Screen 1 is created.
        */
        public function executeFieldSalesExecutivePerformanceMis(sfWebRequest $request)
        {
                $this->cid = $request->getParameter('cid');
                $agentAllocDetailsObj = new AgentAllocationDetails();
                $this->agentName = $agentAllocDetailsObj->fetchAgentName($this->cid);

                if($request->getParameter("submit") || $request->getParameter("outside"))       //If form is submitted or jump is clicked
                {
                        $misGenerationhandlerObj = new misGenerationhandler;
                        if(!$misGenerationhandlerObj->get_SLHDO())
                                $this->errorMsg = "Please give 'Sales Head - Overall' privilege to one user.";

                        $names = $agentAllocDetailsObj->getValidUsersForFieldSalesTarget();
                        if($misGenerationhandlerObj->isPrivilege_P_MG_TRNG($this->agentName))
                        {
                                $boss = $names['BOSS'];
                                if(count($boss)!=1)
                                        $this->errorMsg = "Please give 'Sales Head - Overall' privilege to one user.";
                                else
                                        $this->agentName = $boss[0];
                        }
                        if($request->getParameter("submit"))            //If form is submitted
                        {
                                $formArr = $request->getParameterHolder()->getAll();

                                if($formArr["range_format"]=="MY")      //If month and year is selected
                                {
                                        $start_date = $formArr["yearValue"]."-".$formArr["monthValue"]."-01";
                                        $end_date = $formArr["yearValue"]."-".$formArr["monthValue"]."-".date("t",strtotime($start_date));
                                        $this->displayDate = date("F Y",strtotime($start_date));
                                }
                                else            //If date ranges are selected
                                {
                                        $formArr["date1_dateLists_month_list"]++;
                                        $formArr["date2_dateLists_month_list"]++;
                                        $start_date = $formArr["date1_dateLists_year_list"]."-".$formArr["date1_dateLists_month_list"]."-".$formArr["date1_dateLists_day_list"];
                                        $end_date = $formArr["date2_dateLists_year_list"]."-".$formArr["date2_dateLists_month_list"]."-".$formArr["date2_dateLists_day_list"];
                                        $start_date = date("Y-m-d",strtotime($start_date));
                                        $end_date = date("Y-m-d",strtotime($end_date));
                                        $this->displayDate = date("jS F Y",strtotime($start_date))." To ".date("jS F Y",strtotime($end_date));
                                }
                                if($start_date>$end_date)
                                        $this->errorMsg = "Invalid Date Selected";
                                elseif(ceil((strtotime($end_date)-strtotime($start_date))/(24*60*60))>=45)
                                        $this->errorMsg = "More than 45 days selected in range";
                        }
                        else                    //If Jump is clicked
                        {
                                $end_date = date("Y-m-d");
                                $start_date = date("Y-m")."-01";
                                $this->displayDate = date("jS F Y",strtotime($start_date))." To ".date("jS F Y",strtotime($end_date));
                        }
                        if(!$this->errorMsg)    //If no error message then submit the page
                        {
                                $ddarr = GetDateArrays::getDateArrayForTemplate($start_date,$end_date);
                                $this->range_format = $formArr["range_format"];
                                $this->start_date = $start_date;
                                $this->end_date = $end_date;

                                $hierarchyObj = new hierarchy($this->agentName);
                                $allReporters = $hierarchyObj->getAllReporters();

                                // Get Executives who were given field sales allocation from '30 days prior to Start Date' to 'Selected End Date' (regardless of whether they are active)
                                $fsepmObj = new FieldSalesExecutivePerformanceMis($allReporters,$start_date,$end_date,$ddarr);
                                $this->background_color = $fsepmObj->getBackgroundColor($allReporters);
                                $allReporters = $fsepmObj->getEligibleExecutives($allReporters, $this->start_date, $this->end_date);
                                unset($fsepmObj);
                                if($ddarr && is_array($ddarr) && $allReporters)
                                {
                                        $this->hierarchyData = $hierarchyObj->getHierarchyData($allReporters);
                                        $fsepmObj = new FieldSalesExecutivePerformanceMis($allReporters,$start_date,$end_date,$ddarr);
                                	$this->emp_id_arr = $fsepmObj->getExecEmployeeID($allReporters);
                                	$filterDocumentVerification = true;
                                        $fsepmObj->generateFreshVisitsData($filterDocumentVerification);
                                        $fsepmObj->generateProfilesWhichPaidData();
                                        $fsepmObj->generateSalesData();
                                        $this->individual_result = $fsepmObj->getResultArr();
                                        $this->individual_execWiseAndDayWiseSummation = $fsepmObj->generateExecutiveWiseAndDayWiseSummation();
                                        $fsepmObj->generateTeamWiseData($ddarr, $allReporters);
                                        $this->execWiseAndDayWiseSummation = $fsepmObj->generateExecutiveWiseAndDayWiseSummation(); // Team-wise data
                                        $this->result = $fsepmObj->getResultArr(); // Team-wise data
                                        if($formArr["report_format"]=="XLS" && $this->result && is_array($this->result))
                                        {   $monthArr =  array( '01' => 'January',
                                                        '02' => 'February',
                                                        '03' => 'March',
                                                        '04' => 'April',
                                                        '05' => 'May',
                                                        '06' => 'June',
                                                        '07' => 'July',
                                                        '08' => 'August',
                                                        '09' => 'September',
                                                        '10' => 'October',
                                                        '11' => 'November',
                                                        '12' => 'December',);
                                                if($formArr["range_format"]=="MY"){
                                                        $string .= "For_".$monthArr[$formArr["monthValue"]]."-".$formArr["yearValue"];
                                                } else {
                                                        $string .= $start_date."_to_".$end_date;
                                                }
                                                $xlData = $fsepmObj->generateDataForXLS($this->hierarchyData,$this->execWiseAndDayWiseSummation,$this->emp_id_arr);
                                                header("Content-Type: application/vnd.ms-excel");
                                                header("Content-Disposition: attachment; filename=Field_Sales_Executive_Performance_MIS_".$string.".xls");
                                                header("Pragma: no-cache");
                                                header("Expires: 0");
                                                echo $xlData;
                                                die;
                                        }
                                        unset($fsepmObj);
                }

                                $this->setTemplate('fieldSalesExecutivePerformanceMisResultScreen1');
                        }
                }
                else            //If Field Sales Executive Performance MIS link is clicked in mis
                {
                        $this->startMonthDate = "01";
                        $this->todayDate = date("d");
                        $this->todayMonth = date("m");
                        $this->todayYear = date("Y");
                        $this->rangeYear = date("Y");
                        $this->dateArr = GetDateArrays::getDayArray();
                        $this->monthArr = GetDateArrays::getMonthArray();
                        $this->yearArr = array();
                        $dateArr = GetDateArrays::generateDateDataForRange('2014',($this->todayYear));
                        foreach(array_keys($dateArr) as $key=>$value)
                                $this->yearArr[] = array('NAME'=>$value, 'VALUE'=>$value);
                }
        }

        /*
        This function is called when we click on a hyperlink on result screen1 of Field Sales Executive Performance MIS
        */
        public function executeFieldSalesExecutivePerformanceMisResultScreen2(sfWebRequest $request)
        {
                $this->cid = $request->getParameter('cid');
                $agentAllocDetailsObj   =new AgentAllocationDetails();
                $this->agentName =$agentAllocDetailsObj->fetchAgentName($this->cid);
                unset($agentAllocDetailsObj);

                $this->displayDate = date("jS F Y",strtotime($request->getParameter('startDate')))." To ".date("jS F Y",strtotime($request->getParameter('endDate')));
                $this->exec = $request->getParameter('exec');
                $date = $request->getParameter('date');

                if($request->getParameter('range_format') != 'MY'){
                        $this->header = "For the period ".$this->displayDate;
                } else {
                        $this->header = "For the month of ".date("F Y",strtotime($request->getParameter('startDate')));
                }

                if($date == "TOTAL")
                {
                        $start_date = $request->getParameter('startDate');
                        $end_date = $request->getParameter('endDate');
                        $this->currentDateRange = $this->displayDate;
                }
                else
                {
                        $start_date = $date;
                        $end_date = $date;
                        $this->currentDateRange = date("jS F Y",strtotime($date));
                }
                $this->details = $request->getParameter('details');

                $fsepmObj = new FieldSalesExecutivePerformanceMis(array($this->exec),$start_date,$end_date);
                $fsepmObj->setDateRange($request->getParameter('startDate'), $request->getParameter('endDate'));
                if($this->details == "VD")
                {
                        $fsepmObj->getFreshVisitsDataDetails();
                        $this->execDetailsArr1 = $fsepmObj->getExecDetailsArr();
                        $fsepmObj->sort_execDetailsArr($this->execDetailsArr1);
                }
                elseif($this->details == "PP" || $this->details == "SL")
                {
                        $fsepmObj->getProfilesPaymentDataDetails();
                        $this->execDetailsArr2 = $fsepmObj->getExecDetailsArr();
                        $fsepmObj->sort_execDetailsArr($this->execDetailsArr2);
                }
                elseif($this->details == "ALL")
                {
                        $fsepmObj->getFreshVisitsDataDetails();
                        $this->execDetailsArr1 = $fsepmObj->getExecDetailsArr();
                        $fsepmObj->sort_execDetailsArr($this->execDetailsArr1);

                        $fsepmObj->setExecDetailsArr();
                        $fsepmObj->getProfilesPaymentDataDetails();
                        $this->execDetailsArr2 = $fsepmObj->getExecDetailsArr();
                        $fsepmObj->sort_execDetailsArr($this->execDetailsArr2);
                }
                unset($fsepmObj);
        }

        /*
        This function is called when we click on Field Sales Executive Efficiency MIS or its corresponding Jump link. It also called when we submit the dates and Result Screen 1 is created.
        */
        public function executeFieldSalesExecutiveEfficiencyMis(sfWebRequest $request)
        {
		ini_set('max_execution_time',100);
                $pattern1 = "/^([a-z0-9])+$/";
                $pattern2 = "/^([0-9])+$/";
                $pattern3 = "/^([A-Z])+$/";
                if($request->getParameter("submit") || $request->getParameter("outside"))       //If form is submitted or jump is clicked
                {
                        if(!preg_match($pattern1,$request->getParameter('cid')))
                        {
                                $this->errorMsg = "Invalid cid - ".$request->getParameter('cid');
                        }
                        else
                        {
                                $this->cid=$request->getParameter('cid');
                                $agentAllocDetailsObj   =new AgentAllocationDetails();
                                $this->agentName =$agentAllocDetailsObj->fetchAgentName($this->cid);

                                $misGenerationhandlerObj = new misGenerationhandler;

                                if(!$misGenerationhandlerObj->get_SLHDO()){
                                        $this->errorMsg = "Please give 'Sales Head - Overall' privilege to one user.";
                                }
                                $names = $agentAllocDetailsObj->getValidUsersForFieldSalesTarget();
                                if($misGenerationhandlerObj->isPrivilege_P_MG_TRNG($this->agentName))
                                {
                                        $boss = $names['BOSS'];
                                        if(count($boss)!=1)
                                                $this->errorMsg = "Please give 'Sales Head - Overall' privilege to one user.";
                                        else
                                                $this->agentName = $boss[0];
                                }
                                unset($agentAllocDetailsObj);
                                unset($misGenerationhandlerObj);

                                if($request->getParameter("submit"))            //If form is submitted
                                {
                                        $formArr = $request->getParameterHolder()->getAll();

                                        if(!preg_match($pattern2,$formArr["monthValue"]) || !preg_match($pattern2,$formArr["yearValue"]) || !preg_match($pattern2,$formArr["date1_dateLists_day_list"]) || !preg_match($pattern2,$formArr["date1_dateLists_month_list"]) || !preg_match($pattern2,$formArr["date1_dateLists_year_list"]) || !preg_match($pattern2,$formArr["date2_dateLists_day_list"]) || !preg_match($pattern2,$formArr["date2_dateLists_month_list"]) || !preg_match($pattern2,$formArr["date2_dateLists_year_list"]) || !preg_match($pattern3,$formArr["range_format"]) || !preg_match($pattern3,$formArr["report_format"]))
                                        {
                                                $this->errorMsg = "Invalid parameters";
                                        }
                                        else
                                        {
                                                if($formArr["range_format"]=="MY")      //If month and year is selected
                                                {
                                                        $start_date = $formArr["yearValue"]."-".$formArr["monthValue"]."-01";
                                                        $end_date = $formArr["yearValue"]."-".$formArr["monthValue"]."-".date("t",strtotime($start_date));
                                                        $this->displayDate = date("F Y",strtotime($start_date));
                                                }
                                                else            //If date ranges are selected
                                                {       // incrementing month value by one for counteracting plugin used 
                                                        $formArr["date1_dateLists_month_list"]++;
                                                        $formArr["date2_dateLists_month_list"]++;
                                                        $start_date = $formArr["date1_dateLists_year_list"]."-".$formArr["date1_dateLists_month_list"]."-".$formArr["date1_dateLists_day_list"];
                                                        $end_date = $formArr["date2_dateLists_year_list"]."-".$formArr["date2_dateLists_month_list"]."-".$formArr["date2_dateLists_day_list"];
                                                        $start_date = date("Y-m-d",strtotime($start_date));
                                                        $end_date = date("Y-m-d",strtotime($end_date));
                                                        $this->displayDate = date("jS F Y",strtotime($start_date))." To ".date("jS F Y",strtotime($end_date));
                                                }

                                                if($start_date>$end_date){
                                                        $this->errorMsg = "Invalid Date Selected";
                                                }
                                        }
                                }
                                else                    //If Jump is clicked
                                {
                                        if(!preg_match($pattern1,$request->getParameter('cid')))
                                        {
                                                $this->errorMsg = "Invalid cid - ".$request->getParameter('cid');
                                        }
                                        else
                                        {
                                                $end_date = date("Y-m-d");
                                                $start_date = date("Y-m")."-01";
                                                $this->displayDate = date("jS F Y",strtotime($start_date))." To ".date("jS F Y",strtotime($end_date));
                                        }
                                }
                                if(!$this->errorMsg)    //If no error message then submit the page
                                {
                                        $ddarr = GetDateArrays::getDateArrayForTemplate($start_date,$end_date);
                                        $this->range_format = $formArr["range_format"];
					$this->report_format =$formArr["report_format"];	
                                        $this->start_date = $start_date;
                                        $this->end_date = $end_date;

					// Memcache Key based on Form inputs
					$this->dateFormat =$this->start_date."_".$this->end_date;
				      	$memcacheObj = JsMemcache::getInstance();
				      	$this->memcacheKey = $this->range_format."_".$this->dateFormat."_".$this->report_format;
					if($this->agentName)
						$this->memcacheKey .="_".$this->agentName;
				      	$memKeySet = $memcacheObj->get($this->memcacheKey);
					$params =array('startDate'=>$this->start_date,'endDate'=>$this->end_date,'agentName'=>$this->agentName);
					$params['memKeySet'] = $this->memcacheKey; 	
                                        //echo $this->memcacheKey;
                                        //print_r($params);die;

                                        if($this->range_format != 'MY'){
                                                $this->header = "For the period ".$this->displayDate;
                                        } else {
                                                $this->header = "For the month of ".$this->displayDate;
                                        }
				//$memKeySet='';
			      	if($memKeySet == 'C')
			      	{
					//echo "1";
					$this->computing = true;
					$this->setTemplate('computationFieldSalesExecutiveEfficiencyMisResultScreen1');
				}
      				elseif(is_array($memKeySet))
      				{
					//echo "2";
				        $this->groupData = $memKeySet;
					//print_r($this->groupData);die;
				        $this->computing = false;
					$xlData                                 =$this->groupData['xlData'];
					$this->hierarchyData 			=$this->groupData['hierarchyData'];
					$this->background_color 		=$this->groupData['background_color'];
                                        $this->agentAllotedProfileArray		=$this->groupData['agentAllotedProfileArray'];
                                        $this->agentAllotedProfileArray		=$this->groupData['agentAllotedProfileArray'];
                                        $this->allotedProfileCount		=$this->groupData['allotedProfileCount'];
                                        $this->agentAllotedProfileFreshVisitArray=$this->groupData['agentAllotedProfileFreshVisitArray'];
                                        $this->originalFreshVisitCount		=$this->groupData['originalFreshVisitCount'];
                                        $this->agentAllotedProfilePaidArray	=$this->groupData['agentAllotedProfilePaidArray'];
                                        $this->originalPaidProfileCount		=$this->groupData['originalPaidProfileCount'];
                                        $this->originalTotalSales		=$this->groupData['originalTotalSales'];
                                        $this->newAllotedProfileCount		=$this->groupData['newAllotedProfileCount'];
                                        $this->freshVisitCount			=$this->groupData['freshVisitCount'];
                                        $this->paidProfileCount			=$this->groupData['paidProfileCount'];
                                        $this->totalSales			=$this->groupData['totalSales'];
                                        $this->freshVisitPercentage		=$this->groupData['freshVisitPercentage'];
                                        $this->visitPaidPercentage		=$this->groupData['visitPaidPercentage'];
                                        $this->allotedPaidPercentage		=$this->groupData['allotedPaidPercentage'];
                                        $this->ticketSize			=$this->groupData['ticketSize'];
                                        if($formArr["report_format"]=="XLS")
                                        {       
							$monthArr =  array( '01' => 'January',
                                                        '02' => 'February',
                                                        '03' => 'March',
                                                        '04' => 'April',
                                                        '05' => 'May',
                                                        '06' => 'June',
                                                        '07' => 'July',
                                                        '08' => 'August',
                                                        '09' => 'September',
                                                        '10' => 'October',
                                                        '11' => 'November',
                                                        '12' => 'December',);
                                        	if($formArr["range_format"]=="MY"){
                                        	        $string .= "For_".$monthArr[$formArr["monthValue"]]."-".$formArr["yearValue"];
                                        	} else {
                                        	        $string .= $start_date."_to_".$end_date;
                                        	}
                                        	//$xlData = $fsempObj->generateDataForXLSEfficiency($agents,$this->newAllotedProfileCount, $this->freshVisitCount, $this->freshVisitPercentage, $this->paidProfileCount, $this->visitPaidPercentage, $this->allotedPaidPercentage, $this->totalSales, $this->ticketSize);
                                        	header("Content-Type: application/vnd.ms-excel");
                                        	header("Content-Disposition: attachment; filename=Field_Sales_Executive_Efficiency_MIS_".$string.".xls");
                                        	header("Pragma: no-cache");
                                        	header("Expires: 0");
                                        	echo $xlData;
                                        	die();
                                	}
                                	unset($fsempObj);
	                                $this->setTemplate('fieldSalesExecutiveEfficiencyMisResultScreen1');
				}
			      	elseif($memKeySet == '')
      				{
					//echo "3";
				        $this->computing = true;
				        $memcacheObj->set("$this->memcacheKey","C");
				        $memcacheObj->set("MIS_FS_PARAMS_KEY",$params);
					$params =$memcacheObj->get("MIS_FS_PARAMS_KEY");
				        $filePath = JsConstants::$cronDocRoot."/symfony cron:cronFieldSalesExecutiveEfficiencyMis > /dev/null &";
				        $command = JsConstants::$php5path." ".$filePath;
					//echo $command;
				        passthru($command);
				        $this->setTemplate('computationFieldSalesExecutiveEfficiencyMisResultScreen1');
      				}
                        	}// end of -errorMsg 
               		}
        	}
                else            //If Field Sales Executive Performance MIS link is clicked in mis
                {
                        if(!preg_match($pattern1,$request->getParameter('cid')))
                        {
                                $this->errorMsg = "Invalid cid - ".$request->getParameter('cid');
                        }
                        else
                        {
                                $this->cid=$request->getParameter('cid');
                                $agentAllocDetailsObj   =new AgentAllocationDetails();
                                $this->agentName =$agentAllocDetailsObj->fetchAgentName($this->cid);
                                unset($agentAllocDetailsObj);

                                $this->startMonthDate = "01";
                                $this->todayDate = date("d");
                                $this->todayMonth = date("m");
                                $this->todayYear = date("Y");
                                $this->rangeYear = date("Y");
                                $this->dateArr = GetDateArrays::getDayArray();
                                $this->monthArr = GetDateArrays::getMonthArray();
                                $this->yearArr = array();
                                $dateArr = GetDateArrays::generateDateDataForRange('2014',$this->todayYear);
                                foreach(array_keys($dateArr) as $key=>$value){
                                        $this->yearArr[] = array('NAME'=>$value, 'VALUE'=>$value);
                                }
                        }
                }
        }

        /*
        This function is called when we click on a hyperlink on result screen1 of Field Sales Executive Efficiency MIS
        */
        public function executeFieldSalesExecutiveEfficiencyMisResultScreen2(sfWebRequest $request)
        {
                $this->cid = $request->getParameter('cid');
                $agentAllocDetailsObj   =new AgentAllocationDetails();
                $this->agentName =$agentAllocDetailsObj->fetchAgentName($this->cid);
                unset($agentAllocDetailsObj);

                $this->displayDate = date("jS F Y",strtotime($request->getParameter('startDate')))." To ".date("jS F Y",strtotime($request->getParameter('endDate')));

                $this->exec = $request->getParameter('exec');
                $date = $request->getParameter('date');
                if($date == "TOTAL")
                {
                        $start_date = $request->getParameter('startDate');
                        $end_date = $request->getParameter('endDate');
                        $this->currentDateRange = $this->displayDate;
                }
                else
                {
                        $start_date = $date;
                        $end_date = $date;
                        $this->currentDateRange = date("jS F Y",strtotime($date));
                }
                $this->details = $request->getParameter('details');

                $fsempObj = new FieldSalesExecutivePerformanceMis($agentArray,$start_date,$end_date);

                $agentDetails = $fsempObj->getActualFieldSalesAgents();
                $agent = array_intersect(array_keys($agentDetails), array($this->exec));

                if($request->getParameter('range_format') != 'MY'){
                        $this->header = "For the period ".$this->displayDate;
                } else {
                        $this->header = "For the month of ".date("F Y",strtotime($request->getParameter('startDate')));
                }

                $crmAllot = $fsempObj->getAgentAllotedProfileArray($agent);
                $crmAllotTrac = $fsempObj->getAgentAllotedProfileArrayFromTrac($agent);
                $this->profileArray = $fsempObj->unionCrmData($crmAllot, $crmAllotTrac);
                $this->profileArray = $fsempObj->filterActualData($this->profileArray, $agentDetails);
                $this->profileUsername = $fsempObj->getProfileUsernames($this->profileArray);
                $this->freshVisitArray = $fsempObj->getAgentAllotedProfileFreshVisitArray($this->profileArray, $start_date, $end_date);
                $this->paidArray = $fsempObj->getAgentAllotedProfilePaidArray($this->profileArray, $this->freshVisitArray);
                $this->count = array_shift($fsempObj->getAgentAllotedProfileCount($this->profileArray));
                $this->tempCount = 0;
                unset($fsepmObj);
        }
        /*
        This function is called when we click on Discount Heads MIS or its corresponding Jump link. It is also called when we submit the dates and Result Screen 1 is created.
        */
        public function executeDiscountHeadsMis(sfWebRequest $request)
        {
		$formArr = $request->getParameterHolder()->getAll();
                $this->cid = $formArr['cid'];
                $agentAllocDetailsObj = new AgentAllocationDetails();
                $this->agentName = $agentAllocDetailsObj->fetchAgentName($this->cid);

                if($formArr['submit'] || $formArr['outside'])       //If form is submitted or jump is clicked
		{
			if($formArr['outside']) {      // Default values for "Jump" Hyperlink
				$start_dt = date('Y')."-".date('m')."-01";
				$end_dt = date('Y-m-d');
				$this->cur_type = 'INR';
			} else   {       // if "GO" is clicked of Result Screen 1

				$formArr["date1_dateLists_month_list"]++;
				$formArr["date2_dateLists_month_list"]++; 
				$start_dt = $formArr["date1_dateLists_year_list"]."-".$formArr["date1_dateLists_month_list"]."-".$formArr["date1_dateLists_day_list"];
				$end_dt = $formArr["date2_dateLists_year_list"]."-".$formArr["date2_dateLists_month_list"]."-".$formArr["date2_dateLists_day_list"];
				$this->cur_type = $formArr['transactions'];

				if($start_dt>$end_dt){
					$this->errorMsg = "Invalid Date Selected";
					$this->rangeYear = date("Y");	
					$this->setTemplate('discountHeadsMis');
					return;
				}
			}
			$this->start_dt = date("Y-m-d",strtotime($start_dt));
			$this->end_dt = date("Y-m-d",strtotime($end_dt));
			$this->displayDate = date("jS F Y",strtotime($this->start_dt))." To ".date("jS F Y",strtotime($this->end_dt));
			
			$dsObj = new DiscountHeadsMis($start_dt, $end_dt, $this->cur_type);
			$dsObj->fetchCombinedDiscountHeadsExecWise();
			$this->res = $dsObj->getCombinedDiscountHeadsExecWise();

			if($formArr['output_format'] == 'XLS')       //If excel format for output is selected
			{
				$displayDate = date("jS_F_Y",strtotime($this->start_dt))."_To_".date("jS_F_Y",strtotime($this->end_dt));
				$header = "\t\t\t\tResult_For_Discount_Heads_MIS\n\t\t\t\tDuration:_".$displayDate."\n\t\t\t\tTxn_Currency_=_".$this->cur_type."\n\t\t\t\tAll_numbers_below_are_in_INR";
				$dsObj->createExcelFormatOutput($this->res, $header, $displayDate);
			}
			else        //If html format for output is selected
				$this->setTemplate('discountHeadsMisResultScreen1');
			return;
		}
		$this->rangeYear = date("Y");
	}
        
	/*
        This function is called when we click on a hyperlink on result screen1 of Discount Heads MIS
        */
        public function executeDiscountHeadsMisResultScreen2(sfWebRequest $request)
        {
		$formArr = $request->getParameterHolder()->getAll();
                $this->cid = $formArr['cid'];
                $agentAllocDetailsObj = new AgentAllocationDetails();
                $this->agentName = $agentAllocDetailsObj->fetchAgentName($this->cid);

		$this->displayDate = date("jS F Y",strtotime($formArr['startDate']))." To ".date("jS F Y",strtotime($formArr['endDate']));
		$this->cur_type = $formArr['curType'];
		$this->agent = $formArr['agent'];
		
		$dsObj = new DiscountHeadsMis($formArr['startDate'], $formArr['endDate'], $formArr['curType']);
		$dsObj->fetchBillingDetailsTrxnWise($this->agent);
		$this->res = $dsObj->getAllTrxnDetailAgentWise();
	}

	public function executeCityWiseFreshAndRenewalMis(sfWebRequest $request)
	{
		$formArr = $request->getParameterHolder()->getAll();
		$this->cid = $formArr['cid'];
		$this->yearArr = array();
		for($i=date('Y'); $i>=2004; $i--) {
			$j = $i+1;
			$key = "$i-$j";
			$this->yearArr[$key] = $i;
		}

		if($formArr['submit'] || $formArr['outside']){
			if($formArr['submit']) 
				$formArr['selectionYear'] = $this->yearArr[$formArr['selectionYear']];

			if($formArr['outside']){
				$formArr["selectionYear"] = date('Y');
				$formArr['selectionRange'] = 'M';
				$formArr['saleType'] = 'T';
			}

			if($formArr['saleType'] == "F"){
				$this->selectedSales = "Fresh Sales";
			} elseif($formArr['saleType'] == "R") {
				$this->selectedSales = "Renewal Sales";
			} else {
				$this->selectedSales = "Total Sales";
			}

			if($formArr['selectionRange']=='Q') {
				$this->labelArr = array('Apr-Jun','Jul-Sep','Oct-Dec','Jan-Mar');
				$this->indexArr = array(2,3,4,1);
			}else {
				$this->labelArr = array('Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar');
				$this->indexArr = array(4,5,6,7,8,9,10,11,12,1,2,3);
			}
	
			$this->displayDate = $formArr["selectionYear"]." - ".($formArr["selectionYear"]+1);
			$cityMisObj = new CityWiseFreshAndRenewalMis($formArr["selectionYear"], $formArr['selectionRange'], $formArr['saleType']);
			$cityWiseSale = $cityMisObj->getSaleCityWise();
			$this->saleArr = $cityMisObj->sort_citywiseSale($cityWiseSale);
			if($formArr['output_format'] == "XLS") 
			{
                if($this->selectedSales != "Total Sales"){
                    $msg = "Total ";                        
                }
                                $header = "\t\t\t\tResult_For_City_Wise_Fresh_And_Renewal_MIS\n\t\t\t\tFinancial_Year:_".$this->displayDate."\n\t\t\t\t".$this->selectedSales."_::_All_numbers_below_are_in_INR";
				$cityMisObj->createExcelFormatOutput($this->saleArr, $header, $this->displayDate);
			}
			$this->setTemplate('cityWiseFreshAndRenewalMisResultScreen1');
		}
	}

	public function executeGatewayWiseMis(sfWebRequest $request) 
	{
		$formArr = $request->getParameterHolder()->getAll();
		$this->cid = $formArr['cid'];

            if($formArr['submit'] || $formArr['outside']) 
            {
                  if($formArr['submit'])            //If form is submitted
                  {
                          if($formArr["range_format"]=="MY")      //If month and year is selected
                          {
                                  $start_date = $formArr["yearValue"]."-".$formArr["monthValue"]."-01";
                                  $end_date = $formArr["yearValue"]."-".$formArr["monthValue"]."-".date("t",strtotime($start_date));
                                  $this->displayDate = date("F Y",strtotime($start_date));
                          }
                          else            //If date ranges are selected
                          {
                                  $formArr["date1_dateLists_month_list"]++;
                                  $formArr["date2_dateLists_month_list"]++;
                                  $start_date = $formArr["date1_dateLists_year_list"]."-".$formArr["date1_dateLists_month_list"]."-".$formArr["date1_dateLists_day_list"];
                                  $end_date = $formArr["date2_dateLists_year_list"]."-".$formArr["date2_dateLists_month_list"]."-".$formArr["date2_dateLists_day_list"];
                                  $start_date = date("Y-m-d",strtotime($start_date));
                                  $end_date = date("Y-m-d",strtotime($end_date));
                                  $this->displayDate = date("jS F Y",strtotime($start_date))." To ".date("jS F Y",strtotime($end_date));
                          }
                          if($start_date>$end_date)
                                  $this->errorMsg = "Invalid Date Selected";
                          elseif(ceil((strtotime($end_date)-strtotime($start_date))/(24*60*60))>=45)
                                  $this->errorMsg = "More than 45 days selected in range";
                  }
                  else                   //If Jump is clicked
                  {
                          $end_date = date("Y-m-d");
                          $start_date = date("Y-m")."-01";
                          $this->displayDate = date("jS F Y",strtotime($start_date))." To ".date("jS F Y",strtotime($end_date));
                  }
                  if(!$this->errorMsg)    //If no error message then submit the page
                  {
                        $this->currencyUnit = $formArr['currency_unit'];
                  	$gatewayObj = new GatewayWiseMis($start_date, $end_date, $formArr['currency_unit']);
                  	$this->info = $gatewayObj->fetchGatewayAndChannelWiseData();
                  	$this->sourceArr = $gatewayObj->fetchGatewayAndChannelWiseTotal($this->info);
                  	$this->setTemplate('gatewayWiseMisResultScreen');

                  	if($formArr['report_format']=='XLS') 
                  	{
                  		$header = "\t\t\t\tResult_For_Gateway_Wise_MIS\n\t\t\t\tFor_the_period_:_".$this->displayDate."\n";
					$gatewayObj->createExcelFormatOutput($this->info, $this->sourceArr, $header, $this->displayDate);
                  	}
                  }
            }
            else               // for selection screen
            {
                  $this->startMonthDate = "01";
                  $this->todayDate = date("d");
                  $this->todayMonth = date("m");
                  $this->todayYear = date("Y");
                  $this->rangeYear = date("Y");
                  $this->dateArr = GetDateArrays::getDayArray();
                  $this->monthArr = GetDateArrays::getMonthArray();
                  $this->yearArr = array();
                  $dateArr = GetDateArrays::generateDateDataForRange('2014',($this->todayYear));
                  foreach(array_keys($dateArr) as $key=>$value)
                          $this->yearArr[] = array('NAME'=>$value, 'VALUE'=>$value);

            }
	}

	public function executeFieldSalesDocumentVerificationMis(sfWebRequest $request)
	{
		$formArr = $request->getParameterHolder()->getAll();
		$this->cid = $formArr['cid'];
		
            if($formArr['submit'] || $formArr['outside']) 
            {
                  if($formArr['submit'])            //If form is submitted
                  {
                          if($formArr["range_format"]=="MY")      //If month and year is selected
                          {
                                  $start_date = $formArr["yearValue"]."-".$formArr["monthValue"]."-01";
                                  $end_date = $formArr["yearValue"]."-".$formArr["monthValue"]."-".date("t",strtotime($start_date));
                                  $this->displayDate = date("F Y",strtotime($start_date));
		                      $this->ddarr = range(1,date('t',strtotime($start_date)));
                          }
                          else            //If date ranges are selected
                          {
                                  $formArr["date1_dateLists_month_list"]++;
                                  $formArr["date2_dateLists_month_list"]++;
                                  $start_date = $formArr["date1_dateLists_year_list"]."-".$formArr["date1_dateLists_month_list"]."-".$formArr["date1_dateLists_day_list"];
                                  $end_date = $formArr["date2_dateLists_year_list"]."-".$formArr["date2_dateLists_month_list"]."-".$formArr["date2_dateLists_day_list"];
                                  $start_date = date("Y-m-d",strtotime($start_date));
                                  $end_date = date("Y-m-d",strtotime($end_date));
                                  $this->displayDate = date("jS F Y",strtotime($start_date))." To ".date("jS F Y",strtotime($end_date));
		                      $this->ddarr = range(date('d',strtotime($start_date)),date('d',strtotime($end_date)));
                          }
                          if($start_date>$end_date)
                                  $this->errorMsg = "Invalid Date Selected";
                          elseif(ceil((strtotime($end_date)-strtotime($start_date))/(24*60*60))>=45)
                                  $this->errorMsg = "More than 45 days selected in range";
                  }
                  else                   //If Jump is clicked
                  {
                          $end_date = date("Y-m-d");
                          $start_date = date("Y-m")."-01";
                          $this->displayDate = date("jS F Y",strtotime($start_date))." To ".date("jS F Y",strtotime($end_date));
                          $this->ddarr = range(date('d',strtotime($start_date)),date('d',strtotime($end_date)));
                  }
                  if(!$this->errorMsg)    //If no error message then submit the page
                  {
                  	$fsdvObj = new FieldSalesDocumentVerificationMis($start_date, $end_date);
                  	list($allReporters, $this->hierarchyData, $this->background_color) = $fsdvObj->getHierarchyData($this->cid);
                  	$cntArr = $fsdvObj->fetchVerifiedDocumentsCount($allReporters);
                  	$cntArr = $fsdvObj->generateTeamWiseData($this->ddarr, $allReporters, $cntArr);
                  	$this->cntArr = $fsdvObj->fetchVerifiedDocumentsCountTotal($cntArr, $this->ddarr);

                  	if($formArr['report_format']=='XLS') 
                  	{
                  		$header = "\t\t\t\tResult_For_Field_Sales_Document_Verification_MIS\n\t\t\t\tFor_the_period_:_".$this->displayDate."\n";
					$fsdvObj->createExcelFormatOutput($this->cntArr, $this->ddarr, $header, $this->displayDate);
                  	}
                  	$this->setTemplate('fieldSalesDocumentVerificationMisResultScreen');
                  }            	
            }
            else               // for selection screen
            {
                  $this->startMonthDate = "01";
                  $this->todayDate = date("d");
                  $this->todayMonth = date("m");
                  $this->todayYear = date("Y");
                  $this->rangeYear = date("Y");
                  $this->dateArr = GetDateArrays::getDayArray();
                  $this->monthArr = GetDateArrays::getMonthArray();
                  $this->yearArr = array();
                  $dateArr = GetDateArrays::generateDateDataForRange('2014',($this->todayYear));
                  foreach(array_keys($dateArr) as $key=>$value)
                          $this->yearArr[] = array('NAME'=>$value, 'VALUE'=>$value);
            }
	}

	public function executeCpppMis(sfWebRequest $request)
	{
		$formArr = $request->getParameterHolder()->getAll();
		$this->cid = $formArr['cid'];

            if($formArr['submit'] || $formArr['outside']) 
            {
                  if($formArr['submit'])            //If form is submitted
                  {
                        $start_date = $formArr["yearValue"]."-".$formArr["monthValue"]."-01";
                        $end_date = $formArr["yearValue"]."-".$formArr["monthValue"]."-".date("t",strtotime($start_date));
                  }
                  else                   //If Jump is clicked
                  {
                        $start_date = date("Y-m-01");
                        $end_date = date("Y-m-t");
                  }

                  $this->displayDate = date("F Y",strtotime($start_date));
            	$cpppObj = new CpppMis($start_date, $end_date);

            	list($this->srcWiseDataArr, $this->totalArr) = $cpppObj->fetchDataForMIS();

            	if($formArr['report_format']=='XLS') 
            	{
            		$header = "\t\tResult_For_CPPP_MIS\n\t\tFor_the_period_:_".$this->displayDate."\n";
				$cpppObj->createExcelFormatOutput($this->srcWiseDataArr, $this->totalArr, $header, $this->displayDate);
            	}
            	$this->setTemplate('cpppMisResultScreen');
            }
            else               // for selection screen
            {
                  $this->todayMonth = date("m");
                  $this->todayYear = date("Y");
                  $this->monthArr = GetDateArrays::getMonthArray();
                  $this->yearArr = array();
                  $dateArr = GetDateArrays::generateDateDataForRange('2011',($this->todayYear));
                  foreach(array_keys($dateArr) as $key=>$value)
                          $this->yearArr[] = array('NAME'=>$value, 'VALUE'=>$value);
            }
	}

	// Notification Mis:
        public function executeNotificationMis(sfWebRequest $request)
        {
            $formArr 	= $request->getParameterHolder()->getAll();
            $this->cid 	= $formArr['cid'];

	    // Submit State	
            if($formArr['submit'])
            {
		 $this->channelKey	=$formArr['channelKey'];
		 $notificationKey 	=$formArr['notificationKey'];
		 $this->notificationType=$notificationKey;
                 $start_date 		=$formArr["yearValue"]."-".$formArr["monthValue"]."-01";
                 $end_date 		=$formArr["yearValue"]."-".$formArr["monthValue"]."-".date("t",strtotime($start_date));
                 $this->displayDate 	=date("F Y",strtotime($start_date));

		 $dailyScheduledLog =new MOBILE_API_DAILY_NOTIFICATION_COUNT_LOG('newjs_masterRep');

		 $appNotificationsObj =new MOBILE_API_APP_NOTIFICATIONS('newjs_masterRep');
		 $scheduledNotificaionArr =$appNotificationsObj->getScheduledNotifications();
		 $scheduledNotificaionStr ="'".implode("','", $scheduledNotificaionArr)."'";
		
		//var_dump($start_date);
		//var_dump($end_date);
		//var_dump($notificationKey);
		//var_dump($this->channelKey);
		 $dataArr =$dailyScheduledLog->getData($start_date, $end_date, $notificationKey,'',$this->channelKey);
		 if($notificationKey)
			$dataArrForScheduled =$dataArr;
		 else
			$dataArrForScheduled =$dailyScheduledLog->getData($start_date, $end_date, '', $scheduledNotificaionStr,$this->channelKey);
		 if(count($dataArr)>0){
			foreach($dataArr as $key=>$val){

				$total                                  =$val['TOTAL_COUNT'];
				$pushAcknowledged                       =$val['PUSH_ACKNOWLEDGED'];	
				$localAcknowledged                      =$val['LOCAL_ACKNOWLEDGED'];
				$overallAcknowledged                    =$pushAcknowledged+$localAcknowledged;
				$totalPushed			    	=$val['PUSHED_TO_GCM']+$val['PUSHED_TO_IOS'];
				$totalAccepted				=$val['ACCEPTED_BY_GCM']+$val['ACCEPTED_BY_IOS'];

				// Local Notif data manipulation 
				$totalScheduled                         =$dataArrForScheduled[$key]['TOTAL_COUNT'];
				$pushAcknowledgedScheduled              =$dataArrForScheduled[$key]['PUSH_ACKNOWLEDGED'];
				$totalLocalEligibleScheduled          	=$totalScheduled-$pushAcknowledgedScheduled;
				$dataArr[$key]['TOTAL_LOCAL_ELIGIBLE']  =$totalLocalEligibleScheduled;	

				$dataArr[$key]['TOTAL_PUSHED']		=$totalPushed;
				$dataArr[$key]['TOTAL_ACCEPTED']    	=$totalAccepted;
				$dataArr[$key]['TOTAL_ACKNOWLEDGED']    =$overallAcknowledged;

				// push success rate%
				if($pushAcknowledged){	
					$pushSuccess  				=($pushAcknowledged/$totalPushed)*100;
					$dataArr[$key]['PUSH_SUCCESS_RATE'] 	=round($pushSuccess,0)."%";
				}
				// local success rate%
				if($localAcknowledged){
	                                $localSuccess    			=($localAcknowledged/$totalLocalEligibleScheduled)*100;
	                                $dataArr[$key]['LOCAL_SUCCESS_RATE'] 	=round($localSuccess,0)."%";
				}
				// overall success rate%
				if($overallAcknowledged){
	                                $overallSuccess    			=($overallAcknowledged/$total)*100;
        	                        $dataArr[$key]['OVERALL_SUCCESS_RATE']	=round($overallSuccess,0)."%";
				}
				if($this->channelKey=='A_I'){
					$dataArr[$key]['NOTIFICATION_OPENED_COUNT_ANDROID'] = $val['OPENED_COUNT1'];
					$dataArr[$key]['NOTIFICATION_OPENED_COUNT_IOS'] = $val['OPENED_COUNT2'];
				}
				else{
					$dataArr[$key]['NOTIFICATION_OPENED_COUNT'] = $val['OPENED_COUNT1'];
				}
				$newData[$val['DAY']] =$dataArr[$key];					
			}
			unset($countTypeArr);
			if($this->channelKey=='A_I')	
	                        $countTypeArr =array('TOTAL_COUNT','','','','PUSHED_TO_GCM','PUSHED_TO_IOS','TOTAL_PUSHED','ACCEPTED_BY_GCM','ACCEPTED_BY_IOS','TOTAL_ACCEPTED','PUSH_ACKNOWLEDGED','PUSH_SUCCESS_RATE','','','','TOTAL_LOCAL_ELIGIBLE','LOCAL_API_HIT_BY_DEVICE','LOCAL_SENT_TO_DEVICE','LOCAL_ACKNOWLEDGED','LOCAL_SUCCESS_RATE','','','','TOTAL_ACKNOWLEDGED','OVERALL_SUCCESS_RATE','','','','ACTIVE_LOGIN_7DAY','ACTIVE_LOGIN_1DAY','NOTIFICATION_OPENED_COUNT_ANDROID','NOTIFICATION_OPENED_COUNT_IOS');
			else
				$countTypeArr =array('TOTAL_COUNT','','','','PUSHED_TO_GCM','','','','','','PUSH_ACKNOWLEDGED','PUSH_SUCCESS_RATE','NOTIFICATION_OPENED_COUNT');

			if(!$notificationKey)
				$this->notificationType ='All Notification';
			$this->newData =$newData;
			$this->countTypeArr=$countTypeArr;
			$this->notifExist =1;
			//var_dump($this->notifExist);die("done");
		 }
                 $this->setTemplate('notificationMisResultScreen');
            }
            else // for Selection Criteria
            {
                  $this->todayMonth 	= date("m");
                  $this->todayYear 	= date("Y");
                  $this->monthArr 	= GetDateArrays::getMonthArray();
                  $this->yearArr 	= array();
                  $dateArr 		= GetDateArrays::generateDateDataForRange('2016',($this->todayYear));

		  $this->channelArr	=NotificationEnums::$channelArr;			
                  foreach(array_keys($dateArr) as $key=>$value)
                          $this->yearArr[] = array('NAME'=>$value, 'VALUE'=>$value);

                  $appNotificationObj =new MOBILE_API_APP_NOTIFICATIONS('newjs_masterRep');
		  $fields ='NOTIFICATION_KEY,FREQUENCY';
                  $notificationArrTemp =$appNotificationObj->getActiveNotifications($fields);
		  foreach($notificationArrTemp as $key=>$value){
			$notificationArr[$value['NOTIFICATION_KEY']] =$value;	
		  }
		  $this->notificationArr =$notificationArr;	
            }
        }
    public function executeApplePaymentsLoggingMis(sfWebRequest $request){
		$this->cid      =$request->getParameter('cid');
		$this->name     =$request->getParameter('name');
		$this->monthDropDown = array();
		$this->yearDropDown = array();

		$this->yearDropDown['select'] = 'Select';
		for($i=2015;$i<=date('Y');$i++){
			$this->yearDropDown[$i] = $i;
		}

		$this->monthDropDown['select'] = 'Select';
		for($i=1;$i<=12;$i++){
			$temp = date('F', mktime(0, 0, 0, $i, 10));
			$tempI = (str_pad($i,2,'0',STR_PAD_LEFT));
			$this->monthDropDown[$tempI] = $temp;
		}

		$this->flag = 0;
		
		if($request->getParameter('submit')){
			if($request->getParameter('selectedYear') == 'select'){
				$this->errorMsg = "Please select a valid Year";
			} else if($request->getParameter('selectedMonth') == 'select'){
				$this->errorMsg = "Please select a valid Month";
			} else {
				$start = $request->getParameter('selectedYear')."-".$request->getParameter('selectedMonth')."-01 00:00:00";
				$end = $request->getParameter('selectedYear')."-".$request->getParameter('selectedMonth')."-31 00:00:00";
				$billOrder = new BILLING_ORDERS('newjs_slave');
				$billOrderDev = new billing_ORDERS_DEVICE('newjs_slave');
				$billPurc = new BILLING_PURCHASES('newjs_slave');
				$billServ = new billing_SERVICES('newjs_slave');
				$allOrderDet = $billOrder->getAllOrdersForAppleWithinRange($start, $end);
				$billids = $billOrderDev->getApplePayOrdersForOrderIds(array_keys($allOrderDet));
				$billingDet = $billPurc->fetchAllDataForBillidArr(array_keys($billids));
				$this->flag = 1;
				//print_r(array($allOrderDet, $billids, $billingDet));
				$outputArr = array();
				foreach($allOrderDet as $key=>$val){
					foreach($billingDet as $kk=>$vv){
						if($vv['ORDERID'] == $val['ID']){
							if($val['CURTYPE'] == 'RS'){
								$currencyCount[$val['SERVICEMAIN']]['RS']++;
								$currencyCount[$val['SERVICEMAIN']]['RS_VAL'] += $val['AMOUNT'];
								$currencyCount[$val['SERVICEMAIN']]['RS_VAL_70'] += round(($val['AMOUNT']*0.70),2);
							} else {
								$currencyCount[$val['SERVICEMAIN']]['DOL']++;
								$currencyCount[$val['SERVICEMAIN']]['DOL_VAL'] += $val['AMOUNT'];
								$currencyCount[$val['SERVICEMAIN']]['DOL_VAL_70'] += round(($val['AMOUNT']*0.70),2);
							}
							$outputArr[$val['SERVICEMAIN']][] = array($val['ENTRY_DT'],$vv['ENTRY_DT'],$val['ORDERID']."-".$val['ID'],$vv['BILLID'],$val['PROFILEID'],$val['USERNAME'],$val['SERVICEMAIN'],$val['CURTYPE'],$val['AMOUNT'],round(($val['AMOUNT']*0.70),2),$val['BILL_EMAIL'],$vv['IPADD']);
						}
					}
				}
				$services = implode(",",array_keys($outputArr));
				$this->serviceNames = array();
				foreach(array_keys($outputArr) as $key=>$val){
					$this->serviceNames[$val] = $billServ->getServiceName($val);
				}
				$this->currencyCount = $currencyCount;
				$this->paidDetailsArr = $outputArr;
				//print_r(array($this->serviceNames, $this->paidDetailsArr));
			}
		}
	}

    public function executeSalesProcessWiseTrackingMis(sfWebRequest $request)
    {
        $this->cid = $request->getParameter('cid');
        $this->name = $request->getParameter('name');        
        $this->monthArr = array('04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec', '01' => 'Jan', '02' => 'Feb', '03' => 'Mar');
        for ($i = date("Y"); $i >= 2015; $i--) {
            $yr[$i - 2015] = $i;
        }
        $this->yearArr = $yr;
        unset($yr);
        //If the form is submitted
        if($request->getParameter('submit'))
        {
            $this->dateType = $request->getParameter('dateRange');
            $salesProcessObj = new incentive_SALES_PROCESS_WISE_TRACKING("newjs_slave");
            $salesProcessHeadCountObj = new incentive_SALES_PROCESS_WISE_TRACKING_HEAD_COUNT("newjs_slave");
            $this->processArray = crmParams::$processNames;
            $misGenerationHandlerObj = new misGenerationhandler();
            if($this->dateType == 'D'){
                $this->selectedMonth = $request->getParameter('dateWiseMonth');
                $this->selectedYear = $request->getParameter('dateWiseYear');
                $paramsArr['MONTH_YR'] = $this->monthArr[$this->selectedMonth]."-".$this->selectedYear;
                $this->headCountArr = $salesProcessHeadCountObj->getData($paramsArr)[$paramsArr['MONTH_YR']];
                $stDate = $this->selectedYear."-".$this->selectedMonth."-"."01";
                if($stDate > date('Y-m-d')){
                    $this->error = "Date selection wrong";
                }
                $daysInMonth = date('t',strtotime($stDate));
                $endDate = $this->selectedYear."-".$this->selectedMonth."-".$daysInMonth;
                $this->range = $this->monthArr[$this->selectedMonth]."-".$this->selectedYear;
                $tableData = $salesProcessObj->getData($stDate, $endDate, "DATE");
                if($tableData){
                    $data = $misGenerationHandlerObj->bakeDataForSalesProcessMIS($tableData);

                    $processes = array_keys($data);
                    for($i=01;$i<=$daysInMonth;$i++){
                        $i = ($i<10)?"0".$i:$i;
                        $labelArr[] = $i;
                        foreach($processes as $key => $val){
                            $dt = $this->selectedYear."-".$this->selectedMonth."-".$i;
                            $result[$val][$i] = round($data[$val][$dt]);
                            $total[$val] = round($data[$val]['TOTAL']);
                        }
                    }
                    $this->data = $result;
                    $this->labelArr = $labelArr;
                    $this->total = $total;
                }
                else{
                    $this->noData = "No data exists for the selected date range";
                }
            }
            else if($this->dateType == 'M'){
                $this->selectedYear = $request->getParameter('monthWiseYear');
                $stDate = $this->selectedYear."-04-01";
                if($stDate > date('Y-m-d')){
                    $this->error = "Date selection wrong";
                }
                $endDate = ($this->selectedYear+1)."-03-31";
                /*
                if($endDate > date('Y-m-d')){
                    $lastMonth = new DateTime("last day of last month");
                    $endDate = $lastMonth->format('Y-m-d');
                }
                */
                $this->range = "Financial Year: ".$this->selectedYear."-".($this->selectedYear+1);
                $tableData = $salesProcessObj->getData($stDate, $endDate, "MONTH");
                if($tableData){
                    $data = $misGenerationHandlerObj->bakeDataForSalesProcessMIS($tableData);
                    $processes = array_keys($data);
                    foreach($this->monthArr as $k => $v){
                        $k = ($k<10)?($k-0):$k;
                        foreach ($processes as $key => $val){
                            $result[$val][$k] = round($data[$val][$k]);
                            $total[$val] = round($data[$val]['TOTAL']);
                        }
                    }
                    $this->data = $result;
                    $this->labelArr = $this->monthArr;
                    $this->total = $total;
                }
                else{
                    $this->noData = "No data exists for the selected date range";
                }
            }
            else if($this->dateType == 'Q'){
                $this->selectedYear = $request->getParameter('quarterWiseYear');
                $lastMonth = new DateTime("last day of last month");
                $stDate = $this->selectedYear."-04-01";
                if($stDate > date('Y-m-d')){
                    $this->error = "Date selection wrong";
                }
                $endDate = ($this->selectedYear+1)."-03-31";
                $this->range = "Financial Year: ".$this->selectedYear."-".($this->selectedYear+1);
                $tableData = $salesProcessObj->getData($stDate, $endDate, "QUARTER");
                if($tableData){
                    $data = $misGenerationHandlerObj->bakeDataForSalesProcessMIS($tableData);
                    $processes = array_keys($data);
                    $this->labelArr = array("2" => "Apr-Jun", "3" => "Jul-Sep", "4" => "Oct-Dec", "1"=>"Jan-Mar");
                    foreach($this->labelArr as $k => $v){
                        foreach($processes as $key => $val){
                            $result[$val][$k] = round($data[$val][$k]);
                            $total[$val] = round($data[$val]['TOTAL']);
                        }
                    }
                    $this->data = $result;
                    $this->total = $total;
                }
                else{
                    $this->noData = "No data exists for the selected date range";
                }
            }
            unset($salesProcessObj);
            unset($misGenerationHandlerObj);
            unset($tableData);
            unset($data);
            unset($processes);
            unset($labelArr);
            unset($result);
            unset($total);
            $this->setTemplate('salesProcessWiseTrackingMisResult');
        }
    }

    public function executeInboundSalesCampaignMis(sfWebRequest $request){
		$this->cid      =$request->getParameter('cid');
		$this->name     =$request->getParameter('name');
		$this->monthDropDown = array();
		$this->yearDropDown = array();
		$this->reportDropDown = array("select"=>"Select", "D"=>"Day View", "M"=>"Month View", "Q"=>"Fiscal Year View");
		$this->dateDropDown = array();

		$this->campaignDropDown = array("select"=>"Select", "IB_Sales&IB_SupSale" => "IB_Sale & IB_SupSale", "IB_Service&IB_SupService"=>"IB_Service & IB_SupService", "IB_PaidService&IB_SupPaidservice"=>"IB_PaidService & IB_SupPaidservice");
		
		$this->yearDropDown['select'] = 'Select';
		for($i=2016;$i<=date('Y');$i++){
			$this->yearDropDown[$i] = $i;
		}

		$this->monthDropDown['select'] = 'Select';
		for($i=1;$i<=12;$i++){
			$temp = date('F', mktime(0, 0, 0, $i, 10));
			$tempI = (str_pad($i,2,'0',STR_PAD_LEFT));
			$this->monthDropDown[$tempI] = $temp;
		}

		$this->quarterArr = array('Apr-Jun','Jul-Sep','Oct-Dec','Jan-Mar');
		for($i=1;$i<=31;$i++){
			$this->dateDropDown[$i] = $i;
		}

		$this->flag = 0;
		
		if($request->getParameter('submit')){
			$this->selectedYear = $request->getParameter('selectedYear');
			$this->selectedMonth = $request->getParameter('selectedMonth');
			$this->selectedRange = $request->getParameter('selectedRange');
			$this->campaignSelection = $request->getParameter('campaignSelection');

			if($this->selectedYear == 'select'){
				$this->errorMsg = "Please select a valid Year";
			} else if($this->selectedRange == 'select'){
				$this->errorMsg = "Please select a valid View Type";
			} else if($this->selectedRange == 'D' && $this->selectedMonth == 'select'){
				$this->errorMsg = "Please select a valid Month, required for Daily View";
			} else if($this->campaignSelection == 'select'){
				$this->errorMsg = "Please select a valid Campaign";
			} else {
				$this->flag = 1;
				unset($this->monthDropDown['select']);
				unset($this->dateDropDown['select']);
				unset($this->yearDropDown['select']);
				unset($this->reportDropDown['select']);
				unset($this->campaignDropDown['select']);
				if ($this->selectedMonth && $this->selectedRange == "D") {
					$start = $this->selectedYear."-".$this->selectedMonth."-01 00:00:00";
					$end = $this->selectedYear."-".$this->selectedMonth."-31 23:59:59";
				} else {
					$start = $this->selectedYear."-04-01 00:00:00";
					$end = ($this->selectedYear+1)."-03-31 23:59:59";
				}
				$inbSalesLogObj = new incentive_INBOUND_SALES_LOG('newjs_slave');
				$campaignSelected = explode("&", $this->campaignSelection);
				$campaignData[$campaignSelected[0]] = $inbSalesLogObj->fetchCampaignDetailsWithinRange(trim($campaignSelected[0]), $start, $end, $this->selectedRange);
				$campaignData[$campaignSelected[1]] = $inbSalesLogObj->fetchCampaignDetailsWithinRange(trim($campaignSelected[1]), $start, $end, $this->selectedRange);	
				$quarterArr = array(1=>array(4,5,6),2=>array(7,8,9),3=>array(10,11,12),4=>array(1,2,3));
				
				// Resort Campaign Data according to view format 
				foreach ($campaignData as $name=>$data) {
					if (is_array($data) && !empty($data)) {
						foreach ($data as $val) {
							if ($this->selectedRange == "D") {
								$output[$name][$val['DAY']] += $val['CNT'];
							} else {
								if ($this->selectedRange == "Q") {
									if (in_array($val['MONTH'], $quarterArr[1])) {
										$output[$name]['Apr-Jun'] += $val['CNT'];
									} else if (in_array($val['MONTH'], $quarterArr[2])) {
										$output[$name]['Jul-Sep'] += $val['CNT'];
									} else if (in_array($val['MONTH'], $quarterArr[3])) {
										$output[$name]['Oct-Dec'] += $val['CNT'];
									} else if (in_array($val['MONTH'], $quarterArr[4])) {
										$output[$name]['Jan-Mar'] += $val['CNT'];
									}
								} else {
									$output[$name][date('F', mktime(0, 0, 0, $val['MONTH'], 10))] += $val['CNT'];
								}
							}
						}
					} 
				}
				$this->campaignData = $output;
				// print_r($this->campaignData);
				// die;
			}
		}
	}

	public function executeRCBSalesConversionExecutiveMIS(sfWebRequest $request)
    {
        $this->cid         = $request->getParameter('cid');
        $this->name        = $request->getParameter('name');
        $this->startMonthDate = "01";
        $this->todayDate      = date("d");
        $this->todayMonth     = date("m");
        $this->todayYear      = date("Y");
        $this->rangeYear      = date("Y");
        $this->dateArr        = GetDateArrays::getDayArray();
        $this->monthArr       = GetDateArrays::getMonthArray();
        $this->yearArr        = array();
        $dateArr              = GetDateArrays::generateDateDataForRange('2004', ($this->todayYear));
        foreach (array_keys($dateArr) as $key => $value) {
            $this->yearArr[] = array('NAME' => $value, 'VALUE' => $value);
        }
        if ($request->getParameter("submit")) {
            if ($request->getParameter("submit")) //If form is submitted
            {
                $formArr = $request->getParameterHolder()->getAll();

                if ($formArr["range_format"] == "MY") //If month and year is selected
                {
                    $start_date        = $formArr["yearValue"] . "-" . $formArr["monthValue"] . "-01";
                    $end_date          = $formArr["yearValue"] . "-" . $formArr["monthValue"] . "-" . date("t", strtotime($start_date));
                    $this->displayDate = date("F Y", strtotime($start_date));
                } else //If date ranges are selected
                {
                    $formArr["date1_dateLists_month_list"]++;
                    $formArr["date2_dateLists_month_list"]++;
                    $start_date        = $formArr["date1_dateLists_year_list"] . "-" . $formArr["date1_dateLists_month_list"] . "-" . $formArr["date1_dateLists_day_list"];
                    $end_date          = $formArr["date2_dateLists_year_list"] . "-" . $formArr["date2_dateLists_month_list"] . "-" . $formArr["date2_dateLists_day_list"];
                    $start_date        = date("Y-m-d", strtotime($start_date));
                    $end_date          = date("Y-m-d", strtotime($end_date));
                    $this->displayDate = date("jS F Y", strtotime($start_date)) . " To " . date("jS F Y", strtotime($end_date));
                }
                if ($start_date > $end_date) {
                    $this->errorMsg = "Invalid Date Selected";
                }
            } else //If Jump is clicked
            {
                $end_date          = date("Y-m-d");
                $start_date        = date("Y-m") . "-01";
                $this->displayDate = date("jS F Y", strtotime($start_date)) . " To " . date("jS F Y", strtotime($end_date));
            }
            if (!$this->errorMsg) //If no error message then submit the page
            {
                $jsadminPswrdsObj = new jsadmin_PSWRDS('newjs_slave');
                $billExcClbkObj = new billing_EXC_CALLBACK('newjs_slave');
                $billPurObj = new BILLING_PURCHASES('newjs_slave');
                $billPayDetObj = new BILLING_PAYMENT_DETAIL('newjs_slave');
                $manualAllotObj = new MANUAL_ALLOT('newjs_slave');
                // Fetch RCB Agents (both webmaster leads and premium)
                $agents1 = $jsadminPswrdsObj->fetchAgentsWithPriviliges('%ExcWL%');
                $agents2 = $jsadminPswrdsObj->fetchAgentsWithPriviliges('%ExcPrm%');
                if (!empty($agents1) && !empty($agents2)) {
                	$agents = array_unique(array_merge($agents1, $agents2));
                } else if (empty($agents1)) {
                	$agents = $agents2;
                } else if (empty($agents2)) {
                	$agents = $agents1;
                } else {
                	$agents = null;
                }
                //print_r($agents);die;
                //var_dump($start_date);
                //var_dump($end_date);

                if (!empty($agents)) {
                	//fetch dialer allocated profiles
                	$dailyAllotObj = new CRM_DAILY_ALLOT("newjs_slave");
	                $dailyAllocationProfiles = $dailyAllotObj->getProfilesAllottedInDateRange($start_date,$end_date,$agents,true);
	                unset($dailyAllotObj);
	                //print_r($dailyAllocationProfiles);

	                //fetch manually allotted profiles
	                foreach ($agents as $key=>$agent) {
	                    $profiles[$agent] = $manualAllotObj->getAgentAllotedProfileArrayforRCBCallSource($agent,$start_date,$end_date);
	                  	//print_r($profiles[$agent]);
	                    //print_r($dailyAllocationProfiles[$agent]);

	                    if(is_array($dailyAllocationProfiles) && is_array($dailyAllocationProfiles[$agent])){
	                    	if(!is_array($profiles[$agent])){
	                    		$profiles[$agent] = array();
	                    	}
	                    	$profiles[$agent] = array_merge($profiles[$agent],$dailyAllocationProfiles[$agent]);
	                    }
	               		//print_r($profiles[$agent]);
	                }
	                //print_r($profiles);
	                unset($dailyAllocationProfiles);
	            }
	            //print_r($profiles);
                $this->misData = array();
                $profilesVisited = array();
                if (is_array($profiles) && !empty($profiles)) {
                    foreach ($profiles as $key=>$val) {
                        $this->misData[$key]['count'] = count($val);
                        $this->misData[$key]['paid'] = 0;
                        $this->misData[$key]['revenue'] = 0;
                        if (empty($profilesVisited[$key])) {
                    		$profilesVisited[$key] = array();
                    	}
                        if (is_array($val) && !empty($val)) {
                            foreach ($val as $kk=>$vv) {
                            	if (empty($profilesVisited[$key][$vv['PROFILEID']])) {
                            		$profilesVisited[$key][$vv['PROFILEID']] = array();
                            	}
                                if ($billidArr = $billPurObj->checkIfProfilePaidWithin15Days($vv['PROFILEID'], $vv['ALLOT_TIME'])) {
                          
                                	$profilesVisited[$key][$vv['PROFILEID']] = array_unique(array_merge($billidArr,$profilesVisited[$key][$vv['PROFILEID']]));
                                }
                            }
                        }
                    }
                    foreach ($profilesVisited as $agent=>$profileid) {
                    	foreach ($profileid as $kk=>$billidArr1) {
                        	$this->misData[$agent]['paid'] += count($billidArr1);
                        	if (!empty($billidArr1)) {
                            	$this->misData[$agent]['revenue'] += $billPayDetObj->fetchAverageTicketSizeNexOfTaxForBillidArr($billidArr1);
                        	}
                        }
                    }
                    foreach ($this->misData as $kkk=>$vvv) {
                    	$this->misData[$kkk]['revenue'] = round($this->misData[$kkk]['revenue']/$this->misData[$kkk]['paid'], 2);
                    }
                }
                if($formArr["report_format"]=="XLS")
                {   
                	if($formArr["range_format"]=="MY"){
                                $string .= "For_".$monthArr[$formArr["monthValue"]]."-".$formArr["yearValue"];
                        } else {
                                $string .= $start_date."_to_".$end_date;
                        }
                        $headerString = "Executive\tNo. of RCB Allocations\tUsers who paid within 15 days\tTicket Size(Net of TAX) in RS\r\n";
                        if($this->misData && is_array($this->misData))
						{
							foreach($this->misData as $k=>$v)
							{
								$dataString = $dataString.$k."\t";
								$dataString = $dataString.$v["count"]."\t";
								$dataString = $dataString.$v["paid"]."\t";
								$dataString = $dataString.$v["revenue"]."\r\n";
							}
						}
						$xlData = $headerString.$dataString;
		                header("Content-Type: application/vnd.ms-excel");
		                header("Content-Disposition: attachment; filename=RCB_Sales_Conversion_Executive_MIS.xls");
		                header("Pragma: no-cache");
		                header("Expires: 0");
		                echo $xlData;
                        die;
                } else {
                	$this->setTemplate('RCBSalesConversionExecutiveMISScreen1');
                }
            }
        }
    }

    public function executeRenewalConversionMIS(sfWebRequest $request)
    {
        $this->cid         = $request->getParameter('cid');
        $this->name        = $request->getParameter('name');
        $this->startMonthDate = "01";
        $this->todayDate      = date("d");
        $this->todayMonth     = date("m");
        $this->todayYear      = date("Y");
        $this->rangeYear      = date("Y")+1;
        $this->dateArr        = GetDateArrays::getDayArray();
        $this->monthArr       = GetDateArrays::getMonthArray();
        $this->yearArr        = array();
        $dateArr              = GetDateArrays::generateDateDataForRange('2004', ($this->todayYear)+1);
        foreach (array_keys($dateArr) as $key => $value) {
            $this->yearArr[] = array('NAME' => $value, 'VALUE' => $value);
        }
        if ($request->getParameter("submit")) {
            if ($request->getParameter("submit")) //If form is submitted
            {
                $formArr = $request->getParameterHolder()->getAll();

                if ($formArr["range_format"] == "MY") //If month and year is selected
                {
                    $start_date        = $formArr["yearValue"] . "-" . $formArr["monthValue"] . "-01";
                    $end_date          = $formArr["yearValue"] . "-" . $formArr["monthValue"] . "-" . date("t", strtotime($start_date));
                    $this->displayDate = date("F Y", strtotime($start_date));
                } else //If date ranges are selected
                {
                    $formArr["date1_dateLists_month_list"]++;
                    $formArr["date2_dateLists_month_list"]++;
                    $start_date        = $formArr["date1_dateLists_year_list"] . "-" . $formArr["date1_dateLists_month_list"] . "-" . $formArr["date1_dateLists_day_list"];
                    $end_date          = $formArr["date2_dateLists_year_list"] . "-" . $formArr["date2_dateLists_month_list"] . "-" . $formArr["date2_dateLists_day_list"];
                    $start_date        = date("Y-m-d", strtotime($start_date));
                    $end_date          = date("Y-m-d", strtotime($end_date));
                    $this->displayDate = date("jS F Y", strtotime($start_date)) . " To " . date("jS F Y", strtotime($end_date));
                }
                if ($start_date > $end_date) {
                    $this->errorMsg = "Invalid Date Selected";
                } elseif (ceil((strtotime($end_date) - strtotime($start_date))/(24*60*60))>31 && $formArr["range_format"] != "MY") {
                    $this->errorMsg = "More than 31 days selected in range";
                }
            } else //If Jump is clicked
            {
                $end_date          = date("Y-m-d");
                $start_date        = date("Y-m") . "-01";
                $this->displayDate = date("jS F Y", strtotime($start_date)) . " To " . date("jS F Y", strtotime($end_date));
            }
            if (!$this->errorMsg) //If no error message then submit the page
            {
                $billServStatObj = new BILLING_SERVICE_STATUS('newjs_slave');
                $billPurObj = new BILLING_PURCHASES('newjs_slave');
                $billPayDetObj = new BILLING_PAYMENT_DETAIL('newjs_slave');
                $expiryProfiles = $billServStatObj->getRenewalProfilesDetailsInRangeWithoutActiveCheck($start_date, $end_date);
                $misData = array();
                foreach ($expiryProfiles as $key=>$pd) {
                	$misData[$pd['EXPIRY_DT']]['expiry'][$pd['BILLID']] = $pd['PROFILEID'];
                	list($e30Cnt, $e30BillidArr) = $billPurObj->getRenewedProfilesBillidInE30($pd['PROFILEID'], $pd['BILLID'], $pd['EXPIRY_DT']);
                	list($e30eCnt, $e30ebillidArr) = $billPurObj->getRenewedProfilesBillidInE30E($pd['PROFILEID'], $pd['BILLID'], $pd['EXPIRY_DT']);
                	list($ee10Cnt, $ee10billidArr) = $billPurObj->getRenewedProfilesBillidInEE10($pd['PROFILEID'], $pd['BILLID'], $pd['EXPIRY_DT']);
                	list($e10Cnt, $e10billidArr) = $billPurObj->getRenewedProfilesBillidInE10($pd['PROFILEID'], $pd['BILLID'], $pd['EXPIRY_DT']);
                	$misData[$pd['EXPIRY_DT']]['renewE30'][$pd['BILLID']] = $e30Cnt;
                	$misData[$pd['EXPIRY_DT']]['renewE30E'][$pd['BILLID']] = $e30eCnt;
                	$misData[$pd['EXPIRY_DT']]['renewEE10'][$pd['BILLID']] = $ee10Cnt;
                	$misData[$pd['EXPIRY_DT']]['renewE10'][$pd['BILLID']] = $e10Cnt;
                	$allBillids = array_unique(array_merge($e30BillidArr, $e30ebillidArr, $e10billidArr, $ee10billidArr));
                	if (!empty($allBillids)){
                		$misData[$pd['EXPIRY_DT']]['totalRev'][$pd['BILLID']] = $billPayDetObj->fetchAverageTicketSizeNexOfTaxForBillidArr($allBillids);
                	} else {
                		$misData[$pd['EXPIRY_DT']]['totalRev'][$pd['BILLID']] = 0;
                	}
                	unset($e30Cnt, $e30eCnt, $ee10Cnt, $e10Cnt, $e30BillidArr, $e30ebillidArr, $e10billidArr, $ee10billidArr, $allBillids);
                }
                // Set data for view 
                $this->misData = array();
                for($i = strtotime($start_date); $i <= strtotime($end_date); $i += 86400) {
                	if ($misData[date("Y-m-d", $i)]) {
                		$this->misData[date("j/M/y", $i)]['expiry'] = count($misData[date("Y-m-d", $i)]['expiry']);
                		$this->misData[date("j/M/y", $i)]['renewE30'] = array_sum($misData[date("Y-m-d", $i)]['renewE30']);
                		$this->misData[date("j/M/y", $i)]['renewE30E'] = array_sum($misData[date("Y-m-d", $i)]['renewE30E']);
                		$this->misData[date("j/M/y", $i)]['renewEE10'] = array_sum($misData[date("Y-m-d", $i)]['renewEE10']);
                		$this->misData[date("j/M/y", $i)]['renewE10'] = array_sum($misData[date("Y-m-d", $i)]['renewE10']);
                		$this->misData[date("j/M/y", $i)]['totalRev'] = array_sum($misData[date("Y-m-d", $i)]['totalRev']);
                	} else {
                		$this->misData[date("j/M/y", $i)]['expiry'] = 0;
                		$this->misData[date("j/M/y", $i)]['renewE30'] = 0;
                		$this->misData[date("j/M/y", $i)]['renewE30E'] = 0;
                		$this->misData[date("j/M/y", $i)]['renewEE10'] = 0;
                		$this->misData[date("j/M/y", $i)]['renewE10'] = 0;
                		$this->misData[date("j/M/y", $i)]['totalRev'] = 0;
                	}
                	$this->misData[date("j/M/y", $i)]['trsc'] = 0;
                	$this->misData[date("j/M/y", $i)]['convPerc'] = 0;
                }
                foreach ($this->misData as $key=>$val) {
                	$this->misData[$key]['tsrc'] = $val['renewE30'] + $val['renewE30E'] + $val['renewEE10'] + $val['renewE10'];
                	$this->misData[$key]['convPerc'] = round($this->misData[$key]['tsrc']/$val['expiry'], 2)*100;
                	$this->misData[$key]['totalRev'] = $val['totalRev'];
                }
                $this->totData = array();
                foreach ($this->misData as $key=>$val) {
                	$this->totData['expiry'] += $val['expiry'];
                	$this->totData['renewE30'] += $val['renewE30'];
                	$this->totData['renewE30E'] += $val['renewE30E'];
                	$this->totData['renewEE10'] += $val['renewEE10'];
                	$this->totData['renewE10'] += $val['renewE10'];
                	$this->totData['tsrc'] += $val['tsrc'];
                	$this->totData['totalRev'] += $val['totalRev'];
                }
                $this->totData['convPerc'] = round($this->totData['tsrc']/$this->totData['expiry'], 2)*100;
                
                if($formArr["report_format"]=="XLS")
                {   
            		if($formArr["range_format"]=="MY"){
                            $string .= "For_".$monthArr[$formArr["monthValue"]]."-".$formArr["yearValue"];
                    } else {
                            $string .= $start_date."_to_".$end_date;
                    }
                    $columns = array('expiry'=>'Number of subscriptions expiring','renewE30'=>'Number of subscriptions renewed before E-30','renewE30E'=>'Number of subscriptions renewed on [E-30 - E]','renewEE10'=>'Number of subscriptions renewed on ]E - E+10]','renewE10'=>'Number of subscriptions renewed after E+10','tsrc'=>'Total subscriptions renewed as of current date','convPerc'=>'Conversion %','totalRev'=>'Total Revenue from renewed subscriptions');
                    if($this->misData && is_array($this->misData))
					{
						$headerString = "Metric\t";
                        foreach ($this->misData as $key=>$val) {
                        	$headerString .= "{$key}\t";	
                        }
                        $headerString .= "Total\r\n";
                        $dates = array_keys($this->misData);
                        foreach ($columns as $key=>$name) {
                        	$dataString = $dataString.$name."\t";
							foreach ($dates as $k=>$date) {
								$dataString = $dataString.$this->misData[$date][$key]."\t";
							}
							$dataString = $dataString.$this->totData[$key]."\r\n";
						}
					}
					$xlData = $headerString.$dataString;
	                header("Content-Type: application/vnd.ms-excel");
	                header("Content-Disposition: attachment; filename=Renewal_Conversion_MIS.xls");
	                header("Pragma: no-cache");
	                header("Expires: 0");
	                echo $xlData;
                    die;
                } else {
                	$this->setTemplate('renewalConversionMISScreen1');
                }
            }
        }
    }
}
