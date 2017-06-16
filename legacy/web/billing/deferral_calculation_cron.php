<?php
/**************************************************************************************************************************
Filename     :  deferral_calculation_cronphp
Description  :  Cron to populate deferral related tables and to do necessary calculations
Created On   :  25 Nov 2008
Created By   :  Neha Verma
***************************************************************************************************************************/

include("../jsadmin/connect.inc");
$db=connect_db();
$sql="SELECT DISTINCT(BILLID) FROM billing.PAYMENT_DETAIL WHERE DEFERRAL_FLAG='X'";
$res=mysql_query_decide($sql) or die(mysql_error());
while($row=mysql_fetch_array($res))
{
        $billid_arr[]=$row['BILLID'];
}
$billid_arr=array_unique($billid_arr);
$billid_str=implode(",",$billid_arr);
if(is_array($billid_arr))
{
	$sql="DELETE FROM billing.DEFERRAL_REQUIREMENT_DISTRIBUTION WHERE BILLID IN ($billid_str)";
	mysql_query_decide($sql) or die(mysql_error());

        $sql="SELECT SID,BILLID,SERVICEID, MONTH(START_DATE) as msd,  YEAR(START_DATE) as ysd, MONTH(END_DATE) as med,  YEAR(END_DATE) as yed,  MONTH(SUBSCRIPTION_START_DATE) as msub_sd,  YEAR(SUBSCRIPTION_START_DATE) as ysub_sd, MONTH(SUBSCRIPTION_END_DATE) as msub_ed,  YEAR(SUBSCRIPTION_END_DATE) as ysub_ed,START_DATE,END_DATE,SUBSCRIPTION_START_DATE,SUBSCRIPTION_END_DATE, SHARE, CUR_TYPE, DEFERRABLE,NET_AMOUNT FROM billing.PURCHASE_DETAIL WHERE BILLID IN ($billid_str) ";
        $result = mysql_query($sql) or die(mysql_error(). $sql);
        while($row = mysql_fetch_array($result))
        {
                $sid = $row['SID'];
                $billid=$row['BILLID'];
                $req=$amount=$row['NET_AMOUNT'];
                $serviceid=$row['SERVICEID'];
                $missing_sd=0;
                if($row['msd']!=0)      //START_DATE, END_DATE exist
                {
                        $sm = $row['msd'];
                        $sy = $row['ysd'];
                        $em = $row['med'];
                        $ey = $row['yed'];
                        $st_dt=$row['START_DATE'];
                        $end_dt=$row['END_DATE'];

                }
                else                 
		{
			$sm = $row['msub_sd'];
                        $sy = $row['ysub_sd'];
                        $em = $row['msub_ed'];
                        $ey = $row['ysub_ed'];
                        $st_dt=$row['SUBSCRIPTION_START_DATE'];
                        $end_dt=$row['SUBSCRIPTION_END_DATE'];
                        $missing_sd=1;
                }

                $left = 0;

                if($row['DEFERRABLE']=='N')     //non deferrable product
                {
                        insert_distribution($sid,$billid,$serviceid,$sy,$sm,$req,$req);
                }
                else                            //deferrable product with all details
                {
                        $serviceduration = getTimeDiff($st_dt,$end_dt);

                        $i=0;
                        for($y=$sy;$y<=$ey;$y++)
                        {
                                if($y==$sy)     //for starting year
                                        $m = $sm;
                                else
                                        $m = 1;
                                if($y<$ey)
                                {
                                        $end_month = 12;
                                }
                                else
                                {
                                        $end_month = $em;
                                }
                                for(;$m<=$end_month;$m++)
				 {
                                        $insert[$i]['year']=$y;
                                        $insert[$i]['month']=$m;
                                        $deferredduration[$i] = days_month($st_dt,$end_dt,$m,$y);
                                        $i++;
                                }
                        }
                        if($i==0)       //less than 1 month
                        {
                                insert_distribution($psid,$trans_id,$prod_id,$sy,$sm,$req,$req);
                        }
                        else
                        {
                                for($c=0;$c<$i;$c++)
                                {
					if($c==$i-1)
					{
						$req=$amount-$left;
						$left=$amount;
					}
					else
					{
                                        	$req=  round((($deferredduration[$c] * $amount)/ $serviceduration),2);
                                        	$left= $left+$req;
					}
                                        insert_distribution($sid,$billid,$serviceid,$insert[$c]['year'],$insert[$c]['month'],$req,$left);
                                }
                        }
                }
        }

	$sql="DELETE FROM billing.DEFERRAL_TRANSACTION_DISTRIBUTION  WHERE BILLID IN ($billid_str)";
	mysql_query_decide($sql) or die(mysql_error());

	$sql="DELETE FROM billing.DEFERRAL_BROUGHT_FORWARD WHERE BILLID IN ($billid_str)";
	mysql_query_decide($sql) or die(mysql_error());


	$count=count($billid_arr);
	for($i=0;$i<$count;$i++)
	{
		$billid=$billid_arr[$i];
		unset($service_share);
		//get each service's share
		$sql2="SELECT SID, SHARE FROM billing.PURCHASE_DETAIL WHERE BILLID='$billid'";
		$res2 = mysql_query($sql2) or die(mysql_error(). $sql2);
		while($row2 = mysql_fetch_array($res2))
		{
			$sid = $row2['SID'];
			$service_share[$sid] = $row2['SHARE'];
		}

		 //get payment details STATUS='done' for this transaction
		$sql = "SELECT RECEIPTID, ENTRY_DT, AMOUNT, MONTH( ENTRY_DT ) AS mnt, YEAR( ENTRY_DT ) AS year FROM billing.PAYMENT_DETAIL WHERE BILLID='$billid' AND STATUS='DONE' ORDER BY ENTRY_DT ASC";
		$result = mysql_query($sql) or die(mysql_error(). $sql);
		while($srow = mysql_fetch_array($result))
		{
			$receiptid = $srow['RECEIPTID'];
			$rcv_amount=$srow['AMOUNT'];
			$rcv_date = $srow['year']."-".$srow['mnt']."-01";
			$rcv_year = $srow['year'];
			$rcv_month = $srow['mnt'];
			//allocate to each service
			foreach($service_share as $sid => $share)
			{
				//calculate service's collection from this receipt
				$allocate = round($service_share[$sid]*$rcv_amount/100,2);

				$sum=0;$diff=0;$loop=0;
				//get all present or future (DATE>='$rcv_date') requirement details for this Service 
				$sub_sql="SELECT ID, SID, DATE,SERVICEID,YEAR(DATE) year, MONTH(DATE) month, AMOUNT_REQUIRED, AMOUNT_LEFT FROM billing.DEFERRAL_REQUIREMENT_DISTRIBUTION WHERE DATE>='$rcv_date' AND SID='$sid' AND AMOUNT_LEFT>0 ORDER BY DATE ASC";
				$sub_result = mysql_query($sub_sql) or die(mysql_error(). $sub_sql);
				if(mysql_num_rows($sub_result))
				{
					$bf=0;
					while(($sub_row = mysql_fetch_array($sub_result)) && $allocate>0)
					{
						$id=$sub_row['ID'];
						$sid = $sub_row['SID'];
						$serviceid = $sub_row['SERVICEID'];
						$date = $sub_row['DATE'];
						$year = $sub_row['year'];
						$month = $sub_row['month'];
						$amount_left=$sub_row['AMOUNT_LEFT']-$diff;
						$cf=0;$assign=0;
						if($allocate>$amount_left)
						{
							$new_left=0;
							$new_allocate= $allocate-$amount_left;
						}
						else
						{
							$new_left=$amount_left-$allocate;
							$new_allocate=0;
						}
						if(!$loop)      //first iteration
						{
							if($rcv_year==$year && $rcv_month==$month)      //make allocation only for receipt month year.
							{
								$assign = $allocate-$new_allocate;
								$cf = $new_allocate;
							}
							else    //receipt date is lesser
							{
								$assign=0;
								$cf=$allocate;
								$bf=$allocate-$new_allocate;
								$sql_insert="INSERT INTO billing.DEFERRAL_BROUGHT_FORWARD (SID,BILLID, SERVICEID, RECEIPTID, FROM_DATE, DATE, BROUGHT_FORWARD) VALUES ('$sid','$billid','$serviceid','$receiptid','$rcv_date','$date','$bf')";
								mysql_query($sql_insert) or die(mysql_error(). $sql_insert);
							}
							//insert details(collection, assign,carryforward) for collection month
							$sql_insert="INSERT INTO billing.DEFERRAL_TRANSACTION_DISTRIBUTION (SID, BILLID, SERVICEID, RECEIPTID, DATE, COLLECTION, ASSIGN, CARRY_FORWARD) VALUES ('$sid','$billid','$serviceid','$receiptid','$rcv_date','$allocate','$assign','$cf')";
							mysql_query($sql_insert) or die(mysql_error(). $sql_insert);
							$loop=1;
						}
						else
						{
							//insert into brought forward
							$bf = $allocate-$new_allocate;
							$sql_insert="INSERT INTO billing.DEFERRAL_BROUGHT_FORWARD (SID, BILLID, SERVICEID, RECEIPTID, FROM_DATE, DATE, BROUGHT_FORWARD) VALUES ('$sid','$billid','$serviceid','$receiptid','$rcv_date','$date','$bf')";
							mysql_query($sql_insert) or die(mysql_error(). $sql_insert);
						}
						$sub_amount=$allocate-$new_allocate;
						//update all requirements for this service where DATE>=$rcv_date
						$sql_update ="UPDATE billing.DEFERRAL_REQUIREMENT_DISTRIBUTION SET AMOUNT_LEFT=AMOUNT_LEFT-$sub_amount WHERE DATE>='$date' AND SID='$sid'";
						mysql_query($sql_update) or die(mysql_error(). $sub_update);
						$diff=$diff+$allocate-$new_allocate;
						$allocate=$new_allocate;        //ressign $allocate
					 }
					if($allocate>0) //case where paid amount is greater than transaction amount
					{
						$bf = $allocate;
						$sql_insert="INSERT INTO billing.DEFERRAL_BROUGHT_FORWARD (SID, BILLID, SERVICEID, RECEIPTID, FROM_DATE, DATE, BROUGHT_FORWARD,FLAG) VALUES ('$sid','$billid','$serviceid','$receiptid','$rcv_date','$date','$bf','Y')";
						mysql_query($sql_insert) or die(mysql_error(). $sql_insert);
					}
				}
				else
				{
					//previously required
					//assign whole amount in this month
					$sql_insert="INSERT INTO billing.DEFERRAL_TRANSACTION_DISTRIBUTION (SID, BILLID, SERVICEID, RECEIPTID, DATE, COLLECTION, ASSIGN, CARRY_FORWARD) VALUES ('$sid','$billid','$serviceid','$receiptid','$rcv_date','$allocate','$allocate','0')";
					mysql_query($sql_insert) or die(mysql_error(). $sub_insert);
				}
			}
		}
	}
}

// changing the update_flag to N after all tables have been updated
$sql="UPDATE billing.PAYMENT_DETAIL SET DEFERRAL_FLAG='M' WHERE BILLID IN ($billid_str)";
//mysql_query($sql) or die(mysql_error(). $sql);

//send_email("aman.sharma@jeevansathi.com",'neha.verma@jeevansathi.com','Deferral Cron Sripts','Scripts run successfully !');


function insert_distribution($sid,$billid,$serviceid,$year,$month,$required,$left)
{
        $date=$year.'-'.$month."-01";
        $sql="INSERT INTO billing.DEFERRAL_REQUIREMENT_DISTRIBUTION(SID, BILLID, SERVICEID, YEAR, MONTH, DATE, AMOUNT_REQUIRED, AMOUNT_LEFT) VALUES('$sid','$billid','$serviceid','$year','$month','$date','$required','$left')";
        $result = mysql_query($sql) or die(mysql_error(). $sql);
}

function getlastdayofmonth($mm,$yy)
{
        if($mm<10)
                $mm="0".$mm;

        switch($mm)
        {
                case '01' : $ret='31';
                        break;
                case '02' :
                        $check=date("L",mktime(0,0,0,$mm,31,$yy));
                        if($check)
                                $ret='29';
                        else
                                $ret='28';
                        break;
                case '03' : $ret='31';
                        break;
                case '04' : $ret='30';
                        break;
                case '05' : $ret='31';
                        break;
                case '06' : $ret='30';
                        break;
                case '07' : $ret='31';
                        break;
                case '08' : $ret='31';
                        break;
                case '09' : $ret='30';
                        break;
                case '10' : $ret='31';
                        break;
                case '11' : $ret='30';
                        break;
                case '12' : $ret='31';
                        break;
	 }
        return $ret;
}

function days_month($date1,$date2,$month,$year)
{
//echo $date1."<br>".$date2."<br>".$month."<br>".$year."<br>";
        if(strlen($month)<2)
                $month="0".$month;
        if($date1>$date2)
                return 0;
        $lastdayofmonth=getlastdayofmonth($month,$year);
        $month_end_date=$year."-".$month."-".$lastdayofmonth;
        $month_start_date=$year."-".$month."-01";
        $month_end_date_timestamp= mktime(0,0,0,$month,$lastdayofmonth,$year);
        $next_month_start_timestamp= $month_end_date_timestamp+(24*60*60);
        $next_month_start= date('Y-m-d',$next_month_start_timestamp);

        list($year1,$month1,$day1)=explode("-",$date1);
        list($year2,$month2,$day2)=explode("-",$date2);
        if((($year1.$month1) < ($year.$month)) and (($year2.$month2) > ($year.$month)))
                $returnvalue= $lastdayofmonth;
        else
        {
                if (($year1.$month1) == ($year.$month))
                        $returnvalue= getTimeDiff($date1,$month_end_date);
                elseif(($year2.$month2) == ($year.$month))
                        $returnvalue=  getTimeDiff($month_start_date,$date2);
                else
                        $returnvalue= 0;
        }
return $returnvalue;

}

function getTimeDiff($date1,$date2)
{
        if($date2 > $date1)
        {
                list($yy1,$mm1,$dd1)= explode("-",$date1);
                list($yy2,$mm2,$dd2)= explode("-",$date2);
                $begin_date = gregoriantojd($mm1,$dd1,$yy1);
                $end_date = gregoriantojd($mm2,$dd2,$yy2);

                return ($end_date - $begin_date) + 1;
        }
        elseif($date2 == $date1)
                return 1;
                //return 0 changed to return 1 because, when date1==date2 the service is consumed for 24 hrs, i.e 1 day.
                //return 0;
        else
                return 0;
/*
        if($date2 > $date1)
        {
                list($yy1,$mm1,$dd1)= explode("-",$date1);
                list($yy2,$mm2,$dd2)= explode("-",$date2);
                $date1_timestamp= mktime(0,0,0,$mm1,$dd1,$yy1);
                $date2_timestamp= mktime(0,0,0,$mm2,$dd2,$yy2);
                $timestamp_diff= $date2_timestamp - $date1_timestamp;
                $days_diff= $timestamp_diff / (24*60*60);
                return floor($days_diff);
        }
        elseif($date2 == $date1)
                return 0;
        else
                return 0;
*/
}

?>
