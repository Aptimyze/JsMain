<?php
die("Please contact on lavesh.rawat@jeevansathi.com.");
include_once("connect.inc");
include("../profile/arrays.php");
$db=connect_misdb();
$db2=connect_master();

if(authenticated($cid))
{
	if($CMDGo)
	{
		$smarty->assign("flag",1);

		if($day)
		{
			$st_date=$year."-".$month."-".$day." 00:00:00";
			$end_date=$eyear."-".$emonth."-".$eday." 23:59:59";
		}
		else
		{
			$st_date=$year."-".$month."-01 00:00:00";
			$end_date=$eyear."-".$emonth."-31 23:59:59";
		}

		$total1 = 0;

		$sql="SELECT COUNT(*) as count FROM MIS.SEARCHQUERY2  WHERE DATE BETWEEN '$st_date' AND '$end_date' AND SEARCH_TYPE='J'";
                $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));                                                                                                                             
                while($row=mysql_fetch_array($res))
                {
                        $total1+=$row['count'];
                }

		$sql="SELECT COUNT(*) as count FROM MIS.SEARCHQUERY  WHERE DATE BETWEEN '$st_date' AND '$end_date' AND SEARCH_TYPE='J'";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		
		while($row=mysql_fetch_array($res))
		{
			$total2+=$row['count'];
		}

		$total = $total1 + $total2;

		$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE RELATION !='' AND DATE BETWEEN '$st_date' AND '$end_date' AND SEARCH_TYPE='J'";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		$row=mysql_fetch_array($res);

		$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE RELATION !='' AND EDU_LEVEL_NEW = '' AND OCCUPATION = '' AND INCOME ='' AND MANGLIK ='' AND DIET = '' AND NEWSEARCH_CLUSTERING ='' AND  DATE BETWEEN '$st_date' AND '$end_date' AND SEARCH_TYPE='J'";
                $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                $u_row=mysql_fetch_array($res);

		if ($total1 != '0')
		{
			$sql="Select count(*) as cnt from MIS.SEARCHQUERY2 WHERE RELATION !='' AND DATE BETWEEN '$st_date' AND '$end_date' AND SEARCH_TYPE='J'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$row1=mysql_fetch_array($res);

			$sql="Select count(*) as cnt from MIS.SEARCHQUERY2 WHERE RELATION !='' AND EDU_LEVEL_NEW = '' AND OCCUPATION = '' AND INCOME ='' AND MANGLIK ='' AND DIET = '' AND  DATE BETWEEN '$st_date' AND '$end_date' AND SEARCH_TYPE='J'";
                        $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        $u_row1=mysql_fetch_array($res);
		}

		$relation=$row['cnt'] + $row1['cnt'];
		unset($row1);

                $u_relation=$u_row['cnt'] + $u_row1['cnt'];
                unset($u_row1);

		$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE EDU_LEVEL_NEW !='' AND DATE BETWEEN '$st_date' AND '$end_date' AND SEARCH_TYPE='J'";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		$row=mysql_fetch_array($res);

		$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE EDU_LEVEL_NEW !='' AND RELATION ='' AND OCCUPATION = '' AND INCOME ='' AND MANGLIK ='' AND DIET = '' AND DATE BETWEEN '$st_date' AND '$end_date' AND SEARCH_TYPE='J'";
                $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                $u_row=mysql_fetch_array($res);

		if ($total1 != '0')
                {
			$sql="Select count(*) as cnt from MIS.SEARCHQUERY2 WHERE EDU_LEVEL_NEW !='' AND DATE BETWEEN '$st_date' AND '$end_date' AND SEARCH_TYPE='J'";
                	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$row1=mysql_fetch_array($res);

			$sql="Select count(*) as cnt from MIS.SEARCHQUERY2 WHERE EDU_LEVEL_NEW !='' AND RELATION ='' AND OCCUPATION = '' AND INCOME ='' AND MANGLIK ='' AND DIET = '' AND DATE BETWEEN '$st_date' AND '$end_date' AND SEARCH_TYPE='J'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));                         
			$u_row1=mysql_fetch_array($res);
		}

		$edu_level_new=$row['cnt'] + $row1['cnt'];

		$u_edu_level_new=$u_row['cnt'] + $u_row1['cnt'];
		unset($row1);
		
		$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE MANGLIK!='' AND DATE BETWEEN '$st_date' AND '$end_date' AND SEARCH_TYPE='J'";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		$row=mysql_fetch_array($res);

		$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE MANGLIK!=''  AND  EDU_LEVEL_NEW ='' AND RELATION ='' AND OCCUPATION = '' AND INCOME ='' AND DIET = '' AND DATE BETWEEN '$st_date' AND '$end_date' AND SEARCH_TYPE='J'";
                $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                $u_row=mysql_fetch_array($res);

		if ($total1 != '0')
                {
			$sql="Select count(*) as cnt from MIS.SEARCHQUERY2 WHERE MANGLIK!='' AND DATE BETWEEN '$st_date' AND '$end_date' AND SEARCH_TYPE='J'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$row1=mysql_fetch_array($res);

			$sql="Select count(*) as cnt from MIS.SEARCHQUERY2 WHERE MANGLIK!=''  AND  EDU_LEVEL_NEW ='' AND RELATION ='' AND OCCUPATION = '' AND INCOME ='' AND DIET = '' AND DATE BETWEEN '$st_date' AND '$end_date' AND SEARCH_TYPE='J'";
                	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                	$u_row1=mysql_fetch_array($res);
		}

		$manglik=$row['cnt'] + $row1['cnt'];
		$u_manglik=$u_row['cnt'] + $u_row1['cnt'];
		unset($row1);
		unset($u_row1);

		$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE DIET!='' AND DATE BETWEEN '$st_date' AND '$end_date' AND SEARCH_TYPE='J'" ;
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		$row=mysql_fetch_array($res);

		$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE DIET!='' AND MANGLIK=''  AND  EDU_LEVEL_NEW ='' AND RELATION ='' AND OCCUPATION = '' AND INCOME ='' AND DATE BETWEEN '$st_date' AND '$end_date' AND SEARCH_TYPE='J'" ;
                $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                $u_row=mysql_fetch_array($res);

		if ($total1 != '0')
                {
			$sql="Select count(*) as cnt from MIS.SEARCHQUERY2 WHERE DIET!='' AND DATE BETWEEN '$st_date' AND '$end_date' AND SEARCH_TYPE='J'" ;
                	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                	$row1=mysql_fetch_array($res);

			$sql="Select count(*) as cnt from MIS.SEARCHQUERY2 WHERE DIET!='' AND MANGLIK=''  AND  EDU_LEVEL_NEW ='' AND RELATION ='' AND OCCUPATION = '' AND INCOME ='' AND DATE BETWEEN '$st_date' AND '$end_date' AND SEARCH_TYPE='J'" ;
                	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                	$u_row1=mysql_fetch_array($res);
		}

		$diet=$row['cnt'] + $row1['cnt'];
		$u_diet=$u_row['cnt'] + $u_row1['cnt'];
		unset($row1);
		unset($u_row1);

		$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE NEWSEARCH_CLUSTERING!='' AND DATE BETWEEN '$st_date' AND '$end_date' AND SEARCH_TYPE='J'";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		$row=mysql_fetch_array($res);

		$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE NEWSEARCH_CLUSTERING!='' AND DIET='' AND MANGLIK=''  AND  EDU_LEVEL_NEW ='' AND RELATION ='' AND OCCUPATION = '' AND INCOME ='' AND DATE BETWEEN '$st_date' AND '$end_date' AND SEARCH_TYPE='J'";
                $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                $u_row=mysql_fetch_array($res);

		/*if ($total1 != '0')
                {
			//$sql="Select count(*) as cnt from MIS.SEARCHQUERY2 WHERE NEWSEARCH_CLUSTERING!='' AND DATE BETWEEN '$st_date' AND '$end_date' AND SEARCH_TYPE='J'";
                	//$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                	//$row1=mysql_fetch_array($res);

			//$sql="Select count(*) as cnt from MIS.SEARCHQUERY2 WHERE NEWSEARCH_CLUSTERING!='' AND DIET='' AND MANGLIK=''  AND  EDU_LEVEL_NEW ='' AND RELATION ='' AND OCCUPATION = '' AND INCOME ='' AND DATE BETWEEN '$st_date' AND '$end_date' AND SEARCH_TYPE='J'";
                	//$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                	//$u_row1=mysql_fetch_array($res);
		}*/
		$newsearch=$row['cnt'];// + $row1['cnt'];
		$u_newsearch=$u_row['cnt'];// + $u_row1['cnt'];
		unset($row1);
		unset($u_row1);

		$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE OCCUPATION!='' AND DATE BETWEEN '$st_date' AND '$end_date' AND SEARCH_TYPE='J'" ;
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		$row=mysql_fetch_array($res);

		$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE OCCUPATION!='' AND NEWSEARCH_CLUSTERING='' AND DIET='' AND MANGLIK=''  AND  EDU_LEVEL_NEW ='' AND RELATION ='' AND INCOME ='' AND DATE BETWEEN '$st_date' AND '$end_date' AND SEARCH_TYPE='J'" ;
                $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                $u_row=mysql_fetch_array($res);

		if ($total1 != '0')
                {
			$sql="Select count(*) as cnt from MIS.SEARCHQUERY2 WHERE OCCUPATION!='' AND DATE BETWEEN '$st_date' AND '$end_date' AND SEARCH_TYPE='J'" ;
                	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                	$row1=mysql_fetch_array($res);

			$sql="Select count(*) as cnt from MIS.SEARCHQUERY2 WHERE OCCUPATION!='' AND DIET='' AND MANGLIK=''  AND  EDU_LEVEL_NEW ='' AND RELATION ='' AND INCOME ='' AND DATE BETWEEN '$st_date' AND '$end_date' AND SEARCH_TYPE='J'" ;
                	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                	$u_row1=mysql_fetch_array($res);
		}
		$occupation=$row['cnt'] + $row1['cnt'];
		$u_occupation=$u_row['cnt'] + $u_row1['cnt'];
		unset($row1);
		unset($u_row1);

		$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE INCOME!='' AND DATE BETWEEN '$st_date' AND '$end_date' AND SEARCH_TYPE='J'";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		$row=mysql_fetch_array($res);

		$sql="Select count(*) as cnt from MIS.SEARCHQUERY WHERE INCOME!='' AND OCCUPATION='' AND NEWSEARCH_CLUSTERING='' AND DIET='' AND MANGLIK=''  AND  EDU_LEVEL_NEW ='' AND RELATION ='' AND DATE BETWEEN '$st_date' AND '$end_date' AND SEARCH_TYPE='J'";
                $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                $u_row=mysql_fetch_array($res);

		if ($total1 != '0')
                {
			$sql="Select count(*) as cnt from MIS.SEARCHQUERY2 WHERE INCOME!='' AND DATE BETWEEN '$st_date' AND '$end_date' AND SEARCH_TYPE='J'";
                	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                	$row1=mysql_fetch_array($res);

			$sql="Select count(*) as cnt from MIS.SEARCHQUERY2 WHERE INCOME!='' AND OCCUPATION='' AND DIET='' AND MANGLIK=''  AND  EDU_LEVEL_NEW ='' AND RELATION ='' AND DATE BETWEEN '$st_date' AND '$end_date' AND SEARCH_TYPE='J'";
                	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                	$u_row1=mysql_fetch_array($res);
		}
		$income=$row['cnt'] + $row1['cnt'];
		$u_income=$u_row['cnt'] + $u_row1['cnt'];

		$smarty->assign("total",$total);
		$smarty->assign("relation",$relation);
		$smarty->assign("newsearch",$newsearch);
		$smarty->assign("edu_level_new",$edu_level_new);
		$smarty->assign("diet",$diet);
		$smarty->assign("occupation",$occupation);
		$smarty->assign("income",$income);
		$smarty->assign("manglik",$manglik);

		$smarty->assign("u_relation",$u_relation);
                $smarty->assign("u_newsearch",$u_newsearch);
                $smarty->assign("u_edu_level_new",$u_edu_level_new);
                $smarty->assign("u_diet",$u_diet);
                $smarty->assign("u_occupation",$u_occupation);
                $smarty->assign("u_income",$u_income);
                $smarty->assign("u_manglik",$u_manglik);

		$smarty->assign("day",$day);
		$smarty->assign("year",$year);
		$smarty->assign("month",$month);
		$smarty->assign("eday",$eday);
		$smarty->assign("eyear",$eyear);
		$smarty->assign("emonth",$emonth);
		$smarty->assign("cid",$cid);
		$smarty->display("clustering_mis.htm");
	}
	
	else
	{
		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}

		for($i=0;$i<12;$i++)
                {
                        $mmarr[$i]=$i+1;
                }

                for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}

		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
		$smarty->assign("cid",$cid);
		$smarty->display("clustering_mis.htm");
	}
}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}
?>
