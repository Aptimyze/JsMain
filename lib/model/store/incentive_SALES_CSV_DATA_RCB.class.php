<?php
class incentive_SALES_CSV_DATA_RCB extends TABLE
{
	public function __construct($dbname="")
	{
      		parent::__construct($dbname);
   	}

	public function insertProfile($profileid,$dialerPriority,$score,$dialerDialStatus,$allotedTo,$vdDiscount,$lastLoginDt,$phone1,$phone2,$havePhoto,$birthDt,$mstatus,$everPaid,$gender,$relation,$leadId,$csvDate,$username,$country,$source,$callTime)
	{
		try
		{
			//$csvDate=date("Y-m-d H:i:s",time());
			$sql= "INSERT IGNORE INTO incentive.SALES_CSV_DATA_RCB(PROFILEID,PRIORITY,ANALYTIC_SCORE,OLD_PRIORITY,DIAL_STATUS,SOURCE,AGENT,VD_PERCENT,LAST_LOGIN_DATE,PHONE_NO1,PHONE_NO2,PHOTO,DOB,MSTATUS,EVER_PAID,GENDER,POSTEDBY,PHONE_NO3,PHONE_NO4,USERNAME,COUNTRY,PREFERRED_TIME_IST,LEAD_ID,CSV_ENTRY_DATE) VALUES(:PROFILEID,:PRIORITY,:ANALYTIC_SCORE,:OLD_PRIORITY,:DIAL_STATUS,:SOURCE,:AGENT,:VD_PERCENT,:LAST_LOGIN_DATE,:PHONE_NO1,:PHONE_NO2,:PHOTO,:DOB,:MSTATUS,:EVER_PAID,:GENDER,:POSTEDBY,:PHONE_NO3,:PHONE_NO4,:USERNAME,:COUNTRY,:PREFERRED_TIME_IST,:LEAD_ID,:CSV_ENTRY_DATE)";
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
			$prep->bindValue(":PHONE_NO3",$phone1,PDO::PARAM_STR);
                        $prep->bindValue(":PHONE_NO4",$phone2,PDO::PARAM_STR);
			$prep->bindValue(":SOURCE",$source,PDO::PARAM_STR);

                        $prep->bindValue(":USERNAME",$username,PDO::PARAM_STR);
                        $prep->bindValue(":COUNTRY",$country,PDO::PARAM_STR);

                        $prep->bindValue(":LEAD_ID",$leadId,PDO::PARAM_STR);
                        $prep->bindValue(":CSV_ENTRY_DATE",$csvDate,PDO::PARAM_STR);
			$prep->bindValue(":PREFERRED_TIME_IST",$callTime,PDO::PARAM_STR);
		
			$prep->execute();
		}
		catch(Exception $e)
		{
        		throw new jsException($e);
		}       
	}
        public function updateDialStatus($dateTime)
        {
                try
                {
                        $sql="UPDATE incentive.SALES_CSV_DATA_RCB SET DIAL_STATUS=0 WHERE CSV_ENTRY_DATE<:CSV_ENTRY_DATE AND DIAL_STATUS>0";
                        $prep=$this->db->prepare($sql);
                        $prep->bindValue(":CSV_ENTRY_DATE",$dateTime,PDO::PARAM_STR);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $data;
        }

    public function searchProfileInCSV($profileID){
        try{
            $sql = "SELECT PROFILEID FROM incentive.SALES_CSV_DATA_RCB WHERE PROFILEID = :PROFILEID AND DIAL_STATUS = :DIAL_STATUS";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileID,PDO::PARAM_INT);
            $prep->bindValue(":DIAL_STATUS",1,PDO::PARAM_INT);
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
?>
