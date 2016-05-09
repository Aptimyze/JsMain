<?php

/*********************************************************************************************
* FILE NAME     : ap_queue_report.php
* DESCRIPTION   : It provides details for . 
*********************************************************************************************/
                                                                                                                             
include("connect.inc");

//$db=connect_misdb();
//$db2=connect_master();
//@mysql_select_db("billing",$db);
$db=connect_master(); 
                                                                                                                            
$flag=0;
if($checksum)
	$cid=$checksum;
else
	$checksum =$cid;

if(authenticated($checksum))
{

	// DPP(desired partnet profile) section (DPP Queue)
	$sql_dpp ="SELECT distinct count(PROFILEID) AS COUNT from Assisted_Product.AP_DPP_FILTER_ARCHIVE AS dpp WHERE (dpp.STATUS='NQA' OR dpp.STATUS='RQA')";
	$result=mysql_query_decide($sql_dpp,$db) or die(mysql_error_js());	
	while($row=mysql_fetch_assoc($result))
	{
		$dpp_count =$row['COUNT'];
	}	

        // DISPATCHER section (DISPATCHER Queue)
        $sql_dis ="SELECT distinct count(PROFILEID) AS COUNT from Assisted_Product.AP_SERVICE_TABLE AS s WHERE s.SERVICED='' AND s.NEXT_SERVICE_DATE=CURDATE() ";
        $result=mysql_query_decide($sql_dis,$db) or die(mysql_error_js());
        while($row=mysql_fetch_assoc($result))
        {
                $dis_count =$row['COUNT'];
        }       

        // DISPATCHER MISSED section (DISPATCHER Queue)
        $sql_dis_miss ="SELECT distinct count(PROFILEID) AS COUNT from Assisted_Product.AP_MISSED_SERVICE_LOG AS m WHERE m.COMPLETED=''";
        $result_miss=mysql_query_decide($sql_dis_miss,$db) or die(mysql_error_js());
        while($row_miss=mysql_fetch_assoc($result_miss))
        {
                $dis_count_miss =$row_miss['COUNT'];
        }

        // TELECALLER section (TELECALLER Queue)
        $sql_tc ="SELECT distinct count(MATCH_ID) AS COUNT from Assisted_Product.AP_CALL_HISTORY AS c WHERE c.CALL_STATUS='N' AND c.FOLDER='TBC'";
        $result=mysql_query_decide($sql_tc,$db) or die(mysql_error_js());
        while($row=mysql_fetch_assoc($result))
        {
                $tc_count =$row['COUNT'];
        }       
	
	$smarty->assign("dpp_queue_count",$dpp_count);
	$smarty->assign("dis_queue_count",$dis_count);
	$smarty->assign("dis_queue_count_miss",$dis_count_miss);
	$smarty->assign("tc_queue_count",$tc_count);
	$smarty->assign("cid",$cid);
        $smarty->display("ap_queue_report.htm");
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
