<?php
include_once("connect.inc");
$db=connect_db();
if(authenticated($cid))
{
	$name = getname($cid);
	$date=date('Y-m-d');
	$festiveServices ="P1,P3,P4,P6,P12,PL,C3,C4,C6,C12,CL,NCP3,NCP4,NCP6,NCP12,NCPL,ESP3,ESP4,ESP6,ESP12,ESPL,D3,D4,D6,D12,DL,X3,X4,X6,X12";

	// get festival id
	$festivalId =trim($festivalId);

	// set expirt date
	if($day1 && $month1 && $year1){
		$expiryDt =$year1."-".$month1."-".$day1;
		if(strtotime($expiryDt)<strtotime($date)){
			$smarty->assign("errorDate",'Y');
			$submit='';
		}
		if((($month1=='04' || $month1=='06' || $month1=='09' || $month1=='11') && $day1>30) || ($month1==02 && $day1>28)){
			$smarty->assign("errorDate",'Y');
			$submit='';
		}
	}
	elseif(!$day1 && !$month1 && !$year1)
		$expiryDt =date("Y-m-d",strtotime("$date +6 Days"));
	else{
		$smarty->assign("errorDate",'Y');
		$submit='';	
	}	

	if($submit){
			
		// set active/deactive status	
		if($submit=='Deactivate'){
			$status='Inactive';
			$lastActiveServices =getLastOfferDetails();		
			$serviceArr =explode(",",$lastActiveServices['LAST_ACTIVE_SERVICES']);
			$lastId =$lastActiveServices['ID'];
			$sql ="update billing.FESTIVE_LOG_REVAMP SET STATUS='$status',END_DT='$date',DE_ACTIVATION_DT=now() where ID='$lastId'";
		}
		else{
			$status='Active';
			$serviceArr =@explode(",",$festiveServices);
			$last_active_services   =getActiveServices();
			$last_active_servicesStr =@implode(",",$last_active_services);
			list($services_str, $offered_services_str) = getFestiveServicesMapping();
			$sql="INSERT INTO billing.FESTIVE_LOG_REVAMP (`EXECUTIVE`,`STATUS`,`START_DT`,`END_DT`,`ACTIVATION_DT`,`FESTIVAL`,`LAST_ACTIVE_SERVICES`,`SERVICES`,`OFFERED_SERVICES`) VALUES ('$name','$status','$date','$expiryDt',now(),'$festivalId','$last_active_servicesStr','$services_str','$offered_services_str')";
		}
		$res=mysql_query($sql) or die(mysql_error());
		activateServices($serviceArr);
		unset($serviceArr);

		// function to flush memcache
		flush_memcache_ForMembership();	
		$memHandlerObj = new MembershipHandler(false);
        $memHandlerObj->flushMemcacheForMembership();
        unset($memHandlerObj);
	}

	// Normal execution code	
	$lastStatus =getLastOfferDetails();
	$festivalArr =fetchFestivals();
	list($curyear,$curmonth,$curday) = explode("-",$date);

	$status=$lastStatus['STATUS'];
	if($status=='Active'){
		$action="Deactivate";
		$selFestiveId =$lastStatus['FESTIVAL'];
		$endDateArr =$lastStatus['END_DT'];
		list($year1,$month1,$day1) =explode("-",$endDateArr);
	        $smarty->assign("curday",$day1);
	        $smarty->assign("curmonth",$month1);
	        $smarty->assign("curyear",$year1);
	}
	else
		$action="Activate";
	$smarty->assign('festivalArr',$festivalArr);
	$smarty->assign('selFestiveId',$selFestiveId);
	$smarty->assign('cid',$cid);
	$smarty->assign('status',$status);
	$smarty->assign('action',$action);
	$smarty->assign('name',$name);

	$month_arr = array(
			array("NAME" => "January", "VALUE" => "01"),
			array("NAME" => "February", "VALUE" => "02"),
			array("NAME" => "March", "VALUE" => "03"),
			array("NAME" => "April", "VALUE" => "04"),
			array("NAME" => "May", "VALUE" => "05"),
			array("NAME" => "June", "VALUE" => "06"),
			array("NAME" => "July", "VALUE" => "07"),
			array("NAME" => "August", "VALUE" => "08"),
			array("NAME" => "September", "VALUE" => "09"),
			array("NAME" => "October", "VALUE" => "10"),
			array("NAME" => "November", "VALUE" => "11"),
			array("NAME" => "December", "VALUE" => "12"),
			);
	for($i=1;$i<=31;$i++){
		if($i<10)
			$i ="0".$i;
		$ddarr[] = $i;
	}
	for($i=0;$i<12;$i++)
		$mmarr[] = $month_arr[$i];
        for($i=$curyear;$i<=$curyear+4;$i++)
        	$yyarr[] = $i;
	$smarty->assign("mmarr",$mmarr);
	$smarty->assign("yyarr",$yyarr);
	$smarty->assign("ddarr",$ddarr);

	$smarty->display("change_festive.htm");
}

function fetchFestivals()
{
	$sql ="select ID,FESTIVAL from billing.FESTIVE_BANNER";	
	$res=mysql_query_decide($sql) or die(mysql_error_js());
	while($row=mysql_fetch_array($res)){
		$id =$row['ID'];
		$festivalArr[$id] =$row['FESTIVAL'];			
	}
	return $festivalArr;
}
function getActiveServices()
{
	//$sql ="select SERVICEID from billing.SERVICES where SHOW_ONLINE='Y'";
	$sql ="select SERVICEID from billing.SERVICES where SHOW_ONLINE_NEW NOT LIKE '' AND ACTIVE='Y' AND ADDON='N'";
	$res=mysql_query_decide($sql) or die(mysql_error_js());
	while($row=mysql_fetch_array($res)){
		$serviceArr[] =$row['SERVICEID'];
	}				 
	return $serviceArr;	
}
function activateServices($serviceArr)
{
	$serviceStr ="'".@implode("','",$serviceArr)."'";

	//$sql ="update billing.SERVICES SET SHOW_ONLINE='N'";
	/*$sql ="update billing.SERVICES SET SHOW_ONLINE='N' WHERE ADDON='N' AND ACTIVE='Y'";
	mysql_query_decide($sql) or die(mysql_error_js());*/

	$sql1 ="UPDATE billing.SERVICES SET SHOW_ONLINE_NEW=CASE WHEN SHOW_ONLINE_NEW = '' THEN ',-1,' ELSE SHOW_ONLINE_NEW = CONCAT(SHOW_ONLINE_NEW,'-1,') END where SERVICEID IN($serviceStr)";
	mysql_query_decide($sql1) or die(mysql_error_js());
}
function getLastOfferDetails()
{
	$sql ="select * from billing.FESTIVE_LOG_REVAMP ORDER BY ID DESC limit 1";
        $res=mysql_query_decide($sql) or die(mysql_error_js());
        if($row=mysql_fetch_array($res))
		return $row;
        return;
}
function getFestiveServicesMapping()
{
	$sql ="SELECT `SERVICEID`, `OFFERED_SERVICEID` FROM billing.`FESTIVE_OFFER_LOOKUP` WHERE `SERVICEID` <> `OFFERED_SERVICEID`";
	$res=mysql_query_decide($sql) or die(mysql_error_js());

	$services = array();
	$offered_services = array();

	while($row=mysql_fetch_array($res)){
		$services[] =$row['SERVICEID'];
		$offered_services[] =$row['OFFERED_SERVICEID'];
	}				 
	$services = implode(',', $services);
	$offered_services = implode(',', $offered_services);
	return array($services, $offered_services);	
}

?>
