<?php
/*******************************************************************************************o*
* FILE NAME     : order_conversion.php
* DESCRIPTION   : It provides MIS details for online member conversions.
* CREATION DATE : 4 july, 2005
* CREATED BY    : Aman Sharma
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
                                                                                                                             
                                                                                                                             
include("connect.inc");
                      
$db=connect_misdb();
$db2=connect_master();

// privilege check Start
$privilage	=getprivilage($cid);
$priv     	=explode("+",$privilage);
if(in_array("FPSUP",$priv) || in_array("SLHD",$priv) || in_array("P",$priv) || in_array("MG",$priv))
        $showAll =true;
else
        $showAll =false;
// privilege check ends 

@mysql_select_db("billing",$db);

if($yr1=='' || $mm=='')
        die("Invalid Request");

$day_of_month=$d+1;
$mm1=$mm+1;
$sql ="SELECT t2.PROFILEID AS pid, min( DAYOFMONTH( t1.ENTRY_DT ) ) AS dd FROM ORDERS AS t1, PURCHASES AS t2 WHERE t1.PROFILEID = t2.PROFILEID AND t2.STATUS = 'DONE' AND t2.ENTRY_DT >= t1.ENTRY_DT AND t1.ENTRY_DT >= '$yr1-$mm-01' AND t1.ENTRY_DT < '$yr1-$mm1-01' AND PAYMODE LIKE '%card%' GROUP BY t1.PROFILEID";
        $res=mysql_query_decide($sql,$db) or die(mysql_error_js());
	 while($row=mysql_fetch_array($res))
         {
                                $dd=$row['dd'];
                         	if($dd==$day_of_month)
				{	
				$pidstr.=$row['pid'].',';
				}
				
         }
	$l=strlen($pidstr);
	For($i=0;$i<$l-1;$i++)
	{
	$pid_str.=$pidstr[$i];
	}


	if($ctype=='N')
	{
		$pidstr='';
		 $sql1="select  PROFILEID,min(DAYOFMONTH(ENTRY_DT)) as dd from ORDERS where MONTH(ENTRY_DT)='$mm'  and YEAR(ENTRY_DT)='$yr1' and PAYMODE like '%card%' ";
		if($pid_str!='')
		{
			$sql1.="and PROFILEID not in($pid_str) group by PROFILEID";
		}
		else
		{
			$sql1.="group by PROFILEID";
		}
			
                         $res=mysql_query_decide($sql1,$db) or die(mysql_error_js());
	
			 while($row=mysql_fetch_array($res))
         {
                                $dd=$row['dd'];
                                if($dd==$day_of_month)
                                {
                                        $pidstr.=$row['PROFILEID'].',';
                                }
                                                                                                                             
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






