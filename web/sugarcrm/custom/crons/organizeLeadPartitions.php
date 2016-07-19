<?php
	$path=realpath(dirname(__FILE__));
	$position=stripos($path,"sugarcrm");
	$includePath=substr($path,0,$position-1);
	$_SERVER['DOCUMENT_ROOT']=$includePath;
	include($includePath."/profile/connect_db.php");
	include($includePath."/sugarcrm/custom/crons/housekeepingConfig.php");
	$dbSlave = connect_slave();
	$db=connect_db();

	$dt=date("Y-m-d");

	$sql="select id from sugarcrm_housekeeping.inactive_leads,sugarcrm_housekeeping.inactive_leads_cstm where id=id_c and status in (13,12,24,11) and disposition_c in (24,6,23,19) AND date_entered>DATE_SUB('$dt',INTERVAL 45 DAY)";
	$result=mysql_query($sql,$dbSlave) or die(mysql_error());
	while($myrow=mysql_fetch_array($result))
	{
		$lead_id=$myrow['id'];
		moveLead($lead_id,"inactive","active");
	}

	$sql="select id from sugarcrm_housekeeping.inactive_leads,sugarcrm_housekeeping.inactive_leads_cstm where id=id_c and status in (26,14,17,46,45) and disposition_c in ('31','8','9','10','11','17','18','21','22','25','12','13','14','15','16','32')";
        $result=mysql_query($sql,$dbSlave) or die(mysql_error());
        while($myrow=mysql_fetch_array($result))
        {
                $lead_id=$myrow['id'];
                moveLead($lead_id,"inactive","connected");
        }

	$sql="select id from sugarcrm_housekeeping.connected_leads,sugarcrm_housekeeping.connected_leads_cstm where id=id_c and status in (13,12,24,11) and disposition_c in (24,6,23,19) AND date_entered>DATE_SUB('$dt',INTERVAL 45 DAY)";
        $result=mysql_query($sql,$dbSlave) or die(mysql_error());
        while($myrow=mysql_fetch_array($result))
        {
                $lead_id=$myrow['id'];
                moveLead($lead_id,"connected","active");

	}

	$sql="select id from sugarcrm_housekeeping.connected_leads,sugarcrm_housekeeping.connected_leads_cstm where id=id_c and status in ('32') and deleted='1' and disposition_c in ('26','27','28')";
        $result=mysql_query($sql,$dbSlave) or die(mysql_error());
        while($myrow=mysql_fetch_array($result))
        {
                $lead_id=$myrow['id'];
                moveLead($lead_id,"connected","inactive");

        }

	$sql="select id from sugarcrm_housekeeping.connected_leads,sugarcrm_housekeeping.connected_leads_cstm where id=id_c and status in (13,12,24,11) and disposition_c in (24,6,23,19) AND date_entered<DATE_SUB('$dt',INTERVAL 45 DAY)";
        $result=mysql_query($sql,$dbSlave) or die(mysql_error());
        while($myrow=mysql_fetch_array($result))
        {      
                $lead_id=$myrow['id'];
                moveLead($lead_id,"connected","inactive");

        }
?>
