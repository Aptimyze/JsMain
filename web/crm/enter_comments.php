<?php
include("connect.inc");
include("history.php");

if(authenticated($cid))
{
	if($CMDSubmit)
	{
		$operator_name=getname($cid);
		$is_error=0;
		if(trim($comments==''))
		{
			$is_error++;
			$smarty->assign("NO_COMMENTS","Y");
		}

		if($is_error)
		{
			$flag=1;

		        $privilage = explode("+",getprivilage($cid));
        		if(in_array("SLHD",$privilage) || in_array("SLSUP",$privilage) || in_array("P",$privilage) || in_array("MG",$privilage) || in_array("TRNG",$privilage))
         		       $limit =0;
        		else
			{
				$limitCount =getHistoryCount($profileid);
				if($limitCount>=5)
					$limit =$limitCount;
				else
                                	$limit =5;
			}

			$user_values=gethistory($username,$limit);
			$smarty->assign("ROW",$user_values);

			$smarty->assign("USERNAME",$username);
			$smarty->assign("PROFILEID",$profileid);
			$smarty->assign("INBOUND","Y");
			$smarty->assign("NOTFOUND","");
			$smarty->assign("SHOW_FOLLOW","N");
			$smarty->assign("flag",$flag);
			$smarty->assign("cid",$cid);
			$smarty->assign("name",$operator_name);
			$smarty->display("get_history.htm");
		}
		else
		{
			$sql="SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$row=mysql_fetch_array($res);
			$username=$row['USERNAME'];

			$sql="INSERT INTO incentive.HISTORY (PROFILEID,USERNAME,ENTRYBY,MODE,COMMENT,ENTRY_DT) VALUES ('$profileid','".addslashes($username)."','$operator_name','I','".addslashes($comments)."',now())";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());

			$msg="Entry done successfully<br>  ";
			$msg .="<a href=\"mainpage.php?cid=$cid\">";
			$msg .="Continue </a>";
			$smarty->assign("MSG",$msg);
			$smarty->display("crm_msg.tpl");
		}
	}
	else
	{
		echo "Illegal Access! Get out";
	}
}
else
{
        $msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.php\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("crm_msg.tpl");
}
?>
