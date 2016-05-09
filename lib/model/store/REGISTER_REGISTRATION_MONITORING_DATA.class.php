<?php
class REGISTER_REGISTRATION_MONITORING_DATA extends TABLE{


	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	//This function inserts Hour, Channel, Min, Max, Avg, Standard Deviation of count of complete registration profiles across all channels
	public function insertMonitoringData($dataArray){
		$sqlPart="";
		try
		{
			$sql="REPLACE INTO REGISTER.REGISTRATION_MONITORING_DATA(HOUR,CHANNEL,MIN,MAX,AVG,STD) VALUES ";
			foreach($dataArray as $key=>$value)
			{
				if($sqlPart!='')
					$sqlPart.=",";
				$sqlPart.= "(:HOUR".$key.",:CHANNEL".$key.",:MIN".$key.",:MAX".$key.",:AVG".$key.",:STD".$key.")";
			}
			$sql.=$sqlPart;
			$res = $this->db->prepare($sql);
			foreach($dataArray as $k=>$v)
			{
				$res->bindValue(":HOUR".$k,$v['HOUR'],PDO::PARAM_INT);
				$res->bindValue(":CHANNEL".$k,$v['CHANNEL'],PDO::PARAM_STR);
				$res->bindValue(":MIN".$k,$v['Min'],PDO::PARAM_INT);
				$res->bindValue(":MAX".$k,$v['Max'],PDO::PARAM_INT);
				$res->bindValue(":AVG".$k,$v['Avg'],PDO::PARAM_INT);
				$res->bindValue(":STD".$k,$v['StDev'],PDO::PARAM_STR);
			
			}
			$res->execute();
		}
		catch(PDOException $e)
		{
			 throw new jsException($e);
		}
	}

	//This function will return the Min count for the given hour and given Channel
	public function getMonitoredData($hour,$channel)
	{
		$sql="SELECT MIN FROM REGISTER.REGISTRATION_MONITORING_DATA WHERE HOUR=:HOUR AND CHANNEL=:CHANNEL";
		$prep=$this->db->prepare($sql);
		$prep->bindValue(":HOUR",$hour,PDO::PARAM_INT);
		$prep->bindValue(":CHANNEL",$channel,PDO::PARAM_INT);
		$prep->execute();
        $result = $prep->fetchAll(PDO::FETCH_ASSOC);
        if($prep->rowCount() == 0){
          return 0;
        }
        return $result;
	}

}