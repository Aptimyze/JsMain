<?php 
  	$curFilePath = dirname(__FILE__)."/"; 
 	include_once("/usr/local/scripts/DocRoot.php");
	chdir(dirname(__FILE__));
	ini_set("max_execution_time","0");
	include("../connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once(JsConstants::$docRoot."/commonFiles/sms_inc.php");

	$db = connect_slave();
	$last_7day = date("Y-m-d",time()-7*24*60*60);
	$dateEnd   =date("Y-m-d");
	$totmsgTxt ='';
	$emailBoss ="rohit.manghnani@jeevansathi.com,anamika.singh@jeevansathi.com";

	$sql1 ="SELECT u.USER,p.HEAD_ID FROM jsadmin.UPSELL_AGENT u,jsadmin.PSWRDS p WHERE u.USER=p.USERNAME AND p.ACTIVE='Y'";
        $res1 =mysql_query($sql1,$db) or die(mysql_error($db));       
        while($row1 =mysql_fetch_array($res1))
	{              
        	$user 	=$row1['USER'];
		$headid =$row1['HEAD_ID'];

		$msgTxt ='';
		$msgTxt .="<br><br>Upsell Executive Name: ".$user."<br>";
		$msgTxt .="Cancelled Transaction List:<br>";

		$sql2 ="select EMAIL from jsadmin.PSWRDS WHERE EMP_ID='$headid'";
		$res2 =mysql_query($sql2,$db) or die(mysql_error($db));		
		$row2 =mysql_fetch_array($res2);
		$email =$row['EMAIL'];	

		$sqlC ="select e.PROFILEID,e.ENTRY_DT from billing.EDIT_DETAILS_LOG e,billing.PAYMENT_DETAIL p WHERE e.ENTRYBY='$user' AND e.ENTRY_DT>='$last_7day 00:00:00' AND p.STATUS='CANCEL' and e.PROFILEID=p.PROFILEID";
		$resC =mysql_query($sqlC,$db) or die(mysql_error($db));
		while($rowC=mysql_fetch_array($resC))
		{
			$profileid =$rowC['PROFILEID'];
			$entry_dt  =$rowC['ENTRY_DT'];

			if($profileid)
			{
				$sqlJ ="select USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
				$resJ =mysql_query($sqlJ,$db) or die(mysql_error($db));		
				$rowJ =mysql_fetch_array($resJ);	
				$username =$rowJ['USERNAME'];	

				$msgTxt .=" | ".$username.", Date: ".$entry_dt;
			}
		}
		$totmsgTxt .=$msgTxt;
		sendMail_dis($email,$msgTxt);
        }
	sendMail_dis($emailBoss,$totmsgTxt);

// function to send the email to the user 
function sendMail_dis($to_email,$extraMsg)
{
        //$to_email 	="manoj.rana@naukri.com";
        $from   	="matchpoint@jeevansathi.com";
	$subject	="List of cancelled transaction in last 1 week";
        $msgTxt		="Hi, <br><br>
	        	Please find the list of usernames whose transaction has been cancelled in last 1 week (From: $last_7day - $dateEnd).<br><br>";

        $msgTxt 	.=$extraMsg."<br><br>";
        $msgTxt 	.="Thanks,<br>Team Jeevansathi<br>";
        send_email($to_email,$msgTxt,$subject,$from);
}


?>
