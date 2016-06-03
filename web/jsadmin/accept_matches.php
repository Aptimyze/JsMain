<?php
/******************************************************************************************************************
includeme   : accept_matches.php
Description : Display the accepted matches for the offline customer [2586]
Created By  : Vibhor Garg
Created On  : 22 January 2008
*******************************************************************************************************************/
include("connect.inc");
include("matches_display_results.inc");
$db=connect_db();
if(authenticated($cid))
{
       	comp_info($profileid,0);
	if($accid)
		{
			$sql1= "SELECT ACTIVE FROM jsadmin.OFFLINE_BILLING WHERE PROFILEID= '$profileid'";
	        $res1= mysql_query_decide($sql1) or die(mysql_error_js());
	        while($row= mysql_fetch_array($res1))
	        {
	                $active= $row['ACTIVE'];
	        } 
	        if($active== 'N')
			{
				$nmsg= "YOUR SERVICE IS EXPIRED!!!";
			}		
		}
       	if($searchid)
        {
			$sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE USERNAME='$searchid'";
			$res=mysql_query_decide($sql) or logError($sql);
			if(mysql_num_rows($res)>0)
			{
			    $row=mysql_fetch_assoc($res);
				$searchpid=$row["PROFILEID"];
				$sql="SELECT COUNT(*) AS CNT FROM jsadmin.OFFLINE_MATCHES WHERE MATCH_ID='$searchpid' AND PROFILEID='$profileid' AND STATUS= 'ACC' AND CATEGORY!=''";
				$res=mysql_query_decide($sql) or logError($sql);
				$row=mysql_fetch_assoc($res);
				if($row["CNT"]>0)
				{
					contact($searchpid,1);
					assigndetails($profileid,$searchpid);
					$viewprofile=$smarty->fetch("displayprofile.htm");
					$smarty->assign("viewprofile",$viewprofile);
					$smarty->assign("cid",$cid);
					$smarty->assign("profileid",$profileid);
					$smarty->assign("SEARCHED_PROFILE",1);
					$smarty->assign("POOL",1);
				}
				else
				{
					$smarty->assign("NOTINACC",1);
					$smarty->assign("cid",$cid);
				}	
			}
        	else
        	{
            		$smarty->assign("WRONGID",1);
            		$smarty->assign("cid",$cid);
        	}			

        }       
	elseif($printall)
        {	
                $print_profiles=explode("|",$print_string);
		foreach($print_profiles as $key=>$value)
                {	
			if($value != "")
			{
				contact($value,1);
				assigndetails($profileid,$value);
				$viewprofile[]=$smarty->fetch("print_profile.htm");
			}
     		}
		if(count($print)>1)
                	$smarty->assign("PRINTP",count($print));
                else
                	$smarty->assign("PRINTP",1);
		$smarty->assign("all_print_profiles",$viewprofile);			
        }
	elseif($print)
	{
		if($action == 2)
		{
			foreach($print as $key=>$value)
                        {
				$sql="UPDATE jsadmin.OFFLINE_MATCHES SET COURIERED='Y' WHERE PROFILEID= '$profileid' AND MATCH_ID= '$value'";
	                	mysql_query_decide($sql) or die(mysql_error_js());
			}
        	       	$smarty->assign("COURIERED","Y");
		}
		elseif($action == 3)
                {
                        foreach($print as $key=>$value)
                        {
				$sql="UPDATE jsadmin.OFFLINE_MATCHES SET COURIERED='N' WHERE PROFILEID= '$profileid' AND MATCH_ID= '$value'";
        	                mysql_query_decide($sql) or die(mysql_error_js());
                	}
		        $smarty->assign("COURIERED","N");
                }
		elseif(!$action)
		{
			foreach($print as $key=>$value)
			{
				$sql="SELECT CATEGORY FROM jsadmin.OFFLINE_MATCHES WHERE PROFILEID='$profileid' AND MATCH_ID='$value'";
				$res=mysql_query_decide($sql) or logError($sql);
				$row=mysql_fetch_assoc($res);
				$cat=$row["CATEGORY"];
				$sql="UPDATE jsadmin.OFFLINE_MATCHES SET STATUS='REJ',MOD_DATE=now() WHERE PROFILEID='$profileid' AND MATCH_ID='$value'";
				mysql_query_decide($sql) or logError($sql);
				//$sql="UPDATE jsadmin.OFFLINE_NUDGE_LOG SET SENDER_STATUS='R',RECEIVER_STATUS='R' WHERE SENDER='$value' AND RECEIVER='$profileid' AND TYPE IN('N','NACC')";
				//mysql_query_decide($sql) or logError($sql);
				if($cat==1 || $cat==2)
				{
					$sql="INSERT INTO jsadmin.OFFLINE_NUDGE_LOG(SENDER,RECEIVER,DATE,TYPE) VALUES('$profileid','$value',NOW(),'REJ')";
					mysql_query_decide($sql) or logError($sql);
				}
				$sql_up="delete from newjs.CONTACTS_STATUS where PROFILEID='$value'";
				mysql_query_decide($sql_up) or logError($sql_up);


			}
			if(count($profile)>1)
				$smarty->assign("REJECTED","N");
			else
				$smarty->assign("REJECTED",1);
		}
	}
	else
	{
		if($accid)
                {
                        $sql="SELECT STATUS,CATEGORY FROM jsadmin.OFFLINE_MATCHES WHERE PROFILEID= '$profileid' AND MATCH_ID='$accid'";
                        $res= mysql_query_decide($sql) or die(mysql_error_js());
                        while($row=mysql_fetch_array($res))
                        {
                                $stat= $row['STATUS'];
                                $cat=$row["CATEGORY"];
                        }

                        if($stat=='ACC')
                        {
                                if(($flagr == '1'))
                                {
					$sql="UPDATE jsadmin.OFFLINE_MATCHES SET STATUS= 'REJ',MOD_DATE=now() WHERE PROFILEID= '$profileid' AND MATCH_ID= '$accid'";
                                        mysql_query_decide($sql) or die(mysql_error_js());
					$sql_up="delete from newjs.CONTACTS_STATUS where PROFILEID='$accid'";
					mysql_query_decide($sql_up) or logError($sql_up);

                                        //$sql="UPDATE jsadmin.OFFLINE_NUDGE_LOG SET SENDER_STATUS='R',RECEIVER_STATUS='R' WHERE (SENDER='$accid' AND RECEIVER='$profileid' AND TYPE='NACC') OR (SENDER='$profileid' AND RECEIVER='$accid' AND TYPE IN('N','NNOW')";
                                        //mysql_query_decide($sql) or logError($sql);
                                        if($cat==1 || $cat==2)
                                        {
                                                $sql="INSERT INTO jsadmin.OFFLINE_NUDGE_LOG(SENDER,RECEIVER,DATE,TYPE) VALUES('$profileid','$accid',NOW(),'REJ')";
                                                mysql_query_decide($sql) or logError($sql);
                                        }

                                        if(count($profile)>1)
                                                $smarty->assign("REJECTED","N");
                                        else
                                                $smarty->assign("REJECTED",1);

                                }
			}
		}
	}   
	if($mvta)
	{
		$sql="UPDATE jsadmin.OFFLINE_MATCHES SET COURIERED='N' WHERE PROFILEID= '$profileid' AND MATCH_ID= '$accid'";
		mysql_query_decide($sql) or die(mysql_error_js());
		$smarty->assign("COURIERED","N");
	}
	elseif($mvtc)
	{
		$sql="UPDATE jsadmin.OFFLINE_MATCHES SET COURIERED='Y' WHERE PROFILEID= '$profileid' AND MATCH_ID= '$accid'";
		mysql_query_decide($sql) or die(mysql_error_js());
		$smarty->assign("COURIERED","Y");
	}	
	$PAGELEN=10;
	if(!$j)
	    $j=0;
	if($crd_status)
		$sql="SELECT COUNT(*) AS CNT FROM jsadmin.OFFLINE_MATCHES WHERE PROFILEID='$profileid' AND CATEGORY!='' AND STATUS= 'ACC' AND COURIERED='Y'";
	else
	    	$sql="SELECT COUNT(*) AS CNT FROM jsadmin.OFFLINE_MATCHES WHERE PROFILEID='$profileid' AND CATEGORY!='' AND STATUS= 'ACC' AND COURIERED!='Y'";
	$res=mysql_query_decide($sql) or logError($sql);
	$row=mysql_fetch_assoc($res);
	$totalcount=$row["CNT"];
	if($crd_status)
		$sql="SELECT MATCH_ID AS PROFILEID FROM jsadmin.OFFLINE_MATCHES WHERE PROFILEID='$profileid' AND CATEGORY!='' AND STATUS='ACC' AND COURIERED='Y' ORDER BY MOD_DATE DESC LIMIT $j,$PAGELEN";
	else
	    	$sql="SELECT MATCH_ID AS PROFILEID FROM jsadmin.OFFLINE_MATCHES WHERE PROFILEID='$profileid' AND CATEGORY!='' AND STATUS='ACC' AND COURIERED!='Y' ORDER BY MOD_DATE DESC LIMIT $j,$PAGELEN";
	$res=mysql_query_decide($sql) or logError($sql);
	if(mysql_num_rows($res))
	{
		displayresults($res,$j,"/jsadmin/accept_matches.php",$totalcount,'',"1",'',"cid=$cid&profileid=$profileid&crd_status=$crd_status",'','','','','',"admin",$profileid,$cid);
	}
	else
		$smarty->assign("NOREC",1);
	$smarty->assign("cid",$cid);
	if($crd_status)
		$smarty->assign("crd_status",$crd_status);
	$smarty->assign("profileid",$profileid);
	$smarty->display("accept_matches.htm");   
}
else
{
        $msg="Your session has been timed out<br><br>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
	
