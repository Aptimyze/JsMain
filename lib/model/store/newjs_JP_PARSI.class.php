<?php
class NEWJS_JP_PARSI extends TABLE{
       

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        public function getJpParsi($pid)
        {
			try 
			{
				if($pid)
				{ 
					$sql="SELECT * FROM JP_PARSI WHERE PROFILEID=:PROFILEID";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
					$prep->execute();
          $this->logFunctionCalling(__FUNCTION__);
					if($result = $prep->fetch(PDO::FETCH_ASSOC))
					{
						return $result;
					}
					return array();
				}	
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
		
		public function update($pid,$paramArr=array())
		{
	   
			try {
				$keys="PROFILEID,";
				$values=":PROFILEID ,";
					foreach($paramArr as $key=>$value){
						$keys.=$key.",";
						$values.=":".$key.",";
						$updateStr.=$key."=:".$key.",";
					}
					$updateStr=trim($updateStr,",");
					$keys=substr($keys,0,-1);
					$values=substr($values,0,-1);
					
					$sqlUpdateReligion="Update JP_PARSI SET $updateStr where PROFILEID=:PROFILEID";
					$resUpdateReligion= $this->db->prepare($sqlUpdateReligion);
					foreach($paramArr as $key=>$val)
						$resUpdateReligion->bindValue(":".$key, $val);
					$resUpdateReligion->bindValue(":PROFILEID", $pid);
					$resUpdateReligion->execute();
					
					if(!$resUpdateReligion->rowCount())
					{
						$sqlEditReligion = "REPLACE INTO JP_PARSI ($keys) VALUES ($values)";
						$resEditReligion = $this->db->prepare($sqlEditReligion);
						foreach($paramArr as $key=>$val)
							$resEditReligion->bindValue(":".$key, $val);
						$resEditReligion->bindValue(":PROFILEID", $pid);
						$resEditReligion->execute();
					}
          $this->logFunctionCalling(__FUNCTION__);
					return true;
				}catch(PDOException $e)
					{
						throw new jsException($e);
					}
		}
    private function logFunctionCalling($funName)
    {
    	return;
      // $key = __CLASS__.'_'.date('Y-m-d');
      // JsMemcache::getInstance()->hIncrBy($key, $funName);
      
      // JsMemcache::getInstance()->hIncrBy($key, $funName.'::'.date('H'));
    }
}
?>
