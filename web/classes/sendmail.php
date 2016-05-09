<?php
include(dirname(__FILE__)."/class.phpmailer.php");
include(dirname(__FILE__)."/class.smtp.php");
include_once(JsConstants::$docRoot."/../lib/model/validator/InvalidEmails.php");

function sendmail($from,$to,$msg,$subject,$fromName="")
{
	if(!$from)
		$from="info@jeevansathi.com";
	
	$domain = strstr($to, '@');
	$dotpos = strrpos($domain,".");
	$domain = substr($domain,1,$dotpos-1);
	$domain = strtolower($domain);
	//check for invalid domains
	$emailArr = InvalidEmails::getInvalidSendMailArr();
	$flagValidEmail=true;
	if(in_array(strtolower($domain),$emailArr)) 
	{
		$flagValidEmail=false;
	}

	if(trim(strtolower($to))!="abc@mail.com" && !stristr($to,"@jsxyz.com") && $flagValidEmail)
        {
		$mail=new PHPMailer();
		$mail->IsSMTP();
                $mail->Host= JsConstants::$newMailHost.";".JsConstants::$localHostIp;;
                $mail->Port= JsConstants::$mailPort;


		$mail->Subject= $subject;

		$mail->From="$from";

		$receiver=explode(',',$to);
		$rec_count=count($receiver);

		do
		{
			$rec_count--;
			$mail->AddAddress($receiver[$rec_count]);
		}while($rec_count);

	        if(!$fromName)
	        {
        	        if(strstr($from,'@'))
                	{
                        	$fromarr=explode("@",$from);
	                        $fromName=$fromarr[1];
        	        }
	        }
		if($fromName)
			$mail->FromName=$fromName;

		//$mail->FromName="Jeevansathi.com";

		
		$mail->IsHTML(true);
		$mail->Body=$msg;

		if(!$mail->Send())
		{
 			echo "Mailer Error: " . $mail->ErrorInfo;
			return false;
		}
		else
		{
			return true;
		}
	
	}
}
?>
