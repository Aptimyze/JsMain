<?php

function getConnectionBms()
{
	$dbbms = mysql_connect(MysqlDbConstants::$bms[HOST],MysqlDbConstants::$bms[USER],MysqlDbConstants::$bms[PASS]) or logErrorBms("BMS Site is down for maintenance. Please try after some time.","","ShowErrTemplate");
	return $dbbms;
}
global $dbbms;
$dbbms=getConnectionBms();
mysql_select_db("bms2",$dbbms);
        
/* take connection to server where database manager is kept*/

/*take connection to mmmm*/
function getConnectionMMM()
{
        global $dbbms;
        return $dbmmm;
}

/* take connection to search*/

function getConnectionSearch()
{
        global $dbbms;
        return $dbbms;

}
/* take connection to resman*/
function getConnectionResman()
{
	global $dbbms;
	return $dbbms;
                                                                                                                             
}
function getConnectionCategories()
{
	global $dbbms;
	return $dbbms;
}
?>
