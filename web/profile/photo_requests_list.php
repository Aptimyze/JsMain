<?php
/**
*       Filename        :       photo_requests_list.php
*       Description     :	To display list of all the profiles who have requested your photos
*       Created by      :	Gaurav Arora,Lavesh Rawat
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

        if($data)
        {
                login_relogin_auth($data);
		$my_pid=$data['PROFILEID'];
		//profile_stats($my_pid);
                if(strstr($data['SUBSCRIPTION'],'F'))
                        $smarty->assign("SUBSCRIPTION",'Y');
		$smarty->assign("USERSUBSCRIPTION",$data['SUBSCRIPTION']);
		$smarty->assign("PERSON_LOGGED_IN",1);
                $smarty->assign("j",$j);
                $smarty->assign("my_scriptname",'photo_requests_list.php');

		//mysql_close();
		$db=connect_737_ro();

		//Added by Tanu
		$smarty->assign("VIEWED_PROFILE_CNT",visitors($my_pid,$data['GENDER'],'','y'));
		//Ends here
		reload_on_layer($profileid,$redirect,$action,$nmessage_profileid,$error_msg,$ymessage,$nmessage);
        }
                                                                                                                             

	/**************************** CODE FOR BMS DISPLAY ***********************************/
	$smarty->assign("data",$data["PROFILEID"]);
        $smarty->assign("bms_topright",18);
        $smarty->assign("bms_bottom",19);
        $smarty->assign("bms_left",24);
        $smarty->assign("bms_right",28);
	$smarty->assign("bms_new_win",32);
	/*****************************************************************************************/

        /**************************for link tracking**********************/
        //link_track("photo_requests_list.php");
        /*********************************************************************************/
	
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

		$sql = "SELECT  SQL_CACHE SQL_CALC_FOUND_ROWS PROFILEID FROM PHOTO_REQUEST WHERE PROFILEID_REQ_BY ='$profileid' ORDER BY DATE DESC limit $j_QCACHE,$PAGELEN_QCACHE";
		$result = $mysqlObj->executeQuery($sql,$myDb);

		$csql="Select FOUND_ROWS()";
		$cres=$mysqlObj->executeQuery($csql,$myDb);
		$crow =$mysqlObj->fetchRow($cres);
		$TOTALREC = $crow[0];
		//Sharding Concept added by Lavesh Rawat


		if($TOTALREC>0)	
			displayresults($result,$j,"/P/photo_requests_list.php",$TOTALREC,"","1",$searchchecksum,"",$FRESHNESS,$newsearch,12,$inf_checksum,$castemapping,$crmback);
		else
		{
			$smarty->assign("msg1","Currently, you do not have any members who requested your photos.");
			$smarty->assign("msg2","This feature allows other members to request your photo.");
		}
                $smarty->assign("RECORDCOUNT",$TOTALREC);
                                                                                                                             
		$smarty->assign("photo_req",1);
                $smarty->assign("CHECKSUM",$checksum);
		$smarty->assign("inf_checksum",$inf_checksum);
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
