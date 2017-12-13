<?php
ini_set("max_execution_time",0);
ini_set("memory_limit","512M");
ini_set("mysql.connect_timeout",-1);
ini_set("default_socket_timeout",259200); // 3 days
ini_set("log_errors_max_len",0);

/*
 * Author: Reshu Rajput
 * This cron is used to transfer the images present in modules like picture  to image server for archiving
*/

class PhotoArchiveTask extends sfBaseTask
{
	private $limit = 5000;
	private $errorArray = array();

	protected function configure()
        {

                $this->addArguments(array(
                new sfCommandArgument('totalInstance', sfCommandArgument::REQUIRED, 'My argument'),new sfCommandArgument('currentInstance', sfCommandArgument::REQUIRED, 'My argument'),new sfCommandArgument('months', sfCommandArgument::REQUIRED, 'My argument'),new sfCommandArgument('module', sfCommandArgument::REQUIRED, 'My argument')
                ));

                $this->addOptions(array(
                new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
             ));

            $this->namespace        = 'cron';
            $this->name             = 'PhotoArchive';
            $this->briefDescription = 'transfers images to image server for archiving';
            $this->detailedDescription = <<<EOF
        This cron runs periodically and is used to transfer the images to the image server for archiving for profiles which are not active.
        Call it with:

          [php symfony cron:PhotoArchive totalInstance currentInstance months module]
EOF;
        }

        protected function execute($arguments = array(), $options = array())
        {
		if(!sfContext::hasInstance())
                        sfContext::createInstance($this->configuration);
	
		if($arguments["totalInstance"]<=$arguments["currentInstance"])
			die("Invalid Arguments");
		  if(CommonUtility::hideFeaturesForUptime())
                        successfullDie();

		$module = IMAGE_SERVER_MODULE_NAME_ENUM::getEnum($arguments["module"]);
		$months = $arguments["months"];
		if(!$module)
			die("Invalid Module Arguement");
		
                $fp=fopen('/tmp/PhotoArchiveTask_'.$module.$arguments["totalInstance"].$arguments["currentInstance"]. ".lock","w+");
                if($fp)
                {
                        $gotlock=flock($fp,LOCK_EX + LOCK_NB);
                        if(!$gotlock)
                        {
                                echo "cannot get lock. exiting";
                                fclose($fp);
                                exit;
                        }
                }
                else
                {
                        echo "cannot get lock. exiting";
                        exit;
                }


		$islObj = new ImageServerLog();
		$output = $islObj->fetchDataForArchiveCron($arguments["totalInstance"],$arguments["currentInstance"],$module,$months,$this->limit);
		unset($islObj);
		if($output && is_array($output))
		{
			foreach($output as $k=>$v)
			{
				$whichImage = IMAGE_SERVER_IMAGE_TYPE_ENUM::getImageType($v["IMAGE_TYPE"],$module);
				$url = $this->callImageServerApi($v["AUTOID"],trim($v[$whichImage]));
				if($url)
				{
					if($this->updateUrls($url,$v,$module))
						$this->updateImageServerTable($v["AUTOID"],IMAGE_SERVER_STATUS_ENUM::$onArchiveServer);
				}
			}
			
			if($this->errorArray && is_array($this->errorArray) && count($this->errorArray))
			{
                  			mail("lavesh.rawat@gmail.com,reshu.rajput@gmail.com","error in cloud archive cron ".$arguments["module"],implode("  |  ",$this->errorArray));
			}
		}
	}

	/*
	This function is used to make a call to image server api to transfer the image
	@param - auto increment id, url of image, type is array of type of image (image/jpeg,image/gif) and if archieve is required for the image (optional)
	@return - relative url on image server
	*/
	private function callImageServerApi($id,$url,$type='')
	{
		$serverUrl ="";
		if($url && $id)
		{
			$isaObj = new ImageServerApi;
			$serverOutput = $isaObj->generateDeleteRequestFromPid($id);
			if($serverOutput && is_array($serverOutput) && $serverOutput["urlFile"])
			{
				if($serverOutput["deleted"]=="Y")
					$serverUrl =IMAGE_SERVER_ENUM::$cloudArchiveUrl."/".$serverOutput["urlFile"];
			}
			elseif(strpos($serverOutput,"ERR_UNUSED_PID")==FALSE)
			{
				$this->errorArray[] = "AUTOID = ".$id." & ERROR = ".$serverOutput;
			}
			unset($isaObj);
		}
		return $serverUrl;
	}

	/*
	This function is used to update the urls in the picture tables of different modules
	@param - url to be updated, data array having the picture details obtained from the join of IMAGE_SERVER.LOG and the picture table of different modules,module name
	@return - true if success else false
	*/
	private function updateUrls($url,$dataArr,$module)
	{
		$whichImage = IMAGE_SERVER_IMAGE_TYPE_ENUM::getImageType($dataArr["IMAGE_TYPE"],$dataArr["MODULE_NAME"]);
		$paramArr[$whichImage] = $url;

		if($paramArr && is_array($paramArr) && $dataArr["MODULE_ID"])
		{
			$modObj = UpdateModuleTableFactory::getModuleObject($module);
			$status = $modObj->edit($paramArr,$dataArr["MODULE_ID"],$dataArr["PROFILEID"]);
			unset($modObj);
		}
		return $status;
	}

	/*
	This function is used to update the status in IMAGE_SERVER.LOG table
	@param - id,status
	*/
	private function updateImageServerTable($id,$status)
	{
		$paramArr["STATUS"] = $status;
		$islObj = new ImageServerLog;
		$islObj->updateImageServerTable($id,$paramArr);
		unset($islObj);
	}
}
?>
