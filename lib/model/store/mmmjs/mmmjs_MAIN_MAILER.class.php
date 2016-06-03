<?php
/** 
* store class related with MAIN_MAILER_NEW table
* It contains information for each mailer.
*/
class mmmjs_MAIN_MAILER extends TABLE
{
        public function __construct($dbname="matchalerts_slave_localhost")
        {
                parent::__construct($dbname);
        }

	/**
	* insert entry in the table .....
	* @param $mailer - associative array with key (column name) & value
	* @return int auto id of the table
	*/
	public function insertEntry($mailer)
        {
		$date= new DateTime();
        	try
                {
			$sql="INSERT INTO mmmjs.MAIN_MAILER_NEW(MAILER_NAME,CLIENT_NAME,CTIME,MAIL_TYPE,RESPONSE_TYPE,COMPANY_NAME,PERIOD_OF_STAY,MAILER_FOR,STATUS, UNIQUEID) values(:mailer_name,:client_name, :ctime, :mail_type, :response_type, :company, :pos, :mailer_for, :status, :uniqueid)";
	                $res = $this->db->prepare($sql);
	    		$res->bindValue(":mailer_name", $mailer["mailer_name"], PDO::PARAM_STR);
	    		$res->bindValue(":client_name", $mailer["client_name"], PDO::PARAM_STR); 
			$res->bindValue(":ctime", $date->format('y-m-d h:i:s'), PDO::PARAM_STR); 
			$res->bindValue(":mail_type", $mailer["mail_type"], PDO::PARAM_STR); 
			$res->bindValue(":response_type", $mailer["response_type"], PDO::PARAM_STR); 
			$res->bindValue(":company", $mailer["company"], PDO::PARAM_STR); 
			$res->bindValue(":pos", $mailer["pos"], PDO::PARAM_STR); 
			$res->bindValue(":mailer_for", $mailer["mailer_for"], PDO::PARAM_STR);	
			$res->bindValue(":status", 'NEW', PDO::PARAM_STR);
			$res->bindValue(":uniqueid", $mailer["uniqueid"], PDO::PARAM_STR);				
            		$res->execute();
			return $this->db->lastInsertId();
          	}
          	catch(PDOException $e)
          	{	throw new jsException($e);
          	}
        }

	/**
	 * get fields based on the where conditions .....
	 * @param $whereParamArray - associative array with key (column name) & value
	 * @param $fields - string (name of the columns to be retrieved)
	 * @param $mailerPeriod(default=Y) to show mailers created in last 30 days and those which are not fired yet.
	 * @throws - PDO Exception 
	 */
        public function get($whereParamArray, $fields='*', $orderBy = "ORDER BY MAILER_ID DESC",$mailerPeriod='')
        {
		if($orderBy == '')
			$orderBy = "ORDER BY MAILER_ID DESC";
                if(!$fields)
                        $fields='*';

                $sql = "SELECT $fields from mmmjs.MAIN_MAILER_NEW ";
		
		if(!empty($whereParamArray))
			$sql.=" WHERE ";
		$count = 0;
		foreach($whereParamArray as $key => $value)
		{
			if($count == 0)
			{
				if(is_array($value))
				{
					$sql.="$key IN (";
					foreach($value as $k=>$val)
					{
						if($k==0)
							$sql.=":v$k";
						else
							$sql.=",:v$k";
					}
					$sql.=") ";
				}
				else
					$sql.=" $key IN (:k) ";
				$count++;
			}
			else
			{
				if(is_array($value))
				{
					$row=0;
					$sql.=" AND $key IN (";
					foreach($value as $k=>$val)
					{
						if($k==0)
							$sql.=":v$k";
						else
							$sql.=",:v$k";
					}
					$sql.=") ";
				}
				else
					$sql.="AND $key IN (:k) ";				
			}
		}
		if($mailerPeriod == 'Y')
		{
			$curdate = date('Y-m-d H:i:s');
		//	$ist = strftime("%Y-%m-%d %H:%M",strtotime("$curdate + 10 hours 30 minutes"));
			$ist = strftime("%Y-%m-%d %H:%M",strtotime("$curdate"));
			$date30daysbefore = date('Y-m-d H:i:s',time() - 86400*MmmConfig::$mailerPeriod);
			if(!empty($whereParamArray))
				$sql .= " AND ((CTIME BETWEEN '".$date30daysbefore."' AND '".$ist."') OR STATUS != 'RC') ";
			else
				$sql .= " WHERE ((CTIME BETWEEN '".$date30daysbefore."' AND '".$ist."') OR STATUS != 'RC') ";
		}

		if($mailerPeriod == 'notRequired')
        {
            if(!empty($whereParamArray))
                $sql .= " AND STATUS = 'RC' ";
            else
                $sql .= " WHERE STATUS = 'RC' ";

        }

		if($orderBy)
			$sql.=$orderBy;
                $res = $this->db->prepare($sql);

		$count = 0;
		foreach($whereParamArray as $key => $value)
		{
			if(is_array($value))
			{
				foreach($value as $k=>$val)
				{
					$str=":v".$k;
					$res->bindValue($str,$val,PDO::PARAM_STR);
				}
			}
			else
			$res->bindValue(":k", $value, PDO::PARAM_STR);	
		}            
                $res->execute();
		$arr = array();
                while($row = $res->fetch(PDO::FETCH_ASSOC))
                        $arr[] = $row;
                return $arr;
        }


	/**
	* update the $setfields columns of the MAIN MAILER table based upon $wherefields .....
	* param $wherefields - associative array  with key as field name, value as comma separated string value of the field
	* param $setfields - associative array with key as field name , value as value of the field
	* returns nothing
	**/
	public function update($wherefields, $setfields)
	{
		if($wherefields ["MAILER_ID"])
			$wherefields ["MAILER_ID"] = str_replace("'",'',$wherefields ["MAILER_ID"]);
		try
		{
			if($wherefields && $setfields)
			{
				$sql = "UPDATE mmmjs.MAIN_MAILER_NEW set ";
				$count = 0;
				foreach( $setfields as $key => $value)
				{
					if($count == 0)
					{
						$sql.=" $key = :$key ";
						$count++;
					}
					else
					{
						$sql.=" AND $key = :$key ";
					}
				}
				$sql.= "WHERE ";
				$count = 0;
				foreach( $wherefields as $key => $value)
				{
					$valueStr = str_replace("\'","",$value);
					$valueArray = explode(",",$value);
					foreach($valueArray as $k =>$v)
						$valueArr[]=":v".$k; 
					$valuesSql = implode(",",$valueArr);
					if($count == 0)
					{
						$sql.=" $key IN ($valuesSql) ";
						$count++;
					}
					else
					{
						$sql.=" AND $key IN ($valuesSql) ";
					}
				}
				$res = $this->db->prepare($sql);
				foreach($setfields as $key => $value)
				{
					$res->bindValue(":$key", $value);
				}
				foreach($wherefields as $key => $value)
                                {
					$valueStr = str_replace("\'","",$value);
                                        $valueArray = explode(",",$value);
                                        foreach($valueArray as $k =>$v)
                                        	$res->bindValue(":v".$k, $v);
                                }

				$res->execute();
			}
		}
		catch(PDOException $e)
		{	throw new jsException($e);
		}
	}

	public function updateLastRtime($mailerId){
        try{
                $today = date('Y-m-d H:i:s');
                $sql = "update mmmjs.MAIN_MAILER_NEW SET LAST_RTIME='".$today."' WHERE MAILER_ID='".$mailerId."'";
                $res = $this->db->prepare($sql);
                $res->execute();
           }
        catch(PDOException $e)
           {
                throw new jsException($e);
           }
        }

	/**
	 * to return the mailer names corresponding to the mailer ids specified in the array $ids.
	 * @param $ids an array containing all the ids for which the names are to be returned.
	 * return the names of the mailer corresponding to mailer ids.
	 */
	public function getNames($ids,$WithKeyId='')
	{
		try{
			$arr=array();
			if($ids)
			{
				foreach($ids as $key =>$v)
					$idsArray[]=":v".$key;
				$str=implode(",", $idsArray);
				$sql = "SELECT MAILER_NAME,MAILER_ID from mmmjs.MAIN_MAILER_NEW WHERE MAILER_ID IN(".($str).") ORDER BY MAILER_ID DESC";
				$res = $this->db->prepare($sql);
				foreach($ids as $key=>$val)
	                        {
        	                        $res->bindValue(":v".$key, $val);
                	        }
				$res->execute();
				while($row = $res->fetch(PDO::FETCH_ASSOC))
				{
					if($WithKeyId)
						$arr[$row['MAILER_ID']]=$row['MAILER_NAME'];
					else
						$arr[]=$row['MAILER_NAME'];
				}
			}
			return $arr;
		}
		catch(PDOException $e)
		{	throw new jsException($e);
		}
	}

	public function insertSearchQuery($sql_final,$mailer_id){
                $sql_final = addslashes($sql_final);
                $sql="update mmmjs.MAIN_MAILER_NEW set QUERY = '".$sql_final."' WHERE MAILER_ID='".$mailer_id."'";
                $result=$this->db->prepare($sql);
                $result->execute();
        }
	 public function getSubQuery($mailer_id){
                $sql = "Select QUERY from mmmjs.MAIN_MAILER_NEW where MAILER_ID='".$mailer_id."'";
                $result=$this->db->prepare($sql);
                $result->execute();
                $rowset = $result->fetch(PDO::FETCH_ASSOC);
                $sub_sql = stripslashes($rowset['QUERY']);
                $temp = explode('FROM',$sub_sql);
                return $temp[1];
        }


}
?>
