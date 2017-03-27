<?php
include("connect.inc");
include_once("../profile/pg/functions.php");

$db=connect_misdb();

$data=authenticated($checksum);
$flag=0;

if(isset($data))
{
	if($CMDGo)
	{
		$flag=1;
		$yearp1=$year+1;
		$mmarr=array('Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar');

		$sql="SELECT sum(if(billing.PAYMENT_DETAIL.TYPE='DOL',billing.PAYMENT_DETAIL.AMOUNT*billing.PAYMENT_DETAIL.DOL_CONV_RATE,billing.PAYMENT_DETAIL.AMOUNT)) as amt,month(billing.PAYMENT_DETAIL.ENTRY_DT) as mm FROM billing.PAYMENT_DETAIL,billing.PURCHASES WHERE billing.PAYMENT_DETAIL.STATUS='REFUND' AND billing.PAYMENT_DETAIL.ENTRY_DT BETWEEN '$year-04-01 00:00:00' AND '$yearp1-03-31 23:59:59' AND billing.PURCHASES.BILLID=billing.PAYMENT_DETAIL.BILLID GROUP BY month(billing.PAYMENT_DETAIL.ENTRY_DT)";

		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			$tot=0;
			do
			{
				$mm=$row['mm'];
				if($mm<=3)
				{
					$mm+=8;
				}
				else
				{
					$mm-=4;
				}
				$amt[$mm]=$row['amt'];
				$tot+=$amt[$mm];
			}while($row=mysql_fetch_array($res));
		}
		$smarty->assign("year",$year);
		$smarty->assign("yearp1",$yearp1);
		$smarty->assign("flag",$flag);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("amt",$amt);
		$smarty->assign("tot",$tot);

		$smarty->display("refund_details.htm");
	}
	else
	{
		for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}
/*		$privilage=getprivilage($checksum);
		$priv=explode("+",$privilage);

		if(in_array('MA',$priv) || in_array('MB',$priv))
		{
			$smarty->assign("VIEWALL","Y");
			//run query : select all branches
			$sql="SELECT * FROM billing.BRANCHES";
			$res=mysql_query_decide($sql) or die(mysql_error_js());
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
			$res=mysql_query_decide($sql) or die(mysql_error_js());
			if($row=mysql_fetch_array($res))
			{ 
				$branch=$row['CENTER'];
			}
			$smarty->assign("branch",$branch);
		}
*/
		$smarty->assign("priv",$priv);
		$smarty->assign("flag","0");
//		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yyarr",$yyarr);
		$smarty->assign("CHECKSUM",$checksum);
		$smarty->display("refund_details.htm");
	}
}
else
{
        $smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}
?>
