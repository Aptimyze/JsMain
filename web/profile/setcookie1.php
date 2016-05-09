<?php
/*
        include("js_encryption_functions.php");
        //value1 is profileid 
        //value2 is myjsid
        //value3 is username
        $domain=".jeevansathi.com";
        $checksum=md5($value3)."i".$value3;
        $myjsid=js_encrypt($checksum);
        setcookie($name3,$value3,0,"/",$domain);
        setcookie($name2,$myjsid,0,"/",$domain);
        setcookie($name1,$value1,0,"/",$domain);
*/
/*
        This file is called from the messenger server to set the cookies required for login.
*/

        include_once("connect.inc");

        $mysql= new Mysql;
        $db=$mysql->connect();

        $sql="select PROFILEID,SUBSCRIPTION,USERNAME,GENDER,ACTIVATED,SOURCE from JPROFILE where  activatedKey=1 and PROFILEID='$value1'";
        $result=$mysql->executeQuery($sql,$db);
        $myrow=mysql_fetch_array($result);
        $myrow["SUBSCRIPTION"]=trim($myrow["SUBSCRIPTION"]);
        $protect_obj->setcookies($myrow);
?>
