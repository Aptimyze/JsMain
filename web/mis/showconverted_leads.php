<?php
/*******************************************************************************************o*
* FILE NAME     : order_conversion.php
* DESCRIPTION   : It provides MIS details for online member conversions.
* CREATION DATE : 4 july, 2005
* CREATED BY    : Aman Sharma
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
                                                                                                                             
                                                                                                                             
include("connect.inc");
                      
$db =$db2 =connect_master();
@mysql_select_db("billing",$db);

// privilege check Start
$privilage      =getprivilage($cid);
$priv           =explode("+",$privilage);
if(in_array("FPSUP",$priv) || in_array("SLHD",$priv) || in_array("P",$priv) || in_array("MG",$priv))
        $showAll =true;
else
        $showAll =false;
// privilege check ends 


if($yr1=='' || $mm=='')
        die("Invalid Request");

$day_of_month=$d+1;

if($day_of_month<1 || $day_of_month>31 || $mm<1 || $mm>12)
	die("Invalid Request");

if($day_of_month<10)
	$day_of_month="0".$day_of_month;
$dateSelected =$yr1."-".$mm."-".$day_of_month;

$sql ="SELECT t2.PROFILEID AS pid FROM ORDERS AS t1, PURCHASES AS t2 WHERE t1.PROFILEID = t2.PROFILEID AND t2.STATUS = 'DONE' AND t2.ENTRY_DT >= t1.ENTRY_DT AND t1.ENTRY_DT >='$dateSelected 00:00:00' AND t1.ENTRY_DT <='$dateSelected 23:59:59' AND (PAYMODE LIKE '%card%' or PAYMODE like 'paytm')";
        $res=mysql_query_decide($sql,$db) or die(mysql_error_js());
	 while($row=mysql_fetch_array($res))
         {
		$pidstr.=$row['pid'].',';
				
         }
	$l=strlen($pidstr);
	For($i=0;$i<$l-1;$i++)
	{
	$pid_str.=$pidstr[$i];
	}


	if($ctype=='N')
	{
		$pidstr='';
		 $sql1="select PROFILEID from ORDERS where ENTRY_DT >='$dateSelected 00:00:00' AND ENTRY_DT <='$dateSelected 23:59:59' AND (PAYMODE like '%card%' or PAYMODE like 'paytm')";
		if($pid_str!='')
		{
			$sql1.=" and PROFILEID not in($pid_str)";
		}
			
                        $res=mysql_query_decide($sql1,$db) or die(mysql_error_js());
	
			while($row=mysql_fetch_array($res))
         		{
                                        $pidstr.=$row['PROFILEID'].',';
         		}
	$pid_str='';

                                                                                                                             
        $l=strlen($pidstr);
        For($i=0;$i<$l-1;$i++)
        {
        $pid_str.=$pidstr[$i];
        }
	 	
		
}

if($pid_str=='')
{
	echo "no result";
}
else
{
	if($showAll)
		$sql="select t1.PROFILEID,t1.USERNAME,t1.CITY_RES,t1.COUNTRY_RES,t2.ALLOTED_TO from newjs.JPROFILE as t1 left join incentive.MAIN_ADMIN as t2 on t1.PROFILEID=t2.PROFILEID where t1.PROFILEID in($pid_str)";
	else
		$sql="select t1.PROFILEID,t1.USERNAME,t1.CITY_RES,t1.COUNTRY_RES,t2.ALLOTED_TO from newjs.JPROFILE as t1,incentive.MAIN_ADMIN as t2 where t1.PROFILEID=t2.PROFILEID and t1.PROFILEID in($pid_str) and t2.ALLOTED_TO!=''";

	$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
	 echo"<table rows=2 cols =5 border=1 bordercolor=red width=\"100%\">";
	echo"<tr bgcolor='#CFEAC6'><td><b>SNO</td><td><b>USERNAME</td><td><b>COUNTRY</td><td><b>CITY</td><td><b>ALLOTED_TO</td></tr>";
	$sno=1;
	while($row=mysql_fetch_array($res))
	{
		$pid=$row['PROFILEID'];
		$uname=$row['USERNAME'];
		$city=$row['CITY_RES'];
		$country=$row['COUNTRY_RES'];
		$alloted=$row['ALLOTED_TO'];
		$country1=label_select(COUNTRY,$country);	
		if($country=='51')
		{
			$city1=label_select(CITY_NEW,$city);
		}
		elseif($country=='128')
                {
                        $city1=label_select(CITY_NEW,$city);
                }
		else
                        $city1="";
		

		echo"<tr bgcolor='#CFEAC6'><td>$sno</td><td>$uname</td><td>$country1[0]</td><td>$city1[0]</td><td>$alloted</td></tr>";
		$sno++;
	}
echo"</table>";


	
}


?>






