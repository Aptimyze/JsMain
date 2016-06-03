<?php
define('sugarEntry',true);
require_once('JsMessage.php');
$_SERVER['DOCUMENT_ROOT']=realpath(dirname(__FILE__)."/../../..");
include_once($_SERVER['DOCUMENT_ROOT']."/smarty/Smarty.class.php");
chdir($_SERVER['DOCUMENT_ROOT']."/profile");
require_once('connect_functions.inc');
require_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
require_once('connect_db.php');
require_once(JsConstants::$docRoot."/commonFiles/sms_inc.php");
class JsSms extends JsMessage{
	function sendMessage(){
		global $db,$smarty;
		//Sms will be sent on days included in array  in second argument of 
		//following function call
		$smarty=new Smarty;
		$boundaryTimeString=$this->createTimeBoundQuery('leads.date_entered',array(3,5,7));
		$sms_query="select id as lead_id,enquirer_mobile_no_c as emn,phone_mobile from sugarcrm.leads, sugarcrm.leads_cstm where id=id_c AND ($boundaryTimeString) AND (enquirer_mobile_no_c is not null OR phone_mobile is not null ) AND status='13' AND do_not_call='0' AND deleted='0' and source_c <> 11";
//		echo $sms_query;
		$result_sms=$db->query($sms_query);
		$no_rows=$db->getRowCount($result_sms);
		while($row=$db->fetchByAssoc($result_sms)){
			$mobileNumber=($row['emn'])?$row['emn']:$row['phone_mobile'];
		//	echo "$mobileNumber\n";
			$lead_id=$row['lead_id'];
		$sql="INSERT IGNORE INTO sugarcrm.auto_sms (id) VALUES ('$lead_id')";
		$db->query($sql);
		if($db->getAffectedRowCount())
		{
			$lead_query_string="select age_c,gender_c,caste_c, religion_c,date_birth_c from leads_cstm where id_c='$lead_id'"; 
			$messageToSend=$this->createCompleteMessage($lead_id,$lead_query_string);
	//		echo "$mobileNumber<br> $messageToSend<br>";
			$from = '919282443838';
			$xmlData = generateReceiverXmlData($lead_id, $messageToSend,
				$from, $mobileNumber);
				sendSMS($xmlData, "priority");
		}
	}
	$sql="SELECT COUNT(*) from sugarcrm.auto_sms";
		$no_leads=$db->getOne($sql);
	if($no_leads!=$no_rows)
		send_email("nikhil.dhiman@jeevansathi.com,nitesh.s@jeevansathi.com","Problem in auto followup sms cron $no_leads $no_rows","Auto followup sms cron didnt run completely");
	}
	function createMessage($propertyArr){
	   $message="Over ".$propertyArr['count'];
	   $message.=($propertyArr['gender_c']=='M')?" girls":" boys";	
	   $message.=" match your criteria on Jeevansathi.com. Begin your matrimony search today 
		   on Jeevansathi.com. To Register, reply Y or call on 1800-419-6299";
	   return $message;
	}
}
