<?php 
/**
*       Filename        :       mainmenu.php
*       Description     :
*       Created by      :
*       Changed by      :
*       Changed on      :
        Changes         :       New Service added called Eclassified , changes done due to it.
**/
	
	//to zip the file before sending it
        $zipIt = 0;
        if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
                $zipIt = 1;
        if($zipIt)
                ob_start("ob_gzhandler");
        //end of it

	include("connect.inc");
	include("functions.inc");
        include("sms_service.inc");
	require_once("display_result.inc");
	include_once("ntimes_function.php");
	// connect to database
	$db=connect_db();

	

	$data=authenticated($checksum);
	$PAGELEN=20;
        if(!$j)                                                                                          
		$j=0;
	if($data )
	{

		/**************************Added By Shakti for link tracking**********************/
		link_track("ip_log");
		/*********************************************************************************/
		$profileid=$data['PROFILEID'];
		if(!$profileid)
			timedOut();
		$sql="select COUNTRY_RES from newjs.JPROFILE where COUNTRY_RES=51 and  activatedKey=1 and PROFILEID='$profileid'";
		$res=mysql_query_decide($sql);
		if($row=mysql_fetch_array($res))
		{
			$country=1;
			$smarty->assign("TIME_FORMAT"," in Indian Standard Time");
		}
		else
			$smarty->assign("TIME_FORMAT"," in Eastern Standard/Daylight Time");
		$mysql=new Mysql;
		$myDbName=getProfileDatabaseConnectionName($profileid);
		$myDb=$mysql->connect("$myDbName");
		$sql="select SQL_CALC_FOUND_ROWS IPADDR,`TIME` from newjs.LOG_LOGIN_HISTORY where PROFILEID=$profileid order by `TIME` DESC limit $j,$PAGELEN";
		$res=$mysql->executeQuery($sql,$myDb);
		$i=0;
		while($row=$mysql->fetchArray($res))
		{
			$IP_LOGIN[]=$i;
			$IP[]=$row['IPADDR'];
			$TIME[]=$row['TIME'];
		}
		if(is_array($TIME))
		{
			if($country)
			{
				for($i=0;$i<count($TIME);$i++)
				{
					$time=$TIME[$i];
					$sql="SELECT CONVERT_TZ('$time','SYSTEM','right/Asia/Calcutta')";
					$res=mysql_query_decide($sql);
					if($row=mysql_fetch_array($res))
						$TIME[$i]=$row[0];
				
				}
			}
			for($i=0;$i<count($TIME);$i++)
			{
				$row['TIME']=$TIME[$i];
				$date_time=explode(" ",$row['TIME']);
			
				$time=explode("-",$date_time[0]);

				$day=$time[2];
				$month=$time[1];
				$year=$time[0];
				$timed=explode(":",$date_time[1]);
				$hour=$timed[0];
				$min=$timed[1];
			
				$row['TIME']=my_format_date($day,$month,$year,'',$hour,$min);	
				$DATE[]=$row['TIME'];
			}
		}
		$sql="SELECT found_rows( )";
		$res=$mysql->executeQuery($sql,$myDb);
		if($row=$mysql->fetchArray($res))
		{
			$total_rec=$row[0];
		}
		$curcount=$j;

		if( $curcount )
                        $cPage = ($curcount/$PAGELEN) + 1;
                else
                        $cPage = 1;
		pagelink(20,$total_rec,$cPage,20,"$checksum","ip_log.php",'','','','','','','','','',"","",'');

		
		$smarty->assign("IP_LOGIN",$IP_LOGIN);
		$smarty->assign("IP",$IP);
		$smarty->assign("TYPE",$type);
		$smarty->assign("DATE",$DATE);
		$smarty->display("ip_log.htm");
		
	}
	else
		timedOut();

	
