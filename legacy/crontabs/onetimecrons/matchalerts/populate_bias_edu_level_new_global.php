<?php
include_once(JsConstants::$alertDocRoot."/classes/Mysql.class.php");

$mysqlObj=new Mysql;
$db=$mysqlObj->connect("master");

$sql = "SELECT * FROM matchalerts.bias_edu_level_global";
$result = $mysqlObj->executeQuery($sql,$db);
while($row=$mysqlObj->fetchArray($result))
{
	$sql1 = "SELECT VALUE FROM newjs.EDUCATION_LEVEL_NEW WHERE OLD_VALUE = ".$row["sender_edu_level"];
	$result1 = $mysqlObj->executeQuery($sql1,$db);
	while($row1=$mysqlObj->fetchArray($result1))
	{
		$sql2 = "SELECT VALUE FROM newjs.EDUCATION_LEVEL_NEW WHERE OLD_VALUE = ".$row["receiver_edu_level"];
		$result2 = $mysqlObj->executeQuery($sql2,$db);
		while($row2=$mysqlObj->fetchArray($result2))
        	{
			$insQuery = "INSERT INTO matchalerts.bias_edu_level_new_global VALUES (".$row1["VALUE"].",'".$row["sender_gender"]."',".$row2["VALUE"].",'".$row["receiver_gender"]."',".$row["bias_edu_level"].",".$row["max_bias_edu_level"].",".$row["matching_score_edu_level"].")";
			$mysqlObj->executeQuery($insQuery,$db);
		}
	}
}
echo "DONE";
mysql_close($db);
?>
