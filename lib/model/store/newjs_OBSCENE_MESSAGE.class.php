<?php
class NEWJS_OBSCENE_MESSAGE extends TABLE{
       

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
		
		
		public function updateObsceneMessage($msgCommObj)
        {
			try 
			{
				
					$sql="INSERT INTO OBSCENE_MESSAGE (SENDER,RECEIVER,DATE,IP,MESSAGE,TYPE, MARKCC, BLOCKED, USER, DATE_EDIT) VALUES (:VIEWERID,:VIEWEDID,:DATE,INET_ATON(:IP),:MSG,:TYPE, :MARKCC, :BLOCKED, :USER, :DATE_EDIT )  ";
					$prep=$this->db->prepare($sql);
					$prep->bindValue(":VIEWERID",$msgCommObj->getSENDER(),PDO::PARAM_INT);
					$prep->bindValue(":VIEWEDID",$msgCommObj->getRECEIVER(),PDO::PARAM_INT);
					$prep->bindValue(":DATE",$msgCommObj->getDATE(),PDO::PARAM_STR);
					$prep->bindValue(":IP",$msgCommObj->getIP(),PDO::PARAM_STR);
					$prep->bindValue(":MSG",$msgCommObj->getMESSAGE(),PDO::PARAM_STR);
					$prep->bindValue(":MARKCC",$msgCommObj->getMARKCC(),PDO::PARAM_STR);
					$prep->bindValue(":TYPE",$msgCommObj->getTYPE(),PDO::PARAM_STR);
					$prep->bindValue(":USER",$msgCommObj->getUSER(),PDO::PARAM_STR);
					$prep->bindValue(":BLOCKED",$msgCommObj->getBLOCKED(),PDO::PARAM_STR);
					$prep->bindValue(":DATE_EDIT",$msgCommObj->getDATE_EDIT(),PDO::PARAM_STR);
					$prep->execute();
					return $this->db->lastInsertId(); 
				
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
		
		
		
		
}
?>
