<?php
/*
truncate similar profiles logs twice a months
*/
chdir(dirname(__FILE__));
include_once("connect.inc");
$db = connect_ddl();

$sql="TRUNCATE TABLE newjs.SIM_PROFILE_LOG_TEMP";
$res=mysql_query($sql,$db) or die(mysql_error($db,$sql));

$sql="TRUNCATE TABLE newjs.SIM_PROFILE_LOG";
$res=mysql_query($sql,$db) or die(mysql_error1($db,$sql));

function mysql_error1($db)
{
        $msg = mysql_error($db);
        $msql.=$sql;
        mail("lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com","SIM_PROFILE_LOG housekeeping",$msg);
}
?>
