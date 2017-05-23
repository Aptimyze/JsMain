<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");

include("$_SERVER[DOCUMENT_ROOT]/P/connect.inc");


$mysqlObj=new Mysql;
$dbM=connect_db();
$mysqlObj->executeQuery("set session wait_timeout=10000",$dbM);

$dbS=connect_db();
$mysqlObj->executeQuery("set session wait_timeout=10000",$dbS);

for($serverId=0;$serverId<$noOfActiveServers;$serverId++)
{
        $myDbName=$activeServers[$serverId];
        $myDb[]=$mysqlObj->connect("$myDbName");//put index 0 for shard1, 1 for shard 2 and 3 for shard 3
}

$sql = "SELECT SENDER,RECEIVER,DATE FROM Assisted_Product.AUTOMATED_CONTACTS_TRACKING WHERE DATE>'2012-12-04'";
$result = mysql_query($sql,$dbS) or die(mysql_error($dbS));
while($row=mysql_fetch_assoc($result))
{
	$receiver=$row['RECEIVER'];
	$sender=$row['SENDER'];
	$modR = $receiver%3;
	$modS = $sender%3;

	$contactSelect = "SELECT * FROM newjs.CONTACTS WHERE SENDER=$receiver AND RECEIVER=$sender";
	$res = mysql_query($contactSelect,$myDb[$modR]) or die(mysql_error($myDb[$modR]));
	if($rowC=mysql_fetch_assoc($res))
	{
		if($row['DATE'] > $rowC['TIME'])
		{
			unset($affectedDb);
			unset($col_id);
			$affectedDb[] = $myDb[$modR];	
			if($modR!=$modS)
				$affectedDb[] = $myDb[$modS];	

			/*** Shards ***/
			for($ll=0;$ll<count($affectedDb);$ll++)
			{
				$shardDb =  $affectedDb[$ll];
				$ins = "INSERT IGNORE INTO newjs.AP_EOI_VIEW_LOG_DELETE SELECT * FROM newjs.EOI_VIEWED_LOG WHERE VIEWER=$receiver AND VIEWED=$sender";
				mysql_query($ins,$shardDb) or die(mysql_error($shardDb));

				$del="DELETE FROM newjs.EOI_VIEWED_LOG WHERE VIEWER=$receiver AND VIEWED=$sender";
				mysql_query($del,$shardDb) or die(mysql_error($shardDb));

				$ins = "INSERT IGNORE INTO newjs.AP_CONTACTS_DELETE SELECT * FROM newjs.CONTACTS WHERE SENDER=$sender AND RECEIVER=$receiver";
				mysql_query($ins,$shardDb) or die(mysql_error($shardDb));

				$del="DELETE FROM newjs.CONTACTS WHERE SENDER=$sender AND RECEIVER=$receiver";
				mysql_query($del,$shardDb) or die(mysql_error($shardDb));

				//------
				$sql_1="SELECT ID FROM newjs.MESSAGE_LOG WHERE SENDER=$sender AND RECEIVER=$receiver";
				$res_1=mysql_query($sql_1,$shardDb) or die(mysql_error($shardDb).$sql_1);
				while($row_1=mysql_fetch_array($res_1))
				{
					$col_id[]=$row_1["ID"];
				}
				if(is_array($col_id))
				{
					$col_str=implode("','",$col_id);

					$sql_1="INSERT IGNORE INTO newjs.AP_MESSAGE_LOG_DELETE SELECT * FROM newjs.MESSAGE_LOG WHERE ID IN ('$col_str')";
					mysql_query($sql_1,$shardDb) or die(mysql_error($shardDb).$sql_1);

					$sql_1="DELETE FROM newjs.MESSAGE_LOG WHERE ID IN ('$col_str')";
					mysql_query($sql_1,$shardDb) or die(mysql_error($shardDb).$sql_1);

					$sql_1="REPLACE INTO newjs.AP_DELETED_MESSAGES_DELETE SELECT * FROM newjs.MESSAGES WHERE ID IN ('$col_str')";
					mysql_query($sql_1,$shardDb) or die(mysql_error($shardDb).$sql_1);

					$sql_1="DELETE FROM newjs.MESSAGES WHERE ID IN ('$col_str')";
					mysql_query($sql_1,$shardDb) or die(mysql_error($shardDb).$sql_1);
				}
			}
			//------

			/*** Shards ***/


			$insAp = "INSERT INTO Assisted_Product.AP_BACKUP SELECT * FROM Assisted_Product.AUTOMATED_CONTACTS_TRACKING WHERE SENDER=$sender AND RECEIVER=$receiver";
			mysql_query($insAp,$dbM) or die(mysql_error($dbM));

			$delAp = "DELETE FROM Assisted_Product.AUTOMATED_CONTACTS_TRACKING WHERE SENDER=$sender AND RECEIVER=$receiver";
			mysql_query($delAp,$dbM) or die(mysql_error($dbM));
		}
		else
		{
			$ins = "INSERT IGNORE INTO newjs.AP_CONTACTS_ERROR SELECT * FROM newjs.CONTACTS WHERE SENDER=$sender AND RECEIVER=$receiver";
			mysql_query($ins,$myDb[$modR]) or die(mysql_error($myDb[$modR]));
			if($modR!=$modS)
				mysql_query($ins,$myDb[$modS]) or die(mysql_error($myDb[$modS]));
		}
	}
}	
?>
