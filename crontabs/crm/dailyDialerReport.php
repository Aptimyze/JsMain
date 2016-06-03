<?php
/*********************************************************************************************
* FILE NAME     :  
* DESCRIPTION   : Capture sales campaign details  
* MADE BY       : MANOJ RANA 
*********************************************************************************************/

//Open connection at JSDB
$db_master      = mysql_connect("master.js.jsb9.net","user","CLDLRTa9") or die("Unable to connect to js server");
$db_js          = mysql_connect("ser2.jeevansathi.jsb9.net","user_dialer","DIALlerr") or die("Unable to connect to js server");
$db_dialer      = mssql_connect("dialer.infoedge.com","online","jeev@nsathi@123") or die("Unable to connect to dialer server");
$db_js_111	= mysql_connect("localhost:/tmp/mysql_06.sock","user_sel","CLDLRTa9") or die("Unable to connect to js server");

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
	       
		$squery = "INSERT into test.DAILY_DIALER_REPORT_LOG (`CAMPAIGN_NAME`,`ACTIVE_DATA_POINTS`,`TOTAL_DIAL`,`UNIQUE_DIAL`,`HANDLED_CALL`,`MAX_CONNECTED_SCORE`,`MIN_CONNECTED_SCORE`,`EXECUTIVE_TOOK_CALL`,`AVG_TALK_TIME`,`ENTRY_DT`) VALUES ('$campaignName','$activeData','$TotalDial','$UniqueDial','$HandledCall','$MaxConnectedScore','$MinConnectedScore','$TotExecTookCall','$AvgTalkTime',now())";
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
function logerror($sql="",$db="",$ms='')
{
	$today=@date("Y-m-d H:m:i");
	$filename="logerror.txt";
	if(is_writable($filename)){
		if (!$handle = fopen($filename, 'a')){
			echo "Cannot open file ($filename)";
			exit;
		}
		if(!$ms)
			fwrite($handle,"\n\nQUERY : $sql \t ERROR : " .mssql_get_last_message(). " \t $today");
		else
			fwrite($handle,"\n\nQUERY : $sql \t ERROR : " .mysql_error(). " \t $today");
		fclose($handle);
	}
	else
		echo "The file $filename is not writable";
}
?>
