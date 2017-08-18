<?php

/**
 * This table is used to store the number of photo profiles and photos screened in a day (by a particular screening user) from various sources such as mail, new, etc
**/
class PHOTO_SCREEN_STATS extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

	/**
	 * This function is used to update the number of photo profiles and photos screened in a day by a screening user.
	 * This is executed in case the screening is done for photos sent through mail.
	**/
	public function updateScreenedPhotoCountMail($user,$source,$mailAppPhotos,$otherAppPhotos,$mailDelPhotos,$otherDelPhotos)
	{
		$sql="UPDATE MIS.PHOTO_SCREEN_STATS SET ";
		if($mailAppPhotos)
			$sql.="APP_MAIL=APP_MAIL+1,APP_MAIL_PHOTOS=APP_MAIL_PHOTOS+:MAILAPPPHOTOS,DEL_MAIL_PHOTOS=DEL_MAIL_PHOTOS+:MAILDELPHOTOS ";
//		if($mailDelPhotos && $mailAppPhotos)
//			$sql.=",DEL_MAIL=DEL_MAIL+$mailDelPhotos ";
//		elseif($mailDelPhotos && !$mailAppPhotos)
		elseif($mailDelPhotos)
			$sql.="DEL_MAIL=DEL_MAIL+1, DEL_MAIL_PHOTOS=DEL_MAIL_PHOTOS+:MAILDELPHOTOS ";

		if($source == 'new' || $source == 'edit')
		{
			$source = strtoupper($source);
			$source_photos=$source."_PHOTOS";
			$source_del=$source."_DEL";
			$source_del_photos=$source."_DEL_PHOTOS";
			if(($mailAppPhotos || $mailDelPhotos)&&($otherAppPhotos||$otherDelPhotos))
				$sql.=",";
			if($otherAppPhotos)
				$sql.=" $source=$source+1, $source_photos=$source_photos+:OTHERAPPPHOTOS ";
			if($otherDelPhotos)
			{
				if($otherAppPhotos)
					$sql.=",$source_del_photos=$source_del_photos+:OTHERDELPHOTOS ";
				else
					$sql.=" $source_del=$source_del+1 ,$source_del_photos=$source_del_photos+:OTHERDELPHOTOS ";
			}
		}

		$sql.="WHERE SCREENED_BY = :USER AND DATE=CURDATE()";
	        $res=$this->db->prepare($sql);
		$res->bindValue(":USER", $user, PDO::PARAM_STR);
		if($mailAppPhotos)
			 $res->bindValue(":MAILAPPPHOTOS", $mailAppPhotos, PDO::PARAM_INT);
                $res->bindValue(":MAILDELPHOTOS", $mailDelPhotos, PDO::PARAM_INT);
		if($otherAppPhotos)
			$res->bindValue(":OTHERAPPPHOTOS", $otherAppPhotos, PDO::PARAM_INT);
		if($otherDelPhotos)
			$res->bindValue(":OTHERDELPHOTOS", $otherDelPhotos, PDO::PARAM_INT);
		
		if($mailAppPhotos || $otherAppPhotos || $mailDelPhotos || $otherDelPhotos)
			$res->execute();
	
		if($res->rowCount()==0)
		{
			$columns = "DATE,SCREENED_BY ";
			$values = "CURDATE(),:USER ";

			if($mailAppPhotos)
			{
				$columns.=" ,APP_MAIL,APP_MAIL_PHOTOS,DEL_MAIL_PHOTOS ";
				$values.= ",'1',:MAILAPPPHOTOS,:MAILDELPHOTOS ";
			}
			elseif($mailDelPhotos)
			{
				$columns.=",DEL_MAIL,DEL_MAIL_PHOTOS ";
				$values.= ",'1',:MAILDELPHOTOS ";
			}

//			$columns = "DATE,SCREENED_BY,APP_MAIL,APP_MAIL_PHOTOS,DEL_MAIL ";
//			$values = "CURDATE(),:USER,'1',:MAILAPPPHOTOS,:MAILDELPHOTOS ";
			if($source == 'new' || $source == 'edit')
			{
				if($otherAppPhotos)
				{
					$columns.=",$source,$source_photos";
					$values.=",'1',:OTHERAPPPHOTOS";
				}
				elseif($otherDelPhotos && !$otherAppPhotos)
				{
					$columns.=",$source_del ";
					$values.=",'1' ";
				}
				if($otherDelPhotos)
				{
					$columns.=",$source_del_photos";
					$values.=",:OTHERDELPHOTOS";
				}
			}
			$sql2 = "INSERT INTO MIS.PHOTO_SCREEN_STATS($columns) VALUES($values)";
                	$res2=$this->db->prepare($sql2);
			$res2->bindValue(":USER", $user, PDO::PARAM_STR);
			if($mailAppPhotos)
				$res2->bindValue(":MAILAPPPHOTOS", $mailAppPhotos, PDO::PARAM_INT);
			$res2->bindValue(":MAILDELPHOTOS", $mailDelPhotos, PDO::PARAM_INT);
			if($otherAppPhotos)
	                        $res2->bindValue(":OTHERAPPPHOTOS", $otherAppPhotos, PDO::PARAM_INT);
        	        if($otherDelPhotos)
                	        $res2->bindValue(":OTHERDELPHOTOS", $otherDelPhotos, PDO::PARAM_INT);

			if($mailAppPhotos || $otherAppPhotos || $mailDelPhotos || $otherDelPhotos)
				$res2->execute();
		}
	}

	/**
	 * This function is used to update the number of photo profiles and photos screened in a day by a screening user.
	 * This is executed in case the screening is done for photos uploaded by the user.
	**/
	public function updateScreenedPhotoCount($user,$source,$appPhotos,$delPhotos,$markedForEditingPhotos,$szInterfaceName='')
	{
		try{
			
			$sql="UPDATE MIS.PHOTO_SCREEN_STATS SET ";

			$source = strtoupper($source);
			$source_photos=$source."_PHOTOS";
			$source_del=$source."_DEL";
			$source_del_photos=$source."_DEL_PHOTOS";
			$source_marked_for_editing = $source."_MARKED_FOR_EDITING";
			
			$arrSql = array();
			$arrSql[]="$source=$source+1";
			if($appPhotos)
				$arrSql[]=" $source_photos=$source_photos+:APPPHOTOS";
			if($delPhotos && $appPhotos)
				$arrSql[]="$source_del_photos=$source_del_photos+:DELPHOTOS";
			elseif($delPhotos && !$appPhotos)
				$arrSql[]="$source_del=$source_del+1 ,$source_del_photos=$source_del_photos+:DELPHOTOS";
			if($markedForEditingPhotos )
				$arrSql[]="$source_marked_for_editing=$source_marked_for_editing+:MARKED_FOR_EDITING";
			if(strlen($szInterfaceName))
			{
				$arrSql[]="INTERFACE_NAME=:INTERFACENAME";
			}
			$sql .= implode(" , ",$arrSql);
			$sql.=" WHERE SCREENED_BY = :USER AND DATE=CURDATE() AND INTERFACE_NAME=:INTERFACENAME ";

			$res=$this->db->prepare($sql);
			$res->bindValue(":USER", $user, PDO::PARAM_STR);
			if($appPhotos)
				$res->bindValue(":APPPHOTOS", $appPhotos, PDO::PARAM_INT);
					if($delPhotos)
						$res->bindValue(":DELPHOTOS", $delPhotos, PDO::PARAM_INT);
			if($markedForEditingPhotos)
				$res->bindValue(":MARKED_FOR_EDITING",$markedForEditingPhotos,PDO::PARAM_INT);
			if(strlen($szInterfaceName))
			{
				$res->bindValue(":INTERFACENAME",$szInterfaceName,PDO::PARAM_STR);
			}
			if($appPhotos || $delPhotos || $markedForEditingPhotos)
				$res->execute();

			if($res->rowCount()==0)
			{
				$columns = "DATE,SCREENED_BY,$source ";
				$values = "CURDATE(),:USER,'1' ";
				if($appPhotos)
				{
					$columns.=",$source_photos";
					$values.=",:APPPHOTOS";
				}
				elseif($delPhotos && !$appPhotos)
				{
					$columns.=",$source_del ";
					$values.=",'1' ";
				}
				if($delPhotos)
				{
					$columns.=",$source_del_photos";
					$values.=",:DELPHOTOS";
				}
				if($markedForEditingPhotos)
				{
					$columns.=",$source_marked_for_editing ";
					$values.=",:MARKED_FOR_EDITING ";
				}
				if(strlen($szInterfaceName))
				{
					$columns.=",INTERFACE_NAME";
					$values.=",:INTERFACE_NAME";
				}
				$sql2 = "INSERT INTO MIS.PHOTO_SCREEN_STATS($columns) VALUES($values)";
						$res2=$this->db->prepare($sql2);

				if($appPhotos)
					$res2->bindValue(":APPPHOTOS", $appPhotos, PDO::PARAM_INT);
				if($delPhotos)
					$res2->bindValue(":DELPHOTOS", $delPhotos, PDO::PARAM_INT);
				if($markedForEditingPhotos)
					$res2->bindValue(":MARKED_FOR_EDITING",$markedForEditingPhotos,PDO::PARAM_INT);
				$res2->bindValue(":USER", $user, PDO::PARAM_STR);
				if(strlen($szInterfaceName))
				{
					$res2->bindValue(":INTERFACE_NAME",$szInterfaceName,PDO::PARAM_STR);
				}
				if($appPhotos || $delPhotos || $markedForEditingPhotos)
					$res2->execute();
				
			}
		}catch(Exception $e)
		{
			$szClassName = __CLASS__;
			throw new jsException($e,"Something went wrong in updateScreenedPhotoCount method of $szClassName");
		}
	}

	/**
	 * This function is used to update the number of app photo profiles and app photos screened in a day by a screening user.
	 * @param - screening user name, source, approve photo count, edited photo count
	**/
	public function updateScreenedPhotoCountMobileAppPic($user,$source,$appPhotos,$editPhotos)
	{
		$source = strtoupper($source);

		$sql = "UPDATE MIS.PHOTO_SCREEN_STATS SET ".$source." = ".$source." + 1";
		if($appPhotos)
			$sql = $sql.", ".$source."_APPROVE = ".$source."_APPROVE + 1";
		elseif($editPhotos)
			$sql = $sql.", ".$source."_EDITED = ".$source."_EDITED + 1";
		$sql = $sql." WHERE SCREENED_BY = :USER AND DATE = CURDATE()";
		$res = $this->db->prepare($sql);
		$res->bindValue(":USER", $user, PDO::PARAM_STR);
		$res->execute();
		if($res->rowCount()==0)
                {
			$sql2 = "INSERT INTO MIS.PHOTO_SCREEN_STATS(".$source.",".$source."_APPROVE,".$source."_EDITED,SCREENED_BY,DATE) VALUES (:".$source.",:".$source."_APPROVE,:".$source."_EDITED,:USER,CURDATE())";
			$res2 = $this->db->prepare($sql2);
			$res2->bindValue(":USER", $user, PDO::PARAM_STR);
			$res2->bindValue(":".$source, 1, PDO::PARAM_INT);
			if($appPhotos)
				$res2->bindValue(":".$source."_APPROVE", 1, PDO::PARAM_INT);
			else
				$res2->bindValue(":".$source."_APPROVE", 0, PDO::PARAM_INT);
			if($editPhotos)
				$res2->bindValue(":".$source."_EDITED", 1, PDO::PARAM_INT);
			else
				$res2->bindValue(":".$source."_EDITED", 0, PDO::PARAM_INT);
			$res2->execute();
		}
	}
}
?>
