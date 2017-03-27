<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
This file provides the duplicate entries. 
It is used for finding the duplicate entries for leads and jeevansathi records.
********************************************************************************/
require_once ('include/entryPoint.php');
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
include_once(JsConstants::$docRoot."/profile/connect_db.php");
class Duplicate {
    var $db;
    var $dbUpdate;
    var $partitionsArray = array('connected', 'inactive');
    //Constructor
    function __construct() {
        if (!$this->db) $this->db = & DBManagerFactory::getInstance();
	$this->dbUpdate = connect_db();
    }
    //Checks whether a lead/profile is duplicate
    //Function calls: isDuplicateProfile(), isDuplicateLead()
    //Output: true/false
    function isDuplicate($email = "", $mobile = "", $std = "", $phone = "", $leadId = "", $profileId = "") {
        if ($this->isDuplicateLead($email, $mobile, $std, $phone, $leadId)) return true;
        elseif ($this->isDuplicateProfile($email, $mobile, $std, $phone, $profileId)) return true;
        return false;
    }
    //Check whether a lead is duplicate in JS profile
    //Output: true/false
    function isDuplicateProfile($email = "", $mobile = "", $std = "", $phone = "", $profileId = "") {
        if (empty($email) && empty($mobile) && empty($phone) && !empty($profileId)) {
            $lead = $this->getProfileDetailInJeevansathi($profileId);
            $email = $lead["email"];
            $mobile = $lead["phone_mobile"];
            $phone = $lead["phone_home"];
        }
        if (!empty($email)) $duplicateEmail = $this->isDuplicateEmail($email);
        if (!empty($mobile) && !$duplicateEmail) $duplicateMobile = $this->isDuplicateMobile($mobile);
        if (!empty($phone) && !$duplicatePhone) $duplicatePhone = $this->isDuplicatePhone($std, $phone);
        if ($duplicateEmail || $duplicateMobile || $duplicatePhone) return true;
        return false;
    }
    //Check whether a lead is duplicate in Sugar
    //Output: true/false
    function isDuplicateLead($email = "", $mobile = "", $std = "", $phone = "", $leadId = "") {
        if (empty($email) && empty($mobile) && empty($phone) && !empty($leadId)) {
            $lead = $this->getLeadDetailInSugar($leadId);
            $email = $lead["email"];
            $mobile = $lead["phone_mobile"];
            $phone = $lead["phone_home"];
        }
        if (!empty($email)) $duplicateEmail = $this->isDuplicateEmail($email);
        if (!empty($mobile) && !$duplicateEmail) $duplicateMobile = $this->isDuplicateMobile($mobile);
        if (!empty($phone) && !$duplicatePhone) $duplicatePhone = $this->isDuplicatePhone($std, $phone);
        if ($duplicateEmail || $duplicateMobile || $duplicatePhone) return true;
        return false;
    }
    //Returns lead detail
    function getLeadDetailInSugar($leadId = "") {
        if (!empty($leadId)) {
            $query = "SELECT * FROM sugarcrm.leads WHERE id = '$leadId'";
            $rs = $this->db->query($query);
            $row = $this->db->fetchByAssoc($rs);
            if (empty($row['id'])) {
                foreach ($this->partitionsArray as $partition) {
                    $tableName = "sugarcrm_housekeeping." . $partition . "_leads";
                    $query = "SELECT * FROM $tableName WHERE id = '$leadId'";
                    $rs = $this->db->query($query);
                    $row = $this->db->fetchByAssoc($rs);
                    if (!empty($row['id'])) break;
                }
            }
            if (!empty($row['id'])) {
                $lead["phone_mobile"] = $row["phone_mobile"];
                $lead["phone_home"] = $row["phone_home"];
                $lead["email"] = $this->getLeadEmailInSugar($leadId);
                $customQuery = "SELECT * FROM sugarcrm.leads_cstm WHERE id_c='$leadId'";
                $customRes = $this->db->query($customQuery);
                $customRow = $this->db->fetchByAssoc($customRes);
                if (empty($customRow['id_c'])) {
                    foreach ($this->partitionsArray as $partition) {
                        $tableName = "sugarcrm_housekeeping." . $partition . "_leads_cstm";
                        $customQuery = "SELECT * FROM $tableName WHERE id_c='$leadId'";
                        $customRes = $this->db->query($customQuery);
                        $customRow = $this->db->fetchByAssoc($customRes);
                        if (!empty($customRow['id_c'])) break;
                    }
                }
                if (!empty($customRow['id_c'])) {
                    $lead['enquirer_landline_c'] = $customRow["enquirer_landline_c"];
                    $lead['enquirer_mobile_no_c'] = $customRow["enquirer_mobile_no_c"];
                    $lead['std_c'] = $customRow["std_c"];
                    $lead['std_enquirer_c'] = $customRow["std_enquirer_c"];
		    $lead['enquirer_email_id_c'] = $customRow["enquirer_email_id_c"];
                    $leadUserName = $customRow["jsprofileid_c"];
                    if ($leadUserName) {
                        $profileIdQuery = "SELECT PROFILEID FROM newjs.JPROFILE WHERE USERNAME IN ('$leadUserName')";
                        $profileIdRes = $this->db->query($profileIdQuery);
                        $profileIdRow = $this->db->fetchByAssoc($profileIdRes);
                        if (!empty($profileIdRow["PROFILEID"])) $lead["PROFILEID"] = $profileIdRow["PROFILEID"];
                    }
                }
            }
            return $lead;
        }
    }
    //Get profile detail
    function getProfileDetailInJeevansathi($profileId = "") {
        if (!empty($profileId)) {
            $query = "SELECT * FROM newjs.JPROFILE WHERE PROFILEID = '$profileId'";
            $rs = $this->db->query($query);
            $row = $this->db->fetchByAssoc($rs);
            if (!empty($row['PROFILEID'])) {
                $lead["phone_mobile"] = $row["PHONE_MOB"];
                $lead["phone_home"] = $row["PHONE_WITH_STD"];
                $lead["email"] = $row["EMAIL"];
            }
            return $lead;
        }
    }
    //Returns email id of the lead
    function getLeadEmailInSugar($leadId = "") {
        if (!empty($leadId)) {
            $result = $this->db->query("SELECT email_address FROM sugarcrm.email_addresses e,sugarcrm.email_addr_bean_rel b WHERE e.id = b.email_address_id and b.bean_id = '$leadId' and b.bean_module='Leads'");
            if ($row = $this->db->fetchByAssoc($result)) {
                $email = $row["email_address"];
            } else {
                foreach ($this->partitionsArray as $partition) {
                    $tableName1 = "sugarcrm_housekeeping." . $partition . "_email_addresses";
                    $tableName2 = "sugarcrm_housekeeping." . $partition . "_email_addr_bean_rel";
                    $result = $this->db->query("SELECT email_address FROM $tableName1 e,$tableName2 b WHERE e.id = b.email_address_id and b.bean_id = '$leadId' and b.bean_module='Leads'");
                    if ($row = $this->db->fetchByAssoc($result)) {
                        $email = $row["email_address"];
                        return $email;
                    }
                }
            }
            return $email;
        }
    }
    //Returns enquirer detail
    function getEnquirerDetail($leadId = "") {
        if (!empty($leadId)) {
            $enquirerDetail = array();
            if (is_array($leadId)) $leadIds = "'" . implode("','", $leadId) . "'";
            else $leadIds = "'" . $leadId . "'";
            $result = $this->db->query("SELECT id_c,  enquirer_mobile_no_c, isd_enquirer_c, std_enquirer_c, enquirer_landline_c, enquirer_email_id_c FROM sugarcrm.leads_cstm where id_c IN ($leadIds)");
            if ($this->db->getRowCount($result) == 0) {
                foreach ($this->partitionsArray as $partition) {
                    $tableName = "sugarcrm_housekeeping." . $partition . "_leads_cstm";
                    $result = $this->db->query("SELECT id_c,  enquirer_mobile_no_c, isd_enquirer_c, std_enquirer_c, enquirer_landline_c, enquirer_email_id_c FROM $tableName where id_c IN ($leadIds)");
                    if ($this->db->getRowCount) break;
                }
            }
            $rowCount = $this->db->getRowCount($result);
            if ($rowCount > 1) {
                while ($row = $this->db->fetchByAssoc($result)) {
                    $enquirerDetail["id_c"]["enquirer_email"] = $row["email_address"];
                    $enquirerDetail["id_c"]["enquirer_std"] = $row["std_enquirer_c"];
                    $enquirerDetail["id_c"]["enquirer_isd"] = $row["isd_enquirer_c"];
                    $enquirerDetail["id_c"]["enquirer_mobile"] = $row["enquirer_mobile_no_c"];
                    $enquirerDetail["id_c"]["enquirer_landline"] = $row["enquirer_landline_c"];
                }
            } elseif ($rowCount == 1) {
                $enquirerDetail["enquirer_email"] = $row["email_address"];
                $enquirerDetail["enquirer_std"] = $row["std_enquirer_c"];
                $enquirerDetail["enquirer_isd"] = $row["isd_enquirer_c"];
                $enquirerDetail["enquirer_mobile"] = $row["enquirer_mobile_no_c"];
                $enquirerDetail["enquirer_landline"] = $row["enquirer_landline_c"];
            }
            return $enquirerDetail;
        }
        return false;
    }
    //Returns mapped profile/lead detail checks input type, if array, returns detail in array
    //Function calls: getDuplicateLeadDetail(), getDuplicateProfileDetail()
    function getDuplicateDetail($email = "", $mobile = "", $phone = "", $leadId = "", $profileId = "") {
        $duplicateLead = $this->getDuplicateLead($email, $mobile, $phone, $leadId);
        $duplicateProfile = $this->getDuplicateProfile($email, $mobile, $phone, $profileId);
        if ($duplicateLead || $duplicateProfile) {
            $duplicate = array("lead" => $duplicateLead, "profile" => $duplicateProfile);
            return $duplicate;
        }
        return false;
    }
    //Input: array($email, $mobile, $phone, $leadId)
    //Returns mapped profiles details
    function getDuplicateProfileArr($leadArr) {
        return;
    }
    /*Input: array($email, $mobile, $phone, $leadId)
    //Returns mapped leads details
    function getDuplicateLeadArr($leadArr){
                if(!empty($leadId))
                {
                        $result = $this->db->query("SELECT distinct email_address FROM email_addresses e,email_addr_bean_rel b WHERE e.id = b.email_address_id AND b.deleted = 0 AND e.deleted = 0");
                        if($row = $this->db->fetchByAssoc($result)) {
                                $email = $row["email_address"];
                        }
                        return $email;
                }
    return ;
    }
    */
    /*Returns mapped email ids along with leadId/profileId
    function getDuplicateEmailArr()
    {
    $result = $this->db->query("SELECT count(id) cnt, email_address_id FROM `email_addr_bean_rel` GROUP BY email_address_id having cnt>1");
    while($row = $this->db->fetchByAssoc($result)) {
    $emailArr = $row["email_address"];
    }
    return $emailArr;
    }
    
    function getDuplicateLeadByEmail($emailArr)
    {
    }
    */
    //Returns mapped profile detail
    function getDuplicateProfile($email = "", $mobile = "", $phone = "", $profileId = "") {
        if (empty($email) && empty($mobile) && empty($phone) && !empty($profileId)) {
            $lead = $this->getProfileDetailInJeevansathi($profileId);
            $email = $lead["email"];
            $mobile = $lead["phone_mobile"];
            $phone = $lead["phone_home"];
        }
        if (!empty($email)) $duplicateEmail = $this->getDuplicateEmailInJeevansathi($email);
        if (!empty($mobile) && !$duplicateEmail) $duplicateMobile = $this->getDuplicateMobileInJeevansathi($mobile);
        if (!empty($phone) && !$duplicatePhone) $duplicatePhone = $this->getDuplicatePhoneInJeevansathi($phone);
        if ($duplicateEmail || $duplicateMobile || $duplicatePhone) {
            $duplicate = array("email" => $duplicateEmail, "mobile" => $duplicateMobile, "phone" => $duplicatePhone);
            return $duplicate;
        }
        return false;
    }
    //Returns mapped lead detail
    function getDuplicateLead($email = "", $mobile = "", $phone = "", $leadId = "") {
        if (empty($email) && empty($mobile) && empty($phone) && !empty($leadId)) {
            $lead = $this->getLeadDetailInSugar($leadId);
            $email = $lead["email"];
            $mobile = $lead["phone_mobile"];
            $phone = $lead["phone_home"];
        }
        if (!empty($email)) $duplicateEmail = $this->getDuplicateEmailInSugar($email);
        if (!empty($mobile)) $duplicateMobile = $this->getDuplicateMobileInSugar($mobile);
        if (!empty($phone)) $duplicatePhone = $this->getDuplicatePhoneInSugar($phone);
        if ($duplicateEmail || $duplicateMobile || $duplicatePhone) {
            $duplicate = array("email" => $duplicateEmail, "mobile" => $duplicateMobile, "phone" => $duplicatePhone);
            return $duplicate;
        }
        return false;
    }
    //Returns array having duplicate profile/lead detail
    //Function calls: getDuplicateDetail()
    function filterDuplicate($leadArr) {
        return;
    }
    //Checks duplicate mobile number in sugar
    function isDuplicateMobileInSugar($mobile = "", $ignoreList = "") {
        if (!empty($mobile)) {
            if ((is_array($ignoreList) && count($ignoreList)) || $ignoreList) $ignoreListComma = $this->getIgnoreListString($ignoreList);
            $query = "SELECT id FROM sugarcrm.leads WHERE phone_mobile = '$mobile'";
            if ($ignoreListComma) $query.= " and id NOT IN ($ignoreListComma)";
            $rs = $this->db->query($query);
            $row = $this->db->fetchByAssoc($rs);
            if (empty($row['id'])) {
                $query = "SELECT id_c FROM sugarcrm.leads_cstm WHERE enquirer_mobile_no_c = '$mobile'";
                if ($ignoreListComma) $query.= " and id_c NOT IN ($ignoreListComma)";
                $rs = $this->db->query($query);
                $row = $this->db->fetchByAssoc($rs);
                if (!empty($row["id_c"]))
		{
			$this->updateLeadSeriousnessCount($row['id_c'],'active');
			return true;
		}
                foreach ($this->partitionsArray as $partition) {
                    $tableName = "sugarcrm_housekeeping." . $partition . "_leads";
                    $query = "SELECT id FROM $tableName WHERE phone_mobile = '$mobile'";
                    if ($ignoreListComma) $query.= " and id NOT IN ($ignoreListComma)";
                    $rs = $this->db->query($query);
                    $row = $this->db->fetchByAssoc($rs);
                    if (!empty($row['id']))
		    {
			$this->updateLeadSeriousnessCount($row['id'],$partition);
			return true;
		    }
                    $tableName = "sugarcrm_housekeeping." . $partition . "_leads_cstm";
                    $query = "SELECT id_c FROM $tableName WHERE enquirer_mobile_no_c = '$mobile'";
                    if ($ignoreListComma) $query.= " and id_c NOT IN ($ignoreListComma)";
                    $rs = $this->db->query($query);
                    $row = $this->db->fetchByAssoc($rs);
                    if (!empty($row['id_c']))
		    {
			$this->updateLeadSeriousnessCount($row['id_c'],$partition);
			return true;
		    }
                }
            } else
		{
			$this->updateLeadSeriousnessCount($row['id'],'active'); 
			return true;
		}
        }
        return false;
    }
    //Checks duplicate mobile number in Jeevansathi
    function isDuplicateMobileInJeevansathi($mobile = "", $ignoreProfile = "") {
        if (!empty($mobile)) {
            if ((is_array($ignoreProfile) && count($ignoreProfile)) || $ignoreProfile) $ignoreProfileList = $this->getIgnoreListString($ignoreProfile);
            $query = "SELECT PROFILEID FROM newjs.JPROFILE WHERE PHONE_MOB = '$mobile'";
            if ($ignoreProfileList) $query.= " AND PROFILEID NOT IN ($ignoreProfileList)";
            $result2 = $this->db->query($query);
	    if($this->db->getRowCount($result2))
	    {
	    	while($row2=$this->db->fetchByAssoc($result2))
			$profileArr[]=$row2["PROFILEID"];
		$this->updateProfileSeriousnessCount($profileArr);
            	return true;
	    }
        }
        return false;
    }
    //Globally checks duplicate mobile
    function isDuplicateMobile($mobile = "", $ignoreList = "", $ignoreProfile = "") {
        if ($this->isDuplicateMobileInSugar($mobile, $ignoreList)) return true;
        elseif ($this->isDuplicateMobileInJeevansathi($mobile, $ignoreProfile)) return true;
        return false;
    }
    //Checks duplicate phone number in sugar
    function isDuplicatePhoneInSugar($std = "", $phone = "", $ignoreList = "") {
        if (!empty($phone)) {
            if ((is_array($ignoreList) && count($ignoreList)) || $ignoreList) $ignoreListComma = $this->getIgnoreListString($ignoreList);
            $query = "SELECT id FROM sugarcrm.leads join sugarcrm.leads_cstm ON leads.id=leads_cstm.id_c WHERE phone_home = '$phone'";
            if ($std) {
                $std_without_zero = ltrim($std, "0");
                if ($std_without_zero) {
                    $std = "0" . $std_without_zero;
                    $query.= " and std_c IN ('$std','$std_without_zero')";
                }
            }
            if ($ignoreListComma) $query.= " and id NOT IN ($ignoreListComma)";
            $rs = $this->db->query($query);
            $row = $this->db->fetchByAssoc($rs);
            if (empty($row['id'])) {
                $query = "SELECT id_c FROM sugarcrm.leads_cstm WHERE enquirer_landline_c = '$phone'";
                if ($std_without_zero) $query.= " and std_enquirer_c IN ('$std','$std_without_zero')";
                if ($ignoreListComma) $query.= " and id_c NOT IN ($ignoreListComma)";
                $rs = $this->db->query($query);
                $row = $this->db->fetchByAssoc($rs);
                if (!empty($row["id_c"]))
		{
			$this->updateLeadSeriousnessCount($row['id_c'],'active');
			return true;
		}
                foreach ($this->partitionsArray as $partition) {
                    $tableName1 = "sugarcrm_housekeeping." . $partition . "_leads";
                    $tableName2 = "sugarcrm_housekeeping." . $partition . "_leads_cstm";
                    $query = "SELECT id FROM $tableName1 join $tableName2 ON $tableName1.id=$tableName2.id_c WHERE phone_home = '$phone'";
                    if ($std_without_zero) $query.= " and std_c IN ('$std','$std_without_zero')";
                    if ($ignoreListComma) $query.= " and id NOT IN ($ignoreListComma)";
                    $rs = $this->db->query($query);
                    $row = $this->db->fetchByAssoc($rs);
                    if (!empty($row['id']))
			{
				$this->updateLeadSeriousnessCount($row['id'],$partition);
				return true;
			}
                    $query = "SELECT id_c FROM $tableName2 WHERE enquirer_landline_c = '$phone'";
                    if ($std_without_zero) $query.= " and std_enquirer_c IN ('$std','$std_without_zero')";
                    if ($ignoreListComma) $query.= " and id_c NOT IN ($ignoreListComma)";
                    $rs = $this->db->query($query);
                    $row = $this->db->fetchByAssoc($rs);
                    if (!empty($row["id_c"])) 
			{
				$this->updateLeadSeriousnessCount($row['id_c'],$partition);
				return true;
			}
                }
            } else
		{
			$this->updateLeadSeriousnessCount($row['id'],'active');
			return true;
		}
        }
        return false;
    }
    //Checks duplicate phone number in Jeevansathi
    function isDuplicatePhoneInJeevansathi($std = "", $phone = "", $ignoreProfile = "") {
        if (!empty($phone)) {
            if ((is_array($ignoreProfile) && count($ignoreProfile)) || $ignoreProfile) $ignoreProfileList = $this->getIgnoreListString($ignoreProfile);
            $query = "SELECT PROFILEID FROM newjs.JPROFILE";
            $std_without_zero = ltrim($std, "0");
            if ($std_without_zero) {
                $std = "0" . $std_without_zero;
                $query.= " WHERE PHONE_WITH_STD IN ('" . $std . $phone . "','" . $std_without_zero . $phone . "')";
            } else $query.= " WHERE PHONE_WITH_STD='$phone'";
            if ($ignoreProfileList) $query.= " AND PROFILEID NOT IN($ignoreProfileList)";
            $result2 = $this->db->query($query);
	    if($this->db->getRowCount($result2))
            {
                while($row2=$this->db->fetchByAssoc($result2))
                        $profileArr[]=$row2["PROFILEID"];
                $this->updateProfileSeriousnessCount($profileArr);
                return true;
            }
        }
        return false;
    }
    //Globally checks duplicate phone
    function isDuplicatePhone($std = "", $phone = "", $ignoreList = "", $ignoreProfile = "") {
        if ($this->isDuplicatePhoneInSugar($std, $phone, $ignoreList)) return true;
        elseif ($this->isDuplicatePhoneInJeevansathi($std, $phone, $ignoreProfile)) return true;
        return false;
    }
    //Checks duplicate email in sugar
    function isDuplicateEmailInSugar($email = "", $ignoreList = "") {
        if (!empty($email)) {
            $emailCaps = $this->db->quote(strtoupper(trim($email)));
            $email = $this->db->quote(trim($email));
            if ((is_array($ignoreList) && count($ignoreList)) || $ignoreList) $ignoreListComma = $this->getIgnoreListString($ignoreList);
            $sql = "SELECT b.bean_id FROM sugarcrm.email_addresses a JOIN sugarcrm.email_addr_bean_rel b ON a.id=b.email_address_id WHERE bean_module='Leads' AND email_address_caps='$emailCaps'";
            if ($ignoreListComma) $sql.= " AND b.bean_id NOT IN ($ignoreListComma)";
            $result = $this->db->query($sql);
            $row = $this->db->fetchByAssoc($result);
            if (empty($row['bean_id'])) {
                $sql = "SELECT id_c from sugarcrm.leads_cstm WHERE enquirer_email_id_c='$email'";
                if ($ignoreListComma) $sql.= " AND id_c NOT IN($ignoreListComma)";
                $result = $this->db->query($sql);
                $row = $this->db->fetchByAssoc($result);
                if (!empty($row['id_c']))
		{
			$this->updateLeadSeriousnessCount($row['id_c'],'active');
                	return true;
		}
                foreach ($this->partitionsArray as $partition) {
                    $tableName1 = "sugarcrm_housekeeping." . $partition . "_email_addresses";
                    $tableName2 = "sugarcrm_housekeeping." . $partition . "_email_addr_bean_rel";
                    $sql = "SELECT b.bean_id FROM $tableName1 a JOIN $tableName2 b ON a.id=b.email_address_id WHERE bean_module='Leads' AND email_address_caps='$emailCaps'";
                    if ($ignoreListComma) $sql.= " AND b.bean_id NOT IN ($ignoreListComma)";
                    $result = $this->db->query($sql);
                    $row = $this->db->fetchByAssoc($result);
                    if (!empty($row['bean_id'])) 
			{
                                $this->updateLeadSeriousnessCount($row['bean_id'],$partition);
				return true;
			}
                    $tableName = "sugarcrm_housekeeping." . $partition . "_leads_cstm";
                    $sql = "SELECT id_c from $tableName WHERE enquirer_email_id_c='$email'";
                    if ($ignoreListComma) $sql.= " AND id_c NOT IN($ignoreListComma)";
                    $result = $this->db->query($sql);
                    $row = $this->db->fetchByAssoc($result);
                    if (!empty($row['id_c']))
			{
                                $this->updateLeadSeriousnessCount($row['id_c'],$partition);
                                return true;
			}
                }
            } else
		{
			$this->updateLeadSeriousnessCount($row['bean_id'],'active');
			return true;
		}
        }
        return false;
    }
    //Checks duplicate email in Jeevansathi database
    function isDuplicateEmailInJeevansathi($email = "", $ignoreProfile = "") {
        if (!empty($email)) {
            $email = strtolower($email);
            if ((is_array($ignoreProfile) && count($ignoreProfile)) || $ignoreProfile) $ignoreProfileList = $this->getIgnoreListString($ignoreProfile);
            $query = "SELECT PROFILEID FROM newjs.JPROFILE WHERE EMAIL = '$email'";
            if ($ignoreProfileList) $query.= " AND PROFILEID NOT IN($ignoreProfileList)";
            $result2 = $this->db->query($query);
	    if($this->db->getRowCount($result2))
            {
                while($row2=$this->db->fetchByAssoc($result2))
                        $profileArr[]=$row2["PROFILEID"];
                $this->updateProfileSeriousnessCount($profileArr);
                return true;
            }
        }
        return false;
    }
    //Globally checks duplicate email
    function isDuplicateEmail($email = "", $ignoreList = "", $ignoreProfile = "") {
        if ($this->isDuplicateEmailInSugar($email, $ignoreList)) return true;
        elseif ($this->isDuplicateEmailInJeevansathi($email, $ignoreProfile)) return true;
        return false;
    }
    //Get duplicate mobile number in Sugar
    function getDuplicateMobileInSugar($mobile = "", $ignoreList = "") {
        if (!empty($mobile)) {
            if (is_array($ignoreList)) $ignoreListComma = "'" . implode("','", $ignoreList) . "'";
            elseif ($ignoreList) $ignoreListComma = "'" . $ignoreList . "'";
            $query = "SELECT id,phone_mobile,phone_home FROM sugarcrm.leads WHERE phone_mobile = '$mobile'";
            if ($ignoreListComma) $query.= " and id NOT IN ($ignoreListComma)";
            $rs = $this->db->query($query);
            if ($this->db->getRowCount($rs) == 0) {
                foreach ($this->partitionsArray as $partition) {
                    $tableName = "sugarcrm_housekeeping." . $partition . "_leads";
                    $query = "SELECT id,phone_mobile,phone_home from $tableName WHERE phone_mobile='$mobile'";
                    if ($ignoreListComma) $query.= " AND id NOT IN($ingoreListComma)";
                    $rs = $this->db->query($query);
                    if ($this->db->getRowCount($rs)) break;
                }
            }
            if ($this->db->getRowCount($rs)) {
                while ($row = $this->db->fetchByAssoc($rs)) {
                    $phone[$row['id']]["phone_mobile"] = $row['phone_mobile'];
                    $phone[$row['id']]["phone_home"] = $row['phone_home'];
                    $phone[$row['id']]["email"] = $this->getLeadEmailInSugar($row["id"]);
                    $phone[$row['id']]["leadId"] = $row["id"];
	            $this->updateLeadSeriousnessCount($row["id"]);
                }
            }
            $query = "SELECT id_c,enquirer_mobile_no_c,enquirer_landline_c,enquirer_email_id_c FROM sugarcrm.leads_cstm WHERE enquirer_mobile_no_c = '$mobile'";
            if ($ignoreListComma) $query.= " and id_c NOT IN ($ignoreListComma)";
            $rs = $this->db->query($query);
            if ($this->db->getRowCount($rs) == 0) {
                foreach ($this->partitionsArray as $partition) {
                    $tableName = "sugarcrm_housekeeping." . $partition . "_leads_cstm";
                    $query = "SELECT id_c,enquirer_mobile_no_c,enquirer_landline_c,enquirer_email_id_c FROM $tableName WHERE enquirer_mobile_no_c='$mobile'";
                    $rs = $this->db->query($query);
                    if ($this->db->getRowCount($rs)) break;
                }
            }
            if ($this->db->getRowCount($rs)) {
                while ($row = $this->db->fetchByAssoc($rs)) {
                    $phone[$row['id_c']]["enquirer_mobile"] = $row['enquirer_mobile_no_c'];
                    $phone[$row['id_c']]["enquirer_phone"] = $row['enquirer_landline_c'];
                    $phone[$row['id_c']]["enquirer_email"] = $row['enquirer_email_id_c'];
                    $phone[$row['id_c']]["enquirer_leadId"] = $row["id_c"];
		    $this->updateLeadSeriousnessCount($row["id_c"]);
                }
            }
            return $phone;
        }
        return false;
    }
    //Get duplicate phone number in Sugar
    function getDuplicatePhoneInSugar($phone = "", $ignoreList = "") {
        if (!empty($phone)) {
            if (is_array($ignoreList)) $ignoreListComma = "'" . implode("','", $ignoreList) . "'";
            elseif ($ignoreList) $ignoreListComma = "'" . $ignoreList . "'";
            $query = "SELECT id,phone_mobile,phone_home FROM sugarcrm.leads WHERE phone_home = '$phone'";
            if ($ignoreListComma) $query.= " and id NOT IN ($ignoreListComma)";
            $rs = $this->db->query($query);
            if ($this->db->getRowCount($rs) == 0) {
                foreach ($this->partitionsArray as $partition) {
                    $tableName = "sugarcrm_housekeeping." . $partition . "_leads";
                    $query = "SELECT id,phone_mobile,phone_home FROM $tableName WHERE phone_home = '$phone'";
                    if ($ignoreListComma) $query.= " and id NOT IN ($ignoreListComma)";
                    $rs = $this->db->query($query);
                    if ($this->db->getRowCount($rs)) break;
                }
            }
            if ($this->db->getRowCount($rs)) {
                while ($row = $this->db->fetchByAssoc($rs)) {
                    $phone[$row['id']]["phone_mobile"] = $row['phone_mobile'];
                    $phone[$row['id']]["phone_home"] = $row['phone_home'];
                    $phone[$row['id']]["email"] = $this->getLeadEmailInSugar($row["id"]);
                    $phone[$row['id']]["leadId"] = $row["id"];
		    $this->updateLeadSeriousnessCount($row["id"]);
                }
            }
            $query = "SELECT id_c,enquirer_mobile_no_c,enquirer_landline_c,enquirer_email_id_c FROM sugarcrm.leads_cstm WHERE enquirer_landline_c = '$phone'";
            if ($ignoreListComma) $query.= " and id_c NOT IN ($ignoreListComma)";
            $rs = $this->db->query($query);
            if ($this->db->getRowCount($rs) == 0) {
                foreach ($this->partitionsArray as $partition) {
                    $tableName = "sugarcrm_housekeeping." . $partition . "_leads_cstm";
                    $query = "SELECT id_c,enquirer_mobile_no_c,enquirer_landline_c,enquirer_email_id_c FROM $tableName WHERE enquirer_landline_c = '$phone'";
                    if ($ignoreListComma) $query.= " and id_c NOT IN ($ignoreListComma)";
                    $rs = $this->db->query($query);
                    if ($this->db->getRowCount($rs)) break;
                }
            }
            if ($this->db->getRowCount($rs)) {
                while ($row = $this->db->fetchByAssoc($rs)) {
                    $phone[$row['id']]["enquirer_mobile"] = $row['enquirer_mobile_no_c'];
                    $phone[$row['id']]["enquirer_phone"] = $row['enquirer_landline_c'];
                    $phone[$row['id']]["enquirer_email"] = $row['enquirer_email_id_c'];
                    $phone[$row['id']]["enquirer_leadId"] = $row["id_c"];
		    $this->updateLeadSeriousnessCount($row["id_c"]);
                }
            }
            return $phone;
        }
        return false;
    }
    //Get duplicate mobile number in Jeevansathi
    function getDuplicateMobileInJeevansathi($mobile = "") {
        if (!empty($mobile)) {
            $result2 = $this->db->query("SELECT PHONE_MOB, PHONE_WITH_STD, EMAIL, PROFILEID FROM newjs.JPROFILE WHERE PHONE_MOB = '$mobile'");
	    if($this->db->getRowCount($result2))
            {
		    while ($row2 = $this->db->fetchByAssoc($result2)) {
			$phone[$row2['PROFILEID']]["phone_mobile"] = $row2['PHONE_MOB'];
			$phone[$row2['PROFILEID']]["phone_home"] = $row2['PHONE_WITH_STD'];
			$phone[$row2['PROFILEID']]["email"] = $row2['EMAIL'];
			$phone[$row2['PROFILEID']]["profileId"] = $row2['PROFILEID'];
                        $profileArr[]=$row2["PROFILEID"];
			}
			$this->updateProfileSeriousnessCount($profileArr);
			return $phone;
            }
        }
        return false;
    }
    //Get duplicate phone number in Jeevansathi
    function getDuplicatePhoneInJeevansathi($phone = "") {
        if (!empty($phone)) {
            $result2 = $this->db->query("SELECT PHONE_MOB, PHONE_WITH_STD, EMAIL, PROFILEID FROM newjs.JPROFILE WHERE PHONE_WITH_STD = '$phone'");
	    if($this->db->getRowCount($result2))
            {
	
        	    while ($row2 = $this->db->fetchByAssoc($result2)) {
                	$phone[$row2['PROFILEID']]["phone_mobile"] = $row2['PHONE_MOB'];
	                $phone[$row2['PROFILEID']]["phone_home"] = $row2['PHONE_WITH_STD'];
        	        $phone[$row2['PROFILEID']]["email"] = $row2['EMAIL'];
                	$phone[$row2['PROFILEID']]["profileId"] = $row2['PROFILEID'];
			$profileArr[]=$row2["PROFILEID"];
            	    }
		    $this->updateProfileSeriousnessCount($profileArr);
	            return $phone;
	    }
        }
        return false;
    }
    //Get duplicate mobile number present in Sugar and Jeevansathi
    function getDuplicateMobile($mobile = "", $ignoreList = "") {
        $mobInSugar = $this->getDuplicateMobileInSugar($mobile, $ignoreList);
        $mobInJeevansathi = $this->getDuplicateMobileInJeevansathi($mobile);
        $phone = array("lead" => $mobInSugar, "profile" => $mobInJeevansathi);
        return $phone;
    }
    //Get duplicate email
    function getDuplicateEmailInSugar($email = "", $ignoreList = "") {
        if (!empty($email)) {
            $leadsTable = '';
            if (is_array($ignoreList)) $ignoreListComma = "'" . implode("','", $ignoreList) . "'";
            elseif ($ignoreList) $ignoreListComma = "'" . $ignoreList . "'";
            $emailCaps = $this->db->quote(strtoupper(trim($email)));
            $email = $this->db->quote(trim($email));
            $query = "SELECT b.bean_id FROM sugarcrm.email_addresses a,sugarcrm.email_addr_bean_rel b WHERE a.id=b.email_address_id AND email_address_caps='$emailCaps' AND bean_module='Leads'";
            if ($ignoreListComma) $query.= " AND bean_id NOT IN($ignoreListComma)";
            $result = $this->db->query($query);
            if ($this->db->getRowCount($result) == 0) {
                foreach ($this->partitionsArray as $partition) {
                    $tableName1 = "sugarcrm_housekeeping." . $partition . "_email_addresses";
                    $tableName2 = "sugarcrm_housekeeping." . $partition . "_email_addr_bean_rel";
                    $query = "SELECT b.bean_id FROM $tableName1 a,$tableName2 b WHERE a.id=b.email_address_id AND email_address_caps='$emailCaps' AND bean_module='Leads'";
                    if ($ignoreListComma) $query.= " AND bean_id NOT IN($ignoreListComma)";
                    $result = $this->db->query($query);
                    if ($this->db->getRowCount($result)) {
                        $leadsTable = "sugarcrm_housekeeping." . $partition . "_leads";
                        break;
                    }
                }
            } else $leadsTable = "sugarcrm.leads";
            if ($row = $this->db->fetchByAssoc($result)) {
                $bean_id = $row['bean_id'];
		$this->updateLeadSeriousnessCount($bean_id);
                if ($leadsTable) {
                    $query = "SELECT phone_mobile,phone_home FROM $leadsTable WHERE id = '$bean_id'";
                    $rs = $this->db->query($query);
                    $row2 = $this->db->fetchByAssoc($rs);
                    if (!empty($row2['id'])) {
                        $lead[$bean_id]["phone_mobile"] = $row2["phone_mobile"];
                        $lead[$bean_id]["phone_home"] = $row2["phone_home"];
                        $lead[$bean_id]["email"] = $email;
                        $lead[$bean_id]["leadId"] = $bean_id;
                    }
                    return $lead;
                }
            }
        }
        return false;
    }
    //Get duplicate email number in Jeevansathi
    function getDuplicateEmailInJeevansathi($email = "") {
        if (!empty($email)) {
            $result2 = $this->db->query("SELECT PHONE_MOB, PHONE_WITH_STD, EMAIL, PROFILEID FROM newjs.JPROFILE WHERE EMAIL = '$email'");
	    if($this->db->getRowCount($result2))
            {
        	    while ($row2 = $this->db->fetchByAssoc($result2)) {
			$emailDeatil[$row2['PROFILEID']]["phone_mobile"] = $row2['PHONE_MOB'];
			$emailDetail[$row2['PROFILEID']]["phone_home"] = $row2['PHONE_WITH_STD'];
			$emailDetail[$row2['PROFILEID']]["email"] = $row2['EMAIL'];
			$emailDetail[$row2['PROFILEID']]["profileId"] = $row2['PROFILEID'];
			$profileArr[]=$row2["PROFILEID"];
                        }
                        $this->updateProfileSeriousnessCount($profileArr);
			return $emailDetail;
            }
        }
        return false;
    }
    //Get duplicate email present in Sugar and Jeevansathi
    function getDuplicateEmail($email = "", $ignoreList = "") {
        $emailInSugar = $this->getDuplicateEmailInSugar($email);
        $emailInJeevansathi = $this->getDuplicateEmailInJeevansathi($email);
        $emailDetail = array("lead" => $emailInSugar, "profile" => $emailInJeevansathi);
        return $emailDetail;
    }
    /*Returns mapped lead detail
        function getDuplicateLead($email="", $mobile="", $phone=""){
                if(!empty($email))
                        $duplicateEmail = $this->getDuplicateEmailInSugar($email);
                if(!empty($mobile) && !$duplicateEmail)
                        $duplicateMobile = $this->getDuplicateMobileInSugar($mobile);
                if(!empty($phone) && !$duplicatePhone)
                        $duplicatePhone = $this->getDuplicatePhoneInSugar($phone);
                if($duplicateEmail || $duplicateMobile || $duplicatePhone)
                {
                        $duplicate = array("email"=>$duplicateEmail,"mobile"=>$duplicateMobile,"phone"=>$duplicatePhone);
                        return $duplicate;
                }
                return false;
        }*/
    function getDuplicateLeadId($email = "", $mobile = "", $std = "", $phone = "") {
        $email = trim($email);
        $mobile = trim($mobile);
        $phone = trim($phone);
        if (!empty($email)) {
            $emailCaps = $this->db->quote(strtoupper(trim($email)));
            $emailCaps = $this->db->quote(trim($email));
            $query = "SELECT b.bean_id FROM sugarcrm.email_addresses a,sugarcrm.email_addr_bean_rel b WHERE a.id=b.email_address_id AND email_address_caps='$emailCaps' AND bean_module='Leads'";
            $result = $this->db->query($query);
            while ($row = $this->db->fetchByAssoc($result))
		{
			 $this->updateLeadSeriousnessCount($row["bean_id"],'active');
			 $leadId[] = $row["bean_id"];
		}
                foreach ($this->partitionsArray as $partition) {
                    $tableName1 = "sugarcrm_housekeeping." . $partition . "_email_addresses";
                    $tableName2 = "sugarcrm_housekeeping." . $partition . "_email_addr_bean_rel";
                    $query = "SELECT b.bean_id FROM $tableName1 a,$tableName2 b WHERE a.id=b.email_address_id AND email_address_caps='$emailCaps' AND bean_module='Leads'";
                    $result = $this->db->query($query);
                    while ($row = $this->db->fetchByAssoc($result))
			{
				$this->updateLeadSeriousnessCount($row["bean_id"],$partition);
				$leadId[] = $row["bean_id"];
			}
                }
			$query = "SELECT id_c from sugarcrm.leads_cstm where enquirer_email_id_c='$email'";
			$result = $this->db->query($query);
            while ($row = $this->db->fetchByAssoc($result))
		{
			$this->updateLeadSeriousnessCount($row["id_c"],'active');
			$leadId[] = $row["id_c"];
		}
        }
        if (!empty($mobile)) {
            $query = "SELECT id FROM sugarcrm.leads WHERE phone_mobile = '$mobile'";
            $rs = $this->db->query($query);
            while ($row = $this->db->fetchByAssoc($rs))
		{
			$this->updateLeadSeriousnessCount($row["id"],'active');
			$leadId[] = $row["id"];
		}
                foreach ($this->partitionsArray as $partition) {
                    $tableName = "sugarcrm_housekeeping." . $partition . "_leads";
                    $query = "SELECT id FROM $tableName WHERE phone_mobile='$mobile'";
                    $rs = $this->db->query($query);
		    while ($row = $this->db->fetchByAssoc($rs))
			{ 
				$this->updateLeadSeriousnessCount($row["id"],$partition);
				$leadId[] = $row["id"];
			}
                }
            $query = "SELECT id_c FROM sugarcrm.leads_cstm WHERE enquirer_mobile_no_c = '$mobile'";
            $rs = $this->db->query($query);
            while ($row = $this->db->fetchByAssoc($rs))
		{
		 $this->updateLeadSeriousnessCount($row["id_c"],'active');
		 $leadId[] = $row["id_c"];
		}
            foreach ($this->partitionsArray as $partition) {
                $tableName = "sugarcrm_housekeeping." . $partition . "_leads_cstm";
                $query = "SELECT id_c FROM $tableName WHERE enquirer_mobile_no_c='$mobile'";
                $rs = $this->db->query($query);
				$row = $this->db->fetchByAssoc($rs);
			   	while ($row = $this->db->fetchByAssoc($rs))
				{
					 $this->updateLeadSeriousnessCount($row["id_c"],$partition);
					 $leadId[] = $row["id_c"];
				}
            }
        }
        if (!empty($phone)) {
            $query = "SELECT id FROM sugarcrm.leads join sugarcrm.leads_cstm ON leads.id=leads_cstm.id_c WHERE phone_home = '$phone'";
            if ($std) {
                $std_without_zero = ltrim($std, "0");
                if ($std_without_zero) {
                    $std = "0" . $std_without_zero;
                    $query.= " and std_c IN ('$std','$std_without_zero')";
                }
            }
            $rs = $this->db->query($query);
            while ($row = $this->db->fetchByAssoc($rs))
		{
			$this->updateLeadSeriousnessCount($row["id"],'active');	
			$leadId[] = $row["id"];
		}
            foreach ($this->partitionsArray as $partition) {
                $tableName1 = "sugarcrm_housekeeping." . $partition . "_leads";
                $tableName2 = "sugarcrm_housekeeping." . $partition . "_leads_cstm";
                $query = "SELECT id FROM $tableName1 join $tableName2 ON $tableName1.id=$tableName2.id_c WHERE phone_home = '$phone'";
                if ($std_without_zero) $query.= " and std_c IN ('$std','$std_without_zero')";
                $rs = $this->db->query($query);
                while ($row = $this->db->fetchByAssoc($rs))
		{
			 $this->updateLeadSeriousnessCount($row["id"],$partition);
			 $leadId[] = $row["id"];
		}
            }
            $query = "SELECT id_c FROM sugarcrm.leads_cstm WHERE enquirer_landline_c = '$phone'";
            if ($std_without_zero) $query.= " AND std_enquirer_c IN('$std','$std_without_zero')";
            $rs = $this->db->query($query);
            while ($row = $this->db->fetchByAssoc($rs))
		{
			 $this->updateLeadSeriousnessCount($row["id_c"],'active');
			 $leadId[] = $row["id_c"];
		}
            foreach ($this->partitionsArray as $partition) {
                $tableName = "sugarcrm_housekeeping." . $partition . "_leads_cstm";
                $query = "SELECT id_c FROM $tableName WHERE enquirer_landline_c = '$phone'";
                if ($std_without_zero) $query.= " and std_c IN ('$std','$std_without_zero')";
                $rs = $this->db->query($query);
                while ($row = $this->db->fetchByAssoc($rs))
		{
			 $this->updateLeadSeriousnessCount($row["id_c"],$partition);
			 $leadId[] = $row["id_c"];
		}
            }
        }
        if ($leadId) return array_unique($leadId);
        return false;
    }
    function getIgnoreListString($arr = '') {
        $ignoreListComma = '';
        if (is_array($arr)) {
            if (count($arr)) {
                foreach ($arr as $value) {
                    $value = trim($value);
                    if ($value) $ignoreListComma = $value . "','";
                }
                if ($ignoreListComma) {
                    $ignoreListComma = "'" . trim($ignoreListComma, "','") . "'";
                }
            }
        } elseif ($arr) {
            $value = trim($arr);
            if ($value) $ignoreListComma = "'" . $value . "'";
        }
        return $ignoreListComma;
    }
	function updateLeadSeriousnessCount($id,$partition='')
	{
		$updateJprofile=0;
		$this->dbUpdate = connect_db();
		if($id)
		{
			if($partition)
			{
				if($partition=='active')
				{
					$leadsTableName="sugarcrm.leads";
					$cstmTableName="sugarcrm.leads_cstm";
				}
				elseif($partition=='inactive')
				{
					$leadsTableName="sugarcrm_housekeeping.inactive_leads";
					 $cstmTableName="sugarcrm_housekeeping.inactive_leads_cstm";
				}
				elseif($partition=='connected')
				{
					$leadsTableName="sugarcrm_housekeeping.connected_leads";
					 $cstmTableName="sugarcrm_housekeeping.connected_leads_cstm";
				}
				$sql="UPDATE $leadsTableName,$cstmTableName SET seriousness_count_c=seriousness_count_c+1,date_modified=NOW() WHERE id=id_c AND id='$id'";
				mysql_query($sql,$this->dbUpdate);
				$updateJprofile=1;
			}
			if(!$leadsTableName)
			{
				$sql="UPDATE sugarcrm.leads,sugarcrm.leads_cstm SET seriousness_count_c=seriousness_count_c+1,date_modified=NOW() WHERE id='$id' AND id=id_c";
				mysql_query($sql,$this->dbUpdate);
				if(!$this->dbUpdate->getAffectedRowCount())
				{
					foreach ($this->partitionsArray as $partition)
					{
						$leadsTableName="sugarcrm_housekeeping.".$partition."_leads";
						$cstmTableName = "sugarcrm_housekeeping.".$partition."_leads_cstm";
						$sql="UPDATE $leadsTableName,$cstmTableName SET seriousness_count_c=seriousness_count_c+1,date_modified=NOW() WHERE id='$id' AND id=id_c";
						mysql_query($sql,$this->dbUpdate);
						if($this->dbUpdate->getAffectedRowCount())
						{
							$updateJprofile=1;
							break;
						}
					}
				}
				else
					$updateJprofile=1;
			}
			if($updateJprofile)
			{
				$idsArr=array();
				$whereArr=array();
				$leadDetail=$this->getLeadDetailInSugar($id);
				if(is_array($leadDetail) && count($leadDetail))
				{
					if(trim($leadDetail["email"]))
						$emailArr[]=$leadDetail["email"];
					if(trim($leadDetail["enquirer_email_id_c"]))
						$emailArr[]=$leadDetail["enquirer_email_id_c"];
					if(trim($leadDetail["std_enquirer_c"]) && trim($leadDetail["enquirer_landline_c"]))
					{
						$stdCode=ltrim($leadDetail["std_enquirer_c"],0);
						$landlineArr[]=$stdCode.$leadDetail["enquirer_landline_c"];
						$landlineArr[]="0".$stdCode.$leadDetail["enquirer_landline_c"];
					}
					if(trim($leadDetail["enquirer_mobile_no_c"]))
						$mobArr[]=$leadDetail["enquirer_mobile_no_c"];
					if(trim($leadDetail["phone_mobile"]))
						$mobArr[]=$leadDetail["phone_mobile"];
					if(trim($leadDetail["std_c"]) && trim($leadDetail["phone_home"]))
					{
						$stdCode=ltrim($leadDetail["std_c"],0);
                                                $landlineArr[]=$stdCode.$leadDetail["phone_home"];
                                                $landlineArr[]="0".$stdCode.$leadDetail["phone_home"];
					}
					if(is_array($emailArr) && count($emailArr))
						$whereArr[]="EMAIL IN (\"".implode("\",\"",$emailArr)."\")";
					if(is_array($landlineArr) && count($landlineArr))
                                                $whereArr[]="PHONE_WITH_STD IN (\"".implode("\",\"",$landlineArr)."\")";
					if(is_array($mobArr) && count($mobArr))
                                                $whereArr[]="PHONE_MOB IN (\"".implode("\",\"",$mobArr)."\")";
					if($leadDetail["PROFILEID"])
						$idsArr[]=$leadDetail["PROFILEID"];
					if(count($whereArr))
					{
						$whereString=implode(" OR ",$whereArr);
						$sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE $whereString";
						$res=$this->db->query($sql);
						if($this->db->getRowCount($res))
						{
							while($row=$this->db->fetchByAssoc($res))
							if(!in_array($row["PROFILEID"],$idsArr))
								$idsArr[]=$row["PROFILEID"];
						}
					}
					if(count($idsArr))
					{
						$this->updateProfileSeriousnessCount($idsArr);
					}
				}
			}
		}
	}
	function updateProfileSeriousnessCount($profileArr)
	{
		if(is_array($profileArr) && count($profileArr))
		{
//			$now=date('Y-m-d h:i:s');
//			$profileString="\"".implode("\",\"",$profileArr)."\"";
//			$sql="UPDATE newjs.JPROFILE SET SERIOUSNESS_COUNT=SERIOUSNESS_COUNT+1,SORT_DT='$now' WHERE PROFILEID IN ($profileString)";
//			$this->db->query($sql);

            $objUpdate = JProfileUpdateLib::getInstance();
            $result = $objUpdate->updateProfileSeriousnessCount($profileArr);
            if($result === false){
                //handle any update failure
            }
		}
	}
}
?>
