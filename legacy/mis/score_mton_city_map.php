<?php

ini_set("max_execution_time","0");
                                                                                                                             
/************************************************************************************************************************
*    FILENAME           : score_mton_city_map.php
*    INCLUDED           : connect.inc,contact.inc,payment_array.php
*    CREATED BY         : lavesh
***********************************************************************************************************************/
include("connect.inc");

$db=connect_misdb();
$db2=connect_master();
                                                                                                                             
if(authenticated($cid))
{
	$sql="SELECT MTONGUE,CITY_RES,TOTAL_POINTS,SUBSCRIPTION,E_RISHTA FROM newjs.SEARCH_FEMALE WHERE TOTAL_POINTS>49";
	//$sql="SELECT MTONGUE,CITY_RES,TOTAL_POINTS,SUBSCRIPTION FROM newjs.SEARCH_FEMALE WHERE TOTAL_POINTS in ('600','450','400','300','250','100','50')";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
	while($row=mysql_fetch_array($res))
	{
		$score=$row['TOTAL_POINTS'];
		$mtongue=$row['MTONGUE'];
		$city=$row['CITY_RES'];

		if($row['SUBSCRIPTION'] || $row['E_RISHTA']=='Y')
			$subs=1;
		else
			$subs=0;

		$sql1="SELECT MAPPING FROM newjs.SCORE_MTON_CITY_MAP WHERE CITY='$city' AND COMMUNITY='$mtongue'";
		$res1=mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js($db));
		if($row1=mysql_fetch_array($res1))
		{
			$map=$row1["MAPPING"];
			$final_array[$score][$subs][$map]+=1;
			$total_score[$score][$map]+=1;
			$row_score[$map][$subs]+=1;
			$total_row_score[$map]+=1;
			$col_score[$score][$subs]+=1;
			$total_profiles[$subs]+=1;
		}
		else
		{
			$final_array[$score][$subs][4]+=1;
			$total_score[$score][4]+=1;
			$row_score[4][$subs]+=1;
			$total_row_score[4]+=1;
			$col_score[$score][$subs]+=1;
			$total_profiles[$subs]+=1;

			/*$sqlU="UPDATE mis.SKIIPPED_CITY_MTONGUE set COUNT=COUNT+1 WHERE CITY='$city' AND MTONGUE='$mtongue'";
			mysql_query_decide($sqlU,$db2) or die("$sql1".mysql_error_js($db2));
			if(mysql_affected_rows_js()==0)
			{
				$sqli="INSERT INTO mis.SKIIPPED_CITY_MTONGUE VALUES ('','$city','$mtongue','1')";
				mysql_query_decide($sqli,$db2);
			}*/

		}
	}

	$sql="SELECT MTONGUE,CITY_RES,TOTAL_POINTS,SUBSCRIPTION,E_RISHTA FROM newjs.SEARCH_MALE WHERE TOTAL_POINTS>49";
	//$sql="SELECT MTONGUE,CITY_RES,TOTAL_POINTS,SUBSCRIPTION FROM newjs.SEARCH_MALE WHERE TOTAL_POINTS in ('600','450','400','300','250','100','50')";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
	while($row=mysql_fetch_array($res))
	{
		$score=$row['TOTAL_POINTS'];
		$mtongue=$row['MTONGUE'];
		$city=$row['CITY_RES'];

		if($row['SUBSCRIPTION'] || $row['E_RISHTA']=='Y')
			$subs=1;
		else
			$subs=0;

		$sql1="SELECT MAPPING FROM newjs.SCORE_MTON_CITY_MAP WHERE CITY='$city' AND COMMUNITY='$mtongue'";
		$res1=mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js($db));
		if($row1=mysql_fetch_array($res1))
		{
			$map=$row1["MAPPING"];
			$final_array[$score][$subs][$map]+=1;
			$total_score[$score][$map]+=1;
			$row_score[$map][$subs]+=1;
			$total_row_score[$map]+=1;
			$col_score[$score][$subs]+=1;
			$total_profiles[$subs]+=1;
		}
		else
		{
			$final_array[$score][$subs][4]+=1;
			$total_score[$score][4]+=1;
			$row_score[4][$subs]+=1;
			$total_row_score[4]+=1;
			$col_score[$score][$subs]+=1;
			$total_profiles[$subs]+=1;

			/*$sqlU="UPDATE mis.SKIIPPED_CITY_MTONGUE set COUNT=COUNT+1 WHERE CITY='$city' AND MTONGUE='$mtongue'";
			mysql_query_decide($sqlU,$db2) or die("$sql1".mysql_error_js($db2));
			if(mysql_affected_rows_js()==0)
			{
				$sqli="INSERT INTO mis.SKIIPPED_CITY_MTONGUE VALUES ('','$city','$mtongue','1')";
				mysql_query_decide($sqli,$db2);
			}*/

		}
	}

	$scorearr=array("600","450","400","300","250","100","50");
	$srcarr_label=array("A","B","C","NA","OTHERS");
	$srcarr=array("0","1","2","3","4");

	for($i=0;$i<count($scorearr);$i++)
	{
		$k=$scorearr[$i];
		$final_arr[]=$final_array[$k];
		$total_score_final[]=$total_score[$k];
		$col_score_final[]=$col_score[$k];
		$total_col_score[]=$col_score[$k][0]+$col_score[$k][1];
	}
	$total_profiles[2]=$total_profiles[0]+$total_profiles[1];

	unset($total_score);
	unset($col_score);
	unset($final_array);

	$smarty->assign("scorearr",$scorearr);
	$smarty->assign("srcarr",$srcarr);
	$smarty->assign("srcarr_label",$srcarr_label);
	$smarty->assign("final_array",$final_arr);
	$smarty->assign("total_profiles",$total_profiles);
	$smarty->assign("total_score",$total_score_final);
	$smarty->assign("col_score",$col_score_final);
	$smarty->assign("total_col_score",$total_col_score);
	$smarty->assign("row_score",$row_score);
	$smarty->assign("total_row_score",$total_row_score);
	$smarty->display("score_mton_city_map.htm");
}
else
{
        $smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}
?>
