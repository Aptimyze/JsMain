<?php
/*This class is used to handle the matchalerts*/
class APCallSHistory
{
	public function __construct($dbname='')
	{
		$this->dbname = $dbname;
	}

	public function getIntroCallProfile($profileId)
	{
		$ProfilesObj = new ASSISTED_PRODUCT_AP_CALL_HISTORY($this->dbname);
		$output = $ProfilesObj->getIntroCallProfiles($profileId);
		return $output;
	}
}
?>
