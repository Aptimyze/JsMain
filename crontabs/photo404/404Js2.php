<?php
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");

include(JsConstants::$docRoot."/profile/connect_db.php");
include_once(JsConstants::$docRoot."/profile/login_intermediate_pages.php");
$db=connect_db();
$dbs = connect_slave();

$sql = "SELECT PROFILEID FROM newjs.SEARCH_MALE where HAVEPHOTO IN ('Y','U') LIMIT 10000000";
$res = mysql_query($sql,$dbs) or die(mysql_error($dbs));

while($row = mysql_fetch_array($res))
{
        $pid = $row["PROFILEID"];
        $sql1 = "SELECT PICTUREID,MainPicUrl,OriginalPicUrl FROM newjs.PICTURE_NEW WHERE PROFILEID=$pid AND ORDERING>0 AND (OriginalPicUrl like 'JS%' OR OriginalPicUrl ='') AND (MainPicUrl like 'JS%' OR MainPicUrl='')";
        $res1 = mysql_query($sql1,$db) or die(mysql_error($db));
        while($row1= mysql_fetch_array($res1))
        {
                $mainPicPresent = false;
                $originalPicPresent = false;
                if($row1["MainPicUrl"])
                {
                        $mainPicPath = getPath($row1["MainPicUrl"]);
                        if(file_exists($mainPicPath))
                        {
                                $mainPicPresent = true;
                        }
                }
                if($row1["OriginalPicUrl"])
                {
                        $originalPicPath = getPath($row1["OriginalPicUrl"]);
                        if(file_exists($originalPicPath))
                        {
                                $originalPicPresent = true;
                        }
                }
                if($mainPicPresent==false && $originalPicPresent==false)
                {
                        //deletePhoto($pid,$row1['PICTUREID']);
                        echo $row1['PICTUREID'].",".$pid."\n\n";
                        //die;
                }
        }
}

function getPath($url)
{
        $fileExt = substr($url,0,2);
        if($fileExt=="JS")
        {
                $path = "/var/www/html/web".substr($url,2);
        }
        return $path;
}
function deletePhoto($profileid,$pictureid)
{
        $profileObj = new LoggedInProfile('',$profileid);
        $profileObj->getDetail('', '', '*');
        $pictureServiceObj=new PictureService($profileObj);
        echo $profileid."--".$pictureid."\n";
        $pictureServiceObj->deletePhoto($pictureid,$profileid,"other");
}
