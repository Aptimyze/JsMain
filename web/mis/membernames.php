<?php
require_once('connect.inc');
include("../profile/display_result.inc");

$db=connect_misdb();
$db2=connect_master();

$PAGELEN=10;     // gives maximum limit of records that are shown on each page
$LINKNO=10;      // maximum no of links  shown on a particular page                                                                                                                           
/*if(!$j )
	$j = 0;
$sno=$j+1;

if($checksum)
	$data = $checksum;
elseif($cid)
	$data = $cid;
*/

if(authenticated($checksum))
{
	if($myear)
	{
		$reg_sql = "SELECT SQL_CALC_FOUND_ROWS  newjs.JPROFILE.SUBSCRIPTION as SUBSCRIPTION,newjs.JPROFILE.USERNAME AS MEMBERS FROM jsadmin.AFFILIATE_MAIN LEFT JOIN newjs.JPROFILE ON jsadmin.AFFILIATE_MAIN.EMAIL=newjs.JPROFILE.EMAIL WHERE JPROFILE.EMAIL IS NOT NULL ";

		if ($srcgrp == 1)
			$reg_sql.=" AND jsadmin.AFFILIATE_MAIN.MODE IN ('N','A')";
		elseif ($srcgrp == 2)
			$reg_sql.=" AND jsadmin.AFFILIATE_MAIN.MODE ='N' ";
		elseif ($srcgrp == 3)
			$reg_sql.=" AND jsadmin.AFFILIATE_MAIN.MODE ='A'";

		if($month<=9)
                        $month = "0".$month;
                                                                                                                             
                $reg_sql.= " AND jsadmin.AFFILIATE_MAIN.ENTRYTIME BETWEEN '$myear-$month-01' AND '$myear-$month-31'";
	}

	elseif($dyear)
	{
		if ($day <= 9)
                        $day = "0".$day;
                if ($dmonth<=9)
                        $dmonth = "0".$dmonth;

		$reg_sql = "SELECT SQL_CALC_FOUND_ROWS newjs.JPROFILE.USERNAME as MEMBERS, newjs.JPROFILE.SUBSCRIPTION as SUBSCRIPTION FROM jsadmin.AFFILIATE_MAIN LEFT JOIN newjs.JPROFILE ON jsadmin.AFFILIATE_MAIN.EMAIL=newjs.JPROFILE.EMAIL WHERE JPROFILE.EMAIL IS NOT NULL AND jsadmin.AFFILIATE_MAIN.ENTRYTIME = '$dyear-$dmonth-$day'";   

		if($srcgrp == 1)
			$reg_sql.=" AND MODE IN ('N','A')";
                                                                                                                           
       		elseif($srcgrp == 2)
                	$reg_sql.=" AND MODE IN ('N')";                                                                                                                             
        	elseif($srcgrp == 3)
			$reg_sql.=" AND MODE IN ('A')";                           
	}

	$reg_res = mysql_query_decide($reg_sql) or die("Due to a temporary problem your information cannot be processed.Please try after a few minutes".mysql_error_js());
	
	/****
	$csql 		= "Select FOUND_ROWS()";
	$cres 		= mysql_query_decide($csql) or die(mysql_error_js());
        $crow 		= mysql_fetch_row($cres);
        $TOTALREC 	= $crow[0];
	***/
	
	while ($reg_row = mysql_fetch_array($reg_res))
	{
		$sub	= $reg_row["SUBSCRIPTION"];

		if ($sub!='')
		{
			$paidmembers[] = $reg_row["MEMBERS"];
			$paidno++;
		}
		
		$regmembers[] = $reg_row["MEMBERS"];
		$regno++ ;
	}
	/****
	if ($j)
		$cPage = ($j/$PAGELEN) + 1;            //gives the number of records on each page
        else
		$cPage = 1;
                                                                                                                             
                                                                                                                             
	pagelink($PAGELEN,$TOTALREC,$cPage,$LINKNO,$data,"membernames.php",'','');
                                                                                                                             
	$no_of_pages=ceil($TOTALREC/$PAGELEN);
                                                                                                                             
	$smarty->assign("COUNT",$TOTALREC);             //calculates the total number of pages
	$smarty->assign("CURRENTPAGE",$cPage);
	$smarty->assign("NO_OF_PAGES",$no_of_pages);
	$smarty->assign("paidno",$paidno);
	$smarty->assign("regno",$regno);
	***/

	$smarty->assign("paid",$paid);
	$smarty->assign("cid",$cid);
	$smarty->assign("name",$name);
	
	$smarty->assign("regmembers",$regmembers);
	$smarty->assign("paidmembers",$paidmembers);
	$smarty->assign("checksum",$checksum);
	$smarty->assign("username",$username);
	$smarty->assign("name",$name);
	$smarty->assign("head",$smarty->fetch("head.htm"));
	$smarty->assign("foot",$smarty->fetch("foot.htm"));

	$smarty->display('membernames.htm');
}
else //user timed out
{
        $msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsconnectError.tpl");
}

?>
