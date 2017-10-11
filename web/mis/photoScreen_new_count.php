<?php
include_once("connect.inc");
$db=connect_misdb();
$interfaceName[0]="PREPROCESS";
$interfaceName[1]="Accept/Reject Queue";
$interfaceName[4]="PROCESS Interface";
$data=array();	
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

$key = "PHOTO_SCREEN_COUNT_MIS_NEW_A";
$name = "jstech";
$time = "600";

if($_GET["wait"]!=1)
{
if (JsMemcache::getInstance()->get($key)) {
        JsMemcache::getInstance()->set($key, $name, $time);
        exit("Please refresh after 10 minutes.");
} else
        JsMemcache::getInstance()->set($key, $name, $time);
}
                        
                        
$sql="SELECT count(*) AS TOTAL FROM newjs.PICTURE_FOR_SCREEN_NEW";
$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
while($row=mysql_fetch_assoc($result))
{
        echo "<br><b>TOTAL PHOTOS</b> =>".$row["TOTAL"];
}
$sql="SELECT count( DISTINCT A.PROFILEID ) AS COUNT, B.HAVEPHOTO
FROM `newjs`.`PICTURE_FOR_SCREEN_NEW` A
LEFT JOIN newjs.JPROFILE B ON A.PROFILEID = B.PROFILEID
GROUP BY B.HAVEPHOTO";
$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
while($row=mysql_fetch_assoc($result))
{
        if($row["HAVEPHOTO"]=="Y")
                $edit+=$row["COUNT"];
        else
                $new+=$row["COUNT"];
}
echo "<br><br><b>TOTAL PROFILES</b> =>".($new+$edit);
echo "<br>EDIT PROFILES=>".$edit;
echo "<br>NEW PROFILES=>".$new;


$sql="SELECT count( A.PROFILEID ) AS COUNT, B.HAVEPHOTO,
CASE WHEN A.BIT LIKE '%0%'
THEN 0
WHEN A.BIT LIKE '%1%'
THEN 1
WHEN A.BIT LIKE '%4%'
THEN 4
END AS BITS
FROM (

SELECT PROFILEID, GROUP_CONCAT(
CASE WHEN ORDERING =0
AND SCREEN_BIT LIKE '0%'
THEN 0
WHEN SCREEN_BIT LIKE '0%'
THEN 0
WHEN ORDERING =0
AND SCREEN_BIT LIKE '1%1%'
THEN 1
WHEN ORDERING =0
AND SCREEN_BIT LIKE '1%4%'
THEN 4
WHEN ORDERING =0
AND SCREEN_BIT LIKE '1%2%'
THEN 2
WHEN ORDERING =0 AND CHAR_LENGTH(SCREEN_BIT)=1
THEN 1
WHEN ORDERING !=0 AND CHAR_LENGTH(SCREEN_BIT)>2
THEN SUBSTRING(SCREEN_BIT, 2, 1)
ELSE SCREEN_BIT
END ) AS BIT
FROM newjs.`PICTURE_FOR_SCREEN_NEW`
GROUP BY PROFILEID
) AS A
LEFT JOIN newjs.JPROFILE B ON A.PROFILEID = B.PROFILEID
GROUP BY B.HAVEPHOTO, BITS";
$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
while($row=mysql_fetch_assoc($result))
{
        if($row["HAVEPHOTO"]=="Y")
                $total[$row["BITS"]]["edit"]+=$row["COUNT"];
        else
                $total[$row["BITS"]]["new"]+=$row["COUNT"];
        
}
echo "<br><br><br><br><br>";
foreach($total AS $interface => $number){
if($interface!=0){
echo "<font color=red><u><b>".$interfaceName[$interface]."</b></u></font>";
echo "<br><b>TOTAL PROFILES</b> =>".($number["new"]+$number["edit"]);
echo "<br>EDIT PROFILES=>".$number["edit"];
echo "<br>NEW PROFILES=>".$number["new"];

echo "<br><br>";}
}

?>
