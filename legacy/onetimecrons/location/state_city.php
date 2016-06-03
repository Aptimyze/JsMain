<?php
//INCLUDE FILES HERE
  $curFilePath = dirname(__FILE__)."/";
 include_once("/usr/local/scripts/DocRoot.php");

include_once($_SERVER['DOCUMENT_ROOT']."/profile/config.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
//INCLUDE FILE ENDS

//MAKE CONNECTION TO MASTER AND SLAVE
$mysqlObjM = new Mysql;
$dbM = $mysqlObjM->connect("master") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

$statement = "SELECT VALUE,LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = 51 AND TYPE = 'STATE' ORDER BY SORTBY";
$result = $mysqlObjM->executeQuery($statement,$dbM) or die($statement);
while($row = $mysqlObjM->fetchArray($result))
{
        $CITY[$row["VALUE"]]="";
}

$statement = "SELECT VALUE,LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = 51 AND TYPE = 'CITY' ORDER BY SORTBY";
$result = $mysqlObjM->executeQuery($statement,$dbM) or die($statement);
while($row = $mysqlObjM->fetchArray($result))
{
	$index = substr($row["VALUE"],0,2);
        $CITY[$index]=$CITY[$index]."'".$row["VALUE"]."',";
}

foreach($CITY as $k=>$v)
{
	$CITY[$k] = rtrim($CITY[$k],",");
}


$file = fopen("state_city_dropdown.php", "w") or exit("Unable to open file!");
fwrite($file,"<?php\r\n");
foreach ($CITY as $k=>$v)
{
        fwrite($file,"\$CITY['".$k."'] = \"".trim($v)."\";\r\n");
}
fwrite($file,"?>");

echo "DONE";

//CLOSE DATABASE CONNECTION
mysql_close($dbM);
//CLOSING ENDS
?>

