<?php
/************************************************bms_campaign.php***********************************************************/
  /*
   *  Created By         : Shobha Kumari
   *  Last Modified By   : Shobha Kumari
   *  Description        : used for displaying the campaigns in the system and changing their status.
   *  Includes/Libraries : ./includes/bms_connect.php
***************************************************************************************************************************/

include("./includes/bms_connect.php");
$ip=FetchClientIP();
if ($site != '99acres')
        $data=authenticatedBms($id,$ip,"banadmin");
else
        $data=authenticatedBms($id,$ip,"99acresadmin");
$smarty->assign("site",$site);
//$data=authenticatedBms($id,$ip,"banadmin");

$id=$data["ID"];
global $dbbms,$smarty,$maxlimit;
$maxlimit=array();
$maxlimit=array("0"=>array("name"=>"all","value"=>"all"),"1"=>array("name"=>"recent 10","value"=>"10"), "2"=> array("name"=>"recent 100","value"=>"100"));

$bmsheader=fetchHeaderBms($data);
$bmsfooter=fetchFooterBms();
$smarty->assign("bmsheader",$bmsheader);
$smarty->assign("bmsfooter",$bmsfooter);

$smarty->assign("id",$id);
$smarty->assign("show",$show);
$smarty->assign("daysarr",getDaysBms());
$smarty->assign("monthsarr",getMonthsBms());
$smarty->assign("yearsarr",getYearsBms());
$smarty->assign("maxlimit",$maxlimit);
$smarty->assign("campaignstatusarr",getCampaignStatusArr());
$smarty->display("./$_TPLPATH/bms_campaignsearch.htm");

?>
