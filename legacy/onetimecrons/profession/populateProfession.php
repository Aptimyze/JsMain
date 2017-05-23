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

$filename = "label_change.csv";
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

foreach($old_value as $k=>$v)
{
	$statement = "SELECT VALUE FROM newjs.OCCUPATION WHERE LABEL = '".$v."'";
	$result = $mysqlObjM->executeQuery($statement,$dbM) or die($statement);
	$row = $mysqlObjM->fetchArray($result);
	$statement = "UPDATE newjs.OCCUPATION SET LABEL = '".$new_value[$k]."' WHERE VALUE = ".$row["VALUE"];
	$mysqlObjM->executeQuery($statement,$dbM) or die($statement);
}

unset($data);
unset($old_value);
unset($new_value);

$statement = "SELECT MAX(VALUE) AS VALUE FROM newjs.OCCUPATION";
$result = $mysqlObjM->executeQuery($statement,$dbM) or die($statement);
$row = $mysqlObjM->fetchArray($result);
$value = $row["VALUE"]+1;

$filename = "new_profession.csv";
$file = fopen($filename, "r") or exit("Unable to open file!");
        while(!feof($file))
        {
       		$data[] = trim(fgets($file));
        }
fclose($file);

foreach ($data as $k=>$v)
{
	if(trim($v))
	{
		$statement = "INSERT INTO newjs.OCCUPATION(ID,LABEL,VALUE) VALUES ('','".$v."',".$value.")";
		$mysqlObjM->executeQuery($statement,$dbM) or die($statement);
		$value++;	
	}
}
unset($data);

$statement = "UPDATE newjs.OCCUPATION SET SORTBY = CASE VALUE WHEN 44 THEN 1 WHEN 36 THEN 2 END WHERE VALUE IN (44,36)";
$mysqlObjM->executeQuery($statement,$dbM) or die($statement);

$i=3;
$statement = "SELECT VALUE FROM newjs.OCCUPATION WHERE VALUE NOT IN(43,44,36) ORDER BY LABEL";
$result = $mysqlObjM->executeQuery($statement,$dbM) or die($statement);
$update_statement = "UPDATE newjs.OCCUPATION SET SORTBY = CASE VALUE ";
$update_val = "";
while($row = $mysqlObjM->fetchArray($result))
{
	$update_statement = $update_statement."WHEN ".$row["VALUE"]." THEN ".$i." ";
	$update_val = $update_val.$row["VALUE"].",";
	$i++;
}
$update_val = $update_val."43";
$update_statement = $update_statement."WHEN 43 THEN ".$i." END WHERE VALUE IN (".$update_val.")";
$mysqlObjM->executeQuery($update_statement,$dbM) or die($update_statement);
echo "DONE";

//CLOSE DATABASE CONNECTION
mysql_close($dbM);
//CLOSING ENDS
?>
