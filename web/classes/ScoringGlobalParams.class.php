<?php

/** 
* @author Neha Gupta
* @copyright Copyright 2015, Infoedge India Ltd.
*/

include_once("/usr/local/scripts/DocRoot.php");
include_once("$docRoot/crontabs/connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
// including for logging purpose
include_once(JsConstants::$docRoot."/classes/LoggingWrapper.class.php");


class ScoringGlobalParams
{
	private $nationalityArr;            // Country-Nationality Mapping
	private $groupNameArr;              // Source-GroupName Mapping
	private $cityZoneArr;               // City-Zone Mapping
	private $communityZoneArr;          // Community-Zone Mapping
	private $interceptArr;              // ProfileType-Intercept Mapping
	private $weightParamArr;            // ProfileType-(Weight & Param) Mapping

	public function __construct ($total_profiles_str) {
		$myDb=connect_slave();
		mysql_query('set session wait_timeout=50000',$myDb);

		$this->setNationalityArr();
		$this->setGroupNameArr();
		$this->setCityZoneArr();
		$this->setCommunityZoneArr();
		$this->setInterceptArr();
		$this->setWeightParamArr();

		$this->total_profiles = $total_profiles_str;
		unset($total_profiles_str);
		$this->setSearchParameters();
		$this->setPaymentPageHits();

		unset($myDb);

		$this->setViewParameters();
		unset($this->total_profiles);
	}

	// SET FUNCTIONS
	public function setNationalityArr() {
		$sql1="SELECT Nationality, Country FROM scoring_new.COUNTRY_NATIONALITY";
		$result1 = mysql_query_decide($sql1,$myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql1.mysql_error($myDb))); 
		while($row = mysql_fetch_array($result1)) {
			$this->nationalityArr[$row['Country']] = $row['Nationality'];
		}
	}
	public function setGroupNameArr() {
		$sql1="SELECT GROUPNAME, SourceID FROM MIS.SOURCE";
		$result1 = mysql_query_decide($sql1,$myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql1.mysql_error($myDb))); 
		while($row = mysql_fetch_array($result1)) {
			$this->groupNameArr[$row['SourceID']] = $row['GROUPNAME'];
		}
	}
	public function setCityZoneArr() {
		$sql1="SELECT CityZone, City FROM scoring_new.CITY_ZONE";
		$result1 = mysql_query_decide($sql1,$myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql1.mysql_error($myDb))); 
		while($row = mysql_fetch_array($result1)) {
			$this->cityZoneArr[$row['City']] = $row['CityZone'];
		}
	}
	public function setCommunityZoneArr() {
		$sql1="SELECT ComZone, Community FROM scoring_new.COMMUNITY_ZONE";
		$result1 = mysql_query_decide($sql1,$myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql1.mysql_error($myDb))); 
		while($row = mysql_fetch_array($result1)) {
			$this->communityZoneArr[$row['Community']] = $row['ComZone'];
		}
	}
	public function setInterceptArr() {
		$sql1="SELECT intercept, profile_type FROM scoring_new.INTERCEPT";
		$result1 = mysql_query_decide($sql1,$myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql1.mysql_error($myDb))); 
		while($row = mysql_fetch_array($result1)) {
			$this->interceptArr[$row['profile_type']] = $row['intercept'];
		}
	}
	public function setWeightParamArr() {
		$sql1="SELECT profile_type, weight, param FROM scoring_new.WEIGHT";
		$result1 = mysql_query_decide($sql1,$myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql1.mysql_error($myDb))); 
		while($row = mysql_fetch_array($result1)) {
			$this->weightParamArr[$row['profile_type']][$row['param']] = $row['weight'];
		}
	}
	public function setSearchParameters()
        {
                $lim_7_dt = date("Y-m-d",time()-7*86400);
                $lim_14_dt = date("Y-m-d",time()-14*86400);

                //SEARCHES_14_cap and SEARCHES_accel
                $sql1 = "SELECT COUNT(CASE WHEN DATE >= '$lim_7_dt' THEN 1 ELSE NULL END) AS CNT1,COUNT(CASE WHEN DATE >= '$lim_14_dt' THEN 1 ELSE NULL END) AS CNT2,PROFILEID FROM MIS.SEARCHQUERY WHERE PROFILEID IN ($this->total_profiles) GROUP BY PROFILEID";
                $res1 = mysql_query_decide($sql1,$myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql1.mysql_error($myDb))); 
                while($row1 = mysql_fetch_array($res1)){
                        $this->searchParams[$row1["PROFILEID"]][0] = $row1["CNT1"];
                        $this->searchParams[$row1["PROFILEID"]][1] = $row1["CNT2"];
                    }
        }
	public function setPaymentPageHits()
        {
                $lim_14_dt = date("Y-m-d",time()-14*86400);
		
		//payment_hits_14_1_mod,payment_hits_14_2_mod and payment_hits_14_3_mod
                $sql6 = "SELECT COUNT(*) AS CNT,PAGE,PROFILEID FROM  billing.PAYMENT_HITS WHERE ENTRY_DT>='$lim_14_dt' AND PROFILEID IN ($this->total_profiles) GROUP BY PAGE,PROFILEID";
                $res6 = mysql_query_decide($sql6,$myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql6.mysql_error($myDb))); 
                while($row6 = mysql_fetch_array($res6))
                {
			if($row6["PAGE"]==1)
				$this->paymentPageHits[$row6["PROFILEID"]][1]=$row6["CNT"];
                        elseif($row6["PAGE"]==2)
				$this->paymentPageHits[$row6["PROFILEID"]][2]=$row6["CNT"];
                        elseif($row6["PAGE"]==3)
				$this->paymentPageHits[$row6["PROFILEID"]][3]=$row6["CNT"];
                }

        }
	public function setViewParameters()
	{
		$lim_7_dt = date("Y-m-d",time()-7*86400);
                $lim_14_dt = date("Y-m-d",time()-14*86400);

		//VIEWS_7_cap and views_accel
                $mysqlObj=new Mysql;
                $shDbName=getActiveServerName(1,"slave");
                $shDb2=$mysqlObj->connect($shDbName);
                $sql1 = "SELECT COUNT(CASE WHEN DATE >= '$lim_7_dt' THEN 1 ELSE NULL END) AS CNT1, COUNT(CASE WHEN DATE >= '$lim_14_dt' THEN 1 ELSE NULL END) AS CNT2,VIEWER FROM newjs.VIEW_LOG_TRIGGER WHERE VIEWER IN ($this->total_profiles) GROUP BY VIEWER";
                $res1 = mysql_query_decide($sql1,$shDb2) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql1.mysql_error($shDb2)));
                while($row1 = mysql_fetch_array($res1)){
			$this->viewParameters[$row1["VIEWER"]][0]=$row1["CNT1"];
			$this->viewParameters[$row1["VIEWER"]][1]=$row1["CNT2"];
                }
		unset($shDb2);
	}

	// GET FUNCTIONS
	public function getNationality($country) {
		return $this->nationalityArr[$country];		
	}
	public function getGroupName($sourceid) {
		return $this->groupNameArr[$sourceid];		
	}
	public function getCityZone($city) {
		return $this->cityZoneArr[$city];		
	}
	public function getCommunityZone($community) {
		return $this->communityZoneArr[$community];		
	}
	public function getIntercept($profile_type) {
		return $this->interceptArr[$profile_type];		
	}
	public function getWeightParam($profile_type) {
		return $this->weightParamArr[$profile_type];		
	}
	public function getSearchParameters($profileid) {
		return  $this->searchParams[$profileid];
	}
	public function getPaymentPageHits($profileid) {
                return  $this->paymentPageHits[$profileid];
        }
	public function getViewParameters($profileid) {
                return  $this->viewParameters[$profileid];
        }

}


?>
