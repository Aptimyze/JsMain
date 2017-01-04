<?php

/**
 * api actions.
 * AppRegV1
 * Controller to make user logout
 * @package    jeevansathi
 * @subpackage api
 * @author     Nikhil Dhimn
 */
class pass_changev1Action extends sfActions
{ 
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function execute($request)
	{	
		$responseData = array();
		$loginData = $request->getAttribute("loginData");
	
		$apiObj=ApiResponseHandler::getInstance();
		if($loginData[PROFILEID])
		{
			$curpass=$request->getParameter("curpass"); //Current password of user
			if(MobileCommon::isNewMobileSite() || MobileCommon::isDesktop())
			{
				$curpass=rawurldecode($curpass);
			}
			
			$newpass=$request->getParameter("newpass"); //New password of user
			if(MobileCommon::isNewMobileSite() || MobileCommon::isDesktop())
			{
				$newpass=rawurldecode($newpass);
			}
			
			//profile Obj
			$pObj = LoggedInProfile::getInstance();
                        $pObj->getDetail($loginData['PROFILEID'], "PROFILEID","PASSWORD,EMAIL");
			if(!PasswordHashFunctions::validatePassword($curpass,$pObj->getPASSWORD()))
				$responseArr=ResponseHandlerConfig::$PASSWORD_NOT_MATCH;
			elseif(!$curpass)
				$responseArr=ResponseHandlerConfig::$PASSWORD_CURRENT_EMPTY;
			elseif(!$newpass)
				$responseArr=ResponseHandlerConfig::$PASSWORD_NEW_EMPTY;
			else
			{
				$reg=array("email"=>$pObj->getEMAIL(),password=>$newpass,"_csrf_token"=>"a4ec6e42ea632a304661bc3b8a6180cd");
				$request->setParameter("reg",$reg);
				$this->form = new PageForm('', array("page" => 'CP','request' => $request), '');
				unset($reg[email]);
				$this->form->bind($reg);
				 if ($this->form->isValid()) {
					
					$now = date("Y-m-d G:i:s");
                        	        $today = date("Y-m-d");
					//Update jprofile
					$this->form->updateData($loginData[PROFILEID]);

					//Update auto expiry table
					$dbObj=new ProfileAUTO_EXPIRY;
					$expireDt=date("Y-m-d H:i:s");
					$dbObj->replace($loginData[PROFILEID],"P",$expireDt);
					$responseArr=ResponseHandlerConfig::$PASSWORD_CHANGE;
				}
				else
				{
					$responseArr=ResponseHandlerConfig::$FAILURE;
					foreach ($this->form->getFormFieldSchema() as $name => $formField)
					{
						$error=$formField->getError();
						if($error && $name=='password')
							$responseArr[message]=$error->getMessageFormat();
					}	
						
				}
			}	
		}
		if($responseArr)
			$apiObj->setHttpArray($responseArr);
		$apiObj->generateResponse();
		die;
	}
}
?>
