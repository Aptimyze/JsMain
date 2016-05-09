<?php
/*********************************************************************************************
* FILE NAME	: newAff.php
* DESCRIPTION	: Displays records for Approved or New Affiliates according to the flag passed
* CREATION DATE	: 4 May, 2005
* CREATED BY	: Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

include("connect.inc");
//$db=connect_db();

$data=authenticated($cid);
$smarty->assign("cid",$cid);

if(isset($data))
{

	if(!$fl) $fl='N';

	$sql_data="select USERNAME,NAME,EMAIL,SITENAME,URL,COMPANY,AFFILIATEID,REG_DATE from affiliate.AFFILIATE_DET where STATUS='$fl'";
	$res=mysql_query_decide($sql_data) or logError("Error while selecting data from AFFILIATE_DET. ".mysql_error_js());

	while($row=mysql_fetch_array($res))
	{
			$det[]=array("uname"=>$row[0],"name"=>$row[1],"email"=>$row[2],"sitename"=>$row[3],"url"=>$row[4],"company"=>$row[5],"ID"=>$row[6],"DATE"=>$row[7]);
	}

	$smarty->assign("det",$det);
	$smarty->assign("fl",$fl);
	$smarty->display("newAff.html");
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
