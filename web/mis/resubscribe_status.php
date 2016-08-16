<?php

/************************************************************************************************
File name	:resubscribe_status.php
Created by	:Nikhil Tandon
Created on	:14th Sept 2005
Purpose		:To view The data of the people who have resubscribed with us.
		:And the total number of people who's profile has expired
***********************************************************************************************/
include("connect.inc");
$db=connect_misdb();

if(authenticated($cid))
{
        $smmarr=array('Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
	$pmmarr=array('Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
        $curryear = date("Y");
        $temp_arr=array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
        
	$yrarr=array("2005");
        $num=$curryear-$yrarr[0];//calculating differnence between th present year and 2005
        
	for($i=0;$i<$num;$i++)//dynamic initialization of year to array
        {
               $yrarr[]=$yrarr[$i]+1; 
        }
        $yrcnt=count($yrarr);
        $curdate = date("Y-m-d");
	for($ind2=1;$ind2<$yrcnt;$ind2++)//dynamic initialization of rows and coloumns
	{
	
                $year=$yrarr[$ind2];
		list($cur_year,$cur_mm,$cur_day) = explode("-",$curdate);

		if ($cur_year==$year)
		{
			for ($i=0;$i<$cur_mm;$i++)
			{
				$pmmarr[] = $temp_arr[$i];
				$smmarr[] = $temp_arr[$i];
			}
		}
		else
		{
                        for ($i=0;$i<count($temp_arr);$i++)
			{
				$pmmarr[] = $temp_arr[$i];
				$smmarr[] = $temp_arr[$i];
                        }
		}
	}//end of for


	//for branch cities....
	if($city)
        {
                $sql="SELECT VALUE FROM incentive.BRANCH_CITY WHERE NEAR_BRANCH='$city'";
                $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
                while($row=mysql_fetch_array($res))
                {
                        $cityvalarr[]=$row['VALUE'];//code of the cities
                }
                                                                                                 
                $citystr="'".implode("','",$cityvalarr)."'";
        }
		
	//query to determine how many clients services have expired that month
	if($city)
	{
		$sql="SELECT COUNT(DISTINCT PROFILEID) as cnt,MONTH(s.EXPIRY_DT) as mm, YEAR(s.EXPIRY_DT) as yy FROM billing.SERVICE_STATUS s left join newjs.JPROFILE p on s.PROFILEID=p.PROFILEID WHERE s.EXPIRY_DT BETWEEN '2005-04-01 00:00:00' AND NOW() AND p.CITY_RES IN ($citystr)";
	}
	else
	{	
		$sql="SELECT COUNT(DISTINCT PROFILEID) as cnt,MONTH(EXPIRY_DT) as mm, YEAR(EXPIRY_DT) as yy FROM billing.SERVICE_STATUS s WHERE EXPIRY_DT BETWEEN '2005-04-01 00:00:00' AND NOW()";
	}
	if($service)
	{
		$sql.="AND s.SERVICEID='$service'";
	}
	$sql.="GROUP BY yy,mm";

        $res=mysql_query_decide($sql,$db) or die(mysql_error_js());
        if($row=mysql_fetch_array($res))
        {
                do
                {
                        $cnt=$row['cnt'];
                        $yy=$row['yy'];
                        $mm=$row['mm'];
                        //exptot=total subscriptions expiring that month
                        if($yy==2005)
                        {
                                        $mm=$mm-4;
                                        $exptotal[$mm]+=$cnt;
			}
                        if($yy>2005)
                        {
				//calculating month number wrt april 2005
                                $num=($yy-2005)-1;
                                $mm=$mm+8;
                                $mm=$mm+($num*12);
                                $exptotal[$mm]+=$cnt;
                        }
                        //exp_members_total=> total count of all deactivated for all months
                        $exp_members_total+=$cnt;
                }while($row=mysql_fetch_array($res));
        }
	//till here fine

	//query for service_status:to determine how many clients have renewed their
	//subscription in that month
	
	if($city)
	{
		$sql="SELECT COUNT(DISTINCT p.PROFILEID) AS cnt, month(s.EXPIRY_DT ) AS smm, year(s.EXPIRY_DT) AS syy, month(p.ENTRY_DT) AS pmm, year(p.ENTRY_DT) AS pyy
FROM billing.SERVICE_STATUS s left join billing.PURCHASES p on s.PROFILEID = p.PROFILEID left join newjs.JPROFILE j on j.PROFILEID=p.PROFILEID
WHERE p.ENTRY_DT >= DATE_SUB(s.EXPIRY_DT,INTERVAL 15 DAY)
AND s.EXPIRY_DT > '2005-04-01 00:00:00'
AND s.ACTIVATED = 'Y' AND p.STATUS='DONE'
AND j.CITY_RES IN ($citystr)";
	}
	else
	{
		$sql="SELECT COUNT(DISTINCT p.PROFILEID) AS cnt, month(s.EXPIRY_DT ) AS smm, year(s.EXPIRY_DT) AS syy, month(p.ENTRY_DT) AS pmm, year(p.ENTRY_DT) AS pyy 
FROM billing.SERVICE_STATUS s left join billing.PURCHASES p on s.PROFILEID=p.PROFILEID
WHERE p.ENTRY_DT >= DATE_SUB(s.EXPIRY_DT,INTERVAL 15 DAY)
AND s.EXPIRY_DT > '2005-04-01 00:00:00' AND p.STATUS='DONE'
AND s.ACTIVATED = 'Y'";
	}
	if($service)
        {
                $sql.="AND s.SERVICEID='$service'";
        }
	$sql.="GROUP BY smm,syy,pmm,pyy";

	$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		do
		{
			$cnt=$row['cnt'];
			$smm=$row['smm'];//expiry month
			$syy=$row['syy'];//expiry year
			$pmm=$row['pmm'];//new entry month
			$pyy=$row['pyy'];//new entry year

			if($pyy==2005)
			{
				//march=0th month for column table
				$pmm=$pmm-3;
			}
			
			if($pyy>2005)//for years after 2005
			{
			        //number of months wrt 2005	
				$num=($pyy-2005)-1;
                                $pmm=$pmm+9;
                                $pmm=$pmm+($num*12);
			}

			if($syy==2005)//service is going to expire in 2005
			{
				//april=0th month for rows
				$smm=$smm-4;
				$total_renual_m[$smm][$pmm]+=$cnt;
			}
			if($syy>2005)//service expiring after 2005
			{
				//april=0th month for rows
				$num=($syy-2005)-1;
                                $smm=$smm+8;
				$smm=$smm+($num*12);
                                $total_renual_m[$smm][$pmm]+=$cnt;
			}
			//$ptot[$pmm]+=$cnt;//services renued
			//$stot[$smm]+=$cnt;//services expiring
			//$total_renual+=$cnt;
		}while($row=mysql_fetch_array($res));
	}

	if($city)
	{
		$sql="SELECT COUNT(DISTINCT p.PROFILEID) AS cnt, month(s.EXPIRY_DT ) AS smm, year(s.EXPIRY_DT) AS syy
FROM billing.SERVICE_STATUS s left join billing.PURCHASES p on s.PROFILEID = p.PROFILEID left join newjs.JPROFILE j on j.PROFILEID=p.PROFILEID
WHERE p.ENTRY_DT >= DATE_SUB(s.EXPIRY_DT,INTERVAL 15 DAY)
AND s.EXPIRY_DT > '2005-04-01 00:00:00'
AND s.ACTIVATED = 'Y' AND p.STATUS='DONE'
AND j.CITY_RES IN ($citystr)";
	}
	else
	{
		$sql="SELECT COUNT(DISTINCT p.PROFILEID) AS cnt, month(s.EXPIRY_DT ) AS smm, year(s.EXPIRY_DT) AS syy 
FROM billing.SERVICE_STATUS s left join billing.PURCHASES p on s.PROFILEID=p.PROFILEID
WHERE p.ENTRY_DT >= DATE_SUB(s.EXPIRY_DT,INTERVAL 15 DAY)
AND s.EXPIRY_DT > '2005-04-01 00:00:00' AND p.STATUS='DONE'
AND s.ACTIVATED = 'Y'";
	}
	if($service)
        {
                $sql.="AND s.SERVICEID='$service'";
        }
	$sql.="GROUP BY smm,syy";

	$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		do
		{
			$cnt=$row['cnt'];
			$smm=$row['smm'];//expiry month
			$syy=$row['syy'];//expiry year
			//$pmm=$row['pmm'];//new entry month
			//$pyy=$row['pyy'];//new entry year

			/*if($pyy==2005)
			{
				//march=0th month for column table
				$pmm=$pmm-3;
			}
			
			if($pyy>2005)//for 2006
			{
				$pmm=$pmm+9;
			}*/

			if($syy==2005)//service is going to expire in 2005
			{
				//april=0th month for rows
				$smm=$smm-4;
				//$total_renual_m[$smm][$pmm]+=$cnt;
			}
			if($syy>2005)//service expiring after 2005
			{
				//april=0th month for rows
				$num=($syy-2005)-1;
                                $smm=$smm+8;
				$smm=$smm+($num*12);
                                //$total_renual_m[$smm][$pmm]+=$cnt;
			}
			//$ptot[$pmm]+=$cnt;//services renued
			$stot[$smm]+=$cnt;//services expiring
			//$total_renual+=$cnt;
		}while($row=mysql_fetch_array($res));
	}

	if($city)
	{
		$sql="SELECT COUNT(DISTINCT p.PROFILEID) AS cnt, month(p.ENTRY_DT) AS pmm, year(p.ENTRY_DT) AS pyy
FROM billing.SERVICE_STATUS s left join billing.PURCHASES p on s.PROFILEID = p.PROFILEID left join newjs.JPROFILE j on j.PROFILEID=p.PROFILEID
WHERE p.ENTRY_DT >= DATE_SUB(s.EXPIRY_DT,INTERVAL 15 DAY)
AND s.EXPIRY_DT > '2005-04-01 00:00:00'
AND s.ACTIVATED = 'Y' AND p.STATUS='DONE'
AND j.CITY_RES IN ($citystr)";
	}
	else
	{
		$sql="SELECT COUNT(DISTINCT p.PROFILEID) AS cnt, month(p.ENTRY_DT) AS pmm, year(p.ENTRY_DT) AS pyy 
FROM billing.SERVICE_STATUS s left join billing.PURCHASES p on s.PROFILEID=p.PROFILEID
WHERE p.ENTRY_DT >= DATE_SUB(s.EXPIRY_DT,INTERVAL 15 DAY)
AND s.EXPIRY_DT > '2005-04-01 00:00:00' AND p.STATUS='DONE'
AND s.ACTIVATED = 'Y'";
	}
	if($service)
        {
                $sql.="AND s.SERVICEID='$service'";
        }
	$sql.="GROUP BY pmm,pyy";

	$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		do
		{
			$cnt=$row['cnt'];
			//$smm=$row['smm'];//expiry month
			//$syy=$row['syy'];//expiry year
			$pmm=$row['pmm'];//new entry month
			$pyy=$row['pyy'];//new entry year

			if($pyy==2005)
			{
				//march=0th month for column table
				$pmm=$pmm-3;
			}
			
			if($pyy>2005)//for after 2005
			{
				$num=($pyy-2005)-1;
                                $pmm=$pmm+9;
                                $pmm=$pmm+($num*12);
			}

			/*if($syy==2005)//service is going to expire in 2005
			{
				//april=0th month for rows
				$smm=$smm-4;
				$total_renual_m[$smm][$pmm]+=$cnt;
			}
			if($syy>2005)//service expiring in 2006
			{
				//april=0th month for rows
				$smm=$smm+8;
				$total_renual_m[$smm][$pmm]+=$cnt;
			}*/
			$ptot[$pmm]+=$cnt;//services renued
			//$stot[$smm]+=$cnt;//services expiring
			$total_renual+=$cnt;
		}while($row=mysql_fetch_array($res));
	}

	for($i=0;$i<count($smmarr);$i++)
	{
		//left hand table...as rows by $i
		for($j=0;$j<count($pmmarr);$j++)
		{
			//right hand table as columns by $j
			if($exptotal[$i])
			{
				$total_renual_m[$i][$j];
				$percent[$i][$j]=$total_renual_m[$i][$j]/$exptotal[$i] * 100;
				$percent[$i][$j]=round($percent[$i][$j],1);
			}
		}
		if($exptotal[$i])
		{
			$stotpercent[$i]=$stot[$i]/$exptotal[$i] * 100;
			$stotpercent[$i]=round($stotpercent[$i],1);
		}
	}

	//to derive the name of SERVICES for the drop box
	$j=0;
        $sql="SELECT NAME,SERVICEID FROM billing.SERVICES WHERE ADDON='N'";
        $res=mysql_query_decide($sql,$db) or die(mysql_error_js());
        while($row=mysql_fetch_array($res))
        {
                $servicearr[$j]['LABEL']=$row['NAME'];
                $servicearr[$j]['VAL']=$row['SERVICEID'];
                $j++;
        }
                                                                                                         
        //to derive the name of the CITY from the drop box
        $i=0;
        $sql="SELECT VALUE,LABEL FROM incentive.BRANCH_CITY WHERE NEAR_BRANCH<>'' AND NEAR_BRANCH=VALUE";
        $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
        while($row=mysql_fetch_array($res))
        {
                $cityarr[$i]['VAL']=$row['VALUE'];
                $cityarr[$i]['LABEL']=$row['LABEL'];
                $i++;
        }
        $smarty->assign("city",$city);
        $smarty->assign("cityarr",$cityarr);
	$smarty->assign("service",$service);
	$smarty->assign("servicearr",$servicearr);
	$smarty->assign("cid",$cid);
	$smarty->assign("stot",$stot);
	$smarty->assign("ptot",$ptot);
	$smarty->assign("smmarr",$smmarr);
	$smarty->assign("pmmarr",$pmmarr);
	$smarty->assign("exptotal",$exptotal);
	$smarty->assign("exp_members_total",$exp_members_total);
	$smarty->assign("total_renual_m",$total_renual_m);
	$smarty->assign("total_renual",$total_renual);
	$smarty->assign("percent",$percent);
	$smarty->assign("stotpercent",$stotpercent);
	$smarty->display("resubscribe_status.htm");
}
else
{
	$smarty->display("jsconnectError.tpl");
}
?>
