<?php
class NEWJS_EOI_VIEWED_LOG extends TABLE
{
	public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }

	public function insert($viewer,$viewed)
	{
		if(!$viewer  || !$viewed)
                        throw new jsException("","Problem in eoi viewed log");

		try
		{
			$sql = "INSERT IGNORE INTO newjs.EOI_VIEWED_LOG(VIEWER, VIEWED, `DATE`) VALUES (:VIEWER, :VIEWED, now())";
			$res = $this->db->prepare($sql);
                        $res->bindValue(":VIEWER", $viewer, PDO::PARAM_INT);
                        $res->bindValue(":VIEWED", $viewed, PDO::PARAM_INT);
			$res->execute();
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
		return null;
	}
        
	public function getEoiViewed($viewer,$viewed)
	{
		if(!$viewer  || !$viewed)
			throw new jsException("","Problem in eoi viewed log in function getEoiViewed");

		try
		{
			$sql = "select `DATE` from newjs.EOI_VIEWED_LOG where VIEWER= :VIEWER and VIEWED= :VIEWED";
			$res = $this->db->prepare($sql);
			$res->bindValue(":VIEWER", $viewer, PDO::PARAM_INT);
			$res->bindValue(":VIEWED", $viewed, PDO::PARAM_INT);
			$res->execute();
			$row = $res->fetch(PDO::FETCH_ASSOC);
			$date = $row["DATE"];
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
		return $date;
	}
	
	
	public function getMutipleEoiViewed($viewer,$viewed)
	{
		if(!$viewer  || !$viewed || !is_array($viewer))
			throw new jsException("","Problem in eoi viewed log in function getMutipleEoiViewed");

		try
		{
			
			foreach($viewer as $k=>$v)
						$valueStr[]=":v$k";
				
			$sql = "select VIEWER,DATE from newjs.EOI_VIEWED_LOG where VIEWER IN(".(implode(",",$valueStr)).")and VIEWED= :VIEWED";
			$res = $this->db->prepare($sql);
			
			foreach($viewer as $k=>$v)
				$res->bindValue(":v$k",$v, PDO::PARAM_INT); 
			$res->bindValue(":VIEWED", $viewed, PDO::PARAM_INT);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
      {
					$output[$row["VIEWER"]]["DATE"] = $row["DATE"];
                                 
			}
			
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
		return $output;
	}

        
        public function insertMultiple($array,$time)
	{
		if(!$array || !is_array($array))
			throw new jsException("","Problem in eoi viewed log in function insertMultiple");

		try
		{
			$valueStr="";
                        $timeNow=$time ? $time : (new DateTime)->format('Y-m-j H:i:s');
			foreach($array as $k=>$v)
                        {
                            $valueStr.="(:vR$k , :vS$k, '$timeNow' ),";
                        }
                        $valueStr=substr($valueStr,0,-1);
			$sql = "INSERT IGNORE INTO newjs.EOI_VIEWED_LOG(VIEWER,VIEWED,DATE) VALUES $valueStr";
			$res = $this->db->prepare($sql);
			foreach($array as $k=>$v)
                            {
                        
				$res->bindValue(":vR$k",$v['R'], PDO::PARAM_INT); 
				$res->bindValue(":vS$k",$v['S'], PDO::PARAM_INT); 
                            }
			$res->execute();
						
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
		return $output;
	}
        
}
?>
