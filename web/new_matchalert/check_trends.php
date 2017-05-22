<?php
//for preventing timeout to maximum possible
ini_set('max_execution_time',0);
ini_set('memory_limit',-1);
ini_set('mysql.connect_timeout',-1);
ini_set('default_socket_timeout',259200); // 3 days
ini_set('log_errors_max_len',0);
//for preventing timeout to maximum possible

chdir(dirname(__FILE__));
include_once("connect.inc");
include_once(JsConstants::$alertDocRoot."/new_matchalert/configVariables.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/Receiver.class.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/StrategyNTvsNT.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/StrategyNTvsT.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/StrategyTvsNT.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/StrategyTvsT.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/SendMatchAlert.php");
include_once(JsConstants::$alertDocRoot."/classes/shardingRelated.php");
include_once(JsConstants::$alertDocRoot."/commonFiles/SymfonyPictureFunctions.class.php");

$mysqlObj=new Mysql;

$localdb=$mysqlObj->connect("alerts");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$localdb);
mysql_select_db("matchalerts",$localdb) or die(mysql_error());

$db_211=$mysqlObj->connect("viewLogSlave");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_211);
mysql_select_db("newjs",$db_211) or die(mysql_error());



for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
	$myDbName=getActiveServerName($activeServerId,'slave',$mysqlObj);

	$myDbArr[$myDbName]=$mysqlObj->connect("$myDbName");
	mysql_query('set session wait_timeout=86400,interactive_timeout=86400,net_read_timeout=86400',$myDbArr[$myDbName]);
}
//Write query to pick all profile ids here

//lOGIC FOR PARR SCRIPTS
$total_scripts=$_SERVER['argv'][1];
$this_script=$_SERVER['argv'][2];

//global $maxId;
$idForTarcking=$_SERVER['argv'][3];

$db_fast=$mysqlObj->connect("alerts");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_fast);
mysql_select_db("matchalerts",$db_fast) or die(mysql_error());

if(!$total_scripts || !$idForTarcking)
{
	echo "!!!";
	//MAIL ME 
	die;
	//sms & mail to inform
}

if($this_script==0)
{
        $DATEFORMATCHALERTSMAILER=configVariables::getNoOfDays();
        $DATEFORMATCHALERTSMAILER=getMatchDate($DATEFORMATCHALERTSMAILER);
        $sql="UPDATE matchalerts.DATEFORMATCHALERTSMAILER SET VALUE='$DATEFORMATCHALERTSMAILER'";
        mysql_query($sql,$localdb) or die(mysql_error()); //send sms
}


        $sql_loop="SELECT A.PROFILEID,GENDER FROM matchalerts.JPARTNER A LEFT JOIN PROFILE_LOGS B ON A.PROFILEID=B.PROFILEID WHERE B.PROFILEID IS NULL AND A.PROFILEID%$total_scripts=$this_script";
        //$sql_loop="SELECT PROFILEID,GENDER FROM matchalerts.JPARTNER limit $kk,1000";
        $result_loop=mysql_query($sql_loop,$localdb) or logerror1("In check_trends.php",$sql_loop);
        while($row_loop=mysql_fetch_array($result_loop))
        {
                $profileId=$row_loop["PROFILEID"];
                if(($profileId % $total_scripts)!=$this_script)
                        continue;
                $sql="INSERT INTO PROFILE_LOGS VALUES($profileId)";
                mysql_query($sql,$localdb) or die(mysql_error($localdb).$sql);//logerror1("In matchalert_mailer.php ",$sql);
                $sendAlertObject = new SendMatchAlert($profileId , $myDbArr , $localdb , $mysqlObj);
                $sendAlertObject->send($idForTarcking);

//TEMP LEVEL TRACKING
if($i10_mycar++%5000==0)
{
$sql="REPLACE INTO matchalerts.LEVELTRACK_TEMP VALUES ('NT-NT','$this_script','$total_scripts','$NTvNTlevel1','$NTvNTlevel2','$NTvNTlevel3','$NTvNTlevel4','$NTvNTlevel5','$NTvNTlevel6',now())";
mysql_query($sql,$localdb);

$sql="REPLACE INTO matchalerts.LEVELTRACK_TEMP VALUES ('NT-T','$this_script','$total_scripts','$NTvTlevel1','$NTvTlevel2','$NTvTlevel3','$NTvTlevel4','$NTvTlevel5','$NTvTlevel6',now())";
mysql_query($sql,$localdb);

$sql="REPLACE INTO matchalerts.LEVELTRACK_TEMP VALUES ('T-NT','$this_script','$total_scripts','$TvNTlevel1','$TvNTlevel2','$TvNTlevel3','$TvNTlevel4','$TvNTlevel5','$TvNTlevel6',now())";
mysql_query($sql,$localdb);

$sql="REPLACE INTO matchalerts.LEVELTRACK_TEMP VALUES ('T-T','$this_script','$total_scripts','$TvTlevel1','$TvTlevel2','$TvTlevel3','$TvTlevel4','$TvTlevel5','$TvTlevel6',now())";
mysql_query($sql,$localdb);
}
//TEMP LEVEL TRACKING

        }

        //LEVEL TRACKING
        $sql="INSERT INTO matchalerts.LEVELTRACK VALUES ('NT-NT','$this_script','$total_scripts','$NTvNTlevel1','$NTvNTlevel2','$NTvNTlevel3','$NTvNTlevel4','$NTvNTlevel5','$NTvNTlevel6',now())";
        mysql_query($sql,$localdb);
        
        $sql="INSERT INTO matchalerts.LEVELTRACK VALUES ('NT-T','$this_script','$total_scripts','$NTvTlevel1','$NTvTlevel2','$NTvTlevel3','$NTvTlevel4','$NTvTlevel5','$NTvTlevel6',now())";
        mysql_query($sql,$localdb);
        
        $sql="INSERT INTO matchalerts.LEVELTRACK VALUES ('T-NT','$this_script','$total_scripts','$TvNTlevel1','$TvNTlevel2','$TvNTlevel3','$TvNTlevel4','$TvNTlevel5','$TvNTlevel6',now())";
        mysql_query($sql,$localdb);

        $sql="INSERT INTO matchalerts.LEVELTRACK VALUES ('T-T','$this_script','$total_scripts','$TvTlevel1','$TvTlevel2','$TvTlevel3','$TvTlevel4','$TvTlevel5','$TvTlevel6',now())";
        mysql_query($sql,$localdb);
        //LEVEL TRACKING

function getMatchDate($gap)
{
        //$gap=2211;
        $zero=mktime(0,0,0,01,01,2005);
        $today=$gap*24*60*60+$zero;
        $dt= date("Y-m-d");
        $dtArr=explode("-",$dt);
        $monthnum=array("01"=>"Jan",
                        "02"=>"Feb",
                        "03"=>"Mar",
                        "04"=>"Apr",
                        "05"=>"May",
                        "06"=>"Jun",
                        "07"=>"Jul",
                        "08"=>"Aug",
                        "09"=>"Sep",
                        "10"=>"Oct",
                        "11"=>"Nov",
                        "12"=>"Dec");
        $day=$dtArr[2];
        $l = strlen($day);
        if($l==1)
        {
                if($day == '1')
                        $suffix="st";
                elseif($day == '2')
                        $suffix="nd";
                elseif($day == '3')
                        $suffix="rd";
                else
                        $suffix="th";
        }
        else
        {
                $last_digit = substr($day,1,2);
                if(($day == '11')||($day == '12')||($day == '13'))
                        $suffix="th";
                elseif($last_digit == '1')
                        $suffix="st";
                elseif($last_digit == '2')
                        $suffix="nd";
                elseif($last_digit == '3')
                        $suffix="rd";
                else
                        $suffix="th";
        }
        if(substr($day,0,1)=='0')
                $day=substr($day,1,1);
        $day=$day.$suffix;
        $final=$day." ".$monthnum[$dtArr[1]]."-";
        return $final;
}
?>
