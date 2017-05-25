<?php
/*********************************************************************************************
* FILE NAME     : bank_main.php
* DESCRIPTION   : Displays, Adds and Modifies Banks' names, etc
* CREATION DATE : 7 June, 2005
* CREATEDED BY  : Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
include("../profile/display_result.inc");
include("../jsadmin/connect.inc");
//$db=connect_db();

$PAGELEN=10;
$LINKNO=10;
if(!$j )
	$j = 0;
$sno=$j+1;

if($checksum)
	$data = $checksum;
elseif($cid)
	$data = $cid;

if(authenticated($data))
{
	$prvl_list=getprivilage($data);
	$prvl_arr=explode("+",$prvl_list);

	if(in_array("BA",$prvl_arr))
	{	
		if($flag=="M")
		{
			$sql_mod="SELECT * FROM billing.BANK WHERE ID='$ID'";
			$res_mod=mysql_query_decide($sql_mod) or die("Error while fetching records for modification. ".mysql_error_js());
			$row_mod=mysql_fetch_array($res_mod);
			$smarty->assign("ID",$ID);
			$smarty->assign("NAME",$row_mod['NAME']);
			$smarty->assign("checksum",$data);
			$smarty->assign("flag","M");
			$smarty->display("bnk_mod.html");
		}
		else if($flag=="A")
		{
			$smarty->assign("checksum",$data);
			$smarty->assign("flag","A");
			$smarty->display("bnk_mod.html");
		}
		else
		{
			$i=0;
			$sql_sel="SELECT SQL_CALC_FOUND_ROWS * FROM billing.BANK ORDER BY NAME ";//LIMIT $j,$PAGELEN";
			$res_sql=mysql_query_decide($sql_sel) or die("Error while selecting BANK records. ".mysql_error_js());

			$csql = "Select FOUND_ROWS()";
        	        $cres = mysql_query_decide($csql) or die(mysql_error_js());
	                $crow = mysql_fetch_row($cres);
        	        $TOTALREC = $crow[0];

			while($row_sel=mysql_fetch_array($res_sql))
			{
				$dat[$i]['SNO']=$sno;
				$dat[$i]['ID']=$row_sel['ID'];
				$dat[$i]['NAME']=$row_sel['NAME'];
				$dat[$i]['UPDATED_BY']=$row_sel['UPDATED_BY'];
				$dat[$i]['UPDATED_ON']=$row_sel['UPDATED_ON'];
				$i++;
				$sno++;
			}

			 if ($j)
        	                $cPage = ($j/$PAGELEN) + 1;
                	else
	                        $cPage = 1;

        	        pagelink($PAGELEN,$TOTALREC,$cPage,$LINKNO,$data,"bank_main.php",'','');
	                $no_of_pages=ceil($TOTALREC/$PAGELEN);
                
			$smarty->assign("COUNT",$TOTALREC);
	                $smarty->assign("CURRENTPAGE",$cPage);
	                $smarty->assign("NO_OF_PAGES",$no_of_pages);
			$smarty->assign("DATA",$dat);
			$smarty->assign("checksum",$data);
			$smarty->display("bank_display.html");
		}
	}
	else
	{
		$msg="You do not have the privilage to view this data";
		$smarty->assign("MSG",$msg);
	        $smarty->display("jsadmin_msg.tpl");
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
