<?php
/*
This class handles the interaction with the Image Server
*/
class ImageServerApi
{
	public function __construct()
	{
	}

	/*
	This function is used to send request to the image server
	@param - request message
	@return - output array from the server having pic,pid extension and url or error message
	*/
	private function sendRequestToImageToServer($reqMessage)
	{
		try
		{
			//security policy file
        		$policy_file = file_get_contents(JsConstants::$policyFilePath);
        		$policy = new WSPolicy($policy_file);


        		//Security Token contains domain specific username and password
        		$sec_token = new WSSecurityToken(array("user" => JsConstants::$username,
                                "password" => JsConstants::$password,
                                "passwordType" => JsConstants::$passwordType
                                ));

        		//WS Client Attributes to be set
        		$client = new WSClient(array("useWSA" => TRUE,
                                "useMTOM" => TRUE,
                                "policy" => $policy,
                                "securityToken" => $sec_token));

			//print_r( $reqMessage);die;

        		//send the message to serve
        		$resMessage = $client->request($reqMessage);
			//      printf("<br/> Request = %s </br>", htmlspecialchars($client->getLastRequest()));
        		//printf("<br/> Response = %s </br>", htmlspecialchars($client->getLastResponse()));
        		//printf("Response = %s \n", urldecode($resMessage->str));
  			$simplexml = new SimpleXMLElement($resMessage->str);
        		//pid Extension is optional
        		$output["pidExtension"] = trim($simplexml->pidExtension);
        		$output["pid"] = trim($simplexml->pid);
        		$output["urlFile"] = trim($simplexml->urlFile);
			if($simplexml->deleted)
				$output["deleted"] = trim($simplexml->deleted);
			return $output;
		}
		catch (Exception $e)
		{
			if ($e instanceof WSFault)
				return $this->getErrorCode($e->Reason);
			else
				return $e->getMessage();
		}
	}

	/*
        This function is used to generate upload request for the image server
        @param - auto increment picture id,url of the image,extension if instances are to be created (optional), type of image (image/jpeg,image/gif etc) (optional)
	@return - output array from the server having pic,pid extension and url or error message
        */
	public function generateUploadRequest($pid,$url,$extension='',$type='')
	{
		if(is_array($extension) && array_key_exists("archive",$extension))
                {
                        $archive="Y";
                }
                else
                {
                	 $archive="";
                }
                       
                if(is_array($extension) && array_key_exists("optimise",$extension))
                {
                	$optimise="Y";
                }
                else
                {
                	$optimise="";
                }
		$extension="";
		//content type of file uploaded
		if($type)
			$content_type = $type;
		else
		{
			$size = getimagesize($url);
			$content_type = $size["mime"];
			unset($size);
		}

		if(!$content_type)
			return $this->getErrorCode('Url is empty');
		//$content_type = 'image/gif';

		//request payload format to be sent to cloud
		$reqPayloadString = <<<XML
			<ns1:upload xmlns:ns1="JsConstants::$xml_ns">
			<pid>$pid</pid>
			<pidExtension>$extension</pidExtension>
			<archive>$archive</archive>
			<optimise>$optimise</optimise>
			<ns1:image xmlmime:contentType="$content_type" xmlns:xmlmime="http://www.w3.org/2004/06/xmlmime">
			<xop:Include xmlns:xop="http://www.w3.org/2004/08/xop/include" href="cid:myid1"></xop:Include>
			</ns1:image>
			</ns1:upload>
XML;
		//binary content of the file to be saved
		$f = file_get_contents($url);
		//$f = file_get_contents("computer.gif");
		//compose the WS(Web Service) Request Message
		$reqMessage = new WSMessage($reqPayloadString,
		array("to" => JsConstants::$toPath,
			"action" => JsConstants::$actionPathUpload,
			"attachments" => array("myid1" => $f)));

		$output = $this->sendRequestToImageToServer($reqMessage);
		return $output;
	}

	/*
        This function is used to generate url request for the image server
        @param - auto increment picture id,extension if instances are to be created (optional)
        @return - output array from the server having pic,pid extension and url or error message
        */
	public function generateUrlRequestFromPid($pid,$extension='')
	{
         	//request payload format to be sent to cloud
         	$reqPayloadString = <<<XML
            		<ns1:getPidUrl xmlns:ns1="JsConstants::$xml_ns">
            		<pid>$pid</pid>
            		<pidExtension>$extension</pidExtension>
            		</ns1:getPidUrl>
XML;

         	//compose the WS(Web Service) Request Message
         	$reqMessage = new WSMessage($reqPayloadString,
           	array("to" => JsConstants::$toPath,
                   	"action" => JsConstants::$actionPathGetPid));
		
		$output = $this->sendRequestToImageToServer($reqMessage);
                return $output;
	}

	private function getErrorCode($errText)
	{
		$errArr['ERR_IMAGE_NOT_EXIST'] = "Sorry: Specified Image does not exist";
		$errArr['ERR_DIR_NOT_CREATED'] = "Sorry: Directory could not be created";
		$errArr['ERR_FILE_EXISTS'] = "Sorry: File with this pid alread exists";
		$errArr['ERR_INVALID_TYPE'] = "Sorry: This is invalid Content Type";
		$errArr['ERR_FILE_NAME'] = "Sorry: File Name is invalid";
		$errArr['ERR_BLANK_FILE'] = "Sorry: File is blank";
		$errArr['ERR_UNUSED_PID'] = 'Sorry: This Pid is not yet used';
		$errArr['ERR_NOT_DELETE'] = "Sorry: File shoud not be deleted";
		$errArr['ERR_DELETE'] = 'Sorry: There was error while deleting';
		$errArr['ERR_IP_MISMATCH'] = 'Sorry: Requesting IP is not allowed';
		$errArr['ERR_SAVING'] = 'Sorry: Image was not saved';
		$errArr['ERR_ORIGINAL_FILE'] = 'Sorry: Original file was not saved';
		$errArr['ERR_SIZE_VARIANTS'] = 'Sorry: size variants are not correct';
		$errArr['ERR_SIZE_BORDERS'] = 'Sorry: border dimensions are not correct';
		$errArr['ERR_RESIZE'] = 'Sorry: Resizing was not successful';
		$errArr['ERR_URL_BLANK'] = 'Url is empty';

		$output = array_search($errText,$errArr);
		
		if($output)
			return $output;
		else
			return $errText;
	}


	 /*
        This function is used to delete url request for the image server
        @param pid- picture id,extension if instances are to be created (optional)
        @return - output array from the server having pic,pid extension and url or error message
        */
        public function generateDeleteRequestFromPid($pid,$extension='')
        {
                //request payload format to be sent to cloud
                $reqPayloadString = <<<XML
                        <ns1:delete xmlns:ns1="JsConstants::$xml_ns">
                        <pid>$pid</pid>
                        <pidExtension>$extension</pidExtension>
			<deleteFlag>Y</deleteFlag>
                        </ns1:delete>
XML;

                //compose the WS(Web Service) Request Message
                $reqMessage = new WSMessage($reqPayloadString,
                array("to" => JsConstants::$toPath,
                        "action" => JsConstants::$actionPathDeletePid));

                $output = $this->sendRequestToImageToServer($reqMessage);
                return $output;
        }

}
?>
