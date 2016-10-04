<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
include("connect.inc");
$fileName =  $_SERVER["SCRIPT_FILENAME"];
$http_msg=print_r($_SERVER,true);
mail("reshu.rajput@gmail.com,lavesh.rawat@gmail.com","For DLL Movement - $fileName",$http_msg);
$db=connect_ddl();

/*
$sql="SELECT MIN(ID) AS MID FROM newjs.SEARCHQUERY";
$res=mysql_query($sql) or die(mysql_error1($db));
$row=mysql_fetch_assoc($res);
$sid=$row['MID'];

$sql1="DELETE FROM newjs.SPHINX_SEARCHQUERY_CACHE WHERE SID<$sid";
$res1=mysql_query($sql1) or die(mysql_error1($db)));

$sql2="DELETE FROM newjs.SPHINX_SEARCHRESULTS_CACHE WHERE SID<$sid";
$res2=mysql_query($sql2) or die(mysql_error1($db));

$sql="SELECT MAX(ID) AS MID FROM newjs.MYJS_PARTNERPROFILE_CACHE";
$res=mysql_query($sql) or die(mysql_error1($db));
$row=mysql_fetch_assoc($res);
$sid=$row['MID']-500;

$sql3="DELETE FROM newjs.MYJS_PARTNERPROFILE_CACHE WHERE ID<$sid";
$res3=mysql_query($sql3) or die(mysql_error1($db));
*/

$sql="TRUNCATE TABLE newjs.SPHINX_SEARCHQUERY_CACHE";
$res=mysql_query($sql) or die(mysql_error1($db));

$sql="TRUNCATE TABLE newjs.SPHINX_SEARCHRESULTS_CACHE";
$res=mysql_query($sql) or die(mysql_error1($db));

$sql="TRUNCATE TABLE newjs.MYJS_PARTNERPROFILE_CACHE";
$res=mysql_query($sql) or die(mysql_error1($db));

$sql="TRUNCATE TABLE newjs.FEATURED_PROFILE_CACHE";
$res=mysql_query($sql) or die(mysql_error1($db));

function mysql_error1($db)
{
        mail("lavesh.rawat@jeevansathi.com,neha.verma@jeevansathi.com","error in clear_cache_table.php",mysql_error($db));
}


?>
