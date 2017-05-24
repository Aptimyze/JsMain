<?php
include "../jsadmin/connect.inc";

@mysql_ping_js($db);

//select profile ids which expire.
$sql = "SELECT PROFILEID, EXPIRY_DT FROM billing.SERVICE_STATUS WHERE PROFILEID
                IN
                (
                        SELECT DISTINCT(PROFILEID) FROM billing.SERVICE_STATUS WHERE EXPIRY_DT BETWEEN '2007-09-03' AND '2007-09-13'
                )
                AND PROFILEID NOT IN
                (
                        SELECT DISTINCT(PROFILEID) FROM billing.SERVICE_STATUS WHERE EXPIRY_DT > '2007-09-13'
                )";
if($res=mysql_query_decide($sql))
{
	while($row=mysql_fetch_array($res))
	{
		$profile_arr[]=$row['PROFILEID'];
	}
}
echo count($profile_arr);
echo "\n";
echo count(array_unique($profile_arr));
?>
