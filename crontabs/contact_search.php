<?php
ini_set('memory_limit', '256M');
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

$flag_using_php5=1;
include(JsConstants::$cronDocRoot."/crontabs/connect.inc");
$db=connect_slave81();
mysql_select_db("test",$db);
mysql_query("set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000",$db);

$date_female=date("d");
	
$date_female = $date_female % 5;

$date_male=date("d");

$date_male = $date_male % 3;

if($date_male==0)
	$male_limit=30;
elseif($date_male==1)
	$male_limit=35;
else 
	$male_limit=40;
	
if($date_female==0)
	$female_limit=60;
elseif($date_female==1)
	$female_limit=65;
elseif($date_female==2)
	$female_limit=70;
elseif($date_female==3)
	$female_limit=75;
else
	$female_limit=80;
	
$sql="TRUNCATE TABLE CONTACTS_SEARCH_TEMP";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

$sql="TRUNCATE TABLE CONTACTS_SEARCH_TEMP2";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

$sql="TRUNCATE TABLE LAST_LOGIN_PROFILES";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

$sql="TRUNCATE TABLE CONTACTS_SEARCH_TEMP3";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

$sql="ALTER TABLE LAST_LOGIN_PROFILES DISABLE KEYS";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

$sql="INSERT INTO test.LAST_LOGIN_PROFILES SELECT PROFILEID,LAST_LOGIN_DT FROM newjs.SEARCH_MALE WHERE LAST_LOGIN_DT >= DATE_SUB(CURDATE(), INTERVAL 45 DAY)";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

$sql="INSERT INTO test.LAST_LOGIN_PROFILES SELECT PROFILEID,LAST_LOGIN_DT FROM newjs.SEARCH_FEMALE WHERE LAST_LOGIN_DT >= DATE_SUB(CURDATE(), INTERVAL 45 DAY)";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

$sql="ALTER TABLE LAST_LOGIN_PROFILES ENABLE KEYS";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

$mysqlObj=new Mysql;
for($serverId=0;$serverId<$noOfActiveServers;$serverId++)
{
	$myDbName=$slave_activeServers[$serverId];
	$myDb=$mysqlObj->connect("$myDbName");
	$sql="SELECT RECEIVER,COUNT(*) as cnt FROM newjs.CONTACTS GROUP BY RECEIVER HAVING cnt>=100";
	$mysqlRes=$mysqlObj->executeQuery($sql,$myDb);
	if($mysqlObj->numRows($mysqlRes))
	{
		if($mysqlObj->numRows($mysqlRes)>10000)
		{
			$mysqlNumRows=$mysqlObj->numRows($mysqlRes);
			$totalRows=$mysqlNumRows;
			$index=0;
			while($mysqlNumRows>10000)
			{
				unset($str);
				for($i=1;$i<=10000;$i++)
				{
					$myrow=$mysqlObj->fetchAssoc($mysqlRes);
					$str.="('".$myrow["RECEIVER"]."','".$myrow["cnt"]."'),";
				}
				$str=rtrim($str,",");
				$sql="INSERT INTO CONTACTS_SEARCH_TEMP3(PROFILEID,RESID) VALUES $str";
				mysql_query($sql,$db) or die(mysql_error($db)."<BR>".$sql);
				$mysqlNumRows-=10000;                                        
				$index++;
			}
			if($mysqlNumRows>0)
			{
				unset($str);
				for($i=$index*10000+1;$i<=$totalRows;$i++)
				{
					$myrow=$mysqlObj->fetchAssoc($mysqlRes);
						$str.="('".$myrow["RECEIVER"]."','".$myrow["cnt"]."'),";
				}
				$str=rtrim($str,",");
				$sql="INSERT INTO CONTACTS_SEARCH_TEMP3(PROFILEID,RESID) VALUES $str";
				mysql_query($sql,$db) or die(mysql_error()."<BR>".$sql);
			}
		}
		else
		{
			unset($str);
			while($myrow=$mysqlObj->fetchAssoc($mysqlRes))
			{
				$str.="('".$myrow["RECEIVER"]."','".$myrow["cnt"]."'),";
			}
			$str=rtrim($str,",");
			$sql="INSERT INTO CONTACTS_SEARCH_TEMP3(PROFILEID,RESID) VALUES $str";
			mysql_query($sql,$db) or die(mysql_error()."<BR>".$sql);
		}
	}

	$mysqlObj->ping("$myDb");

	$sql="SELECT COUNT(*) AS cnt, SENDER FROM newjs.CONTACTS,newjs.PROFILEID_SERVER_MAPPING WHERE PROFILEID=SENDER AND SERVERID ='$serverId'  GROUP BY SENDER HAVING cnt>500";
	$res=$mysqlObj->executeQuery($sql,$myDb);
	while($row=$mysqlObj->fetchAssoc($res))
	{
		$str_sender.=$row["SENDER"].",";
	}
	
	unset($myDb);
	unset($myDbName);

}
$str_sender=substr($str_sender,0,strlen($str_sender)-1);
$sql="SELECT A.PROFILEID FROM test.CONTACTS_SEARCH_TEMP3 AS A,newjs.SEARCH_FEMALE AS B WHERE A.PROFILEID=B.PROFILEID AND A.RESID>=200";
$res=mysql_query($sql,$db) or die(mysql_error()."<BR>".$sql);
while($row=mysql_fetch_assoc($res))
{
	$str_id.=$row["PROFILEID"].",";
}
$sql="SELECT A.PROFILEID FROM test.CONTACTS_SEARCH_TEMP3 AS A,newjs.SEARCH_MALE AS B WHERE A.PROFILEID=B.PROFILEID AND A.RESID>=100";
$res=mysql_query($sql,$db) or die(mysql_error()."<BR>".$sql);
while($row=mysql_fetch_assoc($res))
{
        $str_id.=$row["PROFILEID"].",";
}
$str_id=substr($str_id,0,strlen($str_id)-1);



$shard1 = 'echo "truncate table LAST_LOGIN_PROFILES" | '.MysqlDbConstants::$mySqlPath. ' -u '.MysqlDbConstants::$shard1Slave[USER]. ' -p'.MysqlDbConstants::$shard1Slave[PASS]. ' -h  '.MysqlDbConstants::$shard1Slave[HOST]. ' -P '.MysqlDbConstants::$shard1Slave[PORT]. ' test;'.MysqlDbConstants::$mySqlDumpPath. ' -t -u '.MysqlDbConstants::$alertsSlave[USER]. ' -p'.MysqlDbConstants::$alertsSlave[PASS]. ' -h  '.MysqlDbConstants::$alertsSlave[HOST]. ' -P '.MysqlDbConstants::$alertsSlave[PORT]. ' '.'test'. ' LAST_LOGIN_PROFILES | '.MysqlDbConstants::$mySqlPath. ' -t -u '.MysqlDbConstants::$shard1Slave[USER]. ' -p'.MysqlDbConstants::$shard1Slave[PASS]. ' -h  '.MysqlDbConstants::$shard1Slave[HOST]. ' -P '.MysqlDbConstants::$shard1Slave[PORT]. ' test';

passthru($shard1);

$shard2 = 'echo "truncate table LAST_LOGIN_PROFILES" | '.MysqlDbConstants::$mySqlPath. ' -u '.MysqlDbConstants::$shard2Slave[USER]. ' -p'.MysqlDbConstants::$shard2Slave[PASS]. ' -h  '.MysqlDbConstants::$shard2Slave[HOST]. ' -P '.MysqlDbConstants::$shard2Slave[PORT]. ' test;'.MysqlDbConstants::$mySqlDumpPath. ' -t -u '.MysqlDbConstants::$alertsSlave[USER]. ' -p'.MysqlDbConstants::$alertsSlave[PASS]. ' -h  '.MysqlDbConstants::$alertsSlave[HOST]. ' -P '.MysqlDbConstants::$alertsSlave[PORT]. ' '.'test'. ' LAST_LOGIN_PROFILES | '.MysqlDbConstants::$mySqlPath. ' -t -u '.MysqlDbConstants::$shard2Slave[USER]. ' -p'.MysqlDbConstants::$shard2Slave[PASS]. ' -h  '.MysqlDbConstants::$shard2Slave[HOST]. ' -P '.MysqlDbConstants::$shard2Slave[PORT]. '  test';
passthru($shard2);

$shard3 = 'echo "truncate table LAST_LOGIN_PROFILES" | '.MysqlDbConstants::$mySqlPath. ' -u '.MysqlDbConstants::$shard3Slave[USER]. ' -p'.MysqlDbConstants::$shard3Slave[PASS]. ' -h  '.MysqlDbConstants::$shard3Slave[HOST]. ' -P '.MysqlDbConstants::$shard3Slave[PORT]. ' test;'.MysqlDbConstants::$mySqlDumpPath. ' -t -u '.MysqlDbConstants::$alertsSlave[USER]. ' -p'.MysqlDbConstants::$alertsSlave[PASS]. ' -h  '.MysqlDbConstants::$alertsSlave[HOST]. ' -P '.MysqlDbConstants::$alertsSlave[PORT]. ' '.'test'. ' LAST_LOGIN_PROFILES | '.MysqlDbConstants::$mySqlPath. ' -t -u '.MysqlDbConstants::$shard3Slave[USER]. ' -p'.MysqlDbConstants::$shard3Slave[PASS]. ' -h  '.MysqlDbConstants::$shard3Slave[HOST]. ' -P '.MysqlDbConstants::$shard3Slave[PORT]. ' test';
passthru($shard3);

for($serverId=0;$serverId<$noOfActiveServers;$serverId++)
{
	$myDbName=$slave_activeServers[$serverId];
	$myDb=$mysqlObj->connect("$myDbName");
	$sql_delete="DELETE test.LAST_LOGIN_PROFILES.* FROM test.LAST_LOGIN_PROFILES,newjs.PROFILEID_SERVER_MAPPING WHERE test.LAST_LOGIN_PROFILES.PROFILEID=newjs.PROFILEID_SERVER_MAPPING.PROFILEID AND newjs.PROFILEID_SERVER_MAPPING.SERVERID<>'$serverId'";
	$mysqlObj->executeQuery($sql_delete,$myDb);
	if($str_id!="")
	{	
		$sql="SELECT C.SENDER, C.RECEIVER, IF(C.TYPE='A',3,IF(C.TYPE='I',2,1)) AS WEIGHT,EXP(-0.4*(DATEDIFF(NOW(),C.TIME))) AS CONTACT_SCORE FROM newjs.CONTACTS AS C,test.LAST_LOGIN_PROFILES WHERE C.RECEIVER=LAST_LOGIN_PROFILES.PROFILEID and RECEIVER not in ($str_id)";
		$sql2="SELECT C.SENDER, C.RECEIVER, IF(C.TYPE='A',3,IF(C.TYPE='I',2,1)) AS WEIGHT,EXP(-0.4*(DATEDIFF(NOW(),C.TIME))) AS CONTACT_SCORE FROM newjs.CONTACTS AS C,test.LAST_LOGIN_PROFILES WHERE C.RECEIVER=LAST_LOGIN_PROFILES.PROFILEID and RECEIVER in ($str_id)";
	}
	else
	{
		$sql="SELECT C.SENDER, C.RECEIVER, IF(C.TYPE='A',3,IF(C.TYPE='I',2,1)) AS WEIGHT,EXP(-0.4*(DATEDIFF(NOW(),C.TIME))) AS CONTACT_SCORE FROM newjs.CONTACTS AS C,test.LAST_LOGIN_PROFILES WHERE C.RECEIVER=LAST_LOGIN_PROFILES.PROFILEID";
	}

	if($str_sender)
	{	
		$sql.=" AND SENDER NOT IN($str_sender)";
		if($str_id)
			$sql2.=" AND SENDER NOT IN($str_sender)";
	}
	$mysqlRes=$mysqlObj->executeQuery($sql,$myDb);
	if($mysqlObj->numRows($mysqlRes))
        {
                if($mysqlObj->numRows($mysqlRes)>10000)
                {
                        $mysqlNumRows=$mysqlObj->numRows($mysqlRes);
                        $totalRows=$mysqlNumRows;
                        $index=0;
                        while($mysqlNumRows>10000)
                        {
                                unset($valuestr);
                                for($i=1;$i<=10000;$i++)
                                {
                                        $myrow=$mysqlObj->fetchAssoc($mysqlRes);
                                        $valuestr.="('".$myrow["SENDER"]."','".$myrow["RECEIVER"]."','".$myrow["WEIGHT"]."','".$myrow["CONTACT_SCORE"]."'),";
                                }
				$valuestr=rtrim($valuestr,",");
                                $sql="INSERT IGNORE INTO CONTACTS_SEARCH_TEMP(SENDER,RECEIVER,WEIGHT,CONTACT_SCORE) VALUES $valuestr";
                                mysql_query($sql,$db) or die(mysql_error($db)."<BR>".$sql);
                                $mysqlNumRows-=10000;                                   
				$index++;
                        }
			if($mysqlNumRows>0)
			{
				unset($valuestr);
				for($i=$index*10000+1;$i<=$totalRows;$i++)
				{
					$myrow=$mysqlObj->fetchAssoc($mysqlRes);
					$valuestr.="('".$myrow["SENDER"]."','".$myrow["RECEIVER"]."','".$myrow["WEIGHT"]."','".$myrow["CONTACT_SCORE"]."'),";
				}
				$valuestr=rtrim($valuestr,",");
				$sql="INSERT IGNORE INTO CONTACTS_SEARCH_TEMP(SENDER,RECEIVER,WEIGHT,CONTACT_SCORE) VALUES $valuestr";
				mysql_query($sql,$db) or die(mysql_error()."<BR>".$sql);
			}
		}
		else
		{
			unset($valuestr);
			while($myrow=$mysqlObj->fetchAssoc($mysqlRes))
			{
				$valuestr.="('".$myrow["SENDER"]."','".$myrow["RECEIVER"]."','".$myrow["WEIGHT"]."','".$myrow["CONTACT_SCORE"]."'),";
			}
			$valuestr=rtrim($valuestr,",");
			$sql="INSERT IGNORE INTO CONTACTS_SEARCH_TEMP(SENDER,RECEIVER,WEIGHT,CONTACT_SCORE) VALUES $valuestr";
			mysql_query($sql,$db) or die(mysql_error($db)."<BR>".$sql);
		}

	}
	if($sql2)
	{
		$mysqlObj->ping($myDb);
		$mysqlRes=$mysqlObj->executeQuery($sql2,$myDb);
		if($mysqlObj->numRows($mysqlRes))
		{
			if($mysqlObj->numRows($mysqlRes)>10000)
			{
				$mysqlNumRows=$mysqlObj->numRows($mysqlRes);
				$totalRows=$mysqlNumRows;
				$index=0;
				while($mysqlNumRows>10000)
				{
					unset($valuestr);
					for($i=1;$i<=10000;$i++)
					{
						$myrow=$mysqlObj->fetchAssoc($mysqlRes);
						$valuestr.="('".$myrow["SENDER"]."','".$myrow["RECEIVER"]."','".$myrow["WEIGHT"]."','".$myrow["CONTACT_SCORE"]."'),";
					}
					$valuestr=rtrim($valuestr,",");
					$sql="INSERT IGNORE INTO CONTACTS_SEARCH_TEMP2(SENDER,RECEIVER,WEIGHT,CONTACT_SCORE) VALUES $valuestr";
					mysql_query($sql,$db) or die(mysql_error()."<BR>".$sql);
					$mysqlNumRows-=10000;                                        $index++;
				}
				if($mysqlNumRows>0)
				{
					unset($valuestr);
					for($i=$index*10000+1;$i<=$totalRows;$i++)
					{
						$myrow=$mysqlObj->fetchAssoc($mysqlRes);
						$valuestr.="('".$myrow["SENDER"]."','".$myrow["RECEIVER"]."','".$myrow["WEIGHT"]."','".$myrow["CONTACT_SCORE"]."'),";
					}
					$valuestr=rtrim($valuestr,",");
					$sql="INSERT IGNORE INTO CONTACTS_SEARCH_TEMP2(SENDER,RECEIVER,WEIGHT,CONTACT_SCORE) VALUES $valuestr";
					mysql_query($sql,$db) or die(mysql_error()."<BR>".$sql);;
				}
			}
			else
			{
				unset($valuestr);
				while($myrow=$mysqlObj->fetchAssoc($mysqlRes))
				{
					$valuestr.="('".$myrow["SENDER"]."','".$myrow["RECEIVER"]."','".$myrow["WEIGHT"]."','".$myrow["CONTACT_SCORE"]."'),";
				}
				$valuestr=rtrim($valuestr,",");
				$sql="INSERT IGNORE INTO CONTACTS_SEARCH_TEMP2(SENDER,RECEIVER,WEIGHT,CONTACT_SCORE) VALUES $valuestr";
				mysql_query($sql,$db) or die(mysql_error()."<BR>".$sql);
			}
		}
	}

}

$sql="RENAME TABLE CONTACTS_SEARCH_TEMP TO CONTACTS_SEARCH_1, CONTACTS_SEARCH TO CONTACTS_SEARCH_TEMP, CONTACTS_SEARCH_1 TO CONTACTS_SEARCH";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

$sql="RENAME TABLE CONTACTS_SEARCH_TEMP2 TO CONTACTS_SEARCH_2, CONTACTS_SEARCH2 TO CONTACTS_SEARCH_TEMP2, CONTACTS_SEARCH_2 TO CONTACTS_SEARCH2";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

$sql="TRUNCATE TABLE CONTACTS_SEARCH_TEMP";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

$sql="TRUNCATE TABLE CONTACTS_SEARCH_TEMP2";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

$sql="update test.CONTACTS_SEARCH,newjs.SEARCH_FEMALE SET RECEIVER_INCOME=INCOME,CONTACTS_SEARCH.AGE=SEARCH_FEMALE.AGE WHERE PROFILEID=RECEIVER";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

$sql="update test.CONTACTS_SEARCH2,newjs.SEARCH_FEMALE SET RECEIVER_INCOME=INCOME,CONTACTS_SEARCH2.AGE=SEARCH_FEMALE.AGE WHERE PROFILEID=RECEIVER";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

$sql="update test.CONTACTS_SEARCH,newjs.SEARCH_MALE SET RECEIVER_INCOME=INCOME,CONTACTS_SEARCH.AGE=SEARCH_MALE.AGE WHERE PROFILEID=RECEIVER";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

$sql="update test.CONTACTS_SEARCH2,newjs.SEARCH_MALE SET RECEIVER_INCOME=INCOME,CONTACTS_SEARCH2.AGE=SEARCH_MALE.AGE WHERE PROFILEID=RECEIVER";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

//added by lavesh rawat as new simmilar profile logic is based on this new table.
$sql="TRUNCATE TABLE CONTACTS_SEARCH_NEW_TEMP";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

$sql="TRUNCATE TABLE CONTACTS_SEARCH_NEW_1";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

$sql="INSERT IGNORE INTO test.CONTACTS_SEARCH_NEW_TEMP(SENDER,RECEIVER,CONTACT_SCORE)(SELECT SENDER,RECEIVER,CONTACT_SCORE FROM test.CONTACTS_SEARCH)";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

$sql="INSERT IGNORE INTO test.CONTACTS_SEARCH_NEW_TEMP(SENDER,RECEIVER,CONTACT_SCORE)(SELECT RECEIVER,SENDER,CONTACT_SCORE FROM test.CONTACTS_SEARCH,LAST_LOGIN_PROFILES WHERE WEIGHT=3 AND SENDER=LAST_LOGIN_PROFILES.PROFILEID)";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

$sql="INSERT IGNORE INTO test.CONTACTS_SEARCH_NEW_TEMP(SENDER,RECEIVER,CONTACT_SCORE)(SELECT SENDER,RECEIVER,CONTACT_SCORE FROM test.CONTACTS_SEARCH2)";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

$sql="INSERT IGNORE INTO test.CONTACTS_SEARCH_NEW_TEMP(SENDER,RECEIVER,CONTACT_SCORE)(SELECT RECEIVER,SENDER,CONTACT_SCORE FROM test.CONTACTS_SEARCH2,LAST_LOGIN_PROFILES WHERE WEIGHT=3 AND SENDER=LAST_LOGIN_PROFILES.PROFILEID)";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

$sql="update test.CONTACTS_SEARCH_NEW_TEMP,newjs.SEARCH_FEMALE  SET RECEIVER_TOTAL_POINTS=TOTAL_POINTS WHERE PROFILEID=RECEIVER";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

$sql="update test.CONTACTS_SEARCH_NEW_TEMP,newjs.SEARCH_MALE  SET RECEIVER_TOTAL_POINTS=TOTAL_POINTS WHERE PROFILEID=RECEIVER";

mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

$sql="RENAME TABLE CONTACTS_SEARCH_NEW_TEMP TO CONTACTS_SEARCH_NEW1, CONTACTS_SEARCH_NEW TO CONTACTS_SEARCH_NEW_TEMP, CONTACTS_SEARCH_NEW1 TO CONTACTS_SEARCH_NEW";

mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

$sql="TRUNCATE TABLE CONTACTS_SEARCH_NEW_TEMP";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

$sql="TRUNCATE TABLE CONTACTS_SEARCH_NEW_1";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);


$sql="TRUNCATE TABLE TEMPRECEIVER";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

$sql="TRUNCATE TABLE TEMPSENDER";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

$sql="INSERT INTO TEMPRECEIVER SELECT DISTINCT (RECEIVER) FROM (SELECT RECEIVER, COUNT( * ) AS CNT FROM CONTACTS_SEARCH_NEW GROUP BY RECEIVER
HAVING CNT <300)t";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

$sql="INSERT INTO TEMPSENDER SELECT SENDER,COUNT(*) AS CNT FROM CONTACTS_SEARCH_NEW GROUP BY SENDER HAVING CNT < 300";

mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

$arr=array("CONTACTS_SEARCH","CONTACTS_SEARCH2","CONTACTS_SEARCH_NEW","TEMPRECEIVER","TEMPSENDER");
foreach($arr as $v)
{
        $table=$v."_PREV";
        $table2=$v."_PREV2";

        $sql="RENAME TABLE $v TO $table2, $table TO $v , $table2 TO $table";
        mysql_query($sql,$db) or die(mysql_error($db) . "<BR>" . $sql);
}

$sql="TRUNCATE TABLE LAST_LOGIN_PROFILES";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);
?>
