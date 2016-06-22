<?php

/**
 * Description of VERIFICATION_DOCUMENTS
 * Store Class for CRUD Operation on PROFILE.VERIFICATION_DOCUMENTS
 * 
 * @author Bhavana Kadwal
 * @created 8th June 2016
 */

/**
 * 
 */
class VERIFICATION_DOCUMENTS extends TABLE {

        protected $nullValueMarker = "";

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */
        public function __construct($dbname = "") {
                parent::__construct($dbname);
        }

        /**
         * insertRecord
         * @param integer $ProfileID
         * @param char $dataArray verification documents array
         * @return boolean
         * @throws jsException
         */
        public function insertRecord($ProfileID, $dataArray) {
                if (!is_numeric(intval($ProfileID)) || !$ProfileID) {
                        jsException::log("", "iProfileID is not numeric in insertRecord OF VERIFICATION_DOCUMENTS.class.php");
                }
                if (empty($dataArray)) {
                        jsException::log("", "data array is empty in insertRecord OF VERIFICATION_DOCUMENTS.class.php");
                }

                try {
                        $sql = "REPLACE INTO `PROFILE`.`VERIFICATION_DOCUMENTS` (`PROFILEID`,`ID_PROOF_TYPE`,`PROOF_VAL`,`PROOF_TYPE`) VALUES ";
                        foreach($dataArray as $i=>$data){
                                $sql .= '(:PID,:ID_PROOF_TYPE'.$i.',:PROOF_VAL'.$i.',:PROOF_TYPE'.$i.'),';
                        }
                        $sql = rtrim($sql,',');
                        $pdoStatement = $this->db->prepare($sql);
                        $pdoStatement->bindValue(":PID", $ProfileID, PDO::PARAM_INT);
                        foreach($dataArray as $i=>$data){
                                $pdoStatement->bindValue(":ID_PROOF_TYPE".$i, $data['PROOF_KEY'], PDO::PARAM_STR);
                                $pdoStatement->bindValue(":PROOF_VAL".$i, $data['PROOF_VAL'], PDO::PARAM_STR);
                                $pdoStatement->bindValue(":PROOF_TYPE".$i, $data['PROOF_TYPE'], PDO::PARAM_STR);
                        }
                        $pdoStatement->execute();

                        return $pdoStatement->rowCount();
                } catch (Exception $ex) {
                        jsException::nonCriticalError($e);
                }
        }

        /**
         * 
         * @param type $ProfileID
         * @param type $fields
         * @return type
         * @throws jsException
         */
        public function getDocuments($ProfileID,$fields = '*') {
                if (!is_numeric(intval($ProfileID)) || !$ProfileID) {
                        jsException::log("", "iProfileID is not numeric in insertRecord OF PROFILE_PROFILE_COMPLETION_SCORE.class.php");
                }

                try {
                        $sql = "SELECT $fields FROM `PROFILE`.`VERIFICATION_DOCUMENTS` WHERE PROFILEID=:PID";
                        $pdoStatement = $this->db->prepare($sql);
                        $pdoStatement->bindValue(":PID", $ProfileID, PDO::PARAM_INT);
                        $pdoStatement->execute();
                        $profilesArr = array();
                        while($res=$pdoStatement->fetch(PDO::FETCH_ASSOC)){
                                if($fields != '*'){
                                        $profilesArr[$res['id']] =$res['id'];     
                                }else{
                                        $profilesArr[$res["ID_PROOF_TYPE"]]['PROOF_TYPE'] =$res['PROOF_TYPE'];
                                        $profilesArr[$res["ID_PROOF_TYPE"]]['PROOF_VAL'] =$res['PROOF_VAL'];
                                }
                        }
                        return $profilesArr;
                } catch (Exception $ex) {
                        jsException::log("Error While fetching pd verification documents" . $e);
                }
        }
        

	/**
	* This function is used to update using case.
	* @param updateArr array containing docId(key) updateValue(value) to be updated on colum columToUpdate
	* @param columToUpdate column to be updated.
	*/	
	public function multipleDocumentIdUpdate($updateArr,$columToUpdate)
	{
		try
		{
			$sql = "UPDATE PROFILE.VERIFICATION_DOCUMENTS ";
			$sql.= "SET  $columToUpdate  = ";
			$sql.= "CASE ";
			$i=0;
			foreach($updateArr as $k=>$v)
			{
				$sql.="WHEN id = :key$i THEN :value$i ";
				$idArr[] = ":key$i";
				$i++;
			}
			$sql.= "END";
			$idKey = implode(",",$idArr);
			$sql.=" WHERE id  in ($idKey)";
                	$res = $this->db->prepare($sql);

			$i=0;
			foreach($updateArr as $k=>$v)
                	{
                        	$res->bindValue(":key$i",  $k, PDO::PARAM_INT);
                        	$res->bindValue(":value$i",$v, PDO::PARAM_INT);
				$i++;
                	}
                	$res->execute();
			return true;
		}
                catch(PDOException $e)
		{
                        throw new jsException($e);
                }
	}

}
