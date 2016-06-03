<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/*
@author: Kumar Anand
*/

//for preventing timeout to maximum possible
ini_set('max_execution_time',0);
ini_set('memory_limit',-1);
ini_set('mysql.connect_timeout',-1);
ini_set('default_socket_timeout',259200); // 3 days
ini_set('log_errors_max_len',0);
//for preventing timeout to maximum possible

//INCLUDE FILES HERE
include_once("../profile/config.php");
include_once("../classes/Mysql.class.php");
include_once("tempClasses/Picture.class.php");
include_once("tempClasses/ScreenedPictures.class.php");
include_once("tempClasses/PictureFunctions.class.php");
//INCLUDE FILE ENDS

//MAKE CONNECTION TO MASTER AND SLAVE
$mysqlObjS = new Mysql;
$dbS = $mysqlObjS->connect("slave") or die(mysql_error());//logError("Unable to connect to slave","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);

$mysqlObjM = new Mysql;
$dbM = $mysqlObjM->connect("master") or die(mysql_error());//logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);
//CONNECTION MAKING ENDS

//GLOBAL VARIABLES
$pictureObj = new ScreenedPicture;
$pictureFunctionsObj = new PictureFunctions;
if ($whichMachine == "test")
{
        $PHOTO_URL = "http://testphotos.jeevansathi.com";
        $PIC_SERVER_URL = "http://ser7.jeevansathi.com";
        $screenedDirectory=$_SERVER["DOCUMENT_ROOT"];
	$basePhotoPathForCron='/data';
}
elseif ($whichMachine == "local")
{
        $PHOTO_URL = "http://photos.jeevansathi.com";
        $PIC_SERVER_URL = "http://ser7.jeevansathi.com";
        $screenedDirectory=$_SERVER["DOCUMENT_ROOT"];
	$basePhotoPathForCron='/data1';
}
else
{
        $PHOTO_URL = "http://ser7.jeevansathi.com";
	$PIC_SERVER_URL = "http://ser7.jeevansathi.com";
        $screenedDirectory=$_SERVER["DOCUMENT_ROOT"];
	$basePhotoPathForCron='/data';
}
$errCount = 0;
$checkingCountOfThisLoop=0;
$cronSkippedArray=array();
//GLOBAL VARIABLE ENDS

if(!isset($_SERVER['argv'][1]) || !isset($_SERVER['argv'][2]))
        die("Please Specify Argumnets");


//SELECT PROFILES FROM PICTURE TABLE
if ($_SERVER['argv'][3] == 1)
{
	$statement = "SELECT p1.PROFILEID as PROFILEID, p1.MAINPHOTO as MAINPHOTO, p1.ALBUMPHOTO1 as ALBUMPHOTO1, p1.ALBUMPHOTO2 as ALBUMPHOTO2, p1.THUMBNAIL as THUMBNAIL, p1.PROFILEPHOTO as PROFILEPHOTO, p1.VERSION as VERSION FROM newjs.PICTURE p1, test.TEMP_PROFILEID p3 WHERE p1.PROFILEID=p3.PROFILEID AND p1.MAINPHOTO = \"Y\"";
}
else
{
	$statement = "SELECT p1.PROFILEID as PROFILEID, p1.MAINPHOTO as MAINPHOTO, p1.ALBUMPHOTO1 as ALBUMPHOTO1, p1.ALBUMPHOTO2 as ALBUMPHOTO2, p1.THUMBNAIL as THUMBNAIL, p1.PROFILEPHOTO as PROFILEPHOTO, p1.VERSION as VERSION FROM newjs.PICTURE p1 LEFT JOIN newjs.PICTURE_NEW p2 ON p1.PROFILEID = p2.PROFILEID WHERE p2.PROFILEID IS NULL AND p1.MAINPHOTO = \"Y\" AND p1.PROFILEID%".$_SERVER['argv'][1]." =  ".$_SERVER['argv'][2];
}

//$statement = $statement." LIMIT 4,2";

$result = $mysqlObjS->executeQuery($statement,$dbS) or die(mysql_error($statement));//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$statement,"ShowErrTemplate");
//SELECTION ENDS

//FETCH AND SEND THE SELECTED PROFILES ONE BY ONE
while($row = $mysqlObjS->fetchArray($result))
{
	$i = 0;
	$profileCheckSum = md5(intval($row["PROFILEID"])+5)."i".(intval($row["PROFILEID"])+5);
	$profileid=$row["PROFILEID"];

	if ($row["MAINPHOTO"] == "Y")
	{
		$resultArray["PROFILEID"][$i] = $row["PROFILEID"];
		$resultArray["PICTUREID"][$i] = getPictureAutoIncrementId($mysqlObjM,$dbM);
		$resultArray["TITLE"][$i] = null;
		$resultArray["KEYWORDS"][$i] = null;
		$resultArray["PICFORMAT"][$i] = "jpg";
		$resultArray["TYPE"][$i] = "image/jpg";
		$resultArray["WHICHPHOTO"][$i] = "MAINPHOTO";

		$resultArray["MainPicUrl"][$i] = $PIC_SERVER_URL."/profile/photo_serve.php?version=".$row["VERSION"]."&profileid=".$profileCheckSum."&photo=MAINPHOTO";
		$resultArray["Thumbail96Url"][$i] = $pictureObj->getDisplayPicUrl("thumbnail96",$resultArray["PICTUREID"][$i],$resultArray["PROFILEID"][$i],$resultArray["PICFORMAT"][$i],'',$PHOTO_URL);

		if ($row["PROFILEPHOTO"] == "Y")
		{
			$resultArray["ProfilePicUrl"][$i] = $PIC_SERVER_URL."/profile/photo_serve.php?version=".$row["VERSION"]."&profileid=".$profileCheckSum."&photo=PROFILEPHOTO";
			$resultArray["SearchPicUrl"][$i] = $PIC_SERVER_URL."/profile/photo_serve100.php?version=".$row["VERSION"]."&profileid=".$profileCheckSum."&photo=PROFILEPHOTO";
		}
		else
		{
			$resultArray["ProfilePicUrl"][$i] = null;
			$resultArray["SearchPicUrl"][$i] = null;
		}

		if ($row["THUMBNAIL"] == "Y")
			$resultArray["ThumbailUrl"][$i] = $PIC_SERVER_URL."/profile/photo_serve.php?version=".$row["VERSION"]."&profileid=".$profileCheckSum."&photo=THUMBNAIL";
		else
			$resultArray["ThumbailUrl"][$i] = null;
		$i++;
	}

	if ($row["ALBUMPHOTO1"] == "Y")
	{
		$resultArray["PROFILEID"][$i] = $row["PROFILEID"];
		$resultArray["PICTUREID"][$i] = getPictureAutoIncrementId($mysqlObjM,$dbM);
		$resultArray["TITLE"][$i] = null;
		$resultArray["KEYWORDS"][$i] = null;
		$resultArray["PICFORMAT"][$i] = "jpg";
		$resultArray["TYPE"][$i] = "image/jpg";
		$resultArray["WHICHPHOTO"][$i] = "ALBUMPHOTO1";

		$resultArray["MainPicUrl"][$i] = $PIC_SERVER_URL."/profile/photo_serve.php?version=".$row["VERSION"]."&profileid=".$profileCheckSum."&photo=ALBUMPHOTO1";
		$resultArray["Thumbail96Url"][$i] = $pictureObj->getDisplayPicUrl("thumbnail96",$resultArray["PICTUREID"][$i],$resultArray["PROFILEID"][$i],$resultArray["PICFORMAT"][$i],'',$PHOTO_URL);

		$resultArray["ProfilePicUrl"][$i] = null;
		$resultArray["SearchPicUrl"][$i] = null;

		$resultArray["ThumbailUrl"][$i] = null;
		$i++;
	}

	if ($row["ALBUMPHOTO2"] == "Y")
	{
		$resultArray["PROFILEID"][$i] = $row["PROFILEID"];
		$resultArray["PICTUREID"][$i] = getPictureAutoIncrementId($mysqlObjM,$dbM);
		$resultArray["TITLE"][$i] = null;
		$resultArray["KEYWORDS"][$i] = null;
		$resultArray["PICFORMAT"][$i] = "jpg";
		$resultArray["TYPE"][$i] = "image/jpg";
		$resultArray["WHICHPHOTO"][$i] = "ALBUMPHOTO2";

		$resultArray["MainPicUrl"][$i] = $PIC_SERVER_URL."/profile/photo_serve.php?version=".$row["VERSION"]."&profileid=".$profileCheckSum."&photo=ALBUMPHOTO2";
		$resultArray["Thumbail96Url"][$i] = $pictureObj->getDisplayPicUrl("thumbnail96",$resultArray["PICTUREID"][$i],$resultArray["PROFILEID"][$i],$resultArray["PICFORMAT"][$i],'',$PHOTO_URL);

		$resultArray["ProfilePicUrl"][$i] = null;
		$resultArray["SearchPicUrl"][$i] = null;

		$resultArray["ThumbailUrl"][$i] = null;
	}

	if ($row["MAINPHOTO"] == "Y" || $row["ALBUMPHOTO1"] == "Y" || $row["ALBUMPHOTO2"] == "Y")
	{
		$ins_ordering = 0;
		createThumbnails($resultArray,$pictureFunctionsObj,$pictureObj,$screenedDirectory,$profileid,$basePhotoPathForCron);
		performDbAction($resultArray,$ins_ordering,$mysqlObjM,$dbM);
	}

	unset($resultArray);
}
//FETCHING ENDS
echo "============>>>>>>>>>>>>>>>>>";
echo $checkingCountOfThisLoop;
echo "------>>>";
if(is_array($cronSkippedArray))
{
	$cronSkippedArray=array_unique($cronSkippedArray);
	echo implode(",",$cronSkippedArray);
}
die("Done");

function performDbAction($resultArray,$ins_ordering,$mysqlObjM,$dbM)
{
	global $errCount;

	$sql = "REPLACE INTO newjs.PICTURE_NEW (PICTUREID,PROFILEID,ORDERING,TITLE,KEYWORD,MainPicUrl,ProfilePicUrl,ThumbailUrl,Thumbail96Url,PICFORMAT,SearchPicUrl) VALUES ";
	foreach ($resultArray["PICTUREID"] as $i=>$v)
	{
		$paramArr[] = "(\"".$resultArray["PICTUREID"][$i]."\",\"".$resultArray["PROFILEID"][$i]."\",\"".($ins_ordering+$i)."\",\"".$resultArray["TITLE"][$i]."\",\"".$resultArray["KEYWORDS"][$i]."\",\"".$resultArray["MainPicUrl"][$i]."\",\"".$resultArray["ProfilePicUrl"][$i]."\",\"".$resultArray["ThumbailUrl"][$i]."\",\"".$resultArray["Thumbail96Url"][$i]."\",\"".$resultArray["PICFORMAT"][$i]."\",\"".$resultArray["SearchPicUrl"][$i]."\")";
	}
	$paramStr = implode(",",$paramArr);
	$sql = $sql.$paramStr;
	
	$output = $mysqlObjM->executeQuery($sql,$dbM); //or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	if (!$output)
	{
		$sql1 = "INSERT INTO MIS.PHOTO_CRON_ERROR (PROFILEID,TYPE) VALUES (\"".$resultArray["PROFILEID"][0]."\","."PICTURE".")";
		$mysqlObjM->executeQuery($sql1,$dbM) or die(mysql_error($dbM).$sql1);//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql1,"ShowErrTemplate");
		$errCount++;
		if ($errCount == 20)
		{
			die("20 consecutive errors.");
		}
	}
	else
	{
		$errCount = 0;
	}
}

function createThumbnails($resultArray,$pictureFunctionsObj,$pictureObj,$directory,$profileid,$basePhotoPathForCron)
{
	global $checkingCountOfThisLoop,$cronSkippedArray;
	foreach($resultArray["PICTUREID"] as $i=>$v)
	{
                $mainPicPath=$basePhotoPathForCron."/photos/".strtolower($resultArray['WHICHPHOTO'][$i])."/".intval(intval($profileid)/1000) . "/" . $profileid . ".jpg";
                if(!file_exists($mainPicPath))
                {
                        $checkingCountOfThisLoop++;
                        //$mainPicPath=$resultArray["MainPicUrl"][$i];
			$cronSkippedArray[]=$profileid;
                }
		else
		{
			$src_pic_name = $mainPicPath;
			$dest_pic_name = $pictureObj->getSaveUrl("thumbnail96",$resultArray["PICTUREID"][$i],$resultArray["PROFILEID"][$i],$resultArray["PICFORMAT"][$i],'',$directory);
			$pictureFunctionsObj->maintain_ratio_canvas($src_pic_name,$dest_pic_name,0,0,0,0,96,96,$resultArray["TYPE"][$i]);
	                $pictureFunctionsObj->generate_image_for_canvas($dest_pic_name,96,96,$resultArray["TYPE"][$i]);
		}
		unset($src_pic_name);
		unset($dest_pic_name);
	}
}

function getPictureAutoIncrementId($mysqlObjM,$dbM)
{
	$sql="REPLACE INTO newjs.PICTURE_AUTOINCREMENT(AUTO_ID,NO_USE_VARIABLE) VALUES('','X')";
	$mysqlObjM->executeQuery($sql,$dbM) or die(mysql_error($dbM).$sql);//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	return $mysqlObjM->insertId();
}
?>
