<?php
/**Script written by Aman Sharma for Qc-Screening Module**/
include("connect.inc");
include ("../crm/display_result.inc");
$PAGELEN=50;
$LINKNO=5;
$START=1;
if (!$j )
        $j = 0;
                                                                                                 
$sno=$j+1;

if(authenticated($cid))
{
	if($date1 && $date2)
	{
		$st_date_array=explode("-",$date1);
		$st_date=$st_date_array[2]."-".$st_date_array[1]."-".$st_date_array[0]." 00:00:00";
		$end_date_array=explode("-",$date2);
		$end_date=$end_date_array[2]."-".$end_date_array[1]."-".$end_date_array[0]." 23:59:59";
	    $db=connect_slave();			
		$sql="SELECT COUNT(*) from jsadmin.SCREENING_LOG where SCREENED_BY='$screener' and SCREENED_TIME between '$st_date' and '$end_date' and ENTRY_TYPE='M' and GRADED<>'Y'";
                $result=mysql_query_decide($sql,$db) or die(mysql_error_js());
                $myrow = mysql_fetch_row($result);
                $TOTALREC = $myrow[0];
		
		$sql="SELECT REF_ID,PROFILEID,USERNAME,SCREENED_TIME,SCREENING from jsadmin.SCREENING_LOG where SCREENED_BY='$screener' and SCREENED_TIME between '$st_date' and '$end_date' and ENTRY_TYPE='M' and GRADED<>'Y' LIMIT $j,$PAGELEN";
		$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		if(mysql_num_rows($result)>0)
		{
			$i=0;
			while($myrow=mysql_fetch_array($result))
			{
				$arr[$i]["id"]=$myrow["REF_ID"];	
				$arr[$i]["pid"]=$myrow["PROFILEID"];	
				$arr[$i]["username"]=$myrow["USERNAME"];
                                $dt = new DateTime($myrow["SCREENED_TIME"], new DateTimeZone('America/New_York'));
                                $dt->setTimezone(new DateTimeZone('Asia/Kolkata'));
				$arr[$i]["time"]=$dt->format('Y-m-d H:i:s');
                                if($myrow["SCREENING"] == 2)
                                    $arr[$i]["type"] = "New";
                                else if($myrow["SCREENING"] == 3)
                                    $arr[$i]["type"] = "Edit";
                                else
                                    $arr[$i]["type"] = "N/A";
				$i++;
			}
		}
                if( $j )
                        $cPage = ($j/$PAGELEN) + 1;
                else
                        $cPage = 1;
		$smarty->assign("j",$j);
		$smarty->assign("PAGELEN",$PAGELEN);
                pagelink($PAGELEN,$TOTALREC,$cPage,$LINKNO,$cid,"qc_details.php",'','','',$showall,'',$date1,$date2,$screener);
                $smarty->assign("COUNT",$TOTALREC);
                $smarty->assign("CURRENTPAGE",$cPage);
                $no_of_pages=ceil($TOTALREC/$PAGELEN);
                $smarty->assign("NO_OF_PAGES",$no_of_pages);
		$smarty->assign("arr",$arr);
		$smarty->assign("cid",$cid);
		$smarty->assign("SCREENER",$screener);
		$smarty->display("qc_details.htm");
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
