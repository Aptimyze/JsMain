<?php
//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//end of it
include_once("$_SERVER[DOCUMENT_ROOT]/classes/JProfileUpdateLib.php");
include("connect.inc");
include_once("mobile_detect.php");
include("$_SERVER[DOCUMENT_ROOT]/classes/class.rc4crypt.php");
$lang=$_COOKIE["JS_LANG"];

/*************************************Portion of Code added for display of Banners*******************************/
$db=connect_db();
$data=authenticated($checksum);
$key_ja="J1S2T3!@#";
if(!$data)
{
	$inline="style='display:inline'";
	$none="style='display:none'";
	$first_style=$inline;
	$second_style=$none;
	$third_style=$none;
	$error_style=$none;
	if(!$_GET['activekey'])
	{	
		if(count($_POST)>0)
		{
			if($email=="")
				{
						$flag=1;
				}
				elseif (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i", $email))
				{
						$flag=1;
				}
				else
				{
					$sql="select PROFILEID,USERNAME,EMAIL from newjs.JSARCHIVED where EMAIL='$email' and STATUS='Y'";
					$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
					if($row=mysql_fetch_assoc($res))
					{
						$flag=2;
						$headers = "";
						$profileid=$row['PROFILEID'];
						$username=$row['USERNAME'];
						$email=$row['EMAIL'];
						$activeid=bin2hex(rc4crypt::encrypt($key_ja, $row['PROFILEID'], 1));
						
						$to=$email;
						$from="webmaster@jeevansathi.com";
						$subject="Activate your Jeevansathi.com profile";
						$message='<div>Hi User,
  <p>
  </p></div>
<div style="margin-left:60px">
For security reasons, we ask all members to confirm their email addresses. Please click the link below to activate your account.
  <p>
  </p>
   
</div>
<div style="margin-left:60px"><a href="'.$SITE_URL.'/profile/retrieve_archived.php?activekey='.$activeid.'&directmail=2">'.$SITE_URL.'/profile/retrieve_archived.php?activekey='.$activeid.'&directmail=2</a>
  <p>
  </p></div>
<div style="margin-left:60px">
  If the link above doesn\'t work, copy and paste the URL into the \'Address\' bar of your browser.
  <br>
  <br>
</div>
      <br>
      <br>

<div align="left" style="margin-left:60px">Warm Regards,</div>
<div align="left" style="margin-left:60px">Jeevansathi.com Team.</div>

';
							//echo $activeid."-----";
						//echo $dHex = rc4crypt::decrypt($key, hex2bin_ra($activeid), 1);
						//get_correct_ra($key,$activeid);
						send_email($to,$message,$subject,$from);	
						//send_email($to,$message,$subject,$headers);
						
					}
					else
						$flag=1;
				}
			
		}
		
		if($flag==1)
		{
			$error_style=$inline;
		}
		else if($flag==2)
		{
			$first_style=$none;
			$second_style=$inline;
		}
	}
	else
	{
		$profileid = rc4crypt::decrypt($key_ja, hex2bin_ra($activekey), 1);
		if($profileid)
		{
			
				retrieve_profile($profileid);
			
			$first_style=$none;
			$second_style=$none;
			$error_style=$none;
			$third_style=$inline;
		}	
	}	

$smarty->assign("EMAIL",$email);
$smarty->assign("FIRST_STYLE",$first_style);
$smarty->assign("SECOND_STYLE",$second_style);
$smarty->assign("THIRD_STYLE",$third_style);

$smarty->assign("ERROR_CROSS",$error_style);
$smarty->assign("small_header","1");
$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
$smarty->assign("FOOT",$smarty->fetch("footer.htm"));
if($isMobile)
{
	$mb_footer=$smarty->fetch("mobilejs/jsmb_footer.html");
        $mb_header=$smarty->fetch("mobilejs/jsmb_header.html");
        $smarty->assign("FOOTER",$mb_footer);
        $smarty->assign("HEADER", $mb_header);
        $smarty->display("mobilejs/jsmob_archived-result.html");
}
else
{
	$smarty->display("js_arch_ac_active_index.htm");
}
}
else
{
echo "<Script>top.document.location='/profile/login.php';</script>";
die;
}
function retrieve_profile($profileid)
{
	$sql_act = "SELECT ACTIVATED,PREACTIVATED FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
	$res_act = mysql_query_decide($sql_act) or die(mysql_error_js());
	$row_act = mysql_fetch_array($res_act);
	if($row_act['ACTIVATED']=='D')
		$arrFields['ACTIVATED']=$row_act['PREACTIVATED'];
		
	$jprofileUpdateObj = JProfileUpdateLib::getInstance(); 
	
	$arrFields['MOB_STATUS']='N';
	$arrFields['LANDL_STATUS']='N';
	$arrFields['PHONE_FLAG']='';
	$arrFields['activatedKey']=1;
	$arrFields['ACTIVATE_ON']=1;
	$arrFields['JSARCHIVED']=0;
	$exrtaWhereCond = "";
	$jprofileUpdateObj->editJPROFILE($arrFields,$profileid,"PROFILEID",$exrtaWhereCond);
	//$sql="UPDATE newjs.JPROFILE set MOB_STATUS='N',LANDL_STATUS='N',PHONE_FLAG='',ACTIVATED=if(ACTIVATED='D',PREACTIVATED,ACTIVATED),activatedKey=1, ACTIVATE_ON='0',JSARCHIVED=0 where PROFILEID='$profileid'"; 
	//mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$date=date("Y-m-d");
	$sql="UPDATE newjs.JSARCHIVED set STATUS='N',ACT_DATE='$date' where PROFILEID='$profileid' and STATUS='Y'";
	mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		
	$path_1 = $_SERVER['DOCUMENT_ROOT']."/profile/retrieveprofile_bg.php ".$profileid." > /dev/null ";
	$cmd_1 = "php -q ".$path_1;
	$path_2 = $_SERVER['DOCUMENT_ROOT']."/profile/send_mail_sms.php ".$profileid." > /dev/null ";
	$cmd_2= "php -q ".$path_2;
	$cmd="$cmd_1 ; $cmd_2";
	passthru($cmd);
}
function get_correct_ra($value,$key)
{
		$pwd=$key;
		$dHex = rc4crypt::decrypt($pwd, hex2bin_ra($value), 1); // Assuming the key is hexadecimal
		return $dHex;
}
function setSalt_ra($value,$key)
{
		$pwd=$key;
		$eHex = bin2hex(rc4crypt::encrypt($pwd, $value, 1)); // Assuming the key is hexadecimal
		return $eHex;
}
function hex2bin_ra($str)
{
		$bin = "";
		$i = 0;
		do {
		$bin .= chr(hexdec($str{$i}.$str{($i + 1)}));
		$i += 2;
		} while ($i < strlen($str));
		return $bin;
}
// flush the buffer
if($zipIt)
	ob_end_flush();

?>
