<?php
include_once(JsConstants::$alertDocRoot."/classes/Mysql.class.php");

$mysqlObj=new Mysql;
$db=$mysqlObj->connect("master");

$sql = "SELECT * FROM matchalerts.bias_age_global_sender_female";
$result = $mysqlObj->executeQuery($sql,$db);
while($row=$mysqlObj->fetchArray($result))
{
	$ageMin = trim($row["sender_age_min"]);
	$ageMax = trim($row["sender_age_max"]);
	
	for($i=$ageMin;$i<=$ageMax;$i++)
	{
		$receiverMin = $i-trim($row["age_diff_max"]);
		$receiverMax = $i-trim($row["age_diff_min"]);

		for($j=$receiverMin;$j<=$receiverMax;$j++)
		{
			$insQuery = "INSERT INTO matchalerts.bias_age_global_sender_female_new VALUES (".$i.",'".$row["sender_ref_mstatus"]."',".$j.",'".$row["receiver_ref_mstatus"]."',".$row["bias_age"].",".$row["max_bias_age"].",".$row["matching_score_age"].")";
			$mysqlObj->executeQuery($insQuery,$db);
		}
	}
}

$sql = "SELECT * FROM matchalerts.bias_age_global_sender_male";
$result = $mysqlObj->executeQuery($sql,$db);
while($row=$mysqlObj->fetchArray($result))
{
        $ageMin = trim($row["sender_age_min"]);
        $ageMax = trim($row["sender_age_max"]);

        for($i=$ageMin;$i<=$ageMax;$i++)
        {
                $receiverMin = $i-trim($row["age_diff_max"]);
                $receiverMax = $i-trim($row["age_diff_min"]);

                for($j=$receiverMin;$j<=$receiverMax;$j++)
                {
                        $insQuery = "INSERT INTO matchalerts.bias_age_global_sender_male_new VALUES (".$i.",'".$row["sender_ref_mstatus"]."',".$j.",'".$row["receiver_ref_mstatus"]."',".$row["bias_age"].",".$row["max_bias_age"].",".$row["matching_score_age"].")";
                        $mysqlObj->executeQuery($insQuery,$db);
                }
        }
}

echo "DONE";
mysql_close($db);
?>
