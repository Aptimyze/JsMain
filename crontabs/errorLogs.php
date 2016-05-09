<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


$path=$_SERVER['DOCUMENT_ROOT']."/profile";

//$log_date = date("Y-m-d");
$log_date = date("Y-m-d",time() + 37800);
$file = $path."/logerror_temp.txt";
$tot_cnt = shell_exec("grep '$log_date' $file | wc -l");

if($tot_cnt > 100)
{
	$mobile 	= "9999193201";
	$message	= "Mysql Error Count have reached 100 within 5 minutes";
	$from 		= "Jeevan";
	$profileid 	= "111";
	$smsState = send_sms_1($message,$from,$mobile,$profileid,'Y');
	if($smsState)
		log_file($file);	
}
else
{
	log_file($file);
}

function log_file($file)
{
	$file_open = fopen($file,"w");
	fwrite($file_open,"");
	fclose($file_open);
}

function send_sms_1($message,$from,$mobile,$profileid,$encode_message='')
{
        if($encode_message=='Y')
                $message=urlencode($message);

        if($message && $from && $mobile && $profileid)
        {
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
	        return 1;//Valid mobile.
        }
	return 0;//invalid mobile.
}


?>
