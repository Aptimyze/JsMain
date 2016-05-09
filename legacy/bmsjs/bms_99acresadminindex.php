<?PHP

/***************************************************bms_adminindex.php******************************************************/
  /*
   *  Created By         : Abhinav Katiyar
   *  Last Modified By   : Abhinav Katiyar
   *  Description        : Displays the main home page for the admin
   *  Includes/Libraries : bms_connect.php
*/
/***************************************************************************************************************************/

include_once("./includes/bms_connect.php");
$data=authenticatedBms($id,$ip,"99acresadmin");
if ($data)
{
	$user	= $data["USER"];
	$id	= $data["ID"];
	$site   = $data["SITE"];

	$bmsheader=fetchHeaderBms($data);
	$bmsfooter=fetchFooterBms();
	$smarty->assign("bmsheader",$bmsheader);
	$smarty->assign("bmsfooter",$bmsfooter);
	$smarty->assign("id",$id);
	$smarty->assign("site",$site);
	$smarty->display("./$_TPLPATH/bms_99acresadminindex.htm");
}
else
	TimedOutBms();
	
?>
