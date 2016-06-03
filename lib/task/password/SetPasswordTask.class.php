<?php
/*
*/
class SetPasswordTask extends sfBaseTask
{
	protected function configure()
        {
		$this->addArguments(array(
		new sfCommandArgument('profileid', sfCommandArgument::REQUIRED, 'My argument'),
		new sfCommandArgument('password', sfCommandArgument::REQUIRED, 'My argument')));
		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi')));
		$this->namespace        = 'password';
		$this->name             = 'SetPassword';
		$this->briefDescription = 'onetimecron';
		$this->detailedDescription = <<<EOF
        Call it with:
          [php symfony password:SetPassword profileid passwordToBeSet]
EOF;
        }
        protected function execute($arguments = array(), $options = array())
        {
		if(!sfContext::hasInstance())
                        sfContext::createInstance($this->configuration);
		$profileid = $arguments["profileid"];
		$password = $arguments["password"];
		$jprofileObj = new Jprofile;
		$password = PasswordHashFunctions::createHash($password);
		$jprofileObj->edit(array("PASSWORD"=>$password), $profileid, $criteria="PROFILEID");
	}
}
?>
