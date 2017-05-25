<?php
function logError($message,$query="",$critical="exit", $sendmailto="NO")
{
	global $db, $smarty, $checksum;
	assign_template_pathprofile();

	//if(mysql_error_js() && $sendmailto!="YES")
	//	$sendmailto="vikas@jeevansathi.com";

	// Too many connections error
	if(mysql_errno_js()==1040)
	{
		$sendmailto="NO";
		//write_processlist_to_file();
	}
		
	ob_start();
 	var_dump($_SERVER);
 	$ret_val = ob_get_contents();
 	ob_end_clean();

	$errorstring="echo \"" . date("Y-m-d G:i:s",time() + 37800) . "\nErrorMsg: $message\nMysql Error: " . addslashes(mysql_error_js()) ."\nMysql Error Number:". mysql_errno_js()."\nSQL: $query\n#User Agent : " . $_SERVER['HTTP_USER_AGENT'] . "\n #Referer : " . $_SERVER['HTTP_REFERER'] . " \n #Self :  ".$_SERVER['PHP_SELF']."\n #Uri : ".$_SERVER['REQUEST_URI']."\n #Method : ".$_SERVER['REQUEST_METHOD']."\n";
	
	//$errorstring.="\" >> /var/www/html/profile/logerror.txt";
	//$errorstring.="\" >> /var/www/html/sites/jeevansathi.com/htdocs/profile/logerror.txt";
	$errorstring.="\" >> /usr/local/apache/sites/jeevansathi.com/htdocs/profile/logerror.txt";

	passthru($errorstring);
	$errorstring.="\n#Details : $ret_val";
//    	if($sendmailto!="NO" && $sendmailto!="YES")
		//$b=mail($sendmailto,"ERROR in jeevansathi", $errorstring);

	
	if($critical=="exit")
	{
		echo $message;
		exit;
	}
	elseif($critical=="ShowErrTemplate")
	{
		if($_SERVER['REQUEST_METHOD']=="POST")
		{
			$j=0;
			foreach($_POST as $key => $value)
			{
				if($value != "")
				{
					$data[$j]["NAME"]=$key;
					if(is_array($value))
					{
				        $data[$j]["VALUE"]="ARRAY";
						$i=0;
		                foreach($value as $val)
				            if($val != "")
							{
		                		$data[$j][$i++]=$val;
				            }
					}
					else
						$data[$j]["VALUE"]=$value;
					$j++;
				}//if
			}//foreach
		}
		else 
		{
			$j=0;
			foreach($_GET as $key => $value)
			{
				if($value != "")
				{
					$data[$j]["NAME"]=$key;
					if(is_array($value))
					{
				        $data[$j]["VALUE"]="ARRAY";
						$i=0;
		                foreach($value as $val)
				            if($val != "")
							{
		                		$data[$j][$i++]=$val;
				            }
					}
					else
						$data[$j]["VALUE"]=$value;
					$j++;
				}//if
			}//foreach
		}
		
		$smarty->assign("DATA",$data);
		$smarty->assign("ACTION",$_SERVER['PHP_SELF']);
	
		$smarty->assign("CHECKSUM",$checksum);
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
		$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
		//$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
		
		$smarty->assign("msg_error", $message);
		$smarty->display("error_template.htm");
		exit;
	}
	elseif($critical!="continue")
	{
		echo $message;
	}
	
}
