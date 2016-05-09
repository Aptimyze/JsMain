<?php

	//to zip the file before sending it
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt)
		ob_start("ob_gzhandler");
	//end of it

	include("../profile/connect.inc");
	include("../profile/hits.php");
	$db=connect_db();
	$data=authenticated($checksum);
        if($data)
                login_relogin_auth($data);
	
	if($source!="")
	{
		$pagename=$_SERVER['PHP_SELF'];
		savehit($source,$pagename);
	}

	$sql="SELECT SQL_CACHE SID,NAME1,NAME2,HEADING,STORY FROM INDIVIDUAL_STORIES WHERE SID='$sid'";
	$res=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","","ShowErrTemplate");

	if($row=mysql_fetch_array($res))
	{
		$sid=$row['SID'];
		$name1=$row['NAME1'];
		$name2=$row['NAME2'];
		$heading=$row['HEADING'];
		$story=$row['STORY'];
	}

	mysql_free_result($res);
	mysql_close($db);
	
	$smarty->assign("SID",$sid);
	$smarty->assign("NAME1",$name1);
	$smarty->assign("NAME2",$name2);
	$smarty->assign("HEADING",$heading);
	$smarty->assign("STORY",$story);

	/* earlier code
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
	//$smarty->assign("TOPLEFT",$smarty->fetch("../success/topleft.htm"));
	$smarty->assign("LEFTPANEL",$smarty->fetch("../success/leftpanel.htm"));
	$smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
	$smarty->assign("SUBHEADER",$smarty->fetch("../success/subheader.htm"));

	//$smarty->display("../success/complete_success_stories.htm");
	$smarty->display("../success/complete_story_page_1.htm");
	*/
	
	// Code for the Year Panel.
		if($year>=2009)			// Restricting the Success Story Year to 2008 if year is greater than 2009 *Temprory*
			$year=2008;
		$currentYear=date("Y");
	//Since template will be common for the year 2007 and above
		$yearArray=array();
		for($start_year=$currentYear;$start_year>=2007;$start_year--)
			$yearArray[]=$start_year;
		$smarty->assign("yearArray",$yearArray);

	
	//new added by puneet
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
	$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
	$smarty->display("../success/complete_story_page_1.htm");
	//new added by puneet ends	

	// flush the buffer
	if($zipIt)
		ob_end_flush();
?>
