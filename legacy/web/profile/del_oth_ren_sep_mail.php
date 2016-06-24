<?php

//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
        $zipIt = 1;
if($zipIt)
        ob_start("ob_gzhandler");
//end of it
include("connect.inc");
$db=connect_db();
$dbs=connect_slave();
include("../crm/func_sky.php");
global $cnt;
$sql= " select PROFILEID FROM  newjs.JPROFILE WHERE MSTATUS='O'";
$result=mysql_query_decide($sql,$dbs) or die(logError($sql,$dbs));
       while($row=mysql_fetch_array($result))
        {
                $profiles[]=$row['PROFILEID'];
        }
/*$sql= " select PROFILEID FROM  newjs.SEARCH_FEMALE WHERE MSTATUS='O'";
$result=mysql_query_decide($sql) or die(logError($sql,$db));
//$row=mysql_fetch_assoc($result);
       while($row=mysql_fetch_array($result))
        {
                $profiles[]=$row['PROFILEID'];
        }
*/
	$pid= $profiles;
        $j=0;
        $date= date("Y-m-d H:i:s");
        $len=count($pid);
                while($j<$len)
                {
                        $profile=$pid[$j];
                        $sql2="UPDATE newjs.JPROFILE set PREACTIVATED=IF(ACTIVATED<>'H',if(ACTIVATED<>'D',ACTIVATED,PREACTIVATED),PREACTIVATED), ACTIVATED='D',activatedKey=0 where PROFILEID=$profile";
                        mysql_query_decide($sql2,$db) or die(logError($sql2,$db));
                        $tm = date("Y-M-d");
                        $sql1= "SELECT USERNAME FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID=$profile";
                        $res1=mysql_query_decide($sql1,$db) or die(logError($sql1,$db));
                        $row1=mysql_fetch_assoc($res1);
                        $username=$row1['USERNAME'];
			$comments = "Marital status others are no longer valid ";
                        $sql = "INSERT into jsadmin.DELETED_PROFILES(PROFILEID,USERNAME,REASON,COMMENTS,USER,TIME)  values($profile,'$username','Immediate deletion','$comments','$name','$tm')";
                        mysql_query_decide($sql,$db) or die(logError($sql,$db));
                        $path = $_SERVER['DOCUMENT_ROOT']."/profile/deleteprofile_bg.php $profile > /dev/null &";
                        $cmd = JsConstants::$php5path." -q ".$path;
                        passthru($cmd);
                        $j++;
                }
//New list of marital status
$seo_mstatus_drop=array("N" => "Never Married","W" => "Widowed","D" => "Divorced","S" => "Awaiting divorce","O" => "Others","A"=>"Annulled");//Separated renamed as awaiting divorce
$sql= " select PROFILEID FROM  newjs.SEARCH_MALE WHERE MSTATUS='S'";
$result=mysql_query_decide($sql,$db) or die(logError($sql,$db));
       while($row1=mysql_fetch_array($result))
        {
		$sql2="select USERNAME,EMAIL FROM  newjs.JPROFILE  WHERE   activatedKey=1 and PROFILEID='$row1[PROFILEID]'";
		$result2=mysql_query_decide($sql2,$db) or die(logError($sql2,$db));
		$row2=mysql_fetch_assoc($result2);
		$to = $row2['EMAIL'];
		$subject ="Profiles with marital status 'Separated' will be changed to marital status 'Awaiting divorce'";
		$message = "Dear Jeevansathi user,<br><br>We have recently launched the completely new Jeevansathi.com. The new Jeevansathi.com makes it easier for you to search and contact profiles based on your preferences.<br><br>As another step towards improving the user experience on Jeevansathi.com, we have removed the marital status 'Separated' and replaced it with the new marital status 'Awaiting Divorce'. Your marital status has been updated automatically to 'Awaiting Divorce'. If need be, you can edit your profile by logging onto www.jeevansathi.com<br><br>Wishing you the best in your search for a life partner.<br><br>The Jeevansathi team. ";
		$from = "webmaster@jeevansathi.com";
		$headers = "From: $from";
		mail($to,$subject,$message,$headers);
        }
$sql= " select PROFILEID FROM  newjs.SEARCH_FEMALE WHERE MSTATUS='S'";
$result=mysql_query_decide($sql,$db) or die(logError($sql,$db));
       while($row1=mysql_fetch_array($result))
        {
                $sql2="select EMAIL FROM  newjs.JPROFILE  WHERE   activatedKey=1 and PROFILEID='$row1[PROFILEID]'";
                $result2=mysql_query_decide($sql2,$db) or die(logError($sql2,$db));
                $row2=mysql_fetch_assoc($result2);
                $to = $row2['EMAIL'];
                $subject ="Profiles with marital status 'Separated' will be changed to marital status 'Awaiting divorce'";
                $message = "Dear Jeevansathi user,<br><br>We have recently launched the completely new Jeevansathi.com. The new Jeevansathi.com makes it easier for you to search and contact profiles based on your preferences.<br><br>As another step towards improving the user experience on Jeevansathi.com, we have removed the marital status 'Separated' and replaced it with the new marital status 'Awaiting Divorce'. Your marital status has been updated automatically to 'Awaiting Divorce'. If need be, you can edit your profile by logging onto www.jeevansathi.com<br><br>Wishing you the best in your search for a life partner.
<br><br>The Jeevansathi team. ";
                $from = "webmaster@jeevansathi.com";
                $headers = "From: $from";
                mail($to,$subject,$message,$headers);
        }






?>
