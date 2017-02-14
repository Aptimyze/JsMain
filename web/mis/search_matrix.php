<?php
/************************************************************************************************************************
*    FILENAME           : search_matrix.php
*    INCLUDED           : connect.inc
*    DESCRIPTION        : MIS for displaying searchtype + results for analysing purpose
*    CREATED BY         : lavesh
***********************************************************************************************************************/
include_once("connect.inc");
$db=connect_misdb();

$search_type_big_label=array("J" => "Cluster Search",
                "Z" => "Community Search",
                "Q" => "Quick Search",
                "A" => "Advance Search",
                "M" => "Mailer Search",
                "O" => "On-line Search",
                "1" => "Desired Partner Profile Search",
		"2" => "Reverse Partner Profile Search",
                "X" => "Next From Home Page Search",
                "E" => "E-classified Search",
                "P" => "Photo Search",
                "G" => "Post Graduation Search",
                "R" => "Cosmo Search",
                "H" => "Home Page Search",
                "N" => "NRI Search",
                "T" => "Sofware Search",
                "V" => "View Simmilar Pofile Search",
		"U" => "Blank Search Type",
		"K" => "Keyword search Type",
                "S" => "Simillar contact + home page Search");

$month_label=array("1" =>"January",
		   "2" => "February",
                   "3" => "March",
                   "4" => "April",
                   "5" => "May",
                   "6" => "June",
                   "7" => "July",
                   "8" => "August",
                   "9" => "September",
                   "10" => "October",
                   "11" => "November",
                   "12" => "December");

if(authenticated($cid))
{
	if($CMDGo)
	{
		$smarty->assign("flag",1);
                for($i=1;$i<32;$i++)
                {
                        $ddarr[]=$i;
                }

		$info=array(0,1,2,3,4,5);
		$label_info=array('LOGGED IN','LOGGED OUT','>100 REULTS','51-100 RESULTS','1-50 RESULTS','No RESULTS','Going for Original Search','Caste mapping on Zero Results','Remove Cluster on Zero Results','Jeevansathi Recomends on Zero Results');

		$st_date=$year."-".$month."-01";
		$end_date=$year."-".$month."-31";
		$i=0;
														     
                $sql="SELECT * FROM MIS.SEARCH_TYPES  WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' ORDER BY TYPE,ENTRY_DT";
                $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                while($row=mysql_fetch_array($res))
                {
			$dt=substr($row["ENTRY_DT"],8,2)-1;
			$stype=$row["TYPE"];

			if(is_array($search_type))
			{
				if(in_array($stype,$search_type))
					;
				else
				{
					$search_type[]=$stype;
					$search_type_label[]=$search_type_big_label[$stype];
					$i++;
				}
			}
			else
			{
				$search_type[]=$stype;
				$search_type_label[]=$search_type_big_label[$stype];
			}

			$myarray[$i][$dt][0]=$row["LOGGEDIN"];
			$myarray[$i][$dt][1]=$row["LOGGEDOUT"];
			$myarray[$i][$dt][2]=$row["RESULTS1"];
			$myarray[$i][$dt][3]=$row["RESULTS2"];
			$myarray[$i][$dt][4]=$row["RESULTS3"];
			$myarray[$i][$dt][5]=$row["NORESULTS"];	
			$tot_log[$dt]+=$row["LOGGEDIN"];
                }

		$sql_no_fp="SELECT COUNT,DATE FROM newjs.NO_FEATURED_PROFILE WHERE DATE BETWEEN '$st_date' AND '$end_date'";
		$res_no_fp=mysql_query_decide($sql_no_fp,$db) or die("$sql_no_fp".mysql_error_js($db));
		while($row_no_fp=mysql_fetch_array($res_no_fp))
		{
			$dt=substr($row_no_fp["DATE"],8,2)-1;
			$no_fp[$dt]+=$row_no_fp["COUNT"];
		}

		if(is_array($search_type))
		{
			$sql="SELECT NOT_USED,ENTRY_DT,STYPE FROM newjs.RELAXED_CRITERIA WHERE  ENTRY_DT BETWEEN '$st_date' AND '$end_date' ORDER BY STYPE,ENTRY_DT";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			while($row=mysql_fetch_array($res))
			{
				$dt=substr($row["ENTRY_DT"],8,2)-1;
				$stype=$row["STYPE"];

				if(is_array($temp_type))
				{
					if(in_array($stype,$temp_type))
						;
					else
					{
						$temp_type[]=$stype;
						$k=array_search($stype,$search_type);
					}
				}
				else
				{
					$temp_type[]=$stype;
					$k=array_search($stype,$search_type);
				}
				$myarray[$k][$dt][6]=$row["NOT_USED"];
			}
			$k=-1;
			unset($temp_type);
			$sql="SELECT * FROM newjs.ZERORESULTS WHERE  ENTRY_DT BETWEEN '$st_date' AND '$end_date' ORDER BY STYPE,ENTRY_DT";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			while($row=mysql_fetch_array($res))
			{
				$dt=substr($row["ENTRY_DT"],8,2)-1;
				$stype=$row["STYPE"];
	
				if(is_array($temp_type))
				{
					if(in_array($stype,$temp_type))
						;
					else
					{
		                	        $temp_type[]=$stype;
                		        	$k=array_search($stype,$search_type);
			                }
        			}
			        else
        			{
			                $temp_type[]=$stype;
				        $k=array_search($stype,$search_type);
			        }
                                                                                                                             
			        $myarray[$k][$dt][7]=$row["CASTE_MAPPING_USED"]."<br>---<br>".$row["CASTE_MAPPING"];
			        $myarray[$k][$dt][8]=$row["REMOVE_CLUSTER_USED"]."<br>---<br>".$row["REMOVE_CLUSTER"];
			        $myarray[$k][$dt][9]=$row["RECOMENDS_USED"]."<br>---<br>".$row["RECOMENDS"];
			}
			unset($temp_type);
		}

		$smarty->assign("flag",1);
                $smarty->assign("ddarr",$ddarr);
                $smarty->assign("info",$info);
                $smarty->assign("label_info",$label_info);
		$smarty->assign("myarray",$myarray);
		$smarty->assign("tot_log",$tot_log);
		$smarty->assign("no_fp",$no_fp);
		$smarty->assign("search_type",$search_type);
		$smarty->assign("search_type_label",$search_type_label);
		$smarty->assign("mis_month",$month_label[$month]);

		$smarty->display("search_matrix.htm");
	}
	
	else
	{
		for($i=0;$i<12;$i++)
                {
                        $mmarr[$i]=$i+1;
                }

                for($i=0;$i<10;$i++)
                {
                        $yyarr[$i]=$i+2007;
                }

		$smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
		$smarty->assign("cid",$cid);
		$smarty->display("search_matrix.htm");
	}
}
else
{
        $smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}

?>
