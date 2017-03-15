<?php
// @package    operations
// @subpackage Dialer
// @author     Manoj 

class dialerInboundCallIvrAction extends sfActions
{
	/**
	  * Executes backend login action
	  *
	  * @param sfRequest $request A request object
	**/
	function execute($request){

		$phone ='9999216910';
		$phone =$this->phoneValidation($phone);
		$this->callDivertToOnlineSales 	='Y';
		$this->callForwardToCenter 	='N';
		$this->registered 		='N';
		$this->abusiveStatus 		='Y';
		
		if(!$phone){
			$response =$this->generateResponse();
		}else{
			$dialerObj =new DialerInbound();
			$abusive =$dialerObj->getAbusiveStatus($phone);	
			if(!$abusive)
				$this->abusiveStatus ='N';	
			$profileArr =$dialerObj->getProfileDetails($phone);

			if(is_array($profileArr)){
				$totProfiles =count($profileArr);
				if($totProfiles>1){
					$this->multipleProfile ='Y';	
				}
				else{
					$this->multipleProfile ='N';
					$dataArr =$profileArr[0];
					$profileid =$dataArr['PROFILEID'];
				}	
				$this->registered ='Y';
			}
			if($this->registered && $this->multipleProfile=='N'){
				$this->dataArr = $dialerObj->formatResponseData($dataArr);
				$this->memData 	= $dialerObj->getMembershipResponseData($profileid);	
				$response	= $this->generateResponse();

				// Caching
				$this->cachProfileInfo($profileid,$phone);
			}
			$status =$dialerObj->getAbusiveStatus($phone);
			if(!$status)
				$this->abusiveStatus ='N';
		}
		echo $response;
		//return sfView::NONE;
		die;
	}
	public function phoneValidation($phone)
	{
                $phone = preg_replace("/[a-zA-Z!(\' ')@#$+^&*-]/", "",$phone);
                $phone = substr($phone,-10);
                $phoneNumeric = is_numeric($phone);
		if(!$phoneNumeric)
			return;
		return $phone;
	}
	public function generateResponse()
	{
                $responseData['call_divert_to_sales']		=$this->callDivertToOnlineSales;
		$responseData['call_forward_to_center']		=$this->callForwardToCenter;

		$responseData['ABUSIVE']	=$this->abusiveStatus;
		$responseData['PROFILEID'] 	=$this->dataArr['PROFILEID'];
		$responseData['MTONGUE'] 	=$this->dataArr['MTONGUE'];
		$responseData['REGISTERED'] 	=$this->registered;
		$responseData['MULTI_PROFILE']	=$this->multipleProfile;
		$responseData['PAID']		=$this->dataArr['SUBSCRIPTION'];
		$responseData['INFO_DTOFBIRTH ']=$this->dataArr['DTOFBIRTH'];
		$responseData['INFO_GENDER'] 	=$this->dataArr['GENDER'];
		$responseData['INFO_MSTATUS'] 	=$this->dataArr['MSTATUS'];
		$responseData['INFO_RELIGION'] 	=$this->dataArr['RELIGION'];

		$responseData['RENEWAL_ACTIVE']	=$this->memData['RENEWAL_ACTIVE'];
		$responseData['RENEWAL_DAYS'] 	=$this->memData['RENEWAL_DAYS'];

		$responseData['DISCOUNT_ACTIVE']=$this->memData['DISCOUNT_ACTIVE'];
		$responseData['DISCOUNT_PERCENT']=$this->memData['DISCOUNT_PERCENT'];
		$responseData['DISCOUNT_TEXT']   =$this->memData['DISCOUNT_TEXT'];

        	$responseData['MEMBERSHIP']	=$this->memData['MEMBERSHIP'];		
		$response =json_encode($responseData);
		print_r($responseData);
		echo $response;
		return $response;
	}
        public function cachProfileInfo($profileid,$phone){
                $JsMemcacheObj =JsMemcache::getInstance();
                $key ='sales_campaign_'.$phone;
                $profileid = $JsMemcacheObj->set($key,$profileid,86400);
                //$profileid1 = $JsMemcacheObj->get($key);
        }

}
?>
