<?php
class incentive_SALES_CSV_DATA_FAILED_PAYMENT extends TABLE
{
	public function __construct($dbname="")
	{
      		parent::__construct($dbname);
   	}

	public function insertProfile($profileid,$dialerPriority,$score,$dialerDialStatus,$allotedTo,$vdDiscount,$lastLoginDt,$phone1,$phone2,$havePhoto,$birthDt,$mstatus,$everPaid,$gender,$relation,$leadId,$csvDate,$username,$serviceSelected,$fpEntryDt,$discount,$onlineStatus,$netAmount,$country,$source)
	{
		try
		{
			//$csvDate=date("Y-m-d H:i:s",time());
			$sql= "INSERT IGNORE INTO incentive.SALES_CSV_DATA_FAILED_PAYMENT(PROFILEID,PRIORITY,ANALYTIC_SCORE,OLD_PRIORITY,DIAL_STATUS,SOURCE,AGENT,VD_PERCENT,LAST_LOGIN_DATE,PHONE_NO1,PHONE_NO2,PHOTO,DOB,MSTATUS,EVER_PAID,GENDER,POSTEDBY,PHONE_NO3,PHONE_NO4,USERNAME,SERVICE_SELECTED,FP_ENTRY_DT,DISCOUNT,ONLINE_STATUS,LAST_AMOUNT_TRIED,COUNTRY,LEAD_ID,CSV_ENTRY_DATE) VALUES(:PROFILEID,:PRIORITY,:ANALYTIC_SCORE,:OLD_PRIORITY,:DIAL_STATUS,:SOURCE,:AGENT,:VD_PERCENT,:LAST_LOGIN_DATE,:PHONE_NO1,:PHONE_NO2,:PHOTO,:DOB,:MSTATUS,:EVER_PAID,:GENDER,:POSTEDBY,:PHONE_NO3,:PHONE_NO4,:USERNAME,:SERVICE_SELECTED,:FP_ENTRY_DT,:DISCOUNT,:ONLINE_STATUS,:LAST_AMOUNT_TRIED,:COUNTRY,:LEAD_ID,:CSV_ENTRY_DATE)";
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
			$prep->bindValue(":SOURCE",$source,PDO::PARAM_STR);

                        $prep->bindValue(":USERNAME",$username,PDO::PARAM_STR);
                        $prep->bindValue(":SERVICE_SELECTED",$serviceSelected,PDO::PARAM_STR);
                        $prep->bindValue(":FP_ENTRY_DT",$fpEntryDt,PDO::PARAM_STR);
                        $prep->bindValue(":DISCOUNT",$discount,PDO::PARAM_INT);
                        $prep->bindValue(":ONLINE_STATUS",$onlineStatus,PDO::PARAM_STR);
                        $prep->bindValue(":LAST_AMOUNT_TRIED",$netAmount,PDO::PARAM_INT);
                        $prep->bindValue(":COUNTRY",$country,PDO::PARAM_STR);

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
			$sql="DELETE FROM incentive.SALES_CSV_DATA_NOIDA WHERE CSV_ENTRY_DATE<:CSV_ENTRY_DATE";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":CSV_ENTRY_DATE",$csvEntryDate,PDO::PARAM_STR);
			$prep->execute();
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
        public function getData($dateSet)
        {
                try
                {
			$dateArr 	=explode("#", $dateSet);
			$dateTime1	=$dateArr[0];
			$dateTime2	=$dateArr[1];
                        $sql="SELECT * FROM incentive.SALES_CSV_DATA_FAILED_PAYMENT WHERE CSV_ENTRY_DATE>=:CSV_ENTRY_DATE1 AND CSV_ENTRY_DATE<:CSV_ENTRY_DATE2 AND DIAL_STATUS>0";
                        $prep=$this->db->prepare($sql);
                        $prep->bindValue(":CSV_ENTRY_DATE1",$dateTime1,PDO::PARAM_STR);
			$prep->bindValue(":CSV_ENTRY_DATE2",$dateTime2,PDO::PARAM_STR);
                        $prep->execute();
                        $i=0;
                        while($res=$prep->fetch(PDO::FETCH_ASSOC))
                        {
				$res['DATE_FP'] =$dateTime2;
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
        public function updateDialStatus($dateTime,$profiles='')
        {
                try
                {
			if(count($profiles)>0){
				$profileStr =implode(",",$profiles);
			}
                        $sql="UPDATE incentive.SALES_CSV_DATA_FAILED_PAYMENT SET DIAL_STATUS=0 WHERE CSV_ENTRY_DATE<:CSV_ENTRY_DATE AND DIAL_STATUS>0";
			if($profileStr)
				$sql .=" AND PROFILEID NOT IN($profileStr)";	
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
        public function getObseleteProfiles()
        {
                try
                {
                        $sql="select PROFILEID from incentive.SALES_CSV_DATA_FAILED_PAYMENT WHERE DIAL_STATUS=0";
                        $prep=$this->db->prepare($sql);
                        $prep->execute();
                        while($res=$prep->fetch(PDO::FETCH_ASSOC))
                                $profiles[]=$res['PROFILEID'];
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $profiles;
        }
}
?>
