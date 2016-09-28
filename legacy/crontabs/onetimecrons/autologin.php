<?php
include(JsConstants::$docRoot."/profile/connect.inc");
echo JsConstants::$docRoot."/profile/connect.inc";
$db_slave = connect_slave();
$db_master = connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000', $db_slave);
$sql = "SELECT id,url FROM newjs.shortURL WHERE  `url` LIKE  '%responseTracking%' order by id desc";
$res = mysql_query($sql, $db_slave) or die(mysql_error());
$count       = mysql_num_rows($res);

$chunk       = 2000;
$totalChunks = ceil($count / $chunk);
for ($j = 0; $j < $totalChunks; $j++) {
	$urlArray = array();
	$ProfileArray = array();
	$skip     = $j * $chunk;
	mysql_data_seek($res, $skip);
	while (($row = mysql_fetch_assoc($res)) && $trans < $chunk) {
		$urlArray[$row["id"]] = $row;
		$urlArray[$row["id"]]["update"] = "N";
		$url = $row["url"];
		$arr = parse_url($url);
		parse_str($arr["query"], $output);
		$epid=$protect_obj->js_decrypt($output["echecksum"],"Y");
		$epid_arr=explode("i",$epid);
		$profileid=$epid_arr[1];
		if($profileid)
		{
			$profileArr[$profileid]["USERNAME"] = $output["username"];
			$profileArr[$profileid]["id"] = $row["id"];
			$profileidArr[]=$profileid;
		}
		$trans++;
	}
	$profileids = implode(",",$profileidArr);
	$sql1 = "SELECT USERNAME,PROFILEID FROM newjs.JPROFILE WHERE PROFILEID IN ($profileids)";
	$res1 = mysql_query($sql1, $db_slave) or die(mysql_error());
	$count=1;
	while ($row1 = mysql_fetch_assoc($res1))
	{
		
		if($profileArr[$row1["PROFILEID"]]["USERNAME"] == $row1["USERNAME"])
		{	
			$updateString .= $profileArr[$row1["PROFILEID"]]["id"].",";
			$count++;
		}
	}
	$str = substr($updateString, 0, -1);
	$sqlDelete = "DELETE FROM newjs.shortURL WHERE id in ($str)";
	$res = mysql_query($sql, $db_master) or die(mysql_error());
}
	
