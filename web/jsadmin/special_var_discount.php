<?php
include("connect.inc");
                                                                                                                             
$db = connect_db();
                                                                                                                             
$data = authenticated($cid);
if($data)
{
	if($submit)
       	{
		$sql = "SELECT PROFILEID FROM newjs.JPROFILE WHERE USERNAME='$phrase'";
		$result = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		if($row = mysql_fetch_array($result))
		{
			$ts=time();
			$pid = $row['PROFILEID'];
			$sql_score = "SELECT * FROM billing.VARIABLE_DISCOUNT WHERE PROFILEID='$pid' ORDER BY ENTRY_DT DESC";
			$res_score =mysql_query_decide($sql_score) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			if(mysql_num_rows($res_score) > 0)
			{
				$smarty->assign("FOUND",1);
				$i=0;
				while($row=mysql_fetch_array($res_score))
				{
					$main[$i]['SDATE']=$row['SDATE'];
					$main[$i]['EDATE']=$row['EDATE'];
					$main[$i]['DISC']=$row['DISCOUNT'];
					list($y,$m,$d)=explode('-',$row['SDATE']);
					$sts=mktime(0,0,0,$m,$d,$y);
					list($y,$m,$d)=explode('-',$row['EDATE']);
					$ets=mktime('23','59','59',$m,$d,$y);
					if($ets>=$ts && $ts>=$sts)
						$main[$i]['ACTIVE']=1;
					elseif($ets<$ts)
						$main[$i]['ACTIVE']=2;
					else
						$main[$i]['ACTIVE']=0;
					$i++;
				}
				$smarty->assign("DATA",$main);
			}
			else
			$smarty->assign("FOUND",0);
			$smarty->assign("username",$phrase);
	  		$smarty->assign("RESULT_FOUND",1);
		}
		else
		{
		  	$smarty->assign("NO_RESULT_FOUND",1);
		}
	}
	$smarty->assign("cid",$cid);
	$smarty->display("special_var_discount.htm");
	

}
else
{
	$smarty->assign("user",$user);
	$smarty->display("jsconnectError.tpl");
}


?>
