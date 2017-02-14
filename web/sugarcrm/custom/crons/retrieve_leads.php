<?php
	$path=realpath(dirname(__FILE__));
	$position=stripos($path,"sugarcrm");
	$includePath=substr($path,0,$position-1);
	$_SERVER['DOCUMENT_ROOT']=$includePath;
	include($includePath."/profile/connect_db.php");
	$dbSlave = connect_slave();
	$db=connect_db();
	mysql_select_db("sugarcrm_housekeeping");

	$sql="select id from inactive_leads,inactive_leads_cstm where id=id_c and gender_c='F' and status in (13,12,24,16,11,32) and deleted<>1 and mother_tongue_c not in (31,3,16,17) AND (phone_mobile IS NOT NULL OR phone_home IS NOT NULL OR enquirer_mobile_no_c IS NOT NULL OR enquirer_landline_c IS NOT NULL)";
	$result=mysql_query($sql,$dbSlave) or die(mysql_error());

	while($myrow=mysql_fetch_array($result))
	{
		$lead_id=$myrow['id'];
		retrieve_lead($lead_id);
	}

	$sql="select id from inactive_leads,inactive_leads_cstm where id=id_c and gender_c='M' and status in (13,12,24,16,11,32) and deleted<>1 and mother_tongue_c not in (31,3,16,17) and date_entered > date_sub(curdate(), interval 3 month) and (age_c>23 or date_birth_c < date_sub(curdate(), interval 23 YEAR)) AND (phone_mobile IS NOT NULL OR phone_home IS NOT NULL OR enquirer_mobile_no_c IS NOT NULL OR enquirer_landline_c IS NOT NULL)";
	$result=mysql_query($sql,$dbSlave) or die(mysql_error());
echo " ";
	while($myrow=mysql_fetch_array($result))
	{
		$lead_id=$myrow['id'];
		retrieve_lead($lead_id);
	}

	function retrieve_lead($lead_id)
	{
		$sql="REPLACE INTO sugarcrm.leads_cstm SELECT sugarcrm_housekeeping.inactive_leads_cstm.* FROM sugarcrm_housekeeping.inactive_leads_cstm JOIN sugarcrm_housekeeping.inactive_leads ON inactive_leads_cstm.id_c=inactive_leads.id WHERE inactive_leads.id='$lead_id'";
		mysql_query($sql) or die(mysql_error());

		$sql="DELETE sugarcrm_housekeeping.inactive_leads_cstm.* FROM sugarcrm_housekeeping.inactive_leads_cstm JOIN sugarcrm_housekeeping.inactive_leads ON sugarcrm_housekeeping.inactive_leads_cstm.id_c=inactive_leads.id WHERE inactive_leads.id='$lead_id'";
		mysql_query($sql) or die(mysql_error());

		$sql="REPLACE INTO sugarcrm.email_addresses SELECT sugarcrm_housekeeping.inactive_email_addresses.* FROM sugarcrm_housekeeping.inactive_email_addresses JOIN sugarcrm_housekeeping.inactive_email_addr_bean_rel ON sugarcrm_housekeeping.inactive_email_addresses.id=inactive_email_addr_bean_rel.email_address_id JOIN sugarcrm_housekeeping.inactive_leads ON inactive_email_addr_bean_rel.bean_id=inactive_leads.id WHERE inactive_leads.id='$lead_id'";
		mysql_query($sql) or die(mysql_error());

		$sql="DELETE sugarcrm_housekeeping.inactive_email_addresses.* FROM sugarcrm_housekeeping.inactive_email_addresses JOIN sugarcrm_housekeeping.inactive_email_addr_bean_rel ON sugarcrm_housekeeping.inactive_email_addresses.id=inactive_email_addr_bean_rel.email_address_id JOIN sugarcrm_housekeeping.inactive_leads ON inactive_email_addr_bean_rel.bean_id=inactive_leads.id WHERE inactive_leads.id='$lead_id'";
		mysql_query($sql) or die(mysql_error());

		$sql="REPLACE INTO sugarcrm.email_addr_bean_rel SELECT sugarcrm_housekeeping.inactive_email_addr_bean_rel.* FROM sugarcrm_housekeeping.inactive_email_addr_bean_rel JOIN sugarcrm_housekeeping.inactive_leads ON sugarcrm_housekeeping.inactive_email_addr_bean_rel.bean_id=inactive_leads.id WHERE inactive_leads.id='$lead_id'";
		mysql_query($sql) or die(mysql_error());

		$sql="DELETE sugarcrm_housekeeping.inactive_email_addr_bean_rel.* FROM sugarcrm_housekeeping.inactive_email_addr_bean_rel JOIN sugarcrm_housekeeping.inactive_leads ON sugarcrm_housekeeping.inactive_email_addr_bean_rel.bean_id=inactive_leads.id WHERE inactive_leads.id='$lead_id'";
		mysql_query($sql) or die(mysql_error());

		$sql="REPLACE INTO sugarcrm.leads SELECT sugarcrm_housekeeping.inactive_leads.* FROM sugarcrm_housekeeping.inactive_leads WHERE inactive_leads.id='$lead_id'";
		mysql_query($sql) or die(mysql_error());

		$sql="DELETE sugarcrm_housekeeping.inactive_leads.* FROM sugarcrm_housekeeping.inactive_leads WHERE inactive_leads.id='$lead_id'";
		mysql_query($sql) or die(mysql_error());
	}
?>
