<?php
function gethistory($USERNAME,$limit='')
{
	$profileidExist =false;

	if(is_numeric($USERNAME))
		$profileidExist =true;;
	
	$sql="SELECT ENTRYBY,MODE,COMMENT,ENTRY_DT,PROFILEID FROM incentive.HISTORY";
	if($profileidExist) 
		$sql .=" WHERE PROFILEID='$USERNAME' ORDER BY ENTRY_DT DESC";
	else
		$sql .=" WHERE USERNAME='$USERNAME' ORDER BY ENTRY_DT DESC";	
	if($limit)
		$sql .=" LIMIT $limit"; 
       $res=mysql_query_decide($sql) or die(mysql_error_js());
        $i=1;
        while($myrow=mysql_fetch_array($res))
        {
		$profileid = $myrow["PROFILEID"];
		$mode = preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $myrow["MODE"]);
		if(is_numeric($myrow["MODE"])){
			$modeVal = '';
			$processVal = $myrow["MODE"];
		} else {
			$modeVal = $mode[0];
			$processVal = $mode[1];
		}
		$process = crmParams::$processNames[crmParams::$processFlagReverse[$processVal]];
	        $values[] = array("SNO"=>$i,
                                  "NAME"=>$myrow["ENTRYBY"],
                                  "DATE"=>$myrow["ENTRY_DT"],
                                  "MODE"=>$modeVal,
                                  "PROCESS"=>$process,
                                  "COMMENTS"=>str_replace("\n","<br>",$myrow["COMMENT"])
                                 );
                $i++;
        unset($process);
        }
	$factor = getfactor($profileid);
	$sql="SELECT Display FROM incentive.show_IM";
 	$res=mysql_query_decide($sql) or die(mysql_error_js());

	$row=mysql_fetch_assoc($res);
	
        $values['IM'] = $factor;
	$values['show_IM']=$row['Display'];
	return $values;
}

function getfactor($pid,$from='I')
{
        global $db;
        $factor = 0;
        $cnt_arr = array();

	$sql="SELECT ALLOTED_TO FROM incentive.MAIN_ADMIN WHERE PROFILEID='$pid'";
	$res_c=mysql_query_decide($sql,$db) or die("$sql".mysql_error($db));
	$row_c=mysql_fetch_array($res_c);
	$alloted_to= $row_c['ALLOTED_TO'];

	$sql="SELECT PRIVILAGE FROM jsadmin.PSWRDS WHERE USERNAME='$alloted_to'";
	$res_c=mysql_query_decide($sql,$db) or die("$sql".mysql_error($db));
	$row_c=mysql_fetch_array($res_c);
	$priv = explode("+",$row_c['PRIVILAGE']);
	if(in_array("PRALL",$priv))
        	$branch_user=1;
       	else
        	$branch_user=0;

        if($branch_user)
        {
                //previously handled by same user
                $sql_b = "SELECT COUNT(*) as cnt FROM incentive.CRM_DAILY_ALLOT WHERE PROFILEID='$pid' AND ALLOTED_TO='$alloted_to'";
		$res_b  =mysql_query_decide($sql_b,$db) or die("$sql_b".mysql_error($db));
                $row_b = mysql_fetch_array($res_b);
                if($row_b["cnt"]>1)
                        $factor = 1;
                else
                        $factor = 0;
        }

	if(!$factor)
        {
                //ever paid
                $i=0;
                $sqlp = "SELECT ENTRY_DT FROM billing.PAYMENT_DETAIL WHERE PROFILEID='$pid' AND STATUS='DONE' ORDER BY ENTRY_DT DESC LIMIT 2";
		$resp = mysql_query_decide($sqlp,$db) or die("$sqlp".mysql_error($db));
                while($row_p = mysql_fetch_array($resp))
                {
                        $i++;
                        $paid_dt[$i] = $row_p["ENTRY_DT"];
                }
		if($from=='M')
                        $date_limit = date('Y-m-d',JSstrToTime($paid_dt[2]));
                elseif($from=='I')
                        $date_limit = date('Y-m-d',JSstrToTime($paid_dt[1]));
                else
                        $date_limit = '';
                //end
                $sql="select ENTRYBY from incentive.HISTORY WHERE PROFILEID='$pid' AND ENTRYBY!='$alloted_to' AND DISPOSITION NOT IN ('CNC','AA','D','MA','')";
                if($date_limit != '')
                        $sql.=" AND ENTRY_DT>'$date_limit'";

		$res_md =mysql_query_decide($sql,$db) or die("$sql".mysql_error($db));
                while($row_md = mysql_fetch_array($res_md))
                {
                        if(!in_array($row_md["ENTRYBY"],$cnt_arr) && $row_md["ENTRYBY"]!='')
                                $cnt_arr[] = $row_md["ENTRYBY"];
                }

                $cnt = count($cnt_arr);

                if($cnt>6)
                        $factor=4;
                elseif($cnt>=4 && $cnt<=6)
                        $factor=3;
                elseif($cnt>0 && $cnt<=3)
			$factor=2;
                else
                        $factor=1;
        }
        return $factor;
}

function getHistoryCount($profileid='')
{
	$countNo =0;
	if(!$profileid)
		return $countNo;
	$sql_h1 ="select ALLOT_TIME from incentive.MAIN_ADMIN WHERE PROFILEID='$profileid'";
	$res_h1 = mysql_query_decide($sql_h1) or die(mysql_error_js());
	$row_h1=mysql_fetch_array($res_h1);
	if(mysql_num_rows($res_h1)>0)
	{
		$allotmentDate_h  =$row_h1['ALLOT_TIME'];
		$dateArr=explode(" ",$allotmentDate_h);
		$date1 =$dateArr[0];
		$sql_h2 ="select count(*) AS COUNT from incentive.HISTORY WHERE PROFILEID='$profileid' AND ENTRY_DT>='$date1'";
		$res_h2 = mysql_query_decide($sql_h2) or die(mysql_error_js());
		$row_h2 =mysql_fetch_array($res_h2);
		$countNo =$row_h2['COUNT'];
	}
	return $countNo;

}	

?>
