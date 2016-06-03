<?php
class FTO_MIS_USERS_PHONE_PHOTO_DATA extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }


	public function updatePhotoUploadCount()
	{
		$dt=date('Y-m-d',JSstrToTime('now'));
		$sql="UPDATE FTO.MIS_USERS_PHONE_PHOTO_DATA SET NO_OF_USERS_WHO_UPLOADED_FIRST_PHOTO=NO_OF_USERS_WHO_UPLOADED_FIRST_PHOTO+1 WHERE DATE=:DATE";
                $res=$this->db->prepare($sql);
		$res->bindValue(":DATE", $dt, PDO::PARAM_STR);
		$res->execute();
		if($res->rowCount() == 0)
		{
			$insert = "INSERT IGNORE INTO FTO.MIS_USERS_PHONE_PHOTO_DATA (DATE,NO_OF_USERS_WHO_UPLOADED_FIRST_PHOTO) VALUES (:DATEVAL,1)";
			$prep=$this->db->prepare($insert);
			$prep->bindValue(":DATEVAL", $dt, PDO::PARAM_STR);
			$prep->execute();
		}
        }

	public function updateSearchableDbCount($dt,$percentUsersWithApprovedPhoto,$percentUsersWithVerifiedPhone,$noOfProfilesWithVerifiedPhone)
	{
		$sql = "UPDATE FTO.MIS_USERS_PHONE_PHOTO_DATA SET NO_OF_USERS_WHO_VERIFIED_PHONE = :noOfProfilesWithVerifiedPhone, PERCENT_OF_SEARCHABLE_USERS_WITH_VERIFIED_PHONE = :percentUsersWithVerifiedPhone,  PERCENT_OF_SEARCHABLE_USERS_WITH_APPROVED_PHOTO = :percentUsersWithApprovedPhoto WHERE DATE=:DATE ";
                $res=$this->db->prepare($sql);
		$res->bindValue(":DATE", $dt, PDO::PARAM_STR);
		$res->bindValue(":noOfProfilesWithVerifiedPhone", $noOfProfilesWithVerifiedPhone, PDO::PARAM_INT);
		$res->bindValue(":percentUsersWithVerifiedPhone", $percentUsersWithVerifiedPhone, PDO::PARAM_STR);
		$res->bindValue(":percentUsersWithApprovedPhoto", $percentUsersWithApprovedPhoto, PDO::PARAM_STR);
		$res->execute();
		if($res->rowCount() == 0)
		{
			$insert = "INSERT IGNORE INTO FTO.MIS_USERS_PHONE_PHOTO_DATA (DATE,NO_OF_USERS_WHO_VERIFIED_PHONE,PERCENT_OF_SEARCHABLE_USERS_WITH_VERIFIED_PHONE,PERCENT_OF_SEARCHABLE_USERS_WITH_APPROVED_PHOTO) VALUES (:DATEVAL,:noOfProfilesWithVerifiedPhone,:percentUsersWithVerifiedPhone,:percentUsersWithApprovedPhoto)";
			$prep=$this->db->prepare($insert);
			$prep->bindValue(":DATEVAL", $dt, PDO::PARAM_STR);
			$prep->bindValue(":noOfProfilesWithVerifiedPhone", $noOfProfilesWithVerifiedPhone, PDO::PARAM_INT);
			$prep->bindValue(":percentUsersWithVerifiedPhone", $percentUsersWithVerifiedPhone, PDO::PARAM_STR);
			$prep->bindValue(":percentUsersWithApprovedPhoto", $percentUsersWithApprovedPhoto, PDO::PARAM_STR);
			$prep->execute();
		}
	}

}
?>
