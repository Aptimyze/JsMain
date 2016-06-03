<?php

include("connect.inc");
include("history.php");

if(authenticated($cid))
{
	$flag=0;
	$name= getname($cid);
        $privilage = explode("+",getprivilage($cid));

	if($GetHistory)
	{
		$flag=1;
		$sql = "SELECT PROFILEID,EMAIL,SUBSCRIPTION FROM newjs.JPROFILE WHERE USERNAME='$USERNAME'";
                $result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                if($myrow=mysql_fetch_array($result))
		{
	                $profileid=$myrow['PROFILEID'];
			$temp_email=explode("@",$myrow['EMAIL']);
			$email=$temp_email[0]."@xxx.com";
			$smarty->assign("USERNAME",$USERNAME);
			$smarty->assign("PROFILEID",$profileid);
			$smarty->assign("EMAIL",$email);

			/*
			$sqlD ="select DEALLOCATION_DT from incentive.DEALLOCATION_TRACK where PROFILEID='$profileid'";
			$resD=mysql_query_decide($sqlD) or die("$sqlD".mysql_error_js());
			while($rowD=mysql_fetch_array($resD))
				$deAllocateDates[]=$rowD['DEALLOCATION_DT'];
			*/

			//$dateSet =JSstrToTime('2013-03-01 00:00:00');
			$val=0;
			$sql="SELECT ALLOTED_TO, ALLOT_TIME, RELAX_DAYS,DE_ALLOCATION_DT,REAL_DE_ALLOCATION_DT FROM incentive.CRM_DAILY_ALLOT WHERE PROFILEID='$profileid'";
			$sql .=" UNION select ALLOTED_TO, ALLOT_TIME, RELAX_DAYS,DE_ALLOCATION_DT,REAL_DE_ALLOCATION_DT FROM incentive.CRM_DAILY_ALLOT_TRACK WHERE PROFILEID='$profileid' ORDER BY ALLOT_TIME ASC";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			if($row=mysql_fetch_array($res))
			{
				$i=0;
				do
				{
					$allotTime				=$row['ALLOT_TIME'];
					$deAllocationDt				=$row['DE_ALLOCATION_DT'];
					$handled_arr[$i]["SNO"]=$i+1;
					$handled_arr[$i]["ALLOTED_TO"]		=$row['ALLOTED_TO'];
					$handled_arr[$i]["ALLOT_TIME"]		=$allotTime;
					$handled_arr[$i]["DE_ALLOCATION_DT"]	=$deAllocationDt;
					$handled_arr[$i]["RELAX_DAYS"]		=$row['RELAX_DAYS'];
					$handled_arr[$i]["ACTUAL_DEALLOCATION_DT"]=$row['REAL_DE_ALLOCATION_DT'];

					/*
					if(JSstrToTime($allotTime)>$dateSet){				
					foreach($deAllocateDates as $key=>$val){
						$actualDeAllocDt =JSstrToTime($val);
						if($actualDeAllocDt>JSstrToTime($allotTime)){
							$handled_arr[$i]['ACTUAL_DEALLOCATION_DT'] =$val;
							break;
						}	
					}
					unset($deAllocateDates[$key]);
					}*/

					$i++;
				}while($row=mysql_fetch_array($res));

				$smarty->assign("handled_arr",$handled_arr);
				$user_values=gethistory($USERNAME);
				$smarty->assign("ROW",$user_values);

				 $sql="SELECT ALLOTED_TO,STATUS FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid'";
                                $res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                                if($row=mysql_fetch_array($res))
                                {
                                        $smarty->assign("NOTFOUND","");
                                        $orig_alloted_to=$row['ALLOTED_TO'];

                                        if($row['STATUS']=='P' && $myrow['SUBSCRIPTION']<>'')
                                                $smarty->assign("ALREADY_PAID","Y");
                                        elseif($myrow['SUBSCRIPTION']<>'')
                                                $smarty->assign("ALREADY_PAID","Y");
					$smarty->assign("orig_alloted_to",$orig_alloted_to);
				}

			}
			else
			{
				$smarty->assign("NOTFOUND","Y");
			}
			
		}
                else
		{
                        $smarty->assign("wrong_username","Y");
		}
		$smarty->assign("flag",$flag);
		$smarty->assign("cid",$cid);
		$smarty->assign("name",$name);
		$smarty->display("tele_calling_details.htm");
	}
	else
	{
		$smarty->assign("flag",$flag);
		$smarty->assign("name",$name);
		$smarty->assign("cid",$cid);
		$smarty->display("tele_calling_details.htm");
	}
}
else
{
	$msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.php\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("crm_msg.tpl");
}
?>
