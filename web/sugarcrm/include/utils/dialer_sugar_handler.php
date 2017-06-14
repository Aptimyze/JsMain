<?php
include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/sugarcrm/include/utils/systemProcessUsersConfig.php");

global $process_user_mapping;

$processUserId=$process_user_mapping["dialer"];
if(!$processUserId)
        $processUserId=1;

$updateTime=date("Y-m-d H:i:s");

$myDb_master=connect_db();
if($not_login)
{
    $not_login='';
 //   $sql = "UPDATE sugarcrm.leads SET assigned_user_id='',modified_user_id='$processUserId',date_modified='$updateTime' WHERE id='$leadid'";
   // $res=mysql_query($sql,$myDb_master) or die($sql.mysql_error($myDb_master));
    echo "Executive is not logged in.Please login and try again.";
    die;
}
else
    $assigned_user_id=$_COOKIE["ck_login_id_20"];
if($leadid!='')
{
    $sql = "UPDATE sugarcrm.leads SET assigned_user_id='$assigned_user_id',modified_user_id='$processUserId',date_modified='$updateTime' WHERE id='$leadid'";
    $res=mysql_query($sql,$myDb_master) or die($sql.mysql_error($myDb_master));
}
else
{
    echo "Lead id is blank.Please try again.";
    die;
}
if($res)
{
    header("Location: $SITE_URL/sugarcrm/index.php?module=Leads&action=DetailView&record=$leadid&from_dialer=1");
    exit;
}
else
{
    echo "Lead id might be wrong.Please try again.";
    die;
}
?>

