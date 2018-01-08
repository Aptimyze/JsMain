<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

//INCLUDE FILES HERE
include_once($_SERVER['DOCUMENT_ROOT']."/profile/config.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
//INCLUDE FILE ENDS

//MAKE CONNECTION TO MASTER AND SLAVE
$mysqlObjM = new Mysql;
$dbM = $mysqlObjM->connect("master") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

$statement = "TRUNCATE TABLE newjs.CITY_ANAND";
$mysqlObjM->executeQuery($statement,$dbM) or die($statement);

$statement = "INSERT INTO newjs.CITY_ANAND(ID,LABEL,VALUE,STD_CODE,COUNTRY_VALUE) SELECT '',LABEL,VALUE,STD_CODE,COUNTRY_VALUE FROM newjs.CITY_NEW";$mysqlObjM->executeQuery($statement,$dbM) or die($statement);

$statement = "UPDATE newjs.CITY_ANAND SET LABEL = CASE VALUE WHEN 'MH08' THEN 'Pune/ Chinchwad' WHEN 'AP32' THEN 'Vizianagaram' END WHERE VALUE IN ('MH08','AP32')";
$mysqlObjM->executeQuery($statement,$dbM) or die($statement);

$filename = "city_prefix_update.csv";
$file = fopen($filename, "r") or exit("Unable to open file!");
$x=1;
        while(!feof($file))
        {
                if($x!=1)
                {
                        $data[] = trim(fgets($file));
                }
                else
                {
                        fgets($file);
                        $x++;
                }
        }
fclose($file);

foreach ($data as $k=>$v)
{
        $values = explode("|",trim($v));
        if (trim($values[0]) && trim($values[1]))
        {
                $old_value[] = trim($values[0]);
                $new_value[] = trim($values[1]);
        }
}

$statement = "UPDATE newjs.CITY_ANAND SET VALUE = CASE VALUE ";
$valueStr = "";
foreach($old_value as $k=>$v)
{
	$statement = $statement."WHEN '".$v."' THEN '".$new_value[$k]."' ";
	$valueStr=$valueStr."'".$v."',";
}

$valueStr = rtrim($valueStr,",");
$statement = $statement."END WHERE VALUE IN (".$valueStr.")";
$mysqlObjM->executeQuery($statement,$dbM) or die($statement);

unset($data);
unset($old_value);
unset($new_value);

$filename = "delete.csv";
$file = fopen($filename, "r") or exit("Unable to open file!");
        while(!feof($file))
        {
             	$data[] = trim(fgets($file));
        }
fclose($file);

$deleteStr = implode("','",$data);
$deleteStr = "'".$deleteStr."'";
$statement = "DELETE FROM newjs.CITY_ANAND WHERE VALUE IN (".$deleteStr.")";
$mysqlObjM->executeQuery($statement,$dbM) or die($statement);
unset($data);

$statement = "UPDATE newjs.CITY_ANAND SET LABEL = CASE VALUE WHEN 'GU04' THEN 'Baroda/Vadodara' WHEN 'GU06' THEN 'Junagarh' WHEN 'WB02' THEN 'Barddhaman/Burdwan' END WHERE VALUE IN ('GU04','GU06','WB02')";
$mysqlObjM->executeQuery($statement,$dbM) or die($statement);

$statement = "SELECT VALUE,TOP_SORTBY FROM newjs.CITY_INDIA WHERE TOP='Y'";
$result = $mysqlObjM->executeQuery($statement,$dbM) or die($statement);
while($row = $mysqlObjM->fetchArray($result))
{
	if($row["VALUE"]=="KE03")
		$row["VALUE"]="KE13";
	$sql = "UPDATE newjs.CITY_ANAND SET TOP = 'Y',TOP_SORTBY = ".$row["TOP_SORTBY"]." WHERE VALUE = '".$row["VALUE"]."'";
	$mysqlObjM->executeQuery($sql,$dbM) or die($sql);
}

$filename = "add_new_cities.csv";
$file = fopen($filename, "r") or exit("Unable to open file!");
$x=1;
        while(!feof($file))
        {
                if($x!=1)
                {
                        $data[] = trim(fgets($file));
                }
                else
                {
                        fgets($file);
                        $x++;
                }
        }
fclose($file);

foreach ($data as $k=>$v)
{
        $values = explode("|",trim($v));
        if (trim($values[0]) && trim($values[1]) && trim($values[2]))
        {
                $city_value[] = trim($values[0]);
                $state_value[] = trim($values[1]);
		$std_value[] = trim($values[2]);
        }
}

foreach($state_value as $k=>$v)
{
	if($v=="Orissa")
		$v = "Odisha";
	elseif($v=="Uttaranchal")
		$v = "Uttarakhand";

	$sql = "SELECT VALUE FROM newjs.STATE_NEW WHERE LABEL = '".$v."'";
	$result = $mysqlObjM->executeQuery($sql,$dbM) or die($sql);
	$row = $mysqlObjM->fetchArray($result);
	$state_value[$k] = $row["VALUE"];
}

foreach($city_value as $k=>$v)
{
	$val = getCityValue($state_value[$k],$mysqlObjM,$dbM);
	$statement = "INSERT INTO newjs.CITY_ANAND(ID,LABEL,VALUE,STD_CODE,COUNTRY_VALUE) VALUES ('','".$v."','".$val."','".$std_value[$k]."',51)";
	$mysqlObjM->executeQuery($statement,$dbM) or die($statement);
}

unset($data);
unset($city_value);
unset($state_value);
unset($std_value);

$statement = "UPDATE newjs.CITY_ANAND SET TYPE = 'CITY' WHERE COUNTRY_VALUE = 51";
$mysqlObjM->executeQuery($statement,$dbM) or die($statement);

$statement = "INSERT INTO newjs.CITY_ANAND(ID,LABEL,VALUE,TYPE,STD_CODE,COUNTRY_VALUE) SELECT '',LABEL,VALUE,'STATE',0,51 FROM newjs.STATE_NEW";
$mysqlObjM->executeQuery($statement,$dbM) or die($statement);

$statement = "SELECT VALUE FROM newjs.CITY_ANAND WHERE TYPE!='STATE' ORDER BY LABEL";
$result = $mysqlObjM->executeQuery($statement,$dbM) or die($statement);

$updateStr = "UPDATE newjs.CITY_ANAND SET SORTBY = CASE VALUE ";
$valueStr = "";
$x = 1;
while($row = $mysqlObjM->fetchArray($result))
{
	if($row["VALUE"]=='0')
		continue;
        $updateStr = $updateStr."WHEN '".$row["VALUE"]."' THEN ".$x." ";
        $valueStr = $valueStr."'".$row["VALUE"]."',";
        $x++;
}

$updateStr = $updateStr."WHEN '0' THEN ".$x." ";
$valueStr = $valueStr."'0'";
$updateStr = $updateStr."END WHERE VALUE IN (".$valueStr.")";
$mysqlObjM->executeQuery($updateStr,$dbM) or die($updateStr);

$statement = "SELECT VALUE FROM newjs.CITY_ANAND WHERE TYPE='STATE' ORDER BY LABEL";
$result = $mysqlObjM->executeQuery($statement,$dbM) or die($statement);

$updateStr = "UPDATE newjs.CITY_ANAND SET SORTBY = CASE VALUE ";
$valueStr = "";
$x++;
while($row = $mysqlObjM->fetchArray($result))
{
        $updateStr = $updateStr."WHEN '".$row["VALUE"]."' THEN ".$x." ";
        $valueStr = $valueStr."'".$row["VALUE"]."',";
        $x++;
}

$valueStr=rtrim($valueStr,",");
$updateStr = $updateStr."END WHERE VALUE IN (".$valueStr.")";
$mysqlObjM->executeQuery($updateStr,$dbM) or die($updateStr);

echo "DONE";

//CLOSE DATABASE CONNECTION
mysql_close($dbM);
//CLOSING ENDS

function getCityValue($val,$mysqlObjM,$dbM)
{
	$sql = "SELECT MAX(VALUE) AS VALUE FROM newjs.CITY_ANAND WHERE VALUE LIKE '%".$val."%'";
	$result = $mysqlObjM->executeQuery($sql,$dbM) or die($sql);
	$row = $mysqlObjM->fetchArray($result);
	if($row["VALUE"])
	{
		$currentVal = substr($row["VALUE"],2);
		$currentVal = (int)$currentVal;
		$currentVal = $currentVal+1;
		if(strlen($currentVal)==1)
			$currentVal = "0".$currentVal;
		$output = $val.$currentVal;
	}
	else
	{
		$output = $val."01";
	}
	return $output;
}
?>
