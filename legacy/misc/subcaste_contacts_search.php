<?php
                                                                                                                             
ini_set("max_execution_time","0");

include_once("connect_db.php");
$db=connect_db();
 
populate_temp_tables();
filter_table();
actual_computation();

function filter_table()
{
	global $db;

	$sql1="DELETE FROM JPROFILE_MALE_TEMP WHERE SOUND_SUBCASTE=''";
	mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js());

	$sql1="DELETE FROM JPROFILE_FEMALE_TEMP WHERE SOUND_SUBCASTE=''";
	mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js());

	$sql1="ALTER TABLE JPROFILE_MALE_TEMP DROP SOUND_SUBCASTE";
	mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js()); 

	$sql1="ALTER TABLE JPROFILE_FEMALE_TEMP DROP SOUND_SUBCASTE";
	mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js()); 


	$sql="SELECT COUNT(*) AS CNT,SUBCASTE FROM JPROFILE_FEMALE_TEMP GROUP BY SUBCASTE HAVING CNT<5";
        $res = mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
        while($row = mysql_fetch_array($res))
	{
		$subcaste=$row["SUBCASTE"];
	        $sql1="DELETE FROM JPROFILE_FEMALE_TEMP WHERE SUBCASTE='".addslashes(stripslashes($subcaste))."'";
	        mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js());
	}

	$sql="SELECT COUNT(*) AS CNT,SUBCASTE FROM JPROFILE_MALE_TEMP GROUP BY SUBCASTE HAVING CNT<5";
        $res = mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
        while($row = mysql_fetch_array($res))
	{
		$subcaste=$row["SUBCASTE"];
	        $sql1="DELETE FROM JPROFILE_MALE_TEMP WHERE SUBCASTE='".addslashes(stripslashes($subcaste))."'";
	        mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js());
	}
}

function populate_temp_tables()
{
	global $db;

	$sql="SELECT PROFILEID,SUBCASTE,GENDER,SOUNDEX(SUBCASTE) as sound_subcaste FROM newjs.JPROFILE WHERE SUBCASTE<>''";
	$res = mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
	while($row = mysql_fetch_array($res))
	{
		$subcaste=$row["SUBCASTE"];
		if($subcaste)
		{
			$gender=$row["GENDER"];
			$pid=$row["PROFILEID"];
			$sound_subcaste=$row["sound_subcaste"];

			if($gender=='M')
				$sql1="INSERT INTO JPROFILE_MALE_TEMP VALUES('$pid','".addslashes(stripslashes($subcaste))."','$sound_subcaste')";
			else
				$sql1="INSERT INTO JPROFILE_FEMALE_TEMP VALUES('$pid','".addslashes(stripslashes($subcaste))."','$sound_subcaste')";
			mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js());
		}
	}
}

function actual_computation()
{
	global $db;
	/*$ts = time();
	$end_dt=date("Y-m-d H:i:s",$ts);
	$ts-=120*24*60*60;
	$start_dt = date("Y-m-d H:i:s",$ts);*/
                                                                                                                             
	//or for 244 from where data is calculated.
	$end_dt = "2007-03-18";
	$start_dt=date("Y-m-d",mktime(0, 0, 0, date("03"),date("18")-120,date("2007")));

	$sql_1="SELECT DISTINCT(SUBCASTE) as dsub FROM JPROFILE_FEMALE_TEMP";
        $res_1 = mysql_query_decide($sql_1,$db) or die("$sql_1".mysql_error_js());
        while($row_1 = mysql_fetch_array($res_1))
        {
                $subcaste=$row_1["dsub"];
		$sql = "SELECT a.PROFILEID FROM newjs.SEARCH_FEMALE a , JPROFILE_FEMALE_TEMP b WHERE b.SUBCASTE='".addslashes(stripslashes($subcaste))."' AND LAST_LOGIN_DT BETWEEN '$start_dt' AND '$end_dt' AND a.PROFILEID=b.PROFILEID";
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

                if (is_array($senders))
                {
                        //$exclude = ceil((10 * $sender_cnt)/100);
			$exlcude=0;
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
			$sql1 = "SELECT SUBCASTE,count(*) AS cnt FROM newjs.CONTACTS c , newjs.JPROFILE_MALE_TEMP j where c.RECEIVER = j.PROFILEID and c.SENDER IN ($sender_list) GROUP BY SUBCASTE ORDER BY cnt DESC LIMIT 10";
        	        $res1 = mysql_query_decide($sql1) or die("$sql1".mysql_error_js());
                	while($row1 = mysql_fetch_array($res1))
	                {
        	                $finalcount[$c]['COUNT']= $row1['cnt'];
                        	$top_ten_subcaste[] = $row1['SUBCASTE'];
	                        $finalcount[$c]['SUBCASTE']= $row1['SUBCASTE'];
        	                $total+= $row1['cnt'];
                	        $c++;
                	}

			if(is_array($top_ten_subcaste))
		                $pref_subcaste = implode(",",$top_ten_subcaste);

	                $final_cnt = count($finalcount);
	                for ($x =0;$x < $final_cnt;$x++)
        	        {
                	        if ($total)
                        	{
                                	$count = $finalcount[$x]['COUNT'];
	                                $subcaste1 = $finalcount[$x]['SUBCASTE'];
        	                        $per = ($count/$total) * 100;
                	                $per = round($per,2);
                        	}
				if($count>4)
				{
	                        	$sql_ins = "INSERT INTO newjs.CONTACT_SUBCASTE_RANKING_FINAL VALUES ('','".addslashes(stripslashes($subcaste))."','".addslashes(stripslashes($subcaste1))."','$count','$per')";
	        	                mysql_query_decide($sql_ins) or die("$sql_ins".mysql_error_js());
				}
			}
		}
		unset($subcaste);
		unset($top_ten_subcaste);
		unset($pid_str);
		unset($pid);
		unset($senders);
		unset($sender_list);
		unset($exclude);
		unset($finalcount);
		unset($total);
		unset($per);
		unset($count);
	}
}

	


?>
