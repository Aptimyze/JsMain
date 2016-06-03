<?php
/**************************************************************************************************************************
* FILE NAME	: contactus_wedding_directory.php
* DESCRIPTION	: Allows people at the backend to View all people details who are Contacting Us
* CREATION DATE	: 9 September, 2005
* CREATED BY	: Nikhil Tandon
* Copyright  2005, InfoEdge India Pvt. Ltd.
**************************************************************************************************************************/

include("connect.inc");
include("common_func_inc.php");
include('../profile/display_result.inc');

//$db=connect_db();
if($cid)
{
	$data=$cid;
}
else
{
	$data=$checksum;
}

$smarty->assign("cid",$data);

if(authenticated($data))
{	
	$PAGELEN=5;
	$LINKNO=10;
	if(!$j )
	        $j = 0;
	$sno=$j+1;
	
	$sql="SELECT SQL_CALC_FOUND_ROWS * FROM wedding_classifieds.CONTACTUS ORDER BY DATE LIMIT $j,$PAGELEN ";
	$res=mysql_query_decide($sql) or die(mysql_error_js()."<BR>".$sql);

	$csql = "Select FOUND_ROWS()";
	$cres = mysql_query_decide($csql) or die(mysql_error_js());
	$crow = mysql_fetch_row($cres);
	$TOTALREC = $crow[0];
		
	while($row=mysql_fetch_array($res))
	{
		$result[]=array(	"sno"=>$row['SNO'],
		                        "name"=>$row['NAME'],
					"email"=>$row['EMAIL'],
					"phone"=>$row['CONTACT_NUM'],
					"address"=>$row['CONTACT_ADD'],
                		        "requirement"=>$row['REQUIREMENT'],
					"time"=>$row['DATE']);
	}
	$smarty->assign("result",$result);
	if ($j)
        	$cPage = ($j/$PAGELEN) + 1;
	else
	        $cPage = 1;
                                                                                                 
	pagelink($PAGELEN,$TOTALREC,$cPage,$LINKNO,$data,"contactus_wedding_directory.php",'','');
	$no_of_pages=ceil($TOTALREC/$PAGELEN);

	$smarty->display("contactus_wedding_directory.htm");
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
