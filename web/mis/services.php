<?php
include("connect.inc");
$db=connect_misdb();
$db2=connect_master();

$data=authenticated($checksum);
$flag=0;

if(isset($data))
{
	if($CMDGo)
	{
		$flag=1;
		$yearp1=$year+1;
		$mmarr=array('Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar');
		$ssarr=array('Full Member : 3 Months','Full Member : 6 Months','Full Member : 12 Months','Value Added Member : 3 Months','Value Added Member : 6 Months','Value Added Member : 12 Months');

//		$sql="SELECT NAME FROM :";

//		if($branch!="ALL")
//		{
//			$bflag='N';
//			$sql="SELECT sum(if(billing.PAYMENT_DETAIL.TYPE='DOL',billing.PAYMENT_DETAIL.AMOUNT*45,billing.PAYMENT_DETAIL.AMOUNT)) as amt,month(billing.PAYMENT_DETAIL.ENTRY_DT) as mm FROM billing.PAYMENT_DETAIL,billing.PURCHASES WHERE billing.PAYMENT_DETAIL.STATUS='REFUND' AND billing.PAYMENT_DETAIL.ENTRY_DT BETWEEN '$year-04-01' AND '$yearp1-03-31' AND billing.PURCHASES.CENTER='$branch' AND billing.PURCHASES.BILLID=billing.PAYMENT_DETAIL.BILLID GROUP BY month(billing.PAYMENT_DETAIL.ENTRY_DT)";

			$sql="SELECT COUNT(*) as cnt,SERVICEID as sid,month(ENTRY_DT) as mm FROM billing.PURCHASES WHERE STATUS='DONE' AND ENTRY_DT BETWEEN '$year-04-01 00:00:00' AND '$yearp1-03-31 23:59:59' GROUP BY sid,mm";
//		}
//		else
//		{
//			$bflag='A';
  //                      $sql_b="SELECT NAME FROM billing.BRANCHES";
    //                    $res_b=mysql_query_decide($sql_b) or die(mysql_error_js());
      //                  while($row_b=mysql_fetch_array($res_b))
        //                {
          //                      $brancharr[]=$row_b['NAME'];
            //            }

//                        $sql="SELECT sum(if(billing.PAYMENT_DETAIL.TYPE='DOL',billing.PAYMENT_DETAIL.AMOUNT*45,billing.PAYMENT_DETAIL.AMOUNT)) as amt,month(billing.PAYMENT_DETAIL.ENTRY_DT) as mm,billing.PURCHASES.CENTER as center FROM billing.PAYMENT_DETAIL,billing.PURCHASES WHERE billing.PAYMENT_DETAIL.STATUS='REFUND' AND billing.PAYMENT_DETAIL.ENTRY_DT BETWEEN '$year-04-01' AND '$yearp1-03-31' AND billing.PURCHASES.BILLID=billing.PAYMENT_DETAIL.BILLID GROUP BY month(billing.PAYMENT_DETAIL.ENTRY_DT)";

	//		$sql="";
	//	}
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
//			$i=0;
//			$tot=0;
			do
			{
				$sid=$row['sid'];
				$sname=getsname($sid);
				$i=array_search($sname,$ssarr);
				$mm=$row['mm'];
				if($mm<=3)
				{
					$mm+=8;
				}
				else
				{
					$mm-=4;
				}
//				if($branch!="ALL")
//				{
					$tot[$i][$mm]=$row['cnt'];
					$tot1[$mm][$i]=$row['cnt'];
					$tota[$i]+=$tot[$i][$mm];
					$totb[$mm]+=$tot1[$mm][$i];
//				}
//				else
//				{
//					$center=$row['center'];
//					$i=array_search($center,$brancharr);
//					$amt[$i][$mm]=$row['amt'];
//					$totb[$i]+=$amt[$i][$mm];
//					$totm[$i]+=$amt[$mm][$i];
//				}
			}while($row=mysql_fetch_array($res));
		}
		$smarty->assign("year",$year);
		$smarty->assign("yearp1",$yearp1);
		$smarty->assign("flag",$flag);
//		$smarty->assign("bflag",$bflag);
		$smarty->assign("ssarr",$ssarr);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("tot",$tot);
		$smarty->assign("tota",$tota);
		$smarty->assign("totb",$totb);

		$smarty->display("services.htm");
	}
	else
	{
		$user=getname($checksum);
		for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}
		$privilage=getprivilage($checksum);
		$priv=explode("+",$privilage);

		if(in_array('MA',$priv) || in_array('MB',$priv))
		{
			$smarty->assign("VIEWALL","Y");
			//run query : select all branches
			$sql="SELECT * FROM billing.BRANCHES";
			$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
			if($row=mysql_fetch_array($res))
			{
				$i=0;
				do
				{
					$branch[$i]["id"]=$row['ID'];
					$branch[$i]["name"]=$row['NAME'];

					$i++;
				}while($row=mysql_fetch_array($res));
			}
			$smarty->assign("branch",$branch);
		}
		elseif(in_array('MC',$priv) || in_array('MD',$priv))
		{
			// run query : select branch of user
			$sql="SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME='$user'";
			$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
			if($row=mysql_fetch_array($res))
			{ 
				$branch=$row['CENTER'];
			}
			$smarty->assign("branch",$branch);
		}

		$smarty->assign("priv",$priv);
		$smarty->assign("flag",$flag);
//		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yyarr",$yyarr);
		$smarty->assign("CHECKSUM",$checksum);
		$smarty->display("services.htm");
	}
}
else
{
        $smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}

function getsname($sid)
{
	switch($sid)
	{
	case 'S1' : $sname="Full Member : 3 Months";
			break;
	case 'S2' : $sname="Full Member : 6 Months";
			break;
	case 'S3' : $sname="Full Member : 12 Months";
			break;
	case 'S4' : $sname="Value Added Member : 3 Months";
			break;
	case 'S5' : $sname="Value Added Member : 6 Months";
			break;
	case 'S6' : $sname="Value Added Member : 12 Months";
			break;
	}

	return $sname;
}
?>
