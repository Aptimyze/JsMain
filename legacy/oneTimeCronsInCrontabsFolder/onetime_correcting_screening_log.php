<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

                                                                                                 
include_once("connect.inc");
connect_db();
$val=8388607;
$sql="SELECT  ID , REF_ID , PROFILEID FROM jsadmin.SCREENING_LOG WHERE ENTRY_TYPE='P' AND REF_ID=$val";
$res=mysql_query($sql) or die(mysql_error().$sql);
while($row=mysql_fetch_array($res))
{
	$id=$row['ID'];

	$refid=$row['REF_ID'];
	$pid=$row['PROFILEID'];

	$sql_1="SELECT ID FROM jsadmin.SCREENING_LOG WHERE ID>$id and REF_ID=$refid AND PROFILEID=$pid AND ENTRY_TYPE='M' ORDER BY ID ASC LIMIT 1";
	$res_1=mysql_query($sql_1) or die(mysql_error().$sql_1);
	$row_1=mysql_fetch_array($res_1);
	$id_new=$row_1["ID"];

	if($id_new)
	{
	        $sql1="UPDATE jsadmin.SCREENING_LOG SET REF_ID=$id WHERE ID=$id_new";
		mysql_query($sql1) or die(mysql_error().$sql1);

		$sql1="UPDATE jsadmin.SCREENING_LOG SET REF_ID=$id WHERE ID=$id";
		mysql_query($sql1) or die(mysql_error().$sql1);
	}
	
}
?>
