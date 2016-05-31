<?php
class MIS_LOGIN_TRACKING extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
        }

        public function getLast7DaysLoginProfiles($profileStr)
        {
                if(!$profileStr)
                        throw new jsException("","profiles blank passed in");
                try
                {
			$todayDate =date("Y-m-d");
			$date7 =date("Y-m-d",strtotime("$todayDate -6 days"))." 00:00:00";
                        $sql = "select distinct PROFILEID from MIS.LOGIN_TRACKING where PROFILEID IN($profileStr) AND DATE>=:DATE AND WEBSITE_VERSION IN('A','I')";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":DATE",$date7,PDO::PARAM_STR);
                        $res->execute();
                        while($result = $res->fetch(PDO::FETCH_ASSOC))
                                $profilesArr[] =$result['PROFILEID'];
                        return $profilesArr;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
        public function getLastLoginProfilesForDate($profileStr,$lastLoginDt, $channelStr)
        {     
                if(!$profileStr || !$lastLoginDt)
                        throw new jsException("","profiles/date blank passed");
                try
                {
                        $sql = "select distinct PROFILEID from MIS.LOGIN_TRACKING where PROFILEID IN($profileStr) AND DATE>=:DATE AND WEBSITE_VERSION IN($channelStr)";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":DATE",$lastLoginDt,PDO::PARAM_STR);
                        $res->execute();
                        while($result = $res->fetch(PDO::FETCH_ASSOC))
                                $profilesArr[] =$result['PROFILEID'];
                        return $profilesArr;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
        public function createTempTablePool($startDate, $endDate)
        {
                try{
			$sql ="insert into test.TEMP_LOGIN_TRACKING SELECT PROFILEID,DATE from MIS.LOGIN_TRACKING WHERE DATE>=:START_DATE AND DATE<=:END_DATE AND WEBSITE_VERSION IN('A','I')";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":START_DATE",$startDate, PDO::PARAM_STR);
                        $res->bindValue(":END_DATE",$endDate, PDO::PARAM_STR);
                        $res->execute();
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
        }
        
        public function getLastLoginDataForDate($profileStr,$lastLoginDt, $channelStr)
        {
                if(!$profileStr || !$lastLoginDt)
                        throw new jsException("","profiles/date blank passed");
                try
                {
                    
                    $sql = "select distinct PROFILEID, WEBSITE_VERSION from MIS.LOGIN_TRACKING where PROFILEID IN ($profileStr) AND DATE >= :DATE AND WEBSITE_VERSION IN (".$channelStr.")";
                    
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":DATE",$lastLoginDt,PDO::PARAM_STR);
                        $res->execute();
                        while($result = $res->fetch(PDO::FETCH_ASSOC)){
                            $output[$result["PROFILEID"]][$result["WEBSITE_VERSION"]] = 1;
                        }
                        return $output;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }

        public function getLoginChannel($profileid,$date)
        {
                if (!$profileid)
                        throw new jsException("", "Profile id not passed");
                try {
                        $sql = "SELECT DISTINCT `WEBSITE_VERSION` FROM MIS.`LOGIN_TRACKING` WHERE `PROFILEID`=:PROFILEID AND date(DATE)>=:DATE";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":PROFILEID", $profileid,PDO::PARAM_INT);
                        $res->bindValue(":DATE", $date,PDO::PARAM_STR);
                        $res->execute();
                        while ($result = $res->fetch(PDO::FETCH_ASSOC)) {
                                $output[] = $result['WEBSITE_VERSION'];
                        }
                        return $output;
                } catch (PDOException $e) {
                        throw new jsException($e);
                }
        }


}
?>
