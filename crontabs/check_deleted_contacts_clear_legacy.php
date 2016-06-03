<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/****************************************************************************************************************
Filename    : check_deleted_contacts.php
Description : To remove any left over entries while deleting a profile [3308]
Author      : Sadaf Alam
*****************************************************************************************************************/
$flag_using_php5=1;
include("connect.inc");
include($_SERVER['DOCUMENT_ROOT']."/profile/connect_functions.inc");

$TABLES_SHARDED=array(array("TABLE_NAME"=>"newjs.CONTACTS","DEL_TABLE_NAME"=>"newjs.DELETED_PROFILE_CONTACTS","COLUMNS"=>array("SENDER","RECEIVER")),
		array("TABLE_NAME"=>"newjs.HOROSCOPE_REQUEST","DEL_TABLE_NAME"=>"newjs.DELETED_HOROSCOPE_REQUEST","COLUMNS"=>array("PROFILEID","PROFILEID_REQUEST_BY")),
		array("TABLE_NAME"=>"newjs.PHOTO_REQUEST","DEL_TABLE_NAME"=>"newjs.DELETED_PHOTO_REQUEST","COLUMNS"=>array("PROFILEID","PROFILEID_REQ_BY")),
		array("TABLE_NAME"=>"newjs.MESSAGE_LOG","DEL_TABLE_NAME"=>"newjs.DELETED_MESSAGE_LOG","COLUMNS"=>array("SENDER","RECEIVER")));


$TABLES=array(array("TABLE_NAME"=>"newjs.BOOKMARKS","DEL_TABLE_NAME"=>"newjs.DELETED_BOOKMARKS","COLUMNS"=>array("BOOKMARKER","BOOKMARKEE")),
	array("TABLE_NAME"=>"newjs.IGNORE_PROFILE","DEL_TABLE_NAME"=>"newjs.DELETED_IGNORE_PROFILE","COLUMNS"=>array("PROFILEID","IGNORED_PROFILEID")));


for($suffix=1;$suffix<16;$suffix++)
{
	$TABLES_211[]=array("TABLE_NAME"=>"newjs.VIEW_LOG_TRIGGER_$suffix","COLUMNS"=>array("VIEWER"));
}

$db=connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);

$mysqlObj=new Mysql;

$today=date("Y-m-d");
$sql="SELECT PROFILEID FROM newjs.DELETED_PROFILE_LOG WHERE DATA_CHECK='' LIMIT 20";
//echo $sql;
$res=mysql_query($sql,$db) or logError($sql);
if(mysql_num_rows($res))
{
	while($row=mysql_fetch_assoc($res))
	{
		$profileid=$row["PROFILEID"];
		foreach($TABLES as $table)
		{
			if(is_array($table["COLUMNS"]) && count($table["COLUMNS"]>0))
			{
				foreach($table["COLUMNS"] as $column)
				{
					if($table["DEL_TABLE_NAME"])
                                                {
                                                        $sqldel="INSERT INTO $table[DEL_TABLE_NAME] SELECT * FROM $table[TABLE_NAME] WHERE $column='$profileid'";
							//echo "\n".$sqldel;
                                                        $resdel=mysql_query($sqldel,$db) or logError($sqldel);
                                                        if($resdel)
                                                        {
                                                                $sqldel="DELETE FROM $table[TABLE_NAME] WHERE $column='$profileid'";
								//echo "\n".$sqldel;
                                                                mysql_query($sqldel,$db) or logError($sqldel);
                                                        }
                                                }
                                                else
                                                {
                                                        $sqldel="DELETE FROM $table[TABLE_NAME] WHERE $column='$profileid'";
							//echo "\n".$sqldel;
                                                        mysql_query($sqldel,$db) or logError($sqldel);
                                                }
			
				}
			}
		}
		for($serverId=0;$serverId<$noOfActiveServers;$serverId++)
		{
			$myDbName=$activeServers[$serverId];
			$myDb=$mysqlObj->connect("$myDbName");
			mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$myDb);
			
			foreach($TABLES_SHARDED as $table)
			{
				if(is_array($table["COLUMNS"]) && count($table["COLUMNS"])>0)
				{
					foreach($table["COLUMNS"] as $column)
					{
						if($table["DEL_TABLE_NAME"])
						{
							$sqldel="INSERT INTO $table[DEL_TABLE_NAME] SELECT * FROM $table[TABLE_NAME] WHERE $column='$profileid'";
							//echo "\n".$sqldel;
							$resdel=$mysqlObj->executeQuery($sqldel,$myDb);
							if($resdel)
							{
								$sqldel="DELETE FROM $table[TABLE_NAME] WHERE $column='$profileid'";
								//echo "\n".$sqldel;
								$mysqlObj->executeQuery($sqldel,$myDb);
							}
						}
						else
						{
							$sqldel="DELETE FROM $table[TABLE_NAME] WHERE $column='$profileid'";
							//echo "\n".$sqldel;
                                                        $mysqlObj->executeQuery($sqldel,$myDb);		
						}
					}		
				}
			}
			unset($myDb);
			unset($myDbName);
		}
		$db_211=connect_211();
		mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_211);
		unset($suffix);
		$suffix=getsuffix($profileid);
		//echo "\n".$suffix;
		if($suffix)
		{
			$sqldel="DELETE FROM VIEW_LOG_TRIGGER_$suffix WHERE VIEWED='$profileid'";
			//echo "\n".$sqldel;
			mysql_query($sqldel,$db_211) or logError($sqldel);
		}
		foreach($TABLES_211 as $table)
		{
			if(is_array($table["COLUMNS"]) && count($table["COLUMNS"]>0))
                        {
                                foreach($table["COLUMNS"] as $column)
                                {
                                        if($table["DEL_TABLE_NAME"])
					{
						$sqldel="INSERT INTO $table[DEL_TABLE_NAME] SELECT * FROM $table[TABLE_NAME] WHERE $column='$profileid'";
						//echo "\n".$sqldel;
						$resdel=mysql_query($sqldel,$db_211) or logError($sqldel);
						if($resdel)
						{
							$sqldel="DELETE FROM $table[TABLE_NAME] WHERE $column='$profileid'";
							//echo "\n".$sqldel;
							mysql_query($sqldel,$db_211) or logError($sqldel);
						}
					}
					else
					{
						$sqldel="DELETE FROM $table[TABLE_NAME] WHERE $column='$profileid'";
						//echo "\n".$sqldel;
						mysql_query($sqldel,$db_211) or logError($sqldel);
					}

				}
			}
		}
		mysql_ping($db);
		$sqlupdate="UPDATE newjs.DELETED_PROFILE_LOG SET DATA_CHECK='Y' WHERE PROFILEID='$profileid'";
		//echo "\n".$sqlupdate;
		mysql_query($sqlupdate,$db) or logError($sqlupdate);
	}
}
mysql_free_result($res);
?>
