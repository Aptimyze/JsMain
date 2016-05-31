<?php 
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
$db=connect_db();
$db_slave=connect_slave();

/*$oneTime = 0;
if($oneTime)
{
	//Profiles in queue
	$sql = "SELECT PROFILE1,PROFILE2 FROM duplicates.PROBABLE_DUPLICATES";
	$res = mysql_query($sql) or logError($sql,"ShowErrTemplate");
	while($row = mysql_fetch_array($res))
	{
        	$profile1 = $row["PROFILE1"];
	        $profile2 = $row["PROFILE2"];
		$havephoto1 = fetchPhotoStatus($profile1); 
		$havephoto2 = fetchPhotoStatus($profile2);

		if($havephoto1=='Y' && $havephoto2=='Y')
			$havephoto = 'Y';
		else
			$havephoto = 'N';

		$duplicate1 = fetchDuplicateStatus($profile1); 
		$duplicate2 = fetchDuplicateStatus($profile2);
		
		$ftoStateArray = SymfonyFTOFunctions::getFTOStateArray($profile1);
		$ftoState1 = $ftoStateArray["SUBSTATE"];

		if($duplicate1=='Y' && $duplicate2=='Y')
			$priority = 0;
		else
			$priority = fetchProbablePriority($ftoState1,$havephoto);

		$sqlU = "UPDATE duplicates.PROBABLE_DUPLICATES SET PRIORITY='$priority' WHERE PROFILE1='$profile1' AND PROFILE2='$profile2'";
                $resU = mysql_query($sqlU) or logError($sqlU,"ShowErrTemplate");
	}
die;
}*/


$last_hr = date("Y-m-d H:i:s",time()-1800);

//array of profile have photo : photo1 & array of profile modified photo : photo
$sql1 = "SELECT PROFILEID FROM newjs.JPROFILE where PHOTODATE>='$last_hr'";
$res1 = mysql_query($sql1,$db_slave) or logError($sql1,"ShowErrTemplate");
while($row1 = mysql_fetch_array($res1))
	$photo[] = $row1["PROFILEID"];

//array of profile modified state : photo
$sql2 = "SELECT PROFILEID FROM FTO.FTO_STATE_LOG where ENTRY_DATE>='$last_hr'";
$res2 = mysql_query($sql2,$db_slave) or logError($sql2,"ShowErrTemplate");
while($row2 = mysql_fetch_array($res2))
        $photo[] = $row2["PROFILEID"];

//fetch pairs w.r.t. PROFILE1
for($i=0;$i<count($photo);$i++)
{
	$profile1 = $photo[$i];
	$sqlP1 = "SELECT PROFILE2 FROM duplicates.PROBABLE_DUPLICATES WHERE PROFILE1='$profile1'";
	$resP1 = mysql_query($sqlP1,$db_slave) or logError($sqlP1,"ShowErrTemplate");
	while($rowP1 = mysql_fetch_array($resP1))
	{
		$profile2 = $rowP1["PROFILE2"];
		$pair = $profile1."*".$profile2;
		$havephoto1 = fetchPhotoStatus($profile1);
                $havephoto2 = fetchPhotoStatus($profile2);
            	if($havephoto1=='Y' && $havephoto2=='Y')
                        updatePriority($pair,'Y');
                else
                        updatePriority($pair,'N');
	}
}

//fetch pairs w.r.t. PROFILE2
for($i=0;$i<count($photo);$i++)
{
        $profile2 = $photo[$i];
        $sqlP2 = "SELECT PROFILE1 FROM duplicates.PROBABLE_DUPLICATES WHERE PROFILE2='$profile2'";
        $resP2 = mysql_query($sqlP2,$db_slave) or logError($sqlP2,"ShowErrTemplate");
        while($rowP2 = mysql_fetch_array($resP2))
        {
		$profile1 = $rowP2["PROFILE1"];
                $pair = $profile1."*".$profile2;
		$havephoto1 = fetchPhotoStatus($profile1);
                $havephoto2 = fetchPhotoStatus($profile2);
                if($havephoto1=='Y' && $havephoto2=='Y')
			updatePriority($pair,'Y');
                else
			updatePriority($pair,'N');
        }
}

function updatePriority($pairToUpdate,$havephoto)
{
	$pair = explode("*",$pairToUpdate);
	$profile1 = $pair[0];
	$profile2 = $pair[1];

	$duplicate1 = fetchDuplicateStatus($profile1);
        $duplicate2 = fetchDuplicateStatus($profile2);

	$ftoStateArray = SymfonyFTOFunctions::getFTOStateArray($profile1);
        $ftoState1 = $ftoStateArray["SUBSTATE"];

	if($duplicate1=='Y' && $duplicate2=='Y')
        	$priority = 0;
        else
        	$priority = fetchProbablePriority($ftoState1,$havephoto);
	
	$sqlU = "UPDATE duplicates.PROBABLE_DUPLICATES SET PRIORITY='$priority' WHERE PROFILE1='$profile1' AND PROFILE2='$profile2'";
        $resU = mysql_query($sqlU,$db) or logError($sqlU,"ShowErrTemplate");

	if($duplicate1=='Y')
		duplicateTreatment($profile1);
	if($duplicate2=='Y')
        	duplicateTreatment($profile2);
}

function fetchProbablePriority($currentState,$photo_pair)
{
	$sqlP = "SELECT PRIORITY FROM duplicates.SCREENING_PRIORITY WHERE PHOTO_PAIR='$photo_pair' AND FTO_STATE='$currentState'";
        $resP = mysql_query($sqlP,$db_slave) or logError($sqlP,"ShowErrTemplate");
        if($rowP = mysql_fetch_array($resP))
                return $rowP["PRIORITY"];
	else
		return 0;
}

function duplicateTreatment($profile)
{
	$sqlDT1 = "UPDATE duplicates.PROBABLE_DUPLICATES as p JOIN duplicates.DUPLICATE_PROFILES as d SET p.PRIORITY='0' WHERE p.PROFILE1='$profile' AND p.PROFILE2=d.PROFILEID";
        $resDT1 = mysql_query($sqlDT1,$db) or logError($sqlDT1,"ShowErrTemplate");

	$sqlDT2 = "UPDATE duplicates.PROBABLE_DUPLICATES as p JOIN duplicates.DUPLICATE_PROFILES as d SET p.PRIORITY='0' WHERE p.PROFILE2='$profile' AND p.PROFILE1=d.PROFILEID";
        $resDT2 = mysql_query($sqlDT2,$db) or logError($sqlDT2,"ShowErrTemplate");
}

function fetchPhotoStatus($profile)
{
	$sqlH = "SELECT HAVEPHOTO FROM newjs.JPROFILE WHERE PROFILEID='$profile' AND HAVEPHOTO IN ('Y','U')";
	$resH = mysql_query($sqlH,$db_slave) or logError($sqlH,"ShowErrTemplate");
        if($rowH = mysql_fetch_array($resH))
		return 'Y';
	else
		return 'N';
}

//Profiles marked duplicate
function fetchDuplicateStatus($profile)
{
        $sqlD = "SELECT PROFILEID FROM duplicates.DUPLICATE_PROFILES WHERE PROFILEID='$profile'";
        $resD = mysql_query($sqlD,$db_slave) or logError($sqlD,"ShowErrTemplate");
        if($rowD = mysql_fetch_array($resD))
 		return 'Y';
	else
		return 'N';       
}
?>
