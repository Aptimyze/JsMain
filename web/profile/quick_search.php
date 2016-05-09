<?php
	//to zip the file before sending it
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt)
		ob_start("ob_gzhandler");
	//end of it

	include("connect.inc");
	include "search.inc";
	
	$db=connect_db();
	$data = authenticated($checksum);

	/*************************************Portion of Code added for display of Banners*******************************/
	$smarty->assign("data",$data["PROFILEID"]);
	$smarty->assign("bms_topright",4);
	$smarty->assign("bms_left",5);
	$smarty->assign("bms_bottom",6);
	$smarty->assign("bms_right",27);
	$smarty->assign("bms_new_win",33);

//        $regionstr=2;
//       include("../bmsjs/bms_display.php");
        /************************************************End of Portion of Code*****************************************/
        //$db=connect_db();

	$lang=$_COOKIE['JS_LANG'];
	if($lang=="deleted")
		$lang="";

	$profileid=$data["PROFILEID"];
	
	/*$religion=create_dd($Religion,"Religion");
	$smarty->assign("religion",$religion);*/

	$mtongue=create_dd($Mtongue,"Mtongue");
	$smarty->assign("mtongue",$mtongue);
	
	$country_residence=create_dd($Country_Res,"Country_Residence");
	$smarty->assign("country_residence",$country_residence);

	// changes made by shobha for community dropdown modification
	$top_mtongue = create_dd($Mtongue,"top_mtongue");
	$smarty->assign("top_mtongue",$top_mtongue);
	
	$city_india=create_dd($City_Res,"City_India");
	$city_usa=create_dd($City_Usa,"City_USA");
	$city_india .=  $city_usa;
	$smarty->assign("city_india",$city_india);

	$smarty->assign("caste",create_dd("","Caste"));
	
	$smarty->assign("SEARCHONLINE",$searchonline);
	
	$smarty->assign("CHECKSUM",$checksum);
	$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
	$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));

	if($lang)
	{
		$smarty->assign("HEAD",$smarty->fetch($lang."_headnew.htm"));
		$smarty->assign("LEFTPANEL",$smarty->fetch($lang."_leftpanelnew.htm"));
		$smarty->assign("FOOT",$smarty->fetch($lang."_foot.htm"));
		$smarty->assign("SUBFOOTER",$smarty->fetch($lang."_subfooternew.htm"));

		$smarty->display($lang."_quick_search.htm");
	}
	else
	{
		$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
		$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
														 
		$smarty->display("quick_search.htm");
	}
	
	// flush the buffer
	if($zipIt)
		ob_end_flush();

?>
