<?php
/*include("connect.inc"); //calls for connection with database 
connect_db();
$mtongue=array(33,6,20,31,3,17);
//DELETE PREVIOUS RECORDS FROM HOMEPAGE_PROFILES
$sql_del="DELETE FROM HOMEPAGE_PROFILES";
mysql_query_decide($sql_del);
//Records for Groom
for($i=0;$i<6;$i++)
{
	$sql="SELECT S.PROFILEID AS PROFILEID    FROM JPROFILE  AS J, SEARCH_MALE AS S  WHERE  J.PROFILEID =S.PROFILEID AND S.MTONGUE =$mtongue[$i] AND (J.PRIVACY='A' OR J.PRIVACY='') AND J.HAVEPHOTO='Y' AND J.PHOTO_DISPLAY='A' AND S.PROFILEID NOT IN (SELECT H.PROFILEID FROM TEMP_HOMEPAGE_PROFILES AS H) ORDER BY J.NTIMES DESC LIMIT 1";
	$result=mysql_query_decide($sql);
	$row=mysql_fetch_array($result);
	$profileid=$row['PROFILEID'];
	$sqlinsert="INSERT INTO HOMEPAGE_PROFILES VALUES($profileid,'M','Y')";
	mysql_query_decide($sqlinsert);
}
//RECORDS FOR BRIDE
for($i=0;$i<6;$i++)
{
        $sql="SELECT S.PROFILEID AS PROFILEID   FROM  SEARCH_FEMALE  AS S ,JPROFILE AS J  WHERE  J.PROFILEID=S.PROFILEID  AND S.MTONGUE =$mtongue[$i]  AND (J.PRIVACY='A' OR J.PRIVACY='') AND J.HAVEPHOTO='Y' AND J.PHOTO_DISPLAY='A' AND S.PROFILEID NOT IN (SELECT H.PROFILEID FROM TEMP_HOMEPAGE_PROFILES AS H) ORDER BY J.NTIMES DESC LIMIT 1";
        $result=mysql_query_decide($sql);
        $row=mysql_fetch_array($result);
        $profileid=$row['PROFILEID'];
        $sqlinsert="INSERT INTO HOMEPAGE_PROFILES VALUES($profileid,'F','Y')";
        mysql_query_decide($sqlinsert);
}

//SHIFT RECORDS FROM HOMEPAGE_PROFILES INTO TEMP_HOMEPAGE_PROFILES
$sql_shift="INSERT INTO TEMP_HOMEPAGE_PROFILES SELECT * FROM HOMEPAGE_PROFILES";
mysql_query_decide($sql_shift);*/
?>
