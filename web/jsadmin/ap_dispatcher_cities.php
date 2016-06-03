<?php
include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");

$db=connect_db();

if(authenticated($cid))
{
	if($assignCities)
	{
		$updated=0;
		if(is_array($city))
		{
			foreach($city as $key=>$value)
			{
				if(!in_array($value,$myCities))
					$insert[]=$value;
				$updated=1;
			}
			foreach($myCities as $key=>$value)
			{
				if(!in_array($value,$city))	
					$remove[]=$value;
				$updated=1;
			}
		}
		elseif($myCities)
			$remove=$myCities;
		if(is_array($remove))
		{
			$removeCities=implode("','",$remove);
			$sql="DELETE FROM Assisted_Product.AP_DISPATCHER_CITIES WHERE DISPATCHER='$dispatcher' AND CITY IN('$removeCities')";
			mysql_query_decide($sql) or die("Error while deleting cities  ".$sql."   ".mysql_error_js());
		}
		if(is_array($insert))
		{
			foreach($insert as $key=>$value)
				$insertString.="('$dispatcher','$value'),";
			$insertString=trim($insertString,",");
			$sql="INSERT INTO Assisted_Product.AP_DISPATCHER_CITIES(DISPATCHER,CITY) VALUES$insertString";
			mysql_query_decide($sql) or die("Error while inserting cities   ".$sql."   ".mysql_error_js());
		}
		if($updated)
			$smarty->assign("successMessage","Cities successfully edited for $dispatcher");
	}
	if($selectDispatcher)
	{
		$myCities=array();
		$sql="SELECT CITY FROM Assisted_Product.AP_DISPATCHER_CITIES WHERE DISPATCHER='$dispatcher'";
		$res=mysql_query_decide($sql) or die("Error while fetching dispatcher cities   ".$sql."   ".mysql_error_js());
		if(mysql_num_rows($res))
		{
			while($row=mysql_fetch_assoc($res))
				$myCities[]=$row["CITY"];
		}
		$cityOptions='';
		global $CITY_INDIA_DROP;
		foreach($CITY_INDIA_DROP as $key=>$value)
		{
			if(in_array($key,$myCities))
				$cityOptions.="<option value=\"$key\" selected>$value</option>";
			else
				$cityOptions.="<option value=\"$key\">$value</option>";
		}
		$smarty->assign("dispatcher",$dispatcher);	
		$smarty->assign("myCities",$myCities);
		$smarty->assign("cityOptions",$cityOptions);
	}	
	$sql="SELECT USERNAME FROM jsadmin.PSWRDS WHERE PRIVILAGE LIKE '%DIS%'";
	$res=mysql_query_decide($sql) or die("Error while fetching dispatcher names   ".$sql."   ".mysql_error_js());
	if(mysql_num_rows($res))
	{
		while($row=mysql_fetch_assoc($res))
		{
			$dispatcherList[]=$row["USERNAME"];	
		}
		$smarty->assign("dispatcherList",$dispatcherList);
	}
	else
		$smarty->assign("errorMessage","No dispatcher created yet");
	$smarty->assign("user",$user);
	$smarty->assign("cid",$cid);
	$smarty->display("ap_dispatcher_cities.htm");	
}
else
{
	$msg="Your session has been timed out<br><br>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
