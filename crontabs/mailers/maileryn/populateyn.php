<?php

include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/contacts_functions.php");

function populateyn($mailer_id)
{
	global $db;
	global $mysqlObj;
	global $noOfActiveServers;
	global $slave_activeServers;
    
	$db_ddl = connect_ddl();

	$mysqlObj =new Mysql;
	mysql_query('set session wait_timeout=50000,interactive_timeout=50000,net_read_timeout=50000',$db);

	$sql11="TRUNCATE TABLE MAILERYN";
	$result11=mysql_query($sql11,$db_ddl) or logerror1("at truncate MAILER of populateyn",$sql11);	
 
        $emailsum2="EMAILSUM2".$mailer_id;
        $sql="TRUNCATE TABLE `$emailsum2`";
	mysql_query($sql,$db_ddl);

	// insert the No. of times each guy has contacted and the contact is open
	//Sharding of CONTACTS done by Sadaf
	for($serverId=0;$serverId<$noOfActiveServers;$serverId++)
	{
		$myDbName=$slave_activeServers[$serverId];
		$myDb=$mysqlObj->connect("$myDbName");
		mysql_query("set session wait_timeout=50000",$myDb);
		 $sql="select RECEIVER,count(*) as count from newjs.CONTACTS,newjs.PROFILEID_SERVER_MAPPING where TYPE='I' and FILTERED<>'Y' and TIME >= DATE_SUB(CURDATE(), INTERVAL 150 DAY) AND RECEIVER=PROFILEID AND SERVERID='$serverId' group by RECEIVER";
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
                                                $str.="('".$myrow["RECEIVER"]."','".$myrow["count"]."'),";
                                        }
                                        $str=rtrim($str,",");
                                        $sql="INSERT INTO $emailsum2(PROFILEID,RESID) VALUES $str";
                                        mysql_query($sql,$db) or logerror1("at 1 of populateac",$sql);
                                        $mysqlNumRows-=10000;
                                        $index++;
                                }
				if($mysqlNumRows>0)
				{
					unset($str);
					for($i=$index*10000+1;$i<=$totalRows;$i++)
					{
						$myrow=$mysqlObj->fetchAssoc($mysqlRes);
							$str.="('".$myrow["RECEIVER"]."','".$myrow["count"]."'),";
					}
					$str=rtrim($str,",");
					$sql="INSERT INTO $emailsum2(PROFILEID,RESID) VALUES $str";
						mysql_query($sql,$db) or logerror1("at 1 of populateac",$sql);
				}
	        	}
			else
                        {
                                unset($str);
                                while($myrow=$mysqlObj->fetchAssoc($mysqlRes))
                                {
                                        $str.="('".$myrow["RECEIVER"]."','".$myrow["count"]."'),";
                                }
                                $str=rtrim($str,",");
                                $sql="INSERT INTO $emailsum2(PROFILEID,RESID) VALUES $str";
				mysql_query($sql,$db) or logerror1("at 1 of populateyn",$sql);
                        }
                }
		unset($myDb);
		unset($myDbName);
	}
	
	/* Those profiles will not be sent yes/no mailer who have -
	1) Not logged-in in the last 1 year OR
	2) Profile is not live OR
	3) Profile is registered in last 15 days OR
	4) Profile has unsubscribed from service mails
	*/

	$sql="delete A.* from $emailsum2 A left join newjs.JPROFILE B on A.PROFILEID=B.PROFILEID AND B.LAST_LOGIN_DT > DATE_SUB(CURDATE(), INTERVAL 1 YEAR) AND B.ACTIVATED='Y' AND B.activatedKey=1 and B.ENTRY_DT<DATE_SUB(CURDATE(), INTERVAL 15 DAY) and B.SERVICE_MESSAGES='S' where B.PROFILEID IS NULL";
	$result=mysql_query($sql,$db) or logerror1("at 2 of populateyn",$sql);
		
}		
?>
