<?php
include("connect.inc");
$db= connect_db();

/*
$sql="UPDATE JPROFILE SET HEIGHT=HEIGHT+5";
$res=mysql_query($sql) or die("1".mysql_error());

$sql="UPDATE SEARCH_MALE SET  HEIGHT=HEIGHT+5";
$res=mysql_query($sql) or die("2".mysql_error());

$sql="UPDATE SEARCH_FEMALE SET  HEIGHT=HEIGHT+5";
$res=mysql_query($sql) or die("3".mysql_error());
*/

$sql="UPDATE SEARCH_AGENT SET  LHEIGHT=LHEIGHT+5, HHEIGHT=HHEIGHT+5 WHERE LHEIGHT>0 AND HHEIGHT>0";
$res=mysql_query($sql) or die("4".mysql_error());

$sql="UPDATE JPROFILE_AFFILIATE SET HEIGHT=HEIGHT+5";
$res=mysql_query($sql) or die("5".mysql_error());

$sql="UPDATE JPARTNER_PAGE3 SET LHEIGHT=LHEIGHT+5, HHEIGHT=HHEIGHT+5";
$res=mysql_query($sql) or die("6".mysql_error());

/*
global $activeServers,$noOfActiveServers;
$mysqlObj=new Mysql;
for($i=0;$i<$noOfActiveServers;$i++)
{
        $sql="UPDATE JPARTNER SET LHEIGHT=LHEIGHT+5, HHEIGHT=HHEIGHT+5";
        $myDbName=$activeServers[$i];
        $myDb=$mysqlObj->connect("$myDbName");
        $result=$mysqlObj->executeQuery($sql,$myDb);
}
*/
?>
