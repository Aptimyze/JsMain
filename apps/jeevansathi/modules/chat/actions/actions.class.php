<?php

/**
 * chat actions.
 *
 * @package    jeevansathi
 * @subpackage chat
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class chatActions extends sfActions
{
	/**
	* Executes authenticateChatSession action  - returns jid,sid and rid for chat session
	*
	* @param sfRequest $request A request object
	*/
 	public function executeAuthenticateChatSessionV1(sfWebRequest $request)
 	{
		$xmppPrebind = new XmppPrebind('localhost', 'http://localhost:7070/http-bind/', 'converse', false, false);
		$username = substr("a1@localhost", 0,2);
		$xmppPrebind->connect($username, '123');
		$xmppPrebind->auth();
		$response = $xmppPrebind->getSessionInfo(); // array containing sid, rid and jid

		$apiResponseHandlerObj = ApiResponseHandler::getInstance();
		$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
		$apiResponseHandlerObj->setResponseBody($response);
		$apiResponseHandlerObj->generateResponse();
		die;
 	}
    
    public function executeChatUserAuthenticationV1(sfWebRequest $request)
    {
        $apiResponseHandlerObj = ApiResponseHandler::getInstance();
        $loginData = $request->getAttribute("loginData");
        if($loginData){

            $username = $loginData['PROFILEID'];

		$uname = $loginData['USERNAME'];
	    $pass = EncryptPassword::generatePassword($uname);

            $url = JsConstants::$openfireConfig['HOST'].":".JsConstants::$openfireConfig['PORT']."/plugins/restapi/v1/users/".$username;
            //$url = "http://localhost:9090/plugins/restapi/v1/users/".$username;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
            curl_setopt($ch, CURLOPT_TIMEOUT, 4);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

            $headers = array();
            $headers[] = 'Authorization: '.JsConstants::$openfireRestAPIKey;
            $headers[] = 'Accept: application/json';

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $curlResult = curl_exec ($ch);
            curl_close ($ch);
            $result = json_decode($curlResult, true);
            if($result['username']){
                //User exists
                $response['userStatus'] = "User exists";
                $apiResponseHandlerObj->setHttpArray(ChatEnum::$userExists);
            }
            else{
                //create user
                $response['userStatus'] = "New user created";
                $url = JsConstants::$openfireConfig['HOST'].":".JsConstants::$openfireConfig['PORT']."/plugins/restapi/v1/users/";
                //$url = "http://localhost:9090/plugins/restapi/v1/users/";
                $data = array("username" => $username, "password" => $pass);
                $jsonData = json_encode($data);
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
                curl_setopt($ch, CURLOPT_TIMEOUT, 4);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

                $headers = array();
                $headers[] = 'Authorization: '.JsConstants::$openfireRestAPIKey;
                $headers[] = 'Accept: application/json';
                $headers[] = 'Content-Type: application/json';

                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $curlResult = curl_exec ($ch);
                
                if(curl_getinfo($ch, CURLINFO_HTTP_CODE) == '201'){
                    $response['userStatus'] = "New user created";
                    $apiResponseHandlerObj->setHttpArray(ChatEnum::$newUserCreated);
                }
                elseif(curl_getinfo($ch, CURLINFO_HTTP_CODE) == '409'){
                    $response['userStatus'] = "User Exists";
                    $apiResponseHandlerObj->setHttpArray(ChatEnum::$userCreationError);
                }
                else{
                    $result = json_decode($curlResult, true);
                    $reponse['exception'] = $result['exception'];
                    $apiResponseHandlerObj->setHttpArray(ChatEnum::$error);
                }
                curl_close ($ch);
            }
            //Encrypt Password
            $hash = EncryptPassword::cryptoJsAesEncrypt("chat", $pass);
            $response['hash'] = $hash;
            //$response['hash'] = $pass;
        }
        else{
            $response = "Logged Out Profile";
            $apiResponseHandlerObj->setHttpArray(ChatEnum::$loggedOutProfile);
        }
        $apiResponseHandlerObj->setResponseBody($response);
        $apiResponseHandlerObj->generateResponse();
        die;
    }
    
    public function executeFetchCredentialsV1(sfWebRequest $request)
    {
        $apiResponseHandlerObj = ApiResponseHandler::getInstance();
        $loginData = $request->getAttribute("loginData");
        if($loginData){
            //$username = $loginData['USERNAME'];
            //$jid = $username."@localhost";
            $jid = $request->getParameter('jid'); //Will be commented later nitish
            $response['jid'] = $jid;
            $response['password'] = '123';
            $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
        }
        else{
            $response = "Logged Out Profile";
            $apiResponseHandlerObj->setHttpArray(ChatEnum::$loggedOutProfile);
        }
        $apiResponseHandlerObj->setResponseBody($response);
        $apiResponseHandlerObj->generateResponse();
        die;
    }
    
    public function executeFetchVCardV1(sfwebrequest $request){
        $apiResponseHandlerObj = ApiResponseHandler::getInstance();
        $loginData = $request->getAttribute("loginData");
        if($loginData){
            $jid = $request->getParameter('jid');
            if(is_array($jid)){
                foreach($jid as $key => $val){
                    $username.=$val.",";
                }
                $username = rtrim($username,",");
                
                $vcardDetailsObj = new chat_ofVcard();
                $storeResult = $vcardDetailsObj->getVCardDetails($username);
                unset($vcardDetailsObj);
                
                $chatObj = new Chat();
                $result = $chatObj->convertXml($storeResult);
                unset($chatObj);
                $username = $request->getParameter('username');
                $profile["$username"]["NAME"] = "Atul";
                $profile["$username"]["EMAIL"] = "Atul@gmail.com";
                $profile["$username"]["PHOTO"] = "http://mediacdn.jeevansathi.com/1769/6/35386110-1436589041.jpeg";
                $profile["$username"]["AGE"] = "3";
                $profile["$username"]["HEIGHT"] = "5 9";
                $profile["$username"]["PROFFESION"] = "Christian";
                $profile["$username"]["SALARY"] = "Rs. 15 - 20lac";
                $profile["$username"]["CITY"] = "New Delhi";
                $d1["action"] = "INITIATE";
                $d1["label"] = "Send Interest";
                $d1["iconid"] = null;
                $d1["primary"] = "true";
                $d1["secondary"] = "true";
                $d1["params"] = "&stype=P17";
                $d1["enable"] = true;
                $d1["id"] = "INITIATE";
                $buttons["buttons"][] = $d1;
                
                $profile["$username"]["buttonDetails"] = $buttons;
                $response = array("vCard"=>$profile);
                
                $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
            }
            else{
                $apiResponseHandlerObj->setHttpArray(ChatEnum::$invalidFormat);
            }
        }
        else{
            $response = "Logged Out Profile";
            $apiResponseHandlerObj->setHttpArray(ChatEnum::$loggedOutProfile);
        }
        $apiResponseHandlerObj->setResponseBody($response);
        $apiResponseHandlerObj->generateResponse();
        die;
    }


    public function executeGetRosterDataV1(sfwebrequest $request){
	    $profileid = $request->getParameter("profileid");
	    $type = $request->getParameter("type");
	    $limit = $request->getParameter("limit");
	    $getRosterDataObj = new GetRosterData($profileid);
	    $getData["profiles"] = $getRosterDataObj->getRosterDataByType($type,$limit);
	    $apiResponseHandlerObj = ApiResponseHandler::getInstance();
	    $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
	    
$apiResponseHandlerObj->setResponseBody($getData);
	    $apiResponseHandlerObj->generateResponse();
	    die;
    }

    public function executeGetProfileDataV1(sfwebrequest $request){
        $apiResponseHandlerObj = ApiResponseHandler::getInstance();
        $loginData = $request->getAttribute("loginData");
        if($loginData){

		$profileid     = JsCommon::getProfileFromChecksum($request->getParameter("profilechecksum"));
		//$profileid = $request->getParameter("profileid");
		$profile = new Profile();
		$profile->getDetail($profileid, "PROFILEID","*");


                        //Photo logic
                        $pidArr["PROFILEID"] =$profileid;
                        $photoType = 'MainPicUrl';
                        $profileObj=LoggedInProfile::getInstance('newjs_master',$loginData["PROFILEID"]);
                        $multipleProfileObj = new ProfileArray();
                        $profileDetails = $multipleProfileObj->getResultsBasedOnJprofileFields($pidArr);
                        $multiplePictureObj = new PictureArray($profileDetails);
                        $photosArr = $multiplePictureObj->getProfilePhoto();
			$photo = '';
			$photoObj = $photosArr[$profileid];
			if($photoObj)
			{
				eval('$temp =$photoObj->get'.$photoType.'();');
				$photo = $temp;
				unset($temp);
			}
                        //Ends here


		$response = array(
				"username"=>$profile->getUSERNAME(),
				"age"=>$profile->getAGE()." Years",
				"height"=>$profile->getDecoratedHeight(),
				"religion"=>$profile->getDecoratedReligion(),
				"caste"=>$profile->getDecoratedCaste(),
				"mtongue"=>$profile->getDecoratedCommunity(),
				"education"=>$profile->getDecoratedEducation(),
				"occupation"=>$profile->getDecoratedOccupation(),
				"income"=>$profile->getDecoratedIncomeLevel(),
				"city"=>$profile->getDecoratedCity(),
				"photo"=>$photo
				);
            $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);

        }
        else{
            $response = "Logged Out Profile";
            $apiResponseHandlerObj->setHttpArray(ChatEnum::$loggedOutProfile);
        }
        $apiResponseHandlerObj->setResponseBody($response);
        $apiResponseHandlerObj->generateResponse();
        die;

    }

	public function executeGetDppDataV1(sfwebrequest $request)
	{
		$profileid = $request->getParameter("profileid");
		$photoType = $request->getParameter("photoType");
		$limit = $request->getParameter("limit");
		$currentPage = $request->getParameter("currentPage");
		$dontShowFilteredProfiles = $request->getParameter("dontShowFilteredProfiles");

		/***/
		if(!$photoType)
			$photoType = 'MainPicUrl';
		if(!$dontShowFilteredProfiles)
			$dontShowFilteredProfiles = 1;
		if(!$limit)
			$limit = 10;
		if(!$currentPage)
			$currentPage = 1;
		$completeResponse = 1;
		/***/

		$profileObj = LoggedInProfile::getInstance('',$profileid);
		$profileObj->getDetail('','','*');
		$partnerObj = new SearchCommonFunctions();


		$obj = $partnerObj->getMyDppMatches(sort,$profileObj,$limit,$currentPage,$paramArr,$removeMatchAlerts,$dontShowFilteredProfiles,$twoWayMatches,$clustersToShow,$results_orAnd_cluster,$notInProfiles,$completeResponse);
		$arr = $obj->getResultsArr();
		if($arr)
		{
			$pidArr["PROFILEID"] = implode(",",$obj->getSearchResultsPidArr());
			$profileObj=LoggedInProfile::getInstance('newjs_master');
			$multipleProfileObj = new ProfileArray();

			$profileDetails = $multipleProfileObj->getResultsBasedOnJprofileFields($pidArr);
			$multiplePictureObj = new PictureArray($profileDetails);
			$photosArr = $multiplePictureObj->getProfilePhoto();
			foreach($arr as $k=>$v)
			{
				$pid = $v["id"];
				$cArr[$pid]["USERNAME"] = $v["USERNAME"];
				$cArr[$pid]["PROFILECHECKSUM"] = jsAuthentication::jsEncryptProfilechecksum($pid);
				$photoObj = $photosArr[$pid];
				if($photoObj)
				{
					eval('$temp =$photoObj->get'.$photoType.'();');
					$cArr[$pid]["PHOTO"] = $temp;
					unset($temp);
				}
				else
				{
					$cArr[$pid]["PHOTO"] = NULL;
				}

			}
		}
		$getData["profiles"] = $cArr;
		$apiResponseHandlerObj = ApiResponseHandler::getInstance();
		$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
		$apiResponseHandlerObj->setResponseBody($getData);
		$apiResponseHandlerObj->generateResponse();
		die;
	}
}
?>
