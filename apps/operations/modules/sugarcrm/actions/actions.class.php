<?php

/**
 * sugarcrm actions.
 *
 * @package    jeevansathi
 * @subpackage sugarcrm
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sugarcrmActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('default', 'module');
  }
  public function executeCsvToSugar(sfWebRequest $request)
  {
	if($request->getParameter("submit"))
	{
		$mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
		if($_FILES && (in_array($_FILES['csv']['type'],$mimes)))
		{
			$tempName = $_FILES['csv']['tmp_name'];
			$name = sfConfig::get("sf_upload_dir")."/SearchLogs/".date("YmdHis").".csv";
			move_uploaded_file($tempName,$name);
		$currentPath= getcwd();
                chdir(JsConstants::$docRoot."/../crontabs/sugarcrm/");
                $command = JsConstants::$php5path." csvToSugar.class.php ".$name;
                exec($command);
                chdir($currentPath);
		$this->message = "UPLOADED";
		}
		else
			$this->message="Some issue occured";
		$this->result=true;
	}
	else
		$this->result=false;
  }
}
