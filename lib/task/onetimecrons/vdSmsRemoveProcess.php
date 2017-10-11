<?php
include_once("MysqlDbConstants.class.php");
//Open connection at JSDB
$db_master = mysql_connect(MysqlDbConstants::$master['HOST'],MysqlDbConstants::$master['USER'],MysqlDbConstants::$master['PASS']) or die("Unable to connect to nmit server ");
$db_js_111 = mysql_connect(MysqlDbConstants::$slave111['HOST'],MysqlDbConstants::$slave111['USER'],MysqlDbConstants::$slave111['PASS']) or die("Unable to connect to local-111 server");

        $sql= "SELECT PROFILEID FROM test.VD_SMS_PROFILES";
        $res=mysql_query($sql,$db_js_111) or die($sql.mysql_error($db_js_111));
        while($myrow =mysql_fetch_array($res)){
                $profileid =$myrow['PROFILEID'];

                if($profileid){
                        $sql1 ="UPDATE billing.VARIABLE_DISCOUNT SET SENT='Y' WHERE PROFILEID='$profileid'";
                        mysql_query($sql1,$db_master) or die($sql1.mysql_error($db_master));

                        echo "\n".$sql1;
                }
        }
?>

