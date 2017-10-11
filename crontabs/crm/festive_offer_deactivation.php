<?php 
	$curFilePath = dirname(__FILE__)."/"; 
	include_once("/usr/local/scripts/DocRoot.php");
	include("../connect.inc");
	$db_master = connect_db();

	$todaysDt =date("Y-m-d");

	// Festive Offer De-activation
	$sql="SELECT * from billing.FESTIVE_LOG_REVAMP ORDER BY ID DESC limit 1";
	$res=mysql_query($sql,$db_master) or die(mysql_error($db_master)); 
	if($row = mysql_fetch_array($res))
	{
		$status =$row['STATUS'];
		$endDt 	=$row['END_DT'];
		$idSet 	=$row['ID'];
		$lastActiveServices 	=$row['LAST_ACTIVE_SERVICES'];
		$serviceArr 		=explode(",",$lastActiveServices);

		if($status=='Active' && strtotime($endDt)==strtotime($todaysDt)){

                        $sql1 ="update billing.FESTIVE_LOG_REVAMP SET STATUS='Inactive',DE_ACTIVATION_DT=now() where ID='$idSet'";
    	                mysql_query($sql1,$db_master) or die(mysql_error($db_master));
                	activateServices($serviceArr,$db_master);

			mail("manoj.rana@naukri.com","Festive Offer De-Activated on - $date", "");
		}
	}

	// Offer Discount De-activation
        $sql="SELECT ID,STATUS from billing.DISCOUNT_OFFER_LOG where END_DT='$todaysDt' AND STATUS='Y' ORDER BY ID DESC limit 1";
        $res=mysql_query($sql,$db_master) or die(mysql_error($db_master));
        while($row = mysql_fetch_array($res))
        {
                $status =$row['STATUS'];
                $idSet =$row['ID'];

                $sql1 ="update billing.DISCOUNT_OFFER_LOG SET STATUS='N',DE_ACTIVATION_DT=now() WHERE ID='$idSet'";
                mysql_query($sql1,$db_master) or die(mysql_error($db_master));

                mail("manoj.rana@naukri.com","Offer Discount De-Activated on - $date", "");
        }

	// function to get activate previous services
	function activateServices($serviceArr,$db_master)
	{
	        $serviceStr ="'".@implode("','",$serviceArr)."'";

	        $sql ="update billing.SERVICES SET SHOW_ONLINE='N' WHERE ADDON='N' AND ACTIVE='Y'";
	        mysql_query_decide($sql,$db_master) or die(mysql_error_js());

	        $sql1 ="UPDATE billing.SERVICES SET SHOW_ONLINE='Y' where SERVICEID IN($serviceStr)";
	        mysql_query_decide($sql1,$db_master) or die(mysql_error_js());
	}

	
?>
