<?php
include_once('DialerLog.class.php');
include_once('DialerHandler.class.php');
class DialerDncScrubing 
{
        public function __construct($db_js, $db_js_111, $db_dialer){
                $this->db_js            =$db_js;
                $this->db_js_111        =$db_js_111;
                $this->db_dialer        =$db_dialer;
        }
	public function compute_dnc_array($campaign_name, $dateTime='')
	{
		$dnc_array = array();
		$squery1 = "SELECT PROFILEID FROM easy.dbo.ct_$campaign_name JOIN easy.dbo.ph_contact ON easycode=code WHERE Dial_Status='9'";
		if($dateTime) 
			$squery1 .=" and Login_Timestamp>='$dateTime'";
		$sresult1 = mssql_query($squery1,$this->db_dialer) or $this->logError($squery1,$campaign_name,$this->db_dialer,1);
		while($srow1 = mssql_fetch_array($sresult1)){
			if($srow1["PROFILEID"]>0)
				$dnc_array[] = $srow1["PROFILEID"];
		}
		return $dnc_array;
	}
        public function compute_dnc_array_forSalesCampaign($campaign_name, $leadId)
        {
		$squery1 ="SELECT distinct(PHONE_NO1) as PHONE_NO  FROM easy.dbo.ct_$campaign_name JOIN easy.dbo.ph_contact ON easycode=code WHERE Dial_Status='9' and Lead_id='$leadId'";
        	$sresult1 = mssql_query($squery1,$this->db_dialer) or $this->logError($squery1,$campaign_name,$this->db_dialer,1);
        	while($srow1 = mssql_fetch_array($sresult1))
        	{
                	$phoneNo1 =$srow1["PHONE_NO"];
                	$phoneNo =$this->phoneNumberCheck($phoneNo1);
                	if($phoneNo){
                        	$profileArr =$this->getProfileDetails($phoneNo);
                        }
			$phoneArr[$phoneNo1] =$profileArr;
                }
		return $phoneArr;
        }
	public function compute_opt_in_array($dnc_array)
	{
		$opt_in_profiles = array();
		$profileid_str = implode(",",$dnc_array);
		if($profileid_str!=''){
			$sql_vd="select PROFILEID from newjs.CONSENT_DNC WHERE PROFILEID IN ($profileid_str)";
			$res_vd = mysql_query($sql_vd,$this->db_js_111) or $this->logError($sql_vd,$campaign_name,$this->db_js_111); 
			while($row_vd = mysql_fetch_array($res_vd))
				$opt_in_profiles[] = $row_vd["PROFILEID"];
		}
		return $opt_in_profiles;
	}
	public function compute_eligible_in_array($dnc_array,$renewal='',$autoCampaign='')
	{
        	$eligible_profiles = array();
        	$profileid_str = implode(",",$dnc_array);
        	if($profileid_str!=''){
			if($autoCampaign)
				$table ='incentive.IN_DIALER_NEW';
        	        elseif($renewal)
        	                $table ='incentive.RENEWAL_IN_DIALER';
        	        else
        	                $table ='incentive.IN_DIALER';
        	        $sql_vd="select PROFILEID from ".$table." WHERE PROFILEID IN ($profileid_str) AND ELIGIBLE='Y'";
        	        $res_vd = mysql_query($sql_vd,$this->db_js_111) or $this->logError($sql_vd,$campaign_name,$this->db_js_111);
        	        while($row_vd = mysql_fetch_array($res_vd))
        	                $eligible_profiles[] = $row_vd["PROFILEID"];
        	}
        	return $eligible_profiles;
	}
	function start_opt_in_profiles($campaign_name,$opt_in_profile,$dateTime='',$autoCampaign='')
	{
		$dialerHandlerObj =new DialerHandler($this->db_js, $this->db_js_111, $this->db_dialer);
		if($autoCampaign)
			$squery1 = "SELECT SelectedOption,Call_Start_Time,easycode,PROFILEID,easy.dbo.ct_$campaign_name.AGENT FROM easy.dbo.ct_$campaign_name JOIN easy.dbo.ph_contact ON easycode=code WHERE PROFILEID ='$opt_in_profile'";
		else
			$squery1 = "SELECT easycode,PROFILEID,easy.dbo.ct_$campaign_name.AGENT FROM easy.dbo.ct_$campaign_name JOIN easy.dbo.ph_contact ON easycode=code WHERE PROFILEID ='$opt_in_profile'";
                if($dateTime)
                        $squery1 .=" and Login_Timestamp>='$dateTime'";

		$sresult1 = mssql_query($squery1,$this->db_dialer) or $this->logError($squery1,$campaign_name,$this->db_dialer,1);
		while($srow1 = mssql_fetch_array($sresult1))
		{
			$ecode = $srow1["easycode"];
			$proid = $srow1["PROFILEID"];
			$alloted = $srow1['AGENT'];
			if($ecode){
	                        if($alloted)
	                                $dialStatus ='2';
	                        else
	                                $dialStatus ='1';

				if($autoCampaign && $dialStatus==1){
		                        $SelectedOption = $dialer_data["SelectedOption"];
        		                $Call_Start_Time = $dialer_data["Call_Start_Time"];
					$dialStatus =$dialerHandlerObj->getAutoCampaignDialStatus($SelectedOption,$Call_Start_Time);
				}
				$query1 = "UPDATE easy.dbo.ct_$campaign_name SET Dial_Status=$dialStatus WHERE easycode='$ecode'";
				mssql_query($query1,$this->db_dialer) or $this->logError($query1,$campaign_name,$this->db_dialer,1);

				$updateString ='Dial_Status='.$dialStatus;
				$log_query = "INSERT into js_crm.DIALER_UPDATE_LOG (PROFILEID,CAMPAIGN,UPDATE_STRING,TIME,ACTION) VALUES ('$proid','$campaign_name','$updateString',now(),'OPTIN')";
				mysql_query($log_query,$this->db_js_111) or $this->logError($log_query, $campaign_name, $this->db_js_111); 
			}
		}
	}
        function start_opt_in_profiles_forSalesCampaign($campaign_name,$phoneNo,$leadId)
        {
		$squery1 = "SELECT easycode FROM easy.dbo.ct_$campaign_name JOIN easy.dbo.ph_contact ON easycode=code WHERE status=0 AND Dial_Status='9' and PHONE_NO1='$phoneNo' and Lead_id='$leadId'";
                $sresult1 = mssql_query($squery1,$this->db_dialer) or $this->logError($squery1,$campaign_name,$this->db_dialer,1);
                while($srow1 = mssql_fetch_array($sresult1))
                {
                        $ecode = $srow1["easycode"];
                        if($ecode){
				$dialStatus =1;
                                $query1 = "UPDATE easy.dbo.ct_$campaign_name SET Dial_Status=$dialStatus WHERE easycode='$ecode'";
                                mssql_query($query1,$this->db_dialer) or $this->logError($query1,$campaign_name,$this->db_dialer,1);

                                $log_query = "INSERT into js_crm.DIALER_UPDATE_LOG (PROFILEID,CAMPAIGN,UPDATE_STRING,TIME,ACTION) VALUES ('$phoneNo','$campaign_name','Dial_Status=1',now(),'OPTIN')";
                                mysql_query($log_query,$this->db_js_111) or $this->logError($log_query, $campaign_name, $this->db_js_111);
                        }
                }
        }
	// Phone Number Validate
	public function phoneNumberCheck($phoneNumber)
	{
		$phoneNumber    =substr(preg_replace("/[a-zA-Z!(\' ')@#$+^&*-]/", "",$phoneNumber),-10);
		$phoneNumber    =ltrim($phoneNumber,0);
		if(!is_numeric($phoneNumber))
			return false;
		return $phoneNumber;
	}
	// Fetch profiles
	public function getProfileDetails($phoneNo)
	{
		$profileArr =array();
		$sql= "SELECT PROFILEID,ACTIVATED FROM newjs.JPROFILE WHERE PHONE_MOB='$phoneNo' OR PHONE_WITH_STD='$phoneNo'";
		$res=mysql_query($sql,$this->db_js_111) or $this->logError($sql,$campaign_name,$this->db_js_111); 
		while($myrow = mysql_fetch_array($res)){
			if($myrow['ACTIVATED']!='D')
				$profileArr[] = $myrow["PROFILEID"];
		}

		if(count($profileArr)==0){
			$sql1= "SELECT PROFILEID FROM newjs.JPROFILE_CONTACT WHERE ALT_MOBILE='$phoneNo'";
			$res1=mysql_query($sql1,$this->db_js_111) or $this->logError($sql1,$campaign_name,$this->db_js_111); 
			while($myrow1 = mysql_fetch_array($res1)){

				$pid = $myrow1["PROFILEID"];
				$sql2= "SELECT PROFILEID FROM newjs.JPROFILE WHERE PROFILEID='$pid' AND ACTIVATED!='D'";
				$res2=mysql_query($sql2,$this->db_js_111) or $this->logError($sql2,$campaign_name,$this->db_js_111);
				if($myrow2 = mysql_fetch_array($res2)){
					$profileArr[] =$myrow2["PROFILEID"];
                        	}
                	}
        	}
        	return $profileArr;
	}
	public function logError($sql,$campaignName='',$dbConnect='',$ms='')
	{
		$processName ='Optin-Process';
		if(!$campaignName)
			$campaignName ='Optin-Process';
                $dialerLogObj =new DialerLog();
                $dialerLogObj->logError($sql,$campaignName,$dbConnect,$ms, $processName);
	}
}
?>
