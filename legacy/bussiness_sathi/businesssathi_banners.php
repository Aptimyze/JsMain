<?php
/*********************************************************************************************
* FILE NAME     : businesssathi_banners.php
* DESCRIPTION   : Displays Business Sathi banners page after putting Head and Left panel in place
* FUNCTIONS	: connect_db()		: For connecting to the database server.
*		: authenticated()	: To check if the user is authenticated or not.
*		: pagelink()		: To perform paging
*		: TimedOut()		: To take action if the user is not authenticated.
* CREATION DATE : 16 June, 2005
* CREATED BY  	: Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

include("connect.inc");
$db=connect_db();

$PAGELEN=5;
$LINKNO=10;
if(!$j )
        $j = 0;
$sno=$j+1;

$data=authenticated($checksum);
$smarty->assign("CHECKSUM",$checksum);
$smarty->assign("HEAD",$smarty->fetch("business_sathi/heads.htm"));
$smarty->assign("LEFT",$smarty->fetch("business_sathi/left.htm"));

if(isset($data))
{
	$i = 0;
	$sql="SELECT SQL_CALC_FOUND_ROWS * FROM affiliate.BANNERS WHERE SIZE='$cat' ORDER BY TYPE,UPLOAD_DATE DESC LIMIT $j,$PAGELEN";
	$res=mysql_query($sql) or logError(mysql_error(),$sql);

	$csql = "Select FOUND_ROWS()";
        $cres = mysql_query($csql) or logError(mysql_error(),$sql);
        $crow = mysql_fetch_row($cres);
        $TOTALREC = $crow[0];

	while($row=mysql_fetch_array($res))
	{
		$sizes=explode("x",$row["SIZE"]);
		$dat[]=array("id"=>$row["BANNERID"],"type"=>$row["TYPE"],"url"=>$row["URL_CODE"],"size"=>$row["SIZE"],"sizew"=>$sizes[0],"sizeh"=>$sizes[1]);

		$i++;
                $sno++;
		unset($sizes);
	}

	if ($j)
	        $cPage = ($j/$PAGELEN) + 1;            //gives the number of records on each page
        else
        	$cPage = 1;
        
	pagelink($cat,$PAGELEN,$TOTALREC,$cPage,$LINKNO,$checksum,"businesssathi_banners.php",'','');
                                                                                                                            
        $no_of_pages=ceil($TOTALREC/$PAGELEN);

	$smarty->assign("COUNT",$TOTALREC);             //calculates the total number of pages
        $smarty->assign("CURRENTPAGE",$cPage);
        $smarty->assign("NO_OF_PAGES",$no_of_pages);
	$smarty->assign("dat",$dat);
	$smarty->assign("aid",$data["AFFILIATEID"]);
	$smarty->display("business_sathi/businesssathi_banners.htm");
}
else
{
	TimedOut();
}
?>
