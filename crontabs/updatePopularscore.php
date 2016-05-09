<?php 
include_once(JsConstants::$docRoot."/profile/connect.inc");
$sql="SELECT S.PROFILEID,(J.NTIMES/(POW((DATEDIFF(NOW(),ENTRY_DT)),.75))) AS POPULAR  FROM SEARCH_MALE S, JP_NTIMES J WHERE S.PROFILEID = J.PROFILEID";
$db=connect_slave();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);

$res=mysql_query($sql,$db) or die("3 ".mysql_error1($db));

$dbM=connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);
while($row=mysql_fetch_row($res))
{
        $profileid=$row[0];
	$popular=$row[1];

	if($popular)
	{	
	$sqlU="UPDATE SEARCH_MALE SET POPULAR='$popular' WHERE PROFILEID=$profileid";
	mysql_query($sqlU,$dbM);
	}
}


$sql="SELECT S.PROFILEID,(J.NTIMES/(POW((DATEDIFF(NOW(),ENTRY_DT)),.75))) AS POPULAR  FROM SEARCH_FEMALE S, JP_NTIMES J WHERE S.PROFILEID = J.PROFILEID";
$res=mysql_query($sql,$db) or die("3 ".mysql_error1($db));
while($row=mysql_fetch_row($res))
{
        $profileid=$row[0];
        $popular=$row[1];

	if($popular)
	{
        $sqlU="UPDATE SEARCH_FEMALE SET POPULAR='$popular' WHERE PROFILEID=$profileid";
	mysql_query($sqlU,$dbM);
	}
}

function mysql_error1($db)
{
	echo mysql_error($db);
	echo "here";
	exit(1);
}
/*
$today=date("Y-m-d");
mail('lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com','popular score updated',$today);
*/
?>
