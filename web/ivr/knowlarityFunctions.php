<?php
/*************
Author: @ESHA
Desc:functions for handling virtual numbers
*************/

//Messages.enum.php called to fetch knowlarity number.
if($messagesEnum)
{
include_once(JsConstants::$docRoot."/commonFiles/mysql_multiple_connections.php");
        include_once("$_SERVER[DOCUMENT_ROOT]/profile/connect_db.php");
}
else
	include_once(JsConstants::$docRoot."/profile/connect.inc");
connect_db();


//Virtual numbers list provided by Third party - Knowlarity for leads generation
function virtualNumbersListForLeads(){
	return array (8506896060,8506876060,8506936060,8506846060,8506816060);
}

//Function that creates leads out of incoming phone numbers
function createLead($phoneno)
{
	if(is_numeric($phoneno) && strlen($phoneno)>=10)
	{
		global $SITE_URL;
		$link=$SITE_URL."/sugarcrm/custom/crons/create_sugar_lead.php?last_name=$phoneno&mobile1=$phoneno&source_c=17&js_source_c=ProfilePgK";
		$handle = curl_init();
        $header[0] = "Accept: text/html,application/xhtml+xml,text/plain,application/xml,text/xml;q=0.9,image/webp,*/*;q=0.8";
        curl_setopt($handle, CURLOPT_HEADER, $header);
        curl_setopt($handle,CURLOPT_USERAGENT,"JsInternal");
        
		curl_setopt($handle,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle,CURLOPT_MAXREDIRS, 5);
		curl_setopt($handle,CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($handle,CURLOPT_CONNECTTIMEOUT, 20);
		curl_setopt($handle, CURLOPT_URL,$link);
		curl_exec($handle);
		curl_close($handle);
	}
}
function getAllProfileVirtualNumbers($profileid, $phoneno='',$isd='')
{
	if($profileid)
	{
		if(!$phoneno||!$isd)
		{
			$sqlJ="SELECT ISD,PHONE_MOB,PHONE_RES,STD from newjs.JPROFILE where activatedKey=1 and PROFILEID='".$profileid."'";
			$resJ=mysql_query_decide($sqlJ);
			if($rowJ=mysql_fetch_array($resJ))
			{
				$phone_mob=trim(ltrim($rowJ['PHONE_MOB'],'0'));
				$phone_res=trim(ltrim($rowJ['STD'],'0').ltrim($rowJ['PHONE_RES'],'0'));
				$isd = trim(ltrim($rowJ['ISD'],'0'));
				if($phone_mob)
					$vNo=getVirtualNumber($profileid,$phone_mob,$isd);
				if($phone_res)
					$vNo=getVirtualNumber($profileid,$phone_res,$isd);
			}
			$sqlAlt ="SELECT ALT_MOBILE FROM newjs.JPROFILE_CONTACT WHERE PROFILEID='".$profileid."'";
			$resAlt =mysql_query_decide($sqlAlt);
			if($rowAlt =mysql_fetch_array($resAlt))
			{
				$alt_mobile=trim(ltrim($rowAlt['ALT_MOBILE'],'0'));
				if($alt_mobile)
					$vNo=getVirtualNumber($profileid,$alt_mobile,$isd);
			}
			return $vNo;
		}
		else 
			return getVirtualNumber($profileid,$phoneno,$isd);
	}
}
/**************************
name: getVirtualNumber
function takes the profileid and phone number and find its virtual number if already exist or generates a new for the corresponding profileid
**************************/
function getVirtualNumber($profileid,$phoneno,$isd)
{
	$phoneno=trim(ltrim($phoneno,'0'));
	$isd=trim(ltrim($isd,'0'));
	if($profileid && $phoneno && $isd)
	{
		$completeNumber = $isd.$phoneno;
		if($vNoid=searchExistingPid($profileid,$completeNumber))
		{
			$vNo=findvno($vNoid);
			if($isd=="91")
				return "011".$vNo;
			else
				return "+9111".$vNo;
		}
		else
		{
			if($viridArr=checkDuplicatNumber($completeNumber))//returns false if not duplicate
				$ar=generateVno($viridArr);
			else
				$ar=generateVno();
			$vNo=$ar["vNo"];
			$id=$ar["id"];
			saveVNumber($profileid,$completeNumber,$id);
			if($isd=="91")
				return "011".$vNo;
			else
				return "+9111".$vNo;
		}
	}
}
/*********
Name findvno
return virtual no for a virtual number id
************/
function findvno($vNoid)
{
        $sql="SELECT VIRTUALNO FROM newjs.VIRTUALNO WHERE ID='".$vNoid."'";
        $result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
        if($row=mysql_fetch_array($result))
                return $row["VIRTUALNO"];
}

/**************************
Name: generates a virtual number id in round robin
***************************/
function generateVno($viridArr="")
{
	if($viridArr!='')
		$sql="SELECT ID,VIRTUALNO,TIME FROM newjs.VIRTUALNO order by TIME asc";
	else
		$sql="SELECT ID,VIRTUALNO FROM newjs.VIRTUALNO order by TIME asc limit 1";

        $result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql);
        if($row=mysql_fetch_array($result))
	{
		$id=$row["ID"];
		$vNo=$row["VIRTUALNO"];
	}
	if($viridArr!='' && in_array($id,$viridArr))
	{
		while($row=mysql_fetch_array($result))
		{
			$idr=$row["ID"];
			$vNor=$row["VIRTUALNO"];
                        if(!in_array($vNor,$viridArr))
                        {
                        	$id=$idr;
                        	$vNo=$vNor;
                                break;
			}
		}

	}	
	$sql="UPDATE newjs.VIRTUALNO SET TIME= now() WHERE ID='".$id."'";
        $result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql);
	$ar['id']=$id;
	$ar['vNo']=$vNo;
	return $ar;
}


/***********return vid already used******/
function checkDuplicatNumber($phoneno)
{
        $phoneno=trim(ltrim($phoneno,'0'));
	$arr=array();
        $sql="SELECT VIRTUALNO FROM newjs.KNWLARITYVNO WHERE PHONENO='".$phoneno."'";
        $result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql);
        while($row=mysql_fetch_array($result))
		$arr[]=$row["VIRTUALNO"];
	$profUnqArr[]=array_unique($arr);
	if(count($arr)<1)
		return false;
	else
		return $arr;
}


function searchExistingPid($profileid,$phoneno)
{
        $phoneno=trim(ltrim($phoneno,'0'));
	$digits=strlen($phoneno);
	$sql="SELECT VIRTUALNO,PHONENO FROM newjs.KNWLARITYVNO WHERE PROFILEID='".$profileid."'";
        $result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql);
        if($row=mysql_fetch_array($result))
	{
		$vNoid= $row["VIRTUALNO"];
		$rowphn=substr($row["PHONENO"],-$digits);
		if($phoneno!=$rowphn)
			saveVNumber($profileid,$phoneno,$vNoid);
		return $vNoid;
	} 
	return false;
}



function saveVNumber($profileid,$phoneno,$vNoid)
{
        $phoneno=trim(ltrim($phoneno,'0'));
	if(strlen($phoneno)<=7)
		return false;
	$sql="INSERT IGNORE INTO newjs.KNWLARITYVNO VALUES ('','".$profileid."','".$phoneno."','".$vNoid."')";
	mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate") ;
}
/************************************************************************
api functions**********************************************************/
function findvnoId($virtualno)
{
        $sql="SELECT ID FROM newjs.VIRTUALNO WHERE VIRTUALNO='".$virtualno."'";
        $result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
        if($row=mysql_fetch_array($result))
                return $row["ID"];
}


/**********************************************************************************************
Desc: function searches for a combination of a particular profileid and phone number and returns the type of phone number:mobile or landline in JPROFILE 
**********************************************************************************************/
function findPhoneType($phoneno,$profileid)
{
        $phoneno=trim(ltrim($phoneno,'0'));
        $sql="SELECT `PHONE_MOB` ,PHONE_WITH_STD FROM newjs.JPROFILE WHERE PROFILEID='".$profileid."'";
        $result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
        if($row=mysql_fetch_array($result))
        {
                $landline       = $row['PHONE_WITH_STD'];
                $mobile         = $row['PHONE_MOB'];
                $landline       = trim(ltrim($landline,'0'));
                $type ="";
                if($mobile){
                        $mobile =removeAllSpecialChars($mobile);
			$mobile=trim(ltrim($mobile,'0'));
                        if(strstr($phoneno,$mobile) || strstr($mobile,$phoneno))
                                return $type ="M";     // flag set for Mobile
                }
                if($landline){
                        if($landline==$phoneno|| strstr($phoneno,$landline) || strstr($mobile,$landline))
                                return $type ="L";     // flag set for Landline
                }
        }
	$sqlAlt ="SELECT ALT_MOBILE FROM newjs.JPROFILE_CONTACT WHERE PROFILEID='".$profileid."'";
	$resAlt =mysql_query_decide($sqlAlt);
	if($rowAlt =mysql_fetch_array($resAlt))
	{
		$alt	=$rowAlt['ALT_MOBILE'];
		if($alt)
		{
			$alt=removeAllSpecialChars($alt);
			$alt=trim(ltrim($alt,'0'));
			if(strstr($phoneno,$alt) || strstr($alt,$phoneno))
				return $type='A';
		}
	}
}

/****************************************************************************************
Desc: function searches for a combination of phone number and virtual number id in KNWLARITYVNO table and returns the profileid againt the combination.
***************************************************************************************/
function findProfile($phoneno, $vnoid)
{
        $phoneno=trim(ltrim($phoneno,'0'));
        $sql="SELECT PROFILEID FROM newjs.KNWLARITYVNO WHERE PHONENO='".$phoneno."' AND VIRTUALNO='".$vnoid."'";
        $result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
        if($row=mysql_fetch_array($result))
        {
                $profileid=$row["PROFILEID"];
                return $profileid;
        }
        return false;
}

/****************************************************************************************
Desc: function deletes entry from KNWLARITYVNO table 
**************************************************************************************/

function clearEntry($profileid,$phoneno)
{
$sql="DELETE FROM newjs.KNWLARITYVNO WHERE PROFILEID='".$profileid."' AND PHONENO='".$phoneno."'";
$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
}


function genrate_xml()
{
        header('content-type: text/xml');
        $xmlStr='<?xml version="1.0" encoding="ISO-8859-1"?>';
        $xmlStr.="<Status>OK</Status>";
        return $xmlStr;
}
?>
