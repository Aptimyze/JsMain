<?php

/**
 * setting actions.
 * DeleteProfileV1
 * Controller to delete profile with their info data to app
 * @package    jeevansathi
 * @subpackage settings
 * @author     Prashant Pal
 */
class DeleteProfileV1Action extends sfActions 
{
	public function execute($request)
	{
		$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
	    	//print_r($loggedInProfileObj);die;
	    	//$loggedInProfileObj->getDetail("","","PROFILEID,USERNAME");
	    	$profileid = $loggedInProfileObj->getPROFILEID();
	    	if($profileid)
	    		$username = $loggedInProfileObj->getUSERNAME();
	    	elseif(!MobileCommon::isDesktop()) {
	                        $context = sfContext::getInstance();
	                        $context->getController()->forward("static", "logoutPage"); //Logout page
	                        throw new sfStopException();
	                }

		$delete_reason=$request->getParameter("deleteReason");
	    $specify_reason=$request->getParameter("specifyReason");
	    $offerConsent=$request->getParameter("offerConsent");
            if($offerConsent=='Y')
            	(new NEWJS_OFFER_CONSENT())->insertConsent($profileid);
	     
	     switch ($delete_reason) {
	     	case '1':
	     		$delete_reason = "I found my match on Jeevansathi.com";
	     		break;
	     		case '2':
	     		$delete_reason = "I found my match from other website";
	     		break;
	     		case '3':
	     		$delete_reason = "I found my match elsewhere";
	     		break;
	     		case '4':
	     		$delete_reason = "I am unhappy about services";
	     		break;
	     	default:
	     		$delete_reason = "Other reasons";
	     		break;
	     }
	     if($delete_reason=="I found my match on other website")
	        		$specify_reason="www.".trim($specify_reason).".com";
	        	
	        	if($delete_reason=="I am unhappy about services")
	        	{
	        		$msg.="Date  :  ".date("d-m-Y")."<br>";
	        		$msg.="Username  :  ".$data["USERNAME"]."<br>";
	        		$msg.="Email id  :  ".$email."<br>";
	        		if(trim($specify_reason))
	        			$msg.="Reason   : ".trim($specify_reason)."<br>";
	        		else
	        			$msg.="Reason   : No reason specified<br>";
	        		SendMail::send_email("feedback@jeevansathi.com",$msg,"Unhappy user deletes profile","info@jeevansathi.com");
	        	}

	        	if($profileid && $username && $delete_reason){
					$respObj = ApiResponseHandler::getInstance();
					$DeleteProfileObj = new DeleteProfile;
					$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);

					$respObj->setResponseBody(array("output"=>"Deleted Successfully"));
					$respObj->generateResponse();
					$DeleteProfileObj->delete_profile($profileid,$delete_reason,$specify_reason,$username);
	        		$DeleteProfileObj->callDeleteCronBasedOnId($profileid);
					
				}
				else{
					
					$respObj = ApiResponseHandler::getInstance();
					  $respObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
					  $respObj->generateResponse();
					  //print_r($a);die;
				}
	        	
				die;
	        	
		}
	}

