<?php
include_once("connect.inc");

$db=connect_misdb();
mysql_query("set session wait_timeout=1000",$db);

if(authenticated($cid) || $JSIndicator)
{
	 if($outside)
        {
                $CMDGo='Y';
		if(!$today)
			$today=date("Y-m-d");
                list($year,$month,$d)=explode("-",$today);
        }

	if($CMDGo)
	{
		$smarty->assign("flag",1);
		$mmarr=array('Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar');
		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}
		//if($month<10)
		//	$month="0".$month;

		$st_date=$year."-".$month."-01";
		$end_date=$year."-".$month."-31";

		$sql="SELECT COUNT,DAYOFMONTH(LAST_LOGIN_DT) as dd FROM MIS.DAY_LOGIN_COUNT WHERE LAST_LOGIN_DT BETWEEN '$st_date' AND '$end_date' GROUP BY dd";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		if($row=mysql_fetch_array($res))
		{
			do
			{
				$dd=$row['dd']-1;
				$cnt[$dd]=$row['COUNT'];
				$tot+=$row['COUNT'];
			}while($row=mysql_fetch_array($res));
		}

/*************************************************************************************************************************
			Added By	:	Shakti Srivastava
			Date		:	24 November, 2005
			Reason		:	This was needed for stopping further execution of this script whenever
					:	indicator_mis.php was used to obtain data
*************************************************************************************************************************/
		if($JSIndicator==1)
		{
			return;
		}
/**************************************End of Addition********************************************************************/

		// query to find count of people who have logged in more than 10 times
		include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
		include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
		include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");

		$mysql=new Mysql;
		//$db2 = connect_slave();
		//$db=connect_db();
		//$LOG_PRO=array();
		$i =0;
                $onetimelogin = 0;
                $twotimelogin = 0;
		for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
		{
		        $myDbName=getActiveServerName($activeServerId,"slave");
        		$myDb[$myDbName]=$mysql->connect("$myDbName","slave");

		        if(false)
			{
				$sql="SELECT SQL_CALC_FOUND_ROWS COUNT( * ) AS cnt FROM newjs.LOGIN_HISTORY WHERE LOGIN_DT BETWEEN '$st_date' AND '$end_date' GROUP BY PROFILEID HAVING cnt >10 LIMIT 1";
        		$res=$mysql->executeQuery($sql,$myDb[$myDbName]) or die(mysql_error_js($myDb[$myDbName]));
			$num=$mysql->numRows($res);
			$sql= "SELECT found_rows( )";
			$res=$mysql->executeQuery($sql,$myDb[$myDbName]);
	        	if($row=$mysql->fetchArray($res))
        		{
				if($num)
					$morethantenlogin+= $row[0];
                		//$LOG_PRO[$row['PROFILEID']]=$row['PROFILEID'];
		        }
			$sql = "SELECT COUNT( * ) AS cnt FROM newjs.LOGIN_HISTORY WHERE LOGIN_DT BETWEEN '$st_date' AND '$end_date' GROUP BY PROFILEID HAVING cnt <=10";
			$res=$mysql->executeQuery($sql,$myDb[$myDbName]) or die(mysql_error_js($myDb[$myDbName]));
			while($row=$mysql->fetchArray($res))
			{
				 if ($row['cnt'] == 1)
                                	$onetimelogin++;
                        	elseif ($row['cnt'] == 2)
                                	$twotimelogin++;
	                        elseif ($row['cnt'] == 3)
        	                        $threetimelogin++;
                	        elseif ($row['cnt'] == 4)
                        	        $fourtimelogin++;
	                        elseif ($row['cnt'] == 5)
        	                        $fivetimelogin++;
                	        elseif ($row['cnt'] > 5 && $row['cnt'] <= 10)
                        	        $morethanfivelogin++;
                        	$i++;
			}
			}

		}	


		// total unique login count
		$uniquecount = $i + $morethantenlogin;

//		$sql_autologin="SELECT COUNT(*) AS cnt ,DAYOFMONTH(ENTRY_DT) as dd  FROM newjs.AUTOLOGIN_LOGIN WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' GROUP BY dd";
		$sql_autologin="select AUTO_LOGIN as cnt,DAYOFMONTH(LAST_LOGIN_DT) as dd FROM MIS.DAY_LOGIN_COUNT where LAST_LOGIN_DT BETWEEN '$st_date' AND '$end_date'";
		$res=mysql_query_decide($sql_autologin,$db) or die("$sql_autologin".mysql_error_js($db));
		if($row=mysql_fetch_array($res))
                {
                        do
                        {
                                $dd=$row['dd']-1;
                                $cntl[$dd]=$row['cnt'];
                                $totl+=$row['cnt'];
                        }while($row=mysql_fetch_array($res));
                }

		$sql_autologincontact="SELECT COUNT(*) AS cnt ,DAYOFMONTH(ENTRY_DT) as dd  FROM newjs.AUTOLOGIN_CONTACTS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' GROUP BY dd";
                $res=mysql_query_decide($sql_autologincontact,$db) or die("$sql_autologincontact".mysql_error_js($db));
                if($row=mysql_fetch_array($res))
                {
                        do
                        {
                                $dd=$row['dd']-1;
                                $cntc[$dd]=$row['cnt'];
                                $totc+=$row['cnt'];
                        }while($row=mysql_fetch_array($res));
                }

		$smarty->assign("onetimelogin",$onetimelogin);
		$smarty->assign("twotimelogin",$twotimelogin);
		$smarty->assign("threetimelogin",$threetimelogin);
		$smarty->assign("fourtimelogin",$fourtimelogin);
		$smarty->assign("morethanfivelogin",$morethanfivelogin);
		$smarty->assign("fivetimelogin",$fivetimelogin);
		$smarty->assign("morethantenlogin",$morethantenlogin);
		$smarty->assign("uniquecount",$uniquecount);
		$smarty->assign("cnt",$cnt);
		$smarty->assign("cntl",$cntl);
		$smarty->assign("cntc",$cntc);
		$smarty->assign("tot",$tot);
		$smarty->assign("totl",$totl);
		$smarty->assign("totc",$totc);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("year",$year);
		$smarty->assign("month",$month);
		$smarty->assign("cid",$cid);
                $smarty->display("daily_login_count.htm");
		unset($onetimelogin);
		unset($twotimelogin);
		unset($threetimelogin);
		unset($fivetimelogin);
		unset($fourtimelogin);
		unset($uniquecount);
		unset($morethanfivelogin);
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
		$smarty->assign("cid",$cid);
		$smarty->display("daily_login_count.htm");
	}
}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}
?>
