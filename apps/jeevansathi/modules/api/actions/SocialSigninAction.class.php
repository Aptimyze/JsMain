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
	* @var private string app secret for FB app
	*/
	//TODO : Move to ENUM or CONST
	private $app_secret = "775d11b4ebb8dc803ff439cb59fc292a";
	
	/**
	* @fn generateProof
	* @brief function to generate proof to be used to hit fb graph api
	* @param $access_token - access_token from user
	*/
	private function generateProof($access_token){
		return hash_hmac('sha256', $access_token, $this->app_secret);
	}

	/**
	* @fn checkEmailDB
	* @brief function to check email from DB
	* @param $emailValue - email obtained from facebook
	*/
	private function checkEmailDB($emailValue){
		$resp = "N";
		if($emailValue){
		$checkEmailInDBobj = new JPROFILE();
		$resp = $checkEmailInDBobj->get($emailValue , /*$criteria*/"EMAIL", /*$fields*/ "ACTIVATED", /*$extraWhereClause*/ null, /*$cache*/ false)["ACTIVATED"];
		}
		return $resp ? $resp : "N";
	}
    
    /**
	* @fn hitGraphApi
	* @brief function to check email from DB
	* @param $emailValue - email obtained from facebook
	*/
    private function hitGraphApi($access_token){


    	$Url = "https://graph.facebook.com"; // TODO : Move to Enum or constant
    	$postParams = json_encode(array(
    		"access_token" => $access_token,
    		"appsecret_proof" => $this->generateProof($access_token),
    		"batch" => '[{"method":"GET", "relative_url":"me"},]'
    		));
    	$headerArr = array('Content-Type:application/json');
    	return CommonFunction::sendCurlPostRequest($Url,$postParams,"",$headerArr,"POST");
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
		if(!$access_token) return; // TODO:// Handle Failure Case, return failure response
		// $access_token = "EAAIUo67ZBFGEBAN8B7bYgnv8Sh8pFuZC0LPmk1VQAEnPEBlrWJZCHZArVSBZCpFTYUZCikcPgTyhtyKhkXiIC4CvvFNLQZBAOwr7QSWAFr4WG5aO4oEmj7oNOCtwqHb2Mpr8XTxQl6UhglCuB5XvpzbWebDGEiTlJfP9dEg6VXJg1Au6qfIbySZAMNLc4p0tUm4ZD";
        
        
		$userEmail = "";
        // get response from FB
        $fbData = $this->hitGraphApi($access_token);
        $fbResp = json_decode($fbData);
        // jsonifying ugly parts
        if(!$fbResp->error->code){//TODO : Use isset or isempty function for better readabilty of the code
        	$fbResp = json_decode($fbData)[0];
        	$fbResp->jsonBody = json_decode($fbResp->body);
        	$userEmail = $fbResp->jsonBody->email;
        }
        
        $respReturn = $fbResp;
       
        $responseData["FBcode"] = $respReturn;
        
        // $gotEmail = json_decode(json_decode($fbResp)[0]->body)->email;
        // check email from db matching
        $responseData["is_activate"] = $this->checkEmailDB($userEmail);

        // create object of apiresponsehandler
		$respObj = ApiResponseHandler::getInstance();

		// create object of appauthentication and generate authchecksum
        if($responseData["is_activate"] != 'D'){
        	
        	$respObj->setAuthChecksum($this->generateAuthchecksum($userEmail));
        }
		
		$respObj->setHttpArray($status);
		$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
		if(is_array($responseData)) {
			$respObj->setResponseBody($responseData);	
		}
		
		$respObj->generateResponse();
		die();
	
    }

}

