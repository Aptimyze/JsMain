<?php
//This class is used to execute queries on newjs.SWAP_JPARTNER table
class NEWJS_SWAP_JPARTNER extends TABLE
{
        public function __construct($dbname="")
        {
		parent::__construct($dbname);
        }

	/*
	This function inserts data into SWAP_JPARTNER table
	@param - profileid
	*/
        public function insertData($profileid)
        {
		if(!$profileid)
			throw new jsException("","PROFILEID IS BLANK IN insertData() OF NEWJS_SWAP_JPARTNER.class.php");		
	
                try
                {
			$sql="insert ignore into newjs.SWAP_JPARTNER (PROFILEID) values(:profileid)";
                        $res=$this->db->prepare($sql);
			$res->bindValue(":profileid", $profileid, PDO::PARAM_INT);
                        $res->execute();
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
}
?>
