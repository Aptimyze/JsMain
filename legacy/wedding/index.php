<?php
/*********************************************************************************************
* FILE NAME     : index.php
* DESCRIPTION   : Derives Data from Main table for LANDING PAGE, and selects by category if contents from left page is selected
* CREATION DATE : 3 September, 2005
* CREATED BY    : NIKHIL TANDON
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

include('connect.inc');
//For populating left and right panels
include('common_func_inc.php');
//for paging
include('display_result.inc');

$db=connect_db();

/*
$PAGELEN=5;
$LINKNO=10;
if(!$j )
        $j = 0;
$sno=$j+1;
*/

populate_head();
populate_left();
	
$sql_id="SELECT SQL_CALC_FOUND_ROWS l.* FROM wedding_classifieds.LISTINGS l,wedding_classifieds.LANDPAGE_ID li where l.ADV_ID=li.ADV_ID AND l.STATUS='A' ORDER BY RAND() LIMIT 5";
$res_id=mysql_query_decide($sql_id,$db) or logError("Error while populating category ".mysql_error_js(),$sql_id);

/*
$csql = "Select FOUND_ROWS()";
$cres = mysql_query_decide($csql) or die(mysql_error_js());
$crow = mysql_fetch_row($cres);
$TOTALREC = $crow[0];
*/

while($row_id=mysql_fetch_array($res_id))
{	
	$sub=$row_id['SUBSCRIPTION_TYPE'];
	if($sub!='BA')
	{
		$logo=$row_id['LOGO'];
		$url=$row_id['MICROSITE_URL'];
	}
	else
	{
		$logo=0;
		$url=0;
	}
	$result[]=array(
			"adv_id"=>$row_id['ADV_ID'],
			"name"=>$row_id['NAME'],
			"contact_person"=>$row_id['CONTACT_PERSON'],
			"address"=>$row_id['ADDRESS'],
			"phone"=>$row_id['PHONE'],
			"email"=>$row_id['EMAIL'],
			"descpn"=>$row_id['DESCPN'],
			"logo"=>$logo,
			"url"=>$url,
			"category"=>get_category($row_id['CATEGORY']));
}

/*
if ($j)
	$cPage = ($j/$PAGELEN) + 1;
else
	$cPage = 1;

pagelink($PAGELEN,$TOTALREC,$cPage,$LINKNO,$data,"index.php",'',$param);
$no_of_pages=ceil($TOTALREC/$PAGELEN);
*/

/************************************************************************************************************************
CHANGED BY	:SHAKTI
CHANGE DATE	:16 SEPTEMBER, 2005
REASON		:THE CODE BELOW PICKS UP THE CATEGORY WITH MAXIMUM LISTINGS AND PASSES IT AS A PARAMETER TO PAGELINK 
		:FUNCTION, SO THAT THE 'NEXT' BUTTON ON THE INDEX PAGE SEARCHES FOR THE LISTING IN THAT CATEGORY AND 
		:DISPLAYS IT.
*************************************************************************************************************************/
$sql="SELECT count(*) AS CNT, CATEGORY FROM `LISTINGS` WHERE STATUS='A' GROUP BY CATEGORY ORDER BY CNT DESC";
$res=mysql_query_decide($sql) or logError("Error while finding category with max listings ".mysql_error_js(),$sql);
$row=mysql_fetch_array($res);

$smarty->assign("SEARCH_NEXT",$row['CATEGORY']);

$smarty->assign("REFERER","index");
$smarty->assign('result',$result);	
$smarty->assign('WED_HEAD',$smarty->fetch('wedding_head.htm'));
$smarty->assign('WED_LEFT',$smarty->fetch('wedding_left.htm'));
$smarty->assign('WED_RIGHT',$smarty->fetch('wedding_right.htm'));
$smarty->assign('FOOT',$smarty->fetch('foot.htm'));
$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
$smarty->display('index.htm');
?>
