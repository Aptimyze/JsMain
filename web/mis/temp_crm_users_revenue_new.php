<?php

include("connect.inc");
include_once("../profile/pg/functions.php");
$db=connect_misdb();
$db2=connect_master();

// dol conv rate changed to 43.5 - overwitten current 45.8 - so that mis does not change
//$DOL_CONV_RATE=43.5;

if(authenticated($cid))
{
	 if($outside)
        {
                $CMDGo='Y';
                $branch='ALL';
                $today=date("Y-m-d");
                list($myear,$mmonth,$d)=explode("-",$today);
        }

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

		$sql="SELECT STATUS,PROFILEID,if(TYPE='DOL',AMOUNT*$DOL_CONV_RATE,AMOUNT) AS AMOUNT,DAYOFMONTH(ENTRY_DT) as dd FROM billing.PAYMENT_DETAIL WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND STATUS IN ('DONE','REFUND')";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			do
			{
				$ind=0;
				$profileid=$row['PROFILEID'];
				$amount=$row['AMOUNT'];
				$status=$row['STATUS'];

				$sql="SELECT ALLOTED_TO FROM incentive.MAIN_ADMIN_DEC WHERE PROFILEID='$profileid'";
				$res1=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
				if($row1=mysql_fetch_array($res1))
				{
					$dd=$row['dd']-1;
					$alloted_to=$row1['ALLOTED_TO'];
					$sql="SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME='$alloted_to'";
					$res_c=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
					$row_c=mysql_fetch_array($res_c);
					$center=strtoupper($row_c['CENTER']);
					if(is_array($brancharr))
					{
						if(!in_array($center,$brancharr))
						{
							if($branch=='ALL' || strtoupper($branch)==$center)
								$brancharr[]=$center;
						}
					}
					else
					{
						if($branch=='ALL' || strtoupper($branch)==$center)
							$brancharr[]=$center;
					}

					if($branch=='ALL' || strtoupper($branch)==$center)
					{
						$i=array_search($center,$brancharr);
						if(is_array($operatorarr[$i]))
						{
							if(!in_array($alloted_to,$operatorarr[$i]))
							{
								$operatorarr[$i][]=$alloted_to;
							}
						}
						else
						{
							$operatorarr[$i][]=$alloted_to;
						}
						$j=array_search($alloted_to,$operatorarr[$i]);
						if($status=='DONE')
						{
							$amt[$i][$j][$dd]+=$amount;
							$amta[$i][$j]+=$amount;
							$amtb[$i][$dd]+=$amount;
							$amttot[$i]+=$amount;
						}
						else
						{
							$amt[$i][$j][$dd]-=$amount;
							$amta[$i][$j]-=$amount;
							$amtb[$i][$dd]-=$amount;
							$amttot[$i]-=$amount;
						}
					}
				}
			}while($row=mysql_fetch_array($res));
		}
		if($brancharr)
			$smarty->assign("BRANCH","Y");

		$smarty->assign("cid",$cid);
		$smarty->assign("amt",$amt);
		$smarty->assign("amta",$amta);
		$smarty->assign("amtb",$amtb);
		$smarty->assign("amttot",$amttot);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("brancharr",$brancharr);
		$smarty->assign("operatorarr",$operatorarr);

		$smarty->display("crm_users_revenue_new.htm");
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
                $privilage=getprivilage($cid);
                $priv=explode("+",$privilage);
                if(in_array('MA',$priv) || in_array('MB',$priv))
                {
                        $smarty->assign("VIEWALL","Y");
                        //run query : select all branches
                        $sql="SELECT NAME FROM incentive.BRANCHES";
                        $res=mysql_query_decide($sql,$db) or die(mysql_error_js());
                        if($row=mysql_fetch_array($res))
                        {
                                do
                                {
                                        $brancharr[]=$row['NAME'];
                                }while($row=mysql_fetch_array($res));
                        }

                        $smarty->assign("brancharr",$brancharr);
                }
                else
                {
                        // run query : select branch of user
                        $sql="SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME='$user'";
                        $res=mysql_query_decide($sql,$db) or die(mysql_error_js());
                        if($row=mysql_fetch_array($res))
                        {
                                $branch=$row['CENTER'];
                        }

                        $smarty->assign("ONLYBRANCH","Y");
                        $smarty->assign("branch",$branch);
                }

                $smarty->assign("priv",$priv);
                $smarty->assign("ddarr",$ddarr);
                $smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
                $smarty->assign("cid",$cid);
		$smarty->display("crm_users_revenue_new.htm");
	}
}
else
{
	$smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}
?>
