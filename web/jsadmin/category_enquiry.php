<?php
/**************************************************************************************************************************
* FILE NAME	: category_enquiry.php
* DESCRIPTION	: Allows people at the backend to view all the enquiries
* CREATION DATE	: 7 September, 2005
* CREATED BY	: Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
**************************************************************************************************************************/

include("connect.inc");
//$db=connect_db();
include("common_func_inc.php");

$data=authenticated($cid);
$smarty->assign("cid",$cid);

if(isset($data))
{
	if($submit)
	{	
		if(is_array($approve))
		{
			$approve_arr=implode(',',$approve);
		}
		else
		{
			$approve_arr=$approve;
		}

		$sql="UPDATE wedding_classifieds.CATEGORY_ENQ SET STATUS='A' WHERE ID IN (".$approve_arr.")";
		$res=mysql_query_decide($sql) or die(mysql_error_js()."<BR>".$sql);

		$msg="The Enquiry has been Approved for mailing.<br>  ";
	       	$msg .="<a href=\"mainAds.php?cid=$cid\">";
		$msg .="Go to Main Page </a>";
		$smarty->assign("MSG",$msg);
		$smarty->display("jsadmin_msg.tpl");

	}
	else
	{
		$sql="SELECT * FROM wedding_classifieds.CATEGORY_ENQ WHERE SENT='N' AND STATUS='' ORDER BY ENQ_DT";
		$res=mysql_query_decide($sql) or die(mysql_error_js()."<BR>".$sql);
		while($row=mysql_fetch_array($res))
		{
			$enq_arr[]=array(	"ID"=>$row['ID'],
						"NAME"=>$row['NAME'],
						"CONTACT_NUM"=>$row['CONTACT_NUM'],
						"ADDRESS"=>$row['ADDRESS'],
						"EMAIL"=>$row['EMAIL'],
						"CATEGORY"=>get_wedding_category($row['CATEGORY']),
						"ENQ_DT"=>$row['ENQ_DT'],
						"ENQUIRY"=>$row['ENQUIRY'],
						"SENT"=>$row['SENT']);
		}

		$smarty->assign("enq",$enq_arr);
		$smarty->display("category_enquiry.htm");
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
