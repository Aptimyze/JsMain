<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

ini_set("max_execution_time","0");
chdir(dirname(__FILE__));

include("connect.inc");
$db=connect_db();

$sql="select USER from bot_jeevansathi.user_online";
//$sql="select PROFILEID as USER from newjs.SEARCH_MALE limit 10000";
$res=mysql_query($sql) or mysql_error();
while($row=mysql_fetch_assoc($res))
{
        $prof[$row[USER]]=$row[USER];
}
if(is_array($prof))
{
        $profile_str=implode(",",$prof);
//echo count($prof);
        if($profile_str!="")
        {
                $sql="select PROFILEID from newjs.SEARCH_MALE where PROFILEID IN($profile_str) UNION select PROFILEID from newjs.SEARCH_FEMALE where PROFILEID IN($profile_str)";
                $res=mysql_query($sql) or mysql_error();
                while($row=mysql_fetch_assoc($res))
                {
                        unset($prof[$row[PROFILEID]]);
                        //$prof[]=$row[USER];
                }
        }
//echo "--";
//echo count($prof);
        if(is_array($prof))
        {
                $profile_str=implode(",",$prof);
                if($profile_str!="")
                {
                        $sql="delete from bot_jeevansathi.user_online where USER in($profile_str)";
                        mysql_query($sql) or mysql_error();
                }
        }
}
?>
