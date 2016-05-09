<?php

/**
 * This table is used to log entries for all the profiles that have been screened,the result of screening i.e. approved/deleted.
 **/
class MAIN_ADMIN_LOG extends TABLE
{
    public function __construct($dbname = "")
    {
        parent::__construct($dbname);
    }
    
    /**
     * This function is used to log an entry for the photo profile that has been screened.
     **/
    public function logPhotoScreeningAction($profileid, $status, $timezone,$source)
    {
	$time=time();
        $now=date('Y-m-d H:i:s',$time);

	if($source == "appPic")
	{
        	$sql = "INSERT INTO jsadmin.MAIN_ADMIN_LOG(PROFILEID,USERNAME,SCREENING_TYPE,RECEIVE_TIME,SUBMIT_TIME,ALLOT_TIME,SUBMITED_TIME,ALLOTED_TO,STATUS,TIME_ZONE, SUBMITED_TIME_IST) SELECT PROFILEID,USERNAME,:SCREENING_TYPE,RECEIVE_DATE,SUBMIT_DATE,ALLOTED_DATE,:SUBMITED_TIME,ALLOTED_TO,:STATUS,:TIMEZONE, CONVERT_TZ(:SUBMITED_TIME,:TIMEZONE,'IST') from jsadmin.SCREEN_PHOTOS_FOR_APP where PROFILEID=:PROFILEID";
	}
	else
	{
        	$sql = "INSERT INTO jsadmin.MAIN_ADMIN_LOG(PROFILEID,USERNAME,SCREENING_TYPE,RECEIVE_TIME,SUBMIT_TIME,ALLOT_TIME,SUBMITED_TIME,ALLOTED_TO,STATUS,SUBSCRIPTION_TYPE, SCREENING_VAL,TIME_ZONE, SUBMITED_TIME_IST) SELECT PROFILEID,USERNAME,SCREENING_TYPE,RECEIVE_TIME,SUBMIT_TIME,ALLOT_TIME,:SUBMITED_TIME,ALLOTED_TO,:STATUS,SUBSCRIPTION_TYPE, SCREENING_VAL,:TIMEZONE, CONVERT_TZ(:SUBMITED_TIME,:TIMEZONE,'IST') from jsadmin.MAIN_ADMIN where PROFILEID=:PROFILEID AND SCREENING_TYPE=:SCREENING_TYPE";
	}

        $res = $this->db->prepare($sql);
        $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
        $res->bindValue(":TIMEZONE", $timezone, PDO::PARAM_STR);
        $res->bindValue(":STATUS", $status, PDO::PARAM_STR);
        $res->bindValue(":SCREENING_TYPE", "P", PDO::PARAM_STR);
        $res->bindValue(":SUBMITED_TIME", $now, PDO::PARAM_STR);
        $res->execute();
    }
    /**
     * get the activated profile between given time period
     * @param start time and end time
     * @return array of profile details
     * @access public
     */
    public function getActivatedProfile($startTime, $endTime)
    {
        try {
            $sql = "SELECT DISTINCT(b.PROFILEID) screenedProfiles,b.GENDER,b.SUBSCRIPTION,b.ACTIVATED,b.INCOMPLETE,b.SOURCE FROM jsadmin.MAIN_ADMIN_LOG a, newjs.JPROFILE b WHERE a.PROFILEID=b.PROFILEID AND a.SUBMITED_TIME BETWEEN :STARTTIME AND :ENDTIME AND a.SCREENING_TYPE = 'O' AND b.ACTIVATED='Y' AND b.INCOMPLETE='N' and b.activatedKey=1";
            $res = $this->db->prepare($sql);
            $res->bindValue(":STARTTIME", $startTime, PDO::PARAM_STR);
            $res->bindValue(":ENDTIME", $endTime, PDO::PARAM_STR);
            $res->execute();
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $profileid                                  = $row["screenedProfiles"];
                $activeProfiles[$profileid]["GENDER"]       = $row["GENDER"];
                $activeProfiles[$profileid]["SUBSCRIPTION"] = $row["SUBSCRIPTION"];
                $activeProfiles[$profileid]["ACTIVATED"]    = $row["ACTIVATED"];
                $activeProfiles[$profileid]["INCOMPLETE"]   = $row["INCOMPLETE"];
                $activeProfiles[$profileid]["PROFILEID"]    = $row["screenedProfiles"];
                $activeProfiles[$profileid]["SOURCE"]       = $row["SOURCE"];
            }
            return $activeProfiles;
        }
        catch (PDOException $e) {
            throw new jsException($e);
        }
    }
    public function getUnverifiedActivatedProfiles($startTime,$endTime='',$phoneMob='',$phoneFlag='',$isd='',$return="PROFILEID")
    {
        try {
	    if($return=="PROFILEID")
		    $returnStr = "DISTINCT(b.PROFILEID) AS PROFILEID";
	    else
		    $returnStr = "DISTINCT(b.PROFILEID) AS PROFILEID, b.USERNAME, ".$return;
            $sql = "SELECT ".$returnStr." FROM jsadmin.MAIN_ADMIN_LOG a, newjs.JPROFILE b WHERE a.PROFILEID=b.PROFILEID AND a.SUBMITED_TIME>= :STARTTIME AND a.SCREENING_TYPE = 'O' AND b.ACTIVATED='Y' AND b.INCOMPLETE='N' and b.activatedKey=1 AND MOB_STATUS!='Y' AND LANDL_STATUS!='Y' AND SCREENING_VAL=0 ";
	    if($endTime)
		$sql.=" AND a.SUBMITED_TIME<=:ENDTIME ";
	    if(is_array($phoneMob))
	    {
		$sql.=" AND PHONE_MOB NOT IN ( ";
		$sqlPhone='';
		foreach($phoneMob as $k=>$v)
		{
			if($sqlPhone!='')
				$sqlPhone.=",";
			$sqlPhone.=":PHONE_MOB".$k;
		}
		$sql.=$sqlPhone." ) ";
	    }
	    if($phoneFlag)
		$sql.=" AND b.PHONE_FLAG!=:PHONE_FLAG ";
	    if($isd)
		$sql.=" AND b.ISD=:ISD ";
            $res = $this->db->prepare($sql);
            $res->bindValue(":STARTTIME", $startTime, PDO::PARAM_STR);
	    if(is_array($phoneMob))
	    {
		foreach($phoneMob as $k=>$v)
			$res->bindValue(":PHONE_MOB".$k, $v, PDO::PARAM_STR);
	    }
	    if($endTime)
		    $res->bindValue(":ENDTIME", $endTime, PDO::PARAM_STR);
	    if($phoneFlag)
		    $res->bindValue(":PHONE_FLAG", $phoneFlag, PDO::PARAM_STR);
	    if($isd)
		    $res->bindValue(":ISD", $isd, PDO::PARAM_STR);
            $res->execute();
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
		if($return=="PROFILEID")
			$profiles[]       = $row["PROFILEID"];
		else
			$profiles[]	  =  $row;
            }
            return $profiles;
        }
        catch (PDOException $e) {
            throw new jsException($e);
        }

    }
}
?>
