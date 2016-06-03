<?
include('connect.inc');
$db=connect_db();
//$username="test4js";
//$password="test4js";
$data=login($username,$password);
$profileid=$data["PROFILEID"];
$username=$data["USERNAME"];
$checksum=$data["CHECKSUM"];
if($data)
{         $sql="SELECT SUBSCRIPTION FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
        $res=mysql_query_decide($sql,$db) or logError("Error while finding subscription,messenger ".mysql_error_js(),$sql);
        $row=mysql_fetch_array($res);
        $subscriptionArray=explode(",",$row['SUBSCRIPTION']);
        $k=count($subscriptionArray);
        $valuable=0;
        for($i=0;$i<$k;$i++)
        {
                switch($subscriptionArray[$i])
                {
                        case 'F' :
                                //if($valuable>0)$userstatus.=" and ";
                                //$userstatus.="an Erishta member";
                                $valuable++;
                                break;
                        case 'D' :
//if($valuable>0)$userstatus.=" and ";
                                //$userstatus.="an EClassified member";
                                $valuable++;
                        break;
                default :
                        break;
                }
        }
        echo "&username=".$username."&profileid=".$profileid."&checksum=".$checksum."&valuable=".$valuable."&justlikethat=1";
}
else
{
        echo "&username=98184056399434133537&justlikethat=1";
}
?>                                                                          
