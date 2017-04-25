<?php
/**
 * @brief This class is store class of searches performed by uses (newjs.SEARCHQUERY)
 * @author Lavesh Rawat
 * @created 2012-07-15
 */

class SEARCHQUERY extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

        /*
        * This Function is to log search records 
	* @param updateArr key-value pair for records to be updated.
        * @return auto-increment id of searching
        */
	public function addRecords($updateArr)
	{
		try
		{
			$key = 'DATE';
                        $updateArr[$key] = "'".date("Y-m-d H:i:s")."'";
                        foreach($updateArr as $k=>$v)
                        {
				if(trim($v,"'")!='')
				{
					$v = trim($v,"'");
	                                $columnNames.= $k.",";
        	                        $values.=":".$k.",";
					if(in_array($k,SearchConfig::$integerSearchParameters))	
					{
						$bindMeInt[$k] = $v;
					}
					else
						$bindMeStr[$k] = $v;
				}
                        }
                        $columnNames = rtrim($columnNames,",");
                        $values = rtrim($values,",");

			$sql = "INSERT INTO newjs.SEARCHQUERY ($columnNames) VALUES ($values)";
			$res = $this->db->prepare($sql);
			if(is_array($bindMeInt))
				foreach($bindMeInt as $k=>$v)
					$res->bindValue(":$k", $v, PDO::PARAM_INT);
			if(is_array($bindMeStr))
				foreach($bindMeStr as $k=>$v)
					$res->bindValue(":$k", $v, PDO::PARAM_STR);

			$res->execute();	
			return $this->db->lastInsertId();
		}
		catch(PDOException $e)
		{
			throw new jsException("","No Insertion In SEARCHQUERY table : store:SEARCHQUERY.class.php");
		}
	}
        /**
        This function is used to get save search information (SEARCHQUERY table).
        * @param  paramArr array contains where condition on basis of which entry will be fetched.
        * @return detailArr array search paramters info. Return null if searchid not found.
        **/
        public function get($paramArr=array(),$fields="*",$ifNonCritical="")
        {
                foreach($paramArr as $key=>$val)
                        ${$key} = $val;
							
                if(!$ID)
                {
									$profileId=  "----->>>".print_r($paramArr,true);
									$profileId.=  "----->>>".print_r($_POST,true);
									$profileId.=  "----->>>".print_r($_GET,true);
									$http_msg="::::---->>>".print_r($_SERVER,true);
									mail("reshu.rajput@gmail.com","lr2","$profileId: $http_msg");
                        throw new jsException("","ID IS BLANK IN get() of SEARCHQUERY.class.php");
                }
                try
                {
                        $detailArr='';
                        $sql = "SELECT SQL_CACHE $fields FROM newjs.SEARCHQUERY WHERE ";
                        $sql.="ID = :ID";
                        $res = $this->db->prepare($sql);

                        $res->bindValue(":ID", $ID, PDO::PARAM_INT);
                        $res->execute();
                        while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
                                $detailArr[] = $row;
                        }
                        return $detailArr;
                }
                catch(PDOException $e)
                {
			if($ifNonCritical)
				jsException::nonCriticalError("lib/model/store/newjs/SEARCHQUERY.class.php (3)-->.$sql".$e);
			else
	                        throw new jsException($e);
                }
                return NULL;
        }
}
?>
