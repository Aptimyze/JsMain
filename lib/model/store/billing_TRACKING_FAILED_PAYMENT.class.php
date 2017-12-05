<?php
class billing_TRACKING_FAILED_PAYMENT extends TABLE {
  
 	public function __construct($dbname = "") 
	{
		parent::__construct($dbname);
  	}

        public function trackingPaymentPage($profileid,$services,$netAmount,$discount,$currency,$paymentTabClick,$device)
        {
                try
                {
			$dateTime =date("Y-m-d H:i:s",time());
                        $sql="REPLACE INTO billing.TRACKING_FAILED_PAYMENT(PROFILEID,ENTRY_DT,SERVICES,NET_AMOUNT,DISCOUNT,CURRENCY,PAYMENT_OPTION_SELECTED,SOURCE) VALUES(:PROFILEID,:DATE_TIME,:SERVICES,:NET_AMOUNT,:DISCOUNT,:CURRENCY,:PAYMENT_OPTION_SELECTED,:SOURCE)";
                        $row = $this->db->prepare($sql);
                        $row->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			$row->bindValue(":SERVICES",$services, PDO::PARAM_STR);
			$row->bindValue(":NET_AMOUNT",$netAmount, PDO::PARAM_INT);
			$row->bindValue(":DISCOUNT",$discount, PDO::PARAM_INT);
			$row->bindValue(":CURRENCY",$currency, PDO::PARAM_STR);
			$row->bindValue(":PAYMENT_OPTION_SELECTED",$paymentTabClick, PDO::PARAM_STR);
			$row->bindValue(":DATE_TIME",$dateTime, PDO::PARAM_STR);
            $row->bindValue(":SOURCE",$device, PDO::PARAM_STR);
                        $row->execute();
                }
                catch(Exception $e)
                {	
                        throw new jsException($e);
                }
        }
	public function getFailedPaymentProfiles($startDt='', $endDt='')
	{
		try{
			$profileidArr =array();
			$i=0;
			if(!$startDt && !$endDt){	
				$startDt =date("Y-m-d H:i:s", time()-15*60);
				$endDt =date("Y-m-d H:i:s", time()-10*60);
			}
			$sql ="select PROFILEID,ENTRY_DT,SERVICES,SOURCE,NET_AMOUNT,DISCOUNT,SOURCE from billing.TRACKING_FAILED_PAYMENT where ENTRY_DT>=:START_DT AND ENTRY_DT<:END_DT ORDER BY ENTRY_DT DESC";
			$row = $this->db->prepare($sql);
			$row->bindValue(":START_DT",$startDt, PDO::PARAM_STR);
			$row->bindValue(":END_DT",$endDt, PDO::PARAM_STR);
			$row->execute();
            
			while($result=$row->fetch(PDO::FETCH_ASSOC)){
				$profileidArr[$i]['PROFILEID'] =$result['PROFILEID'];	
				$profileidArr[$i]['ENTRY_DT'] =$result['ENTRY_DT'];
				$profileidArr[$i]['SERVICES'] =$result['SERVICES'];
				$profileidArr[$i]['SOURCE'] =$result['SOURCE'];
				$profileidArr[$i]['NET_AMOUNT'] =$result['NET_AMOUNT'];
				$profileidArr[$i]['DISCOUNT'] =$result['DISCOUNT'];
				$profileidArr[$i]['SOURCE'] =$result['SOURCE'];
				$i++;
			}
			return $profileidArr;
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}	
	}
        public function getFailedPaymentProfilesForDateRange($startDt,$endDt)
        {
                try{
                        $profileidArr =array();
                        $sql ="select PROFILEID,ENTRY_DT from billing.TRACKING_FAILED_PAYMENT where ENTRY_DT>=:START_DT AND ENTRY_DT<:END_DT ORDER BY ENTRY_DT DESC";
                        $row = $this->db->prepare($sql);
                        $row->bindValue(":START_DT",$startDt, PDO::PARAM_STR);
                        $row->bindValue(":END_DT",$endDt, PDO::PARAM_STR);
                        $row->execute();
                        while($result=$row->fetch(PDO::FETCH_ASSOC)){
                                $profileidArr[]['PROFILEID'] =$result['PROFILEID'];
                                $profileidArr[]['ENTRY_DT'] =$result['ENTRY_DT'];
                        }
                        return $profileidArr;
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
        public function getLatestProfileDetails($profileid)
        {
                try{
                        $sql ="select * from billing.TRACKING_FAILED_PAYMENT where PROFILEID=:PROFILEID";
                        $row = $this->db->prepare($sql);
                        $row->bindValue(":PROFILEID",$profileid, PDO::PARAM_INT);
                        $row->execute();
			$result=$row->fetch(PDO::FETCH_ASSOC);
			return $result;
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }

    public function searchProfileInCSV($profileID,$entryDate){
        try{
            $sql = "SELECT PROFILEID FROM billing.TRACKING_FAILED_PAYMENT WHERE ENTRY_DATE >= :ENTRY_DATE AND PROFILEID = :PROFILEID LIMIT 1";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":ENTRY_DATE",$entryDate,PDO::PARAM_STR);
            $prep->bindValue(":PROFILEID",$profileID,PDO::PARAM_INT);
            $prep->execute();
            if($res=$prep->fetch(PDO::FETCH_ASSOC)) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e){
            throw new jsException($e);
        }
    }

}
