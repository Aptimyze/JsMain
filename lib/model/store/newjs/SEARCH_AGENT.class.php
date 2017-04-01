<?php
/**
 * @brief This class is store class of user saved searches (newjs.SEARCH_AGENT table)
 * @author Lavesh Rawat
 * @created 2012-08-10
 */

class SEARCH_AGENT extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

	/**
	* This function is used to count the number of save search of a profile.
	* @param profileId
	* @return int number of save searches of a user.
	*/
	public function countRecord($profileId)
	{
                $sql = "SELECT COUNT(*) AS CNT FROM newjs.SEARCH_AGENT WHERE";
                $sql.=" PROFILEID = :PROFILEID";
                $res = $this->db->prepare($sql);
		$res->bindParam(":PROFILEID", $profileId, PDO::PARAM_INT);
                $res->execute();
                $row = $res->fetch(PDO::FETCH_ASSOC);
                return $row["CNT"];
	}

	/**
	* This function is used to delete a save search of a profile.
	* @param profileId
	* @param saveId int unique auto-increment id of the table.
	* @return int (1 if records is deleted successfully)
	*/
	public function deleteRecord($saveId,$profileId)
	{
                $saveIdArr = explode(",",$saveId);
                
                foreach($saveIdArr as $k=>$v){
                        if($v!=0)
                        {
                                $bindArr[":SID".$k]=$v;
                                $binded[] = ":SID".$k;
                        }
		}
                $bindedString = implode(",",$binded);
                        
                $sql = "DELETE FROM newjs.SEARCH_AGENT WHERE";
                $sql.=" PROFILEID = :PROFILEID AND ID IN (".$bindedString.")";
                $res = $this->db->prepare($sql);
		$res->bindParam(":PROFILEID", $profileId, PDO::PARAM_INT);
                
                foreach($bindArr as $kh=>$vh){
                        $res->bindValue($kh, $vh, PDO::PARAM_INT);
                }
                $res->execute();
                return $res->rowCount();
	}

        /*
        * This Function is to add user save-search.
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

			$sql = "REPLACE INTO newjs.SEARCH_AGENT ($columnNames) VALUES ($values)";
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
			throw new jsException("","No Insertion In SEARCH_AGENT table : store:SEARCH_AGENT.class.php");
		}
	}

        /**
        This function is used to get save search information (SEARCH_AGENT table).
        * @param  paramArr array contains where condition on basis of which entry will be fetched.
        * @return detailArr array save search paramters info. Return null in case of no matching rows found.
        **/
	public function get($paramArr=array(),$fields="*")
	{
		foreach($paramArr as $key=>$val)
                	${$key} = $val;

                if(!$PROFILEID && !$ID)
                        throw new jsException("","PROFILEID & ID IS BLANK IN get() of SEARCH_AGENT.class.php");
                try
		{
			$detailArr='';
                        $sql = "SELECT $fields FROM newjs.SEARCH_AGENT WHERE ";
			if($PROFILEID)
				$sql.="PROFILEID = :PROFILEID";
                        if($ID)
                        {
                                if($PROFILEID)
                                        $sql.=" AND ";
                                $sql.="ID = :ID";
                        }
			$sql.=" ORDER BY DATE DESC";
                        $res = $this->db->prepare($sql);

			if($PROFILEID)
                        	$res->bindValue(":PROFILEID", $PROFILEID, PDO::PARAM_INT);
			if($ID)
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
                        //throw new jsException($e);
			jsException::nonCriticalError("newjs/SEARCH_AGENT.class.php(1)-->.$sql".$e);
                        return '';
                }
                return NULL;
	}

        /* This function inserts the receivers of mail along with there saved search names and the corresponding id's 
        */
        public function insertSavedSearchMailerData($receiverData)
        {
                try
                {       if(is_array($receiverData))
                        {
                                $sql="INSERT IGNORE INTO search.send_saved_search_mail (RECEIVER,SEARCH_ID,SEARCH_NAME) VALUES ";                        
                                $res = $this->db->prepare($sql);
                                foreach($receiverData as $key=>$value)
                                {
                                        $sql .="(:RECEIVER".$key.",:SEARCH_ID".$key.",:SEARCH_NAME".$key."),";
                                }
                                $sql = rtrim($sql,",");
                                $res = $this->db->prepare($sql);
                                foreach($receiverData as $key => $value)
                                {
                                        $res->bindValue(":RECEIVER".$key, $value["PROFILEID"], PDO::PARAM_INT);
                                        $res->bindValue(":SEARCH_ID".$key, $value["ID"], PDO::PARAM_INT);
                                        $res->bindValue(":SEARCH_NAME".$key, $value["SEARCH_NAME"], PDO::PARAM_STR);
                                }
                                $res->execute();
                        }
                        
                }
                catch(PDOException $e)
                {
                        //throw new jsException($e);
                        jsException::nonCriticalError("newjs/SEARCH_AGENT.class.php(1)-->.$sql".$e);
                        return '';
                }
        }

        public function selectSavedSearchMailerData($count,$totalInstances,$lastLoginDate)
        {
                try
                {
                        $sql = "SELECT S.PROFILEID, ID, SEARCH_NAME FROM `SEARCH_AGENT` S LEFT JOIN JPROFILE J USING ( PROFILEID ) WHERE J.ACTIVATED IN ('Y', 'U') AND S.PROFILEID%:TOTALINSTANCE=:REMAINDER AND J.activatedKey=1 AND DATE(J.LAST_LOGIN_DT) > :LASTLOGINDATE ORDER BY PROFILEID DESC";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":TOTALINSTANCE", $totalInstances, PDO::PARAM_INT);
                        $res->bindValue(":REMAINDER", $count, PDO::PARAM_INT);
                        $res->bindValue(":LASTLOGINDATE", $lastLoginDate, PDO::PARAM_STR);
                        $res->execute();
                        while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
                                $detailArr[] = $row;
                        }
                        return $detailArr;
                }
                catch(PDOException $e)
                {
                        //throw new jsException($e);
                        jsException::nonCriticalError("newjs/SEARCH_AGENT.class.php(1)-->.$sql".$e);
                        return '';
                }
        }
}
?>
