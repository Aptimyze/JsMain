<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

//This will get the load on the server
$get_load=shell_exec("cat /proc/loadavg");

$mobileno=array("9811637297");        // some no.s for 
$toemail="alok@jeevansathi.com,vikas@jeevansathi.com";
$SERVER=array("11638");

mail_sms($get_load);

function mail_sms($get_load)
{

	$mess=explode(" ",$get_load);
	$message=$mess[0]." ".$mess[1]." ".$mess[2];
	global $mobileno;
	global $SERVER;
	global $toemail;
	$from =$SERVER[0];
	$profileid="111111";
	$subject= "URGENT : FROM SERVER LOAD --$SERVER[0]";


        mail($toemail,$subject,$message);
	$message="LOAD ON SERVER $SERVER[0] -- ".$message;
	$message=rawurlencode($message);
	
	for($i=0;$i<count($mobileno);$i++)
    	{
        	$mobile=$mobileno[$i];
	        $read_data="";
        	$xml_content="";
	        $xml_head="%3C?xml%20version=%221.0%22%20encoding=%22ISO-8859-1%22?%3E%3C!DOCTYPE%20MESSAGE%20SYSTEM%20%22http://127.0.0.1/psms/dtd/message.dtd%22%3E%3CMESSAGE%3E%3CUSER%20USERNAME=%22naukari%22%20PASSWORD=%22na21s8api%22/%3E";
        	$xml_content.="%3CSMS%20UDH=%220%22%20CODING=%221%22%20TEXT=%22$message%22%20PROPERTY=%220%22%20ID=%22$profileid%22%3E%3CADDRESS%20FROM=%22$from%22%20TO=%22$mobile%22%20SEQ=%22$profileid%22%20TAG=%22%22/%3E%3C/SMS%3E";

	        $xml_end="%3C/MESSAGE%3E";
        	$xml_code=$xml_head.$xml_content.$xml_end;

	        $fd=@fopen("http://api.myvaluefirst.com/psms/servlet/psms.Eservice2?data=$xml_code&action=send","rb");
        	if($fd)
	        {
        	    while(!feof($fd))
	               $read_data .= fread($fd,4096);
        	    fclose($fd);
	        }
	}
}
?>
