<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


include("../connect.inc");
$db=connect_db();

$sql="TRUNCATE TABLE newjs.HOMEPAGE_PROFILES_TEMP";
mysql_query($sql,$db) or die("$sql".mysql_error());//logError($sql,$db);

$sql="REPLACE INTO newjs.HOMEPAGE_PROFILES_TEMP (SELECT newjs.SEARCH_MALE.PROFILEID,'M','Y' FROM incentive.MAIN_ADMIN_POOL, newjs.SEARCH_MALE WHERE LAST_LOGIN_DT > DATE_SUB(CURDATE(), INTERVAL 10 DAY) AND HAVEPHOTO = 'Y' AND incentive.MAIN_ADMIN_POOL.PROFILEID = newjs.SEARCH_MALE.PROFILEID AND SCORE >= 400 AND newjs.SEARCH_MALE.AGE BETWEEN '25' AND '30' AND newjs.SEARCH_MALE.MTONGUE IN ('10','12','15','28') ) UNION (SELECT newjs.SEARCH_MALE.PROFILEID,'M','Y' FROM incentive.MAIN_ADMIN_POOL, newjs.SEARCH_MALE WHERE LAST_LOGIN_DT > DATE_SUB(CURDATE(), INTERVAL 10 DAY) AND HAVEPHOTO = 'Y' AND incentive.MAIN_ADMIN_POOL.PROFILEID = newjs.SEARCH_MALE.PROFILEID AND SCORE >= 400 AND newjs.SEARCH_MALE.AGE BETWEEN '25' AND '30' AND newjs.SEARCH_MALE.COUNTRY_RES<>'51' )";
mysql_query($sql,$db) or die("$sql".mysql_error());//logError($sql,$db);

$sql="REPLACE INTO newjs.HOMEPAGE_PROFILES_TEMP (SELECT newjs.SEARCH_FEMALE.PROFILEID,'F','Y' FROM incentive.MAIN_ADMIN_POOL, newjs.SEARCH_FEMALE WHERE LAST_LOGIN_DT > DATE_SUB(CURDATE(), INTERVAL 10 DAY) AND HAVEPHOTO = 'Y' AND incentive.MAIN_ADMIN_POOL.PROFILEID = newjs.SEARCH_FEMALE.PROFILEID AND SCORE >= 400 AND newjs.SEARCH_FEMALE.AGE BETWEEN '24' AND '28' AND newjs.SEARCH_FEMALE.MTONGUE IN ('10','12','15','28')) UNION (SELECT newjs.SEARCH_FEMALE.PROFILEID,'F','Y' FROM incentive.MAIN_ADMIN_POOL, newjs.SEARCH_FEMALE WHERE LAST_LOGIN_DT > DATE_SUB(CURDATE(), INTERVAL 10 DAY) AND HAVEPHOTO = 'Y' AND incentive.MAIN_ADMIN_POOL.PROFILEID = newjs.SEARCH_FEMALE.PROFILEID AND SCORE >= 400 AND newjs.SEARCH_FEMALE.AGE BETWEEN '24' AND '28' AND newjs.SEARCH_FEMALE.COUNTRY_RES<>'51')";
mysql_query($sql,$db) or die("$sql".mysql_error());//logError($sql,$db);

$sql="SELECT COUNT(*) AS cnt, PROFILEID FROM HOMEPAGE_PROFILES_TEMP h, CONTACTS c WHERE h.PROFILEID=c.RECEIVER AND h.GENDER='M' GROUP BY PROFILEID HAVING cnt>15";
$res=mysql_query($sql,$db) or die("$sql".mysql_error());//logError($sql,$db);
while($row=mysql_fetch_array($res))
{
        $pid=$row['PROFILEID'];
        $sql="DELETE FROM newjs.HOMEPAGE_PROFILES_TEMP WHERE PROFILEID = '$pid'";
        mysql_query($sql,$db) or die("$sql".mysql_error());//logError($sql,$db);
}

$sql="SELECT COUNT(*) AS cnt, PROFILEID FROM HOMEPAGE_PROFILES_TEMP h, CONTACTS c WHERE h.PROFILEID=c.RECEIVER AND h.GENDER='F' GROUP BY PROFILEID HAVING cnt>30";
$res=mysql_query($sql,$db) or die("$sql".mysql_error());//logError($sql,$db);
while($row=mysql_fetch_array($res))
{
        $pid=$row['PROFILEID'];
        $sql="DELETE FROM newjs.HOMEPAGE_PROFILES_TEMP WHERE PROFILEID = '$pid'";
        mysql_query($sql,$db) or die("$sql".mysql_error());//logError($sql,$db);
}

$sql="SELECT PROFILEID FROM newjs.HOMEPAGE_PROFILES_TEMP";
$res=mysql_query($sql,$db) or die("$sql".mysql_error());//logError($sql,$db);
while($row=mysql_fetch_array($res))
{
        $pid=$row['PROFILEID'];
        $sql="SELECT COUNT(*) AS cnt FROM newjs.JPROFILE WHERE PROFILEID = '$pid' AND PHOTO_DISPLAY='A' AND HAVEPHOTO='Y' AND PRIVACY NOT IN ('R','F','C')";
        $res1=mysql_query($sql,$db) or die("$sql".mysql_error());//logError($sql,$db);
        $row1=mysql_fetch_array($res1);
        if($row1['cnt']==0)
        {
                $sql="DELETE FROM newjs.HOMEPAGE_PROFILES_TEMP WHERE PROFILEID = '$pid'";
                mysql_query($sql,$db) or die("$sql".mysql_error());//logError($sql,$db);
        }
}

?>
