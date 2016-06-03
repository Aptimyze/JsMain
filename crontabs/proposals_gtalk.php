<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
chdir($_SERVER[DOCUMENT_ROOT]."/profile/");
include("connect.inc");
connect_slave();
$time=date("Y-m-d");
$time1=date("Y-m-d H:i:s",mktime(0, 0, 0, date("m"), date("d")-1, date("y")));
$time2=date("Y-m-d H:i:s",mktime(23, 59, 59, date("m"), date("d")-1, date("y")));
$counter=0;
$sql="SELECT PROFILEID , EMAIL,A.USERNAME as USERNAME FROM newjs.JPROFILE A LEFT JOIN openfire.ofRoster B ON A.EMAIL=B.jid WHERE ENTRY_DT BETWEEN '$time1' and '$time2' AND (B.sub NOT IN ( 2, 3 ) OR B.jid IS NULL) AND A.ACTIVATED = 'Y' AND A.SERVICE_MESSAGES = 'S' AND A.activatedKey=1 ";
$res=mysql_query($sql) or die(mysql_error().$sql);
while($row=mysql_fetch_array($res))
{
        $mypid=$row["PROFILEID"];
        $email=$row["EMAIL"];
	$username=$row['USERNAME'];
		$from_name="Jeevansathi Info";
			if($mypid)
			{
					$myprofilechecksum = md5($mypid)."i".($mypid);
					$smarty->assign("myprofilechecksum",$myprofilechecksum);
					$smarty->assign("USERNAME",$username);
					$smarty->assign("PREHEADER",'Please add info@jeevansathi.com to your address book to ensure delivery of this mail into you inbox');
					$msg=$smarty->fetch("proposals_gtalk.htm");
					$counter++;
					//send_email($email,$msg,"Receive proposals on Gtalk","Jeevansathi.com","","","","","","Y");
					send_email($email,$msg,"Receive proposals on Gtalk","info@jeevansathi.com","","","","","","","1","","Jeevansathi Info");			
			}
		
}
// if script completes successfully send mail
		SendMail::send_email("nitesh.s@jeevansathi.com,nikhil.dhiman@jeevansathi.com"," $counter mail sent out.","Gtalk req cron completed");
/*
$email='lavesh.rawat@gmail.com,lavesh.rawat@jeevansathi.com';
send_email($email,$msg,"Sample Mail:Receive proposals on Gtalk","info@jeevansathi.com","","","","","","Y");

$email='lavesh.rawat@gmail.com,lavesh.rawat@jeevansathi.com';
send_email($email,$counter,"Total proposals on Gtalk","info@jeevansathi.com","","","","","","Y");
*/
?>
