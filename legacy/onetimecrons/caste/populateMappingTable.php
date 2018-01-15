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

$statement = "INSERT INTO newjs.CASTE_GROUP_MAPPING(ID,GROUP_VALUE,CASTE_VALUE) SELECT \"\",c1.VALUE,c2.VALUE FROM newjs.CASTE_OLD_ANAND c1, newjs.CASTE_OLD_ANAND c2 WHERE c1.GROUPID = c2.GROUPID AND c1.ISGROUP = \"Y\" ORDER BY c1.GROUPID, c2.VALUE ASC";
$result = $mysqlObjM->executeQuery($statement,$dbM) or die($statement);

$file = fopen("mapping.csv", "r") or exit("Unable to open file!");
$x=1;
$insertStatement = "";
while(!feof($file))
{
        if($x!=1)
        {
                $data = explode("|",trim(fgets($file)));
                if ($data[0] && $data[1])
                      $insertStatement = $insertStatement."(\"\",".$data[0].",".$data[1]."),";
        }
        else
        {
                fgets($file);
                $x++;
        }
}
$insertStatement = rtrim($insertStatement,",");
$insertStatement = "INSERT INTO newjs.CASTE_GROUP_MAPPING(ID,GROUP_VALUE,CASTE_VALUE) VALUES ".$insertStatement;
$mysqlObjM->executeQuery($insertStatement,$dbM) or die($insertStatement);
fclose($file);

echo "DONE";

//CLOSE DATABASE CONNECTION
mysql_close($dbM);
//CLOSING ENDS
?>
