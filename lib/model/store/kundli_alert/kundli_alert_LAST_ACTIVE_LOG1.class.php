<?php

class kundli_alert_LAST_ACTIVE_LOG1 extends TABLE
{
	public function __construct($dbname="")
	{
			$dbname = "matchalerts_slave";
			parent::__construct($dbname);
	}

	/*this function returns last active partition
         * @return - partition number
         */
	public function getLastActivePartition(){
		try{
			$sql="SELECT NO FROM kundli_alert.LAST_ACTIVE_LOG1";
			$prep = $this->db->prepare($sql);
			$prep->execute();
			$lastPartitionName = $prep->fetch(PDO::FETCH_ASSOC);
			return $lastPartitionName['NO'];
		}
		catch (PDOException $ex) {
			throw new jsException($ex);
		}
	}

	/*this function updates last active partition with newer value
         * @return - partition number
         */
	public function updateLastActivePartition($newPartitionName,$date){
		try{
			$sql="UPDATE kundli_alert.LAST_ACTIVE_LOG1 SET NO= :NEW_PARTITION,DATE= :DATE";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":NEW_PARTITION", $newPartitionName, PDO::PARAM_INT);
			$prep->bindValue(":DATE", $date, PDO::PARAM_STR);
			$prep->execute();
		}
		catch (PDOException $ex) {
			throw new jsException($ex);
		}
	}
}