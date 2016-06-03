<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


/************************************************************************************************************************
*    FILENAME           : band_movement.php
*    INCLUDED           : connect.inc
*    DESCRIPTION        : Works in three steps:
			  1)Populate Table FEMALE_BAND & MALE_BAND based on entries in SEARCH_FEMALE &SEARCH_MALE resp.
			  2)Insert Count of moving Bands in Table BAND_MOVEMENT.
			  3)Delete entries that are older than 7 days.
                          
*    CREATED BY         : Lavesh Raawt
*    CREATED ON         : 20 JULY 2006
***********************************************************************************************************************/

ini_set("max_execution_time","0");
                                                                                                                             
include_once("connect.inc");
                                                                                                                             
$db=connect_db();
//$db2 = connect_737_lan();

populate_bandtable('F');
populate_bandtable('M');
count_moving_profileid();
//delete_records_from_band();
//count_moving_profileid_weekly();

//This function Calculate Band (range 1-16) on basis of TOTAL_POINTS and FRESHNESS_POINTS(for TOTAL_POINTS in 450 & 250 as this score lies in 2 Band and then we need to idenitfy them on basis of FRESHNESS_POINTS+TOTAL_POINTS) from newjs.SEARCH_FEMALE/newjs.SEARCH_MALE.

//The Band value along with some other info is inserted in table newjs.FEMALE_BAND/newjs.MALE_BAND 

function populate_bandtable($gender)
{
	if($gender=='F')
	{
		$table1='SEARCH_FEMALE';
		$table2='FEMALE_BAND';
	}
	else
	{
		$table1='SEARCH_MALE';
                $table2='MALE_BAND';
        }
	
	$sql="SELECT PROFILEID,FRESHNESS_POINTS,TOTAL_POINTS FROM newjs.$table1";
	//$res=mysql_query($sql,$db2) or logError($sql,$db2);
	$res=mysql_query($sql) or die(mysql_error());
															     
	while($row=mysql_fetch_array($res))
	{
		$profileid=$row['PROFILEID'];
		$total=$row['TOTAL_POINTS'];

		if($total==43)
			$band=16;
		elseif($total==44)	
			$band=15;
		elseif($total==45)
			$band=14;
		elseif($total==46)
			$band=13;
		elseif($total==47)
			$band=12;
		elseif($total==48)
			$band=11;
		elseif($total==49)
			$band=10;
		elseif($total==50)
			$band=9;
		elseif($total==100)
			$band=8;
		elseif($total==250)
		//This total value lies in 2 band so we need an extra criteria for band identification.
		{
			if($row['FRESHNESS_POINTS']==300) 
				$band=7;
			else
				$band=6;
		}
		elseif($total==300)
			$band=5;
		elseif($total==450)
		{
			if($row['FRESHNESS_POINTS']==300)
				$band=4;
			else
				$band=2;
		}
		elseif($total==400)
			$band=3;
		elseif($total==600)
			$band=1;
		else
			$band=33;//Error value
		
		$sql1= "INSERT INTO MIS.$table2 VALUES ('$profileid',now(),'$band')";
		//	mysql_query($sql) or die(mysql_error());

		$res1=mysql_query($sql1) or die(mysql_error());
	}
}

//Insert Count of moving Bands in Table BAND_MOVEMENT.COUNTF implies female movement along bands and COUNTM implies male movement along bands.

function count_moving_profileid()
{
	$start=date("Y-m-d",time()-24*60*60);
	$today=date("Y-m-d");

//-------------------------------------------------------Female------------------------------------------------------------//

	//Check for profiles that are yesterday in search table and today are a)not in search table OR b)band has changed.
	$sql="SELECT PROFILEID,BAND FROM MIS.FEMALE_BAND WHERE CHECK_DATE='$start'";
	$res=mysql_query($sql) or die(mysql_error());
	
	while($row=mysql_fetch_array($res))
        {
		$band=$row['BAND'];
		$profileid=$row['PROFILEID'];
		
		$sql1="SELECT BAND FROM MIS.FEMALE_BAND WHERE CHECK_DATE='$today' AND PROFILEID='$profileid' ";

		$res1=mysql_query($sql1) or die(mysql_error());

		$row1=mysql_fetch_array($res1);

		$new_band=$row1['BAND'];

		if($new_band=='')
		{
			$count_arr[0][$band][0]++;
			//1st index of array count_arr is 0 =>profile is Female & last index is 0=>profileid not in search table.
		}
		elseif($new_band<>$band)
		{
			$count_arr[0][$band][$new_band]++;	
		}
	}

	//Check for profiles that are present today but are absent yesterday.
	$sql="SELECT PROFILEID,BAND FROM MIS.FEMALE_BAND WHERE CHECK_DATE='$today'";
        $res=mysql_query($sql) or die(mysql_error());
                                                                                                                             
        while($row=mysql_fetch_array($res))
        {
		$profileid=$row['PROFILEID'];

		$sql1="SELECT count(*) as cnt FROM MIS.FEMALE_BAND WHERE PROFILEID='$profileid' AND CHECK_DATE ='$start' ";
		$res1=mysql_query($sql1) or die(mysql_error());

		$row1=mysql_fetch_array($res1);

		if($row1['cnt']==0)		
		{
			$new_band=$row['BAND'];
			$count_arr[0][0][$new_band]++;
		}
	}

//------------------------------------------------Male----------------------------------------------------------------//
	
	$sql="SELECT PROFILEID,BAND FROM MIS.MALE_BAND WHERE CHECK_DATE='$start'";
	$res=mysql_query($sql) or die(mysql_error());
	
	while($row=mysql_fetch_array($res))
        {
		$band=$row['BAND'];
		$profileid=$row['PROFILEID'];
		
		$sql1="SELECT BAND FROM MIS.MALE_BAND WHERE CHECK_DATE='$today' AND PROFILEID='$profileid' ";
		$res1=mysql_query($sql1) or die(mysql_error());

		$row1=mysql_fetch_array($res1);

		$new_band=$row1['BAND'];

		if($new_band=='')
		{
			$count_arr[1][$band][0]++;
			//1st index of array count_arr is 1 =>profile is male & last index is 0=>profile not in search table.
		}
		elseif($new_band<>$band)
		{
			$count_arr[1][$band][$new_band]++;	
		}
	}


	$sql="SELECT PROFILEID,BAND FROM MIS.MALE_BAND WHERE CHECK_DATE='$today'";
        $res=mysql_query($sql) or die(mysql_error());
                                                                                                                             
        while($row=mysql_fetch_array($res))
        {
		$profileid=$row['PROFILEID'];

		$sql1="SELECT count(*) as cnt FROM MIS.MALE_BAND WHERE PROFILEID='$profileid' AND CHECK_DATE ='$start' ";
		$res1=mysql_query($sql1) or die(mysql_error());

		$row1=mysql_fetch_array($res1);

		if($row1['cnt']==0)		
		{
			$new_band=$row['BAND'];
			$count_arr[1][0][$new_band]++;
		}
	}	

//------------------------------------------------Combining Logic---------------------------------------------------------//

	for($i=0;$i<17;$i++)
	{
		for($j=0;$j<17;$j++)
		{
			if($count_arr[0][$i][$j]>0 || $count_arr[1][$i][$j]>0)
			{
				$countf=$count_arr[0][$i][$j];
				$countm=$count_arr[1][$i][$j];

				//Insert into table if $countf+$countm>0
				$sql="INSERT INTO MIS.BAND_MOVEMENT VALUES ('$i','$j','$countf','$countm',now())";
				mysql_query($sql) or die(mysql_error());
			}
		}
	}			
}			


function count_moving_profileid_weekly()
{
	$start=date("Y-m-d",time()-7*24*60*60);
	$today=date("Y-m-d");

//-------------------------------------------------------Female------------------------------------------------------------//

	//Check for profiles that are week ago in search table and today are a)not in search table OR b)band has changed.
	$sql="SELECT PROFILEID,BAND FROM MIS.FEMALE_BAND WHERE CHECK_DATE='$start'";
	$res=mysql_query($sql) or die(mysql_error());
	
	while($row=mysql_fetch_array($res))
        {
		$band=$row['BAND'];
		$profileid=$row['PROFILEID'];
		
		$sql1="SELECT BAND FROM MIS.FEMALE_BAND WHERE CHECK_DATE='$today' AND PROFILEID='$profileid' ";

		$res1=mysql_query($sql1) or die(mysql_error());

		$row1=mysql_fetch_array($res1);

		$new_band=$row1['BAND'];

		if($new_band=='')
		{
			$count_arr[0][$band][0]++;
			//1st index of array count_arr is 0 =>profile is Female & last index is 0=>profileid not in search table.
		}
		elseif($new_band<>$band)
		{
			$count_arr[0][$band][$new_band]++;	
		}
	}

	//Check for profiles that are present today but are 7days ago.
	$sql="SELECT PROFILEID,BAND FROM MIS.FEMALE_BAND WHERE CHECK_DATE='$today'";
        $res=mysql_query($sql) or die(mysql_error());
                                                                                                                             
        while($row=mysql_fetch_array($res))
        {
		$profileid=$row['PROFILEID'];

		$sql1="SELECT count(*) as cnt FROM MIS.FEMALE_BAND WHERE PROFILEID='$profileid' AND CHECK_DATE ='$start' ";
		$res1=mysql_query($sql1) or die(mysql_error());

		$row1=mysql_fetch_array($res1);

		if($row1['cnt']==0)		
		{
			$new_band=$row['BAND'];
			$count_arr[0][0][$new_band]++;
		}
	}

//------------------------------------------------Male----------------------------------------------------------------//
	
	$sql="SELECT PROFILEID,BAND FROM MIS.MALE_BAND WHERE CHECK_DATE='$start'";
	$res=mysql_query($sql) or die(mysql_error());
	
	while($row=mysql_fetch_array($res))
        {
		$band=$row['BAND'];
		$profileid=$row['PROFILEID'];
		
		$sql1="SELECT BAND FROM MIS.MALE_BAND WHERE CHECK_DATE='$today' AND PROFILEID='$profileid' ";
		$res1=mysql_query($sql1) or die(mysql_error());

		$row1=mysql_fetch_array($res1);

		$new_band=$row1['BAND'];

		if($new_band=='')
		{
			$count_arr[1][$band][0]++;
			//1st index of array count_arr is 1 =>profile is male & last index is 0=>profile not in search table.
		}
		elseif($new_band<>$band)
		{
			$count_arr[1][$band][$new_band]++;	
		}
	}


	$sql="SELECT PROFILEID,BAND FROM MIS.MALE_BAND WHERE CHECK_DATE='$today'";
        $res=mysql_query($sql) or die(mysql_error());
                                                                                                                             
        while($row=mysql_fetch_array($res))
        {
		$profileid=$row['PROFILEID'];

		$sql1="SELECT count(*) as cnt FROM MIS.MALE_BAND WHERE PROFILEID='$profileid' AND CHECK_DATE ='$start' ";
		$res1=mysql_query($sql1) or die(mysql_error());

		$row1=mysql_fetch_array($res1);

		if($row1['cnt']==0)		
		{
			$new_band=$row['BAND'];
			$count_arr[1][0][$new_band]++;
		}
	}	

//------------------------------------------------Combining Logic---------------------------------------------------------//

	for($i=0;$i<17;$i++)
	{
		for($j=0;$j<17;$j++)
		{
			if($count_arr[0][$i][$j]>0 || $count_arr[1][$i][$j]>0)
			{
				$countf=$count_arr[0][$i][$j];
				$countm=$count_arr[1][$i][$j];

				//Insert into table if $countf+$countm>0
				$sql="INSERT INTO MIS.BAND_MOVEMENT_WEEKLY VALUES ('$i','$j','$countf','$countm',now())";
				mysql_query($sql) or die(mysql_error());
			}
		}
	}			
}			


//Delete Records Older than 7 days so that size of table and its overhead can be decreased.
function delete_records_from_band()
{
	$delete_dt=date("Y-m-d",time()-7*24*60*60);
	$sql= "DELETE FROM newjs.FEMALE_BAND WHERE CHECK_DATE < '$delete_dt' ";
	mysql_query($sql) or die(mysql_error());

	$sql= "DELETE FROM newjs.MALE_BAND WHERE CHECK_DATE < '$delete_dt' ";
        mysql_query($sql) or die(mysql_error());
}

?>
