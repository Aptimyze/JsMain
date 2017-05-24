<?php

$flag_using_php5=1;
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
include("../connect.inc");

$db=connect_db();
$mysql=new Mysql;
$defVAS=VariableParams::$defVASPopularity;
$mainSrvc=explode(",",VariableParams::$mainMostpopularSrvc);

$refDate="2012-08-21 00:00:00";

/*Deleting previous records from the ADDON_RANK table*/
$sqlDelAdd="TRUNCATE TABLE billing.`ADDON_RANK`";
$rsDelAdd=mysql_query_decide($sqlDelAdd) or die(mysql_error());

$sqlSelect="SELECT SERVICEID FROM billing.`SERVICES` WHERE ADDON='N' AND ACTIVE='Y' AND (";
for($i=0;$i<count($mainSrvc);$i++){
	$sqlSelect.="SERVICEID LIKE '".$mainSrvc[$i]."%' OR ";
}
$sqlSelect=substr($sqlSelect,0,-3);
$sqlSelect.=")";
$rsSelect=mysql_query_decide($sqlSelect) or die(mysql_error());
while($row=mysql_fetch_assoc($rsSelect)){
$sqlInsert="INSERT INTO billing.`ADDON_RANK`(`MSID`,`VAS_ID`,`RANK`) SELECT '".$row['SERVICEID']."',SUBSTRING(SERVICEID,1,1),0 FROM billing.`SERVICES` WHERE ADDON='Y' AND ACTIVE='Y' GROUP BY SUBSTRING(SERVICEID,1,1)";
$rsInsert=mysql_query_decide($sqlInsert) or die(mysql_error());
}


/*Start-Creating a temporary table and storing the per profile unique purchase of the services*/
$sqlTempTrunc="TRUNCATE Table billing.`VAS_POP`";
$rsTempTrunc=mysql_query_decide($sqlTempTrunc) or die(mysql_error());

$sqlVasData="SELECT PD.SERVICEID AS VASID,PD.PROFILEID AS PROFILE,PC.SERVICEID AS MSID FROM billing.`PURCHASE_DETAIL` PD JOIN billing.`PURCHASES` PC JOIN billing.`SERVICES` SER ON SER.SERVICEID = PD.SERVICEID AND PC.BILLID = PD.BILLID WHERE  PC.`ENTRY_DT`>'".$refDate."' AND PD.`NET_AMOUNT`>0 AND SER.ADDON='Y' AND SER.ACTIVE='Y'";
$rsVasData=mysql_query_decide($sqlVasData) or die(mysql_error());

$sqlTemp="INSERT INTO billing.`VAS_POP`(SERVICEID,PROFILEID,MAINSERID) VALUES";
while($row=mysql_fetch_assoc($rsVasData)){
$sqlTemp.="('".$row['VASID']."',".$row['PROFILE'].",'".$row['MSID']."'),";
}
$sqlTemp=substr($sqlTemp,0,-1);
$rsTemp=mysql_query_decide($sqlTemp) or die(mysql_error());
/*End*/

/*Start-Fetching the services and their respective counts from the temporary table and storing it in the ADDON_RANK table*/
$sql="SELECT MAINSERID,SUBSTRING( SERVICEID, 1, 1 ) AS SERVICEID, COUNT( SERVICEID ) AS POPULARITY FROM billing.`VAS_POP` GROUP BY SUBSTRING( SERVICEID, 1, 1 ),SUBSTRING(MAINSERID,1,LOCATE(',',MAINSERID))";
$rs=mysql_query_decide($sql) or die("$sql".mysql_error());

/*Updating the ADDON_RANK on the basis of popularity of the services*/
while ($row=mysql_fetch_assoc($rs)) {
    $mainSrvcId=substr($row['MAINSERID'],0,strpos($row['MAINSERID'],',',0));
    $sqlUpdate = "UPDATE billing.`ADDON_RANK` SET RANK = '".$row['POPULARITY']."' WHERE VAS_ID='".$row['SERVICEID']."' AND MSID='".$mainSrvcId."'";
    $rsUpdate=mysql_query_decide($sqlUpdate) or die("$sqlUpdate".mysql_error());
}

$sqlAll="SELECT SUBSTRING( SERVICEID, 1, 1 ) AS SERVICEID, COUNT( SERVICEID ) AS POPULARITY FROM billing.`VAS_POP` GROUP BY SUBSTRING( SERVICEID, 1, 1 )";
$rsAll=mysql_query_decide($sqlAll) or die(mysql_error());
$insertAll="INSERT INTO billing.`ADDON_RANK`(MSID,VAS_ID,RANK) VALUES";
	while($row=mysql_fetch_assoc($rsAll)){
		$insertAll.="('ALL','".$row['SERVICEID']."','".$row['POPULARITY']."'),";
	}
	foreach($defVAS as $vas=>$rank){
		$insertAll.="('DEF','".$vas."','".$rank."'),";
	}
	$insertAll=substr($insertAll,0,-1);
	$rsInsertAll=mysql_query_decide($insertAll) or die(mysql_error());
?>

