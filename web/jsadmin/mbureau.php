<?php

include("connect.inc");
include("../crm/display_result.inc");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");

$PAGELEN = 30 ;
$LINKNO=10;
$START=1;
if (!$j )
        $j = 0;
$sno=$j+1;

if(authenticated($cid))
{
	mysql_select_db_js('marriage_bureau');
	$sql = "Select Count(*) from marriage_bureau.BUREAU_PROFILE where ACTIVATED<>'D'";
	$result = mysql_query_decide($sql,$db) or die(mysql_error_js().$sql);;
	$myrow = mysql_fetch_row($result);
	$TOTALREC = $myrow[0];
	$r=0;
	if($TOTALREC>0)
	{
		$sql = "Select PROFILEID,CPP,USERNAME,NAME,CITY,MONEY from marriage_bureau.BUREAU_PROFILE WHERE ACTIVATED<>'D' LIMIT $j,$PAGELEN";
		$result = mysql_query_decide($sql,$db) or die(mysql_error_js().$sql);//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$fromprofilepage=1;//flag for connectmb.inc
		include('../marriage_bureau/connectmb.inc');
		while($myrow = mysql_fetch_array($result))
		{
			mysql_select_db_js('marriage_bureau');
			$mpid=$myrow["PROFILEID"];
$sql = "Select count(*) as cnt from marriage_bureau.VIEWED WHERE BUREAU_PROFILEID='$mpid'";
                        $res = mysql_query_decide($sql,$db) or die(mysql_error_js().$sql);
                        if($row=mysql_fetch_array($res))
				$total_profile_seen_and_paid_for=$row['cnt'];	
			mysql_select_db_js('newjs');	
			$source=generate_source4MB($myrow["PROFILEID"]);
			$sql = "Select count(*) as cnt from newjs.JPROFILE WHERE source='$source'";
	                $res = mysql_query_decide($sql,$db) or die(mysql_error_js().$sql);
			$row=mysql_fetch_array($res);
			$sql = "Select count(*) as cnt from newjs.JPROFILE WHERE source='$source' and ACTIVATED='Y'";
                        $res = mysql_query_decide($sql,$db) or die(mysql_error_js().$sql);
                        $row1=mysql_fetch_array($res);

			//from here
			$profileid=profileid_of_bureau($source);
			$total=shownewjs_details($source,$profileid,'','');
			$total_contacted=$total["totalcontacted"];
			$total_viewed=$total["viewed_corrected"];
			//here

			$values[]=array("sno"=>$sno,
                                        "USERNAME"=>$myrow["USERNAME"],
                                        "CITY"=>$myrow["CITY"],
                                        "MONEY"=>$myrow["MONEY"],
					"NAME"=>$myrow["NAME"],
                                        "PROFILEID"=>$myrow["PROFILEID"],
                                        "CPP"=>$myrow["CPP"],
					"TOTAL_CONTACTED"=>$total_contacted,								 "TOTAL_VIEWED"=>$total_viewed,
					"CURRENT_NO_PROFILE"=>$row1["cnt"],
					"TOTAL_NO_PROFILE"=>$row["cnt"],
				"TOTAL_PROFILE_SEEN_AND_PAID_FOR"=>$total_profile_seen_and_paid_for);
			$sno++;
                        $r=1;
		}
	}
	if( $j )
		$cPage = ($j/$PAGELEN) + 1;
	else
		$cPage = 1;
	pagelink($PAGELEN,$TOTALREC,$cPage,$LINKNO,$cid,"");
	$smarty->assign('arethere',$r);
	$smarty->assign("ROWS",$values);
	$smarty->assign("COUNT",$TOTALREC);
	$smarty->assign("CURRENTPAGE",$cPage);
	$no_of_pages=ceil($TOTALREC/$PAGELEN);
	$smarty->assign("NO_OF_PAGES",$no_of_pages);
	$smarty->assign("CID",$cid);
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->display("mbureau.htm");
}
else
{
	$msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");

}
?>
