<?php

include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
include_once(JsConstants::$docRoot."/commonFiles/incomeCommonFunctions.inc");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

function createDPP($parameters,$profileid,$createdBy,$role,$newDPPStatus='',$currentDPPId='',$currentDPPStatus='',$newCurrentDPPStatus='',$currentDPPOnline='',$currentDPPCreatedBy='',$comments='',$online='',$seVersion='')
{
	if($seVersion && !$currentDPPId)
	{
		$sqlId="SELECT DPP_ID,STATUS,ONLINE,CREATED_BY FROM Assisted_Product.AP_DPP_FILTER_ARCHIVE WHERE PROFILEID='$profileid' AND STATUS!='OBS' ORDER BY DPP_ID DESC";
		$resId=mysql_query_decide($sqlId) or die("Error while fetching id for se version");
		$rowId=mysql_fetch_assoc($resId);
		$currentDPPId=$rowId["DPP_ID"];
		$currentDPPStatus=$rowId["STATUS"];
		$currentDPPOnline=$rowId["ONLINE"];
		$currentDPPCreatedBy=$rowId["CREATED_BY"];
		global $_SERVER;
		mail("sadaf.alam@jeevansathi.com","se creates new dpp without dpp id","parameters profileid $profileid,created by  $createdBy,role $role,new dpp status $newDPPStatus,current dpp status $currentDPPStatus, new current dpp status $newCurrentDPPStatus,current dpp online $currentDPPOnline,current dpp created by $currentDPPCreatedBy, comments $comments, online $online,requesting uri $_SERVER[REQUEST_URI]");
	}
	if($currentDPPStatus=='LIVE' && $role!='ONLINE')
		$online='';
	elseif($currentDPPOnline)
		$online='Y';	
	$sql="INSERT INTO Assisted_Product.AP_DPP_FILTER_ARCHIVE(GENDER,CHILDREN,LAGE,HAGE,LHEIGHT,HHEIGHT,HANDICAPPED,CASTE_MTONGUE,PARTNER_BTYPE,PARTNER_CASTE,PARTNER_CITYRES,STATE,PARTNER_COUNTRYRES,PARTNER_DIET,PARTNER_DRINK,PARTNER_ELEVEL_NEW,PARTNER_INCOME,PARTNER_MANGLIK,PARTNER_MSTATUS,PARTNER_MTONGUE,PARTNER_NRI_COSMO,PARTNER_OCC,PARTNER_RELATION,PARTNER_RES_STATUS,PARTNER_SMOKE,PARTNER_COMP,PARTNER_RELIGION,PARTNER_NAKSHATRA,NHANDICAPPED,AGE_FILTER,MSTATUS_FILTER,RELIGION_FILTER,CASTE_FILTER,COUNTRY_RES_FILTER,CITY_RES_FILTER,MTONGUE_FILTER,INCOME_FILTER,DATE,CREATED_BY,ROLE,ONLINE,STATUS,COMMENTS,PROFILEID,LINCOME,HINCOME,LINCOME_DOL,HINCOME_DOL) VALUES(\"$parameters[GENDER]\",\"$parameters[CHILDREN]\",\"$parameters[LAGE]\",\"$parameters[HAGE]\",\"$parameters[LHEIGHT]\",\"$parameters[HHEIGHT]\",\"$parameters[HANDICAPPED]\",\"$parameters[CASTE_MTONGUE]\",\"$parameters[PARTNER_BTYPE]\",\"$parameters[PARTNER_CASTE]\",\"$parameters[PARTNER_CITYRES]\",\"$parameters[STATE]\",\"$parameters[PARTNER_COUNTRYRES]\",\"$parameters[PARTNER_DIET]\",\"$parameters[PARTNER_DRINK]\",\"$parameters[PARTNER_ELEVEL_NEW]\",\"$parameters[PARTNER_INCOME]\",\"$parameters[PARTNER_MANGLIK]\",\"$parameters[PARTNER_MSTATUS]\",\"$parameters[PARTNER_MTONGUE]\",\"$parameters[PARTNER_NRI_COSMO]\",\"$parameters[PARTNER_OCC]\",\"$parameters[PARTNER_RELATION]\",\"$parameters[PARTNER_RES_STATUS]\",\"$parameters[PARTNER_SMOKE]\",\"$parameters[PARTNER_COMP]\",\"$parameters[PARTNER_RELIGION]\",\"$parameters[PARTNER_NAKSHATRA]\",\"$parameters[NHANDICAPPED]\",\"$parameters[AGE_FILTER]\",\"$parameters[MSTATUS_FILTER]\",\"$parameters[RELIGION_FILTER]\",\"$parameters[CASTE_FILTER]\",\"$parameters[COUNTRY_RES_FILTER]\",\"$parameters[CITY_RES_FILTER]\",\"$parameters[MTONGUE_FILTER]\",\"$parameters[INCOME_FILTER]\",NOW(),'$createdBy','$role','$online','$newDPPStatus','$comments','$profileid',\"$parameters[LINCOME]\",\"$parameters[HINCOME]\",\"$parameters[LINCOME_DOL]\",\"$parameters[HINCOME_DOL]\")";
	mysql_query_decide($sql) or die("Error while inserting DPP   ".mysql_error_js());

	$newDPPId=mysql_insert_id_js();

	if($currentDPPId && $currentDPPStatus!='LIVE' && $currentDPPStatus!='OBS')
	{
		changeDPPStatus($profileid,$createdBy,$currentDPPId,$currentDPPStatus,$newCurrentDPPStatus,$currentDPPOnline,$currentDPPCreatedBy);
	}

	logDPPStatusChange($profileid,$createdBy,$newDPPId,'',$newDPPStatus,$currentDPPId);
}

function changeDPPStatus($profileid,$changedBy,$DPPId,$currentStatus,$newStatus,$online,$createdBy)
{
	$sql="UPDATE Assisted_Product.AP_DPP_FILTER_ARCHIVE SET STATUS='$newStatus' WHERE PROFILEID='$profileid' AND DPP_ID='$DPPId' AND STATUS='$currentStatus'";
	mysql_query_decide($sql) or die("Error while updating DPP status   ".mysql_error_js());
	if(mysql_affected_rows_js()==0)
		return 0;
	else
	{
		logDPPStatusChange($profileid,$changedBy,$DPPId,$currentStatus,$newStatus,'');
		//SMS to be sent for all cases where DPP goes live : Trac 364
		//if($online && $newStatus=="LIVE")
		if($newStatus=='LIVE')
		{
			//if($createdBy=="ONLINE")
				sendOnlineSMS($profileid);
			/*else
				sendOnlineSMS($profileid,1);*/
		}		
	}
}

function logDPPStatusChange($profileid,$changedBy,$DPPId,$currentStatus,$newStatus,$actedOnId='')
{
	$sql="INSERT INTO Assisted_Product.AP_DPP_FILTER_CHANGE_LOG VALUES('$profileid','$DPPId','$changedBy','$currentStatus','$newStatus','$actedOnId',NOW())";
	mysql_query_decide($sql) or die("Error while logging status change   ".mysql_error_js());
}

function checkDPPCurrentStatus($DPPId,$profileid)
{
	$sql="SELECT STATUS FROM Assisted_Product.AP_DPP_FILTER_ARCHIVE WHERE DPP_ID='$DPPId' AND PROFILEID='$profileid'";
	$res=mysql_query_decide($sql) or die("Error while checking current dpp status   ".mysql_error_js());
	$row=mysql_fetch_assoc($res);
	return $row["STATUS"];
}

function fetchCurrentDPP($profileid,$status='LIVE')
{
	$sql="SELECT * FROM Assisted_Product.AP_DPP_FILTER_ARCHIVE WHERE PROFILEID='$profileid'";
	if($status)
		$sql.=" AND STATUS IN('$status')";
	$sql.=" ORDER BY DPP_ID DESC LIMIT 1";
	$res=mysql_query_decide($sql) or die("Error while fetching current Dpp   ".mysql_error_js());
	$row=mysql_fetch_assoc($res);
	return $row;
}

function fetchDPPHistory($profileid,$operator='',$showEdited='')
{
	if($role=="DIS")
		$live=1;
	$sql="SELECT * FROM Assisted_Product.AP_DPP_FILTER_ARCHIVE WHERE PROFILEID='$profileid' ORDER BY DPP_ID ASC";
	$res=mysql_query_decide($sql) or die("Error while fetching profile dpp history   ".mysql_error_js());
	if(mysql_num_rows($res))
	{
		while($row=mysql_fetch_assoc($res))
		{
			$DPPHistory[]=$row;
		}
		$lastIndex=count($DPPHistory)-1;
		if($showEdited)
		{
			$sql="SELECT * FROM Assisted_Product.AP_TEMP_DPP WHERE PROFILEID='$profileid' AND CREATED_BY='$operator'";
			$res=mysql_query_decide($sql) or die("Error while replacing temp parameters   ".mysql_error_js());
			$row=mysql_fetch_assoc($res);
			$DPPHistory[$lastIndex]["CHILDREN"]=$row["CHILDREN"];
			$DPPHistory[$lastIndex]["LAGE"]=$row["LAGE"];
			$DPPHistory[$lastIndex]["HAGE"]=$row["HAGE"];
			$DPPHistory[$lastIndex]["LHEIGHT"]=$row["LHEIGHT"];
			$DPPHistory[$lastIndex]["HHEIGHT"]=$row["HHEIGHT"];
			$DPPHistory[$lastIndex]["HANDICAPPED"]=$row["HANDICAPPED"];
			$DPPHistory[$lastIndex]["NHANDICAPPED"]=$row["NHANDICAPPED"];
			$DPPHistory[$lastIndex]["PARTNER_BTYPE"]=$row["PARTNER_BTYPE"];
			$DPPHistory[$lastIndex]["PARTNER_CASTE"]=$row["PARTNER_CASTE"];
			$DPPHistory[$lastIndex]["PARTNER_COUNTRYRES"]=$row["PARTNER_COUNTRYRES"];
			$DPPHistory[$lastIndex]["PARTNER_CITYRES"]=$row["PARTNER_CITYRES"];
                        $DPPHistory[$lastIndex]["STATE"]=$row["STATE"];
			$DPPHistory[$lastIndex]["PARTNER_DRINK"]=$row["PARTNER_DRINK"];
			$DPPHistory[$lastIndex]["PARTNER_DIET"]=$row["PARTNER_DIET"];
			$DPPHistory[$lastIndex]["PARTNER_ELEVEL_NEW"]=$row["PARTNER_ELEVEL_NEW"];
			$DPPHistory[$lastIndex]["PARTNER_INCOME"]=$row["PARTNER_INCOME"];
			$DPPHistory[$lastIndex]["PARTNER_MANGLIK"]=$row["PARTNER_MANGLIK"];
			$DPPHistory[$lastIndex]["PARTNER_MSTATUS"]=$row["PARTNER_MSTATUS"];
			$DPPHistory[$lastIndex]["PARTNER_MTONGUE"]=$row["PARTNER_MTONGUE"];
			$DPPHistory[$lastIndex]["PARTNER_OCC"]=$row["PARTNER_OCC"];
			$DPPHistory[$lastIndex]["PARTNER_RELATION"]=$row["PARTNER_RELATION"];
			$DPPHistory[$lastIndex]["PARTNER_RES_STATUS"]=$row["PARTNER_RES_STATUS"];
			$DPPHistory[$lastIndex]["PARTNER_SMOKE"]=$row["PARTNER_SMOKE"];
			$DPPHistory[$lastIndex]["PARTNER_COMP"]=$row["PARTNER_COMP"];
			$DPPHistory[$lastIndex]["PARTNER_RELIGION"]=$row["PARTNER_RELIGION"];
			$DPPHistory[$lastIndex]["PARTNER_NAKSHATRA"]=$row["PARTNER_NAKSHATRA"];
			$DPPHistory[$lastIndex]["AGE_FILTER"]=$row["AGE_FILTER"];
			$DPPHistory[$lastIndex]["MSTATUS_FILTER"]=$row["MSTATUS_FILTER"];
			$DPPHistory[$lastIndex]["RELIGION_FILTER"]=$row["RELIGION_FILTER"];
			$DPPHistory[$lastIndex]["CASTE_FILTER"]=$row["CASTE_FILTER"];
			$DPPHistory[$lastIndex]["COUNTRY_RES_FILTER"]=$row["COUNTRY_RES_FILTER"];
			$DPPHistory[$lastIndex]["CITY_RES_FILTER"]=$row["CITY_RES_FILTER"];
			$DPPHistory[$lastIndex]["MTONGUE_FILTER"]=$row["MTONGUE_FILTER"];
			$DPPHistory[$lastIndex]["INCOME_FILTER"]=$row["INCOME_FILTER"];
			$DPPHistory[$lastIndex]["EDITED"]=1;
			$DPPHistory[$lastIndex]["LINCOME"]=$row["LINCOME"];
			$DPPHistory[$lastIndex]["HINCOME"]=$row["HINCOME"];
			$DPPHistory[$lastIndex]["LINCOME_DOL"]=$row["LINCOME_DOL"];
			$DPPHistory[$lastIndex]["HINCOME_DOL"]=$row["HINCOME_DOL"];
		}
		return $DPPHistory;
	}
	else
	{
		$mysqlObj=new Mysql;
		$dbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
		$db=$mysqlObj->connect("$dbName");

		include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
		$jpartnerObj=new Jpartner;
	        $jpartnerObj->setPartnerDetails($profileid,$db,$mysqlObj);

		$SEArray=getSE($profileid);
		$SE=$SEArray[$profileid];

		$sql="SELECT * FROM newjs.FILTERS WHERE PROFILEID='$profileid'";
		$res=mysql_query_decide($sql) or die("Error while fetching filters   ".mysql_error_js());
		if(mysql_num_rows($res))
			$filterRow=mysql_fetch_assoc($res);
		$DPPHistory[]=array(
				"GENDER"=>$jpartnerObj->getGENDER(),
				"CHILDREN"=>$jpartnerObj->getCHILDREN(),
				"LAGE"=>$jpartnerObj->getLAGE(),
				"HAGE"=>$jpartnerObj->getHAGE(),
				"LHEIGHT"=>$jpartnerObj->getLHEIGHT(),
				"HHEIGHT"=>$jpartnerObj->getHHEIGHT(),
				"HANDICAPPED"=>$jpartnerObj->getHANDICAPPED(),
				"CASTE_MTONGUE"=>$jpartnerObj->getCASTE_MTONGUE(),
				"PARTNER_BTYPE"=>$jpartnerObj->getPARTNER_BTYPE(),
				"PARTNER_CASTE"=>$jpartnerObj->getPARTNER_CASTE(),
				"PARTNER_CITYRES"=>$jpartnerObj->getPARTNER_CITYRES(),
                                "STATE"=>$jpartnerObj->getSTATE(),
				"PARTNER_COUNTRYRES"=>$jpartnerObj->getPARTNER_COUNTRYRES(),
				"PARTNER_DIET"=>$jpartnerObj->getPARTNER_DIET(),
				"PARTNER_DRINK"=>$jpartnerObj->getPARTNER_DIET(),
				"PARTNER_ELEVEL_NEW"=>$jpartnerObj->getPARTNER_ELEVEL_NEW(),
				"PARTNER_INCOME"=>$jpartnerObj->getPARTNER_INCOME(),
				"PARTNER_MANGLIK"=>$jpartnerObj->getPARTNER_MANGLIK(),
				"PARTNER_MSTATUS"=>$jpartnerObj->getPARTNER_MSTATUS(),
				"PARTNER_MTONGUE"=>$jpartnerObj->getPARTNER_MTONGUE(),
				"PARTNER_NRI_COSMO"=>$jpartnerObj->getPARTNER_NRI_COSMO(),
				"PARTNER_OCC"=>$jpartnerObj->getPARTNER_OCC(),
				"PARTNER_RELATION"=>$jpartnerObj->getPARTNER_RELATION(),
				"PARTNER_RES_STATUS"=>$jpartnerObj->getPARTNER_RES_STATUS(),
				"PARTNER_SMOKE"=>$jpartnerObj->getPARTNER_SMOKE(),
				"PARTNER_COMP"=>$jpartnerObj->getPARTNER_COMP(),
				"PARTNER_RELIGION"=>$jpartnerObj->getPARTNER_RELIGION(),
				"PARTNER_NAKSHATRA"=>$jpartnerObj->getPARTNER_NAKSHATRA(),
				"PARTNER_HANDICAPPED"=>$jpartnerObj->getHANDICAPPED(),
				"NHANDICAPPED"=>$jpartnerObj->getNHANDICAPPED(),
				"AGE_FILTER"=>$filterRow["AGE"],
				"MSTATUS_FILTER"=>$filterRow["MSTATUS"],
				"RELIGION_FILTER"=>$filterRow["RELIGION"],
				"CASTE_FILTER"=>$filterRow["CASTE"],
				"COUNTRY_RES_FILTER"=>$filterRow["COUNTRY_RES"],
				"CITY_RES_FILTER"=>$filterRow["CITY_RES"],
				"MTONGUE_FILTER"=>$filterRow["MTONGUE_FILTER"],
				"INCOME_FILTER"=>$filterRow["INCOME"],
				"CREATED_BY"=>$SE,
				"PROFILEID"=>$profileid,
				"LINCOME"=>$jpartnerObj->getLINCOME(),
				"HINCOME"=>$jpartnerObj->getHINCOME(),
				"LINCOME_DOL"=>$jpartnerObj->getLINCOME_DOL(),
				"HINCOME_DOL"=>$jpartnerObj->getHINCOME_DOL());
		return $DPPHistory;
	}
}

function editDPP($profileid,$DPPId,$parameters)
{
	if(is_array($parameters))
	{
		$sql="UPDATE Assisted_Product.AP_DPP_FILTER_ARCHIVE SET ";
		foreach($parameters as $key=>$value)
		{
			if($key=="DATE")
				$sql.="$key=NOW(),";
			else
				$sql.="$key = \"$value\",";
		}
		$sql=trim($sql,",");
		$sql.=" WHERE PROFILEID='$profileid' AND DPP_ID='$DPPId'";
		mysql_query_decide($sql) or die("Error while editing dpp   ".mysql_error_js());
	}
}

function checkNewDPP($DPPId,$profileid)
{
	$sql="SELECT STATUS,DPP_ID FROM Assisted_Product.AP_DPP_FILTER_ARCHIVE WHERE PROFILEID='$profileid' AND DPP_ID>'$DPPId' ORDER BY DPP_ID DESC LIMIT 1";
	$res=mysql_query_decide($sql) or die("Error while checking for new dpp   ".mysql_error_js());
	if(mysql_num_rows($res))
	{
		return $res;
	}
	else
		return 0;
}

function sendOnlineSMS($profileid,$modified=0)
{
	//SMS module changed : Trac 364
	/*if($modified)
		$message="Your Desired Partner Profile has been checked by our team and has been modified. Please login to Jeevansathi.com to check your DPP";
	else
		$message="Your Desired Partner Profile has been checked by our team and has been approved. Please login to Jeevansathi.com to check your DPP";
include_once(JsConstants::$docRoot."/commonFiles/sms_inc.php");
	$sql="SELECT PHONE_MOB FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
	$res=mysql_query_decide($sql) or die("Error while fetching profile mobile no.   ".mysql_error_js());
	$row=mysql_fetch_assoc($res);
	if($row["PHONE_MOB"])
		send_sms($message,"9911328109",$row["PHONE_MOB"],$profileid,'','Y');*/
	include_once($_SERVER['DOCUMENT_ROOT']."/profile/InstantSMS.php");
	$sms=new InstantSMS("AP_EDIT",$profileid);
	$sms->send();
}

function displayDPP($DPP)
{
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
	include($_SERVER['DOCUMENT_ROOT']."/profile/manglik.php");
include(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");
	include($_SERVER['DOCUMENT_ROOT']."/profile/arrays.php");
	global $smarty;
	if(is_array($DPP))
	{
		$last=count($DPP)-1;
		foreach($DPP as $key=>$value)
		{
			if($key==0)
				$partner["FIRST"]=1;
			else
				$partner["FIRST"]=0;

			if($key==$last)
			{
				$partner["LAST"]=1;
				$smarty->assign("EDIT_DPP_ID",$value["DPP_ID"]);
				if($value["EDITED"]==1)
					$smarty->assign("DPP_EDITED",1);
				if($value["ONLINE"]=='Y')
					$smarty->assign("DPP_ONLINE",1);
				$smarty->assign("DPP_CREATED_BY",$value["CREATED_BY"]);
				$smarty->assign("DPP_STATUS",$value["STATUS"]);
			}
			else
				$partner["LAST"]=0;

			if($value["LAGE"]!="" && $value["HAGE"]!="")
                                $partner["AGE"]=$value["LAGE"]." to ".$value["HAGE"];
        	        else
	                        $partner["AGE"]="21 to 70";
			$partner["SHOW_AGE_FILTER"]="Y";
			if($value["LHEIGHT"]!="" && $value["HHEIGHT"]!="")
			{
				$lheight=$value["LHEIGHT"];
				if($lheight)
					$lheight=$HEIGHT_DROP["$lheight"];
				else
					$lheight=$HEIGHT_DROP["1"];
				$hheight=$value["HHEIGHT"];
				if($hheight)
					$hheight=$HEIGHT_DROP["$hheight"];
				else
                                	$hheight=$HEIGHT_DROP["32"];
				$lheight1=explode("(",$lheight);
				$hheight1=explode("(",$hheight);
				$partner["HEIGHT"]=$lheight1[0]." to ".$hheight1[0];

			}
			else
				$partner["HEIGHT"]=$HEIGHT_DROP["1"]." to ".$HEIGHT_DROP["32"];
			
			if($value["CHILDREN"]=="")
				$partner["CHILDREN"]="";
                        elseif($value["CHILDREN"]=="N")
				$partner["CHILDREN"]="No";
                        elseif($value["CHILDREN"]=="Y")
				$partner["CHILDREN"]="Yes";

			if($value["HANDICAPPED"]!="")
			{
				$ph_str = substr($value["HANDICAPPED"],1,strlen($value["HANDICAPPED"])-2);
				$ph_val_arr = explode("','",$ph_str);
				for($i=0;$i<count($ph_val_arr);$i++)
				{
					$ph_val=$ph_val_arr[$i];
					$ph_arr[$i]=$HANDICAPPED[$ph_val];
				}
				if(count($ph_arr)>1)
					$ph_fstr = implode(",",$ph_arr);
				elseif(count($ph_arr)==1)
					$ph_fstr = $ph_arr[0];
				else
					$ph_fstr = "";
				if(strstr($ph_fstr,'Physically Handicapped from birth')||strstr($ph_fstr,'Physically Handicapped due to accident'))
                                $partner["SHOWIT"]=1;
				$partner["HANDICAPPED"]=$ph_fstr;
			}

			if($value["NHANDICAPPED"]!="")
			{
				$nph_str = substr($value["NHANDICAPPED"],1,strlen($value["NHANDICAPPED"])-2);
				$nph_val_arr = explode("','",$nph_str);
				for($i=0;$i<count($nph_val_arr);$i++)
				{
					$nph_val=$nph_val_arr[$i];
					$nph_arr[$i]=$NATURE_HANDICAP[$nph_val];
				}
				if(count($nph_arr)>1)
					$nph_fstr = implode(",",$nph_arr);
				elseif(count($nph_arr)==1)
					$nph_fstr = $nph_arr[0];
				else
					$nph_fstr = "";
				if($showit)	
					$partner["SHOWIT"]=1;
				else
					$partner["SHOWIT"]=0;
				$partner["NHANDICAPPED"]=$nph_fstr;
			}
			else
			{
				if($showit)
					$partner["SHOWIT"]=1;
				else
					$partner["SHOWIT"]=0;
			}
			
			$p_manglik=trim($value["PARTNER_MANGLIK"],"'");
	                $p_mtongue=trim($value["PARTNER_MANGLIK"],"'");
			$return_data1=partnermanglik($p_mtongue,$p_manglik);
                        $manglik_data1=explode("+",$return_data1);
                        $partner["MANGLIK_STATUS"]=$manglik_data1[0];

			$temp=display_format($value["PARTNER_BTYPE"]);
                        for($ll=0;$ll<count($temp);$ll++)
                                $PARTNER_BTYPE[]=$BODYTYPE[$temp[$ll]];
                        unset($temp);
			if(is_array($PARTNER_BTYPE))
                                $partner["BTYPE"]=implode(", ",$PARTNER_BTYPE);
                        else
                                $partner["BTYPE"]="   - ";

                        $temp=display_format($value["PARTNER_COMP"]);
                        for($ll=0;$ll<count($temp);$ll++)
                                $PARTNER_COMP[]=$COMPLEXION[$temp[$ll]];
                        unset($temp);
			if(is_array($PARTNER_COMP))
                                $partner["COMP"]=implode(", ",$PARTNER_COMP);
                        else
                                $partner["COMP"]="   - ";

                        $temp=display_format($value["PARTNER_DIET"]);
                        for($ll=0;$ll<count($temp);$ll++)
                                $PARTNER_DIET[]=$DIET[$temp[$ll]];
                        unset($temp);
			if(is_array($PARTNER_DIET))
                                $partner["DIET"]=implode(", ",$PARTNER_DIET);
                        else
                                $partner["DIET"]="   - ";

                        $temp=display_format($value["PARTNER_DRINK"]);
                        for($ll=0;$ll<count($temp);$ll++)
                                $PARTNER_DRINK[]=$DRINK[$temp[$ll]];
                        unset($temp);
			if(is_array($PARTNER_DRINK))
                                $partner["DRINK"]=implode(", ",$PARTNER_DRINK);
                        else
                                $partner["DRINK"]="   - ";

                        $temp=display_format($value["PARTNER_MSTATUS"]);
                        for($ll=0;$ll<count($temp);$ll++)
                        {
                                $PARTNER_MSTATUS[]=$MSTATUS[$temp[$ll]];
                        }
                        unset($temp);
			if(is_array($PARTNER_MSTATUS))
			{
                                $partner["MSTATUS"]=implode(", ",$PARTNER_MSTATUS);
				$partner["SHOW_MSTATUS_FILTER"]="Y";
			}
                        else
                                $partner["MSTATUS"]="   - ";
		
			$temp=display_format($value["PARTNER_RES_STATUS"]);
                        for($ll=0;$ll<count($temp);$ll++)
                                $PARTNER_RES_STATUS[]=$RSTATUS[$temp[$ll]];
                        unset($temp);
			if(is_array($PARTNER_RES_STATUS))
                                $partner["RES_STATUS"]=implode(", ",$PARTNER_RES_STATUS);
                        else
                                $partner["RES_STATUS"]="   - ";

                        $temp=display_format($value["PARTNER_SMOKE"]);
                        for($ll=0;$ll<count($temp);$ll++)
                                $PARTNER_SMOKE[]=$SMOKE[$temp[$ll]];
                        unset($temp);
			if(is_array($PARTNER_SMOKE))
                                $partner["SMOKE"]=implode(", ",$PARTNER_SMOKE);
                        else
                                $partner["SMOKE"]="   - ";

                        $PARTNER_CASTE=display_format($value["PARTNER_CASTE"]);
			if(is_array($PARTNER_CASTE))
				$partner["SHOW_CASTE_FILTER"]="Y";
			$partner["CASTE"]=get_partner_string_from_array($PARTNER_CASTE,"CASTE");
			
                        $PARTNER_RELIGION=display_format($value["PARTNER_RELIGION"]);
			if(is_array($PARTNER_RELIGION))
				$partner["SHOW_RELIGION_FILTER"]="Y";
			$partner["RELIGION"]=get_partner_string_from_array($PARTNER_RELIGION,"RELIGION");


                        $PARTNER_ELEVEL_NEW=display_format($value["PARTNER_ELEVEL_NEW"]);
			$partner["ELEVEL_NEW"]=get_partner_string_from_array($PARTNER_ELEVEL_NEW,"EDUCATION_LEVEL_NEW");

                        $PARTNER_MTONGUE=display_format($value["PARTNER_MTONGUE"]);
			if(is_array($PARTNER_MTONGUE))
				$partner["SHOW_MTONGUE_FILTER"]="Y";
			$partner["MTONGUE"]=get_partner_string_from_array($PARTNER_MTONGUE,"MTONGUE");

                        $PARTNER_OCC=display_format($value["PARTNER_OCC"]);
			$partner["OCC"]=get_partner_string_from_array($PARTNER_OCC,"OCCUPATION");

                        $PARTNER_COUNTRYRES=display_format($value["PARTNER_COUNTRYRES"]);
			if(is_array($PARTNER_COUNTRYRES))
				$partner["SHOW_COUNTRYRES_FILTER"]="Y";
			$partner["COUNTRYRES"]=get_partner_string_from_array($PARTNER_COUNTRYRES,"COUNTRY");

			/* Partner Income Display Part Trac#280 */
						
			
			$cur_sort_arr["maxID"]=$value["HINCOME_DOL"];
			$cur_sort_arr["minID"]=$value["LINCOME_DOL"];
			$cur_sort_arr["maxIR"]=$value["HINCOME"];
			$cur_sort_arr["minIR"]=$value["LINCOME"];
			global $INCOME_MAX_DROP,$INCOME_MIN_DROP;
			
			$varr=getIncomeText($cur_sort_arr);
			if($varr){
				$income_arr[]=implode(",</br>&nbsp;",$varr);
			}

			foreach ($income_arr as $key=>$val)
				 $partner["INCOME"]=$income_arr[$key];

			$PARTNER_INCOME=display_format($value["PARTNER_INCOME"]);
			if(is_array($PARTNER_INCOME))
				$partner["SHOW_INCOME_FILTER"]="Y";

//			$partner["INCOME"]=get_partner_string_from_array($PARTNER_INCOME,"INCOME");
			
			/* Ends Here */

			$PARTNER_CITYRES=display_format($value["PARTNER_CITYRES"]);
			if(is_array($PARTNER_CITYRES))
			{
				$cityLabelArr = FieldMap::getFieldLabel("city_india",'',1);
				foreach($PARTNER_CITYRES as $key=>$val)
				{
					$partner_city_str.=$cityLabelArr[$val] . ", ";
				}
				$partner_city_str=substr($partner_city_str,0,strlen($partner_city_str)-2);
				//$partner["CITYRES"]=$partner_city_str;
			}
			$STATE = display_format($value["STATE"]);
			if(is_array($STATE))
			{
                                $stateLabelArr = FieldMap::getFieldLabel("state_india",'',1);
				foreach($STATE as $key=>$val)
				{
					$partner_state_str.=$stateLabelArr[$val] . ", ";
				}
				$partner_state_str=substr($partner_state_str,0,strlen($partner_state_str)-2);
			}
			if($partner_state_str!="")
			{
				if($partner_city_str!="")
				{
					$partner["CITYRES"] = $partner_state_str.", ".$partner_city_str;
				}
				else
				{
					$partner["CITYRES"] = $partner_state_str;
				}
			}
			else
			{
				$partner["CITYRES"]=$partner_city_str;
			}
			if($partner["CITYRES"])
				$partner["SHOW_CITYRES_FILTER"]="Y";

			$partner["AGE_FILTER"]=$value["AGE_FILTER"];
			$partner["MSTATUS_FILTER"]=$value["MSTATUS_FILTER"];
			$partner["COUNTRY_RES_FILTER"]=$value["COUNTRY_RES_FILTER"];
			$partner["CITY_RES_FILTER"]=$value["CITY_RES_FILTER"];
			$partner["MTONGUE_FILTER"]=$value["MTONGUE_FILTER"];
			$partner["INCOME_FILTER"]=$value["INCOME_FILTER"];
			$partner["RELIGION_FILTER"]=$value["RELIGION_FILTER"];
			$partner["CASTE_FILTER"]=$value["CASTE_FILTER"];
			$partner["DPP_ID"]=$value["DPP_ID"];
			$partner["CREATED_BY"]=$value["CREATED_BY"];
			$partner["ROLE"]=$value["ROLE"];
			$partner["STATUS"]=$value["STATUS"];
			$dt=explode(" ",$value["DATE"]);
			list($year,$month,$day)=explode("-",$dt[0]);
			$partner["DATE"]=my_format_date($day,$month,$year,2);
			$partner["ONLINE"]=$value["ONLINE"];
			$partner["COMMENTS"]=htmlentities($value["COMMENTS"],ENT_QUOTES);
			$displayDPP[]=$partner;
			unset($partner);
			unset($lheight);
			unset($hheight);
			unset($lheight1);
			unset($hheight1);
			unset($ph_str);
			unset($ph_val_arr);
			unset($ph_val);
			unset($ph_arr);
			unset($ph_fstr);
			unset($showit);
			unset($nph_str);
			unset($nph_val_arr);
			unset($nph_val);
			unset($nph_arr);
			unset($nph_fstr);
			unset($p_manglik);
			unset($p_mtongue);
			unset($PARTNER_BTYPE);
			unset($PARTNER_COMP);
			unset($PARTNER_DIET);
			unset($PARTNER_DRINK);
			unset($PARTNER_MSTATUS);
			unset($PARTNER_MANGLIK);
			unset($PARTNER_RES_STATUS);
			unset($PARTNER_SMOKE);
			unset($PARTNER_CASTE);
			unset($PARTNER_RELIGION);
			unset($PARTNER_ELEVEL_NEW);
			unset($PARTNER_MTONGUE);
			unset($PARTNER_OCC);
			unset($PARTNER_COUNTRYRES);
			unset($PARTNER_INCOME);
			unset($return_data1);
			unset($manglik_data1);
			unset($partner_city_str);
			unset($PARTNER_CITYRES);
			unset($partner_state_str);
		}
		$smarty->assign("displayDPP",$displayDPP);
	}
}
if(!function_exists('get_partner_string_from_array'))
{
function get_partner_string_from_array($arr,$tablename)
{
	global $lang;
	if(is_array($arr))
	{
		$str=implode("','",$arr);
		if(substr($str,-1)==",")
		{
			$wr_dt=print_r($_SERVER,true);
			$str=substr($str,0,strlen($str)-2);

		}
		$sql="select SQL_CACHE distinct LABEL from newjs.$tablename where VALUE in ('$str')";
		$dropresult=mysql_query_decide($sql) or die($sql."   Error while displaying dpp   ".mysql_error_js());

		while($droprow=mysql_fetch_array($dropresult))
		{
			$str1.=$droprow["LABEL"] . ", ";
		}

                        mysql_free_result($dropresult);

                        return substr($str1,0,-2);
	}
	else
		return "   - ";
}
}

function deleteTemporaryDPP($profileid,$operator)
{
	if($profileid && $operator)
	{
		$sql="DELETE FROM Assisted_Product.AP_TEMP_DPP WHERE PROFILEID='$profileid' AND CREATED_BY='$operator'";
		mysql_query_decide($sql) or die("Error while deleting temporary dpp   ".mysql_error_js());
	}
}

function updateTemporaryDPP($updateString,$profileid,$operator)
{
	if($updateString && $profileid && $operator)
	{
		$sql="UPDATE Assisted_Product.AP_TEMP_DPP SET $updateString WHERE PROFILEID='$profileid' AND CREATED_BY='$operator'";
		mysql_query_decide($sql) or die("Error while updating temp dpp   ".mysql_error_js());

		if(mysql_affected_rows_js()==0)
		{
			$sql="SELECT COUNT(*) AS COUNT FROM Assisted_Product.AP_TEMP_DPP WHERE PROFILEID='$profileid' AND CREATED_BY='$operator'";
			$res=mysql_query_decide($sql) or die("Error while checking for temp dpp    ".mysql_error_js());
			$row=mysql_fetch_assoc($res);
			if($row["COUNT"])
				return 1;
			else
				return 0;
		}
		else
			return 1;
	}
}

function makeDPPLive($profileid,$dppID,$madeLiveBy,$dppCreatedBy,$online,$presentStatus)
{
	$sql="SELECT * FROM Assisted_Product.AP_DPP_FILTER_ARCHIVE WHERE PROFILEID='$profileid' AND CREATED_BY='$dppCreatedBy' AND DPP_ID='$dppID'";
	$res=mysql_query_decide($sql) or die("Error while making dpp live   ".mysql_error_js());
	$row=mysql_fetch_assoc($res);
	
	$mysqlObj=new Mysql;
	$jpartnerObj=new Jpartner;
	
	$dbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
	$myDb=$mysqlObj->connect("$dbName");
	$jpartnerObj->setPartnerDetails($profileid,$myDb,$mysqlObj);
	$sql="UPDATE newjs.JPARTNER SET CHILDREN=\"$row[CHILDREN]\",LAGE=\"$row[LAGE]\",HAGE=\"$row[HAGE]\",LHEIGHT=\"$row[LHEIGHT]\",HHEIGHT=\"$row[HHEIGHT]\",HANDICAPPED=\"$row[HANDICAPPED]\",PARTNER_BTYPE=\"$row[PARTNER_BTYPE]\",PARTNER_CASTE=\"$row[PARTNER_CASTE]\",PARTNER_CITYRES=\"$row[PARTNER_CITYRES]\",STATE=\"$row[STATE]\",PARTNER_COUNTRYRES=\"$row[PARTNER_COUNTRYRES]\",PARTNER_DIET=\"$row[PARTNER_DIET]\",PARTNER_DRINK=\"$row[PARTNER_DRINK]\",PARTNER_ELEVEL_NEW=\"$row[PARTNER_ELEVEL_NEW]\",PARTNER_INCOME=\"$row[PARTNER_INCOME]\",PARTNER_MANGLIK=\"$row[PARTNER_MANGLIK]\",PARTNER_MSTATUS=\"$row[PARTNER_MSTATUS]\",PARTNER_MTONGUE=\"$row[PARTNER_MTONGUE]\",PARTNER_NRI_COSMO=\"$row[PARTNER_NRI_COSMO]\",PARTNER_OCC=\"$row[PARTNER_OCC]\",PARTNER_RELATION=\"$row[PARTNER_RELATION]\",PARTNER_RES_STATUS=\"$row[PARTNER_RES_STATUS]\",PARTNER_SMOKE=\"$row[PARTNER_SMOKE]\",PARTNER_COMP=\"$row[PARTNER_COMP]\",PARTNER_RELIGION=\"$row[PARTNER_RELIGION]\",PARTNER_NAKSHATRA=\"$row[PARTNER_NAKSHATRA]\",NHANDICAPPED=\"$row[NHANDICAPPED]\",DATE=NOW(),DPP='E',LINCOME=\"$row[LINCOME]\",HINCOME=\"$row[HINCOME]\",LINCOME_DOL=\"$row[LINCOME_DOL]\",HINCOME_DOL=\"$row[HINCOME_DOL]\" WHERE PROFILEID='$profileid'";
	$res=$mysqlObj->executeQuery($sql,$myDb);
	if($mysqlObj->affectedRows()==0)
	{
		$sql="INSERT IGNORE INTO newjs.JPARTNER(PROFILEID,GENDER,CHILDREN,LAGE,HAGE,LHEIGHT,HHEIGHT,HANDICAPPED,DPP,CASTE_MTONGUE,PARTNER_BTYPE,PARTNER_CASTE,PARTNER_CITYRES,STATE,PARTNER_COUNTRYRES,PARTNER_DIET,PARTNER_DRINK,PARTNER_ELEVEL_NEW,PARTNER_INCOME,PARTNER_MANGLIK,PARTNER_MSTATUS,PARTNER_MTONGUE,PARTNER_NRI_COSMO,PARTNER_OCC,PARTNER_RELATION,PARTNER_RES_STATUS,PARTNER_SMOKE,PARTNER_COMP,PARTNER_RELIGION,PARTNER_NAKSHATRA,NHANDICAPPED,DATE,LINCOME,HINCOME,LINCOME_DOL,HINCOME_DOL) VALUES('$profileid','$row[GENDER]','$row[CHILDREN]','$row[LAGE]','$row[HAGE]','$row[LHEIGHT]','$row[HHEIGHT]',\"$row[HANDICAPPED]\",'E',\"$row[CASTE_MTONGUE]\",\"$row[PARTNER_BTYPE]\",\"$row[PARTNER_CASTE]\",\"$row[PARTNER_CITYRES]\",\"$row[STATE]\",\"$row[PARTNER_COUNTRYRES]\",\"$row[PARTNER_DIET]\",\"$row[PARTNER_DRINK]\",\"$row[PARTNER_ELEVEL_NEW]\",\"$row[PARTNER_INCOME]\",\"$row[PARTNER_MANGLIK]\",\"$row[PARTNER_MSTATUS]\",\"$row[PARTNER_MTONGUE]\",\"$row[PARTNER_NRI_COSMO]\",\"$row[PARTNER_OCC]\",\"$row[PARTNER_RELATION]\",\"$row[PARTNER_RES_STATUS]\",\"$row[PARTNER_SMOKE]\",\"$row[PARTNER_COMP]\",\"$row[PARTNER_RELIGION]\",\"$row[PARTNER_NAKSHATRA]\",\"$row[NHANDICAPPED]\",NOW(),\"$row[LINCOME]\",\"$row[HINCOME]\",\"$row[LINCOME_DOL]\",\"$row[HINCOME_DOL]\")";
		$mysqlObj->executeQuery($sql,$myDb);
	}
	else
	{
		$jpartnerEditLog = new JpartnerEditLog();
		$jpartnerEditLog->logAPDppEdit($jpartnerObj,$row);
	}
	$sql="UPDATE newjs.FILTERS SET AGE='$row[AGE_FILTER]',CASTE='$row[CASTE_FILTER]',MTONGUE='$row[MTONGUE_FILTER]',RELIGION='$row[RELIGION_FILTER]',INCOME='$row[INCOME_FILTER]',CITY_RES='$row[CITY_RES_FILTER]',COUNTRY_RES='$row[COUNTRY_RES_FILTER]',MSTATUS='$row[MSTATUS_FILTER]' WHERE PROFILEID='$profileid'";
	$res=mysql_query_decide($sql) or die("Error while updating filters   ".mysql_error_js());
	if(mysql_affected_rows_js()==0)
	{
		$sql="INSERT IGNORE INTO newjs.FILTERS(PROFILEID,AGE,MSTATUS,RELIGION,CASTE,COUNTRY_RES,CITY_RES,MTONGUE,INCOME) VALUES('$profileid','$row[AGE_FILTER]','$row[MSTATUS_FILTER]','$row[RELIGION_FILTER]','$row[CASTE_FILTER]','$row[COUNTRY_RES_FILTER]','$row[CITY_RES_FILTER]','$row[MTONGUE_FILTER]','$row[INCOME_FILTER]')";
		mysql_query_decide($sql) or die("Error while inserting into filters   ".mysql_error_js());
	}
	$sql="SELECT DPP_ID,CREATED_BY,ONLINE,STATUS FROM Assisted_Product.AP_DPP_FILTER_ARCHIVE WHERE STATUS='LIVE' AND PROFILEID='$profileid'";
	$res=mysql_query_decide($sql) or die("Error while making live dpp obsolete   ".mysql_error_js());
	while($row=mysql_fetch_assoc($res))
	{
		changeDPPStatus($profileid,$madeLiveBy,$row["DPP_ID"],$row["STATUS"],'OBS',$row["ONLINE"],$row["CREATED_BY"]);
	}
	changeDPPStatus($profileid,$madeLiveBy,$dppID,$presentStatus,'LIVE',$online,$dppCreatedBy);
}
function getNumberOfTempDPPMatches($profile,$name)
{
        global $tempDPPMatches;
        global $profileid;
        global $numberOfMatches;
        global $tempDPPCreator;
        global $MEM_LOOK;
	global $whichMachine;
	global $SITE_URL;

        $tempDPPMatches=1;
        $tempDPPCreator=$name;
        $profileid=$profile;
	$MEM_LOOK =1;
	$profileChecksum = md5($profileid)."i".$profileid;
	$url = $SITE_URL."/search/partnermatches?profileChecksum=".$profileChecksum."&callingSource=ap&tempDPPCreator=".$tempDPPCreator;
	$numberOfMatches = file_get_contents($url);
	return $numberOfMatches;
}

function createTempDPP($parameters)
{
	$sql="REPLACE INTO Assisted_Product.AP_TEMP_DPP(GENDER,CHILDREN,LAGE,HAGE,LHEIGHT,HHEIGHT,HANDICAPPED,CASTE_MTONGUE,PARTNER_BTYPE,PARTNER_CASTE,PARTNER_CITYRES,STATE,PARTNER_COUNTRYRES,PARTNER_DIET,PARTNER_DRINK,PARTNER_ELEVEL_NEW,PARTNER_INCOME,PARTNER_MANGLIK,PARTNER_MSTATUS,PARTNER_MTONGUE,PARTNER_NRI_COSMO,PARTNER_OCC,PARTNER_RELATION,PARTNER_RES_STATUS,PARTNER_SMOKE,PARTNER_COMP,PARTNER_RELIGION,PARTNER_NAKSHATRA,NHANDICAPPED,AGE_FILTER,MSTATUS_FILTER,RELIGION_FILTER,CASTE_FILTER,COUNTRY_RES_FILTER,CITY_RES_FILTER,MTONGUE_FILTER,INCOME_FILTER,DATE,CREATED_BY,PROFILEID,ACTED_ON_ID,LINCOME,HINCOME,LINCOME_DOL,HINCOME_DOL) VALUES(\"$parameters[GENDER]\",\"$parameters[CHILDREN]\",\"$parameters[LAGE]\",\"$parameters[HAGE]\",\"$parameters[LHEIGHT]\",\"$parameters[HHEIGHT]\",\"$parameters[HANDICAPPED]\",\"$parameters[CASTE_MTONGUE]\",\"$parameters[PARTNER_BTYPE]\",\"$parameters[PARTNER_CASTE]\",\"$parameters[PARTNER_CITYRES]\",\"$parameters[STATE]\",\"$parameters[PARTNER_COUNTRYRES]\",\"$parameters[PARTNER_DIET]\",\"$parameters[PARTNER_DRINK]\",\"$parameters[PARTNER_ELEVEL_NEW]\",\"$parameters[PARTNER_INCOME]\",\"$parameters[PARTNER_MANGLIK]\",\"$parameters[PARTNER_MSTATUS]\",\"$parameters[PARTNER_MTONGUE]\",\"$parameters[PARTNER_NRI_COSMO]\",\"$parameters[PARTNER_OCC]\",\"$parameters[PARTNER_RELATION]\",\"$parameters[PARTNER_RES_STATUS]\",\"$parameters[PARTNER_SMOKE]\",\"$parameters[PARTNER_COMP]\",\"$parameters[PARTNER_RELIGION]\",\"$parameters[PARTNER_NAKSHATRA]\",\"$parameters[NHANDICAPPED]\",\"$parameters[AGE_FILTER]\",\"$parameters[MSTATUS_FILTER]\",\"$parameters[RELIGION_FILTER]\",\"$parameters[CASTE_FILTER]\",\"$parameters[COUNTRY_RES_FILTER]\",\"$parameters[CITY_RES_FILTER]\",\"$parameters[MTONGUE_FILTER]\",\"$parameters[INCOME_FILTER]\",NOW(),'$parameters[CREATED_BY]','$parameters[PROFILEID]','$parameters[ACTED_ON_ID]',\"$parameters[LINCOME]\",\"$parameters[HINCOME]\",\"$parameters[LINCOME_DOL]\",\"$parameters[HINCOME_DOL]\")";
	mysql_query_decide($sql) or die("Error while creating temp dpp  ".mysql_error_js());
}
?>
