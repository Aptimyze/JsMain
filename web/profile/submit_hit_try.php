<?

include("connect.inc");
$db=connect_db();
$dt=date("Y-m-d");

if($type)
{
	if($type>1)
		$sql="update MIS.REG_AJAX_REQ set SUBMIT_ERR=SUBMIT_ERR+1 where `DATE`='$dt'";
	else
		$sql="update MIS.REG_AJAX_REQ set SUBMIT_SUC=SUBMIT_SUC+1 where `DATE`='$dt'";

		mysql_query($sql,$db);
}

if($field)
{
	$sql="INSERT INTO MIS.REG_FIELDS_ERROR (ERROR_STRING,DATE) VALUES ('$field',now())";
	mysql_query($sql,$db);
}

if($second)
{
	if($second==1)
		$sql="INSERT INTO MIS.REG_AJAX_SECOND (USERNAME,COUNT,DATE,SUBMIT_ERR) VALUES ('$username','$count',now(),1)";
	elseif($second==2)
 		$sql="INSERT INTO MIS.REG_AJAX_SECOND (USERNAME,COUNT,DATE,SUBMIT_SUC) VALUES ('$username','$count',now(),1)";
	mysql_query($sql);

	
}



?>
