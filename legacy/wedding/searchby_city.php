<?
/*********************************************************************************************
* FILE NAME     : searchby_city.php
* DESCRIPTION   : Derives Data from Main table for given CATRGORY AND CITY
* CREATION DATE : 3 September, 2005
* CREATED BY    : NIKHIL TANDON
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

include('connect.inc');
include('display_result.inc');
include('common_func_inc.php');

$db=connect_db();

$PAGELEN=5;
$LINKNO=10;
if(!$j )
        $j = 0;
$sno=$j+1;

populate_head();
populate_left();

$params="&category=".$category."&city=".$city;
maStripVARS("stripslashes");
if ($city=='ALL')
{
	$sql_id="SELECT SQL_CALC_FOUND_ROWS l.* FROM wedding_classifieds.LISTINGS l where l.CATEGORY='$category' AND l.STATUS='A' LIMIT $j,$PAGELEN";
}
else
{
	$sql_id="SELECT SQL_CALC_FOUND_ROWS l.* FROM wedding_classifieds.LISTINGS l where l.CATEGORY='$category' AND l.STATUS='A' AND l.CITY='$city' LIMIT $j,$PAGELEN";
}

$res_id=mysql_query_decide($sql_id,$db) or logError("Error while fetching data ".mysql_error_js(),$sql_id);

$csql = "Select FOUND_ROWS()";
$cres = mysql_query_decide($csql) or die(mysql_error_js());
$crow = mysql_fetch_row($cres);
$TOTALREC = $crow[0];

$smarty->assign("RECORDCOUNT",$TOTALREC);
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
			"paid"=>$row_id['PAID'],//not being used in htm file
			"logo"=>$logo,
			"url"=>$url,
			"status"=>$row_id['STATUS'],
			"category"=>get_category($row_id['CATEGORY'])
			);
}

//For the template
if ($city!='ALL')
	$flag_city=1;//'In city' is to be displayed
else
	$flag_city=0;//'In city' is not to be displayed
	
$smarty->assign('flag_city',$flag_city);
$smarty->assign('CITY',$city);
$smarty->assign('CATEGORY',$category);
$smarty->assign('category_name',get_category($category));

$place=label_select('CITY_INDIA',$city);
$smarty->assign('city_name',$place[0]);


if ($j)
        $cPage = ($j/$PAGELEN) + 1;
else
        $cPage = 1;
                                                                                                                            
pagelink($PAGELEN,$TOTALREC,$cPage,$LINKNO,$data,"searchby_city.php",'',$params);
$no_of_pages=ceil($TOTALREC/$PAGELEN);

maStripVARS("addslashes");
$smarty->assign("TOTAL_PAGES",$no_of_pages);
$smarty->assign("THIS_PAGE",$cPage);
$smarty->assign('WED_HEAD',$smarty->fetch('wedding_head.htm'));
$smarty->assign('WED_LEFT',$smarty->fetch('wedding_left.htm'));
$smarty->assign('WED_RIGHT',$smarty->fetch('wedding_right.htm'));
$smarty->assign('result',$result);
$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
$smarty->assign('FOOT',$smarty->fetch('foot.htm'));
$smarty->display('index.htm');

?>
