<?php
class JPayment extends Membership
{
    
    public function get_nearest_branches($profileid) {
        $sql = "SELECT CITY_RES FROM newjs.JPROFILE WHERE PROFILEID = '$profileid'";
        $res = mysql_query_decide($sql) or die("$sql" . mysql_error_js());
        if ($row = mysql_fetch_array($res)) $near_branches = $this->getBranches($row['CITY_RES']);
        return $near_branches;
    }
    
    public function getStates() {
        $SQL = " SELECT DISTINCT STATE ,STATE_VAL FROM newjs.CONTACT_US ORDER BY STATE";
        $RESULT = mysql_query_decide($SQL) or die("$SQL" . mysql_error_js());
        $i = 0;
        while ($ROW = mysql_fetch_array($RESULT)) {
            $STATES[$i]['STATE'] = $ROW['STATE'];
            $STATES[$i]['STATE_VAL'] = $ROW['STATE_VAL'];
            $i++;
        }
        return $STATES;
    }

    public function getChangeBranches($city_value) {
        $sql = " SELECT CONTACT_PERSON,ADDRESS,PHONE,MOBILE,NAME,STATE FROM newjs.CONTACT_US WHERE STATE='" . $city_value . "'";
        $res = mysql_query_decide($sql) or die("$sql" . mysql_error_js());
        $i = 0;
        while ($row_address = mysql_fetch_array($res)) {
            $near_branches[$i]['CONTACT_PERSON'] = $row_address['CONTACT_PERSON'];
            $near_branches[$i]['ADDRESS'] = nl2br($row_address['ADDRESS']);
            $near_branches[$i]['PHONE'] = $row_address['PHONE'];
            $near_branches[$i]['MOBILE'] = $row_address['MOBILE'];
            $near_branches[$i]['NAME'] = $row_address['NAME'];
            $near_branches[$i]['STATE'] = $row_address['STATE'];
            $i++;
        }
        return $near_branches;
    }

    public function getChangeBranchesArr($cityArr) {
    	$cityStr = implode("','",$cityArr);
        $sql = " SELECT SQL_CACHE CONTACT_PERSON,ADDRESS,PHONE,MOBILE,NAME,STATE FROM newjs.CONTACT_US WHERE STATE IN ('$cityStr')";
        $res = mysql_query_decide($sql) or die("$sql" . mysql_error_js());
        $i = 0;
        while ($row_address = mysql_fetch_array($res)) {
            $near_branches[$i]['CONTACT_PERSON'] = $row_address['CONTACT_PERSON'];
            $near_branches[$i]['ADDRESS'] = nl2br($row_address['ADDRESS']);
            $near_branches[$i]['PHONE'] = $row_address['PHONE'];
            $near_branches[$i]['MOBILE'] = $row_address['MOBILE'];
            $near_branches[$i]['NAME'] = $row_address['NAME'];
            $near_branches[$i]['STATE'] = $row_address['STATE'];
            $i++;
        }
        return $near_branches;
    }
    
    public function getBranches($city_value) {
        
        $sql_address = " SELECT CONTACT_PERSON,ADDRESS,PHONE,MOBILE,NAME,STATE FROM newjs.CONTACT_US WHERE CITY_ID='" . $city_value . "'";
        
        $res_add = mysql_query_decide($sql_address) or die("$sql_address" . mysql_error_js());
        $i = 0;
        $row_address = mysql_fetch_array($res_add);
        if ($row_address == '') {
            $sql_address = " SELECT CONTACT_PERSON,ADDRESS,PHONE,MOBILE,NAME,STATE FROM newjs.CONTACT_US WHERE CITY_ID='UP25'";
        }
        $res_address = mysql_query_decide($sql_address) or die("$sql_address" . mysql_error_js());
        $i = 0;
        while ($row_address = mysql_fetch_array($res_address)) {
            $near_branches[$i]['CONTACT_PERSON'] = $row_address['CONTACT_PERSON'];
            $near_branches[$i]['ADDRESS'] = nl2br($row_address['ADDRESS']);
            $near_branches[$i]['PHONE'] = $row_address['PHONE'];
            $near_branches[$i]['MOBILE'] = $row_address['MOBILE'];
            $near_branches[$i]['NAME'] = $row_address['NAME'];
            $near_branches[$i]['STATE'] = $row_address['STATE'];
            $i++;
        }
        return $near_branches;
    }

    public function getBanks() {
        $sql = "SELECT NAME FROM billing.BANK";
        $res = mysql_query_decide($sql) or die(mysql_error_js());
        $i = 0;
        while ($row = mysql_fetch_array($res)) {
            $bank[$i] = $row['NAME'];
            $i++;
        }
        
        return $bank;
    }
    
    public function getCityRes($profileid) {
        
        $sql_order = "SELECT COUNTRY_RES,CITY_RES FROM newjs.JPROFILE WHERE PROFILEID = $profileid ";
        $result = mysql_query_decide($sql_order) or logError_sums($sql_order, 1);
        $row = mysql_fetch_assoc($result);
        if ($row["CITY_RES"] != '') return $row["CITY_RES"];
    }
    
    public function getNearBycities() {
        $sql_near = "SELECT LABEL,VALUE from incentive.BRANCH_CITY where PICKUP='Y' ";
        $result_near = mysql_query_decide($sql_near) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql, "ShowErrTemplate");
        while ($row_near = mysql_fetch_array($result_near)) {
            if ($row_near["VALUE"] != "GU") $near_ar[$row_near["VALUE"]] = $row_near["LABEL"];
        }
        return $near_ar;
    }
}
?>
