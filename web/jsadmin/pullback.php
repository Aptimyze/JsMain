<?php

include("connect.inc");

if(authenticated($cid))
{
	$name=getname($cid);
	if($CMDAssign)
	{
		if(trim($num)=="" && !is_int($num))
                {
                        $msg="Please check the records to assign";
                }
		else
		{
			$sql="INSERT INTO PULLBACK_LOG(MA_ID,PROFILEID,PULLED_FROM,ALLOTED_TO,PULLBACK_TIME,SCREENING_TYPE) SELECT ID,PROFILEID,ALLOTED_TO,'$new_allot_to',now(),SCREENING_TYPE FROM MAIN_ADMIN WHERE SCREENING_TYPE='$scr_type' AND ALLOTED_TO='$old_alloted' ORDER BY ID DESC LIMIT $num";
			mysql_query_decide($sql) or die(mysql_error_js());

			$sql="UPDATE MAIN_ADMIN SET ALLOTED_TO='$new_allot_to' WHERE SCREENING_TYPE='$scr_type' AND ALLOTED_TO='$old_alloted' ORDER BY ID DESC LIMIT $num";
			mysql_query_decide($sql) or die("2".mysql_error_js());

			$msg=" You have successfully assigned $num records to $new_allot_to";
		}

		if($scr_type=='O')
		{
			$msg .= "<a href=\"alternate.php?name=$name&cid=$cid&val=new\">";
		}
		elseif($scr_type=='P')
		{
			$msg .= "<a href=\"showprofilestoassign_new.php?name=$name&cid=$cid&user=n\">";
		}
                $msg .= "Continue &gt;&gt;</a>";

                $smarty->assign("name",$name);
                $smarty->assign("cid",$cid);
                $smarty->assign("MSG",$msg);

                $smarty->display("jsadmin_msg.tpl");
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
