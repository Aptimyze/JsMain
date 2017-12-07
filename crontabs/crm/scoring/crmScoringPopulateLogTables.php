<?php

//Sent mail for daily tracking
$msg="\nPopulate Log Tables # Start Time=".date("Y-m-d H:i:s");
$to="vibhor.garg@jeevansathi.com,manoj.rana@naukri.com";
$sub="Scoring Algorithm Log Tables";
$from="From:vibhor.garg@jeevansathi.com";
mail($to,$sub,$msg,$from);
ini_set('memory_limit', '300M');

//DB Connection
$myDb = mysql_connect("localhost:/tmp/mysql_06.sock","user_sel","CLDLRTa9") or die("Unable to connect to js server".$start);

//Delete entries from MESSAGE_LOG tables for 31st day
$lim_31_dt = date("Y-m-d", time() - 31 * 86400);
$sq1 = "DELETE FROM test.MESSAGE_LOG_SHARD1 WHERE DATE >= '$lim_31_dt 00:00:00' AND DATE <= '$lim_31_dt 23:59:59'";
mysql_query($sq1,$myDb) or die($sq1.mysql_error($myDb));
$sq2 = "DELETE FROM test.MESSAGE_LOG_SHARD2 WHERE DATE >= '$lim_31_dt 00:00:00' AND DATE <= '$lim_31_dt 23:59:59'";
mysql_query($sq2,$myDb) or die($sq2.mysql_error($myDb));
$sq3 = "DELETE FROM test.MESSAGE_LOG_SHARD3 WHERE DATE >= '$lim_31_dt 00:00:00' AND DATE <= '$lim_31_dt 23:59:59'";
mysql_query($sq3,$myDb) or die($sq3.mysql_error($myDb));

//Delete entries from EOI_VIEWED_LOG tables for 61st day
$lim_61_dt = date("Y-m-d", time() - 61 * 86400);
$sq1 = "DELETE FROM test.EOI_VIEWED_LOG_SHARD1 WHERE DATE >= '$lim_61_dt 00:00:00' AND DATE <= '$lim_61_dt 23:59:59'";
mysql_query($sq1,$myDb) or die($sq1.mysql_error($myDb));
$sq2 = "DELETE FROM test.EOI_VIEWED_LOG_SHARD2 WHERE DATE >= '$lim_61_dt 00:00:00' AND DATE <= '$lim_61_dt 23:59:59'";
mysql_query($sq2,$myDb) or die($sq2.mysql_error($myDb));
$sq3 = "DELETE FROM test.EOI_VIEWED_LOG_SHARD3 WHERE DATE >= '$lim_61_dt 00:00:00' AND DATE <= '$lim_61_dt 23:59:59'";
mysql_query($sq3,$myDb) or die($sq3.mysql_error($myDb));

//Populate entries of MESSAGE_LOG & EOI_VIEWED_LOG tables of last day
$lim_1_dt = date("Y-m-d", time() - 1 * 86400);
/*$sqllm = "SELECT SENDER,RECEIVER,TYPE,DATE FROM newjs.MESSAGE_LOG WHERE DATE >= '$lim_1_dt 00:00:00' AND DATE <= '$lim_1_dt 23:59:59'";
$sqlle = "SELECT * FROM newjs.EOI_VIEWED_LOG WHERE DATE >= '$lim_1_dt 00:00:00' AND DATE <= '$lim_1_dt 23:59:59'";*/

//Shard1
	$shDb1 = mysql_connect("productshard2slave.js.jsb9.net:3309","user_sel","CLDLRTa9") or die("Unable to connect to js server".$start);
	$dateArr = getMaxDate($myDb,1);
	$lim_m_dt =$dateArr['M'];
	$lim_e_dt =$dateArr['E'];

	$sqllm = "SELECT SENDER,RECEIVER,TYPE,DATE FROM newjs.MESSAGE_LOG WHERE DATE >= '$lim_m_dt 00:00:00' AND DATE <= '$lim_1_dt 23:59:59'";
	$sqlle = "SELECT * FROM newjs.EOI_VIEWED_LOG WHERE DATE >= '$lim_e_dt 00:00:00' AND DATE <= '$lim_1_dt 23:59:59'";

	$resl = mysql_query($sqllm, $shDb1) or die($sqllm . mysql_error($shDb1));
	while ($rowml2 = mysql_fetch_array($resl)) 
	{
		$sq1="INSERT INTO test.MESSAGE_LOG_SHARD1 (SENDER,RECEIVER,TYPE,DATE) VALUES ('$rowml2[SENDER]','$rowml2[RECEIVER]','$rowml2[TYPE]','$rowml2[DATE]')";
		mysql_query($sq1,$myDb) or die($sq1.mysql_error($myDb));
	}
	$resl = mysql_query($sqlle, $shDb1) or die($sqlle . mysql_error($shDb1));
	while ($rowml2 = mysql_fetch_array($resl))
	{
		$sq1="INSERT IGNORE INTO test.EOI_VIEWED_LOG_SHARD1 VALUES ('$rowml2[VIEWER]','$rowml2[VIEWED]','$rowml2[DATE]')";
		mysql_query($sq1,$myDb) or die($sq1.mysql_error($myDb));
	}

//Shard2
	$shDb2 = mysql_connect("productshard2slave.js.jsb9.net:3306","user_sel","CLDLRTa9") or die("Unable to connect to js server".$start);
        $dateArr =getMaxDate($myDb,2);
        $lim_m_dt =$dateArr['M'];
        $lim_e_dt =$dateArr['E'];

        $sqllm = "SELECT SENDER,RECEIVER,TYPE,DATE FROM newjs.MESSAGE_LOG WHERE DATE >= '$lim_m_dt 00:00:00' AND DATE <= '$lim_1_dt 23:59:59'";
        $sqlle = "SELECT * FROM newjs.EOI_VIEWED_LOG WHERE DATE >= '$lim_e_dt 00:00:00' AND DATE <= '$lim_1_dt 23:59:59'";

	$resl = mysql_query($sqllm, $shDb2) or die($sqllm . mysql_error($shDb2));
	while ($rowml2 = mysql_fetch_array($resl)) 
	{
		$sq2="INSERT INTO test.MESSAGE_LOG_SHARD2 (SENDER,RECEIVER,TYPE,DATE) VALUES ('$rowml2[SENDER]','$rowml2[RECEIVER]','$rowml2[TYPE]','$rowml2[DATE]')";
		mysql_query($sq2,$myDb) or die($sq2.mysql_error($myDb));
	}
	$resl = mysql_query($sqlle, $shDb2) or die($sqlle . mysql_error($shDb2));
	while ($rowml2 = mysql_fetch_array($resl))
	{
		$sq2="INSERT IGNORE INTO test.EOI_VIEWED_LOG_SHARD2 VALUES ('$rowml2[VIEWER]','$rowml2[VIEWED]','$rowml2[DATE]')";
		mysql_query($sq2,$myDb) or die($sq2.mysql_error($myDb));
	}

//Shard 3
	$shDb3 = mysql_connect("productshard2slave.js.jsb9.net:3307","user_sel","CLDLRTa9") or die("Unable to connect to js server".$start);
        $dateArr =getMaxDate($myDb,3);
        $lim_m_dt =$dateArr['M'];
        $lim_e_dt =$dateArr['E'];

        $sqllm = "SELECT SENDER,RECEIVER,TYPE,DATE FROM newjs.MESSAGE_LOG WHERE DATE >= '$lim_m_dt 00:00:00' AND DATE <= '$lim_1_dt 23:59:59'";
        $sqlle = "SELECT * FROM newjs.EOI_VIEWED_LOG WHERE DATE >= '$lim_e_dt 00:00:00' AND DATE <= '$lim_1_dt 23:59:59'";

	$resl = mysql_query($sqllm, $shDb3) or die($sqllm . mysql_error($shDb3));
	while ($rowml2 = mysql_fetch_array($resl))
	{
		$sq3="INSERT INTO test.MESSAGE_LOG_SHARD3 (SENDER,RECEIVER,TYPE,DATE) VALUES ('$rowml2[SENDER]','$rowml2[RECEIVER]','$rowml2[TYPE]','$rowml2[DATE]')";
		mysql_query($sq3,$myDb) or die($sq3.mysql_error($myDb));
	}
	$resl = mysql_query($sqlle, $shDb3) or die($sqlle . mysql_error($shDb3));
	while ($rowml2 = mysql_fetch_array($resl))
	{
		$sq3="INSERT IGNORE INTO test.EOI_VIEWED_LOG_SHARD3 VALUES ('$rowml2[VIEWER]','$rowml2[VIEWED]','$rowml2[DATE]')";
		mysql_query($sq3,$myDb) or die($sq3.mysql_error($myDb));
	}

function getMaxDate($myDb,$Num){
	$sql1 ="select max(DATE) DATE from test.MESSAGE_LOG_SHARD$Num";
	$sql2 ="select max(DATE) DATE from test.EOI_VIEWED_LOG_SHARD$Num";

	$res1 =mysql_query($sql1,$myDb) or die($sql1.mysql_error($myDb));
	$res2 =mysql_query($sql2,$myDb) or die($sql2.mysql_error($myDb));

	if($row1 = mysql_fetch_array($res1)){
		$date1 =$row1['DATE'];
	}
	if($row2 = mysql_fetch_array($res2)){
		$date2 =$row2['DATE'];
	}
	$dataArr =array("M"=>$date1,"E"=>$date2);
	return $dataArr;	
}

//Sent mail for daily tracking
$msg="\n Populate Log Tables # End Time=".date("Y-m-d H:i:s");
mail($to,$sub,$msg,$from);

?>
