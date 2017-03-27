<?php
/*********************************************************************************************
* FILE NAME     :  
* DESCRIPTION   : Capture sales campaign details  
* MADE BY       : MANOJ RANA 
*********************************************************************************************/
include("MysqlDbConstants.class.php");

//Open connection at JSDB
$db_js = mysql_connect(MysqlDbConstants::$misSlave['HOST'],MysqlDbConstants::$misSlave['USER'],MysqlDbConstants::$misSlave['PASS']) or die("Unable to connect to nmit server");
$db_master = mysql_connect(MysqlDbConstants::$master['HOST'],MysqlDbConstants::$master['USER'],MysqlDbConstants::$master['PASS']) or die("Unable to connect to nmit server ");
$db_js_111 = mysql_connect(MysqlDbConstants::$slave111['HOST'],MysqlDbConstants::$slave111['USER'],MysqlDbConstants::$slave111['PASS']) or die("Unable to connect to local-111 server");
$db_dialer = mssql_connect(MysqlDbConstants::$dialer['HOST'],MysqlDbConstants::$dialer['USER'],MysqlDbConstants::$dialer['PASS']) or die("Unable to connect to dialer server");


include("DialerHandler.class.php");
$dialerHandlerObj =new DialerHandler($db_js, $db_js_111, $db_dialer,$db_master);

$today = date("Y-m-d H:i:s");
$campaignArr =array('JS_NCRNEW'=>'noida','MAH_JSNEW'=>'mumbai','JS_RENEWAL'=>'renewal','FP_JS'=>'fp');
$suffix =$dialerHandlerObj->getLeadIdSuffix();

//Compute all the active campaigns
foreach($campaignArr as $campaignName=>$campaign)
{
		$leadid =$campaign.$suffix;	

		// get information from Dialer



		// End information
	       
		$squery = "INSERT into js_crm.DAILY_DIALER_REPORT_LOG (`CAMPAIGN_NAME`,`ACTIVE_DATA_POINTS`,`TOTAL_DIAL`,`UNIQUE_DIAL`,`HANDLED_CALL`,`MAX_CONNECTED_SCORE`,`MIN_CONNECTED_SCORE`,`EXECUTIVE_TOOK_CALL`,`AVG_TALK_TIME`,`ENTRY_DT`) VALUES ('$campaignName','$activeData','$TotalDial','$UniqueDial','$HandledCall','$MaxConnectedScore','$MinConnectedScore','$TotExecTookCall','$AvgTalkTime',now())";
		$sresult = mysql_query($squery,$db_js_111) or die($squery.mysql_error($db_js_111));

		$dataArr[] =array("CAMPAIGN_NAME"=>"$campaignName","ACTIVE_DATA_POINTS"=>"$activeData","TOTAL_DIAL"=>"$TotalDial","UNIQUE_DIAL"=>"$UniqueDial","HANDLED_CALL"=>"$HandledCall","MAX_CONNECTED_SCORE"=>"$MaxConnectedScore","MIN_CONNECTED_SCORE"=>"$MinConnectedScore","EXECUTIVE_TOOK_CALL"=>"$TotExecTookCall","AVG_TALK_TIME"=>"$AvgTalkTime");
		
}

// send Mail 
$msg	=getformattedData($dataArr);
$to     ="manoj.rana@naukri.com";
$from   ="From:JeevansathiCrm@jeevansathi.com";
$sub	="Daily Dialer Report";
mail($to,$sub,$msg,$from);

function getformattedData($dataArr)
{
	if(count($dataArr)>0){
		foreach($dataArr as $key=>$data){
			foreach($data as $title=>$value){
				$str .=$title."=>".$value."<br>";
			}
			$str .="<br>--------------------<br>";
		}
	}
	return $str;
}
?>
