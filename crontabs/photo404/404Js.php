<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");

include(JsConstants::$docRoot."/profile/connect_db.php");
include_once(JsConstants::$docRoot."/profile/login_intermediate_pages.php");
$db=connect_db();
$dbs = connect_slave();

$sql = "SELECT PROFILEID FROM newjs.SEARCH_MALE where HAVEPHOTO IN ('Y','U') LIMIT 10000000";
$res = mysql_query($sql,$dbs) or die(mysql_error($dbs));
while($row = mysql_fetch_array($res)){
        $pid = $row["PROFILEID"];
        /**
	* Case1
        */
	$sql1 = "SELECT PICTUREID,MainPicUrl FROM newjs.PICTURE_NEW WHERE MainPicUrl like 'JS%' AND PROFILEID=$pid";

	/**
        * Case 2
	*/
        //$sql1 = "SELECT PICTUREID,SearchPicUrl as MainPicUrl FROM newjs.PICTURE_NEW WHERE SearchPicUrl like 'JS%' AND PROFILEID=$pid";
        //$sql1.= " AND ORDERING=0";
        $res1 = mysql_query($sql1,$db) or die(mysql_error($db));
        while($row1= mysql_fetch_array($res1)){
                $file = $row1["MainPicUrl"];
                $fileExt = substr($file,0,2);
                if($fileExt=="JS")
                {
                        $path = "/var/www/html/web".substr($file,2);
                        if(!file_exists($path))
                        {
                                //echo $row1["PICTUREID"].",";
                                echo $pid.",";
                        }
                }
        }
}

