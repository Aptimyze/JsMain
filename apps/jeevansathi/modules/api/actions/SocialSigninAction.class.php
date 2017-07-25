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
	const APPSECRET = "5b3a858a007757d76aab1bb9497a25ae";//"775d11b4ebb8dc803ff439cb59fc292a";
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
			"batch" => '[{"method":"GET", "relative_url":"me?fields=email,birthday,name,gender,first_name,location,albums{photos{picture}},relationship_status,last_name,middle_name,picture,permissions,friends,interested_in,languages,education,locale,is_verified,religion"},]'
			));
		$headerArr = array('Content-Type:application/json');
		return CommonFunction::sendCurlPostRequest(SocialSigninAction::GRAPHAPIURL,$postParams,"",$headerArr,"POST");
	}

	/**
	* @fn generateAuthchecksum
	* @brief function to generate authchecksum from email
	* @param $userEmail - email for which authchecksum is to be generated
	*/
	private function generateLoginData($userEmail,$registrationid){
		$authenticationLoginObj= new AppAuthentication();
		$data = $authenticationLoginObj->createFacebookAuthCheckum($userEmail);
		if(CommonFunction::getMainMembership($data[SUBSCRIPTION]))
					$subscription=CommonFunction::getMainMembership($data[SUBSCRIPTION]);
				else
					$subscription="";
		$done = NotificationFunctions::manageGcmRegistrationid($registrationid,$data['PROFILEID'])?"1":"0";
		$notificationStatus = NotificationFunctions::settingStatus($registrationid,$data['PROFILEID']);				
		$loginData=array("GENDER"=>$data[GENDER],
						"USERNAME"=>$data[USERNAME],
						"INCOMPLETE"=>$data[INCOMPLETE],
						"SUBSCRIPTION"=>$subscription,
						"LANDINGPAGE"=>'1',
						"GCM_REGISTER"=>$done,
						"NOTIFICATION_STATUS"=>$notificationStatus,
						"RELIGION"=>$data[RELIGION], 
						"AUTHCHECKSUM" => $data[AUTHCHECKSUM]);
		return $loginData;
	}

	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function execute($request)
	{	
		$access_token = $request->getParameter("access_token");
		$registrationid=$request->getParameter("registrationid");

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
				$responseData["FBresp"] = $fbResp;
				$respObj->setHttpArray(ResponseHandlerConfig::$INVALID_ACCESS_TOKEN);
			}
			else{
				$fbResp = json_decode($fbData)[0];
				$fbResp->jsonBody = json_decode($fbResp->body);
				$userEmail = $fbResp->jsonBody->email;
				$responseData["FBresp"] = $fbResp;
				// get activated status from db
				$responseData["is_activate"] = $this->checkEmailDB($userEmail);
				// generating authchecksum
				if($responseData["is_activate"] != 'D'){
					$loginData = $this->generateLoginData($userEmail,$registrationid);
					if($loginData){
						$respObj->setAuthChecksum($loginData['AUTHCHECKSUM']);
						$responseData["GENDER"]           = $loginData["GENDER"];
						$responseData["USERNAME"]         = $loginData["USERNAME"];
						$responseData["INCOMPLETE"]       = $loginData["INCOMPLETE"];
						$responseData["GCM_REGISTER"]     = $loginData["GCM_REGISTER"];
						$responseData["LANDINGPAGE"]      = $loginData["LANDINGPAGE"];
						$responseData["SUBSCRIPTION"]     = $loginData["SUBSCRIPTION"];
						$responseData["NOTIFICATION_STATUS"] = $loginData["NOTIFICATION_STATUS"];
						$responseData["RELIGION"]         = $loginData["RELIGION"];
					}



				}
				$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
			}// end inner else
		}else{
			$respObj->setHttpArray(ResponseHandlerConfig::$NO_ACCESS_TOKEN);
		}// end outer else
		
		$respObj->setHttpArray($status);
		if(is_array($responseData)) {
			$respObj->setResponseBody($responseData);	
		}
		$respObj->generateResponse();
		die();
	
	}

}

