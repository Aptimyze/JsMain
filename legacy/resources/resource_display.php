<?php
/*********************************************************************************************
* FILE NAME   : resource_display.php
* DESCRIPTION : Displays the list of resources under a category with option to administrator
		to edit or delete any resource in the list               
* MODIFY DATE        : 14 May, 2005
* MODIFIED BY        : Rahul Tara
* REASON             : Addition of page links
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

//print_r($_POST);
//print_r($_GET);     
include("../jsadmin/connect.inc");
include ("../crm/display_result.inc");
$PAGELEN=30;
$LINKNO=10;
$START=1;
if (!$j )
        $j = 0;

$sno=$j+1;


if(authenticated($cid))
{
	if(!isset($CatId))
                $CatId = $flag;
	$sql = "Select * from newjs.RESOURCES_CAT where CAT_ID=$CatId";
	$result = mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
	$myrow = mysql_fetch_array($result);
	$cat_display = $myrow["CAT_DISPLAY"];
	$smarty->assign("CAT_DISPLAY",$cat_display);
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->assign("CID",$cid);

	if(!isset($CatId))
		$CatId = $flag;
	if(isset($page))
	{
		$sql = "select * from newjs.RESOURCES_DETAILS where CAT_ID =$CatId and VISIBLE = 'y' and PAGE ='$page' Order by sortby Limit 0,$PAGELEN" ;
		$result=mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
		while($myrow = mysql_fetch_array($result))
		{
			$values[] = array(      "ID"=>$myrow["ID"],
                                        "CAT_ID" => $myrow["CAT_ID"],
                                        "TITLE" => $myrow["NAME"],
                                        "EMAIL" => $myrow["EMAIL"],
                                        "NAME" => $myrow["CONTACT_NAME"],
                                        "LINK" => $myrow["LINK"],
                                        "DESCR" => $myrow["DESCR"],
                                        "VISIBLE" =>'Y' ) ;
		}
		//print_r($values);
		$sql = "select * from newjs.RESOURCES_DETAILS where CAT_ID =$CatId and VISIBLE = 'y' and PAGE ='$page' Order by sortby Limit $PAGELEN,1000" ;
                $result=mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
                while($myrow = mysql_fetch_array($result))
                {
                        $values[] = array(      "ID"=>$myrow["ID"],
                                        "CAT_ID" => $myrow["CAT_ID"],
                                        "TITLE" => $myrow["NAME"],
                                        "EMAIL" => $myrow["EMAIL"],
                                        "NAME" => $myrow["CONTACT_NAME"],
                                        "LINK" => $myrow["LINK"],
                                        "DESCR" => $myrow["DESCR"],
                                        "VISIBLE" =>'N' ) ;
                }
		//print_r($values);
		$smarty->assign("ROWS",$values);   
		$smarty->display("resources_admin_details1.htm"); 
	
	}
	else
	{
		$sql = "Select count(*) from newjs.RESOURCES_DETAILS where CAT_ID =$CatId Order by ID Desc";
		$result=mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());
		$myrow = mysql_fetch_row($result);
		$TOTALREC = $myrow[0];

		$sql = "Select * from newjs.RESOURCES_DETAILS where CAT_ID =$CatId Order by sortby LIMIT $j,$PAGELEN" ;
		$result = mysql_query_decide($sql) or die("$sql<br>".mysql_error_js());

		while ($myrow = mysql_fetch_array($result))
		{
			if($myrow["VISIBLE"] == 'y')
				$visible_val = "yes";
			elseif($myrow["VISIBLE"] == 'n')
				$visible_val = "no";
			elseif($myrow["VISIBLE"] == 't')
				$visible_val = "new";
			else
				$visible_val = "NA";

			$values[] = array(	"ID"=>$myrow["ID"],
						"CAT_ID" => $myrow["CAT_ID"],
						"TITLE" => $myrow["NAME"],
						"EMAIL" => $myrow["EMAIL"],
						"NAME" => $myrow["CONTACT_NAME"],
						"LINK" => $myrow["LINK"],
						"DESCR" => $myrow["DESCR"],
						"VISIBLE" =>$visible_val ) ;
		   
		}


		//print_r($values);

		if($j)
			$cPage = ($j/$PAGELEN) + 1;
		else
			$cPage = 1;


		$smarty->assign("ROWS",$values);   
		pagelink($PAGELEN,$TOTALREC,$cPage,$LINKNO,$cid,"resource_display.php","",$CatId);
		$smarty->assign("COUNT",$TOTALREC);
		$smarty->assign("CURRENTPAGE",$cPage);
		$no_of_pages=ceil($TOTALREC/$PAGELEN);
		$smarty->assign("NO_OF_PAGES",$no_of_pages);
		$smarty->display("resources_admin_details.htm"); 
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
