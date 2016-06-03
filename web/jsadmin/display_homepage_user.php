<?php
/***************************************************************************************************************
* FILE NAME     : display_homepage_user.php 
* DESCRIPTION   : Displays the complete list of all users on the Home Page
* CREATION DATE : 11 August, 2005
* CREATED BY    : Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*****************************************************************************************************************/

$flag_using_php5=1;
include("connect.inc");
include("../profile/display_result.inc");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

$PAGELEN=10;
$LINKNO=10;
if(!$j )
        $j = 0;
$sno=$j+1;

if($checksum)
        $data = $checksum;
elseif($cid)
        $data = $cid;

$smarty->assign("cid",$data);

if(authenticated($data))
{
	$sql="SELECT  SQL_CALC_FOUND_ROWS s1.PROFILEID,s1.GENDER,s1.USERNAME,s1.HAVEPHOTO,s1.PRIVACY,s1.PHOTO_DISPLAY FROM newjs.HOMEPAGE_PROFILES AS s2,newjs.JPROFILE AS s1 WHERE s1.PROFILEID=s2.PROFILEID LIMIT $j,$PAGELEN";
	$res=mysql_query_decide($sql) or logError(mysql_error_js(),$sql);

	$sql_rec="SELECT FOUND_ROWS()";
	$res_rec=mysql_query_decide($sql_rec) or logError(mysql_error_js(),$sql_rec);
	$row_rec=mysql_fetch_row($res_rec);
	$TOTALREC=$row_rec[0];

	while($row=mysql_fetch_array($res))
	{
		$det[]=array(	"username"=>$row['USERNAME'],
				"gender"=>$row['GENDER'],
				"profileid"=>$row['PROFILEID'],
				"photochecksum"=>md5($row["PROFILEID"]+5)."i".($row["PROFILEID"]+5),
				"havephoto"=>$row['HAVEPHOTO'],
				"privacy"=>$row['PRIVACY'],
				"photo_display"=>$row['PHOTO_DISPLAY']);
	}

	if ($j)
		$cPage = ($j/$PAGELEN) + 1;
	else
		$cPage = 1;

	pagelink($PAGELEN,$TOTALREC,$cPage,$LINKNO,$data,"display_homepage_user.php",'','');
	$no_of_pages=ceil($TOTALREC/$PAGELEN);

	// photo
	foreach($det as $key=>$val)
	{
		$profileid_arr[] =$val['PROFILEID'];
	}
	$profileid_str =implode(",",$profileid_arr); 
	$profilePicUrls = SymfonyPictureFunctions::getPhotoUrls_nonSymfony($profileid_str,"ThumbailUrl");	//Symfony Photo Modification
	
	$smarty->assign("profilePicUrls",$profilePicUrls);		//Symfony Photo Modification
	$smarty->assign("det",$det);
	$smarty->display("display_homepage_user.htm");
}
else
{
        $msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}

?>
