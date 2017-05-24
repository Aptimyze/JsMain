<?php

/*********************************************************************************************
* FILE NAME   : billentry.php
* DESCRIPTION : Display the list of usernames for whom billing entry has to be made
* MODIFY DATE : 3 May, 2005
* MODIFIED BY        : Rahul Tara
* MODIFY REASON      : Pass the ID in incentive.PAYMENT_COLLECT as parameter in the bill entry
*                      for billing entry purpose
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/


include("connect.inc");
if(authenticated($cid))
{
	$name= getname($cid);
	$centre_label=get_centre($cid);
	if($centre_label!="HO")
	{	
		$sql="SELECT VALUE from BRANCH_CITY where UPPER(LABEL) ='".strtoupper($centre_label)."'";
		$myrow=mysql_fetch_array(mysql_query_decide($sql));
		$centre=$myrow['VALUE'];
	}
	else
		$centre="HO";
//	if($centre=="HO")
//                $smarty->assign("showlink","Y");

        if($privilage = getprivilage($cid))
        {
                $priv_arr = explode("+",$privilage);
                if(is_array($priv_arr))
                {
                        if(in_array('PSA',$priv_arr))
                                $smarty->assign("showlink","Y");
                }
        }


		$i=1;	
		$sql="SELECT AR_BRANCH FROM ARAMEX_BRANCHES WHERE ACTIVATION_BRANCH='$centre'";		         $result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($myrow=mysql_fetch_array($result))
		{	
			$ar_branch[]=$myrow['AR_BRANCH'];
		}
		if(count($ar_branch)>0)
			$ar=implode("','",$ar_branch);
		if($showall=="Y")
			$sql="SELECT PAYMENT_COLLECT.ID AS ID,PROFILEID,USERNAME,STATUS,ENTRY_DT,ADDON_SERVICEID,DISCOUNT,SERVICES.NAME as SERVICE from incentive.PAYMENT_COLLECT,billing.SERVICES,incentive.BRANCH_CITY where CONFIRM='Y' and AR_GIVEN='Y' and STATUS in ('C','S') and DISPLAY <> 'N' and PAYMENT_COLLECT.SERVICE=SERVICES.SERVICEID and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE AND BILLING=''";		
                else
			$sql="SELECT PAYMENT_COLLECT.ID AS ID,PROFILEID,USERNAME,STATUS,ENTRY_DT,ADDON_SERVICEID,DISCOUNT,SERVICES.NAME as SERVICE from incentive.PAYMENT_COLLECT,billing.SERVICES,incentive.BRANCH_CITY where CONFIRM='Y' and AR_GIVEN='Y' and STATUS in ('C','S') and DISPLAY <> 'N' and  PAYMENT_COLLECT.CITY IN ('$ar') and PAYMENT_COLLECT.SERVICE=SERVICES.SERVICEID and PAYMENT_COLLECT.CITY=BRANCH_CITY.VALUE AND BILLING=''";		

		$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($myrow=mysql_fetch_array($result))
		{

			$services[] = $myrow["SERVICE"];
			if($myrow["ADDON_SERVICEID"])
			{
				$addon_serviceid = $myrow["ADDON_SERVICEID"];
				$addon_serviceid_ar = explode(",",$addon_serviceid);
                                for($j=0;$j<count($addon_serviceid_ar);$j++)
                                        $addon_serviceid_ar[$j]="'".$addon_serviceid_ar[$j]."'";
                                $addon_serviceid_str = implode(",",$addon_serviceid_ar);

                                $sql = "Select NAME from billing.SERVICES where SERVICEID in ($addon_serviceid_str)";
                                $result_services = mysql_query_decide($sql) or die(mysql_error_js());
                                while($myrow_result_services = mysql_fetch_array($result_services))
                                {
                                        $services[] = "<br>".$myrow_result_services["NAME"];

                                }
                        }

                        $service_names = implode(",",$services);


			$sql="SELECT ENTRYBY from incentive.LOG where PROFILEID='$myrow[PROFILEID]' and CONFIRM='Y' and AR_GIVEN=''";	
			$result1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$myrow1=mysql_fetch_array($result1);
			$entryby=$myrow1['ENTRYBY'];
				
			if($myrow["STATUS"]=='C')
				$status="Payment Collected";
			elseif($myrow["STATUS"]=='S')
				$status="Payment Collected and Service started";	
			$address=$myrow["ADDRESS"]." ".$myrow["CITY"]."-".$myrow["PIN"];
			$values[] = array("sno"=>$i,
					  "id"=>$myrow["ID"],	
					  "profileid"=>$myrow["PROFILEID"],
					  "username"=>$myrow["USERNAME"],
					  "service"=>$service_names,
					  "status"=>$status,
					  "DISCOUNT"=>$myrow["DISCOUNT"],	
					  "date"=>$myrow["ENTRY_DT"],
					  "entryby"=>$entryby,
					 );
			$i++;
			unset($services);
		}

		$smarty->assign("ROW",$values);
		if($showall=="Y")
                        $smarty->assign("showall","Y");
		$smarty->assign("name",$name);
		$smarty->assign("cid",$cid);
		$smarty->display("billentry.htm");
}
else
{
	$msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");

}

?>
