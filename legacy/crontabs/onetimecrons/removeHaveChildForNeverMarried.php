<?php
/* This is a one time cron that will fetch profiles with M_STATUS= "N" and make their 
 * corresponding HAVECHILD = ''. This function is performed both on JPROFILE and 
 * JPARTNER
 * @author Sanyam Chopra
 * @created 9th March 2016
 */
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");
$path = $_SERVER['DOCUMENT_ROOT'];
include_once(JsConstants::$docRoot.'/../crontabs/connect.inc');
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once($path."/classes/Mysql.class.php");
include_once($path."/classes/shardingRelated.php");

/*code to select and reset HAVECHILD in JPROFILE*/
$db_master = connect_db();
$db_slave = connect_slave();
$mysqlObj=new Mysql;

//select PROFILEID from JPROFILE where MSTATUS='N' and HAVECHILD!=''
$sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE MSTATUS='N' AND HAVECHILD!=''";
      
$res=mysql_query($sql,$db_slave) or die(mysql_error($db_slave));

//update HAVECHILD to '' for selected PROFILEID's.
while($row = mysql_fetch_array($res))
{
	$profileId = $row["PROFILEID"];	
	$sql2 = "UPDATE newjs.JPROFILE SET HAVECHILD='' WHERE PROFILEID=".$profileId;
	mysql_query($sql2,$db_master) or die(mysql_error($db_master));
}
echo("\n Updation done on JPROFILE. Now carrying out the process for JPARTNER \n \n");
/*code to select and reset HAVECHILD in JPARTNER*/

//Take the connection on all shards(slaves)
for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{	
	$myDbName=getActiveServerName($activeServerId,"slave");
	$myDbarr[$myDbName]=$mysqlObj->connect("$myDbName","slave");
	mysql_query("set session wait_timeout=10000",$myDbarr[$myDbName]);
}

//Take the connection on all shards(masters)
for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
        $myDbName_master=getActiveServerName($activeServerId);
        $myDbarr_master[$myDbName_master]=$mysqlObj->connect("$myDbName_master");
	mysql_query("set session wait_timeout=10000",$myDbarr_master[$myDbName_master]);
}

//This will hit the select query on each shard and accordinly update the incorrect dat found by setting CHILDREN=''
if(count($myDbarr))
{
	$server=0;
        foreach($myDbarr_master as $key=>$val)
        {
			$myDb_master=$myDbarr_master[$key];
			$myDb_slave=$myDbarr[$key];
                $sql_select="SELECT PROFILEID FROM newjs.JPARTNER WHERE PARTNER_MSTATUS=\"'N'\" AND CHILDREN!=''";
                $res_select=mysql_query($sql_select,$myDb_slave) or die(mysql_error($myDb_slave));
                while($row_select=mysql_fetch_array($res_select))
                {	
                        $pid=$row_select[0];
                        $sql_update="UPDATE newjs.JPARTNER SET CHILDREN='' WHERE PROFILEID=".$pid;
                       	mysql_query($sql_update,$myDb_master) or die(mysql_error($myDb_master));
                }
		$server++;
	}
}