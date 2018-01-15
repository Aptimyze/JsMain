<?php
include("connect.inc");

if(authenticated($cid))
{
	$smarty->assign("cid",$cid);
	if($CMDGo)
	{
		$i = 0;
		$st_date=$year."-".$month."-".$day." 00:00:00";
                $end_date=$year."-".$month."-".$day." 23:59:59";

		$name=getname($cid);

		$sql="SELECT c.PROFILEID , j.PHONE_RES , j.PHONE_MOB FROM incentive.CRM_DAILY_ALLOT c LEFT JOIN newjs.JPROFILE j ON c.PROFILEID=j.PROFILEID WHERE c.ALLOT_TIME BETWEEN '$st_date' AND '$end_date' AND c.ALLOTED_TO='$name'";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$followup[$i]['PROFILEID']= $row['PROFILEID'];
			if ($row['PHONE_RES'] && $row['PHONE_MOB'])
				$followup[$i]['PHONE'] = $row['PHONE_RES']."(R) , ".$row['PHONE_MOB']."(M)";
			elseif ($row['PHONE_RES'])
				$followup[$i]['PHONE'] = $row['PHONE_RES']."(R)";
			elseif ($row['PHONE_MOB'])
				$followup[$i]['PHONE'] = $row['PHONE_MOB']."(M)";
			$i++;
		}
		if (!(is_array($followup)))
			$smarty->assign("norecord",1);
		$smarty->assign("followup",$followup);
		$smarty->assign("flag",1);
		$smarty->assign("name",$name);
		$smarty->display("crm_daily_allot_mis.htm");
	}
	else
	{
		$user=getname($cid);
                $smarty->assign("flag","0");
                for($i=0;$i<31;$i++)
                {
                        $ddarr[$i]=$i+1;
                }
                for($i=0;$i<12;$i++)
                {
                        $mmarr[$i]=$i+1;
                }
                for($i=0;$i<10;$i++)
                {
                        $yyarr[$i]=$i+2006;
                }
                $smarty->assign("ddarr",$ddarr);
                $smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
		$smarty->display("crm_daily_allot_mis.htm");
	}
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
