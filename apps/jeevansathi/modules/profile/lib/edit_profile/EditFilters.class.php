<?php
class EditFilters extends EditProfileComponent {
	public function submit() {
		$UPDATE = 1;
		if ($crmback == "admin") editprofile_change_log($_POST);
/*
		$sql = "UPDATE newjs.JPROFILE SET PRIVACY='$radioprivacy' , MOD_DT=now(),LAST_LOGIN_DT='$today' WHERE PROFILEID='$profileid'";
		mysql_query_decide($sql) or logError("1 Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql, "ShowErrTemplate");
		$sql_el = "INSERT INTO newjs.EDIT_LOG (PROFILEID,PRIVACY,MOD_DT,LAST_LOGIN_DT) VALUES ('$profileid','$radioprivacy',now(),'$today')";
		$result_el = mysql_query_decide($sql_el) or logError("Due to some temporary problem your request could not be processed. Please try after some time.", $sql_el, "ShowErrTemplate");
*/
	}
	public function display() {
	}
}
