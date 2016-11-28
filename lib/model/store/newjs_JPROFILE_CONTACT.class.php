<?php
class NEWJS_JPROFILE_CONTACT extends TABLE{
       

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }


        /**
         * @fn getArray
         * @brief fetches results for multiple profiles to query from JPROFILE_CONTACT
         * @param $valueArray - array with field name as key and comma separated field values as the value corresp to the key - rows satisfying these values are included in the result
         * @param $excludeArray - array with field name as key and comma separated field values as the value corresp to the key - rows satisfying these values are excluded from the result
         * @param $fields Columns to query
         * @return results Array according to criteria having incremented index
         * @exception jsException for blank criteria
         * @exception PDOException for database level error handling
         */

	public function getArray($valueArray="",$excludeArray="",$greaterThanArray="",$fields="PROFILEID",$indexProfileId = 0)
	{
		if(!$valueArray && !$excludeArray  && !$greaterThanArray)
			throw new jsException("","no where conditions passed");
		try
		{
			$fields = $fields?$fields:$this->getFields();//Get columns to query
			$sqlSelectDetail = "SELECT $fields FROM newjs.JPROFILE_CONTACT WHERE ";
			$count = 1;
			if(is_array($valueArray))
			{
				foreach($valueArray as $param=>$value)
				{
					if($count == 1)
						$sqlSelectDetail.=" $param IN ($value) ";
					else
						$sqlSelectDetail.=" AND $param IN ($value) ";
					$count++;
				}
			}
			if(is_array($excludeArray))
			{
				foreach($excludeArray as $excludeParam => $excludeValue)
				{
					if($count == 1)
						$sqlSelectDetail.=" $excludeParam NOT IN ($excludeValue) ";
					else
						$sqlSelectDetail.=" AND $excludeParam NOT IN ($excludeValue) ";
					$count++;
				}
			}
			if(is_array($greaterThanArray))
			{
				foreach($greaterThanArray as $gParam => $gValue)
				{
					if($count == 1)
						$sqlSelectDetail.=" $gParam > '$gValue' ";
					else
						$sqlSelectDetail.=" AND $gParam > '$gValue' ";
					$count++;
				}
			}

			$resSelectDetail = $this->db->prepare($sqlSelectDetail);
			/*
			foreach ($valueArray as $k => $val)
			{
				$resSelectDetail->bindValue(($k+1), $val);
			}
			*/
			$resSelectDetail->execute();
      $this->logFunctionCalling(__FUNCTION__);
			while($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC))
			{
                                if($indexProfileId == 1){
                                        $detailArr[$rowSelectDetail['PROFILEID']] = $rowSelectDetail;
                                }else{
                                        $detailArr[] = $rowSelectDetail;
                                }
			}
			return $detailArr;
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
		return NULL;
	}

        public function getProfileContacts($pid)
        {
			try 
			{
        $this->logFunctionCalling(__FUNCTION__);
                                //Memcache to be moved to library - JSM-938
                                $memObject=JsMemcache::getInstance();
                                if($memObject->get("JPROFILE_CONTACT_".$pid)){
                                        return $memObject->get("JPROFILE_CONTACT_".$pid);
                                }
				else if($pid)
				{ 
                                        $sql="SELECT * FROM newjs.JPROFILE_CONTACT WHERE PROFILEID=:PROFILEID";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
					$prep->execute();
					if($result = $prep->fetch(PDO::FETCH_ASSOC))
					{
                                                $memObject->set("JPROFILE_CONTACT_".$pid,$result);
						return $result;
					}
                                        else
                                                $memObject->set("JPROFILE_CONTACT_".$pid,"false");
					return false;
				}	
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}

		public function updateAltMobile($profileid, $altMobile){
                try{
                        $memObject=JsMemcache::getInstance();
                        $memObject->delete("JPROFILE_CONTACT_".$pid);
                        
                        $sql = "SELECT PROFILEID FROM newjs.JPROFILE_CONTACT WHERE PROFILEID=:PROFILEID";
                        $prep=$this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                        $prep->execute();
                        $result = $prep->fetch(PDO::FETCH_ASSOC);
                        if($result){
                                $sql2 = "UPDATE newjs.JPROFILE_CONTACT SET ALT_MOBILE=:ALT_MOBILE WHERE PROFILEID=:PROFILEID";
                                $prep=$this->db->prepare($sql2);
                                $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                                $prep->bindValue(":ALT_MOBILE",$altMobile,PDO::PARAM_INT);
                                $prep->execute();
                        } else {
                                $sql2 = "INSERT INTO newjs.JPROFILE_CONTACT (PROFILEID, ALT_MOBILE) VALUES (:PROFILEID, :ALT_MOBILE)";
                                $prep=$this->db->prepare($sql2);
                                $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                                $prep->bindValue(":ALT_MOBILE",$altMobile,PDO::PARAM_INT);
                                $prep->execute();
                        }
                        $this->logFunctionCalling(__FUNCTION__);
                        return true;
                }catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }

	public function update($pid,$paramArr=array())
	{
   
		try {
                        $memObject=JsMemcache::getInstance();
                        $memObject->delete("JPROFILE_CONTACT_".$pid);
                        
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
				
				$sqlUpdateContact="Update JPROFILE_CONTACT SET $updateStr where PROFILEID=:PROFILEID";
				$resUpdateContact = $this->db->prepare($sqlUpdateContact);
				foreach($paramArr as $key=>$val)
					$resUpdateContact->bindValue(":".$key, $val);
				$resUpdateContact->bindValue(":PROFILEID", $pid);
				$resUpdateContact->execute();
				if(!$resUpdateContact->rowCount())
				{
					$sqlSelectContact="Select PROFILEID from JPROFILE_CONTACT where PROFILEID=:PROFILEID";
					$resSelectContact = $this->db->prepare($sqlSelectContact);
					$resSelectContact->bindValue(":PROFILEID", $pid);
					$resSelectContact->execute();
					$result=$resSelectContact->fetch(PDO::FETCH_ASSOC);
					if(!$result[PROFILEID])
					{
						$sqlEditContact = "REPLACE INTO JPROFILE_CONTACT ($keys) VALUES ($values)";
						$resEditContact = $this->db->prepare($sqlEditContact);
						foreach($paramArr as $key=>$val)
							$resEditContact->bindValue(":".$key, $val);
						$resEditContact->bindValue(":PROFILEID", $pid);
						$resEditContact->execute();
					}
				}
        $this->logFunctionCalling(__FUNCTION__);
				return true;
			}catch(PDOException $e)
				{
					throw new jsException($e);
				}
	}

        public function checkPhone($numberArray='',$isd=''){
                try
                {
                        $res=null;
			$str='';
                        if($numberArray)
                        {
                                foreach($numberArray as $k=>$num)
                                {
                                        if($k!=0)
                                                $str.=", ";
                                        $str.=":mob".$k.", :mob0".$k.", :mobIsd".$k.", :mobIsdA".$k.", :mobIsd0".$k;
                                }
                        }
                        if($str)
                        {
                                $sql="SELECT PROFILEID,ALT_MOBILE FROM newjs.JPROFILE_CONTACT WHERE ALT_MOBILE IN (".$str.")";
                                $prep=$this->db->prepare($sql);
                                if($numberArray)
                                {
                                        foreach($numberArray as $k=>$num)
                                        {

                                                $prep->bindValue(":mob".$k,$num,PDO::PARAM_STR);
                                                $prep->bindValue(":mob0".$k,'0'.$num,PDO::PARAM_STR);
                                                $prep->bindValue(":mobIsd".$k,$isd.$num,PDO::PARAM_STR);
                                                $prep->bindValue(":mobIsdA".$k,'+'.$isd.$num,PDO::PARAM_STR);
                                                $prep->bindValue(":mobIsd0".$k,'0'.$isd.$num,PDO::PARAM_STR);
                                        }
                                }
                                $prep->execute();
				$i=0;
                                while($result = $prep->fetch(PDO::FETCH_ASSOC))
                                {
                                        $res[$i]['PROFILEID']=$result['PROFILEID'];
                                        $res[$i]['NUMBER']=$result['ALT_MOBILE'];
                                        $res[$i]['TYPE']="ALTERNATE";
					$i++;
                                }
                                $this->logFunctionCalling(__FUNCTION__);
                        }
                        else
                                throw new jsException("No phone number as Input paramter");

                        return $res;

                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
        }
        
    private function logFunctionCalling($funName)
    {
      $key = __CLASS__.'_'.date('Y-m-d');
      JsMemcache::getInstance()->hIncrBy($key, $funName);
      
      JsMemcache::getInstance()->hIncrBy($key, $funName.'::'.date('H'));
    }
}
?>
