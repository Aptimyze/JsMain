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
        // ini_set('memory_limit','256M');
        $this->limitUpdateProfile = 2000;

        try 
        {
            $dbFilterSlave=new ProfileFilter('newjs_slave');
            $dbFilterMaster=new ProfileFilter();

            $profileIDs = array();

            $fieldArray = array('MSTATUS','RELIGION');

            foreach ($fieldArray as $key => $field) 
            {
                $profileIDs = $dbFilterSlave->fetchField($field);


                if ( is_array($profileIDs))
                {
                    $chunkedProfileIDs = array_chunk($profileIDs,$this->limitUpdateProfile);

                    if ( is_array($chunkedProfileIDs) )
                    {
                        foreach ($chunkedProfileIDs as $key => $value) {
                            if ( is_array($value))
                            {
                                $dbFilterMaster->updateField($field,$value);
                            }
                        }
                    }

                }

            }
        } 
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }  
}

