<?php
//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//end of it

include("profileselect.php");
include("arrays.php");
connect_db();
$data=authenticated($checksum);

$profile=$data["PROFILEID"];

selectprofile();
$smarty->assign("PROFILECHECKSUM",$profilechecksum);
$smarty->assign("CHECKSUM",$checksum);
$smarty->assign("PRINT_VERSION","Y");
//Code added by Vibhor Garg
$sql="select CONTACT,CITY_RES,COUNTRY_RES,STD,PHONE_MOB,PHONE_RES,STD,EMAIL,MESSENGER_CHANNEL,MESSENGER_ID,SUBSCRIPTION,PARENTS_CONTACT,SHOWADDRESS,SHOW_PARENTS_CONTACT,SHOWPHONE_RES,SHOWPHONE_MOB,SHOWMESSENGER from newjs.JPROFILE where  activatedKey=1 and PROFILEID='$profile'";
$res=mysql_query_decide($sql) or die(mysql_error_js());
$row=mysql_fetch_array($res);
$mbpd=array("ADDRESS" => $row["CONTACT"],
"CITY" => $row["CITY_RES"],
"COUNTRY" => $row["COUNTRY_RES"],
"STD"=>$row["STD"],
"TELEPHONE1"=>$row["PHONE_RES"],
"TELEPHONE2"=>$row["PHONE_MOB"],
"FAX" => $row["FAX"],
"EMAIL" => $row["EMAIL"]);
$SUBSCRIPTION=$row["SUBSCRIPTION"];
$MESSENGER=$row["MESSENGER_CHANNEL"];
$MESSENGER_ID=$row["MESSENGER_ID"];
$PARENTS_CONTACT=$row["PARENTS_CONTACT"];
$SHOWADDRESS=$row["SHOWADDRESS"];
$SHOW_PARENTS_CONTACT=$row["SHOW_PARENTS_CONTACT"];
$SHOWPHONE_RES=$row["SHOWPHONE_RES"];
$SHOWPHONE_MOB=$row["SHOWPHONE_MOB"];
$SHOWMESSENGER=$row["SHOWMESSENGER"];
if($SHOWMESSENGER == 'Y')
{
	$smarty->assign("MESSENGER_CHANNEL",$MESSENGER_CHANNEL["$MESSENGER"]);
	$smarty->assign("MESSENGER_ID",$MESSENGER_ID );
}
if($mbpd["EMAIL"])
	$smarty->assign("HISEMAIL",$mbpd["EMAIL"]);
if($SHOWPHONE_RES == 'Y' || $SHOWPHONE_MOB == 'Y')
{
	if(($SHOWPHONE_RES == 'Y') && $mbpd["TELEPHONE1"])
		$phone=$mbpd["STD"]."-".$mbpd["TELEPHONE1"];
	if(($SHOWPHONE_MOB == 'Y') && $mbpd["TELEPHONE2"])
		if(($SHOWPHONE_RES == 'Y') && $mbpd["TELEPHONE1"])
			$phone.=",".$mbpd["TELEPHONE2"];
		else
			$phone.=$mbpd["TELEPHONE2"];
       	$smarty->assign("PHONE",$phone);
}
if($SHOWADDRESS == 'Y')
{
	if($mbpd["ADDRESS"])
	{
		$smarty->assign("ADDRESS",trim($mbpd["ADDRESS"]));
	}
}
if($SHOW_PARENTS_CONTACT == 'Y')
{
        if($PARENTS_CONTACT)
        {
                $smarty->assign("PARENTS_ADDRESS",trim($PARENTS_CONTACT));
        }
}
if($SUBSCRIPTION != "")
{	
	$sub=explode(",",$SUBSCRIPTION);
	if(in_array("D",$sub))
		$smarty->assign("ECLASSIFIED_MEM","Y");
	if(in_array("D",$sub)&&in_array("S",$sub))
		$smarty->assign("ECLASSIFIED_MEM_HIDDEN","yes");
	else
        	$smarty->assign("ECLASSIFIED_MEM_HIDDEN","no");
}
$smarty->assign("CONTACTDETAILS","1");
if($noprint)
	$smarty->assign("noprint","1");
//ends
$smarty->display("profile_by_mail.htm");
//$smarty->display("print_version.htm");

// flush the buffer
if($zipIt)
	ob_end_flush();
?>
