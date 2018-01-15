<?php
/*
 * Author: Esha Jain
*/

class haveJeducationErrorTask extends sfBaseTask
{
	protected function configure()
        {
                $this->addOptions(array(
                new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
             ));

            $this->namespace        = 'cron';
            $this->name             = 'haveJeducationError';
            $this->briefDescription = 'onetimecron';
            $this->detailedDescription = <<<EOF
        Call it with:

          [php symfony cron:haveJeducationError]
EOF;
        }

        protected function execute($arguments = array(), $options = array())
        {

		if(!sfContext::hasInstance())
                        sfContext::createInstance($this->configuration);
		$jprofileEducationObj = new NEWJS_JPROFILE_EDUCATION("newjs_slave");
		$jprofileObj = new JPROFILE("newjs_master");
//		$date = '2015-08-28 00:00:00';
		for($i=1;$i<120;$i++)
		{
			$date = date("Y-m-d H:i:s",mktime(0,0,0,date("m"),date("d")-$i,date("Y")));
			$profiles = $jprofileEducationObj->gethaveEducationProfiles($date);
			if(is_array($profiles))
			{
				$profileStr = implode(",",$profiles);
				$jprofileObj->updateHaveJEducation($profileStr);
				unset($profiles);
				unset($profileStr);
			}
		}
	}
}
?>
