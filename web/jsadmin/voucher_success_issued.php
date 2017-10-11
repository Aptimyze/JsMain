<?
/**
*       Filename        :       voucher_success_issued.php
*       Description     :       Script to issue the printed Vouchers for Success Stories.
*       Created by      :       Tanu Gupta
*       Created on      :       19-03-2007
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
		if($CMDDispatch)
		{
			if(count($v_id))
			{
				if($CMDDispatch)
				{	
					for($i=0;$i<count($v_id);$i++)
						$id_str.="'".$v_id[$i]."',";
					if($id_str)
					{
						$id_str=substr($id_str,0,strlen($id_str)-1);
						$sql="UPDATE billing.VOUCHER_SUCCESSSTORY SET SELECTED='D' WHERE ID IN($id_str)";
						mysql_query_decide($sql) or die(mysql_error_js());
						$smarty->assign("msg","Users are successfully marked as dispatched");
					}

				}
			}
			else
				$smarty->assign("msg","Please select atleast one user");
		}			
		if($Dispatch)
		{
			$sql="UPDATE billing.VOUCHER_SUCCESSSTORY SET SELECTED='D' WHERE ID='$id'";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());
			echo "<html><body><font color=red>$user has been marked as dispatched</font></body></html>";
		}
		else
		{
	
		/*	$sql="SELECT * FROM billing.VOUCHER_CLIENTS WHERE SERVICE='Y' AND TYPE='P'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$i=0;
			while($row=mysql_fetch_array($res))
			{
				$clientid[$i]=$row['CLIENTID'];
				$client_name[$i]=$row['CLIENT_NAME'];
				$i++;
			}
			$smarty->assign("client_name",$client_name);
			$smarty->assign("clientid",$clientid);*/
			if($searchchecksum)
				$city_filter=$searchchecksum;
	/*		if(is_array($clientid))
			{*/

				$sql="SELECT ID,PROFILEID,EMAIL,USERNAME_H,USERNAME_W,OPTIONS_AVAILABLE,NAME_H,NAME_W,CONTACT,CITY_RES,PHONE_RES,PHONE_MOB,STORYID FROM billing.VOUCHER_SUCCESSSTORY WHERE SELECTED NOT IN ('D','N')";
				if($city_filter)
				{
					$sql.=" AND CITY_RES='$city_filter'";
				}

				$sql.= " GROUP BY PROFILEID LIMIT $j,$PAGELEN";
				/*if(is_array($clientid))
				{
					$sql.=" AND (";
					for($i=0;$i<count($clientid);$i++)
					{
						$sql.=" OPTIONS_AVAILABLE REGEXP '$clientid[$i]' OR";
					}
					$sql=substr($sql,0,strlen($sql)- 3);
					$sql.=") LIMIT $j,$PAGELEN";
				}*/
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
					$voucher[$i]['ID']=$row['ID'];
					if($row['USERNAME_H'])
					$voucher[$i]['USERNAME']=$row['USERNAME_H'];
					elseif($row['USERNAME_W'])
					$voucher[$i]['USERNAME']=$row['USERNAME_W'];
					else
					$voucher[$i]['USERNAME']='';
									
					if($row['NAME_H'])
					$voucher[$i]['NAME']=$row['NAME_H'];
					elseif($row['NAME_W'])
					$voucher[$i]['NAME']=$row['NAME_W'];
					else
					$voucher[$i]['NAME']='';
				
					$voucher[$i]['EMAIL']=$row['EMAIL'];
					
					$sql="select USERNAME,GENDER from newjs.JPROFILE where PROFILEID=$profileid";

					$res_jp=mysql_query_decide($sql);
					if($row_jp=mysql_fetch_array($res_jp))
					{
						$voucher[$i]['USERNAME']=$row_jp['USERNAME'];

						if($row_jp["GENDER"]=='F')
							$voucher[$i]['NAME']=$row['NAME_W'];
						else 
							$voucher[$i]['NAME']=$row['NAME_H'];
						
					}
					if($row['CITY_RES'])
					{
						$sql_city = "select SQL_CACHE LABEL from newjs.CITY_NEW WHERE VALUE='$row[CITY_RES]'";
						$res_city = mysql_query_decide($sql_city) or logError("error",$sql) ;
						$row_city= mysql_fetch_array($res_city);
						$voucher[$i]['CITY_RES']=$row_city['LABEL'];
					}
					$voucher[$i]['CONTACT']=$row['CONTACT'];
					$voucher[$i]['PHONE_RES']=$row['PHONE_RES'];
					$voucher[$i]['PHONE_MOB']=$row['PHONE_MOB'];		
					$i++;
				}
			//}	
		//print_r($voucher);
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
			if( $j )
                        $cPage = ($j/$PAGELEN) + 1;
                        else
                        $cPage = 1;
			//if(is_array($clientid))
			//{
				$sql="SELECT COUNT(DISTINCT (PROFILEID)) AS COUNT FROM billing.VOUCHER_SUCCESSSTORY WHERE SELECTED NOT IN ('D','N')";
				if($city_filter)
                                {
                                        $sql.=" AND CITY_RES='$city_filter'";
                                }
/*
				for($i=0;$i<count($clientid);$i++)
				{
					$sql.=" OPTIONS_AVAILABLE REGEXP '$clientid[$i]' OR";
				}
				$sql=substr($sql,0,strlen($sql)- 3);
				$sql.=") AND SELECTED='Y'";*/
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				$row=mysql_fetch_assoc($res);
				$TOTALREC=$row["COUNT"];
			//}
			//else
			//$TOTALREC=0;echo $PAGELEN."*".$TOTALREC."*".$cPage."*".$LINKNO;	
                        pagelink($PAGELEN,$TOTALREC,$cPage,$LINKNO,$cid,"voucher_success_issued.php",$city_filter);//For Pagination

			$smarty->assign("city_filter",$city_filter);
			$smarty->assign("cityarr",$cityarr);
			$smarty->assign("CID",$cid);
			$smarty->display("voucher_success_issued.htm");
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
