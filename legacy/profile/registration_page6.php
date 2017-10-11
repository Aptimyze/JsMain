<?php
include_once("connect.inc");
if(!$from_p6)
{
	if(!$sem)
	{
		if(!$data_auth){
		       $data_auth=authenticated($checksum,'y');
		       if(!$data_auth) {
			       header("Location: ".$SITE_URL."/profile/registration_page1.php");
			       exit;
			}
		}
	}
}

$smarty->assign('PROFILECHECKSUM',$profilechecksum);
$reg_page6='1';
$smarty->assign("IS_FTO_LIVE",FTOLiveFlags::IS_FTO_LIVE);
include_once("revamp_filter.php");
exit;
?>
