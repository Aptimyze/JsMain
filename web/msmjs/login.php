<?php
//it starts zipping
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)   
	ob_start("ob_gzhandler");

//end of it
/**
*       Included        :       connect.inc
*       Description     :       contains functions related to database connection and login authentication
**/
include ("connect.inc");
/**
*       Included        :       time.php
*       Description     :       contains functions related to date and time 
**/
//include ("../jsadmin/time.php");
$ip =FetchClientIP();
$connection = login($username, $password);
$smarty->assign("cid","$connection");
$smarty->assign("msmjsHeader",$smarty->fetch("msmjsHeader.htm"));
if($connection)//successful login
{
//	header("Location: $SITE_URL/mainpage.php?name=$username&cid=$connection");		
	/************Prev use
	$smarty->assign("username","$username");
	$city_india=create_dd($City_India,"City_India");
	$city_usa=create_dd($City_Usa,"City_USA");
	$city_india .=  $city_usa;
	$smarty->assign("f_occupation","1");
	$smarty->assign("f_mtongue","1");
	$smarty->assign("f_caste","1");
	//$smarty->assign("f_religion","1");
	$smarty->assign("f_country","1");
	$smarty->assign("b_country","1");
	$smarty->assign("f_city","1");
	$smarty->assign("f_education","1");

	// set residency status to all
	$smarty->assign("r0", 1);
	// set relation to all
	$smarty->assign("re0", 1);
	$smarty->assign("income",create_dd("","Income"));
	$smarty->assign("city_india",$city_india);
	$smarty->assign("education_level",create_dd("","Education_Level"));
	$smarty->assign("maxheight",create_dd("","Height",1));
	$smarty->assign("minheight",create_dd("","Height"));
	$smarty->assign("country_residence",create_dd("","Country_Residence"));
	$smarty->assign("country_birth",create_dd("","Country_Residence"));
	$smarty->assign("occupation",create_dd("","Occupation"));
	$smarty->assign("mtongue",create_dd("","Mtongue"));
	$smarty->assign("caste",create_dd("","Caste"));
	$smarty->display("formQuery.htm");
	*****************Ends********/
	$smarty->display("optForm.htm");
}
else//login failed
{
	$smarty->assign("msmjsHeader",$smarty->fetch("msmjsHeader.htm"));
	if($flag=='out')
	{
		$smarty->assign("flag","Y");
	}	

	$smarty->assign("username","$username");
	$smarty->display("jsconnectError.htm");
}
if($zipIt)
	ob_end_flush();
?>
