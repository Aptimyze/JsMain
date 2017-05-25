<?php
/*
*/
class PasswordHashTask extends sfBaseTask
{
	protected function configure()
        {
                $this->addOptions(array(
                new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
             ));

            $this->namespace        = 'password';
            $this->name             = 'PasswordHash';
            $this->briefDescription = 'onetimecron';
            $this->detailedDescription = <<<EOF
        Call it with:

          [php symfony password:PasswordHash]
EOF;
        }

        protected function execute($arguments = array(), $options = array())
        {
		if(!sfContext::hasInstance())
                        sfContext::createInstance($this->configuration);
		$swapJprofileObj = new NEWJS_SWAP_JPROFILE;
		$swapJprofileObj->dontUpdateTrigger();
		$jprofileObj = new Jprofile;
                for($i=1;$i<12000000;$i++)
		{
			unset($data);
			$data = $jprofileObj->getArray(array("PROFILEID"=>$i),'','',"PASSWORD,PROFILEID");
			if(!is_array($data) || strlen($data[0]['PASSWORD'])>PasswordHashFunctions::$ORIGINAL_PASSWORD_MAXLENGTH)
				continue;
			PasswordUpdate::change($data[0]['PROFILEID'],$data[0]['PASSWORD']);
		}
		
	}
}
?>
