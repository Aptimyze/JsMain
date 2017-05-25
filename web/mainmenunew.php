<?php
function profileview($profileid,$checksum)
{
	global $smarty;	
		
	$sql="select USERNAME,NTIMES,MOD_DT,GENDER,INCOMPLETE from newjs.JPROFILE where PROFILEID='$profileid'";
	$result=mysql_query_decide($sql) or die("1".mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	
	if(mysql_num_rows($result) > 0)
	{
		$myrow=mysql_fetch_array($result);
		
		$mydate=substr($myrow["MOD_DT"],0,10);
		$mydateArr=explode("-",$mydate);
		
		$mydate=my_format_date($mydateArr[2],$mydateArr[1],$mydateArr[0]);
		
		$smarty->assign("VIEWS",$myrow["NTIMES"]);
		$smarty->assign("LAST_MODIFIED",$mydate);
		$smarty->assign("USERNAME",$myrow['USERNAME']);
		
		$gender=$myrow["GENDER"];
		
		// free the recordset
		mysql_free_result($result);
		
		$sql="select count(*) from newjs.JPROFILE where GENDER='$gender' and MOD_DT > '" . $myrow["MOD_DT"] . "'";
		$result=mysql_query_decide($sql) or die("2".mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$countrow=mysql_fetch_row($result);
		
		$thoseAbove=$countrow[0];
		$thoseAbove++;
		
		$smarty->assign("THOSEABOVE",$thoseAbove);
		
		mysql_free_result($result);
		
		$sql="select count(*) from newjs.JPROFILE where GENDER='$gender'";
		$result=mysql_query_decide($sql) or die("3".mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		
		$totalmyrow=mysql_fetch_row($result);
		
		$totalcount=$totalmyrow[0];
		
		$sub_rights=explode(",",$data["SUBSCRIPTION"]);
		
		if(in_array("F",$sub_rights) && !in_array("B",$sub_rights))
		{
			$subscription="yes";
			$membership="Full Member";
		}
		elseif(in_array("F",$sub_rights) && in_array("B",$sub_rights))
		{
			$subscription="yes";
			$membership="Value Added Member";
		}
		else 
		{
			$subscription="no";
			$membership="Free Member";
		}
		
		$sql="select count(*) as cnt from newjs.CONTACTS where RECEIVER='$profileid' and TYPE='I'";
		$result=mysql_query_decide($sql) or die("4".mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		
		$myrow=mysql_fetch_array($result);
		$smarty->assign("RECEIVED_I",$myrow["cnt"]);
		$RECEIVEDSUM=$myrow["cnt"];
		mysql_free_result($result);
		
		$sql="select count(*) as cnt from newjs.CONTACTS where RECEIVER='$profileid' and TYPE='A'";
		$result=mysql_query_decide($sql) or die("5".mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		
		$myrow=mysql_fetch_array($result);
		$smarty->assign("RECEIVED_A",$myrow["cnt"]);
		$RECEIVEDSUM+=$myrow["cnt"];
		
		mysql_free_result($result);
		
		$sql="select count(*) as cnt from newjs.CONTACTS where RECEIVER='$profileid' and TYPE='D'";
		$result=mysql_query_decide($sql) or die("6".mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		
		$myrow=mysql_fetch_array($result);
		$smarty->assign("RECEIVED_D",$myrow["cnt"]);
		$RECEIVEDSUM+=$myrow["cnt"];
		
		mysql_free_result($result);
		
		$sql="select count(*) as cnt from newjs.CONTACTS where SENDER='$profileid' and TYPE='I'";
		$result=mysql_query_decide($sql) or die("7".mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		
		$myrow=mysql_fetch_array($result);
		$smarty->assign("MADE_I",$myrow["cnt"]);
		$MADESUM=$myrow["cnt"];
		
		mysql_free_result($result);
		
		$sql="select count(*) as cnt from newjs.CONTACTS where SENDER='$profileid' and TYPE='A'";
		$result=mysql_query_decide($sql) or die("8".mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		
		$myrow=mysql_fetch_array($result);
		$smarty->assign("MADE_A",$myrow["cnt"]);
		$MADESUM+=$myrow["cnt"];
		
		mysql_free_result($result);
		
		$sql="select count(*) as cnt from newjs.CONTACTS where SENDER='$profileid' and TYPE='D'";
		$result=mysql_query_decide($sql) or die("9".mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		
		$myrow=mysql_fetch_array($result);
		$smarty->assign("MADE_D",$myrow["cnt"]);
		$MADESUM+=$myrow["cnt"];
		
		mysql_free_result($result);

		$smarty->assign("MADESUM",$MADESUM);
		$smarty->assign("RECEIVEDSUM",$RECEIVEDSUM);
		$smarty->assign("GENDER",$gender);
		$smarty->assign("TOTALCOUNT",$totalcount);
		$smarty->assign("MEMBERSHIP",$membership);
		$smarty->assign("SUBSCRIPTION",$subscription);
		$smarty->assign("CHECKSUM",$checksum);
//		$msg= $smarty->fetch("../crm/login1.htm");
//		return $msg;
	}
}
?>
