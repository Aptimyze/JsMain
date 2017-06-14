<?php
/*
This function is used to process queries on test.TEMP_PICTUREID table
*/
class test_TEMP_PICTUREID extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

	/*
	This function inserts given pictureid in the TEMP_PICTUREID table
	@param - pictureid
	*/
	public function insert($pictureId)
	{
		if(!$pictureId)
			throw new jsException("","PICTUREID IS BLANK IN insert() of test_TEMP_PICTUREID.class.php");

		try
		{
			$sql = "REPLACE INTO test.TEMP_PICTUREID(PICTUREID) VALUES (:PICTUREID)";
			$res = $this->db->prepare($sql);
                        $res->bindValue(":PICTUREID",$pictureId, PDO::PARAM_INT);
			$res->execute();
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
}
?>
