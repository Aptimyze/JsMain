<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

function connect_db($arr)
{
	$db=mysql_connect($arr[1],$arr[2],$arr[3]) or dies(mysql_error());
	mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
	//SET sql_log_bin = 0;
//	mysql_query('SET sql_log_bin = 0;',$db);
	return $db;
}
//Server Db connection
/*$SERVER_ARR["Master"]=array("master","10.208.64.109:3306","user","CLDLRTa9");
$SERVER_ARR["737"]=array("slave10269","10.208.68.193:3306","user","CLDLRTa9");
$SERVER_ARR["11Master"]=array("shard1","10.208.68.211:3306","user","CLDLRTa9");
$SERVER_ARR["211Master"]=array("shard2","10.208.68.196:3307","user","CLDLRTa9");
$SERVER_ARR["303Master"]=array("shard3","10.208.68.212:3307","user","CLDLRTa9");
*/
$SERVER_ARR["Master"]=array("master","172.16.3.185:4306","localuser","Km7Iv80l");
$SERVER_ARR["737"]=array("slave10269","172.16.3.185:4306","localuser","Km7Iv80l");
$SERVER_ARR["11Master"]=array("shard1","172.16.3.185:4307","localuser","Km7Iv80l");
$SERVER_ARR["211Master"]=array("shard2","172.16.3.185:4308","localuser","Km7Iv80l");
$SERVER_ARR["303Master"]=array("shard3","172.16.3.185:4309","localuser","Km7Iv80l");
//Server master name
$Arr[0]="Master";
$Arr[1]="737";
$Arr[2]="11Master";
$Arr[3]="211Master";
$Arr[4]="303Master";

//Server master Table, used to run queries
$trr[0]="master_table_master_new";
$trr[1]="master_table_737_new";
$trr[2]="master_table_11Master_new";
$trr[3]="master_table_211_new";
$trr[4]="master_table_303Master_new";

//$SERVER_ARR[11Slave]=array("slave_shard1","10.208.69.14","user","CLDLRTa9",3309);
//$SERVER_ARR[211Slave]=array("slave_shard2","10.208.69.14","user","CLDLRTa9",3306);
//$SERVER_ARR[303Slave]=array("slave_shard3","10.208.69.14","user","CLDLRTa9",3308);
//$SERVER_ARR[11Slave]=array("viewsim","10.208.68.168","user","CLDLRTa9",3308);
//$SERVER_ARR[Slave81]=array("slave10120","10.208.67.196","user","CLDLRTa9",3306);
//$SERVER_ARR[Slave]=array("slave_10018","10.208.68.168","user","CLDLRTa9",3306);

/*$Arr[4]="11Slave";
$Arr[6]="211Slave";
$Arr[8]="303Slave";
$Arr[9]="Slave81";
$Arr[1]="Slave";
*/
/*
$trr[1]="master_table_Slave_new";
$trr[4]="master_table_11Slave_new";
$trr[6]="master_table_211Slave_new";
$trr[8]="master_table_303Slave_new";
$trr[9]="master_table_Slave81_new";
*/


//First argument is for ordering.
//Second Table name.
//third for screening log table and for argument[2] value 0,1,2

// stop if arguments passed are invalid
if(!isset($trr[$argv[1]]))
dies("Please enter valid argument value(1st argument between 0-1 and 2nd between 0-4)");
if(!isset($trr[$argv[2]]))
dies("Please enter valid arguments value(1st argument between 0-1 and 2nd between 0-4)");


$dbName="test";
$dbArr=$SERVER_ARR[$Arr[$argv[2]]];

$db=connect_db($dbArr);

if($argv[1])
        $ordering=" desc";
else
        $ordering=" asc";

$tableName=$trr[$argv[2]];

//Hard coded for the tables that have 'MRG_MYISAM' as engine[SCREENING_LOG] to take care of ordering

if(isset($argv[3]) && ($argv[2]==0 || $argv[2]==1)) 
{
//	$flag=0;
	$sql_fix1="alter table jsadmin.SCREENING_LOG2 change PROFILEID PROFILEID int(11) unsigned DEFAULT 0 NOT NULL";
	$sql_fix2="alter table jsadmin.SCREENING_LOG1 change PROFILEID PROFILEID int(11) unsigned DEFAULT 0 NOT NULL";
	$sql_fix3="alter table jsadmin.SCREENING_LOG change PROFILEID PROFILEID int(11) unsigned DEFAULT 0 NOT NULL";
	mysql_query($sql_fix1,$db) or dies($sql_fix1.mysql_error($db));
	mysql_query($sql_fix2,$db) or dies($sql_fix2.mysql_error($db));
	mysql_query($sql_fix3,$db) or dies($sql_fix3.mysql_error($db));
	
}

while(1)

{
	$db=connect_db($dbArr);
//	mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);

	// pick alter statements from tables where pending is N and Done is N
        $sql="SELECT TABLE_NAME,DATABASE_NAME, Query_name, ID FROM $dbName.$tableName WHERE Pending='N' and Done='N' order by COUNT_ENTRIES $ordering limit 1";
        $res=mysql_query($sql,$db) or dies(mysql_error($db));

        if($row=mysql_fetch_array($res))

        {

                $sql1=stripslashes(addslashes($row[Query_name]));

                $Id=$row[ID];

                $DB_NAME=$row[DATABASE_NAME];
                $Table=$row[TABLE_NAME];

		// check if table exists
                mysql_select_db("$DB_NAME",$db);

                $sql2="SHOW TABLES LIKE '$Table';";

                $res1=mysql_query($sql2,$db) or dies($sql2.mysql_error($db));

               // Update table with Pending Y that is picked up by cron to execute Alter
                $sql2="UPDATE $dbName.$tableName set Pending='Y' where  ID='$Id'";

                $res2=mysql_query($sql2,$db) or dies($sql2.mysql_error($db));

                if(mysql_num_rows($res1))
                {
               
                // <--! Alter statement will execute now-->

                        $StrTime = time();

                        mysql_query($sql1,$db) or dies($sql1.mysql_error($db));

                        $EndTime= time()-$StrTime;

			// update table with Done Y and time taken to execute alter, where Alter has executed successfully

                        $sql3="update $dbName.$tableName  set Done='Y',PENDING='N',TIME='$EndTime' where ID='$Id'";

                        $res1=mysql_query($sql3,$db) or dies($sql3.mysql_error($db));
                }

        }

        else

        {
	        dies("DONE");
	}

}
function dies($mes)
{
	die($mes);
}
