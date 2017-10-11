<?php

	//to zip the file before sending it
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt)
		ob_start("ob_gzhandler");
	//end of it
	
	include("connect.inc");
	
	$db=connect_db();
	
	$data=authenticated($checksum);

	/*************************************Portion of Code added for display of Banners*******************************/
	$smarty->assign("data",$data["PROFILEID"]);
	$smarty->assign("bms_topright",18);
	$smarty->assign("bms_right",28);
	$smarty->assign("bms_bottom",19);
	$smarty->assign("bms_left",24);
	$smarty->assign("bms_new_win",32);

        //$regionstr=8;
        //include("../bmsjs/bms_display.php");
        /************************************************End of Portion of Code*****************************************/
        //$db=connect_db();
	
	$PAGELEN=15;
	
	if($data)
	{
		// if viewing for first time
		if(!$linkSubmit && !$BACK_TO_SEARCH_RESULTS)
		{
			$smarty->assign("CHECKSUM",$checksum);
			$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
			$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
			$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
			//$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
			//$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
			$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
			
			$smarty->display("people_looking_for_me.htm");
		}
		// 
		else 
		{
			include("reverse_search.php");
			include("search.inc");
			
			$profileid=$data["PROFILEID"];
			
			echo $sql=reverse_search($profileid);
			//exit;
			// close master connection
			//mysql_close($db);
			// connect to slave
			$db=connect_slave();
			mysql_select_db_js("srch",$db);
			$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			
			$count=mysql_num_rows($result);
			// close slave connection
			//mysql_close($db);
			// connect to master
			$db=connect_db();
			
			if($count > 0)
			{
				if(!$j)
					$j=0;
				displayresults($result,$j,"looking4me.php",$count,"1",$searchchecksum,"",$FRESHNESS,$newsearch,12,$inf_checksum,$castemapping,$crmback,$savesearch,$label_select_no,"",$save_search_redirect);
			}
			else 
			{
				$smarty->assign("RECORDCOUNT","0");
				$smarty->assign("NORESULTS","1");
				$smarty->assign("NO_OF_PAGES","0");
				$smarty->assign("CURPAGE","0");
			}
			
			if($data["GENDER"]=="M")
				searchBar($gender="F","","","","","");
			else 
				searchBar($gender="M","","","","","");
			
			$smarty->assign("CHECKSUM",$checksum);
			$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
			$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
			$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
			//$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
			//$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
			$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
			
			$smarty->display("photosearch_results.htm");
		}
	}
	else 
	{
		TimedOut();
	}
	
	// flush the buffer
	if($zipIt)
		ob_end_flush();
?>
