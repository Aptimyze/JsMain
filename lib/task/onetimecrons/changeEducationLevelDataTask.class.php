<?php
	/**
 	* This is a one time cron which checks EDU_LEVEL_NEW value fo JPROFILE and accordingly changes the value in newjs.JPROFILE_EDUCATION
 	* * <code>
 	* To execute : $ php symfony oneTimeCron:changeEducationLevelData"
 	* </code>
 	* @author Sanyam Chopra
 	* @created 2nd March 2016
 */
class changeEducationLevelDataTask extends sfBaseTask{
	protected function configure()
  {

    $this->namespace        = 'oneTimeCron';
    $this->name             = 'changeEducationLevelData';
    $this->briefDescription = 'reads EDU_LEVEL_NEW for each profile and based on the value changes the entry in JPROFILE_EDUCATION';
    $this->detailedDescription = <<<EOF
      This is a one time cron that will fetch details of EDU_LEVEL_NEW of profiles and will remove entries of PG degree,PG college, Other PG degree and make them blank  
      Call it with:

      [php symfony oneTimeCron:changeEducationLevelData] 
EOF;
   
  }

  //this function will fetch data from JPROFILE and accordingly update data in JRPOFILE_EDUCATION
  protected function execute($arguments = array(), $options = array())
  { 
    $ugCodeFlag="1";
    $graduateCodeFlag="0";
    $ugCodes="'9','24','23'";
    $graduateCodes="'1','2','3','4','5','6','17','22','25','26','28','32','33','34','35','38','39','40'";
    
    //setting memory_limit and max_execution_time
     ini_set('max_execution_time',-1);
     ini_set('memory_limit','256M');
    $jprofEducationObj = new NEWJS_JPROFILE_EDUCATION("newjs_slave");
    $jprofResetEducationObj = new NEWJS_JPROFILE_EDUCATION("newjs_master");
    
    //Fetching profileId's for $graduateCodes
    $gradProfileIdData = $jprofEducationObj->getEducationData($graduateCodes,$graduateCodeFlag);
    //Reseting the data in NEWJS_JPROFILE_EDUCATION for $graduateCodes
    foreach($gradProfileIdData as $k=>$v)
    {
      $jprofResetEducationObj->resetEducationData($v["PROFILEID"],$graduateCodeFlag);
    }
     //Fetching profileId's for $graduateCodes
    $underGradProfileIdData =  $jprofEducationObj->getEducationData($ugCodes,$ugCodeFlag);
    //Reseting the data in NEWJS_JPROFILE_EDUCATION for $ugCodes
    foreach($underGradProfileIdData as $k=>$v)
    {
      $jprofResetEducationObj->resetEducationData($v["PROFILEID"],$ugCodeFlag);
    }
    echo("cron successfully executed");die;
    
  }
}