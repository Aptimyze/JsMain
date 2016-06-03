<?php

include("connect.inc");
include_once("../profile/pg/functions.php");
$db=connect_slave81();
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


		$zonearr         = array('N','S','W-M','W-R','NRI');
		
		$sql="SELECT VALUE FROM incentive.BRANCH_CITY WHERE VALUE LIKE 'AP%' OR VALUE LIKE 'KA%' OR VALUE LIKE 'KE%' OR VALUE LIKE 'TN%' OR VALUE IN ('PO00')";
                $res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                while($row=mysql_fetch_array($res))
                {
                        $city_arr['S'][]=$row['VALUE'];
                }

		$sql="SELECT VALUE FROM incentive.BRANCH_CITY WHERE VALUE LIKE 'MH%' OR VALUE LIKE 'GO%'";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$city_arr['W-M'][]=$row['VALUE'];
		}

		$sql="SELECT VALUE FROM incentive.BRANCH_CITY WHERE VALUE LIKE 'GU%' OR (VALUE LIKE 'MP%' AND VALUE NOT IN ('MP05','MP06')) ";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$city_arr['W-R'][]=$row['VALUE'];
		}

		$sql="SELECT j.COUNTRY_RES,j.CITY_RES,SUM(if(b.TYPE='DOL',b.AMOUNT*b.DOL_CONV_RATE,b.AMOUNT)) AS AMOUNT,DAYOFMONTH(b.ENTRY_DT) as dd FROM billing.PAYMENT_DETAIL b, newjs.JPROFILE j,billing.PURCHASES c WHERE b.ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND b.STATUS = 'DONE' AND b.PROFILEID=j.PROFILEID AND b.BILLID = c.BILLID AND c.STATUS='DONE' GROUP BY dd,j.COUNTRY_RES,j.CITY_RES";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			do
			{
                		if(1)
                		{
					$amount=$row['AMOUNT'];
					$country_res=$row['COUNTRY_RES'];
					$city_res=$row['CITY_RES'];
					$dd=$row['dd']-1;

					if($country_res==51)
					{
						if(in_array($city_res,$city_arr['S']))
							$i=1;
						elseif(in_array($city_res,$city_arr['W-M']))
							$i=2;
						elseif(in_array($city_res,$city_arr['W-R']))
							$i=3;
						else
							$i=0;
					}
					else
					{
						$i=4;
					}
					
					$amt[$i][$dd]+=$amount;
					$tota[$i]+=$amount;
					$totb[$dd]+=$amount;
					$totall+=$amount;

					//added by sriram.
					//calculating net-off tax values for each day, each zone and total.
					$net_off_tax_zone[$i] = net_off_tax_calculation($tota[$i],$end_date);
					$net_off_tax_day[$dd] = net_off_tax_calculation($totb[$dd],$end_date);
					$net_off_tax_totall = net_off_tax_calculation($totall,$end_date);
				}
			}while($row=mysql_fetch_array($res));
		}
		if($mis_type=="XLS")
		{
			$header = "Zone / Day"."\t";
			for($i=0;$i<count($ddarr);$i++)
			{
				$header=$header.$ddarr[$i]."\t";
			}
			$header=$header."Total"."\t"."Total(Without-Tax)";
		
			for($i=0;$i<=4;$i++)
			{
				if($i==0)
					$data.="NORTH/EAST"."\t";
				elseif($i==1)
					$data.="SOUTH"."\t";
				elseif($i==2)
					$data.="WEST-Maharashtra"."\t";
				elseif($i==3)
					$data.="WEST-Guj/MP"."\t";
				elseif($i==4)
					$data.="NRI"."\t";
				for($j=0;$j<31;$j++)
				{
					$data.=	$amt[$i][$j]."\t";
				}
				$data.=$tota[$i]."\t".$net_off_tax_zone[$i]."\n";
			}	
			
			$data.="TOTAL"."\t";
			for($i=0;$i<=30;$i++)
			{
				$data.=$totb[$i]."\t";
			}
			$data.=$totall."\t".$net_off_tax_totall."\n";
			$data.="TOTAL(Without-Tax)"."\t";
                        for($i=0;$i<=30;$i++)
                        {
                                $data.=$net_off_tax_day[$i]."\t";
                        }
			$data.=$net_off_tax_totall."\t";
			$data = trim($data)."\t \n";

			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=crm-revenue-zonewise.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $final_data = $header."\n".$data;
			die();
		}
		
		if($brancharr)
			$smarty->assign("BRANCH","Y");

		$smarty->assign("cid",$cid);
		$smarty->assign("amt",$amt);
		$smarty->assign("tota",$tota);
		$smarty->assign("totb",$totb);
		$smarty->assign("totall",$totall);
		$smarty->assign("net_off_tax_zone",$net_off_tax_zone);
		$smarty->assign("net_off_tax_day",$net_off_tax_day);
		$smarty->assign("net_off_tax_totall",$net_off_tax_totall);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("zonearr",$zonearr);

		$smarty->display("crm_revenue_zonewise.htm");
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
                /*$privilage=getprivilage($cid);
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

                        //$smarty->assign("brancharr",$brancharr);
                }
                else
                {
                        // run query : select branch of user
                        $sql="SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME='$user'";
                        $res=mysql_query_decide($sql,$db) or die(mysql_error_js());
                        if($row=mysql_fetch_array($res))
                        {
                                $brancharr[]=$row['CENTER'];
                        }

                        //$smarty->assign("ONLYBRANCH","Y");
                        //$smarty->assign("branch",$branch);
                }
		$smarty->assign("brancharr",$brancharr);*/

                //$smarty->assign("priv",$priv);
                $smarty->assign("ddarr",$ddarr);
                $smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
                $smarty->assign("cid",$cid);
		$smarty->display("crm_revenue_zonewise.htm");
	}
}
else
{
	$smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}
?>
