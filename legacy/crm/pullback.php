<?php

include("connect.inc");

if(authenticated($cid))
{
	$name=getname($cid);
	if($CMDAssign)
	{
		if(trim($num)=="" || !is_int($num))
                {
                        $msg="Please check the records to assign";
                }
		else
		{
			$sql="INSERT INTO PULLBACK_LOG(PROFILEID,PULLED_FROM,ALLOTED_TO,PULLBACK_TIME) SELECT PROFILEID,ALLOTED_TO,'$new_allot_to',now() FROM MAIN_ADMIN WHERE STATUS='' AND ALLOTED_TO='$old_alloted' ORDER BY ALLOT_TIME DESC LIMIT $num";
			mysql_query_decide($sql) or die(mysql_error_js());

			$sql="UPDATE MAIN_ADMIN SET ALLOTED_TO='$new_allot_to' WHERE STATUS='' AND ALLOTED_TO='$old_alloted' ORDER BY ALLOT_TIME DESC LIMIT $num";
			mysql_query_decide($sql) or die("2".mysql_error_js());

			$msg=" You have successfully assigned $num records to $new_allot_to";
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
		$sql="SELECT USERNAME,CENTER FROM jsadmin.PSWRDS WHERE PRIVILAGE LIKE '%IUO%'";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			$i=0;
			$total=0;
			do
			{
				$operators[$i]["NAME"]=$row['USERNAME'];
				$temp=$operators[$i]["NAME"];
				$operators[$i]["CENTER"]=$row['CENTER'];
				$sql_c="SELECT COUNT(*) as cnt FROM MAIN_ADMIN WHERE STATUS='' AND ALLOTED_TO='$temp'";
				$res_c=mysql_query_decide($sql_c) or die(mysql_error_js());
				if($row_c=mysql_fetch_array($res_c))
				{
					$operators[$i]["COUNT"]=$row_c['cnt'];
					$total+=$operators[$i]["COUNT"];
				}
				$i++;
			}while($row=mysql_fetch_array($res));
		}

		$smarty->assign("operators",$operators);
		$smarty->assign("total",$total);

		$smarty->assign("name",$name);
		$smarty->assign("cid",$cid);
		$smarty->display("pullback.htm");
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
