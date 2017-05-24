<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

ini_set('memory_limit','256M');

include("connect.inc");
$path=$_SERVER['DOCUMENT_ROOT'];
include($path."/classes/globalVariables.Class.php");
include($path."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");


$ts=time();
$ts-=24*60*60;
$today=date("Y-m-d",$ts);
//$today="2008-06-15";
list($year1,$month1,$day1)=explode('-',$today);

$date1=$year1."-".$month1."-".$day1." 00:00:00";
$date2=$year1."-".$month1."-".$day1." 23:59:59";

//Sharding on CONTACTS done by Neha
global $noOfActiveServers,$slave_activeServers;

$mysqlObj=new Mysql;
for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
        $sql="SELECT PROFILEID FROM newjs.JPARTNER where (CHILDREN IS NOT NULL AND CHILDREN!='') AND (PARTNER_MSTATUS='N' OR PARTNER_MSTATUS='' OR PARTNER_MSTATUS='\'N\'')";
        $myDbSlaveName=getActiveServerName($activeServerId,"slave");
        $myDbSlavearr[$myDbSlaveName]=$mysqlObj->connect("$myDbSlaveName");
        mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$myDbSlavearr[$myDbSlaveName]);
        $result=$mysqlObj->executeQuery($sql,$myDbSlavearr[$myDbSlaveName]);
        $profileidStr = $mysqlObj->fetchArray($result)['PROFILEID'];
        
        $c=0;
        $myDbName=getActiveServerName($activeServerId);
        $myDbarr[$myDbName]=$mysqlObj->connect("$myDbName");
        mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$myDbarr[$myDbName]);
	while($myrow=$mysqlObj->fetchArray($result)){                
            $profileidStr.=','.$myrow['PROFILEID'];
            if($c==5000){
                $sql2 = "UPDATE newjs.JPARTNER SET CHILDREN = '' WHERE PROFILEID IN ($profileidStr)";
                $mysqlObj->executeQuery($sql2,$myDbarr[$myDbName]) or die(mysql_error($myDbarr[$myDbName]));
                $c=0;
                $profileidStr=$mysqlObj->fetchArray($result)['PROFILEID'];
            }
            $c++;
        }
        if($profileidStr != ''){
            $sql2 = "UPDATE newjs.JPARTNER SET CHILDREN = '' WHERE PROFILEID IN ($profileidStr)";
            $mysqlObj->executeQuery($sql2,$myDbarr[$myDbName]) or die(mysql_error($myDbarr[$myDbName]));
        }
}
?>
