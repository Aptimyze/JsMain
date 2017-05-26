<?php
class EditFilters extends EditProfileComponent {
	public function submit() {
		$UPDATE = 1;
		if ($crmback == "admin") editprofile_change_log($_POST);
	}
	public function display() {
	}
}
