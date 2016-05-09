<?
/*********************************************************************************************
* FILE NAME     : isearch.php
* DESCRIPTION   : Intelligence logic for search results–based on user browsing
		: history.as a function
* CREATION DATE : 16th Oct , 2005 
* CREATED BY    : NIKHIL TANDON
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
//include('check.inc');
//include 'display_isresults.php';
//$db=connect_db();
$d='newjs.JPROFILE';
//$per denotes the minimum percentage above which we will print the valid results
//$per=6;
//$sql="SELECT PROFILEID AS id, GENDER FROM $d  WHERE PROFILEID = '$id' ";
$sql="SELECT PROFILEID AS id, GENDER FROM $d  WHERE PROFILEID='$id'";
$res=mysql_query_decide($sql) or logError("Error while main id fetch ".mysql_error_js(),$sql);
$row=mysql_fetch_array($res);
//$id=$row['id'];
$gender=$row['GENDER'];
$t="t";
$min_number_of_contacts=20;
//referencial % being used.
//only % above $per are reflected in the results
$per=6;
/*added for dbase entry*/

$sql="SELECT PROFILEID FROM search_intel.ISEARCH  WHERE PROFILEID='$id'";
$res=mysql_query_decide($sql,$db) or logError("Error while main id fetch ".mysql_error_js(),$sql);
if($row=mysql_fetch_array($res))//If entry exists in the dbase
{
	$d="search_intel.ISEARCH";
	$sql="SELECT * FROM $d WHERE PROFILEID='$id'";
	$res=mysql_query_decide($sql) or logError("Error while main id fetch ".mysql_error_js(),$sql);
	$row=mysql_fetch_array($res);
	$lage=$row['LAGE'];
	$hage=$row['UAGE'];
	
	$lheight=$row['LHEIGHT'];
	$hheight=$row['UHEIGHT'];
	$Photos=$row['PHOTO'];
	//*********FOR INCOME*****************
	$li=$row['LINCOME'];
	$ui=$row['UINCOME'];
	if($li==15)
		$li=0;
	if($ui==15)
		$ui=0;
	if($li < 8 || $li >14)$l_income=1;//in Rs
	else $l_income=0;//in $
	if($ui < 8 || $ui >14)$u_income=1;//in Rs
	else $u_income=0;//in $
											 
	if($l_income && $u_income)
	{
		if($li==0){$incomev[]=15;$li=1;}
		for($i=$li;$i<=$ui;$i++)
		{
			if($i<8 || $i>15)
				$incomev[]=$i;
		}
	}
	elseif($l_income==0 && $u_income==0)
	{
		for($i=$li;$i<=$ui;$i++)
		{
		      $incomev[]=$i;
		}
	}
	else
	{
		//1st element is in Rs
		//2nd element is in Dollars
		if($li==0){$incomev[]=15;$li=1;}
		for($i=$li-1;$i<=$li+1;$i++)
		{
			$incomev[]=$i;
		}
		for($i=$ui-1;$i<=$ui+1;$i++)
		{
			$incomev[]=$i;
		}
	}
	if (is_array($incomev))$income=implode($incomev,",");
//	if (is_array($incomev))$income="'".implode(",",$incomev)."'";
	//*****************************************
	if($gender=='M')$Gender='F';
	if($gender=='F')$Gender='M';
												 
	//CASTE:
	$sql="SELECT CASTE FROM search_intel.ISCASTE WHERE PROFILEID='$id'";
	$res=mysql_query_decide($sql) or logError("Error while main id fetch ".mysql_error_js(),$sql);
	while($row=mysql_fetch_array($res))
	{
		$Caste[]=$row['CASTE'];
	}
												 
	//CITY_RES
	/*
	$sql="SELECT CITY FROM search_intel.ISCITY WHERE PROFILEID='$id'";
	$res=mysql_query_decide($sql) or logError("Error while main id fetch ".mysql_error_js(),$sql);
	while($row=mysql_fetch_array($res))
	{
		$City_Res[]=$row['CITY'];
	}*/
	
	//COUNTRY
	$sql="SELECT COUNTRY FROM search_intel.ISCOUNTRY WHERE PROFILEID='$id'";
        $res=mysql_query_decide($sql) or logError("Error while main id fetch ".mysql_error_js(),$sql);
        while($row=mysql_fetch_array($res))
        {
                $Country_Res[]=$row['COUNTRY'];
        }

	$newsearch="isearch";	
	$total_views=21;//it means that isearch is to be used...
}
else
{

	//in $choice, two_peak elements are placed before and multiple peaks after that.	
	$choice=array('HAVEPHOTO','HEIGHT','AGE','INCOME','CASTE','COUNTRY_RES');
	$two_peaks=1;
	$range_elements=2+$two_peaks;
	$range_forincome=$range_elements+1;
	$multiple_peaks=3;//variable not used yet
	$all=6;
	//main loop will be repeated for each case in $choice

	//Sharding on CONTACTS done by Neha Verma
	$contactResult=getResultSet("RECEIVER",$id);
        if(is_array($contactResult))
        {
		$values1='';
                foreach($contactResult as $key=>$value)
                {
			if($values1!='')
				$values1.= ", ";
                        $receiver=$contactResult[$key]["RECEIVER"];
			$values1.="('".$id."', '".$receiver."')";
		}
        }
        unset($contactResult);

	$contactResult=getResultSet("SENDER","","",$id,"","'A'");
        if(is_array($contactResult))
        {
                $values2='';
                foreach($contactResult as $key=>$value)
                {
                        if($values2!='')
                                $values2.= ", ";
                        $sender=$contactResult[$key]["SENDER"];
                        $values2.="('".$sender."', '".$id."')";
                }
        }
        unset($contactResult);

	$sql= "CREATE TEMPRORY TABLE CONTACT1 (SENDER mediumint(11), RECEIVER mediumint(11))";
	$res=mysql_query_decide($sql,$db) or logError("Error while creating temprory table".mysql_error_js(),$sql);

	$sql_ins="INSERT INTO CONTACT1 VALUES $values1";
	$res_ins=mysql_query_decide($sql_ins,$db) or logError("Error while inserting data in temprory table".mysql_error_js(),$sql_ins);

	$sql= "CREATE TEMPRORY TABLE CONTACT2 (SENDER mediumint(11), RECEIVER mediumint(11))";
        $res=mysql_query_decide($sql,$db) or logError("Error while creating temprory table".mysql_error_js(),$sql);

        $sql_ins="INSERT INTO CONTACT2 VALUES $values2";
        $res_ins=mysql_query_decide($sql_ins,$db) or logError("Error while inserting data in temprory table".mysql_error_js(),$sql_ins);

        //end 


	for($i=0;$i<$all;$i++)
	{
		$sql="CREATE TEMPORARY TABLE $t SELECT COUNT(*) as cnt, j.$choice[$i] FROM CONTACT1 c, $d j WHERE j.PROFILEID=c.RECEIVER GROUP BY $choice[$i] ORDER BY cnt DESC";
		$res=mysql_query_decide($sql,$db) or logError("Error while 1st insertion ".mysql_error_js(),$sql);

		$sql="INSERT INTO $t SELECT COUNT(*) AS cnt, j.$choice[$i] FROM newjs.CONTACT2 c, $d j WHERE j.PROFILEID=c.SENDER GROUP BY $choice[$i] ORDER BY cnt DESC";
		$res=mysql_query_decide($sql,$db) or logError("Error while 2nd insertion ".mysql_error_js(),$sql);
		if($i==0)
                {
                        $sql="SELECT SUM(cnt) as view FROM $t";
                        $res=mysql_query_decide($sql,$db) or logError("Error while 2nd insertion ".mysql_error_js(),$sql);
                        $row=mysql_fetch_array($res);
                        $views=$row['view'];
		
			if(($views>$min_number_of_contacts) && $gender=='M')
				$total_views=$views;
			else
			{
				$sql="INSERT INTO $t SELECT count(*) as cnt, j.$choice[$i] FROM $d j , newjs.VIEW_LOG v WHERE v.VIEWED = j.PROFILEID AND v.VIEWER='$id' AND j.GENDER<>'$gender' GROUP BY $choice[$i]";
        	       		$res=mysql_query_decide($sql,$db) or logError("Error while creating temporary table ".mysql_error_js(),$sql);

				$sql="SELECT SUM(cnt) as total_view FROM $t";
				$res=mysql_query_decide($sql,$db) or logError("Error while 2nd insertion ".mysql_error_js(),$sql);
				$row=mysql_fetch_array($res);
				$total_views=$row['total_view'];
			}
			if($total_views<$min_number_of_contacts){break;}
		}
		elseif(($views<$min_number_of_contacts && $gender=='M') || $gender=='F')
		{
			$sql="INSERT INTO $t SELECT count(*) as cnt, j.$choice[$i] FROM $d j , newjs.VIEW_LOG v WHERE v.VIEWED = j.PROFILEID AND v.VIEWER='$id' AND j.GENDER<>'$gender' GROUP BY $choice[$i]";
			$res=mysql_query_decide($sql,$db) or logError("Error while creating temporary table ".mysql_error_js(),$sql);
		}
		/**************************************************/
		//0 income creating a problem...so removed
		if($choice[$i]=="INCOME")
		{
			$sql="DELETE FROM $t WHERE $choice[$i]=0";
                        $res=mysql_query_decide($sql,$db) or logError("Error while summing".mysql_error_js(),$sql);
		}
		/**************************************************/
	
		if($i<$two_peaks)
		{
			$sql="SELECT SUM(cnt) as cnt, $choice[$i] FROM $t GROUP BY $choice[$i] ORDER BY cnt DESC";
			$res=mysql_query_decide($sql,$db) or logError("Error while summing".mysql_error_js(),$sql);
			$times=0;
			while($row=mysql_fetch_array($res))
			{
				$cnt=$row['cnt'];
				$name=$row[$choice[$i]];
				if($name)
				{
					if($times==0)
					{
						$m[$choice[$i]][0]=$name;
						$m[$choice[$i]][1]=$row['cnt'];
					}
					if($times==1)
					{
						$m[$choice[$i]][2]=$name;
						$m[$choice[$i]][3]=$row['cnt'];
					}
					$times++;
				}
			}
			if($total_views)
			{
				$m[$choice[$i]][1]=round($m[$choice[$i]][1]/$total_views*100,2);
				$percent=$m[$choice[$i]][3]/$total_views*100;
				$relative_percent=$percent/$m[$choice[$i]][1]*100;
				if($relative_percent>10)
				{	
					$m[$choice[$i]][3]=round($m[$choice[$i]][3]/$total_views*100,2);
				}
				else
				{
					$m[$choice[$i]][3]=0;//zero percent
					$m[$choice[$i]][2]=$m[$choice[$i]][0];//same as 1;
				}
			}
		}
		elseif($i<$range_elements)
		{
			$sql="SELECT SUM(cnt) as cnt, $choice[$i] FROM $t GROUP BY $choice[$i] ORDER BY cnt desc ";
			$res=mysql_query_decide($sql,$db) or logError("Error while taking two peaks in range ".mysql_error_js(),$sql);
			$times=0;
			while($row=mysql_fetch_array($res))
			{
				if($times==0)
				{		
					$first=$row['cnt'];
					$first_name=$row[$choice[$i]];
					$second_name=$first_name;
					$times++;
				}
				else
				{
					$s=$row['cnt'];
					$s_name=$row[$choice[$i]];
					if($s!=$first && $times!=1)
						break;
					$second=$s;
					$second_name=$s_name;
					$times++;
				}
			}
			if($times)
			{
				$sql="SELECT SUM(cnt) as cnt, $choice[$i] FROM $t WHERE $choice[$i] < $first_name GROUP BY $choice[$i] ORDER BY $choice[$i] DESC";
				$res=mysql_query_decide($sql,$db) or logError("Error while finding in ascending order".mysql_error_js(),$sql);
				$count=0;
				while($row=mysql_fetch_array($res))
				{
					$cnt=$row['cnt'];
					$name=$row[$choice[$i]];
					if($cnt/$first*100 >45)
					{
						$count+=$cnt;
						$first_name=$name;
						$first1=$cnt;
					}
				 }
				$sql="SELECT SUM(cnt) as cnt, $choice[$i] FROM $t WHERE $choice[$i] > $second_name GROUP BY $choice[$i] ORDER BY $choice[$i] asc";
				$res=mysql_query_decide($sql,$db) or logError("Error while taking in descending order".mysql_error_js(),$sql);
				while($row=mysql_fetch_array($res))
				{
					 $cnt=$row['cnt'];
					 $name=$row[$choice[$i]];
					 if($cnt/$second*100 >45)
					 {
						$count+=$cnt;
						$second_name=$name;
						$second1=$cnt;
					 }
				}
				$m[$choice[$i]][0]=$first_name;//name
				$m[$choice[$i]][1]=0;//not mentioning %;
				$m[$choice[$i]][2]=$second_name;
				$m[$choice[$i]][3]=0;//not mentioning %;
			}
		}
		elseif($i<$range_forincome)
		{
			//keep $ and Rs separately.
			$sql="SELECT SUM(cnt) as cnt, $choice[$i] FROM $t GROUP BY $choice[$i] ORDER BY cnt desc ";
			$res=mysql_query_decide($sql,$db) or logError("Error while taking two peaks in range ".mysql_error_js(),$sql);
			$times=0;$done=1;
			while($row=mysql_fetch_array($res))
			{		
				if($times==0)
				{
					$first=$row['cnt'];
					$first_name=$row[$choice[$i]];
					$second_name=$first_name;
					$times++;
				}
				elseif($done)
				{
					$s=$row['cnt'];
					$s_name=$row[$choice[$i]];
					if($s!=$first && $times!=1)
						{$done=0;}
					$second=$s;
					$second_name=$s_name;
					$times++;
				}
			}
			if($times)
			{
				if($first_name==15)$first_name=0;
				if($second_name==15)$second_name=0;
				if($second_name>7 && $second_name<15)$s_dollars=1;
				else $s_dollars=0;
				if($first_name>7 && $first_name<15)$f_dollars=1;
				else $f_dollars=0;
				
				if($s_dollars==0 && $f_dollars==0)
				{
					$sql="SELECT SUM(cnt) as cnt, $choice[$i] FROM $t WHERE $choice[$i] < $first_name GROUP BY $choice[$i] ORDER BY $choice[$i] DESC";
					$res=mysql_query_decide($sql,$db) or logError("Error while finding in ascending order".mysql_error_js(),$sql);
					$count=0;
					while($row=mysql_fetch_array($res))
					{
						$cnt=$row['cnt'];
						$name=$row[$choice[$i]];
						if($cnt/$first*100 >45)
						{
							$first_name=$name;
						}
					 }
					$sql="SELECT SUM(cnt) as cnt, $choice[$i] FROM $t WHERE $choice[$i] > $second_name GROUP BY $choice[$i] ORDER BY $choice[$i] asc";
					$res=mysql_query_decide($sql,$db) or logError("Error while taking in descending order".mysql_error_js(),$sql);
					while($row=mysql_fetch_array($res))
					{
						 $cnt=$row['cnt'];
						 $name=$row[$choice[$i]];
						 if($cnt/$second*100 >45 && $name!=15)
						 {
							$second_name=$name;
						 }
					
					}
				}	
				elseif($s_dollars==1 && $f_dollars==1)
				{
					//case for $(dollar) entries which are significantly
					//in both the top two
					$sql="SELECT SUM(cnt) as cnt, $choice[$i] FROM $t WHERE $choice[$i] < $first_name AND $choice[$i] > 7 GROUP BY $choice[$i] ORDER BY $choice[$i] DESC";
					$res=mysql_query_decide($sql,$db) or logError("Error while finding in ascending order".mysql_error_js(),$sql);
					$count=0;
					while($row=mysql_fetch_array($res))
					{
						$cnt=$row['cnt'];
						$name=$row[$choice[$i]];
						if($cnt/$first*100 >45)
						{
							$first_name=$name;
						}
					 }
					$sql="SELECT SUM(cnt) as cnt, $choice[$i] FROM $t WHERE $choice[$i] > $second_name AND $choice[$i] <  15 GROUP BY $choice[$i] ORDER BY $choice[$i] asc";
					$res=mysql_query_decide($sql,$db) or logError("Error while taking in descending order".mysql_error_js(),$sql);
					while($row=mysql_fetch_array($res))
					{
						 $cnt=$row['cnt'];
						 $name=$row[$choice[$i]];
						 if($cnt/$second*100 >45)
						 {
							$count+=$cnt;
							$second_name=$name;
							$second1=$cnt;
						 }
											 
					}
					if($second_name<$first_name)
					{
						$temporary=$second_name;
						$second_name=$first_name;
						$first_name=$temporary;
					}
				}
				else
				{
					if($f_dollars)
					{
						$temporary=$first_name;
						$first_name=$second_name;
						$second_name=$temporary;
						//first_name is in Rs
						//second_name is in Dollars
					}
					//dealing with Rs.
					$sql="SELECT SUM(cnt) as cnt, $choice[$i] FROM $t WHERE	$choice[$i] > $first_name GROUP BY $choice[$i] ORDER BY $choice[$i] DESC";
					$res=mysql_query_decide($sql,$db) or logError("Error while finding in ascending order".mysql_error_js(),$sql);
					$count=0;
					while($row=mysql_fetch_array($res))
					{
						$cnt=$row['cnt'];
						$name=$row[$choice[$i]];
						if($name < 8 || $name >14)
						{
							//For Rs only
							if($cnt/$first*100 >45)
							{
								$count+=$cnt;
								$first_name=$name;
								$first1=$cnt;
							}
						}
					}
					//dealing with Dollars
					$sql="SELECT SUM(cnt) as cnt, $choice[$i] FROM $t WHERE  $choice[$i] > $second_name AND ($choice[$i] < 15 OR $choice[$i] > 7) GROUP BY $choice[$i] ORDER BY $choice[$i] DESC";
					$res=mysql_query_decide($sql,$db) or logError("Error while finding in ascending order".mysql_error_js(),$sql);
					while($row=mysql_fetch_array($res))
					{
						$cnt=$row['cnt'];
							$name=$row[$choice[$i]];
							if($cnt/$first*100 >45)
							{
								$second_name=$name;
							}
					}
				}
			}
			if($first_name==0)$first_name=15;
			if($second_name==0)$second_name=15;
			$m[$choice[$i]][0]=$first_name;//name
			$m[$choice[$i]][1]=0;//not mentioning %;
			$m[$choice[$i]][2]=$second_name;
			$m[$choice[$i]][3]=0;//not mentioning %;
		}
		else
		{
			$times=0;
			$k=0;
			$sql="SELECT SUM(cnt) as cnt, $choice[$i] FROM $t GROUP BY $choice[$i] ORDER BY cnt DESC";
			$res=mysql_query_decide($sql,$db) or logError("Error while summing".mysql_error_js(),$sql);
			while($row=mysql_fetch_array($res))
			{
				if(!($row[$choice[$i]]==14 && $choice[$i]=="CASTE"))
				{
					$cnt=$row['cnt'];
					$name=$row[$choice[$i]];
					if($name && $cnt)
					{
						$anyentry=1;
						if($times==0)
						{
							$first_total=$cnt;
							$temp['name'][$k]=$name;
							$temp['cnt'][$k]=$first_total;
							$k++;
							$times++;
						}
						else
						{
							$relative_percent=$cnt/$first_total*100;
							if($relative_percent>10)
							{
								$temp['name'][$k]=$name;
								$temp['cnt'][$k]=$cnt;
								$k++;
							}
						}		
					}
				}
			}
			$two=0;
			for($j=0;$j<$k;$j++)
			{
				$percentage=round($temp['cnt'][$j]/$total_views*100,2);
				if($percentage>$per)
				{
					$m[$choice[$i]][$two]=$temp['name'][$j];
					$m[$choice[$i]][$two+1]=$percentage;
					$m[$choice[$i]][$two+2]='';
					$m[$choice[$i]][$two+3]=0;
					$two=$two+2;
				}
			}
		}
		$sql="DROP TABLE $t";
		mysql_query_decide($sql,$db) or logError("Error while dropping temporary table ".mysql_error_js(),$sql);
	}//from the FOR loop
	unset($j);
	if($total_views>$min_number_of_contacts)
	{
		if($m['HAVEPHOTO'][0]==$m['HAVEPHOTO'][2] && $m['HAVEPHOTO'][0]='Y')
                        $Photos='Y';
                else
                        $Photos='';

		$newsearch="isearch";
		$sql="REPLACE INTO search_intel.ISEARCH VALUES ('$id',
'".$m['HEIGHT'][0]."','".$m['HEIGHT'][2]."',
'".$m['INCOME'][2]."','".$m['INCOME'][0]."',
'".$m['AGE'][0]."','".$m['AGE'][2]."','$Photos')";
			
		$res=mysql_query_decide($sql,$db) or logError("Error while populating INTELLIGENT_SEARCH".mysql_error_js(),$sql);
			
		$lage=$m['AGE'][0];
		$hage=$m['AGE'][2];
		$lheight=$m['HEIGHT'][0];
		$hheight=$m['HEIGHT'][2];
		
		/*****FOR INCOME ********/
		$li=$m['INCOME'][0];
		$ui=$m['INCOME'][2];
		if($li==15)$li=0;
		if($ui==15)$ui=0;
		if($li < 8 || $li >14)$l_income=1;
		else $l_income=0;
		if($ui < 8 || $ui >14)$u_income=1;
		else $u_income=0;
		
		if($l_income && $u_income)
		{//both uppper and lower peak are in Rs
			if($li==0){$incomev[]=15;$li=1;}
			for($i=$li;$i<=$ui;$i++)
			{
				if($i<8 || $i>15)
					$incomev[]=$i;
			}
		}
		elseif($l_income==0 && $u_income==0)
		{//both are in $
			for($i=$li;$i<=$ui;$i++)
			{
			      $incomev[]=$i;
			}
		}
		else
		{//one is in Rs and the other is in $
			//1st element is in Rs
			//2nd element is in Dollars
			if($li==0){$incomev[]=15;$li=1;}
			for($i=$li-1;$i<=$li+1;$i++)
			{
				$incomev[]=$i;
			}
			for($i=$ui-1;$i<=$ui+1;$i++)
			{
				$incomev[]=$i;
			}
		}
	//	$income="'".implode("','",$incomev)."'";
		if(is_array($incomev))$income=implode(",", $incomev);
//		if(is_array($incomev))$income="'".implode(",",$incomev)."'";
		/************************/
		if($gender=='M')$Gender='F';
		if($gender=='F')$Gender='M';
		
		$sql="DELETE FROM search_intel.ISCASTE WHERE PROFILEID='$id'";
		$res=mysql_query_decide($sql,$db) or logError("Error while DELETING search_intel.ISCASTE".mysql_error_js(),$sql);
		$i=0;
		while($m['CASTE'][$i+1] && $m['CASTE'][$i+1]!=0)
		{
			//$caste_name=$m['CASTE'][$i];
			$sql="INSERT IGNORE INTO search_intel.ISCASTE VALUES('$id','".$m['CASTE'][$i]."') ";
		//$sql="INSERT INTO search_intel.ISCASTE VALUES ('$id','$caste_name') ";
			$res=mysql_query_decide($sql,$db) or logError("Error while Inserting into search_intel.ISCASTE".mysql_error_js(),$sql);
			$Caste[]=$m['CASTE'][$i];
			$i+=2;
		}
		/*
		$sql="DELETE FROM search_intel.ISCITY WHERE PROFILEID='$id'";
		$res=mysql_query_decide($sql,$db) or logError("Error while DELETING search_intel.ISCITY".mysql_error_js(),$sql);
		$i=0;
                while($m['CITY_RES'][$i+1]!=0)
		{
			$sql="INSERT INTO search_intel.ISCITY VALUES ('$id','".$m['CITY_RES'][$i]."')";
			$res=mysql_query_decide($sql,$db) or logError("Error while INSERTING INTO search_intel.ISCITY".mysql_error_js(),$sql);
			//$City_Res[]=$m['CITY_RES'][$i];
			$i+=2;
		}*/
		
		$sql="DELETE FROM search_intel.ISCOUNTRY WHERE PROFILEID='$id'";
                $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
                $i=0;
                while($m['COUNTRY_RES'][$i+1]!=0)
		{
                        $sql="INSERT INTO search_intel.ISCOUNTRY VALUES ('$id','".$m['COUNTRY_RES'][$i]."')";
                        $res=mysql_query_decide($sql,$db) or logError("Error while INSERTING INTO search_intel.ISCOUNTRY".mysql_error_js(),$sql);
			$Country_Res[]=$m['COUNTRY_RES'][$i];
			$i+=2;
                }
	}
}
?>
