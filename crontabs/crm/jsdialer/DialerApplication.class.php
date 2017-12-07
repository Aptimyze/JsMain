<?php
include_once('DialerLog.class.php');
class DialerApplication {

    public function checkProfileInProcess($profileID,$inFP=true,$inRCB=true,$inRR=true){
        $db_master = mysql_connect(MysqlDbConstants::$master['HOST'],MysqlDbConstants::$master['USER'],MysqlDbConstants::$master['PASS']) or die("Unable to connect to nmit server ");

        if(!$profileID){
            return false;
        }

        if($inFP){
            $date = date("Y-m-d H:i:s",time()-11.5*60*60);
            $sql = "SELECT PROFILEID FROM billing.TRACKING_FAILED_PAYMENT WHERE ENTRY_DT >= '$date' AND PROFILEID = '$profileID'";
            $res=mysql_query($sql,$db_master) or die($sql.mysql_error($db_master));
            if($myrow = mysql_fetch_array($res)){
                return true;
            }
        }

        if($inRCB){
            $sql = "SELECT PROFILEID FROM incentive.SALES_CSV_DATA_RCB WHERE PROFILEID = '$profileID' AND DIAL_STATUS = '1'";
            $res=mysql_query($sql,$db_master) or die($sql.mysql_error($db_master));
            if($myrow = mysql_fetch_array($res)){
                return true;
            }
        }

        if($inRR){
            $date = date("Y-m-d H:i:s",time()-22.5*60*60);
            $sql = "SELECT USERNAME FROM incentive.LOGGING_CLIENT_INFO WHERE USERNAME = '$profileID' AND ENTRY_DT>='$date'";
            $res=mysql_query($sql,$db_master) or die($sql.mysql_error($db_master));
            if($myrow = mysql_fetch_array($res)){
                return true;
            }
        }
        return false;
    }
    // Fetch profiles
    public function getDeletedProfiles($profiles, $db_slave)
    {
        $profileArr =array();
	$profileStr =implode(",", $profiles);
        $sql= "SELECT PROFILEID FROM newjs.JPROFILE WHERE PROFILEID IN($profileStr) AND ACTIVATED='D'";
        $res=mysql_query($sql,$db_slave) or die($sql.mysql_error($db_slave));
        while($myrow = mysql_fetch_array($res)){
        	$profileArr[] = $myrow["PROFILEID"];
        }
	return $profileArr;
    }
}
?>
