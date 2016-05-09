<?php
	$zipIt = 0;

	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt)
		ob_start("ob_gzhandler");
		
	include("connect.inc");
	include("search.inc");
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");

	$db=connect_db();
	$data = authenticated($checksum);

        if($data)
        {
                login_relogin_auth($data);
        }
                                                                                                                             
	$smarty->assign("data",$data["PROFILEID"]);
        $smarty->assign("bms_topright",18);
        $smarty->assign("bms_bottom",19);
        $smarty->assign("bms_left",24);
        $smarty->assign("bms_right",28);
	$smarty->assign("bms_new_win",32);
        link_track("horoscope_requests_list.php");
	
	$PAGELEN=12;
        if (!$j )
                $j = 0;
                                                                                                                             
        $PAGELEN_QCACHE=120;
        $j_QCACHE=floor($j/120);
        $j_QCACHE=120*$j_QCACHE;

	if(isset($data))
	{
		$profileid=$data["PROFILEID"];

		//Sharding Concept added by Lavesh Rawat
                $mysqlObj=new Mysql;

                $myDbName=getProfileDatabaseConnectionName($profileid);
                $myDb=$mysqlObj->connect("$myDbName");

		$sql = "SELECT  SQL_CACHE SQL_CALC_FOUND_ROWS PROFILEID FROM HOROSCOPE_REQUEST WHERE PROFILEID_REQUEST_BY ='$profileid' ORDER BY DATE DESC limit $j_QCACHE,$PAGELEN_QCACHE";
		$result = $mysqlObj->executeQuery($sql,$myDb);

                $csql="Select FOUND_ROWS()";
                $cres=$mysqlObj->executeQuery($csql,$myDb);
                $crow =$mysqlObj->fetchRow($cres);
                $TOTALREC = $crow[0];
		//Sharding Concept added by Lavesh Rawat

		if($TOTALREC>0)	
			displayresults($result,$j,"/P/horoscope_request_list.php",$TOTALREC,"","1",$searchchecksum,"",$FRESHNESS,$newsearch,12,$inf_checksum,$castemapping,$crmback);
		else
		{
			$smarty->assign("msg1","Currently, you do not have any members who requested your horoscope.");
			$smarty->assign("msg2","This feature allows other members to request your horoscope.");
		}
                $smarty->assign("RECORDCOUNT",$TOTALREC);

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
		$smarty->assign("horo_req",1);
                $smarty->display("favourite_profile.htm");
	}
	else
		TimedOut();

	if($zipIt)
	ob_end_flush();

?>
