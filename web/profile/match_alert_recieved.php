<?php
/******************************************************************************************************
        Filename        :       match_alert_recieved.php
        Description     :       To display the profiles that send as match alerts to the logged in user.
        Created by      :       Vibhor Garg
        Created On      :       19' Feb, 08
******************************************************************************************************/
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
        include_once("contact.inc");
        require_once("display_result.inc");
        include_once("cmr.php");
	include_once("connect_auth.inc");
	$db=connect_db();
        $data = authenticated($checksum);
	if($data)
		login_relogin_auth($data);
	 $smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
	 $smarty->assign("CHECKSUM",$checksum);
         $smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
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
                $db=connect_737_lan();
		if(strstr($data['SUBSCRIPTION'],'F'))
                	$smarty->assign("SUBSCRIPTION",'Y');
                $smarty->assign("USERSUBSCRIPTION",$data['SUBSCRIPTION']);
                $smarty->assign("PERSON_LOGGED_IN",1);
                $smarty->assign("j",$j);
                $smarty->assign("my_scriptname",'match_alert_recieved.php');
                $pid=$data["PROFILEID"];
                self_astro_details($data['PROFILEID']);
		reload_on_layer($pid,$redirect,$action,$nmessage_profileid,$error_msg,$ymessage,$nmessage);
		/*$sql="SELECT COUNT(*) AS CNT FROM matchalerts.LOG WHERE RECEIVER ='$pid'";
        	$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
        	$row=mysql_fetch_assoc($res);
        	$totalcount=$row["CNT"];
       		mysql_free_result($res);
		*/
        	$sql="SELECT USERNAME,GENDER FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$pid'";
        	$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
        	$row=mysql_fetch_assoc($res);
        	$smarty->assign("USERNAME",$row["USERNAME"]);
		if($row["GENDER"]=='M')
			$gender = 'F';
		else
			$gender = 'M';
        	mysql_free_result($res);
		$db=connect_slave81();	
		//$sql="SELECT SQL_CACHE SQL_CALC_FOUND_ROWS USER AS RMA,DATE FROM matchalerts.LOG A,newjs.JPROFILE B WHERE B.GENDER = '$gender' AND A.USER = B.PROFILEID AND A.RECEIVER ='$pid' GROUP BY USER ORDER BY DATE DESC LIMIT $j,$PAGELEN";
		 $sql="SELECT SQL_CACHE SQL_CALC_FOUND_ROWS USER AS RMA,DATE FROM matchalerts.LOG WHERE RECEIVER ='$pid' GROUP BY USER ORDER BY DATE DESC LIMIT $j,$PAGELEN";
        	$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	       	$csql = "Select FOUND_ROWS()";
		$cresult=mysql_query_decide($csql) or die("$csql".mysql_error_js());
		$crow = mysql_fetch_row($cresult);
		$totalcount = $crow[0];
		$smarty->assign("cid",$cid);
        	$smarty->assign("pid",$pid);
		$db=connect_737_lan();

        	//$smarty->assign("crmback","admin");
		//while($row_con=mysql_fetch_array($result))
		     	//$profileid_arr[]=$row_con['RMA'];
		//print_r($profileid_arr);

		if($result)
          	{
                     $TOTALREC=mysql_num_rows($result);
                     if($TOTALREC>0)
		     	displayresults($result,$j,"/P/match_alert_recieved.php",$TOTALREC,"","1",$searchchecksum,"",$FRESHNESS,'','','','',$crmback);
            	}
		//////////////////////////////////////////////////////////////////
                        $curcount=$j;
                        $totalrec=$totalcount;
                        $scriptname=$_SERVER['SCRIPT_NAME'];
                        $scriptname="$SITE_URL".$scriptname;
                        $links_to_show=10;
                        if( $curcount )
                                $cPage = ($curcount/$PAGELEN) + 1;
                        else
                                $cPage = 1;
                        $diff=16;
                        if(isset($_COOKIE['JS_LAST_LOGIN_DT']))
                                $diff=DayDiff($_COOKIE["JS_LAST_LOGIN_DT"],date("Y-m-d"));
                        if($diff<=15)
                                $ordering="S";
                        if(isset($_COOKIE['JS_FRESHNESS']))
                                $ordering=$_COOKIE["JS_FRESHNESS"];
                        if($sortorder)
                        {
                                $ordering=$sortorder;
                                setcookie("JS_FRESHNESS",$sortorder,0,"/",$domain);
                        }
                        if(!$ordering)
                                //$ordering='T';
                                $ordering='S';//change done on 5 june 2007 to make default sort order by date
                        $smarty->assign("sortorder",$ordering);
                        $checksum.="&type=$type&flag=$flag&mmm=$mmm&chat=$chat&sortorder=$ordering&vflag=$vflag";
                        pagelink($PAGELEN,$totalrec,$cPage,$links_to_show,$checksum,$scriptname);
			$smarty->assign("NO_OF_PAGES",ceil($totalrec/$PAGELEN));
                        $smarty->assign("CURPAGE",$cPage);
                        $smarty->assign("PAID_CNT",$paid_cnt);
                        $smarty->assign("UNPAID_CNT",$unpaid_cnt);
                        $smarty->assign("NUDGE_CNT",$nudge_cnt);
                        $smarty->assign("BACK_TO_SEARCH_PAGE",$curcount);
                        $smarty->assign("SCRIPTNAME1",$scriptname);
                        $smarty->assign("BACKLINK",$checksum);

                        $profile_start=($cPage-1)*12+1;

                        if($profile_start+11<$totalrec)
                                $profile_end=$profile_start+11;
                        else
                                $profile_end=$totalrec;

                        $smarty->assign("profile_start1",$profile_start);
                        $smarty->assign("profile_end1",$profile_end);
                ////////////////////////////////////////////////////////////////////////
		$smarty->assign("TOTALREC",$TOTALREC);
                $smarty->assign("RECORDCOUNT",$totalcount);
		if (!$crmback)
                {
                        $smarty->assign("head_tab",'my jeevansathi');
                        $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
                        $smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
                        $smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
                        $smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
                        $smarty->assign("RIGHTPANEL",$smarty->fetch("rightpanel.htm"));
                }
 		$smarty->display("visitors.htm");
        }
        else
                TimedOut();
        // flush the buffer
        if($zipIt)
        	ob_end_flush();
?>
