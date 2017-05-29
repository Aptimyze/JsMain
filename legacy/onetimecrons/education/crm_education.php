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

$statement = "SELECT SQL_CACHE el.VALUE AS VALUE, el.LABEL AS LABEL, el.GROUPING AS GROUPING FROM EDUCATION_LEVEL_NEW el, EDUCATION_GROUPING eg WHERE el.GROUPING = eg.VALUE ORDER BY eg.SORTBY,el.SORTBY";
$result = $mysqlObjM->executeQuery($statement,$dbM) or die($statement);
while($row = $mysqlObjM->fetchArray($result))
{
        $EDU[$row["VALUE"]]=$row["LABEL"];
}

$file = fopen("crm_education_dropdown.php", "w") or exit("Unable to open file!");
fwrite($file,"<?php\r\n");
foreach ($EDU as $k=>$v)
{
        fwrite($file,"  ".$k." => '".trim($v)."',\r\n");
}
fwrite($file,"?>");

echo "DONE";

//CLOSE DATABASE CONNECTION
mysql_close($dbM);
//CLOSING ENDS
?>

