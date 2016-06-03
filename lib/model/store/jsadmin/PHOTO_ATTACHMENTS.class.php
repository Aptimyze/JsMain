<?php

/**
 * This table is used to store details of all the attachments sent by users to photos@jeevansathi.com.
**/
class PHOTO_ATTACHMENTS extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

	/**
	 * This function is used to get a list of all the image files sent in a particular mail.
	**/
	public function getImageAttachments($mailid)
	{
		$sql="SELECT FILENAME FROM jsadmin.PHOTO_ATTACHMENTS WHERE MAILID=:MAILID AND FILETYPE LIKE 'image%'";
		$res=$this->db->prepare($sql);
		$res->bindValue(":MAILID", $mailid, PDO::PARAM_INT);
		$res->execute();

		while($row = $res->fetch(PDO::FETCH_ASSOC))
		{
			$images[] = $row['FILENAME'];
		}
		if($images)
			return $images;
		else
			return NULL;
	}

	/**
	 * This function is used to get a list of all the application files sent in a particular mail.
	**/
	public function getApplicationAttachments($mailid)
	{
		$sql="SELECT FILENAME FROM jsadmin.PHOTO_ATTACHMENTS WHERE MAILID=:MAILID AND FILETYPE LIKE 'application%'";
		$res=$this->db->prepare($sql);
		$res->bindValue(":MAILID", $mailid, PDO::PARAM_INT);
		$res->execute();

		while($row = $res->fetch(PDO::FETCH_ASSOC))
		{
			$applications[] = $row['FILENAME'];
		}
		if($applications)
			return $applications;
		else
			return NULL;
	}
}

?>
