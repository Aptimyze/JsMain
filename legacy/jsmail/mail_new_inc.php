<?php
function new_mails()
{
	global $smarty,$_SERVER,$CITY_INDIA_DROP,$COUNTRY_DROP,$EDUCATION_LEVEL_NEW_DROP,$HEIGHT_DROP,$OCCUPATION_DROP,$CASTE_DROP,$MTONGUE_DROP,$CITY_USA_DROP,$INCOME_DROP,$RELIGIONS,$CITY_DROP,$db,$protect_obj,$mysqlObj,$NewMatchesMail;

	if($NewMatchesMail)			//For New Matches Mails
	{
		$smarty->assign("STYPE_ALBUM_PAGE","F");
		$smarty->assign('STYPE',"");
		$smarty->assign('NewMatchesMail',1);
		$mailsSendArr["PROFILES_MAIL_SENT"] = 0;
		for($i=1;$i<=10;$i++)
		{
			$mailsSendArr[$i."_MATCH"] = 0;
		}
	}
	else
	{
		$smarty->assign("STYPE_ALBUM_PAGE","B");
		$smarty->assign('STYPE',"matchalert1");
	}

	$smarty->assign("TOLL_FREE","1800-419-6299");
	$smarty->assign("RENEW_DISCOUNT","15");
	$smarty->assign("LANDLINE","0120-4393500");
	$IMG_PATH = JsConstants::$imgUrl."/profile/images/jsmail/newMatches";
	$smarty->assign("IMG_PATH",$IMG_PATH);
	
	if($_SERVER['argc']<=1)
        {
                die("Usage: php -q matchalert_220_mail.php <total-scripts> <number-of-this-script>");
        }
        else
        {
                $total_scripts=$_SERVER['argv'][1];
                $this_script=$_SERVER['argv'][2];
        }

	if($NewMatchesMail)
	{
		$sql="SELECT * FROM new_matches_emails.MAILER where SENT<>'Y' AND  (USER1!='')   AND  MOD(SNO,".$total_scripts.")=".$this_script;
		//$sql="SELECT * FROM new_matches_emails.MAILER where SENT<>'Y' AND  (USER1!='')   AND RECEIVER='144111'";
	}
	else
	{
		$sql="SELECT * FROM matchalerts.MAILER where SENT<>'Y' AND  (USER1!='')   AND  MOD(SNO,".$total_scripts.")=".$this_script;
		//$sql="SELECT * FROM matchalerts.MAILER where SENT<>'Y' AND  (USER1!='')   AND RECEIVER='144111'";
	}
     	$result=$mysqlObj->executeQuery($sql,$db) or die(mysql_error());

	while($myrow= $mysqlObj->fetchArray($result))
	{
		unset($user);
		unset($matchesData);
		unset($receiverData);

		$logic=$myrow["LOGIC_USED"];
            	$smarty->assign("logic",$logic);
		if(!$NewMatchesMail)
		{
                	$is_user_active=$myrow["IS_USER_ACTIVE"];
                	$smarty->assign("is_user_active",$is_user_active);
		}

		$receiver=$myrow["RECEIVER"];
		$smarty->assign("PID",$receiver);
		$profilechecksum=md5($receiver)."i".$receiver;
		$receiverData = getReceiverData($receiver);
		$receiverData = getJsCenterDetails($receiverData);
		getDppData($receiver);
		$echecksum=$protect_obj->js_encrypt($profilechecksum,$receiverData["EMAIL"]);

		$smarty->assign('ECHECKSUM',$echecksum);
		$smarty->assign('RECEIVER_PROFILECHECKSUM',$profilechecksum);

		if($myrow["USER1"])
			$user[]=$myrow["USER1"];
		if($myrow["USER2"])
			$user[]=$myrow["USER2"];
		if($myrow["USER3"])
			$user[]=$myrow["USER3"];
		if($myrow["USER4"])
			$user[]=$myrow["USER4"];
		if($myrow["USER5"])
			$user[]=$myrow["USER5"];
		if($myrow["USER6"])
			$user[]=$myrow["USER6"];
		if($myrow["USER7"])
			$user[]=$myrow["USER7"];
		if($myrow["USER8"])
			$user[]=$myrow["USER8"];
		if($myrow["USER9"])
			$user[]=$myrow["USER9"];
		if($myrow["USER10"])
			$user[]=$myrow["USER10"];

		$matchesData = getMatchesData($user,$receiver,$receiverData["GENDER"]);

		if($matchesData && is_array($matchesData) && count($matchesData))	
		{
			$smarty->assign("matchesData",$matchesData);
			if($receiverData["NAME"])
				$smarty->assign("receiverName",$receiverData["NAME"]);
			else
				$smarty->assign("receiverName",$receiverData["USERNAME"]);
			$smarty->assign("CONTACT_PERSON",$receiverData["CONTACT_PERSON"]);
			$smarty->assign("CONTACT_ADDRESS",$receiverData["CONTACT_ADDRESS"]);
			$smarty->assign("CONTACT_NO",$receiverData["CONTACT_NO"]);
			$smarty->assign("GENDER",$receiverData["GENDER"]);
			$smarty->assign("SENT_DATE",getLogicalDate());
			if($NewMatchesMail)
			{
				$smarty->assign("IS_MORE_LINK_REQUIRED",$myrow["LINK_REQUIRED"]);
				$smarty->assign("RELAX_CRITERIA",$myrow["RELAX_CRITERIA"]);
			}
			else
				$smarty->assign("FREQUENCY",$myrow["FREQUENCY"]);

			getFtoData($receiver);
			getVaribaleDiscountDetails($receiver);

			if($receiverData["INCOMPLETE"]=='Y')
				$smarty->assign("incomplete",1);
			else
				$smarty->assign("incomplete",0);
			if(strstr($receiverData["SUBSCRIPTION"],'F'))
			{
				$smarty->assign("PAID",1);
				checkBilling($receiver);
			}
			else
				$smarty->assign("PAID",0);
			if($receiverData["RELIGION"]==2 || $receiverData["RELIGION"]==3)
				$smarty->assign("SHOW_SECT",1);
			else
				$smarty->assign("SHOW_SECT",0);
			$to=$receiverData["EMAIL"];
			$msg = $smarty->fetch("match-alert_new.htm");
			$from = "matchalert@jeevansathi.com";
			$subject = getSubject($logic,$matchesData,$receiverData);
			if($subject)
			{
				//$to = 'lavesh.rawat@gmail.com';
				//echo $msg;
				send_email($to,$msg,$subject,$from,"","","","","","",1,"");
				if($NewMatchesMail)
				{
					$mailsSendArr["PROFILES_MAIL_SENT"] = $mailsSendArr["PROFILES_MAIL_SENT"]+1;
					$mailsSendArr[count($matchesData)."_MATCH"] = $mailsSendArr[count($matchesData)."_MATCH"]+1;
				}
			}
			else
				mail("lavesh.rawat@gmail.com","Subject Blank In Matchalert","Subject Blank In Matchlaert for receiver - ".$receiver);
		}
		if($NewMatchesMail)
			$sql="UPDATE  new_matches_emails.MAILER SET SENT='Y' WHERE RECEIVER='$receiver'";
		else
			$sql="UPDATE  matchalerts.MAILER SET SENT='Y' WHERE RECEIVER='$receiver'";
             	$mysqlObj->executeQuery($sql,$db) or $mysqlObj->logError("In mail_inc.php while updating MAILER - ".$sql);
	}

	if($NewMatchesMail && $mailsSendArr && is_array($mailsSendArr) && $mailsSendArr["PROFILES_MAIL_SENT"]>0)
	{
		$trackObj = new TrackingFunctions("",$mysqlObj);
		$trackObj->trackingMis($mailsSendArr);
		unset($trackObj);
	}
	unset($mailsSendArr);
}

function checkBilling($receiver)
{
	global $smarty,$db,$mysqlObj;	

	$sql="SELECT EXPIRY_DT,SERVICEID from billing.SERVICE_STATUS where PROFILEID='$receiver' AND ACTIVE='Y' AND SERVEFOR LIKE '%F%' order by ID desc";
	$result_2=$mysqlObj->executeQuery($sql,$db) or $mysqlObj->logError("In mail_inc.php while selecting EMAIL,etc - ".$sql);
	$myrow_2=$mysqlObj->fetchArray($result_2);
	$renew_status=$myrow_2["EXPIRY_DT"];

	if($renew_status && !strstr($myrow_2['SERVICEID'],'L') && $renew_status!="0000-00-00" && $renew_status!="0000-00-00 00:00:00")
	{
		$curdate=date('Y-m-d');

		$daysDiff = (strtotime($curdate)-strtotime($renew_status))/(60*60*24);
		if($daysDiff<=10 && $daysDiff>-30)
		{
			$smarty->assign("RENEW_TEXT_DISC",1);
		}
		else
			$smarty->assign("RENEW_TEXT_DISC",0);

		$exp_dt=date('jS M Y',strtotime($renew_status));
		if($daysDiff>-30 && $daysDiff<=0)
		{
			$smarty->assign('pmsg',"Your membership will expire on $exp_dt. To avail uninterrupted services :");
			$smarty->assign("SUBS1",0);
		}
		elseif($daysDiff>0)
		{
			$smarty->assign('pmsg',"Your membership expired on $exp_dt. To avail uninterrupted services :");
			$smarty->assign("SUBS1",0);
		}
		else
			$smarty->assign("SUBS1",1);
	}
	else
		$smarty->assign("SUBS1",1);
}

function getDppData($receiver)
{
	global $jpartnerObj,$db,$myDbArr,$mysqlObj,$RELIGIONS,$CASTE_DROP,$INCOME_DROP,$MTONGUE_DROP_SMALL,$smarty,$HEIGHT_DROP,$MSTATUS_DROP;

	$myDbName=getProfileDatabaseConnectionName($receiver,'slave',$mysqlObj,$db);
      	$myDb=$myDbArr[$myDbName];

	// query to check whether user has filled his/her partner profile or not
	$jpartnerObj->setPROFILEID($receiver);

	$jpartnerObj->setPartnerDetails($receiver,$myDb,$mysqlObj);
//print_r($jpartnerObj);
//print_r($receiver);
//print_r($db);
//die;
	if($jpartnerObj->isPartnerProfileExist($myDb,$mysqlObj,$receiver))
	{
		$HAVEPARTNER='Y';

		if(($jpartnerObj->getLAGE())!='' && ($jpartnerObj->getHAGE())!='')
			$smarty->assign("PARTNER_AGE",$jpartnerObj->getLAGE()." to ".$jpartnerObj->getHAGE());
		else
			$smarty->assign("PARTNER_AGE",'');
		if(($jpartnerObj->getLHEIGHT())!="" && ($jpartnerObj->getHHEIGHT())!="")
		{
			$lheight=$HEIGHT_DROP[$jpartnerObj->getLHEIGHT()];
			$hheight=$HEIGHT_DROP[$jpartnerObj->getHHEIGHT()];
			$lheight1=explode("(",$lheight);
			$hheight1=explode("(",$hheight);
			$smarty->assign("PARTNER_HEIGHT",$lheight1[0] . " to " . $hheight1[0]);
		}

		if($jpartnerObj->getPARTNER_MSTATUS())
		{
			$FILTER_MSTATUS=matchalert_display_format($jpartnerObj->getPARTNER_MSTATUS());
			foreach($FILTER_MSTATUS as $k=>$v)
			{
				if($v=='O')
					$temp.="Other, ";
				elseif($MSTATUS_DROP[$v])
					$temp.=$MSTATUS_DROP[$v].", ";
					
			}
		}
		if($temp)
		{
			$temp=@substr($temp,0,-2);
			$smarty->assign("PARTNER_MSTATUS",$temp);
			unset($temp);
		}
		else
			$smarty->assign("PARTNER_MSTATUS",'');

		if($jpartnerObj->getPARTNER_MTONGUE())
		{
			$FILTER_MTONGUE=matchalert_display_format($jpartnerObj->getPARTNER_MTONGUE());
			foreach($FILTER_MTONGUE as $k=>$v)
			{
				$temp.=$MTONGUE_DROP_SMALL[$v].", ";
			}
		}
		if($temp)
		{
			$temp=@substr($temp,0,-2);
			if(strlen($temp)>55)
				$temp=substr($temp,0,55)."...";
			$smarty->assign("PARTNER_MTONGUE",$temp);
			unset($temp);
		}
		else
			$smarty->assign("PARTNER_MTONGUE",'');

		if($jpartnerObj->getPARTNER_RELIGION() && !$jpartnerObj->getPARTNER_CASTE())
		{
			$FILTER_MSTATUS=matchalert_display_format($jpartnerObj->getPARTNER_RELIGION());
			foreach($FILTER_MSTATUS as $k=>$v)
			{
				$temp.=$RELIGIONS[$v].", ";
			}
		}
		if($temp)
		{
			$temp=@substr($temp,0,-2);
			$temp1=$temp;
			unset($temp);
		}

		if($jpartnerObj->getPARTNER_CASTE())
		{
			$FILTER_MSTATUS=matchalert_display_format($jpartnerObj->getPARTNER_CASTE());
			foreach($FILTER_MSTATUS as $k=>$v)
			{
				$temp.=$CASTE_DROP[$v].", ";
			}
		}
		if($temp)
		{
			$temp=@substr($temp,0,-2);
			if($temp1 && $temp)
				$temp=$temp1." / ".$temp;
			elseif($temp1)
				$temp=$temp1;
		}
		if($temp)
		{
			if(strlen($temp)>55)
				$temp=substr($temp,0,55)."...";
			$smarty->assign("RELIGION_CASTE_INFO",$temp);
		}
		else
			$smarty->assign("RELIGION_CASTE_INFO",$temp1);
		unset($temp1);
		unset($temp);

		unset($incomeRangeArr);
		if($jpartnerObj->getLINCOME() || $jpartnerObj->getLINCOME()=='0')
		{
			$incomeRangeArr["minIR"]=$jpartnerObj->getLINCOME();
			$incomeRangeArr["maxIR"]=$jpartnerObj->getHINCOME();
		}
		if($jpartnerObj->getLINCOME_DOL() || $jpartnerObj->getLINCOME_DOL()=='0')
		{
			$incomeRangeArr["minID"]=$jpartnerObj->getLINCOME_DOL();
			$incomeRangeArr["maxID"]=$jpartnerObj->getHINCOME_DOL();
		}
		if($incomeRangeArr)
		{
			global $INCOME_MAX_DROP,$INCOME_MIN_DROP;
			$varr=getIncomeText($incomeRangeArr);
			$smarty->assign("PARTNER_INCOME",implode(", ",$varr));
		}
		else
			$smarty->assign("PARTNER_INCOME",'');

	}
	else
	{
		$HAVEPARTNER='N';
		$smarty->assign("PARTNER_AGE",'');
		$smarty->assign("PARTNER_HEIGHT",'');
		$smarty->assign("PARTNER_MSTATUS",'');
		$smarty->assign("RELIGION_CASTE_INFO",'');
		$smarty->assign("PARTNER_INCOME",'');
		$smarty->assign("PARTNER_MTONGUE",'');
     	}	
}

function getReceiverData($receiver)
{
	global $db,$mysqlObj;

	$select_statement = "SELECT n.NAME AS NAME,j.EMAIL AS EMAIL,j.USERNAME AS USERNAME,j.GENDER AS GENDER,j.INCOMPLETE AS INCOMPLETE,j.SUBSCRIPTION AS SUBSCRIPTION,j.CITY_RES AS CITY_RES,j.RELIGION AS RELIGION FROM newjs.JPROFILE j LEFT JOIN incentive.NAME_OF_USER n ON j.PROFILEID=n.PROFILEID WHERE j.PROFILEID = ".$receiver;
      	$result = $mysqlObj->executeQuery($select_statement,$db) or $mysqlObj->logError($select_statement);
      	$row = $mysqlObj->fetchArray($result);

     	return $row;
}

function getMatchesData($user,$receiver,$receiverGender)	
{
	if($receiverGender=="M")
		$matchGender="F";
	elseif($receiverGender=="F")
		$matchGender="M";
	else
	{
		mail("lavesh.rawat@gmail.com,lavesh.rawat@jeevansathi.com","Gender Blank In getMatchesData","Gender Blank In getMatchesData() for receiver - ".$receiver);
		return NULL;
		//die;
	}

	global $db,$HEIGHT_DROP,$RELIGIONS,$CASTE_DROP,$MTONGUE_DROP,$EDUCATION_LEVEL_NEW_DROP,$OCCUPATION_DROP,$INCOME_DROP,$CITY_DROP,$CITY_INDIA_DROP,$COUNTRY_DROP,$mysqlObj,$NewMatchesMail,$db_211;

	$sql = "SELECT j.PROFILEID AS PROFILEID,n.NAME AS NAME,j.USERNAME AS USERNAME,j.HAVEPHOTO AS HAVEPHOTO,j.PRIVACY AS PRIVACY,j.PHOTO_DISPLAY AS PHOTO_DISPLAY,j.GENDER AS GENDER,j.AGE AS AGE,j.HEIGHT AS HEIGHT,j.RELIGION AS RELIGION,j.CASTE AS CASTE,j.MTONGUE AS MTONGUE,j.EDU_LEVEL_NEW AS EDU_LEVEL_NEW,DATE(j.ENTRY_DT) AS ENTRY_DT,j.OCCUPATION AS OCCUPATION,j.INCOME AS INCOME,j.CITY_RES AS CITY_RES,j.COUNTRY_RES AS COUNTRY_RES FROM newjs.JPROFILE j LEFT JOIN incentive.NAME_OF_USER n ON j.PROFILEID = n.PROFILEID WHERE j.ACTIVATED = \"Y\" AND j.GENDER = \"".$matchGender."\" AND j.PROFILEID IN (".implode(",",$user).") ORDER BY FIELD (j.PROFILEID,".implode(",",$user).")";
	$result1=$mysqlObj->executeQuery($sql,$db) or $mysqlObj->logError("In mail_inc.php while selecting details from JPROFILE - ".$sql);
      	$i=0;
	
	while($row = $mysqlObj->fetchArray($result1))
	{
		$matchesData[$i]["PROFILEID"] = $row["PROFILEID"];
     		$matchesData[$i]["USERNAME"] = $row["USERNAME"];
     		$matchesData[$i]["NAME"] = $row["NAME"];
             	$matchesData[$i]["HAVEPHOTO"] = $row["HAVEPHOTO"];
             	$matchesData[$i]["PRIVACY"] = $row["PRIVACY"];
           	$matchesData[$i]["PHOTO_DISPLAY"] = $row["PHOTO_DISPLAY"];
          	$matchesData[$i]["GENDER"] = $row["GENDER"];
           	$matchesData[$i]["AGE"] = $row["AGE"];
           	$temp = explode("(",$HEIGHT_DROP[$row["HEIGHT"]]);
           	$matchesData[$i]["HEIGHT"] = trim($temp[0]);
           	$matchesData[$i]["RELIGION"] = $RELIGIONS[$row["RELIGION"]];
           	$matchesData[$i]["RELIGION_NO"] = $row["RELIGION"];
          	if ($row["RELIGION"])
             	{
                 	$temp = explode(":",$CASTE_DROP[$row["CASTE"]]);
                     	$matchesData[$i]["CASTE"] = trim($temp[1]);
            	}
             	else
            	{
                   	$matchesData[$i]["CASTE"] = $CASTE_DROP[$row["CASTE"]];
            	}
             	$matchesData[$i]["MTONGUE"] = $MTONGUE_DROP[$row["MTONGUE"]];
             	$matchesData[$i]["EDU_LEVEL_NEW"] = $EDUCATION_LEVEL_NEW_DROP[$row["EDU_LEVEL_NEW"]];
           	$matchesData[$i]["OCCUPATION"] = $OCCUPATION_DROP[$row["OCCUPATION"]];
             	$matchesData[$i]["ENTRY_DT"] = $row["ENTRY_DT"];

		$days = round(abs(strtotime(date("Y-m-d"))-strtotime($row["ENTRY_DT"]))/(60*60*24));
		if($days == 0)
			$matchesData[$i]["ENTRY_DT_LABEL"] = "Joined today";
		elseif($days == 1)
			$matchesData[$i]["ENTRY_DT_LABEL"] = "Joined yesterday";
		else
			$matchesData[$i]["ENTRY_DT_LABEL"] = "Joined ".$days." days ago";

             	$matchesData[$i]["INCOME"] = $INCOME_DROP[$row["INCOME"]];
             	$matchesData[$i]["INCOME_SUBJECT"] = $row["INCOME"];
                if(is_numeric($row["CITY_RES"]))
               		$matchesData[$i]["CITY_RES"] = $CITY_DROP[$row["CITY_RES"]];
            	else
                 	$matchesData[$i]["CITY_RES"] = $CITY_INDIA_DROP[$row["CITY_RES"]];
               	$matchesData[$i]["CITY_RES_SUBJECT"] = $row["CITY_RES"];
             	$matchesData[$i]["COUNTRY_RES"] = $COUNTRY_DROP[$row["COUNTRY_RES"]];
             	$matchesData[$i]["COUNTRY_RES_SUBJECT"] = $row["COUNTRY_RES"];
		$matchesData[$i]["PROFILECHECKSUM"] = md5($row["PROFILEID"])."i".$row["PROFILEID"];
		$matchesData[$i]["ALBUM_LINK"] = urlencode(JsConstants::$siteUrl.'/profile/layer_photocheck.php?profilechecksum='.$matchesData[$i]["PROFILECHECKSUM"].'&seq=1');
            	$i++;
	}

	if($matchesData && is_array($matchesData) && count($matchesData))
	{
		foreach($matchesData as $k=>$v)
			$matchesUser[] = $v["PROFILEID"];

		if($NewMatchesMail)
		{
			$sql = "SELECT VIEWED,DATE(DATE) AS DT FROM newjs.VIEW_LOG_TRIGGER WHERE VIEWER=".$receiver." AND VIEWED IN (".implode(",",$matchesUser).")";
			$result=$mysqlObj->executeQuery($sql,$db_211) or $mysqlObj->logError("In mail_inc.php while quering VIEW_LOG_TRIGGER - ".$sql);
			while($row = $mysqlObj->fetchArray($result))
			{
				if($row["DT"] && $row["DT"]!="0000-00-00")
				{
					$viewedArr[$row["VIEWED"]] = date('j-M-Y', strtotime($row["DT"]));
				}
			}
		}

		if($NewMatchesMail)
			$sql = "SELECT USER,LOGICLEVEL FROM new_matches_emails.LOG_TEMP WHERE RECEIVER = ".$receiver." AND USER IN (".implode(",",$matchesUser).")";
		else
			$sql = "SELECT USER,LOGICLEVEL FROM matchalerts.LOG_TEMP WHERE RECEIVER = ".$receiver." AND USER IN (".implode(",",$matchesUser).")";
		$result=$mysqlObj->executeQuery($sql,$db) or $mysqlObj->logError("In mail_inc.php while selecting logic levels from LOG_TEMP - ".$sql);
		while($row = $mysqlObj->fetchArray($result))
        	{
			$levelArr[$row["USER"]] = $row["LOGICLEVEL"];
		}

		foreach($matchesData as $k=>$v)
		{
			$matchesData[$k]["LOGICLEVEL"] = getStpyeForLogicLevel($levelArr[$v["PROFILEID"]]);
			$matchesData[$k]["VIEWED_DATE"] = $viewedArr[$v["PROFILEID"]];
		}
		unset($levelArr);
		unset($matchesUser);
		unset($viewedArr);

		$matchesData = getPhotoWithPrivacy($matchesData,$user);
		$matchesData = sortForPhotos($matchesData,$receiver);
	}

	return $matchesData;
}

function getStpyeForLogicLevel($logiclevel)
{
	if($logiclevel==11)
		$stype = "Ba";
	elseif($logiclevel==111)
		$stype = "B1";
	elseif($logiclevel==12)
		$stype = "Bb"; 
	elseif($logiclevel==121)
		$stype = "B2"; 
	elseif($logiclevel==13)
		$stype = "Bc";
	elseif($logiclevel==131)
		$stype = "B3";
	elseif($logiclevel==14)
		$stype = "Bd";
	elseif($logiclevel==141)
		$stype = "B4";
	elseif($logiclevel==15)
		$stype = "Be";
	elseif($logiclevel==16)
		$stype = "Bf";
	elseif($logiclevel==17)
		$stype = "Bg";
	elseif($logiclevel==18)
		$stype = "Bh";
	elseif($logiclevel==21)
		$stype = "Bi";
	elseif($logiclevel==211)
		$stype = "B5";
	elseif($logiclevel==22)
		$stype = "Bj";
	elseif($logiclevel==221)
		$stype = "B6";
	elseif($logiclevel==23)
		$stype = "Bk";
	elseif($logiclevel==231)
		$stype = "B7";
	elseif($logiclevel==24)
		$stype = "Bl";
	elseif($logiclevel==241)
		$stype = "B8";
	elseif($logiclevel==25)
		$stype = "Bm";
	elseif($logiclevel==26)
		$stype = "Bn";
	elseif($logiclevel==27)
		$stype = "Bo";
	elseif($logiclevel==28)
		$stype = "Bp";
	elseif($logiclevel==31)
		$stype = "Bq";
	elseif($logiclevel==32)
		$stype = "Br";
	elseif($logiclevel==33)
		$stype = "Bs";
	elseif($logiclevel==41)
		$stype = "Bt";
	elseif($logiclevel==42)
		$stype = "Bu";
	elseif($logiclevel==43)
		$stype = "Bv";
	elseif($logiclevel==51)
		$stype = "F1";
	elseif($logiclevel==52)
		$stype = "F2";
	elseif($logiclevel==53)
		$stype = "F3";
	elseif($logiclevel==61)
		$stype = "F4";
	elseif($logiclevel==62)
		$stype = "F5";
	elseif($logiclevel==63)
		$stype = "F6";
	return $stype;
}

function sortForPhotos($matchesData,$receiver)
{
	global $NewMatchesMail,$mysqlObj,$db;
	if($NewMatchesMail)
	{
		foreach($matchesData as $k=>$v)
                        $matchesUser[] = $v["PROFILEID"];

		$sql = "SELECT USER FROM matchalerts.LOG WHERE RECEIVER = ".$receiver." AND USER IN (".implode(",",$matchesUser).")";
		$result=$mysqlObj->executeQuery($sql,$db) or $mysqlObj->logError("In mail_inc.php while selecting USER from matchalerts.LOG - ".$sql);
                while($row = $mysqlObj->fetchArray($result))
                {
                        $alreadySentArr[$row["USER"]] = 1;
                }
	}

	foreach($matchesData as $k=>$v)
	{
		if($v["HAVEPHOTO"]=="Y")
		{
			if($v["PHOTO_DISPLAY"]!="C")
			{
				if(!$alreadySentArr[$v["PROFILEID"]])
					$sortArr[$v["PROFILEID"]] = 1;
				else
					$sortArr[$v["PROFILEID"]] = 2;
			}
			else
			{
				if(!$alreadySentArr[$v["PROFILEID"]])
					$sortArr[$v["PROFILEID"]] = 3;
				else
					$sortArr[$v["PROFILEID"]] = 4;
			}
		}
		elseif($v["HAVEPHOTO"]=="U")
		{
			if(!$alreadySentArr[$v["PROFILEID"]])
				$sortArr[$v["PROFILEID"]] = 5;
			else
				$sortArr[$v["PROFILEID"]] = 6;
		}
		else
		{
			if(!$alreadySentArr[$v["PROFILEID"]])
				$sortArr[$v["PROFILEID"]] = 7;
			else
				$sortArr[$v["PROFILEID"]] = 8;
		}
	}
	asort($sortArr);

	$i=0;
	foreach($sortArr as $k=>$v)
	{
		foreach($matchesData as $kk=>$vv)
		{
			if($vv["PROFILEID"]==$k)
			{
				foreach($vv as $kkk=>$vvv)
				{
					$matchesDataFinal[$i][$kkk]=$vvv;
				}
				$i++;
				break;
			}
		}
	}
	return $matchesDataFinal;
}

function matchalert_display_format($str)
{
        if($str)
        {
                $str=trim($str,"'");
                $arr=explode("','",$str);
                return $arr;
        }
}

function getPhotoWithPrivacy($matchesData,$matchingIds)
{
	global $db;
	$searchPicUrls = SymfonyPictureFunctions :: getPhotoUrls_nonSymfony($matchingIds,'SearchPicUrl',$db);

	foreach($matchesData as $k=>$v)
	{
		if($v['HAVEPHOTO']=='Y' && $v['PHOTO_DISPLAY']=='C')
		{
			if($v['GENDER']=='M')
				$picUrl=JsConstants::$imgUrl."/profile/ser4_images/ic_photo_vis_if_b_100.gif";
			elseif($v['GENDER']=='F')
				$picUrl=JsConstants::$imgUrl."/profile/ser4_images/ic_photo_vis_if_g_100.gif";
		}
		else
		{
			if($v['HAVEPHOTO']=='Y' && is_array($searchPicUrls) && $searchPicUrls[$v["PROFILEID"]]['SearchPicUrl'])
			{
				$picUrl=$searchPicUrls[$v["PROFILEID"]]['SearchPicUrl'];
			}
			elseif($v['GENDER']=='M')
			{
				if($v['HAVEPHOTO']=='N' || $v['HAVEPHOTO']=='')
				{
					$picUrl=JsConstants::$imgUrl."/profile/ser4_images/ic_request_photo_b_100.gif";
				}
				elseif($v['HAVEPHOTO']=='U')
				{
					$picUrl=JsConstants::$imgUrl."/profile/ser4_images/ic_photo_coming_b_100.gif";
				}
				else
				{
					$picUrl=JsConstants::$imgUrl."/profile/ser4_images/ic_request_photo_b_100.gif";
				}
			}
			elseif($v['GENDER']=='F')
			{
				if($v['HAVEPHOTO']=='N' || $v['HAVEPHOTO']=='')
				{
					$picUrl=JsConstants::$imgUrl."/profile/ser4_images/ic_request_photo_g_100.gif";
				}
				elseif($v['HAVEPHOTO']=='U')
				{
					$picUrl=JsConstants::$imgUrl."/profile/ser4_images/ic_photo_coming_g_100.gif";
				}
				else
				{
					$picUrl=JsConstants::$imgUrl."/profile/ser4_images/ic_request_photo_g_100.gif";
				}
			}
		}
		$matchesData[$k]["SearchPicUrl"] = $picUrl;
		unset($picUrl);
	}
	return $matchesData;
}

function getJsCenterDetails($receiverData)
{
	$output = CommonFunction::getJsCenterDetails($receiverData["CITY_RES"]);
	if($output)
	{
		$receiverData["CONTACT_PERSON"] = $output["AGENT"];
                $receiverData["CONTACT_ADDRESS"] = $output["LOCALITY"];
                $receiverData["CONTACT_NO"] = $output["MOBILE"];
	}
	return $receiverData;
}

function nameLogic($name)
{
	if($name)
	{
		$tempArr = explode(" ",$name);
		if(strlen($tempArr[0])>2)
		{
			$matchName = $tempArr[0];
		}
		elseif(strlen($name)>2)
		{
			$matchName = $name;
		}
		unset($tempArr);
	}
	return $matchName;	
}

function getSubject($logic,$arr,$receiverData)
{
	global $db,$mysqlObj,$NewMatchesMail;

	if($NewMatchesMail)
	{
		if($receiverData["NAME"])
			$receiverName = nameLogic($receiverData["NAME"]);
		
		if(!$receiverName)
			$receiverName = $receiverData["USERNAME"];

		if($arr[0]["NAME"])
			$matchName = nameLogic($arr[0]["NAME"]);	

		if(!$matchName)
			$matchName = $arr[0]["USERNAME"];

		$subject = "Hi ".$receiverName.", ".$matchName;
		if(count($arr) == 1)
			$subject = $subject." who matches your criteria has";
		elseif(count($arr) == 2)
			$subject = $subject." and one more match have";
		else
		{
			$totalMatches = count($arr);
                        $moreMatches = $totalMatches-1;
			$subject = $subject." and ".$moreMatches." more matches have";
		}
		$subject = $subject." joined Jeevansathi this week.";
	}
	else
	{
		if(strstr($receiverData["EMAIL"],"@gmail"))		//New subject logic only for gmail users
		{
			$totalMatches = count($arr);
			$moreMatches = $totalMatches-1;

			if($receiverData["GENDER"] == "F")
			{
				$maxIncomeProfilePos = -1;
				$maxIncomeProfileId = -1;

				foreach($arr as $k=>$v)
					$sortIncomeArr[$v["PROFILEID"]] = getSortByIncome($v['INCOME_SUBJECT']);
				arsort($sortIncomeArr);

				foreach($sortIncomeArr as $k=>$v)
				{
					if($maxIncomeProfileId<0)
						$maxIncomeProfileId = $k;

					foreach($arr as $kk=>$vv)
					{
						if($vv["PROFILEID"] == $k)
						{
							if($maxIncomeProfilePos<0)
								$maxIncomeProfilePos = $kk;

							if($vv["NAME"])
							{
								$matchName = nameLogic($vv["NAME"]);
							}
							break;
						}
					}
					if($matchName)
						break;
				}
			}
			elseif($receiverData["GENDER"] == "M")
			{
				$maxIncomeProfilePos = 0;

				foreach($arr as $kk=>$vv)
				{
					if($vv["NAME"])
					{
						$matchName = nameLogic($vv["NAME"]);
					}
					if($matchName)
						break;
				}
			}
			else
				return null;

			if($receiverData["NAME"])
			{
				$receiverName = nameLogic($receiverData["NAME"]);
			}

			if($logic == 1 || $logic == 3)		//Reverse Non Trend
			{
				if($receiverName)
					$subject = $receiverName.", get";
				else
					$subject = "Get";
				$subject = $subject." introduced to";
			}
			elseif($logic == 2 || $logic == 4)      //Reverse Trend
			{
				if($receiverName)
					$subject = $receiverName.", meet";
				else
					$subject = "Meet";
			}
			else
				return null;

			if($matchName)
			{
				$subject = $subject." ".$matchName;
			}
			else
			{
				$subject = $subject." ".$arr[$maxIncomeProfilePos]["AGE"]." yr,";
				if($receiverData["GENDER"] == "F")
				{
					$subject = $subject." ".$arr[$maxIncomeProfilePos]["INCOME"]." income, ".str_replace("&quot;","\"",$arr[$maxIncomeProfilePos]["HEIGHT"]).", ".$arr[$maxIncomeProfilePos]["CASTE"];
				}
				elseif($receiverData["GENDER"] == "M")
				{
					$subject = $subject." ".$arr[$maxIncomeProfilePos]["CASTE"].", ".str_replace("&quot;","\"",$arr[$maxIncomeProfilePos]["HEIGHT"]).", ".$arr[$maxIncomeProfilePos]["EDU_LEVEL_NEW"];
				}
				if($arr[$maxIncomeProfilePos]["CITY_RES"])
					$subject = $subject." from ".$arr[$maxIncomeProfilePos]["CITY_RES"];
				elseif($arr[$maxIncomeProfilePos]["COUNTRY_RES"])
					$subject = $subject." from ".$arr[$maxIncomeProfilePos]["COUNTRY_RES"];
			}
			if($moreMatches)
				$subject = $subject." and ".$moreMatches." more people";
		}
		else
		{
			$maxIncome=-1;
			$maxIncomeIpos=-1;

			foreach($arr as $k=>$v)
			{
				if($receiverData["GENDER"] == 'F')
				{
					if($maxIncome<getSortByIncome($v['INCOME_SUBJECT']))
					{
						$maxIncomeIpos=$k;
						$maxIncome=getSortByIncome($v['INCOME_SUBJECT']);
					}
				}
				else
					$maxIncomeIpos=0;
			}

			$sql = "SELECT VALUE FROM matchalerts.DATEFORMATCHALERTSMAILER";
			$result = $mysqlObj->executeQuery($sql,$db) or die(mysql_error());
			$row = $mysqlObj->fetchArray($result);
			$currentDate = $row["VALUE"];

			$subject = "";
			$count = count($arr);

			if($logic==1 || $logic==3)
			{
				$subject="Match";
				if($count>1)
					$subject.="es";
				$subject.=" on ".$currentDate." ";
			}

			$subject.="Match of ";

			if($arr[$maxIncomeIpos]['AGE'])
				$subject.=$arr[$maxIncomeIpos]['AGE']." yr";

			if($receiverData["GENDER"] == 'F')
			{
				if($arr[$maxIncomeIpos]['INCOME'])
					$subject.=", ".$arr[$maxIncomeIpos]['INCOME']." income";
			}

			if($arr[$maxIncomeIpos]['CASTE'])
				$subject.=", ".trim($arr[$maxIncomeIpos]['CASTE']);
			if($arr[$maxIncomeIpos]['HEIGHT'])
				$subject.=", ".str_replace("&quot;","\"",$arr[$maxIncomeIpos]['HEIGHT']);

			if($receiverData["GENDER"] == 'M')
			{
				if($arr[$maxIncomeIpos]['EDU_LEVEL_NEW'])
					$subject.=", ".$arr[$maxIncomeIpos]['EDU_LEVEL_NEW'];
			}
			else
			{
				if($arr[$maxIncomeIpos]['MTONGUE'])
					$subject.=", ".$arr[$maxIncomeIpos]['MTONGUE'];
			}

			if($arr[$maxIncomeIpos]['CITY_RES'])
				$subject.=" from ".$arr[$maxIncomeIpos]['CITY_RES'];
			elseif($arr[$maxIncomeIpos]['COUNTRY_RES'])
				$subject.=" from ".$arr[$maxIncomeIpos]['COUNTRY_RES'];

			if($count>1)
			{
				$count=$count-1;
				$subject.=" and $count more";
			}
		}
	}
	return $subject;
}

function getFtoData($profileid)
{
        global $smarty,$db,$mysqlObj;
	$smarty->assign("FTO_WORTH",FTOLiveFlags::FTO_WORTH);
	$profileObj = Profile::getInstance('newjs_master',$profileid);
	$fto_exp_date = $profileObj->getPROFILE_STATE()->getFTOStates()->getExpiryDate();
	$smarty->assign("FTO_END_MONTH_UPPERCASE",strtoupper(date("M",JSstrToTime($fto_exp_date))));
	$smarty->assign("FTO_END_MONTH",date("M",JSstrToTime($fto_exp_date)));
	$smarty->assign("FTO_END_YEAR",date("Y",JSstrToTime($fto_exp_date)));
	$smarty->assign("FTO_END_DAY",date("d",JSstrToTime($fto_exp_date)));
	$smarty->assign("FTO_END_DAY_SUFFIX",date("S",JSstrToTime($fto_exp_date)));
	$smarty->assign("FTO_END_DAY_SINGLE_DOUBLE_DIGIT",date("j",JSstrToTime($fto_exp_date)));
	$fto_sub_state = JsCommon::getProfileState($profileObj);
	if($fto_sub_state == FTOSubStateTypes::FTO_ELIGIBLE_NO_PHONE_NO_PHOTO)	//C1
		$smarty->assign("MATCHALERT_CASE",1);
	elseif($fto_sub_state == FTOSubStateTypes::FTO_ELIGIBLE_HAVE_PHONE_NO_PHOTO)	//C2
		$smarty->assign("MATCHALERT_CASE",2);
	elseif($fto_sub_state == FTOSubStateTypes::FTO_ELIGIBLE_NO_PHONE_HAVE_PHOTO)	//C3
		$smarty->assign("MATCHALERT_CASE",3);
	elseif($fto_sub_state == FTOSubStateTypes::FTO_ACTIVE_LEAST_THRESHOLD)		//D1
		$smarty->assign("MATCHALERT_CASE",4);
	elseif($fto_sub_state == FTOSubStateTypes::FTO_ACTIVE_BELOW_LOW_THRESHOLD || $fto_sub_state == FTOSubStateTypes::FTO_ACTIVE_BETWEEN_LOW_HIGH_THRESHOLD || $fto_sub_state == FTOSubStateTypes::FTO_ACTIVE_ABOVE_HIGH_THRESHOLD)	//D2,D3,D4
		$smarty->assign("MATCHALERT_CASE",5);
	else
		$smarty->assign("MATCHALERT_CASE","");
}

function getVaribaleDiscountDetails($profileid)
{
	global $smarty;
	$vdObj = new VariableDiscount;
	$details = $vdObj->getDiscDetails($profileid);
	if($details && is_array($details))
	{
		if($details["EDATE"]!="0000-00-00" && $details["EDATE"]!="0000-00-00 00:00:00")
		{
			$smarty->assign("VARIABLE_DISCOUNT_OFFER",1);
			$smarty->assign("VARIABLE_DISCOUNT",$details["DISCOUNT"]);
			$smarty->assign("VD_END_MONTH",date("M",strtotime($details["EDATE"])));
        		$smarty->assign("VD_END_YEAR",date("Y",strtotime($details["EDATE"])));
        		$smarty->assign("VD_END_DAY",date("d",strtotime($details["EDATE"])));
        		$smarty->assign("VD_END_DAY_SUFFIX",date("S",strtotime($details["EDATE"])));
		}
		else
			$smarty->assign("VARIABLE_DISCOUNT_OFFER",0);
		
	}
	else
		$smarty->assign("VARIABLE_DISCOUNT_OFFER",0);
		
	unset($vdObj);
	unset($details);
}

function getLogicalDate()
{
	$today=mktime(0,0,0,date("m"),date("d"),date("Y")); //timestamp for today
        $zero=mktime(0,0,0,01,01,2006); //timestamp for 1 Jan 2006
        $gap=($today-$zero)/(24*60*60); //$gap is the no. of days since 1 Jan 2006.
	return $gap;
}

function decodeLogicalDate($gap)
{
	$zero=mktime(0,0,0,01,01,2006); //timestamp for 1 Jan 2006
	$gap = ($gap*24*60*60);
	$gap = $gap + $zero;
	$dateString = date("Y-m-d",$gap);
	return $dateString;
}
?>
