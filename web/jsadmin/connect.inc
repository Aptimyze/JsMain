<?php
if ($smarty_flag != 'n')
	include_once("config.php");

include_once("handleCrmLogin.php");
include_once(JsConstants::$docRoot."/commonFiles/crmLoginFunctions.php");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");

// include wrapper for logging
include_once(JsConstants::$docRoot."/classes/LoggingWrapper.class.php");
$TOUT = 14400;

if($_SERVER['DOCUMENT_ROOT'])
{
	include_once($_SERVER['DOCUMENT_ROOT']."/profile/contacts_functions.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/authentication.class.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsivrFunctions.php");
	include_once(JsConstants::$cronDocRoot."/lib/model/enums/Membership.enum.class.php");
}
else
{
	$path = JsConstants::$docRoot;
	$_SERVER['DOCUMENT_ROOT']=JsConstants::$docRoot;
	include_once("$path/profile/contacts_functions.php");
	include_once("$path/classes/globalVariables.Class.php");
	include_once("$path/classes/Mysql.class.php");
	include_once("$path/classes/Memcache.class.php");
	include_once("$path/classes/authentication.class.php");
	include_once("$path/ivr/jsivrFunctions.php");	
	include_once("$path/ivr/jsivrFunctions.php");
	include_once(JsConstants::$cronDocRoot."/lib/model/enums/Membership.enum.class.php");	
}

$TAX_RATE = billingVariables::TAX_RATE;




function set_memcache_value($profileid)
{
	if(class_exists(UserMemcache))
	{
		$memcacheObj=new UserMemcache;
		$key="PROF_SCREEN_$profileid";
		$value=2;
		$memcacheObj->setDataToMem($value,$key);
		
	}
	
}
function set_memcache_value_photo($user)
{
        if(class_exists(UserMemcache))
        {
                $memcacheObj=new UserMemcache;
                $key="PHOTO_SCREEN_$user";
                $value=5;
                $memcacheObj->setDataToMem($value,$key,5);

        }

}

// function to flush the memcache for membership subscriptions
function flush_memcache_ForMembership()
{
        if(class_exists(UserMemcache))
        {
                $memcacheObj    =new UserMemcache;
                $time_to_live   ='3600';
                $membershipArray=array("desktop_MEMBERSHIP_RS","desktop_MEMBERSHIP_DOL","desktop_MEMBERSHIP_RS_REN","desktop_MEMBERSHIP_DOL_REN","Android_app_MEMBERSHIP_RS","Android_app_MEMBERSHIP_DOL","Android_app_MEMBERSHIP_RS_REN","Android_app_MEMBERSHIP_DOL_REN","JSAA_mobile_website_MEMBERSHIP_RS","JSAA_mobile_website_MEMBERSHIP_DOL","JSAA_mobile_website_MEMBERSHIP_RS_REN","JSAA_mobile_website_MEMBERSHIP_DOL_REN","mobile_website_MEMBERSHIP_RS","mobile_website_MEMBERSHIP_DOL","mobile_website_MEMBERSHIP_RS_REN","mobile_website_MEMBERSHIP_DOL_REN","old_mobile_website_MEMBERSHIP_RS","old_mobile_website_MEMBERSHIP_DOL","old_mobile_website_MEMBERSHIP_RS_REN","old_mobile_website_MEMBERSHIP_DOL_REN","iOS_app_MEMBERSHIP_RS","iOS_app_MEMBERSHIP_DOL","iOS_app_MEMBERSHIP_RS_REN","iOS_app_MEMBERSHIP_DOL_REN");

                foreach($membershipArray as $key=>$keyVal){
                        $memcacheObj->setDataToMem("",$keyVal,$time_to_live);
                        $dataSet =$memcacheObj->getDataFromMem($keyVal);
                }
        }
}

function login ($id, $pass)
{
	global	$TOUT;	// global variables
	global $company;
        $mycond = 0;
	global $domain;

	$pass = md5($pass);

        if ($company == 'IV')
         	$sql = "select RESID from PSWRDS where USERNAME= '$id' and PASSWORD='$pass'  AND ACTIVE='Y' AND COMPANY='IV'";
        else
                $sql = "select RESID, LAST_LOGIN_DT,MOD_DT,PRIVILAGE from PSWRDS where USERNAME= binary '$id' and PASSWORD='$pass'  AND ACTIVE='Y'";
	$res = mysql_query_decide($sql) or die("Could not Execute query pwdError");
	$count = mysql_num_rows($res);

	if ($count > 0)
	{
		$myrow = mysql_fetch_array($res);
		$recid = $myrow["RESID"];
		$priv = $myrow["PRIVILAGE"];
		$agentName = $id;
		if(strstr($priv,'OB'))
                {
                        setcookie("OPERATOR",$id,0,"/",$domain);
                }
		if($company!="IV")
		{
			$mod_dt=$myrow["MOD_DT"];
			$last_login_dt=$myrow["LAST_LOGIN_DT"];

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
		}

		$tm = time();

		if($expire)
		{
			$sql="UPDATE jsadmin.PSWRDS SET ACTIVE='N' WHERE RESID='$recid'";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$ret="-1";
			
		}
		else
		{
			$sql="delete from CONNECT where TIME < ($tm - $TOUT)";
			$result = mysql_query_decide($sql);// or mail("alok@jeevansathi.com","Mail from jsadmin/connect.inc","SQL is : $sql\nError is : ".mysql_error_js());

			$sql="UPDATE jsadmin.PSWRDS SET LAST_LOGIN_DT=NOW() WHERE RESID='$recid'";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());
		
			$sqlup="insert into CONNECT (ID, USER, IPADDR, TIME) values ('','$recid','$ip','$tm')";
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
 	$sql_chk = "select * from CONNECT where ID=$userno";
	$res_chk = mysql_query_decide($sql_chk) or die("Could not Authenticate User: auError $sql_chk ".mysql_error_js());
 	$count = mysql_num_rows($res_chk);

	if ($count > 0)
	{
		$myrow = mysql_fetch_array($res_chk);

		if (time()-$myrow["TIME"] < $TOUT)
		{
			$tm = time();
			$sql_up = "update CONNECT set TIME=$tm where ID=$userno";
			$res_up = mysql_query_decide($sql_up) or die("Could not Authenticate User: upError");

			$ret["ID"] = $md."i".$userno;
                        $ret["USER"]=$myrow["USER"];
                                
			//$ret = TRUE;
			$cna = preg_replace('/[^A-Za-z0-9\. -_]/', '', $_COOKIE["CRM_NOTIFICATION_AGENT"]);
			setLoginCookies($ret["ID"],$cna);
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

function privarray($cid) //lavesh
{
        global $smarty;
        //adde
	/*
        if(authenticated($cid))
        {
        $name= getname($cid);
        $sql="SELECT USERNAME,GENDER from newjs.JPROFILE where PROFILEID='$profileid'";
        $result=mysql_query_decide($sql);
        $myrow=mysql_fetch_array($result);
        $checksum=md5($profileid)."i".$profileid;
        profileview($profileid,$checksum);
        }*/
        //end

	$privilage = getprivilage($cid);
        $priv = explode("+",$privilage);
        $sql = "select VALUE from jsadmin.PRIVILAGE WHERE ACTIVE='Y' AND COMPANY='IV'";
        $result = mysql_query_decide($sql) or die("Could not return data from PRIVILAGE. $sql ".mysql_error_js());
	if($myrow=mysql_fetch_array($result))
                {
                        $i=0;
                        do
                        {
                        $a[$i]=$myrow["VALUE"];
                        $i++;
                        }while($myrow=mysql_fetch_array($result));
                }
                if(in_array('IVBU',$a))
                {
                        if(in_array('IVBU',$priv))
                        {
                                 $linkarr[]="<a href=\"#\">Billing</a>";
                        }
                }
                                                                                                                             
                if(in_array('IVRU',$a))
                {
                         if(in_array('IVRU',$priv))
                         {
                                $linkarr[]="<a href=\"inf_offline_registration.php?cid=$cid\">Register</a>";
                         }
                }
                                                                                                                             
                if(in_array('IVEU',$a))
                {
                        if(in_array('IVEU',$priv))
                        {
                                 $linkarr[]="<a href=\"inf_editprofile.php?name=$username&cid=$cid\">Edit Profile</a>";
			}
		}		           
	       if(in_array('IVCU',$a))
                {
                        if(in_array('IVCU',$priv))
                        {
                                $linkarr[]="<a href=\"#\">Pick Up Check</a>";
                        }
                }
                                                                                                                             
                if(in_array('IVTU',$a))
                {
                        if(in_array('IVTU',$priv))
                        {
                                 $linkarr[]="<a href=\"#\">Track Status</a>";
                        }
                }
                                                                                                                             
                if(in_array('IVMU',$a))
                {
                        if(in_array('IVMU',$priv))
                        {
                                 $linkarr[]="<a href=\"#\">MIS</a>";
                        }
                }
                                                                                                                             
                if(in_array('IVBA',$a))
                {
                         if(in_array('IVBA',$priv))
                        {
                                $linkarr[]="<a href=\"inf_managebackend.php?name=$username&cid=$cid\">Manage Backend</a>";
                        }
                }
		if(in_array('IVBC',$a))
                {
                        if(in_array('IVBC',$priv))
                        {                            
				 $linkarr[]="<a href=\"inf_bounce_cheque.php?&cid=$cid\">Bounce Cheque</a>";
                        }
                }
		
		if(in_array('IVCS',$a))
                {
                        if(in_array('IVCS',$priv))
                        {
                                $linkarr[]="<a href=\"inf_list_csv.php?bounce=1&cid=$cid\"> Download Csv</a>";
                        }
                }
	
		if(in_array('IVE1',$a))
                {
                        if(in_array('IVE1',$priv))
                        {
                                $linkarr[]="<a href=\"inf_list_csv.php?days=10&subscription=1&cid=$cid\">Subscription Expiry Criteria</a>";
                        }
                }

		/*if(in_array('IVSE',$a))
                {
                        if(in_array('IVSE',$priv))
                        {
                                $linkarr[]="<a href=\"inf_subscription_expiry.php?name=$username&days=10&cid=$cid\">Downloading Subscription Expiry After 10 days Csv</a>";
                        }
                }*/
		
/*		if(in_array('IVE2',$a))
                {
                        if(in_array('IVE2',$priv))
                        {
                                $linkarr[]="<a href=\"#?name=$username&days=30&cid=$cid\">Subscription Expiry After 30 days </a>";
                        }
                }
*/
		/*if(in_array('IVSS',$a))
                {
                        if(in_array('IVSS',$priv))
                        {
                                $linkarr[]="<a href=\"inf_subscription_expiry.php?name=$username&days=30&cid=$cid\">Downloading Subscription Expiry After 30 days Csv</a>";
                        }
                }*/


		$linkarr[]="<a href=\"retrieve_search.php?name=$username&cid=$cid\">Retrieve Search</a>";
		$linkarr[]="<a href=\"inf_mobile_search.php?name=$username&cid=$cid\">Search on Mobile No.</a>";
		$linkarr[]="<a href=\"/profile/contact.php?name=$username&cid=$cid\" target=\"_blank\">Contact Us</a>";
		$linkarr[]="<a href=\"/profile/mem_comparison.php?name=$username&cid=$cid\" target=\"_blank\">Memberships</a>";
		//$linkarr[]="<a href=\"vpin.php?name=$username&cid=$cid\">Change VPIN</a>";
		$smarty->assign("linkarr",$linkarr);
}
                                                                                                                             


/*function logout($checksum)
{
	global $TOUT;

	list($md, $userno)=explode("i",$checksum);
        if(md5($userno)!=$md)
            return FALSE;
        else
        {
		$sql_chk = "delete from CONNECT where ID='$userno'";
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
		$sql_usr = "select USER from CONNECT where ID=$userno";
		$res_usr = mysql_query_decide($sql_usr) or die("Could not return data from Connect1. $sql_usr ".mysql_error_js());
		$count = mysql_num_rows($res_usr);

		if ($count > 0)
		{
			$myrow = mysql_fetch_array($res_usr);
			$ret_usr = $myrow["USER"];
		
			$sql_getusr = "select USERNAME from PSWRDS where RESID=$ret_usr";
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
         
		$sql_usr = "select USER from CONNECT where ID=$userno";
		$res_usr = mysql_query_decide($sql_usr) or die("Could not return data from Connect1.");
		$count = mysql_num_rows($res_usr);

		if ($count > 0)
		{
			$myrow = mysql_fetch_array($res_usr);
			$ret_usr = $myrow["USER"];
		
			$sql_getusr = "select PRIVILAGE from PSWRDS where RESID=$ret_usr";
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
}

*/

/*function getcenter($connection, $ip)
{
	global $TOUT;

	$sql_usr = "select USER from CONNECT where ID=$connection and IPADDR='$ip'";
	$res_usr = mysql_query_decide($sql_usr) or die("Could not return data from Connect1.");
	$count = mysql_num_rows($res_usr);

	if ($count > 0)
	{
		$myrow = mysql_fetch_array($res_usr);
		$ret_usr = $myrow["USER"];
		
		$sql_getusr = "select CENTER from PSWRDS where RESID=$ret_usr";
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

return $ret;
}
*/

function getcenter_for_walkin($walkin)
{
        $sql_usr = "select center from jsadmin.PSWRDS where USERNAME='$walkin' ";
                                                                                                                             
        $res_usr = mysql_query_decide($sql_usr) or die("Could not return data from executive.".mysql_error_js());
                                                                                                                             
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


/*function get_operator_detail($checksum)
{
        global $TOUT;
         list($md, $userno)=explode("i",$checksum);
        if(md5($userno)!=$md)
               return FALSE;
        else
        {
                $sql_usr = "select USER from CONNECT where ID=$userno";
                $res_usr = mysql_query_decide($sql_usr) or die("Could not return data from Connect1.");
                $count = mysql_num_rows($res_usr);

                if ($count > 0)
                {
                        $myrow = mysql_fetch_array($res_usr);
                        $ret_usr = $myrow["USER"];

                        $sql_getusr = "select EMAIL,USERNAME,PHONE from PSWRDS where RESID=$ret_usr";
                        $res_getusr = mysql_query_decide($sql_getusr) or die("Could not return data from Pswrds.");
                        $count_usr = mysql_num_rows($res_getusr);

                        if($count_usr > 0)
                        {
                                $myrow_usr = mysql_fetch_array($res_getusr);
                                $ret["EMAIL"] = $myrow_usr["EMAIL"];
				$ret["USERNAME"] = $myrow_usr["USERNAME"];
				$ret["PHONE"] = $myrow_usr["PHONE"];	
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


/*function getemail($checksum)
{
	global $TOUT;
	 list($md, $userno)=explode("i",$checksum);
        if(md5($userno)!=$md)
	       return FALSE;
	else
	{
		$sql_usr = "select USER from CONNECT where ID=$userno";
		$res_usr = mysql_query_decide($sql_usr) or die("Could not return data from Connect1.");
		$count = mysql_num_rows($res_usr);
	
		if ($count > 0)
		{
			$myrow = mysql_fetch_array($res_usr);
			$ret_usr = $myrow["USER"];
			
			$sql_getusr = "select EMAIL,USERNAME from PSWRDS where RESID=$ret_usr";
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
	}

return $ret;
}*/

function current_date()
{
	$tm = date("Y-m-d",time());
	return $tm;
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

function my_format_date($day,$month,$year,$mem_case=1)
{
	if($month=="01" || $month=="1")
		$month="Jan";
	elseif($month=="02" || $month=="2")
		$month="Feb";
	elseif($month=="03" || $month=="3")
		$month="Mar";
	elseif($month=="04" || $month=="4")
		$month="Apr";
	elseif($month=="05" || $month=="5")
		$month="May";
	elseif($month=="06" || $month=="6")
		$month="Jun";
	elseif($month=="07" || $month=="7")
		$month="Jul";
	elseif($month=="08" || $month=="8")
		$month="Aug";
	elseif($month=="09" || $month=="9")
		$month="Sep";
	elseif($month=="10")
		$month="Oct";
	elseif($month=="11")
		$month="Nov";
	elseif($month=="12")
		$month="Dec";
		
	if(strlen($day)==1)
		$day= "0" . $day;
	if($mem_case==2)		
		return $day." ".$month." ".$year;
	else
		return $month . " " . $day . ", " . $year;
}

function create_dd($selected,$cname,$minormax=0,$labelselect="",$selectedVal="")
{
                                                                                                 
        if(is_array($selected))
        {
                $s_arr = $selected;
                //$selected = array();
        }
        elseif($selected!="")
        {
                $s_arr=explode(",",$selected);
        }
        else
                $s_arr=array();
                                                                                                
        $muli ="[]";
	if ($cname == "top_country")
        {
                $sql = "select SQL_CACHE VALUE, LABEL from newjs.TOP_COUNTRY order by SORTBY";
                $res = mysql_query_decide($sql) or logError("error",$sql);
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
        if ($cname == "Bank")
        {
                $sql = "select SQL_CACHE NAME from billing.BANK";
                $res = mysql_query_decide($sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["NAME"],$s_arr))
                        {
      $ret .= "<option value=\"$myrow[NAME]\" selected>$myrow[NAME]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[NAME]\">$myrow[NAME]</option>\n";                        }
                }
        }
        if ($cname == "Walkin")
        {
		$sql = "select SQL_CACHE USERNAME from jsadmin.PSWRDS where PRIVILAGE LIKE '%BU%' and ACTIVE='Y' UNION SELECT USERNAME from jsadmin.PSWRDS where PRIVILAGE LIKE '%OB%' and ACTIVE='Y' UNION SELECT USERNAME from jsadmin.PSWRDS where PRIVILAGE LIKE '%SE%' and ACTIVE='Y'";
                $res = mysql_query_decide($sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["USERNAME"],$s_arr))
                        {
                                $ret .= "<option value=\"$myrow[USERNAME]\" selected>$myrow[USERNAME]</option>\n";
                        }
                        else
                        {
     $ret .= "<option value=\"$myrow[USERNAME]\">$myrow[USERNAME]</option>\n";                        }
                }
        }
        if ($cname == "privilege")
        {
                $sql = "select SQL_CACHE VALUE , LABEL from PRIVILAGE";
                $res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
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
	//added by lavesh
	if ($cname == "privilege1")
        {
                $sql = "select SQL_CACHE VALUE , LABEL from PRIVILAGE WHERE COMPANY='IV'";
                $res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
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
	//end modification
	if ($cname == "Source")
        {
                $sql = "select SQL_CACHE GROUPNAME, SourceID from MIS.SOURCE order by GROUPNAME, SourceID";
                $res = mysql_query_decide($sql);
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["SourceID"],$s_arr))
                        {
                                $ret .= "<option value=\"$myrow[SourceID]\" selected>$myrow[GROUPNAME]-$myrow[SourceID]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[SourceID]\">$myrow[GROUPNAME]-$myrow[SourceID]</option>\n";
                        }
                }
        }


        if ($cname == "branch")
        {
                $sql = "select SQL_CACHE LABEL from incentive.LOCATION";
                $res = mysql_query_decide($sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array(strtoupper($myrow["NAME"]),$s_arr))
                        {
      				$ret .= "<option value=\"$myrow[NAME]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[NAME]\">$myrow[LABEL]</option>\n";
                        }
                }
        }

        if ($cname == "sub_location")
        {
                $sql = "select SQL_CACHE VALUE,LABEL from incentive.SUB_LOCATION";
		if($selectedVal)
			$sql .=" WHERE PRIORITY='$selectedVal'";
                $res = mysql_query_decide($sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array(strtoupper($myrow["VALUE"]),$s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
                }
        }


	if ($cname == "Education_Level")
        {
                $sql = "select SQL_CACHE VALUE, LABEL from newjs.EDUCATION_LEVEL order by SORTBY";
                $res = mysql_query_decide($sql) or logError("error",$sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["VALUE"], $s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
                }
        }
	if ($cname == "Education_Level_New")
        {
                $sql = "select SQL_CACHE VALUE, LABEL from newjs.EDUCATION_LEVEL_NEW order by SORTBY";
                $res = mysql_query_decide($sql) or logError("error",$sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["VALUE"], $s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }                         else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
                }
        }
	if ($cname == "Caste")
        {
		//REVAMP JS_DB_CASTE
include_once(JsConstants::$docRoot."/commonFiles/RevampJsDbFunctions.php");
                $sql = "select SQL_CACHE VALUE , ISALL , LABEL from newjs.CASTE order by SORTBY";
		//REVAMP JS_DB_CASTE
                $res = mysql_query_decide($sql) or logError("error",$sql);
                $ret="";
                while($myrow = mysql_fetch_array($res))
                {
                        if ($myrow['ISALL'] == 'Y')
                        {
                                $class = "dropbg";
                        }
			//REVAMP JS_DB_CASTE
                        elseif (is_part_of_a_group($myrow['VALUE']))
                        {
                                $class = "dropcolour";
                        }
                        else
                                $class = "";
                                                                                                                            
                        if(in_array($myrow["VALUE"],$s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" class=$class selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" class=$class>$myrow[LABEL]</option>\n";
                        }
                }
        }
	if ($cname == "Mtongue")
        {
                if($labelselect=="small")
                        $sql = "select SQL_CACHE VALUE, SMALL_LABEL from newjs.MTONGUE order by SORTBY";
                else
                        $sql = "select SQL_CACHE VALUE, LABEL from newjs.MTONGUE order by SORTBY";
                $res = mysql_query_decide($sql) or logError("error",$sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["VALUE"],$s_arr))
                        {
                                if($labelselect=="small")
                                        $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[SMALL_LABEL]</option>\n";
                                else
                                        $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                if($labelselect=="small")
                                        $ret .= "<option value=\"$myrow[VALUE]\">$myrow[SMALL_LABEL]</option>\n";
                                else
                                        $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
                }
        }
	if ($cname == "Height")
        {
                $sql = "select SQL_CACHE VALUE, LABEL from newjs.HEIGHT order by SORTBY";
                if($minormax ==1)
                        $sql .= " desc";
                $res = mysql_query_decide($sql) or logError("error",$sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["VALUE"], $s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }                 }
        }
	if ($cname == "Occupation")
        {                 $sql = "select SQL_CACHE VALUE, LABEL from newjs.OCCUPATION order by SORTBY";
                $res = mysql_query_decide($sql) or logError("error",$sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {                         if(in_array($myrow["VALUE"], $s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
                }
        }
	if ($cname == "Country_Residence")
        {
                $sql = "select SQL_CACHE VALUE, LABEL from newjs.COUNTRY_NEW order by ALPHA_ORDER";
                $res = mysql_query_decide($sql) or logError("error",$sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["VALUE"], $s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
                }
        }
	if ($cname == "top_mtongue")
        {
                $sql = "select SQL_CACHE VALUE, LABEL from newjs.TOP_MTONGUE order by SORTBY";
                $res = mysql_query_decide($sql) or logError("error",$sql);
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
	if ($cname == "Income")
        {
                $sql = "select SQL_CACHE VALUE, LABEL from newjs.INCOME where VISIBLE <> 'N' order by SORTBY";
                $res = mysql_query_decide($sql) or logError("error",$sql);
                $ret = "";
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["VALUE"], $s_arr))
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" selected>$myrow[LABEL]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
                        }
                }
        }
	if ($cname == "City_USA")
    	{
        	$sql = "SELECT SQL_CACHE VALUE, LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = 128 ORDER BY SORTBY";
        	$res = mysql_query_decide($sql) or logError("error",$sql);
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
	
        return $ret;
}

function pop_location_with_sublocation($sel_val=0)
{
        $j=0;
        $sql_d="SELECT ID,NAME,VALUE from incentive.LOCATION";
        $result_d= mysql_query_decide($sql_d) or die(mysql_error_js());
        while($myrow_d=mysql_fetch_row($result_d))
        {
                $location_arr[$j]['label'] = "$myrow_d[1]";
                $location_arr[$j]['val'] = "$myrow_d[2]";
                $j++;
        }

        for ($i = 0,$j=0;$j<count($location_arr);$j++)
        {
                $val = $location_arr[$j]['val'];
                $strtemp = '';
                $location_value[]=$location_arr[$j]['val'];
                $location_label[]=$location_arr[$j]['label'];

                $strtemp .= $location_value[$j]."|X|";

               $sql="SELECT VALUE,LABEL from incentive.SUB_LOCATION where PRIORITY='$val'";
                $result1= mysql_query_decide($sql) or die(mysql_error_js());

                while($myrow1=mysql_fetch_row($result1))
                {
                	$reason_value[]="$myrow1[0]";
                        $reason_label[]="$myrow1[1]";
                        $strtemp .= $reason_value[$i]."$".$reason_label[$i]."#";
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
                        $newstr.="<option value=\"" . $str[$x] . "\" selected>" . $location_label[$x] . "</option>\n";
                else
                        $newstr.="<option value=\"" . $str[$x] . "\">" . $location_label[$x] . "</option>\n";
        }
        return $newstr;
}

function getLabel_value($sel_value,$selectParam,$getParam,$tablename,$database)
{
	$sel_label =strtoupper($sel_value);
	$sql ="select SQL_CACHE $getParam from $database.$tablename where $selectParam='$sel_value'";
       	$res =mysql_query_decide($sql) or die("error  ".$sql) ;
       	$row= mysql_fetch_array($res);
	$value =$row["$getParam"];
	return $value;
}

function label_select($columnname,$value,$database="")
{
	global $db;
	if($database=="")
		$database="newjs";

	$tablearr=array("CASTE","OCCUPATION","HEIGHT","CITY_INDIA","CITY_USA","COUNTRY","RELIGION","MTONGUE","EDUCATION_LEVEL_NEW","EDUCATION_LEVEL","INCOME","FAMILY_BACK","MOTHER_OCC","MTONGUE_S");

        if(in_array($columnname,$tablearr))
        {
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
                if($columnname=="RELIGION")
                        return array(${$columnname . S}[$value]);
                elseif($columnname=="MTONGUE_S")
                        return array($MTONGUE_DROP_SMALL[$value]);
                else
                        return array(${$columnname . _DROP}[$value]);
        }
        elseif(in_array(str_replace("newjs.","",$columnname),$tablearr))
        {
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
                $columnname=str_replace("newjs.","",$columnname);
                if($columnname=="RELIGION")
                        return array(${$columnname . S}[$value]);
                else
                        return array(${$columnname . _DROP}[$value]);
        }
        else
        {
                $sql = "select SQL_CACHE LABEL from $database.$columnname WHERE VALUE='$value'";
                $res = mysql_query_decide($sql) or die("error  ".$sql) ;
                $myrow= mysql_fetch_row($res);
                return $myrow;
        }
}


if(!function_exists("logError"))
{
function logError($message,$query="",$critical="exit", $sendmailto="NO")
{
	global $db, $smarty, $checksum;
        ob_start();
        var_dump($_SERVER);
        $ret_val = ob_get_contents();
        ob_end_clean();

        $errorstring="\n" . date("Y-m-d G:i:s",time() + 37800) . "\nErrorMsg: $message\nMysql Error: " . addslashes(mysql_error_js()) ."\nMysql Error Number:". mysql_errno_js()."\nSQL: $query\n#User Agent : " . $_SERVER['HTTP_USER_AGENT'] . "\n #Referer : " . $_SERVER['HTTP_REFERER'] . " \n #Self :  ".$_SERVER['PHP_SELF']."\n #Uri : ".$_SERVER['REQUEST_URI']."\n #Method : ".$_SERVER['REQUEST_METHOD']."\n";

        error_log($errorstring,3,JsConstants::$docRoot . "/jsadmin/temp/logerror.txt");
	LoggingWrapper::getInstance()->sendLog(LoggingEnums::LOG_ERROR, new Exception($errorstring));

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
                                        $data[$j]["NAME"]=htmlspecialchars($key, ENT_QUOTES, false);
                                        if(is_array($value))
                                        {
        	                                $data[$j]["VALUE"]="ARRAY";
                                                $i=0;
                		                foreach($value as $val)
                                	            if($val != "")
                                                    {
	                                                $data[$j][$i++]=htmlspecialchars($val, ENT_QUOTES, false);
        	                                    }
                                        }
                                        else
                                                $data[$j]["VALUE"]=htmlspecialchars($value, ENT_QUOTES, false);
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
                                        $data[$j]["NAME"]=htmlspecialchars($key, ENT_QUOTES, false);
                                        if(is_array($value))
                                        {
                	                        $data[$j]["VALUE"]="ARRAY";
                                                $i=0;
                        		        foreach($value as $val)
                                        	    if($val != "")
                                                    {
                                                	$data[$j][$i++]=htmlspecialchars($val, ENT_QUOTES, false);
                                            	    }
                                        }
                                        else
                                                $data[$j]["VALUE"]=htmlspecialchars($value, ENT_QUOTES, false);
                                        $j++;
                                }//if
                        }//foreach
                }
                $smarty->assign("DATA",$data);
                $smarty->assign("ACTION",$_SERVER['PHP_SELF']);
                $smarty->assign("CHECKSUM",$checksum);
                $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
                $smarty->assign("HEAD",$smarty->fetch("head.htm"));
                $smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
                $smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));

                $smarty->assign("msg_error", $message);
		$smarty->display("error_template.htm");
                exit;
        }
        elseif($critical!="continue")
        {
                echo $message;
        }
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
}
*/

function getIST($time_est)
{
        $ISTtime=strftime("%Y-%m-%d %H:%M",JSstrToTime("$time_est + 10 hours 30 minutes"));
        return $ISTtime;
}

function get_category($category)
{
        $catstr='Top';
        $catarr=explode(".",$category);
        for($i=0;$i<count($catarr)-1;$i++)
        {
                $sql="SELECT QUESTION FROM feedback.QADATA WHERE PARENT='$catarr[$i]'";
                $res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                if($row=mysql_fetch_array($res))
                        $catstr.="->".$row['QUESTION'];
        }
        return $catstr;
}

/*
function deletecontacts($profileid)
{

        //$sql="select CONTACTID from newjs.CONTACTS where SENDER='$profileid'";
	//modified by sriram for updation in the leftpanel
	$sql="select CONTACTID, TYPE, RECEIVER from newjs.CONTACTS where SENDER='$profileid'";
        $result=mysql_query_decide($sql);

        while($myrow=mysql_fetch_array($result))
        {
                $contactid=$myrow["CONTACTID"];

                $sql="insert ignore into newjs.DELETED_PROFILE_CONTACTS select * from newjs.CONTACTS where CONTACTID='$contactid'";
                $res=mysql_query_decide($sql);

                if($res)
                {
                        $sql="delete from newjs.CONTACTS where CONTACTID='$contactid'";
                        mysql_query_decide($sql);
			//added by sriram,lavesh for updation of fields in leftpanel
			if($myrow['TYPE']!='C')
			{
				if($myrow['TYPE']=='I')
					$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET OPEN_CONTACTS=OPEN_CONTACTS-1 WHERE PROFILEID='$myrow[RECEIVER]'";
				elseif($myrow['TYPE']=='A')
					 $sql_upd = "UPDATE newjs.CONTACTS_STATUS SET ACC_BY_ME=ACC_BY_ME-1 WHERE PROFILEID='$myrow[RECEIVER]'";
				elseif($myrow['TYPE']=='D')
					$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET DEC_BY_ME=DEC_BY_ME-1 WHERE PROFILEID='$myrow[RECEIVER]'";
				mysql_query_decide($sql_upd) or die(mysql_error_js());
			}
			//added by sriram for updation of fields in leftpanel
                }
        }

        mysql_free_result($result);

        //$sql="select CONTACTID from newjs.CONTACTS where RECEIVER='$profileid'";
	//modified by sriram for updation in the leftpanel
	$sql="select CONTACTID, TYPE, SENDER from newjs.CONTACTS where RECEIVER='$profileid'";
        $result=mysql_query_decide($sql);

        while($myrow=mysql_fetch_array($result))
        {
                $contactid=$myrow["CONTACTID"];

                $sql="insert ignore into newjs.DELETED_PROFILE_CONTACTS select * from newjs.CONTACTS where CONTACTID='$contactid'";
                $res=mysql_query_decide($sql);

                if($res)
                {
                        $sql="delete from newjs.CONTACTS where CONTACTID='$contactid'";
                        mysql_query_decide($sql);
			//added by sriram,lavesh for updation of fields in leftpanel
			if($myrow['TYPE']!='C')
			{
				if($myrow['TYPE']=='I')
					$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET NOT_REP=NOT_REP-1 WHERE PROFILEID='$myrow[SENDER]'";
				elseif($myrow['TYPE']=='A')
					$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET ACC_ME=ACC_ME-1 WHERE PROFILEID='$myrow[SENDER]'";
				elseif($myrow['TYPE']=='D')
					$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET DEC_ME=DEC_ME-1 WHERE PROFILEID='$myrow[SENDER]'";
				mysql_query_decide($sql_upd) or die(mysql_error_js());
			}
			//added by sriram for updation of fields in leftpanel
                }
        }

        mysql_free_result($result);

        $sql="select ID from newjs.MESSAGE_LOG where SENDER='$profileid'";
        $result=mysql_query_decide($sql,"",1) or die("$sql".mysql_error_js());

        while($myrow=mysql_fetch_array($result))
        {
                $contactid=$myrow["ID"];

                $sql="insert ignore into newjs.DELETED_MESSAGE_LOG select * from newjs.MESSAGE_LOG where ID='$contactid'";
                $res=mysql_query_decide($sql,"",1);

                if($res)
                {
                        $sql="delete from newjs.MESSAGE_LOG where ID='$contactid'";
                        mysql_query_decide($sql,"",1);
                }
        }

         mysql_free_result($result);

        $sql="select ID from newjs.MESSAGE_LOG where RECEIVER='$profileid'";
        $result=mysql_query_decide($sql,"",1) or die("$sql".mysql_error_js());

        while($myrow=mysql_fetch_array($result))
        {
                $contactid=$myrow["ID"];

                $sql="insert ignore into newjs.DELETED_MESSAGE_LOG select * from newjs.MESSAGE_LOG where ID='$contactid'";
                $res=mysql_query_decide($sql,"",1);

                if($res)
                {
                        $sql="delete from newjs.MESSAGE_LOG where ID='$contactid'";
                        mysql_query_decide($sql,"",1);
                }
        }

        mysql_free_result($result);
	//added by sriram
	$sql_del = "DELETE FROM newjs.CONTACTS_STATUS WHERE PROFILEID='$profileid'";
	mysql_query_decide($sql_del);
}
*/

/************************************************************************************************************************
Changed By      :Shakti Srivastava
Change Date     :13 September, 2005
Reason          :This function was till now missing from this file and is quite usefull as we had to call addslashes() and
                :stripslashes() for every variable.
************************************************************************************************************************/
function maStripVARS($action)
{
        global $_GET, $_POST;

        if (get_magic_quotes_gpc() == 0)
        {
                if($action=="stripslashes")
                {
                        if (is_array($_GET))
                        {
                                while(list($k,$v) = each($_GET))
                        {
                                if(!is_array($v))
                                {
                                        $_GET[$k] = strip_tags(str_replace("\"","'",$v));
                                        $GLOBALS[$k] = strip_tags(str_replace("\"","'",$GLOBALS[$k]));
                                }
                        }
                        reset($_GET);
                        }
                        if (is_array($_POST))
                        {
                                while(list($k,$v) = each($_POST))
                        {
                                if(!is_array($v))
                                {
                                        $_POST[$k] = strip_tags(str_replace("\"","'",$v));
                                        $GLOBALS[$k] = strip_tags(str_replace("\"","'",$GLOBALS[$k]));
                                }
                        }
                        reset($_POST);
                        }
                        return;
                }
        }
        else
        {
                if($action=="addslashes")
                {
                        if (is_array($_GET))
                        {
                                while(list($k,$v) = each($_GET))
                        {
                                if(!is_array($v))
                                {
                                        $_GET[$k] = strip_tags(str_replace("\"","'",$v));
                                        $GLOBALS[$k] = strip_tags(str_replace("\"","'",$GLOBALS[$k]));
                                }
                        }
                        reset($_GET);
                        }
                        if (is_array($_POST))
                        {
                                while(list($k,$v) = each($_POST))
                        {
                                if(!is_array($v))
                                {
                                        $_POST[$k] = strip_tags(str_replace("\"","'",$v));
                                        $GLOBALS[$k] = strip_tags(str_replace("\"","'",$GLOBALS[$k]));
                                }
                        }
                        reset($_POST);
                        }
                        return;
                }
        }
 if (is_array($_GET))
        {
                while(list($k,$v) = each($_GET))
        {
                if(!is_array($v))
                {
                        if($action=="stripslashes")
                        {
                                $_GET[$k] = strip_tags(str_replace("\"","'",stripslashes($v)));
                                $GLOBALS[$k] = strip_tags(str_replace("\"","'",stripslashes($GLOBALS[$k])));
                        }

                        if($action=="addslashes")
                        {
                                $_GET[$k] = strip_tags(str_replace("\"","'",addslashes($v)));
                                $GLOBALS[$k] = strip_tags(str_replace("\"","'",addslashes($GLOBALS[$k])));
                        }
                }
        }
        reset($_GET);
        }
        if (is_array($_POST))
        {
                while(list($k,$v) = each($_POST))
        {
                if(!is_array($v))
                {
                        if($action=="stripslashes")
                        {
                                $_POST[$k] = strip_tags(str_replace("\"","'",stripslashes($v)));
                                $GLOBALS[$k] = strip_tags(str_replace("\"","'",stripslashes($GLOBALS[$k])));
                        }

                        if($action=="addslashes")
                        {
                                $_POST[$k] = strip_tags(str_replace("\"","'",addslashes($v)));
                                $GLOBALS[$k] = strip_tags(str_replace("\"","'",addslashes($GLOBALS[$k])));
                        }
                }
        }
        reset($_POST);
        }
}
/*
function retrievecontacts($profileid)
{
        //$sql="select CONTACTID from newjs.DELETED_PROFILE_CONTACTS as a,newjs.JPROFILE as b where a.SENDER='$profileid' and a.RECEIVER=b.PROFILEID and b.ACTIVATED<>'D'";
	//modified by sriram
        $sql="select CONTACTID, TYPE, RECEIVER from newjs.DELETED_PROFILE_CONTACTS as a,newjs.JPROFILE as b where a.SENDER='$profileid' and a.RECEIVER=b.PROFILEID and b.ACTIVATED<>'D'";
        $result=mysql_query_decide($sql);
                                                                                                                            
        while($myrow=mysql_fetch_array($result))
        {
                $contactid=$myrow["CONTACTID"];
                                                                                                                            
                $sql="insert ignore into newjs.CONTACTS select * from newjs.DELETED_PROFILE_CONTACTS where CONTACTID='$contactid'";
                $res=mysql_query_decide($sql) or die(mysql_error_js());
                                                                                                                            
                if($res)
                {
                        $sql="delete from newjs.DELETED_PROFILE_CONTACTS where CONTACTID='$contactid'";
                        mysql_query_decide($sql) or die(mysql_error_js());

			//added by sriram,lavesh for updatation of fields in Leftpanel
			if($myrow['TYPE']!='C')
			{
				if($myrow['TYPE']=='I')
					 $sql_upd = "UPDATE newjs.CONTACTS_STATUS SET OPEN_CONTACTS=OPEN_CONTACTS+1 WHERE PROFILEID='$myrow[RECEIVER]'";
				elseif($myrow['TYPE']=='D')
					$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET DEC_BY_ME=DEC_BY_ME+1 WHERE PROFILEID='$myrow[RECEIVER]'";
				elseif($myrow['TYPE']=='A')
					$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET ACC_BY_ME=ACC_BY_ME+1 WHERE PROFILEID='$myrow[RECEIVER]'";
				mysql_query_decide($sql_upd) or die("$sql_upd".mysql_error_js());
			}
			//added by for sriram updatation of fields in Leftpanel
                }
	}
                                                                                                                            
        mysql_free_result($result);
                                                                                                                            
        //$sql="select CONTACTID from newjs.DELETED_PROFILE_CONTACTS as a,newjs.JPROFILE as b where a.RECEIVER='$profileid' and a.SENDER=b.PROFILEID and b.ACTIVATED<>'D' ";
        $sql="select CONTACTID, TYPE, SENDER from newjs.DELETED_PROFILE_CONTACTS as a,newjs.JPROFILE as b where a.RECEIVER='$profileid' and a.SENDER=b.PROFILEID and b.ACTIVATED<>'D' ";
        $result=mysql_query_decide($sql) or die(mysql_error_js());
                                                                                                                            
        while($myrow=mysql_fetch_array($result))
        {
                $contactid=$myrow["CONTACTID"];
                                                                                                                            
                $sql="insert ignore into newjs.CONTACTS select * from newjs.DELETED_PROFILE_CONTACTS where CONTACTID='$contactid'";
                $res=mysql_query_decide($sql) or die(mysql_error_js());
                                                                                                                            
                if($res)
                {
                        $sql="delete from newjs.DELETED_PROFILE_CONTACTS where CONTACTID='$contactid'";
                        mysql_query_decide($sql) or die(mysql_error_js());
			//added by sriram,lavesh for updatation of fields in Leftpanel
                        if($myrow['TYPE']!='C')
			{
                        	if($myrow['TYPE']=='I')
					 $sql_upd = "UPDATE newjs.CONTACTS_STATUS SET NOT_REP=NOT_REP+1 WHERE PROFILEID='$myrow[SENDER]'";
				elseif($myrow['TYPE']=='D')
					$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET DEC_ME=DEC_ME+1 WHERE PROFILEID='$myrow[SENDER]'";
				elseif($myrow['TYPE']=='A')
					$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET ACC_ME=ACC_ME+1 WHERE PROFILEID='$myrow[SENDER]'";
				mysql_query_decide($sql_upd) or die("$sql_upd".mysql_error_js());
			}
                        //added by for sriram updatation of fields in Leftpanel
                }
        }
                                                                                                                            
        mysql_free_result($result);

        $sql="select ID from newjs.DELETED_MESSAGE_LOG as a,newjs.JPROFILE as b where a.SENDER='$profileid' and a.RECEIVER=b.PROFILEID and b.ACTIVATED<>'D'";
        $result=mysql_query_decide($sql,"",1);
                                                                                                                            
        while($myrow=mysql_fetch_array($result))
        {
                $contactid=$myrow["ID"];
                                                                                                                            
                $sql="insert ignore into newjs.MESSAGE_LOG select * from newjs.DELETED_MESSAGE_LOG where ID='$contactid'";
                $res=mysql_query_decide($sql,"",1) or die(mysql_error_js());
                
		if($res)
                {
                        $sql="delete from newjs.DELETED_MESSAGE_LOG where ID='$contactid'";
                        mysql_query_decide($sql,"",1) or die(mysql_error_js());
                }
	}
                                                                                                                            
        mysql_free_result($result);

        $sql="select ID from newjs.DELETED_MESSAGE_LOG as a,newjs.JPROFILE as b where a.RECEIVER='$profileid' and a.SENDER=b.PROFILEID and b.ACTIVATED<>'D'";
        $result=mysql_query_decide($sql,"",1);
                                                                                                                            
        while($myrow=mysql_fetch_array($result))
        {
                $contactid=$myrow["ID"];
                                                                                                                            
                $sql="insert ignore into newjs.MESSAGE_LOG select * from newjs.DELETED_MESSAGE_LOG where ID='$contactid'";
                $res=mysql_query_decide($sql,"",1) or die(mysql_error_js());
                                                                                                                            
                if($res)
                {
                        $sql="delete from newjs.DELETED_MESSAGE_LOG where ID='$contactid'";
                        mysql_query_decide($sql,"",1) or die(mysql_error_js());
                }
	}
                                                                                                                            
        mysql_free_result($result);
}
*/
function get_rev_saleid($saleid)
{
        $billid="JR-".$saleid;
        return $billid;
}
function get_email($user,$db_slave)
{
        $sql="SELECT EMAIL from jsadmin.PSWRDS WHERE USERNAME='$user' and ACTIVE='Y'";
        $res=mysql_query_decide($sql,$db_slave) or die(mysql_error_js());
        $row=mysql_fetch_array($res);
        $email=$row['EMAIL'];
	if($email=='')
        {
                if($user=='ONLINE' || $user=='OFFLINE')
                        $email="anil.rawat@naukri.com";
                elseif($user=='ARAMEX')
                        $email="rajeev@jeevansathi.com";
                /*else
                        $email='alok@jeevansathi.com';*/
        }
        return $email;
}
                                                                                                                             
function get_boss_email($user,$db_slave)
{
        $sql="SELECT b.EMAIL FROM jsadmin.PSWRDS AS a, jsadmin.PSWRDS AS b WHERE b.EMP_ID = a.HEAD_ID AND a.USERNAME = '$user'";
        $res=mysql_query_decide($sql,$db_slave) or die(mysql_error_js());
        $row=mysql_fetch_array($res);
        $email=$row['EMAIL'];
        if($email=='')
        	$email='alok@jeevansathi.com';
        return $email;
}

//Created By lavesh rawat on 30 august as Caste is selected+populated on basis of religion and caste
function caste_populate_religion($religion,$Caste)
{
        if(!$Caste && $religion)
        {
                $class = "dropcolour";
                $ret .= "<option value=\"\" caste =$class selected>Select any one option</option>\n";
        }
        
	//REVAMP JS_DB_CASTE
include_once(JsConstants::$docRoot."/commonFiles/RevampJsDbFunctions.php");
                                                                                                             
        $sql= "SELECT SQL_CACHE VALUE,LABEL , ISALL from newjs.CASTE WHERE PARENT='$religion' ORDER BY SORTBY";
        //REVAMP JS_DB_CASTE
        $result=mysql_query_decide($sql);
        while($myrow=mysql_fetch_array($result))
        {
		$value_1=$myrow["VALUE"];

		$one_caste=in_array($value_1,array(153,148,1,162));
		$caste_sameas_religion=in_array($value_1,array(14,149,2,154,173));

		if(!$caste_sameas_religion || $one_caste)
                {
                        if ($myrow['ISALL'] == 'Y')
                        {
                                $class = "dropbg";
                        }
        		//REVAMP JS_DB_CASTE
                        elseif (is_part_of_a_group($myrow['VALUE']))
                        {
                                $class = "dropcolour";
                        }
                        else
                                $class = "";
                        if($myrow["VALUE"]==$Caste)
                        {
                                //selected option
                                if($class=='')
                                        $class = "dropcolour";
                                $ret .= "<option value=\"$myrow[VALUE]\" caste =$class selected>$myrow[LABEL]</option>\n";
                        }
			else
                        {
                                $ret .= "<option value=\"$myrow[VALUE]\" caste =$class>$myrow[LABEL]</option>\n";
                        }
                }
                else
                {
                        $class="dropbg";
                        $ret .= "<option value=\"0\" caste =$class disabled>$myrow[LABEL]</option>\n";
                }
        }
return $ret;
}

if(!function_exists("populate_day_month_year"))
{
	function populate_day_month_year()
	{
		global $smarty;
		for($i=1;$i<=31;$i++)
			$ddarr[] = $i;
																     
		$smarty->assign("ddarr",$ddarr);
																     
		$mmarr = array(
				array('LABEL' => 'January', 'VALUE' => '1'),
				array('LABEL' => 'February', 'VALUE' => '2'),
				array('LABEL' => 'March', 'VALUE' => '3'),
				array('LABEL' => 'April', 'VALUE' => '4'),
				array('LABEL' => 'May', 'VALUE' => '5'),
				array('LABEL' => 'June', 'VALUE' => '6'),
				array('LABEL' => 'July', 'VALUE' => '7'),
				array('LABEL' => 'August', 'VALUE' => '8'),
				array('LABEL' => 'September', 'VALUE' => '9'),
				array('LABEL' => 'October', 'VALUE' => '10'),
				array('LABEL' => 'November', 'VALUE' => '11'),
				array('LABEL' => 'December', 'VALUE' => '12'),
				);
																     
		$smarty->assign("mmarr",$mmarr);
																     
		$curyear = date('Y');
		$start_year = $curyear-18;
		$end_year = $curyear-70;
		$preselected_year = $curyear-30;
		for($i=$start_year;$i>=$end_year;$i--)
			$yyarr[]=$i;
																     
		$smarty->assign("preselected_year",$preselected_year);
		$smarty->assign("yyarr",$yyarr);
	}
}

function update_astro_dob($pid,$mod_dob)
{
        $objUpdate = JProfileUpdateLib::getInstance();
        $result = $objUpdate->updateASTRO_DETAILS($pid, array('DTOFBIRTH' => $mod_dob));
        if(false === $result) {
            die('Issue while updating updating Astro Details at line 1718.');
        }
//        $sql_ast_upd = "UPDATE newjs.ASTRO_DETAILS SET DTOFBIRTH='$mod_dob' WHERE PROFILEID='$pid'";
//        mysql_query_decide($sql_ast_upd) or die($sql_ast_upd.mysql_error_js());

       // if(mysql_affected_rows_js() > 0)
               // mail("kunal.verma@jeevansathi.com","Astro DOB changed through connect.inc","Dob of $pid changed to $mod_dob");
}
function comp_info($profile,$redirect=1)
{

	global $cid;
	//Added by Vibhor for Astro Service in Offline Module
	$sql="Select USERNAME,SUBSCRIPTION from newjs.JPROFILE where PROFILEID = '$profile'";
	$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
	$row=mysql_fetch_assoc($result);
	$uname = $row["USERNAME"];
	
	$subscription=explode(",",$row["SUBSCRIPTION"]);
	if(in_array("A",$subscription))
        	$compatibility_subscription=1;
       	else
        	$compatibility_subscription=0;
	//end
	$sql="SELECT BILLID,ENTRY_DATE,ACC_ALLOWED,ACC_MADE FROM jsadmin.OFFLINE_BILLING WHERE PROFILEID='$profile' AND ACTIVE='Y' ORDER BY BILLID DESC LIMIT 1";
	$result=mysql_query_decide($sql,$db) or logError("$sql");
	$row=mysql_fetch_assoc($result);
	$billid=$row["BILLID"];
	$accAllowedArray=$row["ACC_ALLOWED"];
	$accMade=$row["ACC_MADE"];
	$accRemainArray=$row["ACC_ALLOWED"]-$row["ACC_MADE"];
	$dateArray=$row["ENTRY_DATE"];

	$sql="SELECT DATEDIFF(EXPIRY_DT,NOW()) AS DAYREMAIN FROM billing.SERVICE_STATUS WHERE BILLID='$billid' AND ACTIVATED='Y'";
	$result=mysql_query_decide($sql,$db) or logError("$sql");
	$row=mysql_fetch_assoc($result);
	$dayremain=$row["DAYREMAIN"];

	//Query optimization by Sadaf start
	/*catpool and catacc needed for category wise break up of pool & accepted numbers for display on home page 3270
	Added by Sadaf*/
	$sql="SELECT COUNT(*) AS CNT,STATUS,CATEGORY FROM jsadmin.OFFLINE_MATCHES WHERE PROFILEID='$profile' GROUP BY STATUS,CATEGORY";
	$result=mysql_query_decide($sql,$db) or logError("$sql".mysql_error_js($db));
	while($row=mysql_fetch_assoc($result))
	{
		if($row["STATUS"]=="REJ")
		$count_rej+=$row["CNT"];
		if($row["STATUS"]=="SL")
		$count_sl+=$row["CNT"];
		if(($row["STATUS"]=="N" || $row["STATUS"]=="NNOW" || $row["STATUS"]=="NACC") && $row["CATEGORY"]!=0)
		{
			$count_pool+=$row["CNT"];
			$catpool[$row["CATEGORY"]]+=$row["CNT"];
		}
		if($row["STATUS"]=="ACC")
			$catacc[$row["CATEGORY"]]+=$row["CNT"];
	}
	$catpoolstring="(";
	$cataccstring="(";
	for($i=1;$i<=6;$i++)
	{
		if($catpool[$i])
			$catpoolstring.=$catpool[$i]."-";
		else
			$catpoolstring.="0-";
		if($catacc[$i])
			$cataccstring.=$catacc[$i]."-";
		else
			$cataccstring.="0-";	
	}
	$cataccstring.="0)";

	include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");
	$mysqlObj=new Mysql;
        $myDbName=getProfileDatabaseConnectionName($profile,'slave',$mysqlObj);
        $myDb=$mysqlObj->connect("$myDbName");
        $jpartnerObj=new Jpartner;
        $jpartnerObj->setPROFILEID($profile);
        $jpartnerObj->setPartnerDetails($profile,$myDb,$mysqlObj);
        if($jpartnerObj->isPartnerProfileExist($myDb,$mysqlObj,$profile))
        {
                $my_partner_caste=$jpartnerObj->getPARTNER_CASTE();
                $my_partner_cityres=$jpartnerObj->getPARTNER_CITYRES();
                $my_partner_countryres=$jpartnerObj->getPARTNER_COUNTRYRES();
                $my_partner_mstatus=$jpartnerObj->getPARTNER_MSTATUS();
                $my_partner_lage=$jpartnerObj->getLAGE();
                $my_partner_hage=$jpartnerObj->getHAGE();
        }
	$sqlgen="SELECT GENDER FROM newjs.JPROFILE WHERE PROFILEID='$profile'";
        $resgen=mysql_query_decide($sqlgen) or die(mysql_error_js());
        $rowgen=mysql_fetch_assoc($resgen);
        if($rowgen["GENDER"]=="F")
                $my_partner_gender="M";
        else
                $my_partner_gender="F";

	$dbslave=connect_slave81();
	$sql="SELECT COUNT(*) AS COUNT FROM jsadmin.AFFILIATE_DATA AS A LEFT OUTER JOIN newjs.JPROFILE ON A.EMAIL=JPROFILE.EMAIL WHERE JPROFILE.EMAIL IS NULL AND A.GENDER='$my_partner_gender'";
	if($my_partner_caste)
		$sql.=" AND A.CASTE IN ($my_partner_caste)";
	if($my_partner_countryres)
		$sql.=" AND A.COUNTRY_RES IN ($my_partner_countryres)";
	if($my_partner_cityres)
		$sql.=" AND A.CITY_RES IN ($my_partner_cityres)";
	if($my_partner_mstatus)
		$sql.=" AND A.MSTATUS IN ($my_partner_mstatus)";
	if($my_partner_lage && $my_partner_hage)
		$sql.=" AND (A.AGE>=$my_partner_lage && A.AGE<=$my_partner_hage)";
	elseif($my_partner_lage)
		 $sql.=" AND (A.AGE>=$my_partner_lage)";
	elseif($my_partner_hage)
		 $sql.=" AND (A.AGE<=$my_partner_hage)";
	$res=mysql_query_decide($sql,$dbslave) or die(mysql_error_js());
	$row=mysql_fetch_assoc($res);
	$cat7_count=$row["COUNT"];
	$count_pool+=$cat7_count;
	$catpoolstring.="$cat7_count)";
	mysql_close($dbslave);
	$db=connect_db();

	/*$sql="Select COUNT(*) AS CNTP from jsadmin.OFFLINE_MATCHES where PROFILEID = '".$profile."' AND CATEGORY !='' AND STATUS IN('N','NNOW','NACC')";
	$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
	$row=mysql_fetch_assoc($result);
	$count_pool = $row["CNTP"];

	$sql="Select COUNT(*) AS CNTSL from jsadmin.OFFLINE_MATCHES where PROFILEID = '".$profile."' AND STATUS = 'SL' ";
	$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
	$row=mysql_fetch_assoc($result);
	$count_sl = $row["CNTSL"];
	$sql="Select COUNT(*) AS CNTR from jsadmin.OFFLINE_MATCHES where PROFILEID = '".$profile."' AND STATUS = 'REJ' ";
	$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
	$row=mysql_fetch_assoc($result);
	$count_rej = $row["CNTR"];*/
	//Query optimization by Sadaf end

	$sql="Select COUNT(*) AS CNTC from jsadmin.OFFLINE_MATCHES where PROFILEID = '".$profile."' AND STATUS = 'ACC' AND COURIERED='Y'";
	$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
    	$row=mysql_fetch_assoc($result);
    	$count_crd = $row["CNTC"];
	
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");

	//Sharding Concept added by Sadaf Alam on table PHOTO_REQUEST & HOROSCOPE_REQUEST
	$mysqlObj=new Mysql;
	$myDbName=getProfileDatabaseConnectionName($profile,'',$mysqlObj);
	$myDb=$mysqlObj->connect("$myDbName");

	$sql = "SELECT count(*) as cnt FROM newjs.HOROSCOPE_REQUEST WHERE PROFILEID_REQUEST_BY='$profile'";
	$result = $mysqlObj->executeQuery($sql,$myDb);
	$row=$mysqlObj->fetchArray($result);
	$horoscope_requests=$row["cnt"];

	$sql="SELECT count(*) as cnt FROM PHOTO_REQUEST WHERE PROFILEID_REQ_BY='$profile'";
	$result = $mysqlObj->executeQuery($sql,$myDb);
	$row=$mysqlObj->fetchArray($result);
	$photo_requests=$row["cnt"];

	global $smarty;
	$smarty->assign("customer_name",$uname);
	$smarty->assign("acc_remain",$accRemainArray);
	$smarty->assign("day_remain",$dayremain);
	$smarty->assign("pool_profile",$count_pool);
	$smarty->assign("sl_profile",$count_sl);
	$smarty->assign("acc_made",$accMade);
	$smarty->assign("crd_profile",$count_crd);
	$smarty->assign("rej_profile",$count_rej);
	$smarty->assign("horoscope_requests",$horoscope_requests);
	$smarty->assign("photo_requests",$photo_requests);
	$smarty->assign("service_st",$dateArray);
	$smarty->assign("profileid",$profile);
	$profilechecksum=md5($profile)."i".($profile);
        $smarty->assign("profilechecksum",$profilechecksum);
	//Added by Vibhor for Astro Service in Offline Module
	$smarty->assign("COMPATIBILITY_SUBSCRIPTION",$compatibility_subscription);
	//end
	/*catpoolstring and cataccstring needed for category wise break up of pool and accepted numbers to be displayed on 
	home page of offline login - 3270 [Added by Sadaf]*/
	$smarty->assign("CATPOOLSTRING",$catpoolstring);
        $smarty->assign("CATACCSTRING",$cataccstring);

	//Code to know if a profile is marked not verified by an operator-3179 [Added by Neha]
	$sql_ver="SELECT VERIFIED FROM jsadmin.OFFLINE_ASSIGNED WHERE PROFILEID='$profile' ";
	$res_ver=mysql_query_decide($sql_ver) or logError($sql_ver);
	$row_ver=mysql_fetch_assoc($res_ver);
	if($row_ver["VERIFIED"]=='N')
	{
		$smarty->assign("profile_verified","N");
	}
	//End 
	$sql="SELECT ACTIVE FROM jsadmin.OFFLINE_BILLING WHERE PROFILEID='$profile' ORDER BY ENTRY_DATE DESC LIMIT 1";
	$res=mysql_query_decide($sql) or logError($sql);
	$row=mysql_fetch_assoc($res);
	if($row["ACTIVE"]=="N")
	{
		$p_expire="Y";
		$smarty->assign("p_expire","Y");
		if($redirect)
		header("Location: ".$SITE_URL."/jsadmin/accept_matches.php?profileid=$profile&p_expire=Y&cid=$cid");
	}
}
//Added by Vibhor for Astro Service in Offline Module
function compatibility_link($my_profileid,$result_profileid)
{
        global $smarty;

	if(!check_astro_details($result_profileid))
        {
	        $compatibility_link="R";
        }
	else
	{
		//to check if the other person has filled his ASTRO DETAILS in ASTRO_DETAILS table
		$sql = "SELECT * FROM newjs.ASTRO_DETAILS WHERE PROFILEID='$result_profileid'";
		$result=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$row = mysql_fetch_array($result);

		//check if the other person has filled his ASTRO DETAILS
		//if Yes then show the link of Check Compatibility
		if($row['MOON_DEGREES_FULL']&&$row['MARS_DEGREES_FULL']&&$row['VENUS_DEGREES_FULL']&&$row['LAGNA_DEGREES_FULL'])
			$compatibility_link="C";
		else
			$compatibility_link="R";
       	}
       	return $compatibility_link;
}

//function to check weather a user has filled the astro details.
//this function returns true if user has filled his astro details i.e entry exists in ASTRO_DETAILS table.
function check_astro_details($profileid,$show_horoscope="abc")
{
        global $SHOW_HOROSCOPE;  //variable declared in mainmenu.php, used to prevent the query to run unnecessary.

        if(!isset($SHOW_HOROSCOPE))
        {
                if($show_horoscope=="abc")
                {
                        global $jprofile_result;
                        //added by lavesh on 9 aug. If jprofile_result global variable is prevent , query on JPROFILE is saved.
                        if(is_array($jprofile_result))
                                $show_horoscope=$jprofile_result["viewed"]["SHOW_HOROSCOPE"];
                        else
                        {

                                $sql_jp = "SELECT SHOW_HOROSCOPE FROM newjs.JPROFILE WHERE PROFILEID = '$profileid'";
                                $res_jp = mysql_query_optimizer($sql_jp) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_jp,"ShowErrTemplate");
                                $row_jp = mysql_fetch_array($res_jp);
                                $show_horoscope=$row_jp['SHOW_HOROSCOPE'];
                        }
                }
        }
        else
                $show_horoscope=$SHOW_HOROSCOPE;

        if($show_horoscope == 'Y')
        {
                $sql_ast_det = "SELECT COUNT(*) AS COUNT FROM newjs.ASTRO_DETAILS WHERE PROFILEID = '$profileid'";
                $res_ast_det = mysql_query_optimizer($sql_ast_det) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_ast_det,"ShowErrTemplate");
                $row_ast_det = mysql_fetch_array($res_ast_det);
 		if($row_ast_det['COUNT'] > 0)
 			return true;
                else
                        return false;
        }
        else
                return false;
}
//end

//Pass profileid into this function after numeric check
function valid_profileid($profileid)
{
	if($profileid<=16777215)
		return true;
	else
		return false;
}

function valid_username($username)
{
	if(strlen($username)<=40)
		return true;
	else
		return false;
}

function getExcelData($data,$dataHeader){
        $retval = "";
        if (is_array($data)  && !empty($data))
        {
                $row = 0;
                foreach(array_values($data) as $_data)
                {
                        if (is_array($_data) && !empty($_data))
                        {
                                foreach($dataHeader as $key1=>$val1)
                                {
                                        if($row==0)
                                                $headerVal[] =$val1;

                                        $values[] =$_data[$key1];
                                }
                                if($row==0)
                                {
                                        $retval =implode("\t",$headerVal);
                                        $retval .= "\n\n";
                                }

                                $retval .=implode("\t",$values);
                                $retval .= "\n";
                                unset($values);

                                //increment the row so we don't create headers all over again
                                $row++;
                        }
                }
        }
        return $retval;
}

?>
