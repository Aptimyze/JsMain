<?php
/**
 * This task deletes all records before a one year from table AUTOMATED_CONTACTS_TRAKING
 * 
 * @package    jeevansathi
 * @author     Ankit Shukla
 */
class APdeleteOldRecords extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'Assisted_Product';
    $this->name             = 'APdeleteOldRecords';
    $this->briefDescription = 'AP Delete Old Records';
    $this->detailedDescription = <<<EOF
The APdeleteOldRecords task deletes records before 1 year on a regular basis
Call it with:

  [php symfony Assisted_Product:APdeleteOldRecords]
EOF;
   $this->addOptions(array(
    new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));

  }
    /*
   * this function deletes records before 1 year on a regular basis
   * @param : $arguments array of arguments
   * @param : $options array of options
   */
  protected function execute($arguments = array(),$options = array())
  {
    if (!sfContext::hasInstance())
      sfContext::createInstance($this->configuration);
    $beforeDate = date('Y-m-d', strtotime('-1 years')); 
    $deleteRecords = new ASSISTED_PRODUCT_AUTOMATED_CONTACTS_TRACKING();
    $deleteRecords->DeleteRecordsBeforeDate($beforeDate);
  }

}