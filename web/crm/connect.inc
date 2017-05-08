<?php
include("config.php");
include_once("../jsadmin/handleCrmLogin.php");
include_once(JsConstants::$docRoot."/commonFiles/crmLoginFunctions.php");
$jsb9_track_stime=microtime(true);

// include wrapper for logging
include_once(JsConstants::$docRoot."/classes/LoggingWrapper.class.php");

function login ($id, $pass)
{
	global	$TOUT;	// global variables
	$mycond = 0;
	$pass = md5($pass);
	$sql = "select RESID,LAST_LOGIN_DT,MOD_DT from jsadmin.PSWRDS where USERNAME= binary '$id' and PASSWORD= binary '$pass' AND ACTIVE='Y'";
	$res = mysql_query_decide($sql) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception("Could not Execute query pwdError"));
	$count = mysql_num_rows($res);
	
	if ($count > 0)
	{
		$myrow = mysql_fetch_array($res);
		$recid = $myrow["RESID"];
		$last_login_dt=$myrow["LAST_LOGIN_DT"];
		$mod_dt=$myrow["MOD_DT"];

		//if(1)//strcmp($mod_dt,$last_login_st)<0)
		if(1)//$mod_dt<$last_login_dt)
		{
			list($dt,$tm)=explode(" ",$last_login_dt);
			list($yy,$mm,$dd)=explode("-",$dt);
			list($hr,$min,$sec)=explode(":",$tm);

			$last_tm=mktime($hr,$min,$sec,$mm,$dd,$yy);

			$tm = time();

			$diff=intval($tm-$last_tm);
			$days=$diff/(24*60*60);

			if($days>15)
				$expire=1;
		}

		if($expire)
		{
			$sql="UPDATE jsadmin.PSWRDS SET ACTIVE='N' WHERE RESID='$recid'";
                        mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$ret="-1";
		}
		else
		{
			$sql="UPDATE jsadmin.PSWRDS SET LAST_LOGIN_DT=NOW() WHERE RESID='$recid'";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());

			$sql="delete from jsadmin.CONNECT where TIME < ($tm - $TOUT)";
			$result = mysql_query_decide($sql);
		
			$sqlup="insert into jsadmin.CONNECT (ID, USER, IPADDR, TIME) values ('','$recid','$ip','$tm')";
			$sqlup_con = mysql_query_decide($sqlup) or die($sqlup." : ".mysql_error_js());

			$id=mysql_insert_id_js();
			$ret=md5($id)."i".$id;
			setcookie("CRM_LOGIN",$ret,0,"/",$domain);
		}
            	//$ret["USER"]=$name;
            	//$ret["RESID"]=$resid;

	}
	else
	{
		$ret = 0;
	}
//echo "retLogin : $ret";
	return $ret;
}

/*function authenticated($checksum="")
{
	global $TOUT;
	list($md, $userno)=explode("i",$checksum);

	if(md5($userno)!=$md)
            return NULL;
        else
        {
		$sql_chk = "select * from jsadmin.CONNECT where ID=$userno";
		$res_chk = mysql_query_decide($sql_chk) or die("Could not Authenticate User: auError $sql_chk ".mysql_error_js());
		$count = mysql_num_rows($res_chk);

		if ($count > 0)
		{
			$myrow = mysql_fetch_array($res_chk);

			if (time()-$myrow["TIME"] < $TOUT)
			{
				$tm = time();
				$sql_up = "update jsadmin.CONNECT set TIME=$tm where ID=$userno";
				$res_up = mysql_query_decide($sql_up) or die("Could not Authenticate User: upError");

				$ret["ID"] = $md."i".$userno;
				$ret["USER"]=$myrow["USER"];
				$agentCookie = preg_replace('/[^A-Za-z0-9\. -_]/', '',$_COOKIE["CRM_NOTIFICATION_AGENT"]);
				setLoginCookies($ret["ID"],$agentCookie);
				//$ret = TRUE;
			}
			else
			{
				unsetLoginCookies();
				$ret = NULL;
			}
		}
		else
		{
			$ret = NULL;
		}
	}

	return $ret;
}*/

/*function logout($checksum)
{
	global $TOUT;

	list($md, $userno)=explode("i",$checksum);
        if(md5($userno)!=$md)
            return FALSE;
        else
        {
		$sql_chk = "delete from jsadmin.CONNECT where ID='$userno'";
		$res_chk = mysql_query_decide($sql_chk);

		if ($res_chk)
			$ret = TRUE;
		else
			$ret = FALSE;
	}
	return $ret;
}*/

/*function getuser($checksum, $ip="")
{
	global $TOUT;

        list($md, $userno)=explode("i",$checksum);
        if(md5($userno)!=$md)
            return FALSE;
        else
        {                                                                                                                    
		 $sql_usr = "select USER from jsadmin.CONNECT where ID=$userno";
		$res_usr = mysql_query_decide($sql_usr) or die("Could not return data from Connect1. $sql_usr ".mysql_error_js());
		$count = mysql_num_rows($res_usr);

		if ($count > 0)
		{
			$myrow = mysql_fetch_array($res_usr);
			$ret_usr = $myrow["USER"];
		
			$sql_getusr = "select USERNAME from jsadmin.PSWRDS where RESID=$ret_usr";
			$res_getusr = mysql_query_decide($sql_getusr) or die("Could not return data from Pswrds.");
			$count_usr = mysql_num_rows($res_getusr);
		
			if($count_usr > 0)
			{
				$myrow_usr = mysql_fetch_array($res_getusr);
				$ret = $myrow_usr["USERNAME"];
			}
			else
			{
				$ret = FALSE;
			}
		}
		else
		{
			$ret = FALSE;
		}
	}
	return $ret;
}*/

/*function getprivilage($checksum)
{
	global $TOUT;

	list($md, $userno)=explode("i",$checksum);
        if(md5($userno)!=$md)
            return FALSE;
        else
        {
		$sql_usr = "select USER from jsadmin.CONNECT where ID=$userno";
		$res_usr = mysql_query_decide($sql_usr) or die("Could not return data from Connect1.");
		$count = mysql_num_rows($res_usr);

		if ($count > 0)
		{
			$myrow = mysql_fetch_array($res_usr);
			$ret_usr = $myrow["USER"];
		
			$sql_getusr = "select PRIVILAGE from jsadmin.PSWRDS where RESID=$ret_usr";
			$res_getusr = mysql_query_decide($sql_getusr) or die("Could not return data from Pswrds.");
			$count_usr = mysql_num_rows($res_getusr);
		
			if($count_usr > 0)
			{
				$myrow_usr = mysql_fetch_array($res_getusr);
				$ret = $myrow_usr["PRIVILAGE"];
			}
			else
			{
				$ret = FALSE;
			}
		}
		else
		{
			$ret = FALSE;
		}
	}
	return $ret;
}*/

/*function getcenter($connection, $ip)
{ 
	global $TOUT;
        list($md, $userno)=explode("i",$checksum);
        if(md5($userno)!=$md)
            return FALSE;
	else
	{

		$sql_usr = "select USER from jsadmin.CONNECT where ID=$connection and IPADDR='$ip'";
		$res_usr = mysql_query_decide($sql_usr) or die("Could not return data from Connect1.");
		$count = mysql_num_rows($res_usr);
	
		if ($count > 0)
		{
			$myrow = mysql_fetch_array($res_usr);
			$ret_usr = $myrow["USER"];
		
			$sql_getusr = "select CENTER from jsadmin.PSWRDS where RESID=$ret_usr";
			$res_getusr = mysql_query_decide($sql_getusr) or die("Could not return data from Pswrds.");
			$count_usr = mysql_num_rows($res_getusr);
		
			if($count_usr > 0)
			{
				$myrow_usr = mysql_fetch_array($res_getusr);
				$ret = $myrow_usr["CENTER"];
			}
			else
			{
				$ret = FALSE;
			}
		}
		else
		{
			$ret = FALSE;
		}
	}
	return $ret;
}*/

function getcenter_for_walkin($walkin)
{ 
        $sql_usr = "select center from jsadmin.PSWRDS where USERNAME='$walkin' ";
                                                                                                                             
        $res_usr = mysql_query_decide($sql_usr) or die("Could not return data from executive.");
                                                                                                                             
        $count = mysql_num_rows($res_usr);
                                                                                                                             
        if ($count > 0)
        {
                $myrow_usr = mysql_fetch_array($res_usr);
                $ret = $myrow_usr["center"];
        }
        else
        {
                $ret = FALSE;
        }
                                                                                                                             
        return $ret;
}

/*function getemail($connection, $ip)
{
	global $TOUT;

	$sql_usr = "select USER from jsadmin.CONNECT where ID=$connection and IPADDR='$ip'";
	$res_usr = mysql_query_decide($sql_usr) or die("Could not return data from Connect1.");
	$count = mysql_num_rows($res_usr);

	if ($count > 0)
	{
		$myrow = mysql_fetch_array($res_usr);
		$ret_usr = $myrow["USER"];
		
		$sql_getusr = "select EMAIL from jsadmin.PSWRDS where RESID=$ret_usr";
		$res_getusr = mysql_query_decide($sql_getusr) or die("Could not return data from Pswrds.");
		$count_usr = mysql_num_rows($res_getusr);
		
		if($count_usr > 0)
		{
			$myrow_usr = mysql_fetch_array($res_getusr);
			$ret = $myrow_usr["EMAIL"];
		}
		else
		{
			$ret = FALSE;
		}
	}
	else
	{
		$ret = FALSE;
	}

	return $ret;
}*/

function current_date()
{
	$tm = date("Y-m-d",time());
	return $tm;
}

function checkPhoneNumber($number)     // returns phone number if phone number is valid else return false
{
	$rep_values =array(" ", "-", "(", ")" ,"+" ,"." ,",");
	$number =str_replace($rep_values,'',$number);
	if(substr($number,0,1)=='0')
		$number = substr($number,1);
	if(strlen($number)>10)
		$number =substr($number,-10);
	if(!is_numeric($number))
		return false;
	else
		return $number;
}

function checkemail($email)     // returns 1 if email id not valid
{
        $flag=0;
        if( trim($email) =='')
        {
                $flag=1;
        }
        elseif (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i", $email))
        {
                $flag=1;
    	}
        else
        {
                $flag=0;
        }
                                                                                                                             
	return $flag;
}

function my_format_date($day,$month,$year)
{
	if($month=="01" || $month=="1")
		$month="January";
	elseif($month=="02" || $month=="2")
		$month="February";
	elseif($month=="03" || $month=="3")
		$month="March";
	elseif($month=="04" || $month=="4")
		$month="April";
	elseif($month=="05" || $month=="5")
		$month="May";
	elseif($month=="06" || $month=="6")
		$month="June";
	elseif($month=="07" || $month=="7")
		$month="July";
	elseif($month=="08" || $month=="8")
		$month="August";
	elseif($month=="09" || $month=="9")
		$month="September";
	elseif($month=="10")
		$month="October";
	elseif($month=="11")
		$month="November";
	else
		$month="December";
		
	if(strlen($day)==1)
		$day= "0" . $day;
		
	return $month . " " . $day . ", " . $year;
}

function create_dd($selected,$cname,$minormax=0,$labelselect="")
{
	if($cname=='competition_type')
		$selected =STRTOUPPER($selected);
                                                                                                 
        if(is_array($selected))
        {
                $s_arr = $selected;
        }
        elseif($selected!="")
        {
                $s_arr=explode(",",$selected);
        }
        else
                $s_arr=array();
                                                                                                 
        $muli ="[]";
        if ($cname == "City_India")
        {
                $sql = "SELECT SQL_CACHE VALUE, LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = 51 ORDER BY SORTBY";
                $res = mysql_query_decide($sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
			if(in_array($myrow["VALUE"],$s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
                }
        }
	if($cname== 'competition_type')
	{	
		global $negativeListFlagArray;
		$competitionArr =array();
		$arrKeys =array_keys($negativeListFlagArray);	
		foreach($arrKeys as $key=>$val){
			$competitionArr[$val] ="$val";
		}
		$totCnt =count($competitionArr);
		$ret .="<option value=\"\" selected >Select Type </option>";
		foreach($competitionArr as $key=>$value)
		{
			if(in_array(STRTOUPPER($value),$s_arr))
				$ret .="<option value=\"$key\" selected >$value </option>";
			else
				$ret .="<option value=\"$key\">$value </option>";
		}
	}

        return $ret;
}

function label_select($columnname,$value,$database="")
{
	if($database=="")
		$database="newjs";

        $sql = "select SQL_CACHE LABEL from $database.$columnname WHERE VALUE='$value'";
        $res = mysql_query_decide($sql) or logError("error",$sql) ;
        $myrow= mysql_fetch_array($res);
        return $myrow;
}

function logError($message,$query="",$critical="exit", $sendmailto="NO")
{
        global $db, $smarty, $checksum;
        ob_start();
        var_dump($_SERVER);
        $ret_val = ob_get_contents();
        ob_end_clean();

        $errorstring="\n" . date("Y-m-d G:i:s",time() + 37800) . "\nErrorMsg: $message\nMysql Error: " . addslashes(mysql_error_js()) ."\nMysql Error Number:". mysql_errno_js()."\nSQL: $query\n#User Agent : " . $_SERVER['HTTP_USER_AGENT'] . "\n #Referer : " . $_SERVER['HTTP_REFERER'] . " \n #Self :  ".$_SERVER['PHP_SELF']."\n #Uri : ".$_SERVER['REQUEST_URI']."\n #Method : ".$_SERVER['REQUEST_METHOD']."\n";

	error_log($errorstring,3,JsConstants::$docRoot . "/jsadmin/temp/logerror.txt");

        $errorstring.="\n#Details : $ret_val";
		LoggingWrapper::getInstance()->sendLog(LoggingEnums::LOG_ERROR, new Exception($errorstring));
        if($sendmailto!="NO" && $sendmailto!="YES")
                $b=mail($sendmailto,"ERROR in new resman connect", $errorstring);

        if($critical=="exit")
        {
                echo $message;
                exit;
        }
        elseif($critical=="ShowErrTemplate")
        {
                $smarty->assign("CHECKSUM",$checksum);
                $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
                $smarty->assign("HEAD",$smarty->fetch("head.htm"));
                $smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
                $smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));

                $smarty->assign("msg_error",$message);
		$smarty->display("error_template.htm");
                exit;
        }
        elseif($critical!="continue")
        {
                echo $message;
        }
}

/*function getname($cid)
{
        $temp=explode("i",$cid);
        $userid=$temp[1];
        $sql="SELECT USER FROM jsadmin.CONNECT WHERE ID='$userid'";
        $res=mysql_query_decide($sql) or die(mysql_error_js());
        $row=mysql_fetch_array($res);
        $id=$row['USER'];
                                                                                                                             
        $sql="SELECT USERNAME FROM jsadmin.PSWRDS WHERE RESID='$id'";
        $res=mysql_query_decide($sql) or die(mysql_error_js());
        $row=mysql_fetch_array($res);
        $username=$row['USERNAME'];
        return $username;
}*/

function get_centre($cid)
{
        $temp=explode("i",$cid);
        $userid=$temp[1];
        /*$sql="SELECT USER FROM jsadmin.CONNECT WHERE ID='$userid'";
        $res=mysql_query_decide($sql) or die(mysql_error_js());
        $row=mysql_fetch_array($res);
        $id=$row['USER'];
                                                                                                 
                                                                                                 
        $sql="SELECT CENTER FROM jsadmin.PSWRDS WHERE RESID='$id'";
        $res=mysql_query_decide($sql) or die(mysql_error_js());
        $row=mysql_fetch_array($res);
        $centre=$row['CENTER'];*/
        if($userid)
        	$centre = getcenter($userid,"");
        else
        	$centre = null;
        return $centre;
        
}

function get_login_email($cid)
{
	/*$temp=explode("i",$cid);
        $userid=$temp[1];
        $sql="SELECT USER FROM jsadmin.CONNECT WHERE ID='$userid'";
        $res=mysql_query_decide($sql) or die(mysql_error_js());
        $row=mysql_fetch_array($res);
        $id=$row['USER'];
	
	$sql="SELECT EMAIL FROM jsadmin.PSWRDS WHERE RESID='$id'";
        $res=mysql_query_decide($sql) or die(mysql_error_js());
        $row=mysql_fetch_array($res);
        $email=$row['EMAIL'];*/
        if($cid)
        	$email = getemail($cid);
        else
        	$email = null;
        return $email;
}

function get_active_status($activated,$incomplete)
{
        $message='';
        if($incomplete=='N')
                $message.="The profile is complete<br>";
        elseif($incomplete=='Y')
                $message.="The profile is incomplete<br>";
        if($activated=='D')
                $message.="The profile is deleted";
        elseif($activated=='N')
                $message.="The profile is not activated";
        elseif($activated=='Y')
                $message.="The profile is activated";
        elseif($activated=='H')
                $message.="The profile is hidden";
        elseif($activated=='U')
                $message.="The profile is Under Screening";

        return $message;
}

function populate_will_pay($sel_val=0)
{
	$j=0;
	$sql_d="SELECT * from incentive.CRM_DISPOSITION WHERE ACTIVE='Y'";
        $result_d= mysql_query_decide($sql_d) or die(mysql_error_js());
        while($myrow_d=mysql_fetch_row($result_d))
        {
		$will_pay_arr[$j]['val'] = "$myrow_d[1]";
		$will_pay_arr[$j]['label'] = "$myrow_d[2]";
		$j++;
        }

        for ($i = 0,$j=0;$j<count($will_pay_arr);$j++)
        {
                $val = $will_pay_arr[$j]['val'];
                $strtemp = '';
                $will_pay_value[]=$will_pay_arr[$j]['val'];
                $will_pay_label[]=$will_pay_arr[$j]['label'];

                $strtemp .= $will_pay_value[$j]."|X|";

                $sql="SELECT VALIDATION_VALUE,VALIDATION_LABEL from incentive.CRM_DISPOSITION_VALIDATION where DISPOSITION ='$val'";
                $result1= mysql_query_decide($sql) or die(mysql_error_js());


                 while($myrow1=mysql_fetch_row($result1))
                {
			if($myrow1[0]!='DNC1')
			{
                        	$reason_value[]="$myrow1[0]";
                        	$reason_label[]="$myrow1[1]";
                        	$strtemp .= $reason_value[$i]."$".$reason_label[$i]."#";
                        	$i++;
			}
                }
                $strtemp = substr($strtemp,0,(strlen($strtemp)-1));
                $str[] = $strtemp;
        }
        for($x=0;$x<count($str);$x++)
        {
                $str_temp = explode('|X|',$str[$x]);
                $str_val = $str_temp[0];


                if($sel_val == $str_val)
                        $newstr.="<option value=\"" . $str[$x] . "\" selected>" . $will_pay_label[$x] . "</option>\n";
                else
                        $newstr.="<option value=\"" . $str[$x] . "\">" . $will_pay_label[$x] . "</option>\n";
        }
        return $newstr;
}
function populate_call_type($sel_val=0)
{
        $call_type_arr[0]['val'] = 'Q';
        $call_type_arr[0]['label'] = 'Query';

        $call_type_arr[1]['val'] = 'C';
        $call_type_arr[1]['label'] = 'Complain';

        $call_type_arr[2]['val'] = 'S';
        $call_type_arr[2]['label'] = 'Suggestion';

        for ($i = 0,$j=0;$j<count($call_type_arr);$j++)
        {
                $val = $call_type_arr[$j]['val'];
                $strtemp = '';
                $call_type_value[]=$call_type_arr[$j]['val'];
                $call_type_label[]=$call_type_arr[$j]['label'];

                $strtemp .= $call_type_value[$j]."|X|";

                $sql="SELECT SUBTYPE_VALUE,SUBTYPE_LABEL from incentive.SERVICE_CALL_SUBTYPE where CALL_TYPE ='$val'";
                $result1= mysql_query_decide($sql) or die(mysql_error_js());


                 while($myrow1=mysql_fetch_row($result1))
                {
                        $subtype_value[]="$myrow1[0]";
                        $subtype_label[]="$myrow1[1]";
                        $strtemp .= $subtype_value[$i]."$".$subtype_label[$i]."#";
                        $i++;
		}
                $strtemp = substr($strtemp,0,(strlen($strtemp)-1));
                $str[] = $strtemp;
        }
        for($x=0;$x<count($str);$x++)
        {
                $str_temp = explode('|X|',$str[$x]);
                $str_val = $str_temp[0];


                if($sel_val == $str_val)
                        $newstr.="<option value=\"" . $str[$x] . "\" selected>" . $call_type_label[$x] . "</option>\n";
                else
                        $newstr.="<option value=\"" . $str[$x] . "\">" . $call_type_label[$x] . "</option>\n";
        }
        return $newstr;
}
function populate_call_subtype($call_type,$subtype)
{
        if(!$subtype && $call_type)
        {
                $ret .= "<option value=\"\" selected>Select any one option</option>\n";
        }
	echo $call_type;
	$sql="SELECT SUBTYPE_VALUE,SUBTYPE_LABEL from incentive.SERVICE_CALL_SUBTYPE where CALL_TYPE ='$call_type'";
        $result=mysql_query_decide($sql);


        while($myrow=mysql_fetch_array($result))
        {
                if($myrow["SUBTYPE_VALUE"]==$subtype)
                {
                        $ret .= "<option value=\"$myrow[SUBTYPE_VALUE]\" selected>$myrow[SUBTYPE_LABEL]</option>\n";
                }
                else
                                $ret .= "<option value=\"$myrow[SUBTYPE_VALUE]\">$myrow[SUBTYPE_LABEL]</option>\n";
        }
	return $ret;
}

function willpay_populate_reason($will_pay,$reason)
{
        if(!$reason && $will_pay)
        {
                $ret .= "<option value=\"\" selected>Select any one option</option>\n";
        }


        $sql= "SELECT REASON_VALUE,REASON_LABEL from incentive.CRM_DISPOSITION_REASON WHERE WILL_PAY='$will_pay'";
        $result=mysql_query_decide($sql);


        while($myrow=mysql_fetch_array($result))
        {
                if($myrow["REASON_VALUE"]==$reason)
                {
                        $ret .= "<option value=\"$myrow[REASON_VALUE]\" selected>$myrow[REASON_LABEL]</option>\n";
                }
                else
                                $ret .= "<option value=\"$myrow[REASON_VALUE]\">$myrow[REASON_LABEL]</option>\n";
        }
return $ret;
}

function get_name_of_user($profileid)
{
	$sql_name_of_user = "SELECT NAME FROM incentive.NAME_OF_USER WHERE PROFILEID='$profileid'";
	$res_name_of_user = mysql_query_decide($sql_name_of_user) or die("$sql_name_of_user".mysql_error_js());
	$row_name_of_user = mysql_fetch_array($res_name_of_user);

	if($row_name_of_user['NAME'])
		return $row_name_of_user['NAME'];
	else
		return "---NA---";
}
function check_validity($bid,$op_name='')
{
        $go = 1;
	$sql_check="SELECT MEMBERSHIP,PROFILEID,ENTRY_DT FROM billing.PURCHASES WHERE BILLID='$bid' AND STATUS='DONE'";
        $res_check=mysql_query($sql_check) or logError($sql_check);
        $row_check=mysql_fetch_array($res_check);
	$profileid=$row_check["PROFILEID"];
	$billing_day = JSstrToTime($row_check["ENTRY_DT"]);
	$billing_dt=date("Y-m-d",$billing_day);
	$checking_day = date("Y-m-d",$billing_day-86400);

	$sql_check2="SELECT ALLOTED_TO,ALLOT_TIME FROM incentive.CRM_DAILY_ALLOT WHERE PROFILEID='$profileid' ORDER BY ID DESC LIMIT 1";
        $res_check2=mysql_query($sql_check2) or logError($sql_check2);
        $row_check2=mysql_fetch_array($res_check2);
        $allot_time=JSstrToTime($row_check2["ALLOT_TIME"]);
        $allot_to=$row_check2["ALLOTED_TO"];
        if($op_name!='')
        {
                if($allot_to!=$op_name)
                        return 0;
        }
	$allot_dt=date("Y-m-d",$allot_time);
	$checking_time=JSstrToTime($checking_day);
	$diff_time=($checking_time-$allot_time)/86400;
	if($allot_dt==$billing_dt || $diff_time<=15)
	       $go = 1;
	else
	       $go = 0;
	if($op_name!='parul.dagar' && $op_name!='kanika.seth')	
	{
		$sql_check1="SELECT COUNT(*) AS CNT FROM incentive.MANUAL_ALLOT m JOIN incentive.MAIN_ADMIN ma ON m.PROFILEID=ma.PROFILEID WHERE m.PROFILEID='$profileid' AND m.ALLOTED_TO='$op_name' AND m.ALLOT_TIME>=ma.ALLOT_TIME";	
		$res_check1=mysql_query($sql_check1) or logError($sql_check1);
		$row_check1=mysql_fetch_array($res_check1);
		if($row_check1["CNT"]>0)
			$manual_case = 1;
		else
			$manual_case = 0;
	}
	else
		$manual_case = 0;

	if($row_check["ENTRY_DT"]>'2009-06-01' && !$manual_case && !$go)
	{
		$sql_history = "SELECT COUNT( * ) AS cnt FROM incentive.HISTORY WHERE PROFILEID = '$profileid' AND ENTRYBY = '$op_name' AND DATEDIFF('$checking_day', ENTRY_DT) <=15 AND DATEDIFF('$checking_day', ENTRY_DT) >=0";
		$res_history = mysql_query($sql_history) or logError($sql_history);
		if($row_history = mysql_fetch_array($res_history))
			$count=$row_history['cnt'];
		if($count>0)
			return 1;
		else
			return 0;
	}
	else
		return 1;
}
function can_allot($profileid)
{
	// removed online/offline check
        /*$sql_check="SELECT COUNT(*) AS CNT FROM newjs.JPROFILE WHERE PROFILEID='$profileid' AND CRM_TEAM='online'";
        $res_check=mysql_query($sql_check) or logError($sql_check);
        $row_check=mysql_fetch_array($res_check);
        if($row_check["CNT"])*/
        return 1;
        /*else
               return 0;*/
}
function getDiscount($profileStr)
{
	$dataSet =array();
        $sql="SELECT PROFILEID,DISCOUNT,EDATE from billing.VARIABLE_DISCOUNT WHERE PROFILEID IN($profileStr)";
        $res=mysql_query_decide($sql) or die(mysql_error_js());
        while($row=mysql_fetch_array($res))
	{
		$profileid		=$row['PROFILEID'];		
        	$discount 		=$row['DISCOUNT'];
		$eDate			=$row['EDATE'];
		$dataSet[$profileid] 	=array("DISCOUNT"=>"$discount","EDATE"=>"$eDate");
	}
	return $dataSet;
}

?>
