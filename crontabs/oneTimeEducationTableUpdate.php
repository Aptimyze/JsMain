<?php
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
chdir(dirname(__FILE__));
include_once("connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
$dbSlave=connect_slave();
$dbMaster = connect_db();
$sql1='SELECT VALUE,OLD_VALUE FROM EDUCATION_LEVEL_NEW';
$res_main=mysql_query($sql1,$dbSlave);
while($row1=mysql_fetch_array($res_main))
{
    if($row1['OLD_VALUE'] == 0)
        continue;
    $toUpdate = 0;
    $ugGroup = FieldMap::getFieldLabel('degree_ug', '',1);
    $pgGroup = FieldMap::getFieldLabel('degree_pg', '',1);
    if(array_key_exists($row1['VALUE'],$ugGroup) && $row1['OLD_VALUE'] != 4)
        $toUpdate = 4;
    else if(array_key_exists($row1['VALUE'],$pgGroup) && $row1['OLD_VALUE'] != 5 && $row1['OLD_VALUE'] != 6)
        $toUpdate = 5;
    if($toUpdate != 0){
        $valueToUpdate = $row1['VALUE'];
        echo $valueToUpdate."---";
        $sql2="UPDATE EDUCATION_LEVEL_NEW SET OLD_VALUE = $toUpdate WHERE VALUE = $valueToUpdate";
        mysql_query($sql2,$dbMaster);
    }
}                
                                   
                                
                                
                                