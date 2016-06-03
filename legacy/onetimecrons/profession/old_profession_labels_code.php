<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

//INCLUDE FILES HERE
include_once($_SERVER['DOCUMENT_ROOT']."/profile/config.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
//INCLUDE FILE ENDS

//MAKE CONNECTION TO MASTER AND SLAVE
$mysqlObjM = new Mysql;
$dbM1 = $mysqlObjM->connect("master") or logError("Unable to connect to master","ShowErrTemplate");
$dbM = mysql_connect("172.16.3.157","ankit","ankit");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

$filename = "old_labels.csv";
$file = fopen($filename, "r") or exit("Unable to open file!");
        while(!feof($file))
        {
		$x = trim(fgets($file));
		if($x)
                	$data[] = $x;
        }
fclose($file);

$statement = "SELECT LABEL,VALUE FROM newjs.OCCUPATION WHERE LABEL IN ('".implode("','",$data)."') ORDER BY FIELD (LABEL,'".implode("','",$data)."')";
$result = $mysqlObjM->executeQuery($statement,$dbM) or die($statement);
$occArr = array();
while($row = $mysqlObjM->fetchArray($result))
{
        $occArr[$row["VALUE"]] = $row["LABEL"];
}

unset($data);

$filename = "split_labels.csv";
$file = fopen($filename, "r") or exit("Unable to open file!");
        while(!feof($file))
        {
                $x = trim(fgets($file));
                if($x)
                        $data[] = $x;
        }
fclose($file);

foreach($data as $k=>$v)
{
	$tempdata = explode("|",$v);
	$old_label[$k]="'".$tempdata[1]."'";
	$new_label[$k] = $tempdata[0];
	unset($tempdata);
	$tempdata = explode(", ",$new_label[$k]);
	$new_label[$k] = "'".implode("','",$tempdata)."'";
	unset($tempdata);
}

foreach($old_label as $k=>$v)
{
	$statement = "SELECT VALUE FROM newjs.OCCUPATION_AFTER_MILESTONE8 WHERE LABEL = ".$v;
	$result = $mysqlObjM->executeQuery($statement,$dbM1) or die($statement);
	$row = $mysqlObjM->fetchArray($result);
	$old_label_val[$k] = $row["VALUE"];
}

foreach($new_label as $k=>$v)
{
	$statement = "SELECT VALUE FROM newjs.OCCUPATION_AFTER_MILESTONE8 WHERE LABEL IN (".$v.") ORDER BY FIELD (LABEL,".$v.")";
	$result = $mysqlObjM->executeQuery($statement,$dbM1) or die($statement);
	$new_label_val[$k]="";
	while($row = $mysqlObjM->fetchArray($result))
	{
		if($row["VALUE"]!=$old_label_val[$k])
			$new_label_val[$k] = $new_label_val[$k].$row["VALUE"].",";
	}
	$new_label_val[$k] = rtrim($new_label_val[$k],",");
}

$statement = "SELECT LABEL,VALUE FROM newjs.OCCUPATION_AFTER_MILESTONE8 WHERE VALUE>44 ORDER BY SORTBY";
$result = $mysqlObjM->executeQuery($statement,$dbM1) or die($statement);
while($row = $mysqlObjM->fetchArray($result))
{
	$other_dropdown[$row["VALUE"]]=$row["LABEL"];
}

$statement = "SELECT LABEL,VALUE FROM newjs.OCCUPATION_AFTER_MILESTONE8 WHERE VALUE<=43 AND VALUE NOT IN(36) ORDER BY SORTBY";
$result = $mysqlObjM->executeQuery($statement,$dbM1) or die($statement);
while($row = $mysqlObjM->fetchArray($result))
{
	$other_dropdown[$row["VALUE"]]=$row["LABEL"];
}

$file = fopen("old_profession_labels_dropdown_onetime.php", "w") or exit("Unable to open file!");
fwrite($file,"<?php\r\n");
foreach ($occArr as $k=>$v)
{
        fwrite($file,"\$OLD_OCCUPATION_ARR[".$k."] = \"".trim($v)."\";\r\n");
}
foreach($old_label_val as $k=>$v)
{
        fwrite($file,"\$OCC_SPLIT_ARR[".$v."] = \"".trim($new_label_val[$k])."\";\r\n");
}
foreach($other_dropdown as $k=>$v)
{
        fwrite($file,"\$OCC_OTHER_ARR[".$k."] = \"".trim($v)."\";\r\n");
}
fwrite($file,"?>");

echo "DONE";

//CLOSE DATABASE CONNECTION
mysql_close($dbM);
mysql_close($dbM1);
//CLOSING ENDS
?>
