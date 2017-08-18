<?
  $curFilePath = dirname(__FILE__)."/";
 include_once("/usr/local/scripts/DocRoot.php");

$fromCrontab = 1;
include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
$db = connect_db();
include_once($_SERVER['DOCUMENT_ROOT']."/profile/contact.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/commonFiles/connect_dd.inc");
$mysqlObj=new Mysql;
$yesterday=mktime(date("H"),date("i"),date("s"),date("m"),date("d")-1,date("Y"));
$yesterdayDate=date("Y-m-d",$yesterday);
$profiles=array();
$sql="SELECT PROFILEID FROM  newjs.PHOTO_FIRST WHERE  `ENTRY_DT` =  '".$yesterdayDate."'";
$res=mysql_query($sql,$db);
while($row=mysql_fetch_array($res))
{
	$profiles[$row['PROFILEID']]=$row['PROFILEID'];
}
//$profiles = array(144111);
foreach($profiles as $k=>$sender_profileid)
{
//$sender_profileid = 144111;
	$receivers=array();
	$receiver_profileid='';
	$sender_details=get_profile_details($sender_profileid);
	if($sender_details['HAVEPHOTO']=='Y' && $sender_details['ACTIVATED']=='Y')
	{
		$myDbName=getProfileDatabaseConnectionName($sender_profileid);
		$myDb=$mysqlObj->connect("$myDbName");
		$sql_receivers = "SELECT RECEIVER FROM newjs.CONTACTS where SENDER='".$sender_profileid."' AND TYPE='I' AND FILTERED!='Y'";
		$res_receivers=mysql_query($sql_receivers,$myDb);
		while($row_receivers=mysql_fetch_array($res_receivers))
		{
			$receivers[]=$row_receivers['RECEIVER'];
		}
	}
//$receivers = array(2157936);
	if($receivers)
	{
		foreach($receivers as $key=>$receiver_profileid)
		{
			$receiver_details=get_profile_details($receiver_profileid);
			if($receiver_details && $receiver_details['ACTIVATED']=='Y')
			{
				$filtered_contact = getFilteredContact($sender_profileid,$receiver_profileid);
				if($filtered_contact != "Y"){
					$his_her = $sender_details["GENDER"]=="M"?"his":"her";
					$custmessage = "This is a system generated reminder. This member had expressed interest to you earlier but had no photograph on ".$his_her." profile. Please consider the profile again as photo(s) have been uploaded by this member.";
					make_initial_contact($sender_profileid,$receiver_profileid,"",$custmessage,1,"","","",'','',$filtered_contact);
					$message=get_message_to_send_contact($sender_details,$draft_name,$custmessage,$receiver_details,"I",$DRA_MES,'',$filtered_contact);
					$contact_id=get_contact_id($sender_profileid,$receiver_profileid);
					//save_email($contact_id,$sender_profileid,$receiver_profileid,$custmessage,"",1);
					$sql="REPLACE INTO newjs.CONTACTS_ONCE (CONTACTID,SENDER,RECEIVER,TIME,MESSAGE,SENT) VALUES ('".$contact_id."','".$sender_profileid."','".$receiver_profileid."',now(),'".$custmessage."','N')";
					mysql_query($sql,$db);
				}
			}
		}
	}
}
?>
