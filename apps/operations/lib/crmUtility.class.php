<?php
class crmUtility 
{
	public function fetchIST($time)
	{
	        $ISTtime=strftime("%Y-%m-%d %H:%M",JSstrToTime("$time + 10 hours 30 minutes"));
	        return $ISTtime;
	}
        public function getIST($dateTime='')
        {
		$mainAdminObj =new incentive_MAIN_ADMIN();
		$dateTime =$mainAdminObj->getIST($dateTime);
                return $dateTime;
        }
	public function fetchActiveStatus($activated,$incomplete)
	{
	        $message='';
	        if($incomplete=='N')
	                $message.="The profile is complete<br>";
	        elseif($incomplete=='Y')
	                $message.="The profile is incomplete<br>";
	        if($activated=='D')
	                $message.="The profile is deleted";
	        elseif($activated=='N')
	                $message.="The profile is not activated";
	        elseif($activated=='Y')
	                $message.="The profile is activated";
	        elseif($activated=='H')
	                $message.="The profile is hidden";
	        elseif($activated=='U')
	                $message.="The profile is Under Screening";
        	return $message;
	}
	public function fetchPayMode($paymode,$curtype)
	{
        	if(stristr("cheque",$paymode))
        	        $type="Cheque";
        	elseif (stristr("card",$paymode))
        	        $type="Card";
        	if ($curtype == 'RS')
        	        $msg="Tried by $type in Rupees";
        	elseif ($curtype == 'DOL')
        	        $msg="Tried by $type in USD";
        	return $msg;
	}
	public function populateDisposition($sel_val=0)
	{
        	$j=0;
		$dispositionObj =new incentive_CRM_DISPOSITION();
		$validationObj  =new incentive_DISPOSITION_VALIDATION();

		$myrow_d= $dispositionObj->getDisposition();
        	foreach($myrow_d as $key=>$val){
        	        $will_pay_arr[$j]['val'] =$val['DISPOSOTION_VALUE'];
        	        $will_pay_arr[$j]['label'] =$val['DISPOSOTION_LABEL'];
        	        $j++;
        	}
        	for ($i = 0,$j=0;$j<count($will_pay_arr);$j++){
			$val = $will_pay_arr[$j]['val'];
        	        $strtemp = '';
        	        $will_pay_value[]=$will_pay_arr[$j]['val'];
        	        $will_pay_label[]=$will_pay_arr[$j]['label'];
        	        $strtemp .= $will_pay_value[$j]."|X|";

			$myrow1 =$validationObj->getdisValidation($val);
			foreach($myrow1 as $key1=>$val1){
        	                if($val1['VALIDATION_VALUE']!='DNC1'){
        	                        $reason_value[]=$val1['VALIDATION_VALUE'];
        	                        $reason_label[]=$val1['VALIDATION_LABEL'];
        	                        $strtemp .= $reason_value[$i]."$".$reason_label[$i]."#";
        	                        $i++;
        		         }
        	        }
        	        $strtemp = substr($strtemp,0,(strlen($strtemp)-1));
        	        $str[] = $strtemp;
        	}
        	for($x=0;$x<count($str);$x++){
        	        $str_temp = explode('|X|',$str[$x]);
        	        $str_val = $str_temp[0];
        	        if($sel_val == $str_val)
        	                $newstr.="<option value=\"" . $str[$x] . "\" selected>" . $will_pay_label[$x] . "</option>\n";
        	        else
        	                $newstr.="<option value=\"" . $str[$x] . "\">" . $will_pay_label[$x] . "</option>\n";
        	}
        	return $newstr;	 
	}
	public function getFollowupDate($profileid='',$name='',$followupDateSet='')
	{
		// Code added for the selected followup time parsing
		if($followupDateSet){
			list($dateSet,$hoursSet,$minSet) =@explode("-",$followupDateSet);
			$hoursSetArr =@explode(" ",$hoursSet);
		}

	        $mainAdminObj =new incentive_MAIN_ADMIN();
		if($profileid)
			$row =$mainAdminObj->getCurrentAllocDetails($profileid);
	        if($row['PROFILEID']){	
                        $status         =$row['STATUS'];
                        if($status=='P'){
                                $agentAllocationObj =new AgentAllocation();
                                $deAllocDate        =$agentAllocationObj->fetchDeAllocationDate('RENEWAL',$profileid);
                        }
                        else
                                $deAllocDate =$row['DE_ALLOCATION_DT'];
                        $allotTime  =$row['ALLOT_TIME'];
	        }
	        else{
			$allotTime		=date("Y-m-d H:i:s");
			$agentAllocationObj	=new AgentAllocation(); 
			$deAllocDate		=$agentAllocationObj->fetchDeAllocationDate('NEW_PROFILES');
	        }
		$totDays =(JSstrToTime($deAllocDate)-JSstrToTime($allotTime))/86400;
	        $start_dt = date("d M Y",time());
	        $totDays = $totDays-((time()-JSstrToTime($allotTime))/86400);

		$followup_dd['follow_time']="<option value=\"" . 0 . "\">" . "Select" . "</option>\n";
		if($start_dt==$dateSet)
			$followup_dd['follow_time'].="<option value=\"" . $start_dt . "\"selected >" . $start_dt . "</option>\n";
		else
	        	$followup_dd['follow_time'].="<option value=\"" . $start_dt . "\">" . $start_dt . "</option>\n";

		// followup date 
	        for($x=0;$x<$totDays;$x++)
	        {
	                $follow_dts = date("d M Y",(time()+(($x+1)*86400)));
			if($follow_dts==$dateSet)
				$followup_dd['follow_time'].="<option value=\"" . $follow_dts . "\"selected>" . $follow_dts . "</option>\n";
			else	
	                	$followup_dd['follow_time'].="<option value=\"" . $follow_dts . "\">" . $follow_dts . "</option>\n";
	        }
		// followup hours
	        for($x=0,$hour=8;$x<=12;$x++){
	                if($hour>11)
	                        $hour=1;
	                else
	                        $hour++;
	                if($x<3)
	                        $suffix = "am";
	                else
	                        $suffix = "pm";
			if($hour==$hoursSetArr[0] && $suffix==$hoursSetArr[1])
				$followup_dd['hour'].="<option value=\"" . $hour." ".$suffix . "\"selected >" . $hour." ".$suffix . "</option>\n";
			else
	                	$followup_dd['hour'].="<option value=\"" . $hour." ".$suffix . "\">" . $hour." ".$suffix . "</option>\n";
	        }
		// followup minutes
	        for($x=0;$x<60;$x=$x+15){
	                if(!$x)
	                        $followup_dd['min'].="<option value=\"" . $x . "\">" . $x.$x . "</option>\n";
	                elseif($x==$minSet)
	                        $followup_dd['min'].="<option value=\"" . $x . "\"selected>" . $x . "</option>\n";
			else
				$followup_dd['min'].="<option value=\"" . $x . "\">" . $x . "</option>\n";
	        }
        	return $followup_dd;
	}

	public function pageLink($pagelen,$totalrec,$curpage,$link,$scriptname="",$searchchecksum="",$flag="",$getold="",$showall="",$defaultsort="",$st_date="",$end_date="",$screener="",$self_profileid="")
	{
	        // set the links to be shown on each page to be five
	        $linkno=5;
	
	        $totalpage = $totalrec / $pagelen;
	        $totalpage = ceil($totalpage);
	        $curpage = round($curpage);
	        $startwith = $curpage / $linkno;
	        $startwith = abs(floor($startwith - 0.1));
	        $startwith = $startwith * $linkno;
	
	        if($totalpage > ($startwith + $linkno))
	        {
	                $totallinkshow = $linkno;
	                if($totalpage < ($startwith + ($linkno * 2)))
	                $lastlink = $startwith + $totalpage;
	                else
	                $lastlink = $startwith + $linkno + $totallinkshow;
	        }
	        else if($totalpage < ($startwith + ($linkno * 2)))
	        {
	                $totallinkshow = $totalpage - $startwith;
	                $lastlink = "";
	        


}
	        else
	        {
	                $totallinkshow = $totalpage - $startwith;
	                $lastlink = $startwith + $linkno + $totallinkshow;
	        }

        	$prevwith=$startwith-9;
        	$linkvar = "Page : <strong>";
        	if($startwith && !($startwith % $linkno))
        	        $linkvar .= "&nbsp;<span class=\"class6\"><a HREF=\"$scriptname?cid=$link&searchcksum=$searchchecksum&flag=$flag&defaultsort=$defaultsort&showall=$showall&getold=$getold&date1=$st_date&date2=$end_date&screener=$screener&pageIndex=".($startwith-1)*$pagelen."&self_profileid=$self_profileid\">&lt;&lt;</a></span>&nbsp;";


        	for($i=1;$i<=$totallinkshow;$i++)
        	{
                	$nos = $startwith+$i;
                	if($nos != $curpage)
                	        $linkvar .= "<span class=\"class6\"><a HREF=\"$scriptname?cid=$link&searchchecksum=$searchchecksum&flag=$flag&defaultsort=$defaultsort&showall=$showall&getold=$getold&date1=$st_date&date2=$end_date&screener=$screener&pageIndex=".($nos-1)*$pagelen."&self_profileid=$self_profileid\">";
                	else
                	        $linkvar .= "&nbsp;";
	                $linkvar .= "$nos</a></span>&nbsp;";
	        }
	        if($lastlink)
	        {
	                $nos = $startwith+$i;
	                $linkvar .= "&nbsp;<span class=\"class6\"><a HREF=\"$scriptname?cid=$link&searchchecksum=$searchchecksum&flag=$flag&defaultsort=$defaultsort&showall=$showall&getold=$getold&date1=$st_date&date2=$end_date&screener=$screener&pageIndex=".($nos-1)*$pagelen."&self_profileid=$self_profileid\">";
	                $linkvar .= "&gt;&gt;</strong></a></span>&nbsp;";
	        }

	        $linkvar.= "&lt;<strong><font color=\"#999999\">";

	        if($curpage > 1)
	                $linkvar .= "<span class=\"class6\"><a HREF=\"$scriptname?cid=$link&searchchecksum=$searchchecksum&flag=$flag&defaultsort=$defaultsort&showall=$showall&getold=$getold&date1=$st_date&date2=$end_date&screener=$screener&pageIndex=".($curpage-2)*$pagelen."&self_profileid=$self_profileid\">Previous</a></span>";
	        else
	                $linkvar.="Previous";
	
	        $linkvar.="</font></strong> <strong>|";
	
	        $linkvar.="<font color=\"#999999\">";
	        if($curpage < $totalpage)
	                $linkvar .= "<span class=\"class6\"><a HREF=\"$scriptname?cid=$link&searchchecksum=$searchchecksum&flag=$flag&defaultsort=$defaultsort&showall=$showall&getold=$getold&date1=$st_date&date2=$end_date&screener=$screener&pageIndex=". $curpage*$pagelen ."&self_profileid=$self_profileid\">Next</a></span>";
	        else
	                $linkvar.="Next";
	        $linkvar.="</font>";
	
	       	$linkvar.="&gt;</strong>";
	        if($totalpage==0)
	                $linkvar="";
	        return $linkvar;
	}
        public function fetchHourDetails($hour)
        {
                if(strstr($hour,'am')){
                        if($hour=="9 am")
                                $hour = "09";
                        else
                                $hour = substr($hour,0,2);
                }
                else{
                        if($hour=="12 pm")
                                $hour = "12";
                        else
                                $hour = substr($hour,0,1)+12;
                }
		return $hour;
        }
	public function fetchDateDropdown()
	{
                for($i=0;$i<31;$i++)
                        $ddarrDisp[$i]=$i+1;
                for($i=0;$i<12;$i++)
                        $mmarrDisp[$i]=$i+1;
                for($i=2004;$i<=date("Y");$i++)
                        $yyarrDisp[$i-2004]=$i;
		$dateArr =array("ddarr"=>$ddarrDisp,"mmarr"=>$mmarrDisp,"yyarr"=>$yyarrDisp);
		return $dateArr; 
	}
	public function fetchDates($request)
	{
                $getold   =$request->getParameter("getold");
                $yy1      =$request->getParameter("yy1");
                $mm1      =$request->getParameter("mm1");
                $dd1      =$request->getParameter("dd1");
                $yy2      =$request->getParameter("yy2");
                $mm2      =$request->getParameter("mm2");
                $dd2      =$request->getParameter("dd2");
                if($getold || ($yy1 && $mm1 && $dd1 && $yy2 && $mm2 && $dd2)){
                        if($getold){
                                list($st_dt,$end_dt)=explode("--",$getold);
                                $st_dt.=" 00:00:00";
                                $end_dt.=" 23:59:59";
                        }else{
                                $st_dt=$yy1."-".$mm1."-".$dd1." 00:00:00";
                                $end_dt=$yy2."-".$mm2."-".$dd2." 23:59:59";
                                $getold=$yy1."-".$mm1."-".$dd1."--".$yy2."-".$mm2."-".$dd2;
                        }
                }
		$datesArr['start_dt'] 	=$st_dt;
		$datesArr['end_dt'] 	=$end_dt;
		$datesArr['getold']     =$getold;
		return $datesArr;
	}
	public function populateCallSource()
	{
	        $call_source = array(
                                array('name' => 'Chat', 'value' => 'CHAT'),
                                array('name' => 'FAQ', 'value' => 'FAQ'),
                                array('name' => 'Inbound', 'value' => 'INB'),
                                array('name' => 'Mailer', 'value' => 'MAIL'),
                                array('name' => 'Walk-In', 'value' => 'WALKIN'),
                                array('name' => 'Offline order', 'value' => 'OFFORDER'),
                                array('name' => 'Confirm client', 'value' => 'CONCL'),
                                array('name' => 'FP', 'value' => 'FP'),
                                array('name' => 'Old client', 'value' => 'OCL'),
                                array('name' => 'Reference call', 'value' => 'REF'),
                                array('name' => 'Tele calling data', 'value' => 'TELE'),
                                array('name' => 'Online search link', 'value' => 'ONSEARCH'),
                                array('name' => 'Renewal Campaign', 'value' => 'RC'),
                                array('name' => 'VD data', 'value' => 'VD'),
                                array('name' => 'High Score VD', 'value' => 'HSVD'),
                                array('name' => 'Request Callback', 'value' => 'RCB')
                                );
        	return $call_source;
	}
	public function populateQueryType()
	{
	        $query_type = array(
                                array('name' => 'Branch Details', 'value' => 'BDET'),
                                array('name' => 'Cheque Pick Up', 'value' => 'CHPK'),
                                array('name' => 'DOB Change', 'value' => 'DOBC'),
                                array('name' => 'Feedback on Website', 'value' => 'FDBK'),
                                array('name' => 'Gender Change', 'value' => 'GENC'),
                                array('name' => 'Jeevansathi Messenger Related', 'value' => 'JMSN'),
                                array('name' => 'Match Alert', 'value' => 'MA'),
                                array('name' => 'Membership Features and Benefits', 'value' => 'MEMB'),
                                array('name' => 'Membership Fee', 'value' => 'MEMF'),
                                array('name' => 'Offers and Scheme Query', 'value' => 'OFFRS'),
                                array('name' => 'Payment Details', 'value' => 'PD'),
                                array('name' => 'Payment Mode', 'value' => 'PM'),
                                array('name' => 'Photo Upload', 'value' => 'PU'),
                                array('name' => 'Screening of Profile', 'value' => 'SCP'),
                                array('name' => 'Registration', 'value' => 'REG'),
                                array('name' => 'Website Complaint', 'value' => 'WEBCOMP'),
                                array('name' => 'Website Query', 'value' => 'WEBQ'),
                                );
	        return $query_type;
	}
        public function checkemail($email)// returns 1 if email id not valid
        {
                $flag=0;
                $email =trim($email);
                if($email=='')
                        $flag=1;
                elseif (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email))
                        $flag=1;
                else
                        $flag=0;
                return $flag;
        }
	public function getCurlData($profileid,$username='',$cid)
	{	
		$SITE_URL   =JsConstants::$crmUrl;
		$actualUrl  =JsConstants::$siteUrl;	
	       
	       	$tuCurl = curl_init();
		//$uname=urlencode($username);
        	//curl_setopt($tuCurl, CURLOPT_URL, "$SITE_URL/crm/show_profile.php?profileid=$profileid&username=$uname&cid=$cid");
		curl_setopt($tuCurl, CURLOPT_URL,"$SITE_URL/operations.php/commoninterface/ShowProfileStats?cid=$cid&profileid=$profileid&curlReq=1&actualUrl=$actualUrl");
        	curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1);
        	$tuData = curl_exec($tuCurl);
        	curl_close($tuCurl);
        	return $tuData;
	}
	public function fetchPrivilegeLinks($privilege)
	{
		$privilegeArr =@explode("+",$privilege);

		$priv1 =array('ExcFP','FPSUP','ExcDIb','INBSUP','ExcDOb');
		$priv2 =array('ExcBSD','ExcBID','ExcFSD','ExcFID','ExcPrm','ExPrmO','ExPrmI','ExcWFH','SLMNTR','SLSUP','SLHD');
		$priv3 =array('ExcRnw','RnwSup');
		$priv4 =array('ExcUpS','UpSSup');
		$priv5 =array('FTAFTO','FTASup');
		$priv6 =array('P','MG','TRNG');
		$priv7 =array('ExcFP','FPSUP');
		$priv8 =array('CSV');
		$priv9 =array('ExcFld','SupFld');
		$priv10=array('PreAll','ExcPrm','RmnInd');
		$priv11=array('ExcEP','PreNri');
		$priv12=array('ExcWL','ExPmWL');
                $billingPriv 	=array('BU','BA');
                $aramexPriv 	=array('IUP');
		$offlineExclusive =array('OA','OB');

		$allPrivilege		=array_merge($priv1,$priv2,$priv3,$priv4,$priv5,$priv6);
		$failedPaymentPriv 	=array_merge($priv1,$priv2,$priv3,$priv4,$priv6);
		$expiringNext30DaysPriv	=array_merge($priv1,$priv2,$priv3,$priv4,$priv6);
		$renewalNotDue 		=array_merge($priv1,$priv2,$priv3,$priv4,$priv6);
		$newProfilesPriv 	=array_merge($priv2,$priv6,$priv11);
		$renewalPriv 		=array_merge($priv3,$priv6);
		$upsellPriv 		=array_merge($priv4,$priv6);
		$ftoPriv 		=array_merge($priv5,$priv6);
		$newFailedPaymentPriv   =array_merge($priv6,$priv7);
		$newWebmasterLeadsPriv  =array_merge($priv6,$priv12);
		$csvPriv		=$priv8;
		$newFieldSales   	=array_merge($priv6,$priv9);
		$onlineNewProfilesPriv  =array_merge($priv10);
	
		foreach($privilegeArr as $key=>$val){

			if(in_array("$val",$newFieldSales))
				$linkArr['NFS'] ='Y';
			if(in_array("$val",$newFailedPaymentPriv))
				$linkArr['NFP'] ='Y';
			if(in_array("$val",$newWebmasterLeadsPriv))
                                $linkArr['WL'] ='Y';
			if(in_array("$val",$failedPaymentPriv))
				$linkArr['FP'] ='Y';
                        if(in_array("$val",$allPrivilege))
                                $linkArr['ON'] ='Y';
			if(in_array("$val",$onlineNewProfilesPriv))
				$linkArr['ONP'] ='Y';
                        if(in_array("$val",$allPrivilege))
                                $linkArr['F'] ='Y';
                        if(in_array("$val",$expiringNext30DaysPriv))
                                $linkArr['S'] ='Y';
                        if(in_array("$val",$renewalNotDue))
                                $linkArr['RND'] ='Y';
                        if(in_array("$val",$newProfilesPriv))
                                $linkArr['N'] ='Y';
                        if(in_array("$val",$renewalPriv))
                                $linkArr['R'] ='Y';
                        if(in_array("$val",$upsellPriv))
                                $linkArr['U'] ='Y';
                        if(in_array("$val",$ftoPriv))
                                $linkArr['FTA'] ='Y';
                        if(in_array("$val",$allPrivilege))
                                $linkArr['C'] ='Y';
                        if(in_array("$val",$allPrivilege))
                                $linkArr['FF'] ='Y';
                        if(in_array("$val",$allPrivilege))
                                $linkArr['FS'] ='Y';
			if(in_array("$val",$csvPriv))
				$linkArr['CSV'] ='Y';
                        if(in_array("$val",$billingPriv))
                                $linkArr['BILLING'] ='Y';
                        if(in_array("$val",$aramexPriv))
                                $linkArr['ARAMEX'] ='Y';
			if(in_array("$val",$offlineExclusive))
				$linkArr['OFFLINE_EXECUTIVE'] ='Y';
		}	
		return $linkArr;	
	}
    	public function getProcessName($priv){
		if(strpos($priv, 'ExcWL') !== false || strpos($priv, 'SUPWL') !== false ){
			$process ='RCB_TELE';			
		}
        	else if(strpos($priv, 'ExcDIb') !== false){
        	    	$process ='INBOUND_TELE';
        	}
        	else if(strpos($priv, 'ExcBSD') !== false || strpos($priv, 'ExcBID') !== false){
        	        $process ='CENTER_SALES';
        	}
        	else if(strpos($priv, 'ExcFP') !== false){
        	        $process ='FP_TELE';
        	}
        	else if(strpos($priv, 'ExcRnw') !== false){
        	        $process ='CENTRAL_RENEW_TELE';
        	}
        	else if(strpos($priv, 'ExcFld') !== false){
        	        $process ='FIELD_SALES';
        	}
        	else if(strpos($priv, 'ExcFSD') !== false || strpos($priv, 'ExcFID') !== false){
        	        $process ='FRANCHISEE_SALES';
        	}
        	else if(strpos($priv, 'ExcDOb') !== false || strpos($priv, 'ExcPrm') !== false || strpos($priv, 'PreNri') !== false){
        	        $process ='OUTBOUND_TELE';
        	}
        	else{
        	        $process ='UNASSISTED_SALES';
        	}
		return $process;
    	}
        public function getProcessLimit(){
		$allocationLimitObj =new incentive_ALLOCATION_LIMIT_CRM('newjs_slave');	
		$limitArr =$allocationLimitObj->getAllocationLimit();
		return $limitArr;
	}	
				
}	
