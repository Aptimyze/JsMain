<?php
class incentive_QA_ONLINE_CSV_DATA extends TABLE
{
	public function __construct($dbname="")
	{
      		parent::__construct($dbname);
   	}

	public function insertProfile($profileid, $email, $phoneMob, $phoneRes, $score, $type, $csvDate)
	{
		try
		{
			$sql= "INSERT IGNORE INTO incentive.QA_ONLINE_CSV_DATA(`PROFILEID`,`SCORE`,`EMAIL`,`MOBILE`,`LANDLINE`,`TYPE`,`CSV_ENTRY_DATE`) VALUES(:PROFILEID,:SCORE,:EMAIL,:MOBILE,:LANDLINE,:TYPE,:CSV_ENTRY_DATE)";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->bindValue(":SCORE",$score,PDO::PARAM_INT);
			$prep->bindValue(":EMAIL",$email,PDO::PARAM_STR);
			$prep->bindValue(":MOBILE",$phoneMob,PDO::PARAM_STR);
			$prep->bindValue(":LANDLINE",$phoneRes,PDO::PARAM_STR);
			$prep->bindValue(":TYPE",$type,PDO::PARAM_STR);
			$prep->bindValue(":CSV_ENTRY_DATE",$csvDate,PDO::PARAM_STR);
			$prep->execute();
		}
		catch(Exception $e)
		{
        		throw new jsException($e);
		}       
	}
        public function getData($date, $type)
        {
                try
                {
                        $sql="SELECT * FROM incentive.QA_ONLINE_CSV_DATA WHERE CSV_ENTRY_DATE = :CSV_ENTRY_DATE AND TYPE=:TYPE";
                        $prep=$this->db->prepare($sql);
                        $prep->bindValue(":CSV_ENTRY_DATE",$date,PDO::PARAM_STR);
			$prep->bindValue(":TYPE",$type,PDO::PARAM_STR);
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
