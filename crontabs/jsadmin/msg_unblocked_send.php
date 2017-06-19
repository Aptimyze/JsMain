<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


include(JsConstants::$docRoot."/profile/connect.inc");
include(JsConstants::$docRoot."/profile/contact.inc");

$db = connect_db();

$counter = 0 ;
$sql = "Select * from OBSCENE_MESSAGE where BLOCKED ='N'";
$result = mysql_query($sql);

while($myrow = mysql_fetch_array($result))
{

	$counter++;
	$msg_id = $myrow["ID"];
        $sender_profileid = $myrow["SENDER"];
        $receiver_profileid = $myrow["RECEIVER"];
        $markcc = $myrow["MARKCC"];
        $custmessage = $myrow["MESSAGE"];
	$sendername=get_name($sender_profileid);
	$resend = 1;

	if($markcc!="Y")
		$markcc="";
	
	$contact_status = $myrow["TYPE"];
	switch($contact_status)
	{
		case 'I' :
			make_initial_contact($sender_profileid,$receiver_profileid,$savedraft,$custmessage,$flag_again,$markcc,$resend);
			break;	
		
		case 'D' :
		case 'A' :	
			send_response($sender_profileid,$receiver_profileid,$contact_status,$custmessage,$savedraft,$markcc,$resend);
			break;

		case 'M' :
			 send_message($sender_profileid,$receiver_profileid,get_contact_status($sender_profileid,$receiver_profileid),$custmessage,$savedraft,$markcc,$resend);	
			break;	
	}

        $sql = "Update OBSCENE_MESSAGE set BLOCKED = 'D' where ID = $msg_id";
        $update_message = mysql_query($sql) or die(mysql_error());

	//Sharding On Contacts done by Lavesh Rawat
	$contactResult=getResultSet("count(*) as CNT",$sender_profileid,"",$receiver_profileid);
	if($contactResult[0]['CNT'])
		 updateContactsTable($sender_profileid,$receiver_profileid,"","","","","","MSG_DEL = ''");
	else
		 updateContactsTable($receiver_profileid,$sender_profileid,"","","","","","MSG_DEL = ''");
	//Sharding On Contacts done by Lavesh Rawat

}

?>
