<?php
$path=$_SERVER['DOCUMENT_ROOT'];
include_once("$path/profile/connect.inc");
include_once("$path/profile/lead_auto_register.php");
$db=connect_db();
$dbSlave = connect_slave();
include_once(JsConstants::$docRoot."/commonFiles/sms_inc.php");
include_once("$path/classes/SmsResponse.class.php");
include_once("$path/sugarcrm/custom/crons/housekeepingConfig.php");
include_once("$path/sugarcrm/include/utils/systemProcessUsersConfig.php");
global $partitionsArray;
global $process_user_mapping;

$processUserId=$process_user_mapping["sms_response"];
if(!$processUserId)
        $processUserId=1;

$updateTime=date("Y-m-d H:i:s");

$smsObject=new SmsResponse($mobile,$msg);
if($smsObject->err)
{
	echo "problem";
die;
}
$mobileNo=$smsObject->getMobile();
$message=$smsObject->getMessage();
//echo "$mobileNo, $message";
$leadid_to_update=findLeadId($mobileNo);
if(!empty($leadid_to_update)){
if(strrpos($message,"n")!==false){
	//Lead Please Register is 16 and Lead not Interested is 14
	$sql="UPDATE sugarcrm.leads,sugarcrm.leads_cstm SET status='14',disposition_c='8',modified_user_id='$processUserId',date_modified='$updateTime' where id='$leadid_to_update' AND id=id_c AND status='13' AND deleted!='1'";   
	mysql_query_decide($sql,$db);
	if(!mysql_affected_rows_js($db))
	{
		if(is_array($partitionsArray))
		{
			foreach($partitionsArray as $partition=>$partitionArray)
			{
				$partitionLeadsCstm="sugarcrm_housekeeping.".$partition."_leads_cstm";
				$partitionLeads="sugarcrm_housekeeping.".$partition."_leads";
				$sql="UPDATE $partitionLeads,$partitionLeadsCstm SET status='14',disposition_c='8',modified_user_id='$processUserId',date_modified='$updateTime' where id='$leadid_to_update' AND id=id_c AND status='13' AND deleted!='1'";
				mysql_query_decide($sql,$db);
				if(mysql_affected_rows_js($db))
					break;
			}
		}

	}
}
else
	if(strrpos($message,"y")!==false){
		//Auto registeration disabled as required in trac#1485
//		if(!register_lead($leadid_to_update))
//		{
			//$sql="UPDATE sugarcrm.leads SET status=16 where id='$leadid_to_update'";
			//Changes by Sadaf for Tkt#410 - leaving positive responses to auto follow up sms in auto follow up state, instead logging the response
			$sql="INSERT INTO sugarcrm.auto_follow_up_sms_response(id,phone_mob,response,time) VALUES('$leadid_to_update','$mobileNo','Y',NOW())";
			$result=mysql_query_decide($sql,$db);
//		}
	}
	else{
		die;
}
}
function findLeadId($mobile){
	if($mobile){
		$sql="SELECT id from sugarcrm.leads where leads.phone_mobile='$mobile' AND deleted!='1'";
		$res=mysql_query_decide($sql,$dbSlave);
		$row=mysql_fetch_array($res);
		$lead_id=$row['id'];
		if(!$lead_id){
			$sql="SELECT id_c from sugarcrm.leads_cstm join sugarcrm.leads on id=id_c where leads_cstm.enquirer_mobile_no_c='$mobile' and deleted!='1'";
			$res=mysql_query_decide($sql,$dbSlave);
			$row=mysql_fetch_array($res);
			$lead_id=$row['id_c'];
			if(!$lead_id)
			{
				if(is_array($partitionsArray))
                                {
                                        foreach($partitionsArray as $partition=>$partitionArray)
                                        {
                                                $partitionLeadsCstm="sugarcrm_housekeeping.".$partition."_leads_cstm";
                                                $partitionLeads="sugarcrm_housekeeping.".$partition."_leads";
						$sql="SELECT id from $partitionLeads where leads.phone_mobile='$mobile' and deleted!='1'";
						$res=mysql_query_decide($sql,$dbSlave);
						if(mysql_num_rows($res))
						{
							$row=mysql_fetch_array($res);
							$lead_id=$row['id'];
							break;
						}
						else
						{
							$sql="SELECT id_c from $partitionLeadsCstm join $partitionLeads on id=id_c where leads_cstm.enquirer_mobile_no_c='$mobile' AND deleted!='1'";
							$res=mysql_query_decide($sql,$dbSlave);
							if(mysql_num_rows($res))
							{
								$row=mysql_fetch_array($res);
								$lead_id=$row['id_c'];
							}
						}
					}
				}
			}
		}
		return $lead_id;
	}
}

?>
