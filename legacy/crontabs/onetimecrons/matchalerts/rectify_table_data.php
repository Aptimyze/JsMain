<?php
include_once(JsConstants::$alertDocRoot."/classes/Mysql.class.php");

$mysqlObj=new Mysql;
$db=$mysqlObj->connect("master");

$genderArr = array("M","F");
$mstatusArr = array("M","N");
$mainTable = array("OCCUPATION","INCOME","HEIGHT","EDUCATION_LEVEL_NEW","BTYPE","MTONGUE","CASTE","CITY","CITY");
$updateTable = array("bias_occupation_global","bias_income_global","bias_height_global","bias_edu_level_new_global","bias_btype_global","bias_community_global","bias_caste_global","bias_cczone_global_sender_female","bias_cczone_global_sender_male");
rectify($mainTable,$updateTable);
unset($mainTable);
unset($updateTable);

$mainTable = array("AGE","AGE");
$updateTable = array("bias_age_global_sender_female_new","bias_age_global_sender_male_new");
rectify1($mainTable,$updateTable);
unset($mainTable);
unset($updateTable);

echo "DONE\n";
mysql_close($db);

function rectify1($mainTable,$updateTable)
{
	global $db,$mysqlObj,$mstatusArr;

	foreach($mainTable as $k=>$v)
        {
		if($v=="AGE")
		{
			$sql = "SELECT DISTINCT sender_age,receiver_age FROM matchalerts.".$updateTable[$k];
			$result = $mysqlObj->executeQuery($sql,$db);
                        while($row=$mysqlObj->fetchArray($result))
                        {
				foreach($mstatusArr as $kk=>$vv)
				{
					foreach($mstatusArr as $kkk=>$vvv)
					{
						$insQuery = "INSERT IGNORE INTO matchalerts.".$updateTable[$k]." VALUES ('".$row["sender_age"]."','".$vv."','".$row["receiver_age"]."','".$vvv."',0,1,0)";
                                               	$mysqlObj->executeQuery($insQuery,$db);
					}
				}
			}
		}
		echo $updateTable[$k]."\n";
	}
}

function rectify($mainTable,$updateTable)
{
	global $db,$mysqlObj,$genderArr;

	foreach($mainTable as $k=>$v)
	{
		if($v=="BTYPE")
		{
			$value = array("","1","2","3","4");
		}
		elseif($v=="CITY")
		{
			$value = array("E","W","N","S","F");
		}
		else
		{
			$sql = "SELECT VALUE FROM newjs.".$v;
			$result = $mysqlObj->executeQuery($sql,$db);
			while($row=$mysqlObj->fetchArray($result))
				$value[] = $row["VALUE"];
		}

		foreach($value as $kk=>$vv)
		{
			foreach($value as $kkk=>$vvv)
			{
				if($updateTable[$k]=="bias_community_global" || $updateTable[$k]=="bias_caste_global")
				{
					$insQuery = "INSERT IGNORE INTO matchalerts.".$updateTable[$k]." VALUES ('".$vv."','".$vvv."','','','','','',0,1,0)";
					$mysqlObj->executeQuery($insQuery,$db);
				}
				elseif($updateTable[$k]=="bias_cczone_global_sender_female" || $updateTable[$k]=="bias_cczone_global_sender_male")
				{
					foreach($value as $kkkk=>$vvvv)
                                        {
                                                foreach($value as $kkkkk=>$vvvvv)
                                                {
							$insQuery = "INSERT IGNORE INTO matchalerts.".$updateTable[$k]." VALUES ('".$vv."','".$vvvv."','".$vvv."','".$vvvvv."',0,1,0)";
							$mysqlObj->executeQuery($insQuery,$db);
						}
					}
				}
				else
				{
					foreach($genderArr as $kkkk=>$vvvv)
					{
						foreach($genderArr as $kkkkk=>$vvvvv)
						{
							if($updateTable[$k]=="bias_edu_level_new_global")
							{
								$insQuery = "INSERT IGNORE INTO matchalerts.".$updateTable[$k]." VALUES ('".$vv."','".$vvvv."','".$vvv."','".$vvvvv."',0,1,0)";
							}
							else
							{
								$insQuery = "INSERT IGNORE INTO matchalerts.".$updateTable[$k]." VALUES ('".$vv."','".$vvvv."','".$vvv."','".$vvvvv."','','','','','',0,1,0)";
							}
							$mysqlObj->executeQuery($insQuery,$db);
						}
					}
				}
			}
		}
		unset($value);
		echo $updateTable[$k]."\n";
	}
}
?>
