<?php
		include_once(sfConfig::get("sf_web_dir")."/profile/connect_db.php");
		include_once(sfConfig::get("sf_web_dir")."/classes/globalVariables.Class.php");//Memcache
		include_once(sfConfig::get("sf_web_dir")."/classes/Memcache.class.php");//Memcache
                include_once(sfConfig::get("sf_web_dir")."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/commonFiles/mysql_multiple_connections.php");

class UpdateScore
{
	public function update_score($pid)
	{
		$ts = time() - 30*24*60*60;
		$start_dt = date("Y-m-d",$ts);

        	$sql_pid = "SELECT  AGE , ENTRY_DT,YOURINFO , FAMILYINFO , SPOUSE , JOB_INFO , SIBLING_INFO  , FATHER_INFO , HAVEPHOTO , RELATION , LAST_LOGIN_DT  , CITY_RES, SOURCE , HAVEPHOTO,GENDER FROM newjs.JPROFILE WHERE PROFILEID ='$pid'";

			connect_db();
        	$res_pid = mysql_query_decide($sql_pid) or logError($sql_pid);

        	if ($row_pid = mysql_fetch_array($res_pid))
        	{
                	$source=$row_pid['SOURCE'];
			$entry_dt=$row_pid['ENTRY_DT'];

			$mysqlObj=new Mysql;
			$myDbName=getProfileDatabaseConnectionName($pid,'',$mysqlObj);
			$myDb=$mysqlObj->connect("$myDbName");
                	// query to find the first date in an interval of 30 days when the user logged in
                	$sql_login_cnt = "SELECT COUNT(*) AS CNT FROM newjs.LOGIN_HISTORY WHERE PROFILEID = '$pid' AND LOGIN_DT >= '".$start_dt."'";
			$res_login_cnt=$mysqlObj->executeQuery($sql_login_cnt,$myDb) or die(mysql_error_js($myDb));

                	while($row_login_cnt =$mysqlObj->fetchArray($res_login_cnt))
                	{
                	        $login_cnt = $row_login_cnt['CNT'];
                	}
			connect_db();
                	// query to find the count of contacts initiated
                	$sql_init_cnt ="SELECT COUNT(*) AS CNT4 FROM newjs.CONTACTS WHERE SENDER = '$pid' AND TIME >= '".$start_dt."'";
                	$res4 = $mysqlObj->executeQuery($sql_init_cnt,$myDb) or die(mysql_error_js($myDb));
	
        	        $row4 =$mysqlObj->fetchArray($res4);

                	$INITIATE_CNT= $row4['CNT4'];
		
			// query to find the count of contacts accepted
                	$sql_accpt_cnt="SELECT COUNT(*) AS CNT FROM newjs.CONTACTS  WHERE RECEIVER='$pid' and TYPE='A' AND TIME >= '".$start_dt."'";
                	$result=$mysqlObj->executeQuery($sql_accpt_cnt,$myDb) or die(mysql_error_js($myDb));
                                                  
                	$myrow=$mysqlObj->fetchArray($result);

                	$ACCEPTANCE_MADE = $myrow["CNT"];

                	$contact_cnt = $INITIATE_CNT + $ACCEPTANCE_MADE;

                	$PROFILELENGTH = strlen($row_pid['YOURINFO']) + strlen($row_pid['FAMILYINFO']) + strlen($row_pid['SPOUSE']) + strlen($row_pid['FATHER_INFO']) + strlen($row_pid['SIBLING_INFO']) + strlen($row_pid['JOB_INFO']);

                	$score = $this->calc_user_score_search($row_pid['AGE'],$row_pid['GENDER'],$PROFILELENGTH , $row_pid['HAVEPHOTO'], $row_pid['RELATION'],$entry_dt,$login_cnt,$contact_cnt);
                	return $score;
        	}
        	//return 0;
	}

	public function calc_user_score_search($age,$gender,$profilelength,$photo,$postedby,$reg_dt,$login_cnt,$contact_cnt)
	{
        	$user_score = 0;

        	if ($gender == 'F')
        	{
                	if ($age >= 26)
                	{
                        	$user_score += 300;
                	}
                	elseif ($age >= 23 && $age <= 25)
                	{
                	        $user_score += 200;
                	}
                	elseif ($age <= 22)
                	{
                	        $user_score += 100;
                	}
        	}
		else
        	{
                	if ($age >= 28)
                	{
                	        $user_score += 200;
                	}
                	elseif ($age >= 25 && $age <= 27)
                	{
                        	$user_score += 150;
                	}
                	elseif ($age < 25)
                	{
                	        $user_score += 25;
                	}
        	}
	  	if ($profilelength >= 1000)
        	{
        	        $user_score += 75;
        	}
        	elseif ($profilelength >= 600 && $profilelength < 1000)
        	{
        	        $user_score += 50;
        	}
        	elseif ($profilelength < 600)
        	{
        	        $user_score += 0;
        	}
	
        	if ($photo == 'Y')
        	        $user_score += 200;
        	else//if ($photo ==  'N')
        	        $user_score += 100;
	
	        if ($postedby == '2' || $postedby == '3')
	        {
	                $user_score+= 250;
	        }
	        elseif ($postedby == '1')
	        {
	                $user_score+= 150;
	        }
	        else
		{
	                $user_score+= 25;
		}
		$today_ts = mktime(23,59,59,date("m"),date("d"),date("Y"));
	      	//$today_ts = mktime(23,59,59,date("m"),date("d")-1,date("Y"));
	
	        list($yy,$mm,$dd)=explode("-",substr($reg_dt,0,10));
	        $regn_ts = mktime(0,0,0,$mm,$dd,$yy);
	
	        $days_diff = intval(($today_ts - $regn_ts)/(24*60*60));
	
	        if ($days_diff > 30)
	                $days_in_sys = 30;
	        else
	                $days_in_sys = $days_diff;
	
	        if ($days_in_sys && $login_cnt)
	        {
	                $login_freq = $login_cnt/$days_in_sys;
	                if ($login_freq >= 0.4)
	                {
	                        $user_score+=250;
	                }
	                elseif ($login_freq >= 0.2 && $login_freq < 0.4)
	                {
	                        $user_score+=150;
	                }
	                else
			{
	                        $user_score+=0;
			}
	        }
		if ($days_in_sys && $contact_cnt)
	        {
               		$contact_freq = $contact_cnt/$days_in_sys;
                	if ($contact_freq >= 0.8)
                	{
                	        $user_score+= 175;
                	}
                	elseif ($contact_freq >=0.2 && $contact_freq < 0.8)
                	{
                	        $user_score+= 125;
                	}
                	else
			{
                	        $user_score+= 0;
			}
        	}	

        	$user_score = round(($user_score*600)/1250);
	
        	return $user_score;
	}
}
?>
