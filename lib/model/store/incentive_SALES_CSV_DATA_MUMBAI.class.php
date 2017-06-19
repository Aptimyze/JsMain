<?php
class incentive_SALES_CSV_DATA_MUMBAI extends TABLE
{
	public function __construct($dbname="")
	{
      		parent::__construct($dbname);
   	}

	public function insertProfile($profileid,$dialerPriority,$score,$dialerDialStatus,$allotedTo,$vdDiscount,$lastLoginDt,$phone1,$phone2,$havePhoto,$birthDt,$mstatus,$everPaid,$gender,$relation,$leadId)
	{
		try
		{
			$csvDate=date("Y-m-d",time());
			$sql= "INSERT IGNORE INTO incentive.SALES_CSV_DATA_MUMBAI(PROFILEID,PRIORITY,ANALYTIC_SCORE,OLD_PRIORITY,DIAL_STATUS,AGENT,VD_PERCENT,LAST_LOGIN_DATE,PHONE_NO1,PHONE_NO2,PHOTO,DOB,MSTATUS,EVER_PAID,GENDER,POSTEDBY,PHONE_NO3,PHONE_NO4,LEAD_ID,CSV_ENTRY_DATE) VALUES(:PROFILEID,:PRIORITY,:ANALYTIC_SCORE,:OLD_PRIORITY,:DIAL_STATUS,:AGENT,:VD_PERCENT,:LAST_LOGIN_DATE,:PHONE_NO1,:PHONE_NO2,:PHOTO,:DOB,:MSTATUS,:EVER_PAID,:GENDER,:POSTEDBY,:PHONE_NO3,:PHONE_NO4,:LEAD_ID,:CSV_ENTRY_DATE)";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->bindValue(":PRIORITY",$dialerPriority,PDO::PARAM_INT);
			$prep->bindValue(":ANALYTIC_SCORE",$score,PDO::PARAM_INT);
			$prep->bindValue(":OLD_PRIORITY",$dialerPriority,PDO::PARAM_INT);
			$prep->bindValue(":DIAL_STATUS",$dialerDialStatus,PDO::PARAM_INT);
			$prep->bindValue(":AGENT",$allotedTo,PDO::PARAM_STR);
			$prep->bindValue(":VD_PERCENT",$vdDiscount,PDO::PARAM_INT);
			$prep->bindValue(":LAST_LOGIN_DATE",$lastLoginDt,PDO::PARAM_STR);
			$prep->bindValue(":PHONE_NO1",$phone1,PDO::PARAM_STR);
			$prep->bindValue(":PHONE_NO2",$phone2,PDO::PARAM_STR);
			$prep->bindValue(":PHOTO",$havePhoto,PDO::PARAM_STR);
			$prep->bindValue(":DOB",$birthDt,PDO::PARAM_STR);
			$prep->bindValue(":MSTATUS",$mstatus,PDO::PARAM_STR);
			$prep->bindValue(":EVER_PAID",$everPaid,PDO::PARAM_STR);
			$prep->bindValue(":GENDER",$gender,PDO::PARAM_STR);
			$prep->bindValue(":POSTEDBY",$relation,PDO::PARAM_STR);
			//$prep->bindValue(":NEW_VARIABLE1",$mobile1,PDO::PARAM_STR);
			//$prep->bindValue(":NEW_VARIABLE2",$mobile1,PDO::PARAM_STR);
			//$prep->bindValue(":NEW_VARIABLE3",$mobile1,PDO::PARAM_STR);
			$prep->bindValue(":PHONE_NO3",$phone1,PDO::PARAM_STR);
                        $prep->bindValue(":PHONE_NO4",$phone2,PDO::PARAM_STR);
                        $prep->bindValue(":LEAD_ID",$leadId,PDO::PARAM_STR);
                        $prep->bindValue(":CSV_ENTRY_DATE",$csvDate,PDO::PARAM_STR);

			$prep->execute();
		}
		catch(Exception $e)
		{
        		throw new jsException($e);
		}       
	}
	public function removeProfiles($csvEntryDate)
	{
		try
		{
			$sql="DELETE FROM incentive.SALES_CSV_DATA_MUMBAI WHERE CSV_ENTRY_DATE<:CSV_ENTRY_DATE";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":CSV_ENTRY_DATE",$csvEntryDate,PDO::PARAM_STR);
			$prep->execute();
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
        public function getData($date)
        {
                try
                {
                        $sql="SELECT * FROM incentive.SALES_CSV_DATA_MUMBAI WHERE CSV_ENTRY_DATE = :CSV_ENTRY_DATE ORDER BY PRIORITY DESC,ANALYTIC_SCORE DESC,LAST_LOGIN_DATE DESC";
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
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $data;
        }
        public function getCampaignRecordsCnt()
        {
                try
                {
			$date =date("Y-m-d");
                        $sql="SELECT count(*) CNT FROM incentive.SALES_CSV_DATA_MUMBAI WHERE CSV_ENTRY_DATE = :CSV_ENTRY_DATE";
                        $prep=$this->db->prepare($sql);
                        $prep->bindValue(":CSV_ENTRY_DATE",$date,PDO::PARAM_STR);
                        $prep->execute();
                        if($res=$prep->fetch(PDO::FETCH_ASSOC))
                                $totalCnt=$res['CNT'];
                }
                catch(Exception $e){
                        throw new jsException($e);
                }
                return $totalCnt;
        }

}
?>
