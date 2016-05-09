<?
/**
*       Filename        :       voucher_issued_detail.php
*       Description     :       Script to show Dispatched Vouchers.
*       Created by      :       Tanu Gupta
*       Created on      :       10-02-2007
**/

include("connect.inc");
include ("../crm/display_result.inc");

if(authenticated($cid))
{
        $privilage=getprivilage($cid);
        $priv=explode("+",$privilage);
        if(in_array('VU',$priv) || in_array('VA',$priv))
        {
                $PAGELEN=25;
                $LINKNO=5;
                if (!$j )
                        $j = 0;

		$sql="SELECT * FROM billing.VOUCHER_CLIENTS WHERE SERVICE='Y'";
		$res=mysql_query_decide($sql);
		$i=0;
		while($row=mysql_fetch_array($res))
		{
			$clientid[$i]=$row['CLIENTID'];
			$client_name[$i]=$row['CLIENT_NAME'];
			$i++;
		}
		$smarty->assign("client_name",$client_name);
		$smarty->assign("clientid",$clientid);

		$sql="SELECT PROFILEID,OPTIONS_AVAILABLE,NAME,CONTACT,CITY_RES,PHONE_RES,PHONE_MOB FROM billing.VOUCHER_OPTIN WHERE DISPATCHED='Y' LIMIT $j,$PAGELEN";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		$i=0;$k=$j;
		while($row=mysql_fetch_array($res))
		{
			$profileid=$row['PROFILEID'];
			$options=explode(',',$row['OPTIONS_AVAILABLE']);
			for($p=0;$p<count($clientid);$p++)
			{
				if(in_array($clientid[$p],$options))
				{

					$sql_voucher="SELECT VOUCHER_NO,TYPE FROM billing.VOUCHER_NUMBER WHERE CLIENTID='$clientid[$p]' AND PROFILEID='$profileid'";
					$res_voucher=mysql_query_decide($sql_voucher);
					$row_voucher=mysql_fetch_array($res_voucher);

					$voucher[$i][$p]['VOUCHER_NO']=$row_voucher['VOUCHER_NO'];
					$voucher[$i][$p]['TYPE']=$row_voucher['TYPE'];
				}
			}

			$sql_contact="SELECT USERNAME,EMAIL FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
			$res_contact=mysql_query_decide($sql_contact);
			$row_contact=mysql_fetch_array($res_contact);
			$voucher[$i]['SNO']=$k+1;
			$voucher[$i]['PROFILEID']=$profileid;
			$voucher[$i]['USERNAME']=$row_contact['USERNAME'];
			$voucher[$i]['EMAIL']=$row_contact['EMAIL'];
			$voucher[$i]['NAME']=$row['NAME'];
			$voucher[$i]['CONTACT']=$row['CONTACT'];
			$voucher[$i]['CITY_RES']=$row['CITY_RES'];
			$voucher[$i]['PHONE_RES']=$row['PHONE_RES'];
			$voucher[$i]['PHONE_MOB']=$row['PHONE_MOB'];
			$i++;$k++;
		}

		$smarty->assign("voucher",$voucher);
		$smarty->assign("cid",$cid);

		if( $j )
			$cPage = ($j/$PAGELEN) + 1;
		else
			$cPage = 1;

		$sql="SELECT COUNT(*) cnt FROM billing.VOUCHER_OPTIN WHERE DISPATCHED='Y'";
		$res=mysql_query_decide($sql);
		$row=mysql_fetch_array($res);

		$TOTALREC=$row['cnt'];
		pagelink($PAGELEN,$TOTALREC,$cPage,$LINKNO,$cid,"voucher_issued_detail.php");
		$smarty->display("voucher_issued_detail.htm");
        }
        else
        {
                echo "You don't have permission to view this mis";
                die();
        }
}

else
{
        $smarty->display("jsconnectError.tpl");
}
?>

