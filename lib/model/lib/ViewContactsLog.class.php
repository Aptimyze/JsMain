<?php
/*This class is used to handle the matchalerts*/
class ViewContactsLog
{
	public function __construct($dbname='')
	{
		$this->dbname = $dbname;
	}

	/*public function getContactsViewedProfile($condition)
	{
		$ProfilesObj = new jsadmin_VIEW_CONTACTS_LOG($this->dbname);
		$output = $ProfilesObj->getContactsViewedProfile($condition);
		return $output;
	}*/
}
?>
