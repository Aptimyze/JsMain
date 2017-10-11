<?php

include_once("connect.inc");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
$protect_obj=new protect;
$db=connect_db();
	if($echecksum && $checksum)
	{
		$show_chatbar=1;
		if($_COOKIE['chatbar']=='yes')
		$show_chatbar=0;
		$epid=$protect_obj->js_decrypt($echecksum);
		if($checksum==$epid)
		{
			$epid_arr=explode("i",$epid);
	                $profileid=$epid_arr[1];
			if($profileid)
			{
				$today=date("Y-m-d");
				$promo_mails='U';					
//				$sql="UPDATE newjs.JPROFILE SET UDATE=".$today.", PROMO_MAILS='U' WHERE PROFILEID=".$profileid;
//				mysql_query_decide($sql) or logError($errorMsg,"$sql","ShowErrTemplate");
        
        $objUpdate = JProfileUpdateLib::getInstance();
        $result = $objUpdate->editJPROFILE(array('UDATE'=>$today, 'PROMO_MAILS'=>'U'),$profileid,'PROFILEID');
        if(false === $result) {
          
          $sql="UPDATE newjs.JPROFILE SET UDATE=".$today.", PROMO_MAILS='U' WHERE PROFILEID=".$profileid;
          logError($errorMsg,"$sql","ShowErrTemplate");
        }
        
				if ($_GET[flag]=='U')
					$smarty->display('Promo_Unsubscribe.html');
				else
					$smarty->display('Promo_Spam.html');
			}
		}
	}



