<?php
/**
*       Function        :       get_status_color
*       Input           :       submit_time(string),time_diff(string by reference)
*       Output          :       status_color(string)
*       Description     :       gets the status color which signifies varying levels of 
				emergency to do a work
**/

$screen_time=12;

function get_status_color($submit_time,&$time_diff)
{	
	$second_time=3;//time is in hours
	$third_time=6;//time is in hours
	$first_time_color="blue";
	$second_time_color="orange";
	$third_time_color="red";
	
	$submit_time=substr($submit_time,0,-3);//removes the seconds part ":ss" from a time string	
	$current_time=date("Y-m-d H:i");
	
	//determine how much time is remaining for submission
	$time_diff_in_minutes=time_difference($current_time,$submit_time,$time_diff);
	$status_color=$first_time_color;
	if($time_diff_in_minutes<0)
	{
//		if($time_diff_in_minutes<0)		
		$time_diff="Expired";
		$status_color=$third_time_color;
	}	
/*
	elseif($time_diff_in_minutes<$third_time*60)
	{
		
	}
*/
	elseif($time_diff_in_minutes<$second_time*60)	 
		$status_color=$second_time_color;	
	return $status_color;
}

/**
*       Function        :       time_difference 
*       Input           :       time 1, time 2, reference string!! format: (YYYY-mm-dd hr:min) 
*       Output          :       time difference(string)
*       Description     :       calculates the difference between two times as input.
**/
function time_difference($t1, $t2,&$time_diff)
{
	$t1=getIST($t1);
	$t2=getIST($t2);
	if($t1==$t2)
        {
                return 0;
                exit;
        }
        if($t1>$t2)
        {
                $temp=$t1;
                $t1=$t2;
                $t2=$temp;
                $sign="-";
        }
        else
                $sign="";
	$office_hour1=9;
	$office_hour2=22;
	$office_work=$office_hour2-$office_hour1;
	$t1_year=substr($t1,0,4);
	$t1_month=substr($t1,5,2);
	$t1_day=substr($t1,8,2);
	$t1_time=substr($t1,10,15);
	$t2_year=substr($t2,0,4);
	$t2_month=substr($t2,5,2);
	$t2_day=substr($t2,8,2);
	$t2_time=substr($t2,10,15);
	$time1=$t1_month."/".$t1_day."/".$t1_year.$t1_time;	
	$time2=$t2_month."/".$t2_day."/".$t2_year.$t2_time;	
	$time1_min = strftime("%M",JSstrToTime("$time1"));
	$time1_day = strftime("%d",JSstrToTime("$time1"));
	$time1_hour = strftime("%H",JSstrToTime("$time1"));
	$time1_year = strftime("%Y",JSstrToTime("$time1"));
	$time1_month= strftime("%m",JSstrToTime("$time1"));
	$time2_min = strftime("%M",JSstrToTime("$time2"));
	$time2_day = strftime("%d",JSstrToTime("$time2"));
	$time2_hour = strftime("%H",JSstrToTime("$time2"));
	$time2_month= strftime("%m",JSstrToTime("$time2"));
	$time2_year = strftime("%Y",JSstrToTime("$time2"));
/************** Adjusting the time limits to the office hours*********************************/
	if($time1_hour<$office_hour1)
        {
                $time1_hour="0".$office_hour1;
                $time1_min="30";
        }
        elseif($time1_hour>=$office_hour2)
        {
                $time1_hour=$office_hour2;
                $time1_min="00";
        }
	if($time2_hour<$office_hour1)
        {
                $time2_hour="0".$office_hour1;
                $time2_min="30";
        }
        elseif($time2_hour>=$office_hour2)
        {
                $time2_hour=$office_hour2;
                $time2_min="00";
        }
        $date1_new=($time1_year."-".$time1_month."-".$time1_day." ".$time1_hour.":".$time1_min);
        $date2_new=($time2_year."-".$time2_month."-".$time2_day." ".$time2_hour.":".$time2_min);
	//echo $date1_new."<br>".$date2_new."<br>";
        if($date1_new==$date2_new)
        {
                return 0;
                exit;
        }

	$time1_new=($time1_year."-".$time1_month."-".$time1_day);
	$time2_new=($time2_year."-".$time2_month."-".$time2_day);
	$time1_new=date($time1_new);
	$time2_new=date($time2_new);
	
    /*********CALCULATE NUMBER OF HOLIDAYS BERTWEEN GIVEN DATES*******************/
	$sql= "SELECT count(DATE) NUM from HOLIDAY where DATE>'$time1_new' and DATE<'$time2_new'";
	$result=mysql_query_decide($sql);
	$myrow=mysql_fetch_row($result);
	$holidays=$myrow[0];
			
    /****************************************************************************/	

	if (($time1_year%4==0 && $time1_year%100 != 0) || $time1_year%400 == 0 )
		$febday=29;
	else	
		$febday=28;	
	if (($time2_year%4==0 && $time2_year%100 != 0) || $time2_year%400 == 0 )
		$febday2=29;
	else	
		$febday2=28;	
	
	$month_diff=$time1_month-$time2_month;	
		
	$minutes_diff1=0;	
	if($time1_year==$time2_year)
	{
		$month_array= array(31,$febday,31,30,31,30,31,31,30,31,30,31);
		if($time1_month==$time2_month)
		{	
			if($time1_day==$time2_day)
			{
				$minutes_diff1= ($time2_hour*60+$time2_min)-($time1_hour*60+$time1_min); 
			}
/*************Daytemp are number of days between given dates******************************/
			elseif($time1_day<$time2_day)
			{
				$daytemp=$time2_day-$time1_day-1; 
				$min1=($office_hour2*60)-($time1_hour*60)-$time1_min;
				$min2=($time2_hour*60)-($office_hour1*60)+$time2_min;
				$minutes_diff= $min1+($daytemp*$office_work*60)+$min2; 
			}
		}
		else
		{	
			if($time1_month==01 || $time1_month==03 || $time1_month==05 || $time1_month==07 || $time1_month==08 || $time1_month==10 || $time1_month==12)
			{
				$month_days=0;
				for($i=($time1_month+1);$i<$time2_month;$i++)
				 	$month_days=$month_days+$month_array[$i-1];
				$daytemp= ((31-$time1_day)+$month_days+$time2_day)-1; 
			}	
			elseif($time1_month==04  || $time1_month==06 || $time1_month==09 || $time1_month==11)
			{
				$month_days=0;
				for($i=$time1_month+1;$i<$time2_month;$i++)
					$month_days=$month_days+$month_array[$i-1];
				$daytemp= ((30-$time1_day)+$month_days+$time2_day)-1;
			}
			elseif($time1_month==02)
			{
				$month_days=0;
				for($i=$time1_month+1;$i<$time2_month;$i++)
					$month_days=$month_days+$month_array[$i-1];
				if ( ($time1_year%4==0 && $time1_year%100 != 0) || $time1_year%400 == 0 )
                	        {
					$daytemp= ((29-$time1_day)+$month_days+$time2_day)-1;
	                        }
				else
					$daytemp= ((28-$time1_day)+$month_days+$time2_day)-1; 
			}
		}
	}
/************************if YEAR1 < YEAR2**********************************************/	
	elseif($time1_year<$time2_year)
	{
		
		if($time1_month==12)
			$daytemp1=31-$time1_day;
		elseif($time1_month==11)
			$daytemp1=30-$time1_day+31;
		elseif($time1_month==10)
			$daytemp1=31-$time1_day+30+31;
		elseif($time1_month==09)
			$daytemp1=30-$time1_day+31+30+31;
		elseif($time1_month==08)
			$daytemp1=31-$time1_day+30+31+30+31;
		elseif($time1_month==07)
			$daytemp1=31-$time1_day+31+30+31+30+31;
		elseif($time1_month==06)
			$daytemp1=30-$time1_day+31+31+30+31+30+31;
		elseif($time1_month==05)
			$daytemp1=31-$time1_day+30+31+31+30+31+30+31;
		elseif($time1_month==04)
			$daytemp1=30-$time1_day+31+30+31+31+30+31+30+31;
		elseif($time1_month==03)
			$daytemp1=31-$time1_day+30+31+30+31+31+30+31+30+31;
		elseif($time1_month==02)
			$daytemp1=$febday-$time1_day_31+30+31+30+31+31+30+31+30+31;
		elseif($time1_month==01)
			$daytemp1=31-$time1_day+$febday+31+30+31+30+31+31+30+31+30+31;
		
		if($time2_month==12)
                        $daytemp2=$time2_day+31+$febday2+31+30+31+30+31+31+30+31+30-1;
                elseif($time2_month==11)
                        $daytemp2=$time2_day+31+$febday2+31+30+31+30+31+31+30+31-1;
                elseif($time2_month==10)
                        $daytemp2=$time2_day+31+$febday2+31+30+31+30+31+31+30-1;
                elseif($time2_month==09)
                        $daytemp2=$time2_day+31+$febday2+31+30+31+30+31+31-1;
                elseif($time2_month==08)
                        $daytemp2=$time2_day+31+$febday2+31+30+31+30+31-1;
                elseif($time2_month==07)
                        $daytemp2=$time2_day+31+$febday2+31+30+31+30-1;
                elseif($time2_month==06)
                        $daytemp2=$time2_day+31+$febday2+31+30+31-1;
                elseif($time2_month==05)
                        $daytemp2=$time2_day+31+$febday2+31+30-1;
                elseif($time2_month==04)
                        $daytemp2=$time2_day+31+$febday2+31-1;
                elseif($time2_month==03)
                        $daytemp2=$time2_day+31+$febday2-1;
                elseif($time2_month==02)
                        $daytemp2=$time2_day+31-1;
                elseif($time2_month==01)
                        $daytemp2=$time2_day-1;
	
		$daytemp=$daytemp1+$daytemp2;	
	}

	if($minutes_diff1 == 0)
	{
		$min1=($office_hour2*60)-($time1_hour*60)-$time1_min;
		$min2=($time2_hour*60)-($office_hour1*60)+$time2_min;
		$minutes_diff= $min1+(($daytemp-$holidays)*$office_work*60)+$min2;
		//echo "total minutes= ".$minutes_diff."        ";
		
	}
	else
		$minutes_diff= $minutes_diff1;
	
	if($minutes_diff<60)
	{
		$time_diff=$minutes_diff."mins";
		//return $time_diff;
	}
	elseif($minutes_diff>=60 && $minutes_diff<($office_work*60))
	{
		$return_hour=(int) ($minutes_diff/60);
		$return_min=$minutes_diff-($return_hour*60);
		$time_diff=$return_hour."hrs ".$return_min."mins";
		//return $time_diff;
	}
	elseif($minutes_diff>=($office_work*60))
	{
		$return_days= (int) ($minutes_diff/($office_work*60));
		$return_hours=(int) (($minutes_diff-($return_days*$office_work*60))/60);
		$return_min=$minutes_diff-(($return_days*$office_work*60)+($return_hours*60));
		$time_diff=$return_days."days ".$return_hours."hrs ".$return_min."mins";
		//return $time_diff;
		//echo $time_diff;
	}
	return $sign.$minutes_diff;
//$time_diff=time_difference("2004-10-12 11:20","2004-10-12 09:55");
//echo $time_diff;	
}

/**
*       Function        :       newtime
*       Input           :       time1(string), days(int), hours(int), minutes(int) 
*       Output          :       time(string) 
*       Description     :       Calculates new time after taking into account number of holidays 
				and office hours.
**/

function newtime($t1,$d,$h,$m)
{
//changing time t1 to GMT from EST and then addin 5hrs 30 min to convert in IST
        $t1=strftime("%Y-%m-%d %H:%M",JSstrToTime("$t1 + 10 hours 30 minutes"));
                                                                                                 
        global $db;
        $office_hour1=9;
        $office_hour2=22;
        $office_work=$office_hour2-$office_hour1;
        $t1_year=substr($t1,0,4);
        $t1_month=substr($t1,5,2);
        $t1_day=substr($t1,8,2);
        $t1_hour=substr($t1,11,2);
        if($t1_hour>=$office_hour2)
        {
                $timeadjust=0;
                $t1=strftime("%Y-%m-%d $office_hour1:30",JSstrToTime("$t1 + 1 days"));
        }
        elseif($t1_hour<$office_hour1)
        {
                $timeadjust=0;
                $t1=strftime("%Y-%m-%d $office_hour1:30",JSstrToTime("$t1"));
        }
        elseif($t1_hour>$office_hour1 && $t1_hour<$office_hour2)
        {
		list($date,$time)=explode(" ",$t1);
                list($yy,$mm,$dd)=explode("-",$date);
                list($hr,$min,$sec)=explode(":",$time);
                $t1_timestamp=mktime($hr,$min,$sec,$mm,$dd,$yy);
                $t2_timestamp=mktime($office_hour2,0,0,$mm,$dd,$yy);
                $timeadjust=$t2_timestamp - $t1_timestamp;

                $t1=strftime("%Y-%m-%d $office_hour1:30",JSstrToTime("$t1 + 1 days"));
        }
        $daygap= (int) ($h/$office_work);
        $total_days=$d+$daygap;
        $hoursgap= $h%$office_work;
        $newdate=strftime("%Y-%m-%d %H:%M",JSstrToTime("$t1 + $total_days days $hoursgap hours $m minutes"));
        list($date_new,$time_new)=explode(" ",$newdate);
        list($yy,$mm,$dd)=explode("-",$date_new);
        list($hr,$min)=explode(":",$time_new);
        $sec=0;
                                                                                                 
        $newdate=mktime($hr,$min,$sec,$mm,$dd,$yy);
        $newdate=date("Y-m-d H:i",$newdate - $timeadjust);
        $newhour=strftime("%H",JSstrToTime("$newdate"));
        if($newhour>=$office_hour2 || $newhour<$office_hour1)
        {
                $hoursgap=11+$hoursgap;
                $newdate=strftime("%Y-%m-%d %H:%M",JSstrToTime("$t1 + $total_days days $hoursgap hours $m minutes"));
        }
        $time1_new=($t1_year."-".$t1_month."-".$t1_day);
        $time2_new=strftime("%Y-%m-%d",JSstrToTime("$newdate"));
        $return_date=$newdate;
        $flag=0;
                                                                                                 
        $sql= "SELECT count(DATE) NUM from HOLIDAY where DATE>='$time1_new' and DATE<='$time2_new'";
        $result=mysql_query_decide($sql);
        $myrow=mysql_fetch_row($result);
        $holidays=$myrow[0];
        $return_date=strftime("%Y-%m-%d %H:%M",JSstrToTime("$newdate + $holidays days 0 hours 0 minutes" ));
                                                                                                 
        while($flag==0)
        {
                $sql1= "SELECT count(DATE) NUM from HOLIDAY where DATE='".strftime("%Y-%m-%d",JSstrToTime("$return_date"))."'";
                $result1=mysql_query_decide($sql1);
                $myrow1=mysql_fetch_row($result1);
                if($myrow1[0]>0)
                {
                        $return_date=strftime("%Y-%m-%d %H:%M",JSstrToTime("$return_date + 1 day"));
                }
                else
                        $flag=1;
        }

        list($ret_date,$ret_time)=explode(" ",$return_date);
        list($yy,$mm,$dd)=explode("-",$ret_date);
        list($hr,$min)=explode(":",$ret_time);
        $sec=0;
        $return_dt=mktime($hr,$min,$sec,$mm,$dd,$yy);
        $hourdiff = "+10.5";
        $timeadjust = ($hourdiff * 60 * 60);
                                                                                                 
        $return_date=date("Y-m-d H:i:s",$return_dt - $timeadjust);
                                                                                                 
        return $return_date;
}
?>
