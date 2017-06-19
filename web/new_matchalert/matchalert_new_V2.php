<?php
/***************************************************************************************************************
* FILE NAME     : matchalert_new.php
* DESCRIPTION   : Connects to the database, creates an object of Smarty class and then calls the functions one-by-one
*		: which eventually sends the mails
* Created By    : Lavesh Rawat
*****************************************************************************************************************/

//for preventing timeout to maximum possible
ini_set('max_execution_time',0);
ini_set('memory_limit',-1);
ini_set('mysql.connect_timeout',-1);
ini_set('default_socket_timeout',259200); // 3 days
ini_set('log_errors_max_len',0);
//for preventing timeout to maximum possible
chdir(dirname(__FILE__));
include_once(JsConstants::$alertDocRoot."/new_matchalert/connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/flag.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/populate_new_inc_V2.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/matchalert_partnerprofile_V2.php");

$SITE_URL=JsConstants::$siteUrl;

$mysqlObj = new Mysql;
$db=$mysqlObj->connect("alerts");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
mysql_select_db("matchalerts",$db) or die(mysql_error());
if(!$php5)
	$php5=JsConstants::$php5path; //live php5
if(!$logerrorFilePath)
	$logerrorFilePath=JsConstants::$alertDocRoot.'/new_matchalert/logerror.txt';
if(!$logerrorFilePath1)
	$logerrorFilePath1=JsConstants::$alertDocRoot.'/new_matchalert/matchalert_count.txt';
while(1)
{

        @mysql_ping($db);
	/** code for daily count monitoring**/
		$cronDocRoot = JsConstants::$cronDocRoot;
		 passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring MATCHALERT_MAILER");
	/**code ends*/
                 
        /** code for inserting daily count monitoring**/
		 passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring MATCHALERT_MAILER#INSERT");
	/**code ends*/

        $sql="SELECT COUNT(*) FROM matchalerts.MAILER WHERE SENT=''";
        $result=mysql_query($sql) or die(mysql_error().$sql);
        $myrow=mysql_fetch_row($result);
        $n_count=$myrow[0];

        if(!$n_count)
        {
		$sql="UPDATE matchalerts.STARTCRON SET VALUE=0";
		mysql_query($sql) or die(mysql_error().$sql);

		$sql="INSERT INTO matchalerts.MAILER_TEMP SELECT * FROM matchalerts.MAILER";
		mysql_query($sql) or ($exit_flag=1);
		if($exit_flag==1)
		{
			mail('lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com','INSERT INTO matchalerts.MAILER_TEMP SELECT * FROM matchalerts.MAILER',$today);
			exit;
		}
		passthru("$php5 -q matchalert_count.php >> $logerrorFilePath &");
		//passthru("$php5 -q matchalertsMoveLogTables.php >> $logerrorFilePath &");

		//Locking -- To prevent concurent call to the file.
		$lock=1;
		if($lock==1)
		{
			$sql="SELECT MAX(ID) AS MAX FROM matchalerts.NEW_LOGIC_MATCHALERTS";
			$result=mysql_query($sql,$db) or logerror1("In populate_inc.php --search",$sql,"NO","Y");
			$row=mysql_fetch_array($result);
			$maxId=$row["MAX"];
			if($maxId)
				$maxId=$maxId+1;
			else
				$maxId=1;

                        $file = fopen("/tmp/populate_kundli_table.txt","w+");
                        $i=1;
                        while(!flock($file,2))
                        {
                                sleep(60);
                                if ($i==5)
                                {
                                        mail("lavesh.rawat@jeevansathi.com","Error in matchalert_new_V2.php","locking issue");
                                        die;
                                }
                                $i++;
                        }
			populate();
			//Realeasing the lock
			$sql="SELECT RELEASE_LOCK('lavesh_matchalert')";
			$result=mysql_query($sql) or die(mysql_error().$sql);
			//Realeasing the lock
			//pouplate_partnerprofile();
                        passthru("$php5 -q matchalert_partnerprofile_V2_forspeedup.php >> $logerrorFilePath &");
                       	pouplate_partnerprofile('M');
			flock($file,3);
		
			//---------------new Code ------------------------------------------
			$sql="TRUNCATE TABLE PROFILE_LOGS";
			mysql_query($sql,$db) or die(mysql_error($db).$sql);
		
			$sql="TRUNCATE TABLE TRENDS_SEARCH_FEMALE";
                        mysql_query($sql,$db) or die(mysql_error($db).$sql);

			$sql="TRUNCATE TABLE NOTRENDS_SEARCH_FEMALE";
                        mysql_query($sql,$db) or die(mysql_error($db).$sql);

                        $sql="CREATE TEMPORARY TABLE PIDS(PID int(11),INDEX(PID))";
                        mysql_query($sql,$db) or die(mysql_error($db).$sql);

                        $sql="INSERT INTO PIDS SELECT PROFILEID FROM TRENDS WHERE GENDER = 'F' AND (INITIATED + ACCEPTED) >19";
                        mysql_query($sql,$db) or die(mysql_error($db).$sql);

			$sql="INSERT INTO TRENDS_SEARCH_FEMALE SELECT * FROM SEARCH_FEMALE";
                        mysql_query($sql,$db) or die(mysql_error($db).$sql);

			$sql="INSERT INTO NOTRENDS_SEARCH_FEMALE SELECT * FROM SEARCH_FEMALE";
                        mysql_query($sql,$db) or die(mysql_error($db).$sql);

                        $sql="DELETE TRENDS_SEARCH_FEMALE.* FROM TRENDS_SEARCH_FEMALE LEFT JOIN PIDS ON TRENDS_SEARCH_FEMALE.PROFILEID=PIDS.PID WHERE PIDS.PID IS NULL";
                        mysql_query($sql,$db) or die(mysql_error($db).$sql);

                        $sql="DELETE NOTRENDS_SEARCH_FEMALE.* FROM NOTRENDS_SEARCH_FEMALE LEFT JOIN PIDS ON NOTRENDS_SEARCH_FEMALE.PROFILEID=PIDS.PID WHERE PIDS.PID IS NOT NULL";
                        mysql_query($sql,$db) or die(mysql_error($db).$sql);


                        $sql="TRUNCATE TABLE PIDS";
                        mysql_query($sql,$db) or die(mysql_error($db).$sql);

			$sql="TRUNCATE TABLE TRENDS_SEARCH_MALE";
                        mysql_query($sql,$db) or die(mysql_error($db).$sql);

			$sql="TRUNCATE TABLE NOTRENDS_SEARCH_MALE";
                        mysql_query($sql,$db) or die(mysql_error($db).$sql);


                        $sql="INSERT INTO PIDS SELECT PROFILEID FROM TRENDS WHERE GENDER = 'M' AND (INITIATED + ACCEPTED) >19";
                        mysql_query($sql,$db) or die(mysql_error($db).$sql);

			$sql="INSERT INTO TRENDS_SEARCH_MALE SELECT * FROM SEARCH_MALE";
                        mysql_query($sql,$db) or die(mysql_error($db).$sql);

			$sql="INSERT INTO NOTRENDS_SEARCH_MALE SELECT * FROM SEARCH_MALE";
                        mysql_query($sql,$db) or die(mysql_error($db).$sql);

                        $sql="DELETE TRENDS_SEARCH_MALE.* FROM TRENDS_SEARCH_MALE LEFT JOIN PIDS ON TRENDS_SEARCH_MALE.PROFILEID=PIDS.PID WHERE PIDS.PID IS NULL";
                        mysql_query($sql,$db) or die(mysql_error($db).$sql);

                        $sql="DELETE NOTRENDS_SEARCH_MALE.* FROM NOTRENDS_SEARCH_MALE LEFT JOIN PIDS ON NOTRENDS_SEARCH_MALE.PROFILEID=PIDS.PID WHERE PIDS.PID IS NOT NULL";
                        mysql_query($sql,$db) or die(mysql_error($db).$sql);

                        $sql="TRUNCATE TABLE PIDS";
                        mysql_query($sql,$db) or die(mysql_error($db).$sql);
			//---------------new Code ------------------------------------------


			$lock=1;
			//Locking -- To ensure that we can go ahead only if pouplate_partnerprofile fn and matchalert_trend_cron is completed
			if($lock==1)
			{
				include("populateHeapTables.php");

				$sql="UPDATE matchalerts.STARTCRON SET VALUE='$maxId'";
				mysql_query($sql) or die(mysql_error().$sql);
				passthru("$php5 -q new_runMatches_862.php >> $logerrorFilePath");
				die("Done");
			}
			else
			{
				$today=date('Y-m-d');
				mail('lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com','matchalert-multiple not yet completed later',$today);
				exit;
			}
		}
	}
        else
        {
                usleep(300000000);
        }

}
?>
