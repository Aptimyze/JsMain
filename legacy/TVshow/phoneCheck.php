<?php
include_once("../P/connect.inc");
$db=connect_db();
/*
the input url for this module will be 

/TVshow/phoneCheck.php?phone=phone


$phone----------> INPUT-- Entered Number

$registered------>   whether a registered user or not
$jProfileCode---->   whether a paid member or not
$jGender--------->   gender of the person if found
$jProfileId------>   PROFILEID if found OR LEAD-ID if Found in Leads OR 0 

$newSubscription-->  Final Subscription of the final profile found

$row_zeroVer|oneVer|multiVer--- >depending upon no. of verified profiles found in MOBILE_VERIFICATION_IVR table

$count---------> No. of Entries for the given Phone Number  in JPROFILE table
*/

// For Various Test Cases

/*
$phone='8800141208'; 	//0|0|0|0					 //case-1
$phone='9312965144';	//2|0|0|36e1f6d0-2df0-ab08-4966-4ad16b366d0f	 //case-2
$phone='9999193201';	//1|0|F|3183593 				 //case-3
$phone='1234567890';	//1|0|M|3187209					 //case-3
$phone='9999999999';	//1|1|M|3186767					 //case-4
$phone='1919191919';	//1|1|M|248    					 //case-4
$phone='1111111111';	//1|2|M|144111 					 //case-5
$phone='9876543210';	//1|2|M|144111 					 //case-5
*/

//      On success U will get the reuired output along with the case number *P*X|X|X||.... P is the respective case as in SRS   
function checkTV($phone)
{
	global $err1;
	if($phone=="")
		$err1=1;
	elseif(!preg_match("/^[0-9]{10}$/",$phone))
		$err1=2;
	else 
		$err1=0;
	return $err1;
}

function checkNumberTV($phone)
{
	global $jProfileCode,$registered,$jGender,$jProfileId,$db;
	$sql_jProfile="(SELECT PROFILEID,SUBSCRIPTION,GENDER FROM newjs.JPROFILE WHERE PHONE_MOB IN('0$phone','+91$phone','91$phone','$phone')  AND ACTIVATED <>'D' )UNION (SELECT PROFILEID,SUBSCRIPTION,GENDER FROM newjs.JPROFILE WHERE PHONE_WITH_STD='$phone' AND ACTIVATED <>'D')";
	$res_jProfile=mysql_query($sql_jProfile,$db) or mysql_error1("--1--".mysql_error($db));

	$count=0;
	while($row=mysql_fetch_array($res_jProfile))
	{
		$count++;
		$subscription[]=$row['SUBSCRIPTION'];
		$profileId[]=$row['PROFILEID'];
		$gender[]=$row['GENDER'];
	}
	if($count==0)
	{	$jGender=0;
		$jProfileCode=0;
		$sql_leads="SELECT id from sugarcrm.leads where phone_mobile='$phone' AND deleted <> 1";
		//Need To Confirm
		//$sql_lead.=" ORDER BY date_modified DESC LIMIT 1";
		//Need To Confirm
		$res_leads=mysql_query($sql_leads,$db) or mysql_error1("--2--".mysql_error($db));
		$found_leads=mysql_fetch_array($res_leads);
		if(!$found_leads)
		{
			$registered=0;
			$jProfileId=0;
		}
		else
		{
			$registered=2;
			$jProfileId=$found_leads['id'];
		}
	}
	else
	{
		$p=0;
		$registered=1;
		if($count==1)
		{
			$newSubscription=$subscription[0];
			$jGender=$gender[0];
			$jProfileId=$profileId[0];
		}
		else
		{
			$profileIdStr=implode("','",$profileId);
			//$sql_ver="SELECT PROFILEID FROM newjs.MOBILE_VERIFICATION_IVR where PROFILEID IN ('$profileIdStr') AND STATUS='Y'";
			//$sql_ver="SELECT PROFILEID FROM newjs.JPROFILE where PROFILEID IN ('$profileIdStr') AND (LANDL_STATUS='Y' OR MOB_STATUS='Y')";
			$sql_ver="(SELECT PROFILEID FROM newjs.JPROFILE where PROFILEID IN ('$profileIdStr') AND MOB_STATUS='Y') UNION (SELECT PROFILEID FROM newjs.JPROFILE where PROFILEID IN ('$profileIdStr') AND LANDL_STATUS='Y')";
			$res_ver=mysql_query($sql_ver,$db) or mysql_error1("--3--".mysql_error($db));
			$i=0;
			while($row_ver=mysql_fetch_array($res_ver))
			{
				$pidNewArr[]=$row_ver["PROFILEID"];
				$i++;
			}
			if($i!=1)
			{ 
				if($i==0)
				{
					$sql_zeroVer="SELECT PROFILEID,SUBSCRIPTION,GENDER FROM newjs.JPROFILE where PROFILEID IN ('$profileIdStr') ORDER BY SUBSCRIPTION= IF( SUBSCRIPTION LIKE '%F%' ,1,0) , LAST_LOGIN_DT DESC, ENTRY_DT DESC LIMIT 1";	
					$res_zeroVer=mysql_query($sql_zeroVer,$db) or mysql_error1("--4--".mysql_error($db));
					$row_zeroVer=mysql_fetch_array($res_zeroVer);
					$jProfileId=$row_zeroVer['PROFILEID'];
					$newSubscription=$row_zeroVer['SUBSCRIPTION'];
					$jGender=$row_zeroVer['GENDER'];
				}
				if($i>1)
				{	

					$pidNewStr=implode("','",$pidNewArr);
					$sql_multiVer="SELECT PROFILEID,SUBSCRIPTION,GENDER FROM newjs.JPROFILE where PROFILEID IN ('$pidNewStr')  ORDER BY SUBSCRIPTION=IF( SUBSCRIPTION LIKE '%F%',1,0) ,LAST_LOGIN_DT DESC,ENTRY_DT DESC LIMIT 1";
					$res_multiVer=mysql_query($sql_multiVer,$db) or mysql_error1("--5--".mysql_error($db));
					$row_multiVer=mysql_fetch_array($res_multiVer);
					$jProfileId=$row_multiVer['PROFILEID'];
					$newSubscription=$row_multiVer['SUBSCRIPTION'];
					$jGender=$row_multiVer['GENDER'];
				}
			}		
			else
			{	$jProfileId=$pidNewArr[0];
				for($j=0;$j<$count;$j++)
				{
					if($pidNewArr[0]==$profileId[$j])
					{
						$newSubscription=$subscription[$j];
						$jGender=$gender[$j];
					}
				}
			}
		}
		if(strstr($newSubscription,"F"))
		{
			if(strstr($newSubscription,"D"))
				$jProfileCode=2;
			else
				$jProfileCode=1;
		}
		else
			$jProfileCode=0;	
	}	
}
/*
function display()
{
	global $err1,$jProfileCode,$registered;
	if($err1)
	{
		if($err1==1)
		{
			echo "Number cannot be NULL";
			return ;
		}
		if($err1==2)
		{	echo "Enter Number Correctly";
			return ;
		}
	}
	if($registered==0)
		$e='1';
	elseif($registered==2)
		$e=2;
	else
	{
		if($jProfileCode==2)
			$e=5;
		elseif($jProfileCode==1)
			$e=4;
		else
			$e=3;
	}
	return $e;
}
*/
function mysql_error1($msg)
{
	global $db;
        echo 'E';
        //TEMP
        //echo $msg;
	//die;
        //TEMP
        global $phone;
        $msg.=" phone:$phone";
        mail("sandeep.samudrala@jeevansathi.com,lavesh.rawat@gmail.com","Error in PhoneCheck module",$msg);
        $sqlError="INSERT INTO MIS.TVSHOWCASE_ERROR(MOBILE_NUMBER,ENTRY_DT) VALUES('$phone',now())";
	$res=mysql_query($sqlError,$db) or die(mysql_error($db));
	die;

}

if($phone)
{

	$ret=checkTV($phone);
	if(!$ret)
	{
		checkNumberTV($phone);
		//$p=display();
	}
	else
	{	
		$sqlError="INSERT INTO MIS.TVSHOWCASE_ERROR(MOBILE_NUMBER,ENTRY_DT) VALUES('$phone',now())";
		$res=mysql_query($sqlError,$db) or die(mysql_error($db));
		echo "E";
		die;
	}
}
else
{
        $sqlError="INSERT INTO MIS.TVSHOWCASE_ERROR(MOBILE_NUMBER,ENTRY_DT) VALUES('$phone',now())";
	$res=mysql_query($sqlError,$db) or die(mysql_error($db));
	echo "E";
	die;
}
//echo "*".$p."*";
$finalValue=$registered."|".$jProfileCode."|".$jGender."|".$jProfileId;	
echo $finalValue;
?>
