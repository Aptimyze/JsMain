<?php

/**
 * testautomation actions.
 *
 * @package    jeevansathi
 * @subpackage testautomation
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class testautomationActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('default', 'module');
  }
  public function executeGetProfiles(sfWebRequest $request)
  {

        $loggedInDetails['GENDER']=$request->getParameter('loggedInGender');
        $loggedInDetails['ACTIVATED']=$request->getParameter('loggedInActivated');
        $loggedInDetails['INCOMPLETE']=$request->getParameter('loggedInIncomplete');
	$loggedInDetails["SUBSCRIPTION"]=JprofileParamsAllowed::getSubscriptionFromPaid($request->getParameter('loggedInPaid'));
        $otherDetails['GENDER']=$request->getParameter('otherGender');
        $otherDetails['ACTIVATED']=$request->getParameter('otherActivated');
        $otherDetails['INCOMPLETE']=$request->getParameter('otherIncomplete');
        $otherDetails["SUBSCRIPTION"]=JprofileParamsAllowed::getSubscriptionFromPaid($request->getParameter('otherPaid'));
	$newlyRegistered = $request->getParameter('newlyRegistered');
	$filtered = $request->getParameter('filtered');
	$folder = $request->getParameter('folder');
	$hardFilter = $request->getParameter('hardFilter');
	$contactStatus = $request->getParameter('contactStatus');
	$automationGeneratorObj = new AutomationDataGenerator($contactStatus,$loggedInDetails,$otherDetails,$folder,$hardFilter,$newlyRegistered);
	$testingData = $automationGeneratorObj->getTestingData();
	$this->sendResponse($testingData);
  }
  public function executeGetContactInfo(sfWebRequest $request)
  {
	$profileid1=$request->getParameter('profileid1');
	$profileid2=$request->getParameter('profileid2');

	$dbName1 = JsDbSharding::getShardNo($profileid1,'');
	$dbName2 = JsDbSharding::getShardNo($profileid2,'');
	$return = array("ERROR"=>NULL,"CONTACTS"=>NULL,"MESSAGE_LOG"=>NULL,"MESSAGES"=>NULL,"CONTACTS_ONCE"=>NULL);
	$contactsObj1 = new newjs_CONTACTS($dbName1);
	$contactsData1=$contactsObj1->getContactsDetails($profileid1,array($profileid2));
	if($dbName1!=$dbName2)
	{
		$contactsObj2 = new newjs_CONTACTS($dbName2);
		$contactsData2=$contactsObj2->getContactsDetails($profileid1,array($profileid2));
		if(array_diff_assoc($contactsData1[0],$contactsData2[0])||count($contactsData1[0])!=count($contactsData2[0]))
		{
			$return['ERROR'] = "Difference in CONTACTS table in 2 shards";
			$this->sendResponse($return);
		}
	}
	$return['CONTACTS']=$contactsData1[0];

	$messageLogObj1 = new NEWJS_MESSAGE_LOG($dbName1);
	$messageLogData1 = $messageLogObj1->getMessageLogOfProfiles($profileid1,$profileid2);
	if($dbName1!=$dbName2)
	{
		$messageLogObj2 = new NEWJS_MESSAGE_LOG($dbName2);
		$messageLogData2 = $messageLogObj2->getMessageLogOfProfiles($profileid1,$profileid2);
		if(array_diff_assoc($messageLogData1,$messageLogData2)||count($messageLogData1)!=count($messageLogData2))
		{
			$return['ERROR'] = "Difference in MESSAGE_LOG table in shards";
			$this->sendResponse($return);
		}
	}
	$return['MESSAGE_LOG'] = $messageLogData1;
	if($return['MESSAGE_LOG']['ID'])
	{
		$messagesObj1 = new NEWJS_MESSAGES($dbName1);
		$messagesData1 = $messagesObj1->Messages($return['MESSAGE_LOG']['ID']);
		if($dbName1!=$dbName2)
		{
			$messagesObj2 = new NEWJS_MESSAGES($dbName2);
			$messagesData2 = $messagesObj2->Messages($return['MESSAGE_LOG']['ID']);
			if(array_diff_assoc($messagesData1,$messagesData2)||count($messagesData1)!=count($messagesData2))
			{
				$return['ERROR'] = "Difference in MESSAGES table in shards";
				$this->sendResponse($return);
			}
		}
		$return['MESSAGES'] = $messagesData1;
	}

	$contactsOnceObj = new NEWJS_CONTACTS_ONCE;
	$return['CONTACTS_ONCE'] = $contactsOnceObj->getContactOnceInfoOfProfiles($profileid1,$profileid2);
	$this->sendResponse($return);
  }
  private function sendResponse($response)
  {
	$responseContentType = "application/json";
	header('Content-type: ' . $responseContentType);
	echo json_encode($response);
	die;
  }

   public function executeDeleteTestingContacts(sfWebRequest $request)
   {
	   $testingProfileIdsArray=array("11506347");
	   $respObj = ApiResponseHandler::getInstance();
	   if($request->getParameter("contactProfileId") && in_array($request->getParameter("contactProfileId"),$testingProfileIdsArray))
	   {
			$profileId=$request->getParameter("contactProfileId");
            if(is_numeric($profileId) === false) {
                $respObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
                $respObj->generateResponse();
                die;    
            }
 			$path = $_SERVER['DOCUMENT_ROOT']."/profile/deleteprofile_bg.php $profileId > /dev/null &";
			$cmd = JsConstants::$php5path." -q ".$path;
			passthru($cmd);
			$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
			$respObj->generateResponse();
			die;
	   }
	   else
	   {
		   $respObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
           $respObj->generateResponse();
           die;
	   }
	   
   }
}
