<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
chdir($_SERVER["DOCUMENT_ROOT"]."/profile");
include("connect.inc");
$db=connect_db();
$logicArr[1]="Forward Partner & Reverse Partner";
$logicArr[2]="Forward Partner & Reverse Trend";
$logicArr[3]="Forward Trend & Reverse Partner";
$logicArr[4]="Forward Trend & Reverse Trend";

if(!$laveshId)
        $laveshId=1;
$time1=date("Y-m-d",mktime(0, 0, 0, date("m"), date("d")-$laveshId, date("y")));

$today=mktime(0,0,0,date("m"),date("d")-1,date("Y"));
$zero=mktime(0,0,0,01,01,2005);
$gap=floor(($today-$zero)/(24*60*60));

//$sql="SELECT * FROM matchalerts.ALL_RESULT_COUNT ORDER BY ID DESC LIMIT 4,4";
$sql="SELECT * FROM MATCHALERT_TRACKING.MA_SENT WHERE DATE='$time1' ORDER BY LOGIC ASC";
$res=mysql_query($sql) or die(mysql_error());
while($row=mysql_fetch_array($res))
{
	$logic=$row["LOGIC"];
	$arr[$logic]["RESULTS0"]=$row["NO_OF_RES0"];
	$arr[$logic]["RESULTS1"]=$row["NO_OF_RES1"];
	$arr[$logic]["RESULTS2"]=$row["NO_OF_RES2"];
	$arr[$logic]["RESULTS3"]=$row["NO_OF_RES3"];
	$arr[$logic]["RESULTS4"]=$row["NO_OF_RES4"];
	$arr[$logic]["RESULTS5"]=$row["NO_OF_RES5"];
	$arr[$logic]["RESULTS6"]=$row["NO_OF_RES6"];
	$arr[$logic]["RESULTS7"]=$row["NO_OF_RES7"];
	$arr[$logic]["TOTAL_MATCHES_SENT"]=$row["TOTAL_MATCHES_SENT"];
	/*
	$arr[$logic]["RESULTS8"]=$row["NO_OF_RES8"];
	$arr[$logic]["RESULTS9"]=$row["NO_OF_RES9"];
	$arr[$logic]["RESULTS10"]=$row["NO_OF_RES10"];
	*/
}
//ksort($arr);
//print_r($arr);
//die;
$msg="<table border=\"3\"><tbody>";
$msg.="<tr>";
$msg.="<th width=\"10%\">LOGIC</th>"; 
$msg.="<th width=\"8%\">0 Results</th>"; 
$msg.="<th width=\"8%\">1 Results</th>"; 
$msg.="<th width=\"8%\">2 Results</th>"; 
$msg.="<th width=\"8%\">3 Results</th>"; 
$msg.="<th width=\"8%\">4 Results</th>"; 
$msg.="<th width=\"8%\">5 Results</th>"; 
$msg.="<th width=\"8%\">6 Results</th>"; 
$msg.="<th width=\"8%\">7 Results</th>"; 
$msg.="<th width=\"8%\">Total Mails Sent</th>"; 
/*
$msg.="<th width=\"8%\">8 Results</th>"; 
$msg.="<th width=\"8%\">9 Results</th>"; 
$msg.="<th width=\"8%\">10 Results</th>"; 
*/
$msg.="</tr>";
//$msg1="<tr> <td>$KeyPage</td> <td>$Threshold</td>  <td>$percentageAboveThreshold</td>  <td>$avgBrowserStartTime</td>  <td>$avgBrowserendTime</td>  <td>$totalNumberServed</td>  <td>$totalNumberServedAboveThreshold</td></tr>";
foreach($arr as $k=>$v)
{
	$msg1.="<tr><td>$logicArr[$k]</td>";
	foreach($v as $kk=>$vv)
	{
		$msg1.="<td>$vv</td>";	
	}
	$msg1.="</tr>";
}

$msg.=$msg1;
$msg.="</tbody></table>";
if($arr)
	send_email('lavesh.rawat@jeevansathi.com,hitesh@naukri.com,shalabh@brijj.com,vikas.jayna@jeevansathi.com,vivek@jeevansathi.com,rohan.mathur@jeevansathi.com,nishant.pandey@naukri.com,vijay.bhaskar@jeevansathi.com,esha.arora@naukri.com',$msg,"Matchalert Report of $time1","lavesh.rawat@jeevansathi.com","","","","","","Y");
?>
