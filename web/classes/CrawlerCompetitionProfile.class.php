<?php
include_once("CrawlerClassesCommon.php");
class CrawlerCompetitionProfile
{
	private $siteId;
	private $searchId;
	private $competitionId;
	private $se;
	private $quality;
	private $name;
	private $dtOfbirth;
	private $btime;
	private $city_birth;
	private $country_birth;
	private $gender;
	private $mstatus;
	private $age;
	private $blood_group;
	private $mtongue;
	private $citizenship;
	private $religion;
	private $caste;
	private $country_res;
	private $city_res;
	private $edu_level_new;
	private $t_brothers;
	private $t_sisters;
	private $diet;
	private $drink;
	private $smoke;
	private $gothra;
	private $subcaste;
	private $height;
	private $std;
	private $phone_res;
	private $phone_mob;
	private $email;
	private $detail_view_parsed;
	private $contact_details_parsed;
	private $de_duped;

	public function updateContactDetailsParsingStatus()
	{
echo "\n\n";
echo		$sql = "UPDATE crawler.crawler_search_results SET CONTACT_DETAILS_PARSED='X',CONTACT_DETAIL_VIEW_DATE=CURDATE() WHERE COMPETITION_ID='$this->competitionId' AND SITE_ID='$this->siteId'";
echo "\n\n";
		$mysqlObj=new Mysql;
		$db=$mysqlObj->connect('crawler');
		mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
		$res=$mysqlObj->executeQuery($sql,$db);

	}

	public function updateDetailViewParsingStatus()
	{
		$sql = "UPDATE crawler.crawler_search_results SET DETAIL_VIEW_PARSED='X',DETAIL_VIEW_DATE=CURDATE() WHERE COMPETITION_ID='$this->competitionId' AND SITE_ID='$this->siteId'";
		$mysqlObj=new Mysql;
		$db=$mysqlObj->connect('crawler');
		mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
		$res=$mysqlObj->executeQuery($sql,$db);

	}
	public function CrawlerCompetitionProfile($idArr)
	{
		if($idArr["COMPETITION_ID"] && $idArr["SITE_ID"])
		{
			$this->competitionId=$idArr["COMPETITION_ID"];
			$this->siteId=$idArr["SITE_ID"];
			$mysqlObj=new Mysql;
			$db=$mysqlObj->connect('crawler');
			mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
			$sql="SELECT * FROM crawler.crawler_search_results WHERE COMPETITION_ID='$idArr[COMPETITION_ID]' AND SITE_ID='$idArr[SITE_ID]'";
			$res=$mysqlObj->executeQuery($sql,$db);
			if($mysqlObj->numRows($res))
			{
				$row=$mysqlObj->fetchAssoc($res);
				if($row["SEARCH_ID"])
					$this->searchId=$row["SEARCH_ID"];
				if($row["GENDER"])
					$this->gender=$row["GENDER"];
				if($row["se"])
					$this->se=$row["se"];
				if($row["DETAIL_VIEW_PARSED"])
					$this->detail_view_parsed=$row["DETAIL_VIEW_PARSED"];
				if($row["CONTACT_DETAILS_PARSED"])
					$this->contact_details_parsed=$row["CONTACT_DETAILS_PARSED"];
				if($row["QUALITY"])
					$this->quality=$row["QUALITY"];
				if($row["NAME"])
					$this->name=$row["NAME"];
				if($row["DTOFBIRTH"])
					$this->dtofbirth=$row["DTOFBIRTH"];
				if($row["BTIME"])
					$this->btime=$row["BTIME"];
				if($row["CITY_BIRTH"])
					$this->city_birth=$row["CITY_BIRTH"];
				if($row["COUNTRY_BIRTH"])
					$this->country_birth=$row["COUNTRY_BIRTH"];
				if($row["MSTATUS"])
					$this->mstatus=$row["MSTATUS"];
				if($row["AGE"])
					$this->age=$row["AGE"];
				if($row["BLOOD_GROUP"])
					$this->blood_group=$row["BLOOD_GROUP"];
				if($row["MTONGUE"])
					$this->mtongue=$row["MTONGUE"];
				if($row["CITIZENSHIP"])
					$this->citizenship=$row["CITIZENSHIP"];
				if($row["RELIGION"])
					$this->religion=$row["RELIGION"];
				if($row["CASTE"])
					$this->caste=$row["CASTE"];
				if($row["COUNTRY_RES"])
					$this->country_res=$row["COUNTRY_RES"];
				if($row["CITY_RES"])
					$this->city_res=$row["CITY_RES"];
				if($row["EDU_LEVEL_NEW"])
					$this->edu_level_new=$row["EDU_LEVEL_NEW"];
				if($row["T_BROTHER"] || $row["T_BROTHER"]=='0')
					$this->t_brothers=$row["T_BROTHER"];
				if($row["T_SISTER"] || $row["T_SISTER"]=='0')
					$this->t_sisters=$row["T_SISTER"];
				if($row["DIET"])
					$this->diet=$row["DIET"];
				if($row["DRINK"])
					$this->drink=$row["DRINK"];
				if($row["SMOKE"])
					$this->smoke=$row["SMOKE"];
				if($row["GOTHRA"])
					$this->gothra=$row["GOTHRA"];
				if($row["SUBCASTE"])
					$this->subcaste=$row["SUBCASTE"];
				if($row["HEIGHT"])
					$this->height=$row["HEIGHT"];
				if($row["STD"])
					$this->std=$row["STD"];
				if($row["PHONE_RES"])
					$this->phone_res=$row["PHONE_RES"];
				if($row["PHONE_MOB"])
					$this->phone_mob=$row["PHONE_MOB"];
				if($row["EMAIL"])
					$this->email=$row["EMAIL"];
				if($row["DETAIL_PAGE_PARAMS_SM"])
					$this->DETAIL_PAGE_PARAMS_SM = $row["DETAIL_PAGE_PARAMS_SM"];
			}
			unset($mysqlObj);
		}
	}

	public static function getProfilesForCrawlingDetail($siteId)
        {
		$mysqlObj=new Mysql;
		$db=$mysqlObj->connect("crawler");
		mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);

		$sql="SELECT COMPETITION_ID,SITE_ID,SEARCH_ID FROM crawler.crawler_search_results WHERE DETAIL_VIEW_PARSED='N' AND SITE_ID='$siteId' ";
		$res=$mysqlObj->executeQuery($sql,$db);
		if($mysqlObj->numRows($res))
		{
			while($row=$mysqlObj->fetchAssoc($res))
			{
				$crawlerCompetitionProfileArr[]=new CrawlerCompetitionProfile($row);
			}
			unset($mysqlObj);
			return $crawlerCompetitionProfileArr;
		}
        }
        
        public static function getProfilesForCrawlingContactDetail($siteId)
        {
		$mysqlObj=new Mysql;
                $db=$mysqlObj->connect("crawler");
		mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
                $sql="SELECT COMPETITION_ID,SITE_ID FROM crawler.crawler_search_results WHERE CONTACT_DETAILS_PARSED='N' AND SITE_ID=$siteId ";
		echo "\n".$sql;
                $res=$mysqlObj->executeQuery($sql,$db);
                if($mysqlObj->numRows($res))
                {
                        while($row=$mysqlObj->fetchAssoc($res))
                        {
                                $crawlerCompetitionProfileArr[]=new CrawlerCompetitionProfile($row);
                        }
			unset($mysqlObj);
                        return $crawlerCompetitionProfileArr;
                }
        }

	public static function getProfilesForDeDupe($siteId)
        {
                $mysqlObj=new Mysql;
                $db=$mysqlObj->connect("crawler");
		mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
                $sql="SELECT COMPETITION_ID,SITE_ID FROM crawler.crawler_search_results WHERE DETAIL_VIEW_PARSED='Y' AND DE_DUPED='' AND SITE_ID=$siteId ";
                echo "\n".$sql;
                $res=$mysqlObj->executeQuery($sql,$db);
                if($mysqlObj->numRows($res))
                {
                        while($row=$mysqlObj->fetchAssoc($res))
                        {
                                $crawlerCompetitionProfileArr[]=new CrawlerCompetitionProfile($row);
                        }
                        unset($mysqlObj);
                        return $crawlerCompetitionProfileArr;
                }
        }

	public static function getAccountIdFromCompetitionId($competitionIdArr)
	{
		if(is_array($competitionIdArr))
		{
			$mysqlObj=new Mysql;
			$db=$mysqlObj->connect("crawler");
			mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
			$sql="SELECT ACCOUNT_ID,COMPETITION_ID FROM crawler.crawler_detail_view_history WHERE COMPETITION_ID IN ('".implode("','",$competitionIdArr)."') AND CONTACT_DETAIL_VIEW!='Y'";
			$res=$mysqlObj->executeQuery($sql,$db);
			if($mysqlObj->numRows($res))
			{
				while($row=$mysqlObj->fetchAssoc($res))
						$accountId_competitionIdArr[$row["COMPETITION_ID"]]=$row["ACCOUNT_ID"];
				unset($mysqlObj);
				return $accountId_competitionIdArr;
			}
			else
				unset($mysqlObj);
		}
	}


	public function getSITE_ID()
	{
		return $this->siteId;
	}
	
	public function getCOMPETITION_ID()
	{
		return $this->competitionId;
	}

	public function getGENDER()
	{
		return $this->gender;
	}

	public function getSEARCH_ID()
	{
		return $this->searchId;
	}

	public function getse()
	{
		return $this->se;
	}
	
	public function getDETAIL_PAGE_PARAMS_SM()
	{
		return $this->DETAIL_PAGE_PARAMS_SM;
	}
	public function deDupe()
        {
		$existingMapping=$this->deDupeAgainstExistingMapping();
		echo "\n existing mapping  ".$existingMapping;
		if(!$existingMapping)
		{
			$JS=$this->deDupeAgainstJS();
			echo "\n JS is   ";
			var_dump($JS);
			if(is_array($JS))
			{
				if(count($JS)==1)
					$this->mapCompetitionProfileToJS($JS[0]);
				return 1;
			}
			if(!$JS)
			{
				//$this->deDupeAgainstSugar();
				if(!$this->quality)
                                        $this->quality='H';
			}
		}
        }

        public function deDupeAgainstExistingMapping()
        {
		if($this->competitionId && $this->siteId)
		{
			$sql="SELECT COUNT(*) AS COUNT FROM crawler.crawler_JS_competition_user_mapping WHERE COMPETITION_ID='$this->competitionId' AND SITE_ID='$this->siteId'";
			echo "\n".$sql;
			$mysqlObj=new Mysql;
			if($remote)
				$db=$mysqlObj->connect('crawlerRemote');
			else
				$db=$mysqlObj->connect('crawler');
			$res=$mysqlObj->executeQuery($sql,$db);
			$row=$mysqlObj->fetchAssoc($res);
			unset($mysqlObj);
			if($row["COUNT"])
				return 1;
			else
				return 0;
		}
		else
			return 0;
        }

        public function deDupeAgainstJS()
        {
		$match='';
		$matchArr='';
		$map=0;
		$mysqlObj=new Mysql;
		$db=$mysqlObj->connect('157');
		if(($this->btime && $this->city_birth && $this->dtofbirth) || $this->name)
		{
			$map=1;
//			if($this->gender && $this->mtongue && $this->religion && $this->caste && $this->country_res && $this->city_res && $this->mstatus)
			{
				if($this->btime)
					$whereArr[]="BTIME=\"".$this->btime."\"";
				if($this->city_birth)
					$whereArr[]="CITY_BIRTH LIKE \"$this->city_birth%\"";
				if($this->dtofbirth!='0000-00-00' && $this->dtofbirth!='')
					$whereArr[]="DTOFBIRTH=\"".$this->dtofbirth."\"";
				if($this->gender)	
					$whereArr[]="GENDER=\"".$this->gender."\"";
				if($this->mtongue)
					$whereArr[]="MTONGUE=\"".$this->mtongue."\"";
				if($this->religion)
					$whereArr[]="RELIGION=\"".$this->religion."\"";
				if($this->caste)
					$whereArr[]="CASTE=\"".$this->caste."\"";
				if($this->country_res)
					$whereArr[]="COUNTRY_RES=\"".$this->country_res."\"";
				if($this->city_res)
					$whereArr[]="CITY_RES=\"".$this->city_res."\"";
				if($this->mstatus)
					$whereArr[]="MSTATUS=\"".$this->mstatus."\"";
			}
		}
//		elseif($this->gender && ($this->age || $this->dtofbirth) && $this->mtongue && $this->religion && $this->caste && $this->country_res && $this->city_res && $this->mstatus && $this->edu_level_new)
		else
		{
			if($this->dtofbirth!='0000-00-00' && $this->dtofbirth!='')
				$whereArr[]="DTOFBIRTH=\"".$this->dtofbirth."\"";
			elseif($this->age)
				$whereArr[]="AGE=\"".$this->age."\"";
			if($this->gender)
				$whereArr[]="GENDER=\"".$this->gender."\"";
			if(in_array($this->mtongue,array(10,19,33,7,13,28)))
				$whereArr[]=" MTONGUE IN (10,19,33,7,13,28) ";
			else
				$whereArr[]="MTONGUE=\"".$this->mtongue."\"";
			if($this->religion)
				$whereArr[]="RELIGION=\"".$this->religion."\"";
			if($this->caste)
				$whereArr[]="CASTE=\"".$this->caste."\"";
			if($this->country_res)
				$whereArr[]="COUNTRY_RES=\"".$this->country_res."\"";
			if($this->city_res)
				$whereArr[]="CITY_RES=\"".$this->city_res."\"";
			if($this->mstatus)
				$whereArr[]="MSTATUS=\"".$this->mstatus."\"";
			if($this->edu_level_new)
				$whereArr[]="EDU_LEVEL_NEW=\"".$this->edu_level_new."\"";
		}
		if(is_array($whereArr))
		{
			if($this->blood_group)
	                        $whereArr[]="BLOOD_GROUP=\"".$this->blood_group."\"";
                        if($this->citizenship)
                                $whereArr[]="RES_STATUS=\"".$this->citizenship."\"";
//                                $whereArr[]="CITIZENSHIP=\"".$this->citizenship."\"";
			$whereArr[]="DATE(LAST_LOGIN_DT) > DATE_SUB( CURDATE( ) , INTERVAL 365 DAY )";
//			$whereArr[]="DATEDIFF(CURDATE(),LAST_LOGIN_DT)<365";
//			$whereArr[]="DATEDIFF(CURDATE(),ENTRY_DT)<365";
			//$whereArr[]="ACTIVATED='Y'";
			$sqlSearch="SELECT PROFILEID FROM newjs.JPROFILE WHERE ";
			$sqlSearch.=implode(" AND ",$whereArr);
			echo "\n".$sqlSearch;
			$resSearch=$mysqlObj->executeQuery($sqlSearch,$db);
			if($mysqlObj->numRows($resSearch))
			{
				while($rowSearch=$mysqlObj->fetchAssoc($resSearch))
					$matchArr[]=$rowSearch["PROFILEID"];
				if($this->name)
				{
					$pos=stripos($this->name,"Later");
	                                if(!is_numeric($pos))
					{
						$nameArr=explode(" ",$this->name);
						if(count($nameArr)==2)
						{
							$name2=$nameArr[1]." ".$nameArr[0];
						}
						
						$sqlNameSearch="SELECT PROFILEID FROM incentive.NAME_OF_USER WHERE ";
						if($name2)
							$sqlNameSearch.="NAME IN (\"".$this->name."\",\"".$name2."\") AND PROFILEID IN('".implode("','",$matchArr)."')";
						else
							$sqlNameSearch.="NAME=\"".$this->name."\" AND PROFILEID IN('".implode("','",$matchArr)."')";
						echo "\n".$sqlNameSearch;
						$resNameSearch=$mysqlObj->executeQuery($sqlNameSearch,$db);
						if($mysqlObj->numRows($resNameSearch))
						{
							unset($matchArr);
							while($rowNameSearch=$mysqlObj->fetchAssoc($resNameSearch))
								$matchArr[]=$rowNameSearch["PROFILEID"];
						}
					}
				}
				if($map && count($matchArr)==1)
				{
					unset($mysqlObj);
					return $matchArr;
				}
			}
			else
			{
				unset($mysqlObj);
				return 0;
			}
		}
		if(is_array($matchArr))
		{
			if($this->subcaste)
			{
				$pos=stripos($this->subcaste,"Don't know");
				if(!is_numeric($pos))
					$pos=stripos($this->subcaste,"Not specified");
				if(!is_numeric($pos))	
				{
					$sqlSecondary="SELECT PROFILEID FROM newjs.JPROFILE WHERE PROFILEID IN('".implode("','",$matchArr)."') AND SUBCASTE=\"".$this->subcaste."\"";
					echo "\n".$sqlSecondary;
					$resSecondary=$mysqlObj->executeQuery($sqlSecondary,$db);
					unset($matchArr);
					if($mysqlObj->numRows($resSecondary))
					{
						while($rowSecondary=$mysqlObj->fetchAssoc($resSecondary))
							$matchArr[]=$rowSecondary["PROFILEID"];
					}
					else
					{
						$this->quality='M';
						unset($mysqlObj);
						return 0;
					}
				}				
			}
		}
                if(is_array($matchArr))
                {
                        if($this->gothra)
                        {
				$pos=stripos($this->gothra,"Don't know");
                                if(!is_numeric($pos))
                                        $pos=stripos($this->gothra,"Not specified");
                                if(!is_numeric($pos))
                                {
					$sqlSecondary="SELECT PROFILEID FROM newjs.JPROFILE WHERE PROFILEID IN('".implode("','",$matchArr)."') AND GOTHRA=\"".$this->gothra."\"";
					echo "\n".$sqlSecondary;
					$resSecondary=$mysqlObj->executeQuery($sqlSecondary,$db);
					unset($matchArr);
					if($mysqlObj->numRows($resSecondary))
					{
						while($rowSecondary=$mysqlObj->fetchAssoc($resSecondary))
							$matchArr[]=$rowSecondary["PROFILEID"];
					}
					else
					{
						$this->quality='M';
						unset($mysqlObj);
						return 0;
					}
				}
                        }
                }
                if(is_array($matchArr))
                {
                        if($this->diet && $this->smoke && $this->drink && $this->height)
                        {
                                $sqlSecondary="SELECT PROFILEID FROM newjs.JPROFILE WHERE PROFILEID IN('".implode("','",$matchArr)."') AND DRINK=\"".$this->drink."\" AND SMOKE=\"".$this->smoke."\" AND DIET=\"".$this->diet."\" AND HEIGHT=\"".$this->height."\"";    
				echo "\n".$sqlSecondary;
                                $resSecondary=$mysqlObj->executeQuery($sqlSecondary,$db);
				unset($matchArr);
                                if($mysqlObj->numRows($resSecondary))
                                {
                                        while($rowSecondary=$mysqlObj->fetchAssoc($resSecondary))
                                                $matchArr[]=$rowSecondary["PROFILEID"];
                                }
                                else
                                {
					$this->quality='L';
					unset($mysqlObj);
                                        return 0;
                                }
                        }
                }
		unset($mysqlObj);
		if(is_array($matchArr))
			return $matchArr;
		else
			return 0;
        }

        public function deDupeAgainstSugar()
        {
		if($this->gender && $this->age && $this->mstatus && $this->religion && $this->caste && $this->city_res)
		{
			$mysqlObj=new Mysql;
			$db=$mysqlObj->connect('157');
			$sqlSugar="SELECT id_c FROM sugarcrm.leads_cstm WHERE gender_c=\"".$this->gender."\" AND age_c=\"".$this->age."\" AND marital_status_c=\"".$this->mstatus."\" AND religion_c=\"".$this->religion."\" AND caste_c=\"".$this->religion."_".$this->caste."\" AND city_c=\"".$this->city_res."\"";
			echo "\n".$sqlSugar;
			$resSugar=$mysqlObj->executeQuery($sqlSugar,$db);
			if($mysqlObj->numRows($resSugar))
			{
				unset($mysqlObj);
				return 1;
			}
			else
			{
				if(!$this->quality)
					$this->quality='H';
				unset($mysqlObj);
				return 1;
			}
		}
        }

	public function mapCompetitionProfileToJS($profileid)
	{
		if($profileid && $this->competitionId && $this->siteId)
		{
			$mysqlObj=new Mysql;
			if($remote)
				$db=$mysqlObj->connect('crawlerRemote');
			else
				$db=$mysqlObj->connect('crawler');
			$sqlMap="INSERT INTO crawler.crawler_JS_competition_user_mapping(PROFILEID,COMPETITION_ID,SITE_ID) VALUES('$profileid','$this->competitionId','$this->siteId')";
			echo "\n".$sqlMap;
			$mysqlObj->executeQuery($sqlMap,$db);
			unset($mysqlObj);
		}
	}

        public function uploadCompetitionProfileDetails($values,$remote='')
        {
		if(is_array($values) && count($values) && $this->competitionId)
		{
			$deDupe=0;
			$createLead=0;
			foreach($values as $field=>$value)
			{
				if($value || $value=='0')
					$this->$field=$value;
				if($field=='detail_view_parsed' && $value)
				{
					$updateArr[]="DETAIL_VIEW_PARSED=\"".$this->detail_view_parsed."\"";
					$updateArr[]="DE_DUPED=''";			
					$updateArr[]="DETAIL_VIEW_DATE = CURDATE()";
				}
				if($field=='contact_details_parsed' && $value)
				{
					$updateArr[]="CONTACT_DETAILS_PARSED=\"".$this->contact_details_parsed."\"";
					$updateArr[]="CONTACT_DETAIL_VIEW_DATE = CURDATE()";
					if($value=='Y')
						$createLead=1;
				}
			}
			$sqlUpdate="UPDATE crawler.crawler_search_results SET ";
			if($this->se)
				$updateArr[]="se=\"".$this->se."\"";
			if($this->name)
				$updateArr[]="NAME=\"".$this->name."\"";
			if($this->dtofbirth && $this->dtofbirth!='')
				$updateArr[]="DTOFBIRTH=\"".$this->dtofbirth."\"";
			if($this->btime)
			{
				if(strpos($this->btime,':'))
					$updateArr[]="BTIME=\"".$this->btime."\"";
			}
			if($this->city_birth)
				$updateArr[]="CITY_BIRTH=\"".$this->city_birth."\"";
			if($this->country_birth)
				$updateArr[]="COUNTRY_BIRTH=\"".$this->country_birth."\"";
			if($this->gender)
				$updateArr[]="GENDER=\"".$this->gender."\"";
			if($this->mstatus)
				$updateArr[]="MSTATUS=\"".$this->mstatus."\"";
			if($this->age)
				$updateArr[]="AGE=\"".$this->age."\"";
			if($this->blood_group)
				$updateArr[]="BLOOD_GROUP=\"".$this->blood_group."\"";
			if($this->mtongue)
				$updateArr[]="MTONGUE=\"".$this->mtongue."\"";
			if($this->citizenship)
				$updateArr[]="CITIZENSHIP=\"".$this->citizenship."\"";
			if($this->religion)
				$updateArr[]="RELIGION=\"".$this->religion."\"";
			if($this->caste)
				$updateArr[]="CASTE=\"".$this->caste."\"";
			if($this->country_res)
				$updateArr[]="COUNTRY_RES=\"".$this->country_res."\"";
			if($this->city_res)
				$updateArr[]="CITY_RES=\"".$this->city_res."\"";
			if($this->edu_level_new)
				$updateArr[]="EDU_LEVEL_NEW=\"".$this->edu_level_new."\"";
			if($this->t_brothers || $this->t_brothers=='0')
				$updateArr[]="T_BROTHER=\"".$this->t_brothers."\"";
			if($this->t_sisters || $this->t_sisters=='0')
				$updateArr[]="T_SISTER=\"".$this->t_sisters."\"";
			if($this->diet)
				$updateArr[]="DIET=\"".$this->diet."\"";
			if($this->drink)
				$updateArr[]="DRINK=\"".$this->drink."\"";
			if($this->smoke)
				$updateArr[]="SMOKE=\"".$this->smoke."\"";
			if($this->gothra)
				$updateArr[]="GOTHRA=\"".$this->gothra."\"";
			if($this->subcaste && $this->subcaste != 'Not Specified')
				$updateArr[]="SUBCASTE=\"".$this->subcaste."\"";
			if($this->height)
				$updateArr[]="HEIGHT=\"".$this->height."\"";
			if($this->siteId==2)
			{
				if($this->phone_mob)
				{
					if(strpos($this->phone_mob,'-'))
					{
						$values = explode("-",$this->phone_mob);

						$this->std = $values[0];
						$updateArr[]="STD=\"".$values[0]."\"";

						$this->phone_res = $values[1];
						$updateArr[]="PHONE_RES=\"".$values[1]."\"";

						$this->phone_mob = NULL;
//						$updateArr[]="PHONE_MOB=\"".$this->phone_mob."\"";
					}
					else
					{
						$updateArr[]="PHONE_MOB=\"".$this->phone_mob."\"";
					}
				}
			}
			else
			{
				if($this->std)
					$updateArr[]="STD=\"".$this->std."\"";
				if($this->phone_res)
					$updateArr[]="PHONE_RES=\"".$this->phone_res."\"";
				if($this->phone_mob)
					$updateArr[]="PHONE_MOB=\"".$this->phone_mob."\"";
			}
			if($this->email)
				$updateArr[]="EMAIL=\"".$this->email."\"";
			if($this->quality)
				$updateArr[]="QUALITY=\"".$this->quality."\"";
			if($this->de_duped)
				$updateArr[]="DE_DUPED=\"".$this->de_duped."\"";
			/*if($deDupe)
				$this->deDupe();*/
			if($this->quality=='H' && $this->contact_detail_parsed!='Y')
			{
				$updateArr[]="CONTACT_DETAILS_PARSED=IF(CONTACT_DETAILS_PARSED='Y','Y','N')";
			}
			if(is_array($updateArr))
				$sqlUpdate.=implode(",",$updateArr);
			$sqlUpdate.=" WHERE COMPETITION_ID='$this->competitionId' AND SITE_ID='$this->siteId'";
			$mysqlObj=new Mysql;
			if($remote)
				$db=$mysqlObj->connect('crawlerRemote');
			else
				$db=$mysqlObj->connect('crawler');
			echo "\n".$sqlUpdate;
			$mysqlObj->executeQuery($sqlUpdate,$db);
			unset($mysqlObj);
			if($createLead)
			{
				$this->createLead();
			}
		}
        }

        public function createLead()
        {
		if($this->email || $this->phone_mob || $this->phone_res)
		{
			global $leadCreationSiteURL;
			$linkParam=array();
			$link=$leadCreationSiteURL."/sugarcrm/custom/crons/create_sugar_lead.php";
			$noName=1;
			if($this->email)
			{
				$linkParam[]="email=".urlencode($this->email);
				$linkParam[]="last_name=".$this->email;
                                $noName=0;
			}
			if($this->phone_mob)
			{
				$linkParam[]="mobile1=".urlencode($this->phone_mob);
				if($noName)
                                {
                                        $linkParam[]="last_name=".$this->phone_mob;
                                        $noName=0;
                                }
			}
			if($this->religion)
			{
				$linkParam[]="religion_c=$this->religion";
				if($this->caste)
					$linkParam[]="caste_c=".$this->religion."_".$this->caste;
			}
			if($this->gender)
				$linkParam[]="gender_c=$this->gender";
			if($this->mtongue)
				$linkParam[]="mother_tongue_c=$this->mtongue";
			if($this->std)
				$linkParam[]="std_c=$this->std";
			if($this->phone_res)
			{
				$linkParam[]="phone1=".urlencode($this->phone_res);
				if($noName)
                                {
                                        $linkParam[]="last_name=".$this->phone_res;
                                        $noName=0;
                                }
			}
			if($this->name)
				$linkParam[]="last_name=".urlencode($this->name);
			if($this->dtofbirth && $this->dtofbirth!='' && $this->dtofbirth!='0000-00-00')
				$linkParam[]="date_birth_c=".urlencode($this->dtofbirth);
			if($this->age)
				$linkParam[]="age_c=".urlencode($this->age);
			if($this->mstatus)
				$linkParam[]="marital_status_c=$this->mstatus";
			if($this->edu_level_new)
				$linkParam[]="education_c=$this->edu_level_new";
			if($this->city_res)
				$linkParam[]="city_c=$this->city_res";
			if($this->t_brothers || $this->t_brothers=='0')
				$linkParam[]="no_of_brothers_c=$this->t_brothers";
			if($this->t_sisters || $this->t_sisters=='0')
				$linkParam[]="no_of_sisters_c=$this->t_sisters";
			if($this->smoke)
				$linkParam[]="smoke_c=$this->smoke";
			if($this->drink)
				$linkParam[]="drink_c=$this->drink";
			if($this->gothra)
				$linkParam[]="gothra_c=".urlencode($this->gothra);
			if($this->subcaste)
				$linkParam[]="subcaste_c=".urlencode($this->subcaste);
			if($this->height)
				$linkParam[]="height_c=$this->height";	
			$linkParam[]="source_c=16";
			$linkParam[]="checkJprofile=1";
			if(is_array($linkParam))
			{
				$link=$link."?".implode("&",$linkParam);
				echo "\n".$link;
				$handle = curl_init();
				curl_setopt($handle, CURLOPT_RETURNTRANSFER    , true);
//				curl_setopt($handle, CURLOPT_HEADER, 1);
				curl_setopt($handle, CURLOPT_MAXREDIRS        , 5);
				curl_setopt($handle, CURLOPT_FOLLOWLOCATION    , true);
				curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 20);
				curl_setopt($handle, CURLOPT_URL, $link);
				$response = curl_exec($handle);
				curl_close($handle);
				$trackLeadCreation = "INSERT INTO crawler.TRACK_SUGAR_LEAD_CREATION VALUES('$this->siteId','$this->competitionId','$response',CURDATE())";
				$mysqlObj=new Mysql;
				if($remote)
					$db=$mysqlObj->connect('crawlerRemote');
				else
					$db=$mysqlObj->connect('crawler');
				echo "\n".$trackLeadCreation;
				$mysqlObj->executeQuery($trackLeadCreation,$db);
				unset($mysqlObj);
			}
		}
        }
}
?>
