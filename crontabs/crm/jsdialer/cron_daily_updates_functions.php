<?php
function compute_eligible_array($x,$db_js)
{
	$sql = "SELECT PROFILEID FROM incentive.IN_DIALER WHERE PROFILEID%10=$x AND ELIGIBLE!='N'";
        $res = mysql_query($sql,$db_js) or die("$sql".mysql_error($db_js));
        while($row = mysql_fetch_array($res))
                $eligible_array[] = $row["PROFILEID"];
	return $eligible_array;
}

function compute_ignore_array($x,$db_js)
{
        $sql = "SELECT PROFILEID FROM incentive.IN_DIALER WHERE PROFILEID%10=$x AND ELIGIBLE='N'";
        $res = mysql_query($sql,$db_js) or die("$sql".mysql_error($db_js));
        while($row = mysql_fetch_array($res))
                $ignore_array[] = $row["PROFILEID"];
	return $ignore_array;
}

function getVDdiscount($profiles_array,$db_js)
{
	$vd_profiles = array();	
        $profileid_str = implode(",",$profiles_array);
	if($profileid_str!='')
	{
	        $sql_vd="select PROFILEID,DISCOUNT from billing.VARIABLE_DISCOUNT WHERE SDATE<=CURDATE() AND EDATE>=CURDATE() AND PROFILEID IN ($profileid_str)";
        	$res_vd = mysql_query($sql_vd,$db_js) or die("$sql_vd".mysql_error($db_js));
		while($row_vd = mysql_fetch_array($res_vd))
                {
                        $pid = $row_vd["PROFILEID"];
                        $vd_profiles[$pid] = $row_vd["DISCOUNT"];
                }
	}
        return $vd_profiles;
}

function loginWithin15Days($profiles_array,$db_js)
{
	$profileid_str = implode(",",$profiles_array);
	if($profileid_str!='')
        {
	        $sql = "SELECT PROFILEID,LAST_LOGIN_DT FROM newjs.JPROFILE WHERE PROFILEID IN ($profileid_str)";
        	$res = mysql_query($sql,$db_js) or die("$sql".mysql_error($db_js));
	        while($row = mysql_fetch_array($res))
		{
			$pid = $row["PROFILEID"];
			if(strtotime($row["LAST_LOGIN_DT"])>=strtotime(date('Y-m-d',time()-15*86400)))
				$loginWithin15Days[$pid] = 1;
			else
				$loginWithin15Days[$pid] = 0;
		}
	}
	else
		$loginWithin15Days = array();
	return $loginWithin15Days;
}

function allotedArray($profiles_array,$db_js)
{
        $profileid_str = implode(",",$profiles_array);
        if($profileid_str!='')
        {
		$sql = "SELECT PROFILEID,ALLOTED_TO from incentive.MAIN_ADMIN WHERE PROFILEID IN ($profileid_str)";
                $res = mysql_query($sql,$db_js) or die("$sql".mysql_error($db_js));
                while($row = mysql_fetch_array($res))
                {
                        $pid = $row["PROFILEID"];
                        $alloted_array[$pid] = $row["ALLOTED_TO"];
                }
        }
        else
                $alloted_array = array();
        return $alloted_array;
}

function scoreArray($profiles_array,$db_js)
{
        $profileid_str = implode(",",$profiles_array);
        if($profileid_str!='')
        {
                $sql = "SELECT PROFILEID,ANALYTIC_SCORE from incentive.MAIN_ADMIN_POOL WHERE PROFILEID IN ($profileid_str)";
                $res = mysql_query($sql,$db_js) or die("$sql".mysql_error($db_js));
                while($row = mysql_fetch_array($res))
                {
                        $pid = $row["PROFILEID"];
                        $score_array[$pid] = $row["ANALYTIC_SCORE"];
                }
        }
        else    
                $score_array = array();
        return $score_array;
}

function stop_non_eligible_profiles($campaign_name,$x,$ignore_array,$db_dialer,$db_js_157,$vd_profiles)
{
	$squery1 = "SELECT easycode,PROFILEID,Dial_Status,VD_PERCENT FROM easy.dbo.ct_$campaign_name JOIN easy.dbo.ph_contact ON easycode=code WHERE status=0 AND PROFILEID%10=$x";
        $sresult1 = mssql_query($squery1,$db_dialer) or logError($squery1,$campaign_name,$db_dialer,1);
        while($srow1 = mssql_fetch_array($sresult1))
        {
		$ecode = $srow1["easycode"];
		$proid = $srow1["PROFILEID"];
		$vd_discount_dialer = $srow1["VD_PERCENT"];
		$updateStr='';
		if(in_array($proid,$ignore_array))
		{
			if($srow1["Dial_Status"]!='0' && $srow1["Dial_Status"]!='9' && $srow1["Dial_Status"]!='3')
				$updateStr ="Dial_Status=0";
			if(array_key_exists($proid,$vd_profiles))
                                $vdDiscount = $vd_profiles[$proid];
                        else
                                $vdDiscount = 0;
			if($updateStr && $vdDiscount != $vd_discount_dialer)
				$updateStr.=',';
			if($vdDiscount != $vd_discount_dialer)
			{	
				if(!$vdDiscount)
					$updateStr.='VD_PERCENT=0';
				else
					$updateStr.="VD_PERCENT=$vdDiscount";
			}
			if($updateStr!='')
			{
				$query1 = "UPDATE easy.dbo.ct_$campaign_name SET $updateStr WHERE easycode='$ecode'";
				mssql_query($query1,$db_dialer) or logError($query1,$campaign_name,$db_dialer,1);

				$log_query = "INSERT into js_crm.DIALER_UPDATE_LOG (PROFILEID,CAMPAIGN,UPDATE_STRING,TIME,ACTION) VALUES ('$proid','$campaign_name','$updateStr',now(),'STOP')";
				mysql_query($log_query,$db_js_157) or die($log_query.mysql_error($db_js_157));
			}
		}
	}
}

function update_data_of_eligible_profiles($campaign_name,$x,$eligible_array,$db_dialer,$vd_profiles,$loggedinWithin15days,$allotedArray,$scoreArray,$db_js_157)
{
	$squery2 = "SELECT easycode,PROFILEID,easy.dbo.ct_$campaign_name.AGENT,old_priority,VD_PERCENT,SCORE,Dial_Status FROM easy.dbo.ct_$campaign_name JOIN easy.dbo.ph_contact ON easycode=code WHERE status=0 AND PROFILEID%10=$x";
	$sresult2 = mssql_query($squery2,$db_dialer) or logError($squery2,$campaign_name,$db_dialer,1);
	while($srow1 = mssql_fetch_array($sresult2))
	{
		$dialer_data["initialPriority"]=$srow1["old_priority"];
		$ecode = $srow1["easycode"];
		$proid = $srow1["PROFILEID"];
		$dialer_data["profileid"] = $srow1["PROFILEID"];
		$dialer_data["allocated"] = $srow1["AGENT"];
		$dialer_data["discount"] = $srow1["VD_PERCENT"];
		$dialer_data["analytic_score"] = $srow1["SCORE"];
		$dialer_data["dial_status"] = $srow1["Dial_Status"];
		if(in_array($proid,$eligible_array))
		{
			$query1 = "";
			$jp_condition_str=data_comparision($dialer_data,$campaign_name,$ecode,$db_dialer,$vd_profiles,$loggedinWithin15days,$allotedArray,$scoreArray,$db_js_157);
			$jp_condition_arr=explode("*",$jp_condition_str);
			if($jp_condition_arr[0]!='ignore')
			{
                        	$query1 = "UPDATE easy.dbo.ct_$campaign_name SET $jp_condition_arr[0] WHERE easycode='$ecode'";
                                mssql_query($query1,$db_dialer) or logError($query1,$campaign_name,$db_dialer,1);
				$ustr = str_replace("'","",$jp_condition_arr[0]);
				$log_query = "INSERT into js_crm.DIALER_UPDATE_LOG (PROFILEID,CAMPAIGN,UPDATE_STRING,TIME,ACTION) VALUES ('$proid','$campaign_name','$ustr',now(),'UPDATE')";
	                        mysql_query($log_query,$db_js_157) or die($log_query.mysql_error($db_js_157));

			}
			if(count($jp_condition_arr)>1)
			{
				if($jp_condition_arr[1])
        	                {
					$query2 = "UPDATE easy.dbo.ph_contact SET $jp_condition_arr[1] WHERE code='$ecode' AND priority <=6";
                        	        mssql_query($query2,$db_dialer) or logError($query2,$campaign_name,$db_dialer,1);
					$ustr1 = str_replace("'","",$jp_condition_arr[1]);
					$log_query = "INSERT into js_crm.DIALER_UPDATE_LOG (PROFILEID,CAMPAIGN,UPDATE_STRING,TIME,ACTION) VALUES ('$proid','$campaign_name','$ustr1',now(),'UPDATE-PRIORITY')";
	                               	mysql_query($log_query,$db_js_157) or die($log_query.mysql_error($db_js_157));
                        	}
			}
			$sql_chk="select AGENT from easy.dbo.ct_$campaign_name where easycode='$ecode'";
			$sresult_chk = mssql_query($sql_chk,$db_dialer) or logError($sql_chk,$campaign_name,$db_dialer,1);
			$row_chk= mssql_fetch_array($sresult_chk);
		
			if(!$row_chk["AGENT"])
			{
				$query_ph2 = "UPDATE easy.dbo.ph_contact SET Agent=NULL WHERE code='$ecode'";
				mssql_query($query_ph2,$db_dialer) or logError($query_ph2,$campaign_name,$db_dialer,1);
				$log_query = "INSERT into js_crm.DIALER_UPDATE_LOG (PROFILEID,CAMPAIGN,UPDATE_STRING,TIME,ACTION) VALUES ('$proid','$campaign_name','Agent=NULL',now(),'UPDATE-AGENT_NOT_EXIST')";
                                mysql_query($log_query,$db_js_157) or die($log_query.mysql_error($db_js_157));
			}
		}
		unset($dialer_data);
	}
}

function data_comparision($dialer_data,$campaign_name,$ecode,$db_dialer,$vd_profiles,$loggedinWithin15days,$allotedArray,$scoreArray,$db_js_157)
{
	$profileid = $dialer_data["profileid"];
	$update_str='';
	$dialStatus =$dialer_data["dial_status"];

	//VD_PERCENT
	if(array_key_exists($profileid,$vd_profiles))
                $vd_percent=$vd_profiles[$profileid];
        else
                $vd_percent=0;
	if($vd_percent!=$dialer_data['discount'])
	{ 
		if(!$vd_percent)
			$update_str="VD_PERCENT='0'";
		else
			$update_str="VD_PERCENT='$vd_percent'";
	}

	//ANALYTIC_SCORE
	if(array_key_exists($profileid,$scoreArray))
                $score=$scoreArray[$profileid];
        else
                $score='';
        if($score!=$dialer_data['analytic_score'] && $score!='')
        {
                if($update_str=='')
                        $update_str.="SCORE='$score'";
                else
                        $update_str.=",SCORE='$score'";
        }

	//AGENT & Dial_Status
	if(array_key_exists($profileid,$allotedArray))
                $alloted_to = $allotedArray[$profileid];
        else
                $alloted_to = '';
	if($alloted_to!=$dialer_data['allocated'])
	{
		if($alloted_to != '')
		{
			if($update_str=='')
				$update_str.="easy.dbo.ct_$campaign_name.AGENT='$alloted_to'";
			else
				$update_str.=",easy.dbo.ct_$campaign_name.AGENT='$alloted_to'";
			if($dialStatus!=3 && $dialStatus!=9)
				$update_str.=",Dial_Status='2'";
		}
		else
		{
			$query_ph1 = "UPDATE easy.dbo.ph_contact SET Agent=NULL WHERE code='$ecode'";
                        mssql_query($query_ph1,$db_dialer) or logError($query_ph1,$campaign_name,$db_dialer,1);
			$log_query = "INSERT into js_crm.DIALER_UPDATE_LOG (PROFILEID,CAMPAIGN,UPDATE_STRING,TIME,ACTION) VALUES ('$profileid','$campaign_name','Agent=NULL',now(),'UPDATE-AGENT_NULL')";
                        mysql_query($log_query,$db_js_157) or die($log_query.mysql_error($db_js_157));

			if($update_str=='')
                                $update_str.="easy.dbo.ct_$campaign_name.AGENT='$alloted_to'";
                        else
                                $update_str.=",easy.dbo.ct_$campaign_name.AGENT='$alloted_to'";
			if($dialStatus!='9' && $dialStatus!=3 && $loggedinWithin15days[$profileid])
                                $update_str.=",Dial_Status='1'";
			elseif(!$loggedinWithin15days[$profileid] && $dialStatus!=3 && $dialStatus!=9)
                        {       
                                if($update_str=='')
                                        $update_str.="Dial_Status='0'";
                                else
                                        $update_str.=",Dial_Status='0'";
                        }
		}
	}
	elseif($dialer_data['allocated']!='' && $dialStatus!='2' && $dialStatus!='9' && $dialStatus!=3)
	{
		if($update_str=='')
                	$update_str.="Dial_Status='2'";
                else
                	$update_str.=",Dial_Status='2'";
	}
	elseif($dialStatus!='1' && $dialStatus!='9' && $dialStatus!=3 && $loggedinWithin15days[$profileid])
	{
		if($update_str=='')
                        $update_str.="Dial_Status='1'";
                else
                        $update_str.=",Dial_Status='1'";
	}
	elseif(!$loggedinWithin15days[$profileid] && $dialStatus!=3 && $dialStatus!=9)
        {
                if($update_str=='')
                        $update_str.="Dial_Status='0'";
                else
                        $update_str.=",Dial_Status='0'";
        }

	//INITIAL PRIORITY UPDATE 
	$priority=0;
	if($alloted_to=='')
	{
		if($score>=81 && $score<=100)
                        $priority='2';
                elseif($score>=41 && $score<=80)
                        $priority='1';
		else
			$priority='0';
	}
	else
		$priority='0';
        if($priority!=$dialer_data['initialPriority'] && $priority!='')
        {
		if($update_str=='')
                        $update_str.="old_priority='$priority'";
                else
                        $update_str.=",old_priority='$priority'";
		//$update_str.="*priority='$priority'";
        }

	if($update_str!=''){
		$update_str.="*priority='$priority'";
                return $update_str;
	}
        else
                return "ignore";
}

function logError($sql,$campaignName='',$dbConnect='',$ms='')
{
	$dialerLogObj =new DialerLog();
        $dialerLogObj->logError($sql,$campaignName,$dbConnect,$ms);
}
?>
