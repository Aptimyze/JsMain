<?php
//die("This module is currently being revamped. Kindly check again in an hour");
//to zip the file before sending it

/***********************************************************************************************************************
			Changed By	:	Shakti Srivastava
			Date		:	28 November 2005
			Reason		:	This file will be included twice in JSIndicatorMIS andso this function
					:	must be declared only once
***********************************************************************************************************************/
if(!function_exists("getlastdayofmonth"))
{
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
}


if(!$JSIndicator==1)
{
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt)
		ob_start("ob_gzhandler");
}
//end of it

//include("connect.inc");			commented by Shakti on 26 November, 2005 for JSIndicatorMIS

include_once("connect.inc");

include("../profile/arrays.php");
 
//get_today->checkbox marked determining whether Y or N

$db=connect_misdb();

if(authenticated($checksum) || $JSIndicator)
{
	if($CMDGo && $download != 'Y')
	{
		$sec_src=array();
		if($self){
			$sec_src[]="'S'";
		}
		if($sug_mailer)
			$sec_src[]="'M'";
		if($js_mailer)
			$sec_src[]="'I'";
		if($sug_called)
			$sec_src[]="'C'";
		$sec_sources=implode(",",$sec_src);
		if($sourcegp)
		{
			$sql1=" SOURCEID ";//from Source_HITS
                        $sql2=" SourceID ";//from Source
			if($sourcegp=="Business_Sathi")
				$business_flag=1;
			else
			{
				$business_flag=0;
				//$sql1=" SOURCEID ";
				//$sql2=" SourceID ";

				unset($src_imagearr);

				
				if ($prop_mis == 'Y')
                                {
					unset($srcarr);
                                        $sql="SELECT SourceID,SourceGifType,SourceName,PROPERTY,s.PID from MIS.SOURCE s LEFT JOIN MIS.SOURCE_PROPERTY p On p.PID=s.PID WHERE s.GROUPNAME='$sourcegp' ORDER BY s.PID";
                                        $res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
                                        while($myrow=mysql_fetch_array($res))
                                        {
                                                $j = 0;
                                                $prop=$myrow["PID"];
                                                if(is_array($propsArr))
                                                {
                                                        if(!in_array($prop,$propsArr))
                                                        {
                                                                $cnt=count($propsArr);
                                                                $propsArr[$cnt]=$prop;
                                                                $propsName[$prop]=$myrow["PROPERTY"];
                                                        }
                                                }
                                                else
                                                {
                                                        $propsArr[0]=$prop;
                                                        $propsName[$prop]=$myrow["PROPERTY"];
                                                }

                                                $srcarr[$prop][] = $myrow["SourceID"];
                                                $src=$myrow["SourceID"];
                                                $srctoprop[$src]=$prop;

						$src_imagearr[$prop][] = $myrow["SourceGifType"];
						$src_namearr[$prop][]=$myrow['SourceName'];
                                                $j++;
                                        }
                                }
				else
				{
					//Modification done by lavesh for not to display inactive records if desired
					$sql_s="SELECT DISTINCT SourceID FROM MIS.SOURCE WHERE GROUPNAME='$sourcegp'";
					if(!$show_all_record)
						$sql_s.=" AND ACTIVE='Y'";
					//modification ends here
					$res_s=mysql_query_decide($sql_s,$db) or die("$sql_h".mysql_error_js());
					while($row_s=mysql_fetch_array($res_s))
					{
						$srcarr[]=$row_s['SourceID'];
						//modified by sriram to get the source name.
						$sql_namearr="SELECT SourceName , SourceGifType , PID FROM MIS.SOURCE WHERE SourceID='$row_s[SourceID]'";
						$res_namearr=mysql_query_decide($sql_namearr,$db) or die("$sql_namearr".mysql_error_js());
						$row_namearr=mysql_fetch_array($res_namearr);
						$src_namearr[]=$row_namearr['SourceName'];
						$src_imagearr[]=$row_namearr['SourceGifType'];

						$sql_prop = "SELECT PROPERTY FROM MIS.SOURCE_PROPERTY WHERE PID='$row_namearr[PID]'";
						$res_prop = mysql_query_decide($sql_prop) or die("$sql_prop".mysql_error_js());
						$row_prop = mysql_fetch_array($res_prop);
						$src_proparr[] = $row_prop['PROPERTY'];
					//end of modification by sriram.
					}
				}
				$prop_count = count($propsArr);

			}
			$smarty->assign("IS_GROUP","N");
		}
		else
		{
			$level = 1;
			$business_flag=1;
			//$srcarr[]="Unknown";
			$sql1=" SOURCEGP ";
			$sql2=" GROUPNAME ";
			$sql_s="SELECT DISTINCT GROUPNAME FROM MIS.SOURCE";
			$res_s=mysql_query_decide($sql_s,$db) or die("$sql_h".mysql_error_js());
			while($row_s=mysql_fetch_array($res_s))
			{
				$srcarr[]=$row_s['GROUPNAME'];
			}
			//$srcarr[]="Unknown";
			$smarty->assign("IS_GROUP","Y");
		}
		if($get_today)
		{
			$dt_type='day';
		}
		if($dt_type=="day")
		{
			unset($cnt);//count->
			unset($tota);//total a->total of the row of dates
			unset($totb);//total b->total of that particular date
			$smarty->assign("dflag",1);
			$smarty->assign("dt","$ddate_mon-$ddate_yyyy");

			$st_date=$ddate_yyyy."-".$ddate_mon."-01";
			$end_date=$ddate_yyyy."-".$ddate_mon."-31";

			$curdate=date("Y-m-d");
			//to represent month in  2 digits, will be >=0 if current date is valid


			$date_len = strlen($ddate_mon);

			if ($date_len != '2')
			if($ddate_mon<10)
				$ddate_mon="0".$ddate_mon;
			//diff stores diff in alphabetical values of date from current date
			$diff=strcmp($end_date,$curdate);
			//initialises time t1 to 000, 1 year
			$t1=mktime(0,0,0,$ddate_mon,01,$ddate_yyyy);
			if($diff>=0)
			{
				$t2=gettimeofday();
				//t2 below hold the time in sec of the day
				$t2=$t2['sec'];
			}
			else
			{
				$last_day=getlastdayofmonth($ddate_mon,$ddate_yyyy);
				$t2=mktime(23,59,59,$ddate_mon,$last_day,$ddate_yyyy);
			}
			//$t->gives current time minus time 000
			$t=$t2-$t1;
			//$d gives time in days
			$d=$t/(60*60*24);
			//$d rounds days to two decimal places
			$d=round($d,2);
			//$ddarr->is an array of 1 to 31, representing days in a month
			for($i=0;$i<31;$i++)
			{
				$ddarr[$i]=$i+1;
			}

			if($profile_type=='E')
			{
				//To fetch Entered Profiles
				$date_type="ENTRY_DT ";
			}
			else
			{
				//To fetch modified profiles
				$date_type="MOD_DT ";
			}

			if($get_today)
			{
				//$ts->gives time in sec
				$ts=time();
				//$ts->gives $ts-2 days
				$ts-=2*24*60*60;
				//$today->stores the date
				$today=date("Y-m-d",$ts);
				//$sql2->SourceID
				//sql_h->src,cnt,dd,s,h
				if($business_flag)
		        	{
					$sql_hb="SELECT COUNT(*) as cnt, DAYOFMONTH(Date) as dd FROM MIS.HITS where Date>='$today' AND SourceID like 'af%' AND SourceID not like 'afl%' GROUP BY dd";
					$sql_mb="SELECT COUNT(*) as cnt, DAYOFMONTH(j.$date_type) as dd FROM newjs.JPROFILE j where j.$date_type>='$today' AND SOURCE like 'af%' AND SOURCE not like 'afl%' ";
					if($community)
	                                {
        	                                $sql_mb.=" AND j.MTONGUE='$community' ";
                        	        }
                                	if($gender)
                                	{
                                        	$sql_mb.=" AND j.GENDER='$gender' ";
                                	}
					if($mstatus)
                                	{
                                        	$sql_mb.=" AND j.MSTATUS='$mstatus' ";
                                	}
					if($country)
                                	{
                                        	$sql_mb.=" AND j.COUNTRY_RES='$country'";
                                	}
					if($activated)
                	                {
                        	                $sql_mb.=" AND j.ACTIVATED='$activated' ";
                                	}
					if($incomplete)
                	                {
                        	                $sql_mb.=" AND j.INCOMPLETE='$incomplete' ";
                                	}
					if($subs=='P')
                	                {
                        	                $sql_mb.=" AND j.SUBSCRIPTION<>'' ";
                                	}
					if($sec_sources){
						if($self)
							$sql_mb.="AND (j.SEC_SOURCE IS NULL OR j.SEC_SOURCE IN ( $sec_sources )) ";
						else
							$sql_mb.=" AND j.SEC_SOURCE IN ( $sec_sources ) ";
					}
					$sql_mb.=" GROUP BY dd";
				}
        			if(($sourcegp && !$business_flag) || !$sourcegp)
        			{
					$sql_h="SELECT s.$sql2 as src, COUNT(*) as cnt,DAYOFMONTH(Date) as dd FROM MIS.SOURCE s,MIS.HITS h WHERE Date>='$today' ";
					if($sourcegp)
						$sql_h.=" AND s.GROUPNAME='$sourcegp' ";
					else
						$sql_h.=" AND s.GROUPNAME<>'NONE' ";

					$sql_h.=" AND s.SourceID=h.SourceID GROUP BY src,dd";
					//sql_m->src, cnt, dd, j.
					$sql_m="SELECT s.$sql2 as src, COUNT(*) as cnt,DAYOFMONTH(j.$date_type) as dd FROM MIS.SOURCE s,newjs.JPROFILE j WHERE j.$date_type>='$today'";
					if($community)
					{
						$sql_m.=" AND j.MTONGUE='$community' ";
					}
					if($gender)
					{
						$sql_m.=" AND j.GENDER='$gender' ";
					}
					if($mstatus)
					{
						$sql_m.=" AND j.MSTATUS='$mstatus' ";
					}
					if($country)
					{
						$sql_m.=" AND j.COUNTRY_RES='$country'";
					}
					if($activated)
					{
						$sql_m.=" AND j.ACTIVATED='$activated' ";
					}
					if($incomplete)
					{
						$sql_m.=" AND j.INCOMPLETE='$incomplete' ";
					}
					if($subs=='P')
					{
						$sql_m.=" AND j.SUBSCRIPTION<>'' ";
					}
					elseif($subs=='F')
					{
						$sql_m.=" AND j.SUBSCRIPTION='' ";
					}
					if($sec_sources){
						if($self)
							$sql_m.="AND (j.SEC_SOURCE IS NULL OR j.SEC_SOURCE IN ( $sec_sources )) ";
						else
							$sql_m.=" AND j.SEC_SOURCE IN ( $sec_sources ) ";
					}
					if($sourcegp)
						$sql_m.=" AND s.GROUPNAME='$sourcegp' ";
					$sql_m.=" AND s.SourceID=j.SOURCE GROUP BY src,dd";
				}
			}	
			else//for the entire month from start date.
			{
				if($business_flag)
				{
					$sql_hb="SELECT SUM(COUNT) as cnt, DAYOFMONTH(ENTRY_DT) as dd FROM MIS.SOURCE_HITS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date'  AND SOURCEID LIKE 'af%' AND SOURCEID not like 'afl%' GROUP BY dd";
					$sql_mb="SELECT SUM(COUNT) as cnt, DAYOFMONTH(ENTRY_DT) as dd FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND SOURCEID like 'af%' AND SOURCEID not like 'afl%' AND ENTRY_MODIFY='$profile_type' ";
					if($community)
	                                {
                	                        $sql_mb.=" AND MTONGUE='$community' ";
                        	        }
                                	if($gender)
                                	{
                                        	$sql_mb.=" AND GENDER='$gender' ";
                                	}
                                	if($mstatus)
                                	{
                                        	$sql_mb.=" AND MSTATUS='$mstatus' ";
                                	}
	                                if($country)
        	                        {
                	                        $sql_mb.=" AND COUNTRY_RES='$country'";
                        	        }
                                	if($activated)
                                	{
                                        	$sql_mb.=" AND ACTIVATED='$activated' ";
                                	}
	                                if($incomplete)
        	                        {
                        	                $sql_mb.=" AND INCOMPLETE='$incomplete' ";
                	                }
                                	if($subs=='P')
                                	{
                                        	$sql_mb.=" AND SUBSCRIPTION<>'' ";
                                	}
					elseif($subs=='F')
        	                        {
	                                        $sql_mb.=" AND SUBSCRIPTION='' ";
                	                }
					if($sec_sources){
						if($self)
							$sql_mb.="AND (SEC_SOURCE IS NULL OR SEC_SOURCE IN ( $sec_sources )) ";
						else
							$sql_mb.=" AND SEC_SOURCE IN ( $sec_sources ) ";
					}
                                        $sql_mb.=" GROUP BY dd";
				//here
				}
				if(($sourcegp && !$business_flag) || !$sourcegp)
				{
					$sql_h="SELECT $sql1 as src, SUM(COUNT) as cnt,DAYOFMONTH(ENTRY_DT) as dd FROM MIS.SOURCE_HITS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date'";
					if($sourcegp)
						$sql_h.=" AND SOURCEGP='$sourcegp' ";
					else
						$sql_h.=" AND SOURCEGP<>'NONE' ";

					$sql_h.="GROUP BY src,dd";
					$sql_m="SELECT $sql1 as src, SUM(COUNT) as cnt,DAYOFMONTH(ENTRY_DT) as dd FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND ENTRY_MODIFY='$profile_type'";
					if($community)
					{
						$sql_m.=" AND MTONGUE='$community' ";
					}
					if($gender)
					{
						$sql_m.=" AND GENDER='$gender' ";
					}
					if($mstatus)
					{
						$sql_m.=" AND MSTATUS='$mstatus' ";
					}
					if($country)
					{
						$sql_m.=" AND COUNTRY_RES='$country'";
					}
					if($activated)
					{
						$sql_m.=" AND ACTIVATED='$activated' ";
					}
					if($incomplete)
					{
						$sql_m.=" AND INCOMPLETE='$incomplete' ";
					}
					if($subs=='P')
					{
						$sql_m.=" AND SUBSCRIPTION<>'' ";
					}
					elseif($subs=='F')
					{
						$sql_m.=" AND SUBSCRIPTION='' ";
					}
					if($sec_sources){
						if($self)
							$sql_m.="AND (SEC_SOURCE IS NULL OR SEC_SOURCE IN ( $sec_sources )) ";
						else
							$sql_m.=" AND SEC_SOURCE IN ( $sec_sources ) ";
					}
					if($sourcegp)
						$sql_m.=" AND SOURCEGP='$sourcegp' ";
					$sql_m.=" GROUP BY src,dd";
				}
			}//month querys over
			if($business_flag)
			{
	                 	$res_hb=mysql_query_decide($sql_hb,$db) or die("$sql_hb".mysql_error_js($db));
				while($row_hb=mysql_fetch_array($res_hb))
				{
					$dd=$row_hb['dd']-1;
                                        $b_count[$dd]["h"]+=$row_hb['cnt'];
                                        $b_tota["h"]+=$row_hb['cnt'];
                                }
				$res_mb=mysql_query_decide($sql_mb,$db) or die("$sql_mb".mysql_error_js($db));
				while($row_mb=mysql_fetch_array($res_mb))
                        	{
                                        $dd=$row_mb['dd']-1;
                                        $b_count[$dd]["m"]+=$row_mb['cnt'];
                                        $b_tota["m"]+=$row_mb['cnt'];
			        }	
				//end of if of Business_flag condition
			}
			$prop_count = count($propsArr);
	 		if(($sourcegp && !$business_flag) || !$sourcegp)
			{
				$res_h=mysql_query_decide($sql_h,$db) or die("$sql_h".mysql_error_js($db));
				while($row_h=mysql_fetch_array($res_h))
				{
						//src->SourceID
					$src=$row_h['src'];
						//counter->count of number of rows derived from query
					$counter=$row_h['cnt'];
						//$i->relative position of $srcarr from $src=SourceID

					if ($prop_mis == 'Y')
                                        {
                                                for ($p = 0;$p < $prop_count;$p++)
                                                {
                                                        if (is_array($srcarr[$p]))
                                                        {
                                                                $propval = $propsArr[$p];
                                                                $i=array_search($src,$srcarr[$propval]);
                                                                if ($i && $i!='NULL')
									break;
                                                        }
                                                }
                                        }
                                        else
					{
						$i=array_search($src,$srcarr);
						if($i===NULL)
							$i=array_search('Unknown',$srcarr);
					}
					
						//$dd->to take day as -1 from current date
					$dd=$row_h['dd']-1;

					if ($prop_mis == 'Y')
                                        {
                                                $prop = $srctoprop[$src];

                                                $src_cnt[$prop][$srcarr[$prop][$i]][$dd]["h"]+=$counter;
                                                $tot_src_cnta[$prop][$srcarr[$prop][$i]]["h"]+=$counter;
                                                $tot_src_cntb[$prop][$dd]["h"]+=$counter;
                                                $prop_totalh[$prop]+=$counter;
                                        }
                                        else
					{
							//$i th SourceID on dd th day, in h reference
						$cnt[$i][$dd]["h"]+=$counter;
							//tota->$i th SourceID;gives sum of SourceID
						$tota[$i]["h"]+=$counter;
							//totb->no of querys executed 
						$totb[$dd]["h"]+=$counter;
							//totallh->total counters
						$totallh+=$counter;
							//$total_bs[$i][$dd]+=$counter;
					}
				}
				
				$res_m=mysql_query_decide($sql_m,$db) or die("$sql_m".mysql_error_js($db));
				while($row_m=mysql_fetch_array($res_m))
				{
					$src=$row_m['src'];
					$counter=$row_m['cnt'];
					//if($src!==NULL)


					if ($prop_mis == 'Y')
                                        {
                                                for ($p = 0;$p < $prop_count;$p++)
                                                {
                                                        if (is_array($srcarr[$p]))
                                                        {
                                                                $propval = $propsArr[$p];
                                                                $i=array_search($src,$srcarr[$propval]);
                                                                if ($i && $i!='NULL')
									break;
                                                        }
                                                }
                                        }
                                        else
					{	
						$i=array_search($src,$srcarr);
						if($i===NULL)
							$i=array_search('Unknown',$srcarr);
					}

					$dd=$row_m['dd']-1;

					if ($prop_mis == 'Y')
                                        {
                                                $prop=$srctoprop[$src];

                                                $src_cnt[$prop][$srcarr[$prop][$i]][$dd]["m"]+=$counter;
                                                $tot_src_cnta[$prop][$srcarr[$prop][$i]]["m"]+=$counter;
                                                $tot_src_cntb[$prop][$dd]["m"]+=$counter;
                                                $prop_totalm[$prop]+=$counter;
                                        }
					else
					{
						$cnt[$i][$dd]["m"]+=$counter;
						$tota[$i]["m"]+=$counter;
						$totb[$dd]["m"]+=$counter;
						$totallm+=$counter;
					}
				}
			}
		}
		elseif($dt_type=="mnt")//if data type is month
		{
			unset($cnt);
			unset($tota);
			unset($totb);

			$smarty->assign("mflag",1);
			$mdate_yyyyp1=$mdate_yyyy+1;
			$smarty->assign("dt",$mdate_yyyy);
			$smarty->assign("dt1",$mdate_yyyyp1);
			$st_date=$mdate_yyyy."-04-01";
			$end_date=$mdate_yyyyp1."-03-31";

			$mmarr=array('Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar');
			if($business_flag)
			{
				 $sql_ha="Select SUM(COUNT) as cnt, MONTH(ENTRY_DT) as mm FROM MIS.SOURCE_HITS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date'  AND SOURCEID LIKE 'af%' AND SOURCEID not like 'afl%' GROUP BY mm";
				 $sql_ma="Select SUM(COUNT) as cnt, MONTH(ENTRY_DT) as mm FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND SOURCEID like 'af%' AND SOURCEID not like 'afl%' AND ENTRY_MODIFY='$profile_type' ";
				 if($community)
                                 {
                                	 $sql_ma.=" AND MTONGUE='$community' ";
                                 }
                                 if($gender)
                                 {
                                 	 $sql_ma.=" AND GENDER='$gender' ";
                                 }
                                 if($mstatus)
                                 {
                                         $sql_ma.=" AND MSTATUS='$mstatus' ";
                                 }
                                 if($country)
                                 {
                                         $sql_ma.=" AND COUNTRY_RES='$country'";
                                 }
                                 if($activated)
                                 {
                                         $sql_ma.=" AND ACTIVATED='$activated' ";
                                 }
                                 if($incomplete)
                                 {
                                         $sql_ma.=" AND INCOMPLETE='$incomplete' ";
                                 }
                                 if($subs=='P')
                                 {
                                         $sql_ma.=" AND SUBSCRIPTION<>'' ";
                                 }
				 elseif($subs=='F')
                                 {
                                         $sql_ma.=" AND SUBSCRIPTION='' ";
                                 }
					if($sec_sources){
						if($self)
							$sql_ma.="AND (SEC_SOURCE IS NULL OR SEC_SOURCE IN ( $sec_sources )) ";
						else
							$sql_ma.=" AND SEC_SOURCE IN ( $sec_sources ) ";
					}
                                $sql_ma.=" GROUP BY mm";
				$res_ha=mysql_query_decide($sql_ha,$db) or die("$sql_ha".mysql_error_js($db));
                                while($row_ha=mysql_fetch_array($res_ha))
                                {
                                	$mm=$row_ha['mm'];
					if($mm<=3)
					{
						$mm+=8;
					}
					else
					{
						$mm-=4;
					}
                                        $b_count[$mm]["h"]+=$row_ha['cnt'];
                                        $b_tota["h"]+=$row_ha['cnt'];
                                }
				$res_ma=mysql_query_decide($sql_ma,$db) or die("$sql_ma".mysql_error_js($db));
        	                while($row_ma=mysql_fetch_array($res_ma))
                	        {
                       		        $mm=$row_ma['mm'];
					if($mm<=3)
					{
						$mm+=8;
					}
					else
					{
						$mm-=4;
					}
                                       	$b_count[$mm]["m"]+=$row_ma['cnt'];
                               		$b_tota["m"]+=$row_ma['cnt'];
                                }
			}//end of business flag conditon]
			if(($sourcegp && !$business_flag) || !$sourcegp)
			{
				$sql_h="SELECT $sql1 as src, SUM(COUNT) as cnt,MONTH(ENTRY_DT) as mm FROM MIS.SOURCE_HITS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date'";
				if($sourcegp)
					$sql_h.=" AND SOURCEGP='$sourcegp' ";
				$sql_h.=" GROUP BY src,mm";
				$sql_m="SELECT $sql1 as src, SUM(COUNT) as cnt,MONTH(ENTRY_DT) as mm FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND ENTRY_MODIFY='$profile_type'";
				if($community)
				{	
					$sql_m.=" AND MTONGUE='$community' ";
				}
				if($gender)
				{
					$sql_m.=" AND GENDER='$gender' ";
				}
				if($mstatus)
				{
					$sql_m.=" AND MSTATUS='$mstatus' ";
				}
				if($country)
				{
					$sql_m.=" AND COUNTRY_RES='$country'";
				}
				if($activated)
				{
					$sql_m.=" AND ACTIVATED='$activated' ";
				}
				if($incomplete)
				{
					$sql_m.=" AND INCOMPLETE='$incomplete' ";
				}
				if($subs=='P')
				{
					$sql_m.=" AND SUBSCRIPTION<>'' ";
				}
				elseif($subs=='F')
				{
					$sql_m.=" AND SUBSCRIPTION='' ";
				}
					if($sec_sources){
						if($self)
							$sql_m.="AND (SEC_SOURCE IS NULL OR SEC_SOURCE IN ( $sec_sources )) ";
						else
							$sql_m.=" AND SEC_SOURCE IN ( $sec_sources ) ";
					}
				if($sourcegp)
					$sql_m.=" AND SOURCEGP='$sourcegp' ";
				$sql_m.=" GROUP BY src,mm";
				$res_h=mysql_query_decide($sql_h,$db) or die("$sql_h".mysql_error_js($db));
				while($row_h=mysql_fetch_array($res_h))
				{
					$src=$row_h['src'];
					$counter=$row_h['cnt'];
					if ($prop_mis == 'Y')
                                        {
                                                for ($p = 0;$p < $prop_count;$p++)
                                                {
							if (is_array($srcarr[$p]))
							{
								$propval = $propsArr[$p];
                                                        	$i=array_search($src,$srcarr[$propval]);
								if ($i && $i!='NULL')
									break;
							}
                                                }
                                        }
					else
					{
						$i=array_search($src,$srcarr);
						if($i===NULL)
							$i=array_search('Unknown',$srcarr);
					}
					$mm=$row_h['mm'];
					if($mm<=3)
					{
						$mm+=8;
					}
					else
					{
						$mm-=4;
					}
					if ($prop_mis == 'Y')
					{
						$prop=$srctoprop[$src];

						$src_cnt[$prop][$srcarr[$prop][$i]][$mm]["h"]+=$counter;
						$tot_src_cnta[$prop][$srcarr[$prop][$i]]["h"]+=$counter;
						$tot_src_cntb[$prop][$mm]["h"]+=$counter;
						$prop_totalh[$prop]+=$counter;
					}
					else
					{
						$cnt[$i][$mm]["h"]+=$counter;
						$tota[$i]["h"]+=$counter;
						$totb[$mm]["h"]+=$counter;
						$totallh+=$counter;
					}
				}//while($row_h=mysql_fetch_array($res_h));
				$res_m=mysql_query_decide($sql_m,$db) or die("$sql_m".mysql_error_js($db));
				while($row_m=mysql_fetch_array($res_m))
				{
					$src=$row_m['src'];
					$counter=$row_m['cnt'];

					if ($prop_mis == 'Y')
                                        {
                                                for ($p = 0;$p < $prop_count;$p++)
                                                {
                                                        if (is_array($srcarr[$p]))
                                                        {
                                                                $propval = $propsArr[$p];
                                                                $i=array_search($src,$srcarr[$propval]);
                                                                if ($i && $i!='NULL')
									break;
                                                        }
                                                }
                                        }
                                        else
					{
						$i=array_search($src,$srcarr);
						if($i===NULL)
							$i=array_search('Unknown',$srcarr);
					}

					$mm=$row_m['mm'];
					if($mm<=3)
					{
						$mm+=8;
					}
					else
					{
						$mm-=4;
					}
					if ($prop_mis == 'Y')
                                        {
                                                $prop=$srctoprop[$src];

                                                $src_cnt[$prop][$srcarr[$prop][$i]][$mm]["m"]+=$counter;
                                                $tot_src_cnta[$prop][$srcarr[$prop][$i]]["m"]+=$counter;
                                                $tot_src_cntb[$prop][$mm]["m"]+=$counter;
                                                $prop_totalm[$prop]+=$counter;
                                        }
                                        else
					{
						$cnt[$i][$mm]["m"]+=$counter;
						$tota[$i]["m"]+=$counter;
						$totb[$mm]["m"]+=$counter;
						$totallm+=$counter;
					}
				}//while($row_m=mysql_fetch_array($res_m));
			}
		}
		if($dt_type=="day")
		{
			$num=count($ddarr);
		}
		elseif($dt_type=="mnt")
		{
			$num=count($mmarr);
		}
		if ($business_flag)
		{
		        for($j=0;$j<$num;$j++)
                        {
                        	if($b_count[$j]["h"])
                                {
                                	  $b_count[$j]["p"]=($b_count[$j]["m"]/$b_count[$j]["h"]) * 100;
                                          $b_count[$j]["p"]=round($b_count[$j]["p"],2);
                                }
			}
			if($d)
			{
				  $b_tota["e"]=$b_tota["m"]/$d;
				  $b_tota["e"]=round($b_tota["e"],2);
			}
			if($b_tota["h"])
			{
				  $b_tota["p"]=$b_tota["m"]/$b_tota["h"] * 100;
				  $b_tota["p"]=round($b_tota["p"],2);
			}
		}
		if(($sourcegp && !$business_flag) || !$sourcegp)
		{
			if ($prop_mis == 'Y')
			{
				for ($p = 0;$p < $prop_count;$p++)
				{
					$prop = $propsArr[$p];
					
					for($i=0;$i<count($srcarr[$p]);$i++)
                                	{
                                        	for($j=0;$j<$num;$j++)
                                        	{
							if ($src_cnt[$prop][$srcarr[$prop][$i]][$j]["h"])
                                                	{
                                                        	$src_cnt[$prop][$srcarr[$prop][$i]][$j]["p"]=$src_cnt[$prop][$srcarr[$prop][$i]][$j]["m"]/$src_cnt[$prop][$srcarr[$prop][$i]][$j]["h"] * 100;
                                                        	$src_cnt[$prop][$srcarr[$prop][$i]][$j]["p"]=round($src_cnt[$prop][$srcarr[$prop][$i]][$j]["p"],2);
                                                	}
                                        	}
						if ($tot_src_cnta[$prop][$srcarr[$prop][$i]]["h"])
                                        	{
							$tot_src_cnta[$prop][$srcarr[$prop][$i]]["p"] = $tot_src_cnta[$prop][$srcarr[$prop][$i]]["m"]/$tot_src_cnta[$prop][$srcarr[$prop][$i]]["h"] * 100;
							$tot_src_cnta[$prop][$srcarr[$prop][$i]]["p"] = round($tot_src_cnta[$prop][$srcarr[$prop][$i]]["p"],2);
                                        	}
                                        	if($d)
                                        	{
                                                	$prop_avgtota[$prop][$srcarr[$prop][$i]]=$tot_src_cnta[$prop][$srcarr[$prop][$i]]["m"]/$d;
                                                	$prop_avgtota[$prop][$srcarr[$prop][$i]]=round($prop_avgtota[$prop][$srcarr[$prop][$i]],2);
                                        	}
                                	}
					for($j=0;$j<$num;$j++)
					{
						if($tot_src_cntb[$prop][$j]["h"])
						{
							$tot_src_cntb[$prop][$j]["p"] = $tot_src_cntb[$prop][$j]["m"]/$tot_src_cntb[$prop][$j]["h"] * 100;
							$tot_src_cntb[$prop][$j]["p"] = round($tot_src_cntb[$prop][$j]["p"],2);
						}
					}
					if($d)
					{
						$prop_avgtotallm[$prop] = $prop_totalm[$prop]/$d; 
						$prop_avgtotallm[$prop] = round($prop_avgtotallm[$prop],2);
					}
					if ($prop_totalh[$prop])
					{
						$prop_totallp[$prop]=$prop_totalm[$prop]/$prop_totalh[$prop] * 100;
						
						$prop_totallp[$prop]=round($prop_totallp[$prop],2);
					}
				}
			}
			else
			{
				for($i=0;$i<count($srcarr);$i++)
				{
					for($j=0;$j<$num;$j++)
					{
						if($cnt[$i][$j]["h"])
						{
							$cnt[$i][$j]["p"]=$cnt[$i][$j]["m"]/$cnt[$i][$j]["h"] * 100;
							$cnt[$i][$j]["p"]=round($cnt[$i][$j]["p"],2);
						}
					}
					if($tota[$i]["h"])
					{
						$tota[$i]["p"]=$tota[$i]["m"]/$tota[$i]["h"] * 100;
						$tota[$i]["p"]=round($tota[$i]["p"],2);
					}
					if($d)
					{
						$avgtota[$i]=$tota[$i]["m"]/$d;
						$avgtota[$i]=round($avgtota[$i],2);
					}
				}
				for($j=0;$j<$num;$j++)
				{
					if($totb[$j]["h"])
					{
						$totb[$j]["p"]=$totb[$j]["m"]/$totb[$j]["h"] * 100;
						$totb[$j]["p"]=round($totb[$j]["p"],2);
					}
				}
				if($d)
				{
					$avgtotallm=$totallm/$d;
					$avgtotallm=round($avgtotallm,2);
				}
				if($totallh)
				{
					$totallp=$totallm/$totallh * 100;
					$totallp=round($totallp,2);
				}
			}
		}
/*************************************************************************************************************************
                        Added By        :       Shakti Srivastava
                        Date            :       26 November, 2005
                        Reason          :       This was needed for stopping further execution of this script whenever
                                        :       indicator_mis.php was used to obtain data
*************************************************************************************************************************/
                if($JSIndicator==1)
                {
                        return;
                }
/**************************************End of Addition********************************************************************/ 

		$smarty->assign("cnt",$cnt);
		$smarty->assign("b_count",$b_count);
		$smarty->assign("tota",$tota);
		$smarty->assign("totb",$totb);
		$smarty->assign("totallh",$totallh);
		$smarty->assign("totallm",$totallm);
		$smarty->assign("totallp",$totallp);
		$smarty->assign("avgtota",$avgtota);
		$smarty->assign("avgtotallm",$avgtotallm);
		$smarty->assign("b_tota",$b_tota);
		$smarty->assign("gender",$gender);
		$smarty->assign("community",label_select("MTONGUE",$community));
		$smarty->assign("country",label_select("TOP_COUNTRY",$country));
		$smarty->assign("mstatus",$MSTATUS[$mstatus]);
		$smarty->assign("subs",$subs);
		$smarty->assign("incomplete",$incomplete);
		$smarty->assign("activated",$activated);
		$smarty->assign("source",$sourcegp);
		if ($sourcegp=="Business_Sathi" || $sourcegp=="")
			$flag_bs=1;//To display business sathi
		else
			$flag_bs=0;
		if ($sourcegp=="Business_Sathi")
			$flag_b=1;
		else
			$flag_b=0;//To print total line
		$smarty->assign("flag_b",$flag_b);
		$smarty->assign("flag_bs",$flag_bs);	
		$smarty->assign("srcarr",$srcarr);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("hharr",$hharr);
		$smarty->assign("checksum",$checksum);

		//**Code added by sriram**//
		$smarty->assign("sourcegp",$sourcegp);	
		$smarty->assign("profile_type",$profile_type);	
		$smarty->assign("community",$community);	
		$smarty->assign("gender",$gender);	
		$smarty->assign("mstatus",$mstatus);	
		$smarty->assign("country",$country);	
		$smarty->assign("subs",$subs);	
		$smarty->assign("incomplete",$incomplete);	
		$smarty->assign("activated",$activated);	
		$smarty->assign("show_all_record",$show_all_record);	
		$smarty->assign("ddate_mon",$ddate_mon);	
		$smarty->assign("ddate_yyyy",$ddate_yyyy);	
		$smarty->assign("mdate_yyyy",$mdate_yyyy);	
		$smarty->assign("get_today",$get_today);	
		$smarty->assign("dt_type",$dt_type);	
	
		$smarty->assign("src_namearr",$src_namearr);	
		//**end of code added by sriram**/

		$smarty->assign("level",$level);
		$smarty->assign("src_imagearr",$src_imagearr);
		$smarty->assign("src_proparr",$src_proparr);
		$smarty->assign("sec_sources",$sec_sources);

		if ($prop_mis == 'Y')
		{
			$smarty->assign("src_cnt",$src_cnt);
			$smarty->assign("tot_src_cnta",$tot_src_cnta);
			$smarty->assign("tot_src_cntb",$tot_src_cntb);
			$smarty->assign("prop_totalm",$prop_totalm);
			$smarty->assign("prop_totalh",$prop_totalh);
			$smarty->assign("prop_totallp",$prop_totallp);
			$smarty->assign("prop_avgtotallm",$prop_avgtotallm);
			$smarty->assign("prop_avgtota",$prop_avgtota);
			$smarty->assign("propsArr",$propsArr);
			$smarty->assign("propsName",$propsName);


			$smarty->display("source_prop_mis.htm");
		}
		else
			$smarty->display("sourcehits.htm");
		
	}//else condition from Business_flag

/****************************************************************************************************************************
Code added by Sriram Viswanathan to provide option for downloading csv
***************************************************************************************************************************/	
	elseif($CMDGo && $download=='Y' || $download)
	{
		if($self)
			$sec_src[]="'S'";
		if($sug_mailer)
			$sec_src[]="'M'";
		if($js_mailer)
			$sec_src[]="'I'";
		if($sug_called)
			$sec_src[]="'C'";
		$sec_sources=implode(",",$sec_src);
		if($sourcegp)
		{
			 $sql1=" SOURCEID ";//from Source_HITS
                         $sql2=" SourceID ";//from Source
			if($sourcegp=="Business_Sathi")
				$business_flag=1;
			else
			{
				$business_flag=0;
				//$sql1=" SOURCEID ";
				//$sql2=" SourceID ";
				
				//Modification done by lavesh for not to display inactive records if desired
				$sql_s="SELECT DISTINCT SourceID FROM MIS.SOURCE WHERE GROUPNAME='$sourcegp'";
				if(!$show_all_record)
					$sql_s.=" AND ACTIVE='Y'";
				//modification ends here
				$res_s=mysql_query_decide($sql_s,$db) or die("$sql_h".mysql_error_js($db));
				while($row_s=mysql_fetch_array($res_s))
				{
					$srcarr[]=$row_s['SourceID'];
					//modified by sriram to get the source name.
                                        $sql_namearr="SELECT SourceName FROM MIS.SOURCE WHERE SourceID='$row_s[SourceID]'";
                                        $res_namearr=mysql_query_decide($sql_namearr,$db) or die("$sql_namearr".mysql_error_js($db));
                                        $row_namearr=mysql_fetch_array($res_namearr);
                                        $src_namearr[]=$row_namearr['SourceName'];
                                        //end of modification by sriram.
					$src_namearr[]=$row_namearr['SourceName'];
				}
			}
		}
		else
		{
			$business_flag=1;
			//$srcarr[]="Unknown";
			$sql1=" SOURCEGP ";
			$sql2=" GROUPNAME ";
			$sql_s="SELECT DISTINCT GROUPNAME FROM MIS.SOURCE";
			$res_s=mysql_query_decide($sql_s,$db) or die("$sql_h".mysql_error_js($db));
			while($row_s=mysql_fetch_array($res_s))
			{
				$srcarr[]=$row_s['GROUPNAME'];
			}
			//$srcarr[]="Unknown";
		}
		if($get_today)
		{
			$dt_type='day';
		}
		if($dt_type=="day")
		{
			unset($cnt);//count->
			unset($tota);//total a->total of the row of dates
			unset($totb);//total b->total of that particular date

			$st_date=$ddate_yyyy."-".$ddate_mon."-01";
			$end_date=$ddate_yyyy."-".$ddate_mon."-31";

			$curdate=date("Y-m-d");


			//to represent month in  2 digits, will be >=0 if current date is valid
			if($ddate_mon<10)
				$ddate_mon="0".$ddate_mon;
			//diff stores diff in alphabetical values of date from current date
			$diff=strcmp($end_date,$curdate);
			//initialises time t1 to 000, 1 year
			$t1=mktime(0,0,0,$ddate_mon,01,$ddate_yyyy);
			if($diff>=0)
			{
				$t2=gettimeofday();
				//t2 below hold the time in sec of the day
				$t2=$t2['sec'];
			}
			else
			{
				$last_day=getlastdayofmonth($ddate_mon,$ddate_yyyy);
				$t2=mktime(23,59,59,$ddate_mon,$last_day,$ddate_yyyy);
			}
			//$t->gives current time minus time 000
			$t=$t2-$t1;
			//$d gives time in days
			$d=$t/(60*60*24);
			//$d rounds days to two decimal places
			$d=round($d,2);
			//$ddarr->is an array of 1 to 31, representing days in a month
			for($i=0;$i<31;$i++)
			{
				$ddarr[$i]=$i+1;
			}

			if($profile_type=='E')
			{
				//To fetch Entered Profiles
				$date_type="ENTRY_DT ";
			}
			else
			{
				//To fetch modified profiles
				$date_type="MOD_DT ";
			}

			if($get_today)
			{
				//$ts->gives time in sec
				$ts=time();
				//$ts->gives $ts-2 days
				$ts-=2*24*60*60;
				//$today->stores the date
				$today=date("Y-m-d",$ts);
				//$sql2->SourceID
				//sql_h->src,cnt,dd,s,h
				if($business_flag)
		        	{
					$sql_hb="SELECT COUNT(*) as cnt, DAYOFMONTH(Date) as dd FROM MIS.HITS where Date>='$today' AND SourceID like 'af%' AND SourceID not like 'afl%' GROUP BY dd";
					$sql_mb="SELECT COUNT(*) as cnt, DAYOFMONTH(j.$date_type) as dd FROM newjs.JPROFILE j where j.$date_type>='$today' AND SOURCE like 'af%' AND SOURCE not like 'afl%' ";
					if($community)
	                                {
        	                                $sql_mb.=" AND j.MTONGUE='$community' ";
                        	        }
                                	if($gender)
                                	{
                                        	$sql_mb.=" AND j.GENDER='$gender' ";
                                	}
					if($mstatus)
                                	{
                                        	$sql_mb.=" AND j.MSTATUS='$mstatus' ";
                                	}
					if($country)
                                	{
                                        	$sql_mb.=" AND j.COUNTRY_RES='$country'";
                                	}
					if($activated)
                	                {
                        	                $sql_mb.=" AND j.ACTIVATED='$activated' ";
                                	}
					if($incomplete)
                	                {
                        	                $sql_mb.=" AND j.INCOMPLETE='$incomplete' ";
                                	}
					if($subs=='P')
                	                {
                        	                $sql_mb.=" AND j.SUBSCRIPTION<>'' ";
                                	}
					if($sec_sources){
						if($self)
							$sql_mb.="AND (j.SEC_SOURCE IS NULL OR j.SEC_SOURCE IN ( $sec_sources )) ";
						else
							$sql_mb.=" AND j.SEC_SOURCE IN ( $sec_sources ) ";
					}
					$sql_mb.=" GROUP BY dd";
				}
        			if(($sourcegp && !$business_flag) || !$sourcegp)
        			{
					$sql_h="SELECT s.$sql2 as src, COUNT(*) as cnt,DAYOFMONTH(Date) as dd FROM MIS.SOURCE s,MIS.HITS h WHERE Date>='$today' ";
					if($sourcegp)
						$sql_h.=" AND s.GROUPNAME='$sourcegp' ";
					$sql_h.=" AND s.SourceID=h.SourceID GROUP BY src,dd";
					//sql_m->src, cnt, dd, j.
					$sql_m="SELECT s.$sql2 as src, COUNT(*) as cnt,DAYOFMONTH(j.$date_type) as dd FROM MIS.SOURCE s,newjs.JPROFILE j WHERE j.$date_type>='$today'";
					if($community)
					{
						$sql_m.=" AND j.MTONGUE='$community' ";
					}
					if($gender)
					{
						$sql_m.=" AND j.GENDER='$gender' ";
					}
					if($mstatus)
					{
						$sql_m.=" AND j.MSTATUS='$mstatus' ";
					}
					if($country)
					{
						$sql_m.=" AND j.COUNTRY_RES='$country'";
					}
					if($activated)
					{
						$sql_m.=" AND j.ACTIVATED='$activated' ";
					}
					if($incomplete)
					{
						$sql_m.=" AND j.INCOMPLETE='$incomplete' ";
					}
					if($subs=='P')
					{
						$sql_m.=" AND j.SUBSCRIPTION<>'' ";
					}
					elseif($subs=='F')
					{
						$sql_m.=" AND j.SUBSCRIPTION='' ";
					}
					if($sec_sources){
						if($self)
							$sql_m.="AND (j.SEC_SOURCE IS NULL OR j.SEC_SOURCE IN ( $sec_sources )) ";
						else
							$sql_m.=" AND j.SEC_SOURCE IN ( $sec_sources ) ";
					}
					if($sourcegp)
						$sql_m.=" AND s.GROUPNAME='$sourcegp' ";
					$sql_m.=" AND s.SourceID=j.SOURCE GROUP BY src,dd";
				}
			}	
			else//for the entire month from start date.
			{
				if($business_flag)
				{
					$sql_hb="SELECT SUM(COUNT) as cnt, DAYOFMONTH(ENTRY_DT) as dd FROM MIS.SOURCE_HITS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date'  AND SOURCEID LIKE 'af%' AND SOURCEID not like 'afl%' GROUP BY dd";
					$sql_mb="SELECT SUM(COUNT) as cnt, DAYOFMONTH(ENTRY_DT) as dd FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND SOURCEID like 'af%' AND SOURCEID not like 'afl%' AND ENTRY_MODIFY='$profile_type' ";
					if($community)
	                                {
                	                        $sql_mb.=" AND MTONGUE='$community' ";
                        	        }
                                	if($gender)
                                	{
                                        	$sql_mb.=" AND GENDER='$gender' ";
                                	}
                                	if($mstatus)
                                	{
                                        	$sql_mb.=" AND MSTATUS='$mstatus' ";
                                	}
	                                if($country)
        	                        {
                	                        $sql_mb.=" AND COUNTRY_RES='$country'";
                        	        }
                                	if($activated)
                                	{
                                        	$sql_mb.=" AND ACTIVATED='$activated' ";
                                	}
	                                if($incomplete)
        	                        {
                        	                $sql_mb.=" AND INCOMPLETE='$incomplete' ";
                	                }
                                	if($subs=='P')
                                	{
                                        	$sql_mb.=" AND SUBSCRIPTION<>'' ";
                                	}
					elseif($subs=='F')
        	                        {
	                                        $sql_mb.=" AND SUBSCRIPTION='' ";
                	                }
					if($sec_sources){
						if($self)
							$sql_mb.="AND (SEC_SOURCE IS NULL OR SEC_SOURCE IN ( $sec_sources )) ";
						else
							$sql_mb.=" AND SEC_SOURCE IN ( $sec_sources ) ";
					}
                                        $sql_mb.=" GROUP BY dd";
				//here
				}
				if(($sourcegp && !$business_flag) || !$sourcegp)
				{
					$sql_h="SELECT $sql1 as src, SUM(COUNT) as cnt,DAYOFMONTH(ENTRY_DT) as dd FROM MIS.SOURCE_HITS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date'";
					if($sourcegp)
						$sql_h.=" AND SOURCEGP='$sourcegp' ";
					$sql_h.="GROUP BY src,dd";
					$sql_m="SELECT $sql1 as src, SUM(COUNT) as cnt,DAYOFMONTH(ENTRY_DT) as dd FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND ENTRY_MODIFY='$profile_type'";
					if($community)
					{
						$sql_m.=" AND MTONGUE='$community' ";
					}
					if($gender)
					{
						$sql_m.=" AND GENDER='$gender' ";
					}
					if($mstatus)
					{
						$sql_m.=" AND MSTATUS='$mstatus' ";
					}
					if($country)
					{
						$sql_m.=" AND COUNTRY_RES='$country'";
					}
					if($activated)
					{
						$sql_m.=" AND ACTIVATED='$activated' ";
					}
					if($incomplete)
					{
						$sql_m.=" AND INCOMPLETE='$incomplete' ";
					}
					if($subs=='P')
					{
						$sql_m.=" AND SUBSCRIPTION<>'' ";
					}
					elseif($subs=='F')
					{
						$sql_m.=" AND SUBSCRIPTION='' ";
					}
					if($sec_sources){
						if($self)
							$sql_m.="AND (j.SEC_SOURCE IS NULL OR j.SEC_SOURCE IN ( $sec_sources )) ";
						else
							$sql_m.=" AND j.SEC_SOURCE IN ( $sec_sources ) ";
					}
					if($sourcegp)
						$sql_m.=" AND SOURCEGP='$sourcegp' ";
					$sql_m.=" GROUP BY src,dd";
				}
			}//month querys over
			if($business_flag)
			{
	                 	$res_hb=mysql_query_decide($sql_hb,$db) or die("$sql_hb".mysql_error_js($db));
				while($row_hb=mysql_fetch_array($res_hb))
				{
					$dd=$row_hb['dd']-1;
                                        $b_count[$dd]["h"]+=$row_hb['cnt'];
                                        $b_tota["h"]+=$row_hb['cnt'];
                                }
				$res_mb=mysql_query_decide($sql_mb,$db) or die("$sql_mb".mysql_error_js($db));
				while($row_mb=mysql_fetch_array($res_mb))
                        	{
                                        $dd=$row_mb['dd']-1;
                                        $b_count[$dd]["m"]+=$row_mb['cnt'];
                                        $b_tota["m"]+=$row_mb['cnt'];
			        }	
				//end of if of Business_flag condition
			}
	 		if(($sourcegp && !$business_flag) || !$sourcegp)
			{
				$res_h=mysql_query_decide($sql_h,$db) or die("$sql_h".mysql_error_js($db));
				while($row_h=mysql_fetch_array($res_h))
				{
						//src->SourceID
					$src=$row_h['src'];
						//counter->count of number of rows derived from query
					$counter=$row_h['cnt'];
						//$i->relative position of $srcarr from $src=SourceID
					$i=array_search($src,$srcarr);
					if($i===NULL)
						$i=array_search('Unknown',$srcarr);
						//$dd->to take day as -1 from current date
					$dd=$row_h['dd']-1;
						//$i th SourceID on dd th day, in h reference
					$cnt[$i][$dd]["h"]+=$counter;
						//tota->$i th SourceID;gives sum of SourceID
					$tota[$i]["h"]+=$counter;
						//totb->no of querys executed 
					$totb[$dd]["h"]+=$counter;
						//totallh->total counters
					$totallh+=$counter;
						//$total_bs[$i][$dd]+=$counter;
				}
				$res_m=mysql_query_decide($sql_m,$db) or die("$sql_m".mysql_error_js($db));
				while($row_m=mysql_fetch_array($res_m))
				{
					$src=$row_m['src'];
					$counter=$row_m['cnt'];
					//if($src!==NULL)
					$i=array_search($src,$srcarr);
					if($i===NULL)
						$i=array_search('Unknown',$srcarr);
					$dd=$row_m['dd']-1;
					$cnt[$i][$dd]["m"]+=$counter;
					$tota[$i]["m"]+=$counter;
					$totb[$dd]["m"]+=$counter;
					$totallm+=$counter;
				}
			}
		}
		elseif($dt_type=="mnt")//if data type is month
		{
			unset($cnt);
			unset($tota);
			unset($totb);
			$smarty->assign("mflag",1);
			$mdate_yyyyp1=$mdate_yyyy+1;
			$st_date=$mdate_yyyy."-04-01";
			$end_date=$mdate_yyyyp1."-03-31";
			$mmarr=array('Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar');
			if($business_flag)
			{
				 $sql_ha="Select SUM(COUNT) as cnt, MONTH(ENTRY_DT) as mm FROM MIS.SOURCE_HITS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date'  AND SOURCEID LIKE 'af%' AND SOURCEID not like 'afl%' GROUP BY mm";
				 $sql_ma="Select SUM(COUNT) as cnt, MONTH(ENTRY_DT) as mm FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND SOURCEID like 'af%' AND SOURCEID not like 'afl%' AND ENTRY_MODIFY='$profile_type' ";
				 if($community)
                                 {
                                	 $sql_ma.=" AND MTONGUE='$community' ";
                                 }
                                 if($gender)
                                 {
                                 	 $sql_ma.=" AND GENDER='$gender' ";
                                 }
                                 if($mstatus)
                                 {
                                         $sql_ma.=" AND MSTATUS='$mstatus' ";
                                 }
                                 if($country)
                                 {
                                         $sql_ma.=" AND COUNTRY_RES='$country'";
                                 }
                                 if($activated)
                                 {
                                         $sql_ma.=" AND ACTIVATED='$activated' ";
                                 }
                                 if($incomplete)
                                 {
                                         $sql_ma.=" AND INCOMPLETE='$incomplete' ";
                                 }
                                 if($subs=='P')
                                 {
                                         $sql_ma.=" AND SUBSCRIPTION<>'' ";
                                 }
				 elseif($subs=='F')
                                 {
                                         $sql_ma.=" AND SUBSCRIPTION='' ";
                                 }
					if($sec_sources){
						if($self)
							$sql_ma.="AND (SEC_SOURCE IS NULL OR SEC_SOURCE IN ( $sec_sources )) ";
						else
							$sql_ma.=" AND SEC_SOURCE IN ( $sec_sources ) ";
					}
                                $sql_ma.=" GROUP BY mm";
				$res_ha=mysql_query_decide($sql_ha,$db) or die("$sql_ha".mysql_error_js($db));
                                while($row_ha=mysql_fetch_array($res_ha))
                                {
                                	$mm=$row_ha['mm'];
					if($mm<=3)
					{
						$mm+=8;
					}
					else
					{
						$mm-=4;
					}
                                        $b_count[$mm]["h"]+=$row_ha['cnt'];
                                        $b_tota["h"]+=$row_ha['cnt'];
                                }
				$res_ma=mysql_query_decide($sql_ma,$db) or die("$sql_ma".mysql_error_js($db));
        	                while($row_ma=mysql_fetch_array($res_ma))
                	        {
                       		        $mm=$row_ma['mm'];
					if($mm<=3)
					{
						$mm+=8;
					}
					else
					{
						$mm-=4;
					}
                                       	$b_count[$mm]["m"]+=$row_ma['cnt'];
                               		$b_tota["m"]+=$row_ma['cnt'];
                                }
			}//end of business flag conditon]
			if(($sourcegp && !$business_flag) || !$sourcegp)
			{
				$sql_h="SELECT $sql1 as src, SUM(COUNT) as cnt,MONTH(ENTRY_DT) as mm FROM MIS.SOURCE_HITS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date'";
				if($sourcegp)
					$sql_h.=" AND SOURCEGP='$sourcegp' ";
				else
					$sql_h.=" AND SOURCEGP<>'NONE' ";

				$sql_h.=" GROUP BY src,mm";
				$sql_m="SELECT $sql1 as src, SUM(COUNT) as cnt,MONTH(ENTRY_DT) as mm FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND ENTRY_MODIFY='$profile_type'";
				if($community)
				{	
					$sql_m.=" AND MTONGUE='$community' ";
				}
				if($gender)
				{
					$sql_m.=" AND GENDER='$gender' ";
				}
				if($mstatus)
				{
					$sql_m.=" AND MSTATUS='$mstatus' ";
				}
				if($country)
				{
					$sql_m.=" AND COUNTRY_RES='$country'";
				}
				if($activated)
				{
					$sql_m.=" AND ACTIVATED='$activated' ";
				}
				if($incomplete)
				{
					$sql_m.=" AND INCOMPLETE='$incomplete' ";
				}
				if($subs=='P')
				{
					$sql_m.=" AND SUBSCRIPTION<>'' ";
				}
				elseif($subs=='F')
				{
					$sql_m.=" AND SUBSCRIPTION='' ";
				}
				if($sourcegp)
					$sql_m.=" AND SOURCEGP='$sourcegp' ";
					if($sec_sources){
						if($self)
							$sql_m.="AND (SEC_SOURCE IS NULL OR SEC_SOURCE IN ( $sec_sources )) ";
						else
							$sql_m.=" AND SEC_SOURCE IN ( $sec_sources ) ";
					}
				$sql_m.=" GROUP BY src,mm";
				$res_h=mysql_query_decide($sql_h,$db) or die("$sql_h".mysql_error_js($db));
				while($row_h=mysql_fetch_array($res_h))
				{
					$src=$row_h['src'];
					$counter=$row_h['cnt'];
					$i=array_search($src,$srcarr);
					if($i===NULL)
						$i=array_search('Unknown',$srcarr);
					$mm=$row_h['mm'];
					if($mm<=3)
					{
						$mm+=8;
					}
					else
					{
						$mm-=4;
					}
					$cnt[$i][$mm]["h"]+=$counter;
					$tota[$i]["h"]+=$counter;
					$totb[$mm]["h"]+=$counter;
					$totallh+=$counter;
				}//while($row_h=mysql_fetch_array($res_h));
				$res_m=mysql_query_decide($sql_m,$db) or die("$sql_m".mysql_error_js($db));
				while($row_m=mysql_fetch_array($res_m))
				{
					$src=$row_m['src'];
					$counter=$row_m['cnt'];
					$i=array_search($src,$srcarr);
					if($i===NULL)
						$i=array_search('Unknown',$srcarr);
					$mm=$row_m['mm'];
					if($mm<=3)
					{
						$mm+=8;
					}
					else
					{
						$mm-=4;
					}
					$cnt[$i][$mm]["m"]+=$counter;
					$tota[$i]["m"]+=$counter;
					$totb[$mm]["m"]+=$counter;
					$totallm+=$counter;
				}//while($row_m=mysql_fetch_array($res_m));
			}
		}
		if($dt_type=="day")
		{
			$num=count($ddarr);
		}
		elseif($dt_type=="mnt")
		{
			$num=count($mmarr);
		}
		if ($business_flag)
		{
		        for($j=0;$j<$num;$j++)
                        {
                        	if($b_count[$j]["h"])
                                {
                                	  $b_count[$j]["p"]=($b_count[$j]["m"]/$b_count[$j]["h"]) * 100;
                                          $b_count[$j]["p"]=round($b_count[$j]["p"],2);
                                }
			}
			if($d)
			{
				  $b_tota["e"]=$b_tota["m"]/$d;
				  $b_tota["e"]=round($b_tota["e"],2);
			}
			if($b_tota["h"])
			{
				  $b_tota["p"]=$b_tota["m"]/$b_tota["h"] * 100;
				  $b_tota["p"]=round($b_tota["p"],2);
			}
		}
		if(($sourcegp && !$business_flag) || !$sourcegp)
		{
			for($i=0;$i<count($srcarr);$i++)
			{
				for($j=0;$j<$num;$j++)
				{
					if($cnt[$i][$j]["h"])
					{
						$cnt[$i][$j]["p"]=$cnt[$i][$j]["m"]/$cnt[$i][$j]["h"] * 100;
						$cnt[$i][$j]["p"]=round($cnt[$i][$j]["p"],2);
					}
				}
				if($tota[$i]["h"])
				{
					$tota[$i]["p"]=$tota[$i]["m"]/$tota[$i]["h"] * 100;
					$tota[$i]["p"]=round($tota[$i]["p"],2);
				}
				if($d)
				{
					$avgtota[$i]=$tota[$i]["m"]/$d;
					$avgtota[$i]=round($avgtota[$i],2);
				}
			}
			for($j=0;$j<$num;$j++)
			{
				if($totb[$j]["h"])
				{
					$totb[$j]["p"]=$totb[$j]["m"]/$totb[$j]["h"] * 100;
					$totb[$j]["p"]=round($totb[$j]["p"],2);
				}
			}
			if($d)
			{
				$avgtotallm=$totallm/$d;
				$avgtotallm=round($avgtotallm,2);
			}
			if($totallh)
			{
				$totallp=$totallm/$totallh * 100;
				$totallp=round($totallp,2);
			}
		}

                if($JSIndicator==1)
                {
                        return;
                }

		if ($sourcegp=="Business_Sathi" || $sourcegp=="")
			$flag_bs=1;//To display business sathi
		else
			$flag_bs=0;
		if ($sourcegp=="Business_Sathi")
			$flag_b=1;
		else
			$flag_b=0;//To print total line
	

		if($sourcegp=="")
		{
			$sourcegp="All";
		}
		if($community[0]=="")
		{
			$community[0]="All";
		}
		if($gender=="")
		{
			$gender="All";
		}
		if($mstatus=="")
		{
			$mstatus="All";
		}
		if($country[0]=="")
		{
			$country[0]="All";
		}
		if($subs=="")
		{
			$subs="All";
		}
		if($activated=="")
		{
			$activated="All";
		}
		if($incomplete=="")
		{
			$incomplete="All";
		}
		
		$header = "Source : ".$sourcegp."\t"."Community : ".$community[0]."\t"."Gender : ".$gender."\t"."Marital Status : ".$mstatus."\t"."Country : ".$country[0]."\t"."Subscription : ".$subs."\t"."Activated : ".$activated."\t"."Incomplete : ".$incomplete."\t"."Secondary Source : ".$sec_sources."\t \n";
		
		if($dt_type=="day")
		{
			$loop=31;
			$header .= "\n";
			$header .= "Day"."\t"."Color";
			for($i=1;$i<=31;$i++)
			{
				$header .= "\t".$i;
			}
			$header .= "\t"."Total"."\t"."Color"."\t"."Day";
		}
		if($dt_type=="mnt")
		{
			$loop=12;
			$header .= "\n";
			$header .= "Month"."\t"."Color";
			$header .= "\t"."Apr"."\t"."May"."\t"."Jun"."\t"."Jul"."\t"."Aug"."\t"."Sep"."\t"."Oct"."\t"."Nov"."\t"."Dec"."\t"."Jan"."\t"."Feb"."\t"."Mar";
			$header .= "\t"."Total"."\t"."Color"."\t"."Day";
		}

		for($t=0;$t<count($srcarr);$t++)
			$srcarr[$t] = trim($srcarr[$t]);

		for($source=0;$source<count($srcarr);$source++)
		{
			$data .= $srcarr[$source];
			
			if(count($src_namearr)!=0)
				$data .= ", (".$src_namearr[$source].")";

			$data .= "\t"."C - Conversion Ratio";
			for($date=0;$date<$loop;$date++)
			{
				$data .= "\t".$cnt[$source][$date]['p'];
			}
			$data .= "\t".$tota[$source]['p'];
			$data .= "\t"."C";
			$data .= "\t".$srcarr[$source];
			
			if(count($src_namearr)!=0)
                                $data .= ", (".$src_namearr[$source].")";
			
			$data .= "\n";

			$data .= "\t"."R - Registrations";
			for($date=0;$date<$loop;$date++)
			{
				$data .= "\t".$cnt[$source][$date]['m'];
			}
			$data .= "\t".$tota[$source]['m'];
			$data .= "\t"."R";
			
			$data .= "\n";
			
			$data .= "\t"."A - Average Profiles";
			for($date=0;$date<$loop;$date++)
			{
				$data .= "\t".$cnt[$source][$date]['e'];
			}
			$data .= "\t".$avgtota[$source];
			$data .= "\t"."A";
															     
			$data .= "\n";

			$data .= "\t"."H - Hits";
			for($date=0;$date<$loop;$date++)
			{
				$data .= "\t".$cnt[$source][$date]['h'];
			}
			$data .= "\t".$tota[$source]['h'];
			$data .= "\t"."H";
															     
			$data .= "\n";
		}

		if($flag_b == "0")
		{
			$data .= "Total";
			$data .= "\t"."C - Conversion Ratio";
			for($date=0;$date<$loop;$date++)
			{
				$data .= "\t".$totb[$date]['p'];
			}
			$data .= "\t".$totallp;
			$data .= "\t"."C";
			$data .= "\t"."Total";
															     
			$data .= "\n";

			$data .= "\t"."R - Registrations";
			for($date=0;$date<$loop;$date++)
			{
				$data .= "\t".$totb[$date]['m'];
			}
			$data .= "\t".$totallm;
			$data .= "\t"."R";
															     
			$data .= "\n";
															     
			$data .= "\t"."A - Average Profiles";
			for($date=0;$date<$loop;$date++)
			{
				$data .= "\t".$totb[$date]['e'];
			}
			$data .= "\t".$avgtotallm;
			$data .= "\t"."A";
															     
			$data .= "\n";

			$data .= "\t"."H - Hits";
			for($date=0;$date<$loop;$date++)
			{
				$data .= "\t".$totb[$date]['h'];
			}
			$data .= "\t".$totallh;
			$data .= "\t"."H";
														     
			$data .= "\n";
		}

		if($flag_bs == "1")
		{
			$data .= "Business Sathi";
			$data .= "\t"."C - Conversion Ratio";
			for($date=1;$date<=$loop;$date++)
			{
				$data .= "\t".$b_count[$date]['p'];
			}
			$data .= "\t".$b_tota['p'];
			$data .= "\t"."C";
			$data .= "\t"."Business Sathi";
															     
			$data .= "\n";

			$data .= "\t"."R - Registrations";
			for($date=0;$date<$loop;$date++)
			{
				$data .= "\t".$b_count[$date]['m'];
			}
			$data .= "\t".$b_tota['m'];
			$data .= "\t"."R";
															     
			$data .= "\n";

			$data .= "\t"."A - Average Profiles";
			for($date=0;$date<$loop;$date++)
			{
				$data .= "\t";
			}
			$data .= "\t".$b_tota['e'];
			$data .= "\t"."A";
															     
			$data .= "\n";

			$data .= "\t"."H - Hits";
			for($date=0;$date<$loop;$date++)
			{
				$data .= "\t".$b_count[$date]['h'];
			}
			$data .= "\t".$b_tota['h'];
			$data .= "\t"."H";
															     
			$data .= "\n";
		}

		//$data = ereg_replace("\r\n|\n\r|\n|\r"," , ",str_replace("\"","'",$data));
		$data = trim($data)."\t \n";

		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Collection_Details.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo $final_data = $header."\n".$data;
	}
/***************************************************************************************************************************
end of code added by Sriram Viswanathan
***************************************************************************************************************************/
	else//if CMDGo value is  not present, it becomes Go after executing srcinit.htm
	{	
		$sql="SELECT DISTINCT GROUPNAME FROM MIS.SOURCE";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		$i=0;
		while($row=mysql_fetch_array($res))
		{
			$srcgrp[$i]=$row['GROUPNAME'];
			$i++;
		}
		$srcgrp[]="Business_Sathi";

		$sql="SELECT VALUE,SMALL_LABEL FROM newjs.MTONGUE WHERE 1";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		$i=0;
		while($row=mysql_fetch_array($res))
		{
			$commarr[$i]["VAL"]=$row['VALUE'];
			$commarr[$i]["LABEL"]=$row['SMALL_LABEL'];
			$i++;
		}

		$sql="SELECT VALUE,LABEL FROM newjs.TOP_COUNTRY WHERE 1";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		$i=0;
		while($row=mysql_fetch_array($res))
		{
			$ctryarr[$i]['VAL']=$row['VALUE'];
			$ctryarr[$i]['LABEL']=$row['LABEL'];
			$i++;
		}

		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}
		for($i=0;$i<12;$i++)
		{
			$mmarr[$i]=$i+1;
		}
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("commarr",$commarr);
		$smarty->assign("ctryarr",$ctryarr);
		$smarty->assign("mstatusarr",$MSTATUS);
		$smarty->assign("srcgrp",$srcgrp);
		$smarty->assign("checksum",$checksum);
		//$smarty->assign("total",$total);
		$smarty->display("srcinit.htm");
	}
	//else condition from Business_flag
}
else
{
	$smarty->display("jsconnectError.tpl");
}

// flush the buffer
if($zipIt)
	ob_end_flush();
?>
