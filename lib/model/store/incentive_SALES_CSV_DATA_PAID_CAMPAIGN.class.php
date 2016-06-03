<?php
class incentive_SALES_CSV_DATA_PAID_CAMPAIGN extends TABLE
{
	public function __construct($dbname="")
	{
      		parent::__construct($dbname);
   	}

	public function insertProfile($profileid,$dialerDialStatus,$username,$phone1='',$phone2='',$gender,$membership='',$addon='',$paymentDate,$leadId)
	{
		try{
			$csvDate=date("Y-m-d",time());
			$sql= "INSERT IGNORE INTO incentive.SALES_CSV_DATA_PAID_CAMPAIGN(PROFILEID,USERNAME,DIAL_STATUS,PHONE_NO1,PHONE_NO2,GENDER,MEMBERSHIP,ADDON,PAYMENT_DT,LEAD_ID,CSV_ENTRY_DATE) VALUES(:PROFILEID,:USERNAME,:DIAL_STATUS,:PHONE_NO1,:PHONE_NO2,:GENDER,:MEMBERSHIP,:ADDON,:PAYMENT_DT,:LEAD_ID,:CSV_ENTRY_DATE)";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->bindValue(":USERNAME",$username,PDO::PARAM_STR);
			$prep->bindValue(":DIAL_STATUS",$dialerDialStatus,PDO::PARAM_INT);
			$prep->bindValue(":PHONE_NO1",$phone1,PDO::PARAM_STR);
			$prep->bindValue(":PHONE_NO2",$phone2,PDO::PARAM_STR);
			$prep->bindValue(":GENDER",$gender,PDO::PARAM_STR);
			$prep->bindValue(":MEMBERSHIP",$membership,PDO::PARAM_STR);
			$prep->bindValue(":ADDON",$addon,PDO::PARAM_STR);
                        $prep->bindValue(":LEAD_ID",$leadId,PDO::PARAM_STR);
			$prep->bindValue(":PAYMENT_DT",$paymentDate,PDO::PARAM_STR);
                        $prep->bindValue(":CSV_ENTRY_DATE",$csvDate,PDO::PARAM_STR);
			$prep->execute();
		}
		catch(Exception $e){
        		throw new jsException($e);
		}       
	}
        public function updateDialStatus($dateTime)
        {
                try
                {
                        $sql="UPDATE incentive.SALES_CSV_DATA_PAID_CAMPAIGN SET DIAL_STATUS=0 WHERE CSV_ENTRY_DATE<:CSV_ENTRY_DATE AND DIAL_STATUS>0";
                        $prep=$this->db->prepare($sql);
                        $prep->bindValue(":CSV_ENTRY_DATE",$dateTime,PDO::PARAM_STR);
                        $prep->execute();
                }
                catch(Exception $e){
                        throw new jsException($e);
                }
        }
        public function getData($date)
        {
                try{
                        $sql="SELECT * FROM incentive.SALES_CSV_DATA_RENEWAL WHERE CSV_ENTRY_DATE = :CSV_ENTRY_DATE ORDER BY PRIORITY DESC,ANALYTIC_SCORE DESC,LAST_LOGIN_DATE DESC";
                        $prep=$this->db->prepare($sql);
                        $prep->bindValue(":CSV_ENTRY_DATE",$date,PDO::PARAM_STR);
                        $prep->execute();
                        $i=0;
                        while($res=$prep->fetch(PDO::FETCH_ASSOC))
                        {
                                $data[$i]=$res;
                                $i++;
                        }
                }
                catch(Exception $e){
                        throw new jsException($e);
                }
                return $data;
        }

}
?>
