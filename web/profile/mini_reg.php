<?php

//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
$zipIt = 1;
if($zipIt && !$dont_zip_now)
{
        $dont_zip_more=1;
        ob_start("ob_gzhandler");
}
//end of it

$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path."/profile/connect.inc");
include_once($path."/profile/arrays.php");
$db = connect_db();

//Gets ipaddress of user
$ip = FetchClientIP();
if(strstr($ip, ","))
{
	$ip_new = explode(",",$ip);
		$ip = $ip_new[1];
}


/* Email & Phone Number Request Capture by Ajax */
if($action=='lead_capture')
{

	if($email_val && $mobile && $email_val!='Email' && $mobile!='Mobile No')
	{
		$time = date("Y-m-d G:i:s");
		$flag=0;
		if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/",$email_val))
		{
			$flag=1;
		}
		$part=explode("@",$email_val);
		$domain = strstr($email_val, '@');
		  $dotpos = strrpos($domain,".");
		  $domain = substr($domain,1,$dotpos-1);
		  $domain = strtolower($domain);
		if(!$flag)
		{
			if(strtolower($part[1])=="jeevansaathi.com")
				$flag=3;
			elseif(strtolower($part[1])=="jeevansathi.com")
				$flag=3;
			else{
				$sql = "SELECT DOMAIN FROM newjs.INVALID_DOMAINS";
				$res = mysql_query_decide($sql) or logError("error",$sql);
				while($row = mysql_fetch_array($res))
				{
					if(strstr($domain,$row['DOMAIN']))
					{
						$flag = 3;
						break;
					}
				}
			}
		}
		if(!$flag)
			$email_val=mysql_escape_string($email_val);
		else
			$email_val="";
		$mobile=preg_replace( '/[^0-9]/', '', $mobile);
		$mobile=mysql_escape_string($mobile);
		$source=mysql_escape_string($source);
		$sql="INSERT INTO MIS.MINI_REG_AJAX_LEAD(EMAIL,MOBILE,DATE,IPADD,SOURCE) VALUES ('$email_val','$mobile','$time','$ip','$source')";
		mysql_query($sql,$db);
	}
}

/* Date Generation */
$curDate=date('Y', JSstrToTime('-6570 days')); // Finding 18 years back year
for($i=$curDate;$i>=1939;$i--)
        $yearArray[]=$i;
$smarty->assign('yearArray',$yearArray);

for($i=1;$i<=31;$i++)
        $dayArray[]=$i;
$smarty->assign('dayArray',$dayArray);

/*
$caste_string =populate_caste_topband();
$smarty->assign('caste_str',$caste_string);
*/

function populate_caste_topband()
{
	$Caste=unserialize($Caste);

	if(is_array($Caste))
		$sel_arr_caste = $Caste;

	//REVAMP JS_DB_CASTE
include_once(JsConstants::$docRoot."/commonFiles/RevampJsDbFunctions.php");
	$sql_religion = "select SQL_CACHE a.LABEL , a.VALUE,a.PARENT, a.ISALL, a.ISGROUP from CASTE as a,CASTE as b where a.PARENT=b.PARENT and b.ISALL='Y' order by b.TOP_SORTBY,a.SORTBY";
	//REVAMP JS_DB_CASTE

	$res_religion = mysql_query_decide($sql_religion) or logError("error",$sql_religion);
	$ret_religion = "";
	while($myrow_religion = mysql_fetch_array($res_religion))
	{
		if ($myrow_religion['ISALL'] == 'Y')
		{
			$ret_religion .= "<optgroup  label=\"&nbsp;\" disabled=\"disabled\"></optgroup>";
			$ret_religion1 = "<optgroup  label=\"&nbsp;\" disabled=\"disabled\"></optgroup>";
			$class = "dropbg";
		}
		//REVAMP JS_DB_CASTE
		elseif (is_part_of_a_group($myrow_religion['VALUE']) && $myrow_religion['ISGROUP'] == 'Y')
		{
			$class = "dropcolour";
		}
		else
		{
			$class = "";
		}
		if(ereg(":",$myrow_religion['LABEL']))
		{
			$label=explode(":",$myrow_religion['LABEL']);
			$reg=$label[1];
		}
		else
			$reg=$myrow_religion['LABEL'];

		if($myrow_religion['ISGROUP']=='Y')
			$reg="All ".$reg;

		if(is_array($sel_arr_caste) && in_array($myrow_religion["VALUE"],$sel_arr_caste))
		{
			if($myrow_religion['ISGROUP']=='Y'){
				$ret_religion1 = "<option  value=\"$myrow_religion[VALUE]||$myrow_religion[PARENT]\" class=\"$class\" style=\"color:#e06400;\" selected>$reg</option>";
				$ret_religion .= "<option  value=\"$myrow_religion[VALUE]||$myrow_religion[PARENT]\" class=\"$class\" style=\"color:#e06400;\" selected>$reg</option>";
			}
			else{
				$ret_religion1 = "<option  value=\"$myrow_religion[VALUE]||$myrow_religion[PARENT]\" class=\"$class\" selected>$reg</option>";
				$ret_religion .= "<option  value=\"$myrow_religion[VALUE]||$myrow_religion[PARENT]\" class=\"$class\" selected>$reg</option>";
			}
		}
		else
		{
			if ($myrow_religion['ISALL'] == 'Y'){
				$nocaste=array('14','2','149','154','173','162');
				foreach($nocaste as $key=>$val)
				{
				        $value = strtolower(trim($val));
					$arrayValues[] = $value;
				}

				if(!in_array("$myrow_religion[VALUE]",$arrayValues))
				{
					$ret_religion .= "<option class=\"$class\" style=\"background-color:#ffd84f\" value=\"$myrow_religion[VALUE]||$myrow_religion[PARENT]\">$reg</option>";
					$ret_religion1 = "<option class=\"$class\" style=\"background-color:#ffd84f\" value=\"$myrow_religion[VALUE]||$myrow_religion[PARENT]\">$reg</option>";
				}
				else
				{
					if($myrow_religion[VALUE]!=162)
					{
						$ret_religion .= "<optgroup class=\"$class\" disabled=\"disabled\" style=\"background-color:#ffd84f\" label=\"$reg\" value=\"$myrow_religion[VALUE]||$myrow_religion[PARENT]\"></optgroup>";
						$ret_religion1 = "<optgroup class=\"$class\" disabled=\"disabled\" label=\"$reg\" style=\"background-color:#ffd84f\" value=\"$myrow_religion[VALUE]||$myrow_religion[PARENT]\"></optgroup>";
					}
				}
			}
			else
			{
				if($myrow_religion['ISGROUP']=='Y'){
					$ret_religion .= "<option class=\"$class\" style=\"color:#e06400;\" value=\"$myrow_religion[VALUE]||$myrow_religion[PARENT]\">$reg</option>";
					$ret_religion1 = "<option class=\"$class\" style=\"color:#e06400;\" value=\"$myrow_religion[VALUE]||$myrow_religion[PARENT]\">$reg</option>";
				}
				else{
					$ret_religion .= "<option class=\"$class\" value=\"$myrow_religion[VALUE]||$myrow_religion[PARENT]\">$reg</option>";
					$ret_religion1 = "<option class=\"$class\" value=\"$myrow_religion[VALUE]||$myrow_religion[PARENT]\">$reg</option>";
				}
			}
		}
	}
	return $ret_religion;
}




/* Image and Headline Custmization */
global $coup_image;
$source=mysql_escape_string($_GET['source']);

$sql_custom="SELECT HEADING,COUP_IMAGE,CATEGORY,STORY FROM MIS.MINI_REG_CUSTOMIZE WHERE SOURCE='$source'";
$res_custom = mysql_query_decide($sql_custom) or logError("error",$sql_custom);
if($row_custom = mysql_fetch_array($res_custom))
{
	$heading = $row_custom['HEADING'];
	$coup_image = $row_custom['COUP_IMAGE'];
	$cat = $row_custom['CATEGORY'];
	$story = $row_custom['STORY'];
	$smarty->assign('HEADING',$heading);
	$smarty->assign('COUP_IMAGE',$coup_image);
	$smarty->assign('CAT',$cat);
	$smarty->assign('STORY',$story);
}

$smarty->assign('SOURCE',htmlentities($source,ENT_QUOTES));
$smarty->assign("FOOT",$smarty->fetch("footer_1024.htm")); // New Footer for 1024 Resolution
$smarty->assign("IS_FTO_LIVE",FTOLiveFlags::IS_FTO_LIVE);
$smarty->display("mini_reg.htm");
// flush the buffer
if($zipIt && !$dont_zip_now)
ob_end_flush();

?>
