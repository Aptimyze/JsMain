<?php

include("connect.inc");

if(authenticated($cid))
{
	$name=getname($cid);
	if($CMDAssign)
	{
		if(trim($username)=="")
                {
                        $msg="No username specified";
                }
		else
		{
			$sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE USERNAME='$username'";
			$res=mysql_query_decide($sql) or die(mysql_error_js());
			$row=mysql_fetch_array($res);
			$profileid=$row['PROFILEID'];

			$sql="SELECT ALLOTED_TO FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid'";
			$res=mysql_query_decide($sql) or die(mysql_error_js());
			$row=mysql_fetch_array($res);
			$alloted_to=$row['ALLOTED_TO'];

			if($alloted_to!=$old_alloted)
			{
				$msg="The user $username is not alloted to $old_alloted. <br>";
				$msg.="Please check records again.";
			}
			else
			{
//				$sql="INSERT INTO PULLBACK_LOG(PROFILEID,PULLED_FROM,ALLOTED_TO,PULLBACK_TIME) SELECT PROFILEID,ALLOTED_TO,'$new_allot_to',now() FROM MAIN_ADMIN WHERE PROFILEID='$profileid'";
				$sql="INSERT INTO PULLBACK_LOG(PROFILEID,PULLED_FROM,ALLOTED_TO,PULLBACK_TIME) VALUES('$profileid','$old_alloted','$new_allot_to','now()')";
				mysql_query_decide($sql) or die(mysql_error_js());

				$sql="UPDATE MAIN_ADMIN SET ALLOTED_TO='$new_allot_to' WHERE PROFILEID='$profileid' ";
				mysql_query_decide($sql) or die("2".mysql_error_js());

				$msg=" You have successfully assigned $username to $new_allot_to";
			}
		}

		$msg .= "<a href=\"pullback.php?name=$name&cid=$cid\">";
                $msg .= "Continue &gt;&gt;</a>";

                $smarty->assign("name",$name);
                $smarty->assign("cid",$cid);
                $smarty->assign("MSG",$msg);

                $smarty->display("jsadmin_msg.tpl");
	}
	else
	{
		echo "How did u get here. Click on browser back button to go back.";
	}
}
else
{
	$msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.php\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
