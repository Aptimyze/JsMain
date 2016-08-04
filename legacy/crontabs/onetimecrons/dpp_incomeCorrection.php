<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
chdir(dirname(__FILE__));

include_once(JsConstants::$docRoot."/profile/connect.inc");
include_once(JsConstants::$docRoot."/classes/Mysql.class.php");
ini_set('max_execution_time','0');
ini_set('memory_limit',-1);

const NO_OF_PROFILES = 5000;
const DEBUG_INFO	= 0;

function dpp_IncomeCorrection()
{
	global	$noOfActiveServers;
	$mysqlObj=new Mysql;

	for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
	{
		$dbNameS=getActiveServerName($activeServerId,"slave");
		$dbNameMaster=getActiveServerName($activeServerId,"master");
		$dbS=$mysqlObj->connect($dbNameS);
		mysql_select_db("newjs",$dbS);

		$sql='SELECT PROFILEID FROM newjs.JPARTNER WHERE `LINCOME` = "" AND `HINCOME` = "" AND `LINCOME_DOL` = "" AND `HINCOME_DOL` = "" AND `PARTNER_INCOME` <> ""';
		if(DEBUG_INFO)
			echo $dbS." : ".$sql."\n\n";
		$results = mysql_query($sql,$dbS) or die(mysql_error($dbS).$sql);

		$iCounter   = NO_OF_PROFILES;
		$arrInStr 	= array();
		
		while(($row = mysql_fetch_assoc($results)) || count($arrInStr))
		{
			if($row['PROFILEID'])
			{
				$arrInStr[]=$row['PROFILEID'];
				--$iCounter;
			}
			else
			{
				$iCounter = 0;
			}
				
			if($iCounter === 0 && count($arrInStr))
			{
				$iCounter = NO_OF_PROFILES;
				$szInStr ="( " . implode(",",$arrInStr) . " )";
				if(!strlen($szInStr))
				{
					continue;
				}
				$dbM=$mysqlObj->connect($dbNameMaster);
				$sql_update = " UPDATE newjs.JPARTNER SET `PARTNER_INCOME`='' WHERE `LINCOME` = '' AND `HINCOME` = '' AND `LINCOME_DOL` = '' AND `HINCOME_DOL` = '' AND `PROFILEID` IN  $szInStr ";
				
				mysql_query($sql_update,$dbM) or die(mysql_error($dbM).$sql_update);

				if(DEBUG_INFO)
					echo "Update : $sql_update \n";

				//Free Memory
				unset($arrInStr);
				$szInStr= "";
				
				$arrInStr = array();
			}//End Of If
		}//End Of While
		mysql_close($dbM);
		mysql_close($dbS);
	}//End Of For
}

function EndScript($st_Time='')
{
        $end_time = microtime(TRUE);
        $var = memory_get_usage(true);

         if ($var < 1024)
                $mem =  $var." bytes";
         elseif ($var < 1048576)
                $mem =  round($var/1024,2)." kilobytes";
         else
                $mem = round($var/1048576,2)." megabytes";


        echo $mem ."\n";
        echo $end_time - $st_Time;
        die;

}

$st_Time = microtime(TRUE);
dpp_IncomeCorrection();
EndScript($st_Time);

?>
