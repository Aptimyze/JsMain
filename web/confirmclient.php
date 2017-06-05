<?php

include("connect.inc");
if(authenticated($cid))
//if(1)
{
	if($FLAG==1)
	{
		$sql="UPDATE incentive.PAYMENT_COLLECT set CONFIRM='Y', ENTRYBY='$name' where PROFILEID='$pid'";
		mysql_query_decide($sql);
		echo "<br>";
		echo "<font color=\"blue\">$user</font> has been confirmed for payment";
		/*echo "<script language='JavaScript'>
			 opener.location.reload(true);
                       </script>";*/
//echo "opener.location.reload(true);";
//		echo "<br>". "<A href=\"javascript: self.close ()\">Close this Window</A>";
//		echo "<br>". "<A href=\"javascript: window.close ()\">Close this Window</A>";
//		echo "<a href=\"\" onclick=\"closeWindow('submit','confirmclient.php?cid=$cid','confirmclient');\">close</a>";
		die;
		$flag=0;
	}
	else
	{
		$i=1;
		$sql="SELECT PROFILEID,USERNAME,PAYMENT_COLLECT.NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICES.NAME as SERVICE,ADDRESS,BRANCH_CITY.LABEL as CITY,PIN from incentive.PAYMENT_COLLECT, billing.SERVICES,incentive.BRANCH_CITY where CONFIRM='' and AR_GIVEN='' and PAYMENT_COLLECT.SERVICE=SERVICES.SERVICEID and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE";	
		$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		if($myrow=mysql_fetch_array($result))
		{
			do
			{
				
				$address=$myrow["ADDRESS"]." ".$myrow["CITY"]."-".$myrow["PIN"];
				$values[] = array("sno"=>$i,
						  "profileid"=>$myrow["PROFILEID"],
						  "username"=>$myrow["USERNAME"],
						  "name"=>$myrow["NAME"],
						  "email"=>$myrow["EMAIL"],
						  "phone_res"=>$myrow["PHONE_RES"],
						  "phone_mob"=>$myrow["PHONE_MOB"],
						  "service"=>$myrow["SERVICE"],
						  "address"=>$address,
						 );
				$i++;
			}while($myrow=mysql_fetch_array($result));
		}
	
		$smarty->assign("ROW",$values);

		$smarty->assign("name",$name);
		$smarty->assign("cid",$cid);
		$smarty->display("confirmclient.htm");
	}
}
else
{
	$msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");

}

function closeWindow($action, $url="", $form_name="")
{
       echo "<script language='JavaScript'>";
       if($action=="refresh")
       {
               echo "opener.location.reload(true);";
       }
       elseif($action=="submit" && $form_name!="" && $url!="")
       {
               echo "opener.document.$form_name.action='$url';";
               echo "opener.document.$form_name.submit();";
       }
       echo "window.close();</script>";
       die(); 
}
?>
