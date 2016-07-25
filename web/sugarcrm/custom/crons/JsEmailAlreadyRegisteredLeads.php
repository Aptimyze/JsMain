<?php
/***************************************************************************************************************************************************
Objective    : This script will send consolidated list of Already Registered+Don't Know Profile Id to LTF supervisor
Created for  : Trac Tkt#410
Script calls : Daily from crontabs
****************************************************************************************************************************************************/

$path=realpath(dirname(__FILE__));
$position=stripos($path,"sugarcrm");
$includePath=substr($path,0,$position-1);
include($includePath."/profile/connect.inc");
include($includePath."/sugarcrm/config.php");

$db=connect_slave();
mysql_query("set session wait_timeout=10000",$db);

$sql="SELECT last_name,id FROM sugarcrm.leads join sugarcrm.leads_cstm ON id=id_c WHERE status='46' AND disposition_c='22' AND deleted<>'1'";
$res=mysql_query($sql,$db) or die("Error while generating list of already registered profiles  ".mysql_error($db));
if(mysql_num_rows($res))
{
	global $sugar_config;
	$to=$sugar_config['mail_ltf_supervisor'];
	echo "\n".$to;
	$subject="List of leads - Already Registered + Don't Know Profile ID";
	$mailBody.="Following leads were marked Already Registered with disposition Don't Know Profile ID :";
	$mailBody.="\n\nLead Name\t\t\tLeadId";
	while($row=mysql_fetch_assoc($res))
		$mailBody.="\n$row[last_name]\t\t$row[id]";
	var_dump($mailBody);
	/*if($to)
		mail($to,$subject,$mailBody);*/
}
?>
