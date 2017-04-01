<?php
include_once("connect.inc");

//include("../profile/connect.inc");	//done by Shakti for JSIndicator 24 Nov, 2005

$db=connect_misdb();
if(authenticated($cid) || $JSIndicator)
{
	 if($outside)
        {
                $submitResult='Y';
		$resultPage = "detailView";
        }



	if($submitResult)
	{
		$smarty->assign("resultPage", $resultPage);
		
		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}

		if(!$year && !$month)
		{
			$today=date("Y-m-d");
			list($year,$month,$d)=explode("-",$today);
		}
		$st_date=$year."-".$month."-01";
		$end_date=$year."-".$month."-31";

		if($resultPage == "detailView")
		{
			$sql="SELECT VIEWER,VIEWED,DATE,SOURCE FROM jsadmin.VIEW_CONTACTS_LOG WHERE DATE BETWEEN '$st_date' AND '$end_date' AND SOURCE='".CONTACT_ELEMENTS::CALL_DIRECTLY_TRACKING."'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			$dayDetail = array();
			if(mysql_num_rows($res))
			{
				while($row=mysql_fetch_array($res))	
				{
					$viewedDate = $row["DATE"];
					$viewer = $row["VIEWER"];
					$viewed = $row["VIEWED"];
					$viewedDay = round(substr($viewedDate, 8, 2));
					$viewedDayCount[$viewedDay]++;
					$uniqueViewerCount[$viewedDay][$viewer]++;
					$uniqueViewedCount[$viewedDay][$viewed]++;
					$uniqueViewer[$viewer]++;
					$uniqueViewed[$viewed]++;
					$totalCount["totalViewed"]++;
				}
				foreach($uniqueViewer as $key=>$val)
				{
					if($val<10)	
						$totalCount["tLt10"]++;
					if($val<25)
						$totalCount["tLt25"]++;
					if($val<50)
						$totalCount["tLt50"]++;
					if($val<100)
						$totalCount["tLt100"]++;
					if($val>100)
						$totalCount["tGt100"]++;
					$totalCount["tUniqueViewers"]++;
				}
                                foreach($uniqueViewed as $key=>$val)
                                {
                                        if($val<=2 && $val>=1)
                                                $totalCount["tLt1"]++;
                                        if($val<=5 && $val>=3)
                                                $totalCount["tLt2"]++;
                                        if($val<=10 && $val>=6)
                                                $totalCount["tLt3"]++;
                                        if($val<=20 && $val>=11)
                                                $totalCount["tLt4"]++;
                                        if($val>20)
                                                $totalCount["tGt1"]++;
                                        $totalCount["tUniqueViewed"]++;
                                }
				if($totalCount["totalViewed"])
				{
					$totalCount["tPercentUniqueViewers"] = round(($totalCount["tUniqueViewers"]/$totalCount["totalViewed"])*100);
					$totalCount["tPercentUniqueViewed"] = round(($totalCount["tUniqueViewed"]/$totalCount["totalViewed"])*100);
				}
				$smarty->assign("totalCount",$totalCount);
				foreach($ddarr as $key=>$val)
				{
					$dayDetail[$val]["totalCount"] = $viewedDayCount[$val];
					$dayDetail[$val]["uniqueViewers"] = count($uniqueViewerCount[$val]);
					$dayDetail[$val]["uniqueViewed"] = count($uniqueViewedCount[$val]);
					if($dayDetail[$val]["totalCount"])
					{
						$dayDetail[$val]["percentUniqueViewers"] = round(($dayDetail[$val]["uniqueViewers"]/$dayDetail[$val]["totalCount"])*100);
						$dayDetail[$val]["percentUniqueViewed"] = round(($dayDetail[$val]["uniqueViewed"]/$dayDetail[$val]["totalCount"])*100);
					}
					if(array_key_exists($val, $uniqueViewerCount))
					{
						$dayDetail[$val]["lt10"] = 0;
						$dayDetail[$val]["lt25"] = 0;
						$dayDetail[$val]["lt50"] = 0;
						$dayDetail[$val]["lt100"] = 0;
						$dayDetail[$val]["gt100"] = 0;
						$uniqueViewerCountPerDay = $uniqueViewerCount[$val];
						foreach($uniqueViewerCountPerDay as $k=>$v)
						{
							if($v<10)
								$dayDetail[$val]["lt10"]++;
							if($v<25)
								$dayDetail[$val]["lt25"]++;
							if($v<50)
								$dayDetail[$val]["lt50"]++;
							if($v<100)
								$dayDetail[$val]["lt100"]++;
							if($v>100)
								$dayDetail[$val]["gt100"]++;
						}
					}
                                        if(array_key_exists($val, $uniqueViewedCount))
                                        {
                                                $uniqueViewedCountPerDay = $uniqueViewedCount[$val];
                                                $dayDetail[$val]["lt1"] = 0;
                                                $dayDetail[$val]["lt2"] = 0;
                                                $dayDetail[$val]["lt3"] = 0;
                                                $dayDetail[$val]["lt4"] = 0;
                                                $dayDetail[$val]["gt1"] = 0;
                                                foreach($uniqueViewedCountPerDay as $k=>$v)
                                                {
                                                        if($v<=2 && $v>=1)
                                                                $dayDetail[$val]["lt1"]++;
                                                        if($v<=5 && $v>=3)
                                                                $dayDetail[$val]["lt2"]++;
                                                        if($v<=10 && $v>=6)
                                                                $dayDetail[$val]["lt3"]++;
                                                        if($v<=20 && $v>=11)
                                                                $dayDetail[$val]["lt4"]++;
                                                        if($v>20)
                                                                $dayDetail[$val]["gt1"]++;
                                                }
                                        }
				}
			}
			$smarty->assign("dayDetail",$dayDetail);
		}
		elseif($resultPage == "mostViewed")
		{
			$sql = "SELECT COUNT(*) cnt,VIEWED FROM jsadmin.VIEW_CONTACTS_LOG WHERE DATE BETWEEN '$st_date' AND '$end_date' AND SOURCE='".CONTACT_ELEMENTS::CALL_DIRECTLY_TRACKING."' GROUP BY VIEWED ORDER BY cnt DESC LIMIT 50";
                        $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
                        $dayDetail = array();
                        if(mysql_num_rows($res))
                        {
                                while($row=mysql_fetch_array($res))
                                {       
                                        $viewer[$row["VIEWED"]]["cnt"] = $row["cnt"];
                                        $profileIdArr[] = "'".$row["VIEWED"]."'";
                                }       
                                $profileIds = implode(",", $profileIdArr);
                                $sql1 = "SELECT PROFILEID, USERNAME, GENDER, AGE, HAVEPHOTO FROM newjs.JPROFILE WHERE PROFILEID IN ($profileIds)";
                                $res1=mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js());
                                $i = 0;
                                while($row1=mysql_fetch_array($res1))
                                {
                                        $viewedDetail[$i]["PROFILEID"] = $row1["PROFILEID"];
                                        $viewedDetail[$i]["USERNAME"] = $row1["USERNAME"];
                                        $viewedDetail[$i]["AGE"] = $row1["AGE"];
                                        $viewedDetail[$i]["HAVEPHOTO"] = $row1["HAVEPHOTO"];
                                        $viewedDetail[$i]["CNT"] = $viewer[$row1["PROFILEID"]]["cnt"];
                                        $viewedDetail[$i]["GENDER"] = $row1["GENDER"]=="M"?"Male":"Female";
                                        $i++;
                                }
                                $viewedDetail = orderBy($viewedDetail, "CNT");
                                $smarty->assign("viewedDetail",$viewedDetail);
			}			
		}
                elseif($resultPage == "mostViewer")
                {
                        $sql = "SELECT COUNT(*) cnt,VIEWER FROM jsadmin.VIEW_CONTACTS_LOG WHERE DATE BETWEEN '$st_date' AND '$end_date' AND SOURCE='".CONTACT_ELEMENTS::CALL_DIRECTLY_TRACKING."' GROUP BY VIEWER ORDER BY cnt DESC LIMIT 50";
                        $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
                        $dayDetail = array();
                        if(mysql_num_rows($res))
                        {
                                while($row=mysql_fetch_array($res))
                                {
					$viewer[$row["VIEWER"]]["cnt"] = $row["cnt"];
					$profileIdArr[] = "'".$row["VIEWER"]."'";
                                }
				$profileIds = implode(",", $profileIdArr);
				$sql1 = "SELECT PROFILEID, USERNAME, AGE, GENDER, HAVEPHOTO FROM newjs.JPROFILE WHERE PROFILEID IN ($profileIds)";
				$res1=mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js());
				$i = 0;
				while($row1=mysql_fetch_array($res1))
				{
					$viewerDetail[$i]["PROFILEID"] = $row1["PROFILEID"];
					$viewerDetail[$i]["USERNAME"] = $row1["USERNAME"];
					$viewerDetail[$i]["AGE"] = $row1["AGE"];
					$viewerDetail[$i]["HAVEPHOTO"] = $row1["HAVEPHOTO"];
					$viewerDetail[$i]["CNT"] = $viewer[$row1["PROFILEID"]]["cnt"];
                                        $viewerDetail[$i]["GENDER"] = $row1["GENDER"]=="M"?"Male":"Female";
					$i++;
				}
				$viewerDetail = orderBy($viewerDetail, "CNT");
				$smarty->assign("viewerDetail",$viewerDetail);
                        }
                }
                $smarty->assign("ddarr",$ddarr);
                $smarty->assign("year",$year);
                $smarty->assign("month",$month);
                $smarty->assign("cid",$cid);
	}
	elseif($selectMonth)
	{
		for($i=0;$i<12;$i++)
                {
                        $mmarr[$i]=$i+1;
                }

                for($i=0;$i<10;$i++)
                {
                        $yyarr[$i]=$i+2009;
                }

		$smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
		$smarty->assign("selectMonth",$selectMonth);
                list($curmonth,$curyear) = explode("-",date('m-Y'));
                $smarty->assign("curmonth",$curmonth);
                $smarty->assign("curyear",$curyear);
	}
	else
		$smarty->assign("landingPage", 1);
	$smarty->assign("cid",$cid);
	$smarty->display("directCallMis.htm");
}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}

function orderBy($data, $field, $sortingOrder="DESC")
{
	$code = "return strnatcmp(\$a['$field'], \$b['$field']);";
	if($sortingOrder=='DESC')
		usort($data, create_function('$b,$a', $code));
	else
		usort($data, create_function('$a,$b', $code));
	return $data;
}

?>
