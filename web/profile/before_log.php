<?php
/*
*       Filename        :       login.php
*/
	require_once("connect.inc");
	$db=connect_db();
    
    $data=authenticated();
	
	$page_arr=array("1"=>"/P/mainmenu.php",
		"2"=>"/P/contacts_made_received.php",
		"3"=>"/P/viewprofile.php?ownview=1",
		"5"=>"/profile/dpp",
		"4"=>"/P/mem_comparison.php",
		"6"=>"/search/partnermatches",
		"7"=>"/search/reverseDpp",
		"8"=>"/search/twoway",
		"9"=>"/P/contacts_made_received.php?page=eoi&filter=R",
		"10"=>"/P/contacts_made_received.php?page=accept&filter=A",
		"11"=>"/P/contacts_made_received.php?page=visitors&filter=R",
		"12"=>"/P/contacts_made_received.php?page=photo&filter=R",
		"13"=>"/P/contacts_made_received.php?page=messages&filter=R",
		"14"=>"/P/contacts_made_received.php?page=viewed_contacts_by&filter=R",
		"15"=>"/P/contacts_made_received.php?page=favorite&filter=M",
		"16"=>"/P/contacts_made_received.php?page=matches&filter=R",
		"17"=>"/P/contacts_made_received.php?page=kundli&filter=R",
		"18"=>"/P/viewprofile.php?ownview=1&EditWhatNew=ContactDetails",
		"19"=>"/P/viewprofile.php?ownview=1&EditWhatNew=FamilyDetails",
		"20"=>"/social/addPhotos",
		"21"=>"/profile/dpp?EditWhatNew=Dpp_Info",
		"22"=>"/profile/dpp?EditWhatNew=Dpp_Details");
	if(!array_key_exists($page,$page_arr))
		die("ERROR#Wrong page value passed");

	$page_source=array("1"=>"L_MMENU",
	"2"=>"L_MYCONT","9"=>"L_MYCONT","10"=>"L_MYCONT","11"=>"L_MYCONT","12"=>"L_MYCONT","13"=>"L_MYCONT","14"=>"L_MYCONT","15"=>"L_MYCONT",
	"3"=>"L_MYPAGE","18"=>"L_MYPAGE","19"=>"L_MYPAGE","20"=>"L_MYPAGE",	
	"4"=>"L_MEMPAGE",
	"6"=>"l_MEMLK","7"=>"L_MEMLKME","8"=>"L_MEMLKME","16"=>"L_MEMLKME","17"=>"L_MEMLKME",
	"5"=>"hp_black","21"=>"hp_black","22"=>"hp_black");
	if($data['PROFILEID'])
	{
		echo "<script>document.location.href='$SITE_URL/$page_arr[$page]';$.colorbox.close();</script>";
		die;
	}
	else
		$smarty->assign("REDIRECT_TO","$SITE_URL/$page_arr[$page]");
	
	$smarty->assign("BEF_LOG_SRC",$page_source[$page]);
	include_once("include_file_for_login_layer.php");
        $smarty->display("login_layer.htm");
        die;
?>
