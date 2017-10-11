<?php
class incentive_FTA_CSV_DATA extends TABLE
{
	public function __construct($dbname="")
	{
      		parent::__construct($dbname);
   	}

	public function insertProfile($profileid,$username,$regDate,$gender,$postedBy,$photo,$phoneVerified,$login_freq_perc,$eoi_waiting,$eoi_declined,$eoi_rcvd_vs_viewed,$photo_request,$eligible,$mobile1,$mobile2,$landline,$mobile1,$mobile2,$landline,$priority)
	{
		try
		{
			if(!$postedBy)
				$postedBy='';
			$csvDate=date("Y-m-d",time());
			$sql= "INSERT INTO incentive.FTA_CSV_DATA(PROFILEID,USERNAME,ENTRY_DATE,GENDER,RELATION,HAVEPHOTO,PHONE_VERIFIED,LOGIN_FREQ_PERC,EOI_WAITING,EOI_DECLINED,EOI_RCVD_VS_VIEWED,PHOTO_REQUEST_RECIEVED,RESPONSE_BOOSTER_ELIGIBLE,MOBILE1,MOBILE2,LANDLINE,MOBILE1_COPY,MOBILE2_COPY,LANDLINE_COPY,PRIORITY,CSV_ENTRY_DATE) VALUES(:PROFILEID,:USERNAME,:ENTRY_DATE,:GENDER,:RELATION,:HAVEPHOTO,:PHONE_VERIFIED,:LOGIN_FREQ_PERC,:EOI_WAITING,:EOI_DECLINED,:EOI_RCVD_VS_VIEWED,:PHOTO_REQUEST,:RESPONSE_BOOSTER_ELIGIBLE,:MOBILE1,:MOBILE2,:LANDLINE,:MOBILE1_COPY,:MOBILE2_COPY,:LANDLINE_COPY,:PRIORITY,:CSV_ENTRY_DATE)";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->bindValue(":USERNAME",$username,PDO::PARAM_STR);
			$prep->bindValue(":ENTRY_DATE",$regDate,PDO::PARAM_STR);
			$prep->bindValue(":GENDER",$gender,PDO::PARAM_STR);
			$prep->bindValue(":RELATION",$postedBy,PDO::PARAM_STR);
			$prep->bindValue(":HAVEPHOTO",$photo,PDO::PARAM_STR);
			$prep->bindValue(":PHONE_VERIFIED",$phoneVerified,PDO::PARAM_STR);
			$prep->bindValue(":LOGIN_FREQ_PERC",$login_freq_perc,PDO::PARAM_INT);
			$prep->bindValue(":EOI_WAITING",$eoi_waiting,PDO::PARAM_INT);
			$prep->bindValue(":EOI_DECLINED",$eoi_declined,PDO::PARAM_INT);
			$prep->bindValue(":EOI_RCVD_VS_VIEWED",$eoi_rcvd_vs_viewed,PDO::PARAM_INT);
			$prep->bindValue(":PHOTO_REQUEST",$photo_request,PDO::PARAM_INT);
			$prep->bindValue(":RESPONSE_BOOSTER_ELIGIBLE",$eligible,PDO::PARAM_STR);
			$prep->bindValue(":CSV_ENTRY_DATE",$csvDate,PDO::PARAM_STR);
			$prep->bindValue(":MOBILE1",$mobile1,PDO::PARAM_STR);
			$prep->bindValue(":MOBILE2",$mobile2,PDO::PARAM_STR);
			$prep->bindValue(":LANDLINE",$landline,PDO::PARAM_STR);
			$prep->bindValue(":MOBILE1_COPY",$mobile1,PDO::PARAM_STR);
			$prep->bindValue(":MOBILE2_COPY",$mobile2,PDO::PARAM_STR);
			$prep->bindValue(":LANDLINE_COPY",$landline,PDO::PARAM_STR);
			$prep->bindValue(":PRIORITY",$priority,PDO::PARAM_INT);
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
			$sql="DELETE FROM incentive.FTA_CSV_DATA WHERE CSV_ENTRY_DATE<:CSV_ENTRY_DATE";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":CSV_ENTRY_DATE",$csvEntryDate,PDO::PARAM_STR);
			$prep->execute();
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
	public function truncate()
	{
		try
		{
			$sql="TRUNCATE TABLE incentive.TEMP_CSV_FTA_TECH";
			$prep=$this->db->prepare($sql);
			$prep->execute();
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
	public function insertProfilesToCsvTable($entryDt)
	{
		try
		{
			$sql="INSERT INTO incentive.FTA_CSV_DATA(PROFILEID) (SELECT PROFILEID FROM newjs.JPROFILE WHERE ENTRY_DT > :ENTRY_DT)";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":ENTRY_DT",$entryDt,PDO::PARAM_STR);
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
			$sql="SELECT * FROM incentive.FTA_CSV_DATA WHERE CSV_ENTRY_DATE = :ENTRY_DT ORDER BY ENTRY_DATE DESC";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":ENTRY_DT",$date,PDO::PARAM_STR);
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
}
?>
