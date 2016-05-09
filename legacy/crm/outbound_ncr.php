<?php

include("connect.inc");
include("history.php");

if(authenticated($cid))
{
	$flag=0;
	$name= getname($cid);
	if($GetHistory)
	{
		$flag=1;
		$sql = "SELECT USERNAME , EMAIL,SUBSCRIPTION FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
                $result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                if($myrow=mysql_fetch_array($result))
		{
			$temp_email=explode("@",$myrow['EMAIL']);
			$email=$temp_email[0]."@xxx.com";
			$USERNAME = $myrow['USERNAME'];
			$smarty->assign("USERNAME",$USERNAME);
			$smarty->assign("PROFILEID",$profileid);
			$smarty->assign("EMAIL",$email);

			$sql="SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME='$name'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$row=mysql_fetch_array($res);
			$center=strtoupper($row['CENTER']);

			$sql="SELECT USERNAME FROM jsadmin.PSWRDS WHERE UPPER(CENTER)='$center'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$allotarr[]=$row['USERNAME'];
			}
			if($allotarr)
			{
				$allot_str=implode("','",$allotarr);

				$sql="SELECT ALLOTED_TO FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid' AND MODE='O' AND ALLOTED_TO IN ('$allot_str')";
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				if($row=mysql_fetch_array($res))
				{
					$smarty->assign("NOTFOUND","");
					$orig_alloted_to=$row['ALLOTED_TO'];

					$user_values=gethistory($USERNAME);
					$smarty->assign("ROW",$user_values);

					if($orig_alloted_to==$name || $orig_alloted_to =='')
					{
						$smarty->assign("SHOW_FOLLOW","Y");
						if($myrow['SUBSCRIPTION']<>'')
							$smarty->assign("ALREADY_PAID","Y");

					}
					elseif($orig_alloted_to!=$name)
					{
						$smarty->assign("SHOW_FOLLOW","N");
						$smarty->assign("INBOUND","Y");
					}
					$smarty->assign("orig_alloted_to",$orig_alloted_to);
				}
				else
				{
					$smarty->assign("SHOW_FOLLOW","Y");
					$smarty->assign("PROFILEID",$profileid);
					if($myrow['SUBSCRIPTION']<>'')
						$smarty->assign("ALREADY_PAID","Y");
				}
			}
		}
                else
		{
			$smarty->assign("PROFILEID",$profileid);
                        $smarty->assign("wrong_username","Y");
		}

		$smarty->assign("ORDERS",$orders);
		$smarty->assign("flag",$flag);
		$smarty->assign("cid",$cid);
		$smarty->assign("name",$name);
		$smarty->display("outbound_ncr.htm");
	}
	else
	{
		$smarty->assign("flag",$flag);
		$smarty->assign("name",$name);
		$smarty->assign("cid",$cid);
		$smarty->display("outbound_ncr.htm");
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
