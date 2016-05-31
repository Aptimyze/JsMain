<?php
include_once(sfConfig::get("sf_web_dir")."/classes/class.phpmailer.php");
include_once(sfConfig::get("sf_web_dir")."/classes/class.smtp.php");

class SendMail
{
        public static $maxNumberArray = array("1"=>"5000",
                                                "2"=>"10000",
                                                "3"=>"15000",
                                                "4"=>"15000",
                                                "5"=>"25000",
                                                "6"=>"25000",
                                                "7"=>"50000",
                                                "8"=>"50000",
                                                "9"=>"100000",
                                                "10"=>"100000",
                                                "11"=>"100000",
                                                "12"=>"150000",
                                                "13"=>"150000",
                                                "14"=>"300000",
                                                "15"=>"300000",
                                                "16"=>"500000",
                                                "17"=>"500000",
                                                "18"=>"650000",
                                                "19"=>"650000",
                                                "20"=>"650000",
                                                "21"=>"900000",
                                                "22"=>"900000",
                                                "23"=>"1100000",
                                                "24"=>"1100000",
                                                "25"=>"1500000",
                                                "26"=>"1500000"
                                                );
	public static function send_email($to,$msg="",$subject="",$from="",$cc="",$bcc="",$attach="",$filetype="",$filename="",$registration="",$html="1",$reply_to="",$from_name="")
	{
                //VA
                if($_SERVER['HTTP_BURP'] == "burp") return true;
                //Ends here
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

/** warmup activity for new mailer*/
/*
			$date1 = "2016-01-11 00:00:00";
			$date2    = date("Y-m-d H:i:s");
			$diff = abs(JsCommon::dateDiff($date1,$date2));

			$randomNumber = rand(0,1000);
			$maxNumber = SendMail::$maxNumberArray[$diff];
			if($diff>26)
				$maxNumber = SendMail::$maxNumberArray[26];
			if($from=="matchalert@jeevansathi.com" && ($randomNumber<=$maxNumber || in_array($to,JsConstants::$mailAllowedArray)))
			{
					$mail->Host= JsConstants::$newMailHost.";".JsConstants::$localHostIp;
					$date = date("Y-m-d");
					file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/mailCount".$date.".txt","\n",FILE_APPEND);
			}
			else
			{
					$mail->Host= JsConstants::$mailHost.";".JsConstants::$localHostIp;
			}
*/
/** warmup activity for new mailer*/
                        $mail->Host= AssignMailHost::getMailHost($from,$to);
                        $mail->Port= JsConstants::$mailPort;

                	if($subject=="")
                        	$mail->Subject= "Info from jeevansathi.com";
                	else
                	        $mail->Subject= $subject;
                	if($from=="")
                	        $mail->From="webmaster@jeevansathi.com";
                	else
                	        $mail->From="$from";
                	//$mail->Body="$msg";
	
                	$receiver=explode(',',$to);
                	$rec_count=count($receiver);
                	do
                	{
                	        $rec_count--;
                	        $mail->AddAddress($receiver[$rec_count]);
                	}while($rec_count);

			if(trim($from_name))
                        	$mail->FromName=trim($from_name);
                	else
                		$mail->FromName="Jeevansathi.com";

                	//If reply is set.
                	if($reply_to)
                	{
                	        $mail->AddReplyTo($reply_to);
                	}
		
			if($cc != "")
                	{
                        	$CC=explode(',',$cc);
                        	$cc_count=count($CC);
                        	do
                        	{
                        	        $cc_count--;
                        	        $mail->AddCC($CC[$cc_count]);
                        	}while($cc_count);
                	}
                	if($bcc != "")
                	{
                	        $BCC=explode(',',$bcc);
                	        $bcc_count=count($BCC);
                	        do
                        	{
                        	        $bcc_count--;
                        	        $mail->AddBCC($BCC[$bcc_count]);
                        	}while($bcc_count);
                	}

                	if($attach)
                	{
                        	if(!$filename)
                        	        $filename="BILL.rtf";
                        	if(!$filetype)
                        	        $filetype='application/octet-stream';
                        	$mail->AddStringAttachment($attach,$filename,'base64',$filetype);
                	}

			if($html=='1')
                        	$mail->IsHTML(true);
                	$mail->Body=$msg;
                	if(!$mail->Send())
                	{
                        	//echo "Mailer Error: " . $mail->ErrorInfo;
                	}
                	else
                	{
                        	return true;
                	}
        	}
	}
}
?>
