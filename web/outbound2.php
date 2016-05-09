<?php
/**
*       Filename        :       outbound2.php
**/
/**
*       Included        :       connect.inc
*       Description     :       contains functions related to database connection and login authentication
**/

echo " ";
include("connect.inc");
include("mainmenunew.php"); 
include("viewprofilenew.php"); 
//include("../profile/arrays.php");
//include(JsConstants::$docRoot."/commonFiles/flag.php");

if(authenticated($cid))
{
        $name= getname($cid);
//	$center=getcenter($cid,'');
	if($submit)
	{
		if(!is_numeric($ALTERNATE_NO))
		{
			$smarty->assign("check_alternate","Y");
		}
                if($follow=="F")
                {
                        $smarty->assign("check_status","Y");
                }
//		if(!$chour || !$cmin || ($follow=="F" && $follow_time==0))
		if(($follow=="F" && $follow_time==0))
		{
		
	                $sql="SELECT ENTRYBY,MODE,COMMENT,ENTRY_DT FROM incentive.HISTORY WHERE PROFILEID='$profileid' ORDER BY ENTRY_DT DESC";
        	        $res=mysql_query_decide($sql) or die(mysql_error_js());
                	$i=1;
	                while($myrow=mysql_fetch_array($res))
        	        {
                	        $values[] = array("SNO"=>$i,
                                          "NAME"=>$myrow["ENTRYBY"],
                                          "DATE"=>$myrow["ENTRY_DT"],
                                          "MODE"=>$myrow["MODE"],
                                          "COMMENTS"=>str_replace("\n","<br>",$myrow["COMMENT"])
                                         );
	                        $i++;
                	}
			$smarty->assign("ROW",$values);

			if($follow=="F" && $follow_time==0)
				$check_followtime = "Y";
			$smarty->assign("check_followtime",$check_followtime);
			$smarty->assign("follow_time",$follow_time);
                   //     $smarty->assign("chour",$chour);
                     //   $smarty->assign("cmin",$cmin);
			$smarty->assign("follow",$follow);
			$smarty->assign("ALTERNATE_NO",$ALTERNATE_NO);
			$smarty->assign("profileid",$profileid);
			
			$smarty->assign("COMMENTS",$COMMENTS);
			$smarty->assign("WILL_PAY",$WILL_PAY);
			$smarty->assign("USERNAME",$USERNAME);
			$smarty->assign("PHONE_MOB",$PHONE_MOB);
			$smarty->assign("PHONE_RES",$PHONE_RES);
			$checksum=md5($profileid)."i".$profileid;
//			$smarty->assign("username",$username);
			$smarty->assign("profileid",$profileid);
			$smarty->assign("CHECKSUM",$checksum);
			$smarty->assign("cid",$cid);

	                $pmsg=viewprofile($USERNAME,"internal");
	                $msg=profileview($profileid,$checksum);
        	        $smarty->assign("msg",$msg);
                	$smarty->assign("pmsg",$pmsg);

			$smarty->display("outbound2.htm");
		}
		else
		{
			$convincearr=array($chour,$cmin,"00");
			$convince=implode(":",$convincearr);
			if($follow!='F')
			{
				$sql1="DELETE FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid' AND ALLOTED_TO='$name'";
				mysql_query_decide($sql1) or die("1 $sql1".mysql_error_js());
				$sql2 = "INSERT INTO incentive.CLAIM (PROFILEID,USERNAME,CONVINCE_TIME,COMMENT,ENTRY_TIME,ENTRYBY,STATUS,MODE,RES_NO,MOB_NO,EMAIL,WILL_PAY) VALUES ('$profileid','$USERNAME',now(),'$COMMENTS',now(),'$name','$follow','O','$PHONE_MOB','$PHONE_RES','$EMAIL','$WILL_PAY')";
            			mysql_query_decide($sql2) or die("2 $sql2".mysql_error_js());
			}
			else
			{
				$sql3 = "UPDATE incentive.MAIN_ADMIN SET STATUS='$follow',FOLLOWUP_TIME='$follow_time',ALTERNATE_NO='$ALTERNATE_NO',CONVINCE_TIME=now(),WILL_PAY='$WILL_PAY',COMMENTS='$COMMENTS' WHERE PROFILEID='$profileid' AND ALLOTED_TO='$name' AND MODE='O'";
				mysql_query_decide($sql3) or die("3 $sql3".mysql_error_js());		
			}
                        $sql4 = "INSERT INTO incentive.HISTORY (PROFILEID,USERNAME,ENTRYBY,MODE,COMMENT,ENTRY_DT) VALUES ('$profileid','$USERNAME','$name','O','$COMMENTS',now())";
			mysql_query_decide($sql4) or die("4 $sql4".mysql_error_js());

		}	
		$msg ="Entry for <font color=\"blue\">$USERNAME</font> is done<br>";
		$msg .= "<a href=\"\" onclick= \"window.close()\">Close Window</a>";
                                                                                                 
                $smarty->assign("name",$name);
                $smarty->assign("cid",$cid);
                $smarty->assign("MSG",$msg);
                $smarty->display("incentive_msg.tpl");

                echo "<br>";
                echo "<script language='JavaScript'>
                         opener.location.reload(true);
                       </script>";
		die;

	}
	else
	{
        	$sql= "SELECT USERNAME,PHONE_MOB,PHONE_RES FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
	        $result = mysql_query_decide($sql) or die("4".mysql_error_js());
	        $myrow=mysql_fetch_array($result);
		$USERNAME=$myrow["USERNAME"];
		$PHONE_MOB=$myrow["PHONE_MOB"];
		$PHONE_RES=$myrow["PHONE_RES"];	

		$sql="SELECT ALTERNATE_NO,COMMENTS,WILL_PAY FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid' AND STATUS='F'";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		$row=mysql_fetch_array($res);
		$alt_no=$row['ALTERNATE_NO'];
		$comments=$row['COMMENTS'];
		$will_pay=$row['WILL_PAY'];

		$sql="SELECT ENTRYBY,MODE,COMMENT,ENTRY_DT FROM incentive.HISTORY WHERE PROFILEID='$profileid' ORDER BY ENTRY_DT DESC";
                $res=mysql_query_decide($sql) or die(mysql_error_js());
		$i=1;
		while($myrow=mysql_fetch_array($res))
		{
			$values[] = array("SNO"=>$i,
					  "NAME"=>$myrow["ENTRYBY"],
					  "DATE"=>$myrow["ENTRY_DT"],
					  "MODE"=>$myrow["MODE"],
					  "COMMENTS"=>str_replace("\n","<br>",$myrow["COMMENT"])
					 );
			$i++;

		}

                $smarty->assign("ROW",$values);

	        $smarty->assign("USERNAME",$USERNAME);
		$smarty->assign("PHONE_MOB",$PHONE_MOB);
		$smarty->assign("PHONE_RES",$PHONE_RES);
		$smarty->assign("ALTERNATE_NO",$alt_no);
		$smarty->assign("COMMENTS",$comments);
		$smarty->assign("WILL_PAY",$will_pay);

                $checksum=md5($profileid)."i".$profileid;
		$smarty->assign("follow_time","0000:00:00 00:00");
                $smarty->assign("profileid",$profileid);
                $smarty->assign("CHECKSUM",$checksum);
                $smarty->assign("cid",$cid);
                $smarty->assign("profileid",$profileid);

		$pmsg=viewprofile($USERNAME,"internal");
//                $msg=profileview($profileid,$checksum);
		profileview($profileid,$checksum);
		$msg=$smarty->fetch("../crm/login1.htm");
                $smarty->assign("msg",$msg);
                $smarty->assign("pmsg",$pmsg);
                $smarty->display("outbound2.htm");
	}
}
else//user timed out
{
        $msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.php\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
