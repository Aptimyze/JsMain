<?php
/**
*       Filename        :       bookmark.php
*       Description     :
*       Created by      :
*       Changed by      :       Lavesh Rawat
*       Changed on      :       
        Changes         :       New Service added called Eclassified , changes done for that.
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
	//include("display_result.inc");
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
	
	$db=connect_db();
	$data = authenticated($checksum);

	//Added By lavesh for contact details on left panel
	if($data)
                login_relogin_auth($data);
	//Ends here

	/**************************** CODE FOR BMS DISPLAY ***********************************/
	$smarty->assign("data",$data["PROFILEID"]);
        $smarty->assign("bms_topright",18);
        $smarty->assign("bms_bottom",19);
        $smarty->assign("bms_left",24);
        $smarty->assign("bms_right",28);
	$smarty->assign("bms_new_win",32);
	/*****************************************************************************************/

        link_track("bookmark.php");

	$PAGELEN=12;
	if (!$j )
                $j = 0;

	$smarty->assign("j",$j);
        $PAGELEN_QCACHE=120;
        $j_QCACHE=floor($j/120);
        $j_QCACHE=120*$j_QCACHE;

	if(isset($data))
	{
		//Added By lavesh 
		if(strstr($data['SUBSCRIPTION'],'F'))
                        $smarty->assign("SUBSCRIPTION",'Y');
		
                $smarty->assign("PERSON_LOGGED_IN",1);
	        $smarty->assign("j",$j);
		$smarty->assign("my_scriptname",'bookmark.php');
		$profileid=$data["PROFILEID"];
		reload_on_layer($profileid,$redirect,$action,$nmessage_profileid,$error_msg,$ymessage,$nmessage);
		//Ends here

		if( $del == 1 )
		{
			$id=explode("i",$pid);
			$pid=$id[1];
			$sql="DELETE FROM BOOKMARKS WHERE BOOKMARKEE=$pid AND BOOKMARKER=$profileid";
			$result=mysql_query_decide($sql,$db) or logError("error",$sql);
			$smarty->assign('layer',1);
			$smarty->assign("favourable_response",'Y');
			$removed_profile=user_get_name($pid);
			$smarty->assign("ymessage","You have successfully removed 1 profile from your shortlist : ".$removed_profile);
		}
	
		$count=0;         
		$sql = "SELECT count(*) FROM BOOKMARKS WHERE BOOKMARKER='$profileid'";
		$result=mysql_query_decide($sql,$db) or logError("error",$sql);
		$myrow = mysql_fetch_row($result);
		$TOTALREC = $myrow[0];
		
		$sql = "SELECT BOOKMARKEE as PROFILEID FROM BOOKMARKS WHERE BOOKMARKER='$profileid' limit $j_QCACHE,$PAGELEN_QCACHE";
		$result=mysql_query_decide($sql,$db) or logError("error",$sql);
		
		if($TOTALREC>0)
			displayresults($result,$j,"/P/bookmark.php",$TOTALREC,"","1",$searchchecksum,"",$FRESHNESS,$newsearch,12,$inf_checksum,$castemapping,$crmback);                         
		else
		{
			$smarty->assign("msg1","Currently, you do not have any members in your shortlist.");
			$smarty->assign("msg2","This feature lets you save relevant profiles that you like, so that you can contact them later.");
					
		}

		//$smarty->assign("RECORDCOUNT",$TOTALREC);

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
		
		//added By lavesh
		$smarty->display("favourite_profile.htm");			
		//$smarty->display("bookmark.htm");
	}
	else
		TimedOut();

	// flush the buffer
	if($zipIt)
	ob_end_flush();
?>
