<?php

class IMPORT_PAGES_TRACKING extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

        /**
        * This function updates the table MIS.IMPORT_PAGES_TRACKING with the 
        * number of times a user has visited a page belonging to the import module.
        **/
	public function updatePageViewCounter($profileid,$pageName,$importSite)
	{
		$validPageNames= Array("PERMISSION_PAGE","ALBUM_PAGE","PHOTO_PAGE","ZERO_ALBUMS","IMPORT_FAILED","NO_PICASA_ACCOUNT");
		if(in_array($pageName,$validPageNames))
		{
			$date=date("Y-m-d");
			$sql="UPDATE MIS.IMPORT_PAGES_TRACKING SET $pageName=$pageName+1 WHERE PROFILEID=:PROFILEID AND IMPORT_SITE=:IMPORTSITE AND DATE =:DATE";
			$res=$this->db->prepare($sql);
			$res->bindValue(":IMPORTSITE", $importSite, PDO::PARAM_STR);
			$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			$res->bindValue(":DATE", $date, PDO::PARAM_STR);
			$res->execute();
		
			if($res->rowCount()==0)
			{
				$sql1="INSERT INTO MIS.IMPORT_PAGES_TRACKING(PROFILEID,$pageName,IMPORT_SITE,DATE) VALUES (:PROFILEID,'1',:IMPORTSITE,:DATE)";
				$res1=$this->db->prepare($sql1);
				$res1->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
				$res1->bindValue(":IMPORTSITE", $importSite, PDO::PARAM_STR);
				$res1->bindValue(":DATE", $date, PDO::PARAM_STR);
				$res1->execute();
			}
		}
	}
}
?>
