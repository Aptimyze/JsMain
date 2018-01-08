<?php
class incentive_SALES_CAMPAIGN_PROFILE_DETAILS extends TABLE {
  
  public function __construct($dbname = "") {
    parent::__construct($dbname);
  }

	public function getProfiles()
        {
                try{
                        $sql ="select PROFILEID, CAMPAIGN from incentive.SALES_CAMPAIGN_PROFILE_DETAILS WHERE MAIL_SENT='N'";
                        $row = $this->db->prepare($sql);
                        $row->execute();
                        while($result=$row->fetch(PDO::FETCH_ASSOC)){
                                $profileidArr[$result['PROFILEID']] = $result['CAMPAIGN'];
                        }
                        return $profileidArr;
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
        public function updateMailerStatus($profileid, $deliveryStatus)
        {
                try
                {
                        $sql = "UPDATE incentive.SALES_CAMPAIGN_PROFILE_DETAILS SET MAIL_SENT=:MAIL_SENT WHERE PROFILEID=:PROFILEID";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$res->bindValue(":MAIL_SENT",$deliveryStatus,PDO::PARAM_STR);
                        $res->execute();
                }
                catch(PDOException $e){
                        die($e);
                        throw new jsException($e);
                }
        }
	public function getMailCountForRange()
        {
                try{
                        $sql = "SELECT count(1) as cnt,MAIL_SENT as SENT FROM incentive.SALES_CAMPAIGN_PROFILE_DETAILS group by MAIL_SENT";
                        $res=$this->db->prepare($sql);
                        $res->execute();
                        $total = 0;
                        while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
                                if($row['SENT']=='Y')
                                        $output['SENT'] = $row['cnt'];
                                if($row['SENT']=='B')
                                        $output['BOUNCED'] = $row['cnt'];
                                if($row['SENT']=='I')
                                        $output['INCOMPLETE'] = $row['cnt'];
                                if($row['SENT']=='U')
                                        $output['UNSUBSCRIBE'] = $row['cnt'];
                                $total = $total+$row['cnt'];
                        }
                        $output['TOTAL'] = $total;
                }
                catch(PDOException $e)
                {
                   throw new jsException($e);
                }
                return $output;

        }
     public function getCountSentMailPreviousDate($startDate,$endDate){
         try {
             $sql="SELECT count(*) as count, campaign FROM incentive.SALES_CAMPAIGN_PROFILE_DETAILS where MAIL_SENT='Y' AND DATE >= :START_DATE AND DATE <= :END_DATE GROUP BY CAMPAIGN";
             $res = $this->db->prepare($sql);
             $res->bindValue(":START_DATE",$startDate,PDO::PARAM_STR);
             $res->bindValue(":END_DATE",$endDate,PDO::PARAM_STR);
             $res->execute();
             while($row = $res->fetch(PDO::FETCH_ASSOC)){
                 $campaign = $row['campaign'];
                 $count = $row['count'];
                 $resArr[$campaign] = $count;
             }
         } catch (PDOException $e) {
             throw new jsException($e);
         }
         return $resArr;
     }
}
