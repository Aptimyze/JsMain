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
include_once("tempClasses/NonScreenedPictures.class.php");
include_once("tempClasses/PictureFunctions.class.php");
//INCLUDE FILE ENDS

//MAKE CONNECTION TO MASTER AND SLAVE
$mysqlObjS = new Mysql;
$dbS = $mysqlObjS->connect("slave") or logError("Unable to connect to slave","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);

$mysqlObjM = new Mysql;
$dbM = $mysqlObjM->connect("master") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);
//CONNECTION MAKING ENDS

//GLOBAL VARIABLES
$pictureObj = new NonScreenedPicture;
$pictureFunctionsObj = new PictureFunctions;
if ($whichMachine == "test")
{
        $PHOTO_URL = "http://testphotos.jeevansathi.com";
        $nonScreenedDirectory=$_SERVER["DOCUMENT_ROOT"];
}
elseif ($whichMachine == "local")
{
        $PHOTO_URL = "http://photos.jeevansathi.com";
        $nonScreenedDirectory=$_SERVER["DOCUMENT_ROOT"];
}
else
{
        $PHOTO_URL = "http://ser7.jeevansathi.com";
        $nonScreenedDirectory=$_SERVER["DOCUMENT_ROOT"];
}

$errCount = 0;
//GLOBAL VARIABLE ENDS

if(!isset($_SERVER['argv'][1]) || !isset($_SERVER['argv'][2]))
        die("Please Specify Argumnets");

//SELECT PROFILES FROM PICTURE TABLE
if ($_SERVER['argv'][3] == 1)
{
	$statement = "SELECT p1.PROFILEID as PROFILEID, p1.MAINPHOTO as MAINPHOTO, p1.ALBUMPHOTO1 as ALBUMPHOTO1, p1.ALBUMPHOTO2 as ALBUMPHOTO2, p1.THUMBNAIL as THUMBNAIL, p1.PROFILEPHOTO as PROFILEPHOTO FROM newjs.PICTURE_FOR_SCREEN p1, test.TEMP_PROFILEID p3 WHERE p1.UPLOADED NOT IN ('D','Y') AND p1.PROFILEID=p3.PROFILEID";
}
else
{
	$statement = "SELECT p1.PROFILEID as PROFILEID, p1.MAINPHOTO as MAINPHOTO, p1.ALBUMPHOTO1 as ALBUMPHOTO1, p1.ALBUMPHOTO2 as ALBUMPHOTO2, p1.THUMBNAIL as THUMBNAIL, p1.PROFILEPHOTO as PROFILEPHOTO FROM newjs.PICTURE_FOR_SCREEN p1 LEFT JOIN newjs.PICTURE_FOR_SCREEN_NEW p2 ON p1.PROFILEID = p2.PROFILEID WHERE p1.UPLOADED NOT IN ('D','Y') AND p2.PROFILEID IS NULL AND p1.PROFILEID%".$_SERVER['argv'][1]." =  ".$_SERVER['argv'][2];
}

//$statement = $statement." LIMIT 5";

$result = $mysqlObjS->executeQuery($statement,$dbS) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$statement,"ShowErrTemplate");
//SELECTION ENDS

//FETCH AND SEND THE SELECTED PROFILES ONE BY ONE
while($row = $mysqlObjS->fetchArray($result))
{
	$i = 0;
	$profileCheckSum = md5(intval($row["PROFILEID"])+5)."i".(intval($row["PROFILEID"])+5);

	if ($row["MAINPHOTO"])
	{
		$resultArray["PROFILEID"][$i] = $row["PROFILEID"];
		$resultArray["PICTUREID"][$i] = getPictureAutoIncrementId($mysqlObjM,$dbM);
		$resultArray["TITLE"][$i] = null;
		$resultArray["KEYWORDS"][$i] = null;
		$resultArray["PICFORMAT"][$i] = "jpeg";
		$resultArray["TYPE"][$i] = "image/jpeg";
		$resultArray["WHICHPHOTO"][$i] = "MAINPHOTO";

		$resultArray["MainPicUrl"][$i] = $pictureObj->getDisplayPicUrl("mainPic",$resultArray["PICTUREID"][$i],$resultArray["PROFILEID"][$i],$resultArray["PICFORMAT"][$i],'',$PHOTO_URL);

		$resultArray["Thumbail96Url"][$i] = $pictureObj->getDisplayPicUrl("thumbnail96",$resultArray["PICTUREID"][$i],$resultArray["PROFILEID"][$i],$resultArray["PICFORMAT"][$i],'',$PHOTO_URL);

		if ($row["PROFILEPHOTO"])
			$resultArray["ProfilePicUrl"][$i] = $pictureObj->getDisplayPicUrl("profilePic",$resultArray["PICTUREID"][$i],$resultArray["PROFILEID"][$i],$resultArray["PICFORMAT"][$i],'',$PHOTO_URL);
		else
			$resultArray["ProfilePicUrl"][$i] = null;

		if ($row["THUMBNAIL"])
			$resultArray["ThumbailUrl"][$i] = $pictureObj->getDisplayPicUrl("thumbnail",$resultArray["PICTUREID"][$i],$resultArray["PROFILEID"][$i],$resultArray["PICFORMAT"][$i],'',$PHOTO_URL);
		else
			$resultArray["ThumbailUrl"][$i] = null;
		$i++;
	}

	if ($row["ALBUMPHOTO1"])
	{
		$resultArray["PROFILEID"][$i] = $row["PROFILEID"];
		$resultArray["PICTUREID"][$i] = getPictureAutoIncrementId($mysqlObjM,$dbM);
		$resultArray["TITLE"][$i] = null;
		$resultArray["KEYWORDS"][$i] = null;
		$resultArray["PICFORMAT"][$i] = "jpeg";
		$resultArray["TYPE"][$i] = "image/jpeg";
		$resultArray["WHICHPHOTO"][$i] = "ALBUMPHOTO1";

		$resultArray["MainPicUrl"][$i] = $pictureObj->getDisplayPicUrl("mainPic",$resultArray["PICTUREID"][$i],$resultArray["PROFILEID"][$i],$resultArray["PICFORMAT"][$i],'',$PHOTO_URL);

		$resultArray["Thumbail96Url"][$i] = $pictureObj->getDisplayPicUrl("thumbnail96",$resultArray["PICTUREID"][$i],$resultArray["PROFILEID"][$i],$resultArray["PICFORMAT"][$i],'',$PHOTO_URL);

		$resultArray["ProfilePicUrl"][$i] = null;

		$resultArray["ThumbailUrl"][$i] = null;
		$i++;
	}

	if ($row["ALBUMPHOTO2"])
	{
		$resultArray["PROFILEID"][$i] = $row["PROFILEID"];
		$resultArray["PICTUREID"][$i] = getPictureAutoIncrementId($mysqlObjM,$dbM);
		$resultArray["TITLE"][$i] = null;
		$resultArray["KEYWORDS"][$i] = null;
		$resultArray["PICFORMAT"][$i] = "jpeg";
		$resultArray["TYPE"][$i] = "image/jpeg";
		$resultArray["WHICHPHOTO"][$i] = "ALBUMPHOTO2";

		$resultArray["MainPicUrl"][$i] = $pictureObj->getDisplayPicUrl("mainPic",$resultArray["PICTUREID"][$i],$resultArray["PROFILEID"][$i],$resultArray["PICFORMAT"][$i],'',$PHOTO_URL);

		$resultArray["Thumbail96Url"][$i] = $pictureObj->getDisplayPicUrl("thumbnail96",$resultArray["PICTUREID"][$i],$resultArray["PROFILEID"][$i],$resultArray["PICFORMAT"][$i],'',$PHOTO_URL);

		$resultArray["ProfilePicUrl"][$i] = null;

		$resultArray["ThumbailUrl"][$i] = null;
	}

	if ($row["MAINPHOTO"] || $row["ALBUMPHOTO1"] || $row["ALBUMPHOTO2"])
	{
		$ins_ordering = getOrderingForInsertion($mysqlObjS,$dbS,$row["PROFILEID"]);
		createMainPics($resultArray,$pictureObj,$nonScreenedDirectory,$row);	
		createThumbnails($resultArray,$pictureFunctionsObj,$pictureObj,$nonScreenedDirectory);
		performDbAction($resultArray,$ins_ordering,$mysqlObjM,$dbM);
	}

	unset($resultArray);
}
//FETCHING ENDS


//CLOSE DATABASE CONNECTION
mysql_close($dbS);
mysql_close($dbM);
//CLOSING ENDS

function performDbAction($resultArray,$ins_ordering,$mysqlObjM,$dbM)
{
	global $errCount;

	$sql = "INSERT INTO newjs.PICTURE_FOR_SCREEN_NEW (PICTUREID,PROFILEID,ORDERING,TITLE,KEYWORD,MainPicUrl,ProfilePicUrl,ThumbailUrl,Thumbail96Url,PICFORMAT) VALUES ";
	foreach ($resultArray["PICTUREID"] as $i=>$v)
	{
		$paramArr[] = "(\"".$resultArray["PICTUREID"][$i]."\",\"".$resultArray["PROFILEID"][$i]."\",\"".($ins_ordering+$i)."\",\"".$resultArray["TITLE"][$i]."\",\"".$resultArray["KEYWORDS"][$i]."\",\"".$resultArray["MainPicUrl"][$i]."\",\"".$resultArray["ProfilePicUrl"][$i]."\",\"".$resultArray["ThumbailUrl"][$i]."\",\"".$resultArray["Thumbail96Url"][$i]."\",\"".$resultArray["PICFORMAT"][$i]."\")";
	}
	$paramStr = implode(",",$paramArr);
	$sql = $sql.$paramStr;
	
	$output = $mysqlObjM->executeQuery($sql,$dbM);// or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	if (!$output)
	{
		$sql1 = "INSERT INTO MIS.PHOTO_CRON_ERROR (PROFILEID,TYPE) VALUES (\"".$resultArray["PROFILEID"][0]."\","."PICTURE_FOR_SCREEN".")";
		$mysqlObjM->executeQuery($sql1,$dbM);// or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql1,"ShowErrTemplate");
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

function createThumbnails($resultArray,$pictureFunctionsObj,$pictureObj,$directory)
{
	foreach($resultArray["PICTUREID"] as $i=>$v)
	{
		$src_pic_name = $pictureObj->getSaveUrl("mainPic",$resultArray["PICTUREID"][$i],$resultArray["PROFILEID"][$i],$resultArray["PICFORMAT"][$i],'',$directory);
		$dest_pic_name = $pictureObj->getSaveUrl("thumbnail96",$resultArray["PICTUREID"][$i],$resultArray["PROFILEID"][$i],$resultArray["PICFORMAT"][$i],'',$directory);
		$pictureFunctionsObj->maintain_ratio_canvas($src_pic_name,$dest_pic_name,0,0,0,0,96,96,$resultArray["TYPE"][$i]);
                $pictureFunctionsObj->generate_image_for_canvas($dest_pic_name,96,96,$resultArray["TYPE"][$i]);
		unset($src_pic_name);
		unset($dest_pic_name);
	}
}

function createMainPics($resultArray,$pictureObj,$directory,$row)
{
	foreach($resultArray["PICTUREID"] as $i=>$v)
        {
		if ($resultArray["WHICHPHOTO"][$i] == "MAINPHOTO")
		{
			$src_handle = ImageCreateFromString($row["MAINPHOTO"]);
                	$dest_pic_name = $pictureObj->getSaveUrl("mainPic",$resultArray["PICTUREID"][$i],$resultArray["PROFILEID"][$i],$resultArray["PICFORMAT"][$i],'',$directory);
			imagejpeg($src_handle,$dest_pic_name,100);
			chmod($dest_pic_name, 0777);

			if ($resultArray["ProfilePicUrl"][$i])
			{
				$src_handle = ImageCreateFromString($row["PROFILEPHOTO"]);
                		$dest_pic_name = $pictureObj->getSaveUrl("profilePic",$resultArray["PICTUREID"][$i],$resultArray["PROFILEID"][$i],$resultArray["PICFORMAT"][$i],'',$directory);
				imagejpeg($src_handle,$dest_pic_name,100);
				chmod($dest_pic_name, 0777);
			}

			if ($resultArray["ThumbailUrl"][$i])
			{
				$src_handle = ImageCreateFromString($row["THUMBNAIL"]);
                		$dest_pic_name = $pictureObj->getSaveUrl("thumbnail",$resultArray["PICTUREID"][$i],$resultArray["PROFILEID"][$i],$resultArray["PICFORMAT"][$i],'',$directory);
				imagejpeg($src_handle,$dest_pic_name,100);
				chmod($dest_pic_name, 0777);
			}
		}

		else if ($resultArray["WHICHPHOTO"][$i] == "ALBUMPHOTO1")
		{
			$src_handle = ImageCreateFromString($row["ALBUMPHOTO1"]);
                	$dest_pic_name = $pictureObj->getSaveUrl("mainPic",$resultArray["PICTUREID"][$i],$resultArray["PROFILEID"][$i],$resultArray["PICFORMAT"][$i],'',$directory);
			imagejpeg($src_handle,$dest_pic_name,100);
			chmod($dest_pic_name, 0777);
		}

		else if ($resultArray["WHICHPHOTO"][$i] == "ALBUMPHOTO2")
		{
			$src_handle = ImageCreateFromString($row["ALBUMPHOTO2"]);
                	$dest_pic_name = $pictureObj->getSaveUrl("mainPic",$resultArray["PICTUREID"][$i],$resultArray["PROFILEID"][$i],$resultArray["PICFORMAT"][$i],'',$directory);
			imagejpeg($src_handle,$dest_pic_name,100);
			chmod($dest_pic_name, 0777);
		}
	}
}

function getPictureAutoIncrementId($mysqlObjM,$dbM)
{
        $sql="REPLACE INTO newjs.PICTURE_AUTOINCREMENT(AUTO_ID,NO_USE_VARIABLE) VALUES('','X')";
        $mysqlObjM->executeQuery($sql,$dbM) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        return $mysqlObjM->insertId();
}

function getOrderingForInsertion($mysqlObjS,$dbS,$profileid)
{
	$sql="SELECT MAX(ORDERING) as ORDERING FROM newjs.PICTURE_NEW WHERE PROFILEID = ".$profileid;
        $result = $mysqlObjS->executeQuery($sql,$dbS) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$row = $mysqlObjS->fetchArray($result);
	if ($row["ORDERING"]!=null)
		return ($row["ORDERING"]+1);
        else
		return 0;
}
?>
