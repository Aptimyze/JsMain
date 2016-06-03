<?php
/***************************************************************bms_designerbms.php*****************************************/
  /*
   *  Created By         : Shobha Kumari
   *  Last Modified By   : Shobha Kumari
   *  Description        : This file is for designer's login
   *  Includes/Libraries : ./includes/bms_connect.php
****************************************************************************************************************************/

   include_once("./includes/bms_connect.php");
   include("bms_getcode.php");

   $ipaddr=FetchClientIP();
   $data=authenticatedBms($id,$ipaddr,"designer");
   if($data)
   {
	if($submit1)
 	{
		
		$zonearr=explode("|",$zone);
	        $zoneid=$zonearr[0];
		$istrue=getcode($zoneid);
		if($istrue != "true")
		{
			$smarty->assign('err',"There are no banners currently live on this location");
		}
		$id=$data["ID"];
	    	$bmsheader=fetchHeaderBms($data);
                $bmsfooter=fetchFooterBms();
                $smarty->assign("bmsheader",$bmsheader);
                $smarty->assign("bmsfooter",$bmsfooter);
                $smarty->assign("id",$id);
		
		$smarty->display("./$_TPLPATH/bms_getcode.htm");
	}
	else
	{
		function getBannerClass()
		{
        		$bannerclassarr=array("Banner" , "MailerBanner" , "PopUnder/PopUp/Banner-NewWindow");
        		return $bannerclassarr;
		}
		$smarty->assign("bannerclassarr",getBannerClass());

	        $id=$data["ID"];
		$bmsheader=fetchHeaderBms($data);
		$bmsfooter=fetchFooterBms();
	       	$smarty->assign("bmsheader",$bmsheader);
         	$smarty->assign("bmsfooter",$bmsfooter);
	        $smarty->assign("id",$id);
        	assignRegionZoneDropDowns("","");
		$smarty->display("./$_TPLPATH/bms_designerbms.htm");
	}
	
   }
   else
   {
	TimedOutBms();	
   }	
?>
