<?php

/************************************************************************************************************************
*    FILENAME           : bms_connect_billing.php
*    DESCRIPTION        : Configuration file for JS billing & bms integration.
			  Required for displaying results on basis of transaction/ref id.
*    CREATED BY         : lavesh
***********************************************************************************************************************/

$TOUT1 = 3600;	
$TOUT = 3600;   

global $_SVNENABLE , $_SVN;
global $dbbms;
global $_HOST_NAME , $_USER , $_PASSWORD , $_SITEURL;

$_SVNENABLE="false";
//for live site
$_HOST_NAME = MysqlDbConstants::$bms[HOST].":".MysqlDbConstants::$bms[PORT];
$_SITEURL = JsConstants::$bmsUrl;
$_USER = MysqlDbConstants::$bms[USER];
$_PASSWORD = MysqlDbConstants::$bms[PASS];
//ends here


$dbbms = getConnectionBms();

function getConnectionBms()
{
        global $_HOST_NAME , $_USER , $_PASSWORD , $_SITEURL , $dbbms;
        if(!$dbbms = @mysql_connect($_HOST_NAME,$_USER,$_PASSWORD))
        {
                logErrorBms("BMS Site is down for maintenance. Please try after some time.","","ShowErrTemplate");
        }
        @mysql_select_db_js("bms2",$dbbms);
        return $dbbms;
}

function authenticatedBms($checksum,$ip,$priv)
{
	global	$dbbms, $TOUT1,$smarty,$_SVN,$_SVNENABLE;
	list($md, $userno)=explode("~",$checksum);

	if(md5($userno)!=$md)
	{
	    return NULL;
	}

	$sql_chk = "select USERID,USERNAME, TIME_IN,PRIVILEGE , SITE from bms2.CONNECT where ID='$userno' and PRIVILEGE='$priv'";
	$res_chk = mysql_query_decide($sql_chk,$dbbms) or die(mysql_error_js());
	$count=mysql_num_rows($res_chk);

	if ($count > 0)	
	{
		$myrow = mysql_fetch_array($res_chk);

		if (time()-$myrow["TIME_IN"] < $TOUT1)
		{
			$tm = time();
			$sql_up = "update bms2.CONNECT set TIME_IN='$tm' where ID='$userno'";
			$res_up = mysql_query_decide($sql_up,$dbbms) or die(mysql_error_js());
			$id=$md."~".$userno;
			$ret["ID"] = $id;
			$ret["USER"] = $myrow["USERNAME"];
			$ret["USERID"] = $myrow["USERID"];
			$ret["PRIVILEGE"] = $myrow["PRIVILEGE"];
			$ret["SITE"]=$myrow["SITE"];

			if ($_SVNENABLE == "true")
				$_SVN="/".$myrow["USERNAME"];
		}
		else
			$ret= NULL;
		return $ret;
	}
	else
	{
		return NULL;
	}
}

?>
