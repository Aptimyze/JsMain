<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
include_once("$docRoot/crontabs/connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once("allocate_functions_revamp.php");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/contacts_functions.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");
include($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once("$docRoot/../lib/model/lib/FieldMapLib.class.php");

//////////////////////////////////
$start_time=date("Y-m-d H:i:s");
mail("vibhor.garg@jeevansathi.com,lakshay@jeevansathi.com","FTA CSV Generation Started At $start_time<EOD>","","From:JeevansathiCrm@jeevansathi.com");
/////////////////////////////////

$filename = $_SERVER['DOCUMENT_ROOT']."/crm/csv_files/FTA_FTO_Csv_Crm_Data".date('Y-m-d')."nonDNC.dat";

$fp = fopen($filename,"w+");
$db     = connect_db();
$db_dnc = connect_dnc();
//$db_dnc=$db; //for testing purpose
if(!$fp)
	die("no file pointer");

//$profilesInC = SymfonyFTOFunctions::getProfilesInState(FTOStateTypes::FTO_ELIGIBLE);
//$profilesInD1 = SymfonyFTOFunctions::getProfilesInState(FTOStateTypes::FTO_ACTIVE, FTOSubStateTypes::FTO_ACTIVE_LEAST_THRESHOLD);
$profiles = SymfonyFTOFunctions::getProfilesInState(FTOStateTypes::FTO_ELIGIBLE);
//$profiles=array_merge($profilesInC,$profilesInD1);
if(is_array($profiles))
{
	$profiles=filterProfiles($profiles);
	writeContentsFTAFile($profiles);
}
fclose($fp);

//////////////////////////////////
$end_time=date("Y-m-d H:i:s");
mail("vibhor.garg@jeevansathi.com,lakshay@jeevansathi.com","FTA CSV Generation Completed At $end_time<EOD>","","From:JeevansathiCrm@jeevansathi.com");
/////////////////////////////////

$profileid_file = $SITE_URL."/crm/csv_files/FTA_FTO_Csv_Crm_Data".date('Y-m-d')."nonDNC.dat";

$msg.="\nFor FTA FTO Calling : ".$profileid_file;

$to="rohan.mathur@jeevansathi.com,nisha.kumari@jeevansathi.com,anamika.singh@jeevansathi.com";
$bcc="vibhor.garg@jeevansathi.com,lakshay@jeevansathi.com";
$sub="FTA FTO CSVs";
$from="From:lakshay@jeevansathi.com";
$from .= "\r\nBcc:$bcc";

/*live*/
mail($to,$sub,$msg,$from);
/*live*/

function filterProfiles($profiles)
{
	global $db;
	for($i=0;$i<count($profiles);$i++)
	{
		$profileid=$profiles[$i]['PROFILEID'];
		if(check_profile($profileid))
		{
			$sql_pre="SELECT COUNT(*) AS CNT FROM incentive.FTA_ALLOCATION_TECH WHERE PROFILEID=$profileid";
			$res_pre=mysql_query($sql_pre,$db);
			$row_pre=mysql_fetch_assoc($res_pre);
			if($row_pre['CNT']>0)
				continue;

			$profilesFiltered[]=$profiles[$i];
		}
	}	
	return $profilesFiltered;	
}	
function writeContentsFTAFile($profiles)
{
	global $db,$fp;
	$mysql=new Mysql;
	$exclude_mtongue=array(1,3,16,17,31);
	for($i=0;$i<count($profiles);$i++)
	{
		$profileid=$profiles[$i]['PROFILEID'];
		$sql_data="SELECT USERNAME,ACTIVATED,LANDL_STATUS,MOB_STATUS,SOURCE,SEC_SOURCE,DTOFBIRTH,ISD,MTONGUE,SUBSCRIPTION,INCOMPLETE,ENTRY_DT,HAVEPHOTO,RELATION,GENDER,PHONE_MOB,PHONE_WITH_STD FROM newjs.JPROFILE WHERE PROFILEID=$profileid";
		$res_data=mysql_query($sql_data,$db);
		while($row_data=mysql_fetch_assoc($res_data))
		{
			if((strstr($row_data['SUBSCRIPTION'],"F")!="")||(strstr($row_data['SUBSCRIPTION'],"D")!=""))
                                                continue;
			$indianNo =isIndianNo($row_data['ISD']);
			$mtongue=$row_data['MTONGUE'];	
			$age=getAge($row_data['DTOFBIRTH']);
			if($row_data['INCOMPLETE']!='N'||!$indianNo||in_array("$mtongue",$exclude_mtongue))	
				continue;
			if($row_data['GENDER']=="M" && $age<=22)
				continue;
			if($row_data['SOURCE']=="onoffreg"||$row_data['SEC_SOURCE']=="C")
				continue;

			if($row_data["ACTIVATED"]!="Y")
				continue;

			//PhoneNumArray
			$phoneNumArray = array();
			if($row_data['PHONE_WITH_STD']!="")
				$PHONE_RES = $row_data['PHONE_WITH_STD'];
			else
				$PHONE_RES = $row_data['STD'].$row_data['PHONE_RES'];
			if($PHONE_RES)
				$PHONE_RES =phoneNumberCheck($PHONE_RES);
			$phoneNumArray['PHONE3'] = $PHONE_RES;

			$sql_AN="SELECT ALT_MOBILE,ALT_MOB_STATUS FROM newjs.JPROFILE_CONTACT WHERE PROFILEID='$profileid'";
			$res_AN=mysql_query($sql_AN,$db);
			$row_AN=mysql_fetch_array($res_AN);
			$AL_NUMBER=$row_AN['ALT_MOBILE'];
			$AL_MOB_STATUS=$row_AN['ALT_MOB_STATUS'];

			//$AL_NUMBER =getOtherPhoneNums($profileid);
			if($AL_NUMBER)
				$PHONE_MOB2 =phoneNumberCheck($AL_NUMBER);
			else
				$PHONE_MOB2 ='';
			$phoneNumArray['PHONE2'] = $PHONE_MOB2;

			$PHONE_MOB =$row_data['PHONE_MOB'];
			if($PHONE_MOB)
				$PHONE_MOB =phoneNumberCheck($PHONE_MOB);
			$phoneNumArray['PHONE1']=$PHONE_MOB;

			$phoneNumArray = checkDNC($phoneNumArray);
			$isDNC = $phoneNumArray["STATUS"];
			if($isDNC)
				continue;
			else
			{
				$cnt=0;
				while($cnt!=4)
				{
					$param = "PHONE".$cnt."S";
					if($phoneNumArray[$param]=='Y')
						$phoneNumArray["PHONE$cnt"]="";
					$cnt++;
				}
			}
			for($cnt=1;$cnt<=3;$cnt++)
			{
				if($phoneNumArray["PHONE$cnt"]!="")
					$phoneNumArray["PHONE$cnt"]="0".$phoneNumArray["PHONE$cnt"];
			}
			$today=date('dMy');
			$leadId="FTA_FTO_".$today;
			$username=$row_data['USERNAME'];
			$regDate=fetchIST($row_data['ENTRY_DT']);
			if($row_data['HAVEPHOTO']=='Y')
				$photo="Yes";
			elseif($row_data['HAVEPHOTO']=='U')
				$photo="Scrn";
			else
				$photo="No";

			if($row_data['MOB_STATUS']=='Y'||$row_data['LANDL_STATUS']=='Y'||$AL_MOB_STATUS=='Y')
				$phoneVerified="Yes";
			else
				$phoneVerified="No";
			$postedBy=$row_data['RELATION'];
			$postedByValue=FieldMap::getFieldLabel("relation",$postedBy);
			$gender=$row_data['GENDER'];
			$mobile1=$phoneNumArray["PHONE1"];
			$mobile2=$phoneNumArray["PHONE2"];
			$landline=$phoneNumArray["PHONE3"];
			$dialStatus=1;
			$eoi_accepted=0;
			$eoi_declined=0;
			$eoi_waiting=0;
			$eoi_sent=0;

			//contacts made and accepted 
			$contactResult = getResultSet("RECEIVER,TIME","$profileid","","","","'A'","","","","","","","","","$table_name");

			for($j=0;$j<count($contactResult);$j++)
				$contact_made_accepted[] = $contactResult[$j]["RECEIVER"];

			$eoi_accepted =count($contact_made_accepted);
			unset($contact_made_accepted); 

			//Contacts made and Declined
			$contactResult = getResultSet("RECEIVER","$profileid","","","","'D'","","","","","","","","","$table_name");
			for($k=0;$k<count($contactResult);$k++)
				$contact_made_denied[] = $contactResult[$k]["RECEIVER"];
			$total_contacts_made_denied =count($contact_made_denied);
			$eoi_declined+=$total_contacts_made_denied;
			unset($contact_made_denied);
			//ends

			//contact made and waiting
			$contactResult = getResultSet("RECEIVER","$profileid","","","","'I'","","","","","","","","","$table_name");
			for($k=0;$k<count($contactResult);$k++)
				$contact_made_initiated[] = $contactResult[$k]["RECEIVER"];
			$total_contacts_made_initiated =count($contact_made_initiated);
			$eoi_waiting+=$total_contacts_made_initiated;
			unset($contact_made_initiated);

			//sent EOI
			$eoi_sent=$eoi_waiting+$eoi_declined+$eoi_accepted;
			$myDbName=getProfileDatabaseConnectionName($profileid,'',$mysql);
		        $myDb=$mysql->connect("$myDbName");

			//Photo Request Recieved
			$sql_photo="SELECT count(*) as cnt FROM newjs.PHOTO_REQUEST WHERE PROFILEID_REQ_BY='$profileid'";
			$result_photo = $mysql->executeQuery($sql_photo,$myDb);
			$row_photo=$mysql->fetchArray($result_photo);
			$photo_request =$row_photo['cnt'];
			 //ends

			$stateId=$profiles[$i]['STATE_ID'];
			$priority=calculatePriority($stateId,$eoi_sent);			
			$line="$leadId"."|"."$profileid"."|"."$username"."|"."$regDate"."|"."$photo"."|"."$photo_request"."|"."$phoneVerified"."|"."$eoi_sent"."|"."$postedByValue"."|"."$gender"."|"."$mobile1"."|"."$mobile2"."|"."$landline"."|"."$mobile1"."|"."$mobile2"."|"."$landline"."|"."$priority"."|"."$dialStatus"."\n";

			fwrite($fp,$line);
		}
	}
}
function isIndianNo($num){
	if($num && ($num==91 || $num=='0091' || $num=='+91'))
		return 1;
	else
		return 0;
}
function getAge($newDob)
{
        $today=date("Y-m-d");
        $datearray=explode("-",$newDob);
        $todayArray=explode("-",$today);

        $years=($todayArray[0]-$datearray[0]);

        if(intval($todayArray[1]) < intval($datearray[1]))
                $years--;
        elseif(intval($todayArray[1]) == intval($datearray[1]) && intval($todayArray[2]) < intval($datearray[2]))
                $years--;

        return $years;
}
function calculatePriority($stateId,$eoi_sent)
{
	if($stateId==1 && $eoi_sent>0)
		$priority=8;
	elseif($stateId==2 && $eoi_sent>0)	
		$priority=7;
	elseif($stateId==3 && $eoi_sent>0)
		$priority=6;
	elseif($stateId==1 && $eoi_sent==0)
		$priority=5;
	elseif($stateId==2 && $eoi_sent==0)
		$priority=4;
	elseif($stateId==3 && $eoi_sent==0)
		$priority=3;
	else
		$priority=2;

	return $priority;
}
function fetchIST($time)
{
	$ISTtime=strftime("%Y/%m/%d %H:%M:%S",JSstrToTime("$time + 10 hours 30 minutes"));
	return $ISTtime;
}
