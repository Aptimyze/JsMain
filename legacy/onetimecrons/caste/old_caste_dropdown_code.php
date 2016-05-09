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

$filename = "overall_updates.csv";
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
                $old_value[] = trim($values[1]);
                $new_value[] = trim($values[0]);
        }
}

$old_castes = implode(",",$old_value).",".implode(",",$new_value);

$statement = "SELECT LABEL,VALUE FROM newjs.CASTE_OLD_ANAND WHERE VALUE IN (".$old_castes.")";
$result = $mysqlObjM->executeQuery($statement,$dbM) or die($statement);
$casteArr = array();
while($row = $mysqlObjM->fetchArray($result))
{
        $casteArr[$row["VALUE"]] = $row["LABEL"];
}

$file = fopen("old_caste_dropdown_onetime.php", "w") or exit("Unable to open file!");
fwrite($file,"<?php\r\n");
foreach ($casteArr as $k=>$v)
{
        fwrite($file,"\$OLD_CASTE_ARR[\"".$k."\"] = \"".trim($v)."\";\r\n");
}
foreach($old_value as $k=>$v)
{
        fwrite($file,"\$MERGE_CASTE_ARR[".$v."] = ".$new_value[$k].";\r\n");
        fwrite($file,"\$MERGE_CASTE_ARR[".$new_value[$k]."] = ".$v.";\r\n");
}
fwrite($file,"?>");

echo "DONE";

//CLOSE DATABASE CONNECTION
mysql_close($dbM);
//CLOSING ENDS
?>
