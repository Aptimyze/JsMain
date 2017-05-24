<?php
	include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
	$to='eshajain88@gmail.com';
	$msg='';
	$subject="web/stats/select.php file called";
	$msg='web/stats/select.php file called';
	$msg.='<br/><br/>Warm Regards';
	//echo $msg."\n\n\n";
	send_email($to,$msg,$subject,"",$cc);


