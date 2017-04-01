<?php

include("connect.inc");
include("matches_display_results.inc");
include("../crm/func_sky.php");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");

$db=connect_db();

if(authenticated($cid))
{
        comp_info($profileid);
	$sql="SELECT BILLID FROM jsadmin.OFFLINE_BILLING WHERE PROFILEID='$profileid' ORDER BY ENTRY_DATE DESC LIMIT 1";
	$res=mysql_query_decide($sql) or logError($sql);
	$row=mysql_fetch_assoc($res);
	$billid=$row["BILLID"];
	$sql="SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
	$res=mysql_query_decide($sql) or logError($sql);
	$row=mysql_fetch_assoc($res);
	$oc_id=$row["USERNAME"];
	$smarty->assign("oc_id",$oc_id);
	$jprofileObj    =JProfileUpdateLib::getInstance();
        if($searchid)
        {
		$sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE USERNAME='$searchid' AND ACTIVATED='Y'";
		$res=mysql_query_decide($sql) or logError($sql);
		if(mysql_num_rows($res)>0)
		{
			$row=mysql_fetch_assoc($res);
			$searchpid=$row["PROFILEID"];
			$sql="SELECT COUNT(*) AS CNT FROM jsadmin.OFFLINE_MATCHES WHERE MATCH_ID='$searchpid' AND PROFILEID='$profileid' AND STATUS= 'REJ' AND CATEGORY!=''";
			$res=mysql_query_decide($sql) or logError($sql);
			$row=mysql_fetch_assoc($res);
			if($row["CNT"]>0)
			{
				assigndetails($profileid,$searchpid);
				$viewprofile=$smarty->fetch("displayprofile.htm");
				$smarty->assign("viewprofile",$viewprofile);
				$smarty->assign("SEARCHED_PROFILE",1);
			}
			else
				$smarty->assign("NOTINREJ",1);
		}
        	else
		{
			$smarty->assign("WRONGID",1);
		}
		
        }
	elseif($accid)
	 {
		accept_profile($profileid,$accid,$oc_id);
		$sql= "SELECT ACC_MADE,ACC_ALLOWED,ACC_UPGRADED FROM jsadmin.OFFLINE_BILLING WHERE PROFILEID= '$profileid' AND BILLID='$billid'";
		$res= mysql_query_decide($sql) or die(mysql_error_js());
		$row= mysql_fetch_array($res);
		$acc_made= $row['ACC_MADE'];
		$acc_allowed= $row['ACC_ALLOWED'];
		$acc_upgraded=$row["ACC_UPGRADED"];
		$acc_made= $acc_made+1;
		$acc_remain= $acc_allowed+$acc_upgraded-$acc_made;
		if($acc_remain<=0)
		{
			$sql= "UPDATE jsadmin.OFFLINE_BILLING SET ACTIVE= 'N',ACC_MADE='$acc_made' WHERE PROFILEID= '$profileid' AND BILLID='$billid'";
			mysql_query_decide($sql) or logError($sql);
			/*$sql= "UPDATE newjs.JPROFILE SET ACTIVATED= 'D',activatedKey=0 WHERE PROFILEID= '$profileid'";
			mysql_query_decide($sql) or logError($sql);*/
                        $paramArr =array("ACTIVATED"=>'D',"activatedKey"=>0);
                        $jprofileObj->editJPROFILE($paramArr,$profileid,'PROFILEID');

			comp_info($profileid);
		}
		else
		{
			$sql="UPDATE jsadmin.OFFLINE_BILLING SET ACC_MADE='$acc_made' WHERE PROFILEID='$profileid' AND BILLID='$billid'";
			mysql_query_decide($sql) or logError($sql);
			$smarty->assign("acc_remain",$acc_remain);
		}
		$smarty->assign("ACCEPTED",1);
	 }
	elseif($profile)
	{
		$count=0;
		foreach($profile as $key=>$value)
		{
			accept_profile($profileid,$value,$oc_id);
			$smarty->assign("ACCEPTED",1);
			$count++;
		}
		$sql= "SELECT ACC_MADE,ACC_ALLOWED,ACC_UPGRADED FROM jsadmin.OFFLINE_BILLING WHERE PROFILEID= '$profileid' AND BILLID='$billid'";
                $res= mysql_query_decide($sql) or die(mysql_error_js());
                $row= mysql_fetch_array($res);
                        $acc_made= $row['ACC_MADE'];
                        $acc_allowed= $row['ACC_ALLOWED'];
                        $acc_upgraded=$row["ACC_UPGRADED"];
                        $acc_made= $acc_made+$count;
                        $acc_remain= $acc_allowed+$acc_upgraded-$acc_made;
                if($acc_remain<=0)
                 {
                           $sql= "UPDATE jsadmin.OFFLINE_BILLING SET ACTIVE= 'N',ACC_MADE='$acc_made' WHERE PROFILEID= '$profileid' AND BILLID='$billid'";
                                mysql_query_decide($sql) or logError($sql);
                                /*$sql= "UPDATE newjs.JPROFILE SET ACTIVATED= 'D',activatedKey=0 WHERE PROFILEID= '$profileid'";
                                mysql_query_decide($sql) or logError($sql);*/
	                        $paramArr =array("ACTIVATED"=>'D',"activatedKey"=>0);
	                        $jprofileObj->editJPROFILE($paramArr,$profileid,'PROFILEID');

                                comp_info($profileid);
                  }
                  else
                  {
                       $sql="UPDATE jsadmin.OFFLINE_BILLING SET ACC_MADE='$acc_made' WHERE PROFILEID='$profileid' AND BILLID='$billid'";
                       mysql_query_decide($sql) or logError($sql);
                                $smarty->assign("acc_remain",$acc_remain);
                    }
	}
	        $PAGELEN=10;
                if(!$j)
                $j=0;
                $sql="SELECT COUNT(*) AS CNT FROM jsadmin.OFFLINE_MATCHES WHERE PROFILEID='$profileid' AND CATEGORY!='' AND STATUS= 'REJ'";
                $res=mysql_query_decide($sql) or logError($sql);
                $row=mysql_fetch_assoc($res);
                $totalcount=$row["CNT"];
                $sql="SELECT MATCH_ID AS PROFILEID FROM jsadmin.OFFLINE_MATCHES WHERE PROFILEID='$profileid' AND CATEGORY!='' AND STATUS='REJ' ORDER BY MOD_DATE DESC LIMIT $j,$PAGELEN";
                $res=mysql_query_decide($sql) or logError($sql);
                if(mysql_num_rows($res))
                {
                        displayresults($res,$j,"/jsadmin/rejected_matches.php",$totalcount,'',"1",'',"cid=$cid&profileid=$profileid",'','','','','',"admin",$profileid,$cid);
                }
                else
                $smarty->assign("NOREC",1);
        
	$smarty->assign("accid",$accid);
	$smarty->assign("flg",$flg);	
        $smarty->assign("cid",$cid);
        $smarty->assign("profileid",$profileid);
	

	 $smarty->display("rejected_matches.htm");

}
else
{
        $msg="Your session has been timed out<br><br>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}

?>

