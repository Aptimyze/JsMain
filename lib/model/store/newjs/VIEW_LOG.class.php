<?php
class VIEW_LOG extends TABLE
{
        public function __construct($dbname="")
        {
						
			$dbname=$dbname?$dbname:"viewLogRep";
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
                        $sql = "SELECT VIEWED,DATE FROM VIEW_LOG WHERE VIEWER=$viewer";
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
				$sql .= " LIMIT :limit ";
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
                        	$res->bindValue(":limit", $limit+1000, PDO::PARAM_INT);// for recent 5000 if exceeds before housekeeping cron
                        }
                        $res->execute();
                        $count=0;
                        while($result = $res->fetch(PDO::FETCH_ASSOC))
						{
							if($key=='spaceSeperator')
			                    	$resultArr.= $result['VIEWED']." ";
							else if($key == 1)
								$resultArr[$result['VIEWED']] = 1;
							else
				                $resultArr[] = $result['VIEWED'];
				            $count++;
				            
						}
						if($limit && $count>=$limit){
							$memObject=JsMemcache::getInstance();
							$memObject->storeDataInCacheByPipeline("ViewLogGT5k",$viewer,86400);
										
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
