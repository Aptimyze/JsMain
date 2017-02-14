<?php
/**
 * cronDuplicationEntriesDeletionTask
 * 
 * This task is used to delete entries from  test.JPROFILE_FOR_DUPLICATION
 * 
 * @author     Reshu Rajput
 * @created    28-06-2013
 */

class cronDuplicationEntriesDeletionTask extends sfBaseTask
{
  protected function configure()
  {

    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));

    $this->namespace        = 'cron';
    $this->name             = 'cronDuplicationEntriesDeletion';
    $this->briefDescription = 'this is used to delete entries from test JPROFILE_FOR_DELETION table ';
    $this->detailedDescription = <<<EOF
The [cronDuplicationEntriesDeletion|INFO] task delete entries from test.JPROFILE_FOR_DELETION table which have last login date before the one configured in CrawlerConfig. Call it with:

  [php symfony cron:cronDuplicationEntriesDeletion]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {

	sfContext::createInstance($this->configuration);
	$JprofileForDuplicationObj= new JprofileForDuplication();
	$result = $JprofileForDuplicationObj->del();
	if(!$result)
		mail("reshu.rajput@jeevansathi.com","cronDuplicationEntriesDeletionTask","No entries got deleted from JPROFILE_FOR_DUPLICATION");
		mail("palash.chordia@jeevansathi.com","cronDuplicationEntriesDeletionTask","cron still running");
        
        
  }
}
