<?
ini_set("max_execution_time","0");
chdir(dirname(__FILE__));
include("../../config.php");
include($_SERVER['DOCUMENT_ROOT']."/profile/config.php"); 
include(JsConstants::$docRoot."/commonFiles/flag.php");
include($_SERVER['DOCUMENT_ROOT']."/profile/connect_db.php");

$smarty->template_dir=JsConstants::$docRoot."/smarty/templates/mailer";
$db=connect_mmm() or die(mysql_error());

mysql_query("set session wait_timeout=50000,interactive_timeout=50000,net_read_timeout=50000",$db);
                                                                                                 
$mailer_id="S1";

include "populateyn.php";
populateyn($mailer_id);
include "maileryn.php";
maileryn($mailer_id);	
include "mailyn.php";
mailyn($mailer_id);
?>
