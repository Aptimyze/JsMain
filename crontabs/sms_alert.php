<?php 
successfullDie("No Longer Needed");
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


$mobileno=array("9818424749");	// some no.s for alert
$toemail="lavesh.rawat@jeevansathi.com";	// some emails for alert

$serverarr=array(array(MysqlDbConstants::$master["HOST"],"10074"),
		 array(MysqlDbConstants::$bms["HOST"].":".MysqlDbConstants::$master["PORT"],"10076")
		);

function mail_sms($message,$from,$server)
{
    global $mobileno;
    global $toemail;

    $profileid="111111";

    $subject= "URGENT : FROM ".$from;

    if(mysql_connect($server,"root","HGpZD141"))
    {
	$message1=$message."\n";
	$sql="SHOW FULL PROCESSLIST";
	$res=@mysql_query($sql);
	while($row=@mysql_fetch_row($res))
	{
		$message1.=implode(",\t",$row)."\n";
	}
	$message=$message1;
    }
    else
    {
	$message.="Cant get root connection. ".mysql_error();
    }

    mail($toemail,$subject,$message);
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

	    $fp=fopen("/tmp/".$from."_lock","w");
	    if($fp)
	    {
		$read_data.="\nMysql Error: ".mysql_error();
		fputs($fp,$read_data);
	    }
	    fclose($fp);
        }
    }
}

for($i=0;$i<count($serverarr);$i++)
{
	if(!@mysql_connect($serverarr[$i][0],"user","CLDLRTa9"))
	{
		$msg="Can not connect to mysql on ".$serverarr[$i][1];

		$file="/tmp/".$serverarr[$i][1]."_lock";

		if(!file_exists($file))
			mail_sms($msg,$serverarr[$i][1],$serverarr[$i][0]);
		else
		{
			$filemtime=filemtime($file);
			$ts=time();
			$diff=$ts-$filemtime;
			if($diff>=300)
				mail_sms($msg,$serverarr[$i][1],$serverarr[$i][0]);
		}
	}
	@mysql_close();
}
?>
