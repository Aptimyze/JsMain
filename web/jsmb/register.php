<?php
unset($get_post);
if(is_array($_GET))
{
        foreach($_GET as $key => $value)
                $get_post[] = "$key=$value";
}
if(is_array($_POST))
{
        foreach($_POST as $key => $value)
                $get_post[] = "$key=$value";
}
if(is_array($get_post))
        $get_post_string = @implode("&",$get_post);

header("HTTP/1.1 301 Moved Permanently");
header("Location:/register/page1?$get_post_string");
die;
include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/mobile_detect.php");
$db=connect_db();
$header=$smarty->fetch("mobilejs/jsmb_header.html");
$footer=$smarty->fetch("mobilejs/jsmb_footer.html");
$smarty->assign("HEADER",$header);
$smarty->assign("FOOTER",$footer);
if($submit)
{
	$email=trim($email);
	$mobile=trim($mobile);
	$err=0;
	if($mobile){
		if($email){
			$flag=checkemail($email);
			if($flag && $flag !=2)
			$err=1;
			$smarty->assign("INVALID_EMAIL",1);
		}
		$ereg="/[0-9]+/";
		if(!preg_match($ereg,$mobile))
		{
			$err++;
			$smarty->assign("INVALID_MOBILE",1);
		}
		else if(!(strlen($mobile)>9&&strlen($mobile)<12)){
			$err++;
			$smarty->assign("INVALID_MOBILE1",1);
		}

		if($age){
			if(!preg_match($ereg,$age)){
				$err++;
				$smarty->assign("INVALID_AGE",1);
			}
			else{
				if($gender=='' || $gender=='F'){
					if($age<18){
						$err++;
						$smarty->assign("INVALID_AGE_F",1);
					}
				}
				else
					if($age<21){
						$err++;
						$smarty->assign("INVALID_AGE_F",1);
					}
			}
		}
		if(!$err)
		{
			if($flag==2)
				$suc=false;
			else
				$suc=true;
			if($suc){
				if($email)
					$name=$email;
				else
					$name=$mobile;

        if ($source !== "" && isset($_COOKIE['JS_SOURCE'])) {
          $source = $_COOKIE['JS_SOURCE'];
        }
				//Write code of adding data in sugarcrm lead database
				$link=$SITE_URL."/sugarcrm/custom/crons/create_sugar_lead.php?email=$email&mobile1=$mobile&age_c=$age&mother_tongue_c=$mtongue&last_name=$name&gender_c=$gender&source_c=12&checkJprofile=1&posted_by_c=0&js_source_c=$source";
				$handle = curl_init();
				curl_setopt($handle,CURLOPT_RETURNTRANSFER, true);
				curl_setopt($handle, CURLOPT_HEADER, 1);
				curl_setopt($handle,CURLOPT_MAXREDIRS, 5);
				curl_setopt($handle,CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($handle,CURLOPT_CONNECTTIMEOUT, 20);
				curl_setopt($handle, CURLOPT_URL,$link);
				curl_exec($handle);
				curl_close($handle);
				
			$msg="Thank you! <br/>
					Our customer executive will<br/> 
					soon contact you to complete<br/>
					the registration.<br/>
					Alternatively, you can visit<br/> 
					www.jeevansathi.com<br/>
					from your personal computer.<br />";
			$smarty->assign("MESSAGE",$msg);
      $mis_source_query = "SELECT * FROM MIS.SOURCE WHERE SourceID='$source'";
      $mis_source_result = mysql_query($mis_source_query, $db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $mis_source_query, "ShowErrTemplate");
      $mis_source_row = mysql_fetch_assoc($mis_source_result);

      if (mysql_num_rows($mis_source_result) !== 0) {
        $group_name = $mis_source_row['GROUPNAME'];
        $mis_pixelcode_query = "SELECT * FROM MIS.PIXELCODE WHERE GROUPNAME='$group_name'";
        $mis_pixelcode_result = mysql_query($mis_pixelcode_query, $db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$mis_pixelcode_query,"ShowErrTemplate");
        $mis_pixelcode_row = mysql_fetch_assoc($mis_pixelcode_result);
        
        if (mysql_num_rows($mis_pixelcode_result) !== 0) {
          $pixel_code = $mis_pixelcode_row['PIXELCODE'];
          $smarty->assign("PIXEL_CODE", $pixel_code);
        }
      }
      setcookie("JS_SOURCE","",time() - 3600,"/");
			}
			else{
				$fp_url="jsmb/jsmb_forgotpassword.php?in_field=$email";
				if($email)
				{
					$arcsql="select PROFILEID from newjs.JSARCHIVED where EMAIL='$email' and STATUS ='Y'";
        	                        $arcres=mysql_query($arcsql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $arcsql, "ShowErrTemplate");
	                                $arcrow=mysql_fetch_array($arcres);
                                	if(mysql_num_rows($arcres) > 0)
                        	        {
                	                       $fp_url="profile/retrieve_archived.php?email=$email";
        	                                //die;
	                                }
				}	
				$err_msg="This email is already registered<br/>
					in our system. To retrieve the<br/>
					username and password,<br/><a href=\"$SITE_URL/$fp_url\">click here</a>.";
				$msg=$smarty->assign("ERR_MSG",$err_msg);
			}
			$smarty->display("mobilejs/register_thanks.html");
			die;
		}
	}
	else
		$smarty->assign("BLANK_ERR",1);
}
	$smarty->assign("GENDER",$gender);
$smarty->assign("EMAIL",stripslashes($email));
$smarty->assign("MOBILE",$mobile);
$smarty->assign("mtongue",$mtongue);
$smarty->assign("source",$source);
$smarty->assign("AGE",$age);
$header=$smarty->fetch("mobilejs/jsmb_header.html");
$footer=$smarty->fetch("mobilejs/jsmb_footer.html");
$smarty->assign("HEADER",$header);
$smarty->assign("FOOTER",$footer);
$smarty->display("mobilejs/jsmb_register.html");
?>
