<?
/**
*       Filename        :       voucher_issued.php
*       Description     :       Script to issue the printed Vouchers.
*       Created by      :       Tanu Gupta
*       Created on      :       10-02-2007
**/
include("connect.inc");
include ("../crm/display_result.inc");
if(authenticated($cid))
{
        $PAGELEN=25;
	$LINKNO=5;
	if(!$j)
		$j=0;
	$privilage=getprivilage($cid);
        $priv=explode("+",$privilage);
        if(in_array('VA',$priv) or in_array('VU',$priv))
        {
		$ts=time();
		$today=date('Y-m-d G:i:s',$ts);
		if($Dispatch)
		{
			$sql="UPDATE billing.VOUCHER_NUMBER SET ISSUE_DATE='$today' WHERE PROFILEID='$profileid' AND TYPE='P'";
			//mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$sql="UPDATE billing.VOUCHER_OPTIN SET DISPATCHED='Y' WHERE PROFILEID='$profileid'";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());
			echo "<html><body>Vouchers are successfully marked as dispatched for user <font color=blue>$user</font>.</body></html>";	
		}
		else
		{
			$clientid[]="TAN93";
			$client_name[]="Tanishq";
			$sql="SELECT * FROM billing.VOUCHER_CLIENTS WHERE SERVICE='Y' AND TYPE='P'";
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

			if($searchchecksum)
				$city_filter=$searchchecksum;

			if(is_array($clientid))
			{
				$sql="SELECT PROFILEID,OPTIONS_AVAILABLE,NAME,CONTACT,CITY_RES,PHONE_RES,PHONE_MOB FROM billing.VOUCHER_OPTIN WHERE DISPATCHED!='Y'";

				if($city_filter)
				$sql.=" AND CITY_RES='$city_filter'";
				elseif($city_filter_val)
				$sql.=" AND CITY_RES='$city_filter_val'";

				$sql.=" AND (";
				for($i=0;$i<count($clientid);$i++)
				{
					$sql.=" OPTIONS_AVAILABLE REGEXP '$clientid[$i]' OR";
				}
				$sql=substr($sql,0,strlen($sql)- 3);
				$sql.=") LIMIT $j,$PAGELEN";
			
				$res=mysql_query_decide($sql) or die(mysql_error_js());
				$i=0;
				while($row=mysql_fetch_array($res))
				{
					$profileid=$row['PROFILEID'];
					$options=explode(',',$row['OPTIONS_AVAILABLE']);
					for($k=0;$k<count($clientid);$k++)
					{
						if(in_array($clientid[$k],$options))
							$voucher[$i][$k]='Y';
					}
					$sql_contact="SELECT USERNAME,EMAIL FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
					$res_contact=mysql_query_decide($sql_contact);
					$row_contact=mysql_fetch_array($res_contact);
					$voucher[$i]['PROFILEID']=$profileid;
					$voucher[$i]['USERNAME']=$row_contact['USERNAME'];
					$voucher[$i]['EMAIL']=$row_contact['EMAIL'];
					$voucher[$i]['NAME']=$row['NAME'];
					$sql_city = "select SQL_CACHE LABEL from newjs.CITY_NEW WHERE VALUE='$row[CITY_RES]'";
					$res_city = mysql_query_decide($sql_city) or logError("error",$sql) ;
					$row_city= mysql_fetch_array($res_city);
					$voucher[$i]['CONTACT']=$row['CONTACT'];
					$voucher[$i]['CITY_RES']=$row_city['LABEL'];
					$voucher[$i]['PHONE_RES']=$row['PHONE_RES'];
					$voucher[$i]['PHONE_MOB']=$row['PHONE_MOB'];		
					$i++;
				}	
			}
			//print_r($voucher);
			if( $j )
			$cPage = ($j/$PAGELEN) + 1;                
			else
			$cPage = 1;
			if(is_array($clientid))
			{
				$sql="SELECT COUNT(*) AS COUNT FROM billing.VOUCHER_OPTIN WHERE (";
				for($i=0;$i<count($clientid);$i++)
				{
					$sql.=" OPTIONS_AVAILABLE REGEXP '$clientid[$i]' OR";
				}
				$sql=substr($sql,0,strlen($sql)- 3);
				$sql.=") AND DISPATCHED!='Y'";
				if($city_filter)
                                $sql.=" AND CITY_RES='$city_filter'";
                                elseif($city_filter_val)
                                $sql.=" AND CITY_RES='$city_filter_val'";
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				$row=mysql_fetch_assoc($res);
				$TOTALREC=$row["COUNT"];
			}
			else
			$TOTALREC=0;
			pagelink($PAGELEN,$TOTALREC,$cPage,$LINKNO,$cid,"voucher_issued.php");//For Pagination

			$smarty->assign("voucher",$voucher);
			$smarty->assign("cid",$cid);
			if(!in_array('VA',$priv))
			{
				$smarty->assign("no_admin",1);
			}
			$i=0;
			$sql="SELECT SQL_CACHE VALUE, LABEL FROM newjs.CITY_NEW WHERE COUNTRY_VALUE = 51 ORDER BY SORTBY";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$cityarr[$i]['VAL']=$row['VALUE'];
				$cityarr[$i]['LAB']=$row['LABEL'];
				$i++;
			}
			$smarty->assign("city_filter",$city_filter);
			$smarty->assign("cityarr",$cityarr);
			$smarty->display("voucher_issued.htm");
		}
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
