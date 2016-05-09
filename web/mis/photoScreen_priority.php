<?php
include_once("connect.inc");
$db=connect_misdb();

include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

$sql="SELECT DISTINCT A.PROFILEID,DATE(A.UPDATED_TIMESTAMP) AS DAY,B.USERNAME FROM newjs.PICTURE_FOR_SCREEN_NEW A LEFT JOIN newjs.JPROFILE B ON A.PROFILEID=B.PROFILEID ORDER BY `UPDATED_TIMESTAMP` ASC LIMIT 20";
$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
while($row=mysql_fetch_assoc($result))
{
	 echo $row["USERNAME"]."<br>";
}

?>
