<?php
include(JsConstants::$cronDocRoot."/crontabs/connect.inc");
$db=connect_db4();

$date=date("Y-m-d");

$checkTables=array("TEMPSENDER","TEMPRECEIVER","CONTACTS_SEARCH_NEW","CONTACTS_SEARCH");

foreach($checkTables as $k=>$v)
{
	$sql="show table status from newjs  like '$v'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$searchrow=mysql_fetch_array($result);
	$Update_time=substr($searchrow['Update_time'],0,10);
	$dateDiff=DayDiff_1($Update_time,$date);
	if($dateDiff>1)
		mail("lavesh.rawat@gmail.com,lavesh.rawat@jeevansathi.com,vidushi@naukri.com,reshu.rajput@jeevansathi.com","VIEWSIMILLAR_ERROR","VIEWSIMILLAR_ERROR:$v");
	else
	{
		$arr[$v]=$searchrow['Rows'];
	}
}

/*
$msg.="<table cellspacing=1 cellpadding=1 border=1><tr><th>Table Name</th><th>Entries</td></tr>";
foreach($arr as $k=>$v)
	$msg.="<tr><td>".$k."</td><td>".$v."</td></tr>";
$msg.="</table>";
send_email("lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com",$msg,"View Simillar table report","matchalert@jeevansathi.com");
*/

		
function DayDiff_1($StartDate, $StopDate)
{       
        return (date('U', strtotime($StopDate)) - date('U', strtotime($StartDate))) / 86400; //seconds a day
}      
