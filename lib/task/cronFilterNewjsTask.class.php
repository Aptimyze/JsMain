<?php
/**
 * This cron identifies Junk characters entered in 'About me' and auto-mark incomplete after removing Junk characters
 */

class cronFilterNewjsTask extends sfBaseTask
{
    protected function configure()
    {
        $this->namespace           = 'cron';
        $this->name                = 'cronFilterNewjs';
        $this->briefDescription    = 'cron to remove junk characters from about me section.';
        $this->detailedDescription = <<<EOF
     cron to get values from slave filter newjs and update it on master filter newjs.
      Call it with:[php symfony cron:cronFilterNewjs|INFO]
EOF;
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi')
        ));
    }
    
    protected function execute($arguments = array(), $options = array()){
        if(!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);

        $this->limitFetchProfile = 1000;

        try 
        {
            $dbFilterSlave=new NEWJS_FILTER('newjs_slave');
            $dbFilterMaster=new NEWJS_FILTER();

            $profileIDs = array();

            $filedArray = array('MSTATUS','RELIGION');

            foreach ($filedArray as $key => $field) 
            {
                $i = 0;
                do
                {
                    $profileIDs = $dbFilterSlave->fetchField($field,$this->limitFetchProfile,$i * $this->limitFetchProfile);
                    
                    if(is_array($profileIDs))
                    {
                        $dbFilterMaster->updateField($field,$profileIDs);
                        $i++;
                    }
                    else
                    {
                        break;
                    }
                } while (1);
            }
        } 
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }  
}

