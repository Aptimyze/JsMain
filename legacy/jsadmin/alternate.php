<?php
/*****
	Modified by shiv on Jun 17 , 2005 .
	MOD_DT condition removed from queries as index is made on SCREENING field in JPROFILE
*****/

include("connect.inc");
include("time.php");
include(JsConstants::$docRoot."/commonFiles/flag.php");

global $screen_time;
$tdate=date("Y-m-d");
$lastweek_date=strftime("%Y-%m-%d",JSstrToTime("$tdate-5days "));
$sum=SetAllFlags();

if(authenticated($cid))
{
	$name=getname($cid);
	if($CMDAssign)
	{
		if(trim($num)=="" || !is_numeric($num))
		{
			$msg="Please check the records to assign";
		}
		else
		{
			$pid="";
			if($val=="new")
			{
				$sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE ACTIVATED='N' AND INCOMPLETE = 'N' ORDER BY MOD_DT ASC LIMIT 0,$num";
				//$sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE ACTIVATED='N' AND INCOMPLETE = 'N' ORDER BY MOD_DT ASC LIMIT 0,$num";
			}
			elseif($val=="edit")
			{
				//$sql="SELECT newjs.JPROFILE.PROFILEID as PROFILEID FROM newjs.JPROFILE LEFT JOIN jsadmin.MAIN_ADMIN ON newjs.JPROFILE.PROFILEID = jsadmin.MAIN_ADMIN.PROFILEID WHERE (jsadmin.MAIN_ADMIN.PROFILEID IS NULL) AND ( MOD_DT >= '$lastweek_date' AND ACTIVATED = 'Y' AND SCREENING < '4094303' ) ORDER BY MOD_DT ASC LIMIT 0,$num";
				$sql="SELECT newjs.JPROFILE.PROFILEID as PROFILEID FROM newjs.JPROFILE USE INDEX(SCREENING) LEFT JOIN jsadmin.MAIN_ADMIN ON newjs.JPROFILE.PROFILEID = jsadmin.MAIN_ADMIN.PROFILEID WHERE (jsadmin.MAIN_ADMIN.PROFILEID IS NULL) AND ACTIVATED = 'Y' AND SCREENING < '1099511627775'  ORDER BY MOD_DT ASC LIMIT 0,$num";
			}
			$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());

			$profileid = array();
			if($row=mysql_fetch_array($result))
			{
				$i=0;
				do
				{
					$profileid[$i]=$row['PROFILEID'];
					$i++;
				}while($row=mysql_fetch_array($result));
			}
			$pid="'".implode("','",$profileid)."'";

			for($i=0;$i<count($profileid);$i++)
			{
        	                $sql="SELECT USERNAME, ENTRY_DT,MOD_DT, SUBSCRIPTION, SCREENING from newjs.JPROFILE where PROFILEID='$profileid[$i]'"; 
	                        $result1=mysql_query_decide($sql) or die(mysql_error_js());
        	                $myrow1=mysql_fetch_array($result1);
                	        $receivetime=$myrow1['MOD_DT'];
                        	$submittime=newtime($receivetime,0,$screen_time,0);
	                        $username=$myrow1['USERNAME'];
        	                $subscribe=$myrow1['SUBSCRIPTION'];
				$screening_val=$myrow1['SCREENING'];

                	        $sql_i="REPLACE jsadmin.MAIN_ADMIN (PROFILEID, USERNAME, RECEIVE_TIME, SUBMIT_TIME, ALLOT_TIME, ALLOTED_TO, SCREENING_TYPE, SUBSCRIPTION_TYPE, SCREENING_VAL) values('$profileid[$i]','".addslashes($username)."','$receivetime','$submittime','".date("Y-m-d H:i")."', '$users','O', '$subscribe','$screening_val')"; 
                        	$result=mysql_query_decide($sql_i) or die("$sql_i".mysql_error_js());
	                }

			$sql_u="UPDATE newjs.JPROFILE SET ACTIVATED='U' WHERE ACTIVATED='N' AND PROFILEID in ($pid)";
			$res_u=mysql_query_decide($sql_u) or die("$sql_u".mysql_error_js());

			$msg=" You have successfully assigned $num records to $users";
		}

		$msg .= "<a href=\"alternate.php?name=$name&cid=$cid&val=$val\">";
		$msg .= "Continue &gt;&gt;</a>";

		$smarty->assign("name",$name);
		$smarty->assign("cid",$cid);
		$smarty->assign("flag",$flag);
		$smarty->assign("MSG",$msg);
		$smarty->assign("val",$val);
		
		$smarty->display("jsadmin_msg.tpl");
	}
	else
	{
		if($val=="new")
		{
			$sql_s="SELECT COUNT(*) as cnt FROM newjs.JPROFILE WHERE ACTIVATED='N' AND newjs.JPROFILE.INCOMPLETE='N'";
			//$sql_s="SELECT COUNT(*) as cnt FROM newjs.JPROFILE WHERE ACTIVATED='N' AND newjs.JPROFILE.INCOMPLETE<>'Y'";
		}
		elseif($val=="edit")
		{
			//$sql_s="SELECT count(*) as cnt FROM newjs.JPROFILE LEFT JOIN jsadmin.MAIN_ADMIN ON jsadmin.MAIN_ADMIN.PROFILEID = newjs.JPROFILE.PROFILEID WHERE (jsadmin.MAIN_ADMIN.PROFILEID IS NULL) AND ( ACTIVATED = 'Y' AND SCREENING < '4094303' ) AND MOD_DT>='$lastweek_date' ORDER BY MOD_DT";
			$sql_s="SELECT count(*) as cnt FROM newjs.JPROFILE USE INDEX(SCREENING) LEFT JOIN jsadmin.MAIN_ADMIN ON jsadmin.MAIN_ADMIN.PROFILEID = newjs.JPROFILE.PROFILEID WHERE (jsadmin.MAIN_ADMIN.PROFILEID IS NULL) AND ( ACTIVATED = 'Y' AND SCREENING < '1099511627775' ) ";
		}
		$res_s=mysql_query_decide($sql_s) or die("$sql_s".mysql_error_js());
		$row_s=mysql_fetch_array($res_s);
		$cnt=$row_s['cnt'];
		if(mysql_num_rows($res_s)<1)
		{
			$cnt=0;
		}

		$sql_u="SELECT SQL_CACHE USERNAME,PRIVILAGE FROM jsadmin.PSWRDS WHERE PRIVILAGE like '%NU%' AND ACTIVE='Y'";
		$res_u=mysql_query_decide($sql_u) or die(mysql_error_js());
		if($row_u=mysql_fetch_array($res_u))
		{
			$i=0;
			do
			{
//				$privilage=$row_u['PRIVILAGE'];
//				$priv=explode("+",$privilage);
//				if(count($priv)>2)
//				{
//				}
//				else
//				{
					$user[$i]=$row_u['USERNAME'];
	//				$sql="SELECT COUNT(*) AS sno FROM MAIN_ADMIN WHERE ALLOTED_TO='$user[$i]' AND STATUS='' AND SUBMITED_TIME='0' AND ALLOT_TIME >='$lastweek_date' AND SCREENING_TYPE='O'";
					$sql="SELECT COUNT(*) AS sno FROM MAIN_ADMIN,newjs.JPROFILE WHERE ALLOTED_TO='$user[$i]' AND STATUS='' AND SUBMITED_TIME=0 AND SCREENING_TYPE='O' AND newjs.JPROFILE.ACTIVATED<>'D' AND MAIN_ADMIN.PROFILEID=newjs.JPROFILE.PROFILEID";
// changed on 21st feb by shiv to clear all deleted records - but it didnt click - other users still had profiles - while mahesh was shown zero profiles - changed back on 22nd by shiv
//					$sql="SELECT COUNT(*) AS sno FROM MAIN_ADMIN WHERE ALLOTED_TO='$user[$i]' AND STATUS='' AND SUBMITED_TIME='0' AND SCREENING_TYPE='O'";
					$result=mysql_query_decide($sql) or die(mysql_error_js());
					$row=mysql_fetch_array($result);
					$sno[$i]=$row['sno'];
					$tqueue+=$sno[$i];
					$i++;
//				}
			}while($row_u=mysql_fetch_array($res_u));
		}
		$smarty->assign("user",$user);
		$smarty->assign("cid",$cid);
		$smarty->assign("val",$val);
		$smarty->assign("name",$name);
		$smarty->assign("sno",$sno);
		$smarty->assign("totalnew",$cnt);
		$smarty->assign("totalqueue",$tqueue);
		$smarty->assign("flag",$flag);

		$smarty->display("alternate.htm");
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
