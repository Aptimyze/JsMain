<?php
chdir(dirname(__FILE__));
//$_CRONPATH="/usr/local/apache/htdocs/saurabh/bms";

//include("$_CRONPATH/includes/bms_connections.php");
include("../includes/bms_connections.php");  
  
$date=date("Y-m-d");
  
$sql="Update bms2.BANNER set BannerStatus='served' where BannerStatus in ('live','deactive','deactivesums') and BannerEndDate < '$date' and BannerEndDate!='0000-00-00'";
mysql_query($sql,$dbbms) or mail("abhinav.katiyar@jeevansathi.com","error in bms_durationexpiry",mysql_error($dbbms).$sql);
  
$sql="Update bms2.BANNER set BannerStatus='expired' where BannerStatus in ('booked','newrequest','ready') and BannerEndDate < '$date' and BannerEndDate!='0000-00-00'";
mysql_query($sql,$dbbms) or mail("abhinav.katiyar@jeevansathi.com","error in bms_durationexpiry",mysql_error($dbbms).$sql);
  
$sql="Select CampaignId from bms2.CAMPAIGN where CampaignEndDt < '$date' and CampaignEndDt!='0000-00-00'";
$result=mysql_query($sql,$dbbms) or mail("abhinav.katiyar@jeevansathi.com","error in bms_durationexpiry",mysql_error($dbbms).$sql);

if($myrow=mysql_fetch_array($result))
{
	$i=0;
  	do 
  	{
  		if($campaign)
  		{
  			$campaign.=",".$myrow["CampaignId"];
  		}
  		else 
  		{
  			$campaign=$myrow["CampaignId"];
  		}  		
  	}while($myrow=mysql_fetch_array($result));
  	
  	$sql="Update bms2.CAMPAIGN set CampaignStatus='served' where CampaignId in ($campaign)";
	mysql_query($sql,$dbbms) or mail("abhinav.katiyar@jeevansathi.com","error in bms_durationexpiry",mysql_error($dbbms).$sql);
	$sql="Update bms2.BANNER set BannerStatus='served' where BannerStatus in ('live','deactive','deactivesums') and CampaignId in ($campaign)";
	mysql_query($sql,$dbbms) or mail("abhinav.katiyar@jeevansathi.com","error in bms_durationexpiry",mysql_error($dbbms).$sql);

	$sql="Update bms2.BANNER set BannerStatus='expired' where BannerStatus in ('booked','newrequest','ready') and CampaignId in ($campaign)";
	mysql_query($sql,$dbbms) or mail("abhinav.katiyar@jeevansathi.com","error in bms_durationexpiry",mysql_error($dbbms).$sql);
}
?>
