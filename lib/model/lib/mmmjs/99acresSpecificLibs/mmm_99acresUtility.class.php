<?php

/*
*       Name            :       mmm_99acresUtility.class.php
*       Description     :
*       Created By      :       Neha Jain
*       Created On      :       Sept 12 2013
*       Copyright 2013, Info Edge Pvt. Ltd.
*/

class mmm_99acresUtility
{

	public function getSmartyArray($response_type,$mailer_id,$pid,$mailer)
	{
		$smartyArr = array();
		if($response_type == 'sc')
		{
			$timestamp = time();
			$url_arg = base64_encode($mailer_id.'|'.$pid.'|'.$timestamp);
			$interested_url = JsConstants::$baseUrl99."/do/MMM_Response/authUser?MMM=".$url_arg;
			$smartyArr['interested_url'] = $interested_url;
			return $smartyArr;
		}
		if($response_type=='fm' || $response_type == 'bm')
		{
			$timestamp = time();
			$url_arg = base64_encode($mailer_id.'|'.$pid.'|'.$mailer['NAME'].'|'.$mailer['EMAIL'].'|'.$mailer['PHONE'].'|'.$timestamp.'|'.$response_type);
                        $interested_url = JsConstants::$baseUrl99."/do/saveFormMailerResponse/saveResponse?MMM=".$url_arg;
                        // profile id 0 was hard coded , now changed to actual profileid against ticket #2542,comment 4 last point
			$url_update_arg=base64_encode($mailer_id.'|'.$pid.'|'.$mailer['NAME'].'|'.$mailer['EMAIL'].'|'.$mailer['PHONE'].'|'.$timestamp.'|'.$response_type);
                        $updateResponse_url=JsConstants::$baseUrl99."/do/saveFormMailerResponse/saveResponse?MMM=".$url_update_arg."&update=Y";
			$smartyArr['interested_url'] = $interested_url;
			$smartyArr['update_response_url'] = $updateResponse_url;
			return $smartyArr;
		}

	}
	
	public function unsubscribeSpamButton($flag,$checksum,$pid,$isMrc='',$email=''){
		if($flag == 'U')
                {
			if($isMrc=='Y')
			{
				$pid_array = base64_decode($pid);
				$array = explode("|",$pid_array);
				$profileid =  $this->convertToNormalProfileId($array['1']);
			
	                	$URL = "/do/subscribe_unsubscribe/subscribe_unsubscribe/showUnsubscribeLayer?subscriber=$email:$profileid&source=MMM"; 
                        	$redirectURL = JsConstants::$baseUrl99.$URL;
			}
			else
			{
	                	$URL = "/do/subscribe_unsubscribe/subscribe_unsubscribe/logInUser?exclusive=N"; 
                        	$redirectURL = JsConstants::$baseUrl99."/maillink?code=".$checksum."&url=".base64_encode($URL);
			}
                        header("Location: ".$redirectURL);
                        die;
                }
                else
                {
                	$url = JsConstants::$baseUrl99."/do/MMM_Utility/markSpam";
                        $postParams = "pid=$pid";
                        $output = $this->sendCurlRequestFor99($url."?".$postParams);
                 }

	}
	public function is15DigitProfileId($profileId){
		if($profileId > 111111111111111)
			return true;

		return false;
	}		

 	function convertToNormalProfileId($profileId){
   		if($profileId > 111111111111111 )
       		return ( $profileId - 111111111111111);

   		return 0;
	}
	public static function sendCurlRequestFor99($url){
		$ch = curl_init($url);
                 $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,application/json,";
			$header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/jpeg,*/*;q=0.9";
			curl_setopt($ch, CURLOPT_HEADER, $header);
			curl_setopt($ch,CURLOPT_USERAGENT,"JsInternal");
                curl_setopt($ch, CURLOPT_POST, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 500);
                curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
                $output = curl_exec($ch);
                return $output;

	}
	function getChildCity(&$data,$typeOfCity){
		if($typeOfCity == 'city'){
			$arr = $data['city_region'];
		}else if($typeOfCity == 'seller_prop_city'){
			$arr = $data['seller_prop_city_region'];
		}else if ($typeOfCity == 'buyer_prop_city'){
			$arr = $data['buyer_prop_city_region'];
		}
		$cityStr = '';
		foreach($arr as $value){
               		switch($value){
                        case 'N':
                        	$cityStr .= "1,234,235,236,245,246,249,250,";
                        break;
			case 'S':
				$cityStr .= "20,32,38,228,224,238,227,";
			break;
			case 'E':
				$cityStr .= "25,229,230,231,237,240,241,242,243,244,247,248,226,";
			break;
			case 'W':
				$cityStr .= "12,45,19,233,225,223,";
			break;
                        
               		}
		}
		$str = substr($cityStr,0,strlen($cityStr)-1);
		$cityArr = explode(',',$str);
		$data[$typeOfCity] = $cityArr;
	}

	function parseHTML($msg,$toEmail,$mailerId){

                            $host = JsConstants::$ser2Url."/masscomm.php/mmm/redirectUrl";
                            $html = $msg;
                            $html = str_replace('&','&amp;',$html);
                            $dom = new DomDocument();
                            $dom->loadHtml($html);
                            $aTags = $dom->getElementsByTagName('a');
                            foreach ($aTags as $aElement) {
                                $href = $aElement->getAttribute('href');
                                if(substr($href,0,7) == 'mailto:' || $href == '') continue;
                                $href = $host.'?url='.urlencode($href).'&email='.$toEmail.'&mailerId='.$mailerId;
                                $aElement->setAttribute('href', $href);
                            }

                            $html = $dom->saveHtml();
                            $msg = str_replace('&amp;','&',$html);

                            return $msg;

    }

}
?>
