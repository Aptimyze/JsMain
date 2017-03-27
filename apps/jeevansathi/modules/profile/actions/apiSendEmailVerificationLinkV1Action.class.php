<?php 
/**
 * @package    jeevansathi
 * @subpackage profile
 * @author     Palash Chordia
 * @date	   6th Dec 2016
 * @version    
 */
 
class apiSendEmailVerificationLinkV1Action extends sfAction 
{
	public function execute($request)	
	{
    $respObj = ApiResponseHandler::getInstance();
    $emailType = $request->getParameter('emailType');
    $loggedIn = LoggedInProfile::getInstance();
    $profileId = $loggedIn->getPROFILEID();
    if(!$emailType)
    {
    	$respObj->setHttpArray(ResponseHandlerConfig::$NO_EMAILTYPE);
	$respObj->generateResponse();
        die;
    }
    switch ($emailType)
    {

	case 1:
            $tempArray=(new NEWJS_EMAIL_CHANGE_LOG())->getLastEntry($profileId);
            $emailUID = $tempArray['ID'];
            $result = (new emailVerification())->sendVerificationMail($profileId, $emailUID);
            $email = $tempArray['EMAIL'];
            break;
        case 2 :
            $contactNumOb=new ProfileContact();
            $numArray=$contactNumOb->getArray(array('PROFILEID'=>$profileId),'','',"ALT_EMAIL");
            $tempArray = (new NEWJS_ALTERNATE_EMAIL_LOG())->getLastEntry($profileId);
            $emailUID =  $tempArray['ID'];
            $result = (new emailVerification())->sendAlternateVerificationMail($profileId, $emailUID,$numArray[0]['ALT_EMAIL']);
            $email = $tempArray['EMAIL'];
            break;
    }


        if($result)
        {

            $respObj->setHttpArray(str_replace('{email}',$email,ResponseHandlerConfig::$ALTERNATE_EMAIL_SUCCESS));
            $respObj->generateResponse();
            die;
        }
        else 
        {
        
        if(!$emailUID)
            {
            $response=ResponseHandlerConfig::$ALTERNATE_EMAIL_ID_NOT_FOUND;
            }
        else $response = ResponseHandlerConfig::$FAILURE;
        $respObj->setHttpArray($response);
	$respObj->generateResponse();
        die;
       }
    }
	
}
?>
