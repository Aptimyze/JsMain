<?php

//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
        $zipIt = 1;
if($zipIt)
        ob_start("ob_gzhandler");
//end of it

include("connect.inc");

$db=connect_misdb();

if(authenticated($cid))
{
	$flag=0;
	if($CMDGo)
	{
		if($sourcegp!='')
		{
			if($get_today)
			{
				$SOURCE_SEL="GROUPNAME";
			}
			else
			{
				$SOURCE_SEL="SOURCEGP";
			}
			if($sourcegp=="ALL")
				$sourceval="";
			else
				$sourceval=$sourcegp;
			$smarty->assign("sourceval",$sourceval);
		}
		elseif($sourceid!='')
		{
			if($get_today)
			{
				$SOURCE_SEL="SourceID";
			}
			else
			{
				$SOURCE_SEL="SOURCEID";
			}
			$sourceval=$sourceid;
			$smarty->assign("sourceval",$sourceval);
		}

		if($get_today)
		{
			$ts=time();
			$ts-=2*24*60*60;
			$today=date("Y-m-d",$ts);
			$dt_type="day";
			/*if($grouping=='age')
			{
				$agearr=array('below20','21-24','25-30','31+');

				for($i=0;$i<31;$i++)
				{
					$ddarr[$i]=$i+1;
				}

				$sql="SELECT j.AGE as ages, COUNT(*) as cnt,DAYOFMONTH(j.ENTRY_DT) as dd,j.GENDER FROM MIS.SOURCE s,newjs.JPROFILE j WHERE j.ENTRY_DT>='$today' AND s.$SOURCE_SEL='$sourceval' AND  j.INCOMPLETE<>'Y' AND j.ACTIVATED<>'D' AND s.SourceID=j.SOURCE GROUP BY GENDER,ages,dd";
				$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
				while($row=mysql_fetch_array($res))
				{
					$src=$row_m['ages'];
					$gender=$row_m['GENDER'];
					if($gender=='M')
						$k=0;
					else
						$k=1;
					$counter=$row_m['cnt'];
				 
					if($src<=20)
						$i=0;
					elseif($src>20 && $src<25)
						$i=1;
					elseif($src>=25 && $src<=30)
						$i=2;
					else
						$i=3;

					$dd=$row_m['dd']-1;
					$cntg[$i][$dd][$k]+=$counter;
					$cnt[$i][$dd]+=$counter;
					$totga[$i][$k]+=$counter;
					$tota[$i]+=$counter;
					$totgb[$dd][$k]+=$counter;
					$totb[$dd]+=$counter;
					$totallg[$k]+=$counter;
					$totall+=$counter;
				} 

				$smarty->assign("cnt",$cnt);
				$smarty->assign("cntg",$cntg);
				$smarty->assign("totga",$totga);
				$smarty->assign("tota",$tota);
				$smarty->assign("totgb",$totgb);
				$smarty->assign("totb",$totb);
				$smarty->assign("totallg",$totallg);
				$smarty->assign("totall",$totall);
				$smarty->assign("get_today",$get_today);

				$smarty->assign("source",$sourcegp);
				$smarty->assign("srcarr",$agearr);
				$smarty->assign("mmarr",$mmarr);
				$smarty->assign("ddarr",$ddarr);

				$smarty->display("ageloc_sourcehits.htm");
			}
			else
			{
				$smarty->assign("locflag",1);
				
				$sql="SELECT j.GENDER,j.COUNTRY_RES as CTR,j.CITY_RES as CTY, COUNT(*) as cnt,DAYOFMONTH(j.ENTRY_DT) as dd FROM MIS.SOURCE s,newjs.JPROFILE j WHERE j.ENTRY_DT>='$today' AND s.$SOURCE_SEL='$sourceval' AND  j.INCOMPLETE<>'Y' AND j.ACTIVATED<>'D' AND s.SourceID=j.SOURCE GROUP BY GENDER,CTR,CTY,dd";
				$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
				while($row=mysql_fetch_array($res))
				{
					$dd=$row_m['dd']-1;
					$ctrsrc=$row_m['CTR'];
					$ctysrc=$row_m['CTY'];
					$counter=$row_m['cnt'];
					$gender=$row_m['GENDER'];
					if($gender=='M')
						$k=0;
					else
						$k=1;
					if($ctrsrc=='51' )
					{
						if(is_array($indarrval))
						{
							if(!in_array($ctysrc,$indarrval))
							{
								$indarrval[]=$ctysrc;
								$lab=label_select("CITY_INDIA",$ctysrc);
								$indarrlab[]=$lab[0];
							}
						}
						else
						{
							$indarrval[]=$ctysrc;
							$lab=label_select("CITY_INDIA",$ctysrc);
							$indarrlab[]=$lab[0];
						}
						$i=array_search($ctysrc,$indarrval);
						$icntg[$i][$dd][$k]+=$counter;
						$icnt[$i][$dd]+=$counter;
						$itotga[$i][$k]+=$counter;
						$itota[$i]+=$counter;
						$itotgb[$dd][$k]+=$counter;
						$itotb[$dd]+=$counter;
						$itotallg[$k]+=$counter;
						$itotall+=$counter;
					}
					elseif($ctrsrc=='128')
					{
						if(is_array($usaarrval))
						{
							if(!in_array($ctysrc,$usaarrval))
							{
								$usaarrval[]=$ctysrc;
								$lab=label_select("CITY_USA",$ctysrc);
								$usaarrlab[]=$lab[0];
							}
						}
						else
						{
							$usaarrval[]=$ctysrc;
							$lab=label_select("CITY_USA",$ctysrc);
							$usaarrlab[]=$lab[0];
						}
						$i=array_search($ctysrc,$usaarrval);
						$ucntg[$i][$dd][$k]+=$counter;
						$ucnt[$i][$dd]+=$counter;
						$utotga[$i][$k]+=$counter;
						$utota[$i]+=$counter;
						$utotgb[$dd][$k]+=$counter;
						$utotb[$dd]+=$counter;
						$utotallg[$k]+=$counter;
						$utotall+=$counter;
					}
					else
					{
						if(is_array($ctryarrval))
						{
							if(!in_array($ctrsrc,$ctryarrval))
							{
								$ctryarrval[]=$ctrsrc;
								$lab=label_select("COUNTRY",$ctrsrc);
								$ctryarrlab[]=$lab[0];
							}
						}
						else
						{
							$ctryarrval[]=$ctrsrc;
							$lab=label_select("COUNTRY",$ctrsrc);
							$ctryarrlab[]=$lab[0];
						}
						$i=array_search($ctrsrc,$ctryarrval);
						$ocntg[$i][$dd][$k]+=$counter;
						$ocnt[$i][$dd]+=$counter;
						$ototga[$i][$k]+=$counter;
						$otota[$i]+=$counter;
						$ototgb[$dd][$k]+=$counter;
						$ototb[$dd]+=$counter;
						$ototallg[$k]+=$counter;
						$ototall+=$counter;
					}
				}

				$smarty->assign("icnt",$icnt);
				$smarty->assign("itota",$itota);
				$smarty->assign("itotb",$itotb);
				$smarty->assign("itotall",$itotall);
				$smarty->assign("ocnt",$ocnt);
				$smarty->assign("otota",$otota);
				$smarty->assign("ototb",$ototb);
				$smarty->assign("ototall",$ototall);
				$smarty->assign("ucnt",$ucnt);
				$smarty->assign("utota",$utota);
				$smarty->assign("utotb",$utotb);
				$smarty->assign("utotall",$utotall);
				$smarty->assign("icntg",$icntg);
				$smarty->assign("itotga",$itotga);
				$smarty->assign("itotgb",$itotgb);
				$smarty->assign("itotallg",$itotallg);
				$smarty->assign("ocntg",$ocntg);
				$smarty->assign("ototga",$ototga);
				$smarty->assign("ototgb",$ototgb);
				$smarty->assign("ototallg",$ototallg);
				$smarty->assign("ucntg",$ucntg);
				$smarty->assign("utotga",$utotga);
				$smarty->assign("utotgb",$utotgb);
				$smarty->assign("utotallg",$utotallg);
				$smarty->assign("source",$sourcegp);
			
				$smarty->assign("usaarrlab",$usaarrlab);
				$smarty->assign("indarrlab",$indarrlab);
				$smarty->assign("ctryarrlab",$ctryarrlab);
				
				$smarty->assign("mmarr",$mmarr);
				$smarty->assign("ddarr",$ddarr);
				$smarty->assign("hharr",$hharr);
												
				$smarty->display("ageloc_sourcehits.htm");
			}*/
		}
//		else
//		{
		if($dt_type=='mnt')
		{
			$smarty->assign("mflag",1);
			$mdate_yyyyp1=$mdate_yyyy+1;
			$smarty->assign("dt",$mdate_yyyy);
			$smarty->assign("dt1",$mdate_yyyyp1);

			$mmarr=array('Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar');
			$st_date=$mdate_yyyy."-04-01 00:00:00";
			$end_date=$mdate_yyyyp1."-03-31 23:59:59";
			$sql_dt="MONTH(ENTRY_DT)";
		}
		else
		{
			$smarty->assign("dflag",1);
			$smarty->assign("dt","$ddate_mon-$ddate_yyyy");
			$agearr=array('below20','21-24','25-30','31+');

			for($i=0;$i<31;$i++)
			{
				$ddarr[$i]=$i+1;
			}
			$st_date=$ddate_yyyy."-".$ddate_mon."-01 00:00:00";
			$end_date=$ddate_yyyy."-".$ddate_mon."-31 23:59:59";
			$sql_dt="DAYOFMONTH(ENTRY_DT)";
		}

		if($grouping=="age")
		{	
			$agearr=array('below20','21-24','25-30','31+');		

			if($get_today)
			{
				if($sourceval)
					$sql_m="SELECT j.AGE as ages, COUNT(*) as cnt,DAYOFMONTH(j.ENTRY_DT) as dd,j.GENDER FROM MIS.SOURCE s,newjs.JPROFILE j WHERE j.ENTRY_DT>='$today' AND s.$SOURCE_SEL='$sourceval' AND  j.INCOMPLETE<>'Y' AND j.ACTIVATED<>'D' AND s.SourceID=j.SOURCE GROUP BY GENDER,ages,dd";
				else
					$sql_m="SELECT j.AGE as ages, COUNT(*) as cnt,DAYOFMONTH(j.ENTRY_DT) as dd,j.GENDER FROM MIS.SOURCE s,newjs.JPROFILE j WHERE j.ENTRY_DT>='$today' AND j.INCOMPLETE<>'Y' AND j.ACTIVATED<>'D' AND s.SourceID=j.SOURCE GROUP BY GENDER,ages,dd";
			}
			else
			{
				if($sourceval)
					$sql_m="SELECT AGE as ages, SUM(COUNT) as cnt,$sql_dt as dd,GENDER FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND $SOURCE_SEL='$sourceval' AND  INCOMPLETE<>'Y' AND ACTIVATED<>'D' AND ENTRY_MODIFY='E' GROUP BY GENDER,ages,dd";
				else
					$sql_m="SELECT AGE as ages, SUM(COUNT) as cnt,$sql_dt as dd,GENDER FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND INCOMPLETE<>'Y' AND ACTIVATED<>'D' AND ENTRY_MODIFY='E' GROUP BY GENDER,ages,dd";
			}

			$res_m=mysql_query_decide($sql_m,$db) or die(mysql_error_js());
			if($row_m=mysql_fetch_array($res_m))
			{
				do
				{
					$src=$row_m['ages'];
					$gender=$row_m['GENDER'];
					if($gender=='M')
						$k=0;
					else
						$k=1;
					$counter=$row_m['cnt'];
				 
					if($src<=20)
						$i=0;
					elseif($src>20 && $src<25)
						$i=1;
					elseif($src>=25 && $src<=30)
						$i=2;
					else
						$i=3;

					if($dt_type=='mnt')
					{
						$dd=$row_m['dd'];
						if($dd<=3)
						{
							$dd+=8;
						}
						else
						{
							$dd-=4;
						}
					}
					else
					{
						$dd=$row_m['dd']-1;
					}
					$cntg[$i][$dd][$k]+=$counter;
					$cnt[$i][$dd]+=$counter;
					$totga[$i][$k]+=$counter;
					$tota[$i]+=$counter;
					$totgb[$dd][$k]+=$counter;
					$totb[$dd]+=$counter;
					$totallg[$k]+=$counter;
					$totall+=$counter;
				}while($row_m=mysql_fetch_array($res_m));
			} 
			$smarty->assign("cnt",$cnt);
			$smarty->assign("cntg",$cntg);
			$smarty->assign("totga",$totga);
			$smarty->assign("tota",$tota);
			$smarty->assign("totgb",$totgb);
			$smarty->assign("totb",$totb);
			$smarty->assign("totallg",$totallg);
			$smarty->assign("totall",$totall);

			$smarty->assign("source",$sourcegp);
			$smarty->assign("srcarr",$agearr);
			$smarty->assign("mmarr",$mmarr);
			$smarty->assign("ddarr",$ddarr);

			$smarty->display("ageloc_sourcehits.htm");
		}
		else
		{
			$smarty->assign("locflag",1);

			if($get_today)
			{
				if($sourceval)
					$sql_m="SELECT j.GENDER,j.COUNTRY_RES as CTR,j.CITY_RES as CTY, COUNT(*) as cnt,DAYOFMONTH(j.ENTRY_DT) as dd FROM MIS.SOURCE s,newjs.JPROFILE j WHERE j.ENTRY_DT>='$today' AND s.$SOURCE_SEL='$sourceval' AND  j.INCOMPLETE<>'Y' AND j.ACTIVATED<>'D' AND s.SourceID=j.SOURCE GROUP BY GENDER,CTR,CTY,dd";
				else
					$sql_m="SELECT j.GENDER,j.COUNTRY_RES as CTR,j.CITY_RES as CTY, COUNT(*) as cnt,DAYOFMONTH(j.ENTRY_DT) as dd FROM MIS.SOURCE s,newjs.JPROFILE j WHERE j.ENTRY_DT>='$today' AND  j.INCOMPLETE<>'Y' AND j.ACTIVATED<>'D' AND s.SourceID=j.SOURCE GROUP BY GENDER,CTR,CTY,dd";
			}
			else
			{	
				if($sourceval)			
					$sql_m="SELECT GENDER,COUNTRY_RES as CTR,CITY_RES as CTY, SUM(COUNT) as cnt,$sql_dt as dd FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND $SOURCE_SEL='$sourceval' AND  INCOMPLETE<>'Y' AND ACTIVATED<>'D' AND ENTRY_MODIFY='E' GROUP BY GENDER,CTR,CTY,dd"; 
				else
					$sql_m="SELECT GENDER,COUNTRY_RES as CTR,CITY_RES as CTY, SUM(COUNT) as cnt,$sql_dt as dd FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND  INCOMPLETE<>'Y' AND ACTIVATED<>'D' AND ENTRY_MODIFY='E' GROUP BY GENDER,CTR,CTY,dd";
			}
			
			$res_m=mysql_query_decide($sql_m,$db) or die(mysql_error_js());
			if($row_m=mysql_fetch_array($res_m))
			{
				do
				{
					if($dt_type=='mnt')
					{
						$dd=$row_m['dd'];
						if($dd<=3)
						{
							$dd+=8;
						}
						else
						{
							$dd-=4;
						}
					}
					else
					{
						$dd=$row_m['dd']-1;
					}
					$ctrsrc=$row_m['CTR'];
					$ctysrc=$row_m['CTY'];
					$counter=$row_m['cnt'];
					$gender=$row_m['GENDER'];
					if($gender=='M')
						$k=0;
					else
						$k=1;
					if($ctrsrc=='51' )
					{
						if(is_array($indarrval))
						{
							if(!in_array($ctysrc,$indarrval))
							{
								$indarrval[]=$ctysrc;
								$lab=label_select("CITY_NEW",$ctysrc);
								$indarrlab[]=$lab[0];
							}
						}
						else
						{
							$indarrval[]=$ctysrc;
							$lab=label_select("CITY_NEW",$ctysrc);
							$indarrlab[]=$lab[0];
						}
						$i=array_search($ctysrc,$indarrval);
						$icntg[$i][$dd][$k]+=$counter;
						$icnt[$i][$dd]+=$counter;
						$itotga[$i][$k]+=$counter;
						$itota[$i]+=$counter;
						$itotgb[$dd][$k]+=$counter;
						$itotb[$dd]+=$counter;
						$itotallg[$k]+=$counter;
						$itotall+=$counter;
					}
					elseif($ctrsrc=='128')
					{
						if(is_array($usaarrval))
						{
							if(!in_array($ctysrc,$usaarrval))
							{
								$usaarrval[]=$ctysrc;
								$lab=label_select("CITY_NEW",$ctysrc);
								$usaarrlab[]=$lab[0];
							}
						}
						else
						{
							$usaarrval[]=$ctysrc;
							$lab=label_select("CITY_NEW",$ctysrc);
							$usaarrlab[]=$lab[0];
						}
						$i=array_search($ctysrc,$usaarrval);
						$ucntg[$i][$dd][$k]+=$counter;
						$ucnt[$i][$dd]+=$counter;
						$utotga[$i][$k]+=$counter;
						$utota[$i]+=$counter;
						$utotgb[$dd][$k]+=$counter;
						$utotb[$dd]+=$counter;
						$utotallg[$k]+=$counter;
						$utotall+=$counter;
					}
					else
					{
						if(is_array($ctryarrval))
						{
							if(!in_array($ctrsrc,$ctryarrval))
							{
								$ctryarrval[]=$ctrsrc;
								$lab=label_select("COUNTRY",$ctrsrc);
								$ctryarrlab[]=$lab[0];
							}
						}
						else
						{
							$ctryarrval[]=$ctrsrc;
							$lab=label_select("COUNTRY",$ctrsrc);
							$ctryarrlab[]=$lab[0];
						}
						$i=array_search($ctrsrc,$ctryarrval);
						$ocntg[$i][$dd][$k]+=$counter;
						$ocnt[$i][$dd]+=$counter;
						$ototga[$i][$k]+=$counter;
						$otota[$i]+=$counter;
						$ototgb[$dd][$k]+=$counter;
						$ototb[$dd]+=$counter;
						$ototallg[$k]+=$counter;
						$ototall+=$counter;
					}
				}while($row_m=mysql_fetch_array($res_m));
			}

			$smarty->assign("icnt",$icnt);
			$smarty->assign("itota",$itota);
			$smarty->assign("itotb",$itotb);
			$smarty->assign("itotall",$itotall);
			$smarty->assign("ocnt",$ocnt);
			$smarty->assign("otota",$otota);
			$smarty->assign("ototb",$ototb);
			$smarty->assign("ototall",$ototall);
			$smarty->assign("ucnt",$ucnt);
			$smarty->assign("utota",$utota);
			$smarty->assign("utotb",$utotb);
			$smarty->assign("utotall",$utotall);
			$smarty->assign("icntg",$icntg);
			$smarty->assign("itotga",$itotga);
			$smarty->assign("itotgb",$itotgb);
			$smarty->assign("itotallg",$itotallg);
			$smarty->assign("ocntg",$ocntg);
			$smarty->assign("ototga",$ototga);
			$smarty->assign("ototgb",$ototgb);
			$smarty->assign("ototallg",$ototallg);
			$smarty->assign("ucntg",$ucntg);
			$smarty->assign("utotga",$utotga);
			$smarty->assign("utotgb",$utotgb);
			$smarty->assign("utotallg",$utotallg);
			$smarty->assign("source",$sourcegp);
		
			$smarty->assign("usaarrlab",$usaarrlab);
			$smarty->assign("indarrlab",$indarrlab);
			$smarty->assign("ctryarrlab",$ctryarrlab);
			
			$smarty->assign("mmarr",$mmarr);
			$smarty->assign("ddarr",$ddarr);
			$smarty->assign("hharr",$hharr);
											
			$smarty->display("ageloc_sourcehits.htm");
		}
		//}
	}
	else
	{	
		$sql="SELECT DISTINCT GROUPNAME FROM SOURCE";// WHERE GROUPNAME<>'NONE'";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		$i=0;
		while($row=mysql_fetch_array($res))
		{
			$srcgrp[$i]=$row['GROUPNAME'];
			$i++;
		}

		$sql="SELECT DISTINCT SourceID,GROUPNAME FROM SOURCE";// WHERE GROUPNAME<>'NONE'";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		$i=0;
		while($row=mysql_fetch_array($res))
		{
			$srcid[$i]["id"]=$row['SourceID'];
			$srcid[$i]["name"]=$row['GROUPNAME'];
			
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

		$smarty->assign("cid",$cid);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("srcid",$srcid);
		$smarty->assign("srcgrp",$srcgrp);
		$smarty->assign("srcgrpsel",$srcgp);
		$smarty->display("ageloc_srcinit.htm");
	}
}
else
{
	$smarty->display("jsconnectError.tpl");
}

// flush the buffer
if($zipIt)
        ob_end_flush();

?>
