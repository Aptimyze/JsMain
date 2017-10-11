<?php
/*
 * This Class provide functions for JSADMIN.DELETED_PROFILE_VERIFICATION_DOCUMENTS table
 * @author Reshu Rajput
 * @created March 24, 2014
*/


class JSADMIN_DELETED_PROFILE_VERIFICATION_DOCUMENTS extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }


	 /** 
        This function is used to insert multiple values in DELETED_PROFILE_VERIFICATION_DOCUMENTS table.
        * @param docid Array of doc  ids to be inserted
	* @param $deletedBy Array of corresponding deleted by user
        **/
        public function insertbulkDocuments($docid,$deletedBy)
	{
                try
                {
			$now = date("y-m-d H:i:s");
			$sql = "INSERT INTO jsadmin.DELETED_PROFILE_VERIFICATION_DOCUMENTS (DOCUMENT_ID,DELETED_BY,DELETED_DATE) VALUES ";
                        for ($i=0;$i<count($docid);$i++)
                        {
                                $param[] = "(:DOCUMENT_ID".$i.",:DELETED_BY".$i.",:DELETED_DATE".$i.")";
                        }
                        $paramStr = implode(",",$param);
                        $sql = $sql.$paramStr;
                        $res = $this->db->prepare($sql);
                        for ($i=0;$i<count($docid);$i++)
                        {
				$res->bindParam(":DOCUMENT_ID".$i, $docid[$i], PDO::PARAM_INT);
				$res->bindParam(":DELETED_DATE".$i, $now, PDO::PARAM_STR);
                                $res->bindParam(":DELETED_BY".$i, $deletedBy[$i], PDO::PARAM_STR);
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
?>
