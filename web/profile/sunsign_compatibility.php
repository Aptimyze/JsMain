<?php

function getSunshineData($loggedInPID,$viewedPID,$dob='',$gender='')
{
	$sunshineData = array();
	if($dob!='' && $gender!='')
	{
		$dobUser		= $dob[$loggedInPID];	
		$dobViewedUser  	= $dob[$viewedPID];
		$genderUser		= $gender[$loggedInPID];
		$genderViewedUser	= $gender[$viewedPID];

	}else
	{
		$dataArr = getDateOfBirth($loggedInPID,$viewedPID);
		$dateOfBirthArr         = $dataArr['dateOfBirth'];
		$genderArr              = $dataArr['GENDER'];	
		$dobUser 		= $dateOfBirthArr[0];
		$dobViewedUser          = $dateOfBirthArr[1];
		$genderUser		= $genderArr[0];		
		$genderViewedUser	= $genderArr[1];
	}
	$genderArr		= $dataArr['GENDER'];
	$userSunshineArr	= getSunshine($dobUser);
	$viewedUserSunshineArr  = getSunshine($dobViewedUser);		
	$compatibility 		= getCompatibility($userSunshineArr['NAME'],$genderUser,$viewedUserSunshineArr['NAME'],$genderViewedUser);
	$sunshineData =array("USER_SUNSHINE"=>$userSunshineArr['NAME'],"VIEWED_USER_SUNSHINE"=>$viewedUserSunshineArr['NAME'],"VIEWED_USER_SHUNSHINE_DESC"=>$viewedUserSunshineArr['DESCRIPTION'],"COMPAT"=>$compatibility);
	return $sunshineData;
}

function getDateOfBirth($loggedInPID,$viewedPID)
{
	$strPID = "";
	$strPID = $loggedInPID.",".$viewedPID;
	$sql="select DTOFBIRTH,GENDER FROM newjs.JPROFILE where  activatedKey=1 and PROFILEID in($strPID)";
	$result= mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$i = 0;
	while($row = mysql_fetch_array($result))
	{
		$dtOfBirth[$i] = $row['DTOFBIRTH']; 
		$gender[$i]    = $row['GENDER'];
		$i++;
	}
	return array("dateOfBirth"=>$dtOfBirth,"GENDER"=>$gender);
}

function getSunshine($dtOfBirth)
{
	$dtOfBirthArr = array();
	$data=array();
	$dtOfBirthArr 	= explode("-",$dtOfBirth); 
	$year  		= $dtOfBirthArr[0];
	$month 		= $dtOfBirthArr[1];
	$day   		= $dtOfBirthArr[2];
	$monthFormat	=date("F",mktime( 0, 0, 0, $month, $day, $year));

	$order = "ASC";	
	if($monthFormat =='March')
		$order ="DESC";
	$sql="select * FROM newjs.SUNSIGN_LOOKUP where TO_MONTH='$monthFormat' OR FROM_MONTH='$monthFormat' order by ID $order";
	$result= mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$i = 0;
	while($row = mysql_fetch_array($result))
        {
		$name[$i]	= $row['NAME'];
		$value[$i]	= $row['VALUE'];
		$fromDay[$i] 	= $row['FROM_DAY']; 
		$fromMonth[$i] 	= $row['FROM_MONTH'];
		$toMonth[$i]	= $row['TO_MONTH'];
		$toDay[$i]	= $row['TO_DAY'];
		$desc[$i]	= $row['DESC'];
                $i++;
        }
	if($day >=0 && $day <=$toDay[0]){ 
		$selName  = $name[0];
		$selDesc  = $desc[0];
		$selValue = $value[0];
	}	
	else if($day >=0 && $day >=$fromDay[1]){
		$selName  = $name[1];
		$selDesc  = $desc[1];
		$selValue = $value[1];
	}
	$data = array("NAME"=>$selName,"VALUE"=>$selValue,"DESCRIPTION"=>$selDesc);
	return $data;
}

function getCompatibility($userSunshine,$userGender,$viewedUserSunshine,$viewedUserGender)
{
	if($userGender =='M' && $viewedUserGender=='F'){
		$sunshineM = $userSunshine;
		$sunshineF = $viewedUserSunshine;
	}
	else{
		$sunshineF = $userSunshine;
		$sunshineM = $viewedUserSunshine;
	}	
	$sql="select `DESC` FROM newjs.SUNSIGN_COMPAT WHERE `MAN`='$sunshineM' AND `WOMAN`='$sunshineF'";
	$result= mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$row = mysql_fetch_array($result);
	if($row['DESC'])
		return $row['DESC'];
	return;
}	

?>
