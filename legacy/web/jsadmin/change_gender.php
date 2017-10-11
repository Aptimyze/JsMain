<?php
die("this module is temporarily down. Kindly conatct Shiv or Alok for details");                                                                                
include("connect.inc");

//$db2=connect_737();
//$db=connect_db();

if(authenticated($cid))
{
		
	if($Submit)
	{
		$msgval = array();
		$sqlval = array();
		
		$sql = "UPDATE newjs.JPROFILE set ";	
		if($Gender!= '')
		{
			$sqlval[] = "GENDER = '$Gender'";
			$msgval[] = " Gender updated successfully. ";
		}

		if(trim($Dtofbirth))
		{
			$sqlval[] = " DTOFBIRTH = '$Dtofbirth' ";
			$msgval[] = " Date of Birth updated successfully. ";
		}
	
		if(count($sqlval))
		{
			if (trim($Dtofbirth))
			{
				$age= getAge($Dtofbirth);
				$sql = $sql.implode(",",$sqlval).", AGE = '$age' where PROFILEID='$pid'";
			}
			else
                                $sql = $sql.implode(",",$sqlval)." where PROFILEID='$pid'";

			mysql_query_decide($sql) or die("$sql".mysql_error_js());  
			if($Gender=='M')
				$partner_gender= 'F';
			elseif($Gender=='F')	
				$partner_gender= 'M';
			$sql="UPDATE newjs.JPARTNER set GENDER= '$partner_gender' where PROFILEID='$pid'";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());
			
			if($gender=='M')
			{
				$sql="DELETE from newjs.SEARCH_MALE where PROFILEID='$pid'";
				mysql_query_decide($sql) or die("$sql".mysql_error_js());
				//mysql_close($db);
				$sql="DELETE from newjs.SEARCH_MALE_REV where PROFILEID='$pid'";
				mysql_query_decide($sql) or die("$sql".mysql_error_js());
				$sql="DELETE from newjs.SEARCH_MALE_TEXT where PROFILEID='$pid'";
				mysql_query_decide($sql) or die("$sql".mysql_error_js());
				$db=connect_737();
				//mysql_close($db);
			}
			if($gender=='F')
			{
				$sql="DELETE from newjs.SEARCH_FEMALE where PROFILEID='$pid'";
				mysql_query_decide($sql) or die("$sql".mysql_error_js());
				$sql="DELETE from newjs.SEARCH_FEMALE_REV where PROFILEID='$pid'";
				mysql_query_decide($sql) or die("$sql".mysql_error_js());
				$sql="DELETE from newjs.SEARCH_FEMALE_TEXT where PROFILEID='$pid'";
				mysql_query_decide($sql) or die("$sql".mysql_error_js());
				//mysql_close($db);
				$db2=connect_737();
				//mysql_close($db);
			}
				
			$msg = implode("<br>",$msgval);
               	}
		else
			$msg="Nothing get updated <br>Please try again.";

                $msg .= "<br><br><a href=\"searchpage.php?user=$user&cid=$cid\">Continue &gt;&gt;</a>";

                $smarty->assign("name",$user);
                $smarty->assign("cid",$cid);
                $smarty->assign("MSG",$msg);
                $smarty->display("jsadmin_msg.tpl");
	}
	else
	{
                $tdate=date("Y-m-d");
                $lastweek_date=strftime("%Y-%m-%d",JSstrToTime("$tdate-7days "));

		$sql="SELECT USERNAME, GENDER, DTOFBIRTH, ENTRY_DT from newjs.JPROFILE where PROFILEID=$pid";
		$result=mysql_query_decide($sql);
		$myrow=mysql_fetch_array($result);

		$sql="SELECT count(*) as cnt from newjs.CONTACTS where SENDER= '$pid'";
		$result1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$myrow1=mysql_fetch_array($result1);
		$cnt=$myrow1['cnt'];

		$sql="SELECT count(*) as cnt from newjs.CONTACTS where RECEIVER= '$pid'";
		$result1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$myrow1=mysql_fetch_array($result1);
		$cnt+=$myrow1['cnt'];

		if ($myrow['ENTRY_DT'] < $lastweek_date || $myrow1['cnt'] != 0)
		{
			$msg= "Sorry you can't modify this profile because";
			if ($myrow['ENTRY_DT'] < $lastweek_date)
				$msg.= "<br><>This profile was not made in last 7 days.";
			if ($cnt != 0)
				$msg.= "<br><>Contacts have been made or received against this profile.";
			$msg .= "<br><br><a href=\"searchpage.php?user=$user&cid=$cid\">Continue &gt;&gt;</a>";

			$smarty->assign("name",$user);
			$smarty->assign("cid",$cid);
			$smarty->assign("MSG",$msg);
	                $smarty->display("jsadmin_msg.tpl");
		}
		else
		{
			$smarty->assign("username",$myrow['USERNAME']);
			$smarty->assign("gender",$myrow['GENDER']);
			$smarty->assign("dateofbirth",$myrow['DTOFBIRTH']);
			$smarty->assign("pid",$pid);
			$smarty->assign("cid",$cid);
			$smarty->assign("user",$user);
			$smarty->display("change_gender.tpl");
		}
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

function getAge($newDob)
{
        $today=date("Y-m-d");
        $datearray=explode("-",$newDob);
        $todayArray=explode("-",$today);
        $years=($todayArray[0]-$datearray[0]);
        if(intval($todayArray[1]) < intval($datearray[1]))
                $years--;
        elseif(intval($todayArray[1]) == intval($datearray[1]) && intval($todayArray[2]) < intval($datearray[2]))
                $years--;
                                                                                                 
        return $years;
}

?>
