<?php
ini_set("max_execution_time","0");

/************************************************************************************************************************
*    FILENAME           : sim_caste_mtongue_contacts_search.php
*    DESCRIPTION        : caste-mtongue mapping based on contacts
*    CREATED BY         : lavesh
***********************************************************************************************************************/

include_once("connect_db.php"); //for 244
$db=connect_db();


                                                                                                                             
function label_select($columnname,$value)
{
        $sql = "select SQL_CACHE LABEL from $columnname WHERE VALUE='$value'";
        $res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
        $myrow= mysql_fetch_row($res);
        return $myrow;
                                                                                                                             
}
                                                                                                                             
$j = 0;

/*$ts = time();
$end_dt=date("Y-m-d H:i:s",$ts);
$ts-=120*24*60*60;
$start_dt = date("Y-m-d H:i:s",$ts);*/

//or for 244 from where data is calculated.
$end_dt = "2007-03-18";
$start_dt=date("Y-m-d",mktime(0, 0, 0, date("03"),date("18")-120,date("2007")));
                                                                                                                             


//removing top 5% of receivers getting contacts.
//These profile may be (attractive profile, good looking etc)
$sql="SELECT MAX(ID) as MID FROM newjs.TEMP_SEARCH_MALE";
$res = mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
$row = mysql_fetch_array($res);
$mid=$row["MID"];

$mid=round($mid*0.05);

$sql="SELECT PROFILEID FROM newjs.TEMP_SEARCH_MALE ORDER BY COUNT DESC LIMIT $mid";
$res = mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
while($row = mysql_fetch_array($res))
{
	$ignored_rec.=$row["PROFILEID"].',';
}
$ignored_rec=rtrim($ignored_rec,',');


$sql_caste = "SELECT VALUE,ISALL,SMALL_LABEL FROM newjs.CASTE";
$res_caste = mysql_query_decide($sql_caste,$db) or die("$sql_caste".mysql_error_js());
while($row = mysql_fetch_array($res_caste))
{
        if ($row['ISALL'] == 'Y')
        {
                $isall[] = $row['VALUE'];
        }
        elseif($row['SMALL_LABEL'] == 'Others')
        {
                $isall[] = $row['VALUE'];
        }
        else
                $castearr[] = $row['VALUE'];
}
                                                                                                                             
$isall_caste=implode("','",$isall);
$caste_cnt = count($castearr);
                                                                                                                             
$sql_mtongue = "SELECT VALUE FROM newjs.MTONGUE";
$res_mtongue = mysql_query_decide($sql_mtongue,$db) or die("$sql_mtongue".mysql_error_js());
while($row_mtongue = mysql_fetch_array($res_mtongue))
{
        $mtonguearr[] = $row_mtongue['VALUE'];
}
$mtongue_cnt = count($mtonguearr);

for($k=0;$k<$mtongue_cnt;$k++)
{
	for ($i = 0;$i < $caste_cnt;$i++)
	{
		$sql = "SELECT PROFILEID FROM newjs.SEARCH_FEMALE WHERE CASTE = '$castearr[$i]' AND MTONGUE='$mtonguearr[$k]' AND LAST_LOGIN_DT BETWEEN '$start_dt' AND '$end_dt'";
	        $res = mysql_query_decide($sql) or die("Error while retrieving data from newjs.JPROFILE".mysql_error_js());

	        while($row=mysql_fetch_array($res))
		{
			$pid[] = $row['PROFILEID'];
		}
		if (is_array($pid))
			$pid_str = implode(",",$pid);
																     
		if ($pid_str)
		{
			$sql_contact = "SELECT COUNT( * ) AS cnt, SENDER FROM newjs.CONTACTS WHERE SENDER IN ($pid_str) GROUP BY SENDER ORDER BY cnt ASC";
			$res_contact = mysql_query_decide($sql_contact,$db) or die("$sql_contact".mysql_error_js());
			while($row_contact = mysql_fetch_array($res_contact))
                	{
                        	$senders[]=$row_contact['SENDER'];
	                }
        	}
                                                                                                                             
	        $sender_cnt = count($senders);

		//removing top 10 senders as they can be spammers.
	        if (is_array($senders))
        	{
                	$exclude = ceil((10 * $sender_cnt)/100);
	        }
        	$exc_count = $sender_cnt - $exclude;
	        for ($j =0;$j < $exc_count;$j++)
        	{
                	$sender_list.="'".$senders[$j]."'".",";
	        }
        	$sender_list=substr($sender_list,0,-1);
		
		if ($sender_list)
        	{
               		$c = 0;

	                $sql1 = "SELECT CASTE, count(*) AS cnt FROM newjs.CONTACTS c,newjs.TEMP_CASTE_MTONGUE_PID j where c.RECEIVER NOT IN ($ignored_rec) AND c.RECEIVER = j.PROFILEID and c.SENDER IN ($sender_list)  AND j.CASTE NOT IN ('$isall_caste') GROUP BY j.CASTE ORDER BY cnt DESC LIMIT 10";
        	        $res1 = mysql_query_decide($sql1) or die("$sql1".mysql_error_js());
                	while($row1 = mysql_fetch_array($res1))
	                {
        	                $finalcount[$c]['COUNT']= $row1['cnt'];
                        	$top_ten_caste[] = $row1['CASTE'];
                        	$caste_val = label_select('CASTE',$row1['CASTE']);
	                        $finalcount[$c]['CASTE']= $caste_val[0];
        	                $total+= $row1['cnt'];
                	        $c++;
                	}
			if($top_ten_caste)
	                	$pref_caste = implode(",",$top_ten_caste);

        	        $final_cnt = count($finalcount);
                	for ($x =0;$x < $final_cnt;$x++)
			{
                	        $m = 0;
				$caste = $finalcount[$x]['CASTE'];
				$castelabel = label_select('CASTE',$castearr[$i]);
				$mtonguelabel = label_select('MTONGUE',$mtonguearr[$k]);
	                        $sql_mtongue = "SELECT COUNT(*) AS cnt, j.MTONGUE, j.CASTE FROM newjs.CONTACTS c, newjs.TEMP_CASTE_MTONGUE_PID j where c.RECEIVER NOT IN ($ignored_rec) AND  c.RECEIVER = j.PROFILEID and c.SENDER IN ($sender_list) AND j.CASTE = '$top_ten_caste[$x]' GROUP BY j.MTONGUE ORDER BY cnt DESC LIMIT 10";
        	                $res_mtongue = mysql_query_decide($sql_mtongue) or die("$sql_mtongue".mysql_error_js());
                	        while($row_mtongue=mysql_fetch_array($res_mtongue))
                        	{
                                	$mtongue_caste[$x][$m]['CNT'] =  $row_mtongue['cnt'];
					$mtongue_caste[$x][$m]['CASTE']=$caste;
	                                $mtongue_val=label_select('MTONGUE',$row_mtongue['MTONGUE']);
					$mtongue_caste[$x][$m]['MTONGUE']=$mtongue_val[0];
	                                $total1[$x]+= $row_mtongue['cnt'];
        	                        $m++;
                	        }
                	}

	                for ($x =0;$x < $final_cnt;$x++)
	                {
				$cnt = count($mtongue_caste[$x]);
				for ($m = 0;$m < $cnt;$m++)
				{
					if ($total1[$x])
					{
						$CNT = $mtongue_caste[$x][$m]['CNT'];
						$percent = ($CNT/$total) * 100;
						$percent = round($percent,2);
					}
					$associated_caste=$mtongue_caste[$x][$m]['CASTE'];
					$associated_mtongue=$mtongue_caste[$x][$m]['MTONGUE'];
					$sql_ins1 = "INSERT IGNORE INTO newjs.CONTACT_MAPPING_RANKING VALUES ('','$castelabel[0]','$mtonguelabel[0]','$associated_caste','$associated_mtongue',$CNT,'$percent')";
                                	mysql_query_decide($sql_ins1) or die("$sql_ins1".mysql_error_js());
                        	}
                                                                                                                             
	                }
                	unset($mtongue_caste);
                                                                                                                             
        	}
		unset($mtongue_caste);
		unset($top_ten_caste);
		unset($pid_str);
		unset($pid);
		unset($senders);
		unset($sender_list);
		unset($exclude);
		unset($finalcount);
		unset($total);
		unset($per);
	}
}
?>
