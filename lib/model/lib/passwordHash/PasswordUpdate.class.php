<?php
class PasswordUpdate
{
    
    public static function change($profileid,$originalPassword)
    {
	$jprofileObj = new Jprofile;
	$encryptedPassword = PasswordHashFunctions::createHash($originalPassword);
	$done = $jprofileObj->edit(array("PASSWORD"=>$encryptedPassword), $profileid, $criteria="PROFILEID");
	return $done;
    }
}
?>
