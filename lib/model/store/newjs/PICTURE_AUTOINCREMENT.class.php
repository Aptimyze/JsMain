<?php
class PICTURE_AUTOINCREMENT extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

	/**
	This function is used to get autoincrement id from the table.
	Column NO_USE_VARIABLE is used for maintaining unique r/l.So that everytime a replace commnad is run existing row gets repalced and we can get a new increment id and not even increasing rows of table. 
	@return AUTO_ID id auto increment id which will be used as pictureId for PICTURE_NEW & PICTURE_FOR_SCREEN_NEW
        */
	public function getAutoIncrementPictureId()
	{
                $sql="REPLACE INTO newjs.PICTURE_AUTOINCREMENT(AUTO_ID,NO_USE_VARIABLE) VALUES('','X')";
                $res=$this->db->prepare($sql);
		$res->execute();
		return $this->db->lastInsertId();
        }
}
?>
