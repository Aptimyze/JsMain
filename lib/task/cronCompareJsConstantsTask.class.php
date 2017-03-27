<?
/*
This php script reads JsConstants on both branch and usr/local/scripts and tells the variable that may be missing.
*/

class cronCompareJsConstantsTask extends sfBaseTask
{
  /**
   * 
   * Configuration details for cron:cronCompareJsConstants
   * 
   * @access protected
   * @param none
  */
  protected function configure()
  {

    $this->namespace           = 'cron';
    $this->name                = 'cronCompareJsConstants';
    $this->briefDescription    = 'to compare variables present in JsConstants';
    $this->detailedDescription = <<<EOF
     The [cronCompareJsConstants|INFO] compares usr/local/scripts/ and the branch JsConstants and prints if a varibale is missing.
     [php symfony cron:cronCompareJsConstants branchName] 
EOF;
    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi')
    ));
  }

  /**
   * 
   * Function for executing cron. 
   * 
   * @access protected
   * @param $arguments,$options
   */
  protected function execute($arguments = array(), $options = array())
  {
    if (!sfContext::hasInstance())
    sfContext::createInstance($this->configuration);
    $compareKeysArr = array_keys(get_class_vars("JsConstants"));
    //print_r($compareKeysArr);die;
    $jsConstantsArr = file(JsConstants::$cronDocRoot."/commonConfig/JsConstants.class.php");
    foreach($jsConstantsArr as $key=>$value)
    {
      if(strpos($value,"="))
      {
        $intermediateArr[] = explode("=",$value);
      }
    }

    foreach($intermediateArr as $key=>$value)
    {
      if(strpos($value[0],"$"))
      {
          $finalArr[] = explode("$",$value[0]);
      }
    }

    foreach($finalArr as $key=>$val)
    {
      $jsConstantsKeyArr[] = trim($val[1]);
    }
    
    foreach($jsConstantsKeyArr as $key=>$value)
    {
      if(!in_array($value,$compareKeysArr))
      {
        echo($value."\n\n");
      }
    }
	}
}
?>