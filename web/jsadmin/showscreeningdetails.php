<?php
/**
*       Included        :       connect.inc
*       Description     :       contains functions related to database connection and login authentication
**/
include ("connect.inc");

	$sql="select SUBMITED_TIME,PROFILEID,USERNAME,ALLOT_TIME from jsadmin.MAIN_ADMIN where ALLOTED_TO='$operator' and STATUS='$status'";
	$result=mysql_query_decide($sql);
	while($myrow=mysql_fetch_array($result))
	{
		$month_submit=substr($myrow["SUBMITED_TIME"],5,2);
		//echo "month : " $month_submit;
		if($month==$month_submit)
		{
			$user_arr[] = array("USERNAME" => $myrow["USERNAME"],
					 "PROFILEID" => $myrow["PROFILEID"],
					 "ALLOT_TIME"=> $myrow["ALLOT_TIME"],
					 "SUBMITED_TIME"=> $myrow["SUBMITED_TIME"]
					 );
		}			 
	}
	$smarty->assign("user_arr",$user_arr);
	$smarty->display("showscreeningdetails.tpl");
?>
