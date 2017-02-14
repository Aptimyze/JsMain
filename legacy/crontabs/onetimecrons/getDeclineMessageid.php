<?php

include_once(JsConstants::$docRoot."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/profile/connect.inc");


$mysqlObj = new Mysql;
global $activeServers, $noOfActiveServers, $slave_activeServers;
for ($activeServerId = 0; $activeServerId < $noOfActiveServers; $activeServerId++) {
	 echo $myDbName           = getActiveServerName($activeServerId, "slave");
	 $myDbarr[$myDbName] = $mysqlObj->connect("$myDbName");
	mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000', $myDbarr[$myDbName]) or die(mysql_error());
}
foreach ($myDbarr as $k => $conn) {
$sql = "SELECT M.SENDER, M.RECEIVER, M.ID,M1.MESSAGE as MESSAGE  FROM  newjs.`MESSAGE_LOG` M JOIN newjs.MESSAGES M1 ON (M.ID = M1.ID) WHERE TYPE =  'D' AND DATE BETWEEN  '2014-05-26 00:00:00' AND  '2014-05-27 23:59:59'";
$result = mysql_query($sql,$conn) or die(mysql_error());
while($row = $mysqlObj->fetchArray($result))
{
	$message = $row["MESSAGE"];
	if(strpos($message,"have accepted your Interest"))
		$changeid[] = $row["ID"];
}
$changeidstr= implode(",",$changeid);

}
unset($myDbName);
unset($myDbarr);
for ($activeServerId = 0; $activeServerId < $noOfActiveServers; $activeServerId++) {
	 $myDbName           = getActiveServerName($activeServerId, "master");
	 $myDbarr[$myDbName] = $mysqlObj->connect("$myDbName");
	mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000', $myDbarr[$myDbName]) or die(mysql_error());
}
foreach ($myDbarr as $k => $conn) {
$sql = "UPDATE MESSAGES SET MESSAGE = 'I am sorry, I do not think I am the right match for you. Wish you luck in your search for a Jeevansathi.' WHERE ID IN ($changeidstr) ";
$res = mysql_query($sql,$conn);
}
echo $changeidstr;

?>
