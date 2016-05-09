<?php
class newjs_SERIES extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
			$this->tableName = "newjs.SERIES";
			$this->PROFILEID_BIND_TYPE = "INT";
			$this->ID_BIND_TYPE = "STR";
			$this->HASH_BIND_TYPE = "STR";
			$this->TIME_BIND_TYPE = "STR";
			$this->USED_BIND_TYPE = "STR";
        }
	public function disableProfileidLinks($profileid)
	{
		if(!$profileid)
			return;
		$used = "Y";
		$sqlUpdate = "UPDATE ".$this->tableName." SET  `USED` = :USED WHERE PROFILEID=:PROFILEID";
                $resUpdate = $this->db->prepare($sqlUpdate);
                $resUpdate->bindValue(":PROFILEID",$profileid,constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
                $resUpdate->bindValue(":USED",$used,constant('PDO::PARAM_'.$this->{'USED_BIND_TYPE'}));
                return $resUpdate->execute();
	}
        public function updateUsed($id,$used='Y')
        {
                if(!$id)
                        return;
                $sqlUpdate = "UPDATE ".$this->tableName." SET  `USED` = :USED WHERE ID=:ID";
                $resUpdate = $this->db->prepare($sqlUpdate);
                $resUpdate->bindValue(":ID",$id,constant('PDO::PARAM_'.$this->{'ID_BIND_TYPE'}));
                $resUpdate->bindValue(":USED",$used,constant('PDO::PARAM_'.$this->{'USED_BIND_TYPE'}));
                return $resUpdate->execute();
        }

	public function insert($data)
	{
		if(!is_array($data))
			return false;
                $sqlInsert = "INSERT IGNORE INTO ".$this->tableName." (`ID` ,`HASH_ID`,`PROFILEID`,`TIME`,`USED`) VALUES (:ID,:HASH_ID,:PROFILEID,now(),:USED)";
                $resInsert = $this->db->prepare($sqlInsert);
                $resInsert->bindValue(":ID",$data['ID'],constant('PDO::PARAM_'.$this->{'ID_BIND_TYPE'}));
                $resInsert->bindValue(":HASH_ID",$data['HASH_ID'],constant('PDO::PARAM_'.$this->{'HASH_ID_BIND_TYPE'}));
                $resInsert->bindValue(":PROFILEID",$data['PROFILEID'],constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
                $resInsert->bindValue(":USED","N",constant('PDO::PARAM_'.$this->{'USED_BIND_TYPE'}));
                return $resInsert->execute();
	}

	public function getArray($valueArray="",$excludeArray="",$greaterThanArray="",$fields="PROFILEID",$lessThanArray="",$orderby="",$limit="",$greaterThanEqualArrayWithoutQuote="", $lessThanEqualArrayWithoutQuote="", $like="",$nolike="",$groupBy="",$having="")
	{
		if(!$valueArray && !$excludeArray  && !$greaterThanArray && !$lessThanArray && !$lessThanEqualArrayWithoutQuote)
			throw new jsException("","no where conditions passed");
		try
		{
			$arrays = array('valueArray'	=> "IN",
					'excludeArray'	=> "NOT IN",
					'greaterThanArray' => ">",
					'lessThanArray' => "<",
					'greaterThanEqualArrayWithoutQuote' => ">=",
					'lessThanEqualArrayWithoutQuote' => "<=",
					'like' => "LIKE",
					'nolike' => "NOT LIKE");
			if($fields!='returnOnlySql')
			{
                	        if(!stristr($fields,"*"))
                        	{
					if($fields)
					{
						foreach($defaultFieldsRequired as $k=>$fieldName)
						{
							if(!stristr($fields,$fieldName))
								$fields.=",".$fieldName;
						}
					}
					else
					{
						$fields = implode (", ",$defaultFieldsRequired);
					}
	                        }
			}
			$sqlSelectDetail = "SELECT $fields FROM ".$this->tableName." WHERE ";
			$count = 1;
			foreach($arrays as $executionArray=>$relation)
			{
				if(is_array($$executionArray))
				{
					foreach($$executionArray as $param=>$value)
					{
						$sqlSelectDetail.= $this->getBindString($value,$param,$count,$relation);
						$count++;
					}
				}
			}
			if($groupBy)
			{
				$sqlSelectDetail.=" group by $groupBy ";
				if($having)
				{
					$sqlSelectDetail.=" having ".$having;
				}
			}
			if($orderby)
				$sqlSelectDetail.=" order by $orderby ";
			if($limit)
				$sqlSelectDetail.=" limit $limit ";
			if($fields=='returnOnlySql')
				return $sqlSelectDetail;
			$resSelectDetail = $this->db->prepare($sqlSelectDetail);
			$count = 1;
			foreach($arrays as $executionArray=>$relation)
			{
				if(is_array($$executionArray))
				{
					foreach($$executionArray as $param=>$value)
					{
						$paramBindValue = $this->{$param."_BIND_TYPE"};
						$values = explode(",",$value);
						foreach($values as $k1=>$val)
							$resSelectDetail->bindValue(":".$param."_".$count."_".$k1,$val,constant('PDO::PARAM_'.$paramBindValue));
						$count++;
					}
				}
			}
			$resSelectDetail->execute();
			while($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC))
			{
				$detailArr[] = $rowSelectDetail;
			}
			return $detailArr;
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
		return NULL;
	}

	public function getBindString($value,$param,$count,$relation)
	{
		if($count>1)
			$return = " AND ";
		
		$array = explode(",",$value);
		foreach($array as $k1=>$val)
			$bindArr[] = ":".$param."_".$count."_".$k1;
		$str=implode(",",$bindArr);
		if(strstr($relation,"LIKE"))
			$return.=" ".$param." ".$relation." '%$str%' ";
		else
			$return.=" ".$param." ".$relation." (".$str.") ";
		return $return;
	}
}
?>
