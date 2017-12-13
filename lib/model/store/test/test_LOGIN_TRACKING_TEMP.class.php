<?php
class test_LOGIN_TRACKING_TEMP extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
        }

        public function addInTempPool($startDate)
        {
                try{
			$sql ="insert into test.LOGIN_TRACKING_TEMP SELECT PROFILEID,DATE,CHANNEL,WEBSITE_VERSION from MIS.LOGIN_TRACKING WHERE DATE>=:START_DATE";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":START_DATE",$startDate, PDO::PARAM_STR);
                        $res->execute();
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
        }
        public function getMaxDate()
        {
                try
                {
                        $sql = "select max(DATE) DATE from test.LOGIN_TRACKING_TEMP";
                        $res = $this->db->prepare($sql);
                        $res->execute();
                        if($result = $res->fetch(PDO::FETCH_ASSOC))
                                $dateTime =$result['DATE'];
                        return $dateTime;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }

        
        public function getLastLoginDataForDate($profileStr,$lastLoginDt, $channelStr)
        {
                if(!$profileStr || !$lastLoginDt)
                        throw new jsException("","profiles/date blank passed");
                try{
                    $sql = "select distinct PROFILEID, WEBSITE_VERSION from test.LOGIN_TRACKING_TEMP where PROFILEID IN ($profileStr) AND DATE >= :DATE AND WEBSITE_VERSION IN (".$channelStr.")";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":DATE",$lastLoginDt,PDO::PARAM_STR);
                        $res->execute();
                        while($result = $res->fetch(PDO::FETCH_ASSOC)){
                            $output[$result["PROFILEID"]][$result["WEBSITE_VERSION"]] = 1;
                        }
                        return $output;
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
        }
        public function deleteFromTempPool($endDate)
        {
                try{
                        $sql ="delete from test.LOGIN_TRACKING_TEMP WHERE DATE<:END_DATE";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":END_DATE",$endDate, PDO::PARAM_STR);
                        $res->execute();
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
        }


}
?>
