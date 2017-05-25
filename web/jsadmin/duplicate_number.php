<?php

/**************************************************************************************
  *       Filename        :       duplicate_number.php
  *       Mantis          :       4781 (Duplicate profile handling)
  *       Description     :       The table will contain all the profiles entered so far that share the same mobile and/or land line number with required details.
  *       Created by      :       Anurag Gautam
***************************************************************************************/

include("connect.inc");

$db_slave = connect_slave();
$db_master = connect_db();

ini_set('max_execution_time','0');
ini_set("memory_limit","-1");

mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_slave);
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_master);

if(authenticated($cid))
{
	$name= getname($cid);
        $smarty->assign("name",$name);

	/* Pagination Functions Starts */
	
	$limit=20;
	if(!isset($page))
	{
		$page=1;
	}
	$start=($page-1)*$limit;

	$sql_page="SELECT * FROM jsadmin.DUPLICATE_NUMBER_PROFILE";
	$res_page=mysql_query_decide($sql_page);
	$total_record=mysql_num_rows($res_page);
	$results_to_show = 20;
	
	if(!$whereis)
		$whereis=1;
	$whereis=$page;
	pagination($whereis,$total_record,$results_to_show,$MORE_URL);
	
	if($default=='yes')
		$smarty->assign('DEFAULT_PAGINATION','1');	

	/* End of Pagination */

	if($default=='yes')
	{
		$sql="SELECT * FROM jsadmin.DUPLICATE_NUMBER_PROFILE LIMIT $start,$limit";
		$res= mysql_query($sql,$db_slave) or die(mysql_error($db_slave));
		while($row=mysql_fetch_array($res))
		{
			$pid[]=$row['PROFILEID'];
			$username[]=$row['USERNAME'];
			$entry_date[]=$row['ENTRY_DT'];

			$mob=$row['PHONE_MOB'];
			$mob=str_replace("+91","",$mob);
			$mobArr[]=$mob;

			$phone=$row['PHONE_RES'];
			$std=$row['STD'];
			//$landline[]=$std."-".$phone;
			$landline[]=$phone;
			$last_login[]=$row['LAST_LOGIN_DT'];
			$gender[]=$row['GENDER'];
			$rel[]=$row['RELATION'];
			$sub[]=$row['SUBSCRIPTION'];

			$caste=$row['CASTE'];
			$temp=label_select("CASTE",$caste);
			$label[]=$temp[0];
			
			$score[]=$row['SCORE'];
		}
	}
	
	if($sorting=='yes')
	{
		if($sort=='date')
		{
			$sql_3="SELECT *,IF(DUPLICATE_TYPE='M',PHONE_MOB,PHONE_RES) AS A FROM jsadmin.DUPLICATE_NUMBER_PROFILE ORDER BY DUPLICATE_TYPE,A $sortby,ENTRY_DT $sortby LIMIT $start,$limit";
			$res_3= mysql_query($sql_3,$db_slave) or die(mysql_error($db_slave));

			$smarty->assign("sortby",$sortby);
			$smarty->assign("sorting",$sorting);
			$smarty->assign("sort",$sort);
			$smarty->assign("col","DATE");
		}
		elseif($sort=='score')
		{
			$sql_3="SELECT *,IF(DUPLICATE_TYPE='M',PHONE_MOB,PHONE_RES) AS A FROM jsadmin.DUPLICATE_NUMBER_PROFILE ORDER BY DUPLICATE_TYPE,A $sortby,SCORE $sortby LIMIT $start,$limit";
			$res_3= mysql_query($sql_3,$db_slave) or die(mysql_error($db_slave));

			$smarty->assign("col","SCORE");
			$smarty->assign("sortby",$sortby);
			$smarty->assign("sorting",$sorting);
			$smarty->assign("sort",$sort);
		}
		elseif($sort=='gender')
		{
			$sql_3="SELECT *,IF(DUPLICATE_TYPE='M',PHONE_MOB,PHONE_RES) AS A FROM jsadmin.DUPLICATE_NUMBER_PROFILE ORDER BY DUPLICATE_TYPE,A $sortby,GENDER $sortby LIMIT $start,$limit";
			$res_3= mysql_query($sql_3,$db_slave) or die(mysql_error($db_slave));

			$smarty->assign("sortby",$sortby);
			$smarty->assign("col","GENDER");
			$smarty->assign("sorting",$sorting);
			$smarty->assign("sort",$sort);
		}
		elseif($sort=='caste')
		{
			//$sql_array="SELECT * FROM jsadmin.DUPLICATE_NUMBER_PROFILE ORDER BY DUPLICATE_TYPE,CASTE $sortby LIMIT $start,$limit";
			$sql_array="SELECT PROFILEID,IF(DUPLICATE_TYPE='M',PHONE_MOB,PHONE_RES) AS A FROM jsadmin.DUPLICATE_NUMBER_PROFILE ORDER BY DUPLICATE_TYPE,A $sortby,CASTE $sortby LIMIT $start,$limit";
			$res_array= mysql_query($sql_array,$db_slave) or die(mysql_error($db_slave));
			while($row_array=mysql_fetch_array($res_array))
			{
				$pro_id[]=$row_array['PROFILEID'];
			}
			$pro_id_new=implode(",",$pro_id);

			$sql_3="SELECT A.*,IF(DUPLICATE_TYPE='M',PHONE_MOB,PHONE_RES) AS C FROM jsadmin.DUPLICATE_NUMBER_PROFILE AS A, newjs.CASTE AS B WHERE A.CASTE = B.VALUE AND A.PROFILEID IN ($pro_id_new) ORDER BY A.DUPLICATE_TYPE,C $sortby,SORTBY $sortby";
			$res_3= mysql_query($sql_3,$db_slave) or die(mysql_error($db_slave));

			$smarty->assign("col","CASTE");
			$smarty->assign("sortby",$sortby);
			$smarty->assign("sorting",$sorting);
			$smarty->assign("sort",$sort);
		}
		$smarty->assign('REST_PAGINATION','1');

		while($row_3=mysql_fetch_array($res_3))
		{
			$pid[]=$row_3['PROFILEID'];
			$username[]=$row_3['USERNAME'];
			$entry_date[]=$row_3['ENTRY_DT'];

			$mob=$row_3['PHONE_MOB'];
			$mob=str_replace("+91","",$mob);
			$mobArr[]=$mob;

			$phone=$row_3['PHONE_RES'];
			$std=$row_3['STD'];
			//$landline[]=$std."-".$phone;
			$landline[]=$phone;

			$last_login[]=$row_3['LAST_LOGIN_DT'];
			$gender[]=$row_3['GENDER'];
			$rel[]=$row_3['RELATION'];
			$sub[]=$row_3['SUBSCRIPTION'];

			$caste=$row_3['CASTE'];
			$temp=label_select("CASTE",$caste);
			$label[]=$temp[0];
			
			$score[]=$row_3['SCORE'];
		}
	}
	
	/* Assigning value */

	$smarty->assign("PID",$pid);
	$smarty->assign("RELATIONSHIP",$rel);
	$smarty->assign("MOBILE",$mobArr);
	$smarty->assign("LANDLINE",$landline);
	$smarty->assign("CASTE",$label);
	$smarty->assign("PAID",$sub);
	$smarty->assign("USERNAME",$username);
	$smarty->assign("ENTRY_DATE",$entry_date);
	$smarty->assign("LAST_LOGIN",$last_login);
	$smarty->assign("GENDER",$gender);
	$smarty->assign("SCORE",$score);

	$profilechecksum=md5($profileid)."i".($profileid);
	$smarty->assign("PROFILECHECKSUM",$profilechecksum);
        $smarty->assign("cid",$cid);
	$smarty->display("duplicate_number.htm");
}
else
{
	$msg="Your session has been timed out<br>";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";
	$smarty->assign("MSG",$msg);
	$smarty->display("jsadmin_msg.tpl");
}

function pagination($whereis,$total_record,$results_to_show,$MORE_URL='')
{
	global $smarty;
	
	if($total_record >0)
	{
		$show=$total_record%20;
		if(!$show)
		{
			$show=20;
		}
		$pages=ceil($total_record/20);
		if($whereis==1)
			$prev=0;
		else
			$prev=$whereis-1;
		
		if($pages==$whereis)
			$next=0;
		else
			$next=$whereis+1;
	
		pagesarr($whereis,$pages);
		$smarty->assign("whereis",$whereis);
		$smarty->assign("next",$next);
		$smarty->assign("prev",$prev);
		$smarty->assign("pages",$pages);
		$smarty->assign("MORE_URL",$MORE_URL);
	}
}

function pagesarr($n,$l)
{
	global $smarty;
	//1 The current page of the search results is the first populated. (n)
        $arr[]=$n;

        //2 : The 5 pages before and after the current page are populated next, given that these are real numbers. (n-5…n…n+5)
        $start=( ($n-5) < 1 ? 1 : ($n-5) );
        $end=( ($n+5) > $l ? $l : ($n+5) );
        for($i=$start;$i<=$end;$i++)
        {
                $arr[]=$i;
        }

        //3 Next, the first 10 pages and then the last 10 pages are populated. (1…10 | n-5…n…n+5 | last-9…last)
        $start=1;
        $end=( ($l>9) ? 10 : ($l-9) );
        for($i=1;$i<=$end;$i++)//3
        {
                $arr[]=$i;
        }

        $start=( (($l-9)>1) ? ($l-9) : 1);
        $end=$l;
        for($i=$start;$i<=$end;$i++)
        {
                $arr[]=$i;
        }

        //4 Next the space between pages 10 and n-5 is divided into 10 equal parts and 10 page numbers are populated by rounding them off.
        $a=$n-5-10;
        if($a>20)       
        {
                $start=$a/10;
                $end=$n-5;
                for($i=$start;$i<$end;$i=$i+$start)
                {
			$tempK=round(10+$i);
			if($tempK<=$n-4)
			{
	                        $arr[]=$tempK;
			}
                }
        }
	
        //5 The space between pages n+5 and last-9 are divided into 10 equal parts and 10 page numbers are populated by rounding them off
        $a=$l-9-($n+5);
        if($a>20)
        {
                $start=$a/10;
                $end=$l-9;
                for($i=$start;$i<$end;$i=$i+$start)
                {
			$tempK=round(($n+5)+$i);
			if($tempK<=$l-8)
			{
	                        $arr[]=$tempK;
        	                $arr111[]=$tempK;
			}
                }
        }
        $arr=array_unique($arr);
        sort($arr);
	$smarty->assign("total_pages",$arr);
}



