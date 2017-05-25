<?php
include("../jsadmin/connect.inc");
//print_r($_POST);
if(authenticated($cid))
{
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->assign("CID",$cid);
  
 	if($fn=='category')
	{
		if($category_name)
		{
			if($POSITION =='E'||$POSITION =='')
			{
				$sql = "SELECT MAX(SORTBY) as max FROM newjs.RESOURCES_CAT" ;
				$result = mysql_query_decide($sql,$db) or die("$sql<br>".mysql_error_js());
				$myrow = mysql_fetch_array($result);
				$max=$myrow[max];
			}
			else if($POSITION =='B')
			{
				$max =1;
				$sql = "update newjs.RESOURCES_CAT set SORTBY = SORTBY+1 "; 
				mysql_query_decide($sql) or logError($errorMsg,$sql,"ShowErrTemplate");

			}
			else 
			{
				$max = $CatId +1;
				$sql = "update newjs.RESOURCES_CAT set SORTBY = SORTBY+1 WHERE SORTBY > '$CatId'";
				mysql_query_decide($sql) or logError($errorMsg,$sql,"ShowErrTemplate");
			}
			if($Show=='')$Show = 'N';
			$sql = "insert into newjs.RESOURCES_CAT(CAT_DISPLAY,CAT_NAME,ACTIVE,SORTBY) VALUES('$category_name','".str_replace(' ','',$category_name)."','$Show','$max')";
			mysql_query_decide($sql) or logError($errorMsg,$sql,"ShowErrTemplate");
		}
		else
		{
			$sql = "Select * from newjs.RESOURCES_CAT WHERE ACTIVE='Y' ORDER BY SORTBY";
			$result = mysql_query_decide($sql,$db) or die("$sql<br>".mysql_error_js());
			while($myrow = mysql_fetch_array($result))
			{
				$values[] = array("SORTBY"=>$myrow["SORTBY"],"CAT_NAME"=>$myrow["CAT_NAME"],"CAT_DISPLAY"=>$myrow["CAT_DISPLAY"]);
			}
			$smarty->assign("ROWS",$values);
			$smarty->display("resources_admin_cat2.htm");
			die();
		}
	}
	if($fn=='viewdc') 
	{
		if($ACTIVATE)
		{
			 $sql = "update newjs.RESOURCES_CAT set ACTIVE ='Y' where CAT_ID ='$CAT_ID'";
			mysql_query_decide($sql) or logError($errorMsg,$sql,"ShowErrTemplate");

		}
		$sql = "Select * from newjs.RESOURCES_CAT where ACTIVE ='N' ORDER BY SORTBY";
		$result = mysql_query_decide($sql,$db) or die("$sql<br>".mysql_error_js());
                        while($myrow = mysql_fetch_array($result))
                        {
                                $values[] = array("CAT_ID"=>$myrow["CAT_ID"],"CAT_NAME"=>$myrow["CAT_NAME"],"CAT_DISPLAY"=>$myrow["CAT_DISPLAY"]);
                        }
			$smarty->assign("ROWS",$values);
                        $smarty->display("resources_admin_cat3.htm");
                        die();
	}
	if($fn=='da')
	{
	//	$sql = "delete from newjs.RESOURCES_CAT where CAT_ID ='$CatId'";
		$sql = "update newjs.RESOURCES_CAT set ACTIVE ='N'  where CAT_ID ='$CatId'";
                mysql_query_decide($sql) or logError($errorMsg,$sql,"ShowErrTemplate");
	//	$sql = "delete from newjs.RESOURCES_DETAILS where CAT_ID ='$CatId'";
		$sql = "update newjs.RESOURCES_DETAILS set VISIBLE = 'n' where CAT_ID ='$CatId'";
		mysql_query_decide($sql) or logError($errorMsg,$sql,"ShowErrTemplate");
	}
	else if($fn=='dc')
	{
		$sql = "update newjs.RESOURCES_CAT set ACTIVE ='N'  where CAT_ID ='$CatId'";
//		$sql = "delete from newjs.RESOURCES_CAT where CAT_ID ='$CatId'";
		mysql_query_decide($sql) or logError($errorMsg,$sql,"ShowErrTemplate");
		$sql = "update newjs.RESOURCES_DETAILS set newjs.RESOURCES_DETAILS.CAT_ID ='0',VISIBLE = 'n' where newjs.RESOURCES_DETAILS.CAT_ID = '$CatId'";
		mysql_query_decide($sql) or logError($errorMsg,$sql,"ShowErrTemplate");
	}
	$sql = "Select * from newjs.RESOURCES_CAT WHERE ACTIVE='Y' ORDER BY SORTBY";
	$result = mysql_query_decide($sql,$db) or die("$sql<br>".mysql_error_js());
	if($fn=='pending')
	{
		while($myrow = mysql_fetch_array($result))
	        {
			$values[] = array("CAT_ID"=>$myrow["CAT_ID"],"CAT_NAME"=>$myrow["CAT_NAME"],"CAT_DISPLAY"=>$myrow["CAT_DISPLAY"]);
		}
		$smarty->assign("ROWS",$values);
		if($perform=='Delete')
		{
			$sql = "delete from newjs.RESOURCES_DETAILS where ID='$ID'";
		//	$sql = "update newjs.RESOURCES_DETAILS set VISIBLE = 'n' where ID='$ID'";
			mysql_query_decide($sql) or logError($errorMsg,$sql,"ShowErrTemplate");	
		}
		else if($perform=='Submit')
		{
			$sql = "update newjs.RESOURCES_DETAILS set CAT_ID ='$CatId' ,PAGE ='$pageno', VISIBLE = 'y',SORTBY=(select SORTBY+1 from (SELECT SORTBY FROM newjs.RESOURCES_DETAILS order by SORTBY DESC limit 1)as x) where ID='$ID'";
			mysql_query_decide($sql) or logError($errorMsg,$sql,"ShowErrTemplate");
			$sql ="Select count(*) as cnt from newjs.RESOURCES_DETAILS where CAT_ID =$CatId and PAGE ='$pageno' and  VISIBLE = 'y'";//to be checked
			$result = mysql_query_decide($sql,$db) or die("$sql<br>".mysql_error_js());
			$myrow = mysql_fetch_array($result);
			if($myrow[cnt] >30)
			{
				echo "<html><head><script>alert('No. of entries on page-$pageno exceeds more than 30.Hence,this entry can not be shown on the live page');</script></head></html>";
			}
		}
		$sql ="Select * from newjs.RESOURCES_DETAILS where CAT_ID = '0' order by SORTBY";
		$result = mysql_query_decide($sql,$db) or die("$sql<br>".mysql_error_js());
		$i=0;
		 while($myrow = mysql_fetch_array($result))
                {
			$pended[] = array("EMAIL"=>$myrow['EMAIL'],"TITLE"=>$myrow['NAME'],"NAME"=>$myrow['CONTACT_NAME'],"LINK"=>$myrow['LINK'],"DESCR"=>$myrow['DESCR'],"ID"=>$myrow['ID']);
		}	
		$smarty->assign("pended",$pended);
		$smarty->display("resources_admin_cat1.htm");
		die();
	}
      
	while($myrow = mysql_fetch_array($result))
	{
		$sql = "Select count(*) as COUNT from newjs.RESOURCES_DETAILS where CAT_ID = $myrow[CAT_ID]";
		$result_count = mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$myrow_count = mysql_fetch_array($result_count);
		$count = $myrow_count['COUNT'];
		$values[] = array("CAT_ID"=>$myrow["CAT_ID"],"CAT_NAME"=>$myrow["CAT_NAME"],"CAT_DISPLAY"=>$myrow["CAT_DISPLAY"],"COUNT" =>$count);
		$smarty->assign("VALUES",$values);

	}
  
	$smarty->display("resources_admin_cat.htm");
}
else
{
	$msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"../jsadmin/index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
