<?
  $curFilePath = dirname(__FILE__)."/";
 include_once("/usr/local/scripts/DocRoot.php");

//***one time cron to send eoi reminder on first photo upload before live date of daily cron

$fromCrontab = 1;
include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
$db = connect_db();
include_once($_SERVER['DOCUMENT_ROOT']."/profile/contact.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/commonFiles/connect_dd.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/comfunc.inc");
$mysqlObj=new Mysql;
$fromDate='2012-05-01';//date("Y-m-d",$date1);
$toDate='2012-07-13';//date("Y-m-d",$date2);
$profiles=array();
$sql="SELECT PROFILEID,ENTRY_DT FROM  newjs.PHOTO_FIRST WHERE  `ENTRY_DT` BETWEEN  '".$fromDate."' AND '".$toDate."'";
$res=mysql_query($sql,$db);
while($row=mysql_fetch_array($res))
{
	$profiles[$row['PROFILEID']]['PROFILEID']=$row['PROFILEID'];
	$profiles[$row['PROFILEID']]['ENTRY_DT']=$row['ENTRY_DT'];
}
$i = 0;
foreach($profiles as $k=>$sender_profileid)
{
//$sender_profileid = 144111;
	$receivers=array();
	$receiver_profileid='';
	$sender_details=get_profile_details($sender_profileid['PROFILEID']);
	if($sender_details['HAVEPHOTO']=='Y' && $sender_details['ACTIVATED']=='Y')
	{
		$myDbName=getProfileDatabaseConnectionName($sender_profileid['PROFILEID']);
		$myDb=$mysqlObj->connect("$myDbName");
	$sql_receivers = "SELECT RECEIVER FROM newjs.CONTACTS where SENDER='".$sender_profileid['PROFILEID']."' AND TYPE='I' AND FILTERED!='Y' AND TIME <='".$sender_profileid['ENTRY_DT']." 23:59:59'";
echo $sql_receivers."\n";
		$res_receivers=mysql_query($sql_receivers,$myDb);
		while($row_receivers=mysql_fetch_array($res_receivers))
		{
			$receivers[]=$row_receivers['RECEIVER'];
		}
	}
//$receiver_profileid = 2379216;
	if($receivers)
	{
		foreach($receivers as $key=>$receiver_profileid)
		{
			$receiver_details=get_profile_details($receiver_profileid);
			if($receiver_details && $receiver_details['ACTIVATED']=='Y')
			{
				$filtered_contact = getFilteredContact($sender_profileid['PROFILEID'],$receiver_profileid);
				if($filtered_contact != "Y")
				{
					$his_her = $sender_details["GENDER"]=="M"?"his":"her";
					$custmessage = "This is a system generated reminder. This member had expressed interest to you earlier but had no photograph on ".$his_her." profile. Please consider the profile again as photo(s) have been uploaded by this member.";
					make_initial_contact($sender_profileid['PROFILEID'],$receiver_profileid,$custmessage,$custmessage,1,"","","",'','',$filtered_contact);
					$message=get_message_to_send_contact($sender_details,$draft_name,$custmessage,$receiver_details,'I',$DRA_MES,'',$filtered_contact);
					$contact_id=get_contact_id($sender_profileid['PROFILEID'],$receiver_profileid);
				//save_email($contact_id,$sender_profileid['PROFILEID'],$receiver_profileid,$custmessage,"",1);
					$sql="REPLACE INTO newjs.CONTACTS_ONCE (CONTACTID,SENDER,RECEIVER,TIME,MESSAGE,SENT) VALUES ('".$contact_id."','".$sender_profileid['PROFILEID']."','".$receiver_profileid."',now(),'".$custmessage."','N')";
					echo $sql."\n";
					mysql_query($sql,$db) or die(mysql_error());
					$i++;
				}
			}
		}
	}
}
$to='nitesh.s@jeevansathi.com';
$subject = "Total $i: One time Reminder mailer send success mail";
$msg='Cron executed successfully<br/><br/>Warm Regards';
send_email($to,$msg,$subject,"",$cc);

?>
