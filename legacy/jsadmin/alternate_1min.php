<?php
/*********************************************************************************************
* FILE NAME             : alternate_1min.php
* DESCRIPTION           : script for allocating profiles of 1 min registration to a particular user
* CREATION DATE         : 5 Oct, 2005
* CREATED BY            : Gaurav Arora
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
include("connect.inc");
include("time.php");
include(JsConstants::$docRoot."/commonFiles/flag.php");

global $screen_time;
$tdate=date("Y-m-d");
$lastweek_date=strftime("%Y-%m-%d",JSstrToTime("$tdate-7days "));
$sum=SetAllFlags();

if(authenticated($cid))
{
	$name=getname($cid);
	//$sql="SELECT * FROM MAIN_ADMIN_1MIN WHERE ";
	if($CMDAssign)
	{
		if(trim($num)=="" || !is_numeric($num))
		{
			$msg="Please check the records to assign";
		}
		else
		{
			$pid="";
                        $sql="SELECT ID FROM newjs.JPROFILE_AFFILIATE WHERE BACKEND='Y' AND MOVED='N' ORDER BY MOD_DT ASC LIMIT 0,$num";
			$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$profileid = array();
			if($row=mysql_fetch_array($result))
			{
				$i=0;
				do
				{
					$profileid[$i]=$row['ID'];
					$i++;
				}while($row=mysql_fetch_array($result));
			}
			$pid="'".implode("','",$profileid)."'";
			$sql_u="UPDATE newjs.JPROFILE_AFFILIATE SET BACKEND='B' WHERE ID in ($pid)";
			$res_u=mysql_query_decide($sql_u) or die("$sql_u".mysql_error_js());

			for($i=0;$i<count($profileid);$i++)
			{
			        $sql="SELECT ENTRY_DT,MOD_DT from newjs.JPROFILE_AFFILIATE where ID='$profileid[$i]'"; 
	                        $result1=mysql_query_decide($sql) or die(mysql_error_js());
        	                $myrow1=mysql_fetch_array($result1);
                	        $receivetime=$myrow1['MOD_DT'];
                        	$submittime=newtime($receivetime,0,$screen_time,0);

                	        $sql_i="REPLACE jsadmin.MAIN_ADMIN_1MIN (PROFILEID, RECEIVE_TIME, SUBMIT_TIME, ALLOT_TIME, ALLOTED_TO) values('$profileid[$i]','$receivetime','$submittime','".date("Y-m-d H:i")."', '$users')"; 
                        	$result=mysql_query_decide($sql_i) or die("$sql_i".mysql_error_js());
	                }

			$msg=" You have successfully assigned $num records to $users";
		}

		$msg .= "<a href=\"alternate_1min.php?name=$name&cid=$cid\">";
		$msg .= "Continue &gt;&gt;</a>";
		$smarty->assign("name",$name);
		$smarty->assign("cid",$cid);
		$smarty->assign("flag",$flag);
		$smarty->assign("MSG",$msg);
		//$smarty->assign("val",$val);
		
		$smarty->display("jsadmin_msg.tpl");
	}
	else
	{
		$sql_u="SELECT SQL_CACHE USERNAME,PRIVILAGE FROM jsadmin.PSWRDS WHERE PRIVILAGE like '%NU%'";
		$res_u=mysql_query_decide($sql_u) or die(mysql_error_js());
		if($row_u=mysql_fetch_array($res_u))
		{
			$i=0;
			do
			{
					$user[$i]=$row_u['USERNAME'];
					$sql="SELECT COUNT(*) AS sno FROM MAIN_ADMIN_1MIN WHERE ALLOTED_TO='$user[$i]'"; //AND COMPLETED='N'";
					$result=mysql_query_decide($sql) or die(mysql_error_js());
					$row=mysql_fetch_array($result);
					$sno[$i]=$row['sno'];
					$tqueue+=$sno[$i];
					$i++;
			}while($row_u=mysql_fetch_array($res_u));
		}
		$smarty->assign("user",$user);
		$smarty->assign("cid",$cid);
		$smarty->assign("val",$val);
		$smarty->assign("name",$name);
		$smarty->assign("sno",$sno);
		$sql="SELECT COUNT(*) AS cnt FROM newjs.JPROFILE_AFFILIATE where BACKEND='Y' AND MOVED='N'";
		$result=mysql_query_decide($sql) or die(mysql_error_js());
		$row=mysql_fetch_array($result);
		$smarty->assign("totalnew",$row['cnt']);
		$smarty->assign("totalqueue",$tqueue);
		$smarty->assign("flag",$flag);

		$smarty->display("alternate_1min.htm");
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
