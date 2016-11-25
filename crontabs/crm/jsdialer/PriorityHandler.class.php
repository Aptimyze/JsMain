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

		$fields ='easycode,priority,old_priority,PROFILEID,Dial_Status';
		if($campaignName=='JS_RENEWAL' || $campaignName=='OB_RENEWAL_MAH')
			$fields .=',EXPIRY_DT';
		elseif($campaignName=='OB_JS_RCB')
			$fields .=',PREFERRED_TIME_IST';

		$squery1 = "SELECT $fields FROM easy.dbo.ct_$campaignName JOIN easy.dbo.ph_contact ON easycode=code WHERE status=0 and priority!='10' and Dial_Status!='9' AND Dial_Status!='0' AND Dial_Status!='3'";
		if($profileStr)	
			$squery1.=" AND PROFILEID IN ('$profileStr')";
		else
			$squery1.=" AND PROFILEID%10=$icount";
                $sresult1 = mssql_query($squery1,$this->db_dialer) or $this->logError($squery1,$campaignName,$this->db_dialer,1);
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
		$data		=$dataArr[$profileid];
		$ecode 		=$data['easycode'];
		$dialStatus 	=$data['Dial_Status'];
		$priorityType	='P';
		$sourceType	='DURATION';	
			
		$query = "UPDATE easy.dbo.ph_contact SET priority ='$npriority' WHERE code='$ecode' AND status=0 and priority!='10'";
		mssql_query($query,$this->db_dialer) or $this->logError($query,$campaignName,$this->db_dialer,1);

		$query1 = "UPDATE easy.dbo.ct_$campaignName SET LAST_LOGIN_DATE=getdate(),lastonlinepriority='$npriority',lastpriortizationt=getdate() FROM easy.dbo.ct_$campaignName where easycode='$ecode'";
		mssql_query($query1,$this->db_dialer) or $this->logError($query1,$campaignName,$this->db_dialer,1);		

		$dialeLogObj =new DialerLog($this->db_js_111);
		$dialeLogObj->logOnlinePriority($profileid,$npriority,$dialStatus,$priorityType,$campaignName,$sourceType);	
	}
	// De-prioritize function
        public function dePrioritizeProfile($profileid,$campaignName,$dataArr){

		$data           =$dataArr[$profileid];
                $ecode 		=$data['easycode'];
		$old_priority 	=$data['old_priority'];

                $query = "UPDATE easy.dbo.ph_contact SET priority ='$old_priority' WHERE code='$ecode' AND status=0 and priority!='10'";
                mssql_query($query,$this->db_dialer) or $this->logError($query,$campaignName,$this->db_dialer,1);

        }
	// get TimeSlot for RCB campaign priority
	/*  Slots are defined based on process frequency of 15 min. interval */	
	public function getTimeSlot($timeInMin){
		$slotExist =false;

		$slotArr[] =array('start'=>'30','end'=>'45');		//7
		$slotArr[] =array('start'=>'60','end'=>'75');		//6
		$slotArr[] =array('start'=>'90','end'=>'105');		//5
		$slotArr[] =array('start'=>'120','end'=>'135');		//4
		$slotArr[] =array('start'=>'150','end'=>'165');		//3
		$slotArr[] =array('start'=>'180','end'=>'195');		//2
		$slotArr[] =array('start'=>'210','end'=>'225');		//1
		$slotArr[] =array('start'=>'240','end'=>'');		//0

		foreach($slotArr as $key=>$data){
			$start 	=$data['start'];
			$end	=$data['end'];
			if($timeInMin>=$start && $timeInMin<=$end){
				$slotExist =true;
				return $slotExist;
			}
			elseif($timeInMin>=240){
				$slotExist =true;
				return $slotExist;
			}
		}		
		return $slotExist;
	}
	// logging Erro
        public function logError($sql,$campaignName='',$dbConnect='',$ms='')
        {
                $dialerLogObj =new DialerLog();
                $dialerLogObj->logError($sql,$campaignName,$dbConnect,$ms);
        }
}
?>
