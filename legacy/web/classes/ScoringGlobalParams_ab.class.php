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


class ScoringGlobalParams_ab
{
	public function __construct ($total_profiles_str) {
		$myDb=connect_slave();
		mysql_query('set session wait_timeout=50000',$myDb);
		$this->total_profiles = $total_profiles_str;
		unset($total_profiles_str);
		$this->setSearchParameters();
		unset($myDb);
		unset($this->total_profiles);
	}

	// SET FUNCTIONS
	public function setSearchParameters()
        {
                $lim_14_dt = date("Y-m-d",time()-14*86400);

                //SEARCHES_14_cap
                $sql1 = "SELECT COUNT(1) AS CNT,PROFILEID FROM MIS.SEARCHQUERY WHERE PROFILEID IN ($this->total_profiles) AND DATE >= '$lim_14_dt' GROUP BY PROFILEID";
                $res1 = mysql_query_decide($sql1,$myDb) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql1.mysql_error($myDb)));
                while($row1 = mysql_fetch_array($res1)){
                        $this->searchParams[$row1["PROFILEID"]] = $row1["CNT"];
                    }
        }

	// GET FUNCTIONS
	public function getSearchParameters($profileid) {
		return  $this->searchParams[$profileid];
	}
}


?>
