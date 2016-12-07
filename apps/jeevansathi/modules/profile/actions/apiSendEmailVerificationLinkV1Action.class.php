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
            $emailUID=(new NEWJS_EMAIL_CHANGE_LOG())->getLastEntry($profileId);
            $result = (new emailVerification())->sendVerificationMail($profileId, $emailUID);
            break;
        case 2 :
            $contactNumOb=new newjs_JPROFILE_CONTACT();
            $numArray=$contactNumOb->getArray(array('PROFILEID'=>$profileId),'','',"ALT_EMAIL");
            $emailUID = (new NEWJS_ALTERNATE_EMAIL_LOG())->getLastEntry($profileId);
            $result = (new emailVerification())->sendAlternateVerificationMail($profileId, $emailUID,$numArray[0]['ALT_EMAIL']);
            break;
    }


        if($result)
        {

            $respObj->setHttpArray(ResponseHandlerConfig::$ALTERNATE_EMAIL_SUCCESS);
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
