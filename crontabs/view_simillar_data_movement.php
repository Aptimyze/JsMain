<?php
$flag_using_php5=1;
include(JsConstants::$cronDocRoot."/crontabs/connect.inc");

//Checing if tables are properly populated
$db=connect_slave81();
mysql_select_db("test",$db);

mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);

$arr=array("CONTACTS_SEARCH","CONTACTS_SEARCH2","CONTACTS_SEARCH_NEW","TEMPRECEIVER","TEMPSENDER");
foreach($arr as $v)
{
        $table=$v."_PREV";

        $sql="SELECT COUNT(*) AS CNT FROM $table";
        $res=mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);
	$row=mysql_fetch_array($res);
	if($row["CNT"]<1)
	{
		$today=date("Y-m-d");
		mail('lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com','VIEW SIMILLAR ERROR:view_simillar_data_movement.php',$today);
		die($table);
	}
}
//Checing if tables are properly populated

//TRUNCATE target db tables

$passthruString = 'echo "truncate table CONTACTS_SEARCH_PREV" | '.MysqlDbConstants::$mySqlPath.' -u'. MysqlDbConstants::$viewSimilarDDL[USER].' -p'.MysqlDbConstants::$viewSimilarDDL[PASS] .' -h'. MysqlDbConstants::$viewSimilarDDL[HOST]. ' -P '.MysqlDbConstants::$viewSimilarDDL[PORT].' newjs;echo "truncate table CONTACTS_SEARCH2_PREV" | '.MysqlDbConstants::$mySqlPath.' -u'. MysqlDbConstants::$viewSimilarDDL[USER].' -p'.MysqlDbConstants::$viewSimilarDDL[PASS] .' -h'. MysqlDbConstants::$viewSimilarDDL[HOST]. ' -P '.MysqlDbConstants::$viewSimilarDDL[PORT].' newjs;echo "truncate table CONTACTS_SEARCH_NEW_PREV" | '.MysqlDbConstants::$mySqlPath.' -u'. MysqlDbConstants::$viewSimilarDDL[USER].' -p'.MysqlDbConstants::$viewSimilarDDL[PASS] .' -h'. MysqlDbConstants::$viewSimilarDDL[HOST]. ' -P '.MysqlDbConstants::$viewSimilarDDL[PORT].' newjs;echo "truncate table TEMPRECEIVER_PREV" | '.MysqlDbConstants::$mySqlPath.' -u'. MysqlDbConstants::$viewSimilarDDL[USER].' -p'.MysqlDbConstants::$viewSimilarDDL[PASS] .' -h'. MysqlDbConstants::$viewSimilarDDL[HOST]. ' -P '.MysqlDbConstants::$viewSimilarDDL[PORT].' newjs;echo "truncate table TEMPSENDER_PREV" | '.MysqlDbConstants::$mySqlPath.' -u'. MysqlDbConstants::$viewSimilarDDL[USER].' -p'.MysqlDbConstants::$viewSimilarDDL[PASS] .' -h'. MysqlDbConstants::$viewSimilarDDL[HOST]. ' -P '.MysqlDbConstants::$viewSimilarDDL[PORT].' newjs;';
passthru($passthruString);
//TRUNCATE target db tables


//Disable keys at target db.
$db2=connect_db4_ddl();
mysql_select_db("newjs",$db2);
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db2);
foreach($arr as $v)
{
        $table=$v."_PREV";
	$sql="ALTER TABLE  $table DISABLE KEYS";
	$res=mysql_query($sql,$db2) or die(mysql_error() . "<BR>" . $sql);
}
//Disable keys at target db.

//Dump
$passthruString = MysqlDbConstants::$mySqlDumpPath.' -t -u '.MysqlDbConstants::$alertsSlave['USER'].' -p'.MysqlDbConstants::$alertsSlave['PASS'].' -h '.MysqlDbConstants::$alertsSlave['HOST'].' -P '.MysqlDbConstants::$alertsSlave['PORT'].' '.'test'.' CONTACTS_SEARCH_PREV | '.MysqlDbConstants::$mySqlPath.' -u'. MysqlDbConstants::$viewSimilarDDL[USER].' -p'.MysqlDbConstants::$viewSimilarDDL[PASS] .' -h'. MysqlDbConstants::$viewSimilarDDL[HOST]. ' -P '.MysqlDbConstants::$viewSimilarDDL[PORT].' newjs;'.MysqlDbConstants::$mySqlDumpPath.' -t -u '.MysqlDbConstants::$alertsSlave['USER'].' -p'.MysqlDbConstants::$alertsSlave['PASS'].' -h '.MysqlDbConstants::$alertsSlave['HOST'].' -P '.MysqlDbConstants::$alertsSlave['PORT'].' '.'test'.' CONTACTS_SEARCH2_PREV | '.MysqlDbConstants::$mySqlPath.' -u'. MysqlDbConstants::$viewSimilarDDL[USER].' -p'.MysqlDbConstants::$viewSimilarDDL[PASS] .' -h'. MysqlDbConstants::$viewSimilarDDL[HOST]. ' -P '.MysqlDbConstants::$viewSimilarDDL[PORT].' newjs;'.MysqlDbConstants::$mySqlDumpPath.' -t -u '.MysqlDbConstants::$alertsSlave['USER'].' -p'.MysqlDbConstants::$alertsSlave['PASS'].' -h '.MysqlDbConstants::$alertsSlave['HOST'].' -P '.MysqlDbConstants::$alertsSlave['PORT'].' '.'test'.' CONTACTS_SEARCH_NEW_PREV | '.MysqlDbConstants::$mySqlPath.' -u'. MysqlDbConstants::$viewSimilarDDL[USER].' -p'.MysqlDbConstants::$viewSimilarDDL[PASS] .' -h'. MysqlDbConstants::$viewSimilarDDL[HOST]. ' -P '.MysqlDbConstants::$viewSimilarDDL[PORT].' newjs;'.MysqlDbConstants::$mySqlDumpPath.' -t -u '.MysqlDbConstants::$alertsSlave['USER'].' -p'.MysqlDbConstants::$alertsSlave['PASS'].' -h '.MysqlDbConstants::$alertsSlave['HOST'].' -P '.MysqlDbConstants::$alertsSlave['PORT'].' '.'test'.' TEMPRECEIVER_PREV | '.MysqlDbConstants::$mySqlPath.' -u'. MysqlDbConstants::$viewSimilarDDL[USER].' -p'.MysqlDbConstants::$viewSimilarDDL[PASS] .' -h'. MysqlDbConstants::$viewSimilarDDL[HOST]. ' -P '.MysqlDbConstants::$viewSimilarDDL[PORT].' newjs;'.MysqlDbConstants::$mySqlDumpPath.' -t -u '.MysqlDbConstants::$alertsSlave['USER'].' -p'.MysqlDbConstants::$alertsSlave['PASS'].' -h '.MysqlDbConstants::$alertsSlave['HOST'].' -P '.MysqlDbConstants::$alertsSlave['PORT'].' '.'test'.' TEMPSENDER_PREV | '.MysqlDbConstants::$mySqlPath.' -u'. MysqlDbConstants::$viewSimilarDDL[USER].' -p'.MysqlDbConstants::$viewSimilarDDL[PASS] .' -h'. MysqlDbConstants::$viewSimilarDDL[HOST]. ' -P '.MysqlDbConstants::$viewSimilarDDL[PORT].' newjs';
passthru($passthruString);
//Dump

//enable keys at target db.
$db2=connect_db4_ddl();
mysql_select_db("newjs",$db2);
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db2);
foreach($arr as $v)
{
        $table=$v."_PREV";
	$sql="ALTER TABLE  newjs.$table ENABLE KEYS";
	$res=mysql_query($sql,$db2) or die(mysql_error() . "<BR>" . $sql);
}
//enable keys at target db.


//Checing if tables after crons are properly populated
$db2=connect_db4_ddl();
mysql_select_db("newjs",$db2);
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db2);
$arr=array("CONTACTS_SEARCH","CONTACTS_SEARCH2","CONTACTS_SEARCH_NEW","TEMPRECEIVER","TEMPSENDER");
foreach($arr as $v)
{
        $table=$v."_PREV";
        $sql="SELECT COUNT(*) AS CNT FROM newjs.$table";
        $res=mysql_query($sql,$db2) or die(mysql_error() . "<BR>" . $sql);
	$row=mysql_fetch_array($res);
	if($row["CNT"]<1)
	{
		$today=date("Y-m-d");
		mail('lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com','VIEW SIMILLAR ERROR2:view_simillar_data_movement.php',$today);
		die;
	}
}
//Checing if tables after crons are properly populated

$arr=array("CONTACTS_SEARCH","CONTACTS_SEARCH2","CONTACTS_SEARCH_NEW","TEMPRECEIVER","TEMPSENDER");
foreach($arr as $v)
{
        $table=$v."_PREV";
        $table2=$v."_PREV2";

	$sql="RENAME TABLE $v TO $table2, $table TO $v , $table2 TO $table";
        mysql_query($sql,$db2) or die(mysql_error() . "<BR>" . $sql);

	$sql="TRUNCATE TABLE $table";
	mysql_query($sql,$db2) or die(mysql_error() . "<BR>" . $sql);
}
?>
