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
            $headers[] = 'Authorization: '.ChatEnum::$openFireAuthorizationKey;
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
                $headers[] = 'Authorization: '.ChatEnum::$openFireAuthorizationKey;
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
            //$hash = EncryptPassword::cryptoJsAesEncrypt("chat", $pass);
            //$response['hash'] = $hash;
            $response['hash'] = $pass;
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
                
                $response = array("vCard"=>$result);
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
}
?>
