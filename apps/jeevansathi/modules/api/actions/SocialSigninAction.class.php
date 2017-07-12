<?php

/**
 * /apps/jeevansathi/modules/api/actions/SocialSigninAction.class.php
 * 
 * Controller for Social Signin
 * @package    jeevansathi
 * @subpackage api
 * @author     Himanshu Gautam
 */
class SocialSigninAction extends sfActions
{ 
	
	/**
	* @const app secret for FB app
	*/
	const APPSECRET = "775d11b4ebb8dc803ff439cb59fc292a";
	/**
	* @const facebook graph api url
	*/
	const GRAPHAPIURL = "https://graph.facebook.com";
	
	/**
	* @fn generateProof
	* @brief function to generate proof to be used to hit fb graph api
	* @param $access_token - access_token from user
	*/
	private function generateProof($access_token){
		return hash_hmac('sha256', $access_token, SocialSigninAction::APPSECRET);
	}

	/**
	* @fn checkEmailDB
	* @brief function to check email from DB
	* @param $emailValue - email obtained from facebook
	*/
	private function checkEmailDB($emailValue){
		$resp = "D";
		if($emailValue){
		$checkEmailInDBobj = new JPROFILE();
		$resp = $checkEmailInDBobj->get($emailValue , /*$criteria*/"EMAIL", /*$fields*/ "ACTIVATED", /*$extraWhereClause*/ null, /*$cache*/ false)["ACTIVATED"];
		}
		return $resp ? $resp : "D";
	}
    
    /**
	* @fn hitGraphApi
	* @brief function to check email from DB
	* @param $emailValue - email obtained from facebook
	*/
    private function hitGraphApi($access_token){
    	$postParams = json_encode(array(
    		"access_token" => $access_token,
    		"appsecret_proof" => $this->generateProof($access_token),
    		"batch" => '[{"method":"GET", "relative_url":"me?fields=email"},]'
    		));
    	$headerArr = array('Content-Type:application/json');
    	return CommonFunction::sendCurlPostRequest(SocialSigninAction::GRAPHAPIURL,$postParams,"",$headerArr,"POST");
    }

    /**
	* @fn generateAuthchecksum
	* @brief function to generate authchecksum from email
	* @param $userEmail - email for which authchecksum is to be generated
	*/
	private function generateAuthchecksum($userEmail){
		$authenticationLoginObj= new AppAuthentication();
		$data = $authenticationLoginObj->createFacebookAuthCheckum($userEmail);
		return $data['AUTHCHECKSUM'];
	}

	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function execute($request)
	{	
		$access_token = $request->getParameter("access_token");
        
        // create object of apiresponsehandler
		$respObj = ApiResponseHandler::getInstance();

		// if no access_token
		if($access_token){

			$userEmail = "";
	        // get response from FB
	        $fbData = $this->hitGraphApi($access_token);
	        // check if fb response has error code
	        	$fbResp = json_decode($fbData);
	        if($fbResp->error->code){
	        	$responseData["FBcode"] = $fbResp;
	        	$responseData["is_activate"] = "D";
	        }
	        else{
	        	$fbResp = json_decode($fbData)[0];
	        	$fbResp->jsonBody = json_decode($fbResp->body);
	        	$userEmail = $fbResp->jsonBody->email;
		        $responseData["FBcode"] = $fbResp;
		        // get activated status from db
			    $responseData["is_activate"] = $this->checkEmailDB($userEmail);
			    // generating authchecksum
		        if($responseData["is_activate"] != 'D'){
		        	$authchecksum = $this->generateAuthchecksum($userEmail);
		        	if($authchecksum){
		        		$respObj->setAuthChecksum($authchecksum);
		        	}
		        }
	        }// end inner else
		}else{
			$responseData['error'] = "access_token not provided";
		}// end outer else
        
		$respObj->setHttpArray($status);
		$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
		if(is_array($responseData)) {
			$respObj->setResponseBody($responseData);	
		}
		$respObj->generateResponse();
		die();
	
    }

}

