<?php
/**Script written by Aman Sharma for Qc-Screening Module**/
include("connect.inc");
$db=connect_misdb();

if(authenticated($checksum))
{
	if($CMDGo)
	{
		$smarty->assign("flag","1");
		$st_date=$year."-".$month."-".$day." 00:00:00";
		$end_date=$year2."-".$month2."-".$day2." 23:59:59";
		
		$sql="SELECT SCREENED_BY as scr,COUNT(*) as cnt,SUM(FIELDS_SCREENED) as OPPORTUNITIES,SUM(ERRORS) as TOT_ERR from jsadmin.SCREENING_GRADES where ENTRY_DT BETWEEN '$st_date' and '$end_date' group by scr order by scr"; 	
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				
				$scr=$row["scr"];
				$cnt=$row["cnt"];
				if(is_array($scrarr))
				{
					if(!in_array($scr,$scrarr))
					{
						$scrarr[]=$scr;
					}
				}
				else
				{
					$scrarr[]=$scr;
				}
                                                                                                                             
                        	$i=array_search($scr,$scrarr);
			        $arr[$i]["name"]=$scr;
				$arr[$i]["cnt"]=$cnt;
				$arr[$i]["tot_err"]=$row["TOT_ERR"];
				$arr[$i]["oppor"]=$row["OPPORTUNITIES"];
				
			}
		$sql="SELECT SCREENED_BY as scr,COUNT(*) as err from jsadmin.SCREENING_GRADES where ENTRY_DT BETWEEN '$st_date' and '$end_date' and ERRORS>0 group by scr order by scr";
                $res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                        while($row=mysql_fetch_array($res))
                        {
                                $scr=$row["scr"];
                                $err=$row["err"];
				$i=array_search($scr,$scrarr);
				$arr[$i]["err"]=$err;
			}
		for($i=0;$i<count($arr);$i++)
		{
			$arr[$i]["er_rate"]=round(($arr[$i]["err"]/$arr[$i]["cnt"])*100,2);
			//$arr[$i]["acc_rate"]=round((100-$arr[$i]["er_rate"]),2);
			$arr[$i]["acc_rate"]=round((($arr[$i]["oppor"]-$arr[$i]["tot_err"])/$arr[$i]["oppor"])*100,2);
		}
		$smarty->assign("arr",$arr);
		$smarty->assign("day",$day);
		$smarty->assign("month",$month);
		$smarty->assign("year",$year);
		$smarty->assign("day2",$day2);
		$smarty->assign("month2",$month2);
		$smarty->assign("year2",$year2);
		$smarty->assign("date1",my_format_date($day,$month,$year));
		$smarty->assign("date2",my_format_date($day2,$month2,$year2));
		$smarty->assign("checksum",$checksum);
		$smarty->display("qc_grades_mis.htm");
	}
	else
	{
		for($i=0;$i<12;$i++)
                {
                        $mmarr[$i]=$i+1;
                }
                for($i=0;$i<10;$i++)
                {
                        $yyarr[$i]=$i+2006;
                }
		for($i=0;$i<31;$i++)
                {
                        $ddarr[$i]=$i+1;
                }
		$date_arr=explode("-",Date("Y-m-d"));
                $smarty->assign("dd",$date_arr[2]);
                $smarty->assign("mm",$date_arr[1]);
                $smarty->assign("yy",$date_arr[0]);
		
                $smarty->assign("ddarr",$ddarr);
                $smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
                $smarty->assign("checksum",$checksum);
                $smarty->display("qc_grades_mis.htm");
	}
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
