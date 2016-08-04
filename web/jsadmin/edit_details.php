<?php
                                                                                                 
include("time.php");
include("connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
$mysqlObj=new Mysql;

if(authenticated($cid))
{
		
	if($Submit)
	{
		$msgval = array();
		$sqlval = array();
		$updateLog = array();
		$sql = "UPDATE newjs.JPROFILE set ";	
/*
		if(trim($password))
		{
			//Insert into autoexpiry table, to expire all autologin url coming before date
			$expireDt=date("Y-m-d H:i:s");
			$sqlExpire="replace into jsadmin.AUTO_EXPIRY set PROFILEID='$pid',TYPE='P',DATE='$expireDt'";
			mysql_query_decide($sqlExpire) or logError($errorMsg,"$sqlExpire","ShowErrTemplate");
			//end
			$sqlval[] = " PASSWORD = '$password' ";
			$msgval[] = " Password updated successfully. ";
		}
*/
		if(trim($address))
		{
			$sqlval[] = " CONTACT = '$address' ";
			$msgval[] = " Address updated successfully. ";
			$updateLog[CONTACT] = $address;
		}
		
		if(trim($parents_address))
		{
			$sqlval[] = " PARENTS_CONTACT = '$parents_address' ";
			$msgval[] = " Parent Address updated successfully. ";
			$updateLog[PARENTS_CONTACT] = $parents_address;
		}
		
		if(count($updateLog))
		{
			$szAgentName = getname($cid);

			$updateLog[MOD_DT] = date('Y-m-d H:i:s');
			$updateLog[ENTRY_DT] = date('Y-m-d H:i:s');
			$updateLog[PROFILEID] = $pid;
			$updateLog[SOURCE] = "B_" . substr($szAgentName,0,8);
			include_once("../profile/functions_edit_profile.php");
			log_edit($updateLog);
		}
		
		if(count($sqlval))
		{
			$objUpdate = JProfileUpdateLib::getInstance();
			$updateParams = $objUpdate->convertUpdateStrToArray(implode(",",$sqlval));
			$result = $objUpdate->editJPROFILE($updateParams,$pid,'PROFILEID');
			if(false === $result) {
				die("Issue while update JPROFILE at line 65");
			}
//			$sql = $sql.implode(",",$sqlval)." where PROFILEID='$pid'";
//			mysql_query_decide($sql) or die("$sql".mysql_error_js());
			
			$msg .= implode("<br>",$msgval);
        }
		else
			$msg="Nothing got updated <br>Please try again.";
		
		$msg .= "<br><br><a href=\"searchpage.php?user=$user&cid=$cid&user=$user\">Continue &gt;&gt;</a>";

		$smarty->assign("name",$user);
		$smarty->assign("cid",$cid);
		$smarty->assign("MSG",$msg);
		$smarty->display("jsadmin_msg.tpl");
	}
	else
	{
		$prev=getprivilage($cid);
		$priv=explode("+",$prev);
		
		if(in_array('FTAFTO',$priv) || in_array('FTAReg',$priv) || in_array('FTASup',$priv) || in_array('ExcFTA',$priv))
		{
			$smarty->assign("SHOWEMAIL","Y");
			$smarty->assign("SHOWCONTACT","N");
		}
     
		if(in_array('ExcFld',$priv))
		{
			$smarty->assign("SHOWPASSWORD","N");
			$smarty->assign("SHOWEMAIL","N");
			$smarty->assign("SHOWPHONE","N");
			$smarty->assign("SHOWCONTACT","Y");
			$smarty->assign("SHOWEDITCONTACT","Y");
			$smarty->assign("SHOWINFO","N");
		}
		
        if(in_array('MA',$priv) || in_array('MC',$priv) || in_array('FTA',$priv) || in_array('SupFld',$priv) || in_array('MgrFld',$priv))
		{
            $smarty->assign("SHOWPASSWORD","Y");
			$smarty->assign("SHOWEMAIL","Y");
			$smarty->assign("SHOWPHONE","Y");
			$smarty->assign("SHOWCONTACT","Y");
			$smarty->assign("SHOWEDITCONTACT","Y");
			$smarty->assign("SHOWINFO","Y");
		}
		
		if(in_array('LTFExc',$priv) || in_array('LTFHD',$priv) || in_array('LTFSUP',$priv) || in_array('LTFVnd',$priv))
		{
			$smarty->assign("SHOWEMAIL","Y");
			$smarty->assign("SHOWPHONE","Y");
			$smarty->assign("SHOWCONTACT","Y");
		}

		
		$sql="SELECT USERNAME, EMAIL, CONTACT, PHONE_WITH_STD, ISD, PHONE_MOB, PARENTS_CONTACT, PHOTOGRADE, GENDER, DTOFBIRTH, ENTRY_DT, SHOWPHONE_RES, SHOWPHONE_MOB, SHOWADDRESS, SHOW_PARENTS_CONTACT, MESSENGER_ID,  MESSENGER_CHANNEL, SHOWMESSENGER, PRIVACY from newjs.JPROFILE where PROFILEID='$pid'";
		$result=mysql_query_decide($sql)or die("$sql".mysql_error_js());
		$myrow=mysql_fetch_array($result);
		$smarty->assign("profilename",htmlspecialchars($myrow['USERNAME']));
		$smarty->assign("email",$myrow['EMAIL']);
                $smarty->assign("gender",$myrow['GENDER']);
                $smarty->assign("dateofbirth",$myrow['DTOFBIRTH']);

		$smarty->assign("contact",$myrow['CONTACT']?$myrow['CONTACT']:"Address not given");
		$smarty->assign("phoneres",$myrow['PHONE_WITH_STD']?"(R)".$myrow['PHONE_WITH_STD']:"Res Phone not given");
		$smarty->assign("phonemob",$myrow['PHONE_MOB']?"(M)".$myrow['PHONE_MOB']:"Mobile not given");
		$smarty->assign("isd",$myrow['ISD']);

		$smarty->assign("parentscontact",$myrow['PARENTS_CONTACT']?$myrow['PARENTS_CONTACT']:"Address not given");

                $smarty->assign("showphone_res",$myrow['SHOWPHONE_RES']);
                $smarty->assign("showphone_mob",$myrow['SHOWPHONE_MOB']);
                $smarty->assign("showaddress",$myrow['SHOWADDRESS']);
                $smarty->assign("show_parents_contact",$myrow['SHOW_PARENTS_CONTACT']);
                $smarty->assign("privacy",$myrow['PRIVACY']);
                $smarty->assign("messenger_id",$myrow['MESSENGER_ID']?$myrow['MESSENGER_ID']:"Not Given");
                $smarty->assign("messenger_channel",$myrow['MESSENGER_CHANNEL']);
                $smarty->assign("showmessenger",$myrow['SHOWMESSENGER']);
		$smarty->assign("entry_dt",$myrow['ENTRY_DT']);		
		$smarty->assign("pid",$pid);
		$smarty->assign("cid",$cid);
		$smarty->assign("user",$user);

		$sql="select ALT_MOBILE,SHOWALT_MOBILE from newjs.JPROFILE_CONTACT where PROFILEID='$pid'";
		$result=mysql_query_decide($sql)or die("$sql".mysql_error_js());
		$myrow=mysql_fetch_array($result);

		$smarty->assign("showalt_mob",$myrow['SHOWALT_MOBILE']);
		$smarty->assign("altmob",$myrow['ALT_MOBILE']?"(ALT M)".$myrow['ALT_MOBILE']:"Alt Mobile not given");

		$smarty->display("edit_details.tpl");
	}
}
else
{
	$msg="Your session has been timed out<br>";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";
	$smarty->assign("MSG",$msg);
	$smarty->assign("user",$user);
	$smarty->display("jsadmin_msg.tpl");
}

?>
