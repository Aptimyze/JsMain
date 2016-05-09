<?php
/**************************************************************************************************
Filename    : voucher_backend_tech.php
Description : Upload series for new deals by tech team for voucher backend module [2190]
Created On  : 7 September 2007
Created By  : Sadaf Alam
**************************************************************************************************/
include("connect.inc");
include("../crm/func_sky.php");
$db=connect_db();

if(authenticated($cid))
{
	if($formsubmit)
	{
		$sql_status="SELECT SERVICE,STATUS FROM billing.VOUCHER_CLIENTS WHERE CLIENTID='$clientid'";
		$res_status=mysql_query_decide($sql_status) or die("$sql_status".mysql_error_js());
		$row_status=mysql_fetch_assoc($res_status);
		if($row_status["SERVICE"]=="N")
		{
			$msg="The deal has been stopped by the sales team. Please wait until furthur intimation";
			$msg .="&nbsp;&nbsp;";
			$msg .="<a href=\"voucher_backend_tech.php?name=$name&cid=$cid\">";
			$msg .="Click here to continue&gt;&gt;</a>";
			$smarty->assign("MSG",$msg);
			$smarty->assign("name",$name);
			$smarty->assign("cid",$cid);
			$smarty->display("jsadmin_msg.tpl");
			die;
		}
		if($editdeal)
		{
			if($row_status["STATUS"]=="D" || $row_status["STATUS"]=="RD")
			{
				$msg="The deal is being updated by the design team. Once they are done, the tech team will be intimated and the deal can be updated from the Design Pending Deals section";
                                $msg .="&nbsp;&nbsp;";
                                $msg .="<a href=\"voucher_backend_tech.php?name=$name&cid=$cid&edit=1\">";
                                $msg .="Click here to continue&gt;&gt;</a>";
                                $smarty->assign("MSG",$msg);
                                $smarty->assign("name",$name);
                                $smarty->assign("cid",$cid);
                                $smarty->display("jsadmin_msg.tpl");
                                die;

			}
		}
		else
		{
			if($row_status["STATUS"]=="RT")
			{
				$sql="UPDATE billing.VOUCHER_CLIENTS SET STATUS='T' WHERE CLIENTID='$clientid'";
				mysql_query_decide($sql) or die("$sql".mysql_error_js());
				$msg="The deal has been updated again by the sales team for tech details. Please select the deal from the Design Pending Deals section";
                                $msg .="&nbsp;&nbsp;";
                                $msg .="<a href=\"voucher_backend_tech.php?name=$name&cid=$cid\">";
                                $msg .="Click here to continue&gt;&gt;</a>";
                                $smarty->assign("MSG",$msg);
                                $smarty->assign("name",$name);
                                $smarty->assign("cid",$cid);
                                $smarty->display("jsadmin_msg.tpl");
                                die;

			}
			elseif($row_status["STATUS"]=="D" || $row_status["STATUS"]=="RD")
			{
				$msg="The deal has been assigned to the design team for design details. Once they are done, the tech team will be intimated by email and the deal can be updated from the Design Pending Deals section";
                                $msg .="&nbsp;&nbsp;";
                                $msg .="<a href=\"voucher_backend_tech.php?name=$name&cid=$cid\">";
                                $msg .="Click here to continue&gt;&gt;</a>";
                                $smarty->assign("MSG",$msg);
                                $smarty->assign("name",$name);
                                $smarty->assign("cid",$cid);
                                $smarty->display("jsadmin_msg.tpl");
                                die;

			}
			
		}
		if(($selfseries || $series) && !$seriestext)
		{
			$smarty->assign("NO_SERIES","1");
			$smarty->assign("cname",$cname);
			$smarty->assign("sdate",$sdate);
			$smarty->assign("edate",$edate);
			$smarty->assign("comments",$comments);
			$smarty->assign("duration",$duration);
			$smarty->assign("num",$num);
			if($selfseries)
			$smarty->assign("selfseries","1");
			elseif($series)
			$smarty->assign("series",$series);
			$smarty->assign("clientid",$clientid);
			$smarty->assign("EDIT","1");
			if($editdeal)
			$smarty->assign("editdeal",$editdeal);
		}
		else
		{
			if($editdeal)
			{
				if($selfseries || $series)
				$sql="UPDATE billing.VOUCHER_CLIENTS SET SERIES='$seriestext' WHERE CLIENTID='$clientid'";
			}
			else
			{
				$sql_status="SELECT SERVICE FROM billing.VOUCHER_CLIENTS WHERE CLIENTID='$clientid'";
				$res_status=mysql_query_decide($sql_status) or die("$sql_status".mysql_error_js());
				$row_status=mysql_fetch_assoc($res_status);
				$sql="UPDATE billing.VOUCHER_CLIENTS SET ";
				if($row_status["SERVICE"]=='')
				$sql.="STATUS='C'";
				elseif($row_status["SERVICE"]=='Y')
				$sql.="STATUS='L'";
				if($selfseries || $series)
				$sql.=",SERIES='$seriestext'";
				$sql.=" WHERE CLIENTID='$clientid'";
			}
			if($sql)
			mysql_query_decide($sql) or die("$sql".mysql_error_js());
			if($editdeal)
			{
				$msg="Technical End for Client : ".$cname." has been edited. The deal will go live on the $sdate";
				$subject="Deal Edited by Tech team ".$cname;
			}
			else
			{
				$msg="Technical End for Client : ".$cname." has been successfully completed. The deal will go live on $sdate";
				$subject="Deal Updated by Tech team ".$cname;
			}
			if($selfseries || $series)
			$msg.=" Series generated for the same is : ".$seriestext;
			else
			$msg.=" Series has been provided by the client";
			send_mail("shweta.bahl@naukri.com",'lotika.sharma@naukri.com','',$msg,$subject,"promotions@jeevansathi.com");
			$smarty->assign("cname",$cname);
			$smarty->assign("DONE","1");
			if($editdeal)
			$smarty->assign("editdeal",$editdeal);
		}	
	}
	elseif($dealsubmit)
	{
		$sql="SELECT CLIENT_NAME,DURATION,SERIES,NUM,START_DATE,EXPIRY_DATE,COMMENTS,SERIES_FILE FROM billing.VOUCHER_CLIENTS WHERE CLIENTID='$deal'";
		$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$row=mysql_fetch_assoc($result);
		$smarty->assign("duration",$row["DURATION"]);
		$smarty->assign("clientid",$deal);
		$smarty->assign("cname",$row["CLIENT_NAME"]);
		$smarty->assign("sdate",$row["START_DATE"]);
		$smarty->assign("edate",$row["EXPIRY_DATE"]);
		$smarty->assign("num",$row["NUM"]);
		$smarty->assign("comments",html_entity_decode($row["COMMENTS"],ENT_QUOTES));
		if($row["SERIES"]=="FILE")
		{
			$filename=$row["SERIES_FILE"];
			$link="<a href=\"voucher_backend_download.php?filename=$filename&tech=1\">Download</a>";
			$smarty->assign("link",$link);
		}
		elseif($row["SERIES"]=="SELF")
		$smarty->assign("selfseries","1");
		else
		{
			$smarty->assign("series",$row["SERIES"]);
		}
		
		$smarty->assign("EDIT","1");	 	
		if($editdeal)
		$smarty->assign("editdeal",$editdeal);
	}
	else
	{
		if($editdeal)
		$sql="SELECT CLIENTID,CLIENT_NAME FROM billing.VOUCHER_CLIENTS WHERE STATUS NOT IN('T','RT') AND SERVICE!='N'";
		else
		$sql="SELECT CLIENTID,CLIENT_NAME,STATUS FROM billing.VOUCHER_CLIENTS WHERE STATUS IN('T','RT') AND SERVICE!='N'";
		$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		if(mysql_num_rows($result))
		{
			while($row=mysql_fetch_assoc($result))
			{
				$value=$row["CLIENTID"];
				$label=$row["CLIENT_NAME"];
				$options.="<option value=$value>$label</option>";
				if($row["STATUS"]=="RT")
                                {
					$sql_status="UPDATE billing.VOUCHER_CLIENTS SET STATUS='T' WHERE CLIENTID='$row[CLIENTID]'";
                                        mysql_query_decide($sql_status) or die("$sql_status".mysql_error_js());
                                }

			}
			$smarty->assign("options",$options);
		}
		else
		$smarty->assign("NODEALS","1");
		if($editdeal)
		$smarty->assign("editdeal","1");
	}
	$smarty->assign("name",$name);
	$smarty->assign("cid",$cid);
	$smarty->display("voucher_backend_tech.htm");
}
else
{
	$msg="Your session has been timed out";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
