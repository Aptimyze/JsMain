<?php
/*
 * This Class provide get id function for PROFILE_VERIFICATION.DOC_AUTOINCREMENT table
 * @author Reshu Rajput
 * @created March 20, 2014
*/

class PROFILE_VERIFICATION_DOC_AUTOINCREMENT extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

	/**
	This function is used to get autoincrement id from the table.
	Column NO_USE_VARIABLE is used for maintaining unique r/l.So that everytime a replace commnad is run existing row gets repalced and we can get a new increment id and not even increasing rows of table. 
	@return AUTO_ID id auto increment id which will be used as document id for PROFILE_VERIFICATION.DOCUMENTS
        */
	public function getAutoIncrementDocumentId()
	{
                $sql="REPLACE INTO PROFILE_VERIFICATION.DOC_AUTOINCREMENT(AUTO_ID,NO_USE_VARIABLE) VALUES('','X')";
                $res=$this->db->prepare($sql);
		$res->execute();
		return $this->db->lastInsertId();
        }
}
?>
