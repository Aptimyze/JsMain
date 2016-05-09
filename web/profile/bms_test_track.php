<?php

include("connect.inc");
connect_737_lan();
mysql_select_db("test");
if($flag==1)
{
	$sql_testing="UPDATE test.PROFILEPAGE_TEST SET PAGE_LOADS=PAGE_LOADS+1";
	mysql_query_decide($sql_testing);
}
if($flag==2)
{
	$sql_testing="UPDATE test.PROFILEPAGE_TEST SET PAGE_ENDS=PAGE_ENDS+1";
	mysql_query_decide($sql_testing);
}

?>
