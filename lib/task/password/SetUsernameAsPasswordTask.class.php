<?php
/*
*/
class SetUsernameAsPasswordTask extends sfBaseTask
{
	protected function configure()
        {
                $this->addOptions(array(
                new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
             ));

            $this->namespace        = 'password';
            $this->name             = 'SetUsernameAsPassword';
            $this->briefDescription = 'onetimecron';
            $this->detailedDescription = <<<EOF
        Call it with:

          [php symfony password:SetUsernameAsPassword]
EOF;
        }

        protected function execute($arguments = array(), $options = array())
        {
		$rowCount=20;
		if(!sfContext::hasInstance())
                        sfContext::createInstance($this->configuration);
		$jprofileObj = new Jprofile;
		for($i=0;$i>=0;$i++)
		{
			unset($data);
			$offset = $i*$rowCount;
			$data = $jprofileObj->getArray('','','',"PROFILEID,USERNAME",'','',$offset.",".$rowCount);
			if(!is_array($data))
				break;
			foreach($data as $k=>$v)
			{
				$password = PasswordHashFunctions::createHash($v['USERNAME']);
				$jprofileObj->edit(array("PASSWORD"=>$password), $v['PROFILEID'], $criteria="PROFILEID");
			}
		}
		
	}
}
?>
