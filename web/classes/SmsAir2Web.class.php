<?php
/**
 * @class SmsAir2Web
 * @brief contains Sms vendor air2web API definitions
 * Find more information in http://devjs.infoedge.com/mediawiki/index.php/SMS_MODULE#New_vendor_-_air2web
 * @author Tanu Gupta
 * @created 2012-05-21
 */
 
class SmsAir2Web{
	/*private $urlArr = array(
			"SS"=>"http://luna.a2wi.co.in:7501/failsafe/HttpData_SS",
			"SM"=>"http://luna.a2wi.co.in:7501/failsafe/HttpData_SM",
			"MM"=>"http://luna.a2wi.co.in:7501/failsafe/HttpData_MM");*/
	private $fromAddress = "Jeevan";
	private $accId;
	private $pin;
	private $credentialArr;
	public function __construct()
	{
		//Credentials
		$this->credentialArr = array("transaction"=>JsConstants::$airToWebTransaction,
				"promotion"=>JsConstants::$airToWebPromotion,"OTP"=>JsConstants::$airToWebOTP);
	}

        /**
         * @fn generateXml
         * @brief Helper function for generating Request API
         * @param $uniqueId Unique identifier of sms
         * @param $destAddress Indian receiver mobile number with country code
	 * @param $messageTxt message text
	 * $param $scheuleTime Time to schedule for sending sms
         */	
	public function generateXml($uniqueId, $destAddress, $messageTxt, $scheduleTime=""){
		$messageTxt = htmlspecialchars($messageTxt,ENT_NOQUOTES); //Message text in vendor requested format
		if(strlen($destAddress) == 10) $destAddress = "91".$destAddress; //Mobile format in vendor requested format
		$this->destAddress=$destAddress;
        $this->message=$messageTxt;
		if($scheduleTime){
		$scheduleTime = date("Y/m/d/H/i",JSstrToTime($scheduleTime)); //Schedule time in vendor requested format
                $xmldata = <<<XML

<messageList>
<fromAddress>$this->fromAddress</fromAddress>
<destAddress>$destAddress</destAddress>
<messageTxt>$messageTxt</messageTxt>
<custref>$uniqueId</custref>
<scheduleTime>$scheduleTime</scheduleTime>
</messageList>
XML;
		}
		else
                $xmldata = <<<XML
<messageList>
<fromAddress>$this->fromAddress</fromAddress>
<destAddress>$destAddress</destAddress>
<messageTxt>$messageTxt</messageTxt>
<custref>$uniqueId</custref>
</messageList>
XML;
	return $xmldata;
	}

        /**
         * @fn headXml
         * @brief Helper function for generating request API
         */
	private function headXml(){
                $headXml = <<<XML
<?xml version="1.0" ?>
<a2wml version="2.0">
<request accId="$this->accId" pin="$this->pin" >
XML;
	return $headXml;
	}

        /**
         * @fn footXml
         * @brief Helper function for generating request API
         */
	private function footXml(){
		$footXml = "</request></a2wml>";
		return $footXml;
	}

        /**
         * @fn send
         * @brief Sends sms
         * @param $xml Request XML
         * @param $account transaction/promotion By default sets as transaction
         */
	public function send($xml,$account){
		//VA
		if($_SERVER['HTTP_BURP'] == "burp") return true;
		//Ends here
		/*if(strstr($_SERVER['PHP_SELF'],'symfony_index.php') || strstr($_SERVER['PHP_SELF'],'operations.php'))
		{
			$whichMachine = sfConfig::get("app_whichmachine");
		}
		else{
			include_once($_SERVER['DOCUMENT_ROOT']."/profile/config.php");
			global $whichMachine;
		}
		if($whichMachine == 'local')
			return true;
		*/
		if(!$account) $account = "transaction";
		//if(!$method) $method = "MM";
		//$this->url =  $this->urlArr[$method];
		$this->url =  $this->credentialArr[$account]["url"];
		$this->accId = $this->credentialArr[$account]["accId"];
		$this->pin = $this->credentialArr[$account]["pin"];



		if($account=='OTP'){
			$this->message=urlencode($this->message);
			$this->url=$this->url."?aid=$this->accId&pin=$this->pin&message=$this->message&mnumber=$this->destAddress";
// Set some options - we are passing in a useragent too here
	$ch = curl_init ();
                curl_setopt ( $ch, CURLOPT_URL, $this->url );
                curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1);
		if(php_sapi_name() != 'cli'){
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
			curl_setopt($ch, CURLOPT_TIMEOUT, 4);
		}


    
	// Send the request & save response to $resp
	$response = curl_exec($ch);
	// Close request to clear up some resources
	curl_close($ch);


		}

		else {
		$xml = $this->headXml().$xml.$this->footXml();
                $ch = curl_init ();
                curl_setopt ( $ch, CURLOPT_URL, $this->url );
                curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1);
		if(php_sapi_name() != 'cli'){
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
			curl_setopt($ch, CURLOPT_TIMEOUT, 4);
		}
                curl_setopt ( $ch, CURLOPT_POST, 1 );
                curl_setopt ( $ch, CURLOPT_POSTFIELDS, $xml );
                $response = curl_exec ( $ch );
                curl_close ( $ch );

		} 

		return $response;
	}
}
?>
