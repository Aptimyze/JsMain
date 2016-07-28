<?php
include("connect.inc");
$db=connect_misdb();

$data=authenticated($checksum);

if(isset($data))
{

	if($CMDGo)
	{
		$flag=1;

		if($type=='M')
		{
			$mflag=1;
			$myearp1=$myear+1;
			$start_date=$myear."-04-01 00:00:00";
			$end_date=$myearp1."-03-31 23:59:59";

			$mmarr=array('Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar');

			// query to get monthly count of people who have registered on JS 
			//$sql1 = "SELECT COUNT(*) as cnt,MONTH(ENTRY_DT) as mm FROM newjs.JPROFILE WHERE ENTRY_DT BETWEEN '$start_date' AND '$end_date' GROUP BY mm";
			$sql1 = "SELECT SUM(COUNT) as cnt,MONTH(ENTRY_DT) as mm FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$start_date' AND '$end_date' AND ENTRY_MODIFY='E' GROUP BY mm";
			$res1 = mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js());
			while($row1=mysql_fetch_array($res1))
                        {
                                $mm = $row1['mm'];
                                if ($mm <= 3)
                                {
                                        $mm+=8;
                                }
                                else
                                {
                                        $mm-=4;
                                }
                                $photo_countarr[$mm]['REGISTERED'] = $row1['cnt'];
				$photo_countarr['REGISTERED_TOT']+= $row1['cnt'];
				//$register_tot+= $photo_countarr['REGISTERED'][$mm];
                        }
			
			// query to get monthly count of people who have registered on JS and their profile is active
			//$sql2 = "SELECT COUNT(*) as cnt,MONTH(ENTRY_DT) as mm FROM newjs.JPROFILE WHERE ENTRY_DT BETWEEN '$start_date' AND '$end_date' AND ACTIVATED = 'Y' GROUP BY mm";
			$sql2 = "SELECT SUM(COUNT) as cnt,MONTH(ENTRY_DT) as mm FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$start_date' AND '$end_date' AND ACTIVATED = 'Y' AND ENTRY_MODIFY='E' GROUP BY mm";
			$res2 = mysql_query_decide($sql2,$db) or die("$sql2".mysql_error_js());
			while($row2=mysql_fetch_array($res2))
                        {
                                $mm = $row2['mm'];
                                if ($mm <= 3)
                                {
                                        $mm+=8;
                                }
                                else
                                {
                                        $mm-=4;
                                }
                                $photo_countarr[$mm]['ACTIVATED'] = $row2['cnt'];
				$photo_countarr['ACTIVATED_TOT']+= $row2['cnt'];
                        }


// query to find monthly count of people who have registered on JS and are paid members now (with/ without photos)
			$sql3 = "SELECT COUNT( * ) AS cnt, HAVEPHOTO, MONTH( j.ENTRY_DT ) AS mm FROM billing.PURCHASES p , newjs.JPROFILE j WHERE p.PROFILEID = j.PROFILEID AND j.ENTRY_DT BETWEEN '$start_date' AND '$end_date' GROUP BY mm, j.HAVEPHOTO";
                        $res3 = mysql_query_decide($sql3,$db) or die("$sql3".mysql_error_js());
			while($row3=mysql_fetch_array($res3))
                        {
                                $mm = $row3['mm'];
                                if ($mm <= 3)
				{
					$mm+=8;
                                }
                                else
                                {
                                        $mm-=4;
                                }
				if ($row3['HAVEPHOTO'] == 'Y')
				{
                                	$photo_countarr[$mm]['PAID_PHOTO'] = $row3['cnt'];
					$photo_countarr['PAID_PHOTO_TOT']+= $row3['cnt'];
				}

				$photo_countarr[$mm]['PAID']+= $row3['cnt'];
				$photo_countarr['PAID_TOT']+= $row3['cnt'];
				if ($photo_countarr[$mm]['PAID']!= 0 && $photo_countarr[$mm]['PAID_PHOTO']!= 0)
				{
				//	$photo_countarr[$mm]['PAID_PHOTO_PER'] = round($photo_countarr[$mm]['PAID_PHOTO']/$photo_countarr[$mm]['PAID']*100 , 2);
				}
                        }

			// query to find monthly count of registered members with photo(s)
			$sql4="SELECT COUNT(*) as cnt,MONTH(ENTRY_DT) as mm FROM newjs.JPROFILE WHERE HAVEPHOTO='Y' AND ENTRY_DT BETWEEN '$start_date' AND '$end_date' GROUP BY mm";
			$res4 = mysql_query_decide($sql4,$db) or die("$sql4".mysql_error_js());
			while($row4=mysql_fetch_array($res4))
			{
				$mm=$row4['mm'];
				if($mm<=3)
				{
					$mm+=8;
				}
				else
				{
					$mm-=4;
				}
				$photo_countarr[$mm]['REGISTERED_PHOTO']=$row4['cnt'];
				if ($photo_countarr[$mm]['REGISTERED_PHOTO']!= 0 && $photo_countarr[$mm]['ACTIVATED']!= 0)
					//$photo_countarr[$mm]['REGISTERED_PHOTO_PER']=round($photo_countarr[$mm]['REGISTERED_PHOTO']/$photo_countarr[$mm]['ACTIVATED']*100 , 2);
				$photo_countarr['REGISTERED_PHOTO_TOT']+=$row4['cnt'];
			}

			// query to show monthly count of new photos added to the site
			$sql5="SELECT SUM(NEW_COUNT) as cnt,MONTH(DATE) as mm FROM MIS.NEW_PHOTOS_COUNT  WHERE DATE BETWEEN '$start_date' AND '$end_date' GROUP BY mm";
                        $res5 = mysql_query_decide($sql5,$db) or die("$sql5".mysql_error_js());
                        while($row5=mysql_fetch_array($res5))
                        {
                                $mm=$row5['mm'];
                                if($mm<=3)
                                {
                                        $mm+=8;
                                }
                                else
                                {
                                        $mm-=4;
                                }
                                $photo_countarr[$mm]['NEW_PHOTO']=$row5['cnt'];
                                $photo_countarr['NEW_PHOTO_TOT']+=$row5['cnt'];
                        }
		}
		elseif($type=='D')
		{
			$dflag=1;
			$start_date=$dyear."-".$dmonth."-01 00:00:00";
			$end_date=$dyear."-".$dmonth."-31 23:59:59";

			for($i=0;$i<31;$i++)
			{
				$ddarr[$i]=$i+1;
			}

			// query to get monthly count of people who have registered on JS
                        //$sql1 = "SELECT COUNT(*) as cnt , DAYOFMONTH(ENTRY_DT) as dd FROM newjs.JPROFILE WHERE ENTRY_DT BETWEEN '$start_date' AND '$end_date' GROUP BY dd";
                        $sql1 = "SELECT SUM(COUNT) as cnt , DAYOFMONTH(ENTRY_DT) as dd FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$start_date' AND '$end_date' AND ENTRY_MODIFY='E' GROUP BY dd";
                        $res1 = mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js());
                        if($row1=mysql_fetch_array($res1))                         
			{
				do
                                {
                                        $dd=$row1['dd']-1;
					$photo_countarr[$dd]['REGISTERED'] = $row1['cnt'];
                                	$photo_countarr['REGISTERED_TOT']+= $row1['cnt'];
                                }while($row1=mysql_fetch_array($res1));
                        }

			//$sql2 = "SELECT COUNT(*) as cnt , DAYOFMONTH(ENTRY_DT) as dd FROM newjs.JPROFILE WHERE ENTRY_DT BETWEEN '$start_date' AND '$end_date' AND ACTIVATED = 'Y' GROUP BY dd";
			$sql2 = "SELECT SUM(COUNT) as cnt , DAYOFMONTH(ENTRY_DT) as dd FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$start_date' AND '$end_date' AND ACTIVATED = 'Y' AND ENTRY_MODIFY='E' GROUP BY dd";
                        $res2 = mysql_query_decide($sql2,$db) or die("$sql2".mysql_error_js());
			if($row2=mysql_fetch_array($res2))
			{
                                do
                                {
                                        $dd=$row2['dd']-1;
                                        $photo_countarr[$dd]['ACTIVATED'] = $row2['cnt'];
                                        $photo_countarr['ACTIVATED_TOT']+= $row2['cnt'];
                                }while($row2=mysql_fetch_array($res2));
                        }
			
			$sql3 = "SELECT COUNT( * ) AS cnt, HAVEPHOTO, DAYOFMONTH( j.ENTRY_DT ) AS dd FROM billing.PURCHASES p , newjs.JPROFILE j WHERE p.PROFILEID = j.PROFILEID AND j.ENTRY_DT BETWEEN '$start_date' AND '$end_date' GROUP BY dd, j.HAVEPHOTO";
                        $res3 = mysql_query_decide($sql3,$db) or die("$sql3".mysql_error_js());
			if($row3=mysql_fetch_array($res3))
                        {
                                do
                                {
                                        $dd=$row3['dd']-1;
					if ($row3['HAVEPHOTO'] == 'Y')
					{
                                        	$photo_countarr[$dd]['PAID_PHOTO'] = $row3['cnt'];
						$photo_countarr['PAID_PHOTO_TOT']+= $row3['cnt'];
					}

					$photo_countarr[$dd]['PAID']+= $row3['cnt'];
					$photo_countarr['PAID_TOT']+= $row3['cnt'];
					if ($photo_countarr[$dd]['PAID']!= 0 && $photo_countarr[$dd]['PAID_PHOTO']!= 0)
					{
						//$photo_countarr[$dd]['PAID_PHOTO_PER'] = round($photo_countarr[$dd]['PAID_PHOTO']/$photo_countarr[$dd]['PAID']*100,2);
					}
                                }while($row3=mysql_fetch_array($res3));
                        }

			$sql4="SELECT COUNT(*) as cnt, DAYOFMONTH(ENTRY_DT) as dd FROM newjs.JPROFILE WHERE HAVEPHOTO='Y' AND ENTRY_DT BETWEEN '$start_date' AND '$end_date' GROUP BY dd";
                        $res4 = mysql_query_decide($sql4,$db) or die("$sql4".mysql_error_js());
                       	if($row4=mysql_fetch_array($res4))
                        {
				do
                                {
					$dd=$row4['dd']-1;

					$photo_countarr[$dd]['REGISTERED_PHOTO']=$row4['cnt'];
                                	$photo_countarr['REGISTERED_PHOTO_TOT']+=$row4['cnt'];
				}while($row4=mysql_fetch_array($res4));
			}

			// query to show daily count of new photos added to the site
                        $sql5="SELECT NEW_COUNT as cnt, DAYOFMONTH(DATE) as dd FROM MIS.NEW_PHOTOS_COUNT  WHERE DATE BETWEEN '$start_date' AND '$end_date' GROUP BY dd";
                        $res5 = mysql_query_decide($sql5,$db) or die("$sql5".mysql_error_js());
                        if($row5=mysql_fetch_array($res5))
                        {
				do
				{
					$dd=$row5['dd']-1;

					$photo_countarr[$dd]['NEW_PHOTO']=$row5['cnt'];
					$photo_countarr['NEW_PHOTO_TOT']+=$row5['cnt'];
				}while($row5=mysql_fetch_array($res5));
			}
		}
		if($type=="D")
                {
                        $num=count($ddarr);
                }
                elseif($type=="M")
                {
                        $num=count($mmarr);
                }
		for($j=0;$j<$num;$j++)
		{
			if($photo_countarr[$j]['PAID'])
			{
				$photo_countarr[$j]["PAID_PHOTO_PER"]=($photo_countarr[$j]["PAID_PHOTO"]/$photo_countarr[$j]["PAID"]) * 100;
                                $photo_countarr[$j]["PAID_PHOTO_PER"]=round($photo_countarr[$j]["PAID_PHOTO_PER"],2);
			}
			if($photo_countarr[$j]['ACTIVATED'])
			{
				$photo_countarr[$j]["REGISTERED_PHOTO_PER"]=($photo_countarr[$j]["REGISTERED_PHOTO"]/$photo_countarr[$j]["ACTIVATED"]) * 100;
                                $photo_countarr[$j]["REGISTERED_PHOTO_PER"]=round($photo_countarr[$j]["REGISTERED_PHOTO_PER"],2);
			}
		}

		if($photo_countarr["PAID_TOT"])
			$photo_countarr["PAID_PHOTO_TOT_PER"]=round($photo_countarr["PAID_PHOTO_TOT"]/$photo_countarr["PAID_TOT"]*100 , 2);

		if($photo_countarr["ACTIVATED_TOT"])
			$photo_countarr["REGISTERED_PHOTO_TOT_PER"]=round($photo_countarr["REGISTERED_PHOTO_TOT"]/$photo_countarr["ACTIVATED_TOT"]*100,2);
		
		
		$smarty->assign("photo_countarr",$photo_countarr);
		$smarty->assign("cnt",$cnt);
		$smarty->assign("tot",$tot);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("flag",$flag);
                $smarty->assign("mflag",$mflag);
                $smarty->assign("dflag",$dflag);
		$smarty->assign("myear",$myear);
		$smarty->assign("myearp1",$myearp1);
		$smarty->assign("dyear",$dyear);
		$smarty->assign("dmonth",$dmonth);

                $smarty->display("photomis.htm");
	}
	else
	{
		for($i=0;$i<12;$i++)
                {
                        $mmarr[$i]=$i+1;
                }
                for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}

		$smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
		$smarty->assign("CHECKSUM",$checksum);
		$smarty->display("photomis.htm");
	}
}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}
?>
