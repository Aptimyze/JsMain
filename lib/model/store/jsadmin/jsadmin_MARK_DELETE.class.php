<?php
class JSADMIN_MARK_DELETE extends TABLE
{
        public function __construct($dbname="")
        {
		parent::__construct($dbname);
        }

	/**
	  * This function gets a list of profiles that have been viewed by a user.
	  * Pass $keyVal as 1 if the profileids are to sent in the key of the returned array.
	**/

	public function Update($profileid)
	{
		try
                {	$now=date("Y-m-d H:i:s");
			$sql="UPDATE jsadmin.MARK_DELETE SET STATUS='D', DATE='$now' WHERE PROFILEID=:profileid";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":profileid",$profileid,PDO::PARAM_INT);
			$prep->execute();
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }

	}
}
?>
