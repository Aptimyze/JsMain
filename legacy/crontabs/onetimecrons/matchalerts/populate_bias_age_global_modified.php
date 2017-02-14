<?php
include_once(JsConstants::$alertDocRoot."/classes/Mysql.class.php");

$mysqlObj=new Mysql;
$db=$mysqlObj->connect("master");

$sql = "SELECT * FROM matchalerts.bias_age_global";
$result = $mysqlObj->executeQuery($sql,$db);
while($row=$mysqlObj->fetchArray($result))
{
	if(trim($row["sender_age_bucket"])=="G45")
	{
		$ageMin = 45;
		$ageMax = 70;
	}
	elseif(trim($row["sender_age_bucket"])=="L20")
	{
		$ageMin = 18;
		$ageMax = 19;
	}
	else
	{
		$ageArr = explode("-",trim($row["sender_age_bucket"]));
		$ageMin = $ageArr[0];
		$ageMax = $ageArr[1]-1;
		unset($ageArr);
	}

	if(trim($row["age_diff"])=='SE0t2')
	{
		$diffMin = 1;
		$diffMax = 1;
	}
	elseif(trim($row["age_diff"])=='SE2t4')
	{
		$diffMin = 2;
		$diffMax = 3;
	}
	elseif(trim($row["age_diff"])=='SE4t7')
	{
		$diffMin = 4;
		$diffMax = 6;
	}
	elseif(trim($row["age_diff"])=='SE7t10')
	{
		$diffMin = 7;
		$diffMax = 9;
	}
	elseif(trim($row["age_diff"])=='SEe10')
	{
		$diffMin = 10;
		$diffMax = 15;
	}
	elseif(trim($row["age_diff"])=='equal')
	{
		$diffMin = 0;
		$diffMax = 0;
	}
	elseif(trim($row["age_diff"])=='SY0t2')
	{
		$diffMin = -2;
		$diffMax = -1;
	}
	elseif(trim($row["age_diff"])=='SY2t4')
	{
		$diffMin = -4;
		$diffMax = -3;
	}
	elseif(trim($row["age_diff"])=='SY4t7')
	{
		$diffMin = -7;
		$diffMax = -5;
	}
	elseif(trim($row["age_diff"])=='SY7t10')
	{
		$diffMin = -10;
		$diffMax = -8;
	}
	elseif(trim($row["age_diff"])=='SY10')
	{
		$diffMin = -15;
		$diffMax = -11;
	}
	else
	{
		echo "HERE";
	}

	$insQuery = "INSERT INTO matchalerts.bias_age_global_modified VALUES (".$ageMin.",".$ageMax.",'".$row["sender_gender"]."','".$row["sender_ref_mstatus"]."',".$diffMin.",".$diffMax.",'".$row["receiver_gender"]."','".$row["receiver_ref_mstatus"]."',".$row["bias_age"].",".$row["max_bias_age"].",".$row["matching_score_age"].")";
	$mysqlObj->executeQuery($insQuery,$db);
}
echo "DONE";
mysql_close($db);
?>
