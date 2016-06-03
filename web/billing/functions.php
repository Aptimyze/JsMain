<?php

$curdate = date("Y-m-d",time()-24*60*60);
$error_msg = "";

function sendmail($from,$to,$cc,$bcc,$subject,$attachment)
{
	global $smarty;
	//$announce_from_email = "alok@naukri.com";
	if(!$from)
		$announce_from_email = "webmaster@jeevansathi.com";
	else
		$announce_from_email = $from;

	if(!$subject)
		$subject = "Test Mail from jeevansathi.com";
	else
		$subject = $subject;

	if(!$to)
		$announce_to_email = "alok@jeevansathi.com";
	else
		$announce_to_email = $to;

	if($cc)
		$announce_cc_email = $cc;
	else
		$announce_cc_email = '';

	if($bcc)
		$announce_bcc_email = $bcc;
	else
		$announce_bcc_email = '';

	$body = $attachment;

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
	if($announce_cc_email)
	fputs($fd, "Cc: $announce_cc_email\n");
	if($announce_bcc_email)
	fputs($fd, "Bcc: $announce_bcc_email\n");
	fputs($fd, "From: $announce_from_email \n");
	fputs($fd, "Subject: $subject \n");
														     
	fputs($fd, "Content-Type: text/html; charset=us-ascii \n");
	fputs($fd, "Content-Transfer-Encoding: 7bit \n\n");
	fputs($fd, "$msg\n"); // message goes here
	fputs($fd, "--%s", $boundry);
														     
	fputs($fd, "$body\n");
														     
	$retval = pclose($fd);

	return $retval;
}
?>
