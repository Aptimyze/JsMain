<?php
class VIEW_LOG_TRIGGER extends TABLE{
       

       
    const MailerDaysCheck = 1;
    const VisitorsDaysCheck = 15;

    public function __construct($dbname="")
        {
			if(!JsConstants::$communicationRep)
				$dbname=$dbname?$dbname:"viewLogRep";
			else
				$dbname=$dbname?$dbname:"shard2_master";
			parent::__construct($dbname);
        }
        public function updateViewTrigger($viewer,$viewed)
        {
			try 
			{

				$sqlUpdate="REPLACE INTO VIEW_LOG_TRIGGER  (VIEWER,VIEWED,DATE) VALUES ('$viewer','$viewed',now())";
				//$sqlUpdate = "UPDATE MIS.FEATURED_PROFILE_VIEW SET COUNT=COUNT+1 WHERE DATE='$date'";

				$this->db->exec($sqlUpdate);
				
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
		public function updateViewLog($viewer,$viewed)
        {
			try 
			{

				$sql="insert ignore into VIEW_LOG(VIEWER,VIEWED,DATE,VIEWED_MMM) values ('$viewer','$viewed',now(),'Y')";
				$this->db->exec($sql);
				
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
		public function getViewLogData($viewed,$skipProfile,$days = '',$limit='')
        {
			try 
			{
				if(!viewed)
					throw("No Profileid provided in function getViewLogData in VIEW_LOG_TRIGGER.class.php");
				if($skipProfile)
				{
					$str = " AND VIEWER NOT IN (";
					$count=1;
					foreach($skipProfile as $key => $value)
					{
						$str = $str.":VALUE".$count.",";
						$bindArr["VALUE".$count] = $value;
						$count++;
					}
					$str = substr($str, 0, -1);
					$str = $str.")";
				}
                                if($days)
                                    $yday=mktime(0,0,0,date("m"),date("d")-$days,date("Y"));     //for mailers
                                else
                                    $yday=mktime(0,0,0,date("m"),date("d")-15,date("Y"));    // To get the time for before 15 days to get visitors
				$date=date("Y-m-d",$yday)." 00:00:00";
                $limitVisitors="";
                if($limit)         
                    $limitVisitors = "LIMIT ".$limit;
				
				$sql= "SELECT SQL_CACHE VIEWER,DATE AS TIME,SEEN FROM VIEW_LOG_TRIGGER WHERE VIEWED = :PROFILEID ".$str." AND VIEWER!= :PROFILEID AND DATE>=:DATE ORDER BY TIME DESC $limitVisitors";
				$res=$this->db->prepare($sql);
				$res->bindValue(":PROFILEID",$viewed,PDO::PARAM_INT);
				if(is_array($bindArr))
					foreach($bindArr as $k=>$v)
						$res->bindValue($k,$v,PDO::PARAM_INT);
				$res->bindValue(":DATE",$date,PDO::PARAM_STR);
				$res->execute();
				while($row = $res->fetch(PDO::FETCH_ASSOC))
				{
					$output[$row["VIEWER"]] = $row;
				}
				return $output;
				
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
		
		public function insertVisitors($param)
		{
			try{
				$sql = "INSERT INTO VIEW_LOG_TRIGGER (VIEWER,VIEWED,DATE) VALUES (:VIEWER,:VIEWED,:DATE)";
				$prep = $this->db->prepare($sql);
				$prep->bindValue(":VIEWER",$param["VIEWER"],PDO::PARAM_INT);
				$prep->bindValue(":VIEWED",$param["VIEWED"],PDO::PARAM_INT);
				$prep->bindValue(":DATE",$param["DATE"],PDO::PARAM_STR);
				$prep->execute();
			}
			catch(PDOException $e)
			{
				throw new jsException($e);
		}
	}
		public function getViewedProfiles($date)
		{
			try
			{
				$start_date = $date." 00:00:00";
				$end_date = $date." 23:59:59";
				$sql = "Select DISTINCT(VIEWED) from newjs.VIEW_LOG_TRIGGER where DATE between :start_date and :end_date";
				$prep = $this->db->prepare($sql);
				$prep->bindValue(":start_date",$start_date,PDO::PARAM_STR);
				$prep->bindValue(":end_date",$end_date,PDO::PARAM_STR);
				$prep->execute();
				while($row = $prep->fetch(PDO::FETCH_ASSOC))
                {
                    $result[] = $row;
                }
            	return $result;
			}
			catch(PDOException $e)
			{
				throw new jsException($e);
			}
		}
			
}
?>
