<?php

global $listMainArray;

$listMainArray=array('SL','FIL','TBD','DIS','TBC');

$profileTypeArray=array("IAD"=>"Profile Ad Response",
			"IP"=>"Incoming contact [Awaiting]",
			"IA"=>"Incoming contact [Accepted]",
			"OP"=>"Outgoing contact [Evalue/offline]",
			"OA"=>"Outgoing contact [Accepted]");

function startAutoApply($profileid,$operator='',$status='BILLED')
{
        $status = 'LIVE';
	$operator=getSEBilling($profileid,$operator);

	$sql="REPLACE INTO Assisted_Product.AP_PROFILE_INFO(PROFILEID,SE,STATUS,SEND,ENTRY_DT) VALUES('$profileid','$operator','$status','Y',now())";
	mysql_query_decide($sql) or die("Error while inserting info in AP_PROFILE_INFO  ".mysql_error_js());

/*	$sql="INSERT INTO Assisted_Product.AP_ASSIGN_LOG(PROFILEID,USER,DATE) VALUES('$profileid','$operator',NOW())";
	mysql_query_decide($sql) or die("Error while inserting into AP_ASSIGN_LOG  ".mysql_error_js());

	$status='NQA';

	$parameters=array();

	$mysqlObj=new Mysql;
	$dbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
	$db=$mysqlObj->connect("$dbName");

	include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
	$jpartnerObj=new Jpartner;
	$jpartnerObj->setPartnerDetails($profileid,$db,$mysqlObj);

	if($jpartnerObj->isPartnerProfileExist($db,$mysqlObj,$profileid))
	{
		$parameters["GENDER"]=$jpartnerObj->getGENDER();
		$parameters["CHILDREN"]=$jpartnerObj->getCHILDREN();
		$parameters["LAGE"]=$jpartnerObj->getLAGE();
		$parameters["HAGE"]=$jpartnerObj->getHAGE();
		$parameters["LHEIGHT"]=$jpartnerObj->getLHEIGHT();
		$parameters["HHEIGHT"]=$jpartnerObj->getHHEIGHT();
		$parameters["CASTE_MTONGUE"]=$jpartnerObj->getCASTE_MTONGUE();
		$parameters["HANDICAPPED"]=$jpartnerObj->getHANDICAPPED();
		$parameters["PARTNER_BTYPE"]=$jpartnerObj->getPARTNER_BTYPE();
		$parameters["PARTNER_CASTE"]=$jpartnerObj->getPARTNER_CASTE();
		$parameters["PARTNER_CITYRES"]=$jpartnerObj->getPARTNER_CITYRES();
                $parameters["STATE"]=$jpartnerObj->getSTATE();
		$parameters["PARTNER_COUNTRYRES"]=$jpartnerObj->getPARTNER_COUNTRYRES();
		$parameters["PARTNER_DIET"]=$jpartnerObj->getPARTNER_DIET();
		$parameters["PARTNER_DRINK"]=$jpartnerObj->getPARTNER_DRINK();
		$parameters["PARTNER_ELEVEL_NEW"]=$jpartnerObj->getPARTNER_ELEVEL_NEW();
		$parameters["PARTNER_INCOME"]=$jpartnerObj->getPARTNER_INCOME();
		$parameters["PARTNER_MANGLIK"]=$jpartnerObj->getPARTNER_MANGLIK();
		$parameters["PARTNER_MSTATUS"]=$jpartnerObj->getPARTNER_MSTATUS();
		$parameters["PARTNER_MTONGUE"]=$jpartnerObj->getPARTNER_MTONGUE();
		$parameters["PARTNER_NRI_COSMO"]=$jpartnerObj->getPARTNER_NRI_COSMO();
		$parameters["PARTNER_OCC"]=$jpartnerObj->getPARTNER_OCC();
		$parameters["PARTNER_RELATION"]=$jpartnerObj->getPARTNER_RELATION();
		$parameters["PARTNER_RES_STATUS"]=$jpartnerObj->getPARTNER_RES_STATUS();
		$parameters["PARTNER_SMOKE"]=$jpartnerObj->getPARTNER_SMOKE();
		$parameters["PARTNER_COMP"]=$jpartnerObj->getPARTNER_COMP();
		$parameters["PARTNER_RELIGION"]=$jpartnerObj->getPARTNER_RELIGION();
		$parameters["PARTNER_NAKSHATRA"]=$jpartnerObj->getPARTNER_NAKSHATRA();
		$parameters["NHANDICAPPED"]=$jpartnerObj->getNHANDICAPPED();	
		$parameters["LINCOME"]=$jpartnerObj->getLINCOME();
		$parameters["HINCOME"]=$jpartnerObj->getHINCOME();
		$parameters["LINCOME_DOL"]=$jpartnerObj->getLINCOME_DOL();
		$parameters["HINCOME_DOL"]=$jpartnerObj->getHINCOME_DOL();
	}
	
	$sql="SELECT * FROM newjs.FILTERS WHERE PROFILEID='$profileid'";
	$res=mysql_query_decide($sql) or die("Error while fetching info from FILTERS   ".mysql_error_js());
	
	if(mysql_num_rows($res))
	{
		$row=mysql_fetch_assoc($res);

		$parameters["AGE_FILTER"]=$row["AGE"];
		$parameters["MSTATUS_FILTER"]=$row["MSTATUS"];
		$parameters["RELIGION_FILTER"]=$row["RELIGION"];
		$parameters["CASTE_FILTER"]=$row["CASTE"];
		$parameters["COUNTRY_RES_FILTER"]=$row["COUNTRY_RES"];
		$parameters["CITY_RES_FILTER"]=$row["CITY_RES"];
		$parameters["MTONGUE_FILTER"]=$row["MTONGUE"];
		$parameters["INCOME_FILTER"]=$row["INCOME"];
	}

	if(!$parameters["GENDER"])
	{
		$sqlGender="SELECT GENDER FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
		$resGender=mysql_query_decide($sqlGender) or die("Error while fetching gender   ".mysql_error_js());
		$rowGender=mysql_fetch_assoc($resGender);
		if($rowGender=="M")
			$parameters["GENDER"]="F";
		else
			$parameters["GENDER"]="M";
	}

	include_once($_SERVER['DOCUMENT_ROOT']."/jsadmin/ap_dpp_common.php");
	createDPP($parameters,$profileid,$operator,'SE',$status);*/

}
function addAutoApplyLog($profileid,$type,$v='')
{
	$str =$type."-".$v;
        $sql="INSERT INTO Assisted_Product.AP_PROFILE_INFO_DEBUG(PROFILEID,TYPE,ENTRY_DT) VALUES('$profileid','$str',now())";
        mysql_query_decide($sql) or die("Error while inserting info in AP_PROFILE_INFO_DEBUG  ".mysql_error_js());
}

function startHomeDelivery($profileid,$city)
{
	$flag=0;
	$serviceCycle=15;
	$sql="SELECT STATUS FROM Assisted_Product.AP_PROFILE_INFO WHERE PROFILEID='$profileid'";
	$res=mysql_query_decide($sql) or die("Error while starting home delivery   ".mysql_error_js());
	if(mysql_num_rows($res)==0)
		$flag=1;
	else
	{
		$row=mysql_fetch_assoc($res);
		if($row["STATUS"]=="LIVE")
			$flag=1;
	}
	if($flag)
	{
		if($city=='')
		{
			$sql="SELECT CITY_RES FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
			$res=mysql_query_decide($sql) or die("Error while selecting city   ".mysql_error_js());
			$row=mysql_fetch_assoc($res);
			$city=$row["CITY_RES"];
		}
		$sql="REPLACE INTO Assisted_Product.AP_SERVICE_TABLE(PROFILEID,CITY,NEXT_SERVICE_DATE) VALUES('$profileid','$city',DATE_ADD(CURDATE(),INTERVAL $serviceCycle DAY))";
		mysql_query_decide($sql) or die(mysql_error_js());
	}
	
}

function endAutoApply($profileid)
{
	$sql="DELETE FROM Assisted_Product.AP_PROFILE_INFO WHERE PROFILEID='$profileid'";
	mysql_query_decide($sql) or die("Error while updating AP_PROFLE_INFO  ".mysql_error_js());

	/*$sql="SELECT DPP_ID,STATUS,CREATED_BY,ONLINE FROM Assisted_Product.AP_DPP_FILTER_ARCHIVE WHERE STATUS!='OBS' AND PROFILEID='$profileid'";
	$res=mysql_query_decide($sql) or die("Error while selecting dpp   ".mysql_error_js());
	while($row=mysql_fetch_assoc($res))
	{
		include_once($_SERVER['DOCUMENT_ROOT']."/jsadmin/ap_dpp_common.php");
		changeDPPStatus($profileid,'END_OF_SERVICE',$row["DPP_ID"],$row["STATUS"],'OBS',$row["ONLINE"],$row["CREATED_BY"]);
	}*/
}

function endHomeDelivery($profileid)
{
	$sql="DELETE FROM Assisted_Product.AP_SERVICE_TABLE WHERE PROFILEID='$profileid'";
	mysql_query_decide($sql) or die("Error while updating AP_SERVICE_TABLE  ".mysql_error_js());

	$sql="UPDATE Assisted_Product.AP_MISSED_SERVICE_LOG SET COMPLETED='N' WHERE COMPLETED='' AND PROFILEID='$profileid'";
	mysql_query_decide($sql) or die("Error while updating AP_MISSED_SERVICE_LOG  ".mysql_error_js());
}
function endIntroCalls($profileid)
{
	$sql="UPDATE Assisted_Product.AP_CALL_HISTORY SET CALL_STATUS='C' WHERE PROFILEID='$profileid'";
	mysql_query_decide($sql) or die("Error while updating AP_CALL_HISTORY  ".mysql_error_js());
	//$sql="UPDATE Assisted_Product.AP_CALL_HISTORY SET CALL_STATUS='C' WHERE PROFILEID='$profileid' AND CALL_STATUS='Y'";
	//mysql_query_decide($sql) or die("Error while updateing AP_CALL_HISTORY  ".mysql_error_js());
}
function fetchDashboard($name,$pagination=1,$PAGELEN=10,$start=0,$active=1,$role='SE',$count=0)
{
	switch($role)
	{
		case 'SE': 
			if($active){
				if($count)
				{
					$sql="SELECT COUNT(*) AS COUNT FROM Assisted_Product.AP_PROFILE_INFO WHERE SE='$name'";
				}
				else
				{
					$sql="SELECT PROFILEID,STATUS FROM Assisted_Product.AP_PROFILE_INFO WHERE SE='$name'";
				}
				$sql.=" AND STATUS IN('BILLED','LIVE','SCREENING')";
			}
			else{
                                if($count)
                                {

                                        $sql="SELECT COUNT(*) AS COUNT FROM billing.SERVICE_STATUS as b,Assisted_Product.AP_ASSIGN_LOG AS a WHERE b.PROFILEID=a.PROFILEID AND a.USER='$name'";
					//$sql ="SELECT COUNT(*) AS COUNT from Assisted_Product.AP_ASSIGN_LOG AS a LEFT JOIN Assisted_Product.AP_PROFILE_INFO AS p ON a.PROFILEID=p.PROFILEID WHERE p.PROFILEID IS NULL AND a.USER='$name'";
                                }
                                else
                                {
					$sql="SELECT a.PROFILEID FROM billing.SERVICE_STATUS as b,Assisted_Product.AP_ASSIGN_LOG AS a WHERE b.PROFILEID=a.PROFILEID AND a.USER='$name'";
					//$sql ="SELECT a.PROFILEID from Assisted_Product.AP_ASSIGN_LOG AS a LEFT JOIN Assisted_Product.AP_PROFILE_INFO AS p ON a.PROFILEID=p.PROFILEID WHERE p.PROFILEID IS NULL AND a.USER='$name'";
                                }
				$sql .=" AND b.ACTIVE='E' AND SERVEFOR IN('L','T','I')";
			}
			if($pagination && !$count)
				$sql.=" LIMIT $start,$PAGELEN";
			break;

		case 'OP_HEAD': 
			if($count)
			{
				$sql="SELECT COUNT(*) AS COUNT FROM Assisted_Product.AP_MISSED_SERVICE_LOG WHERE COMPLETED=''";
			}
			else
			{
				$sql="SELECT PROFILEID FROM Assisted_Product.AP_MISSED_SERVICE_LOG WHERE COMPLETED=''";
			}
			break;
	}
	if($sql)
	{
		if($count)
		{
			$res=mysql_query_decide($sql) or die("Error while fetching dashboard count   ".mysql_error_js());
			$row=mysql_fetch_assoc($res);
			return $row["COUNT"];
		}
		else
		{
			$res=mysql_query_decide($sql) or die("Error while fetching dashboard information  ".mysql_error_js());
			return $res;
		}
	}
}

//Function to get number of profiles in the shorlist/filtered/to be dispatched/dispatched
function getNumberInList($profileArray,$listArray)
{
	global $noOfActiveServers,$contactsCountArray,$leadsCountArray;
	if(is_array($profileArray) && ($listArray))
	{
		for($i=0;$i<count($profileArray);$i++)
		{
			$dbName=getProfileDatabaseConnectionName($profileArray[$i]);
			$dbNameArray[$dbName][]=$profileArray[$i];
		}
		$lists=implode("','",$listArray);

		foreach($dbNameArray as $key=>$value)
		{
			$mysqlObj=new Mysql;
			$db=$mysqlObj->connect("$key");
			$profiles=implode("','",$value);

			$sql="SELECT COUNT(*) AS COUNT,SENDER AS PROFILEID,FOLDER FROM newjs.CONTACTS WHERE SENDER IN('$profiles') AND FOLDER IN('$lists') GROUP BY SENDER,FOLDER UNION ALL SELECT COUNT(*) AS COUNT,RECEIVER AS PROFILEID,FOLDER FROM newjs.CONTACTS WHERE RECEIVER IN('$profiles') AND FOLDER IN('$lists') GROUP BY RECEIVER,FOLDER";	
			//$sql="SELECT COUNT(*) AS COUNT FROM newjs.CONTACTS WHERE SENDER IN('$profiles') UNION SELECT COUNT(*) AS COUNT FROM newjs.CONTACTS WHERE RECEIVER IN('$profiles')";
			$res=$mysqlObj->executeQuery($sql,$db);
			while($row=$mysqlObj->fetchAssoc($res))
			{
				$valueArray[$row["PROFILEID"]][$row["FOLDER"]]+=$row["COUNT"];
				$contactsCountArray[$row["PROFILEID"]][$row["FOLDER"]]+=$row["COUNT"];
			}
			unset($profiles);
		}
		unset($dbNameArray);

		$profiles=implode("','",$profileArray);

		if(in_array("TBC",$listArray))
		{
			$sql="SELECT COUNT(*) AS COUNT,PROFILEID FROM Assisted_Product.AP_CALL_HISTORY WHERE PROFILEID IN('$profiles') AND CALL_STATUS!='C' GROUP BY PROFILEID";
			$res=mysql_query_decide($sql);
			while($row=mysql_fetch_assoc($res))
				$valueArray[$row["PROFILEID"]]["TBC"]=$row["COUNT"];
			
		}

		//Fetching lead counts
		$db = connect_slave();
		$sql="SELECT PROFILEID,USERNAME FROM newjs.JPROFILE WHERE PROFILEID IN('$profiles')";
		$res=mysql_query_decide($sql,$db) or die("Error while fetching usernames  ".mysql_error_js());
		while($row=mysql_fetch_assoc($res))
		{
			$usernamePIDArray[$row["USERNAME"]]=$row["PROFILEID"];
			$usernames .="'".$row["USERNAME"]."',";
		}
		$usernames=trim($usernames,",");
		$sql="SELECT COUNT(*) AS COUNT,campaigns_cstm.username_c AS USERNAME,leads_cstm.folder_c AS FOLDER FROM sugarcrm.campaigns_cstm JOIN sugarcrm.leads ON campaigns_cstm.id_c = sugarcrm.leads.campaign_id JOIN sugarcrm.leads_cstm ON id = leads_cstm.id_c WHERE campaigns_cstm.username_c IN($usernames) AND leads.deleted!=1 AND leads_cstm.jsprofileid_c='' GROUP BY campaigns_cstm.username_c,leads_cstm.folder_c";
		$res=mysql_query_decide($sql,$db) or die("Error while fetching lead counts   ".mysql_error_js());
		while($row=mysql_fetch_assoc($res))
		{
			$PID=$usernamePIDArray[$row["USERNAME"]];
			$valueArray[$PID][$row["FOLDER"]]+=$row["COUNT"];
			$leadsCountArray[$PID][$row["FOLDER"]]=$row["COUNT"];
		}
		//Fetch called profiles
		$sql="SELECT COUNT(*) AS COUNT,PROFILEID,FOLDER FROM Assisted_Product.AP_CALL_HISTORY WHERE PROFILEID IN('$profiles') AND FOLDER IN('$lists') AND CALL_STATUS!='C' GROUP BY PROFILEID,FOLDER";
		$res=mysql_query_decide($sql,$db) or die("Error while fetching call counts  ".mysql_error_js());
		while($row=mysql_fetch_assoc($res))
		{
			if(in_array("TBC",$listArray))
			{
				if($row["FOLDER"]!="TBC")
					$valueArray[$row["PROFILEID"]][$row["FOLDER"]]+=$row["COUNT"];
			}
			else
				$valueArray[$row["PROFILEID"]][$row["FOLDER"]]+=$row["COUNT"];
		}
		return $valueArray;
	}
}

function getList($profileid,$list,$contactArray='',$leadArray='',$username='',$profile_start='',$PAGELEN='',$pagination=0)
{
	$mysqlObj=new Mysql;
	$dbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
	$myDb=$mysqlObj->connect("$dbName");
	$contacts=$contactArray[$list];		
	$leads=$leadArray[$list];
	if(!$contacts)
		$contacts=0;
	if(!$leads)
		$leads=0;
	if($list=='TBC')
	{
		$sql="SELECT PROFILEID,MATCH_ID,CALL_STATUS FROM Assisted_Product.AP_CALL_HISTORY WHERE PROFILEID='$profileid' AND CALL_STATUS!='C'";
		if($pagination)
			$sql.=" LIMIT $profile_start,$PAGELEN";
		$res=mysql_query_decide($sql) or die("Error while fetching call history ".mysql_error_js());
	}
	else
	{
		$sql="SELECT SENDER AS PROFILEID,RECEIVER AS MATCH_ID,TIME,TYPE,FILTERED,1 AS DIR FROM newjs.CONTACTS WHERE SENDER IN('$profileid') AND FOLDER IN('$list') UNION ALL SELECT RECEIVER AS PROFILEID,SENDER AS MATCH_ID,TIME,TYPE,FILTERED,2 AS DIR FROM newjs.CONTACTS WHERE RECEIVER IN('$profileid') AND FOLDER IN('$list')";
		//$sql="SELECT SENDER AS PROFILEID,RECEIVER AS MATCH_ID,TIME,TYPE,FILTERED,1 AS DIR FROM newjs.CONTACTS WHERE SENDER IN('$profileid') UNION SELECT RECEIVER AS PROFILEID,SENDER AS MATCH_ID,TIME,TYPE,FILTERED,2 AS DIR FROM newjs.CONTACTS WHERE RECEIVER IN('$profileid')";
		if($pagination)
			$sql.=" LIMIT $profile_start,$PAGELEN";
		$res=$mysqlObj->executeQuery($sql,$myDb) or die($mysqlObj->error());
	}
	while($row=mysql_fetch_assoc($res))
	{
		$disable=0;
		if($row["DIR"]==1)
		{
			if($row["TYPE"]=='I')
				$matchType='OP';
			if($row["TYPE"]=='A')
				$matchType='OA';
		}
		elseif($row["DIR"]==2)
		{
			if($row["FILTERED"]=="Y")
				$matchType='IF';
			else
			{
				if($row["TYPE"]=="I")
					$matchType='IP';
				if($row["TYPE"]=="A")
					$matchType='IA';
			}
		}
		if($list=="TBC")
		{
			if($row["CALL_STATUS"]=="Y")
				$disable=1;
		}
		$valueArray[]=array("PROFILEID"=>$row["MATCH_ID"],
				"DATE"=>$row["DATE"],
				"MATCH_TYPE"=>$matchType,
				"CHECKBOX_ID"=>$row["MATCH_ID"],
				"DISABLE"=>$disable);
	}
	$moreProfiles=0;	
	if($pagination)
	{
		if(count($valueArray))
		{
			if(count($valueArray)<$PAGELEN)
			{
				$moreProfiles=1;
				$newProfileStart=0;
				$newPAGELEN=$PAGELEN-count($valueArray);
			}
		}
		else
		{
			$moreProfiles=1;
			if($contacts)
				$newProfileStart=$profile_start-$contacts;
			else
				$newProfileStart=0;
			$newPAGELEN=$PAGELEN;
		}
	}
	else
		$moreProfiles=1;
	if($moreProfiles)
	{
		if($list=='SL' || $list=='TBD' || $list=='DIS')
		{
			$db = connect_slave();
			$sql="SELECT $profileid AS PROFILEID,leads_cstm.id_c AS MATCH_ID,NOW() AS TIME,'' AS TYPE,'' AS FILTERED,3 AS DIR FROM sugarcrm.campaigns_cstm JOIN sugarcrm.leads ON sugarcrm.campaigns_cstm.id_c = sugarcrm.leads.campaign_id JOIN sugarcrm.leads_cstm ON id = leads_cstm.id_c WHERE campaigns_cstm.username_c = '$username' AND leads_cstm.folder_c = '$list' AND leads.deleted!='1' AND leads_cstm.jsprofileid_c=''";
			if($pagination)
				$sql.="	LIMIT $newProfileStart,$newPAGELEN";
			$res=mysql_query_decide($sql,$db) or die("Error while fetching leads   ".mysql_error_js());
			while($row=mysql_fetch_assoc($res))
			{
				$valueArray[]=array("LEAD_ID"=>$row["MATCH_ID"],
							"CHECKBOX_ID"=>"LEAD_".$row["MATCH_ID"],
							"MATCH_TYPE"=>"IAD");
			}
		}
	}
	$moreProfiles=0;
	if($pagination)
	{
		if(count($valueArray))
		{
			if(count($valueArray)<$PAGELEN)
			{
				$moreProfiles=1;
				$newProfileStart=0;
				$newPAGELEN=$PAGELEN-count($valueArray);
			}
		}
		else
		{
			$moreProfiles=1;
			if($contacts || $leads)
				$newProfileStart=$profile_start-$contacts-$leads;
			else
				$newProfileStart=0;
			$newPAGELEN=$PAGELEN;
		}
	}
	else
		$moreProfiles=1;
        if($moreProfiles)
        {
                if($list=='TBD' || $list=='DIS')
                {
			$sql="SELECT PROFILEID,MATCH_ID,CALL_STATUS FROM Assisted_Product.AP_CALL_HISTORY WHERE PROFILEID='$profileid' AND FOLDER='$list' AND CALL_STATUS!='C'";
			if($pagination)
				$sql.=" LIMIT $newProfileStart,$newPAGELEN";
                        $res=mysql_query_decide($sql) or die("Error while fetching calls   ".mysql_error_js());
                        while($row=mysql_fetch_assoc($res))
                        {
                                $valueArray[]=array("PROFILEID"=>$row["MATCH_ID"],
                                                        "CHECKBOX_ID"=>"CALL_".$row["MATCH_ID"]);
                        }
                }
        }
	return $valueArray;
}

function getNextServiceDate($profiles)
{
	if($profiles)
	{
		$sql="SELECT PROFILEID,NEXT_SERVICE_DATE FROM Assisted_Product.AP_SERVICE_TABLE WHERE PROFILEID IN('$profiles')";
		$res=mysql_query_decide($sql) or die("Error while fetching service dates  ".mysql_error_js());
		while($row=mysql_fetch_assoc($res))
		$serviceArray[$row["PROFILEID"]]=$row["NEXT_SERVICE_DATE"];
		return $serviceArray;
	}
}

function getSEBilling($profileid,$operator)
{
	$defaultSE="default.se";
	$sql="SELECT EXECUTIVE FROM newjs.OFFLINE_REGISTRATION WHERE PROFILEID='$profileid'";
	$res=mysql_query_decide($sql) or die("Error while fetching executive name from OFFLINE_REGISTRATION   ".mysql_error_js());
	if(mysql_num_rows($res))
	{
		$row=mysql_fetch_assoc($res);
		$reg_operator=$row["EXECUTIVE"];
	}

	if($reg_operator || $operator)
	{
		$sql="SELECT USERNAME,PRIVILAGE FROM jsadmin.PSWRDS WHERE USERNAME IN";
		if($reg_operator && $operator)
			$sql.="('$reg_operator','$operator')";
		elseif($reg_operator)
			$sql.="('$reg_operator')";
		else
			$sql.="('$operator')";
		$sql.=" AND ACTIVE='Y'";
		$res=mysql_query_decide($sql) or die("Error while fetching privilages  ".mysql_error_js());
		if(mysql_num_rows($res))
		{
			while($row=mysql_fetch_assoc($res))
			{
				if($row["USERNAME"]==$reg_operator)
				{
					$privArray=explode("+",$row["PRIVILAGE"]);
					if(@in_array("SE",$privArray))
						return $reg_operator;
					else
						$defaultFlag=1;
				}
				if($row["USERNAME"]==$operator)
				{
					$privArray=explode("+",$row["PRIVILAGE"]);
					if(@in_array("SE",$privArray))
						return $operator;
					else
						$defaultFlag=1;
				}
				unset($privArray);
			}
		}
		else
			return $defaultSE;
		if($defaultFlag)
			return $defaultSE;
	}
	else
		return $defaultSE;	
}

function fetchRole($cid)
{
	$privilage=getprivilage($cid);
	$privArray=explode("+",$privilage);
	foreach($privArray as $key=>$value)
	{
		switch($value)
		{
			case "QA" : return "QA";
			case "DIS": return "DIS";
			case "TC" : return "TC";
			case "SE" : return "SE";
		}
	}
	
}

function getSE($profileid)
{
	if(is_array($profileid))
		$profiles=implode("','",$profileid);
	elseif($profileid)
		$profiles=$profileid;
	if($profiles)
	{
		$sql="SELECT SE,PROFILEID FROM Assisted_Product.AP_PROFILE_INFO WHERE PROFILEID IN('$profiles')";
		$res=mysql_query_decide($sql) or die("Error while fetching SE names   ".mysql_error_js());
		while($row=mysql_fetch_assoc($res))
		{
			$SEArray[$row["PROFILEID"]]=$row["SE"];
		}
		return $SEArray;
		
	}
}

function makeProfileLive($profileid,$city_res='',$sub='',$fromScreening='')
{
	if($fromScreening)
        {
                if(!$city_res || !$subscription)
                {
                        $sql="SELECT CITY_RES,SUBSCRIPTION FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
                        $res=mysql_query_decide($sql) or die("Error while fetching screening info   ".mysql_error_js());
                        $row=mysql_fetch_assoc($res);
                        $city_res=$row["CITY_RES"];
                        $sub=$row["SUBSCRIPTION"];
                }
                if(strstr($sub,"T"))
                {
                        $sql2="SELECT STATUS FROM Assisted_Product.AP_PROFILE_INFO WHERE PROFILEID='$profileid'";
                        $res2=mysql_query_decide($sql2) or die("Error while fetching screening info   ".mysql_error_js());
                        $row2=mysql_fetch_assoc($res2);
                        if($row2["STATUS"]=="SCREENING")
                                $status="LIVE";
                }
        }
        else
        {
                $sql="SELECT SCREENING,CITY_RES,SUBSCRIPTION FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
                $res=mysql_query_decide($sql) or die("Error while fetching screening info   ".mysql_error_js());
                $row=mysql_fetch_assoc($res);
                if($row['SCREENING']=='1099511627775')
                        $status="LIVE";
                else
                        $status="SCREENING";
                $sub=$row["SUBSCRIPTION"];
        }
	if($status)
        {
                $sql="UPDATE Assisted_Product.AP_PROFILE_INFO SET STATUS='$status'";
                if($status=="LIVE")
                        $sql.=",LIVE_ON=NOW()";
                $sql.=" WHERE PROFILEID='$profileid'";
                $res=mysql_query_decide($sql) or die("Error while updating profile info   ".mysql_error_js());
        }
        if($status=="LIVE")
        {
                if($sub)
                {
                        $subArray=explode(",",$sub);
                        if(in_array("L",$subArray))
                                startHomeDelivery($profileid,$row["CITY_RES"]);
                }
        }
}

function fetchNextProfile($role,$name,$new=1,$cities='',$submittedProfile='',$callreq_pid='',$qtype)
{
	switch($role)
	{
		case 'QA' :
			$sql =" SELECT AP_DPP_FILTER_ARCHIVE.PROFILEID from Assisted_Product.AP_DPP_FILTER_ARCHIVE,Assisted_Product.AP_QUEUE WHERE AP_DPP_FILTER_ARCHIVE.PROFILEID=AP_QUEUE.PROFILEID AND AP_QUEUE.ASSIGNED_TO='$name' AND AP_QUEUE.SUBMIT_TIME='0000-00-00 00:00:00'"; 
			if($new==1)
				$sql.=" AND AP_QUEUE.ASSIGNED_FOR='NQA' AND AP_DPP_FILTER_ARCHIVE.STATUS = 'NQA'";
			else
				$sql.=" AND AP_QUEUE.ASSIGNED_FOR='RQA' AND AP_DPP_FILTER_ARCHIVE.STATUS = 'RQA'";
			$res=mysql_query_decide($sql) or die("Error while fetching profile for QA   ".mysql_error_js());
			if(mysql_num_rows($res))
			{
				$row=mysql_fetch_assoc($res);
				return $row;
			}
			else
			{
				$sql="SELECT AP_DPP_FILTER_ARCHIVE.PROFILEID,AP_QUEUE.ASSIGNED_TO FROM Assisted_Product.AP_DPP_FILTER_ARCHIVE,Assisted_Product.AP_QUEUE WHERE AP_DPP_FILTER_ARCHIVE.PROFILEID=AP_QUEUE.PROFILEID AND ASSIGN_TIME < DATE_SUB(NOW(), INTERVAL 30 MINUTE)";
				if($new==1)
                	                $sql.=" AND AP_QUEUE.ASSIGNED_FOR='NQA' AND AP_DPP_FILTER_ARCHIVE.STATUS = 'NQA'";
	                        else
        	                        $sql.=" AND AP_QUEUE.ASSIGNED_FOR='RQA' AND AP_DPP_FILTER_ARCHIVE.STATUS = 'RQA'";
				$sql.=" ORDER BY ASSIGN_TIME ASC";
				$res=mysql_query_decide($sql) or die("Error while fetching profile for QA   ".mysql_error_js());
				if(mysql_num_rows($res))
				{
					while($row=mysql_fetch_assoc($res))
					{
						$pid=$row["PROFILEID"];
						deleteTemporaryDPP($row["PROFILEID"],$row["ASSIGNED_TO"]);
						$sqlUpdate="UPDATE Assisted_Product.AP_QUEUE SET ASSIGNED_TO='$name',ASSIGN_TIME=NOW() WHERE PROFILEID='$pid' AND ASSIGNED_TO='$row[ASSIGNED_TO]'";
						if($new==1)
							$sqlUpdate.=" AND ASSIGNED_FOR='NQA'";
						else
							$sqlUpdate.=" AND ASSIGNED_FOR='RQA'";
						mysql_query_decide($sqlUpdate) or die("Error while assigning profile to QA   ".mysql_error_js());
						if(mysql_affected_rows_js())
						{
							return $row;
						}
						else
							$stop=0;							
					}
				}
				else
					$stop=0;
				if(!$stop)
				{
					$sql="SELECT Assisted_Product.AP_DPP_FILTER_ARCHIVE.PROFILEID FROM Assisted_Product.AP_DPP_FILTER_ARCHIVE LEFT JOIN Assisted_Product.AP_QUEUE ON Assisted_Product.AP_DPP_FILTER_ARCHIVE.PROFILEID = Assisted_Product.AP_QUEUE.PROFILEID LEFT JOIN Assisted_Product.AP_QA_SKIPPED_RECORDS ON AP_DPP_FILTER_ARCHIVE.PROFILEID=AP_QA_SKIPPED_RECORDS.PROFILEID WHERE Assisted_Product.AP_QA_SKIPPED_RECORDS.PROFILEID IS NULL AND (Assisted_Product.AP_QUEUE.PROFILEID IS NULL";
					if($new==1)
        	                                $sql.="  OR AP_QUEUE.ASSIGNED_FOR!='NQA') AND AP_DPP_FILTER_ARCHIVE.STATUS = 'NQA'";
	                                else
                	                        $sql.=" OR AP_QUEUE.ASSIGNED_FOR!='RQA') AND AP_DPP_FILTER_ARCHIVE.STATUS = 'RQA'";
					$sql.=" ORDER BY DATE ASC";
					$res=mysql_query_decide($sql) or die("Error while assigning profile to QA   ".mysql_error_js());
					if(mysql_num_rows($res))
					{	while($row=mysql_fetch_assoc($res))
						{
							$pid=$row["PROFILEID"];
							if($new==1)
								$loggedFor='NQA';
							else
								$loggedFor='RQA';
							$sqlUpdate="INSERT IGNORE INTO Assisted_Product.AP_QUEUE(PROFILEID,ASSIGNED_TO,ASSIGN_TIME,ASSIGNED_FOR) VALUES('$pid','$name',NOW(),'$loggedFor')";
							mysql_query_decide($sqlUpdate) or die("Error while assigning profile to QA   ".mysql_error_js());
							if(mysql_affected_rows_js())
							{
								return $row;
							}
						}
					}	
					else
					{
                                                $sql="SELECT Assisted_Product.AP_DPP_FILTER_ARCHIVE.PROFILEID FROM Assisted_Product.AP_DPP_FILTER_ARCHIVE LEFT JOIN Assisted_Product.AP_QUEUE ON Assisted_Product.AP_DPP_FILTER_ARCHIVE.PROFILEID = Assisted_Product.AP_QUEUE.PROFILEID LEFT JOIN Assisted_Product.AP_QA_SKIPPED_RECORDS ON AP_DPP_FILTER_ARCHIVE.PROFILEID=AP_QA_SKIPPED_RECORDS.PROFILEID WHERE Assisted_Product.AP_QA_SKIPPED_RECORDS.PROFILEID!='' AND (Assisted_Product.AP_QUEUE.PROFILEID IS NULL";
                                                if($new==1)
                                                        $sql.="  OR AP_QUEUE.ASSIGNED_FOR!='NQA') AND AP_DPP_FILTER_ARCHIVE.STATUS = 'NQA'";
                                                else
                                                        $sql.=" OR AP_QUEUE.ASSIGNED_FOR!='RQA') AND AP_DPP_FILTER_ARCHIVE.STATUS = 'RQA'";
                                                $sql.=" ORDER BY SKIPPED_ON ASC LIMIT 1";
                                                $res=mysql_query_decide($sql) or die("Error while assigning profile to QA   ".mysql_error_js());
                                                if(mysql_num_rows($res))
                                                {       while($row=mysql_fetch_assoc($res))
                                                        {
                                                                $pid=$row["PROFILEID"];
                                                                if($new==1)
                                                                        $loggedFor='NQA';
                                                                else
                                                                        $loggedFor='RQA';
                                                                $sqlUpdate="INSERT IGNORE INTO Assisted_Product.AP_QUEUE(PROFILEID,ASSIGNED_TO,ASSIGN_TIME,ASSIGNED_FOR) VALUES('$pid','$name',NOW(),'$loggedFor')";
								mysql_query_decide($sqlUpdate) or die("Error while assigning profile to QA   ".mysql_error_js());
                                                                if(mysql_affected_rows_js())
                                                                {
                                                                        return $row;
                                                                }
                                                        }
                                                }
                                                else
                                                        return 0;
                                        }
					return 0;
				}
			}
			break;
		case 'DIS' :
			$sql ="SELECT AP_SERVICE_TABLE.PROFILEID from Assisted_Product.AP_SERVICE_TABLE JOIN Assisted_Product.AP_QUEUE ON AP_SERVICE_TABLE.PROFILEID=AP_QUEUE.PROFILEID WHERE NEXT_SERVICE_DATE=CURDATE() AND AP_QUEUE.ASSIGNED_TO='$name' AND AP_QUEUE.SUBMIT_TIME='0000-00-00 00:00:00' AND AP_QUEUE.ASSIGNED_FOR='DIS' AND AP_SERVICE_TABLE.SERVICED=''";
			if($submittedProfile)
				$sql.=" AND AP_SERVICE_TABLE.PROFILEID!='$submittedProfile'";
                        $res=mysql_query_decide($sql,$db) or die("Error while fetching profile for dispatcher   ".mysql_error_js());
                        if(mysql_num_rows($res))
                        {
                                $row=mysql_fetch_assoc($res);
                                return $row;
                        }
			else
			{
				$sql="SELECT AP_MISSED_SERVICE_LOG.PROFILEID from Assisted_Product.AP_MISSED_SERVICE_LOG JOIN Assisted_Product.AP_QUEUE ON AP_MISSED_SERVICE_LOG.PROFILEID=AP_QUEUE.PROFILEID WHERE AP_QUEUE.ASSIGNED_TO='$name' AND AP_QUEUE.SUBMIT_TIME='0000-00-00 00:00:00' AND AP_QUEUE.ASSIGNED_FOR='DIS' AND COMPLETED=''";
				if($submittedProfile)
					$sql.=" AND AP_MISSED_SERVICE_LOG.PROFILEID!='$submittedProfile'";
				$sql.=" ORDER BY MISSED_SERVICE_DATE ASC LIMIT 1";
				$res=mysql_query_decide($sql,$db) or die("Error while fetching profile for dispatcher   ".mysql_error_js());
				if(mysql_num_rows($res))
				{
					$row=mysql_fetch_assoc($res);
					return $row;
				}
				else
				{
					if($cities)
					{
						$sql ="SELECT AP_SERVICE_TABLE.PROFILEID FROM Assisted_Product.AP_SERVICE_TABLE LEFT JOIN Assisted_Product.AP_QUEUE ON AP_SERVICE_TABLE.PROFILEID = AP_QUEUE.PROFILEID WHERE (AP_QUEUE.PROFILEID IS NULL OR AP_QUEUE.ASSIGNED_FOR!='DIS') AND NEXT_SERVICE_DATE=CURDATE() AND AP_SERVICE_TABLE.SERVICED=''";
						if($submittedProfile)
							$sql.=" AND AP_SERVICE_TABLE.PROFILEID!='$submittedProfile'";
					}
					else
					{
						$sql="SELECT AP_SERVICE_TABLE.PROFILEID FROM Assisted_Product.AP_SERVICE_TABLE LEFT JOIN Assisted_Product.AP_QUEUE ON AP_SERVICE_TABLE.PROFILEID=AP_QUEUE.PROFILEID WHERE NEXT_SERVICE_DATE=CURDATE() AND (AP_QUEUE.PROFILEID IS NULL OR ASSIGNED_FOR!='DIS') AND AP_SERVICE_TABLE.SERVICED=''";
						if($submittedProfile)
						       $sql.=" AND AP_SERVICE_TABLE.PROFILEID!='$submittedProfile'";
					}
					//$dbslave=connect_slave();
					$res=mysql_query_decide($sql,$db) or die("Error while assigning profile to dispatcher   ".mysql_error_js());
					//$db=connect_db();
					if(mysql_num_rows($res))
					{       
						while($row=mysql_fetch_assoc($res))
						{
							$pid=$row["PROFILEID"];
							$loggedFor='DIS';
							$sqlUpdate="INSERT INTO Assisted_Product.AP_QUEUE(PROFILEID,ASSIGNED_TO,ASSIGN_TIME,ASSIGNED_FOR) VALUES('$pid','$name',NOW(),'$loggedFor')";
							mysql_query_decide($sqlUpdate,$db) or die("Error while assigning profile to dispatcher   ".mysql_error_js());
							if(mysql_affected_rows_js())
							{
								return $row;
							}
						}
					}
					else
					{
						if($cities)
						{
							$sql="SELECT AP_MISSED_SERVICE_LOG.PROFILEID FROM Assisted_Product.AP_MISSED_SERVICE_LOG LEFT JOIN Assisted_Product.AP_QUEUE ON AP_MISSED_SERVICE_LOG.PROFILEID = AP_QUEUE.PROFILEID WHERE (AP_QUEUE.PROFILEID IS NULL OR AP_QUEUE.ASSIGNED_FOR!='DIS') AND COMPLETED=''";
							if($submittedProfile)
								$sql.=" AND AP_MISSED_SERVICE_LOG.PROFILEID!='$submittedProfile'";
							$sql.=" ORDER BY MISSED_SERVICE_DATE ASC LIMIT 1";
						}
						else
						{
							$sql="SELECT AP_MISSED_SERVICE_LOG.PROFILEID FROM Assisted_Product.AP_MISSED_SERVICE_LOG LEFT JOIN Assisted_Product.AP_QUEUE ON AP_MISSED_SERVICE_LOG.PROFILEID = AP_QUEUE.PROFILEID WHERE (AP_QUEUE.PROFILEID IS NULL OR AP_QUEUE.ASSIGNED_FOR!='DIS') AND COMPLETED=''";
							if($submittedProfile)
								$sql.=" AND AP_MISSED_SERVICE_LOG.PROFILEID!='$submittedProfile'";
							$sql.=" ORDER BY MISSED_SERVICE_DATE ASC LIMIT 1";
						}
						//$dbslave=connect_slave();
						$res=mysql_query_decide($sql,$db) or die("Error while assigning profile to dispatcher   ".mysql_error_js());
						//$db=connect_db();
						if(mysql_num_rows($res))
						{       while($row=mysql_fetch_assoc($res))
							{
								$pid=$row["PROFILEID"];
								$loggedFor='DIS';
								$sqlUpdate="INSERT INTO Assisted_Product.AP_QUEUE(PROFILEID,ASSIGNED_TO,ASSIGN_TIME,ASSIGNED_FOR) VALUES('$pid','$name',NOW(),'$loggedFor')";
								mysql_query_decide($sqlUpdate,$db) or die("Error while assigning profile to dispatcher   ".mysql_error_js());
								if(mysql_affected_rows_js())
								{
									return $row;
								}
							}
						}
						else
							return 0;
					}
					return 0;
				}
				return 0;
			}
			break;
		 case 'TC' :
			//Remove all deleted and expired from AP_CALL_HISTORY table
			$sql_check="SELECT jp.PROFILEID as pid, jp.SUBSCRIPTION, jp.ACTIVATED, jp.USERNAME FROM newjs.JPROFILE AS jp, Assisted_Product.AP_CALL_HISTORY AS ap WHERE ap.PROFILEID = jp.PROFILEID AND ( jp.SUBSCRIPTION NOT LIKE  '%i%' OR jp.ACTIVATED =  'D' ) AND ap.CALL_STATUS =  'N'";
			$res_check=mysql_query_decide($sql_check) or die("Error while fetching profile for TC   ".mysql_error_js());
			while($row_check=mysql_fetch_assoc($res_check))
			{
				$rProfileids[$row_check[pid]]=$row_check[pid];
			}
			$sql_check="SELECT ap.MATCH_ID as pid, jp.PROFILEID, jp.SUBSCRIPTION, jp.ACTIVATED, jp.USERNAME FROM newjs.JPROFILE AS jp, Assisted_Product.AP_CALL_HISTORY AS ap WHERE ap.MATCH_ID = jp.PROFILEID AND (jp.ACTIVATED =  'D') AND ap.CALL_STATUS =  'N'";
                        $res_check=mysql_query_decide($sql_check) or die("Error while fetching profile for TC   ".mysql_error_js());
                        while($row_check=mysql_fetch_assoc($res_check))
                        {
                                $mProfileids[$row_check[pid]]=$row_check[pid];
                        }
			if($rProfileids || $mProfileids)
			{
				if($rProfileids)
				{
					$rPidStr=implode(",",$rProfileids);
					$combineLog[]=$rPidStr;
					$sql_cancel="update Assisted_Product.AP_CALL_HISTORY set CALL_STATUS='C' where PROFILEID IN($rPidStr)";
					mysql_query_decide($sql_cancel) or die("Error while fetching profile for TC   ".mysql_error_js());
				}
				if($mProfileids)
                                {
                                        $mPidStr=implode(",",$mProfileids);
					$combineLog[]=$mPidStr;
                                        $sql_cancel="update Assisted_Product.AP_CALL_HISTORY set CALL_STATUS='C' where MATCH_ID IN($mPidStr)";
                                        mysql_query_decide($sql_cancel) or die("Error while fetching profile for TC   ".mysql_error_js());
                                }
				$combineStr=implode(",",$combineLog);
				$combineArr=explode(",",$combineStr);
				foreach($combineArr as $key=>$val)
				{
					$combineSql="insert into Assisted_Product.AP_CALL_HISTORY_EXPIRED_LOG(PID,date) values('$val',now())";
					mysql_query_decide($combineSql) or die("Error while fetching profile for TC   ".mysql_error_js());
				}
			}
			// Get the records from the call history queue in ascending order by the TELECALLER
                        $sql = getTcQuerry($qtype,'1',$callreq_pid);

                        $res=mysql_query_decide($sql) or die("Error while fetching profile for TC   ".mysql_error_js());
                        if(mysql_num_rows($res))
                        {
		        	$row=mysql_fetch_assoc($res);
                                $sql="UPDATE Assisted_Product.AP_QUEUE SET ASSIGNED_TO='$name',ASSIGN_TIME=NOW() WHERE PROFILEID='$row[MATCH_ID]' AND ASSIGNED_FOR='TC'";
                                mysql_query_decide($sql) or die("Error while assigning profile to TC   ".mysql_error_js());
                                $sql1= getTcQuerry($qtype,'2',$callreq_pid);
                                $res1=mysql_query_decide($sql1) or die("Error while fetching profile for QUEUE   ".mysql_error_js());
				if(mysql_num_rows($res1))
				{
                                	while($row1=mysql_fetch_assoc($res1))
					{
                                                $pid1=$row1["MATCH_ID"];
                                                $sqlUpdate="UPDATE Assisted_Product.AP_QUEUE SET ASSIGNED_TO='',ASSIGN_TIME='0000-00-00 00:00:00' WHERE PROFILEID='$pid1' AND ASSIGNED_TO='$row1[ASSIGNED_TO]' AND ASSIGNED_FOR='TC'";
                                                mysql_query_decide($sqlUpdate) or die("Error while update the QUEUE  ".mysql_error_js());
                                        }
                                }
                                return $row;
                        }
                        else
                        {
                                $stop=0;
                        }
			 if(!$stop)
                        {
                                // Delete the records from the queue which are served once by the TELECALLER
                                //$sql="DELETE FROM Assisted_Product.AP_QUEUE WHERE CONVERT(`ASSIGNED_FOR` USING utf8) = 'TC' AND ASSIGNED_TO!=''";
				$sql="DELETE FROM Assisted_Product.AP_QUEUE WHERE CONVERT(`ASSIGNED_FOR` USING utf8)='TC'";
                                mysql_query_decide($sql) or die("Error while deleting profile from QUEUE which were assigned to TC ".mysql_error_js());

                                // Insert records into AP_QUEUE(telecaller queue) to maintain call history queue.
                                $sql= getTcQuerry($qtype,'3',$callreq_pid);
				$sql .=" ORDER BY AP_CALL_HISTORY.REQUEST_DATE ASC";

                                $res=mysql_query_decide($sql) or die("Error while assigning profile to QUEUE for TC   ".mysql_error_js());  
                                $sel_pid="";
                                if(mysql_num_rows($res))
                                {       while($row=mysql_fetch_assoc($res))
                                        {
                                                $pid=$row["MATCH_ID"];
                                                if(empty($sel_pid))
                                                        $sel_pid =$pid;
                                                $sqlUpdate="UPDATE Assisted_Product.AP_CALL_HISTORY SET FOLDER='TBC' WHERE MATCH_ID='$pid' AND CALL_STATUS='N'";
                                                mysql_query_decide($sqlUpdate) or die("Error while updating TC   ".mysql_error_js());
                                                $sqlUpdate="REPLACE INTO Assisted_Product.AP_QUEUE(PROFILEID,ASSIGNED_FOR) VALUES('$pid','TC')";
                                                mysql_query_decide($sqlUpdate) or die("Error while insert into AP_QUEUE  ".mysql_error_js());
                                        }
                                        $sqlUpdate="UPDATE Assisted_Product.AP_QUEUE SET ASSIGNED_TO='$name',ASSIGN_TIME=NOW() WHERE PROFILEID='$sel_pid' AND ASSIGNED_FOR='TC'";
                                        mysql_query_decide($sqlUpdate) or die("Error while assigning profile to QA   ".mysql_error_js());
					if($sel_pid){
                                                $row['MATCH_ID']=$sel_pid;
                                                return $row;
                                        }
                                }
                                return 0;
                        }
                        break;
	}
}
function getTcQuerry($qtype,$pos,$uid,$count)
{
    if($count)
    {
        $count= " COUNT(DISTINCT CONCAT(Assisted_Product.AP_CALL_HISTORY.MATCH_ID,Assisted_Product.AP_CALL_HISTORY.PROFILEID)) AS CNT,";
    }
    else
        $count="";
    if($pos=='1')
    {
        $partquery1= " ";
        $partquery2= " AND QUE.ASSIGNED_TO='' AND QUE.ASSIGN_TIME='0000-00-00 00:00:00' AND QUE.ASSIGNED_FOR='TC' AND Assisted_Product.AP_CALL_HISTORY.CALL_STATUS = 'N' ";
        $partquery3= " ";
        $partquery4= " ";
    }   
    elseif($pos=='2')
    {
        $partquery1= ",QUE.ASSIGNED_TO ";
        $partquery2= " AND QUE.ASSIGN_TIME < DATE_SUB(NOW(), INTERVAL 30 MINUTE) AND QUE.ASSIGNED_FOR='TC' AND Assisted_Product.AP_CALL_HISTORY.CALL_STATUS = 'N' AND QUE.ASSIGNED_TO!='' ";
        $partquery3= " ";
        $partquery4= " ";
    }
    elseif($pos=='3')
    {
        $partquery1= " ";   
        $partquery2=" ";
        $partquery3= " LEFT ";
        $partquery4= " AND (QUE.PROFILEID IS NULL OR QUE.ASSIGNED_FOR!='TC') AND Assisted_Product.AP_CALL_HISTORY.CALL_STATUS = 'N' ";
        if($count)
            $partquery4= " AND Assisted_Product.AP_CALL_HISTORY.CALL_STATUS = 'N' ";
    }
    if($qtype=='fresh')
    {
        $sqlquery= "SELECT".$count." Assisted_Product.AP_CALL_HISTORY.MATCH_ID".$partquery1." FROM Assisted_Product.AP_CALL_HISTORY"
.$partquery3."JOIN Assisted_Product.AP_QUEUE AS QUE ON Assisted_Product.AP_CALL_HISTORY.MATCH_ID = QUE.PROFILEID".$partquery2.
"LEFT JOIN Assisted_Product.AP_MATCH_COMMENTS AS TAB_COM ON (TAB_COM.MATCH_ID = AP_CALL_HISTORY.MATCH_ID AND TAB_COM.PROFILEID = AP_CALL_HISTORY.PROFILEID)
LEFT JOIN newjs.JPROFILE AS TAB_PRO ON TAB_PRO.PROFILEID=Assisted_Product.AP_CALL_HISTORY.MATCH_ID WHERE TAB_PRO.COUNTRY_RES NOT IN ('128','22') AND TAB_COM.COMMENTS IS NULL".$partquery4;
    }
    elseif($qtype=='nonfresh')
    {
        $sqlquery= "SELECT".$count." Assisted_Product.AP_CALL_HISTORY.MATCH_ID".$partquery1." FROM Assisted_Product.AP_CALL_HISTORY"
.$partquery3."JOIN Assisted_Product.AP_QUEUE AS QUE ON Assisted_Product.AP_CALL_HISTORY.MATCH_ID = QUE.PROFILEID".$partquery2.
"LEFT JOIN Assisted_Product.AP_MATCH_COMMENTS AS TAB_COM ON (TAB_COM.MATCH_ID = AP_CALL_HISTORY.MATCH_ID AND TAB_COM.PROFILEID = AP_CALL_HISTORY.PROFILEID) 
LEFT JOIN newjs.JPROFILE AS TAB_PRO ON TAB_PRO.PROFILEID=Assisted_Product.AP_CALL_HISTORY.MATCH_ID WHERE TAB_PRO.COUNTRY_RES NOT IN ('128','22') AND TAB_COM.COMMENTS IS NOT NULL AND TAB_COM.PROFILEID!=TAB_COM.MATCH_ID".$partquery4;
    }
    elseif($qtype=='uscan')
    {
        $sqlquery="SELECT".$count." Assisted_Product.AP_CALL_HISTORY.MATCH_ID".$partquery1." FROM Assisted_Product.AP_CALL_HISTORY"
.$partquery3."JOIN Assisted_Product.AP_QUEUE AS QUE ON Assisted_Product.AP_CALL_HISTORY.MATCH_ID = QUE.PROFILEID".$partquery2. 
"LEFT JOIN newjs.JPROFILE AS TAB_PRO ON TAB_PRO.PROFILEID=Assisted_Product.AP_CALL_HISTORY.MATCH_ID WHERE TAB_PRO.COUNTRY_RES IN ('128','22')".$partquery4;
    }  
    elseif($qtype=='userid')
    {
        if($pos=='1')
        {
            $sqlquery="SELECT Assisted_Product.AP_CALL_HISTORY.MATCH_ID from Assisted_Product.AP_CALL_HISTORY,Assisted_Product.AP_QUEUE WHERE AP_CALL_HISTORY.MATCH_ID=AP_QUEUE.PROFILEID AND AP_QUEUE.ASSIGNED_TO='' AND AP_QUEUE.ASSIGN_TIME='0000-00-00 00:00:00' AND AP_QUEUE.ASSIGNED_FOR='TC' AND AP_CALL_HISTORY.CALL_STATUS = 'N'";
        }
        elseif($pos=='2')
        {
            $sqlquery="SELECT AP_CALL_HISTORY.MATCH_ID,AP_QUEUE.ASSIGNED_TO FROM Assisted_Product.AP_CALL_HISTORY,Assisted_Product.AP_QUEUE WHERE AP_CALL_HISTORY.MATCH_ID=AP_QUEUE.PROFILEID AND AP_QUEUE.ASSIGN_TIME < DATE_SUB(NOW(), INTERVAL 30 MINUTE) AND AP_QUEUE.ASSIGNED_FOR='TC' AND AP_CALL_HISTORY.CALL_STATUS = 'N' AND AP_QUEUE.ASSIGNED_TO!=''";
        }
        elseif($pos=='3')
        {
            $sqlquery="SELECT Assisted_Product.AP_CALL_HISTORY.MATCH_ID FROM Assisted_Product.AP_CALL_HISTORY LEFT JOIN Assisted_Product.AP_QUEUE ON Assisted_Product.AP_CALL_HISTORY.MATCH_ID = Assisted_Product.AP_QUEUE.PROFILEID WHERE (Assisted_Product.AP_QUEUE.PROFILEID IS NULL OR AP_QUEUE.ASSIGNED_FOR!='TC') AND AP_CALL_HISTORY.CALL_STATUS = 'N'";
        }
        $sqlquery .=" AND AP_CALL_HISTORY.PROFILEID='$uid'";
    }
    if($pos=='2')
    {
        if($qtype=='userid')
                $sqlquery .=" ORDER BY ASSIGN_TIME ASC";
        else
                $sqlquery .=" ORDER BY QUE.ASSIGN_TIME ASC";
    }
    if($count)
    {
        $res=mysql_query_decide($sqlquery);
        $row=mysql_fetch_assoc($res);
        return($row["CNT"]);
    }
    else
            return $sqlquery;
    
}
function logSubmitProfile($profileid,$operator,$loggedFor,$status)
{
	$sql="SELECT ASSIGN_TIME FROM Assisted_Product.AP_QUEUE WHERE PROFILEID='$profileid' AND ASSIGNED_TO='$operator' AND ASSIGNED_FOR='$loggedFor'";
	$res=mysql_query_decide($sql) or die("Error while fetching assign time   ".mysql_error_js());
	$row=mysql_fetch_assoc($res);
	$assignTime=$row["ASSIGN_TIME"];
	$sql="INSERT INTO Assisted_Product.AP_QUEUE_LOG(PROFILEID,ASSIGNED_TO,ASSIGN_TIME,SUBMIT_TIME,STATUS,ASSIGNED_FOR) VALUES('$profileid','$operator','$assignTime',NOW(),'$status','$loggedFor')";
	mysql_query_decide($sql) or die("Error while logging submission of profile   ".mysql_error_js());
	$sql="DELETE FROM Assisted_Product.AP_QUEUE WHERE PROFILEID='$profileid' AND ASSIGNED_TO='$operator' AND ASSIGNED_FOR='$loggedFor'";
	mysql_query_decide($sql) or die("Error while deleting entry from queue   ".mysql_error_js());
	if($loggedFor=='DIS')
	{
		$sql="SELECT COUNT(*) AS COUNT FROM Assisted_Product.AP_AUDIT_TRAIL WHERE PROFILEID='$profileid' AND DESTINATION='DIS' AND MOVED_BY='$operator' AND DATE(DATE)=CURDATE()";
                $res=mysql_query_decide($sql) or die("Error while checking if profile is serviced   ".$sql.mysql_error_js());
                $row=mysql_fetch_assoc($res);
                if($row["COUNT"])
                        $serviced='Y';
                else
                        $serviced='S';
		$sql="UPDATE Assisted_Product.AP_SERVICE_TABLE SET SERVICED='$serviced' WHERE PROFILEID='$profileid' AND NEXT_SERVICE_DATE=CURDATE()";
		$res=mysql_query_decide($sql) or die("Error while updating service table   ".$sql."  ".mysql_error_js());
		if(mysql_affected_rows_js()==0)
		{
			$sql="UPDATE Assisted_Product.AP_MISSED_SERVICE_LOG SET COMPLETED='Y',COMPLETED_ON=CURDATE(),COMPLETED_BY='$operator' WHERE PROFILEID='$profileid'";
			mysql_query_decide($sql) or die("Error while updating missed service log  ".$sql."  ".mysql_error_js());
		}
	}
	if($loggedFor=='NQA' || $loggedFor=='RQA')
        {
                $sql="DELETE FROM Assisted_Product.AP_QA_SKIPPED_RECORDS WHERE PROFILEID='$profileid'";
                mysql_query_decide($sql) or die("Error while deleting entry from skipped records table   ".mysql_error_js());
        }
}

function checkAssigned($profileid,$loggedFor,$assignedTo,$role)
{
	if(!$loggedFor)
	{
		if($role=='DIS')
			$loggedFor="DIS";
		if($role=='TC')
			$loggedFor="TC";
	}
	$flag=0;
	switch($role)
	{
		case 'QA' : 
			$sql="SELECT * FROM Assisted_Product.AP_QUEUE WHERE PROFILEID='$profileid' AND ASSIGNED_FOR='$loggedFor'";
			if($assignedTo)
				$sql.=" AND ASSIGNED_TO='$assignedTo'";
			$res=mysql_query_decide($sql) or die("Error while checking if profile is assigned   ".mysql_error_js());
			if(mysql_num_rows($res))
				$flag=1;
			else
				$flag=0;
			break;
		case 'SE' :
			$sql="SELECT * FROM Assisted_Product.AP_PROFILE_INFO WHERE PROFILEID='$profileid' AND SE='$assignedTo'";
			$res=mysql_query_decide($sql) or die("Error while checking if profile is assigned   ".mysql_error_js());
			if(mysql_num_rows($res))
				$flag=1;
			else
				$flag=0;
			break;
		case 'DIS':
			$sql="SELECT * FROM Assisted_Product.AP_QUEUE WHERE PROFILEID='$profileid' AND ASSIGNED_FOR='$loggedFor'";
                        if($assignedTo)
                                $sql.=" AND ASSIGNED_TO='$assignedTo'";
                        $res=mysql_query_decide($sql) or die("Error while checking if profile is assigned   ".mysql_error_js());
                        if(mysql_num_rows($res))
                                $flag=1;
                        else
                                $flag=0;
                        break;
		case 'TC':
			$sql="SELECT * FROM Assisted_Product.AP_QUEUE WHERE PROFILEID='$profileid' AND ASSIGNED_FOR='$loggedFor'";
                        if($assignedTo)
                                $sql.=" AND ASSIGNED_TO='$assignedTo'";
                        $res=mysql_query_decide($sql) or die("Error while checking if profile is assigned   ".mysql_error_js());
                        if(mysql_num_rows($res))
                                $flag=1;
                        else
                                $flag=0;
                        break;
		default :
			$flag=0;
	}
	return $flag;
}
function leftPanelLinksCount($countArr,$profileid)
{
	if(!$countArr[$profileid]['SL'])
                $valueArray['SL']="";
        else
		$valueArray['SL']="(".$countArr[$profileid]['SL'].")";
        if(!$countArr[$profileid]['TBD'])
		$valueArray['TBD']="";
	else
		$valueArray['TBD']="(".$countArr[$profileid]['TBD'].")";
	if(!$countArr[$profileid]['FIL'])
                $valueArray['FIL']="";
        else
                $valueArray['FIL']="(".$countArr[$profileid]['FIL'].")";
	if(!$countArr[$profileid]['TBC'])
                $valueArray['TBC']="";
        else
                $valueArray['TBC']="(".$countArr[$profileid]['TBC'].")";
	if(!$countArr[$profileid]['DIS'])
                $valueArray['DIS']="";
        else
                $valueArray['DIS']="(".$countArr[$profileid]['DIS'].")";
	return $valueArray;
}

function fetchLeftPanelLinks($role,$cid,$profileid,$new,$page,$countArr,$callreq_pid="")
{
	global $smarty;
	if($role!='TC')
		$counts=leftPanelLinksCount($countArr,$profileid);
	switch($role)
	{
		case 'QA' : 
			$links="<ul class=\"left_nav b f_13 fl\">";
			if($page=="DPP")
				$links.="<li><a href=\"ap_dpp.php?cid=$cid&new=$new\" class=\"active\">QA Profile</a></li>";
			else
				$links.="<li><a href=\"ap_dpp.php?cid=$cid&new=$new\">QA Profile</a></li>";
			if($page=="MYPROFILE")
                                $links.="<li><a href=\"#\" class=\"active\">View my Profile</a></li>";
                        else
                                $links.="<li><a href=\"ap_viewprofile.php?cid=$cid&profileid=$profileid&new=$new&list=MYPROFILE\">View my Profile</a></li>";
			global $outOfQueue,$pulledProfile;
                        if(!$pulledProfile)
                                $links.="<li><a href=\"ap_dpp.php?cid=$cid&editedProfile=$profileid&new=$new&skip=1&outOfQueue=$outOfQueue\">Skip profile</a>";
			$links.="<li><a href=\"$SITE_URL/jsadmin/mainpage.php?cid=$cid\">Main Page</a></li>";
			$links.="</ul>";
			break;
		case 'SE':
			$links="<ul class=\"left_nav b f_13 fl\">";
			if($page=="DPP")
				$links.="<li><a href=\"#\" class=\"active\">Desired Partner Profile</a></li>";
			else
				$links.="<li><a href=\"ap_dpp.php?cid=$cid&profile=$profileid&new=$new\">Desired Partner Profile</a></li>";
			if($page=="MYPROFILE")
                                $links.="<li><a href=\"#\" class=\"active\">View my Profile</a></li>";
                        else
                                $links.="<li><a href=\"ap_viewprofile.php?cid=$cid&profileid=$profileid&list=MYPROFILE\">View my Profile</a></li>";
			if($page=="FIL")
				$links.="<li><a href=\"#\" class=\"active\">Filtered $counts[FIL]</a></li>";
			else
				$links.="<li><a href=\"ap_list.php?cid=$cid&profileid=$profileid&list=FIL\">Filtered $counts[FIL]</a></li>";
			if($page=="SL")
				$links.="<li><a href=\"#\" class=\"active\">Shortlisted $counts[SL]</a></li>";
			else
				$links.="<li><a href=\"ap_list.php?cid=$cid&profileid=$profileid&list=SL\">Shortlisted $counts[SL]</a></li>";
			if($page=="TBD")
				$links.="<li><a href=\"#\" class=\"active\">To be Dispatched $counts[TBD]</a></li>";
			else
				$links.="<li><a href=\"ap_list.php?cid=$cid&profileid=$profileid&list=TBD\">To be Dispatched $counts[TBD]</a></li>";
			if($page=="DIS")
				$links.="<li><a href=\"#\" class=\"active\">Dispatched $counts[DIS]</a></li>";
			else
				$links.="<li><a href=\"ap_list.php?cid=$cid&profileid=$profileid&list=DIS\">Dispatched $counts[DIS]</a></li>";
			if($page=="TBC")
				$links.="<li><a href=\"#\" class=\"active\">To be called $counts[TBC]</a></li>";
			else
				$links.="<li><a href=\"ap_list.php?cid=$cid&profileid=$profileid&list=TBC\">To be called $counts[TBC]</a></li>";
			$links.="<li><a href=\"$SITE_URL/jsadmin/mainpage.php?cid=$cid\">Main Page</a></li>";
			$links.="</ul>";
			break;
		case 'DIS' :
			$links="<ul class=\"left_nav b f_13 fl\">";
                        if($page=="DPP")
                                $links.="<li><a href=\"#\" class=\"active\">Desired Partner Profile</a></li>";
                        else
                                $links.="<li><a href=\"ap_dpp.php?cid=$cid&profile=$profileid\">Desired Partner Profile</a></li>";
			if($page=="MYPROFILE")
                                $links.="<li><a href=\"#\" class=\"active\">View my Profile</a></li>";
                        else
                                $links.="<li><a href=\"ap_viewprofile.php?cid=$cid&profileid=$profileid&list=MYPROFILE\">View my Profile</a></li>";
                        if($page=="FIL")
                                $links.="<li><a href=\"#\" class=\"active\">Filtered $counts[FIL]</a></li>";
                        else
                                $links.="<li><a href=\"ap_list.php?cid=$cid&profileid=$profileid&list=FIL\">Filtered $counts[FIL]</a></li>";
                        if($page=="SL")
                                $links.="<li><a href=\"#\" class=\"active\">Shortlisted $counts[SL]</a></li>";
                        else
                                $links.="<li><a href=\"ap_list.php?cid=$cid&profileid=$profileid&list=SL\">Shortlisted $counts[SL]</a></li>";
                        if($page=="TBD")
                                $links.="<li><a href=\"#\" class=\"active\">To be Dispatched $counts[TBD]</a></li>";
                        else
                                $links.="<li><a href=\"ap_list.php?cid=$cid&list=TBD\">To be Dispatched $counts[TBD]</a></li>";
                        if($page=="DIS")
                                $links.="<li><a href=\"#\" class=\"active\">Dispatched $counts[DIS]</a></li>";
                        else
                                $links.="<li><a href=\"ap_list.php?cid=$cid&profileid=$profileid&list=DIS\">Dispatched $counts[DIS]</a></li>";
			global $outOfQueue;
                        if($outOfQueue)
                                $links.="<li><a href=\"ap_list.php?cid=$cid&submitProfile=1&profileid=$profileid&list=TBD&outOfQueue=1\" onClick=\"return confirmNextProfile();\">Submit Profile</a></li>";
                        else
                                $links.="<li><a href=\"ap_list.php?cid=$cid&submitProfile=1&profileid=$profileid&list=TBD\" onClick=\"return confirmNextProfile();\">Next Profile</a></li>";
			$links.="<li><a href=\"$SITE_URL/jsadmin/mainpage.php?cid=$cid\">Main Page</a></li>";
                        $links.="</ul>";
                        break;
		CASE 'TC' :
                        $links="<ul class=\"left_nav b f_13 fl\">";
                        if($page=="MYPROFILE" || $page=='CALL' || $page=='PULL')
                                $links.="<li><a href=\"#\" class=\"active\">Profile in Queue</a></li>";
                        else
                                $links.="<li><a href=\"ap_viewprofile.php?cid=$cid&profileid=$profileid&list=CALL&callreq_pid=$callreq_pid\">Profile in Queue</a></li>";
                        if($page=="CALLERS")
                                $links.="<li><a href=\"#\" class=\"active\"><font color='black'>Requested Users</a></li>";
                        else
                                $links.="<li><a href=\"ap_callUsers.php?cid=$cid&profileid=$profileid&list=CALLERS&callreq_pid=$callreq_pid \">Requested Users</a></li>";
			if($callreq_pid)
				$links.="<li><a href=\"ap_viewprofile.php?cid=$cid&page=MYPROFILE&list=PULL&callreq_pid=$callreq_pid&qtype=userid\">Next profile</a></li>";		
			$links.="<li><a href=\"$SITE_URL/jsadmin/mainpage.php?cid=$cid\">Main Page</a></li>";
                        $links.="</ul>";
                        break;
	}
	$smarty->assign("LINKS",$links);
}

function getTitle($list,$count,$searchType='',$setDate='',$username='')
{
	global $smarty;
	if($count)
		$title=$count;
	else
		$title="0 ";
	switch($list)
	{
		case 'SL' : if($searchType=='CAT')
			$title.=" Profiles found for your search";
				else
			$title.=" Profiles are shortlisted";
			break;
		case 'DIS' : if($searchType=='SET' && $setDate)
				{
					list($year,$month,$day)=explode("-",$setDate);
					$displayDate=my_format_date($day,$month,$year,2);
					$title.=" Profiles dispatched on ".$displayDate	;
				}
				else
					$title.=" Profiles dispatched to this User";
			break;
		case 'FIL' : $title.=" Filtered Users";
			break;
		case 'TBD' : $title.=" Profiles to be dispatched";
			if($username)
				$title.=" for $username";
			break;
		case 'TBC' : $title="Calls to be initiated to ".$title." users";
			break;
		case 'CALLERS' :if($title=='1') 
					$title.=" User has Requested to call this user";
				else
					$title.=" Users have Requested to call this user";
                        break;
	}
	$smarty->assign("TITLE",$title);
}

function getButtons($list,$role)
{
	global $smarty;
	switch($list)
	{
		case 'SL' :
			if($role=='SE')
			{
				$buttons="<input name=\"TBD\" type=\"submit\" class=\"b green_btn\" value=\"Move to - to  be dispached\" style=\"width:160px;\" />&nbsp;<input name=\"REM\" type=\"submit\" class=\"b green_btn\" value=\"Delete Profiles\" style=\"width:110px;\">";
			}
			if($role=='DIS')
			{
				$buttons="<input name=\"TBD\" type=\"submit\" class=\"b green_btn\" value=\"Move to - to be dispatched\" style=\"width:160px;\">";
			}
			break;
		case 'FIL' :
			if($role=='SE') 
                        {
                                $buttons="<input name=\"TBD\" type=\"submit\" class=\"b green_btn\" value=\"Move to - to  be dispached\" style=\"width:160px;\" />&nbsp;<input name=\"REM\" type=\"submit\" class=\"b green_btn\" value=\"Delete Profiles\" style=\"width:110px;\">";
                        }
                        if($role=='DIS')
                        {
				$buttons="<input name=\"TBD\" type=\"submit\" class=\"b green_btn\" value=\"Move to - to be dispatched\" style=\"width:160px;\">";
                        }
			break;
		case 'TBD' :
			if($role=='SE')
			{
				$buttons="<input name=\"ORIG\" type=\"submit\" class=\"b green_btn\" value=\"Move to Original Folder\" style=\"width:160px;\" />&nbsp;<input name=\"REM\" type=\"submit\" class=\"b green_btn\" value=\"Delete Profiles\" style=\"width:110px;\">";
			}
			if($role=='DIS')
			{
				$buttons="<input name=\"ORIG\" type=\"submit\" class=\"b green_btn\" value=\"Move to Original Folder\" style=\"width:160px;\" />&nbsp;<input name=\"DIS\" type=\"submit\" class=\"b green_btn\" value=\"Move to Dispatched\" style=\"width:125px;\">";
			}
			break;
		case 'DIS' :
			if($role=='DIS')
			{
				 $buttons="<input name=\"TBD\" type=\"submit\" class=\"b green_btn\" value=\"Move to - to  be dispached\" style=\"width:160px;\" />";
			}
	}
	$smarty->assign("BUTTONS",$buttons);
}
function auditTrail($movedBy,$profileid,$matchArr,$leadsArr,$callsArr,$sourceFolder,$desFolder)
{
	if($profileid && (is_array($matchArr) || is_array($leadsArr) || is_array($callsArr)) && $movedBy && $sourceFolder && $desFolder)
	{
		if(is_array($matchArr) && count($matchArr))
		{
			if($desFolder=='ORIG')
			{
				$mysqlObj=new Mysql;
				$dbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
				$myDb=$mysqlObj->connect("$dbName");
				$contactProfiles=implode("','",$matchArr);
				$sql="SELECT FILTERED,SENDER FROM newjs.CONTACTS WHERE RECEIVER='$profileid' AND SENDER IN('$contactProfiles')";
				$res=$mysqlObj->executeQuery($sql,$myDb);
				while($row=$mysqlObj->fetchAssoc($res))
				{
					if($row["FILTERED"])
						$desFolderArr[$row["SENDER"]]="FIL";
					else
						$desFolderArr[$row["SENDER"]]="SL";
				}
			}
			foreach($matchArr as $key=>$value)
			{
				if($desFolder=='ORIG')
				{
					if(!$desFolderArr[$value])
						$desFolder="SL";
					else
						$desFolder=$desFolderArr[$value];
				}
				$valueString.="('$profileid','$value','$sourceFolder','$desFolder','$movedBy',NOW(),'P'),";
			}
		}
		if(is_array($callsArr) && count($callsArr))
		{
			if($desFolder=='DIS' || $desFolder=='TBD' || $desFolder=='DUP')
			{
				foreach($callsArr as $key=>$value)
				{
					$valueString.="('$profileid','$value','$sourceFolder','$desFolder','$movedBy',NOW(),'C'),";
				}
			}
		}
		if(is_array($leadsArr) && count($leadsArr))
		{
			if($desFolder=='ORIG')
				$desFolder='SL';
			foreach($leadsArr as $key=>$value)
			{
				$valueString.="('$profileid','$value','$sourceFolder','$desFolder','$movedBy',NOW(),'L'),";
			}
		}
                $valueString=trim($valueString,",");
		if($valueString)
		{
                	$sql="INSERT INTO Assisted_Product.AP_AUDIT_TRAIL(PROFILEID,MATCH_ID,SOURCE,DESTINATION,MOVED_BY,DATE,MATCH_TYPE) VALUES $valueString";
			mysql_query_decide($sql) or die("Error in audit trail   ".mysql_error_js());
		}
	}
}

function moveProfiles($movedBy,$profileid,$matchArr,$leadsArr,$callsArr,$sourceFolder,$desFolder)
{
	if($movedBy && $profileid && (is_array($matchArr) || is_array($leadsArr) || is_array($callsArr)) && $sourceFolder && $desFolder)
	{
		$valueString="";
		if($desFolder=="TBC" && is_array($matchArr) && count($matchArr))
			placeCallRequest($profileid,$matchArr);
		else
		{
			if(is_array($matchArr) && count($matchArr))
			{
				global $activeServers,$noOfActiveServers;
				$mysqlObj=new Mysql;
				if($desFolder=='ORIG' || $desFolder=='REM')
				{
					$dontRemoveProfiles=isProfileInFolder($movedBy,$profileid,$matchArr,$desFolder,0,1);
					if(is_array($dontRemoveProfiles))
						$matchArr=array_diff($matchArr,$dontRemoveProfiles);
				}
				if(is_array($matchArr) && count($matchArr))
				{
					$dbNameArr[]=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
					foreach($matchArr as $key=>$value)
					{
						$dbName=getProfileDatabaseConnectionName($value,'',$mysqlObj);
						if(!in_array($dbName,$dbNameArr))
							$dbNameArr[]=$dbName;
						if(count($dbNameArr)==$noOfActiveServers)
							break;
					}
					foreach($dbNameArr as $key=>$value)
						$dbConn[$value]=$mysqlObj->connect("$value");
					$matches=implode("','",$matchArr);

					if($desFolder=='ORIG')
					{
						$sql1="UPDATE newjs.CONTACTS SET FOLDER=IF(FILTERED='Y','FIL','SL') WHERE SENDER='$profileid' AND RECEIVER IN('$matches') AND FOLDER='$sourceFolder'";
						$sql2="UPDATE newjs.CONTACTS SET FOLDER=IF(FILTERED='Y','FIL','SL') WHERE RECEIVER='$profileid' AND SENDER IN('$matches') AND FOLDER='$sourceFolder'";	
						
					}
					elseif($desFolder=='REM')
					{
						$sql1="UPDATE newjs.CONTACTS SET FOLDER='$desFolder' WHERE SENDER='$profileid' AND RECEIVER IN('$matches') AND FOLDER='$sourceFolder'";
						$sql2="UPDATE newjs.CONTACTS SET FOLDER='$desFolder',TYPE='D' WHERE RECEIVER='$profileid' AND SENDER IN('$matches') AND FOLDER='$sourceFolder'";
						$sql3="delete from newjs.CONTACTS_STATUS where PROFILEID IN ('$profileid','$matches')";
                                                mysql_query_decide($sql3) or die("Eror while updating CONTACTS_STATUS  ".mysql_error_js());
					}
					elseif($desFolder=='DIS')
					{
						$sql1="UPDATE newjs.CONTACTS SET FOLDER='$desFolder' WHERE SENDER='$profileid' AND RECEIVER IN('$matches') AND FOLDER='$sourceFolder'";
                                                $sql2="UPDATE newjs.CONTACTS SET FOLDER='$desFolder',TYPE='A' WHERE RECEIVER='$profileid' AND SENDER IN('$matches') AND FOLDER='$sourceFolder'";
						$sql3="delete from newjs.CONTACTS_STATUS where PROFILEID IN ('$profileid','$matches')";
                                                mysql_query_decide($sql3) or die("Eror while updating CONTACTS_STATUS  ".mysql_error_js());
						dispatchComments($profileid,$matchArr);
					}
					else
					{
						$sql1="UPDATE newjs.CONTACTS SET FOLDER='$desFolder' WHERE SENDER='$profileid' AND RECEIVER IN('$matches') AND FOLDER='$sourceFolder'";
						$sql2="UPDATE newjs.CONTACTS SET FOLDER='$desFolder' WHERE RECEIVER='$profileid' AND SENDER IN('$matches') AND FOLDER='$sourceFolder'";
					}
					foreach($dbConn as $key=>$value)
					{
						$mysqlObj->executeQuery($sql1,$value);
						$mysqlObj->executeQuery($sql2,$value);
					}
				}
			}
			if(is_array($leadsArr))
			{
				if($desFolder=='ORIG')
					$desFolder='SL';
				$leads=implode("','",$leadsArr);
				$sql1="UPDATE sugarcrm.leads_cstm SET folder_c='$desFolder' WHERE id_c IN('$leads') AND folder_c='$sourceFolder'";
				mysql_query_decide($sql1) or die("Error while moving leads  ".mysql_error_js());
			}
			auditTrail($movedBy,$profileid,$matchArr,$leadsArr,'',$sourceFolder,$desFolder);
			if(is_array($callsArr))
			{
				if($desFolder=='TBD')
				{
					$resultFolder=isProfileInFolder($movedBy,$profileid,$callsArr,$desFolder,1,0);
					foreach($callsArr as $key=>$value)
					{
						unset($calledProfileArr);
						$sql1="UPDATE Assisted_Product.AP_CALL_HISTORY SET FOLDER='$resultFolder[$value]' WHERE PROFILEID='$profileid' AND MATCH_ID='$value'";
						mysql_query_decide($sql1) or die("Error while moving calls  ".mysql_error_js());
						$calledProfileArr[]=$value;
						auditTrail($movedBy,$profileid,'','',$calledProfileArr,$sourceFolder,$resultFolder[$value]);
					}
				}
				elseif($desFolder=='DIS')
				{
					$matches=implode("','",$callsArr);
					$sql1="UPDATE Assisted_Product.AP_CALL_HISTORY SET FOLDER='$desFolder' WHERE PROFILEID='$profileid' AND MATCH_ID IN('$matches')";
					mysql_query_decide($sql1) or die("Error while moving calls  ".mysql_error_js());
					dispatchComments($profileid,$callsArr);
					auditTrail($movedBy,$profileid,'','',$callsArr,$sourceFolder,$desFolder);
				}
			}
		}
	}
}

function placeCallRequest($profileid,$matchArr)
{
	if($profileid && is_array($matchArr) && count($matchArr))
	{	
		$valueString="";
		foreach($matchArr as $key=>$value)
		{
			$valueString.="('$profileid','$value','N','TBC'),";
		}
		$valueString=trim($valueString,",");
		$sql="INSERT IGNORE INTO AP_CALL_HISTORY(PROFILEID,MATCH_ID,CALL_STATUS,FOLDER) VALUES $valueString";
		mysql_query_decide($sql) or die("Error while placing call request   ".mysql_error_js());
	}
}

function isProfileInFolder($movedBy,$profileid,$matchArr,$desFolder,$callMoved,$contactMoved)
{
	if($profileid && is_array($matchArr) && $movedBy)
	{
		if($callMoved)
		{
			$receiversIn="'".implode("','",$matchArr)."'";
			$sendersIn="'$profileid'";
			$folderResultSet=getResultSet("FOLDER,RECEIVER",$sendersIn,'',$receiversIn);
			if(is_array($folderResultSet))
			{
				foreach($folderResultSet as $key=>$value)
				{
					if($value["FOLDER"])
						$folders[$value["RECEIVER"]]=array("SENDER"=>"1",
										"FOLDER"=>$value["FOLDER"]);
				}
			}
			$sendersIn=$receiversIn;
			$receiversIn="'$profileid'";
			$folderResultSet=getResultSet("FOLDER,SENDER",$sendersIn,'',$receiversIn);
			if(is_array($folderResultSet))
			{
				foreach($folderResultSet as $key=>$value)
				{
					if($value["FOLDER"])
						$folders[$value["SENDER"]]=array("RECEIVER"=>"1",
										"FOLDER"=>$value["FOLDER"]);
				}
			}
			foreach($matchArr as $key=>$value)
			{
				if($folders[$value]["FOLDER"])
				{
					unset($movedProfileArr);
					$resultFolder[$value]='DUP';
					if($folders[$value]["SENDER"])
					{
						$sender_profileid=$profileid;
						$receiver_profileid=$value;
					}		
					elseif($folders[$value]["RECEIVER"])
					{
						$sender_profileid=$value;
                                                $receiver_profileid=$profileid;
					}
					if($folders[$value]["FOLDER"]!=$desFolder)
						$movedProfileArr[]=$value;
				}
				else
					$resultFolder[$value]=$desFolder;
			}
			moveProfiles($movedBy,$profileid,$movedProfileArr,'','',$folders[$value]["FOLDER"],$desFolder);
			return $resultFolder;
		}
		if($contactMoved)
		{
			$matches=implode("','",$matchArr);
			$sql1="SELECT MATCH_ID FROM Assisted_Product.AP_CALL_HISTORY WHERE PROFILEID='$profileid' AND MATCH_ID IN('$matches') AND CALL_STATUS='Y'";
			$res1=mysql_query_decide($sql1) or die("Error while fetching calls status   ".mysql_error_js());
			if(mysql_num_rows($res1))
			{
				while($row1=mysql_fetch_assoc($res1))
				{
					$dontRemoveProfiles[]=$row1["MATCH_ID"];
				}
			}
			return $dontRemoveProfiles;	
		}
	}
}

function isProfileNew($profileid)
{
	$sql="SELECT STATUS FROM Assisted_Product.AP_PROFILE_INFO WHERE PROFILEID='$profileid'";
	$res=mysql_query_decide($sql) or die("Error while checking if profile is new  ".mysql_error_js());
	$row=mysql_fetch_assoc($res);
	if($row["STATUS"]=='LIVE')
		return 0;
	else
		return 1;
}

function getDispatcherCities($name)
{
	$sql="SELECT CITY FROM Assisted_Product.AP_DISPATCHER_CITIES WHERE DISPATCHER='$name'";
	$res=mysql_query_decide($sql) or die("Error while fetching dispatcher cities  ".mysql_error_js());
	if(mysql_num_rows($res))
	{
		while($row=mysql_fetch_assoc($res))
			$cityArr[]=$row["CITY"];
		return $cityArr;
	}
	else
		return '';
}

function fetchCourierSet($profileid)
{
	global $smarty;
	if($profileid)
	{
		$sql="SELECT DISTINCT(DATE(DATE)) AS DAY FROM Assisted_Product.AP_AUDIT_TRAIL WHERE DESTINATION='DIS' AND PROFILEID='$profileid' ORDER BY DATE(DATE) ASC";
		$res=mysql_query_decide($sql) or die("Error while fetching courier sets   ".$sql."  ".mysql_error_js());
		if(mysql_num_rows($res))
		{
			$i=1;
			while($row=mysql_fetch_assoc($res))
			{
				if($i%10==1)
					$number="1st";
				elseif($i%10==2)
				$number="2nd";
				elseif($i%10==3)
					$number="3rd";
				else
					$number=$i."th";
				list($year,$month,$day)=explode("-",$row["DAY"]);
				$displayDate=my_format_date($day,$month,$year,2);	
				$courierList[$row["DAY"]]=$number." Set - Dispatched On ".$displayDate;
				$i++;
			}
			$smarty->assign("courierList",$courierList);
		}
	}
}

function getNumberOfSearchedProfiles($profileid,$searchType,$setDate='')
{
	if($profileid && $searchType)
	{
		if($searchType=='SET' && $setDate)
		{
			$sql="SELECT COUNT(DISTINCT(MATCH_ID)) AS COUNT FROM Assisted_Product.AP_AUDIT_TRAIL WHERE PROFILEID='$profileid' AND DESTINATION='DIS' AND DATE(DATE)='$setDate'";
			$res=mysql_query_decide($sql) or die("Error while getting number of searched profiles  ".$sql."  ".mysql_error_js());
			if(mysql_num_rows($res))
			{
				$row=mysql_fetch_assoc($res);
				return $row["COUNT"];
			}
			else
				return '';
		}
	}
	return '';
}

function getSearchedProfiles($profileid,$searchType,$setDate='',$pagination='',$profile_start='',$PAGELEN='')
{
	if($profileid && $searchType)
	{
		if($searchType=='SET' && $setDate)
		{
			$sql="SELECT DISTINCT(MATCH_ID),MATCH_TYPE FROM Assisted_Product.AP_AUDIT_TRAIL WHERE DESTINATION='DIS' AND PROFILEID='$profileid' AND DATE(DATE)='$setDate'";
			if($pagination)
				$sql.=" LIMIT $profile_start,$PAGELEN";
			$res=mysql_query_decide($sql) or die("Error while fetching searched profiles   ".mysql_error_js());
			while($row=mysql_fetch_assoc($res))
			{
				if($row["MATCH_TYPE"]=="P")
				{
					$details=array("PROFILEID"=>$row["MATCH_ID"],
							"CHECKBOX_ID"=>$row["MATCH_ID"]);
					$profilesArr[]=$row["MATCH_ID"];
				}
				elseif($row["MATCH_TYPE"]=="L")
				{
					$details=array("LEAD_ID"=>$row["MATCH_ID"],
                                                        "CHECKBOX_ID"=>"LEAD_".$row["MATCH_ID"]);
					$leadsArr[]=$row["MATCH_ID"];
				}
				elseif($row["MATCH_TYPE"]=="C")
				{
					$details=array("PROFILEID"=>$row["MATCH_ID"],
                                                        "CHECKBOX_ID"=>"CALL_".$row["MATCH_ID"]);
                                        $callsArr[]=$row["MATCH_ID"];

				}
				$detailsArr[]=$details;
			}
			$extraDetails=getFolder($profileid,$profilesArr,$callsArr,$leadsArr);
			$folderArr=$extraDetails["FOLDER_INFO"];
			$disableLeads=$extraDetails["DISABLE_LEADS"];
			if(!$disableLeads)
				$disableLeads=array();
			foreach($detailsArr as $key=>$value)
			{
				if(substr($value["CHECKBOX_ID"],0,5)=="LEAD_")	
				{
					if(in_array($value["LEAD_ID"],$disableLeads))
						$detailsArr[$key]["DISABLE"]=1;
					elseif($folderArr[$value["LEAD_ID"]]!='DIS')
					{
						$detailsArr[$key]["DISABLE"]=1;
						$detailsArr[$key]["NOT_IN_DIS"]=1;
					}
				}
				else
				{
					if($folderArr[$value["PROFILEID"]]!='DIS')
					{
						$detailsArr[$key]["DISABLE"]=1;
						$detailsArr[$key]["NOT_IN_DIS"]=1;
					}
				}
			}
			return $detailsArr;
		}
	}
}

function getFolder($profileid,$profilesArr,$callsArr,$leadsArr)
{
	if(is_array($profilesArr))
	{
		$mysqlObj=new Mysql;
		$dbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
		$myDb=$mysqlObj->connect("$dbName");
		$profiles=implode("','",$profilesArr);
		$sql="SELECT FOLDER,RECEIVER AS MATCH_ID FROM newjs.CONTACTS WHERE SENDER='$profileid' AND RECEIVER IN('$profiles') UNION SELECT FOLDER,SENDER AS MATCH_ID FROM newjs.CONTACTS WHERE SENDER IN('$profiles') AND RECEIVER='$profileid'";
		$res=$mysqlObj->executeQuery($sql,$myDb);
		if(mysql_num_rows($res))
		{
			while($row=mysql_fetch_assoc($res))
				$folderArr[$row["MATCH_ID"]]=$row["FOLDER"];
		}
	}
	if(is_array($leadsArr))
	{
		$db = connect_slave();
		$leads=implode("','",$leadsArr);
		$sql="SELECT id_c,folder_c,deleted,converted FROM sugarcrm.leads_cstm JOIN sugarcrm.leads ON id_c=id WHERE id_c IN('$leads')";
		$res=mysql_query_decide($sql,$db) or die("error while fetching leads info  ".$sql."  ".mysql_error_js());
		while($row=mysql_fetch_assoc($res))
		{
			$folderArr[$row["id_c"]]=$row["folder_c"];
			if($row["deleted"]==1 || $row["converted"]==1)
				$disableLead[]=$row["id_c"];
		}
	}
	if(is_array($callsArr))
        {
                $calls=implode("','",$callsArr);
                $sql="SELECT FOLDER,MATCH_ID FROM Assisted_Product.AP_CALL_HISTORY WHERE PROFILEID='$profileid' AND MATCH_ID IN('$calls')";
                $res=mysql_query_decide($sql) or die("error while fetching leads info  ".$sql."  ".mysql_error_js());
                while($row=mysql_fetch_assoc($res))
                        $folderArr[$row["MATCH_ID"]]=$row["FOLDER"];
        }
	$details["FOLDER_INFO"]=$folderArr;
	$details["DISABLE_LEADS"]=$disableLead;
	return $details;
}

function dispatchComments($profileid,$profileArr)
{
	if(is_array($profileArr) && $profileid)
	{
		$profiles=implode("','",$profileArr);
		$sql="UPDATE Assisted_Product.AP_MATCH_COMMENTS SET SENT='Y' WHERE PROFILEID='$profileid' AND MATCH_ID IN('$profiles')";
		mysql_query_decide($sql) or die("Error while updating match comments   ".mysql_error_js());
	}
}

function getProfileMoveCount($idArr,$profileid,$list)
{
	if(is_array($idArr) && $profileid)
	{
		$idString=implode("','",$idArr);
		$sql="SELECT COUNT(*) AS COUNT,MATCH_ID FROM Assisted_Product.AP_AUDIT_TRAIL WHERE MATCH_ID IN('$idString') AND DESTINATION='$list' AND PROFILEID='$profileid' GROUP BY MATCH_ID";
		$res=mysql_query_decide($sql) or die("Error while fetching counts");
		while($row=mysql_fetch_assoc($res))
		{
			$profileMoveCount[$row["MATCH_ID"]]=$row["COUNT"];
		}
		return $profileMoveCount;
	}
}
//Function to be called when match point profile with intro calls/auto apply/profile home delivery is deleted
?>
