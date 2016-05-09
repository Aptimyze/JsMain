<?php
	
	include("connect.inc");
	
	// connect to database
	//$db=connect_db();
	
	$show_details = FALSE;
	mysql_select_db_js("newjs",$db);
	if(!$checksum)
	{
		//if some internal executive want to see profile stats,then validation skipped
		list($CHECKSUM,$PID) = explode("i",$cid);
		if($CHECKSUM == md5($PID))
			$show_details = TRUE;

		$sql="select PROFILEID,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,USERNAME,GENDER,ACTIVATED from JPROFILE where PROFILEID = '$profileid'";
        	$result=@mysql_query_decide($sql);

		$myrow=mysql_fetch_array($result);
		
		$data["PROFILEID"]=$myrow["PROFILEID"];
		$data["USERNAME"]=$myrow["USERNAME"];
		$data["GENDER"]=$myrow["GENDER"];
		$data["ACTIVATED"]=$myrow["ACTIVATED"];
		
		$today=date("Y-m-d");
		
		if(trim($myrow["SUBSCRIPTION"])!="")// && $myrow["SUBSCRIPTION_EXPIRY_DT"] >= $today)
			$data["SUBSCRIPTION"]=trim($myrow["SUBSCRIPTION"]);
		
	}
	else
	$data=authenticated($checksum);
	
	if(isset($data) && $show_details)
	{
		//$checksum=$data["CHECKSUM"];
		$profileid=$data["PROFILEID"];
		
		$sql="select MOD_DT,GENDER from JPROFILE where PROFILEID='$profileid'";
		$result=mysql_query_decide($sql);// or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		
		if(mysql_num_rows($result) > 0)
		{
			$myrow=mysql_fetch_array($result);
			
			$mydate=substr($myrow["MOD_DT"],0,10);
			$mydateArr=explode("-",$mydate);
			
			$mydate=my_format_date($mydateArr[2],$mydateArr[1],$mydateArr[0]);
			
			if($_SERVER['DOCUMENT_ROOT'])
				include_once($_SERVER['DOCUMENT_ROOT']."/profile/ntimes_function.php");
			else
				include_once("../profile/ntimes_function.php");

			$ntimes = ntimes_count($profileid,"SELECT");

			$smarty->assign("VIEWS",$ntimes);
			$smarty->assign("LAST_MODIFIED",$mydate);
			
			$gender=$myrow["GENDER"];
			
			// free the recordset
			mysql_free_result($result);
			
			$sql="select count(*) from JPROFILE where GENDER='$gender' and MOD_DT > '" . $myrow["MOD_DT"] . "'";
			$result=mysql_query_decide($sql);// or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$countrow=mysql_fetch_row($result);
			
			$thoseAbove=$countrow[0];
			$thoseAbove++;
			
			$smarty->assign("THOSEABOVE",$thoseAbove);
			
			mysql_free_result($result);
			
			$sql="select count(*) from JPROFILE where GENDER='$gender'";
			$result=mysql_query_decide($sql);// or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			
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

			/*if(strstr($data["SUBSCRIPTION"],"B"))
			{
				$subscription="yes";
				$membership="Value Added Member";
			}
			elseif($data["SUBSCRIPTION"]=="F")
			{
				$subscription="yes";
				$membership="Full Member";
			}
			else 
			{
				$subscription="no";
				$membership="Free Member";
			}*/
			
			$sql="select count(*) as cnt from CONTACTS where RECEIVER='" . $data["PROFILEID"] . "' and TYPE='I'";
			$result=mysql_query_decide($sql);// or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			
			$myrow=mysql_fetch_array($result);
			$smarty->assign("RECEIVED_I",$myrow["cnt"]);
			$RECEIVEDSUM=$myrow["cnt"];
			
			mysql_free_result($result);
			
			$sql="select count(*) as cnt from CONTACTS where RECEIVER='" . $data["PROFILEID"] . "' and TYPE='A'";
			$result=mysql_query_decide($sql);// or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			
			$myrow=mysql_fetch_array($result);
			$smarty->assign("RECEIVED_A",$myrow["cnt"]);
			$RECEIVEDSUM+=$myrow["cnt"];
			
			mysql_free_result($result);
			
			$sql="select count(*) as cnt from CONTACTS where RECEIVER='" . $data["PROFILEID"] . "' and TYPE='D'";
			$result=mysql_query_decide($sql);// or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			
			$myrow=mysql_fetch_array($result);
			$smarty->assign("RECEIVED_D",$myrow["cnt"]);
			$RECEIVEDSUM+=$myrow["cnt"];
			
			mysql_free_result($result);
			
			$sql="select count(*) as cnt from CONTACTS where SENDER='" . $data["PROFILEID"] . "' and TYPE='I'";
			$result=mysql_query_decide($sql);// or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			
			$myrow=mysql_fetch_array($result);
			$smarty->assign("MADE_I",$myrow["cnt"]);
			$MADESUM=$myrow["cnt"];
			
			mysql_free_result($result);
			
			$sql="select count(*) as cnt from CONTACTS where SENDER='" . $data["PROFILEID"] . "' and TYPE='A'";
			$result=mysql_query_decide($sql);// or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			
			$myrow=mysql_fetch_array($result);
			$smarty->assign("MADE_A",$myrow["cnt"]);
			$MADESUM+=$myrow["cnt"];
			
			mysql_free_result($result);
			
			$sql="select count(*) as cnt from CONTACTS where SENDER='" . $data["PROFILEID"] . "' and TYPE='D'";
			$result=mysql_query_decide($sql);// or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			
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
			//$smarty->assign("CHECKSUM",$checksum);
			$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
			$smarty->assign("HEAD",$smarty->fetch("head.htm"));
			$smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
			$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));

			if(!$show_details)
			{
			$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
			$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanel.htm"));
			}
			$smarty->display("show_details.htm");
		}
	}
	else 
	{
		//echo "There are some error in showing details";
		$msg="Your session has been timed out<br> <br> ";
		$msg .="<a href=\"index.htm\">";
		$msg .="Login again </a>";
		$smarty->assign("MSG",$msg);
		$smarty->display("jsadmin_msg.tpl");
	}
?>
