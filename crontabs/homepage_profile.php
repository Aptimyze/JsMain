<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include("connect.inc"); //calls for connection with database 
//opening slave
$db_slave=connect_slave();
$mtongue=array(33,6,20,31,3,17);
//Records for Groom
for($i=0;$i<6;$i++)
{
		
	$sql="SELECT S.PROFILEID AS PROFILEID   from SEARCH_MALE AS S  where S.MTONGUE =$mtongue[$i] AND (S.PRIVACY='A' OR S.PRIVACY='') AND S.HAVEPHOTO='Y' AND S.PHOTO_DISPLAY='A' AND S.AGE<=30 and S.PROFILEID NOT IN (SELECT H.PROFILEID FROM TEMP_HOMEPAGE_PROFILES AS H) ORDER BY (S.NTIMES/(DATEDIFF(now(),`ENTRY_DT`))) DESC LIMIT 1";
	$result=mysql_query($sql) or die(mysql_error());
	$row=mysql_fetch_array($result);
	if($row['PROFILEID'])
		$profileid_arr["MALE"][]=$row['PROFILEID'];
}
//RECORDS FOR BRIDE
for($i=0;$i<6;$i++)
{
        $sql="SELECT S.PROFILEID AS PROFILEID   from SEARCH_FEMALE AS S  where S.MTONGUE =$mtongue[$i] AND (S.PRIVACY='A' OR S.PRIVACY='') AND S.HAVEPHOTO='Y' AND S.PHOTO_DISPLAY='A' AND S.AGE<=30 and S.PROFILEID NOT IN (SELECT H.PROFILEID FROM TEMP_HOMEPAGE_PROFILES AS H) ORDER BY (S.NTIMES/(DATEDIFF(now(),`ENTRY_DT`))) DESC LIMIT 1";
        $result=mysql_query($sql) or die(mysql_error());
        $row=mysql_fetch_array($result);
	if($row['PROFILEID'])
        	$profileid_arr["FEMALE"][]=$row['PROFILEID'];
}
//closing slave
mysql_close($db_slave);
//opening master
connect_db();
//DELETE PREVIOUS RECORDS FROM HOMEPAGE_PROFILES
$sql_del="DELETE FROM HOMEPAGE_PROFILES";
mysql_query($sql_del)  or die(mysql_error());
//INSERT RECORDS INTO HOMEPAGE_PROFILES
for($i=0;$i<count($profileid_arr["MALE"]);$i++)
{
	$profileid=$profileid_arr['MALE'][$i];
	$sqlinsert="INSERT INTO HOMEPAGE_PROFILES VALUES($profileid,'M','Y')";
        mysql_query($sqlinsert) or die(mysql_error());
}
for($i=0;$i<count($profileid_arr["FEMALE"]);$i++)
{
	$profileid=$profileid_arr['FEMALE'][$i];
        $sqlinsert="INSERT INTO HOMEPAGE_PROFILES VALUES($profileid,'F','Y')";
        mysql_query($sqlinsert)  or die(mysql_error());
}


//SHIFT RECORDS FROM HOMEPAGE_PROFILES INTO TEMP_HOMEPAGE_PROFILES
$sql_shift="INSERT INTO TEMP_HOMEPAGE_PROFILES SELECT * FROM HOMEPAGE_PROFILES";
mysql_query($sql_shift)  or die(mysql_error());
?>
