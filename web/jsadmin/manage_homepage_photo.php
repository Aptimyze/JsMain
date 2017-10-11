<?php
include("connect.inc");
if(authenticated($cid))
{

	if($Submit)
	{
		if($clear_prev_list)
		{
			$sql="UPDATE newjs.JPROFILE set PHOTOGRADE='B' where PHOTOGRADE = 'A' and GENDER = '$gender'
                              and DATE(LAST_LOGIN_DT) <='$last_date'";

			mysql_query_decide($sql) or die("Can not reset previous list because of : ".mysql_error_js());
			$msg .= mysql_affected_rows_js()." photographs for $gender have been removed from homepage<br>";
		}

		if(trim($usernames)){
		$usernames = "'".ereg_replace(",","','",$usernames)."'";

		$sql="UPDATE newjs.JPROFILE set PHOTOGRADE='A' where USERNAME in ($usernames)";
		mysql_query_decide($sql) or die("Can not update photograding because of : ".mysql_error_js());
                $msg .= "You have successfully assigned ".mysql_affected_rows_js()." new photographs for homepage<br>";
		}

		$msg .= "<br><a href=\"manage_homepage_photo.php?name=$user&cid=$cid\">";
		$msg .= "Continue &gt;&gt;</a>";

		$smarty->assign("cid",$cid);
		$smarty->assign("MSG",$msg);
		$smarty->display("jsadmin_msg.tpl");
	}
	else
	{
		/*$sql="SELECT SQL_CALC_FOUND_ROWS * from FEEDBACK where STATUS='' ORDER BY DATE desc";
		$result=mysql_query_decide($sql);
		$sql="SELECT FOUND_ROWS() as NUM";
		$result1=mysql_query_decide($sql);
		$myrow1=mysql_fetch_array($result1);
		$i=1;
		while($myrow=mysql_fetch_array($result))
		{
			$values[] = array("SNO"=>$i,
					  "NAME"=>$myrow["NAME"],
					  "ADDRESS"=>$myrow["ADDRESS"],
					  "EMAIL"=>$myrow["EMAIL"],
				  "COMMENTS"=>$myrow["COMMENTS"],
				  "DATE"=>$myrow["DATE"],
				  "ID"=>$myrow["ID"]	
                          );
		$i++;
		}
		$smarty->assign("NUM",$myrow1["NUM"]);
		$smarty->assign("ROW",$values);*/
		$smarty->assign("cid",$cid);
		$smarty->display("manage_homepage_photo1.tpl");
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

?>
