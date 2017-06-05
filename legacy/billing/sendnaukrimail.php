<?php

//$db= mysql_connect("localhost:/usr/local/src/mysql/mysql.sock","root","Km7Iv80l") or die(mysql_error_js());
$db= mysql_connect("198.64.140.118:3307","jsuser","jsKm7Iv80l") or die(mysql_error_js());
mysql_select_db("manager",$db);

include("/usr/local/apache/sites/jeevansathi.com/htdocs/smarty/Smarty.class.php");
$smarty=new Smarty;

$curdate = date("Y-m-d",time()-24*60*60);
$error_msg = "";

$sql = "Select NAME, EMAIL from RESUME where ENTRY_DT = '$curdate'";
$res = mysql_query_decide($sql) or $error_msg .= "\nError in selecting Records because of ".mysql_error_js();

$total_rows = mysql_num_rows($res);

//$announce_from_email = "alok@naukri.com";
$announce_from_email = "webmaster@jeevansathi.com";
$subject = "An Invitation to Fall in Love";
$i = 0;

while($myrow = mysql_fetch_array($res))
{
	//$announce_to_email = "alok@naukri.com";
	$announce_to_email = $myrow["EMAIL"];
	$smarty->assign("NAME",$myrow["NAME"]);
	$smarty->assign("EMAIL",$myrow["EMAIL"]);
	//$smarty->display("mailer/mailer_new_mem_naukri.htm");
	$body = $smarty->fetch("mailer/mailer_new_mem_naukri.htm");	

	$MP = "/usr/sbin/sendmail -N failure -t";
	$spec_envelope = 1;
	// Access Sendmail
	// Conditionally match envelope address
	if($spec_envelope)
	{
		$MP .= " -f $announce_from_email";
	}
	$fd = popen($MP,"w");
														     
	fputs($fd, "X-Mailer: PHP3\n");
	fputs($fd, "MIME-Version:1.0 \n");
	fputs($fd, "To: $announce_to_email\n");
	fputs($fd, "From: $announce_from_email \n");
	fputs($fd, "Subject: $subject \n");
														     
	fputs($fd, "Content-Type: text/html; charset=us-ascii \n");
	fputs($fd, "Content-Transfer-Encoding: 7bit \n\n");
	fputs($fd, "$msg\n"); // message goes here
	fputs($fd, "--%s", $boundry);
														     
	fputs($fd, "$body\n");
														     
	$retval = pclose($fd);

	if(!$retval)
	{
		$i++;
	}
	else
	{
		$error_msg .= "\nMail can not be sent for ".$myrow["EMAIL"];
	}
}

$msg = "Total Records to be send : ".$total_rows;
$msg .= "\nMail sent to : ".$i;

mail("alok@jeevansathi.com,devanshu.bhatia@naukri.com","Mail for new member sent","$msg\n$error_msg");
?>
