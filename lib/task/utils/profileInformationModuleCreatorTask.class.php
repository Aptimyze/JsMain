<?php
/*
 * Author: Reshu Rajput
 * Created: Sep 24, 2013
 * This cron is used to configuration mapping file for all the modules in profileInformation
*/

class profileInformationModuleCreatorTask extends sfBaseTask
{
  protected function configure()
  {
    $this->modules= array("MYJSAPP","ContactCenterAPP");
    $this->addOptions(array(
       new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','jeevansathi'),
     ));

    $this->namespace        = 'utils';
    $this->name             = 'profileInformationModuleCreator';
    $this->briefDescription = 'creating mapping files for configuration of modules in profileInformation';
    $this->detailedDescription = <<<EOF
The profileInformationModuleCreator task create a file called profileInformationModuleMap in lib/model/lib/profileCommunication/
Call it with:

  [php symfony utils:profileInformationModuleCreator]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
	 if(!sfContext::hasInstance())
                        sfContext::createInstance($this->configuration);
	$arrays= Array();
	foreach($this->modules as $k=>$v)
	{
		$module=$v.'_CONFIG';
		$moduleObj= new $module;
		$where= Array("ACTIVE_FLAG"=>"Y"); // It will retrieve only active configurations used to avoid extra condition
		$arrays[$v]= $moduleObj->getConfig("",$where);
		unset($moduleObj);
	}
	$fp=fopen(JsConstants::$cronDocRoot."/lib/model/lib/profileCommunication/ProfileInformationModuleMap.class.php","w");
        $now=date("Y-m-d");
	fwrite($fp,"<?php\n /*
        This is auto-generated class by running utils:profileInformationModuleCreator task
        This class should not be updated manually.
        Created on $now
 */
class ProfileInformationModuleMap
{
        /*This declares array of  all the configurations of all the given modules*/\n");
        foreach($arrays as $module=>$configs)
               fwrite($fp,"\n\tpublic static \$$module;");
	fwrite($fp,"\n\tstatic public function init()
        {");

	foreach($arrays as $module=>$configs)
        {

                fwrite($fp,"\n\t\tself::\$$module=Array(");
		foreach($configs as $row=>$rowValue)
		{
			fwrite($fp,"\n\t\t\"$row\"=>Array( ");
			foreach($rowValue as $col=>$colvalue)
			{
				fwrite($fp,"\n\t\t\t\"$col\"=> \"$colvalue\",");
			}
			fwrite($fp,"\n\t\t),");
		} 
		fwrite($fp,"\n\t\t);");
        }
	fwrite($fp,"\n\t}");
	fwrite($fp,"\n\t/* This function will return configuration of given module and infoType*/
	public function getConfiguration(\$module,\$infoType='')
	{
		self::init();
		if(isset(self::\${\$module}))
                {
	                if(\$infoType=='')
        	                return self::\${\$module};
                	if(array_key_exists(\$infoType,self::\${\$module}))
                        	return self::\${\$module}[\$infoType];
		}
		throw new JsException(\"\",\"Wrong module or infoType is given in profileInformationModuleMap.class.php\");
	}

        /* This function will return the infotype(example:INTEREST_RECEIVED) based on id*/
        public static function getInfoTypeById(\$module,\$id)
        {
                self::init();
                if(isset(self::\${\$module}))
                {
                        foreach(self::\${\$module} as \$k=>\$v)
                        {
                                if(\$v['ID']==\$id)
                                        return \$k;
                        }
                }
                throw new JsException(\"\",\"Wrong module or infoType is given in profileInformationModuleMap.class.php\");
        }
	");
	fwrite($fp,"\n}");
	fclose($fp);
	unset($arrays);

  }
}
