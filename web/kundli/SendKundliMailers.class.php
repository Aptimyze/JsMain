<?php
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

class SendKundliMailers
{
	private $db;
	private $mysqlObj;
	private $receiverId;
	private $kundli_paid;

	function __construct($db,$mysqlObj,$receiverId,$kundli_paid)
	{
		$this->db = $db;
		$this->mysqlObj = $mysqlObj;
		$this->receiverId = $receiverId;
		$this->kundli_paid = $kundli_paid;
	}

	public function fetchMatchesForMailers($mailerLimit)
	{
		if($this->kundli_paid)
			$table = "kundli_alert.MAILER_PAID";
		else
			$table = "kundli_alert.MAILER_UNPAID";
		
		$statement = "SELECT MATCHID FROM ".$table." WHERE PROFILEID = ".$this->receiverId." ORDER BY VENUS DESC , MARS DESC , GUNA DESC,ENTRY_DT DESC LIMIT ".$mailerLimit;
		$result = $this->mysqlObj->executeQuery($statement,$this->db) or $this->mysqlObj->logError($statement);
		while ($row = $this->mysqlObj->fetchArray($result))
                {
                        $output[] = $row["MATCHID"];
                }
		return $output;
	}

	public function fetchName()
	{
		$select_statement = "SELECT n.NAME AS NAME,j.EMAIL AS EMAIL,j.USERNAME AS USERNAME FROM newjs.JPROFILE j LEFT JOIN incentive.NAME_OF_USER n ON j.PROFILEID=n.PROFILEID WHERE j.PROFILEID = ".$this->receiverId;
		$result = $this->mysqlObj->executeQuery($select_statement,$this->db) or $this->mysqlObj->logError($select_statement);
		$row = $this->mysqlObj->fetchArray($result);
		if(!$row["NAME"])
			$row["NAME"] = $row["USERNAME"];
		
		return $row;
	}

	public function fetchMatchData($matchingIds,$profileid)
	{
		if($this->kundli_paid)
			$table = "kundli_alert.MAILER_PAID";
		else
			$table = "kundli_alert.MAILER_UNPAID";
		
		global $HEIGHT_DROP,$RELIGIONS,$CASTE_DROP,$MTONGUE_DROP,$EDUCATION_LEVEL_NEW_DROP,$OCCUPATION_DROP,$INCOME_DROP,$CITY_DROP,$CITY_INDIA_DROP,$COUNTRY_DROP;
		$select_statement = "SELECT j.PROFILEID AS PROFILEID,j.USERNAME AS USERNAME,j.HAVEPHOTO AS HAVEPHOTO,j.PRIVACY AS PRIVACY,j.PHOTO_DISPLAY AS PHOTO_DISPLAY,j.GENDER AS GENDER,j.AGE AS AGE,j.HEIGHT AS HEIGHT,j.RELIGION AS RELIGION,j.CASTE AS CASTE,j.MTONGUE AS MTONGUE,j.EDU_LEVEL_NEW AS EDU_LEVEL_NEW,j.ENTRY_DT AS ENTRY_DT,j.OCCUPATION AS OCCUPATION,j.INCOME AS INCOME,j.CITY_RES AS CITY_RES,j.COUNTRY_RES AS COUNTRY_RES,k.GUNA AS GUNA,k.LAGNA AS LAGNA,k.SUN AS SUN,k.MERCURY AS MERCURY,k.JUPITER AS JUPITER,k.SATURN AS SATURN,k.MARS AS MARS,k.VENUS AS VENUS,jc.LINKEDIN_URL FROM ((newjs.JPROFILE j LEFT JOIN newjs.JPROFILE_CONTACT jc ON j.PROFILEID = jc.PROFILEID) INNER JOIN ".$table." k ON j.PROFILEID = k.MATCHID) WHERE j.ACTIVATED = \"Y\" AND j.PROFILEID IN (".implode(",",$matchingIds).") AND k.PROFILEID=$profileid ORDER BY FIELD (j.PROFILEID,".implode(",",$matchingIds).")";
		$result = $this->mysqlObj->executeQuery($select_statement,$this->db) or $this->mysqlObj->logError($select_statement);
		$i=0;

		while($row = $this->mysqlObj->fetchArray($result))
		{
			$matchesData[$i]["PROFILEID"] = $row["PROFILEID"];
			$matchesData[$i]["USERNAME"] = $row["USERNAME"];
			$matchesData[$i]["HAVEPHOTO"] = $row["HAVEPHOTO"];
			$matchesData[$i]["PRIVACY"] = $row["PRIVACY"];
			$matchesData[$i]["PHOTO_DISPLAY"] = $row["PHOTO_DISPLAY"];
			$matchesData[$i]["GENDER"] = $row["GENDER"];
			$matchesData[$i]["AGE"] = $row["AGE"];
			$temp = explode("(",$HEIGHT_DROP[$row["HEIGHT"]]);
			$matchesData[$i]["HEIGHT"] = trim($temp[0]);
			$matchesData[$i]["RELIGION"] = $RELIGIONS[$row["RELIGION"]];
			if ($row["RELIGION"])
			{
				$temp = explode(":",$CASTE_DROP[$row["CASTE"]]);
				$matchesData[$i]["CASTE"] = $temp[1];
			}
			else
			{
				$matchesData[$i]["CASTE"] = $CASTE_DROP[$row["CASTE"]];
			}
			$matchesData[$i]["MTONGUE"] = $MTONGUE_DROP[$row["MTONGUE"]];
			$matchesData[$i]["EDU_LEVEL_NEW"] = $EDUCATION_LEVEL_NEW_DROP[$row["EDU_LEVEL_NEW"]];
			$matchesData[$i]["OCCUPATION"] = $OCCUPATION_DROP[$row["OCCUPATION"]];
			$matchesData[$i]["ENTRY_DT"] = $row["ENTRY_DT"];
			$matchesData[$i]["INCOME"] = $INCOME_DROP[$row["INCOME"]];
			if (is_numeric($row["CITY_RES"]))
				$matchesData[$i]["CITY_RES"] = $CITY_DROP[$row["CITY_RES"]];
			else
				$matchesData[$i]["CITY_RES"] = $CITY_INDIA_DROP[$row["CITY_RES"]];
			$matchesData[$i]["COUNTRY_RES"] = $COUNTRY_DROP[$row["COUNTRY_RES"]];
			$matchesData[$i]["GUNA"] = round($row["GUNA"]);
			$matchesData[$i]["LAGNA"] = $row["LAGNA"];
			$matchesData[$i]["SUN"] = $row["SUN"];
			$matchesData[$i]["MERCURY"] = $row["MERCURY"];
			$matchesData[$i]["JUPITER"] = $row["JUPITER"];
			$matchesData[$i]["SATURN"] = $row["SATURN"];
			$matchesData[$i]["MARS"] = $row["MARS"];
			$matchesData[$i]["VENUS"] = $row["VENUS"];
			$matchesData[$i]["LINKEDIN_URL"] = $row["LINKEDIN_URL"];
			$matchesData[$i]["PROFILECHECKSUM"] = md5($row["PROFILEID"])."i".$row["PROFILEID"];
			$i++;
		}

		$matchesData = $this->getPhotoWithPrivacy($matchesData,$matchingIds);
		return $matchesData;
	}
	
	public function insertIntoContactTable($matchesData)
	{
		$insert_statement = "REPLACE INTO kundli_alert.KUNDLI_CONTACT_CENTER(PROFILEID,MATCHID,GUNA,LAGNA,SUN,MERCURY,JUPITER,SATURN,MARS,VENUS,ENTRY_DT,MAIL_DT) VALUES ";
		foreach($matchesData as $k=>$v)
		{
			$insert_statement = $insert_statement."(".$this->receiverId.",".$v["PROFILEID"].",".$v["GUNA"].",".$v["LAGNA"].",".$v["SUN"].",".$v["MERCURY"].",".$v["JUPITER"].",".$v["SATURN"].",".$v["MARS"].",".$v["VENUS"].",\"".$v["ENTRY_DT"]."\",NOW()),";
		}
		$insert_statement = rtrim($insert_statement,",");
		$this->mysqlObj->executeQuery($insert_statement,$this->db) or $this->mysqlObj->logError($insert_statement);
	}

	public function removeIds($ids)
	{
		if($this->kundli_paid)
			$table = "kundli_alert.MAILER_PAID";
		else
			$table = "kundli_alert.MAILER_UNPAID";
		
		$statement = "DELETE FROM ".$table." WHERE MATCHID IN (".implode(",",$ids).") AND PROFILEID = ".$this->receiverId;	
		$this->mysqlObj->executeQuery($statement,$this->db) or $this->mysqlObj->logError($statement);
	}

	public function getPhotoWithPrivacy($matchesData,$matchingIds)
	{
		$searchPicUrls = SymfonyPictureFunctions :: getPhotoUrls_nonSymfony($matchingIds,'SearchPicUrl',$this->db);

		foreach($matchesData as $k=>$v)
		{
			if($v['HAVEPHOTO']=='Y' && ($v['PRIVACY']=='R' || $v['PRIVACY']=='F' || $v['PHOTO_DISPLAY']=='C'))
                	{
                        	if($v['GENDER']=='M')
                                	$picUrl=JsConstants::$imgUrl."/profile/ser4_images/ic_login_to_view_b_100.gif";
                        	elseif($v['GENDER']=='F')
                               		$picUrl=JsConstants::$imgUrl."/profile/ser4_images/ic_login_to_view_g_100.gif";
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
                                       		$picUrl=JsConstants::$imgUrl."/profile/ser4_images/ic_photo_notavailable_b_100.gif";
                                	}
                                	elseif($v['HAVEPHOTO']=='U')
                                	{
                                        	$picUrl=JsConstants::$imgUrl."/profile/ser4_images/ic_photo_coming_b_100.gif";
                                	}
                                	else
                                	{
                                	        $picUrl=JsConstants::$imgUrl."/profile/ser4_images/ic_photo_notavailable_b_100.gif";
                                	}
                        	}
                        	elseif($v['GENDER']=='F')
                        	{
                                	if($v['HAVEPHOTO']=='N' || $v['HAVEPHOTO']=='')
                                	{
                                	        $picUrl=JsConstants::$imgUrl."/profile/ser4_images/ic_photo_notavailable_g_100.gif";
                                	}
                                	elseif($v['HAVEPHOTO']=='U')
                                	{
                                	        $picUrl=JsConstants::$imgUrl."/profile/ser4_images/ic_photo_coming_g_100.gif";
                                	}
                                	else
                                	{
                                	        $picUrl=JsConstants::$imgUrl."/profile/ser4_images/ic_photo_notavailable_g_100.gif";
                                	}
                        	}
			}
			$matchesData[$k]["SearchPicUrl"] = $picUrl;
			unset($picUrl);
		}
		return $matchesData;
	}
}
?>
