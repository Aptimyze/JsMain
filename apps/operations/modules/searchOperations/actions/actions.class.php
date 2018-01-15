<?php

/**
 * searchOperations actions.
 *
 * @package    jeevansathi
 * @subpackage searchOperations
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class searchOperationsActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
	public function executeSaveDppBackend(sfWebRequest $request)
	{
		$profileChecksumPassed = $request->getParameter("profileChecksum");
		if($profileChecksumPassed)
                {
                        $tempPid = JsAuthentication::jsDecryptProfilechecksum($profileChecksumPassed);
                        if($tempPid)
			{
                                $loggedInProfileObj = Profile::getInstance('newjs_master',$tempPid);
				$loggedInProfileObj->getDetail("","","GENDER,PROFILEID");
			}
                        else
                        {
                                $msg = "Invalid ID";
                        }
                }

		if($loggedInProfileObj->getPROFILEID()!='')
                {
			$SearchParamtersObj = new AdvanceSearch($loggedInProfileObj);
			$SearchParamtersObj->getSearchCriteria($request);
			$SearchParamtersObj->setSEARCH_TYPE(SearchConfig::$backendSaveDpp);
			
			if($loggedInProfileObj->getGENDER()==$SearchParamtersObj->getGENDER())
                               	$msg = "Invalid Gender";

			$UserSavedSearches = new PartnerProfile($loggedInProfileObj);
                        $success = $UserSavedSearches->saveSearchAsDpp($SearchParamtersObj);
                        if($success)
                                $msg = "Desired partner profile updated successfully!!!";
                        else
                                $msg = "Failure in updating Desired partner profile!!!";
		}
		else
		{
			$msg = "Logout";
		}
		$this->message = $msg;
	}
}
