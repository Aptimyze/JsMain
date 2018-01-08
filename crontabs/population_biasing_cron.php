<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include_once($docRoot."/crontabs/config.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/arrays.php");

$db_ddl = connect_db();

$mysqlObj = new Mysql;
$dbM = $mysqlObj->connect("master") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

$category = array("CASTE","CITY_RES","OCCUPATION","EDU_LEVEL_NEW","MTONGUE","AGE","HEIGHT","INCOME","MANGLIK","MSTATUS");
$update_category = array("CASTE","CITY","OCCUPATION","EDUCATION","MTONGUE","AGE","HEIGHT","INCOME","MANGLIK","MSTATUS");
$gender = array("MALE","FEMALE");

$min_threshold = 0.001;

foreach($CASTE_DROP as $k=>$v)
{
	$original_arr_male["CASTE"][$k] = 0;
	$original_arr_female["CASTE"][$k] = 0;
}
foreach($CITY_DROP as $k=>$v)
{
	$original_arr_male["CITY_RES"][$k] = 0;
	$original_arr_female["CITY_RES"][$k] = 0;
}
foreach($OCCUPATION_DROP as $k=>$v)
{
	$original_arr_male["OCCUPATION"][$k] = 0;
	$original_arr_female["OCCUPATION"][$k] = 0;
}
foreach($EDUCATION_LEVEL_NEW_DROP as $k=>$v)
{
	$original_arr_male["EDU_LEVEL_NEW"][$k] = 0;
	$original_arr_female["EDU_LEVEL_NEW"][$k] = 0;
}
foreach($MTONGUE_DROP as $k=>$v)
{
	$original_arr_male["MTONGUE"][$k] = 0;
	$original_arr_female["MTONGUE"][$k] = 0;
}
foreach($HEIGHT_DROP as $k=>$v)
{
	$original_arr_male["HEIGHT"][$k] = 0;
	$original_arr_female["HEIGHT"][$k] = 0;
}
foreach($INCOME_DROP as $k=>$v)
{
	$original_arr_male["INCOME"][$k] = 0;
	$original_arr_female["INCOME"][$k] = 0;
}
foreach($MANGLIK_LABEL as $k=>$v)
{
	$original_arr_male["MANGLIK"][$k] = 0;
	$original_arr_female["MANGLIK"][$k] = 0;
}
foreach($MSTATUS_DROP as $k=>$v)
{
	$original_arr_male["MSTATUS"][$k] = 0;
	$original_arr_female["MSTATUS"][$k] = 0;
}
for($i=18;$i<71;$i++)
{
	$original_arr_female["AGE"][$i] = 0;
	if($i>20)
		$original_arr_male["AGE"][$i] = 0;
}

foreach($gender as $kk=>$vv)
{
	$select_statement = "SELECT ".implode(",",$category)." FROM newjs.SEARCH_".$vv;
	$result = $mysqlObj->executeQuery($select_statement,$dbM) or $mysqlObj->logError($select_statement);
	$sum[$vv] = 0;
	while($row = $mysqlObj->fetchArray($result))
	{
		foreach($category as $k=>$v)
		{
			if($vv=="MALE")
			{
				if(array_key_exists($row[$v],$original_arr_male[$v]))
					$original_arr_male[$v][$row[$v]] = $original_arr_male[$v][$row[$v]] + 1;
				else
					$original_arr_male[$v][$row[$v]] = 1;
			}
			elseif($vv=="FEMALE")
			{
				if(array_key_exists($row[$v],$original_arr_female[$v]))
					$original_arr_female[$v][$row[$v]] = $original_arr_female[$v][$row[$v]] + 1;
				else
					$original_arr_female[$v][$row[$v]] = 1;
			}
		}
		$sum[$vv]++;
	}
}

foreach($category as $k=>$v)
{
	foreach($original_arr_male[$v] as $kk=>$vv)
	{
		if($vv)
		{
			$tempVal = ($vv/$sum["MALE"])*100;
			if($tempVal>$min_threshold)
				$original_arr_male[$v][$kk] = round($tempVal,3);
			else
				$original_arr_male[$v][$kk] = $min_threshold;
		}
		else
		{
			$original_arr_male[$v][$kk] = $min_threshold;
		}
	}

	foreach($original_arr_female[$v] as $kk=>$vv)
	{
		if($vv)
		{
			$tempVal = ($vv/$sum["FEMALE"])*100;
			if($tempVal>$min_threshold)
				$original_arr_female[$v][$kk] = round($tempVal,3);
			else
				$original_arr_female[$v][$kk] = $min_threshold;
		}
		else
		{
			$original_arr_female[$v][$kk] = $min_threshold;
		}
	}
}

foreach($gender as $k=>$v)
{
	foreach($category as $kk=>$vv)
	{
		$insert_statement = "REPLACE INTO twowaymatch.".$update_category[$kk]."_".$v."_PERCENT (".$update_category[$kk].",PERCENT) VALUES ";
		if($v=="MALE")
		{
			foreach($original_arr_male[$vv] as $kkk=>$vvv)
			{
				$insert_statement = $insert_statement."('".$kkk."','".$vvv."'),";
			}
		}
		elseif($v=="FEMALE")
		{
			foreach($original_arr_female[$vv] as $kkk=>$vvv)
			{
				$insert_statement = $insert_statement."('".$kkk."','".$vvv."'),";
			}
		}
		$insert_statement = rtrim($insert_statement,",");
	
		$truncate_statement = "TRUNCATE TABLE twowaymatch.".$update_category[$kk]."_".$v."_PERCENT";

		$mysqlObj->executeQuery($truncate_statement,$db_ddl) or $mysqlObj->logError($truncate_statement);
		$mysqlObj->executeQuery($insert_statement,$dbM) or $mysqlObj->logError($insert_statement);
	}
}

mysql_close($dbM);
?>
