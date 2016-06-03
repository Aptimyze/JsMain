<?php
include("connect.inc");

$db=connect_db();

if($filterName && $filterValue && $profileID && $operator)
{
	$sql="UPDATE Assisted_Product.AP_TEMP_DPP SET $filterName='$filterValue' WHERE PROFILEID='$profileID' AND CREATED_BY='$operator'";
	mysql_query_decide($sql);
}
?>
