<?php
class BackendAppAuthentication extends BackendApiAuthentication
{
	public function __construct()
	{
		parent::__construct();
		$this->isApp=true;
		$this->trackLogin = true; //login time tracking for app
		$this->maintainSession = false;
	} 
	 
}
?>
