<?php
// @package    operations
// @subpackage Dialer
// @author     Ayush

class dialerOutboundCallIvrAction extends sfActions
{
	/**
	  * Executes backend login action
	  * @param sfRequest $request A request object
	  * @return api response in JSON format
	  *
	**/
	function execute($request){

		$phone =$request->getParameter('phone');
		$phone =$this->phoneValidation($phone);
		$this->abusiveStatus 		='Y';

		if(!$phone){
			$response =$this->generateResponse();
		}else{
			$dialerObj =new DialerInbound();
			$abusive =$dialerObj->getAbusiveStatus($phone);	
			if(!$abusive){
				$this->abusiveStatus ='N';
				$optinDncObj =new incentive_OPTIN_DNC();
				$optinDncObj->addOptinRecord($phone);
			}
			if($this->abusiveStatus == 'N'){
                $profileArr = $dialerObj->excludeIncompleteInactiveDeletedProfiles($phone);
                if(is_array($profileArr)){
                    $totProfiles =count($profileArr);
                    $profileid = $profileArr[0]["PROFILEID"];
                    if($totProfiles>1){
                        $this->multipleProfile ='Y';
                        $this->returnValue = "view_".$dialerObj->getDiscountPercent($profileid);
                    }
                    else{
                        $this->multipleProfile ='N';
                        $acceptances = $dialerObj->getAcceptances($profileid);
                        if($acceptances > 0){
                            $this->returnValue = "acceptance_".$dialerObj->getDiscountPercent($profileid);
                        }else{
                            $this->returnValue = "view_".$dialerObj->getDiscountPercent($profileid);
                        }
                    }
                }
			}
			$response = $this->generateResponse();
		}
		echo $response;
		//return sfView::NONE;
		die;
	}
	public function phoneValidation($phone)
	{
		$phone = preg_replace("/[a-zA-Z!(\' ')@#$+^&*-]/", "",$phone);
		$phone = substr($phone,-10);
		if(strlen($phone)<6)
			return;
		$phoneNumeric = is_numeric($phone);
		if(!$phoneNumeric)
			return;
		return $phone;
	}
	public function generateResponse()
	{
		$responseData["MULTIPLE_PROFILE"] = $this->multipleProfile;
		$responseData["RETURN_VALUE"] = $this->returnValue;
		$responseData["ABUSIVE_STATUS"] = $this->abusiveStatus;
		$response =json_encode($responseData);
		//print_r($responseData);
		//echo $response;
		return $response;
	}

}
?>
