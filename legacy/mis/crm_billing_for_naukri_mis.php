<?php

include("connect.inc");
include_once("../profile/pg/functions.php");
$db=connect_misdb();
$db2=connect_master();

// dol conv rate changed to 43.5 - overwitten current 45.8 - so that mis does not change
//$DOL_CONV_RATE=43.5;

if(authenticated($cid))
{
	$name= getname($cid);
	if($CMDGo)
	{
		$smarty->assign("flag","1");
		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}

		$smarty->assign("yy",$myear);
		$smarty->assign("mm",$mmonth);

		$st_date=$myear."-".$mmonth."-01 00:00:00";
		$end_date=$myear."-".$mmonth."-31 23:59:59";

		$privilage=getprivilage($cid);
                $priv=explode("+",$privilage);

		/*if(in_array("IA",$priv))
		{
			$admin=1;
			$smarty->assign("ADMIN","Y");
		}*/
																	    if(in_array('BN',$priv))
		{
			$smarty->assign("INBOUND","Y");
		}

		$sql="SELECT CENTER,EMP_ID FROM jsadmin.PSWRDS WHERE USERNAME='$name' AND COMPANY='JS'";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$row=mysql_fetch_array($res);
		$center=strtoupper($row['CENTER']);
		$emp_id=$row['EMP_ID'];
														    
		$emp_id_str="'$emp_id'";
		$emp_id_str1="";
                                                                                                                            
		//if($admin)
			$sql="SELECT EMP_ID FROM jsadmin.PSWRDS WHERE HEAD_ID='$emp_id' AND COMPANY='JS' AND EMP_ID<>0";
		//else
		//	$sql="SELECT EMP_ID FROM jsadmin.PSWRDS WHERE UPPER(CENTER)='$center' AND COMPANY='JS'";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
														    
		while($row=mysql_fetch_array($res))
		{
			if(strstr($emp_id_str , "'$row[EMP_ID]'") == "")
			{
				$emp_id_str1 .= "'$row[EMP_ID]',";
			}
		}
														    
		$emp_id_str = $emp_id_str1 . $emp_id_str;
		$val=0;
		//if($admin)
		//{
			while(1)
			{
				$emp_id_str1=substr($emp_id_str1, 0, strlen($emp_id_str1) - 1);
				$emp_id_str2="";
														    
				$sql="SELECT EMP_ID FROM jsadmin.PSWRDS WHERE HEAD_ID IN ($emp_id_str1) AND COMPANY='JS'";
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
														    
				if(mysql_num_rows($res) == 0)
				{
					break;
				}
				else
				{
					while($row=mysql_fetch_array($res))
					{
						if(strstr($emp_id_str , "'$row[EMP_ID]'") == "")
						$emp_id_str2 .= "'$row[EMP_ID]',";
					}
					if(!$emp_id_str2)
						break;
					$emp_id_str1=$emp_id_str2;
					$emp_id_str = $emp_id_str2 . $emp_id_str;
					$val++;
					if($val == 15)
					{
						echo $val;
						die();
					}
				}
			}
		//}
		$sql="SELECT USERNAME FROM jsadmin.PSWRDS WHERE EMP_ID IN ($emp_id_str) AND COMPANY='JS' AND PRIVILAGE LIKE '%BN%'";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			//$allotarr[]=$row['USERNAME'];
			$userarr[] = $row['USERNAME'];
		}

		/*$sql = "SELECT USERNAME FROM jsadmin.PSWRDS WHERE PRIVILAGE LIKE '%BN%'";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		while ($row = mysql_fetch_array($res))
		{
			$userarr[] = $row['USERNAME'];
		}*/

		if (is_array($userarr))
			$user_str = implode("','",$userarr);

		$sql="SELECT ENTRYBY , STATUS,PROFILEID,BILLID,if(TYPE='DOL',AMOUNT*DOL_CONV_RATE,AMOUNT) AS AMOUNT,ENTRY_DT,DAYOFMONTH(ENTRY_DT) as dd FROM billing.PAYMENT_DETAIL WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND STATUS IN ('DONE','REFUND') AND ENTRYBY IN ('$user_str')";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			do
			{
				//$ind=0;
				$profileid=$row['PROFILEID'];
				$billid=$row['BILLID'];
				$valid_id = 0;
		                $valid_id = check_validity($billid);
                		if($valid_id)
                		{
					$amount=$row['AMOUNT'];
					$status=$row['STATUS'];
					$paid_dt=$row['ENTRY_DT'];
					$alloted_to=$row['ENTRYBY'];
				
					$dd=$row['dd']-1;
					if(is_array($operatorarr))
					{
						if(!in_array($alloted_to,$operatorarr))
						{
							$operatorarr[]=$alloted_to;
						}
					}
					else
					{
						$operatorarr[]=$alloted_to;
					}
					$j=array_search($alloted_to,$operatorarr);
					if($status=='DONE')
					{
						$amt[$j][$dd]+=$amount;
						$amttot[$j]+=$amount;
					}
					else
					{
						$amt[$j][$dd]-=$amount;
						$amttot[$j]-=$amount;
					}
				}
			}while($row=mysql_fetch_array($res));
		}

		for($i=0;$i<count($operatorarr);$i++)
		{
			$net_off_tax[$i]=net_off_tax_calculation($amttot[$i],$end_date);
		}

		if(!count($amt))
			$smarty->assign("NORECORD","Y");

		$smarty->assign("cid",$cid);
		$smarty->assign("amt",$amt);
		$smarty->assign("amta",$amta);
		$smarty->assign("amtb",$amtb);
		$smarty->assign("amttot",$amttot);
		$smarty->assign("net_off_tax",$net_off_tax);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("brancharr",$brancharr);
		$smarty->assign("operatorarr",$operatorarr);

		$smarty->display("crm_billing_for_naukri_mis.htm");

	}
	else
	{
		$user=getname($cid);
		$smarty->assign("flag","0");

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
		$smarty->display("crm_billing_for_naukri_mis.htm");
	}
}
else
{
	$smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}
?>
