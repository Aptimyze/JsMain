<?php
/*********************************************************************************************
* FILE NAME     : connectmb.inc
* DESCRIPTION   : Has common functions required for Marriage Bureau
* CREATION DATE : 27 March, 2006
* CREATED BY    : Nikhil Tandon 
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
$TOUT1 = 1800;
$TOUT2 = 7200;

if(!$active_db)
	$active_db = "";
if(!$previous_db)
	$previous_db = "";
if(!$db_211)
        $db_211="";
include_once($_SERVER['DOCUMENT_ROOT']."/profile/mysql_multiple_connections.php");

if($fromprofilepage!=1)
{
	include($_SERVER['DOCUMENT_ROOT']."/smarty/Smarty.class.php");
	$smarty=new Smarty;
	$smarty->relative_dir="marriage_bureau/";
	
	$ip=$_SERVER["HTTP_HOST"];
	$SITE_URL="http://".$ip;
	$smarty->assign("SITE_URL",$SITE_URL);
}
function connect_dbmb()
{
	$db=@db_set_active("master","10.208.67.37","user","CLDLRTa9") or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","","ShowErrTemplate");
	mysql_select_db_js("marriage_bureau",$db);
	return $db;
}


if(!function_exists('connect_211'))
{
	function connect_211()
	{
		$db2 = db_set_active("211","10.208.66.117","user","CLDLRTa9") or die("Can't connect to Database".mysql_error());
		mysql_select_db_js("newjs",$db2);               // connection string
		return $db2;
	}
}


function assign_template_pathmb()
{
	global $smarty;
	$smarty->relative_dir="marriage_bureau/";
}
function assign_template_pathprofile()
{
        global $smarty;
        $smarty->relative_dir="jeevansathi/";
}
?>
