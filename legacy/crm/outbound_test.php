<?php
                                                                                                 
/**
*       Filename        :       outbound1.php.php
*       Created by      :       Abhinav
**/
/**
*       Included        :       connect.inc
*       Description     :       contains functions related to database connection and login authentication
**/
include ("connect.inc");
include ("display_result.inc");
$PAGELEN=25;
$LINKNO=10;
$START=1;
if (!$j )
        $j = 0;

$sno=$j+1;

if (authenticated($cid))
{
        $name= getname($cid);
	$today=date("Y-m-d")." 23:59:59";

	$orderby="CONTACTS_ACC";

	if($flag=='O')
	{
		$sql =" SELECT COUNT(*) FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO='$name' AND STATUS NOT IN ('F','C') AND ORDERS='Y' ";
	        $result=mysql_query_decide($sql,$db) or die(mysql_error_js());
	        $myrow = mysql_fetch_row($result);
        	$TOTALREC = $myrow[0];

		//$sql =" SELECT incentive.MAIN_ADMIN.PROFILEID,newjs.JPROFILE.USERNAME,incentive.MAIN_ADMIN.TIMES_TRIED,newjs.JPROFILE.NTIMES,incentive.MAIN_ADMIN.CONTACTS_ACC,incentive.MAIN_ADMIN.CONTACTS_RCV,incentive.MAIN_ADMIN.RES_NO,incentive.MAIN_ADMIN.MOB_NO,newjs.JPROFILE.CITY_RES,newjs.JPROFILE.ENTRY_DT,newjs.JPROFILE.LAST_LOGIN_DT,incentive.MAIN_ADMIN.STATUS FROM incentive.MAIN_ADMIN,newjs.JPROFILE WHERE incentive.MAIN_ADMIN.PROFILEID=newjs.JPROFILE.PROFILEID AND ALLOTED_TO='$name'  AND ORDERS='Y' AND STATUS='' ORDER BY TIMES_TRIED DESC LIMIT $j,$PAGELEN";
		$sql =" SELECT newjs.JPROFILE.AGE,newjs.JPROFILE.GENDER,incentive.MAIN_ADMIN.PROFILEID,newjs.JPROFILE.USERNAME,incentive.MAIN_ADMIN.TIMES_TRIED,newjs.JPROFILE.NTIMES,incentive.MAIN_ADMIN.CONTACTS_ACC,incentive.MAIN_ADMIN.CONTACTS_RCV,incentive.MAIN_ADMIN.RES_NO,incentive.MAIN_ADMIN.MOB_NO,newjs.JPROFILE.CITY_RES,newjs.JPROFILE.ENTRY_DT,newjs.JPROFILE.LAST_LOGIN_DT,incentive.MAIN_ADMIN.STATUS FROM incentive.MAIN_ADMIN LEFT JOIN newjs.JPROFILE ON incentive.MAIN_ADMIN.PROFILEID=newjs.JPROFILE.PROFILEID WHERE ALLOTED_TO='$name'  AND ORDERS='Y' AND STATUS NOT IN ('F','C') ORDER BY TIMES_TRIED DESC LIMIT $j,$PAGELEN";
		$result= mysql_query_decide($sql) or die(mysql_error_js());
		while($myrow=mysql_fetch_array($result))
		{
			$city=label_select("BRANCH_CITY",$myrow['CITY_RES'],"incentive");
			if($myrow['RES_NO'])
				$ph_res=$myrow['RES_NO'];
			else 
				$ph_res="-";
			if($myrow['MOB_NO'])
				$ph_mob=$myrow['MOB_NO'];
			else 
				$ph_mob="-";
			
			$ordersusersarr[] = array("SNO"=> $sno,
						"USERNAME" => $myrow['USERNAME'],
						"PROFILEID" => $myrow['PROFILEID'],
						"NTIMES" => $myrow['NTIMES'],
						"CONTACTS_ACC" => $myrow['CONTACTS_ACC'],
						"CONTACTS_RCV" => $myrow['CONTACTS_RCV'],
						"RES_NO" => $ph_res,	
						"MOB_NO" => $ph_mob,
						"CITY_INDIA" => $city['LABEL'],
						"AGE" => $myrow['AGE'],
						"GENDER" => $myrow['GENDER'],
						"ENTRY_DT" => $myrow['ENTRY_DT'],
						"TIMES_TRIED" => $myrow['TIMES_TRIED'],
						"LAST_LOGIN_DT" => $myrow['LAST_LOGIN_DT']);
			$sno++;
		}
		$smarty->assign("ordersusersarr",$ordersusersarr);
	}
	elseif($flag=='OF')
	{
		$sql =" SELECT COUNT(*) FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO='$name'  AND STATUS='F' AND ORDERS='Y' AND FOLLOWUP_TIME<='$today'";
	        $result=mysql_query_decide($sql,$db) or die(mysql_error_js());
	        $myrow = mysql_fetch_row($result);
        	$TOTALREC = $myrow[0];

		//$sql =" SELECT incentive.MAIN_ADMIN.PROFILEID,newjs.JPROFILE.USERNAME,incentive.MAIN_ADMIN.TIMES_TRIED,newjs.JPROFILE.NTIMES,incentive.MAIN_ADMIN.CONTACTS_ACC,incentive.MAIN_ADMIN.CONTACTS_RCV,incentive.MAIN_ADMIN.RES_NO,incentive.MAIN_ADMIN.MOB_NO,newjs.JPROFILE.CITY_RES,newjs.JPROFILE.ENTRY_DT,newjs.JPROFILE.LAST_LOGIN_DT,incentive.MAIN_ADMIN.STATUS FROM incentive.MAIN_ADMIN,newjs.JPROFILE WHERE incentive.MAIN_ADMIN.PROFILEID=newjs.JPROFILE.PROFILEID AND ALLOTED_TO='$name' AND ORDERS='Y' AND STATUS='F' AND FOLLOWUP_TIME<='$today' ORDER BY TIMES_TRIED DESC LIMIT $j,$PAGELEN";
		$sql =" SELECT newjs.JPROFILE.AGE,newjs.JPROFILE.GENDER,incentive.MAIN_ADMIN.PROFILEID,newjs.JPROFILE.USERNAME,incentive.MAIN_ADMIN.TIMES_TRIED,newjs.JPROFILE.NTIMES,incentive.MAIN_ADMIN.CONTACTS_ACC,incentive.MAIN_ADMIN.CONTACTS_RCV,incentive.MAIN_ADMIN.RES_NO,incentive.MAIN_ADMIN.MOB_NO,newjs.JPROFILE.CITY_RES,newjs.JPROFILE.ENTRY_DT,newjs.JPROFILE.LAST_LOGIN_DT,incentive.MAIN_ADMIN.STATUS FROM incentive.MAIN_ADMIN LEFT JOIN newjs.JPROFILE ON incentive.MAIN_ADMIN.PROFILEID=newjs.JPROFILE.PROFILEID WHERE ALLOTED_TO='$name' AND ORDERS='Y' AND STATUS='F' AND FOLLOWUP_TIME<='$today' ORDER BY TIMES_TRIED DESC LIMIT $j,$PAGELEN";
		$result= mysql_query_decide($sql) or die(mysql_error_js());
		while($myrow=mysql_fetch_array($result))
		{
			$city=label_select("BRANCH_CITY",$myrow['CITY_RES'],"incentive");
			if($myrow['RES_NO'])
				$ph_res=$myrow['RES_NO'];
			else 
				$ph_res="-";
			if($myrow['MOB_NO'])
				$ph_mob=$myrow['MOB_NO'];
			else 
				$ph_mob="-";
			
			$sql1 =" SELECT FOLLOWUP_TIME FROM incentive.MAIN_ADMIN WHERE PROFILEID=$myrow[PROFILEID]  AND ORDERS='Y'";
			$result1= mysql_query_decide($sql1) or die(mysql_error_js());
			$myrow1=mysql_fetch_array($result1);
//			$today=date('Y-m-d');
			$follow=strftime("%Y-%m-%d",JSstrToTime($myrow1['FOLLOWUP_TIME']));
//			if($follow==$today)
//			{
				$followordersusersarr[] = array("SNO"=> $sno,
						"USERNAME" => $myrow['USERNAME'],
						"PROFILEID" => $myrow['PROFILEID'],
						"NTIMES" => $myrow['NTIMES'],
						"CONTACTS_ACC" => $myrow['CONTACTS_ACC'],
						"CONTACTS_RCV" => $myrow['CONTACTS_RCV'],
						"RES_NO" => $ph_res,	
						"MOB_NO" => $ph_mob,
						"CITY_INDIA" => $city['LABEL'],
                                                "AGE" => $myrow['AGE'],
                                                "GENDER" => $myrow['GENDER'],
						"ENTRY_DT" => $myrow['ENTRY_DT'],
						"TIMES_TRIED" => $myrow['TIMES_TRIED'],
						"LAST_LOGIN_DT" => $myrow['LAST_LOGIN_DT']);
//			}
			$sno++;
		}
		$smarty->assign("followordersusersarr",$followordersusersarr);
	}
	elseif($flag=='NF')
	{
		$sql =" SELECT COUNT(*) FROM incentive.MAIN_ADMIN LEFT JOIN newjs.JPROFILE ON incentive.MAIN_ADMIN.PROFILEID=newjs.JPROFILE.PROFILEID WHERE ALLOTED_TO='$name'  AND STATUS='F' AND ORDERS='' AND FOLLOWUP_TIME<='$today' AND AGE>=25 AND SUBSCRIPTION=''";
	        $result=mysql_query_decide($sql,$db) or die(mysql_error_js());
	        $myrow = mysql_fetch_row($result);
        	$TOTALREC = $myrow[0];

		//$sql =" SELECT incentive.MAIN_ADMIN.PROFILEID,newjs.JPROFILE.USERNAME,newjs.JPROFILE.NTIMES,incentive.MAIN_ADMIN.CONTACTS_ACC,incentive.MAIN_ADMIN.CONTACTS_RCV,incentive.MAIN_ADMIN.RES_NO,incentive.MAIN_ADMIN.MOB_NO,newjs.JPROFILE.CITY_RES,newjs.JPROFILE.ENTRY_DT,newjs.JPROFILE.LAST_LOGIN_DT,incentive.MAIN_ADMIN.STATUS FROM incentive.MAIN_ADMIN,newjs.JPROFILE WHERE incentive.MAIN_ADMIN.PROFILEID=newjs.JPROFILE.PROFILEID AND ALLOTED_TO='$name' AND incentive.MAIN_ADMIN.STATUS='F' AND ORDERS='' AND FOLLOWUP_TIME<='$today' ORDER BY $orderby DESC LIMIT $j,$PAGELEN";
		$sql =" SELECT newjs.JPROFILE.AGE,newjs.JPROFILE.GENDER,incentive.MAIN_ADMIN.PROFILEID,newjs.JPROFILE.USERNAME,newjs.JPROFILE.NTIMES,incentive.MAIN_ADMIN.CONTACTS_ACC,incentive.MAIN_ADMIN.CONTACTS_RCV,incentive.MAIN_ADMIN.RES_NO,incentive.MAIN_ADMIN.MOB_NO,newjs.JPROFILE.CITY_RES,newjs.JPROFILE.ENTRY_DT,newjs.JPROFILE.LAST_LOGIN_DT,incentive.MAIN_ADMIN.STATUS FROM incentive.MAIN_ADMIN LEFT JOIN newjs.JPROFILE ON incentive.MAIN_ADMIN.PROFILEID=newjs.JPROFILE.PROFILEID WHERE ALLOTED_TO='$name' AND incentive.MAIN_ADMIN.STATUS='F' AND ORDERS='' AND FOLLOWUP_TIME<='$today' AND AGE>=25 AND SUBSCRIPTION='' ORDER BY $orderby DESC LIMIT $j,$PAGELEN";
		$result= mysql_query_decide($sql) or die(mysql_error_js());
		while($myrow=mysql_fetch_array($result))
		{
			$city=label_select("BRANCH_CITY",$myrow['CITY_RES'],"incentive");
			if($myrow['RES_NO'])
				$ph_res=$myrow['RES_NO'];
			else 
				$ph_res="-";
			if($myrow['MOB_NO'])
				$ph_mob=$myrow['MOB_NO'];
			else 
				$ph_mob="-";
			
			$sql1 =" SELECT FOLLOWUP_TIME FROM incentive.MAIN_ADMIN WHERE PROFILEID=$myrow[PROFILEID] AND ORDERS='' ";
			$result1= mysql_query_decide($sql1) or die(mysql_error_js());
			$myrow1=mysql_fetch_array($result1);
//			$today=date('Y-m-d');
			$follow=strftime("%Y-%m-%d",JSstrToTime($myrow1['FOLLOWUP_TIME']));
//			if($follow==$today)
//			{
				$followusersarr[] = array("SNO"=> $sno,
						"USERNAME" => $myrow['USERNAME'],
						"PROFILEID" => $myrow['PROFILEID'],
						"NTIMES" => $myrow['NTIMES'],
						"CONTACTS_ACC" => $myrow['CONTACTS_ACC'],
						"CONTACTS_RCV" => $myrow['CONTACTS_RCV'],
						"RES_NO" => $ph_res,	
						"MOB_NO" => $ph_mob,
						"CITY_INDIA" => $city['LABEL'],
                                                "AGE" => $myrow['AGE'],
                                                "GENDER" => $myrow['GENDER'],
						"ENTRY_DT" => $myrow['ENTRY_DT'],
						"LAST_LOGIN_DT" => $myrow['LAST_LOGIN_DT']);
//			}
			$sno++;
		}
		$smarty->assign("followusersarr",$followusersarr);
	}
	elseif($flag=='N')
	{
		if($getold)
		{
			$sql =" SELECT COUNT(*) FROM incentive.MAIN_ADMIN LEFT JOIN newjs.JPROFILE ON incentive.MAIN_ADMIN.PROFILEID=newjs.JPROFILE.PROFILEID WHERE ALLOTED_TO='$name'  AND STATUS='' AND ORDERS='' AND AGE>=25 AND SUBSCRIPTION=''";
			$result=mysql_query_decide($sql,$db) or die(mysql_error_js());
			$myrow = mysql_fetch_row($result);
			$TOTALREC = $myrow[0];

			$sql =" SELECT newjs.JPROFILE.AGE,newjs.JPROFILE.GENDER,incentive.MAIN_ADMIN.PROFILEID,newjs.JPROFILE.USERNAME,newjs.JPROFILE.NTIMES,incentive.MAIN_ADMIN.CONTACTS_ACC,incentive.MAIN_ADMIN.CONTACTS_RCV,incentive.MAIN_ADMIN.RES_NO,incentive.MAIN_ADMIN.MOB_NO,newjs.JPROFILE.CITY_RES,newjs.JPROFILE.ENTRY_DT,newjs.JPROFILE.LAST_LOGIN_DT,incentive.MAIN_ADMIN.STATUS FROM incentive.MAIN_ADMIN LEFT JOIN newjs.JPROFILE ON incentive.MAIN_ADMIN.PROFILEID=newjs.JPROFILE.PROFILEID WHERE ALLOTED_TO='$name'  AND incentive.MAIN_ADMIN.STATUS='' AND ORDERS='' AND AGE>=25 AND SUBSCRIPTION='' ORDER BY LAST_LOGIN_DT DESC LIMIT $j,$PAGELEN ";
		}
		else
		{
			$sql =" SELECT COUNT(*) FROM incentive.MAIN_ADMIN LEFT JOIN newjs.JPROFILE ON incentive.MAIN_ADMIN.PROFILEID=newjs.JPROFILE.PROFILEID WHERE ALLOTED_TO='$name'  AND STATUS='N' AND ORDERS='' AND FOLLOWUP_TIME<='$today' AND FOLLOWUP_TIME<>0 AND AGE>=25 AND SUBSCRIPTION=''";
			$result=mysql_query_decide($sql,$db) or die(mysql_error_js());
			$myrow = mysql_fetch_row($result);
			$TOTALREC = $myrow[0];

			$sql =" SELECT newjs.JPROFILE.AGE,newjs.JPROFILE.GENDER,incentive.MAIN_ADMIN.PROFILEID,newjs.JPROFILE.USERNAME,newjs.JPROFILE.NTIMES,incentive.MAIN_ADMIN.CONTACTS_ACC,incentive.MAIN_ADMIN.CONTACTS_RCV,incentive.MAIN_ADMIN.RES_NO,incentive.MAIN_ADMIN.MOB_NO,newjs.JPROFILE.CITY_RES,newjs.JPROFILE.ENTRY_DT,newjs.JPROFILE.LAST_LOGIN_DT,incentive.MAIN_ADMIN.STATUS FROM incentive.MAIN_ADMIN LEFT JOIN newjs.JPROFILE ON incentive.MAIN_ADMIN.PROFILEID=newjs.JPROFILE.PROFILEID WHERE ALLOTED_TO='$name'  AND incentive.MAIN_ADMIN.STATUS='N' AND ORDERS='' AND FOLLOWUP_TIME<='$today' AND FOLLOWUP_TIME<>0 AND AGE>=25 AND SUBSCRIPTION='' ORDER BY FOLLOWUP_TIME DESC,CONTACTS_ACC DESC LIMIT $j,$PAGELEN ";
		}
	        $result= mysql_query_decide($sql) or die(mysql_error_js());
        	while($myrow=mysql_fetch_array($result))
	        {
			$city=label_select("BRANCH_CITY",$myrow['CITY_RES'],"incentive");
			if($myrow['RES_NO'])
				$ph_res=$myrow['RES_NO'];
			else
				$ph_res="-";
			if($myrow['MOB_NO'])
				$ph_mob=$myrow['MOB_NO'];
			else
				$ph_mob="-";
			$newusersarr[] = array("SNO"=> $sno,
					"USERNAME" => $myrow['USERNAME'],
					"PROFILEID" => $myrow['PROFILEID'],
					"NTIMES" => $myrow['NTIMES'],
					"CONTACTS_ACC" => $myrow['CONTACTS_ACC'],
					"CONTACTS_RCV" => $myrow['CONTACTS_RCV'],
					"RES_NO" => $ph_res,	
					"MOB_NO" => $ph_mob,
					"CITY_INDIA" => $city['LABEL'],
					"AGE" => $myrow['AGE'],
					"GENDER" => $myrow['GENDER'],
					"ENTRY_DT" => $myrow['ENTRY_DT'],
					"LAST_LOGIN_DT" => $myrow['LAST_LOGIN_DT']);
			$sno++;
	        }
		$smarty->assign("newusersarr",$newusersarr);
	}
	elseif($flag=='S')
	{

		$sql_id = "SELECT MAX( ID ) AS ID, PROFILEID FROM billing.SERVICE_STATUS GROUP BY PROFILEID";
		$result_id=mysql_query_decide($sql_id) or  $msg .= "\n$sql_id \nError :".mysql_error_js();
		while($myrow_id=mysql_fetch_array($result_id))
		{
			$arr_id[]=$myrow_id['ID'];
		}
		$list_id=implode("','",$arr_id);


		$sql =" SELECT COUNT(distinct incentive.MAIN_ADMIN.PROFILEID) FROM incentive.MAIN_ADMIN LEFT JOIN billing.SERVICE_STATUS ON incentive.MAIN_ADMIN.PROFILEID = billing.SERVICE_STATUS.PROFILEID LEFT JOIN newjs.JPROFILE ON incentive.MAIN_ADMIN.PROFILEID = newjs.JPROFILE.PROFILEID WHERE ALLOTED_TO = '$name' AND EXPIRY_DT >= '$today' AND EXPIRY_DT <= DATE_ADD( '$today', INTERVAL 15 DAY ) ORDER BY EXPIRY_DT and billing.SERVICE_STATUS.ID in ('$list_id')";
		$result=mysql_query_decide($sql,$db) or die(mysql_error_js());
		$myrow = mysql_fetch_row($result);
		$TOTALREC = $myrow[0];

		$sql ="SELECT DISTINCT incentive.MAIN_ADMIN.PROFILEID, newjs.JPROFILE.AGE, newjs.JPROFILE.GENDER, newjs.JPROFILE.USERNAME, newjs.JPROFILE.NTIMES, incentive.MAIN_ADMIN.CONTACTS_ACC, incentive.MAIN_ADMIN.CONTACTS_RCV, incentive.MAIN_ADMIN.RES_NO, incentive.MAIN_ADMIN.MOB_NO, newjs.JPROFILE.CITY_RES, newjs.JPROFILE.ENTRY_DT, newjs.JPROFILE.LAST_LOGIN_DT, incentive.MAIN_ADMIN.STATUS, billing.SERVICE_STATUS.EXPIRY_DT FROM incentive.MAIN_ADMIN LEFT JOIN billing.SERVICE_STATUS ON incentive.MAIN_ADMIN.PROFILEID = billing.SERVICE_STATUS.PROFILEID LEFT JOIN newjs.JPROFILE ON incentive.MAIN_ADMIN.PROFILEID = newjs.JPROFILE.PROFILEID WHERE ALLOTED_TO = '$name' AND EXPIRY_DT >= '$today' AND EXPIRY_DT <= DATE_ADD( '$today', INTERVAL 15 DAY ) and billing.SERVICE_STATUS.ID in ('$list_id') ORDER BY EXPIRY_DT LIMIT $j,$PAGELEN ";
	        $result= mysql_query_decide($sql) or die(mysql_error_js());
        	while($myrow=mysql_fetch_array($result))
	        {
			$city=label_select("BRANCH_CITY",$myrow['CITY_RES'],"incentive");
			if($myrow['RES_NO'])
				$ph_res=$myrow['RES_NO'];
			else
				$ph_res="-";
			if($myrow['MOB_NO'])
				$ph_mob=$myrow['MOB_NO'];
			else
				$ph_mob="-";
			$newusersarr[] = array("SNO"=> $sno,
					"USERNAME" => $myrow['USERNAME'],
					"PROFILEID" => $myrow['PROFILEID'],
					"NTIMES" => $myrow['NTIMES'],
					"CONTACTS_ACC" => $myrow['CONTACTS_ACC'],
					"CONTACTS_RCV" => $myrow['CONTACTS_RCV'],
					"RES_NO" => $ph_res,	
					"MOB_NO" => $ph_mob,
					"CITY_INDIA" => $city['LABEL'],
					"AGE" => $myrow['AGE'],
					"GENDER" => $myrow['GENDER'],
					"ENTRY_DT" => $myrow['ENTRY_DT'],
					"LAST_LOGIN_DT" => $myrow['LAST_LOGIN_DT'],
					"EXPIRY_DT" => $myrow['EXPIRY_DT']);
			$sno++;
	        }
		$smarty->assign("newusersarr",$newusersarr);
	}
        if( $j )
                $cPage = ($j/$PAGELEN) + 1;
        else
                $cPage = 1;

	pagelink($PAGELEN,$TOTALREC,$cPage,$LINKNO,$cid,"outbound1.php",'',$flag,$getold);
        $smarty->assign("COUNT",$TOTALREC);
        $smarty->assign("CURRENTPAGE",$cPage);
        $no_of_pages=ceil($TOTALREC/$PAGELEN);
        $smarty->assign("NO_OF_PAGES",$no_of_pages);

	$smarty->assign("flag",$flag);
	$smarty->assign("sort_order",$sort_order);
        $smarty->assign("name",$name);
        $smarty->assign("cid",$cid);
        $smarty->display("outbound1.htm");
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
