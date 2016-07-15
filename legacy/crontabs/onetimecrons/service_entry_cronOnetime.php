<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

include("../connect.inc");
$db =connect_db();
$db_slave =connect_slave();

$valArr =array("ESP","ES");
/* Section for newly purchased services */
$sql="SELECT COUNT( * ) cnt,P.SERVICEID,P.CENTER,LEFT( P.ENTRY_DT, 10 ) AS ENTRY_DT FROM billing.PURCHASES P WHERE P.STATUS='DONE' AND P.SERVICEID LIKE 'ES%' AND P.ENTRY_DT<'2013-12-19 00:00:00' GROUP BY LEFT(P.SERVICEID,5), P.CENTER, LEFT( P.ENTRY_DT, 10 )";
$res=mysql_query_decide($sql,$db_slave) or die("$sql".mysql_error_js($db_slave));
while($row=mysql_fetch_array($res))
{
	$count		=$row["cnt"];
	$serviceid	=$row["SERVICEID"];
	$center		=$row["CENTER"];
	$entryDt	=$row["ENTRY_DT"];	

	$serviceIdArr	=@explode(",",$serviceid);
	foreach($serviceIdArr as $key=>$val){
		if(strstr($val,'ES')){
			$eSathiId =$val;
			break;
		}
	}
	$serviceNum =str_replace($valArr,'',$eSathiId);
	$mainServiceId ='P'.$serviceNum; 

	if($count>0){
		$sql1 ="update MIS.SERVICE_DETAILS SET COUNT=COUNT-$count WHERE ENTRY_DT='$entryDt' AND SERVICE='$mainServiceId' AND BRANCH='$center'";
		echo $sql1."\n";	
		mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js($db));	

		$sql2 ="select count(*) AS newCnt from MIS.SERVICE_DETAILS where ENTRY_DT='$entryDt' AND SERVICE='$eSathiId' AND BRANCH='$center'";
		$res2=mysql_query_decide($sql2,$db) or die("$sql2".mysql_error_js($db));
		$row2=mysql_fetch_array($res2);
		$newCnt =$row2['newCnt'];
		if($newCnt>0)
			$sql3 ="update MIS.SERVICE_DETAILS SET COUNT=COUNT+$count WHERE ENTRY_DT='$entryDt' AND SERVICE='$eSathiId' AND BRANCH='$center'";
		else
			$sql3 ="insert into MIS.SERVICE_DETAILS(`ENTRY_DT`,`COUNT`,`SERVICE`,`BRANCH`) VALUES('$entryDt','$count','$eSathiId','$center')";
		echo $sql3."\n"; 
		mysql_query_decide($sql3,$db) or die("$sql3".mysql_error_js($db));
	}
	unset($newCnt);	
	unset($serviceIdArr);
	unset($eSathiId);
	unset($mainServiceId);
	unset($serviceid);
}

$sqlDel ="delete from MIS.SERVICE_DETAILS where COUNT<1";
mysql_query_decide($sqlDel,$db) or die("$sqlDel".mysql_error_js($db));
/*  service cancelled section ends */

?>
