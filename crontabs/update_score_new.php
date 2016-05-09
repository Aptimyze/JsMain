<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


/***********************************************************************************************************************
* FILE NAME     : update_score_new.php
* DESCRIPTION   : call 3 function which are for specific pupose as below
		  a)call_update_score_when_paid-->Update score from 49 as when user becomes a paid member

		  b)call_update_always_free------>Update scores to 49 if he is a free user and  is
		    1)contacted by 5 or more paid profiles.(If Male User)
				 OR
		    2)contacted by 10 or more paid profiles.(If Female User)

		  c)update_30days---------------->If user has not logged in last 30 Days then update its score to
	            1)(600->48)(450->47)(400->46)(300->45)(250->44)(100->43)(50->42).
		    2)if score is 49 then calculate it original score and then update score on basis of c)1) 

* INCLUDES      : connect.inc
* CREATION DATE : 13 July 2006
* CREATED BY    : Lavesh Rawat
************************************************************************************************************************/

ini_set("max_execution_time","0");
//include_once("comfunc1.inc");  //Uncoment for 244/220
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once("connect.inc");
$db=connect_db();
$db2 = connect_737_lan();
//$db2=connect_db();

$today=date("Y-m-d");
$ts = time();
$ts-=31*24*60*60;
$start_dt=date("Y-m-d",$ts);

include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");

$mysql=new Mysql;
//$db2 = connect_slave();
$LOG_PRO=array();

for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
        $myDbName=getActiveServerName($activeServerId);
        $myDb[$myDbName]=$mysql->connect("$myDbName","slave");

}

//update_30days();
call_update_score_when_paid();
call_update_always_free();


//call function update_30days_old_records which update(degrade) score of user who has not been log in in last 30 days.
//Highly log-in user should have more preference as it will increase our response.
function update_30days()
{
        update_30days_old_records("SEARCH_FEMALE");
        //update_30days_old_records("SEARCH_MALE");
        //update_30days_old_records("SEARCH_FEMALE_FULL1");
        //update_30days_old_records("SEARCH_MALE_FULL1");
}

//call function update_score_when_paid which Update score from 49(upgrade) as when user becomes a paid member.
//49 score is for free user and if they paid,they need to switch back to its original score.
function call_update_score_when_paid()
{
	update_score_when_paid("SEARCH_FEMALE");
	update_score_when_paid("SEARCH_MALE");
	update_score_when_paid("SEARCH_FEMALE_FULL1");
	update_score_when_paid("SEARCH_MALE_FULL1");
}

//call function always_free which Update(degrade) score to 49 if he is a free user and is getting good response.
//By degrading these free profiles ,other good score and less responsed profile can be displayed in earlier page.
function call_update_always_free()
{
	always_free("SEARCH_FEMALE");
	always_free("SEARCH_MALE");
	always_free_full("SEARCH_FEMALE_FULL1");
	always_free_full("SEARCH_MALE_FULL1");

}


function always_free($table)
{
	global $start_dt,$db,$db2;

	if($table=="SEARCH_FEMALE")
	{
		$gender='F';
		$table2="SEARCH_FEMALE_FULL1";
		$max_acc=15;
	}
	else
	{
		$gender='M';
		$table2="SEARCH_MALE_FULL1";
		$max_acc=10;
	}

	@mysql_ping($db2);

	//Score of logical 1st grid(heavy preference records) .
	$sql="SELECT PROFILEID FROM newjs.$table WHERE TOTAL_POINTS >49 ";
	$res=mysql_query($sql,$db2) or die("$sql".mysql_error($db2)); 

        while($row=mysql_fetch_array($res))
        {
                $pid=$row['PROFILEID'];
		//if($q++>0)
			//$pid=31;
		//echo $pid;echo '--';

		$sql1="SELECT count(*) as cnt FROM billing.PURCHASES WHERE PROFILEID='$pid'";
		$res1=mysql_query($sql1,$db2) or die("$sql1".mysql_error($db2));

        	$row1=mysql_fetch_array($res1);
                                                                                                                             
	        $cnt=$row1['cnt'];

		//if($pid>1600)
                //	break;
                                                                                                                             
        	if($cnt==0)
		//Profiles not paid  till now.
		{

			//List user which are A)paid members accepted by free members B)free members contacted by paid members.
			//Sharding on CONTACTS done by Neha Verma
				
			$contactResult=getResultSet("SENDER","","",$pid,"","'A','I'","","","","","","","slave");
		        if(is_array($contactResult))
		        {
		                foreach($contactResult as $key=>$value)
		                {
		                     	$sender=$contactResult[$key]["SENDER"];
					$sqlres="SELECT SUBSCRIPTION FROM newjs.JPROFILE WHERE PROFILEID='$sender'";
					$myres=mysql_query($sqlres,$db2) or die("$sqlres".mysql_error($db2)); 
					$myrow=mysql_fetch_array($myres);
					
					//Record contact of free member having profileis $i by a paid member.
					if($myrow['SUBSCRIPTION']<>'')
						$paid_acc+=1;
				}
			}
			unset($contactResult);

			if($paid_acc < $max_acc)
			{
				//List free user whose initial contact is accepted by paid members.
		
				$contactResult=getResultSet("RECEIVER",$pid,"","","","'A'","","","","","","","slave");
                	        if(is_array($contactResult))
                        	{
                                	foreach($contactResult as $key=>$value)
                                	{
                                        	$rec=$contactResult[$key]["RECEIVER"];
						$sqlres="SELECT SUBSCRIPTION FROM newjs.JPROFILE WHERE PROFILEID='$rec'";
						$myres=mysql_query($sqlres,$db2) or die("$sqlres".mysql_error($db2)); 
						$myrow=mysql_fetch_array($myres);
						
						if($myrow['SUBSCRIPTION']<>'')
							$paid_acc+=1;
					}
				}
                        unset($contactResult);
			}
			if($paid_acc >= $max_acc)
                        {
				$limit_cross=1;			
			}
			unset($paid_acc);
			if($limit_cross)
			{
				$today=date("Y-m-d");
				
				$sql1="SELECT HAVEPHOTO,PHOTODATE,ENTRY_DT FROM newjs.JPROFILE WHERE PROFILEID='$pid'";
				$res1=mysql_query($sql1,$db2) or die("$sql1".mysql_error($db2)); 
				$row1=mysql_fetch_array($res1);

				$entry_dt=$row1["ENTRY_DT"];
				$photo_dt=$row1["PHOTODATE"];

				//freshness_points is how new the record is .(It is latest of photo_dt and entry_dt )
				if($row1["HAVEPHOTO"]=='Y')
					$diff=DayDiff(substr($photo_dt,0,10),$today);
				else
					$diff=DayDiff(substr($entry_dt,0,10),$today);

				$freshness_points=0;

				if($diff<16)
					$freshness_points=300;
				elseif($diff>15 && $diff<46)
					$freshness_points=150;
				else
					$freshness_points=100;				
			
				//total score = 49
				$score_points=49-$freshness_points;
				
		                @mysql_ping($db);		

				$sql1="UPDATE newjs.$table set SCORE_POINTS='$score_points',FRESHNESS_POINTS='$freshness_points' ,TOTAL_POINTS='49' WHERE PROFILEID='$pid'";
				$res1=mysql_query($sql1,$db) or die("$sql1".mysql_error($db));
				//echo "<br>";

				$sql1="UPDATE newjs.$table2 set SCORE_POINTS='$score_points',FRESHNESS_POINTS='$freshness_points' ,TOTAL_POINTS='49' WHERE PROFILEID='$pid'";
                                $res1=mysql_query($sql1,$db2) or die("$sql1".mysql_error($db2));
				//echo "<br>";

				unset($limit_cross);		
			}					
		}
	}
}


function always_free_full($table)
{
	global $start_dt,$db,$db2;
                                                                                                                             
        @mysql_ping($db2);

	if($table=="SEARCH_FEMALE_FULL1")
        {
                $gender='F';
                $table2="SEARCH_FEMALE";
                $max_acc=15;
        }
        else
        {
                $gender='M';
                $table2="SEARCH_MALE";
                $max_acc=10;
        }
                                                                                                                             
        $sql="SELECT PROFILEID FROM newjs.$table WHERE TOTAL_POINTS >49";
        $res=mysql_query($sql,$db2) or die("$sql".mysql_error($db2));
                                                                                                                             
        while($row=mysql_fetch_array($res))
	{
		$pid=$row['PROFILEID'];
		$sql_1="SELECT count(*) as cnt FROM newjs.$table2 WHERE PROFILEID='$pid'";
		$res_1=mysql_query($sql_1,$db2) or die("$sql_1".mysql_error($db2));
		$row_1=mysql_fetch_array($res_1);
		if($row_1['cnt']==0)
		{
			$sql1="SELECT count(*) as cnt FROM billing.PURCHASES WHERE PROFILEID='$pid'";
			$res1=mysql_query($sql1,$db2) or die("$sql1".mysql_error($db2));

			$row1=mysql_fetch_array($res1);

			$cnt=$row1['cnt'];

			//if($pid>1600)
			//      break;

			if($cnt==0)
			//Profiles not paid  till now.
			{	
				//List user which are A)paid members accepted by free members B)free members contacted by paid members.
				//Sharding on CONTACTS done by Neha Verma
				$contactResult=getResultSet("SENDER","","",$pid,"","'A','I'","","","","","","","slave");
                                if(is_array($contactResult))
                                {
                                        foreach($contactResult as $key=>$value)
                                        {
                                                $sender=$contactResult[$key]["SENDER"];
						$sqlres="SELECT SUBSCRIPTION FROM newjs.JPROFILE WHERE PROFILEID='$sender'";
						$myres=mysql_query($sqlres,$db2) or die("$sqlres".mysql_error($db2));
						$myrow=mysql_fetch_array($myres);
					//Record contact of free member having profileis $i by a paid member.
						if($myrow['SUBSCRIPTION']<>'')
							$paid_acc+=1;
					}
				}
				unset($contactResult);	
				if($paid_acc < $max_acc)
				{
					//List free user whose initial contact is accepted by paid members.
					$contactResult=getResultSet("RECEIVER",$pid,"","","","'A'","","","","","","","slave");
	                                if(is_array($contactResult))
	                                {
	                                        foreach($contactResult as $key=>$value)
	                                        {
	                                                $rec=$contactResult[$key]["RECEIVER"];
							$sqlres="SELECT SUBSCRIPTION FROM newjs.JPROFILE WHERE PROFILEID='$rec'";
							$myres=mysql_query($sqlres,$db2) or die("$sqlres".mysql_error($db2));

							$myrow=mysql_fetch_array($myres);

							if($myrow['SUBSCRIPTION']<>'')
								$paid_acc+=1;
						}
					}
					unset($contactResult);
				}

				if($paid_acc >= $max_acc)
				{
					$limit_cross=1;
				}

				unset($paid_acc);

				if($limit_cross)
				{
					$today=date("Y-m-d");

					$sql1="SELECT HAVEPHOTO,PHOTODATE,ENTRY_DT FROM newjs.JPROFILE WHERE PROFILEID='$pid'";
					$res1=mysql_query($sql1,$db2) or die("$sql1".mysql_error($db2));

					$row1=mysql_fetch_array($res1);

					$entry_dt=$row1["ENTRY_DT"];
					$photo_dt=$row1["PHOTODATE"];

					//freshness_points is how new the record is .(It is latest of photo_dt and entry_dt )
					if($row1["HAVEPHOTO"]=='Y')
						$diff=DayDiff(substr($photo_dt,0,10),$today);
					else
						$diff=DayDiff(substr($entry_dt,0,10),$today);

					$freshness_points=0;

					if($diff<16)
						$freshness_points=300;
					elseif($diff>15 && $diff<46)
						$freshness_points=150;
					else
						$freshness_points=100;

					//total score = 49
					$score_points=49-$freshness_points;

					$sql1="UPDATE newjs.$table set SCORE_POINTS='$score_points',FRESHNESS_POINTS='$freshness_points' ,TOTAL_POINTS='49' WHERE PROFILEID='$pid'";
                         	        $res1=mysql_query($sql1,$db2) or die("$sql1".mysql_error($db2));
					//echo "<br>";
                                                                                                                             
                                	unset($limit_cross);
                        	}
			}
		}
	}
}

function update_30days_old_records($table)
{
	global $start_dt,$db,$db2,$myDb,$mysql;

	if($table=="SEARCH_FEMALE" || $table=="SEARCH_MALE")
                $db_flag=1;
        else
                $db_flag=0;
                                                                                                                             
        @mysql_ping($db2);

        $sql="SELECT PROFILEID,TOTAL_POINTS FROM newjs.$table WHERE LAST_LOGIN_DT < '$start_dt' AND TOTAL_POINTS >48";
        $res=mysql_query($sql,$db2) or logError($sql,$db2);
	
	while($row=mysql_fetch_array($res))
        {
                $profileid=$row['PROFILEID'];
                $total_points_swap=$row['TOTAL_POINTS'];
		
		//if($profileid>1000)
          	//break;		

		if($total_points_swap==49)
			$sql1 = "SELECT  ENTRY_DT, AGE , GENDER , YOURINFO , FAMILYINFO , SPOUSE , JOB_INFO , SIBLING_INFO  , FATHER_INFO , HAVEPHOTO , RELATION , LAST_LOGIN_DT  , CITY_RES, SOURCE , HAVEPHOTO , PHOTODATE FROM newjs.JPROFILE WHERE PROFILEID ='$profileid'";
		else
			$sql1="SELECT HAVEPHOTO,PHOTODATE,LAST_LOGIN_DT,ENTRY_DT FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";

		$res1=mysql_query($sql1,$db2) or logError($sql1,$db2);
		$row1=mysql_fetch_array($res1);

		//if($row1['LAST_LOGIN_DT']< $start_dt)
		//{		
			$today=date("Y-m-d");

			$entry_dt=$row1["ENTRY_DT"];
                        $photo_dt=$row1["PHOTODATE"];
		
                        if($row1["HAVEPHOTO"]=='Y')
                                $diff=DayDiff(substr($photo_dt,0,10),$today);
                        else
                                $diff=DayDiff(substr($entry_dt,0,10),$today);

			$freshness_points=0;
                                                                                                                             
                        if($diff<16)
                                $freshness_points=300;
                        elseif($diff>15 && $diff<46)
                                $freshness_points=150;
                        else
                                $freshness_points=100;

			if($total_points_swap==49)
                        {
                                /*$sql_rec = "SELECT SCORE FROM incentive.MAIN_ADMIN_POOL WHERE PROFILEID ='$profileid'";
                                $res_rec = mysql_query($sql_rec,$db2) or logError($sql_rec,$db2);

                                if($row_rec = mysql_fetch_array($res_rec))
					$score=$row_rec['SCORE'];
                                else 
				{*/                                        

					$source=$row1['SOURCE'];
					$myDbName=getProfileDatabaseConnectionName($pid);
					if(!$myDb[$myDbName])
						$myDb[$myDbName]=$mysql->connect("$myDbName","slave");

					// query to find the first date in an interval of 30 days when the user logged in
					$sql_login_cnt = "SELECT COUNT(*) AS CNT FROM newjs.LOGIN_HISTORY WHERE PROFILEID = '$profileid' AND LOGIN_DT >= '$start_dt'";
					$res_login_cnt = mysql_query($sql_login_cnt,$myDb[$myDbName]) or logError($sql_login_cnt,$myDb[$myDbName]);

					while($row_login_cnt = mysql_fetch_array($res_login_cnt))
					{
						$login_cnt = $row_login_cnt['CNT'];
					}

					// query to find the count of contacts initiated
					//Sharding On CONTACTS done by Neha Verma
					$contactResult=getResultSet("COUNT(*) AS CNT4",$profileid,"","","","","","TIME >= '$start_dt'","","","","","slave");
                                        $INITIATE_CNT=$contactResult[0]["CNT4"];
									
					unset($contactResult);
					// query to find the count of contacts accepted
					$contactResult=getResultSet("COUNT(*) AS CNT","","","$profileid","","'A'","","TIME >= '$start_dt'","","","","","slave");
                                        $ACCEPTANCE_MADE=$contactResult[0]["CNT"];

					$contact_cnt = $INITIATE_CNT + $ACCEPTANCE_MADE;

					$PROFILELENGTH = strlen($row1['YOURINFO']) + strlen($row1['FAMILYINFO']) + strlen($row1['SPOUSE']) + strlen($row1['FATHER_INFO']) + strlen($row1['SIBLING_INFO']) + strlen($row1['JOB_INFO']);
	
					$score = calc_user_score_search($row1['AGE'],$row1['GENDER'],$PROFILELENGTH,$row1['HAVEPHOTO'], $row1['RELATION'],$entry_dt,$login_cnt,$contact_cnt);
				
				//}
				
				if($score<=150)
					$newscore=-50;
				elseif($score<326)
					$newscore=150;
				else
					$newscore=300;
				$total_points_swap=$freshness_points+$newscore ;
                        }

			if($total_points_swap==450 || $total_points_swap==600)
			{
				$score_points=48-$freshness_points;
				$total_points=48;
			}
			elseif($total_points_swap==400)
			{
				$score_points=47-$freshness_points;
				$total_points=47;
			}
			elseif($total_points_swap==300)
			{
				$score_points=46-$freshness_points;
				$total_points=46;
			}
			elseif($total_points_swap==250)
			{
				$score_points=45-$freshness_points;
				$total_points=45;
			}
			elseif($total_points_swap==100)
			{
				$score_points=44-$freshness_points;
				$total_points=44;
			}
			elseif($total_points_swap==50)
			{
				$score_points=43-$freshness_points;	
				$total_points=43;
			}

			//echo '(';
			//echo $kk++;
			//echo ')------>';

			if($db_flag==1)
				@mysql_ping($db);

			 $sql1="UPDATE newjs.$table set SCORE_POINTS='$score_points',FRESHNESS_POINTS='$freshness_points' ,TOTAL_POINTS='$total_points' WHERE PROFILEID='$profileid'"; 
			if($db_flag==1)
				$res1=mysql_query($sql1,$db) or logError($sql1,$db);
			else
				$res1=mysql_query($sql1,$db2) or logError($sql1,$db2);
	//}
	}
}


function update_score_when_paid($table)
{
	global $start_dt,$db,$db2,$myDb,$mysql;
	//echo "****************************************************************************************************";

	if($table=="SEARCH_FEMALE" || $table=="SEARCH_MALE")
                $db_flag=1;
        else
                $db_flag=0;
                                                                                                                             
        @mysql_ping($db2);

	//49 score is for free user with heavy response.
	$sql="SELECT PROFILEID FROM newjs.$table WHERE TOTAL_POINTS='49'";
        $res=mysql_query($sql,$db2) or logError($sql,$db2);

	while($row=mysql_fetch_array($res))
	{
		$pid=$row['PROFILEID'];

		$sql1="SELECT SUBSCRIPTION,PHOTODATE,ENTRY_DT,HAVEPHOTO FROM newjs.JPROFILE WHERE PROFILEID='$pid'";
		$res1=mysql_query($sql1,$db2) or logError($sql1,$db2);
		$row1=mysql_fetch_array($res1);

		if($row1['SUBSCRIPTION']<>'')
		{
			$entry_dt=$row1["ENTRY_DT"];
                	$photo_dt=$row1["PHOTODATE"];

			/*$sql_rec = "SELECT SCORE FROM incentive.MAIN_ADMIN_POOL WHERE PROFILEID ='$pid'";
			$res_rec = mysql_query($sql_rec,$db2) or logError($sql_rec,$db2);
			if($row_rec = mysql_fetch_array($res_rec))
				$score=$row_rec['SCORE'];
			else
			{*/
				//Calculate score.
				$sql_pid = "SELECT  AGE , YOURINFO , FAMILYINFO , SPOUSE , JOB_INFO , SIBLING_INFO  , FATHER_INFO , HAVEPHOTO , RELATION , LAST_LOGIN_DT  , CITY_RES, SOURCE , HAVEPHOTO,GENDER FROM newjs.JPROFILE WHERE PROFILEID ='$pid'";

				$res_pid = mysql_query($sql_pid,$db2) or logError($sql_pid,$db2);

				if ($row_pid = mysql_fetch_array($res_pid))
				{
					$source=$row_pid['SOURCE'];
					$myDbName=getProfileDatabaseConnectionName($pid);
					
					 if(!$myDb[$myDbName])
                                                $myDb[$myDbName]=$mysql->connect("$myDbName","slave");

					// query to find the first date in an interval of 30 days when the user logged in
					$sql_login_cnt = "SELECT COUNT(*) AS CNT FROM newjs.LOGIN_HISTORY WHERE PROFILEID = '$pid' AND LOGIN_DT >= '$start_dt'";                 
					$res_login_cnt = mysql_query($sql_login_cnt,$myDb[$myDbName]) or logError($sql_login_cnt,$myDb[$myDbName]);

					while($row_login_cnt = mysql_fetch_array($res_login_cnt))
					{
						$login_cnt = $row_login_cnt['CNT'];
					}

					// query to find the count of contacts initiated
					//Sharding on CONTACTS done  by Neha
					$contactResult=getResultSet("COUNT(*) AS CNT4",$pid,"","","","","","TIME >= '$start_dt'","","","","","slave");
                                        $INITIATE_CNT=$contactResult[0]["CNT4"];
					unset($contactResult);
					$contactResult=getResultSet("COUNT(*) AS CNT","","",$pid,"'A'","","","TIME >= '$start_dt'","","","","","slave");
                                        $ACCEPTANCE_MADE=$contactResult[0]["CNT"];
					unset($contactResult);

					//End

					$contact_cnt = $INITIATE_CNT + $ACCEPTANCE_MADE;
					$PROFILELENGTH = strlen($row_pid['YOURINFO']) + strlen($row_pid['FAMILYINFO']) + strlen($row_pid['SPOUSE']) + strlen($row_pid['FATHER_INFO']) + strlen($row_pid['SIBLING_INFO']) + strlen($row_pid['JOB_INFO']);
					$score = calc_user_score_search($row_pid['AGE'],$row_pid['GENDER'],$PROFILELENGTH , $row_pid['HAVEPHOTO'], $row_pid['RELATION'],$entry_dt,$login_cnt,$contact_cnt);
				}
			//}

			if($score<=150)
				$newscore=-50;
			elseif($score<326)
				$newscore=150;
			else
				$newscore=300;
			
			$today=date("Y-m-d");
			if($row1["HAVEPHOTO"]=='Y')
				$diff=DayDiff(substr($photo_dt,0,10),$today);
			else
				$diff=DayDiff(substr($entry_dt,0,10),$today);
			$freshness_points=0;

			if($diff<16)
				$freshness_points=300;
			elseif($diff>15 && $diff<46)
				$freshness_points=150;
			else
				$freshness_points=100;

			$total_points=$newscore+$freshness_points;

			if($db_flag==1)
				@mysql_ping($db);

			$sql1="UPDATE newjs.$table SET SCORE_POINTS='$newscore',FRESHNESS_POINTS=$freshness_points,TOTAL_POINTS='$total_points',PROFILE_SCORE='$score' WHERE PROFILEID='$pid'";
			if($db_flag==1)
				$res1=mysql_query($sql1,$db) or logError($sql1,$db);
			else
				$res1=mysql_query($sql1,$db2) or logError($sql1,$db2);
			//echo '-----------------------------------------------------------------------------';

			unset($pid);
			unset($score);
			unset($contact_cnt);
			unset($PROFILELENGTH);
		}
	}
}

function DayDiff($StartDate, $StopDate)
{
   // converting the dates to epoch and dividing the difference
   // to the approriate days using 86400 seconds for a day
   //
   return (date('U', strtotime($StopDate)) - date('U', strtotime($StartDate))) / 86400; //seconds a day
}

?>
