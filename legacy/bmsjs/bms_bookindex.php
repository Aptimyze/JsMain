<?PHP

/************************************************bms_adminindex.php********************************************************/
  /*
   *  Created By         : Abhinav Katiyar
   *  Last Modified By   : Abhinav Katiyar
   *  Description        : Displays the main home page for the campaign booking 
   *  Includes/Libraries : bms_connect.php
**************************************************************************************************************************/
include_once("./includes/bms_connect.php");
$data = authenticatedBms($id,$ip,"book");

if($data)
{
	$user=$data["USER"];
	$id=$data["ID"];
	$bmsheader=fetchHeaderBms($data);
	$bmsfooter=fetchFooterBms();
	$smarty->assign("bmsheader",$bmsheader);
	$smarty->assign("bmsfooter",$bmsfooter);
	$smarty->assign("id",$id);
	$smarty->display("./$_TPLPATH/bms_bookindex.htm");
}
else
	TimedOutBms();
	
?>
