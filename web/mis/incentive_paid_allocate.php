<?php
ini_set("max_execution_time","0");
ini_set("memory_limit","32M");

/**************************************************************************************************************************
*       FILE NAME        :incentive_paid_allocate.php 
*       CREATED BY       :Lavesh Rawat 
*       FILE DESCRIPTION :This MIS gives us scorewise breakup of users registered in a particular month and and is of which 
			  country and city(in case of india) 
*       FILES INCLUDED   :connect.inc
**************************************************************************************************************************/
include("connect.inc");
$db2=connect_misdb();
                                                                                                                             
$flag = 0;
$scorearr=array("600","550","500","450","400","350","300","250","200","150","100","50","0");

$i = 0;
$ii = 0;

$sql="SELECT PROFILEID,CITY_RES,COUNTRY_RES FROM newjs.JPROFILE WHERE ACTIVATED IN ('Y','H','D')";
$res=mysql_query_decide($sql,$db2) or die("$sql".mysql_error_js($db2));
while($myrow=mysql_fetch_array($res))
{
	$profileid=$myrow['PROFILEID'];
	$country=$myrow['COUNTRY_RES'];	

        $sql1="SELECT SCORE FROM incentive.MAIN_ADMIN_POOL WHERE PROFILEID='$profileid'" ;
	$res1=mysql_query_decide($sql1,$db2) or die("$sql1".mysql_error_js($db2));
	$row=mysql_fetch_array($res1);	
	$score=$row['SCORE'];
	$score=get_round_score($score);
	if(!$score)
                $score=0;
        $score=array_search($score,$scorearr);

	$country=$myrow['COUNTRY_RES'];
	if($country=='51')
	{
		$city=$myrow['CITY_RES'];
		$india_flag=1;
		$set='I';
		$myrow=label_select('CITY_NEW',$city,"newjs");
		$city=$myrow[0];
		if(!$city)
			$city='Others';
		if(is_array($all_city))
		{
			if(!in_array($city,$all_city))
			{
				$all_city[$i]=$city;
				$i++;
			}
		}
		else
		{
			$all_city[$i]=$city;
			$i++;
		}

	}
	else
	{
		$set='0';
		$city='';
		$myrow=label_select("COUNTRY",$country,"newjs");
		$country=$myrow[0];
		if(!$country)
			$country="Others";

		if(is_array($other_country))
		{
			if(!in_array($country,$other_country))
			{
				$other_country[$ii]=$country;
				$ii++;
			}
		}
		else
		{
			$other_country[$ii]=$country;
			$ii++;
		}

	}

$sql1=" SELECT COUNT(*) as cnt FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid'";
$res1=mysql_query_decide($sql1,$db2) or die("$sql1".mysql_error_js($db2));
$row1=mysql_fetch_array($res1);
if($row1['cnt']>'0')
{
	$allocated='Y';
}
else
{
	$allocated='';
}
$sql2="SELECT COUNT(*) as cnt FROM billing.PURCHASES WHERE PROFILEID='$profileid' AND STATUS='DONE'";
$res2=mysql_query_decide($sql2,$db2) or die("$sql2".mysql_error_js($db2));
$row2=mysql_fetch_array($res2);

if($set=='I')
	$j=array_search($city,$all_city);//$j is index of city in all_city
else
	$m=array_search($country,$other_country);//$m is the index of country in array other_country

if($row2['cnt']>'0')
{
	$paid='Y';

	if($allocated=='Y')
	{
		if($set=='I')
		{
			$paid_and_allocated[0][$j][$score]++;
			$total_paid_allocated[0][$j]+=1;
		}
		else
		{
			$paid_and_allocated[1][$m][$score]++;
			$total_paid_allocated[1][$m]+=1;
		}
	}
	else
	{	
		if($set=='I')
		{
			$paid_but_not_allocated[0][$j][$score]++;
			$total_paid_not_allocated[0][$j]+=1;
		}
		else
		{
			$paid_but_not_allocated[1][$m][$score]++;
			$total_paid_not_allocated[1][$m]+=1;
		}
	}
}	
else
{
	$paid='N';

	if($allocated=='Y')
	{	
		if($set=='I')
		{
			$notpaid_but_allocate[0][$j][$score]++;
			$total_not_paid_allocated[0][$j]+=1;
		}
		else
		{
			$notpaid_but_allocate[1][$m][$score]++;
			$total_not_paid_allocated[1][$m]+=1;
		}
	}
	else
	{
		if($set=='I')
		{
			$notpaid_and_not_allocated[0][$j][$score]++;
			$total_nothing[0][$j]+=1;
		}
		else
		{
			$notpaid_and_not_allocated[1][$m][$score]++;
			$total_nothing[1][$m]+=1;
		}
	}
}

//Test Condition
//if($k++>17)
//	break;
}

for ($k= 0;$k < count($all_city);$k++)
{
for ($j =0;$j < count($scorearr); $j++)
{
	$total_india_notpaid_notallocated[$j]+=$notpaid_and_not_allocated[0][$k][$j];
	$total_india_notpaid_but_allocate[$j]+=$notpaid_but_allocate[0][$k][$j];
	$total_india_paid_and_allocated[$j]+=$paid_and_allocated[0][$k][$j];
	$total_india_paid_but_not_allocated[$j]+=$paid_but_not_allocated[0][$k][$j];		
}
}

for ($l=0;$l< count($total_india_paid_and_allocated); $l++)
{
//Array for india
$total[0]+=$total_india_paid_and_allocated[$l];
$total[1]+=$total_india_paid_but_not_allocated[$l];
$total[2]+=$total_india_notpaid_but_allocate[$l];
$total[3]+=$total_india_notpaid_notallocated[$l];
}



for($k= 0;$k < count($other_country);$k++)
{
for ($j =0;$j < count($scorearr); $j++)
{
	$total_other_notpaid_notallocated[$j]+=$notpaid_and_not_allocated[1][$k][$j];
	$total_other_notpaid_but_allocate[$j]+=$notpaid_but_allocate[1][$k][$j];
	$total_other_paid_and_allocated[$j]+=$paid_and_allocated[1][$k][$j];
	$total_other_paid_but_not_allocated[$j]+=$paid_but_not_allocated[1][$k][$j];
}
}
for ($l=0;$l< count($total_other_paid_and_allocated); $l++)
{
//Array for other countries.
$total[4]+=$total_other_paid_and_allocated[$l];
$total[5]+=$total_other_paid_but_not_allocated[$l];
$total[6]+=$total_other_notpaid_but_allocate[$l];
$total[7]+=$total_other_notpaid_notallocated[$l];
}


if($india_flag)
{
$smarty->assign("INDIA",'Y');
$smarty->assign("all_city",$all_city);
$smarty->assign("country",$country);
$smarty->assign("total_india_notpaid_notallocated",$total_india_notpaid_notallocated);
$smarty->assign("total_india_notpaid_but_allocate",$total_india_notpaid_but_allocate);
$smarty->assign("total_india_paid_and_allocated",$total_india_paid_and_allocated);
$smarty->assign("total_india_paid_but_not_allocated",$total_india_paid_but_not_allocated);
unset($total_india_notpaid_notallocated);
unset($total_india_notpaid_but_allocate);
unset($total_india_paid_and_allocated);
unset($total_india_paid_but_not_allocated);
unset($all_city);
unset($country);
}
if($other_country)
{
$smarty->assign("Others",'Y');
$smarty->assign("other_country",$other_country);
$smarty->assign("total_other_notpaid_notallocated",$total_other_notpaid_notallocated);
$smarty->assign("total_other_notpaid_but_allocate",$total_other_notpaid_but_allocate);
$smarty->assign("total_other_paid_and_allocated",$total_other_paid_and_allocated);
$smarty->assign("total_other_paid_but_not_allocated",$total_other_paid_but_not_allocated);
unset($other_country);
unset($total_other_notpaid_notallocated);
unset($total_other_paid_and_allocated);
unset($total_other_notpaid_but_allocate);
unset($total_other_paid_but_not_allocated);
}
if((!$india_flag)&&(!$other_country))
$smarty->assign("no_record",'1');
else
//Common array used by indian city + other country . so defined seperately.
{
$smarty->assign("total_paid_allocated",$total_paid_allocated);
$smarty->assign("total_paid_not_allocated",$total_paid_not_allocated);
$smarty->assign("total_not_paid_allocated",$total_not_paid_allocated);
$smarty->assign("total_nothing",$total_nothing);
$smarty->assign("total",$total);
$smarty->assign("paid_and_allocated",$paid_and_allocated);
$smarty->assign("notpaid_but_allocate",$notpaid_but_allocate);
$smarty->assign("paid_but_not_allocated",$paid_but_not_allocated);
$smarty->assign("notpaid_and_not_allocated",$notpaid_and_not_allocated);
unset($total_india_paid_but_not_allocated);
unset($total_paid_allocated);
unset($total_paid_not_allocated);
unset($total_nothing);
unset($paid_and_allocated);
unset($notpaid_but_allocate);
unset($notpaid_and_not_allocated);
unset($total_paid);
unset($total);
}
$smarty->assign("scorearr",$scorearr);
$smarty->display("incentive_paid_allocate.htm");

?>
