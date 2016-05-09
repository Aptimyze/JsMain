<?
/**
* Validation of Mmm
*/
include(JsConstants::$cronDocRoot."/crontabs/connect.inc");
$db = connect_slave81();

$sql = "SELECT A.MAILER_ID,B.UPPER_LIMIT FROM mmmjs.MAIN_MAILER_NEW A, mmmjs.MAILER_SPECS_JS B WHERE A.MAILER_ID = B.MAILER_ID AND MAILER_FOR =  'J' AND STATUS =  'RC' AND UPPER_LIMIT >0 and A.MAILER_ID NOT IN (9100)";
$res=mysql_query($sql,$db) or die(mysql_error());
while($row=mysql_fetch_assoc($res))
{
	$uLimit = $row["UPPER_LIMIT"];
	$mailerIdArr[$uLimit] = $row["MAILER_ID"];
}

foreach($mailerIdArr as $k=>$v)
{
	$sql = "SELECT COUNT(*) C FROM mmmjs.".$v."mailer";
	$res=mysql_query($sql,$db) or die(mysql_error());
	$row=mysql_fetch_assoc($res);
	$cntt = $row["C"];
	if($cntt!=$k)
	{
		$ids["mailerId:$v"] = "Expected Cnt:$k, Actual Count:$cntt";
	}
}
if($ids)
{
	$ids=print_r($ids,true);
	mail("lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com",'jeevansathi-mmmjsError',$ids);
}
