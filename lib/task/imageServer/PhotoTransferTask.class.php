<?php
ini_set("max_execution_time",0);
ini_set("memory_limit","512M");
ini_set("mysql.connect_timeout",-1);
ini_set("default_socket_timeout",259200); // 3 days
ini_set("log_errors_max_len",0);

/*
 * Author: Kumar Anand
 * This cron is used to transfer the images(present in modules like picture and success story) to image server
*/

class PhotoTransferTask extends sfBaseTask
{
	private $limit = 1000;
	private $errorArray = array();

	protected function configure()
        {

                $this->addArguments(array(
                new sfCommandArgument('totalInstance', sfCommandArgument::REQUIRED, 'My argument'),new sfCommandArgument('currentInstance', sfCommandArgument::REQUIRED, 'My argument'),new sfCommandArgument('module', sfCommandArgument::REQUIRED, 'My argument')
                ));

                $this->addOptions(array(
                new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
             ));

            $this->namespace        = 'cron';
            $this->name             = 'PhotoTransfer';
            $this->briefDescription = 'transfers images to image server';
            $this->detailedDescription = <<<EOF
        This cron runs periodically and is used to transfer the images to the image server.
        Call it with:

          [php symfony cron:PhotoTransfer totalInstance currentInstance module]
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
		if(!$module)
			die("Invalid Module Arguement");
		
                $fp=fopen('/tmp/PhotoTransferTask_'.$module.$arguments["currentInstance"]. ".lock","w+");
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


		$islObj = new ImageServerLog;
		$output = $islObj->fetchDataForCron($arguments["totalInstance"],$arguments["currentInstance"],$module,$this->limit);
		unset($islObj);
		if($output && is_array($output))
		{
			foreach($output as $k=>$v)
			{
				$whichImage = IMAGE_SERVER_IMAGE_TYPE_ENUM::getImageType($v["IMAGE_TYPE"],$module);
				
				if($module == "PICTURE" && $whichImage=="OriginalPicUrl")
                                {
				       	$type = array("archive"=>1,"optimise"=>'Y');
								$serverEnum = IMAGE_SERVER_STATUS_ENUM::$onArchiveServer;
				}
				elseif($module == "PICTURE_DELETED")
                                {
				       	$type = array("archive"=>1);
								$serverEnum = IMAGE_SERVER_STATUS_ENUM::$onArchiveServer;
				}
        else
				{
					$serverEnum = IMAGE_SERVER_STATUS_ENUM::$onImageServer;
					$type="";
				}
			
				$url = $this->callImageServerApi($v["AUTOID"],trim($v[$whichImage]),$type);
				if($url)
				{
					if($this->updateUrls($url,$v,$module))
						$this->updateImageServerTable($v["AUTOID"],$serverEnum);
				}
				else{
					if($module == "PICTURE_DELETED"){
						$serverEnum = IMAGE_SERVER_STATUS_ENUM::$deleted;
						$this->updateImageServerTable($v["AUTOID"],$serverEnum);
					}
				}
			}
			
			if($this->errorArray && is_array($this->errorArray) && count($this->errorArray))
			{
				if($arguments["module"] == "SUCCESS_STORY")
                  			mail("lavesh.rawat@gmail.com,reshu.rajput@gmail.com,nikcomestotalk@gmail.com","error in cloud cron ".$arguments["module"],implode("  |  ",$this->errorArray));
				else
                  			mail("lavesh.rawat@gmail.com,reshu.rajput@gmail.com","error in cloud cron ".$arguments["module"],implode("  |  ",$this->errorArray));
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
		if($url)
		{
			$url = PictureFunctions::getCloudOrApplicationCompleteUrl($url);
			$isaObj = new ImageServerApi;
			
			$serverOutput = $isaObj->generateUploadRequest($id,$url,$type);
			if($serverOutput && is_array($serverOutput))
			{
				if($serverOutput["urlFile"])
				{
					$server = $this->getServerValue($type);//is_array($type)?IMAGE_SERVER_ENUM::$cloudArchiveUrl:IMAGE_SERVER_ENUM::$cloudUrl;// make a function call
					$serverUrl = $server."/".$serverOutput["urlFile"];
				}	
			}
			else
			{
				if($serverOutput == "ERR_FILE_EXISTS")
                                {
					$serverOutput1 = $isaObj->generateUrlRequestFromPid($id);
					if($serverOutput1 && is_array($serverOutput1))
                        		{
						if($serverOutput1["urlFile"])
						{
                                		        $server = $this->getServerValue($type);//is_array($type)?IMAGE_SERVER_ENUM::$cloudArchiveUrl:IMAGE_SERVER_ENUM::$cloudUrl;
                                        		$serverUrl = $server."/".$serverOutput1["urlFile"];
        	                        	}
	
                        		}
					else
					{
						$this->errorArray[] = "AUTOID = ".$id." & ERROR = ".$serverOutput." AND ".$serverOutput1;
					}
                                }
				elseif($serverOutput == "ERR_URL_BLANK")
				{
					$this->updateImageServerTable($id,IMAGE_SERVER_STATUS_ENUM::$invalid);
					$this->errorArray[] = "AUTOID = ".$id." & ERROR = ".$serverOutput;
				}
                                else
                                {
					$this->errorArray[] = "AUTOID = ".$id." & ERROR = ".$serverOutput;
                                }
			}
			unset($isaObj);
		}
		else
		{
			$this->updateImageServerTable($id,IMAGE_SERVER_STATUS_ENUM::$deleted);
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
	 private function getServerValue($type)
	 {
	 	if(is_array($type) && array_key_exists("archive",$type) && !array_key_exists("optimise",$type))
	 	{
	 		$source = IMAGE_SERVER_ENUM::$cloudArchiveUrl;
	 	}
	 	else
	 	{
	 		$source = IMAGE_SERVER_ENUM::$cloudUrl;
	 	}
	 	return $source;
	 }
}
?>
