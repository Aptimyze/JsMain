<?php
/* This cron is created to execute PopulateTablesForNewMatchesMailer.php and SendNewMatches.php crons internally 
*for new matches email
*Author : Reshu
*Created :Sep 9 2014
*/
//for preventing timeout to maximum possible
ini_set('max_execution_time',0);
ini_set('memory_limit',-1);
ini_set('mysql.connect_timeout',-1);
ini_set('default_socket_timeout',259200); // 3 days
ini_set('log_errors_max_len',0);
if(!$php5)
        $php5=JsConstants::$php5path;
if(!$logerrorFilePath)
        $logerrorFilePath=JsConstants::$alertDocRoot.'/newMatches/logerror.txt';
chdir(dirname(__FILE__));
passthru("$php5 -q PopulateTablesForNewMatchesMailer.php >> $logerrorFilePath ");

$totalScript=3;
$start=0;
while($start<$totalScript)
{
	passthru("$php5 -q SendNewMatches.php ".$totalScript." ".$start." >> $logerrorFilePath &");
	$start++;
}

/* we can skip this logic.
while(1)
{
	$outputShell = passthru("ps aux | grep 'SendNewMatches.php' | grep -v grep");
	if(!$outputShell)
	{
		if(date('H')>9 && date('H')<18)
			$totalScript=9;
		else
			$totalScript=6;
		$start=0;
		while($start<$totalScript)
		{
			passthru("$php5 -q SendNewMatches.php ".$totalScript." ".$start." >> $logerrorFilePath &");
			$start++;
		}
	}
}
*/
?>
