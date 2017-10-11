<?php

class incentive_SALES_CSV_DATA_MOBILE_APP_REGISTRATIONS extends TABLE
{
	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	public function insertProfile($phoneMob,$username,$email,$profileid,$score,$priority,$dnc){
		try
		{
			$sql="INSERT INTO incentive.SALES_CSV_DATA_MOBILE_APP_REGISTRATIONS VALUES(:PHONEMOB,:USERNAME,:EMAIL,:PROFILEID,:SCORE,:PROIRITY,:DNC,:CSV_ENTRY_DT)";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PHONEMOB",$phoneMob,PDO::PARAM_STR);
			$prep->bindValue(":USERNAME",$username,PDO::PARAM_STR);
			$prep->bindValue(":EMAIL",$email,PDO::PARAM_STR);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->bindValue(":SCORE",$score,PDO::PARAM_INT);
			$prep->bindValue(":PROIRITY",$priority,PDO::PARAM_INT);
			$prep->bindValue(":DNC",$dnc,PDO::PARAM_INT);
			$prep->bindValue(":CSV_ENTRY_DT",date("Y-m-d"),PDO::PARAM_STR);
			$prep->execute();
		}
		catch(Exception $e){
			throw new jsException($e);
		}
	}

	public function getData($date, $dnc_status)
	{
		$start_date = $date." 00:00:00";
		$end_date = $date." 23:59:59";
		try
		{
			$sql="SELECT PHONE_MOB,USERNAME,EMAIL,PROFILEID,SCORE,PRIORITY FROM incentive.SALES_CSV_DATA_MOBILE_APP_REGISTRATIONS WHERE CSV_ENTRY_DT>=:START_DT AND CSV_ENTRY_DT<=:END_DT AND DNC=:DNC";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":START_DT",$start_date,PDO::PARAM_STR);
			$prep->bindValue(":END_DT",$end_date,PDO::PARAM_STR);
			$prep->bindValue(":DNC",$dnc_status,PDO::PARAM_INT);
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
