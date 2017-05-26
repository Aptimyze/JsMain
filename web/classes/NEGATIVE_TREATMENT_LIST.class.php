<?php
include_once(dirname(__FILE__)."/Mysql.class.php");
/**
This file handles operations related to spam profiles.
*/
class NEGATIVE_TREATMENT_LIST
{
	private $notViewable='N';
	private $noInboxEoi='N';
	private $noContactsDetailsVisible='N';
	private $noOutboundCall='N';
	private $noInboundCall='N';
	private $noChatInitiation ='N';
	private $whereConditionArr;
	private $mysql;

	function __construct ($db='')
	{
                $this->mysql= new Mysql;
		if($db)
			$this->db=$db;
		else
	                $this->db=$this->mysql->connect();
		$this->table='incentive.NEGATIVE_TREATMENT_LIST';
	}

	/*
	* This Function is used to get list of spam profile based on flag(s)
	* @flagParameters array list of flag used as a where condition
	* @return array array of profileid.
	*/
	public function getListOfSpammers($flagParameters='')
	{
		if($flagParameters)
			$this->generateWhereConditionsFromParameters($flagParameters);

                $sql="SELECT PROFILEID FROM $this->table";
                if($this->whereConditionArr)
		{
                        $whereCondition=implode(" AND ",$this->whereConditionArr);
			$sql.=" WHERE $whereCondition";
		}
                $result = $this->mysql->executeQuery($sql,$this->db);
                while($myrow =$this->mysql->fetchAssoc($result))
		{
                        $pidArr[]=$myrow["PROFILEID"];
		}
		return $pidArr;
	}

	/*
	* This Function is used to check if profileid is spam.
	* @profileid int profiled againts which to determine if its a spammer or not.
	* @flagParameters array list of flag used as a where condition
	* @return int 1: profile is spammer, 0:profile is non=spammer
	*/
	public function isNegativeTreatmentRequired($profileid,$flagParameters='')
	{
		if($profileid)
			$this->whereConditionArr[]="PROFILEID='$profileid'";
		if($flagParameters)
			$this->generateWhereConditionsFromParameters($flagParameters);
		if($this->whereConditionArr)
			$whereCondition=implode(" AND ",$this->whereConditionArr);

		$sql="SELECT COUNT(*) as CNT FROM $this->table WHERE $whereCondition";
		$result = $this->mysql->executeQuery($sql,$this->db);
		$myrow =$this->mysql->fetchAssoc($result);
		if($myrow["CNT"]>0)
			return 1;
		else
			return 0;
	}

	/*
	* This Function is used to set whereConditionArr which store where conditions of various flags.
	* @flagParameters array list of flag used as a where condition
	*/
	public function generateWhereConditionsFromParameters($flagParameters)
	{	
		if($flagParameters['FLAG_VIEWABLE'])
			$this->whereConditionArr[]="FLAG_VIEWABLE='$this->notViewable'";
		if($flagParameters['FLAG_INBOX_EOI'])
			$this->whereConditionArr[]="FLAG_INBOX_EOI='$this->noInboxEoi'";
		if($flagParameters['FLAG_CONTACT_DETAIL'])
			$this->whereConditionArr[]="FLAG_CONTACT_DETAIL='$this->noContactsDetailsVisible'";
		if($flagParameters['FLAG_OUTBOUND_CALL'])
			$this->whereConditionArr[]="FLAG_OUTBOUND_CALL='$this->noOutboundCall'";
		if($flagParameters['FLAG_INBOUND_CALL'])
			$this->whereConditionArr[]="FLAG_INBOUND_CALL='$this->noInboundCall'";
		if($flagParameters['CHAT_INITIATION'])
			$this->whereConditionArr[]="CHAT_INITIATION='$this->noChatInitiation'";
	}

	/*
	* This function handles delete joins.
	* @flagParameters array list of flag used as a where condition
	* @table string table from which records need to be deleted.
	* @joinParamter string paramter against which PROFILEID of NEGATIVE_TREATMENT_LIST is matched.
	*/
	public function deleteJoin($flagParameters,$table,$joinParamter)
	{
		if($flagParameters)
			$this->generateWhereConditionsFromParameters($flagParameters);	
		if($this->whereConditionArr)
			$whereCondition=implode(" AND ",$this->whereConditionArr);

		$sql="DELETE A.* FROM $table A , $this->table B WHERE A.$joinParamter=B.PROFILEID AND $whereCondition";
		$this->mysql->executeQuery($sql,$this->db);
	}

	/*
	* This function is used to insert records NOT IN USE
	* @insertUpdateParamters array column value (key-value) pair to be updated.
	* @profileid int profileid of a updated user.
	*/
	public function addRecords($insertUpdateParamters,$profileid)
	{
		if($profileid && is_array($insertUpdateParamters))
		{
			foreach($insertUpdateParamters as $k=>$v)
			{
				$kArray[]=$k;
				$vArray[]="'".$v."'";
			}
			if(!$insertUpdateParamters["ENTRY_DT"])
			{
				$kArray[]='ENTRY_DT';
				$vArray[]="now()";
			}
			if(!$insertUpdateParamters["PROFILEID"])
			{
				$kArray[]='PROFILEID';
				$vArray[]=$profileid;
			}
			$kStr=implode(",",$kArray);
			$vStr=implode(",",$vArray);

			$sql="INSERT IGNORE INTO $this->table ($kStr) VALUES ($vStr)";
			$this->mysql->executeQuery($sql,$this->db);	
			if($this->mysql->affectedRows())
			{
				if($insertUpdateParamters['FLAG_VIEWABLE']=='N')
					$this->deleteSearchRecords($profileid);
			}
		}
	}

        /*
        * This function is used to update records and Flags 
        * @updateParamters array column value (key-value) pair to be updated.
        * @profileid int profileid of a updated user.
        */
        public function updateRecords($updateParamters,$profileid)
        {
                if($profileid && is_array($updateParamters))
                {
                        foreach($updateParamters as $k=>$v)
                        {
                                $val="'".$v."'";
                                $strArray[]=$k."=".$val;
                        }
                        $kStr=implode(",",$strArray);

                        $sql="UPDATE $this->table SET $kStr where PROFILEID='$profileid'";
                        $this->mysql->executeQuery($sql,$this->db); 
                        if($this->mysql->affectedRows())
                        {	
                                if($updateParamters['FLAG_VIEWABLE']=='N')
                                        $this->deleteSearchRecords($profileid);
                        }
                }
        }

	/*
	* This function is used to delete an entry from the table. NOT IN USE
	* @profileid int profileid of a updated user.
	*/
	public function deleteRecords($profileid)	
	{
		$sql="DELETE FROM $this->table where PROFILEID=$profileid";
		$this->mysql->executeQuery($sql,$this->db);	
	}

	/*
	this function will move on search class
	*/
	public function deleteSearchRecords($profileid)	
	{
		$sql="DELETE FROM newjs.SEARCH_FEMALE WHERE PROFILEID='$profileid'";	
		$this->mysql->executeQuery($sql,$this->db);

		$sql="DELETE FROM newjs.SEARCH_MALE WHERE PROFILEID='$profileid'";	
		$this->mysql->executeQuery($sql,$this->db);
	}

	public function removeNegativeIdsFromList($flagParameters,$profileIdArr)
	{
                if($flagParameters)
                        $this->generateWhereConditionsFromParameters($flagParameters);

                $sql="SELECT PROFILEID FROM $this->table";
                if($this->whereConditionArr)
                {
                        $whereCondition = implode(" AND ",$this->whereConditionArr);
			$profileIdStr=implode("','",$profileIdArr);
                        $sql.=" WHERE $whereCondition";
			$sql.= "AND PROFILEID IN ('$profileIdStr')";
                }
                $result = $this->mysql->executeQuery($sql,$this->db);
                while($myrow =$this->mysql->fetchAssoc($result))
                {
                        $pidArr[]=$myrow["PROFILEID"];
                }
		if(!is_array($pidArr))
			return $profileIdArr;
		$finalArr=array_diff($profileIdArr,$pidArr);
                return $finalArr;
	}
}
?>
