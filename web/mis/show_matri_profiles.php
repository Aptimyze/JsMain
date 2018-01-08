<?php
header('Location: ../jsadmin/show_matriprofile.php?checksum='.$checksum);
die();
include("../mis/connect.inc"); 

$db2=connect_master();

if(authenticated($checksum))
{
	$user=getname($checksum);
	$privilage=getprivilage($checksum);
	$priv=explode("+",$privilage);
	if(in_array('MPU',$priv))
	{
		$smarty->assign("flag","1");

	//	$sql = "SELECT PROFILEID, USERNAME, SERVICEID, ADDON_SERVICEID , ENTRY_DT FROM billing.PURCHASES where STATUS = 'DONE' AND (SERVICEID = 'M' OR ADDON_SERVICEID REGEXP 'M' ) ORDER BY ENTRY_DT ASC";
//		$sql = "SELECT a.PROFILEID, a.USERNAME,  ENTRY_DT FROM billing.PURCHASES as a left join billing.UPLOAD_MATRI_STATUS as b  ON a.PROFILEID=b.PROFILEID WHERE b.PROFILEID is NULL  AND a.STATUS = 'DONE' AND (SERVICEID = 'M' OR ADDON_SERVICEID REGEXP 'M' ) ORDER BY ENTRY_DT ASC";
		$sql = "SELECT a.PROFILEID, a.USERNAME,  ENTRY_DT FROM billing.PURCHASES as a left join billing.UPLOAD_MATRI_STATUS as b  ON a.PROFILEID=b.PROFILEID WHERE b.PROFILEID is NULL  AND a.STATUS = 'DONE' AND (SERVICEID LIKE '%M%' OR ADDON_SERVICEID REGEXP 'M' ) ORDER BY ENTRY_DT ASC";
		$res=mysql_query_decide($sql,$db2) or die("$sql".mysql_error_js());
                if($row=mysql_fetch_array($res))
                {
                        $i=0;
                        do
                        {

                                $arr[$i]['SNO']=$i+1;
                                $arr[$i]['PROFILEID']=$row['PROFILEID'];
				$arr[$i]['USERNAME']=$row['USERNAME'];
				$arr[$i]['ENTRY_DT']=$row['ENTRY_DT'];
				$arr[$i]['PROFILEID']=$row['PROFILEID']; 
				$arr[$i]['USERNAME']=$row['USERNAME'];


				$sql_det = "Select EMAIL,PHONE_MOB,PHONE_RES from newjs.JPROFILE where PROFILEID=$row[PROFILEID]";
				$result_det = mysql_query_decide($sql_det) or die(mysql_error_js());
				$myrow_det = mysql_fetch_array($result_det);
                                $arr[$i]['EMAIL']=$myrow_det['EMAIL'];
                                $arr[$i]['PHONE_MOB']=$myrow_det['PHONE_MOB'];
                                $arr[$i]['PHONE_RES']=$myrow_det['PHONE_RES'];
				

				$i++;
                        }while($row=mysql_fetch_array($res));
		}
		$smarty->assign("arr",$arr);
                $smarty->assign("checksum",$checksum);
		$smarty->display("show_matri_profiles.htm");
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
