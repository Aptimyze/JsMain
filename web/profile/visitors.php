<?php
/**
       	Filename        :       visitors.php
	Description	:	To display the profiles of the users, who have visited the logged in user's profile.
       	Created by      :	Tanu Gupta
	Created On	:	03' Apr, 07
**/


	//to zip the file before sending it
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt)
		ob_start("ob_gzhandler");
	//end of it
		
	include("connect.inc");
	include("search.inc");
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
	
	$db=connect_db();
	$data = authenticated($checksum);

	if($data)
                login_relogin_auth($data);

	/**************************** CODE FOR BMS DISPLAY ***********************************/
	$smarty->assign("data",$data["PROFILEID"]);
        $smarty->assign("bms_topright",18);
        $smarty->assign("bms_bottom",19);
        $smarty->assign("bms_left",24);
        $smarty->assign("bms_right",28);
	$smarty->assign("bms_new_win",32);
	/*****************************************************************************************/

        link_track("visitors.php");

	$PAGELEN=12;
	if (!$j )
                $j = 0;

	$smarty->assign("j",$j);
        $PAGELEN_QCACHE=120;
        $j_QCACHE=floor($j/120);
        $j_QCACHE=120*$j_QCACHE;

	if(isset($data))
	{
		//mysql_close();
		$db=connect_737_lan();

		if(strstr($data['SUBSCRIPTION'],'F'))
                        $smarty->assign("SUBSCRIPTION",'Y');
		$smarty->assign("USERSUBSCRIPTION",$data['SUBSCRIPTION']);
                $smarty->assign("PERSON_LOGGED_IN",1);
	        $smarty->assign("j",$j);
		$smarty->assign("my_scriptname",'visitors.php');
		$profileid=$data["PROFILEID"];
		reload_on_layer($profileid,$redirect,$action,$nmessage_profileid,$error_msg,$ymessage,$nmessage);
		$count=0;         
		$result=visitors($profileid,$data['GENDER'],'y');
		$db=connect_db();
		if($result)
		{
			$TOTALREC=mysql_num_rows($result);
			if($TOTALREC>0)
				displayresults($result,$j,"/P/visitors.php",$TOTALREC,"","1",$searchchecksum,"",$FRESHNESS,$newsearch,12,$inf_checksum,$castemapping,$crmback);                         
			$smarty->assign("visitors",1);
			$smarty->assign("STYPE","U");
		}
                else
                {
                        $smarty->assign("msg1","Currently, No member has viewed your profile.");
                        $smarty->assign("msg2","This feature allows other members to view your profile.");
               	}		
		$smarty->assign("CHECKSUM",$checksum);
		$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
		$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
		
		if (!$crmback)
		{
			$smarty->assign("head_tab",'my jeevansathi');
			$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
			$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
			$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
			$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
			$smarty->assign("RIGHTPANEL",$smarty->fetch("rightpanel.htm"));
		}
		
		$smarty->display("favourite_profile.htm");			
	}
	else
		TimedOut();

	// flush the buffer
	if($zipIt)
	ob_end_flush();
?>
