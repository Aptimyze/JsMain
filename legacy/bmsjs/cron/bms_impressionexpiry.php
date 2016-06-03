<?php
chdir(dirname(__FILE__));
//$_CRONPATH="/usr/local/apache/htdocs/saurabh/bms";

	//include("$_CRONPATH/includes/bms_connections.php");
  include("../includes/bms_connections.php");

  $sql="Select b.CampaignId as campaign,sum(b.BannerServed) as tot,c.CampaignImpressions from bms2.CAMPAIGN c,bms2.BANNER b where c.CampaignType='impression' and c.CampaignStatus='active' and b.CampaignId=c.CampaignId Group By b.CampaignId Having tot >= c.CampaignImpressions"; 
$result=mysql_query($sql,$dbbms) or mail("abhinav.katiyar@jeevansathi.com","error in bms_impressionexpiry",mysql_error($dbbms).$sql);  
  
  if($myrow=mysql_fetch_array($result))
  {
  	do 
  	{
  		if($campaign)
  		{
  			$campaign.=",".$myrow["campaign"];
  		}
  		else 
  		{
  			$campaign=$myrow["campaign"];
  		}
  		
  	}while($myrow=mysql_fetch_array($result));
  	
  	$sql="Update bms2.CAMPAIGN c set c.CampaignStatus='served' where c.CampaignId in ($campaign)"; 
  	mysql_query($sql,$dbbms) or mail("abhinav.katiyar@jeevansathi.com","error in bms_impressionexpiry",mysql_error($dbbms).$sql);  
  	
  	$sql="Update bms2.BANNER set BannerStatus='served' where CampaignId in ($campaign) and BannerStatus in ('live','deactive','deactivesums')"; 
  	mysql_query($sql,$dbbms) or mail("abhinav.katiyar@jeevansathi.com","error in bms_impressionexpiry",mysql_error($dbbms).$sql);  
  	
  	$sql="Update bms2.BANNER set BannerStatus='expired' where CampaignId in ($campaign) and BannerStatus in ('booked','newrequest','ready')"; 
  	mysql_query($sql,$dbbms) or mail("abhinav.katiyar@jeevansathi.com","error in bms_impressionexpiry",mysql_error($dbbms).$sql);  
  	
  }
  
  
?>
