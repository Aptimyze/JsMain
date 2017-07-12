<?php
class VIEW_LOG extends TABLE
{
        public function __construct($dbname="")
        {
					if(!JsConstants::$communicationRep)
							$dbname=$dbname?$dbname:"viewLogRep";
					else
							$dbname=$dbname?$dbname:"shard2_master";
						parent::__construct($dbname);
        }

	/**
	  * This function gets a list of profiles that have been viewed by a user.
	  * Pass $keyVal as 1 if the profileids are to sent in the key of the returned array.
	**/

        public function get($viewer,$viewedStr='',$key='',$limit="")
        {
                try
                {
                        $sql = "SELECT VIEWED FROM VIEW_LOG WHERE VIEWER=$viewer";
			if($viewedStr)
			{
				$str = str_replace("\'","",$viewedStr);
				$viewedArray = explode(",",$str);
				foreach($viewedArray as $key =>$v)
					$viewedSql[]=":v".$key;
				$viewedSqlStr = implode(",",$viewedSql);
				$sql.=" AND VIEWED IN ($viewedSqlStr)";
				unset($viewedSql);
				unset($viewedArray);
			}
			if($limit)
			{
				$sql .= " ORDER BY DATE DESC LIMIT :limit ";
			}
                        $res=$this->db->prepare($sql);
			if($viewedStr)
                        {
                        	$str = str_replace("\'","",$viewedStr);
                        	$viewedArray = explode(",",$str);
                        	foreach($viewedArray as $key =>$v)
                        		$res->bindValue(":v$key", $v, PDO::PARAM_INT);        
                        }
                        if($limit)
                        {
                        	$res->bindValue(":limit", $limit, PDO::PARAM_INT);
                        }
                        $res->execute();
                        while($result = $res->fetch(PDO::FETCH_ASSOC))
			{
				if($key=='spaceSeperator')
                                	$resultArr.= $result['VIEWED']." ";
				else if($key == 1)
					$resultArr[$result['VIEWED']] = 1;
				else
	                                $resultArr[] = $result['VIEWED'];
			}			
                        return $resultArr;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
}
?>
