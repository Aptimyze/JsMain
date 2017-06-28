<?php
include("config.php");

include_once("../jsadmin/handleCrmLogin.php");
include_once(JsConstants::$docRoot."/commonFiles/crmLoginFunctions.php");

if($flag_using_php5)
{
        include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
        include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
        include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
}
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once(JsConstants::$cronDocRoot."/lib/model/enums/Membership.enum.class.php");


function login ($id, $pass)
{
        global  $TOUT;  // global variables
	global $db2;
        $mycond = 0;
	$pass = md5($pass);
        $sql = "select RESID, LAST_LOGIN_DT,MOD_DT from jsadmin.PSWRDS where USERNAME= binary '$id' and PASSWORD= binary '$pass' AND ACTIVE='Y'";
        $res = mysql_query_decide($sql,$db2) or die("Could not Execute query pwdError");
        $count = mysql_num_rows($res);

        if ($count > 0)
        {
                $myrow = mysql_fetch_array($res);
                $recid = $myrow["RESID"];
		$mod_dt=$myrow["MOD_DT"];
		$last_login_dt=$myrow["LAST_LOGIN_DT"];

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

                $tm = time();

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
			$result = mysql_query_decide($sql,$db2);

			$sqlup="insert into jsadmin.CONNECT (ID, USER, IPADDR, TIME) values ('','$recid','$ip','$tm')";
			$sqlup_con = mysql_query_decide($sqlup,$db2) or die($sqlup." : ".mysql_error_js());

			$id=mysql_insert_id_js();

			$ret=md5($id)."i".$id;
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
	global $db2;
        list($md, $userno)=explode("i",$checksum);

        if(md5($userno)!=$md)
            return NULL;
        else
        {
        $sql_chk = "select * from jsadmin.CONNECT where ID=$userno";
        $res_chk = mysql_query_decide($sql_chk,$db2) or die("Could not Authenticate User: auError $sql_chk ".mysql_error_js());
        $count = mysql_num_rows($res_chk);

        if ($count > 0)
        {
                $myrow = mysql_fetch_array($res_chk);

                if (time()-$myrow["TIME"] < $TOUT)
                {
                        $tm = time();
                        $sql_up = "update jsadmin.CONNECT set TIME=$tm where ID=$userno";
                        $res_up = mysql_query_decide($sql_up,$db2) or die("Could not Authenticate User: upError");

                        $ret["ID"] = $md."i".$userno;
                        $ret["USER"]=$myrow["USER"];
			$cna = preg_replace('/[^A-Za-z0-9\. -_]/', '', $_COOKIE["CRM_NOTIFICATION_AGENT"]);
			setLoginCookies($ret["ID"],$cna);
				

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
	global $db2;

        list($md, $userno)=explode("i",$checksum);
        if(md5($userno)!=$md)
            return FALSE;
        else
        {
                $sql_chk = "delete from jsadmin.CONNECT where ID='$userno'";
                $res_chk = mysql_query_decide($sql_chk,$db2);

                if ($res_chk)
                        $ret = TRUE;
                else
                        $ret = FALSE;
        }
return $ret;
}*/
                                                                                                 
function create_dd($selected,$cname,$minormax=0,$labelselect="")
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
  if ($cname == "Source")
        {
                $sql = "select SQL_CACHE GROUPNAME, SourceID from MIS.SOURCE";
                $res = mysql_query_decide($sql);
                while($myrow = mysql_fetch_array($res))
                {
                        if(in_array($myrow["SourceID"],$s_arr))
                        {
                                $ret .= "<option value=\"$myrow[SourceID]\" selected>$myrow[GROUPNAME]-$myrow[SourceID]</option>\n";
                        }
                        else
                        {
                                $ret .= "<option value=\"$myrow[SourceID]\">$myrow[GROUPNAME]-$myrow[SourceID]</option>\n";                        }
                }
        }
        return $ret;
}
function my_format_date($day,$month,$year)
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
        else
                $month="Dec";

        if(strlen($day)==1)
                $day= "0" . $day;

        return $month . " " . $day . ", " . $year;
}

function current_date()
{
        $tm = date("Y-m-d",time());
        return $tm;
}

/*function getuser($checksum, $ip="")
{
        global $TOUT;
	global $db2;

        list($md, $userno)=explode("i",$checksum);
        if(md5($userno)!=$md)
            return FALSE;
        else
        {
                $sql_usr = "select USER from jsadmin.CONNECT where ID=$userno";
                $res_usr = mysql_query_decide($sql_usr,$db2) or die("Could not return data from Connect1. $sql_usr ".mysql_error_js());
                $count = mysql_num_rows($res_usr);

                if ($count > 0)
                {
                        $myrow = mysql_fetch_array($res_usr);
                        $ret_usr = $myrow["USER"];

                        $sql_getusr = "select USERNAME from jsadmin.PSWRDS where RESID=$ret_usr";
                        $res_getusr = mysql_query_decide($sql_getusr,$db2) or die("Could not return data from Pswrds.");
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

/*function getname($cid)
{
        global $TOUT;
	global $db2;
        $temp=explode("i",$cid);
        $userid=$temp[1];
        $sql="SELECT USER FROM jsadmin.CONNECT WHERE ID='$userid'";
        $res=mysql_query_decide($sql,$db2) or die(mysql_error_js());
        $row=mysql_fetch_array($res);
        $id=$row['USER'];

        $sql="SELECT USERNAME FROM jsadmin.PSWRDS WHERE RESID='$id'";
        $res=mysql_query_decide($sql,$db2) or die(mysql_error_js());
        $row=mysql_fetch_array($res);
        $username=$row['USERNAME'];
        return $username;
}*/


/*function getprivilage($checksum="",$opname="")
{
	
	if($opname)
	{	
		$sql_priv="select PRIVILAGE from jsadmin.PSWRDS where USERNAME='$opname'";
		$res_priv=mysql_query_decide($sql_priv,$db) or die("Could not return from PSWRDS");
		if($row_priv=mysql_fetch_assoc($res_priv))
		{
			$ret=$row_priv['PRIVILAGE'];
		}
	} 
	else
	{		
		
        	global $TOUT;
		global $db2;

        	list($md, $userno)=explode("i",$checksum);
        	if(md5($userno)!=$md)
            		return FALSE;
        	else
        	{

                	$sql_usr = "select USER from jsadmin.CONNECT where ID=$userno";
                	$res_usr = mysql_query_decide($sql_usr,$db2) or die("Could not return data from Connect1.");
                	$count = mysql_num_rows($res_usr);

                	if ($count > 0)
                	{
                       	 	$myrow = mysql_fetch_array($res_usr);
                        	$ret_usr = $myrow["USER"];

                        	$sql_getusr = "select PRIVILAGE from jsadmin.PSWRDS where RESID=$ret_usr";
                        	$res_getusr = mysql_query_decide($sql_getusr,$db2) or die("Could not return data from Pswrds.");
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
	}
	return $ret;
}*/

/*function getcenter($connection, $ip="")
{
	global $db2;
        global $TOUT;

        //$sql_usr = "select USER from jsadmin.CONNECT where ID=$connection and IPADDR='$ip'";
        $sql_usr = "select USER from jsadmin.CONNECT where ID=$connection";
        $res_usr = mysql_query_decide($sql_usr,$db2) or die("Could not return data from Connect1.<br>".mysql_error_js());
        $count = mysql_num_rows($res_usr);

        if ($count > 0)
        {
                $myrow = mysql_fetch_array($res_usr);
                $ret_usr = $myrow["USER"];

                $sql_getusr = "select CENTER from jsadmin.PSWRDS where RESID=$ret_usr";
                $res_getusr = mysql_query_decide($sql_getusr,$db2) or die("Could not return data from Pswrds.");
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
}*/

function getcenter_for_operator($walkin)
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

function label_select($columnname,$value,$database="")
{
	global $db;
        if($database=="")
                $database="newjs";

        $sql = "select SQL_CACHE LABEL from $database.$columnname WHERE VALUE='$value'";

        $res = mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));//logError("error",$sql) ;
        $myrow= mysql_fetch_row($res);
        return $myrow;
}

function net_off_tax_calculation($amount,$date='')
{
	global $smarty;
        if($date)
        {
                $dt=explode(' ',$date);
                list($y,$m,$d)=explode('-',$dt[0]);
                $ts_dt=mktime(0, 0, 0,$m,$d,$y);
        }
        else
                $ts_dt=time();
        $ts=mktime(0,0,0,'4','1','2009');
	$ts_new = mktime(0,0,0,'4','1','2012');
	$ts_new2 = mktime(0,0,0,'6','1','2015');
	$ts_new3 = mktime(0,0,0,'11','15','2015');
	$ts_new4 = mktime(0,0,0,'06','01','2016');
        $ts_gst = mktime(0,0,0,'07','01','2017');
        if($ts_gst<=$ts_dt){    //Added for GST which will start from 1st July 2017
            $net_off_tax_rate = billingVariables::NET_OFF_TAX_RATE;
	    $percent=$net_off_tax_rate*100;	
        }
        elseif($ts_new4<=$ts_dt)
        {
                $net_off_tax_rate =0.130435;
                $percent=$net_off_tax_rate*100;
        }
        elseif($ts_new3<=$ts_dt)
        {
                $net_off_tax_rate =0.12664;
                $percent=$net_off_tax_rate*100;
        }
	elseif($ts_new2<=$ts_dt)
	{
		//$net_off_tax_rate =billingVariables::NET_OFF_TAX_RATE;
		$net_off_tax_rate =0.12281;
		$percent=$net_off_tax_rate*100;
	}
	elseif($ts>$ts_dt || $ts_new<=$ts_dt)
	{
		$net_off_tax_rate = 0.11;
                $percent='11';
	}
        else
        {
                $net_off_tax_rate = 0.09;
                $percent='9';
        }
        $net_off_tax_amount = round($amount - (($net_off_tax_rate) * $amount),1);

        $net_off_msg = "<table width=\"100%\" align=\"center\"><tr class=\"formhead\" align=\"center\"><td><font color=\"red\">Net-off tax = Gross - ($percent% of Gross)</font></td></tr></table>";
        $smarty->assign("NET_OFF_MSG",$net_off_msg);

        return $net_off_tax_amount;
}

function getIST($time_est)
{
        $ISTtime=strftime("%Y-%m-%d %H:%M",JSstrToTime("$time_est + 10 hours 30 minutes"));
        return $ISTtime;
}

function check_table_existance($database,$table)
{
	global $db2;
	$sql_info_chk = "SELECT COUNT(*) AS COUNT FROM information_schema.TABLES WHERE TABLE_SCHEMA='$database' AND TABLE_NAME='$table'";
	$res_info_chk = mysql_query_decide($sql_info_chk,$db2) or die("$sql_info_chk".mysql_error_js($db2));
	$row_info_chk = mysql_fetch_array($res_info_chk);

	if($row_info_chk['COUNT'] > 0)
		return 1;
	else
		return 0;
}

function get_servicename($sid)
{
        $sid_arr=explode(",",$sid);
        foreach($sid_arr as $k=>$v)
        {
                if(strstr($v,'A'))
                        $val="Astro Compatibility";
                elseif(strstr($v,'B'))
                        $val="Profile Highlighting";
                elseif(strstr($v,'C'))
                        $val="e-Value Pack";
                elseif(strstr($v,'M'))
                        $val="Matri-Profile";
                elseif(strstr($v,'O'))
                        $val="Offline product";
                elseif(strstr($v,'P'))
                        $val="e-Rishta";
                elseif(strstr($v,'O'))
                        $val="Offline product";
                elseif(strstr($v,'HDO'))
                        $val="101 Membership";
                elseif(strstr($v,'SC'))
                        $val="Super Saver e-Rishta";

		if(strstr($v,'12'))
                        $dur="12 Months";
                elseif(strstr($v,'1'))
                        $dur="1 Months";
                elseif(strstr($v,'2'))
                        $dur="2 Months";
                elseif(strstr($v,'3'))
                        $dur="3 Months";
                elseif(strstr($v,'4'))
                        $dur="4 Months";
                elseif(strstr($v,'5'))
                        $dur="5 Months";
                elseif(strstr($v,'6'))
                        $dur="6 Months";
		elseif(strstr($v,'9'))
                        $dur="9 Months";
                elseif(strstr($v,'12'))
                        $dur="12 Months";
                elseif(strstr($v,'L'))
                        $dur="Unlimited";
                $service_arr[]=$val." - ".$dur;
        }
        $services=implode(",",$service_arr);
        return $services;
}
function check_validity($bid)
{
	return 1;
}
function check_validity_followup($bid,$op_name='',$db='')
{
	
	/*Code for JS Premium/ JS Premium Outsourced  users check*/
	$priv=getprivilage("",$op_name);
	$privilages=explode("+",$priv);
	if(in_array("ExcPrm",$privilages) || in_array("ExPrmO",$privilages))
	{
		$sql_service="SELECT SERVICEID FROM billing.PURCHASES WHERE BILLID='$bid' AND STATUS='DONE'";
		$res_service=mysql_query_decide($sql_service,$db) or die("Could not return from PURCHASES");
		if($row_service=mysql_fetch_assoc($res_service))
		{
			$service=$row_service['SERVICEID'];
			if(in_array("ExPrmO",$privilages)){
				if(!strstr($service,'X'))
					return 0;
			}
			else{
				if(!strstr($service,'ES')&&!strstr($service,'X'))
					return 0;
			}
		}
	}
	/* Code for JS Premium/ JS Premium Outsourced users check*/

        $go = 1;
	$sql_check="SELECT MEMBERSHIP,PROFILEID,ENTRY_DT FROM billing.PURCHASES WHERE BILLID='$bid' AND STATUS='DONE'";
        $res_check=mysql_query($sql_check,$db) or logError($sql_check);
        $row_check=mysql_fetch_array($res_check);
        $profileid=$row_check["PROFILEID"];
        $billing_day = JSstrToTime($row_check["ENTRY_DT"]);
        $billing_dt=date("Y-m-d",$billing_day);
        $checking_day = date("Y-m-d",$billing_day-86400);
	
	$sql_check2="SELECT ALLOTED_TO,ALLOT_TIME FROM incentive.CRM_DAILY_ALLOT WHERE PROFILEID='$profileid' ORDER BY ID DESC LIMIT 1";
        $res_check2=mysql_query($sql_check2,$db) or logError($sql_check2);
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
	if($op_name!='parul.dagar' && $op_name!='dayanidhi')
	{
		$sql_check1="SELECT COUNT(*) AS CNT FROM incentive.MANUAL_ALLOT m JOIN incentive.MAIN_ADMIN ma ON m.PROFILEID=ma.PROFILEID WHERE m.PROFILEID='$profileid' AND m.ALLOTED_TO='$op_name' AND m.ALLOT_TIME>=ma.ALLOT_TIME";
		$res_check1=mysql_query($sql_check1,$db) or logError($sql_check1);
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
		$res_history = mysql_query($sql_history,$db) or logError($sql_history);
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
function offline_online_sale($yr1,$yr2,$mnth1,$mnth2,$diversify='')
{
        $min_dt = "$yr1-$mnth1-01 00:00:00";
        $max_dt = "$yr2-$mnth2-31 23:59:59";
	$all_sql = "SELECT USERNAME FROM jsadmin.PSWRDS WHERE PRIVILAGE LIKE '%OFF%'";
	$all_res= mysql_query_decide($all_sql) or die(mysql_error_js());
        while($all_row= mysql_fetch_array($all_res))
                $ofl_arr[]= $all_row['USERNAME'];
        if(count($ofl_arr)>1)
                $ofl_exes = implode("','",$ofl_arr);
        else
                $ofl_exes = $ofl_arr;
        if($diversify)
        {
                $sql_offline2="SELECT BILLID FROM billing.PURCHASES WHERE ENTRYBY IN ('$ofl_exes') AND ENTRY_DT BETWEEN '$min_dt' AND '$max_dt' AND STATUS='DONE' AND SALES_TYPE='online'";
                $res_offline2=mysql_query_decide($sql_offline2,$db) or die("$sql_offline2".mysql_error_js($db));
                while($row_offline2=mysql_fetch_array($res_offline2))
                        $offline_bills2[]=$row_offline2['BILLID'];
                $offline_str2=@implode(',',$offline_bills2);
                return $offline_str2;
        }
        else
	{
		$sql_offline1="SELECT BILLID FROM billing.PURCHASES WHERE ENTRY_DT BETWEEN '$min_dt' AND '$max_dt' AND STATUS='DONE' AND SALES_TYPE='offline'";
	        $res_offline1=mysql_query_decide($sql_offline1,$db) or die("$sql_offline1".mysql_error_js($db));
        	while($row_offline1=mysql_fetch_array($res_offline1))
                	$offline_bills1[]=$row_offline1['BILLID'];
	        $offline_str1=@implode(',',$offline_bills1);
        	return $offline_str1;
	}
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

function check_validity_followup_new($billid,$op_name='',$db='')
{
        /*Code for JS Premium/ JS Premium Outsourced  users check*/
        $priv=getprivilage("",$op_name);
        $privilages=explode("+",$priv);

        $sql_service    ="SELECT PROFILEID,ENTRY_DT,SERVICEID FROM billing.PURCHASES WHERE BILLID='$billid' AND STATUS='DONE'";
        $res_service    =mysql_query_decide($sql_service,$db) or die("Could not return from PURCHASES");
        $row_check      =mysql_fetch_assoc($res_service);

        if(in_array("ExcPrm",$privilages) || in_array("ExPrmO",$privilages)){
                if($row_check){
                        $service=$row_check['SERVICEID'];
                        if(in_array("ExPrmO",$privilages)){
                                if(!strstr($service,'X'))
                                        return 0;
                        }
                        else{
                                if(!strstr($service,'ES')&&!strstr($service,'X'))
                                        return 0;
                        }
                }
        }
        /* Code for JS Premium/ JS Premium Outsourced users check*/

        $profileid      =$row_check["PROFILEID"];
        $billing_day    =JSstrToTime($row_check["ENTRY_DT"]);
        $billing_dt     =date("Y-m-d",$billing_day);
        $checking_day   =date("Y-m-d",$billing_day-86400);
        $sql_check2     ="SELECT ALLOTED_TO,ALLOT_TIME FROM incentive.CRM_DAILY_ALLOT WHERE PROFILEID='$profileid' ORDER BY ID DESC LIMIT 1";
        $res_check2     =mysql_query_decide($sql_check2,$db) or die("Could not get from CRM_DAILY_ALLOT");
        $row_check2     =mysql_fetch_array($res_check2);
        $allot_time     =JSstrToTime($row_check2["ALLOT_TIME"]);
        $allot_to       =$row_check2["ALLOTED_TO"];
        if($op_name!=''){
                if($allot_to!=$op_name)
                        return 0;
        }
        $allot_dt       =date("Y-m-d",$allot_time);
        $checking_time  =JSstrToTime($checking_day);
        $diff_time      =($checking_time-$allot_time)/86400;
        if($allot_dt==$billing_dt || $diff_time<=15)
               $go = 1;
        else
               $go = 0;

        $sql_check1="SELECT COUNT(*) AS CNT FROM incentive.MANUAL_ALLOT m JOIN incentive.MAIN_ADMIN ma ON m.PROFILEID=ma.PROFILEID WHERE m.PROFILEID='$profileid' AND m.ALLOTED_TO='$op_name' AND m.ALLOT_TIME>=ma.ALLOT_TIME";
        $res_check1=mysql_query($sql_check1,$db) or logError($sql_check1);
        $row_check1=mysql_fetch_array($res_check1);
        if($row_check1["CNT"]>0)
               $manual_case = 1;
        else
               $manual_case = 0;
        if(!$manual_case && !$go)
        {
                $sql_history = "SELECT COUNT(*) AS cnt FROM incentive.HISTORY WHERE PROFILEID = '$profileid' AND ENTRYBY = '$op_name' AND DATEDIFF('$checking_day', ENTRY_DT) <=15 AND DATEDIFF('$checking_day', ENTRY_DT) >=0";
                $res_history = mysql_query($sql_history,$db) or logError($sql_history);
                if($row_history =mysql_fetch_array($res_history))
                        $count=$row_history['cnt'];
                if($count>0)
                        return 1;
                else
                        return 0;
        }
        else
                return 1;
}

?>
