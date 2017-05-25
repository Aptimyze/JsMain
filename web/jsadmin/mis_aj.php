<?php
/**
*       Filename        :       mis_aj.php
*       Included        :       connect.inc
*       Description     :       dislays the MIS for the photo operators
*       Created by      :       Anmol
**/

/**
*       Included        :       connect.inc
*       Description     :       contains functions related to database connection and login authentication
**/
include ("../jsadmin/connect.inc");

	//Get the operators list and their total for the year
	$sql =" SELECT SQL_CACHE USERNAME from jsadmin.PSWRDS WHERE PRIVILAGE like '%PU%' ";
	$result = mysql_query_decide($sql) or die(mysql_error_js());
	$i=0;
	while($myrow=mysql_fetch_array($result))
	{		
		$sql1="select count(*) as count from jsadmin.MAIN_ADMIN where SCREENING_TYPE='P' and ALLOTED_TO='$myrow[USERNAME]' and SUBMITED_TIME<>'0000-00-00 00:00:00'";
		$result1=mysql_query_decide($sql1) or die(mysql_error_js($sql1));
		$myrow1=mysql_fetch_array($result1);
		$keys[$myrow["USERNAME"]] = $i;
		$photo_operators[]=$myrow["USERNAME"];
		$photo_operator_total[]=$myrow1["count"];
		$i++;
	}
	
	$month_total["Jan"]	=0;
	$month_total["Feb"]	=0;
	$month_total["Mar"]	=0;
	$month_total["Apr"]	=0;
	$month_total["May"]	=0;
	$month_total["Jun"]	=0;
	$month_total["Jul"]	=0;
	$month_total["Aug"]	=0;
	$month_total["Sep"]	=0;
	$month_total["Oct"]	=0;
	$month_total["Nov"]	=0;
	$month_total["Dec"]	=0;
	//Get the total for each month
	foreach ($photo_operators as $operator) 
	{		
		$sql="select SUBMITED_TIME from jsadmin.MAIN_ADMIN where SCREENING_TYPE='P' and ALLOTED_TO='$operator' and SUBMITED_TIME<>'0000-00-00 00:00:00'";
		$result=mysql_query_decide($sql);
		while($myrow=mysql_fetch_array($result))
		{			
			$str1 =JSstrToTime($myrow["SUBMITED_TIME"]);
            $month = strftime("%b", $str1);						
            $month_total["$month"]=$month_total["$month"]+1;
		}		
	}	
	
	//Get the array for each user for each month according to approved or deleted
	foreach ($photo_operators as $operator)
	{
		/*$photo_operator_arr[$keys[$operator]]["Jan"]["APPROVED"]=0;
		$photo_operator_arr[$keys[$operator]]["Feb"]["APPROVED"]=0;
		$photo_operator_arr[$keys[$operator]]["Mar"]["APPROVED"]=0;
		$photo_operator_arr[$keys[$operator]]["Apr"]["APPROVED"]=0;
		$photo_operator_arr[$keys[$operator]]["May"]["APPROVED"]=0;
		$photo_operator_arr[$keys[$operator]]["Jun"]["APPROVED"]=0;
		$photo_operator_arr[$keys[$operator]]["Jul"]["APPROVED"]=0;
		$photo_operator_arr[$keys[$operator]]["Aug"]["APPROVED"]=0;
		$photo_operator_arr[$keys[$operator]]["Sep"]["APPROVED"]=0;
		$photo_operator_arr[$keys[$operator]]["Oct"]["APPROVED"]=0;
		$photo_operator_arr[$keys[$operator]]["Nov"]["APPROVED"]=0;
		$photo_operator_arr[$keys[$operator]]["Dec"]["APPROVED"]=0;

		$photo_operator_arr[$keys[$operator]]["Jan"]["DELETED"]=0;
		$photo_operator_arr[$keys[$operator]]["Feb"]["DELETED"]=0;
		$photo_operator_arr[$keys[$operator]]["Mar"]["DELETED"]=0;
		$photo_operator_arr[$keys[$operator]]["Apr"]["DELETED"]=0;
		$photo_operator_arr[$keys[$operator]]["May"]["DELETED"]=0;
		$photo_operator_arr[$keys[$operator]]["Jun"]["DELETED"]=0;
		$photo_operator_arr[$keys[$operator]]["Jul"]["DELETED"]=0;
		$photo_operator_arr[$keys[$operator]]["Aug"]["DELETED"]=0;
		$photo_operator_arr[$keys[$operator]]["Sep"]["DELETED"]=0;
		$photo_operator_arr[$keys[$operator]]["Oct"]["DELETED"]=0;
		$photo_operator_arr[$keys[$operator]]["Nov"]["DELETED"]=0;
		$photo_operator_arr[$keys[$operator]]["Dec"]["DELETED"]=0;
		*/
		$sql="select SUBMITED_TIME,STATUS from jsadmin.MAIN_ADMIN where ALLOTED_TO='$operator' and SCREENING_TYPE='P' and SUBMITED_TIME<>'0000-00-00 00:00:00'";
		$result=mysql_query_decide($sql);		
		if(mysql_num_rows($result)>0)
		{
			while($myrow=mysql_fetch_array($result))
			{						
				$str1 =JSstrToTime($myrow["SUBMITED_TIME"]);
                $month = strftime("%b", $str1);
				$status=$myrow["STATUS"];				
				$photo_operator_arr[$keys[$operator]][$month][$status]=$photo_operator_arr[$keys[$operator]][$month][$status]+1;
			}	
		}	
	}	
	
	//Get the complete total
	$sql="select count(*) as count from jsadmin.MAIN_ADMIN where SCREENING_TYPE='P' and SUBMITED_TIME<>'0000-00-00 00:00:00'";
	$result=mysql_query_decide($sql);
    $myrow=mysql_fetch_array($result);
    $complete_total=$myrow["count"];
	
	$smarty->assign("photo_operators",$photo_operators);
	$smarty->assign("photo_operator_arr",$photo_operator_arr);
	$smarty->assign("photo_operator_total",$photo_operator_total);
	$smarty->assign("month_total",$month_total);
	$smarty->assign("complete_total",$complete_total);	
	$smarty->display("mis.tpl");
?>
