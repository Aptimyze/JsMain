<?php
include_once('DialerLog.class.php');
class PriorityHandler 
{
        public function __construct($db_js, $db_js_111, $db_dialer,$db_master=''){
                $this->db_js            =$db_js;
                $this->db_js_111        =$db_js_111;
                $this->db_dialer        =$db_dialer;
                $this->db_master        =$db_master;
        }

	// Fecth Dialer Profiles
	public function getDialerProfileForPriority($campaignName,$profileArr='',$icount=''){

		$profileStr ='';
		if(is_array($profileArr))
			$profileStr =implode("','",$profileArr);

		$squery1 = "SELECT easycode,old_priority,PROFILEID,Dial_Status FROM easy.dbo.ct_$campaign_name JOIN easy.dbo.ph_contact ON easycode=code WHERE status=0 and priority!='10' and Dial_Status!='9' AND Dial_Status!='0' AND Dial_Status!='3'";
		if($profileStr)	
			$squery1.=" AND PROFILEID IN ('$profileStr')";
		else
			$squery1.=" AND PROFILEID%10=$icount";
                $sresult1 = mssql_query($squery1,$this->db_dialer) or logError($squery1,$campaign_name,$this->db_dialer,1);
                while($srow1 = mssql_fetch_array($sresult1)){
			$profileid      	=$srow1["PROFILEID"];
			$dataArr[$profileid] 	=$srow1;
		}
		return $dataArr;
	}
	// Prioritize function
	public function prioritizeProfile($profileid,$campaignName,$dataArr='',$npriority=''){

		if(!$npriority)
			$npriority =5;
		$ecode 		=$dataArr['easycode'];
		$dialStatus 	=$dataArr['Dial_Status'];
		$priorityType	='P';
		$sourceType	='DURATION';	

		$query = "UPDATE easy.dbo.ph_contact SET priority ='$npriority' WHERE code='$ecode' AND status=0 and priority!='10'";
		mssql_query($query,$this->db_dialer) or logError($query,$campaignName,$this->db_dialer,1);

		$query1 = "UPDATE easy.dbo.ct_$campaign_name SET LAST_LOGIN_DATE=getdate(),lastonlinepriority='$npriority',lastpriortizationt=getdate() FROM easy.dbo.ct_$campaign_name where easycode='$ecode'";
		mssql_query($query1,$this->db_dialer) or logError($query1,$campaignName,$this->db_dialer,1);		

		$dialeLogObj =new DialerLog($this->db_js_111);
		$dialeLogObj->logOnlinePriority($profileid,$npriority,$dialStatus,$priorityType,$campaignName,$sourceType);	
	}
	// De-prioritize function
        public function dePrioritizeProfile($profileid,$campaignName,$dataArr){

                $ecode 		=$dataArr['easycode'];
		$old_priority 	=$dataArr['old_priority'];

                $query = "UPDATE easy.dbo.ph_contact SET priority ='$old_priority' WHERE code='$ecode' AND status=0 and priority!='10'";
                mssql_query($query,$this->db_dialer) or logError($query,$campaignName,$this->db_dialer,1);

        }
	



}
?>
