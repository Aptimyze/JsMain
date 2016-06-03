<?php
/*********************************************************************************************
* FILE NAME	: addAds.php
* DESCRIPTION	: Adds listings chosen by the admin to the landing page of Wedding Gallery
* CREATION DATE	: 14 September, 2005
* CREATED BY	: Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

include("connect.inc");
//$db=connect_db();
include("common_func_inc.php");

$data=authenticated($cid);
$smarty->assign("cid",$cid);

if(isset($data))
{
	if($submit)
	{
		$listings = explode(",",$LISTINGS);
                        for($a=0;$a<count($listings);$a++)
                        {
                                $listings1[$a]=trim($listings[$a]);
                        }
                        $ads=implode("','",$listings1);

		$sql="INSERT IGNORE INTO wedding_classifieds.LANDPAGE_ID SELECT ADV_ID FROM wedding_classifieds.LISTINGS WHERE NAME IN ('".$ads."')";
		$res=mysql_query_decide($sql) or die("Error while inserting into LANDPAGE_ID ".mysql_error_js());
		$cnt = mysql_affected_rows_js();
                                                                                                                            
		if($cnt==0)
		{
			$msg="The Listing already exists on the Landpage<br>  ";
		}
		else
		{
			$msg="$cnt Username has been added successfully<br>  ";
		}

		$msg .="<a href=\"mainAds.php?cid=$cid\">";
		$msg .="Go To Manage Wedding Gallery page </a>";
		$smarty->assign("MSG",$msg);
		$smarty->display("jsadmin_msg.tpl");

	}
	else
	{
		$smarty->display("addAds.htm");
	}
}
else
{
        $msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
