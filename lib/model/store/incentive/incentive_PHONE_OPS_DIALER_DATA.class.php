<?php
class incentive_PHONE_OPS_DIALER_DATA extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
        }
        public function getData($date)
        {
                try
                {
                        $sql="SELECT * FROM incentive.PHONE_OPS_DIALER_DATA WHERE ENTRY_TIME = :ENTRY_DT ORDER BY ENTRY_TIME DESC";
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
	public function insert($insertData,$setDate)
	{
		try
		{
			foreach($insertData as $k=>$v)
			{
				if($sqlStr!='')
					$sqlStr.=",";
				$sqlStr.= "(:NUMBER_CALLED$k,:NUMBER_DISPLAY$k,:USERNAME$k,:NAME$k,:SCREENED_TIME$k,:EMAIL$k,:SET_DATE,:LEAD_ID)";
			}
			$sql="INSERT IGNORE INTO incentive.PHONE_OPS_DIALER_DATA(`NUMBER_CALLED`,`NUMBER_DISPLAY`,`USERNAME`,`NAME`,`SCREENED_TIME`,`EMAIL`,`ENTRY_TIME`,`LEAD_ID`) VALUES ".$sqlStr;
                        $prep=$this->db->prepare($sql);
			foreach($insertData as $k=>$v)
			{
				$name = $v['NAME']?$v['NAME']:'';
				$prep->bindValue(":NUMBER_CALLED".$k,"0".$v['PHONE_MOB'],PDO::PARAM_STR);
				$prep->bindValue(":NUMBER_DISPLAY".$k,"0".$v['PHONE_MOB'],PDO::PARAM_STR);
				$prep->bindValue(":USERNAME".$k,$v['USERNAME'],PDO::PARAM_STR);
				$prep->bindValue(":NAME".$k,$name,PDO::PARAM_STR);
				$prep->bindValue(":SCREENED_TIME".$k,$v['SUBMITED_TIME'],PDO::PARAM_STR);
				$prep->bindValue(":EMAIL".$k,$v['EMAIL'],PDO::PARAM_STR);
			}
			$date = date("d");
			//$prep->bindValue(":LEAD_ID","PV".$date,PDO::PARAM_STR);
			$prep->bindValue(":LEAD_ID","PV",PDO::PARAM_STR);
			$prep->bindValue(":SET_DATE",$setDate,PDO::PARAM_STR);
                        $prep->execute();
		}
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
	}
	public function getLastDate()
	{
                try
                {
                        $sql="SELECT max(SCREENED_TIME) AS SCREENED_TIME FROM incentive.PHONE_OPS_DIALER_DATA WHERE 1";
                        $prep=$this->db->prepare($sql);
                        $prep->execute();
                        if($res=$prep->fetch(PDO::FETCH_ASSOC))
                                $maxScreenTime = $res['SCREENED_TIME'];
			else
				$maxScreenTime = 0;
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $maxScreenTime;
        }

}
?>
