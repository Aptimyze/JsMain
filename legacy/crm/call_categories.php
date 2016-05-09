<?php
/***************************************************************************************************************************
Filename    : call_categories.php
Description : Display the his/her detailed data to the sales executive of all types of call for his/her work process.
Created By  : Vibhor Garg
Created On  : 15 May 2008
****************************************************************************************************************************/
include("connect.inc");
include("display_result.inc");
$PAGELEN=25;
$LINKNO=10;
$START=1;
if (!$j )
        $j = 0;

$sno=$j+1;

if(authenticated($cid))
{
        $name= getname($cid);
	$today=date("Y-m-d");
	
	if($cat=='P')
	{
		$sql =" SELECT COUNT(*) FROM incentive.SERVICE_ADMIN WHERE REALLOTED_TO='$name' AND HANDLED_DT ='$today' ";
	        $result=mysql_query_decide($sql,$db) or die(mysql_error_js());
	        $myrow = mysql_fetch_row($result);
        	$TOTALREC = $myrow[0];

		if($TOTALREC != 0)
		{
			$sql =" SELECT newjs.JPROFILE.EMAIL,newjs.JPROFILE.GENDER,incentive.SERVICE_ADMIN.ALTERNATE_NO,incentive.SERVICE_ADMIN.PROFILEID,newjs.JPROFILE.USERNAME,newjs.JPROFILE.PHONE_RES,newjs.JPROFILE.PHONE_MOB,newjs.JPROFILE.CITY_RES,incentive.SERVICE_ADMIN.CALL_STATUS FROM incentive.SERVICE_ADMIN LEFT JOIN newjs.JPROFILE ON incentive.SERVICE_ADMIN.PROFILEID=newjs.JPROFILE.PROFILEID WHERE REALLOTED_TO='$name' AND HANDLED_DT ='$today' ORDER BY REALLOT_TIME LIMIT $j,$PAGELEN";
			$result= mysql_query_decide($sql) or die(mysql_error_js());
			while($myrow=mysql_fetch_array($result))
			{
				$city=label_select("BRANCH_CITY",$myrow['CITY_RES'],"incentive");
				if($myrow['PHONE_RES'])
					$ph_res=$myrow['PHONE_RES'];
				else 
					$ph_res="-";
				if($myrow['ALTERNATE_NO'])
				{
					$ph_res.=",".$myrow['ALTERNATE_NO'];
				}
				if($myrow['PHONE_MOB'])
					$ph_mob=$myrow['PHONE_MOB'];
				else 
					$ph_mob="-";
				$temp_email=explode("@",$myrow["EMAIL"]);
				$email=$temp_email[0]."@xxx.com";
			
				$profileid=$myrow["PROFILEID"];
				
				$sql = "SELECT COUNT(*) AS CNT FROM billing.SERVICE_STATUS WHERE PROFILEID = '$profileid' AND ACTIVE='Y' AND SERVEFOR LIKE '%F%'";
                                $res1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                                $row1=mysql_fetch_array($res1);
                                $count=$row1['CNT'];
					
				$ordersusersarr[] = array("SNO"=> $sno,
							"NAME" => addslashes(stripslashes(get_name_of_user($myrow['PROFILEID']))),
							"USERNAME" => addslashes(stripslashes($myrow['USERNAME'])),
							"EMAIL" => $email,
							"PROFILEID" => $myrow['PROFILEID'],
							"RES_NO" => $ph_res,	
							"MOB_NO" => $ph_mob,
							"CITY_INDIA" => $city['LABEL'],
							"GENDER" => $myrow['GENDER'],
							"PAYMENTS_MADE" => $count);
				$sno++;
			}
			$smarty->assign("ordersusersarr",$ordersusersarr);
			unset($ordersusersarr);
		}
	}
	if($cat=='C')
        {
                $sql =" SELECT COUNT(*) FROM incentive.SERVICE_ADMIN WHERE REALLOTED_TO='$name' AND FEEDBACK_DT='$today'";
                $result=mysql_query_decide($sql,$db) or die(mysql_error_js());
                $myrow = mysql_fetch_row($result);
                $TOTALREC = $myrow[0];

                if($TOTALREC != 0)
                {
                        $sql =" SELECT newjs.JPROFILE.EMAIL,newjs.JPROFILE.GENDER,incentive.SERVICE_ADMIN.ALTERNATE_NO,incentive.SERVICE_ADMIN.PROFILEID,newjs.JPROFILE.USERNAME,incentive.SERVICE_ADMIN.HANDLED_COMMENTS,newjs.JPROFILE.PHONE_RES,newjs.JPROFILE.PHONE_MOB,newjs.JPROFILE.CITY_RES,incentive.SERVICE_ADMIN.CALL_STATUS FROM incentive.SERVICE_ADMIN LEFT JOIN newjs.JPROFILE ON incentive.SERVICE_ADMIN.PROFILEID=newjs.JPROFILE.PROFILEID WHERE REALLOTED_TO='$name' AND FEEDBACK_DT='$today' LIMIT $j,$PAGELEN";
                        $result= mysql_query_decide($sql) or die(mysql_error_js());
                        while($myrow=mysql_fetch_array($result))
                        {
                                $city=label_select("BRANCH_CITY",$myrow['CITY_RES'],"incentive");
                                if($myrow['PHONE_RES'])
                                        $ph_res=$myrow['PHONE_RES'];
                                else
                                        $ph_res="-";
                                if($myrow['ALTERNATE_NO'])
                                {
                                        $ph_res.=",".$myrow['ALTERNATE_NO'];
                                }
                                if($myrow['PHONE_MOB'])
                                        $ph_mob=$myrow['PHONE_MOB'];
                                else
                                        $ph_mob="-";
                                $temp_email=explode("@",$myrow["EMAIL"]);
                                $email=$temp_email[0]."@xxx.com";

                                $profileid=$myrow["PROFILEID"];

                                $ordersusersarr[] = array("SNO"=> $sno,
                                                        "NAME" => addslashes(stripslashes(get_name_of_user($myrow['PROFILEID']))),
                                                        "USERNAME" => addslashes(stripslashes($myrow['USERNAME'])),
                                                        "EMAIL" => $email,
                                                        "PROFILEID" => $myrow['PROFILEID'],
                                                        "RES_NO" => $ph_res,
                                                        "MOB_NO" => $ph_mob,
                                                        "CITY_INDIA" => $city['LABEL'],
                                                        "GENDER" => $myrow['GENDER'],
                                                        "HANDLED_COMMENTS" => $myrow['HANDLED_COMMENTS']);
                                $sno++;
                        }
                        $smarty->assign("ordersusersarr",$ordersusersarr);
			unset($ordersusersarr);
                }
        }

	if($cat=='E')
        {
                $sql =" SELECT COUNT(*) FROM incentive.SERVICE_ADMIN WHERE REALLOTED_TO='$name' AND RECONVINCE_DT='$today'";
                $result=mysql_query_decide($sql,$db) or die(mysql_error_js());
                $myrow = mysql_fetch_row($result);
                $TOTALREC = $myrow[0];

                if($TOTALREC != 0)
                {
                        $sql =" SELECT newjs.JPROFILE.EMAIL,newjs.JPROFILE.GENDER,incentive.SERVICE_ADMIN.ALTERNATE_NO,incentive.SERVICE_ADMIN.PROFILEID,newjs.JPROFILE.USERNAME,incentive.SERVICE_ADMIN.HANDLED_COMMENTS,incentive.SERVICE_ADMIN.FEEDBACK_COMMENTS,newjs.JPROFILE.PHONE_RES,newjs.JPROFILE.PHONE_MOB,newjs.JPROFILE.CITY_RES,incentive.SERVICE_ADMIN.CALL_STATUS FROM incentive.SERVICE_ADMIN LEFT JOIN newjs.JPROFILE ON incentive.SERVICE_ADMIN.PROFILEID=newjs.JPROFILE.PROFILEID WHERE REALLOTED_TO='$name' AND RECONVINCE_DT='$today' LIMIT $j,$PAGELEN";
                        $result= mysql_query_decide($sql) or die(mysql_error_js());
                        while($myrow=mysql_fetch_array($result))
                        {
                                $city=label_select("BRANCH_CITY",$myrow['CITY_RES'],"incentive");
                                if($myrow['PHONE_RES'])
                                        $ph_res=$myrow['PHONE_RES'];
                                else
                                        $ph_res="-";
                                if($myrow['ALTERNATE_NO'])
                                {
                                        $ph_res.=",".$myrow['ALTERNATE_NO'];
                                }
                                if($myrow['PHONE_MOB'])
                                        $ph_mob=$myrow['PHONE_MOB'];
                                else
                                        $ph_mob="-";
                                $temp_email=explode("@",$myrow["EMAIL"]);
                                $email=$temp_email[0]."@xxx.com";
				$profileid=$myrow["PROFILEID"];

                                $ordersusersarr[] = array("SNO"=> $sno,
                                                        "NAME" => addslashes(stripslashes(get_name_of_user($myrow['PROFILEID']))),
                                                        "USERNAME" => addslashes(stripslashes($myrow['USERNAME'])),
                                                        "EMAIL" => $email,
                                                        "PROFILEID" => $myrow['PROFILEID'],
                                                        "RES_NO" => $ph_res,
                                                        "MOB_NO" => $ph_mob,
                                                        "CITY_INDIA" => $city['LABEL'],
                                                        "GENDER" => $myrow['GENDER'],
                                                        "HANDLED_COMMENTS" => $myrow['HANDLED_COMMENTS'],
							"FEEDBACK_COMMENTS" => $myrow['FEEDBACK_COMMENTS']);
                                $sno++;
                        }
                        $smarty->assign("ordersusersarr",$ordersusersarr);
                        unset($ordersusersarr);
                }
        }
       
	if($cat=='F')
        {
                $sql =" SELECT COUNT(*) FROM incentive.SERVICE_ADMIN WHERE REALLOTED_TO='$name' AND FOLLOWUP_DT='$today'";
                $result=mysql_query_decide($sql,$db) or die(mysql_error_js());
                $myrow = mysql_fetch_row($result);
                $TOTALREC = $myrow[0];

                if($TOTALREC != 0)
                {
                        $sql =" SELECT newjs.JPROFILE.EMAIL,newjs.JPROFILE.GENDER,incentive.SERVICE_ADMIN.ALTERNATE_NO,incentive.SERVICE_ADMIN.PROFILEID,newjs.JPROFILE.USERNAME,incentive.SERVICE_ADMIN.HANDLED_COMMENTS,incentive.SERVICE_ADMIN.FEEDBACK_COMMENTS,incentive.SERVICE_ADMIN.CALL_STATUS,newjs.JPROFILE.PHONE_RES,newjs.JPROFILE.PHONE_MOB,newjs.JPROFILE.CITY_RES,incentive.SERVICE_ADMIN.CALL_STATUS FROM incentive.SERVICE_ADMIN LEFT JOIN newjs.JPROFILE ON incentive.SERVICE_ADMIN.PROFILEID=newjs.JPROFILE.PROFILEID WHERE REALLOTED_TO='$name' AND FOLLOWUP_DT='$today' ORDER BY CALL_STATUS DESC LIMIT $j,$PAGELEN";
                        $result= mysql_query_decide($sql) or die(mysql_error_js());
                        while($myrow=mysql_fetch_array($result))
                        {
                                $city=label_select("BRANCH_CITY",$myrow['CITY_RES'],"incentive");
                                if($myrow['PHONE_RES'])
                                        $ph_res=$myrow['PHONE_RES'];
                                else
                                        $ph_res="-";
                                if($myrow['ALTERNATE_NO'])
                                {
                                        $ph_res.=",".$myrow['ALTERNATE_NO'];
                                }
                                if($myrow['PHONE_MOB'])
                                        $ph_mob=$myrow['PHONE_MOB'];
                                else
                                        $ph_mob="-";
                                $temp_email=explode("@",$myrow["EMAIL"]);
                                $email=$temp_email[0]."@xxx.com";
                                $profileid=$myrow["PROFILEID"];
				$ordersusersarr[] = array("SNO"=> $sno,
                                                        "NAME" => addslashes(stripslashes(get_name_of_user($myrow['PROFILEID']))),
                                                        "USERNAME" => addslashes(stripslashes($myrow['USERNAME'])),
                                                        "EMAIL" => $email,
                                                        "PROFILEID" => $myrow['PROFILEID'],
                                                        "RES_NO" => $ph_res,
                                                        "MOB_NO" => $ph_mob,
                                                        "CITY_INDIA" => $city['LABEL'],
                                                        "GENDER" => $myrow['GENDER'],
                                                        "HANDLED_COMMENTS" => $myrow['HANDLED_COMMENTS'],
                                                        "FEEDBACK_COMMENTS" => $myrow['FEEDBACK_COMMENTS'],
							"CALL_STATUS" => $myrow['CALL_STATUS']);
                                $sno++;
                        }
                        $smarty->assign("ordersusersarr",$ordersusersarr);
                        unset($ordersusersarr);
                }
        }
	if($cat=='H')
        {
                $sql ="SELECT COUNT(*) FROM incentive.SERVICE_ADMIN WHERE REALLOTED_TO='$name' AND CALL_STATUS!=0";
                $result=mysql_query_decide($sql,$db) or die(mysql_error_js());
                $myrow = mysql_fetch_row($result);
                $TOTALREC = $myrow[0];

                if($TOTALREC != 0)
                {
                        $sql =" SELECT newjs.JPROFILE.EMAIL,newjs.JPROFILE.GENDER,incentive.SERVICE_ADMIN.ALTERNATE_NO,incentive.SERVICE_ADMIN.PROFILEID,newjs.JPROFILE.USERNAME,incentive.SERVICE_ADMIN.REALLOT_TIME,incentive.SERVICE_ADMIN.HANDLED_COMMENTS,incentive.SERVICE_ADMIN.FEEDBACK_COMMENTS,incentive.SERVICE_ADMIN.CALL_STATUS,newjs.JPROFILE.PHONE_RES,newjs.JPROFILE.PHONE_MOB,newjs.JPROFILE.CITY_RES,incentive.SERVICE_ADMIN.CALL_STATUS FROM incentive.SERVICE_ADMIN LEFT JOIN newjs.JPROFILE ON incentive.SERVICE_ADMIN.PROFILEID=newjs.JPROFILE.PROFILEID WHERE REALLOTED_TO='$name' AND CALL_STATUS!=0 ORDER BY CALL_STATUS DESC LIMIT $j,$PAGELEN";
                        $result= mysql_query_decide($sql) or die(mysql_error_js());
                        while($myrow=mysql_fetch_array($result))
                        {
                                $city=label_select("BRANCH_CITY",$myrow['CITY_RES'],"incentive");
                                if($myrow['PHONE_RES'])
                                        $ph_res=$myrow['PHONE_RES'];
                                else
                                        $ph_res="-";
                                if($myrow['ALTERNATE_NO'])
                                {
                                        $ph_res.=",".$myrow['ALTERNATE_NO'];
                                }
                                if($myrow['PHONE_MOB'])
                                        $ph_mob=$myrow['PHONE_MOB'];
                                else
                                        $ph_mob="-";
                                $temp_email=explode("@",$myrow["EMAIL"]);
                                $email=$temp_email[0]."@xxx.com";
                                $profileid=$myrow["PROFILEID"];
                                $ordersusersarr[] = array("SNO"=> $sno,
							"NAME" => addslashes(stripslashes(get_name_of_user($myrow['PROFILEID']))),
                                                        "USERNAME" => addslashes(stripslashes($myrow['USERNAME'])),
                                                        "EMAIL" => $email,
                                                        "PROFILEID" => $myrow['PROFILEID'],
                                                        "RES_NO" => $ph_res,
                                                        "MOB_NO" => $ph_mob,
                                                        "CITY_INDIA" => $city['LABEL'],
                                                        "GENDER" => $myrow['GENDER'],
                                                        "HANDLED_COMMENTS" => $myrow['HANDLED_COMMENTS'],
                                                        "FEEDBACK_COMMENTS" => $myrow['FEEDBACK_COMMENTS'],
                                                        "CALL_STATUS" => $myrow['CALL_STATUS']);
                                $sno++;
                        }
                        $smarty->assign("ordersusersarr",$ordersusersarr);
                        unset($ordersusersarr);
                }
        }
	if($cat=='N')
        {
                $sql =" SELECT COUNT(*) FROM incentive.SERVICE_ADMIN WHERE REALLOTED_TO='$name' AND ON_TIME='N'";
                $result=mysql_query_decide($sql,$db) or die(mysql_error_js());
                $myrow = mysql_fetch_row($result);
                $TOTALREC = $myrow[0];

                if($TOTALREC != 0)
                {
                        $sql =" SELECT newjs.JPROFILE.EMAIL,newjs.JPROFILE.GENDER,incentive.SERVICE_ADMIN.ALTERNATE_NO,incentive.SERVICE_ADMIN.PROFILEID,newjs.JPROFILE.USERNAME,incentive.SERVICE_ADMIN.REALLOT_TIME,incentive.SERVICE_ADMIN.HANDLED_COMMENTS,incentive.SERVICE_ADMIN.FEEDBACK_COMMENTS,incentive.SERVICE_ADMIN.CALL_STATUS,newjs.JPROFILE.PHONE_RES,newjs.JPROFILE.PHONE_MOB,newjs.JPROFILE.CITY_RES,incentive.SERVICE_ADMIN.CALL_STATUS FROM incentive.SERVICE_ADMIN LEFT JOIN newjs.JPROFILE ON incentive.SERVICE_ADMIN.PROFILEID=newjs.JPROFILE.PROFILEID WHERE REALLOTED_TO='$name' AND ON_TIME='N' ORDER BY CALL_STATUS DESC LIMIT $j,$PAGELEN";
                        $result= mysql_query_decide($sql) or die(mysql_error_js());
                        while($myrow=mysql_fetch_array($result))
                        {
                                $city=label_select("BRANCH_CITY",$myrow['CITY_RES'],"incentive");
                                if($myrow['PHONE_RES'])
                                        $ph_res=$myrow['PHONE_RES'];
                                else
                                        $ph_res="-";
                                if($myrow['ALTERNATE_NO'])
                                {
                                        $ph_res.=",".$myrow['ALTERNATE_NO'];
                                }
                                if($myrow['PHONE_MOB'])
                                        $ph_mob=$myrow['PHONE_MOB'];
                                else
                                        $ph_mob="-";
                                $temp_email=explode("@",$myrow["EMAIL"]);
                                $email=$temp_email[0]."@xxx.com";
                                $profileid=$myrow["PROFILEID"];
                                $ordersusersarr[] = array("SNO"=> $sno,
							"NAME" => addslashes(stripslashes(get_name_of_user($myrow['PROFILEID']))),
                                                        "USERNAME" => addslashes(stripslashes($myrow['USERNAME'])),
                                                        "EMAIL" => $email,
                                                        "PROFILEID" => $myrow['PROFILEID'],
                                                        "RES_NO" => $ph_res,
                                                        "MOB_NO" => $ph_mob,
                                                        "CITY_INDIA" => $city['LABEL'],
                                                        "GENDER" => $myrow['GENDER'],
                                                        "HANDLED_COMMENTS" => $myrow['HANDLED_COMMENTS'],
                                                        "FEEDBACK_COMMENTS" => $myrow['FEEDBACK_COMMENTS'],
                                                        "CALL_STATUS" => $myrow['CALL_STATUS']);
                                $sno++;
                        }
                        $smarty->assign("ordersusersarr",$ordersusersarr);
                        unset($ordersusersarr);
                }
        }
 
	if( $j )
                $cPage = ($j/$PAGELEN) + 1;
        else
                $cPage = 1;

	$smarty->assign("defaultsort",$defaultsort);
	pagelink($PAGELEN,$TOTALREC,$cPage,$LINKNO,$cid,"call_process.php",'',$flag,$getold,"",$defaultsort);
        $smarty->assign("COUNT",$TOTALREC);
        $smarty->assign("CURRENTPAGE",$cPage);
        $no_of_pages=ceil($TOTALREC/$PAGELEN);
	if(!$no_of_pages)
		$no_of_pages=1;
        $smarty->assign("NO_OF_PAGES",$no_of_pages);

	$smarty->assign("cat",$cat);
	$smarty->assign("sort_order",$sort_order);
        $smarty->assign("name",$name);
        $smarty->assign("cid",$cid);
        $smarty->display("call_categories.htm");
}
else //user timed out
{
        $msg="Your session has been timed out  ";
        $msg .="<a href=\"index.php\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
