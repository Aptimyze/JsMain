<?
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
chdir(dirname(__FILE__));
include_once(JsConstants::$docRoot."/profile/connect.inc");
ini_set('max_execution_time','0');
ini_set('memory_limit','128M');

$dbS = connect_slave();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);

$dbM = connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);
$profileId=$_SERVER['argv'][1];
$profileId=explode(",",$profileId);
//print_r($profileId);die;
$num=5000;
$date = "2015-07-01";

//$pid = "11023847";
//print_r("expression");die;
//$sql = "SELECT ID FROM IGNORE_PROFILE WHERE PROFILEID='$pid' and DATE<'$date'";
//$sql = "SELECT  PROFILEID FROM ( SELECT COUNT( * ) AS COUNT, PROFILEID , DATE FROM newjs.IGNORE_PROFILE GROUP BY PROFILEID ) AS T WHERE COUNT > 5000 ORDER BY DATE DESC ;"
//$sql = "SELECT COUNT( * ) AS COUNT, PROFILEID , DATE FROM newjs.IGNORE_PROFILE GROUP BY PROFILEID WHERE PROFILEID = ".$profileId;
//echo "\n".$sql."\n";;die;
//$result=mysql_query($sql,$dbS) or mysql_error1(mysql_error($db).$sql);
//while($row=mysql_fetch_assoc($result))
//{
    
  //  $pid = $row["PROFILEID"];
    //$count = $row["COUNT"];
    //$limit = $count-$num;
    //if($count>$num)
    //{
        $profileCount=count($profileId);//print_r($profileCount);die;
        while ($profileCount>=0) {
           // var_dump($profileId[$profileCount-1]);die;
        $sql = 'SELECT  PROFILEID,ID FROM newjs.IGNORE_PROFILE WHERE PROFILEID='.$profileId[$profileCount-1].' ORDER BY DATE DESC';
       // $sql = "SELECT PROFILEID,ID FROM (SELECT ROW_NUMBER OVER(ORDER BY DATE ASC) RowNumber, * FROM newjs.IGNORE_PROFILE WHERE PROFILEID=$pid) t WHERE RowNumber >= 5000";
        $result1=mysql_query($sql,$dbS) or mysql_error1(mysql_error($db).$sql);
        $i=0;
        while($row1=mysql_fetch_assoc($result1))
        {
            $id[$i] = $row1["ID"];
            $i++;
           // $sql = "DELETE FROM newjs.IGNORE_PROFILE WHERE PROFILEID='$pid' AND ID = '$id'";
            //mysql_query($sql,$dbM) or mysql_error1(mysql_error($dbM).$sql);
            //$lavesh++;
        }
       // print_r($id);die;
        $count = count($id);
        //print_r($count);die;
        for ($i=$count; $i > 5000 ; $i--) { 
         $sql = 'DELETE FROM newjs.IGNORE_PROFILE WHERE PROFILEID='.$profileId[$profileCount-1].' AND ID = '.$id[$i-1].'';
            mysql_query($sql,$dbM) or mysql_error1(mysql_error($dbM).$sql);
        }
        $profileCount--;
    }


/*
if($row=mysql_fetch_assoc($result))
{
        $id = $row["ID"];
        $count = $row["COUNT"];
        //$sql = "DELETE FROM newjs.IGNORE_PROFILE WHERE PROFILEID='$pid' AND ID='$id'";
        if($count>$num)
        {
        $sql = "DELETE FROM newjs.IGNORE_PROFILE WHERE PROFILEID='$id'";
        mysql_query($sql,$dbM) or mysql_error1(mysql_error($dbM).$sql);
        $lavesh++;
    }
}*/
//echo $lavesh;

function mysql_error1($msg)
{
        echo $msg;die;
}       
