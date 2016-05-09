<?php
/************************************************************************************************************************
*    FILENAME           : sms_inc.php
*    DESCRIPTION        : 1)validation of mobile
			  2)correct formatting of mobile number.
			  3)To send sms.
*    CREATED BY         : lavesh
***********************************************************************************************************************/
function valid_gsm_no($mobile,$formating=0)
//Check whether a given no. is valid gsm no. or not.
{
	if($formating)
	{
		if(strlen($mobile)==10)
			$mobile='91'.$mobile;
	}
	if( strlen($mobile)!=12 || !(ctype_digit($mobile)) )
		return 0;
	else
	{
		if((substr($mobile,2,2)=='94' or substr($mobile,2,2)=='96' or substr($mobile,2,2)=='97' or substr($mobile,2,2)=='98' or substr($mobile,2,2)=='99'))
                        return 1;
	}
	return 0;
}

function valid_cdma_no($mobile)
{

	if( strlen($mobile)!=12 || !(ctype_digit($mobile)) )
                return 0;
        else
        {
                if( substr($mobile,2,2)=='92' or substr($mobile,2,2)=='93')
                        return 1;
        }
        return 0;

}

function mobile_correct_format($mobile)
//This function convert the given mobile number into proper format of 12 digit mobile no.
{
	$mobile=str_replace(' ','',$mobile);
	$mobile=str_replace('-','',$mobile);

	if( substr($mobile,0,1)=='0' || substr($mobile,0,1)=='+' )	
	{
		if(strlen($mobile)==11)
			$mobile=substr_replace($mobile,'91',0,1);
		elseif(strlen($mobile)==13)
			$mobile=substr($mobile,1,12);
	}
	elseif(strlen($mobile)==10)
		$mobile='91'.$mobile;

	return $mobile;
}

function send_sms($message,$from,$mobile,$profileid,$gsm,$table='',$encode_message='')
//message should be less than 160 characters.
//from is sender mobile number.
//$mobile is receiver mobile no.
//gsm=1 if sender is a valid gsm no.
{
	$mobile=mobile_correct_format($mobile);

	if($gsm)
	{
		$rec_is_correct1=valid_gsm_no($mobile);
		$rec_is_correct2=valid_cdma_no($mobile);
		if($rec_is_correct1  || $rec_is_correct2)
			$rec_is_correct=1;
	}
	else
	{	
		$rec_is_correct=valid_gsm_no($mobile);
	}

        if($encode_message=='Y')
                $message=urlencode($message);
	
	if(valid_gsm_no($mobile))
                $from="Jeevansathi";

	$checkmobile = smsinc_checkmphone($mobile);
														     
	if($rec_is_correct && !$checkmobile)
	{
		if($message && $from && $mobile && $profileid)
		{
			$sql="SET SESSION wait_timeout=500";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$xml_content="";
			$i = 0;
			$xml_head="%3C?xml%20version=%221.0%22%20encoding=%22ISO-8859-1%22?%3E%3C!DOCTYPE%20MESSAGE%20SYSTEM%20%22http://127.0.0.1/psms/dtd/message.dtd%22%3E%3CMESSAGE%3E%3CUSER%20USERNAME=%22naukari%22%20PASSWORD=%22na21s8api%22/%3E";
			$xml_content.="%3CSMS%20UDH=%220%22%20CODING=%221%22%20TEXT=%22$message%22%20PROPERTY=%220%22%20ID=%22$profileid%22%3E%3CADDRESS%20FROM=%22$from%22%20TO=%22$mobile%22%20SEQ=%22$profileid%22%20TAG=%22%22/%3E%3C/SMS%3E";
															     
			$xml_end="%3C/MESSAGE%3E";
			$xml_code=$xml_head.$xml_content.$xml_end;
			$fd=@fopen("http://api.myvaluefirst.com/psms/servlet/psms.Eservice2?data=$xml_code&action=send","rb");                       
			if($fd)
			{
				$response = '';
				while (!feof($fd))
				{
					$response.= fread($fd, 4096);
				}
				fclose($fd);
				$ts=time();
				$today=date('Y-m-d',$ts);
			}
			if(strpos($response,"Expired"))
			{
				$msg=$response;
				$subject="SMS quota full";
				$to="vikas.jayna@jeevansathi.com";
				//send_email($to,$msg,$subject);
				$to="aman.sharma@jeevansathi.com";
				//send_email($to,$msg,$subject);
				return 0;
			}
				

			$ts=time();
			$today=date('Y-m-d H:i:s',$ts);
			if($table)
			{
				if($table=="newjs.SENT_VERIFICATION_SMS")
					$sql="INSERT INTO $table VALUES('','$profileid','$response','$today','$mobile')";
				else
					$sql="INSERT INTO $table VALUES('$profileid','$response','$today')";
					mysql_query_decide($sql) or die($sql.mysql_error_js());
			}
			
			return 1;//Valid mobile.
		}
	}
	return 0;//invalid mobile.
}

function smsinc_checkmphone($phone)     // returns 1 if phone no. is not valid
{
        $flag=0;
        if( trim($phone) =='')
        {
                $flag=1;
        }

        elseif (!ereg("^[+]?[0-9]+$", $phone))
        {
                $flag=1;
        }

        return $flag;
}

/*
Function send_message to send multiple sms in one http request through post method
Author          : Sadaf Alam

Parameters 
-----------
@msgarray       : Array of arrays each containing receiver mobile number,message and profileid in TO,MSG and PID
@from           : sender number
@gsm            : 1 if gsm numbers are also present
@table          : 1 if entries of each sms is to be put into a table
@encode_message : if the details of sms is to be encoded
*/

function send_message($msgarray,$from,$gsm,$table,$encode_message="Y")
{
	$orig_from=$from;
	if(!is_array($msgarray))
		return 0;

	$cnt=count($msgarray);
	$xmldata = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><!DOCTYPE MESSAGE SYSTEM \"http://127.0.0.1/psms/dtd/messagev12.dtd\" ><MESSAGE VER=\"1.2\"><USER USERNAME=\"naukari\" PASSWORD=\"na21s8api\"/>";
	for($i = 0; $i<$cnt;$i++) 
	{
        	$phone = $msgarray[$i]["TO"];
		$phone=mobile_correct_format($phone);
		if($gsm)
		{
			$rec_is_correct1=valid_gsm_no($phone);
			$rec_is_correct2=valid_cdma_no($phone);
			if($rec_is_correct1  || $rec_is_correct2)
				$rec_is_correct=1;
		}
		else
		{
			$rec_is_correct=valid_gsm_no($phone);
		}
		if(valid_gsm_no($phone))
	                $from="Jeevansathi";
		else
			$from=$orig_from;
		
		$checkmobile = smsinc_checkmphone($phone);

		if($rec_is_correct && !$checkmobile)
	        {
			if($msgarray[$i]["MSG"] && $from && $phone)
			{
				$seq = $i;
				$msg=$msgarray[$i]["MSG"];
				$count++;
				$xmldata.="<SMS  UDH=\"0\" CODING=\"1\" TEXT=\"$msg\" PROPERTY=\"0\" ID=\"$seq\"><ADDRESS FROM=\"$from\" TO=\"$phone\" SEQ=\"1\" TAG=\"sms\"/></SMS>";
			}
		}
	}
	$xmldata.="</MESSAGE>";
	if($encode_message=="Y")
		$xmldata=urlencode($xmldata);
	$data = 'data='.$xmldata.'&action=send';
	$URL="http://api.myvaluefirst.com/psms/servlet/psms.Eservice2";
	$ch=curl_init();
	curl_setopt($ch,CURLOPT_URL,$URL);
	curl_setopt($ch,CURLOPT_POST,1);
	curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	$retval=curl_exec($ch);
	$ts=time();
        $today=date('Y-m-d H:i:s',$ts);
	curl_close($ch);
	if($table)
        {
	        $response_array=explode("GUID GUID=",$retval);
                for($i=1;$i<count($response_array);$i++)
                {                        $response="<GUID GUID=".trim($response_array[$i],"</MESSAGEACK>1").">";
                        $profileid=$msgarray[$i-1]["PID"];
                        $mobile=$msgarray[$i-1]["TO"];
                        if($table=="newjs.SENT_VERIFICATION_SMS")
                                $sql="INSERT INTO $table VALUES('','$profileid','$response','$today','$mobile')";
                        else
                                $sql="INSERT INTO $table VALUES('','$profileid','$response','$today')";
                        mysql_query_decide($sql) or die($sql.mysql_error_js());

                }
        }
	return 1;
}
?>
