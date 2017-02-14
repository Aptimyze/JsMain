<?
include(JsConstants::$docRoot."/profile/connect.inc");
ini_set('max_execution_time','0');
ini_set('memory_limit',-1);

$dbM = connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

$sql_1="ALTER TABLE  newjs.SEARCHQUERY ADD  (MATCHALERTS_DATE_CLUSTER VARCHAR( 20 ) NOT NULL, KUNDLI_DATE_CLUSTER VARCHAR( 20 ) NOT NULL)";
$sql_2="INSERT INTO  newjs.SEARCH_CLUSTERS (  DISPLAY_LABEL ,  SOLR_LABEL ,  VALUE ,  ACTIVE )  VALUES ('Date Sent',  'MATCHALERTS_DATE_CLUSTER',  '30',  'Y')";
$sql_3="ALTER TABLE  MIS.SEARCHQUERY ADD  (MATCHALERTS_DATE_CLUSTER VARCHAR( 20 ) NOT NULL, KUNDLI_DATE_CLUSTER VARCHAR( 20 ) NOT NULL)";
$sql_4="ALTER TABLE  MIS.SEARCHQUERY_TEMP ADD  (MATCHALERTS_DATE_CLUSTER VARCHAR( 20 ) NOT NULL,KUNDLI_DATE_CLUSTER VARCHAR( 20 ) NOT NULL)";

updateQuery($sql_3);
updateQuery($sql_1);
updateQuery($sql_2);
updateQuery($sql_4);

function updateQuery($sql)
{
	global $dbM;
	$result=mysql_query($sql,$dbM) or mysql_error1(mysql_error($dbM).$sql);
}
function mysql_error1($msg)
{ 
        //echo $msg;die;
        mail("lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com","SEARCHQUERY_4293_UPDATES",$msg);
        //exit;
}
