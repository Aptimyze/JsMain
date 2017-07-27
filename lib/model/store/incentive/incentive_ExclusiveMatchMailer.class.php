<?php 

/**
* 
*/
class incentive_ExclusiveMatchMailer extends TABLE {
	
	public function __construct($dbname="") {
    	parent::__construct($dbname);
  	}

  	//This function truncates the incentive_ExclusiveMatchMailer table
    public function truncate(){
    	try {
            $sql="TRUNCATE TABLE incentive.ExclusiveMatchMailer";
			$res = $this->db->prepare($sql);
            $res->execute();
        } catch (PDOException $e) {
			throw new jsException($e);
        }
    }

    public function insertReceiversAndAgentDetails($receiverData) {
    	try {
            $sql = "INSERT INTO incentive.ExclusiveMatchMailer (RECEIVER,AGENT_NAME,AGENT_EMAIL,AGENT_PHONE) VALUES";
            $COUNT = 1;
            $valueToInsert .= "(:KEY".$COUNT.",";
            $bind["KEY".$COUNT]["VALUE"] = $receiverData["CLIENT_ID"];
            $bind["KEY".$COUNT]["TYPE"] = "INT";
            $COUNT++;
            $valueToInsert .=":KEY".$COUNT.",";
            $bind["KEY".$COUNT]["VALUE"] = $receiverData["AGENT_NAME"];
            $bind["KEY".$COUNT]["TYPE"] = "STRING";
            $COUNT++;
            $valueToInsert .=":KEY".$COUNT.",";
            $bind["KEY".$COUNT]["VALUE"] = $receiverData["AGENT_EMAIL"];
            $bind["KEY".$COUNT]["TYPE"] = "STRING";
            $COUNT++;
            $valueToInsert .=":KEY".$COUNT.",";
            $bind["KEY".$COUNT]["VALUE"] = $receiverData["AGENT_PHONE"];
            $bind["KEY".$COUNT]["TYPE"] = "STRING";
            $valueInsert .= rtrim($valueToInsert,',')."),";
            $valueInsert = rtrim($valueInsert,',');
            $sql .=$valueInsert;
            $pdoStatement = $this->db->prepare($sql);
            
            foreach($bind as $key=>$val) {
                if($val["TYPE"] == "STRING")
                    $pdoStatement->bindValue($key, $val["VALUE"], PDO::PARAM_STR);
                else
                    $pdoStatement->bindValue($key, $val["VALUE"], PDO::PARAM_INT);
            }
            $pdoStatement->execute();
        }
        catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    public function getReceivers() {
    	try {
    		$sql = "SELECT RECEIVER 
    				FROM incentive.ExclusiveMatchMailer 
    				WHERE STATUS = :STATUS ;" ;

    		$prep = $this->db->prepare($sql);
    		$prep->bindValue(':STATUS','N',PDO::PARAM_STR);
    		$prep->execute();
    		$prep->setFetchMode(PDO::FETCH_ASSOC);
    		while ($row = $prep->fetch()) {
    			$result[] = $row["RECEIVER"];
    		}
    		return $result;
    	} catch (Exception $e) {
    		throw new jsException($e);
    	}
    }

    public function updateAcceptancesAndStatus($acceptances,$receiverID) {
    	try {
    		$sql = "UPDATE  incentive.ExclusiveMatchMailer 
    			SET ACCEPTANCES = :ACCEPTANCES, STATUS = :STATUS  
    			WHERE RECEIVER = :RECEIVER ;";

			$prep = $this->db->prepare($sql);
			$prep->bindValue(':ACCEPTANCES',$acceptances,PDO::PARAM_STR);
			$prep->bindValue('STATUS','U',PDO::PARAM_STR);
			$prep->bindValue(':RECEIVER',$receiverID,PDO::PARAM_INT);
			$prep->execute();	
    	} catch (Exception $e) {
    		throw new jsException($e);
    	}
    }

    public function getAll() {
        try {
            $sql = "SELECT RECEIVER, ACCEPTANCES, AGENT_EMAIL, AGENT_NAME, AGENT_PHONE
                    FROM incentive.ExclusiveMatchMailer
                    WHERE STATUS = :STATUS ;";

            $prep= $this->db->prepare($sql);
            $prep->bindValue(':STATUS','U',PDO::PARAM_STR);
            $prep->execute();
            $prep->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $prep->fetch()) {
                $result[] = $row;
            }
            return $result;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }

    public function updateStatus($pid,$status) {
        try {
            $sql = "UPDATE  incentive.ExclusiveMatchMailer 
                SET STATUS = :STATUS  
                WHERE RECEIVER = :RECEIVER ;";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(':STATUS',$status,PDO::PARAM_STR);
            $prep->bindValue(':RECEIVER',$pid,PDO::PARAM_INT);
            $prep->execute();
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }
}
?>